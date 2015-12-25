<?php
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/

set_time_limit(0);

ini_set('include_path', '/var/www/html/vtigercrm');
require_once('include/utils/utils.php');
require_once('include/logging.php');

if($_GET[date]) {
	$_POST[month] = date('Y-m', strtotime($_GET[date]));
	$_POST[first_date] = $_POST[month]."-01";
	$_POST[end_date] = date('Y-m-t', strtotime($_POST[first_date]));
} else {
	print "日付が指定されていません。";
	exit;
}//End if

$arrDivision = array('101:求人広告' => '101:求人広告', '103:人材派遣' => '103:人材派遣', '201:風評コンサル' => '201:風評コンサル', '202:Webコミュ' => '202:Webコミュ', '203:サロコン' => '203:サロコン', '204:Eコマース');

if($_GET['division'] == true && $arrDivision[$_GET['division']] == true) {
	$_POST['division'] = $_GET['division'];
} else {
	print "事業部が選択されてません。<br /><br />";
	foreach($arrDivision as $strDivision) {
		print $strDivision."<br />";
	}//End foreach
	exit;
}//End if

$_GET[salesorder_id] = (int)$_GET[salesorder_id];

if($_GET[salesorder_id]) {
	$intSalesorderId = $_GET[salesorder_id];
} else {
	$intSalesorderId = 0;
}//End if

#global $adb, $log;
#$log =& LoggerManager::getLogger('RecurringInvoice');
#$log->debug("invoked RecurringInvoice");

global $VTIGER_BULK_SAVE_MODE;
$VTIGER_BULK_SAVE_MODE = true;

print "<p>請求書作成 開始</p>";

print "<p>請求書詳細追加</p>";
listing_bill_detail($intSalesorderId);

deleteInvoice($intSalesorderId);	#指定した月の請求書の削除

print "<p>請求書作成 削除完了</p>";

print "<p>請求書作成 開始</p>";

invoiceDataCreate($intSalesorderId);	#請求書の作成
#$intSalesorderId=20289;
print "<p>請求書作成　完了</p>";



if($_POST[division] === '202:Webコミュ') {
	print "<p>SEO成果・固定請求金額の計算　開始</p>";

	chargeCreate($intSalesorderId);

	print "<p>SEO成果・固定請求金額の計算　完了</p>";

	totalFeeUpdate($intSalesorderId);
}//End if

if($_POST[division] === '203:サロコン') {
	scInvoice($intSalesorderId);
}//End if

print "<p>完了しました。</p>";

