<?php
//151118修正 大熊
function jas_account_save($accountid) {

	$arrDeadLineKind = array('翌月10日'=>'9','翌月15日'=>'1','翌月20日'=>'6','翌月末(30日)'=>'2','翌々月10日(40日)'=>'3','翌々月15日(45日)'=>'4','翌々月20日(50日)'=>'10','翌々月末日(60日)'=>'5',
										'翌々月5日(35日)'=>'7','3ヶ月後10日(100日)'=>'8');

	$arrAgencyFlag = array('1:代理店' => '1', '2:代理店顧客' => '2', '3:紹介代理店' => '3', '7:直クライアント' => '0', '6:その他' => '0', '4:パートナー' => '0', '5:グループ' => '0');

	//vtiger_account
	$query  = " SELECT `vtiger_account`.`jgid` AS rcl_id, accountname, payday, bill_code, bill_state, bill_city, bill_street, phone, `vtiger_account`.`email1`, client_type, `vtiger_users`.`jgid` AS rcl_charger_id FROM vtiger_account ";
	$query .= " INNER JOIN vtiger_accountbillads ON accountid = accountaddressid ";
	$query .= " INNER JOIN vtiger_crmentity ON accountid = crmid ";
	$query .= " INNER JOIN vtiger_users ON smownerid = `vtiger_users`.`id` ";
	$query .= " WHERE TRUE ";
	$query .= " AND accountid = {$accountid} ";

	print $query."<br /><br />";

#exit;
	global $adb;
	$result=$adb->pquery($query);
	$countNum = $adb->num_rows($result);
	$row =  $adb->fetch_array($result);

	$arrClientData['rcl_name'] = $row['accountname'];	#顧客企業名
	$arrClientData['rcl_deadline_kind'] = $arrDeadLineKind[$row['payday']];	#締め日
	$arrClientData['rcl_postcode'] = $row['bill_code'];#郵便番号
	$arrClientData['rcl_address'] = $row['bill_state'] . " " . $row['bill_city'] . " " . $row['bill_street'];#住所
	$arrClientData['rcl_tel'] = $row['phone'];#電話番号
	$arrClientData['rcl_cover_mail'] = $row['email1'];	#メールアドレス

#	if($_POST['agencyflag'] == true) {
#		$arrClientData['rcl_agencyflag'] = 2;
#		$client_type = "2：代理店顧客";
#	} elseif($row['client_type'] == false) {	#顧客タイプがNULLのとき
#		$arrClientData['rcl_agencyflag'] = 0;
#		$client_type = "7：直クライアント";
#	} else {	#
		$arrClientData['rcl_agencyflag'] = (int)$arrAgencyFlag[$row['client_type']];
#	}//End if

	$arrClientData['rcl_charger_id'] = (int)$row['rcl_charger_id'];

	$intRclId = (int)$row[rcl_id];	#JASのクライアントID

	if($intRclId !== 0 && $_POST['module'] === 'Accounts') {	#基幹システムにJASのクライアントID(rcl_id)が登録されているとき
		#JASのクライアント情報の変更
		updateClientData($intRclId, $arrClientData);	#JASのクライアント情報を更新
	} elseif($_POST['division'] === '202:Webコミュ') {	#基幹システムにJASのクライアントIDが登録されていないとき
#		require('cin/SQLConnect.php');
		require('cin/SQLConnect_jas.php');
		$sqlRcl  = " SELECT * FROM rank_client ";
		$sqlRcl .= " WHERE TRUE ";
		$sqlRcl .= " AND rcl_name = '{$row['accountname']}' LIMIT 0,1";

		print $sqlRcl . "<br />";

		$rsRcl = mysql_db_query($dbName, $sqlRcl);
		$rsRclCount = mysql_num_rows($rsRcl);

		if($rsRclCount !== 0) {	#JASのクライアント情報があるとき
			//JASのクライアントIDを取得
			$rowRcl = mysql_fetch_array($rsRcl);
			$intRclId = $rowRcl['rcl_id'];	#JASのクライアントID
			if($_POST['module'] === 'Accounts') {	#JASにクライアント情報があるとき
				updateClientData($intRclId, $arrClientData);	#JASのクライアント情報を更新
			}//End if
		} else {	#JASにクライアント情報がないとき
			#JASにクライアント情報を登録(Insert)
			$intRclId = insertClientData($arrClientData);
		}//End if

		require('cin/SQLClose.php');

		#基幹システムにクライアントIDを保存(update)
		$query_account  = " UPDATE vtiger_account SET jgid = '{$intRclId}' ";
		if($client_type) $query_account  .= ", client_type = '".$client_type."' ";
		$query_account .= " WHERE TRUE ";
		$query_account .= " AND accountid = {$accountid} ";

		print "account_update : ".$query_account."<br /><br />";

		$adb->pquery($query_account);

		return $intRclId;
	}//End if

}//End function

