<?
//月額固定金額ディリー計算
function monthFeeCalc($date, &$intMonthFeePc, &$intMonthFeeMobile){
?>
月額固定金額
<table border=1 cellpadding=3 cellspacing=1>
<tr>
  <td>キーワードID</td>
  <td>キーワード</td>
  <td>合計</td>
</tr>
<?

  //山村特許事務所さん対応
  $arrYTSkip[] = "2014-09-01";
  $arrYTSkip[] = "2014-12-01";
  $arrYTSkip[] = "2015-03-01";
  $arrYTSkip[] = "2015-06-01";
  $arrYTSkip[] = "2015-09-01";

  require('SQLConnect_jas.php');
//月額固定　前月以降
if(preg_match("|-01$|", $date)){
  $sql  = "SELECT * FROM `rank_keyword` ";
  $sql .= "Where ";
  $sql .= " (rkw_account_type = 4 and rank_keyword.rkw_contract_startdate <= '" . $date . "') ";
  $sql .= " AND (rank_keyword.rkw_contract_enddate >= '" . $date . "') ";
  $sql .= " AND (rank_keyword.rkw_segment IN (1, 2))";
  print "dailyMonth : " . $sql . "<br><br>";
  //SQL文を実行する
  $temp_yen = 0;
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);		//レコード数
  
  for ($i=0; $i<=$rsCount-1; $i++){
    $row = @mysql_fetch_array($rs);
    
    if($row[rkw_mobile_flag] == "0")$intMonthFeePc += $row[rkw_fee_month];
    if($row[rkw_mobile_flag] == "1")$intMonthFeeMobile += $row[rkw_fee_month];
    
    //SEO
    if((int)$row[rkw_segment] === 1) $intSeoFeeMonth += $row[rkw_fee_month];
    //逆SEO
    if((int)$row[rkw_segment] === 2) $intGyakuSeoFeeMonth += $row[rkw_fee_month];
    
    ?>
    <tr>
      <td><?=$row[rkw_id]?></td>
      <td><?=$row[rkw_word]?></td>
      <td><?=$row[rkw_fee_month]?></td>
      <td><?=$row[rkw_segment]?></td>
    </tr>
    
    <?
  }//End for
?>
  <tr>
    <td>SEO</td>
    <td></td>
    <td></td>
    <td><?=$intSeoFeeMonth?></td>
  </tr>
  <tr>
    <td>逆SEO</td>
    <td></td>
    <td></td>
    <td><?=$intGyakuSeoFeeMonth?></td>
  </tr>
  <tr>
    <td>合計</td>
    <td></td>
    <td></td>
    <td><?=$intMonthFeePc?></td>
  </tr>
<?
}else{
//月額固定　前月以降?>
    <tr>
      <td colspan=3>月額固定　前月以降</td>
    </tr>
  <?
  $sql  = "SELECT * FROM `rank_keyword` ";
  $sql .= "Where ";
#  $sql .= " (rkw_account_type = 4 and rank_keyword.rkw_resistdate = '" . $date . "') ";
  $sql .= " (rkw_account_type = 4 and rank_keyword.rkw_contract_startdate = '" . $date . "') ";
  $sql .= " AND (rank_keyword.rkw_segment IN (1, 2))";
  print "dailyMonth : " . $sql . "<br><br>";
  //SQL文を実行する
  $temp_yen = 0;
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);		//レコード数
  
  for ($i=0; $i<=$rsCount-1; $i++){
    $row = @mysql_fetch_array($rs);
    if($row[rkw_mobile_flag] == "0")$intMonthFeePc += $row[rkw_fee_month];
    if($row[rkw_mobile_flag] == "1")$intMonthFeeMobile += $row[rkw_fee_month];
    ?>
    <tr>
      <td><?=$row[rkw_id]?></td>
      <td><?=$row[rkw_word]?></td>
      <td><?=$row[rkw_fee_month]?></td>
    </tr>
    
    <?
  }
}//End if?>
  </table>
  
<?
  require('SQLClose.php');
}//End function

//外部リンク月額固定
function externalMonthFeeCalc($date, &$intMonthFeePc,$measure,$strKwInfo){
?>
月額固定金額
<table border=1 cellpadding=3 cellspacing=1>
<tr>
  <td>キーワードID</td>
  <td>キーワード</td>
  <td>合計</td>
</tr>
<?

if(preg_match("|-01$|", $date)){
  require('SQLConnect_jas.php');
  $sql = "SELECT * FROM `rank_keyword` ";
  if($measure === 'klink' || $measure === 'slink') {
    $sql .= "LEFT JOIN satellite_branch ON rkw_id = sab_rkwid ";
    $sql .= "LEFT JOIN satellite_list ON sab_salid = stl_id ";
  }//End if
  $sql .= "Where(rkw_account_type = 4 ";
  
  switch($measure) {
    case"kotei80":
      $sql .= " AND (rkw_R201311_flag = 1) ";#固定80サイトあり
      $sql .= " AND (rkw_random_flag != 9)  ";#新ランダムなし
      break;
    //ランダムリンク2014年1月作成
    case"random":
      $sql .= "         AND (rank_keyword.rkw_random_flag = 9) ";#ランダムあり
      $sql .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      $sql .= "         AND (rank_keyword.rkw_id NOT IN ({$strKwInfo})) ";#JGサテ(80固定サテぬき)
      break;
    //ランダムリンク2014年2月作成
    case"random2":
      $sql .= "         AND (rank_keyword.rkw_random_flag = 8) ";#ランダムあり
      $sql .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      $sql .= "         AND (rank_keyword.rkw_id NOT IN ({$strKwInfo})) ";#JGサテ(80固定サテぬき)
      break;
    //ランダムリンク2014年3月作成
    case"random3":
      $sql .= "         AND (rank_keyword.rkw_random_flag = 7) ";#ランダムあり
      $sql .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      $sql .= "         AND (rank_keyword.rkw_id NOT IN ({$strKwInfo})) ";#JGサテ(80固定サテぬき)
      break;
    case"naverlink":
      $sql .= "         AND (rank_keyword.rkw_naver_flag = 1) ";#ネイバーリンクあり
      break;
    case"klink":
      $sql .= "         AND (satellite_list.stl_purchase = 'PR4（金井リンク）') ";#PR4あり
      $sql .= "         AND (rank_keyword.rkw_random_flag  != 9) ";#ランダムなし
      $sql .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      $sql .= "         AND (sab_flag = 0) ";#はってあるキーワード対象
      break;
    case"slink":
      $sql .= "         AND (satellite_list.stl_purchase = 'PR3（SEO株式会社）') ";#PR3あり
      $sql .= "         AND (rank_keyword.rkw_random_flag  != 9) ";#ランダムなし
      $sql .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      $sql .= "         AND (sab_flag = 0) ";#はってあるキーワード対象
      break;
  }//End switch

    $sql  .= " AND (rkw_gra_flag != 1) ";#外注ジラフなし
    $sql  .= " AND (rkw_seo_flag != 1) ) ";#外注SEO㈱なし
    $sql .= "AND (rank_keyword.rkw_contract_startdate <= '" . $date . "')  ";
    $sql .= "AND (rank_keyword.rkw_contract_enddate >= '" . $date . "') ";
    $sql  .= " GROUP BY rkw_id";
  print "月額固定 : {$sql}<br>|| {$measure} ||<br>";
  //SQL文を実行する
  $temp_yen = 0;
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);		//レコード数
  
  for ($i=0; $i<=$rsCount-1; $i++){
    $row = @mysql_fetch_array($rs);
    if($row[rkw_mobile_flag] == "0")$intMonthFeePc += $row[rkw_fee_month];
    ?>
    <tr>
      <td><?=$row[rkw_id]?></td>
      <td><?=$row[rkw_word]?></td>
      <td><?=$row[rkw_fee_month]?></td>
    </tr>
    
    <?
  }
  require('SQLClose.php');
  ?>
  </table>
  
  <?
  
  
}else{
  ?>
  </table>
  
  <?
}

}//End function

