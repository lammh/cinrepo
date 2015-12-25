<?php
  /** vtiger:vtiger_products,vtiger_service → jas:rank_keyword の同期 **/
	$arrrUrlStrict = array(
		"0" => "部分一致",
		"1" => "ドメイン一致",
		"2" => "完全一致",
		"3" => "文字のマッチ"
	);

	$arrayAccountType = array(
		"1" => "日毎課金",
		"5" => "日毎どちらか課金",
		"3" => "n日どちらか課金",
		"4" => "月額固定",
		"2" => "n日課金" 
	); 


#	ini_set('include_path', '/var/www/html/vtigercrm_test');
  ini_set('include_path', '/var/www/html/vtigercrm');
	require_once('include/utils/utils.php');
	require_once('include/logging.php');

  $sqlAccount  = " SELECT accountid, jgid FROM vtiger_account ";

	print $sqlAccount . "<br />";

	$resultAccount = $adb->pquery($sqlAccount, array());
	$no_of_account = $adb->num_rows($resultAccount);

	for($i=0; $i<$no_of_account;$i++) {

	  $rowAccount =  $adb->fetch_array($resultAccount);
	  $arrAccount[$rowAccount[jgid]] = $rowAccount[accountid];
	
	}//End for

  require('SQLConnect_jas.php');

  $jasSql  = " SELECT * FROM rank_keyword ";
  $jasSql .= " WHERE TRUE ";
#  $jasSql .= " AND rkw_rclid = 1423 ";


  print $jasSql."<br />";

  $rsJas = mysql_db_query($dbName, $jasSql);
  $rsJasCount = mysql_num_rows($rsJas);

  for($j=0;$j<$rsJasCount;$j++) {
    $rowJas = mysql_fetch_array($rsJas);
    $arrContactDate[$rowJas[rkw_id]][seo_keyword] =$rowJas[rkw_word];
    $arrContactDate[$rowJas[rkw_id]][start_date] =$rowJas[rkw_contract_startdate];
    $arrContactDate[$rowJas[rkw_id]][expiry_date] =$rowJas[rkw_contract_enddate];
#    $arrContactDate[$rowJas[rkw_id]][seo_agency_customers] = ($rowJas[rkw_child_cliid] == false) ? 0 : $arrAccount[$rowJas[rkw_child_cliid]];
    $arrContactDate[$rowJas[rkw_id]][seo_segment] = $rowJas[rkw_segment];
    $arrContactDate[$rowJas[rkw_id]][seo_url] = $rowJas[rkw_url];
    $arrContactDate[$rowJas[rkw_id]][seo_rank_check] = $arrrUrlStrict[$rowJas[rkw_url_strict]];
    $arrContactDate[$rowJas[rkw_id]][seo_fee_method] = $arrayAccountType[$rowJas[rkw_account_type]];
    $arrContactDate[$rowJas[rkw_id]][seo_rank_daynum] = $rowJas[rkw_account_date];
    $arrContactDate[$rowJas[rkw_id]][seo_away_flag] = $rowJas[rkw_away_flag];

  }//End 

/*
  $jasSql  = " SELECT * FROM rank_keyword ";
  $jasSql .= " INNER JOIN rank_client ON rkw_rclid = rcl_id "; 
  $jasSql .= " WHERE TRUE ";
  $jasSql .= " AND rkw_segment = 1 ";
  $jasSql .= " AND rkw_contract_enddate >= '2015-09-01' ";

  print $jasSql."<br />";

  $rsJas = mysql_db_query($dbName, $jasSql);
  $rsJasCount = mysql_num_rows($rsJas);

  for($j=0;$j<$rsJasCount;$j++) {
    $rowJas = mysql_fetch_array($rsJas);

    $arrJasKwId[$rowJas[rkw_id]][keyword] = $rowJas[rkw_word];
    $arrJasKwId[$rowJas[rkw_id]][client] = $rowJas[rcl_name];
  }//End 
*/

  $jasSql2  = " SELECT rfe_rkwid, rfe_rank_start, rfe_rank_end, rfe_yen FROM rank_fee ";
  $jasSql2 .= " INNER JOIN rank_keyword ON rfe_rkwid = rkw_id "; 
  $jasSql2 .= " WHERE TRUE ";
  $jasSql2 .= " AND rfe_status = 1 ";
  $jasSql2 .= " AND rfe_provider_kind = 2 ";
