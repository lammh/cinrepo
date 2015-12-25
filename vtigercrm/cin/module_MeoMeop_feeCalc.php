<?
function clientFee($isFs, $intClientId, $thisMonth, &$intSumYen, &$intCapYen){
  $intSumYen = 0;
  $intCapYen = 0;
  //print $intClientId;
  //GoogleMap使用を明示
  require('SQLConnect_googlemap.php');
  //print $thisMonth;
  
  //例外処理のため取得
  $sql = "SELECT * FROM `gm_client` Where(gcl_id = " . $intClientId . ");";
  //print $sql . "<br><br>";
  //SQL文を実行する
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);		//レコード数
  
  //for ($i=0; $i<=$rsCount-1; $i++){
    $row = @mysql_fetch_array($rs);
    $gcl_name = $row[gcl_name];
  //}
  
  //キャップ取得
  $sql = "SELECT * FROM `gm_cap_mas` Where(gcm_client_id = " . $intClientId . ");";
  //print $sql . "<br><br>";
  //SQL文を実行する
  $rs0 = mysql_db_query($dbName,$sql);
  $rsCount0 = mysql_num_rows($rs0);		//レコード数
  
  for ($l=0; $l<=$rsCount0-1; $l++){
    $row0 = @mysql_fetch_array($rs0);
    $gcm_id = $row0[gcm_id];
    if($isFs){
      $intCapYen += $row0[gcm_max_fs_yen];
    }else{
      $intCapYen += $row0[gcm_max_yen];
    }
    //print $intCapYen ."<br>";
    
    $arrKeyid = Array();
    $sql = "SELECT * FROM `gm_cap_child` Where(gcc_capid = " . $gcm_id . ");";
    //print $sql . "<br><br>";
    //SQL文を実行する
    $rs = mysql_db_query($dbName,$sql);
    $rsCount = mysql_num_rows($rs);		//レコード数
    
    for ($i=0; $i<=$rsCount-1; $i++){
      $row = @mysql_fetch_array($rs);
      $arrKeyid[] = $row[gcc_keyid];
    }
    //print join(", ", $arrKeyid) . "<br>";
    if(count($arrKeyid) == 0){
      print "該当の件がありません";
      return;
    }
    
    $sql = "SELECT * FROM `gm_keyword` Where(gke_id in (" . join(", ", $arrKeyid) . ") );";
    //print $sql . "<br><br>";
    //SQL文を実行する
    $rs = mysql_db_query($dbName,$sql);
    $rsCount = mysql_num_rows($rs);		//レコード数
    for ($i=0; $i<=$rsCount-1; $i++){
      $row = @mysql_fetch_array($rs);
  
  
      $sql2 = "SELECT * FROM `gm_fee` Where(gfe_status = 1 and gfe_gkeid = " . $row[gke_id] . ");";
      //print $sql2 . "<br><br>";
      //SQL文を実行する
      $rs2 = mysql_db_query($dbName,$sql2);
      $rsCount2 = mysql_num_rows($rs2);		//レコード数
      
      for ($j=0; $j<=$rsCount2-1; $j++){
        $row2 = @mysql_fetch_array($rs2);
        $tempCount = 0;
        $sql3 = "SELECT gcr_rank FROM `rn2_gm_crawl` Where(gcr_gkeid = " . $row[gke_id] . " and gcr_date Like '" .  $thisMonth . "%');";
        //print $sql3 . "<br><br>";
        //SQL文を実行する
        $rs3 = mysql_db_query($dbName,$sql3);
        $rsCount3 = mysql_num_rows($rs3);		//レコード数
        
        for ($k=0; $k<=$rsCount3-1; $k++){
          $row3 = @mysql_fetch_array($rs3);
          if($row2[gfe_rank_start] <= $row3[gcr_rank] && $row2[gfe_rank_end] >= $row3[gcr_rank]){
            $tempCount++;
          }
          
        }
        if($isFs){
          $tempSum = $row2[gfe_fs_yen] * $tempCount;
        }else{
          $tempSum = $row2[gfe_yen] * $tempCount;
        }
        
        //エルミタージュ・RITZの例外処理
        if($gcl_name=="エルミタージュ"){
          if($tempCount>=10){
            $intSumYen = 25000;
            return;
          }
        }

        if($gcl_name=="RITZ"){
          if($tempCount>=10){
            $intSumYen = 45000;
            return;
          }
        }
        
        $intSumYen += $tempSum;
        //print $row[gke_keyword] . " : " . $tempSum . "<br>" ;
      }
      
      
    }

  }
  
  
  

}//End function


