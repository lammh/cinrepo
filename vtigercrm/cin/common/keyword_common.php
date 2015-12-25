<?php
//151118修正大熊
function jas_services_save($serviceid, $salesorderflag) {

	$arrrUrlStrict = array(
		"part" => "0",
		"部分一致（下層ページも課金対象）" => "0",
		"domain" => "1",
		"ドメイン一致" => "1",
		"perfect" => "2",
		"完全一致" => "2",
		"文字のマッチ" => "3",
		"文字マッチ" => "3",
		"character" => "3"
	);

	$arrayAccountType = array(
		"日毎課金" => "1",
		"日毎どちらか課金" => "5",
		"n日どちらか課金" => "3",
		"月額固定" => "4",
		"n日課金" => "2",
		"daily" => "1",
		"month" => "4",
		"either_daily" => "5",
		"n_daily" => "2",
		"either_n_daily" => "3"
	);

	$queryItem  = " SELECT * FROM `vtiger_service` ";
	$queryItem .= " LEFT JOIN  `vtiger_account` ON `seo_agency_customers` = `accountid`";
	$queryItem .= " WHERE TRUE ";
	$queryItem .= " AND serviceid = {$serviceid} ";
	$queryItem .= " AND servicecategory = 'SEO' ";

	print $queryItem."<br /><br />";

#exit;
	global $adb;
	$resultItem=$adb->pquery($queryItem);
	$countItemNum = $adb->num_rows($resultItem);
	$rowItem =  $adb->fetch_array($resultItem);

	#if($countItemNum === 0) return false; 
	#print "コメントあうとしてね<br /><br />";
	if(is_null($rowItem[seo_keyword]) || is_null($rowItem[seo_url])) return false;	#キーワード、URLどちらかが空で合った場合、登録・変更しない

	//KwIDの取得(基幹)
	$arrKeywordInfo['rkw_id'] = (int)$rowItem['seo_kwid'];	#Kwid

	/** ▼ JASへの代理店顧客情報保存準備 ****/
	if((int)$rowItem['seo_agency_customers'] !== 0) {	#代理店顧客があるとき

		if($rowItem['jgid'] == false) {

			//JASに代理店顧客情報がないので登録する
			$_POST['agencyflag'] = true;
			$arrKeywordInfo['rkw_child_cliid'] = jas_account_save($rowItem['accountid']);
			$arrKeywordInfo['rkw_child_client'] = $rowItem['accountname'];

		} else {

			$arrKeywordInfo['rkw_child_cliid'] = $rowItem['jgid'];
			$arrKeywordInfo['rkw_child_client'] = $rowItem['accountname'];

		}//End if

	} else {	#代理店顧客がないとき

			$arrKeywordInfo['rkw_child_cliid'] = 0;
			$arrKeywordInfo['rkw_child_client'] = "";

	}//End if
	/** ▲ JASへの代理店顧客情報保存準備 ****/

	#rkw_word キーワード名
	$arrKeywordInfo['rkw_word'] = $rowItem['seo_keyword'];
	#rkw_url
	$arrKeywordInfo['rkw_url']  = $rowItem['seo_url'];
	
	#$arrKeywordInfo['rkw_resistdate'] = date('Y-m-d');

	#rkw_contract_startdate 契約期間開始日
	$arrKeywordInfo['rkw_contract_startdate'] = $rowItem['start_date'];
	#rkw_contract_enddate   契約期間終了日
	$arrKeywordInfo['rkw_contract_enddate'] = $rowItem['expiry_date'];

	#rkw_url_strict	順位チェック方法
	$arrKeywordInfo['rkw_url_strict'] = $arrrUrlStrict[$rowItem['seo_rank_check']];

	#rkw_account_type 課金方法
	$arrKeywordInfo['rkw_account_type'] = $arrayAccountType[$rowItem['seo_fee_method']];

	#rkw_account_date 課金日数
	$arrKeywordInfo['rkw_account_date'] = (int)$rowItem['seo_rank_daynum'];

	#rkw_away_flag　解約処理
	if((int)$rowItem['seo_away_flag'] === 1) {	#有効がいいえのとき
		$arrKeywordInfo['rkw_away_flag'] = 1;	#JASの解約フラグをたてる
	} else {	#それ以外(有効がはいのとき)
		$arrKeywordInfo['rkw_away_flag'] = 0;	#JASの解約フラグを解除する
	}//End if

	//課金額情報
	for($intFeeNum=1;$intFeeNum<=5;$intFeeNum++) {
		if($rowItem['seo_rank_top_'.$intFeeNum]) {
			$arrFeeInfo[$intFeeNum][rfe_id] = null;
			$arrFeeInfo[$intFeeNum][rfe_status] = 1;
			$arrFeeInfo[$intFeeNum][rfe_datetime] = date('Y-m-d H:i:s');
			$arrFeeInfo[$intFeeNum][rfe_rank_start] = $rowItem['seo_rank_top_'.$intFeeNum];
			$arrFeeInfo[$intFeeNum][rfe_rank_end] = $rowItem['seo_rank_down_'.$intFeeNum];
			$arrFeeInfo[$intFeeNum][rfe_yen] = $rowItem['seo_fee_'.$intFeeNum];
		}//End if
	}//End for

	/** ▼ ** vitiger_crmentityへの契約期間を説明に反映 ***/
	$queryUpdate  = " UPDATE `vtiger_crmentity` SET ";
	$queryUpdate .= " description = CONCAT(";
	$queryUpdate .= " description, ' 契約期間:".$arrKeywordInfo['rkw_contract_startdate']."～".$arrKeywordInfo['rkw_contract_enddate']."' ";
	$queryUpdate .= " ) ";
	$queryUpdate .= " WHERE TRUE ";
	$queryUpdate .= " AND crmid = {$serviceid} ";

	print $queryUpdate."<br /><br />";

	global $adb;
	$adb->pquery($queryUpdate);
	/** ▲ ** vtiger_crmentityへの契約期間を説明の反映 ***/

	/** ▼ JASへの反映 **/
	if($salesorderflag == true && $arrKeywordInfo['rkw_id'] === 0) {	#JASにKWが登録されてないとき

		/** ▼ JASのキーワードデータの追加 *****/
		#rkw_input_flag　キーワード内訳
		$arrKeywordInfo['rkw_input_flag'] = 1;

		#rkw_segment セグメント
		$arrKeywordInfo['rkw_segment'] = 1;

		#rkw_random_flag ランダムフラグ デフォルト値：1 
		$arrKeywordInfo['rkw_random_flag'] = 1;

		$arrKeywordInfo['rkw_fee_status'] = "Y/G";

		foreach($arrKeywordInfo as $rkw_fields_name => $strKeywordData) {
			$_POST[$rkw_fields_name] = $strKeywordData;
		}//End foreach

		//キーワードIDの初期化
		unset($_POST['rkw_id']);

		print "Insert : " . DataJasInsertString('rank_keyword') . "<br /><br />";
		$_POST['rkw_id'] = DataJasInsert('rank_keyword');

		$queryUpdate  = " UPDATE `vtiger_service` SET ";
		$queryUpdate .= " seo_kwid = {$_POST['rkw_id']} ";
		$queryUpdate .= " WHERE TRUE ";
		$queryUpdate .= " AND serviceid = {$serviceid} ";

		print $queryUpdate."<br /><br />";

		global $adb;
		$adb->pquery($queryUpdate);

		/** ▲ JASのキーワードデータの追加 *****/
	} elseif((int)$arrKeywordInfo['rkw_id'] === 0) {	#キーワードIDが0のときはなにもしない
		return false;
	} elseif($module === 'Products' || $module === 'Services') {
		/** ▼ JASのキーワードデータの更新 ***/

		/** ▼ 変更前キーワード情報の取得 ****/
#		require('cin/SQLConnect.php');
		require('cin/SQLConnect_jas.php');
		$sqlRkw = "SELECT * FROM rank_keyword WHERE TRUE AND rkw_id = {$arrKeywordInfo['rkw_id']} ";

		print $sqlRkw . "<br /><br />";

		$rsRkw = mysql_db_query($dbName, $sqlRkw);
		$rsCountRkw = mysql_num_rows($rsRkw);
		$rowRkw = mysql_fetch_array($rsRkw);
		$arrRkw = $rowRkw;

		if((int)$rowRkw['rkw_account_type'] !== 5) return false;

		require('cin/SQLClose.php');
		/** ▲ 変更前キーワード情報の取得 ****/

		/** ▼ ** キーワードの履歴を保存する ****/
		foreach($arrRkw as $rkw_fields_name => $strRkwValue) {
			unset($_POST[$rkw_fields_name]);
			$_POST[$rkw_fields_name] = $strRkwValue;
		}//End foreach

		$_POST[rkw_create_datetime] = date('Y-m-d H:i:s');
		print "Insert : " . DataInsertString('backup_keyword') . "<br /><br />";
		DataInsert('backup_keyword');
		/** ▲ ** キーワードの履歴を保存する ****/

		/** ▼ ** 変更後の反映 *********/
		foreach($arrKeywordInfo as $rkw_fields_name => $strKeywordData) {
			$_POST[$rkw_fields_name] = $strKeywordData;
		}//End foreach

		print "Update : " . DataJasUpdateString('rank_keyword') . "<br /><br />";
		DataJasUpdate('rank_keyword');
		/** ▲ ** 変更後の反映 *********/

	/** ▲ JASのキーワードデータの更新 ***/
	}//End if

	//課金額データの更新
#	require('cin/SQLConnect.php');

	if(($salesorderflag == true && $arrKeywordInfo['rkw_id'] === 0) || $module === 'Products' || $module === 'Services') {	#製品登録でのみJASに保存される
		require('cin/SQLConnect_jas.php');

		//フラグ9を更新
		$sqlUpRfe  = " UPDATE `rank_fee` SET `rfe_status` = 9 ";
		$sqlUpRfe .= " WHERE TRUE ";
		$sqlUpRfe .= " AND rfe_status = 1 ";
		$sqlUpRfe .= " AND rfe_rkwid = {$_POST[rkw_id]};";

		print $sqlUpRfe . "<br /><br />";

		mysql_db_query($dbName, $sqlUpRfe);


		$_POST['rfe_rkwid'] = $_POST['rkw_id'];

		for($int_provider_kind=1;$int_provider_kind<=2;$int_provider_kind++) {
			
			$_POST['rfe_provider_kind'] = $int_provider_kind;	#1:yahoo, 2:google
			
			foreach($arrFeeInfo as $intFeeKey => $arrFeeInfoVlaue) {

				foreach ($arrFeeInfoVlaue as $rfe_filed_data => $strFeeData) {
					//各フィールドの初期化
					unset($_POST[$rfe_filed_data]);
					$_POST[$rfe_filed_data] = $strFeeData;
				}//End foreach

				//課金額データの追加
				print "Insert : " . DataJasInsertString('rank_fee') . "<br /><br />";
				DataJasInsert('rank_fee');

			}//End foreach
		}//End for

		require('cin/SQLClose.php');
	}//End if

	registDomain($_POST[rkw_id]);

	//キーワードIDを返す
	return (int)$_POST[rkw_id];

}//End function


