<?

function clientCalc($cid, $month, &$yen, &$date){
//全ての金額を出すためUpdate、Deleteはしない

//過去のデータを削除

//DELETE
require('SQLConnect.php');
//全ての金額を出すためUpdate、Deleteはしない
//$sql = "DELETE From `rank_calc_month` WHERE (`rcm_month` ='" . $month . "' and rcm_rclid = '" . $cid . "');";
//print $sql . "<BR><BR>";
  //$c_hit = mysql_db_query($dbName,$sql) or die('UPDATE error: '.mysql_errno().', '.mysql_error());

mysql_close($dbHandle);


require('SQLConnect_jas.php');
//対象料金テーブルを取得
if ($cid == 0){
$sql = "SELECT * FROM `rank_keyword`;";
}else{
$sql = "SELECT * FROM `rank_keyword` Where(rkw_rclid = '" . $cid . "');";
}
//print $sql;

//print $sql;
//SQL文を実行する
$rs = mysql_db_query($dbName,$sql);
$rsCount = mysql_num_rows($rs);		//レコード数
$yenAll = 0;
for ($i=0; $i<=$rsCount-1; $i++){
  $row = @mysql_fetch_array($rs);
  //print $row[rkw_client] . "<br>";

  //print "開始日：" . $row[rkw_contract_startdate] . "<br>";
  //print "終了日：" . $row[rkw_contract_enddate] . "<br><br>";
  $yenAll += keywordCalc($month, $row[rkw_id], $row[rkw_contract_startdate], $row[rkw_contract_enddate]);
  
  
  
}


require('SQLClose.php');

//print "合計：" . $yenAll . "<br>";

$_POST[rcm_month] = $month;
$_POST[rcm_rclid] = $cid;
$_POST[rcm_makedatetime] = DateNow(0);
$_POST[rcm_yen] = $yenAll;
//全ての金額を出すためUpdate、Deleteはしない
//DataInsert("rank_calc_month");

$yen = $yenAll;
$date = $_POST[rcm_makedatetime];

}//End function

