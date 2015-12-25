<?php
/** vtiger:vtiger_products,vtiger_service → vtiger:vtiger_invoice_recurring_info:end_periodの一番新しい期間の更新 **/

#ini_set('include_path', '/var/www/html/vtigercrm_test');
ini_set('include_path', '/var/www/html/vtigercrm');
require_once('include/utils/utils.php');
require_once('include/logging.php');

  global $adb, $log;
  global $current_user;

  $sql  = " SELECT ";
  $sql .= " `vtiger_salesorder`.`salesorderid`, ";
  $sql .= " `vtiger_products`.`productid`,  ";
  $sql .= " MAX(`vtiger_products`.`expiry_date`) AS end_date ";
  $sql .= " FROM ";
  $sql .= " `vtiger_salesorder` "; 
  $sql .= " LEFT JOIN `vtiger_inventoryproductrel` ON `vtiger_salesorder`.`salesorderid` = `vtiger_inventoryproductrel`.`id` ";
  $sql .= " LEFT JOIN `vtiger_products` ON `vtiger_inventoryproductrel`.`productid` = `vtiger_products`.`productid`  ";
  $sql .= " WHERE TRUE  ";
  $sql .= "  AND `expiry_date` != '' ";
  $sql .= "  AND `vtiger_salesorder`.`division` = '202:Webコミュ' ";  
#  $sql .= "  AND `seo_kwid` != 0 ";
#  $sql .= "  AND `vtiger_salesorder`.`salesorderid` = 24893 ";  
  $sql .= "  GROUP BY `vtiger_salesorder`.`salesorderid`";

  print $sql."<br /><br />";

#exit;

  $result = $adb->pquery($sql, array());
  $no_of_salesorder = $adb->num_rows($result);

  for($i=0; $i<$no_of_salesorder;$i++) {

    $rowSalesOrder =  $adb->fetch_array($result);
    $arrMonthSeo[$rowSalesOrder[salesorderid]] = $rowSalesOrder[end_date];
  }//End for

  foreach($arrMonthSeo as $salesorderid => $contract_enddate) {
	  print "固定 受注ID : ".$salesorderid.", 契約終了日：".$contract_enddate."<br />";
    $arrContractEndDate[$salesorderid] = $contract_enddate;
  }//End foreach

  print "<br /><br />";

  $sqlResult  = " SELECT ";
  $sqlResult .= " `vtiger_salesorder`.`salesorderid`, ";
  $sqlResult .= " `vtiger_service`.`serviceid`,  ";
  $sqlResult .= " MAX(`vtiger_service`.`expiry_date`) AS end_date ";
  $sqlResult .= " FROM ";
  $sqlResult .= " `vtiger_salesorder` "; 
  $sqlResult .= " LEFT JOIN `vtiger_inventoryproductrel` ON `vtiger_salesorder`.`salesorderid` = `vtiger_inventoryproductrel`.`id` ";
  $sqlResult .= " LEFT JOIN `vtiger_service` ON `vtiger_inventoryproductrel`.`productid` = `vtiger_service`.`serviceid`  ";
  $sqlResult .= " WHERE TRUE  ";
  $sqlResult .= "  AND `expiry_date` != '' ";
  $sqlResult .= "  AND `vtiger_salesorder`.`division` != '203:サロコン' ";  
#  $sqlResult .= "  AND `seo_kwid` != 0 ";
#  $sqlResult .= "  AND `vtiger_salesorder`.`salesorderid` = 24893 ";  
  $sqlResult .= "  GROUP BY `vtiger_salesorder`.`salesorderid`";

  print $sqlResult . "<br />";

  $result_2 = $adb->pquery($sqlResult, array());
  $no_of_salesorder_2 = $adb->num_rows($result_2);

  for($i=0; $i<$no_of_salesorder_2;$i++) {

    $rowSalesOrder2 =  $adb->fetch_array($result_2);
    $arrResultSeo[$rowSalesOrder2[salesorderid]] = $rowSalesOrder2[end_date];
  }//End for

  foreach($arrResultSeo as $salesorderid => $contract_enddate) {
	  print "成果　受注ID : ".$salesorderid.", 契約終了日：".$contract_enddate."<br />";
	  if($arrContractEndDate[$salesorderid] == true) {	#成果あり
	  	//日付の比較 
	  	if($contract_enddate >= $arrContractEndDate[$salesorderid]) { #固定の終了日が成果の終了日よりあとのとき
		  	$arrContractEndDate[$salesorderid] = $contract_enddate;
		  }//End if
		} else {
        $arrContractEndDate[$salesorderid] = $contract_enddate;
    }//End if
  }//End foreach

  var_dump($arrContractEndDate);
  
  print "<br />============================================<br />";



  foreach($arrContractEndDate as $salesorderid => $contract_enddate) {

    if($contract_enddate == false) continue; 
	  #print "受注ID : ".$salesorderid.", 契約終了日：".$contract_enddate."<br />";

    $sqlUpdate  = " UPDATE `vtiger_invoice_recurring_info` SET ";
    $sqlUpdate .= " end_period = '".$contract_enddate."' ";
    $sqlUpdate .= " WHERE TRUE ";
    $sqlUpdate .= " AND salesorderid = ".$salesorderid.";";

    require('SQLConnect.php');

    print $sqlUpdate."<br />";

#    print "コメントアウト：UPDATEされません。<br />";

    $result1 = mysql_db_query($dbName, $sqlUpdate);
    #$result1 = $adb->pquery($sqlUpdate, array());

    if($result1 == true) {
      print "更新<br />";
    } else {
      print "失敗<br />";
    }//End if

  }//End foreach

print "完了しました。";