//10日課金以外のディリー金額集計
function daylyCalc($date, $rkw_id, &$rcd_yen_pc, &$rcd_yen_mobile, $kView, $arrTodayCap){

  //print_r($arrTodayCap);

  $yenAll = 0;
  $rcd_yen_pc = 0;
  $rcd_yen_mobile = 0;
  require('SQLConnect_jas.php');
  
  //紹介案件データの取得/////////////////////////////////////////
  $sqlRin  = "SELECT * FROM rank_keyword ";
  $sqlRin .= "INNER JOIN rank_introduction ON rkw_rclid = rin_referral_rclid ";
  $sqlRin .= " WHERE TRUE ";  
  $sqlRin .= " AND rkw_rclid != 719 "; #グルーヴス様、アデプト様は別で
  
  #print "アデプト以外 : " . $sqlRin . "<br />";
  
  $rsRin = mysql_db_query($dbName, $sqlRin);
  $rsRinCount = mysql_num_rows($rsRin);
  for($intRin=0;$intRin<$rsRinCount;$intRin++) {
    $rowRin = mysql_fetch_array($rsRin);
    $arrRin[$rowRin[rkw_id]] = $rowRin;
    $arrRinKw[$rowRin[rkw_id]] = $rowRin[rkw_word];
  }//End for
  
  #グルーヴス様、アデプト様のみ
  $sqlRin  = "SELECT * FROM rank_keyword ";
  $sqlRin .= "INNER JOIN rank_introduction ON rkw_rclid = rin_referral_rclid ";
  $sqlRin .= " WHERE TRUE ";  
  $sqlRin .= " AND rkw_rclid = 719 "; #グルーヴス様、アデプト様指定
  $sqlRin .= " AND ";
  $sqlRin .= " (rkw_url LIKE '%abc-store-japan.com%' OR rkw_url LIKE '%nax.mobi%' )";
  
  #print "アデプト : " . $sqlRin . "<br />";
  
  $rsRin = mysql_db_query($dbName, $sqlRin);
  $rsRinCount = mysql_num_rows($rsRin);
  for($intRin=0;$intRin<$rsRinCount;$intRin++) {
    $rowRin = mysql_fetch_array($rsRin);
    $arrRin[$rowRin[rkw_id]] = $rowRin;
    $arrRinKw[$rowRin[rkw_id]] = $rowRin[rkw_word];
  }//End for

  //紹介手数料-マイナス金額
  $sqlRin  = "SELECT * FROM rank_keyword ";
  $sqlRin .= "INNER JOIN rank_introduction_minus ON rkw_rclid = rim_referral_rclid ";
  $sqlRin .= " WHERE TRUE ";
  

  $rsRin = mysql_db_query($dbName, $sqlRin);
  $rsRinCount = mysql_num_rows($rsRin);
  for($intRin=0;$intRin<$rsRinCount;$intRin++) {
    $rowRin = mysql_fetch_array($rsRin);
    $arrRin[$rowRin[rkw_id]] = $rowRin;
    $arrRinKw[$rowRin[rkw_id]] = $rowRin[rkw_word];
  }//End for
  //////////////////////////////////////////////////////////////
  
  if ($rkw_id){
    $sql = "SELECT rank_crawl.rcr_id, rank_crawl.rcr_rkwid, rank_crawl.rcr_yahooranking, rank_crawl.rcr_googleranking, rank_crawl.rcr_gooranking, rank_keyword.rkw_account_type, rank_keyword.rkw_is_fee, rank_keyword.rkw_word, rank_client.rcl_status, rank_crawl.rcr_datetime FROM (rank_crawl LEFT JOIN rank_keyword ON rank_crawl.rcr_rkwid = rank_keyword.rkw_id) LEFT JOIN rank_client ON rank_crawl.rcr_rclid = rank_client.rcl_id WHERE ( (rank_keyword.rkw_id = " . $rkw_id . ") AND (rank_crawl.rcr_datetime Like '" . $date . "%') AND (rank_keyword.rkw_account_type in (1,5)) AND (rank_client.rcl_status=1) AND (rank_keyword.rkw_contract_startdate <= '" . $date . "') AND (rank_keyword.rkw_contract_enddate >= '" . $date . "')  ) Order By rcr_rkwid;";

  }else{
    $sql = "SELECT rank_crawl.rcr_id, rank_crawl.rcr_rkwid, rank_crawl.rcr_yahooranking, rank_crawl.rcr_googleranking, rank_crawl.rcr_gooranking, rank_keyword.rkw_mobile_flag, rank_keyword.rkw_account_type, rank_keyword.rkw_is_fee, rank_keyword.rkw_word, rank_client.rcl_status, rank_crawl.rcr_datetime FROM (rank_crawl LEFT JOIN rank_keyword ON rank_crawl.rcr_rkwid = rank_keyword.rkw_id) LEFT JOIN rank_client ON rank_crawl.rcr_rclid = rank_client.rcl_id WHERE (rkw_segment IN (1, 2)) AND ((rank_crawl.rcr_datetime Like '" . $date . "%') AND (rank_keyword.rkw_account_type in (1,2,3,5)) AND (rank_client.rcl_status=1)AND (rank_keyword.rkw_contract_startdate <= '" . $date . "') AND (rank_keyword.rkw_contract_enddate >= '" . $date . "') ) Order By rkw_mobile_flag ASC, rcr_rkwid;";

  //  $sql = "SELECT rank_crawl.rcr_id, rank_crawl.rcr_rkwid, rank_crawl.rcr_yahooranking, rank_crawl.rcr_googleranking, rank_crawl.rcr_gooranking, rank_keyword.rkw_account_type, rank_keyword.rkw_is_fee, rank_keyword.rkw_word, rank_client.rcl_status, rank_crawl.rcr_datetime FROM (rank_crawl LEFT JOIN rank_keyword ON rank_crawl.rcr_rkwid = rank_keyword.rkw_id) LEFT JOIN rank_client ON rank_crawl.rcr_rclid = rank_client.rcl_id WHERE ((rank_crawl.rcr_datetime Like '" . $date . "%') AND (rank_keyword.rkw_account_type=1) AND (rank_client.rcl_status=1)AND (rank_keyword.rkw_contract_startdate <= '" . $date . "') AND ((rank_keyword.rkw_contract_enddate >= '" . $date . "') OR (rank_keyword.rkw_contract_enddate = '2009-01-31')) ) Order By rcr_rkwid;";
  }


  //if(!$kView) print $sql . "<br>";

  print "daylyCalc : ".$sql."<br /><br />";

  $arrTendays = array();

  //10日課金の計算
  $sql2 = makeSqlTendays($date);
  //print "10日: <br>" . $sql2 . "<br><br>";
  $rs2  = mysql_db_query($dbName,$sql2);
  $rsCount2 = mysql_num_rows($rs2);		  //レコード数

  for ($i=0; $i<=$rsCount2-1; $i++){
    $tempRow = @mysql_fetch_array($rs2);
    
    if($tempRow[rfe_provider_kind]==1) $chgFieldName = "rcr_yahoo_yen";
    if($tempRow[rfe_provider_kind]==2) $chgFieldName = "rcr_google_yen";
    if($tempRow[rfe_provider_kind]==3) $chgFieldName = "rcr_goo_yen";
    $tempSql = "UPDATE `rank_crawl` SET `" . $chgFieldName . "` = '" . $tempRow[rfe_yen] . "' WHERE (`rcr_id` = " . $tempRow[rcr_id] . ");";
  //  print $tempSql . "<BR><BR>";
    $c_hit = mysql_db_query($dbName,$tempSql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());

    if(array_key_exists($tempRow["rcr_rkwid"], $arrTendays)){
      $arrTendays[$tempRow["rcr_rkwid"]]["rfe_yen"] += $tempRow["rfe_yen"];
    } else {
      $arrTendays[$tempRow["rcr_rkwid"]] = $tempRow;
    }
  }//End for

  //10日どちららか課金の計算
  $sql2 = makeSqlTendaysWhich($date);
  //print "10日: <br>" . $sql2 . "<br><br>";
  $rs2  = mysql_db_query($dbName,$sql2);
  $rsCount2 = mysql_num_rows($rs2);		  //レコード数

  for ($i=0; $i<=$rsCount2-1; $i++){
    $tempRow = @mysql_fetch_array($rs2);
    
    if($tempRow[rfe_provider_kind]==1) $chgFieldName = "rcr_yahoo_yen";
    if($tempRow[rfe_provider_kind]==2) $chgFieldName = "rcr_google_yen";
    if($tempRow[rfe_provider_kind]==3) $chgFieldName = "rcr_goo_yen";
    $tempSql = "UPDATE `rank_crawl` SET `" . $chgFieldName . "` = '" . $tempRow[rfe_yen] . "' WHERE (`rcr_id` = " . $tempRow[rcr_id] . ");";
  //  print $tempSql . "<BR><BR>";
    $c_hit = mysql_db_query($dbName,$tempSql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());

    if(array_key_exists($tempRow["rcr_rkwid"], $arrTendays)){
      $arrTendays[$tempRow["rcr_rkwid"]]["rfe_yen"] += $tempRow["rfe_yen"];
    } else {
      $arrTendays[$tempRow["rcr_rkwid"]] = $tempRow;
    }
  }//End for


  if(!$kView){
  ?>
  PC用画面
  <table border=1 cellpadding=3 cellspacing=1>
  <tr>
    <td>ID</td>
    <td>キーワードID</td>
    <td>キーワード</td>
    <td>Y順位</td>
    <td>G順位</td>
    <td>Goo順位</td>
    <td>Y金額</td>
    <td>G金額</td>
    <td>Goo金額</td>
    <td>10日課金</td>
    <td><font color=red>キャップ</font></td>
    <td>合計</td>
  </tr>
  <?
  }
  //SQL文を実行する
  $isMobile = false;
  $rs  = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);		//レコード数
  for ($i=0; $i<=$rsCount-1; $i++){
      $row  = @mysql_fetch_array($rs);
      $yahooYen = 0;
      $googleYen = 0;
      $gooYen = 0;
      $tendaysYen = 0;
      $tempYen = 0;
      
      //通常課金

        if ((!$isMobile) && ($row[rkw_mobile_flag])){
        $isMobile = true;
        if(!$kView){
  ?>
        </table>
        <br><br>
        携帯用画面
        <table border=1 cellpadding=3 cellspacing=1>
        <tr>
          <td>ID</td>
          <td>キーワードID</td>
          <td>キーワード</td>
          <td>Y順位</td>
          <td>G順位</td>
          <td>Goo順位</td>
          <td>Y金額</td>
          <td>G金額</td>
          <td>Goo金額</td>
          <td>10日課金</td>
          <td><font color=red>キャップ</font></td>
          <td>合計</td>
        </tr>
  <?
        }
        }
        $tendaysYen = 0;
        if ($row["rkw_account_type"] ==1){
          //print "---" . $row[rcr_gooranking] . "---<br>";
          $tempYen = feeCalc($row[rcr_rkwid], $row[rcr_yahooranking], $row[rcr_googleranking], $row[rcr_gooranking], $yahooYen, $googleYen, $gooYen);
          //金額をアップデート
          require('SQLConnect_jas.php');
          $tempSql = "UPDATE `rank_crawl` SET `rcr_yahoo_yen` = '" . $yahooYen . "', `rcr_google_yen` = '" . $googleYen . "', `rcr_goo_yen` = '" . $gooYen . "' WHERE (`rcr_id` = " .  $row[rcr_id]. ");";
          //print $tempSql . "<BR><BR>";
          $c_hit = mysql_db_query($dbName,$tempSql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());
          
          
        }

        
        //日毎どちらか課金の時は、金額の大きいほうを足し合わせる
        if ($row["rkw_account_type"] ==5){
          feeCalc($row[rcr_rkwid], $row[rcr_yahooranking], $row[rcr_googleranking], $row[rcr_gooranking], $yahooYen, $googleYen, $gooYen);
          
          if($yahooYen >= $googleYen) {
            $tempYen = $yahooYen;

          //混乱を防ぐため、金額の良いほうに記録
            $tempSql = "UPDATE `rank_crawl` SET `rcr_yahoo_yen` = '" . $yahooYen . "', `rcr_google_yen` = '0' WHERE (`rcr_id` = " .  $row[rcr_id]. ");";
          }else{
            $tempYen = $googleYen;

          //混乱を防ぐため、金額の良いほうに記録
            $tempSql = "UPDATE `rank_crawl` SET `rcr_yahoo_yen` = '0', `rcr_google_yen` = '" . $googleYen . "' WHERE (`rcr_id` = " .  $row[rcr_id]. ");";
          }
          
          //金額をアップデート
          require('SQLConnect_jas.php');
          //print $tempSql . "<BR><BR>";
          $c_hit = mysql_db_query($dbName,$tempSql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());
          
          
        }
        
        //課金体系がn日課金・n日どちらか課金の場合
        if ($row["rkw_account_type"] ==2 || $row["rkw_account_type"] ==3){
          if(array_key_exists($row["rcr_rkwid"], $arrTendays)){
            $tendaysYen = (int)$arrTendays[$row["rcr_rkwid"]]["rfe_yen"];
            $tempYen = (int)$arrTendays[$row["rcr_rkwid"]]["rfe_yen"];
          }else{
            $tempYen = 0;
          }
        }
        
        $key = $row[rcr_rkwid];
        
        if(!$kView){
        ?>

            <tr>
              <td><?=$row[rcr_id]?></td>
              <td><?=$row[rcr_rkwid]?></td>
              <td><?=$row[rkw_word]?></td>
              <td><?=$row[rcr_yahooranking]?></td>
              <td><?=$row[rcr_googleranking]?></td>
              <td><?=$row[rcr_gooranking]?></td>
              <td><?=$yahooYen?></td>
              <td><?=$googleYen?></td>
              <td><?=$gooYen?></td>
              <td><?=$tendaysYen?></td>
              <td>
              <?
              $key = $row[rcr_rkwid];
              if($arrTodayCap[$key]!="") print "<font color=red>" . $arrTodayCap[$key] . "</font>";
              $arrTodayCap[$key] = "OK";
              ?>
              </td>
              <td><?=$tempYen?></td>
            </tr>
              
        <?
        }//End if


        $yenAll += $tempYen;
        if ($row["rkw_account_type"] ==1 || $row["rkw_account_type"] ==5){
          if(!$isMobile) $rcd_yen_pc += $tempYen;
          if($isMobile) $rcd_yen_mobile += $tempYen;
        }//End if
        
        //もしも課金になったら、フラグを付ける
        if($row[rcr_rkwid] && $row[rkw_is_fee]==0 && $tempYen != 0){
          $sql = "UPDATE `rank_keyword` SET `rkw_is_fee` = '1' WHERE (`rkw_id` ="  . $row[rcr_rkwid] . ");";
          print $sql . "<BR><BR>";
          $c_hit = mysql_db_query($dbName,$sql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());
        }//End if        
        
    }//for KWごと課金額を追加

  require('SQLClose.php');

  if(!$kView){
  ?>
  </table>

  <?
  }
  //print "トータルは" . $yenAll . "円です<br>";
  #print_r($arrTodayCap);

  return $yenAll;

}//End function


