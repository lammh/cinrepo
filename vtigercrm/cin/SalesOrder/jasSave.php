<?php
//151118修正 大熊
print "<pre>";
var_dump($_POST);
print "</pre>";

require_once('cin/defalut.php');
require_once('cin/common/client_common.php');	#顧客企業の登録・更新ファイル
require_once('cin/common/charger_common.php');	#顧客担当者の登録・更新ファイル
require_once('cin/common/keyword_common.php');	#キーワードの登録・更新ファイル

//■2015/07/21 mod ohkuma JAS　rank_keyword rank_feeへのInsertとUpdate -----------------------------------------------------------------▼
#Module : SalesOrder _ 
/** ▼ 受注案件情報(vtiger_salesorder) **********/
//受注案件情報の取得(基幹)
$queryItem  = " SELECT ";
$queryItem .= " `vtiger_account`.`jgid` AS rcl_id , ";	#JASのクライアントID
$queryItem .= " `vtiger_account`.`accountname`, ";	#クライアント名
$queryItem .= " `vtiger_account`.`accountid`, ";	#クライアント名
$queryItem .= " `vtiger_contactdetails`.`rcm_id`, ";	#JASの担当者ID
$queryItem .= " `vtiger_contactdetails`.`contactid`, ";	#基幹顧客担当者ID
$queryItem .= " `vtiger_salesorder`.`submission_date`AS rkw_resistdate, ";	#JASの申込み日
$queryItem .= " `vtiger_salesorder`.`update_interval`AS rkw_contract_term ";	#JASの契約期間
$queryItem .= " FROM `vtiger_salesorder` ";
$queryItem .= " LEFT JOIN `vtiger_contactdetails` ON `vtiger_salesorder`.`contactid` = `vtiger_contactdetails`.`contactid` ";
$queryItem .= " LEFT JOIN `vtiger_account` ON `vtiger_salesorder`.`accountid` = `vtiger_account`.`accountid` ";
$queryItem .= " WHERE TRUE ";
$queryItem .= " AND salesorderid = {$salesorderid} ";

print "受注案件情報の取得 : ".$queryItem . "<br /><br />";
#exit;
global $adb;

$resultItem=$adb->pquery($queryItem);
$countItemNum = $adb->num_rows($resultItem);
#if($countItemNum === 0) continue;
$rowItem =  $adb->fetch_array($resultItem);

/*** 会社と顧客担当者とKW情報とのひもづけ ******/

//登録日(申込日)
#$rkw_resistdate = $rowItem['rkw_resistdate'];

//更新間隔
$rkw_contract_term = $rowItem['rkw_contract_term'];
if($rkw_contract_term === '0:なし') $rkw_contract_term = 0;

//顧客企業ID(accountid)
//$rowItem['rcl_id']の値があるかどうか
if((int)$rowItem['rcl_id'] !== 0) {	#JASのクライアントIDがある場合
	$rkw_rclid = $rowItem['rcl_id'];
} else {	#JASのクライアントIDがない場合
	$rkw_rclid = jas_account_save($rowItem['accountid']);
}//End if

$rkw_client = $rowItem['accountname'];
#exit;
//顧客担当者ID(contactid)
//$rowItem['rcm_id']の値があるかどうか
if((int)$rowItem['rcm_id'] !== 0) {	#担当者IDが保存されているとき
	$rkw_rcmid = $rowItem['rcm_id'];
} elseif((int)$rowItem['contactid'] !== 0) {	#担当者IDがない場合
	//JASに担当者情報を保存する
	$rkw_rcmid = jas_contact_save($rowItem['contactid']);
} else {
	$rkw_rcmid = 0;
}//End if

/** ▼ キーワードIDの取得 ***/
$queryItem2   = " SELECT ";
$queryItem2  .= " `vtiger_products`.`seo_kwid` AS rkw_id_1, ";
$queryItem2  .= " 	`vtiger_products`.`productcategory`, ";
$queryItem2  .= " 	`vtiger_service`.`seo_kwid` AS rkw_id_2, ";
$queryItem2  .= " 	`vtiger_service`.`servicecategory`, ";
$queryItem2  .= " 	`vtiger_inventoryproductrel`.`productid` AS item_id ";
$queryItem2  .= " FROM  ";
$queryItem2  .= "  `vtiger_inventoryproductrel` ";
$queryItem2  .= "   LEFT JOIN `vtiger_products`  ON  ";
$queryItem2  .= "   `vtiger_inventoryproductrel`.`productid` = `vtiger_products`.`productid` ";
$queryItem2  .= "   LEFT JOIN `vtiger_service`  ON ";
$queryItem2  .= "   `vtiger_inventoryproductrel`.`productid` = `vtiger_service`.`serviceid` ";
$queryItem2  .= " WHERE TRUE ";
$queryItem2  .= " AND `vtiger_inventoryproductrel`.`id` = '{$salesorderid}' "; 
#$queryItem2  .= " AND (`vtiger_service`.`seo_kwid` != 0 OR `vtiger_products`.`seo_kwid` != 0 )  ";

