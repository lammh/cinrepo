<?
	print "<p>account_id : ".$_POST['account_id']."</p>";
	$arrayAccountType = array('daily' => '1',	'n_daily' => '2' , 'either_n_daily' => '3', 'month' => '4',	'either_daily' => '5');
	$arrrUrlStrict  = array('part' => '0', 'domain' => '1', 'perfect' => '2', 'character' => '3');

	require_once("./cin/common/keyword_common.php");
	require('cin/defalut.php');
	require('cin/SQLConnect.php');


	$sqlRcl  = " SELECT * FROM `rank_client` ";
	$sqlRcl .= " WHERE TRUE ";
	$sqlRcl .= " AND rcl_accountid = 0 ";

	print $sqlRcl . "<br />";

	$rsRcl = mysql_db_query($dbName, $sqlRcl);
	$rsRclCount = mysql_num_rows($rsRcl);

	for($intRcl=0;$intRcl<$rsRclCount;$intRcl++) {
		$rowRcl = mysql_fetch_array($rsRcl);
		$arrNoAccountIdClient[$rowRcl[rcl_id]][client_name] = mysql_real_escape_string($rowRcl[rcl_name]);
	}//End for

#	var_dump($arrNoAccountIdClient);

	require('cin/SQLClose.php');

	foreach ($arrNoAccountIdClient as $intRclid => $arrNoAccountIdClientValue) {

		$queryItem = " SELECT accountid, accountname FROM vtiger_account WHERE accountname = '{$arrNoAccountIdClientValue[client_name]}' LIMIT 0,1";
#		print $queryItem . "<br />";

		global $adb;
		$resultItem=$adb->pquery($queryItem);
		$countItemNum = $adb->num_rows($resultItem);
		if($countItemNum === 0) continue;
		$rowItem =  $adb->fetch_array($resultItem);

		$arrAccountIdInfo[$intRclid] = $rowItem[accountid];
	}//End foreach

	require('cin/SQLConnect.php');

	if(count($arrAccountIdInfo) !== 0) {
		foreach($arrAccountIdInfo as $intRclid => $intAccountid) {
			$upSqlAccountid = " UPDATE rank_client SET rcl_accountid = {$intAccountid} WHERE rcl_id = {$intRclid} LIMIT 1 ";

			print $upSqlAccountid . "<br />";
			mysql_db_query($dbName, $upSqlAccountid);
		}//End foreach
	}//End if

	$sqlRcl  = " SELECT * FROM `rank_client` ";
	$sqlRcl .= " WHERE TRUE ";
	$sqlRcl .= " AND rcl_accountid = {$_POST[account_id]} ";

	print $sqlRcl . "<br />";

	$rsRcl = mysql_db_query($dbName, $sqlRcl);
	$rsRclCount = mysql_num_rows($rsRcl);

	for($intRcl=0;$intRcl<$rsRclCount;$intRcl++) {
		$rowRcl = mysql_fetch_array($rsRcl);
		#rkw_rclid クライアントID
		$_POST['rkw_rclid'] = $rowRcl[rcl_id];
		#rkw_client クライアント名
		$_POST['rkw_client'] = $rowRcl[rcl_name];
	}//End for

	$_POST[contact_id_display] = preg_replace('@\s|　@','|',$_POST[contact_id_display]);
	$arrrChargerName = explode('|', $_POST[contact_id_display]);

	$sqlRcm  = " SELECT * FROM `rank_charger_mas` ";
	$sqlRcm .= " INNER JOIN rank_client ON rcm_client_id = rcl_id ";
	$sqlRcm .= " WHERE TRUE ";
	$sqlRcm .= " AND (rcm_name = '{$arrrChargerName[0]}　{$arrrChargerName[1]}' ";
	$sqlRcm .= " OR rcm_name = '{$arrrChargerName[1]}　{$arrrChargerName[0]}' ) ";
	$sqlRcm .= " AND rcl_accountid =  {$_POST[account_id]} ";

	print $sqlRcm . "<br />";

	$rsRcm = mysql_db_query($dbName, $sqlRcm);
	$rsRcmCount = mysql_num_rows($rsRcm);

	if($rsRcmCount === 0) {
		print "<p>顧客担当者がありません。システム担当者へ相談下さい。</p>";
		exit;
	}//End if

	for($intRcm=0;$intRcm<$rsRcmCount;$intRcm++) {
		$rowRcm = mysql_fetch_array($rsRcm);
		#rkw_rcmid 担当者ID
		$_POST['rkw_rcmid'] =  $rowRcm[rcm_id];
	}//End for

#	echo "<pre>";
#	var_dump($_POST);
#	echo "</pre>";

	
	require('../kuma/SQLClose.php');

	#rkw_contract_startdate 契約開始日
	$_POST['rkw_contract_startdate'] = $_POST[start_period];

	#rkw_contract_enddate 契約終了日
	$_POST['rkw_contract_enddate'] =  $_POST[end_period];

	#rkw_contract_term	契約期間
	$_POST['rkw_contract_term'] = (int)$_POST[contract_term];

	$intCountCintId = 0;
#		var_dump($_POST['hdnProductId']);
	#	exit;

	/** START KW 登録繰り返し *******************************/
	while($intCountCintId < 10) {
#		var_dump($_POST['hdnProductId'.$intCountCintId]);
		$intCinId = (int)$_POST['hdnProductId'.$intCountCintId];
		
		UNSET($_POST[rkw_fee_month]);
		$_POST[rkw_fee_month] = (int)$_POST['listPrice'.$intCountCintId];

		$intCountCintId++;

		if($intCinId === 0) continue;

		keywordRegist($intCinId);

	}//End while
	/** END KW 登録繰り返し *******************************/