<?php
function keywordRegist($intCinId) 
{
  require('cin/defalut.php');

	$arrrUrlStrict = array(
		"part" => "0",
		"domain" => "1",
		"perfect" => "2",
		"character" => "3"
	);

	$arrayAccountType = array(
		"daily" => "1",
		"either_daily" => "5",
		"either_n_daily" => "3",
		"month" => "4",
		"n_daily" => "2",
	);

	require('cin/SQLConnect.php');

		if((int)$_POST['record'] !== 0) {	#更新
		  //キーワードIDの取得
			print "<pre>";
			var_dump($_POST);
			print "</pre>";

			//成果の場合

			//更新のためにキーワードIDを取得する ▼
			$sqlRkw  = " SELECT * FROM rank_keyword ";
			$sqlRkw .= " WHERE TRUE ";
			$sqlRkw .= " AND rkw_cinid = $intCinId ";

			print "<p>" . $sqlRkw . "</p>";

			$rsRkw = mysql_db_query($dbName, $sqlRkw);

			$rsCountRkw = mysql_num_rows($rsRkw);

			$rowRkw = mysql_fetch_array($rsRkw);

			$_POST[rkw_id] = $rowRkw[rkw_id];
			$_POST[rfe_rkwid] = $rowRkw[rkw_id];
			//更新のためにキーワードIDを取得する ▲
		}//End if

		$queryItem  = " SELECT * FROM `vtiger_service` ";
		$queryItem .= " WHERE TRUE ";
		$queryItem .= " AND serviceid = {$intCinId} ";

		print "<p>SQL : {$queryItem}</p>";

		global $adb;
		$resultItem=$adb->pquery($queryItem);
		$countItemNum = $adb->num_rows($resultItem);
		if($countItemNum === 1) $rowItem =  $adb->fetch_array($resultItem);

		$queryItem  = " SELECT * FROM `vtiger_products` ";
		$queryItem .= " WHERE TRUE ";
		$queryItem .= " AND productid = {$intCinId} ";

		print $queryItem."<br />";

#var_dump($_POST[rkw_child_client]);
#		print "<p>SQL : {$queryItem}</p>";
#exit;
		global $adb;
		$resultItem=$adb->pquery($queryItem);
		$countItemNum = $adb->num_rows($resultItem);
		if($countItemNum === 1) $rowItem =  $adb->fetch_array($resultItem);
		
		if($_POST[seo_agency_customers] == true || $rowItem[seo_agency_customers] == true) {
			$sqlRcl  = " SELECT * FROM `rank_client` ";
			$sqlRcl .= " WHERE TRUE ";
			if($_POST[seo_agency_customers] == true) $sqlRcl .= " AND rcl_accountid = {$_POST[seo_agency_customers]} ";
			if($rowItem[seo_agency_customers] == true) $sqlRcl .= " AND rcl_accountid = {$rowItem[seo_agency_customers]} ";

				print $sqlRcl . "<br />";
#exit;
			$rsRcl = mysql_db_query($dbName, $sqlRcl);
			$rsRclCount = mysql_num_rows($rsRcl);

			for($intRcl=0;$intRcl<$rsRclCount;$intRcl++) {
				$rowRcl = mysql_fetch_array($rsRcl);
				#rkw_child_cliid	子クライアントID
				$_POST[rkw_child_cliid] = $rowRcl[rcl_id];
				#rkw_child_client 子クライアント名
				$_POST[rkw_child_client] = $rowRcl[rcl_name];
			}//End for
			//更新のためにキーワードIDを取得する ▲

			if(!$_POST[rkw_child_client]) {
				print "<p>代理店顧客情報がありません。システム担当者へ相談下さい。</p>";
				exit;
			}//End if
		}//End if

		if(is_null($rowItem[seo_keyword]) || is_null($rowItem[seo_url])) {
		print "<p>ppp</p>";
			return false;
		}//End if

		#rkw_word キーワード名
		$_POST[rkw_word] = $rowItem[seo_keyword];
		$_POST[rkw_url]  = $rowItem[seo_url];

		#rkw_input_flag　キーワード内訳
		$_POST['rkw_input_flag'] = 1;

		#rkw_segment セグメント
		$_POST['rkw_segment'] = 1;

		#rkw_account_date　n日課金の日数
		$_POST['rkw_account_date'] = $rowItem['seo_rank_daynum'];

		#rkw_random_flag ランダムフラグ デフォルト値：1 
		$_POST['rkw_random_flag'] = 1;

		#rkw_url_strict	順位チェック方法
		$_POST['rkw_url_strict'] = $arrrUrlStrict[$rowItem[seo_rank_check]];

		#rkw_account_type 課金方法
		$_POST['rkw_account_type'] = $arrayAccountType[$rowItem['seo_fee_method']];

		#rkw_fee_status
		if($_POST['rkw_account_type'] == 5) $_POST['rkw_fee_status'] = 'Y/G';
		if($_POST['module'] === 'Products') {
			$_POST['rkw_fee_status'] = '固定';
			$_POST['rkw_account_type'] = 4;
		}//End if
		$_POST[rkw_cinid] = $intCinId;

		if($_POST[unit_price] == true) $_POST[rkw_fee_month] = preg_replace('@,@', '',$_POST[unit_price]);

		if($_POST['module'] === 'Products' || $_POST[module] === "Services") {
			unset($_POST[rkw_rclid]);
			unset($_POST[rkw_client]);
		} elseif($_POST[rkw_rclid] == false || $_POST[rkw_client] == false) {
			print "<p>クライアント情報がありません。システムまで相談下さい。</p>";
			exit;
		}//End if

		if((int)$_POST['record'] === 0) {	#登録

			$_POST[rkw_resistdate] = date(Y-m-d);

			print DataInsertString('rank_keyword') . "<br />";
			$_POST[rfe_rkwid] = DataInsert('rank_keyword');
	#		exit;
		} else { #更新
			unset($_POST[rkw_resistdate]);
#			print "update : " . DataUpdateString('rank_keyword') . "<br />";
			DataUpdate('rank_keyword');
	#		exit;
		}//End if

		//成果のみ実行 ▼
		if($_POST[data-module-name] === "Services" || $rowRkw[rkw_account_type] !== 4) {
			//更新前にすべて削除フラグをたてる
			$updateRfe  = " UPDATE rank_fee SET rfe_status = 9 ";
			$updateRfe .= " WHERE TRUE ";
			$updateRfe .= " AND rfe_status = 1 ";
			$updateRfe .= " AND rfe_rkwid  = {$_POST[rkw_id]} ";

			#print $updateRfe . "<br />";

			mysql_db_query($dbName, $updateRfe);

			for($intRfeId =1;$intRfeId<=5;$intRfeId++) {

		#		print "<p>".'seo_fee_'.$intRfeId."</p>";
				if($rowItem['seo_fee_'.$intRfeId]) {

					$arrRfe[$intRfeId][rfe_rank_start] = $rowItem['seo_rank_top_'.$intRfeId];
					$arrRfe[$intRfeId][rfe_rank_end]   = $rowItem['seo_rank_down_'.$intRfeId];
					$arrRfe[$intRfeId][rfe_yen]        = $rowItem['seo_fee_'.$intRfeId];

					$_POST[rfe_rank_start] = $rowItem['seo_rank_top_'.$intRfeId];
					$_POST[rfe_rank_end]   = $rowItem['seo_rank_down_'.$intRfeId];
					$_POST[rfe_yen]        = $rowItem['seo_fee_'.$intRfeId];

					$_POST[rfe_status]   = 1;
					$_POST[rfe_datetime] = date('Y-m-d H:i:s');

					$_POST[rfe_provider_kind] = 1;
					print DataInsertString('rank_fee') . "<br />";
					DataInsert('rank_fee');

					$_POST[rfe_provider_kind] = 2;
					print DataInsertString('rank_fee') . "<br />";
					DataInsert('rank_fee');

				}//End if
			}//End for
		}//End if
		
		//ドメイン情報の登録
		registDomain($_POST[rkw_id]);

		require('cin/SQLClose.php');
		//成果のみ実行 ▲
#		echo "<pre>";
#		var_dump($_POST);
#		echo "</pre>";
}//End function