#  $jasSql2 .= " AND rkw_rclid = 3346 ";
#  $jasSql2 .= " AND rkw_account_type IN (3, 5) ";
  $jasSql2 .= " ORDER BY rkw_id ASC, rfe_rank_start ASC ";

  print $jasSql2."<br />";

  $rsJas2 = mysql_db_query($dbName, $jasSql2);
  $rsJasCount2 = mysql_num_rows($rsJas2);

  $logKwId = 0;

  for($j2=0;$j2<$rsJasCount2;$j2++) {
    $rowJas2 = mysql_fetch_array($rsJas2);

    if((int)$logKwId !== (int)$rowJas2[rfe_rkwid]) {

    	for($intJ3=$intRfeCount++;$intJ3<=5;$intJ3++) {
		    $arrRankFee[$logKwId]['seo_rank_top_'.$intJ3] = 0;
		    $arrRankFee[$logKwId]['seo_rank_down_'.$intJ3] = 0;
		    $arrRankFee[$logKwId]['seo_fee_'.$intJ3] = 0;
    	}//End for

    	$intRfeCount = 1;
    	$logKwId = $rowJas2[rfe_rkwid];
    }//End if

    $arrRankFee[$rowJas2[rfe_rkwid]]['seo_rank_top_'.$intRfeCount] = $rowJas2[rfe_rank_start];
    $arrRankFee[$rowJas2[rfe_rkwid]]['seo_rank_down_'.$intRfeCount] = $rowJas2[rfe_rank_end];
    $arrRankFee[$rowJas2[rfe_rkwid]]['seo_fee_'.$intRfeCount] = $rowJas2[rfe_yen];

    $intRfeCount++;
  }//End 

  for($intJ3=$intRfeCount++;$intJ3<=5;$intJ3++) {
	  $arrRankFee[$logKwId]['seo_rank_top_'.$intJ3] = 0;
	  $arrRankFee[$logKwId]['seo_rank_down_'.$intJ3] = 0;
	  $arrRankFee[$logKwId]['seo_fee_'.$intJ3] = 0;
  }//End for





  $sqlResult  = " SELECT ";
  $sqlResult .= " `seo_kwid`, ";
  $sqlResult .= " `seo_keyword` ";
  $sqlResult .= " FROM ";
  $sqlResult .= " `vtiger_products` ";
  $sqlResult .= " LEFT JOIN `vtiger_inventoryproductrel` ON `vtiger_products`.`productid` = `vtiger_inventoryproductrel`.`productid` ";
  $sqlResult .= " WHERE TRUE  ";
#  $sqlResult .= "  AND `expiry_date` != '' ";
  $sqlResult .= "  AND `seo_kwid` != 0 ";
#  $sqlResult .= "  AND `vtiger_inventoryproductrel`.`id` = 24963 ";  
#  $sqlResult .= "  GROUP BY `vtiger_salesorder`.`salesorderid`";

  print $sqlResult . "<br />";

  $result = $adb->pquery($sqlResult, array());
  $no_of_products = $adb->num_rows($result);

  for($i=0; $i<$no_of_products;$i++) {

    $rowProducts =  $adb->fetch_array($result);
    $arrProducts[$rowProducts[seo_kwid]] = $rowProducts[seo_keyword];

    $arrKikanKwId[$rowProducts[seo_kwid]] = $rowProducts[seo_keyword];

  }//End for


  foreach ($arrProducts as $kwid => $strKeyWord) {

  	$strUpdateProducts  = " UPDATE `vtiger_products` SET ";
    $strUpdateProducts .= " `seo_keyword` = '{$arrContactDate[$kwid][seo_keyword]}', ";
    $strUpdateProducts .= " `start_date`  = '{$arrContactDate[$kwid][start_date]}', ";
    $strUpdateProducts .= " `expiry_date` = '{$arrContactDate[$kwid][expiry_date]}', ";
    $strUpdateProducts .= " `seo_segment` = '{$arrContactDate[$kwid][seo_segment]}', ";
    $strUpdateProducts .= " `seo_url` = '{$arrContactDate[$kwid][seo_url]}', ";
    $strUpdateProducts .= " `seo_rank_check` = '{$arrContactDate[$kwid][seo_rank_check]}', ";

    $strUpdateProducts .= " `division` = '202:Webコミュ', ";

    $strUpdateProducts .= " `seo_away_flag` = '{$arrContactDate[$kwid][seo_away_flag]}' ";

  	$strUpdateProducts .= " WHERE TRUE ";
  	$strUpdateProducts .= " AND  seo_kwid = {$kwid} ";


    require('SQLConnect.php');

  	print $strUpdateProducts . "<br />";

#    print "コメントアウト：更新されません。<br />";
    
    $result1 = mysql_db_query($dbName, $strUpdateProducts);

#    $result1 = $adb->pquery($strUpdateProducts, array());

    if($result1 == true) {
    	print "更新<br />";
    } else {
    	print "失敗<br />";
    }//End if

    #exit;

  }//End foreach

  $sqlResult  = " SELECT ";
  $sqlResult .= " `seo_kwid`, ";
  $sqlResult .= " `seo_keyword` ";
  $sqlResult .= " FROM ";
  $sqlResult .= " `vtiger_service` ";
  $sqlResult .= " LEFT JOIN `vtiger_inventoryproductrel` ON `vtiger_service`.`serviceid` = `vtiger_inventoryproductrel`.`productid` ";
  $sqlResult .= " WHERE TRUE  ";
#  $sqlResult .= "  AND `expiry_date` != '' ";
  $sqlResult .= "  AND `seo_kwid` != 0 ";
