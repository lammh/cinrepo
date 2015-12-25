<?
function chargeMake($salesorderid=0){
 
  require_once('module.php');
  require_once('capCalc.php');
  print "|".$salesorderid."<br />";
  #print "キーワードの計算をします<br />";

  //キャップ金額の取得
  if($_GET[cap] !== "off") {
  	$arrKeywordMinus = capCalc($arrTodayCap);
    print "<p>キャップ計算完了</p>";
  } else {
	  print "<p><b>キャップ金額取得できていません。</b></p>";
  }//End if

#  echo "<pre>";
#  var_dump($arrKeywordMinus);
#  echo "";
#  exit;

  global $adb, $log;
  global $current_user;

  $log =& LoggerManager::getLogger('RecurringInvoice');
  $log->debug("invoked RecurringInvoice");

  /** ▼ ** SEO　成果　製品 KW 情報の取得 ** ▼ ****/
  $sql  = " SELECT `vtiger_inventoryproductrel`.`lineitem_id`, `invoiceid`, `serviceid`, `seo_kwid` FROM  `vtiger_inventoryproductrel` ";
  $sql .= " INNER JOIN `vtiger_invoice` ON `id` = `invoiceid` ";
  $sql .= " INNER JOIN `vtiger_service` ON `productid` = `serviceid` ";
  $sql .= " INNER JOIN `vtiger_crmentity` ON `invoiceid` = `crmid` ";
  $sql .= " INNER JOIN vtiger_invoice_recurring_info ON `vtiger_invoice`.`salesorderid` = `vtiger_invoice_recurring_info`.`salesorderid` ";
  $sql .= " WHERE TRUE ";
  if($salesorderid !== 0) $sql .= " AND  `vtiger_invoice`.`salesorderid` = {$salesorderid} ";
  $sql .= " AND  `servicecategory` = 'SEO' ";
  $sql .= " AND  `seo_kwid` != 0 ";  
#  $sql .= " AND  `division` = '202:Webコミュ' ";
  $sql .= " AND  `start_date` <= '".$_POST[end_date]."' ";  #契約開始日
  $sql .= " AND  `expiry_date` >= '".$_POST[first_date]."' "; #契約終了日
  $sql .= " AND  `deleted` = 0 ";
  
  print "<p>成果</p>";
  print $sql . "<br />";

  $result = $adb->pquery($sql, array());
  $no_of_invoice = $adb->num_rows($result);

  for($i=0;$i<$no_of_invoice;$i++) {
     $intInventoryproductrelId = (int)$adb->query_result($result, $i,'lineitem_id');
     $arrInvoiceSeoFee[$intInventoryproductrelId][rkwid] = $adb->query_result($result, $i,'seo_kwid');  #KwId
     $arrInvoiceSeoFee[$intInventoryproductrelId][invoiceid] = $adb->query_result($result, $i,'invoiceid'); #請求ID
  }//End for

  if($no_of_invoice !== 0) {
    foreach($arrInvoiceSeoFee as $intInventoryproductrelId => $arrInvoiceSeoFeeValue) {
  
      //課金額の計算
      $arrInvoiceSeoFee[$intInventoryproductrelId][fee] =  
        (int)keywordCalc($_POST[month], $arrInvoiceSeoFeeValue[rkwid], $_POST[first_date], $_POST[end_date], $yahooYen, $googleYen);

    }//End foreach
  }//End if

  /** ▲ ** SEO　成果　製品 KW 情報の取得 ** ▲ ****/


  /** ▼ ** SEO　固定　製品 KW 情報の取得 ** ▼ ****/
/*
  $sql  = " SELECT `vtiger_inventoryproductrel`.`lineitem_id`, `invoiceid`, `vtiger_products`.`productid`, `unit_price`, `seo_kwid` FROM  `vtiger_inventoryproductrel` ";
  $sql .= " INNER JOIN `vtiger_invoice` ON `id` = `invoiceid` ";
  $sql .= " INNER JOIN `vtiger_products` ON `vtiger_inventoryproductrel`.`productid` = `vtiger_products`.`productid` ";
  $sql .= " INNER JOIN `vtiger_crmentity` ON `invoiceid` = `crmid` ";
  $sql .= " INNER JOIN vtiger_invoice_recurring_info ON `vtiger_invoice`.`salesorderid` = `vtiger_invoice_recurring_info`.`salesorderid` ";
  $sql .= " WHERE TRUE ";
  if($salesorderid !== 0) $sql .= " AND  `vtiger_invoice`.`salesorderid` = {$salesorderid} ";
  $sql .= " AND  `start_date` <= '".$_POST[end_date]."' ";
  $sql .= " AND  `expiry_date` >= '".$_POST[first_date]."' ";
  $sql .= " AND  `deleted` = 0 ";

  print "<p>固定</p>";
  print $sql . "<br />";

  $result = $adb->pquery($sql, array());
  $no_of_invoice = $adb->num_rows($result);

  for($i=0;$i<$no_of_invoice;$i++) {
     $intInventoryproductrelId = (int)$adb->query_result($result, $i,'lineitem_id');
     $arrInvoiceSeoFee[$intInventoryproductrelId][rkwid] = $adb->query_result($result, $i,'seo_kwid');  #KwId
     $arrInvoiceSeoFee[$intInventoryproductrelId][invoiceid] = $adb->query_result($result, $i,'invoiceid'); #請求ID
     $arrInvoiceSeoFee[$intInventoryproductrelId][fee] = $adb->query_result($result, $i,'unit_price'); #請求ID
  }//End for
*/
  /** ▲ ** SEO　固定　製品 KW 情報の取得 ** ▲ ****/


  /** ▼ ** SEO　初期　製品 KW 情報の取得 ** ▼ ****/
  /*
  $sql  = " SELECT `vtiger_inventoryproductrel`.`lineitem_id`, `invoiceid`, `vtiger_products`.`productid`, `unit_price`, `seo_kwid` FROM  `vtiger_inventoryproductrel` ";
  $sql .= " INNER JOIN `vtiger_invoice` ON `id` = `invoiceid` ";
  $sql .= " INNER JOIN `vtiger_products` ON `vtiger_inventoryproductrel`.`productid` = `vtiger_products`.`productid` ";
  $sql .= " INNER JOIN `vtiger_crmentity` ON `invoiceid` = `crmid` ";
  $sql .= " INNER JOIN vtiger_invoice_recurring_info ON `vtiger_invoice`.`salesorderid` = `vtiger_invoice_recurring_info`.`salesorderid` ";
  $sql .= " WHERE TRUE ";
  if($salesorderid !== 0) $sql .= " AND  `vtiger_invoice`.`salesorderid` = {$salesorderid} ";
  $sql .= " AND  `first_fee_date` >= '".$_POST[first_date]."' ";
  $sql .= " AND  `first_fee_date` <= '".$_POST[end_date]."' ";
  $sql .= " AND  `deleted` = 0 ";

  print "<p>初期</p>";
  print $sql . "<br />";

  $result = $adb->pquery($sql, array());
  $no_of_invoice = $adb->num_rows($result);

  for($i=0;$i<$no_of_invoice;$i++) {
     $intInventoryproductrelId = (int)$adb->query_result($result, $i,'lineitem_id');
     $arrInvoiceSeoFee[$intInventoryproductrelId][rkwid] = $adb->query_result($result, $i,'seo_kwid');  #KwId
     $arrInvoiceSeoFee[$intInventoryproductrelId][invoiceid] = $adb->query_result($result, $i,'invoiceid'); #請求ID
     $arrInvoiceSeoFee[$intInventoryproductrelId][fee] = $adb->query_result($result, $i,'unit_price'); #請求ID
  }//End for
  /** ▲ ** SEO　初期　製品 KW 情報の取得 ** ▲ ****/

  /** ▼  **/
  if(count($arrInvoiceSeoFee) !== 0) {
    /** ▼ SEO成果の金額反映 **/
    foreach($arrInvoiceSeoFee as $intInventoryproductrelId => $arrInventSeoFeeValue) {

      if($arrInventSeoFeeValue[fee] === 0) continue; 

      $sqlUpdate  = " UPDATE vtiger_inventoryproductrel SET listprice = " . (int)$arrInventSeoFeeValue[fee] . " ";
      $sqlUpdate .= " , discount_amount = " . (int)$arrKeywordMinus[$arrInventSeoFeeValue[rkwid]] . " ";  #キャップ金額の追加
      $sqlUpdate .= " WHERE TRUE ";
      $sqlUpdate .= " AND lineitem_id = $intInventoryproductrelId ";

      print $sqlUpdate . "<br />";

      $adb->pquery($sqlUpdate);
      
      $arrInvoiceFee[$arrInventSeoFeeValue[invoiceid]] += (int)$arrInventSeoFeeValue[fee];

    }//End foreach
    /** ▲ SEO成果の金額反映　**/
  }//End if

}//End function
