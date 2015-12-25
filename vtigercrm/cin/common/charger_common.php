<?php
//151118修正 大熊
function jas_contact_save($contactid) {

	//vtiger_account
	$query  = " SELECT ";
	$query .= " `firstname`, ";
	$query .= " `lastname`, ";
	$query .= " `title`, ";
	$query .= " `mailingzip`, ";
	$query .= " `mailingstate`, ";
	$query .= " `mailingcity`, ";
	$query .= " `mailingstreet`, ";
	$query .= " `otherzip`, ";
	$query .= " `otherstate`, ";
	$query .= " `othercity`, ";
	$query .= " `otherstreet`, ";
	$query .= " `vtiger_contactdetails`.`email`, ";
	$query .= " `vtiger_contactdetails`.`secondaryemail`, ";
	$query .= " `vtiger_contactdetails`.`phone`, ";
	$query .= " `vtiger_contactdetails`.`mobile`, ";
	$query .= " `vtiger_account`.`jgid` AS rcl_id, ";
	$query .= " `vtiger_account`.`accountid`, ";
	$query .= " `rcm_id`, ";
	$query .= " `accountname` FROM `vtiger_contactdetails` ";
	$query .= " LEFT JOIN `vtiger_contactaddress` ON contactid = contactaddressid ";
	$query .= " LEFT JOIN `vtiger_account` ON `vtiger_contactdetails`.`accountid` = `vtiger_account`.`accountid`";
	$query .= " WHERE TRUE ";
	$query .= " AND contactid = {$contactid} ";

	print $query."<br /><br />";

	global $adb;
	$result=$adb->pquery($query);
	$countNum = $adb->num_rows($result);
	$row =  $adb->fetch_array($result);

	$arrChargerMasData['rcm_name'] = $row['firstname'] ."　".$row['lastname'];
	$arrChargerMasData['rcm_post'] = $row['title'];

	$zip = ($row['mailingzip']) ? $row['mailingzip'] : $row['otherzip'];
	$arrChargerMasData['rcm_postcode'] = $zip;

	$state  = ($row[$row['mailingstate']]) ? $row['mailingstate'] : $row['otherstate'];
	$city   = ($row['mailingcity']) ? $row['mailingcity'] : $row['othercity'];
	$street = ($row['mailingstreet']) ? $row['mailingstreet'] : $row['otherstreet'];

	$arrChargerMasData['rcm_address'] = $state . "　" . $city . "　" . $street;
	
	if($row['email']) {
		$strRcmMail = $row['email'];
	} else {
		$strRcmMail = $row['secondaryemail'];
	}//End if
	$arrChargerMasData['rcm_mail'] = $strRcmMail;

	if($row['phone']) {
		$strRcmTel = $row['phone'];
	} else {
		$strRcmTel = $row['mobile'];
	}//End if
	$arrChargerMasData['rcm_tel'] = $strRcmTel;

	$arrChargerMasData['rcm_client_id'] = $row['rcl_id'];

	$intAccountId = $row['accountid'];
	$strAccountName = $row['accountname'];

	$intRcmid = (int)$row['rcm_id'];

	//JASのクライアント情報(rank_client)の保存
	if($arrChargerMasData['rcm_client_id'] == false) $arrChargerMasData['rcm_client_id'] = jas_account_save($intAccountId);

	if($intRcmid !== 0 && $_POST['module'] === 'Contacts') {	#基幹システムにJASの顧客担当者ID(rcm_id)が登録されているとき
		#JASの顧客担当者情報の変更
		updateChargerMasData($intRcmid, $arrChargerMasData);	#JASのクライアント情報を更新
	} elseif($_POST['division'] === '202:Webコミュ') {	#基幹システムにJASのクライアントIDが登録されていないとき
#		require('cin/SQLConnect.php');
		require('cin/SQLConnect_jas.php');
		$sqlRcm  = " SELECT * FROM rank_charger_mas ";
		$sqlRcm .= " INNER JOIN rank_client ON rcm_client_id = rcl_id ";
		$sqlRcm .= " WHERE TRUE ";
		$sqlRcm .= " AND rcl_name = '{$strAccountName}' ";
		$sqlRcm .= " AND rcm_name = '{$arrChargerMasData['rcm_name']}' LIMIT 0,1";

		print $sqlRcm . "<br /><br />";

		$rsRcm = mysql_db_query($dbName, $sqlRcm);
		$rsRcmCount = mysql_num_rows($rsRcm);

		if($rsRcmCount !== 0) {	#JASに顧客担当者情報があるとき
			//JASの顧客担当者IDを取得
			$rowRcm = mysql_fetch_array($rsRcm);
			$intRcmid = $rowRcm['rcm_id'];	#JASの顧客担当者ID

			if($_POST['module'] === 'Contacts') {
				updateChargerMasData($intRcmid, $arrChargerMasData);	#JASの顧客担当者情報を更新
			}//End if
		} else {	#JASに顧客担当者情報がないとき
			#JASに顧客担当者情報を登録(Insert)
			$intRcmid = insertChargerMasData($arrChargerMasData);
		}//End if

		require('cin/SQLClose.php');

		#基幹システムに顧客担当者IDを保存(update)
		$query_contact  = " UPDATE vtiger_contactdetails SET rcm_id = {$intRcmid} ";
		$query_contact .= " WHERE TRUE ";
		$query_contact .= " AND contactid = {$contactid} ";

		print "contact_update : ".$query_contact."<br /><br />";

		$adb->pquery($query_contact);
	}//End if

	return $intRcmid;

}//End function