print $queryItem2 . "<br /><br />";

#exit;

$resultItem2=$adb->pquery($queryItem2);
$countItemNum2 = $adb->num_rows($resultItem2);
#if($countItemNum === 0) continue;

for($intI=0;$intI<$countItemNum2;$intI++) {

	$rowItem2 =  $adb->fetch_array($resultItem2);

	if($rowItem2['productcategory'] === 'SEO') {
		$arrKwId[$intI]['kw_id'] = $rowItem2['rkw_id_1'];
		$arrKwId[$intI]['type'] = "products";
		$arrKwId[$intI]['item_id'] = $rowItem2['item_id'];
	}//End if

	if($rowItem2['servicecategory'] === 'SEO') {
		$arrKwId[$intI]['kw_id'] = $rowItem2['rkw_id_2'];
		$arrKwId[$intI]['type'] = "service";
		$arrKwId[$intI]['item_id'] = $rowItem2['item_id'];
	}//End if

}//End for
/** ▲ キーワードIDの取得 ***/

print "<p>製品内容</p>";

print "<pre>";
var_dump($arrKwId);
print "</pre>";

//JASへの反映
foreach($arrKwId as $intKey => $arrKwIdValue) {

	switch($arrKwIdValue['type']) {

		case 'products':	#製品固定のとき
			print "固定 : kwid : ". $arrKwIdValue[kw_id] . "<br /><br />";
			//製品固定のJASへの反映
			//1,productid 2,受注登録フラグ
			$intKwId = jas_products_save($arrKwIdValue['item_id'], true);
			break;

		case 'service':	#製品成果のとき
			print "成果 : kwid : ". $arrKwIdValue[kw_id] . "<br /><br />";
			//1,serviceid 2,受注登録フラグ
			$intKwId = jas_services_save($arrKwIdValue['item_id'], true);
			break;

	}//End switch

#exit;

	/* ▼ * JASのキーワードのクライアントID、クライアント名、顧客担当IDのひもづけ *******/
	if((int)$intKwId !== 0) {
		require('cin/SQLConnect_jas.php');

		/** ▼ ** キーワードの履歴を保存する ****/
		foreach($arrRkw as $rkw_fields_name => $strRkwValue) {
			unset($_POST[$rkw_fields_name]);
			$_POST[$rkw_fields_name] = $strRkwValue;
		}//End foreach

		$_POST[rkw_create_datetime] = date('Y-m-d H:i:s');
		print "Insert : " . DataInsertString('backup_keyword') . "<br /><br />";
		DataInsert('backup_keyword');

		//初期化
		foreach($arrRkw as $rkw_fields_name => $strRkwValue) {
			unset($_POST[$rkw_fields_name]);
		}//End foreach
		/** ▲ ** キーワードの履歴を保存する ****/

		require('cin/SQLConnect_jas.php');

		$sqlUpdate  = " UPDATE rank_keyword SET ";
		if($rkw_rclid) $sqlUpdate .= " rkw_rclid = {$rkw_rclid}, ";
		if($rkw_rcmid) $sqlUpdate .= " rkw_rcmid = {$rkw_rcmid}, ";
		if($rkw_client) $sqlUpdate .= " rkw_client = '{$rkw_client}', ";

		//受注に紐付いたKW全て反映されてしまう
#		if($rkw_contract_term) $sqlUpdate .= " rkw_contract_term = {$rkw_contract_term}, ";//契約更新間隔(登録のみ追加)

#		$sqlUpdate .= " rkw_resistdate = '{$rkw_resistdate}' ";
		$sqlUpdate .= " WHERE TRUE AND rkw_id = {$intKwId} ";

		print $sqlUpdate . "<br />";
		mysql_db_query($dbName, $sqlUpdate);

		require('cin/SQLClose.php');
	}//End if
	/* ▼ * クライアントID、クライアント名、顧客担当IDのひもづけ *******/

}//End foreach
/** ▲ 受注案件情報(vtiger_salesorder) **********/