#  $sqlResult .= "  AND `vtiger_inventoryproductrel`.`id` = 24963 ";  
#  $sqlResult .= "  GROUP BY `vtiger_salesorder`.`salesorderid`";

  print $sqlResult . "<br />";

  $result = $adb->pquery($sqlResult, array());
  $no_of_service = $adb->num_rows($result);

  for($i=0; $i<$no_of_service;$i++) {

    $rowService =  $adb->fetch_array($result);
    $arrService[$rowService[seo_kwid]] = $rowService[seo_keyword];

    $arrKikanKwId[$rowService[seo_kwid]] = $rowService[seo_keyword];
  }//End for

  foreach($arrJasKwId as $kwId => $arrJasKwIdValue) {
  	if($arrKikanKwId[$kwId] == false) {
  		print $kwId . " : " . $arrJasKwIdValue[keyword] . " : " . $arrJasKwIdValue[client] . "<br />";
  	}//End if
  }//End foreach


  foreach ($arrService as $kwid => $strKeyWord) {

  	$strUpdateService  = " UPDATE `vtiger_service` SET ";
    $strUpdateService .= " `seo_keyword` = '{$arrContactDate[$kwid][seo_keyword]}', ";
    $strUpdateService .= " `start_date`  = '{$arrContactDate[$kwid][start_date]}', ";
    $strUpdateService .= " `expiry_date` = '{$arrContactDate[$kwid][expiry_date]}', ";
    $strUpdateService .= " `seo_segment` = '{$arrContactDate[$kwid][seo_segment]}', ";
    $strUpdateService .= " `seo_url` = '{$arrContactDate[$kwid][seo_url]}', ";
    $strUpdateService .= " `seo_rank_check` = '{$arrContactDate[$kwid][seo_rank_check]}', ";
    $strUpdateService .= " `seo_fee_method` = '{$arrContactDate[$kwid][seo_fee_method]}', ";
    $strUpdateService .= " `seo_rank_daynum` = '{$arrContactDate[$kwid][seo_rank_daynum]}', ";

    if($arrContactDate[$kwid][seo_fee_method] === "日毎どちらか課金" || $arrContactDate[$kwid][seo_fee_method] === "n日どちらか課金") {
      $strUpdateService .= " `seo_rank_top_1` = '{$arrRankFee[$kwid][seo_rank_top_1]}', ";
      $strUpdateService .= " `seo_rank_down_1` = '{$arrRankFee[$kwid][seo_rank_down_1]}', ";
      $strUpdateService .= " `seo_fee_1` = '{$arrRankFee[$kwid][seo_fee_1]}', ";
      $strUpdateService .= " `seo_rank_top_2` = '{$arrRankFee[$kwid][seo_rank_top_2]}', ";
      $strUpdateService .= " `seo_rank_down_2` = '{$arrRankFee[$kwid][seo_rank_down_2]}', ";
      $strUpdateService .= " `seo_fee_2` = '{$arrRankFee[$kwid][seo_fee_2]}', ";
      $strUpdateService .= " `seo_rank_top_3` = '{$arrRankFee[$kwid][seo_rank_top_3]}', ";
      $strUpdateService .= " `seo_rank_down_3` = '{$arrRankFee[$kwid][seo_rank_down_3]}', ";
      $strUpdateService .= " `seo_fee_3` = '{$arrRankFee[$kwid][seo_fee_3]}', ";
      $strUpdateService .= " `seo_rank_top_4` = '{$arrRankFee[$kwid][seo_rank_top_4]}', ";
      $strUpdateService .= " `seo_rank_down_4` = '{$arrRankFee[$kwid][seo_rank_down_4]}', ";
      $strUpdateService .= " `seo_fee_4` = '{$arrRankFee[$kwid][seo_fee_4]}', ";
      $strUpdateService .= " `seo_rank_top_5` = '{$arrRankFee[$kwid][seo_rank_top_5]}', ";
      $strUpdateService .= " `seo_rank_down_5` = '{$arrRankFee[$kwid][seo_rank_down_5]}', ";
      $strUpdateService .= " `seo_fee_5` = '{$arrRankFee[$kwid][seo_fee_5]}', ";
    }//End if

    $strUpdateService .= " `division` = '202:Webコミュ', ";

    $strUpdateService .= " `seo_away_flag` = '{$arrContactDate[$kwid][seo_away_flag]}' ";

  	$strUpdateService .= " WHERE TRUE ";
  	$strUpdateService .= " AND  seo_kwid = {$kwid} ";

    require('SQLConnect.php');

  	print $strUpdateService . "<br />";

#    print "コメントアウト：更新されません。<br />";

   $result1 = mysql_db_query($dbName, $strUpdateService);

#    $result1 = $adb->pquery($strUpdateService, array());

   	if($result1 == true) {
    	print "更新<br />";
    } else {
    	print "失敗<br />";
    }//End if

    #exit;

  }//End foreach


  print "<p>完了しました。</p>";