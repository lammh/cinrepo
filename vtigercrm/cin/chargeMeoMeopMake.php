<?php

meo_meop_fee();

function meo_meop_fee($salesorderid=0) {
	require('module_MeoMeop_feeCalc.php');  #大熊追加

	print "<p>テスト用</p>";
	$_POST[first_date] = "2015-10-01";
	$_POST[end_date] = "2015-10-31";
	$_POST[month]    = "2015-10";

  global $adb, $log;
  global $current_user;

  $log =& LoggerManager::getLogger('RecurringInvoice');
  $log->debug("invoked RecurringInvoice");

  /** ▼ ** SEO　成果　製品 KW 情報の取得 ** ▼ ****/
  $sql  = " SELECT `vtiger_inventoryproductrel`.`lineitem_id`, `invoiceid`, `vtiger_products`.`productid`, `seo_kwid` FROM  `vtiger_inventoryproductrel` ";
  $sql .= " INNER JOIN `vtiger_invoice` ON `id` = `invoiceid` ";
  $sql .= " INNER JOIN `vtiger_products` ON `vtiger_inventoryproductrel`.`productid` = `vtiger_products`.`productid` ";
  $sql .= " INNER JOIN `vtiger_crmentity` ON `invoiceid` = `crmid` ";
  $sql .= " INNER JOIN vtiger_invoice_recurring_info ON `vtiger_invoice`.`salesorderid` = `vtiger_invoice_recurring_info`.`salesorderid` ";
  $sql .= " WHERE TRUE ";
  if($salesorderid !== 0) $sql .= " AND  `vtiger_invoice`.`salesorderid` = {$salesorderid} ";
  $sql .= " AND  `productcategory` IN ('MEO', 'MEOP') ";
  $sql .= " AND  `seo_kwid` != 0 ";  
#  $sql .= " AND  `division` = '202:Webコミュ' ";
  $sql .= " AND  `start_date` <= '".$_POST[end_date]."' ";  #契約開始日
  $sql .= " AND  `expiry_date` >= '".$_POST[first_date]."' "; #契約終了日
  $sql .= " AND  `deleted` = 0 ";
  
  print $sql . "<br />";

  $result = $adb->pquery($sql, array());
  $no_of_invoice = $adb->num_rows($result);

  exit;

  for($i=0;$i<$no_of_invoice;$i++) {
     $intInventoryproductrelId = (int)$adb->query_result($result, $i,'lineitem_id');
     $arrInvoiceSeoFee[$intInventoryproductrelId][rkwid] = $adb->query_result($result, $i,'seo_kwid');  #KwId
     $arrInvoiceSeoFee[$intInventoryproductrelId][invoiceid] = $adb->query_result($result, $i,'invoiceid'); #請求ID
  }//End for


  exit;


	$arrMeoMeop = getMeoMeopData();

	echo "<pre>";
	var_dump($arrMeoMeop);
	echo "</pre>";

}//function