## 10日課金計算メソッド
## 引数   YYYY-MM-DD  指定された日
## 返値   int         指定された日付に10日目を迎えた企業全ての課金合計金額
function tendaysCalc($date, $kView, &$pcYen, &$mobileYen){

require('SQLConnect.php');

$pcYen = 0;
$mobileYen = 0;
$sql2 = makeSqlTendays($date);
$rs2 = mysql_db_query($dbName,$sql2);
$rsCount2 = mysql_num_rows($rs2);		//レコード数

if(!$kView){
?>
n日課金データ
<table border=1 cellpadding=3 cellspacing=1>
<tr>
  <td>キーワードID</td>
  <td>キーワード</td>
  <td>Yahoo/Google</td>
  <td>金額</td>
</tr>

<?
}
for ($i=0; $i<=$rsCount2-1; $i++){
  $row2 = @mysql_fetch_array($rs2);
  
  if(!$kView){
  ?>
  <tr>
    <td><?=$row2[rcr_rkwid]?></td>
    <td><?=$row2[rkw_word]?></td>
    <td>
        <?
        if ($row2[rfe_provider_kind] == "1") print "Yahoo";
        if ($row2[rfe_provider_kind] == "2") print "Google";
                ?></td>
    <td><?=$row2[rfe_yen]?></td>
  </tr>
  
  <?
  }
  $key = $row2[rkw_word];
  if($row2[rkw_mobile_flag] == "0") $pcYen+= $row2["rfe_yen"];
  if($row2[rkw_mobile_flag] == "1") $mobileYen+= $row2["rfe_yen"];
}

if(!$kView){
?>
</table>
PC合計：<?=$pcYen?><br>
携帯合計：<?=$mobileYen?><br>

<?
}
require('SQLClose.php');

//return (int)$yenTendays;

}//End function

## n日課金計算用SQL
## 引数   YYYY-MM-DD  指定された日
## 返値   string      10日課金計算用SQL
function makeSqlExternalTendays($date, $measure,$strKwInfo){

  $sql2  = "";
  $sql2 .= " SELECT ";
  $sql2 .= "   Y.* ";
  $sql2 .= " FROM ( ";
  $sql2 .= "   SELECT ";
  $sql2 .= "     X.rcr_id , ";
  $sql2 .= "     X.rcr_rkwid , ";
  $sql2 .= "     X.rkw_word , ";
  $sql2 .= "     X.rkw_mobile_flag , ";
  $sql2 .= "     X.rkw_account_date , ";
  $sql2 .= "     X.rfe_provider_kind , ";
  $sql2 .= "     X.rfe_yen , ";
  $sql2 .= "     COUNT(X.rcr_rkwid) AS countTendays , ";
  $sql2 .= "     MAX(X.rcr_datetime) AS rcr_datetime";
  $sql2 .= "   FROM (";
  $sql2 .= "     SELECT ";
  $sql2 .= "       rank_crawl   .rcr_id,";
  $sql2 .= "       rank_crawl   .rcr_rkwid,";
  $sql2 .= "       rank_crawl   .rcr_yahooranking,";
  $sql2 .= "       rank_crawl   .rcr_googleranking,";
  $sql2 .= "       rank_keyword .rkw_mobile_flag,";
  $sql2 .= "       rank_keyword .rkw_account_type,";
  $sql2 .= "       rank_keyword .rkw_account_date,";
  $sql2 .= "       rank_keyword .rkw_word,";
  $sql2 .= "       rank_client  .rcl_status,";
  $sql2 .= "       rank_crawl   .rcr_datetime ,";
  $sql2 .= "       rank_fee     .rfe_id , ";
  $sql2 .= "       rank_fee     .rfe_provider_kind , ";
  $sql2 .= "       rank_fee     .rfe_yen ";
  $sql2 .= "     FROM ";
  $sql2 .= "       (";
  $sql2 .= "         rank_crawl ";
  $sql2 .= "         LEFT JOIN ";
  $sql2 .= "           rank_keyword ";
  $sql2 .= "         ON ";
  $sql2 .= "           rank_crawl.rcr_rkwid = rank_keyword.rkw_id";
  $sql2 .= "       ) ";
  $sql2 .= "     LEFT JOIN ";
  $sql2 .= "       rank_client ";
  $sql2 .= "     ON ";
  $sql2 .= "       rank_crawl.rcr_rclid = rank_client.rcl_id ";
  $sql2 .= "     LEFT JOIN ";
  $sql2 .= "       rank_fee ";
  $sql2 .= "     ON ";
  $sql2 .= "       rcr_rkwid = rfe_rkwid AND ";
  $sql2 .= "       (";
  $sql2 .= "         (";
  $sql2 .= "           rcr_yahooranking  >= rfe_rank_start AND ";
  $sql2 .= "           rcr_yahooranking  <= rfe_rank_end   AND ";
  $sql2 .= "           rfe_provider_kind =  1 ";
  $sql2 .= "         ) OR ";
  $sql2 .= "         (";
  $sql2 .= "           rcr_googleranking >= rfe_rank_start AND ";
  $sql2 .= "           rcr_googleranking <= rfe_rank_end   AND ";
  $sql2 .= "           rfe_provider_kind =  2 ";
  $sql2 .= "         )";
  $sql2 .= "       )  ";
  
  if($measure === 'klink' || $measure === 'slink') {
    $sql2 .= "     LEFT JOIN ";
    $sql2 .= "       satellite_branch ";
    $sql2 .= "     ON ";
    $sql2 .= "       rank_keyword.rkw_id =  satellite_branch.sab_rkwid  ";
    $sql2 .= "     LEFT JOIN ";
    $sql2 .= "       satellite_list ";
    $sql2 .= "     ON ";
    $sql2 .= "       satellite_list.stl_id =   satellite_branch.sab_salid  ";
  }//End if
  
  $sql2 .= "     WHERE ";
  $sql2 .= "       (";
  $sql2 .= "         (rank_fee    .rfe_status             =   1) AND ";
  $sql2 .= "         (rank_crawl  .rcr_datetime           >= '".substr($date, 0, 8)."01 00:00:00') AND ";
  $sql2 .= "         (rank_crawl  .rcr_datetime           <= '".$date." 23:59:59') AND ";
  $sql2 .= "         (rank_client .rcl_status             =   1) AND ";
  $sql2 .= "         (rank_keyword.rkw_account_type       =   2) AND ";
  $sql2 .= "         (rank_keyword.rkw_contract_startdate <= '".substr($date, 0, 8)."01 00:00:00') AND ";
  $sql2 .= "         (rank_keyword.rkw_contract_enddate   >= '".$date." 23:59:59') ";
  
  switch($measure) {
    case"kotei80":
      $sql2 .= " AND (rank_keyword.rkw_R201311_flag = 1) ";#固定80サイトあり
      $sql2 .= " AND (rank_keyword.rkw_random_flag != 9)  ";#新ランダムなし
      break;
    case"random":
      $sql2 .= "         AND (rank_keyword.rkw_random_flag = 9) ";#ランダムあり
      $sql2 .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      $sql2 .= "         AND (rank_keyword.rkw_id NOT IN ({$strKwInfo})) ";#JGサテ(80固定サテぬき)なし
      break;
    case"naverlink":
      $sql2 .= "         AND (rank_keyword.rkw_naver_flag = 1) ";#ネイバーリンクあり
      break;
    case"klink":
      $sql2 .= "         AND (satellite_list.stl_purchase = 'PR4（金井リンク）') ";#PR4あり
      $sql2 .= "         AND (rank_keyword.rkw_random_flag  != 9) ";#ランダムなし
      $sql2 .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      break;
    case"slink":
      $sql2 .= "         AND (satellite_list.stl_purchase = 'PR3（SEO株式会社）') ";#PR4あり
      $sql2 .= "         AND (rank_keyword.rkw_random_flag  != 9) ";#ランダムなし
      $sql2 .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      break;
  }//End switch

  
  $sql2 .= " AND (rank_keyword.rkw_gra_flag != 1) ";#ジラフなし
  $sql2 .= " AND (rank_keyword.rkw_seo_flag != 1) ";#SEOなし
  
  $sql2 .= "       ) ";
  $sql2 .= "     ORDER BY ";
  $sql2 .= "       rcr_rkwid, ";
  $sql2 .= "       rfe_provider_kind, ";
  $sql2 .= "       rcr_datetime";
  $sql2 .= "   ) X ";
  $sql2 .= "   GROUP BY ";
  $sql2 .= "     X.rcr_rkwid , ";
  $sql2 .= "     X.rfe_provider_kind";
  $sql2 .= " ) Y ";
  $sql2 .= " WHERE ";
  $sql2 .= "   Y.countTendays = Y.rkw_account_date AND ";
  $sql2 .= "   Y.rcr_datetime >= '".$date." 00:00:00' AND ";
  $sql2 .= "   Y.rcr_datetime <= '".$date." 23:59:59'";

  return $sql2;
}//End function External