/** START 請求金額の合計の反映 ***/
function totalFeeUpdate($salesorder_id) {

	require_once('modules/Invoice/Invoice.php');

	global $adb, $log;
	global $current_user;

	$arrInvoiceFee = array();

	$sql  = " SELECT invoiceid, salesorderid, salesamount FROM  `vtiger_invoice` ";
	$sql .= " INNER JOIN `vtiger_crmentity` ON `invoiceid` = `crmid` ";
	$sql .= " WHERE TRUE ";
	if($salesorder_id !== 0) $sql .= " AND  `salesorderid` = {$salesorder_id} "; 
	$sql .= " AND  `division` = '{$_POST['division']}' ";
	$sql .= " AND  `invoicedate` >= '".$_POST[first_date]."' ";	#請求日
	$sql .= " AND  `invoicedate` <= '".$_POST[end_date]."' ";	#請求日
	$sql .= " AND  `deleted` = 0 ";
	$sql .= " AND  `invoicestatus` = '自動作成' ";

	print $sql . "<br />";

	$result = $adb->pquery($sql, array());
	$no_of_invoice = $adb->num_rows($result);

	unset($invoice_id);
	unset($intTotalInventFee);
	
	for($i=0;$i<$no_of_invoice;$i++) {
		$invoice_id = $adb->query_result($result, $i,'invoiceid');
		$salesamount = $adb->query_result($result, $i,'salesamount');
		$arrInvoiceFee[$invoice_id] = $salesamount;
	}//End for

  $focus = new Invoice();

  /** ▼ ** 請求金額合計のデータ更新 ********/
  foreach($arrInvoiceFee as $intInvoiceId => $intTotalInventFee) {

    $updateInvoice  = " UPDATE "; 
    $updateInvoice .= " vtiger_invoice, "; 
    $updateInvoice .= " ( ";
    $updateInvoice .= "   SELECT "; 
    $updateInvoice .= "     SUM( listprice - COALESCE(discount_amount, 0) ) AS sum_fee, ";   #合計金額(税抜)
#   $updateInvoice .= "     SUM( listprice) AS sum_fee, ";   #合計金額(税抜)
    $updateInvoice .= "     SUM( (listprice - COALESCE(discount_amount, 0) ) * ( 1 + (tax4 * 0.01) ) ) AS tax_fee ";  #合計(税込)
#   $updateInvoice .= "     SUM( listprice * ( 1 + (tax4 * 0.01) ) ) AS tax_fee ";  #合計(税込)
    $updateInvoice .= "   FROM vtiger_inventoryproductrel ";
    $updateInvoice .= "   WHERE TRUE AND id = {$intInvoiceId} ";
    $updateInvoice .= "   GROUP BY id ";
    $updateInvoice .= " ) AS vtiger_inventoryproductrel_2 "; 
    $updateInvoice .= " SET  ";
    $updateInvoice .= "   vtiger_invoice.salesamount = vtiger_inventoryproductrel_2.sum_fee, ";  #売上額（総合計から保存時設定）
    $updateInvoice .= "   vtiger_invoice.amount = vtiger_inventoryproductrel_2.sum_fee, ";       #粗利（保存時自動設定）
    $updateInvoice .= "   vtiger_invoice.subtotal = vtiger_inventoryproductrel_2.sum_fee, ";      #アイテム合計(税抜)
    $updateInvoice .= "   vtiger_invoice.balance = vtiger_inventoryproductrel_2.tax_fee, ";       #バランス(税込)
    $updateInvoice .= "   vtiger_invoice. total = vtiger_inventoryproductrel_2.tax_fee ";         #総合計(税込)

    $updateInvoice .= " WHERE TRUE AND invoiceid = {$intInvoiceId}; ";

    #print "invoiceid_".$intInvoiceId."<br /><br />";

    print "updateInvoice : " . $updateInvoice . "<br /><br />";

    $adb->pquery($updateInvoice);

    ob_flush();
    flush();

  }//End foreach
  /** ▲ ** 請求金額合計のデータ更新 ********/

	$arrInvoiceFee = array();

	$sql  = " SELECT invoiceid, salesorderid, salesamount FROM  `vtiger_invoice` ";
	$sql .= " INNER JOIN `vtiger_crmentity` ON `invoiceid` = `crmid` ";
	$sql .= " WHERE TRUE ";
	$sql .= " AND  `division` = '{$_POST['division']}' ";
	if($salesorder_id !== 0) $sql .= " AND  `salesorderid` = {$salesorder_id} ";
	$sql .= " AND  `invoicedate` >= '".$_POST[first_date]."' ";	#請求日
	$sql .= " AND  `invoicedate` <= '".$_POST[end_date]."' ";	#請求日
	$sql .= " AND  `deleted` = 0 ";
	$sql .= " AND  `invoicestatus` = '自動作成' "; 

	print $sql . "<br />";

	$result = $adb->pquery($sql, array());
	$no_of_invoice = $adb->num_rows($result);

	unset($invoice_id);
	unset($intTotalInventFee);

	for($i=0;$i<$no_of_invoice;$i++) {
		$invoice_id = $adb->query_result($result, $i,'invoiceid');
		$intTotalInventFee = $adb->query_result($result, $i,'salesamount');

    //金額が０のチェックと金額が0の場合は削除する
  	if((int)$intTotalInventFee === 0) {
  		print "削除されました。<br /><br />";
  	  $focus->trash("Invoice", $invoice_id);
  	  #exit;
  	  continue;
  	}//End if

	}//End for

}//End function
/** END 請求金額の合計の反映 ***/

function chargeCreate($salesorder_id=0) {
	require_once('cin/chargeMake.php');
	chargeMake($salesorder_id);
}//End function chargeCreate

