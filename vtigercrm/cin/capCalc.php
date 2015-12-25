<?
/** ▼ キャップ金額計算 ****************************/
function capCalc(&$arrTodayCap = Array()){

  //会社名を割り出し
  $arrClientName = Array();

  //担当者名を割り出し
  $arrRcmName = Array();
  #print $_SERVER['HTTP_REFERER']."<br />";

  //デイリー計算用PHP
  require_once "feeCalc.php";
 
  ob_end_flush();
  ob_start('mb_output_handler');
  $arrMinusPc = Array();
  $arrMinusMobile = Array();
  $arrKeywordMinus = Array();
  $arrEachWords = Array();
  //$startDate = date('Y-m-01');
  $startDate = $_POST[month] . "-01";
  $nowMonth = date('Y-m');

  if($nowMonth == $_POST[month]){
    $endDate = date('Y-m-d');//今日まで
  }else{
    $endDate = date('Y-m-t', strtotime($startDate));//その月の月末
  }//End if

  $arrClientMinusYenPc = Array();
  $arrClientMinusYenMobile = Array();
  print $startDate . "～" . $endDate . "<br><br>";

  $intDays = (strtotime($endDate) - strtotime($startDate)) / (60*60*24);


  /** ▼ DELETE処理 *****************/
  #require('SQLConnect.php');
  //該当日分を削除
  #$sql = "DELETE FROM `rank_cap_dayly` WHERE (`cad_date` Between '" . $startDate . "' and '" . $endDate . "');";
  //print $sql . "<BR><BR>";
  #  $c_hit = mysql_db_query($dbName,$sql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());

  //rank_chargeも削除
  #$sql = "DELETE FROM `rank_charge` WHERE (`rch_proviso` = '上限差引金額' and rch_month = '" . $_POST[month] . "');";
  //print $sql . "<BR><BR>";
  #  $c_hit = mysql_db_query($dbName,$sql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());

  #mysql_close($GLOBALS[dbHandle]);

  #var_dump($_POST[month]);
  #exit;

  //初期化
  #for ($l = 0; $l<= $intDays; $l++){
    //該当の日付を出します。
  #  $tempDate = date('Y-m-d', strtotime("+" . $l. " day " . $startDate)) ;
  #  $arrMinusPc[$tempDate] = 0;
  #  $arrMinusMobile[$tempDate] = 0;
  #}//End for

  #require('SQLClose.php');
  /** ▲ DELETE処理 *****************/

  require('SQLConnect_jas.php');
  $sql  = "SELECT * FROM `rank_cap_mas` ";
  $sql .= " WHERE TRUE  ";
  $sql .= "  AND ((cap_enddate > '{$startDate}') OR (cap_enddate = '0000-00-00')) ";

  if($_POST[month] !== '2014-09') {
    $sql .= "   AND cap_id != 479 ";
  }//End if

  print "Cap : " . $sql . "<br><br>";
  //SQL文を実行する
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);   //レコード数

  for ($i=0; $i<=$rsCount-1; $i++){
    $row = @mysql_fetch_array($rs);
    //print "最高：" . $row[cap_max_yen] . "円<br>";
    $restYen = $row[cap_max_yen];
    $clientId = $row[cap_clientid];


    for ($l = 0; $l<= $intDays; $l++){  #日付ごとに繰り返し
      //該当の日付を出します。
      $tempDate = date('Y-m-d', strtotime("+" . $l. " day " . $startDate)) ;
      //print $tempDate . "<br>";

      $sql2  = " SELECT cac_id, cac_capid, cac_rkwid, rkw_id, rkw_mobile_flag, rkw_account_type, rkw_rclid, rkw_client, rcm_id, rcm_name ";
      $sql2 .= " FROM `rank_cap_child` inner join rank_keyword on rkw_id = cac_rkwid ";
      $sql2 .= " inner join rank_charger_mas on rcm_id = rkw_rcmid ";
      $sql2 .= " Where(cac_capid = " . $row[cap_id] . " ";
      $sql2 .= " and rkw_contract_startdate <= '" . $tempDate . "' ";
      $sql2 .= " and rkw_contract_enddate >= '" . $tempDate . "'";
      $sql2 .= " and cac_status = 0 ";
      $sql2 .= " );";
      #print $sql2 . "<br><br>";
      #exit;
      //SQL文を実行する
      $rs2 = mysql_db_query($dbName,$sql2);
      $rsCount2 = mysql_num_rows($rs2);   //レコード数
      //print $rsCount2 . "<br>";
      $intDaylyPcAll = 0;
      $intDaylyMobileAll = 0;
      
      $intPcCount = 0;
      $intMobileCount = 0;
      

      for ($j=0; $j<=$rsCount2-1; $j++){
        $row2 = @mysql_fetch_array($rs2);
        $key = $row2[rkw_rclid];
        $arrClientName[$key] = $row2[rkw_client];
        $arrRcmId[$key] = $row2[rcm_id];
        $arrRcmName[$key] = $row2[rcm_name];

        if($row2[rkw_account_type] != 1 && $row2[rkw_account_type] != 5 ) die($row2[rkw_id]."処理できない：". $row2[rkw_id] . " : " .  $row2[rkw_account_type]);
        
        if($row2[rkw_mobile_flag] == "0"){
          $intPcCount++;
        }else{
          $intMobileCount++;
        }//End if
        
        $sql3  = "SELECT rcr_id, rcr_yahooranking, rcr_googleranking, rcr_gooranking FROM ";
        $sql3 .= " `rank_crawl` ";
        $sql3 .= " Where TRUE ";
        $sql3 .= " and (rcr_datetime Like '" . $tempDate . "%' ";
        $sql3 .= " and rcr_rkwid = " . $row2[cac_rkwid] . ");";
        
  #      print $sql3 . "<br><br>";
        //SQL文を実行する
        $rs3 = mysql_db_query($dbName,$sql3);
        $rsCount3 = mysql_num_rows($rs3);   //レコード数
        
        if($rsCount3==0)continue;
        
        $rcd_yen_pc = 0;
        $rcd_yen_mobile = 0;
        $row3 = @mysql_fetch_array($rs3);
        //print "id : " . $row2[cac_rkwid] . "<br>";
        //print "Y : " . $row3[rcr_yahooranking] . "<br>";
        //print "G : " . $row3[rcr_googleranking] . "<br>";
        $intFee = feeCalc($row2[cac_rkwid], $row3[rcr_yahooranking], $row3[rcr_googleranking], $row3[rcr_gooranking], $yahooYen, $googleYen, $gooYen);
        if($row2[rkw_account_type]==5){
          //大きいほうをとる
          if($yahooYen > $googleYen){
            $intFee = $yahooYen;
          }else{
            $intFee = $googleYen;
          }//End if
        }//End if
        
        //if($row[cap_id]==4)print $intFee . "<br>";
        //if ($row2[cac_rkwid] ==400||$row2[cac_rkwid] ==401) print $intFee . "<br>";
        if($row2[rkw_mobile_flag]==1){
          //ﾓﾊﾞｲﾙの計算
          $intDaylyMobileAll += $intFee;
          $rcd_yen_mobile= $intFee;

          //マイナスに行ったら全部加える
          if($restYen < 0){
            $arrMinusMobile[$tempDate] += $intFee;
            $arrKeywordMinus[$row2[cac_rkwid]] += $intFee;
            $arrEachWords[$tempDate][$row2[cac_rkwid]] = $intFee;
            $arrClientMinusYenMobile[$clientId] += $intFee;
          }else{
          
            //ちょうどかかるときは差を引く
            if($restYen < $intFee){
              $arrMinusMobile[$tempDate] += $intFee - $restYen;
              $arrKeywordMinus[$row2[cac_rkwid]] += $intFee - $restYen;
              $arrEachWords[$tempDate][$row2[cac_rkwid]] = $intFee - $restYen;
              $arrClientMinusYenMobile[$clientId] += $intFee - $restYen;
            }//End if

          }//End if
          
          $restYen -= $intFee;
          
        }else{

          //PCの計算
          $intDaylyPcAll += $intFee;
          $rcd_yen_pc = $intFee;
          
          //マイナスに行ったら全部加える
          if($restYen < 0){
            $arrMinusPc[$tempDate] += $intFee;
            $arrKeywordMinus[$row2[cac_rkwid]] += $intFee;
            $arrEachWords[$tempDate][$row2[cac_rkwid]] = $intFee;
            $arrClientMinusYenPc[$clientId] += $intFee;
          }else{
            //ちょうどかかるときは差を引く
            if($restYen < $intFee){
              $arrMinusPc[$tempDate] += $intFee - $restYen;
              $arrKeywordMinus[$row2[cac_rkwid]] += $intFee - $restYen;
              $arrEachWords[$tempDate][$row2[cac_rkwid]] = $intFee - $restYen;
              $arrClientMinusYenPc[$clientId] += $intFee - $restYen;
            }//End if
            
          }//End if

          $restYen -= $intFee;
         
        }//End if
        
        //print $intFee.  "円<br>";
        //print "PC : " . $rcd_yen_pc . "<br>";
        //print "Mobile : " . $rcd_yen_mobile . "<br>";
        if(false){
        ?>
        
        <?=$tempDate?><br>
        PC合計:<?=$intDaylyPcAll?><br>
        携帯合計:<?=$intDaylyMobileAll?><br>
        残り：<?=$restYen?><br>
        <br><br>
        <?
        }//End If
        
      }
       
      ob_flush();
      flush();

      //if($l==0)break(1);
    }//End for DayLoop

  }//End for キャップ金額 rank_cap_mas

  require('SQLClose.php');

  return $arrKeywordMinus;

}//End function
/** ▲ キャップ金額計算 ****************************/