function jas_products_save($productid, $salesorderflag) {

	$arrrUrlStrict = array(
		"part" => "0",
		"部分一致（下層ページも課金対象）" => "0",
		"domain" => "1",
		"ドメイン一致" => "1",
		"perfect" => "2",
		"完全一致" => "2",
		"文字のマッチ" => "3",
		"文字マッチ" => "3",
		"character" => "3"
	);

	$queryItem  = " SELECT * FROM `vtiger_products` ";
	$queryItem .= " LEFT JOIN  `vtiger_account` ON `seo_agency_customers` = `accountid`";
	$queryItem .= " WHERE TRUE ";
	$queryItem .= " AND productid = {$productid} ";
	$queryItem .= " AND productcategory = 'SEO' ";

	print $queryItem."<br /><br />";

	global $adb;
	$resultItem=$adb->pquery($queryItem);
	$countItemNum = $adb->num_rows($resultItem);
	$rowItem =  $adb->fetch_array($resultItem);

	#if($countItemNum === 0) return false; 
	#print "コメントあうとしてね<br /><br />";
	if(is_null($rowItem[seo_keyword]) || is_null($rowItem[seo_url])) return false;	#キーワード、URLどちらかが空で合った場合、登録・変更しない

	//KwIDの取得(基幹)
	$arrKeywordInfo['rkw_id'] = (int)$rowItem['seo_kwid'];	#Kwid

	/** ▼ JASへの代理店顧客情報保存準備 ****/
	if((int)$rowItem['seo_agency_customers'] !== 0) {	#代理店顧客があるとき

		if($rowItem['jgid'] == false) {

			//JASに代理店顧客情報がないので登録する
			$_POST['agencyflag'] = true;
			$arrKeywordInfo['rkw_child_cliid'] = jas_account_save($rowItem['accountid']);
			$arrKeywordInfo['rkw_child_client'] = $rowItem['accountname'];

		} else {

			$arrKeywordInfo['rkw_child_cliid'] = $rowItem['jgid'];
			$arrKeywordInfo['rkw_child_client'] = $rowItem['accountname'];

		}//End if

	} else {	#代理店顧客がないとき

			$arrKeywordInfo['rkw_child_cliid'] = 0;
			$arrKeywordInfo['rkw_child_client'] = "";

	}//End if
	/** ▲ JASへの代理店顧客情報保存準備 ****/

	#rkw_word キーワード名
	$arrKeywordInfo['rkw_word'] = $rowItem['seo_keyword'];
	#rkw_url
	$arrKeywordInfo['rkw_url']  = $rowItem['seo_url'];

	#$arrKeywordInfo['rkw_resistdate'] = date('Y-m-d');

	#rkw_contract_startdate 契約期間開始日
	$arrKeywordInfo['rkw_contract_startdate'] = $rowItem['start_date'];
	#rkw_contract_enddate   契約期間終了日
	$arrKeywordInfo['rkw_contract_enddate'] = $rowItem['expiry_date'];

	#rkw_url_strict	順位チェック方法
	$arrKeywordInfo['rkw_url_strict'] = $arrrUrlStrict[$rowItem['seo_rank_check']];

	#rkw_away_flag　解約処理
	if((int)$rowItem['seo_away_flag'] === 1) {	#有効がいいえのとき
		$arrKeywordInfo['rkw_away_flag'] = 1;	#JASの解約フラグをたてる
	} else {	#それ以外(有効がはいのとき)
		$arrKeywordInfo['rkw_away_flag'] = 0;	#JASの解約フラグを解除する
	}//End if

	#rkw_fee_month 月額固定金額
	$arrKeywordInfo['rkw_fee_month'] = (int)preg_replace('@,@', '',$rowItem['unit_price']);

	print "KwId : ".$arrKeywordInfo['rkw_id']."<br />";

		echo "<pre>";
		var_dump($salesorderflag);
		var_dump($arrKeywordInfo);
		echo "</pre>";

	
	/** ▼ JASへの反映 **/
	if($salesorderflag == true && $arrKeywordInfo['rkw_id'] === 0) {	#JASにKWが登録されているとき

		/** ▼ JASのキーワードデータの追加 *****/
		#rkw_input_flag　キーワード内訳
		$arrKeywordInfo['rkw_input_flag'] = 1;

		#rkw_segment セグメント
		$arrKeywordInfo['rkw_segment'] = 1;

		#rkw_random_flag ランダムフラグ デフォルト値：1 
		$arrKeywordInfo['rkw_random_flag'] = 1;

		$arrKeywordInfo['rkw_fee_status'] = '固定';
		$arrKeywordInfo['rkw_account_type'] = 4;
		
		foreach($arrKeywordInfo as $rkw_fields_name => $strKeywordData) {
			$_POST[$rkw_fields_name] = $strKeywordData;
		}//End foreach

		//キーワードIDの初期化
		unset($_POST['rkw_id']);

		print "Insert : " . DataJasInsertString('rank_keyword') . "<br /><br />";
		$_POST['rkw_id'] = DataJasInsert('rank_keyword');

		$queryInsert  = " UPDATE `vtiger_products` SET ";
		$queryInsert .= " seo_kwid = {$_POST['rkw_id']} ";
		$queryInsert .= " WHERE TRUE ";
		$queryInsert .= " AND productid = {$productid} ";

		print $queryInsert."<br /><br />";

		global $adb;
		$adb->pquery($queryInsert);

		/** ▲ JASのキーワードデータの追加 *****/

	} elseif($arrKeywordInfo['rkw_id'] === 0) {	#キーワードIDが0のときはなにもしない
		return false;
	} elseif($module === 'Products' || $module === 'Services') {	#製品登録のみキーワードの更新をおこなう
		/** ▼ JASのキーワードデータの更新 ***/

		/** ▼ 変更前キーワード情報の取得 ****/
#		require('cin/SQLConnect.php');
		require('cin/SQLConnect_jas.php');
		$sqlRkw = "SELECT * FROM rank_keyword WHERE TRUE AND rkw_id = {$arrKeywordInfo['rkw_id']} ";

		print $sqlRkw . "<br /><br />";

		$rsRkw = mysql_db_query($dbName, $sqlRkw);
		$rsCountRkw = mysql_num_rows($rsRkw);
		$rowRkw = mysql_fetch_array($rsRkw);
		$arrRkw = $rowRkw;

		if((int)$rowRkw['rkw_account_type'] !== 4) return false;

		require('cin/SQLClose.php');
		/** ▲ 変更前キーワード情報の取得 ****/

		/** ▼ ** キーワードの履歴を保存する ****/
		foreach($arrRkw as $rkw_fields_name => $strRkwValue) {
			unset($_POST[$rkw_fields_name]);
			$_POST[$rkw_fields_name] = $strRkwValue;
		}//End foreach

		$_POST[rkw_create_datetime] = date('Y-m-d H:i:s');
		print "Insert : " . DataInsertString('backup_keyword') . "<br /><br />";
		DataInsert('backup_keyword');
		/** ▲ ** キーワードの履歴を保存する ****/

		/** ▼ ** 変更後の反映 *********/
		foreach($arrKeywordInfo as $rkw_fields_name => $strKeywordData) {
			$_POST[$rkw_fields_name] = $strKeywordData;
		}//End foreach

		print "Update : " . DataJasUpdateString('rank_keyword') . "<br /><br />";
		DataJasUpdate('rank_keyword');
		/** ▲ ** 変更後の反映 **********/

	/** ▲ JASのキーワードデータの更新 ***/
	}//End if

	/** ▼ ** vitiger_crmentityへの契約期間を説明に反映 ***/
	$queryUpdate  = " UPDATE `vtiger_crmentity` SET ";
	$queryUpdate .= " description = CONCAT(";
	$queryUpdate .= " description, ' 契約期間:".$arrKeywordInfo['rkw_contract_startdate']."～".$arrKeywordInfo['rkw_contract_enddate']."' ";
	$queryUpdate .= " ) ";
	$queryUpdate .= " WHERE TRUE ";
	$queryUpdate .= " AND crmid = {$productid} ";

	print $queryUpdate."<br /><br />";

	global $adb;
	$adb->pquery($queryUpdate);
	/** ▲ ** vtiger_crmentityへの契約期間を説明の反映 ***/

	registDomain($_POST[rkw_id]);
	
	return (int)$_POST[rkw_id];
}//End function