function getMeoMeopData() {

	$objDao = new mapeoDao;

	$strFirstDay = $_POST[first_date];
	$strLastDay  = $_POST[end_date];

	$str30Day = date("Y/m/t" , strtotime("+1 month " . $strFirstDay));
	$str35Day = date("Y/m/05", strtotime("+2 month " . $strFirstDay));
	$str40Day = date("Y/m/10", strtotime("+2 month " . $strFirstDay));
	$str45Day = date("Y/m/15", strtotime("+2 month " . $strFirstDay));
	$str60Day = date("Y/m/t", strtotime("+2 month " . $strFirstDay));

	print $strLastDay . "<br>";
	print $strNextLastDay . "<br>";
	print $str2Next10Day . "<br>";

	require('SQLConnect_googlemap.php');

	#gcl_status 稼働確認 1:稼働中, gcl_agency_flag 代理店フラグ　1:代理店, gcl_parent_client_id 親クライアント名ID
	$sql = "SELECT * FROM `gm_client` Where(gcl_status = 1 and (gcl_agency_flag = 1 or gcl_parent_client_id = 1)) Order By `gcl_id` DESC;";
	#$sql = "SELECT * FROM `gm_client` Where gcl_id = 964 ";
	#print $sql . "<br><br>";

	//SQL文を実行する
	$rs = mysql_db_query($dbName,$sql);
	$rsCount = mysql_num_rows($rs);

	#var_dump($rsCount);

	# クライアント全体をループ処理
	for ($i=0; $i<=$rsCount-1; $i++) {

	  $flgMeo  = false; # MEO案件フラグ
	  $flgMeoP = false; # MEOプレミアム案件フラグ

	  $tempSumYen = 0;
	  $row = @mysql_fetch_array($rs);

	  $arrContent = Array();
	  $arrYen = Array();
	  $strBillDay = date('Y年m月d日');

	  # 請求書の右上の種別コード初期化
	  if ($row[gcl_agency_flag]==0) {
	    $strCode = "AM";
	  } else {
	    $strCode = "BM";
	  }//if

	  # クライアント単位で変わる各種変数初期化
	  $strPostCode = "";
	  $strAddress = "";
	  $strCompanyName = "";
	  $strChargerPost = "";
	  $strChargerName = "";
	  $strLastFee     = "";

	  # ループ処理内（≒クライアント）に紐付く請求者を取得
	  $sql2 = "SELECT * FROM `gm_charger_mas` Where (gch_client_id = '" . $row[gcl_id] . "');";
	  #print $sql2 . "<br><br>";

	  $rs2 = mysql_db_query($dbName,$sql2);
	  $rsCount2 = mysql_num_rows($rs2);
	  $isCharger = true;

	  if ($rsCount2==0) {
#	    print "担当者：担当者なし<br>";
	    $isCharger = false;

	  } else {

	    if ($rsCount2 >= 2) print "<font color=red>担当者：担当者複数</font><br>";

	    $row2 = @mysql_fetch_array($rs2);
#	    print "<font color=red>担当者：" . $row2[gch_name] . "</font><br>";
	    $strPostCode = "〒" . $row2[gch_postcode];
	    $strAddress = $row2[gch_address];
	    $strCompanyName = $row[gcl_name];
	    $strChargerPost = $row2[gch_post];
	    $strChargerName = $row2[gch_name] . "様";

	    # 締め日が登録されていないケース（至急受注担当者へ締め日の確認）
	    if($row[gcl_deadline_kind] == 0){
	      $strLastFee = "不明";
#	      print "締め日不明<br>";
	    }//if

	    # 30、35、40、45、60日締め
	    if($row[gcl_deadline_kind] == 1) $strLastFee = $str30Day;
	    if($row[gcl_deadline_kind] == 2) $strLastFee = $str35Day;
	    if($row[gcl_deadline_kind] == 3) $strLastFee = $str40Day;
	    if($row[gcl_deadline_kind] == 4) $strLastFee = $str45Day;
	    if($row[gcl_deadline_kind] == 5) $strLastFee = $str60Day;

	  }//if

	  # 請求書右上の請求コード用文字列生成
	  $strBillNumber = $strCode . "-" .
	                   substr("00000" . $row[gcl_id], -5) . "-" .
	                   substr($_POST[month], 2,2) .
	                   substr($_POST[month], 5,2) . "-" . 
	                   "00001";

	  ###############################
	  //例外・RITZはエルミタージュと混ぜる
	  if ($row[gcl_name]=="RITZ") continue;
	  //リブレットは別で請求書でてるからいらない
	  if ($row[gcl_name]=="株式会社リブレット") continue;
	  //例外・RITZはエルミタージュと混ぜる
	  if ($row[gcl_name] == "エルミタージュ") {
	    $sql2 = "SELECT * FROM `gm_client` Where(gcl_name = 'RITZ' or gcl_name = 'エルミタージュ');";
	  } else {
	    $sql2 = "SELECT * FROM `gm_client` Where(gcl_parent_client_id = " . $row[gcl_id] . " or gcl_id = " . $row[gcl_id] . ");";
	  }
	  ###############################


	  $rs2 = mysql_db_query($dbName,$sql2);
	  $rsCount2 = mysql_num_rows($rs2);
#	  print $rsCount2 . "件<br>";
	  $jCounter = 0;


	  # 請求者単位でループ処理
	  for ($j=0; $j<=$rsCount2-1; $j++) {
	    $row2 = @mysql_fetch_array($rs2);

	    $intSumYen=0;
	    $intSumYenP=0;

#	    print $row2[gcl_name];
	    $sql3 = "SELECT * FROM `gm_cap_mas` Where(gcm_client_id = " . $row2[gcl_id] . ");";

	    $rs3 = mysql_db_query($dbName,$sql3);
	    $rsCount3 = mysql_num_rows($rs3);
	    
	    for ($k=0; $k<=$rsCount3-1; $k++){
	      $row3 = @mysql_fetch_array($rs3);

	      ### MEO用
	        $arrTempFee = $objDao->clientFeeForCap($row2[gcl_id], $row3[gcm_id], $_POST[month]);

	        $tempYen = 0;
	        if($arrTempFee[cap] < $arrTempFee[sum]){
	          //オーバーしていればキャップの金額
	          $tempYen = $arrTempFee[cap];
	          $intSumYen += $tempYen;
	        }else{
	          //オーバーしていなければ通常の金額
	          $tempYen = $arrTempFee[sum];
	          $intSumYen += $tempYen;
	        }//if


	        if ($tempYen > 0) $flgMeo = true; # MEO案件フラグ


	      ### MEOプレミアム用
	        $arrTempFeeP = $objDao->clientFeeForCapFeatMEOP($row2[gcl_id], $row3[gcm_id], $_POST[month]);

	        $tempYenP = 0;
	        if($arrTempFeeP[cap] < $arrTempFeeP[sum]){
	          //オーバーしていればキャップの金額
	          $tempYenP = $arrTempFeeP[cap];
	          $intSumYenP += $tempYenP;
	        }else{
	          //オーバーしていなければ通常の金額
	          $tempYenP = $arrTempFeeP[sum];
	          $intSumYenP += $tempYenP;
	        }//if


	        if ($tempYenP > 0) $flgMeoP = true; # MEOプレミアム案件フラグ


	    }//for



			  if($flgMeoP && $intSumYenP !== 0) {
			  		print "MEOP : ".$row2[gcl_name]."<br />";
				    $arrMeoMeop[$row2[gcl_id]][meop] += $intSumYenP;
				} elseif($intSumYen !== 0) {
						print "MEO  : ".$row2[gcl_name]."<br />";
				    $arrMeoMeop[$row2[gcl_id]][meo]  += $intSumYen;
				}//ENd if

	    //自分自身で0円のときは金額も表示しない
	    if($row2[gcl_name] == $row[gcl_name] && ($intSumYen + $intSumYenP) == 0) continue;

	    ### 20110302 ADD ARIHARA
	    ### クライアント名で処理を分岐したらダメって言ったでしょ！！
	    ### 一先ず、コメントアウトします。
	#    if($row2[gcl_name] == "株式会社セイドウ") continue;
	#    if($row2[gcl_name] == "リプラスウィング") continue;
	#    if($row2[gcl_name] == "リプラスウィング　梅田店") continue;
	#    if($row2[gcl_name] == "チケットショップ　ライフインテリジェンス") continue;
	#    if($row2[gcl_name] == "Ottoman Konak(オスマン コナック)") continue;
	#    if($row2[gcl_name] == "CRP流通サービス") continue;

	    ### 20110302 ADD ARIHARA
	    ### 親クライアントがビジョン(client_id : 14)の場合、
	    ### 明細行数が足りないので請求額0円のクライアントは明細非表示とする
	    if ( ((int)$row2["gcl_parent_client_id"] ===  14) && (int)$intSumYen == 0 ) continue;
	    if ( ((int)$row2["gcl_parent_client_id"] ===  21) && (int)$intSumYen == 0 ) continue;
	    if ( ((int)$row2["gcl_parent_client_id"] ===  68) && (int)$intSumYen == 0 ) continue;
	#    if ( ((int)$row2["gcl_parent_client_id"] === 371) && (int)$intSumYenP == 0 ) continue;

	    $arrContent[$jCounter] = $row2[gcl_name];
	    $arrYen[$jCounter]  = $intSumYen;
	    $arrYenP[$jCounter] = $intSumYenP;
	    $jCounter++;
	 
	    $tempSumYen += ($intSumYen + $intSumYenP);
	  }//for 請求者単位
	  
	 
	  $intAllYen += $tempSumYen;

	  if ($isCharger == false && $tempSumYen !=0 ) {
	    print "<font color=red>担当者が登録されていないのに売上が発生しました。</font>";
	  }//End if

	}//for クライアント単位

	require('SQLClose.php');

	return $arrMeoMeop;

}//End function