function registCheck($intCinId) {
	require('cin/SQLConnect.php');
	$sql  = " SELECT * FROM rank_keyword ";
	$sql .= " WHERE TRUE ";
	$sql .= " AND rkw_cinid = {$intCinId} ";

	print $sql."<br />";

	$rs = mysql_db_query($dbName, $sql);
	$rsCount = mysql_num_rows($rs);

	require('cin/SQLClose.php');
	if($rsCount !== 0) {
		return true;
	} else {
		return false;
	}//End if
}//End function


function registDomain($intRkwid = 0) {

  if($intRkwid == 0) return;

	require('cin/SQLConnect.php');

  $sqlRkw  = " SELECT rkw_id, rkw_domid, rkw_url, rkw_rclid FROM rank_keyword ";
  $sqlRkw .= " WHERE TRUE ";
  $sqlRkw .= " AND rkw_domid = 0 ";
  if($intRkwid)  $sqlRkw .= " AND rkw_id = {$intRkwid} ";
  $sqlRkw .= " AND rkw_url != '' ";

  print $sqlRkw."<br />";
  #exit;

  $rsRkw = mysql_db_query($dbName, $sqlRkw);

  $rsRkwCount = mysql_num_rows($rsRkw);

  if($rsRkwCount) {

    for($intRkw=0;$intRkw<$rsRkwCount;$intRkw++) {
      $rowRkw = mysql_fetch_array($rsRkw);

      $strDomain = getUrlDomain3($rowRkw[rkw_url]);

      $sqlDom  = " SELECT * FROM domain_mgt ";
      $sqlDom .= " WHERE TRUE ";
      $sqlDom .= " AND dom_domain = '{$strDomain}'";

      $rsDom = mysql_db_query($dbName, $sqlDom);
      $rsCountDom = mysql_num_rows($rsDom);

      print $sqlDom ."<br />";


      if(!$rsCountDom) {  #ドメインが保存されていない場合

        $_POST[dom_rclid] = $rowRkw[rkw_rclid];
        $_POST[dom_registdate] = date('Y-m-d');
        $_POST[dom_domain] = $strDomainName;

        print DataInsertString(domain_mgt)."<br />";
        $_POST[rkw_domid] = DataInsert(domain_mgt);

        $sqlUpDate  = " UPDATE rank_keyword SET rkw_domid = {$_POST[rkw_domid]} ";
        $sqlUpDate .= " WHERE TRUE ";
        $sqlUpDate .= " AND rkw_id = {$intRkwid} ";

#        print $sqlUpDate . "<br />";
        mysql_db_query($dbName, $sqlUpDate);
      }//End if

    }//End for
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