function invoiceDataCreate($salesorder_id=0) {
	// Get the list of Invoice for which Recurring is enabled.

	global $adb, $log;
	$log =& LoggerManager::getLogger('RecurringInvoice');
	$log->debug("invoked RecurringInvoice");

	$sqlApproval =" AND sostatus = '10.承認済み' ";
  	$sqlApproval.=" AND ( ";
  	$sqlApproval.=" carrier = '契約' ";
  	$sqlApproval.=" OR ";
  	$sqlApproval.=" carrier = '契約変更' ";
  	$sqlApproval.=" )";
	$sqlApproval.=" AND (hr_claim_flag = 0 OR hr_claim_flag is null) ";


	/* ▼ ** ショット請求書作成 ***************/
	$sql =" SELECT ";
	$sql.=" vtiger_salesorder.salesorderid, vtiger_salesorder.subject ";
	$sql.=" FROM vtiger_salesorder ";
	$sql.=" INNER JOIN vtiger_crmentity AS crmentity_salesorder ON vtiger_salesorder.salesorderid = crmentity_salesorder.crmid AND crmentity_salesorder.deleted = 0 ";

	$sql .=" left join ( ";
	$sql .="   SELECT `salesorderid`, `invoicedate`, `invoicestatus` FROM `vtiger_invoice`  ";
	$sql .="   inner join `vtiger_crmentity` AS `crmentity_invoice` ON `vtiger_invoice`.`invoiceid` = `crmentity_invoice`.`crmid` AND `crmentity_invoice`.`deleted` = 0  ";
	$sql .="   WHERE TRUE  ";
	$sql .="   AND `vtiger_invoice`.`invoicedate` <= '".$_POST[end_date]."' ";
	$sql .="   AND `vtiger_invoice`.`invoicedate` >= '".$_POST[first_date]."'  ";
	$sql .=" ) `vtiger_invoice` ON `vtiger_salesorder`.`salesorderid` = `vtiger_invoice`.`salesorderid`   ";

	$sql.=" WHERE TRUE ";
	if($salesorder_id !== 0) $sql .= " AND vtiger_salesorder.salesorderid = {$salesorder_id} ";	#受注ID(salesorderid)
	$sql.= "AND  `vtiger_salesorder`.`division` = '{$_POST['division']}' ";
	$sql.=" AND submission_date <= '".$_POST[end_date]."' ";	#申込日
	$sql.=" AND submission_date >= '".$_POST[first_date]."' ";	#申込日
	$sql.=" AND enable_recurring = 0 ";	#ストック計上有効 > 無効
	$sql.=" AND ( `vtiger_invoice`.`invoicestatus`  NOT IN ('上長確認済', '営業確認済')  OR `vtiger_invoice`.`invoicestatus` is null) ";
	$sql.=$sqlApproval;

  	print "ショット案件<br />";
	print "sql : ".$sql."<br /><br />";

	$result = $adb->pquery($sql, array());
	$no_of_salesorder = $adb->num_rows($result);

	for($i=0; $i<$no_of_salesorder;$i++) {

		$rowSalesOrder =  $adb->fetch_array($result);
		$intSalesorderId = $rowSalesOrder[salesorderid];

		print "<p>ID : ".$intSalesorderId."</p>";

		createInvoice($intSalesorderId);

	  	ob_flush();
    	flush();
		
	}//End for
	/* ▲ ** ショット請求書作成 ***************/

	/* ▼ ** 固定(ストック)・成果 請求書作成 ***************/
	/*
	$sql =" SELECT ";
	$sql.=" vtiger_salesorder.salesorderid, vtiger_salesorder.subject, recurring_frequency, start_period, end_period, last_recurring_date, `invoicedate`, ";
	$sql.=" payment_duration_values, invoice_status FROM vtiger_salesorder ";
	$sql.=" INNER JOIN vtiger_crmentity AS crmentity_salesorder ON vtiger_salesorder.salesorderid = crmentity_salesorder.crmid AND crmentity_salesorder.deleted = 0 ";
	$sql.=" INNER JOIN vtiger_invoice_recurring_info ON vtiger_salesorder.salesorderid = vtiger_invoice_recurring_info.salesorderid ";


	$sql.=" INNER JOIN vtiger_invoice ON vtiger_salesorder.salesorderid = vtiger_invoice.salesorderid AND vtiger_invoice.invoicestatus NOT IN ('上長確認済', '営業確認済') ";
	$sql.=" INNER JOIN vtiger_crmentity AS crmentity_invoice    ON vtiger_invoice.invoiceid = crmentity_invoice.crmid AND crmentity_invoice.deleted = 0 ";


	$sql.=" WHERE TRUE ";
	if($salesorder_id !== 0) $sql .= " AND vtiger_salesorder.salesorderid = {$salesorder_id} ";	#受注ID(salesorderid)
	$sql.=" AND DATE_FORMAT(start_period,'%Y-%m-%d') <= '".$_POST[end_date]."' ";	#契約開始日 
	$sql.=" AND DATE_FORMAT(end_period,'%Y-%m-%d') >= '".$_POST[first_date]."' ";	#契約終了日
 	$sql.=" AND enable_recurring = 1 ";	#ストック計上有効 > 有効
	$sql.=$sqlApproval;
*/
	$sql  =" SELECT  ";
	$sql .=" 	`vtiger_salesorder`.`salesorderid`, `vtiger_salesorder`.`subject`, `recurring_frequency`, `start_period`, `end_period`, `last_recurring_date` ";
	$sql .=" 	, `vtiger_invoice`.`invoicedate`, `vtiger_invoice`.`invoicestatus`  ";
	$sql .=" FROM `vtiger_salesorder` ";

	$sql .=" inner join `vtiger_crmentity` AS `crmentity_salesorder` ON `vtiger_salesorder`.`salesorderid` = `crmentity_salesorder`.`crmid` AND `crmentity_salesorder`.`deleted` = 0  ";
	$sql .=" inner join `vtiger_invoice_recurring_info` ON `vtiger_salesorder`.`salesorderid` = `vtiger_invoice_recurring_info`.`salesorderid`   ";

	$sql .=" left join ( ";
	$sql .="   SELECT `salesorderid`, `invoicedate`, `invoicestatus` FROM `vtiger_invoice`  ";
	$sql .="   inner join `vtiger_crmentity` AS `crmentity_invoice` ON `vtiger_invoice`.`invoiceid` = `crmentity_invoice`.`crmid` AND `crmentity_invoice`.`deleted` = 0  ";
	$sql .="   WHERE TRUE  ";
	$sql .="   AND `vtiger_invoice`.`invoicedate` <= '".$_POST[end_date]."' ";
	$sql .="   AND `vtiger_invoice`.`invoicedate` >= '".$_POST[first_date]."'  ";
	$sql .=" ) `vtiger_invoice` ON `vtiger_salesorder`.`salesorderid` = `vtiger_invoice`.`salesorderid`   ";

	$sql .=" WHERE TRUE  ";
	if($salesorder_id !== 0) $sql .=" AND `vtiger_salesorder`.`salesorderid` = {$salesorder_id}  ";
	$sql.= "AND `vtiger_salesorder`.`division` = '{$_POST['division']}' ";
	$sql.=" AND DATE_FORMAT(start_period,'%Y-%m-%d') <= '".$_POST[end_date]."' ";	#契約開始日 
	$sql.=" AND DATE_FORMAT(end_period,'%Y-%m-%d') >= '".$_POST[first_date]."' ";	#契約終了日
	$sql .=" AND ( `vtiger_invoice`.`invoicestatus`  NOT IN ('上長確認済', '営業確認済')  OR `vtiger_invoice`.`invoicestatus` is null) ";
	$sql .=$sqlApproval;

  print "ストック案件<br />";
	print "sql : ".$sql."<br /><br />";
#exit;

	$result = $adb->pquery($sql, array());
	$no_of_salesorder = $adb->num_rows($result);

	for($i=0; $i<$no_of_salesorder;$i++) {

		$rowSalesOrder =  $adb->fetch_array($result);
		$intSalesorderId = $rowSalesOrder[salesorderid];

#		if(($rowSalesOrder[invoicedate] >= $_POST[first_date] AND $rowSalesOrder[invoicedate] <= $_POST[end_date]) == false) continue;

		print "<p>ID : ".$intSalesorderId."</p>";

		createInvoice($intSalesorderId);

	  	ob_flush();
    	flush();
		
	}//End for
	/* ▼ ** 固定(ストック)・成果 請求書作成 ***************/	
	
}//End function