/** START 成果課金額の計算 *******/
function keywordCalc($month, $rkw_id, $startdate, $enddate, &$yahooYen=0, &$googleYen=0, &$gooYen=0){

$yahooYen = 0;
$googleYen = 0;
$gooYen = 0;
$tempAll = 0;

$firstday = $month . "-01";
$lastday = date('Y-m-t', strtotime($firstday));

require('SQLConnect_jas.php');
$sql  = " SELECT rkw_id, rkw_account_type, rkw_account_date, rank_fee.* FROM rank_keyword ";
$sql .= "INNER JOIN `rank_fee` ON rank_keyword.rkw_id = rank_fee.rfe_rkwid ";
$sql .= " Where TRUE ";
$sql .= " AND (rkw_id = '" . $rkw_id . "' ";
$sql .= " AND rfe_status = '1');";

#print $sql . "<br>";

//SQL文を実行する
$rs = mysql_db_query($dbName,$sql);
$rsCount = mysql_num_rows($rs);		//レコード数

if($rsCount === 0) return;

for ($i=0; $i<=$rsCount-1; $i++){
  $row = @mysql_fetch_array($rs);
  
  //日毎どちらか課金の時、Googleは見ない。
  if($row[rkw_account_type] == "5" && $row[rfe_provider_kind] == "2")continue;
  //日毎どちらか課金の時、Gooは見ない。
  if($row[rkw_account_type] == "5" && $row[rfe_provider_kind] == "3")continue;
 
  $sql2 = "SELECT * FROM `rank_crawl` Where(rcr_datetime Like '" . $month . "%'" .
         " and rcr_datetime between '" . $startdate . " 00:00:00' and '" . $enddate . " 23:59:59'" . 
         " and  rcr_rkwid = " . $rkw_id . ");";
  
  #print $sql2 . "<br />";
#  exit;

  //SQL文を実行する
  $Counter = 0;
  $rs2 = mysql_db_query($dbName,$sql2);
  $rsCount2 = mysql_num_rows($rs2);		//レコード数
  for ($j=0; $j<=$rsCount2-1; $j++){
    $row2 = @mysql_fetch_array($rs2);

    $id = $row2[rcr_rkwid];
    
    if ($row[rkw_account_type]=="5"){
      //どちらも同じ料金である前提で計算をおこなう
      $isCount = false;
      $betterRanking = $row2[rcr_yahooranking];
      if($betterRanking > $row2[rcr_googleranking])$betterRanking = $row2[rcr_googleranking];
      if($betterRanking >= $row[rfe_rank_start] &&
         $betterRanking <= $row[rfe_rank_end]){
        $Counter++;
      }
    }else{
      //Yahooの計算
      if($row[rfe_provider_kind]=="1" &&
         $row2[rcr_yahooranking] >= $row[rfe_rank_start] &&
         $row2[rcr_yahooranking] <= $row[rfe_rank_end]){
             $Counter++;
             //print "Y:" . $row2[rcr_datetime] . $row2[rcr_clientname] . " : " . $row[rfe_yen] . "<br>";
      }
      
      //Googleの計算
      if($row[rfe_provider_kind]=="2" &&
         $row2[rcr_googleranking] >= $row[rfe_rank_start] &&
         $row2[rcr_googleranking] <= $row[rfe_rank_end]){
           $Counter++;
           //print "G:" . $row[rcr_datetime] . $row[rcr_clientname] . " : " . $row[rfe_yen] . "<br>";
      }

      //Gooの計算
      if($row[rfe_provider_kind]=="3" &&
         $row2[rcr_gooranking] >= $row[rfe_rank_start] &&
         $row2[rcr_gooranking] <= $row[rfe_rank_end]){
           $Counter++;
           //print "Goo:" . $row[rcr_datetime] . $row[rcr_clientname] . " : " . $row[rfe_yen] . "<br>";
      }

    }

  }
  
  //計算
  
  //日毎課金
  if ($row[rkw_account_type]=="1"){
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "日";
    //print "×" . $row[rfe_yen] . "円＝" . ($row[rfe_yen] * $Counter)  . "円<br>";
    $tempAll += ($row[rfe_yen] * $Counter);
    if($row[rfe_provider_kind]=="1") $yahooYen += $row[rfe_yen] * $Counter;
    if($row[rfe_provider_kind]=="2") $googleYen += $row[rfe_yen] * $Counter;
    if($row[rfe_provider_kind]=="3") $gooYen += $row[rfe_yen] * $Counter;
  }
  
  //日毎課金
  if ($row[rkw_account_type]=="5"){
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "日";
    //print "×" . $row[rfe_yen] . "円＝" . ($row[rfe_yen] * $Counter)  . "円<br>";
    $tempAll += ($row[rfe_yen] * $Counter);
    //全部Yahooとしてみるよ
    $yahooYen += $row[rfe_yen] * $Counter;
    
  }
  
  //n日課金
  if ($row[rkw_account_type]=="2"){
    $tempYen = 0;
    if ($Counter >=$row[rkw_account_date]) $tempYen = $row[rfe_yen]*1;
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "/10日";
    //print "×" . $row[rfe_yen] . "円＝" . $tempYen  . "円<br>";
    $tempAll += $tempYen;
    if($row[rfe_provider_kind]=="1") $yahooYen += $tempYen;
    if($row[rfe_provider_kind]=="2") $googleYen += $tempYen;
  }

  //n日どちらか課金
  if ($row[rkw_account_type]=="3"){
    $tempYen = 0;
    if ($Counter >=$row[rkw_account_date]) $tempYen = $row[rfe_yen]*1;
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "/10日";
    //print "×" . $row[rfe_yen] . "円＝" . $tempYen  . "円<br>";
    if($tempYen != 0){
      $tempAll += $tempYen;
      if($row[rfe_provider_kind]=="1") $yahooYen += $tempYen;
      if($row[rfe_provider_kind]=="2") $googleYen += $tempYen;
      //print "aaa" . $tempYen . "aaa";
      break(1);//一回課金されればそれ以上課金されない
    }
  }

}
require('SQLClose.php');
return $tempAll;

?>

<?
}//End function
/** END 成果課金額の計算 *******/