## 10日課金計算メソッド
## 引数   YYYY-MM-DD  指定された日
## 返値   int         指定された日付に10日目を迎えた企業全ての課金合計金額
function tendaysWhichCalc($date, $kView, &$pcYen, &$mobileYen){

require('SQLConnect.php');

$pcYen = 0;
$mobileYen = 0;
$sql2 = makeSqlTendaysWhich($date);

$rs2 = mysql_db_query($dbName,$sql2);
$rsCount2 = mysql_num_rows($rs2);		//レコード数

if(!$kView){
?>
n日どちらか課金データ
<table border=1 cellpadding=3 cellspacing=1>
<tr>
  <td>キーワードID</td>
  <td>キーワード</td>
  <td>Yahoo/Google</td>
  <td>金額</td>
</tr>

<?
}

$rcr_rkwid_moto = "";
for ($i=0; $i<=$rsCount2-1; $i++){
  $row2 = @mysql_fetch_array($rs2);
  if($row2[rcr_rkwid] == $rcr_rkwid_moto) continue(1);  //1回課金された時は飛ばす
  if(!$kView){
  ?>
  <tr>
    <td><?=$row2[rcr_rkwid]?></td>
    <td><?=$row2[rkw_word]?></td>
    <td>
        <?
        if ($row2[rfe_provider_kind] == "1") print "Yahoo";
        if ($row2[rfe_provider_kind] == "2") print "Google";
                ?></td>
    <td><?=$row2[rfe_yen]?></td>
  </tr>
  
  <?
  }
  $key = $row2[rkw_word];
  if($row2[rkw_mobile_flag] == "0") $pcYen+= $row2["rfe_yen"];
  if($row2[rkw_mobile_flag] == "1") $mobileYen+= $row2["rfe_yen"];
  //print "<br>課金: " . $row2["rfe_yen"] . "<br>";
  $rcr_rkwid_moto = $row2[rcr_rkwid];
}

if(!$kView){
?>
</table>
PC合計：<?=$pcYen?><br>
携帯合計：<?=$mobileYen?><br>

<?
}
require('SQLClose.php');

//return (int)$yenTendays;

}//End function


## 外部リンク
## 10日課金計算メソッド
## 引数   YYYY-MM-DD  指定された日
## 返値   int         指定された日付に10日目を迎えた企業全ての課金合計金額
function externalTendaysCalc($date, $kView, $measure,$strKwInfo){

require('SQLConnect.php');

$pcExternalYen = 0;

//外部リンク10日課金SQL
$sql2  = makeSqlExternalTendays($date, $measure,$strKwInfo);
print "$sql2<br />";

$rs2 = mysql_db_query($dbName,$sql2);
$rsCount2 = mysql_num_rows($rs2);		//レコード数

if(!$kView){
?>
10日課金データ
<table border=1 cellpadding=3 cellspacing=1>
<tr>
  <td>キーワードID</td>
  <td>キーワード</td>
  <td>Yahoo/Google</td>
  <td>金額</td>
</tr>

<?
}
for ($i=0; $i<=$rsCount2-1; $i++){
  $row2 = @mysql_fetch_array($rs2);
  
  if(!$kView){
  ?>
  <tr>
    <td><?=$row2[rcr_rkwid]?></td>
    <td><?=$row2[rkw_word]?></td>
    <td>
        <?
        if ($row2[rfe_provider_kind] == "1") print "Yahoo";
        if ($row2[rfe_provider_kind] == "2") print "Google";
                ?></td>
    <td><?=$row2[rfe_yen]?></td>
  </tr>
  
  <?
  }
  $key = $row2[rkw_word];
  if($row2[rkw_mobile_flag] == "0") $pcExternalYen+= $row2["rfe_yen"];
}

if(!$kView){
?>
</table>
PC合計：<?=$pcExternalYen?><br>

<?
}
require('SQLClose.php');

return (int)$pcExternalYen;
//return (int)$yenTendays;

}//End function random



## 10日課金計算メソッド
## 引数   YYYY-MM-DD  指定された日
## 返値   int         指定された日付に10日目を迎えた企業全ての課金合計金額
function externalTendaysWhichCalc($date, $kView, $measure,$strKwInfo){

require('SQLConnect.php');

$pcRandomYen = 0;
//外部施策10日課金SQL出力
$sql2 = makeSqlExternalTendaysWhich($date,$measure,$strKwInfo);
print "$sql2<br />";
$rs2 = mysql_db_query($dbName,$sql2);
$rsCount2 = mysql_num_rows($rs2);		//レコード数

if(!$kView){
?>
ランダムn日課金データ
<table border=1 cellpadding=3 cellspacing=1>
<tr>
  <td>キーワードID</td>
  <td>キーワード</td>
  <td>Yahoo/Google</td>
  <td>金額</td>
</tr>

<?
}

$rcr_rkwid_moto = "";
for ($i=0; $i<=$rsCount2-1; $i++){
  $row2 = @mysql_fetch_array($rs2);
  if($row2[rcr_rkwid] == $rcr_rkwid_moto) continue(1);  //1回課金された時は飛ばす
  if(!$kView){
  ?>
  <tr>
    <td><?=$row2[rcr_rkwid]?></td>
    <td><?=$row2[rkw_word]?></td>
    <td>
        <?
        if ($row2[rfe_provider_kind] == "1") print "Yahoo";
        if ($row2[rfe_provider_kind] == "2") print "Google";
                ?></td>
    <td><?=$row2[rfe_yen]?></td>
  </tr>
  
  <?
  }
  $key = $row2[rkw_word];
  if($row2[rkw_mobile_flag] == "0") $pcRandomYen+= $row2["rfe_yen"];
  //print "<br>課金: " . $row2["rfe_yen"] . "<br>";
  $rcr_rkwid_moto = $row2[rcr_rkwid];
}

if(!$kView){
?>
</table>
PC合計：<?=$pcRandomYen?><br>

<?
}
require('SQLClose.php');

return (int)$pcRandomYen;
//return (int)$yenTendays;

}//End function random