/* Function to create a new Invoice using the given Sales Order id */
function createInvoice($salesorder_id) {
	require_once('include/utils/utils.php');
	require_once('modules/SalesOrder/SalesOrder.php');
	require_once('modules/Invoice/Invoice.php');
	require_once('modules/Accounts/Accounts.php');
	require_once('modules/Users/Users.php');

	global $log, $adb;
	global $current_user;

	// Payment duration in days
	$payment_duration_values = Array(
    'net 01 day' => '1',
    'net 05 days' => '5',
    'net 07 days' => '7',
    'net 10 days' => '10',
    'net 15 days' => '15',
	'net 30 days' => '30',
	'net 45 days' => '45',
	'net 60 days' => '60'
	);

	if(!$current_user) {
		$current_user = Users::getActiveAdminUser();
	}//End if

	$so_focus = new SalesOrder();
	$so_focus->id = $salesorder_id;
	$so_focus->retrieve_entity_info($salesorder_id,"SalesOrder");

  //SalesOrderのデータ抽出
	foreach($so_focus->column_fields as $fieldname=>$value) {
#	print $fieldname . " : " . $value. "<br />";
		$so_focus->column_fields[$fieldname] = decode_html($value);
	}//End foreach

	$account_id = $so_focus->column_fields[account_id];
	
	/** ▼ ** 顧客情報呼び出し ****/
	$ac_focus = new Accounts();
	$ac_focus->id = $account_id;

#	print "アカウントID : ".$account_id."<br />";

	$ac_focus->retrieve_entity_info($account_id, "Accounts");

	foreach ($ac_focus->column_fields as $fieldname=>$value) {
		$ac_focus->column_fields[$fieldname] = decode_html($value);
	}//End foreach
	/** ▲ ** 顧客情報呼び出し ****/

	$strPayDay = $ac_focus->column_fields[payday];

	$focus = new Invoice();
	// This will only fill in the basic columns from SO to Invoice and also Update the SO id in new Invoice
	$focus = getConvertSoToInvoice($focus,$so_focus,$salesorder_id);
	
	switch ($strPayDay) {
		case '翌々月10日(40日)':
			$durationinsec = date('Y-m', strtotime('+2 month'.$_POST[first_date]))."-10";
			break;
		case '翌々月15日(45日)':
			$durationinsec = date('Y-m', strtotime('+2 month'.$_POST[first_date]))."-15";
			break;
		case '翌々月末日(60日)':
			$durationinsec = date('Y-m-t', strtotime('+2 month'.$_POST[first_date]));
			break;
		case '3ヶ月後10日(100日)':
			$durationinsec = date('Y-m', strtotime('+3 month'.$_POST[first_date]))."-10";
			break;
		case '翌月10日':
			$durationinsec = date('Y-m', strtotime('+1 month'.$_POST[first_date]))."-10";
			break;
		case '翌月15日':
			$durationinsec = date('Y-m', strtotime('+1 month'.$_POST[first_date]))."-15";
			break;
		case '翌月20日':
			$durationinsec = date('Y-m', strtotime('+1 month'.$_POST[first_date]))."-20";
			break;
		case '翌月末(30日)':
			$durationinsec = date('Y-m-t', strtotime($_POST[first_date] . ' +1 month'));
			break;
	}//End switch
	
	// Pick up the Payment due date based on the Configuration in SO
#	$payment_duration = $so_focus->column_fields['payment_duration'];
#	$due_duration = $payment_duration_values[trim(strtolower($payment_duration))];
#	$durationinsec = mktime(0,0,0,date('m'),date('d')+$due_duration,date('Y'));

	// Cleanup focus object, to duplicate the Invoice.
	$focus->id = '';
	$focus->mode = '';
#	$focus->column_fields['invoicestatus'] = $so_focus->column_fields['invoicestatus'];
	$focus->column_fields['invoicestatus'] = "自動作成";
	$focus->column_fields['closing_week'] = $so_focus->column_fields['closing_week'];
	$focus->column_fields['invoicedate'] = date('Y-m-t', strtotime($_POST[first_date]));
#	$focus->column_fields['duedate'] = date('Y-m-d', $durationinsec);

	if(preg_match('@\-12\-31@', $durationinsec)) {
		$focus->column_fields['duedate'] = '2015-12-28';
	} else {
		$focus->column_fields['duedate'] = $durationinsec;
	}//End if

	print "<p>受注実績番号 : ".$so_focus->column_fields['salesorder_no']."</p>";

	$focus->column_fields['salesorder_no'] = $so_focus->column_fields['salesorder_no']; #受注実績番号

	$focus->column_fields['salesamount'] = $so_focus->column_fields['salesamount']; #売上
	$focus->column_fields['amount'] = $so_focus->column_fields['amount']; #粗利
	if(preg_match('@\((.*?)分\)|（(.*?)分）@', $so_focus->column_fields['description'])) {
		$focus->column_fields['description'] = $so_focus->column_fields['description'];
	} else {
		$focus->column_fields['description'] = "";
	}//End if

	// Additional SO fields to copy -> Invoice field name mapped to equivalent SO field name
	$invoice_so_fields = Array (
		'txtAdjustment' => 'txtAdjustment',
		'hdnSubTotal' => 'hdnSubTotal',
		'hdnGrandTotal' => 'hdnGrandTotal',
		'hdnTaxType' => 'hdnTaxType',
		'hdnDiscountPercent' => 'hdnDiscountPercent',
		'hdnDiscountAmount' => 'hdnDiscountAmount',
		'hdnS_H_Amount' => 'hdnS_H_Amount',
		'assigned_user_id' => 'assigned_user_id',
		'currency_id' => 'currency_id',
		'conversion_rate' => 'conversion_rate',
		'division' => 'division',
		'pre_tax_total' => 'pre_tax_total',
		'balance' => 'hdnGrandTotal',
		'margin_rate' => 'margin_rate'
	);
#	foreach($invoice_so_fields as $invoice_field => $so_field) {
#		print $so_field." : ".$so_focus->column_fields[$so_field]."<br />";
#	}//End foreach

	foreach($invoice_so_fields as $invoice_field => $so_field) {
		$focus->column_fields[$invoice_field] = $so_focus->column_fields[$so_field];
	}//End foreach

	$focus->_salesorderid = $salesorder_id;
	$focus->_recurring_mode = 'recurringinvoice_from_so';
	$focus->save("Invoice");

}//End function


