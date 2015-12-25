<?php
	$arrAgencyFlag = array('1：代理店' => '1', '2：代理店顧客' => '2', '3：紹介代理店' => '3', '7：直クライアント' => '0');
	$_POST['rcl_agencyflag'] = $arrAgencyFlag[$_POST['client_type']];

	$arrDeadLineKind = array('翌月10日'=>'9','翌月15日'=>'1','翌月20日'=>'6','翌月末(30日)'=>'2','翌々月10日(40日)'=>'3','翌々月15日(45日)'=>'4','翌々月20日(50日)'=>'10','翌々月末日(60日)'=>'5',
										'翌々月5日(35日)'=>'7','3ヶ月後10日(100日)'=>'8');
	$_POST['rcl_deadline_kind'] = $arrDeadLineKind[$_POST[payday]];	#締め日種類

	$_POST['rcl_name'] = $_POST['accountname'];	#クライアント名
	
	$_POST['rcl_postcode'] = $_POST['bill_code'];	#郵便番号
	
  	$_POST['rcl_address'] = $_POST['bill_state'];	#住所

	$_POST['rcl_tel'] = $_POST['phone'];	#電話番号

	$_POST['rcl_cover_mail'] = $_POST['email1'];	#メールアドレス

#	$_POST['rcl_charger_id'] = '';	#営業担当者ID
	
	$_POST['rcl_status'] = '1';	#稼働:1　非稼働:0

	$_POST['rcl_resisted'] = '1';	#登録済かどうか これでseo-rankingのIDとPASSの判断する？

	$_POST['rcl_accountid'] = $this->id;

  require('cin/defalut.php');

  if($_POST[record] == false) {	#$_POST[record]がないときINSERTする

	#	print DataJasInsertString('rank_client') . "<br />";
		$_POST[rcl_id] = DataJasInsert('rank_client');



	  require('cin/SQLConnect_jas.php');

	  $sql  = " SELECT * FROM `rank_client` ";
	  $sql .= " WHERE TRUE ";
	  $sql .= " AND rcl_id = {$_POST[rcl_id]} ";

	  #print $sql . "<br />";

	  $rs = mysql_db_query($dbName, $sql);
	  $row = mysql_fetch_array($rs);


	//既にseo-rankingのユーザーID、パスがある場合登録しない
#		if(!(($row[rcl_username]) && ($row[rcl_password]))) {  #15-02-13変更
		//クライアントIDの発行
		//====================================================================
		$prefix = "";
		if($_POST[rcl_agencyflag] =="0") $prefix = "A";
		if($_POST[rcl_agencyflag] =="1") $prefix = "E";
		if($_POST[rcl_agencyflag] =="2") $prefix = "A";

		$username = $prefix . "-" . substr(DateNow(0), 2,2) . substr(	(0), 5,2) . "-" . substr("00000" . $row[rcl_id], -5);

		$pass = randPass(6);
	  require('cin/SQLConnect_jas.php');
		
		#$sql = "UPDATE `rank_client` SET `rcl_username` = '" . $username . "', `rcl_password` = '" . $pass . "' WHERE (`rcl_name` = '" . $_POST[rcl_name] ."');";
		$sql = "UPDATE `rank_client` SET `rcl_username` = '" . $username . "', `rcl_password` = '" . $pass . "' WHERE (`rcl_id` = '" . $_POST[rcl_id] ."');";  #15-02-13変更

		print $sql . "<BR><BR>";
		$c_hit = mysql_db_query($dbName,$sql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());

		mysql_close($GLOBALS[dbHandle]);
		//====================================================================
#		}//End if


  } else {	#$_POST[record]があるときUPDATEする

		require('cin/SQLConnect_jas.php');
		
		$sql  = " SELECT rcl_id FROM `rank_client` ";
		$sql .= " WHERE TRUE ";
		$sql .= " AND rcl_accountid = {$_POST[record]} ";

	#	print $sql . "<br />";

		$rsRcl = mysql_db_query($dbName, $sql);
		$rowRcl = mysql_fetch_array($rsRcl);
		$_POST[rcl_id] = $rowRcl[rcl_id];
		require('cin/SQLClose.php');

	#	print "update : " . DataJasUpdateString('rank_client') . "<br />";
		DataJasUpdate('rank_client');
  }//End if