function keywordCalcYahoo($month, $kid, $startdate, $enddate){

$tempAll = 0;
//return $month . $kid . "<br>";

require('SQLConnect_jas.php');
$sql = "SELECT rkw_account_type, rkw_account_date, rank_fee.* FROM rank_keyword INNER JOIN `rank_fee` ON rank_keyword.rkw_id = rank_fee.rfe_rkwid Where(rfe_rkwid = '" . $kid . "' and rfe_status = '1');";
//print $sql . "<br>";
//SQL文を実行する
$rs = mysql_db_query($dbName,$sql);
$rsCount = mysql_num_rows($rs);		//レコード数

for ($i=0; $i<=$rsCount-1; $i++){
  $row = @mysql_fetch_array($rs);
  $sql2 = "SELECT * FROM `rank_crawl` Where(rcr_datetime Like '" . $month . "%'" .
         " and rcr_datetime between '" . $startdate . " 00:00:00' and '" . $enddate . " 23:59:59'" . 
         " and  rcr_rkwid = " . $kid. ");";
  //print_r ($fees);
  //exit;
  //print $sql2 . "<br>";
  //SQL文を実行する
  $Counter = 0;
  $rs2 = mysql_db_query($dbName,$sql2);
  $rsCount2 = mysql_num_rows($rs2);		//レコード数
  for ($j=0; $j<=$rsCount2-1; $j++){
    $row2 = @mysql_fetch_array($rs2);
    //print $j . " : ";
    //print $row2[rcr_yahooranking] . " : ";
    //print $row[rfe_rank_start] . " : ";
    //print $row[rfe_rank_end] . " : ";
    //print $row[rfe_provider_kind] . " : ";
   
    //print "<br><br>";
    $id = $row2[rcr_rkwid];

    //Yahooの計算
    if($row[rfe_provider_kind]=="1" &&
       $row2[rcr_yahooranking] >= $row[rfe_rank_start] &&
       $row2[rcr_yahooranking] <= $row[rfe_rank_end]){
           $Counter++;
           //print "Y:" . $row2[rcr_datetime] . $row2[rcr_clientname] . " : " . $row[rfe_yen] . "<br>";
    }
    
  }
  
  //計算
  if ($row[rkw_account_type]=="1"){
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "日";
    //print "×" . $row[rfe_yen] . "円＝" . ($row[rfe_yen] * $Counter)  . "円<br>";
    $tempAll += ($row[rfe_yen] * $Counter);
  }
  if ($row[rkw_account_type]=="2"){
    $tempYen = 0;
    if ($Counter >=$row[rkw_account_date]) $tempYen = $row[rfe_yen]*1;
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "/10日";
    //print "×" . $row[rfe_yen] . "円＝" . $tempYen  . "円<br>";
    $tempAll += $tempYen;
  }

  //ここは単純に10日課金と併せてる。
  if ($row[rkw_account_type]=="3"){
    $tempYen = 0;
    if ($Counter >=$row[rkw_account_date]) $tempYen = $row[rfe_yen]*1;
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "/10日";
    //print "×" . $row[rfe_yen] . "円＝" . $tempYen  . "円<br>";
    $tempAll += $tempYen;
  }
    
  

}
require('SQLClose.php');
return $tempAll;

?>

<?
}//End function

function keywordCalcGoogle($month, $kid, $startdate, $enddate){

$tempAll = 0;
//return $month . $kid . "<br>";

require('SQLConnect_jas.php');
$sql = "SELECT rkw_account_type, rkw_account_date, rank_fee.* FROM rank_keyword INNER JOIN `rank_fee` ON rank_keyword.rkw_id = rank_fee.rfe_rkwid Where(rfe_rkwid = '" . $kid . "' and rfe_status = '1');";
//print $sql . "<br>";
//SQL文を実行する
$rs = mysql_db_query($dbName,$sql);
$rsCount = mysql_num_rows($rs);		//レコード数

for ($i=0; $i<=$rsCount-1; $i++){
  $row = @mysql_fetch_array($rs);
  $sql2 = "SELECT * FROM `rank_crawl` Where(rcr_datetime Like '" . $month . "%'" .
         " and rcr_datetime between '" . $startdate . " 00:00:00' and '" . $enddate . " 23:59:59'" . 
         " and  rcr_rkwid = " . $kid. ");";
  //print_r ($fees);
  //exit;
  //print $sql2 . "<br>";
  //SQL文を実行する
  $Counter = 0;
  $rs2 = mysql_db_query($dbName,$sql2);
  $rsCount2 = mysql_num_rows($rs2);		//レコード数
  for ($j=0; $j<=$rsCount2-1; $j++){
    $row2 = @mysql_fetch_array($rs2);
    //print $j . " : ";
    //print $row2[rcr_yahooranking] . " : ";
    //print $row[rfe_rank_start] . " : ";
    //print $row[rfe_rank_end] . " : ";
    //print $row[rfe_provider_kind] . " : ";
   
    //print "<br><br>";
    $id = $row2[rcr_rkwid];

    
    //Googleの計算
    if($row[rfe_provider_kind]=="2" &&
       $row2[rcr_googleranking] >= $row[rfe_rank_start] &&
       $row2[rcr_googleranking] <= $row[rfe_rank_end]){
         $Counter++;
         //print "G:" . $row[rcr_datetime] . $row[rcr_clientname] . " : " . $row[rfe_yen] . "<br>";
    }
  }
  
  //計算
  if ($row[rkw_account_type]=="1"){
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "日";
    //print "×" . $row[rfe_yen] . "円＝" . ($row[rfe_yen] * $Counter)  . "円<br>";
    $tempAll += ($row[rfe_yen] * $Counter);
  }
  if ($row[rkw_account_type]=="2"){
    $tempYen = 0;
    if ($Counter >=$row[rkw_account_date]) $tempYen = $row[rfe_yen]*1;
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "/10日";
    //print "×" . $row[rfe_yen] . "円＝" . $tempYen  . "円<br>";
    $tempAll += $tempYen;
  }
    
  //ここは単純に10日課金と併せてる。Yahooがあったらひく処理が必要。
  if ($row[rkw_account_type]=="3"){
    $tempYen = 0;
    if ($Counter >=$row[rkw_account_date]) $tempYen = $row[rfe_yen]*1;
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "/10日";
    //print "×" . $row[rfe_yen] . "円＝" . $tempYen  . "円<br>";
    $tempAll += $tempYen;
  }
  

}
require('SQLClose.php');
return $tempAll;

?>

<?
}//End function