function deleteInvoice($salesorder_id=0) {

	#	require_once('include/utils/utils.php');
		require_once('modules/Invoice/Invoice.php');
	#	require_once('modules/Users/Users.php');

	global $adb, $log;
	global $current_user;

	if(!$current_user) {
			$current_user = Users::getActiveAdminUser();
	}//End if

	$log =& LoggerManager::getLogger('RecurringInvoice');
	$log->debug("invoked RecurringInvoice");

	$sql  = " SELECT invoiceid, salesorderid FROM  `vtiger_invoice` ";
	$sql .= " INNER JOIN `vtiger_crmentity` ON `invoiceid` = `crmid` ";
	$sql .= " WHERE TRUE ";
	if($salesorder_id !== 0) $sql .= " AND  `salesorderid` = {$salesorder_id} ";
	$sql .= " AND  `vtiger_invoice`.`division` = '{$_POST['division']}' ";
	$sql .= " AND  `invoicedate` >= '".$_POST[first_date]."' ";	#請求日
	$sql .= " AND  `invoicedate` <= '".$_POST[end_date]."' ";	#請求日
	$sql .= " AND  `deleted` = 0 ";
	$sql .= " AND  `invoicestatus` = '自動作成' "; 
	$sql .= " AND  `invoicestatus` NOT IN ('上長確認済','営業確認済') "; 

	print $sql . "<br />";
	
#	exit;

	$result = $adb->pquery($sql, array());
	$no_of_invoice = $adb->num_rows($result);

	$focus = new Invoice();

	for($i=0;$i<$no_of_invoice;$i++) {
		$invoice_id = $adb->query_result($result, $i,'invoiceid');
		$focus->trash("Invoice", $invoice_id);
#		print "<p>$invoice_id : 削除完了です。</p>";
	}//End for

}//End function deleteInvoice

