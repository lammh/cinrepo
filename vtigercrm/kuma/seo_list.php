<?php

ini_set('include_path', '/var/www/html/vtigercrm');

$date = date('Y-m-d');

$dateStart = date('Y-m', strtotime($date))."-01";

$dateEnd   = date('Y-m-t', strtotime($date));

require_once('include/utils/utils.php');
require_once('include/logging.php');

global $adb;

/** ▼ ** SEO　成果　製品 KW 情報の取得 ** ▼ ****/

$sqlSeoData  = " SELECT `vtiger_inventoryproductrel`.`productid`, ";
$sqlSeoData .= " (CASE WHEN `vtiger_products`.`seo_kwid` is null THEN 'service' WHEN `vtiger_service`.`seo_kwid` is null THEN 'product' ELSE null END) as kind, ";
$sqlSeoData .= " (CASE WHEN `vtiger_products`.`seo_kwid` is null THEN `vtiger_service`.`seo_kwid` WHEN `vtiger_service`.`seo_kwid` is null THEN `vtiger_products`.`seo_kwid` ELSE null END) as kw_id, ";
$sqlSeoData .= " (CASE WHEN `vtiger_products`.`seo_kwid` is null THEN `vtiger_service`.`seo_keyword` WHEN `vtiger_service`.`seo_kwid` is null THEN `vtiger_products`.`seo_keyword` ELSE null END) as keyword ";
$sqlSeoData .= " FROM `vtiger_inventoryproductrel` ";
$sqlSeoData .= " INNER JOIN `vtiger_crmentity` ON `vtiger_inventoryproductrel`.`productid` = `vtiger_crmentity`.`crmid` AND `vtiger_crmentity`.`deleted` = 0 ";
$sqlSeoData .= " LEFT  JOIN `vtiger_products` ON `vtiger_inventoryproductrel`.`productid` = `vtiger_products`.`productid` ";
$sqlSeoData .= " LEFT  JOIN `vtiger_service` ON `vtiger_inventoryproductrel`.`productid` = `vtiger_service`.`serviceid` ";
$sqlSeoData .= " WHERE TRUE ";
$sqlSeoData .= " AND ((`vtiger_products`.`seo_kwid` is not null OR `vtiger_service`.`seo_kwid` is not null) AND (`vtiger_products`.`seo_kwid` != 0 OR `vtiger_service`.`seo_kwid` != 0) ) ";
$sqlSeoData .= " GROUP BY `vtiger_products`.`seo_kwid` ";

print $sqlSeoData."<br /><br />";

$resultSeoData = $adb->pquery($sqlSeoData, array());
$rsCountSeoData = $adb->num_rows($resultSeoData);

for($intSeo=0;$intSeo<$rsCountSeoData;$intSeo++) {
	$rowSeoData = $adb->fetch_array($resultSeoData);
	#print "kwid : ".$rowSeoData['kw_id']."<br />";
	$arrSeoData[$rowSeoData['kw_id']]['word'] = $rowSeoData['keyword'];
}//End for

require('cin/SQLConnect_jas.php');

$sqlKw  = " SELECT `rkw_id`, `rkw_word` FROM `rank_keyword` ";
$sqlKw .= " WHERE TRUE ";
$sqlKw .= " AND `rkw_segment` = 1 ";
$sqlKw .= " AND `rkw_contract_startdate` <= '{$dateStart}' ";
$sqlKw .= " AND `rkw_contract_enddate` >= '{$dateEnd}'";

print $sqlKw."<br />";

$rsKw = mysql_db_query($dbName, $sqlKw);

$rsKwCount = mysql_num_rows($rsKw);

for($intKw=0;$intKw<$rsKwCount;$intKw++) {
	$rowKw = mysql_fetch_array($rsKw);
	$arrKeywordData[$rowKw['rkw_id']]['word'] = $rowKw['rkw_word'];
}//End for

foreach($arrKeywordData as $intKwid => $arrKeywordDataValue) {
	print $intKwid . "|" . $arrKeywordDataValue['word'] ."|";
	print $arrSeoData[$intKwid]['word']."<br />";
}//End foreach