## n日課金計算用SQL
## 引数   YYYY-MM-DD  指定された日
## 返値   string      10日課金計算用SQL
function makeSqlTendays($date){

  $sql2  = "";
  $sql2 .= " SELECT ";
  $sql2 .= "   Y.* ";
  $sql2 .= " FROM ( ";
  $sql2 .= "   SELECT ";
  $sql2 .= "     X.rcr_id , ";
  $sql2 .= "     X.rcr_rkwid , ";
  $sql2 .= "     X.rkw_word , ";
  $sql2 .= "     X.rkw_mobile_flag , ";
  $sql2 .= "     X.rkw_account_date , ";
  $sql2 .= "     X.rfe_provider_kind , ";
  $sql2 .= "     X.rfe_yen , ";
  $sql2 .= "     COUNT(X.rcr_rkwid) AS countTendays , ";
  $sql2 .= "     MAX(X.rcr_datetime) AS rcr_datetime";
  $sql2 .= "   FROM (";
  $sql2 .= "     SELECT ";
  $sql2 .= "       rank_crawl   .rcr_id,";
  $sql2 .= "       rank_crawl   .rcr_rkwid,";
  $sql2 .= "       rank_crawl   .rcr_yahooranking,";
  $sql2 .= "       rank_crawl   .rcr_googleranking,";
  $sql2 .= "       rank_keyword .rkw_mobile_flag,";
  $sql2 .= "       rank_keyword .rkw_account_type,";
  $sql2 .= "       rank_keyword .rkw_account_date,";
  $sql2 .= "       rank_keyword .rkw_word,";
  $sql2 .= "       rank_client  .rcl_status,";
  $sql2 .= "       rank_crawl   .rcr_datetime ,";
  $sql2 .= "       rank_fee     .rfe_id , ";
  $sql2 .= "       rank_fee     .rfe_provider_kind , ";
  $sql2 .= "       rank_fee     .rfe_yen ";
  $sql2 .= "     FROM ";
  $sql2 .= "       (";
  $sql2 .= "         rank_crawl ";
  $sql2 .= "         LEFT JOIN ";
  $sql2 .= "           rank_keyword ";
  $sql2 .= "         ON ";
  $sql2 .= "           rank_crawl.rcr_rkwid = rank_keyword.rkw_id";
  $sql2 .= "       ) ";
  $sql2 .= "     LEFT JOIN ";
  $sql2 .= "       rank_client ";
  $sql2 .= "     ON ";
  $sql2 .= "       rank_crawl.rcr_rclid = rank_client.rcl_id ";
  $sql2 .= "     LEFT JOIN ";
  $sql2 .= "       rank_fee ";
  $sql2 .= "     ON ";
  $sql2 .= "       rcr_rkwid = rfe_rkwid AND ";
  $sql2 .= "       (";
  $sql2 .= "         (";
  $sql2 .= "           rcr_yahooranking  >= rfe_rank_start AND ";
  $sql2 .= "           rcr_yahooranking  <= rfe_rank_end   AND ";
  $sql2 .= "           rfe_provider_kind =  1 ";
  $sql2 .= "         ) OR ";
  $sql2 .= "         (";
  $sql2 .= "           rcr_googleranking >= rfe_rank_start AND ";
  $sql2 .= "           rcr_googleranking <= rfe_rank_end   AND ";
  $sql2 .= "           rfe_provider_kind =  2 ";
  $sql2 .= "         )";
  $sql2 .= "       )  ";
  $sql2 .= "     WHERE ";
  $sql2 .= "       (";
  $sql2 .= "         (rank_fee    .rfe_status             =   1) AND ";
  $sql2 .= "         (rank_crawl  .rcr_datetime           >= '".substr($date, 0, 8)."01 00:00:00') AND ";
  $sql2 .= "         (rank_crawl  .rcr_datetime           <= '".$date." 23:59:59') AND ";
  $sql2 .= "         (rank_client .rcl_status             =   1) AND ";
  $sql2 .= "         (rank_keyword.rkw_account_type       =   2) AND ";
  $sql2 .= "         (rank_keyword.rkw_contract_startdate <= '".substr($date, 0, 8)."01 00:00:00') AND ";
  $sql2 .= "         (rank_keyword.rkw_contract_enddate   >= '".$date." 23:59:59')";
  $sql2 .= "       ) ";
  $sql2 .= "     ORDER BY ";
  $sql2 .= "       rcr_rkwid, ";
  $sql2 .= "       rfe_provider_kind, ";
  $sql2 .= "       rcr_datetime";
  $sql2 .= "   ) X ";
  $sql2 .= "   GROUP BY ";
  $sql2 .= "     X.rcr_rkwid , ";
  $sql2 .= "     X.rfe_provider_kind";
  $sql2 .= " ) Y ";
  $sql2 .= " WHERE ";
  $sql2 .= "   Y.countTendays = Y.rkw_account_date AND ";
  $sql2 .= "   Y.rcr_datetime >= '".$date." 00:00:00' AND ";
  $sql2 .= "   Y.rcr_datetime <= '".$date." 23:59:59'";

  return $sql2;
}//End function

## 10日課金計算用SQL
## 引数   YYYY-MM-DD  指定された日
## 返値   string      10日課金計算用SQL
function makeSqlTendaysWhich($date){

  $sql2  = "";
  $sql2 .= " SELECT ";
  $sql2 .= "   Y.* ";
  $sql2 .= " FROM ( ";
  $sql2 .= "   SELECT ";
  $sql2 .= "     X.rcr_id , ";
  $sql2 .= "     X.rcr_rkwid , ";
  $sql2 .= "     X.rkw_word , ";
  $sql2 .= "     X.rkw_mobile_flag , ";
  $sql2 .= "     X.rkw_account_date , ";
  $sql2 .= "     X.rfe_provider_kind , ";
  $sql2 .= "     X.rfe_yen , ";
  $sql2 .= "     COUNT(X.rcr_rkwid) AS countTendays , ";
  $sql2 .= "     MAX(X.rcr_datetime) AS rcr_datetime";
  $sql2 .= "   FROM (";
  $sql2 .= "     SELECT ";
  $sql2 .= "       rank_crawl   .rcr_id,";
  $sql2 .= "       rank_crawl   .rcr_rkwid,";
  $sql2 .= "       rank_crawl   .rcr_yahooranking,";
  $sql2 .= "       rank_crawl   .rcr_googleranking,";
  $sql2 .= "       rank_keyword .rkw_mobile_flag,";
  $sql2 .= "       rank_keyword .rkw_account_type,";
  $sql2 .= "       rank_keyword .rkw_account_date,";
  $sql2 .= "       rank_keyword .rkw_word,";
  $sql2 .= "       rank_client  .rcl_status,";
  $sql2 .= "       rank_crawl   .rcr_datetime ,";
  $sql2 .= "       rank_fee     .rfe_id , ";
  $sql2 .= "       rank_fee     .rfe_provider_kind , ";
  $sql2 .= "       rank_fee     .rfe_yen ";
  $sql2 .= "     FROM ";
  $sql2 .= "       (";
  $sql2 .= "         rank_crawl ";
  $sql2 .= "         LEFT JOIN ";
  $sql2 .= "           rank_keyword ";
  $sql2 .= "         ON ";
  $sql2 .= "           rank_crawl.rcr_rkwid = rank_keyword.rkw_id";
  $sql2 .= "       ) ";
  $sql2 .= "     LEFT JOIN ";
  $sql2 .= "       rank_client ";
  $sql2 .= "     ON ";
  $sql2 .= "       rank_crawl.rcr_rclid = rank_client.rcl_id ";
  $sql2 .= "     LEFT JOIN ";
  $sql2 .= "       rank_fee ";
  $sql2 .= "     ON ";
  $sql2 .= "       rcr_rkwid = rfe_rkwid AND ";
  $sql2 .= "       (";
  $sql2 .= "         (";
  $sql2 .= "           rcr_yahooranking  >= rfe_rank_start AND ";
  $sql2 .= "           rcr_yahooranking  <= rfe_rank_end   AND ";
  $sql2 .= "           rfe_provider_kind =  1 ";
  $sql2 .= "         ) OR ";
  $sql2 .= "         (";
  $sql2 .= "           rcr_googleranking >= rfe_rank_start AND ";
  $sql2 .= "           rcr_googleranking <= rfe_rank_end   AND ";
  $sql2 .= "           rfe_provider_kind =  2 ";
  $sql2 .= "         )";
  $sql2 .= "       )  ";
  $sql2 .= "     WHERE ";
  $sql2 .= "       (";
  $sql2 .= "         (rank_fee    .rfe_status             =   1) AND ";
  $sql2 .= "         (rank_crawl  .rcr_datetime           >= '".substr($date, 0, 8)."01 00:00:00') AND ";
  $sql2 .= "         (rank_crawl  .rcr_datetime           <= '".$date." 23:59:59') AND ";
  $sql2 .= "         (rank_client .rcl_status             =   1) AND ";
  $sql2 .= "         (rank_keyword.rkw_account_type       =   3) AND ";
  $sql2 .= "         (rank_keyword.rkw_contract_startdate <= '".substr($date, 0, 8)."01 00:00:00') AND ";
  $sql2 .= "         (rank_keyword.rkw_contract_enddate   >= '".$date." 23:59:59')";
  $sql2 .= "       ) ";
  $sql2 .= "     ORDER BY ";
  $sql2 .= "       rcr_rkwid, ";
  $sql2 .= "       rfe_provider_kind, ";
  $sql2 .= "       rcr_datetime";
  $sql2 .= "   ) X ";
  $sql2 .= "   GROUP BY ";
  $sql2 .= "     X.rcr_rkwid , ";
  $sql2 .= "     X.rfe_provider_kind";
  $sql2 .= " ) Y ";
  $sql2 .= " WHERE ";
  $sql2 .= "   Y.countTendays = Y.rkw_account_date AND ";
  $sql2 .= "   Y.rcr_datetime >= '".$date." 00:00:00' AND ";
  $sql2 .= "   Y.rcr_datetime <= '".$date." 23:59:59'";

  return $sql2;
}//End function 