/** サロコン 粗利反映 *******/
function scInvoice($salesorder_id) {

	print "<p>サロコン粗利反映</p>";

	require_once('modules/Invoice/Invoice.php');

	global $adb, $log;
	global $current_user;

	$arrInvoiceFee = array();

	$sql  = " SELECT invoiceid, salesorderid, salesamount, margin_rate FROM  `vtiger_invoice` ";
	$sql .= " INNER JOIN `vtiger_crmentity` ON `invoiceid` = `crmid` ";
	$sql .= " WHERE TRUE ";
	if($salesorder_id !== 0) $sql .= " AND  `salesorderid` = {$salesorder_id} ";
	$sql .= " AND `division` = '203:サロコン' ";
	$sql .= " AND  `invoicedate` >= '".$_POST[first_date]."' ";	#請求日
	$sql .= " AND  `invoicedate` <= '".$_POST[end_date]."' ";	#請求日
	$sql .= " AND  `deleted` = 0 ";
	$sql .= " AND  `invoicestatus` = '自動作成' "; 

	print $sql . "<br />";

	$result = $adb->pquery($sql, array());
	$no_of_invoice = $adb->num_rows($result);

	unset($invoice_id);
	unset($intTotalInventFee);
	
	for($i=0;$i<$no_of_invoice;$i++) {
		$invoice_id = $adb->query_result($result, $i,'invoiceid');
		$salesamount = $adb->query_result($result, $i,'salesamount');
		$margin_rate = $adb->query_result($result, $i,'margin_rate');
		$arrInvoiceFee[$invoice_id]['salesamount'] = (int)$salesamount;
		$arrInvoiceFee[$invoice_id]['margin_rate'] = (int)$margin_rate;
	}//End for

  $focus = new Invoice();

  /** ▼ ** 請求金額合計のデータ更新(サロコンのみ) ********/
  foreach($arrInvoiceFee as $intInvoiceId => $arrInvoiceFeeValue) {


#    	新規 		既存	
#10月	57.60%		27.00%	
#11月	57.20%	0.40%	26.60%	0.40%
#12月	56.70%	0.50%	25.70%	0.90%
#1月	56.30%	0.40%	25.30%	0.40%
#2月	55.90%	0.40%	24.90%	0.40%
#3月	55.50%	0.40%	24.50%	0.40%


  	if($arrInvoiceFeeValue['margin_rate'] === 0) {
	    $updateInvoice  = " UPDATE "; 
	    $updateInvoice .= " vtiger_invoice ";
	    $updateInvoice .= " SET  ";
	    $updateInvoice .= " vtiger_invoice.amount = vtiger_invoice.amount ";       #粗利（保存時自動設定）

	    $updateInvoice .= " WHERE TRUE AND invoiceid = {$intInvoiceId}; ";
  	} else {
	    $updateInvoice  = " UPDATE "; 
	    $updateInvoice .= " vtiger_invoice ";
	    $updateInvoice .= " SET  ";
	    $updateInvoice .= " vtiger_invoice.amount = vtiger_invoice.salesamount * (margin_rate / 100) ";       #粗利（保存時自動設定）

	    $updateInvoice .= " WHERE TRUE AND invoiceid = {$intInvoiceId}; ";
	  }//End if

    #print "invoiceid_".$intInvoiceId."<br /><br />";

    print "updateInvoice : " . $updateInvoice . "<br /><br />";

    #exit;
    $adb->pquery($updateInvoice);

    ob_flush();
    flush();

  }//End foreach
  /** ▲ ** 請求金額合計のデータ更新(サロコンのみ) ********/

}//End function