function keywordCalcGoo($month, $kid, $startdate, $enddate){

$tempAll = 0;
//return $month . $kid . "<br>";

require('SQLConnect_jas.php');
$sql = "SELECT rkw_account_type, rkw_account_date, rank_fee.* FROM rank_keyword INNER JOIN `rank_fee` ON rank_keyword.rkw_id = rank_fee.rfe_rkwid Where(rfe_rkwid = '" . $kid . "' and rfe_status = '1');";
//print $sql . "<br>";
//SQL文を実行する
$rs = mysql_db_query($dbName,$sql);
$rsCount = mysql_num_rows($rs);		//レコード数

for ($i=0; $i<=$rsCount-1; $i++){
  $row = @mysql_fetch_array($rs);
  $sql2 = "SELECT * FROM `rank_crawl` Where(rcr_datetime Like '" . $month . "%'" .
         " and rcr_datetime between '" . $startdate . " 00:00:00' and '" . $enddate . " 23:59:59'" . 
         " and  rcr_rkwid = " . $kid. ");";
  //print_r ($fees);
  //exit;
  //print $sql2 . "<br>";
  //SQL文を実行する
  $Counter = 0;
  $rs2 = mysql_db_query($dbName,$sql2);
  $rsCount2 = mysql_num_rows($rs2);		//レコード数
  for ($j=0; $j<=$rsCount2-1; $j++){
    $row2 = @mysql_fetch_array($rs2);
    //print $j . " : ";
    //print $row2[rcr_yahooranking] . " : ";
    //print $row[rfe_rank_start] . " : ";
    //print $row[rfe_rank_end] . " : ";
    //print $row[rfe_provider_kind] . " : ";
   
    //print "<br><br>";
    $id = $row2[rcr_rkwid];

    
    //Gooの計算
    if($row[rfe_provider_kind]=="3" &&
       $row2[rcr_gooranking] >= $row[rfe_rank_start] &&
       $row2[rcr_gooranking] <= $row[rfe_rank_end]){
         $Counter++;
         //print "G:" . $row[rcr_datetime] . $row[rcr_clientname] . " : " . $row[rfe_yen] . "<br>";
    }
  }
  
  //計算
  if ($row[rkw_account_type]=="1"){
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "日";
    //print "×" . $row[rfe_yen] . "円＝" . ($row[rfe_yen] * $Counter)  . "円<br>";
    $tempAll += ($row[rfe_yen] * $Counter);
  }
  if ($row[rkw_account_type]=="2"){
    $tempYen = 0;
    if ($Counter >=$row[rkw_account_date]) $tempYen = $row[rfe_yen]*1;
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "/10日";
    //print "×" . $row[rfe_yen] . "円＝" . $tempYen  . "円<br>";
    $tempAll += $tempYen;
  }
    
  //ここは単純に10日課金と併せてる。Yahooがあったらひく処理が必要。
  if ($row[rkw_account_type]=="3"){
    $tempYen = 0;
    if ($Counter >=$row[rkw_account_date]) $tempYen = $row[rfe_yen]*1;
    //print "Y" . $row[rfe_rank_start] . "～" . $row[rfe_rank_end] . "位：" . $Counter . "/10日";
    //print "×" . $row[rfe_yen] . "円＝" . $tempYen  . "円<br>";
    $tempAll += $tempYen;
  }
  

}
require('SQLClose.php');
return $tempAll;

?>

<?
}//End function



?>