## 10日課金計算用SQL
## 引数   YYYY-MM-DD  指定された日
## 返値   string      10日課金計算用SQL
function makeSqlExternalTendaysWhich($date,$measure,$strKwInfo){

  $sql2  = "";
  $sql2 .= " SELECT ";
  $sql2 .= "   Y.* ";
  $sql2 .= " FROM ( ";
  $sql2 .= "   SELECT ";
  $sql2 .= "     X.rcr_id , ";
  $sql2 .= "     X.rcr_rkwid , ";
  $sql2 .= "     X.rkw_word , ";
  $sql2 .= "     X.rkw_mobile_flag , ";
  $sql2 .= "     X.rkw_account_date , ";
  $sql2 .= "     X.rfe_provider_kind , ";
  $sql2 .= "     X.rfe_yen , ";
  $sql2 .= "     COUNT(X.rcr_rkwid) AS countTendays , ";
  $sql2 .= "     MAX(X.rcr_datetime) AS rcr_datetime";
  $sql2 .= "   FROM (";
  $sql2 .= "     SELECT ";
  $sql2 .= "       rank_crawl   .rcr_id,";
  $sql2 .= "       rank_crawl   .rcr_rkwid,";
  $sql2 .= "       rank_crawl   .rcr_yahooranking,";
  $sql2 .= "       rank_crawl   .rcr_googleranking,";
  $sql2 .= "       rank_keyword .rkw_mobile_flag,";
  $sql2 .= "       rank_keyword .rkw_account_type,";
  $sql2 .= "       rank_keyword .rkw_account_date,";
  $sql2 .= "       rank_keyword .rkw_word,";
  $sql2 .= "       rank_client  .rcl_status,";
  $sql2 .= "       rank_crawl   .rcr_datetime ,";
  $sql2 .= "       rank_fee     .rfe_id , ";
  $sql2 .= "       rank_fee     .rfe_provider_kind , ";
  $sql2 .= "       rank_fee     .rfe_yen ";
  $sql2 .= "     FROM ";
  $sql2 .= "       (";
  $sql2 .= "         rank_crawl ";
  $sql2 .= "         LEFT JOIN ";
  $sql2 .= "           rank_keyword ";
  $sql2 .= "         ON ";
  $sql2 .= "           rank_crawl.rcr_rkwid = rank_keyword.rkw_id";
  $sql2 .= "       ) ";
  $sql2 .= "     LEFT JOIN ";
  $sql2 .= "       rank_client ";
  $sql2 .= "     ON ";
  $sql2 .= "       rank_crawl.rcr_rclid = rank_client.rcl_id ";
  $sql2 .= "     LEFT JOIN ";
  $sql2 .= "       rank_fee ";
  $sql2 .= "     ON ";
  $sql2 .= "       rcr_rkwid = rfe_rkwid ";
  
  if($measure === 'klink' || $measure === 'slink') {
    $sql2 .= "     LEFT JOIN ";
    $sql2 .= "       satellite_branch ";
    $sql2 .= "     ON ";
    $sql2 .= "       rank_keyword.rkw_id =  satellite_branch.sab_rkwid  ";
    $sql2 .= "     LEFT JOIN ";
    $sql2 .= "       satellite_list ";
    $sql2 .= "     ON ";
    $sql2 .= "       satellite_list.stl_id =   satellite_branch.sab_salid  ";
  }//End if
  
  $sql2 .= " AND ";
  $sql2 .= "       (";
  $sql2 .= "         (";
  $sql2 .= "           rcr_yahooranking  >= rfe_rank_start AND ";
  $sql2 .= "           rcr_yahooranking  <= rfe_rank_end   AND ";
  $sql2 .= "           rfe_provider_kind =  1 ";
  $sql2 .= "         ) OR ";
  $sql2 .= "         (";
  $sql2 .= "           rcr_googleranking >= rfe_rank_start AND ";
  $sql2 .= "           rcr_googleranking <= rfe_rank_end   AND ";
  $sql2 .= "           rfe_provider_kind =  2 ";
  $sql2 .= "         )";
  $sql2 .= "       )  ";
  
  
#  $sql2 .= "     LEFT JOIN ";
#  $sql2 .= "       satellite_branch ";
#  $sql2 .= "     ON ";
#  $sql2 .= "       rank_keyword.rkw_id =  satellite_branch.sab_rkwid  ";
#  $sql2 .= "     LEFT JOIN ";
#  $sql2 .= "       satellite_list ";
#  $sql2 .= "     ON ";
#  $sql2 .= "       satellite_list.stl_id =   satellite_branch.sab_salid  ";



  $sql2 .= "     WHERE ";
  $sql2 .= "       (";
  $sql2 .= "         (rank_fee    .rfe_status             =   1) AND ";
  $sql2 .= "         (rank_crawl  .rcr_datetime           >= '".substr($date, 0, 8)."01 00:00:00') AND ";
  $sql2 .= "         (rank_crawl  .rcr_datetime           <= '".$date." 23:59:59') AND ";
  $sql2 .= "         (rank_client .rcl_status             =   1) AND ";
  $sql2 .= "         (rank_keyword.rkw_account_type       =   3) AND ";
  $sql2 .= "         (rank_keyword.rkw_contract_startdate <= '".substr($date, 0, 8)."01 00:00:00') AND ";
  $sql2 .= "         (rank_keyword.rkw_contract_enddate   >= '".$date." 23:59:59')";
  
  
    
  switch($measure) {
    case"kotei80":
      $sql2 .= " AND (rank_keyword.rkw_R201311_flag = 1) ";#固定80サイトあり
      $sql2 .= " AND (rank_keyword.rkw_random_flag != 9)  ";#新ランダムなし
      break;
    case"random":
      $sql2 .= "         AND (rank_keyword.rkw_random_flag = 9) ";#ランダムあり
      $sql2 .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      $sql2 .= "         AND (rank_keyword.rkw_id NOT IN ({$strKwInfo})) ";#JGサテ(80固定サテぬき)
      break;
    case"random2":
      $sql2 .= "         AND (rank_keyword.rkw_random_flag = 8) ";#ランダムあり
      $sql2 .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      $sql2 .= "         AND (rank_keyword.rkw_id NOT IN ({$strKwInfo})) ";#JGサテ(80固定サテぬき)
      break;
    case"random3":
      $sql2 .= "         AND (rank_keyword.rkw_random_flag = 7) ";#ランダムあり
      $sql2 .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      $sql2 .= "         AND (rank_keyword.rkw_id NOT IN ({$strKwInfo})) ";#JGサテ(80固定サテぬき)
      break;
    case"naverlink":
      $sql2 .= "         AND (rank_keyword.rkw_naver_flag = 1) ";#ネイバーリンクあり
      break;
    case"klink":
      $sql2 .= "         AND (satellite_list.stl_purchase = 'PR4（金井リンク）') ";#PR4あり
      $sql2 .= "         AND (rank_keyword.rkw_random_flag  != 9) ";#ランダムなし
      $sql2 .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      break;
    case"slink":
      $sql2 .= "         AND (satellite_list.stl_purchase = 'PR3（SEO株式会社）') ";#PR4あり
      $sql2 .= "         AND (rank_keyword.rkw_random_flag  != 9) ";#ランダムなし
      $sql2 .= "         AND (rank_keyword.rkw_R201311_flag != 1) ";#80固定サテなし
      break;
  }//End switch
  
  $sql2 .= " AND (rank_keyword.rkw_gra_flag != 1) ";#ジラフなし
  $sql2 .= " AND (rank_keyword.rkw_seo_flag != 1) ";#SEOなし

  $sql2 .= "       ) ";
  $sql2 .= "     ORDER BY ";
  $sql2 .= "       rcr_rkwid, ";
  $sql2 .= "       rfe_provider_kind, ";
  $sql2 .= "       rcr_datetime";
  $sql2 .= "   ) X ";
  $sql2 .= "   GROUP BY ";
  $sql2 .= "     X.rcr_rkwid , ";
  $sql2 .= "     X.rfe_provider_kind";
  $sql2 .= " ) Y ";
  $sql2 .= " WHERE ";
  $sql2 .= "   Y.countTendays = Y.rkw_account_date AND ";
  $sql2 .= "   Y.rcr_datetime >= '".$date." 00:00:00' AND ";
  $sql2 .= "   Y.rcr_datetime <= '".$date." 23:59:59'";

  return $sql2;
}//End function


## 順位達成費用検索
## 引数   YYYY-MM-DD  指定された日
## 返値   int         金額合計
function achievementCalc($date,  $achieveYenPc, $achieveYenMobile){

$achieveYenPc = 0;
$achieveYenMobile = 0;

  $sql  = "";
  $sql .= " SELECT ";
  $sql .= "   rae_yen, rkw_mobile_flag";
  $sql .= " FROM ";
  $sql .= "   rank_achievement_expense ";
  $sql .= " inner join  ";
  $sql .= " rank_keyword  ";
  $sql .= " on  ";
  $sql .= " rkw_id = rae_rkwid ";
  $sql .= " WHERE ";
  $sql .= "   rae_success_time >= '".$date." 00:00:00' AND ";
  $sql .= "   rae_success_time <= '".$date." 23:59:59'";
//print $sql;
require('SQLConnect.php');
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);		//レコード数
  $intAchiveYen = 0;
  for ($i=0; $i<=$rsCount-1; $i++){
    $row = @mysql_fetch_array($rs);
    $intAchiveYen += $row[rae_yen];
    if($row[rkw_mobile_flag] == "0") $achieveYenPc += $row[rae_yen];
    if($row[rkw_mobile_flag] == "1") $achieveYenMobile += $row[rae_yen];
  }
require('SQLClose.php');

  return $intAchiveYen;
}