require "baseDao.php";
/**
 * ログイン
 * 
 * @param  str $strUser, $strPass
 * 
 * @return arr $arrDataContent      アカウントIDと代理店フラグの配列
 */
Class mapeoDao extends baseDao {


  var $keywordDetail;

  var $arrDailyRank;


  /**
   * 店舗単位の金額
   * 
   * （修正したらY:\test\googlemap\members\lib\mapeoDao.phpのclientFeeForCapと同期を取ること）
   * 
   * @param  int $intClienttId    クライアントID
   *         int $intCapId        キャップID
   *         int $strTargetMonth  対象月（YYYY-MM）
   * 
   * @return arr                  ["sum"] (int)合計金額
   *                              ["cap"] (int)キャップ金額
   */
  function clientFeeForCap($intClientId, $intCapId, $strTargetYM = "") {

    $intSumYen = 0;
    $intCapYen = 0;
    $strTargetYM  = (preg_match("/[0-9]{4}-[0-9]{2}/", $strTargetYM)) ? $strTargetYM : date("Y-m");
    $strDateS     = "{$strTargetYM}-01";
    $strDateE     = date("Y-m-t", strtotime("{$strTargetYM}-01"));
    $strDateTimeS = "{$strTargetYM}-01 00:00:00";
    $strDateTimeE = date("Y-m-t", strtotime("{$strTargetYM}-01")) . " 23:59:59";

    //例外処理のため取得
    $sql = "SELECT gcl_name FROM gm_client_foreign WHERE gcl_id = {$intClientId} ";
    $this->query($sql);
    $intRows = $this->rows();
    $row = $this->fetch();
    $strClientName = $row["gcl_name"];

    //キャップ取得
    $sql = "SELECT gcm_id, gcm_max_yen FROM gm_cap_mas WHERE gcm_id = {$intCapId} ";
    $this->query($sql);
    $rsCount0 = $this->rows();

    if ($rsCount0 > 0) {
      //ループ（キャップ単位）
      while ($row0 = $this->fetch()) {

        $intGcmId   = (int)$row0["gcm_id"];
        $intCapYen += (int)$row0["gcm_max_yen"];

        $arrKeyid = array();
        $sql = "SELECT gcc_keyid FROM gm_cap_child WHERE gcc_capid = {$intGcmId} ";
        $this->query($sql);
        $rsCount = $this->rows();

        if ($rsCount > 0) {
          $arrKeyid = array();# 初期化
          while ($row = $this->fetch()) {
            $arrKeyid[] = (int)$row["gcc_keyid"];
          }//while
        }//if

        if (count($arrKeyid) === 0) break(1);//全てのループというしがらみから脱出

        $strWhereInKeyId = join(", ", $arrKeyid);

        //対象年月に契約日が最低でも1日以上引っかかっるキーワードを処理の対象とする
        $sql  = "SELECT gke_id FROM gm_keyword ";
        $sql .= "WHERE  gke_id IN ({$strWhereInKeyId}) AND gke_contract_startdate <= '{$strDateE}' AND '{$strDateS}' <= gke_contract_enddate ";
        $sql .= "  AND  gke_premium_flag = 0 ";

#        print $sql."<br />";

        $this->query($sql);
        $rsCount = $this->rows();


        if ($rsCount > 0) {

          $arrGkeId = array();# 初期化
          while ($row = $this->fetch()) {
            $arrGkeId[] = (int)$row["gke_id"];
          }//while

          //ループ（キーワード単位）
          foreach ($arrGkeId as $intGkeId) {

            //課金条件抽出（複数条件の場合有り）
            $sql2  = "SELECT gfe_rank_start, gfe_rank_end, gfe_yen FROM gm_fee WHERE gfe_status = 1 AND gfe_gkeid = {$intGkeId} ";
            $sql2 .= "ORDER BY gfe_rank_start, gfe_rank_end ";
            $this->query($sql2);
            $rsCount2 = $this->rows();
            if ($rsCount2 > 0) {

              $arrContract = array();# 初期化
              while ($row = $this->fetch()) {
                $arrContract[] = $row;
              }//while

              //ループ（課金単位）
              foreach ($arrContract as $arrTempContract) {
                $intGfeStart = (int)$arrTempContract["gfe_rank_start"];
                $intGfeEnd   = (int)$arrTempContract["gfe_rank_end"];
                $intGfeYen   = (int)$arrTempContract["gfe_yen"];

                $intTempCount = 0;

                //対象年月のクロールデータのうち、契約日を満たすレコードを全て抽出
                // => 課金対象の順位データのみ抽出
                $sql3  = "SELECT gcr_rank, gke_keyword FROM rn2_gm_crawl ";
                $sql3 .= "INNER JOIN gm_keyword ON gcr_gkeid = gke_id ";
                $sql3 .= "WHERE gcr_gkeid = {$intGkeId} AND '{$strDateTimeS}' <= gcr_date AND gcr_date <= '{$strDateTimeE}' ";  # 対象年月日で絞り込み
                $sql3 .= "  AND gke_contract_startdate <= gcr_date AND gcr_date <= gke_contract_enddate ";                      # 契約日とクロール日を紐付け
                $sql3 .= "  AND gke_premium_flag = 0 ";
                $sql3 .= "ORDER BY gcr_date ASC ";

#                print "sql3 : ".$sql3."<br />";

                $this->query($sql3);
                $rsCount3 = $this->rows();

                if ($rsCount3 > 0) {
                  while ($row3 = $this->fetch()) {
                    $intGcrRank = (int)$row3["gcr_rank"];
                    //既定順位達成
                    if ($intGfeStart <= $intGcrRank &&  $intGcrRank <= $intGfeEnd) $intTempCount++;

#                    print "<p>".$row3["gke_keyword"]." : ".$intGfeStart." <= ".$intGcrRank." &&  ".$intGcrRank." <= ".$intGfeEnd."</p>";

                  }//while
                }//if

                $intTempSum = $intGfeYen * $intTempCount;
                //エルミタージュ・RITZの例外処理
                if (($strClientName === "エルミタージュ") && ($intTempCount >= 10)) {
                  $intSumYen = 25000;
                  break(3);//全てのループというしがらみから脱出
                }//if

                if (($strClientName === "RITZ") && ($intTempCount >= 10)) {
                  $intSumYen = 45000;
                  break(3);//全てのループというしがらみから脱出
                }//if

                $intSumYen += $intTempSum;

              }//foreach
            }//if

          }//foreach
        }//if

      }//while
    }//if


    return array("sum" => $intSumYen, "cap" => $intCapYen);

  }//End function



  /**
   * 店舗単位の金額＠MEOプレミアム用
   * 
   * （修正したらY:\test\googlemap\members\lib\mapeoDao.phpのclientFeeForCapと同期を取ること）
   * 
   * @param  int $intClienttId    クライアントID
   *         int $intCapId        キャップID
   *         int $strTargetMonth  対象月（YYYY-MM）
   * 
   * @return arr                  ["sum"] (int)合計金額
   *                              ["cap"] (int)キャップ金額
   */
  function clientFeeForCapFeatMEOP($intClientId, $intCapId, $strTargetYM = "") {

    $intSumYen = 0;
    $intCapYen = 0;
    $strTargetYM  = (preg_match("/[0-9]{4}-[0-9]{2}/", $strTargetYM)) ? $strTargetYM : date("Y-m");
    $strDateS     = "{$strTargetYM}-01";
    $strDateE     = date("Y-m-t", strtotime("{$strTargetYM}-01"));
    $strDateTimeS = "{$strTargetYM}-01 00:00:00";
    $strDateTimeE = date("Y-m-t", strtotime("{$strTargetYM}-01")) . " 23:59:59";

    //例外処理のため取得
    $sql = "SELECT gcl_name FROM gm_client_foreign WHERE gcl_id = {$intClientId} ";
    $this->query($sql);
    $intRows = $this->rows();
    $row = $this->fetch();
    $strClientName = $row["gcl_name"];

    //キャップ取得
    $sql = "SELECT gcm_id, gcm_max_yen FROM gm_cap_mas WHERE gcm_id = {$intCapId} ";
    $this->query($sql);
    $rsCount0 = $this->rows();

    if ($rsCount0 > 0) {
      //ループ（キャップ単位）
      while ($row0 = $this->fetch()) {

        $intGcmId   = (int)$row0["gcm_id"];
        $intCapYen += (int)$row0["gcm_max_yen"];

        $arrKeyid = array();
        $sql = "SELECT gcc_keyid FROM gm_cap_child WHERE gcc_capid = {$intGcmId} ";
        $this->query($sql);
        $rsCount = $this->rows();

        if ($rsCount > 0) {
          $arrKeyid = array();# 初期化
          while ($row = $this->fetch()) {
            $arrKeyid[] = (int)$row["gcc_keyid"];
          }//while
        }//if

        if (count($arrKeyid) === 0) break(1);//全てのループというしがらみから脱出

        $strWhereInKeyId = join(", ", $arrKeyid);

        //対象年月に契約日が最低でも1日以上引っかかっるキーワードを処理の対象とする
        $sql  = "SELECT gke_id FROM gm_keyword ";
        $sql .= "WHERE  gke_id IN ({$strWhereInKeyId}) AND gke_contract_startdate <= '{$strDateE}' AND '{$strDateS}' <= gke_contract_enddate ";
        $sql .= "  AND  gke_premium_flag = 1 ";
        $this->query($sql);
        $rsCount = $this->rows();


        if ($rsCount > 0) {

          $arrGkeId = array();# 初期化
          while ($row = $this->fetch()) {
            $arrGkeId[] = (int)$row["gke_id"];
          }//while

          //ループ（キーワード単位）
          foreach ($arrGkeId as $intGkeId) {

            //課金条件抽出（複数条件の場合有り）
            $sql2  = "SELECT gfe_rank_start, gfe_rank_end, gfe_yen FROM gm_fee WHERE gfe_status = 1 AND gfe_gkeid = {$intGkeId} ";
            $sql2 .= "ORDER BY gfe_rank_start, gfe_rank_end ";
            $this->query($sql2);
            $rsCount2 = $this->rows();
            if ($rsCount2 > 0) {

              $arrContract = array();# 初期化
              while ($row = $this->fetch()) {
                $arrContract[] = $row;
              }//while

              //ループ（課金単位）
              foreach ($arrContract as $arrTempContract) {
                $intGfeStart = (int)$arrTempContract["gfe_rank_start"];
                $intGfeEnd   = (int)$arrTempContract["gfe_rank_end"];
                $intGfeYen   = (int)$arrTempContract["gfe_yen"];

                $intTempCount = 0;

                //対象年月のクロールデータのうち、契約日を満たすレコードを全て抽出
                // => 課金対象の順位データのみ抽出
                $sql3  = "SELECT gcr_rank FROM rn2_gm_crawl ";
                $sql3 .= "INNER JOIN gm_keyword ON gcr_gkeid = gke_id ";
                $sql3 .= "WHERE gcr_gkeid = {$intGkeId} AND '{$strDateTimeS}' <= gcr_date AND gcr_date <= '{$strDateTimeE}' ";  # 対象年月日で絞り込み
                $sql3 .= "  AND gke_contract_startdate <= gcr_date AND gcr_date <= gke_contract_enddate ";                      # 契約日とクロール日を紐付け
                $sql3 .= "  AND gke_premium_flag = 1 ";
                $sql3 .= "ORDER BY gcr_date ASC ";
                $this->query($sql3);
                $rsCount3 = $this->rows();

                if ($rsCount3 > 0) {
                  while ($row3 = $this->fetch()) {
                    $intGcrRank = (int)$row3["gcr_rank"];
                    //既定順位達成
                    if ($intGfeStart <= $intGcrRank &&  $intGcrRank <= $intGfeEnd) $intTempCount++;
                  }//while
                }//if

                $intTempSum = $intGfeYen * $intTempCount;
                //エルミタージュ・RITZの例外処理
                if (($strClientName === "エルミタージュ") && ($intTempCount >= 10)) {
                  $intSumYen = 25000;
                  break(3);//全てのループというしがらみから脱出
                }//if

                if (($strClientName === "RITZ") && ($intTempCount >= 10)) {
                  $intSumYen = 45000;
                  break(3);//全てのループというしがらみから脱出
                }//if

                $intSumYen += $intTempSum;

              }//foreach
            }//if

          }//foreach
        }//if

      }//while
    }//if


    return array("sum" => $intSumYen, "cap" => $intCapYen);

  }//End function


}//Class


?>