function insertChargerMasData($arrChargerMasData) {

		foreach($arrChargerMasData as $field_rank_charger_mas => $value_rank_charger_mas) {
			unset($_POST[$field_rank_charger_mas]);
			$_POST[$field_rank_charger_mas] = $value_rank_charger_mas;
		}//End foreach

		$_POST['rcm_make_datetime'] = date('Y-m-d H:i:s');

		unset($_POST[rcm_id]);
		print DataJasInsertString('rank_charger_mas') . "<br /><br />";
		$intChargerMasId = DataJasInsert('rank_charger_mas');
		
		return $intChargerMasId;

}//End function

function updateChargerMasData($intRcmId, $arrChargerMasData) {
	backupRankChargerData($intRcmId);

	foreach($arrChargerMasData as $field_rank_charger_mas => $value_rank_charger_mas) {
		unset($_POST[$field_rank_charger_mas]);
		$_POST[$field_rank_charger_mas] = $value_rank_charger_mas;
	}//End foreach

	$_POST[rcm_id] = $intRcmId;

	print DataJasUpdateString('rank_charger_mas') . "<br /><br />";
	DataJasUpdate('rank_charger_mas');
	#exit;
}//End function

//顧客担当者データのバックアップ
function backupRankChargerData($intRcmId) {
#	require ('cin/SQLConnect_jas.php');
	require ('cin/SQLConnect_jas.php');
	$sql  = " SELECT * FROM rank_charger_mas ";
	$sql .= " WHERE TRUE ";
	$sql .= " AND rcm_id = ".$intRcmId." ;";

	print $sql . "<br />";

	$rs = mysql_db_query($dbName, $sql);
	$rsCount = mysql_num_rows($rs);
	$row = mysql_fetch_array($rs);

	require ('cin/SQLClose.php');

	if($rsCount !== 0) {
		foreach($row as $rcm_fields_name => $strRcmValue) {
			$_POST[$rcm_fields_name] = $strRcmValue;
		}//End foreach

		$_POST[rcm_datetime] = date('Y-m-d H:i:s');

		print DataInsertString('backup_charger_mas') . "<br /><br />";
		DataInsert('backup_charger_mas');
	}//End if

	foreach($row as $rcl_fields_name => $strRclValue) {
		unset($_POST[$rcl_fields_name]);
	}//End foreach

}//End function