function registDomain($intRkwid = 0) {

  if($intRkwid == 0) return;

	require('cin/SQLConnect_jas.php');

  $sqlRkw  = " SELECT rkw_id, rkw_word, rkw_domid, rkw_url, rkw_rclid FROM rank_keyword ";
  $sqlRkw .= " WHERE TRUE ";
  $sqlRkw .= " AND rkw_domid = 0 ";
  $sqlRkw .= " AND rkw_id = {$intRkwid} ";
  $sqlRkw .= " AND rkw_url != '' ";

  print $sqlRkw."<br />";

  $rsRkw = mysql_db_query($dbName, $sqlRkw);

  $rsRkwCount = mysql_num_rows($rsRkw);

  $rowRkw = mysql_fetch_array($rsRkw);

  if($rowRkw[rkw_word] == true && (int)$rowRkw[rkw_domid] === 0) {	#ドメインIDが保存されてないとき


      $strDomainName = getUrlDomain3($rowRkw[rkw_url]);

      $sqlDom  = " SELECT * FROM domain_mgt ";
      $sqlDom .= " WHERE TRUE ";
      $sqlDom .= " AND dom_domain = '{$strDomainName}'";

      $rsDom = mysql_db_query($dbName, $sqlDom);
      $rsCountDom = mysql_num_rows($rsDom);

      print $sqlDom ."<br />";

      if($rsCountDom === 0) {  #ドメインが保存されていない場合

        $_POST[dom_rclid] = $rowRkw[rkw_rclid];
        $_POST[dom_registdate] = date('Y-m-d');
        $_POST[dom_domain] = $strDomainName;

        print DataJasInsertString(domain_mgt)."<br />";
        $_POST[rkw_domid] = DataJasInsert(domain_mgt);

      } else {	#既にドメインが保存されているとき
      	$rowDom = mysql_fetch_array($rsDom);
      	$_POST[rkw_domid] = $rowDom['dom_id'];
      }//End if

    	$sqlUpDate  = " UPDATE rank_keyword SET rkw_domid = {$_POST[rkw_domid]} ";
    	$sqlUpDate .= " WHERE TRUE ";
    	$sqlUpDate .= " AND rkw_id = {$intRkwid} ";

      print $sqlUpDate . "<br />";
	    mysql_db_query($dbName, $sqlUpDate);

  }//End if
	require('cin/SQLClose.php');

}//End function

//URLを短縮
function getUrlDomain3($url){

  $url = preg_replace('@^https*://@i', '', $url);
  $url = preg_replace('@^www\.@i', '', $url);
  $arrUrl = explode("/", $url);

  return($arrUrl[0]);

}//End function