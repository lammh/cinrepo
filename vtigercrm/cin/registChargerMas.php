<?
if($_POST[module]=="Leads"){exit;}

$query  = " SELECT contactid, rcm_id FROM `vtiger_contactdetails` ";
$query .= " WHERE TRUE ";
$query .= " AND contactid = '".$_POST[record]."' ;";

print "<p>SQL : {$query}</p>";

if($_POST[record]==""){exit;}

print("test".$_POST[record]);
global $adb;
$resultItem=$adb->pquery($query);
$countItemNum = $adb->num_rows($resultItem);

#for($intI=0;$intI<$countItemNum;$intI++) {
$rowItem =  $adb->fetch_array($resultItem);
$intRcmId = $rowItem[rcm_id];
$arrContactId[$rowItem[contactid]] = $rowItem[rcm_id];
#}//End for

require('cin/SQLConnect_jas.php');
$sqlRcm  = " SELECT * FROM rank_charger_mas ";
$sqlRcm .= " WHERE TRUE ";
$sqlRcm .= " AND rcm_id = {$rowItem[rcm_id]} ";

print "<p>SQL : {$sqlRcm}</p>";

$rsRcm = mysql_db_query($dbName, $sqlRcm);

$rsCountRcm = mysql_num_rows($rsRcm);
print($rsCountRcm);
if($rsCountRcm == 1){
	$rowRcm = mysql_fetch_array($rsRcm);
	$_POST[rcm_client_id] = $rowRcm[rcm_client_id];
	$_POST[rcm_name] = $rowRcm[rcm_name];
	$_POST[rcm_post] = $rowRcm[rcm_post];
	$_POST[rcm_postcode] = $rowRcm[rcm_postcode];
	$_POST[rcm_address] = $rowRcm[rcm_address];
	$_POST[rcm_mail] = $rowRcm[rcm_mail];
	$_POST[rcm_tel] = $rowRcm[rcm_tel];
}//End if

if($_POST['firstname']) $_POST['rcm_name'] = $_POST['firstname'] . "　" . $_POST['lastname']; #担当者名
$strRcmAddress = $_POST['mailingstate'] . " " . $_POST['mailingcity'] . " " . $_POST['mailingstreet'];

if($strRcmAddress) $_POST['rcm_address'] =  $strRcmAddress;  #住所

#$_POST[title] 肩書 
if($_POST['department']) $_POST['rcm_post'] = $_POST['department'];	#部門

if($_POST['mailingzip']) $_POST['rcm_postcode'] = $_POST['mailingzip'];	#郵便番号

//電話番号
if($_POST['phone']) {
	$_POST['rcm_tel'] = $_POST['phone'];
} else {
	$_POST['rcm_tel'] = $_POST['mobile'];
}//End if

//メールアドレス
if($_POST['email']) $_POST['rcm_mail'] = $_POST['email'];

#$_POST['rcm_contactid'] = $this->id;

require('cin/defalut.php');
if($_POST[record] == false || $rsCountRcm === 0) {	#$_POST[record]がないときは	INSERT

	$sqlRcl  = " SELECT * FROM rank_client ";
	$sqlRcl .= " WHERE TRUE ";
	$sqlRcl .= " AND rcl_accountid = {$_POST[account_id]} ";
	#print $sqlRcl . "<br />";

	$rsRcl = mysql_db_query($dbName, $sqlRcl);
	$rowRcl = mysql_fetch_array($rsRcl);

	unset($_POST[rcm_id]);
	$_POST['rcm_client_id'] = $rowRcl['rcl_id'];
	$_POST['rcm_make_datetime'] = date('Y-m-d H:i:s');	#データ作成日付
	#print DataJasInsertString('rank_charger_mas') . "<br />";
	#exit;
	DataJasInsert('rank_charger_mas');
}	 else {
	$_POST[rcm_id] = $rowRcm[rcm_id];
	#print "update : " . DataJasUpdateString('rank_charger_mas') . "<br />";
	#exit;
	DataJasUpdate('rank_charger_mas');
}//End if