function insertClientData($arrClientData) {

		foreach($arrClientData as $field_rank_client => $value_rank_client) {
			unset($_POST[$field_rank_client]);
			$_POST[$field_rank_client] = $value_rank_client;
		}//End foreach

		$_POST['rcl_status'] = 1;
		$_POST['rcl_resisted'] = 1;

		unset($_POST[rcl_id]);
		print DataJasInsertString('rank_client') . "<br /><br />";
		$intClientId = DataJasInsert('rank_client');

		//seo-rankingのユーザー名とパスワードの登録(update)
		//ユーザー名
		$prefix = "";
		if($_POST[rcl_agencyflag] =="0") $prefix = "A";
		if($_POST[rcl_agencyflag] =="1") $prefix = "E";
		if($_POST[rcl_agencyflag] =="2") $prefix = "A";
		$username = $prefix . "-" . substr(DateNow(0), 2,2) . substr(	(0), 5,2) . "-" . substr("00000" . $intClientId, -5);
		//パスワード
		$pass = randPass(6);

	  	require('cin/SQLConnect_jas.php');
#	  	require('cin/SQLConnect.php');
				
		$sql = "UPDATE `rank_client` SET `rcl_username` = '" . $username . "', `rcl_password` = '" . $pass . "' WHERE (`rcl_id` = '" . $intClientId ."');";  #15-02-13変更

		print $sql . "<BR><BR>";
		$c_hit = mysql_db_query($dbName,$sql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());

		mysql_close($GLOBALS[dbHandle]);

	  	require('cin/SQLClose.php');
		
		return $intClientId;

}//End function

function updateClientData($intRclId, $arrClientData) {
	backupRankClientData($intRclId);

	foreach($arrClientData as $field_rank_client => $value_rank_client) {
		$_POST[$field_rank_client] = $value_rank_client;
	}//End foreach

	$_POST[rcl_id] = $intRclId;

	print DataJasUpdateString('rank_client') . "<br /><br />";
	DataJasUpdate('rank_client');
#	exit;
}//End function

/** ▼ * 顧客企業データのバックアップ ***/
function backupRankClientData($intRclId) {
#	require ('cin/SQLConnect.php');
	require ('cin/SQLConnect_jas.php');

	$sql  = " SELECT * FROM rank_client ";
	$sql .= " WHERE TRUE ";
	$sql .= " AND rcl_id = ".$intRclId." ;";

	print $sql . "<br />";

	$rs = mysql_db_query($dbName, $sql);
	$rsCount = mysql_num_rows($rs);
	$row = mysql_fetch_array($rs);

	require ('cin/SQLClose.php');

	if($rsCount !== 0) {
		foreach($row as $rcl_fields_name => $strRclValue) {
			$_POST[$rcl_fields_name] = $strRclValue;
		}//End foreach

		$_POST[rcl_datetime] = date('Y-m-d H:i:s');
		print DataInsertString('backup_rank_client')."<br /><br />";
		DataInsert('backup_rank_client');
	}//End if

	foreach($row as $rcl_fields_name => $strRclValue) {
		unset($_POST[$rcl_fields_name]);
	}//End foreach
}//End function
/** ▲ * 顧客企業データのバックアップ ***/