//rkw_account_typeが1のものしか計算できない:10日間の計測などができないから
function feeCalc($rkw_id, $yahooranking, $googleranking, $gooranking, &$yahooYen, &$googleYen, &$gooYen){

  $tempYen = 0;
  $yahooYen = 0;
  $googleYen = 0;
  $gooYen = 0;

  //Yahoo計算
  require('SQLConnect_jas.php');
  $sql = "SELECT * FROM `rank_fee` Where(rfe_rkwid = " . $rkw_id . " and rfe_provider_kind = 1 " . 
         " and rfe_rank_start<= " . $yahooranking . " and rfe_rank_end >= " . $yahooranking . 
         " and rfe_status = 1);";
  //print $sql . "<br>";
  //SQL文を実行する
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);		//レコード数
  if ($rsCount>1) die("なんかYahoo" . $rsCount . "個あるよ" . $rkw_id);
    $row = @mysql_fetch_array($rs);
    $tempYen += $row[rfe_yen];
    $yahooYen += $row[rfe_yen];
  //require('SQLClose.php');

  //Google計算
  require('SQLConnect_jas.php');
  $sql = "SELECT * FROM `rank_fee` Where(rfe_rkwid = " . $rkw_id . " and rfe_provider_kind = 2 " . 
         " and rfe_rank_start<= " . $googleranking . " and rfe_rank_end >= " . $googleranking .
         " and rfe_status = 1);";
  //print $sql . "<br>";
  //SQL文を実行する
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);		//レコード数
  if ($rsCount>1) die("なんかGoogle" . $rsCount . "個あるよ" . $rkw_id);
    $row = @mysql_fetch_array($rs);
    $googleYen += $row[rfe_yen];
    $tempYen += $row[rfe_yen];
  //require('SQLClose.php');

  //Goo計算
  require('SQLConnect_jas.php');
  $sql = "SELECT * FROM `rank_fee` Where(rfe_rkwid = " . $rkw_id . " and rfe_provider_kind = 3 " . 
         " and rfe_rank_start<= " . $gooranking . " and rfe_rank_end >= " . $gooranking .
         " and rfe_status = 1);";
  //print $sql . "<br>";
  //SQL文を実行する
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);		//レコード数
  if ($rsCount>1) die("なんかGoo" . $rsCount . "個あるよ" . $rkw_id);
    $row = @mysql_fetch_array($rs);
    $gooYen += $row[rfe_yen];
    $tempYen += $row[rfe_yen];
  //require('SQLClose.php');

  return $tempYen;

}//End function


function daylyCalcMeasure($date, $rkw_id, &$rcd_measure_yen_pc, $kView, $arrTodayCap, $measure,$strKwInfo){

//print_r($arrTodayCap);

$yenAll = 0;
$rcd_measure_yen_pc = 0;


switch($measure) {
  case "kotei80":
    $plusSql  = " AND (rkw_R201311_flag = 1) ";#固定80サイトあり
    $plusSql .= " AND (rkw_random_flag != 9)  ";#新ランダムなし
#    $plusSql .= " AND (rkw_naver_flag != 1)  ";#ネイバーなし
    break;
  
  //ランダムリンク2014年1月製造
  case "random":
    $plusSql .= " AND (rkw_R201311_flag != 1) ";#固定80サイトなし
    $plusSql  = " AND (rkw_random_flag   = 9) ";#新ランダム有り
#    $plusSql .= " AND (rkw_naver_flag != 1)  ";#ネイバーなし
    $plusSql .= " AND (rkw_id NOT IN ($strKwInfo)) ";
    break;
  
  //ランダムリンク2014年2月製造
  case "random2":
    $plusSql .= " AND (rkw_R201311_flag != 1) ";#固定80サイトなし
    $plusSql  = " AND (rkw_random_flag   = 8) ";#新ランダム有り
#    $plusSql .= " AND (rkw_naver_flag != 1)  ";#ネイバーなし
    $plusSql .= " AND (rkw_id NOT IN ($strKwInfo)) ";
    break;
  
  //ランダムリンク2014年3月製造
  case "random3":
    $plusSql .= " AND (rkw_R201311_flag != 1) ";#固定80サイトなし
    $plusSql  = " AND (rkw_random_flag   = 7) ";#新ランダム有り
#    $plusSql .= " AND (rkw_naver_flag != 1)  ";#ネイバーなし
    $plusSql .= " AND (rkw_id NOT IN ($strKwInfo)) ";
    break;
        
  case "naverlink":
    $plusSql .= " AND (rkw_R201311_flag != 1) ";#固定80サイトなし
    $plusSql  = " AND (rkw_random_flag  != 9) ";#新ランダムなし
    $plusSql .= " AND (rkw_naver_flag = 1)  ";#ネイバーあり
    $plusSql .= " AND (rkw_id NOT IN ($strKwInfo)) ";
    break;
}//End switch

require('SQLConnect_jas.php');

  $sql  = "SELECT rank_crawl.rcr_id, rank_crawl.rcr_rkwid, rank_crawl.rcr_yahooranking, rank_crawl.rcr_googleranking, rank_crawl.rcr_gooranking, rank_keyword.rkw_account_type, rank_keyword.rkw_is_fee, rank_keyword.rkw_word, rank_client.rcl_status, rank_crawl.rcr_datetime ";
  $sql .= "FROM (rank_crawl LEFT JOIN rank_keyword ON rank_crawl.rcr_rkwid = rank_keyword.rkw_id) ";
  $sql .= "LEFT JOIN rank_client ON rank_crawl.rcr_rclid = rank_client.rcl_id ";
  $sql .= "WHERE ( TRUE ";
  $sql .= $plusSql;
  $sql .= "AND (rkw_gra_flag != 1)  ";
  $sql .= "AND (rkw_seo_flag != 1)  ";
  $sql .= "AND (rank_crawl.rcr_datetime Like '" . $date . "%') ";
  $sql .= "AND (rank_keyword.rkw_contract_startdate <= '" . $date . "') ";
  $sql .= "AND (rank_keyword.rkw_contract_enddate >= '" . $date . "')  ) ";
  $sql .= "Order By rcr_rkwid;";

switch($measure) {
  //KリンクSQL
  case "klink":
    $sql   = "SELECT rank_crawl.rcr_id, rank_crawl.rcr_rkwid, rank_crawl.rcr_yahooranking, rank_crawl.rcr_googleranking, rank_crawl.rcr_gooranking, rank_keyword.rkw_account_type, rank_keyword.rkw_is_fee, rank_keyword.rkw_word, rank_client.rcl_status, rank_crawl.rcr_datetime  ";
    $sql  .= "FROM (rank_crawl LEFT JOIN rank_keyword ON rank_crawl.rcr_rkwid = rank_keyword.rkw_id)";
    $sql  .= "LEFT JOIN rank_client ON rank_crawl.rcr_rclid = rank_client.rcl_id ";
    $sql  .= "LEFT JOIN satellite_branch ON rkw_id = sab_rkwid ";
    $sql  .= "LEFT JOIN satellite_list ON sab_salid = stl_id ";
    $sql  .= "WHERE (TRUE ";
    $sql  .= " AND (rank_crawl.rcr_datetime Like '" . $date . "%') ";
    $sql  .= " AND (rank_keyword.rkw_contract_startdate <= '" . $date . "') ";
    $sql  .= " AND (rank_keyword.rkw_contract_enddate >= '" . $date . "') ";
    $sql  .= " AND (stl_purchase = 'PR4（金井リンク）') ";
    $sql  .= " AND (sab_flag = 0) ";#はってあるキーワード対象
    $sql  .= " AND (rkw_random_flag != 9) ";#ランダムなし
    $sql  .= " AND (rkw_R201311_flag != 1) ";#80固定サテなし
    $sql  .= " AND (rkw_gra_flag != 1) ";#外注ジラフなし
    $sql  .= " AND (rkw_seo_flag != 1) ) ";#外注SEO㈱なし
#    $sql  .= " AND (rkw_naver_flag != 1)  ";#ネイバーなし
    $sql  .= " GROUP BY rkw_id";
#    $externalSql  .= "";
#    $externalSql  .= "";
    break;
  
  //SリンクSQL
  case "slink":
    $sql   = "SELECT rank_crawl.rcr_id, rank_crawl.rcr_rkwid, rank_crawl.rcr_yahooranking, rank_crawl.rcr_googleranking, rank_crawl.rcr_gooranking, rank_keyword.rkw_account_type, rank_keyword.rkw_is_fee, rank_keyword.rkw_word, rank_client.rcl_status, rank_crawl.rcr_datetime  ";
    $sql  .= "FROM (rank_crawl LEFT JOIN rank_keyword ON rank_crawl.rcr_rkwid = rank_keyword.rkw_id)";
    $sql  .= "LEFT JOIN rank_client ON rank_crawl.rcr_rclid = rank_client.rcl_id ";
    $sql  .= "LEFT JOIN satellite_branch ON rkw_id = sab_rkwid ";
    $sql  .= "LEFT JOIN satellite_list ON sab_salid = stl_id ";
    $sql  .= "WHERE (TRUE ";
    $sql  .= " AND (rank_crawl.rcr_datetime Like '" . $date . "%') ";
    $sql  .= " AND (rank_keyword.rkw_contract_startdate <= '" . $date . "') ";
    $sql  .= " AND (rank_keyword.rkw_contract_enddate >= '" . $date . "') ";
    $sql  .= " AND (stl_purchase = 'PR3（SEO株式会社）') ";
    $sql  .= " AND (sab_flag = 0) ";#はってあるキーワードを対象
    $sql  .= " AND (rkw_random_flag != 9) ";#ランダムなし
    $sql  .= " AND (rkw_R201311_flag != 1) ";#80固定サテなし
    $sql  .= " AND (rkw_gra_flag != 1) ";#外注ジラフなし
    $sql  .= " AND (rkw_seo_flag != 1) ) ";#外注SEO㈱なし
#    $sql  .= " AND (rkw_naver_flag != 1)  ";#ネイバーなし
    $sql  .= " GROUP BY rkw_id";
    break;
  }//End switch

  print "$sql<br /><br />";

$arrTendays = array();

//10日課金の計算
$sql2 = makeSqlExternalTendays($date, $measure,$strKwInfo);
#施策別でWHEREに追加

print "10日課金 : {$sql2}<br />";

//print "10日: <br>" . $sql2 . "<br><br>";
$rs2  = mysql_db_query($dbName,$sql2);
$rsCount2 = mysql_num_rows($rs2);		  //レコード数

for ($i=0; $i<=$rsCount2-1; $i++){
  $tempRow = @mysql_fetch_array($rs2);
  
  #2014-02-17
#  if($tempRow[rfe_provider_kind]==1) $chgFieldName = "rcr_yahoo_yen";
#  if($tempRow[rfe_provider_kind]==2) $chgFieldName = "rcr_google_yen";
#  if($tempRow[rfe_provider_kind]==3) $chgFieldName = "rcr_goo_yen";
#  $tempSql = "UPDATE `rank_crawl` SET `" . $chgFieldName . "` = '" . $tempRow[rfe_yen] . "' WHERE (`rcr_id` = " . $tempRow[rcr_id] . ");";
//  print $tempSql . "<BR><BR>";
#  $c_hit = mysql_db_query($dbName,$tempSql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());

  if(array_key_exists($tempRow["rcr_rkwid"], $arrTendays)){
    $arrTendays[$tempRow["rcr_rkwid"]]["rfe_yen"] += $tempRow["rfe_yen"];
  } else {
    $arrTendays[$tempRow["rcr_rkwid"]] = $tempRow;
  }
}

//10日どちららか課金の計算
$sql2 = makeSqlExternalTendaysWhich($date, $measure,$strKwInfo);

print "10日課金どちらか : {$sql2}<br />";

#$yahooTenyen  = 0;
#$googleTenyen = 0;
#$gooTenyen = 0;

//print "10日: <br>" . $sql2 . "<br><br>";
$rs2  = mysql_db_query($dbName,$sql2);
$rsCount2 = mysql_num_rows($rs2);		  //レコード数

for ($i=0; $i<=$rsCount2-1; $i++){
  $tempRow = @mysql_fetch_array($rs2);

  $yahooTenyen  = 0;
  $googleTenyen = 0;
  $gooTenyen = 0;

#  if($tempRow[rfe_provider_kind]==1) $chgFieldName = "rcr_yahoo_yen";
#  if($tempRow[rfe_provider_kind]==2) $chgFieldName = "rcr_google_yen";
#  if($tempRow[rfe_provider_kind]==3) $chgFieldName = "rcr_goo_yen";
#  $tempSql = "UPDATE `rank_crawl` SET `" . $chgFieldName . "` = '" . $tempRow[rfe_yen] . "' WHERE (`rcr_id` = " . $tempRow[rcr_id] . ");";
//  print $tempSql . "<BR><BR>";
#  $c_hit = mysql_db_query($dbName,$tempSql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());

  if((int)$tempRow['rfe_provider_kind'] === 1) $yahooTenyen = (int)$tempRow[rfe_yen];
  if((int)$tempRow['rfe_provider_kind'] === 2) $googleTenyen = (int)$tempRow[rfe_yen];
  if((int)$tempRow['rfe_provider_kind'] === 3) $gooTenyen = (int)$tempRow[rfe_yen];
  
  if(array_key_exists($tempRow["rcr_rkwid"], $arrTendays)){
    if($yahooYen >= $googleYen) {
      $tempYen = $yahooYen;
    }else{
      $tempYen = $googleYen;
    }//End if
    #$arrTendays[$tempRow["rcr_rkwid"]]["rfe_yen"] += $tempRow["rfe_yen"];
    $arrTendays[$tempRow["rcr_rkwid"]]["rfe_yen"] += $tempYen;
  } else {
    $arrTendays[$tempRow["rcr_rkwid"]] = $tempRow;
  }
}


if(!$kView){
?>
PC用画面
<table border=1 cellpadding=3 cellspacing=1>
<tr>
  <td>ID</td>
  <td>キーワードID</td>
  <td>キーワード</td>
  <td>Y順位</td>
  <td>G順位</td>
  <td>Goo順位</td>
  <td>Y金額</td>
  <td>G金額</td>
  <td>Goo金額</td>
  <td>10日課金</td>
  <td><font color=red>キャップ</font></td>
  <td>合計</td>
</tr>
<?
}
//SQL文を実行する
$isMobile = false;
$rs  = mysql_db_query($dbName,$sql);
$rsCount = mysql_num_rows($rs);		//レコード数
  for ($i=0; $i<=$rsCount-1; $i++){
    $row  = @mysql_fetch_array($rs);
    $yahooYen = 0;
    $googleYen = 0;
    $gooYen = 0;
    $tendaysYen = 0;
    $tempYen = 0;
    
    //通常課金

      if ((!$isMobile) && ($row[rkw_mobile_flag])){
      $isMobile = true;
      if(!$kView){
?>
      </table>
      <br><br>
      携帯用画面
      <table border=1 cellpadding=3 cellspacing=1>
      <tr>
        <td>ID</td>
        <td>キーワードID</td>
        <td>キーワード</td>
        <td>Y順位</td>
        <td>G順位</td>
        <td>Goo順位</td>
        <td>Y金額</td>
        <td>G金額</td>
        <td>Goo金額</td>
        <td>10日課金</td>
        <td><font color=red>キャップ</font></td>
        <td>合計</td>
      </tr>
<?
      }
      }
      $tendaysYen = 0;
      if ($row["rkw_account_type"] ==1){
        //print "---" . $row[rcr_gooranking] . "---<br>";
        $tempYen = feeCalc($row[rcr_rkwid], $row[rcr_yahooranking], $row[rcr_googleranking], $row[rcr_gooranking], $yahooYen, $googleYen, $gooYen);
        
        
      }

      
      //日毎どちらか課金の時は、金額の大きいほうを足し合わせる
      if ($row["rkw_account_type"] ==5){
        feeCalc($row[rcr_rkwid], $row[rcr_yahooranking], $row[rcr_googleranking], $row[rcr_gooranking], $yahooYen, $googleYen, $gooYen);
        
        if($yahooYen >= $googleYen) {
          $tempYen = $yahooYen;
        }else{
          $tempYen = $googleYen;
        }
        
        //金額をアップデート
        require('SQLConnect_jas.php');
      }//End if
      
      //n日課金もしくはn日どちらか課金
      //account_type=2 : n日課金
      if ($row["rkw_account_type"] ==2){
        if(array_key_exists($row["rcr_rkwid"], $arrTendays)){
          $tendaysYen = (int)$arrTendays[$row["rcr_rkwid"]]["rfe_yen"];
          $tempYen = (int)$arrTendays[$row["rcr_rkwid"]]["rfe_yen"];
        }else{
          $tempYen = 0;
        }
      }

      #account_type=3 : n日どちらか課金
      if ($row["rkw_account_type"] ==3){
        if(array_key_exists($row["rcr_rkwid"], $arrTendays)){
          $tendaysYen = (int)$arrTendays[$row["rcr_rkwid"]]["rfe_yen"];
          $tempYen = (int)$arrTendays[$row["rcr_rkwid"]]["rfe_yen"];
        }else{
          $tempYen = 0;
        }
      }

      
      $key = $row[rcr_rkwid];
if(!$kView){
?>

    <tr>
      <td><?=$row[rcr_id]?></td>
      <td><?=$row[rcr_rkwid]?></td>
      <td><?=$row[rkw_word]?></td>
      <td><?=$row[rcr_yahooranking]?></td>
      <td><?=$row[rcr_googleranking]?></td>
      <td><?=$row[rcr_gooranking]?></td>
      <td><?=$yahooYen?></td>
      <td><?=$googleYen?></td>
      <td><?=$gooYen?></td>
      <td><?=$tendaysYen?></td>
      <td>
      <?
      $key = $row[rcr_rkwid];
      if($arrTodayCap[$key]!="") print "<font color=red>" . $arrTodayCap[$key] . "</font>";
      $arrTodayCap[$key] = "OK";
      ?>
      </td>
      <td><?=$tempYen?></td>
    </tr>
      
<?
}


      $yenAll += $tempYen;
      
      //施策ごとのPC課金のSUM
      if ($row["rkw_account_type"] ==1 || $row["rkw_account_type"] ==5){
        if(!$isMobile) $rcd_measure_yen_pc += $tempYen;
      }//End if
  }//for





require('SQLClose.php');

if(!$kView){
?>
</table>

<?
}
//print "トータルは" . $yenAll . "円です<br>";
#print_r($arrTodayCap);

return $yenAll;
?>

<?


}//End function Random


