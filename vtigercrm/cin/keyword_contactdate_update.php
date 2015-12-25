<?php

ini_set('include_path', '/var/www/html/vtigercrm');
require_once('include/utils/utils.php');
require_once('include/logging.php');

  global $adb, $log;
  global $current_user;

#  /** ▼ ** SEO　成果　製品 KW 情報の取得 ** ▼ ****/
#  $sql   = " SELECT `productid`,`seo_kwid` FROM  ";
#  $sql  .= " `vtiger_products` ";
#  $sql  .= " WHERE TRUE ";
#  $sql  .= " AND `seo_kwid` != 0";

#  print $sql."<br /><br />";

#  $result = $adb->pquery($sql, array());
#  $rsCount = $adb->num_rows($result);

#  var_dump($rsCount);

#  for($i=0;$i<$rsCount;$i++) {
#    $row =  $adb->fetch_array($result);
#    $arr[$row[seo_kwid]] += 1;
#  }//End for


  require('SQLConnect_jas.php');

  $jasSql = " SELECT * FROM rank_keyword ";
#  $jasSql .= " WHERE rkw_id = 10552 ";

  $rsJas = mysql_db_query($dbName, $jasSql);
  $rsJasCount = mysql_num_rows($rsJas);

  for($j=0;$j<$rsJasCount;$j++) {
    $rowJas = mysql_fetch_array($rsJas);
    $arrJas[$rowJas[rkw_id]][rkw_contract_startdate] =$rowJas[rkw_contract_startdate];
    $arrJas[$rowJas[rkw_id]][rkw_contract_enddate] =$rowJas[rkw_contract_enddate];
  }//End for


  foreach($arrJas as $kwid => $arrJasValue) {
    $sqlUpdate  = " UPDATE vtiger_products SET ";
    $sqlUpdate .= " start_date = '".$arrJasValue[rkw_contract_startdate]."', ";
    $sqlUpdate .= " expiry_date = '".$arrJasValue[rkw_contract_enddate]."' ";
    $sqlUpdate .= " WHERE TRUE ";
    $sqlUpdate .= " AND seo_kwid = ".$kwid.";";

    print $sqlUpdate."<br />";

    $result1 = $adb->pquery($sqlUpdate, array());

    if($result1 == false) {
      print $kwid."固定 : エラー<br />";
    } //End if

    $sqlUpdate  = " UPDATE vtiger_service SET ";
    $sqlUpdate .= " start_date = '".$arrJasValue[rkw_contract_startdate]."', ";
    $sqlUpdate .= " expiry_date = '".$arrJasValue[rkw_contract_enddate]."' ";
    $sqlUpdate .= " WHERE TRUE ";
    $sqlUpdate .= " AND seo_kwid = ".$kwid.";";    

    print $sqlUpdate."<br />";

    $result2 = $adb->pquery($sqlUpdate, array());

    if($result2 == false) {
      print $kwid."成果 : エラー<br />";
    } //End if

  }//End foreach

  print "完了しました。";

  exit;
  
/*
  $result = $adb->pquery($sql, array());
  $no_of_invoice = $adb->num_rows($result);

  for($i=0;$i<$no_of_invoice;$i++) {
     $intInventoryproductrelId = (int)$adb->query_result($result, $i,'lineitem_id');
     $arrInvoiceSeoFee[$intInventoryproductrelId][rkwid] = $adb->query_result($result, $i,'seo_kwid');  #KwId
     $arrInvoiceSeoFee[$intInventoryproductrelId][invoiceid] = $adb->query_result($result, $i,'invoiceid'); #請求ID
  }//End for

var_dump($arrInvoiceSeoFee);
*/