/** START リスティングの月分追加 *****/
function listing_bill_detail($salesorderid) {

  global $adb;

	$sqlApproval =" AND sostatus = '10.承認済み' ";
  $sqlApproval.=" AND ( ";
  $sqlApproval.=" carrier = '契約' ";
  $sqlApproval.=" OR ";
  $sqlApproval.=" carrier = '契約変更' ";
  $sqlApproval.=" )";
	$sqlApproval.=" AND (hr_claim_flag = 0 OR hr_claim_flag is null) ";

  $sql  = " SELECT `vtiger_products`.`productid`, `productname`, `comment`, `submission_date`, `bill_detail`, `productcategory` FROM  `vtiger_inventoryproductrel` ";
  $sql .= " INNER JOIN `vtiger_salesorder` ON `vtiger_inventoryproductrel`.`id` = `vtiger_salesorder`.`salesorderid` ";
  $sql .= " INNER JOIN `vtiger_crmentity` ON `vtiger_salesorder`.`salesorderid` = `vtiger_crmentity`.`crmid` ";
  $sql .= " INNER JOIN `vtiger_invoice_recurring_info` ON `vtiger_salesorder`.`salesorderid` = `vtiger_invoice_recurring_info`.`salesorderid` ";
  $sql .= " INNER JOIN `vtiger_products` ON `vtiger_inventoryproductrel`.`productid` = `vtiger_products`.`productid` ";
  $sql .= " WHERE TRUE ";
  if($salesorderid !== 0) $sql .= " AND  `vtiger_salesorder`.`salesorderid` = {$salesorderid} ";
  $sql .= " AND  `vtiger_salesorder`.`division` = '202:Webコミュ' ";

	$sql .= " AND ( ";
	$sql .= " ( submission_date <= '".$_POST[end_date]."' ";	#申込日
	$sql .= " AND submission_date >= '".$_POST[first_date]."' ";	#申込日
	$sql .= " AND enable_recurring = 0 )";	#ストック計上有効 > 無効
	$sql .= " OR ";
	$sql .= " ( DATE_FORMAT(start_period,'%Y-%m-%d') <= '".$_POST[end_date]."' ";	#契約開始日 
	$sql .= " AND DATE_FORMAT(end_period,'%Y-%m-%d') >= '".$_POST[first_date]."') ";	#契約終了日
	$sql .= " ) ";
 
  $sql .= " AND  `deleted` = 0 ";
  $sql .= " AND  `productcategory` = 'リスティング' ";

  print $sql . "<br />";

#exit;
  $result = $adb->pquery($sql, array());
  $no_of_products = $adb->num_rows($result);

  if($no_of_products === 0) return;

  for($i=0;$i<$no_of_products;$i++) {
  	$intProductId = (int)$adb->query_result($result, $i,'productid');
  	$strSubmissionDate = $adb->query_result($result, $i,'submission_date');
  	$strBillDetail = $adb->query_result($result, $i,'productname');

	  //申込み日が当月の場合
  	if($strSubmissionDate >= $_POST['first_date'] && $strSubmissionDate <= $_POST['end_date'] && (preg_match('@広告費@', $strBillDetail)) == true) {
	 		$arrBillDetailDate[$intProductId] = $strBillDetail." （".date('n', strtotime('+1 month'.$strSubmissionDate))."月分）";
  	} elseif($strSubmissionDate < $_POST['first_date'] && (preg_match('@広告費|運用費@', $strBillDetail)) == true ) {
	 		$arrBillDetailDate[$intProductId] = $strBillDetail." （".date('n', strtotime('+2 month'.$_POST[first_date]))."月分）";
		}//End if

  }//End for

	foreach($arrBillDetailDate as $intProductId => $strBillDetail) {
		$sqlUpdate = "UPDATE `vtiger_products` SET `bill_detail` = '".$strBillDetail."' WHERE productid = ".$intProductId.";";
#		print $sqlUpdate."<br />";
		$adb->pquery($sqlUpdate);
	}//End foreach

}//End function
/** END リスティングの月分追加 *****/
?>