//JG外部リンク一覧(80固定除く)
function externalLinkList($date) {

  require('SQLConnect_jas.php');
  
  $sql  = "SELECT sab_rkwid FROM ";
  $sql .= "satellite_branch INNER JOIN ";
  $sql .= "satellite_list ON sab_salid = stl_id INNER JOIN ";
  $sql .= "rank_keyword ON sab_rkwid = rkw_id ";
  $sql .= " WHERE TRUE ";
  $sql .= " AND sab_flag  = 0 ";
#  $sql .= " AND rkw_random_flag != 9 ";
#  $sql .= " AND rkw_R201311_flag !=1 ";
#  $sql .= " AND rkw_naver_flag != 1 ";
  $sql .= " AND rkw_gra_flag != 1 ";
  $sql .= " AND rkw_seo_flag != 1 ";
  $sql .= " AND stl_purchase != 'R201311' ";#80固定除く
  $sql .= " AND rank_keyword.rkw_contract_startdate <= '" . $date . "' ";
  $sql .= " AND rank_keyword.rkw_contract_enddate >= '" . $date . "' ";
  $sql .= "GROUP BY rkw_id";
  $sql .= "";
  $sql .= "";
  $sql .= "";
  $sql .= "";

  print "externalLink : $sql<br/ >";
  
  $rs = mysql_db_query($dbName,$sql);
  
  $row = mysql_num_rows($rs);
  
  for($i=0;$i<$row;$i++) {
    $arrRows = mysql_fetch_array($rs);
    $arraySqlList[] = $arrRows[sab_rkwid];
  }//End for
  
  require('SQLClose.php');
  return($arraySqlList);
}//End function

//数字格納配列をカンマで文字列にする
function arraySqlStrConversion($arraySqlList) {
  foreach($arraySqlList as $key => $value) {
    if($key!=0) {
      $strSqlList .= ", {$value}";
    } else {
      $strSqlList = $value;
    }//End if
  }//End foreach
  
  return($strSqlList);
  
}//End funciton