<?php

/** ▼ JASへの保存 ****************************************/
function DataJasInsertString($tableName, $flagNoEscape = false){

  $fields = array();
  //接続文読み込み
  require('SQLConnect_jas.php');
  
  $sql1 = "";
  $sql2 = "";
  //print $tableName;
  $rs = mysql_list_fields("_seotool",$tableName,$GLOBALS[dbHandle]);
  for ($i = 0; $i < mysql_num_fields($rs); $i++){
    //print mysql_fieldname($rs, $i);
    $fldname = mysql_fieldname($rs, $i);
    $sql1 .= "`" .$fldname . "`,";

    //予めエスケープ処理されたものを扱うか否かで処理分け（DEFAULTは"$flagNoEscape = false"）
    if($flagNoEscape) {
      $sql2 .= "'". $_POST[$fldname] . "',";
    } else {
      $sql2 .= "'". mysql_real_escape_string($_POST[$fldname]) . "',";
    }//End if
    
    //print mysql_fieldname($rs, $i) . " ";
  }//End for

  $sql1 = Substr($sql1,0,-1);
  $sql2 = Substr($sql2,0,-1);
  $sql = "insert into `" . $tableName . "` (" . $sql1 . ")" . " VALUES (" . $sql2 . ")";
  //print $sql . "<BR><BR>";
  return $sql;


}//End function DataJasInsertString

function DataJasInsert($tableName, $flagNoEscape = false, $flagErrorToGo = false){

  $fields = array();
  //接続文読み込み
  require('SQLConnect_jas.php');
  
  $sql1 = "";
  $sql2 = "";
  
  $idName = "";
  $idValue = "";
  $rs = mysql_list_fields("_seotool",$tableName,$GLOBALS[dbHandle]);
  for ($i = 0; $i < mysql_num_fields($rs); $i++){
    //print mysql_fieldname($rs, $i);
    $fldname = mysql_fieldname($rs, $i);
    if($i == 0) $idName = $fldname;
    $sql1 .= "`" .$fldname . "`,";

    //予めエスケープ処理されたものを扱うか否かで処理分け（DEFAULTは"$flagNoEscape = false"）
    if($flagNoEscape) {
      $sql2 .= "'". $_POST[$fldname] . "',";
    } else {
      $sql2 .= "'". mysql_real_escape_string($_POST[$fldname]) . "',";
    }
    //print mysql_fieldname($rs, $i) . " ";
  }
  //切断文読み込み
  require('SQLClose.php');
  

  $sql1 = Substr($sql1,0,-1);
  $sql2 = Substr($sql2,0,-1);
  $sql = "insert into `" . $tableName . "` (" . $sql1 . ")" . " VALUES (" . $sql2 . ")";
  //print $sql . "<BR><BR>";
  require('SQLConnect_jas.php');
  //sql文を実行する
  if($flagErrorToGo){
    $c_hit = @mysql_db_query("_seotool",$sql);
  }else{
    $c_hit = mysql_db_query("_seotool",$sql) or die('error: '.mysql_errno().', '.mysql_error());
  }
  
  if (!$c_hit) {
    print "DB Error, could not query the database<br />";
    print 'MySQL Error: ' . mysql_error();
  }//End if

  //idを取る
  require('SQLConnect_jas.php');
  $sql = "SELECT * FROM `" . $tableName . "` Order By " . $idName . " DESC Limit 0,1;";
  //print $sql . "<br><br>";
  //SQL文を実行する
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);   //レコード数
  $row = @mysql_fetch_array($rs);
  $idValue = $row[$idName];
  
  //まあ要らないか
  //require('SQLClose.php');
  
  return $idValue;

}//End function DataJasInsert
/** ▲ JASへの保存 ****************************************/

/** ▼ JASへの変更 ****************************************/

function DataJasUpdateString($tableName, $flagNoEscape = false){

  $Names = Array();
  foreach($_POST as $key => $value){
    array_push($Names,$key);
  }

  //接続文読み込み
  require('SQLConnect_jas.php');
  
  $sql = "UPDATE `" . $tableName . "` SET ";
  
  $rs = mysql_list_fields("_seotool",$tableName,$GLOBALS[dbHandle]);
  $idName = mysql_fieldname($rs, 0);
  for ($i = 1; $i < mysql_num_fields($rs); $i++){
    //print mysql_fieldname($rs, $i);
    $fldname = mysql_fieldname($rs, $i);
    if (in_array($fldname,$Names)){

      //予めエスケープ処理されたものを扱うか否かで処理分け（DEFAULTは"$flagNoEscape = false"）
      if($flagNoEscape) {
        $sql .= " `" . $fldname . "` = '" . $_POST[$fldname] . "', ";
      } else {
        $sql .= " `" . $fldname . "` = '" . addslashes($_POST[$fldname]) . "', ";
      }
    }
    
  }
  $sql = substr($sql,0,-2);
  $sql .= " WHERE " . $idName . " = '" . $_POST[$idName] . "'";
  
  return $sql;
  
}//End function DataJasUpdateString

function DataJasUpdate($tableName, $flagNoEscape = false){

  $Names = Array();
  foreach($_POST as $key => $value){
    array_push($Names,$key);
  }

  //接続文読み込み
  require('SQLConnect_jas.php');
  
  $sql = "UPDATE `" . $tableName . "` SET ";
  
  $rs = mysql_list_fields("_seotool",$tableName,$GLOBALS[dbHandle]);
  $idName = mysql_fieldname($rs, 0);

  for ($i = 1; $i < mysql_num_fields($rs); $i++){
    //print mysql_fieldname($rs, $i);
    $fldname = mysql_fieldname($rs, $i);
    if (in_array($fldname,$Names)){

      //予めエスケープ処理されたものを扱うか否かで処理分け（DEFAULTは"$flagNoEscape = false"）
      if($flagNoEscape) {
        $sql .= " `" . $fldname . "` = '" . $_POST[$fldname] . "', ";
      } else {
        $sql .= " `" . $fldname . "` = '" . addslashes($_POST[$fldname]) . "', ";
      }//End if
    }//End if
    
  }//End for

  //切断文読み込み
  require('SQLClose.php');
  $sql = substr($sql,0,-2);
  $sql .= " WHERE " . $idName . " = '" . $_POST[$idName] . "'";

  require('SQLConnect_jas.php');
  //sql文を実行する
  //print $sql . "<BR><BR>";
    $c_hit = mysql_db_query("_seotool",$sql)
      or die('error: '.mysql_errno().', '.mysql_error());
  $sql_bk = $sql;
   
  

  mysql_close($GLOBALS[dbHandle]);
  require('SQLConnect_jas.php');
  //データバックアップ
  $sql = "insert into `jas_sql_backup` (`jsb_id`,`jsb_datetime`,`jsb_name`,`jsb_kind`,`jsb_sql`)" .
         " VALUES ('','" . DateNow(0) . "','" . $_SESSION[jm_name] . "','Update','".
         mysql_real_escape_string($sql_bk)."')";
  //print $sql . "<BR><BR>";
  //  $c_hit = mysql_db_query("_seotool",$sql)
  //    or die('error: '.mysql_errno().', '.mysql_error());

  //まあ要らないか
  //mysql_close($GLOBALS[dbHandle]);
  


}//End function DataJasUpdate
/** ▲ JASへの変更 ****************************************/


/** ▼ 基幹システムへの保存 ****************************************/

function DataInsertString($tableName, $flagNoEscape = false){



  $fields = array();
  //接続文読み込み
  require('SQLConnect.php');
  
  $sql1 = "";
  $sql2 = "";
  //print $tableName;
  $rs = mysql_list_fields("_seotool",$tableName,$GLOBALS[dbHandle]);
  for ($i = 0; $i < mysql_num_fields($rs); $i++){
    //print mysql_fieldname($rs, $i);
    $fldname = mysql_fieldname($rs, $i);
    $sql1 .= "`" .$fldname . "`,";

    //予めエスケープ処理されたものを扱うか否かで処理分け（DEFAULTは"$flagNoEscape = false"）
    if($flagNoEscape) {
      $sql2 .= "'". $_POST[$fldname] . "',";
    } else {
      $sql2 .= "'". mysql_real_escape_string($_POST[$fldname]) . "',";
    }
    
    //print mysql_fieldname($rs, $i) . " ";
  }


  
  $sql1 = Substr($sql1,0,-1);
  $sql2 = Substr($sql2,0,-1);
  $sql = "insert into `" . $tableName . "` (" . $sql1 . ")" . " VALUES (" . $sql2 . ")";
  //print $sql . "<BR><BR>";
  return $sql;


}//End function

function DataInsert($tableName, $flagNoEscape = false, $flagErrorToGo = false){



  $fields = array();
  //接続文読み込み
  require('SQLConnect.php');
  
  $sql1 = "";
  $sql2 = "";
  
  $idName = "";
  $idValue = "";
  $rs = mysql_list_fields("_seotool",$tableName,$GLOBALS[dbHandle]);
  for ($i = 0; $i < mysql_num_fields($rs); $i++){
    //print mysql_fieldname($rs, $i);
    $fldname = mysql_fieldname($rs, $i);
    if($i == 0) $idName = $fldname;
    $sql1 .= "`" .$fldname . "`,";

    //予めエスケープ処理されたものを扱うか否かで処理分け（DEFAULTは"$flagNoEscape = false"）
    if($flagNoEscape) {
      $sql2 .= "'". $_POST[$fldname] . "',";
    } else {
      $sql2 .= "'". mysql_real_escape_string($_POST[$fldname]) . "',";
    }
    //print mysql_fieldname($rs, $i) . " ";
  }
  //切断文読み込み
  require('SQLClose.php');
  

  $sql1 = Substr($sql1,0,-1);
  $sql2 = Substr($sql2,0,-1);
  $sql = "insert into `" . $tableName . "` (" . $sql1 . ")" . " VALUES (" . $sql2 . ")";
  //print $sql . "<BR><BR>";
  require('SQLConnect.php');
  //sql文を実行する
  if($flagErrorToGo){
    $c_hit = @mysql_db_query("_seotool",$sql);
  }else{
    $c_hit = mysql_db_query("_seotool",$sql) or die('error: '.mysql_errno().', '.mysql_error());
  }
  
  if (!$c_hit) {
    print "DB Error, could not query the database<br />";
    print 'MySQL Error: ' . mysql_error();
  }//End if

#  $sql_bk = $sql;
#  mysql_close($GLOBALS[dbHandle]);
#  require('SQLConnect.php');
  //データバックアップ
#  $sql = "insert into `jas_sql_backup` (`jsb_id`,`jsb_datetime`,`jsb_name`,`jsb_kind`,`jsb_sql`)" .
#         " VALUES ('','" . DateNow(0) . "','" . $_SESSION[jm_name] . "','Insert','".
#         " VALUES ('','" . DateNow(0) . "','大熊','Insert','".
#         mysql_real_escape_string($sql_bk)."')";
  //print $sql . "<BR><BR>";
  //  $c_hit = mysql_db_query("_seotool",$sql)
  //    or die('error: '.mysql_errno().', '.mysql_error());

#  mysql_close($GLOBALS[dbHandle]);

  //idを取る
  require('SQLConnect.php');
  $sql = "SELECT * FROM `" . $tableName . "` Order By " . $idName . " DESC Limit 0,1;";
  //print $sql . "<br><br>";
  //SQL文を実行する
  $rs = mysql_db_query($dbName,$sql);
  $rsCount = mysql_num_rows($rs);   //レコード数
  $row = @mysql_fetch_array($rs);
  $idValue = $row[$idName];
  
  //まあ要らないか
  //require('SQLClose.php');
  
  return $idValue;

}//End function


Function DateNow($timeplus){

  $date_array = getdate(time()+$timeplus);
  return $date_array["year"] . "/" .
         substr ("00" . $date_array["mon"],-2) . "/" .
         substr ("00" . $date_array["mday"],-2) . " " .
         substr ("00" . $date_array["hours"],-2) . ":" .
         substr ("00" . $date_array["minutes"],-2) . ":" .
         substr ("00" . $date_array["seconds"],-2);

}//End Function


function randPass($num){

$pass_seed = "abcdefghijkmnprstuvwxyz0123456789";
$pass_len = strlen($pass_seed);

srand((double)microtime()*1000000);
$pass = "";
do{
  $pass = "";
  for($i=0; $i<$num; $i++){
    $number=round(rand(1,$pass_len));
    $pass.= substr($pass_seed, $number, 1);
  }
} while(preg_match("/[0-9]/",$pass) == false);
return $pass;

}//End function


function DataUpdateString($tableName, $flagNoEscape = false){

  $Names = Array();
  foreach($_POST as $key => $value){
    array_push($Names,$key);
  }

  //接続文読み込み
  require('SQLConnect.php');
  
  $sql = "UPDATE `" . $tableName . "` SET ";
  
  $rs = mysql_list_fields("_seotool",$tableName,$GLOBALS[dbHandle]);
  $idName = mysql_fieldname($rs, 0);
  for ($i = 1; $i < mysql_num_fields($rs); $i++){
    //print mysql_fieldname($rs, $i);
    $fldname = mysql_fieldname($rs, $i);
    if (in_array($fldname,$Names)){

      //予めエスケープ処理されたものを扱うか否かで処理分け（DEFAULTは"$flagNoEscape = false"）
      if($flagNoEscape) {
        $sql .= " `" . $fldname . "` = '" . $_POST[$fldname] . "', ";
      } else {
        $sql .= " `" . $fldname . "` = '" . addslashes($_POST[$fldname]) . "', ";
      }
    }
    
  }
  $sql = substr($sql,0,-2);
  $sql .= " WHERE " . $idName . " = '" . $_POST[$idName] . "'";
  
  return $sql;
  


}//End function

function DataUpdate($tableName, $flagNoEscape = false){

  $Names = Array();
  foreach($_POST as $key => $value){
    array_push($Names,$key);
  }

  //接続文読み込み
  require('SQLConnect.php');
  
  $sql = "UPDATE `" . $tableName . "` SET ";
  
  $rs = mysql_list_fields("_seotool",$tableName,$GLOBALS[dbHandle]);
  $idName = mysql_fieldname($rs, 0);
  for ($i = 1; $i < mysql_num_fields($rs); $i++){
    //print mysql_fieldname($rs, $i);
    $fldname = mysql_fieldname($rs, $i);
    if (in_array($fldname,$Names)){

      //予めエスケープ処理されたものを扱うか否かで処理分け（DEFAULTは"$flagNoEscape = false"）
      if($flagNoEscape) {
        $sql .= " `" . $fldname . "` = '" . $_POST[$fldname] . "', ";
      } else {
        $sql .= " `" . $fldname . "` = '" . addslashes($_POST[$fldname]) . "', ";
      }
    }
    
  }
  //切断文読み込み
  require('SQLClose.php');
  $sql = substr($sql,0,-2);
  $sql .= " WHERE " . $idName . " = '" . $_POST[$idName] . "'";

  require('SQLConnect.php');
  //sql文を実行する
  //print $sql . "<BR><BR>";
    $c_hit = mysql_db_query("_seotool",$sql)
      or die('error: '.mysql_errno().', '.mysql_error());
  $sql_bk = $sql;
   
  

  mysql_close($GLOBALS[dbHandle]);
  require('SQLConnect.php');
  //データバックアップ
  $sql = "insert into `jas_sql_backup` (`jsb_id`,`jsb_datetime`,`jsb_name`,`jsb_kind`,`jsb_sql`)" .
         " VALUES ('','" . DateNow(0) . "','" . $_SESSION[jm_name] . "','Update','".
         mysql_real_escape_string($sql_bk)."')";
  //print $sql . "<BR><BR>";
  //  $c_hit = mysql_db_query("_seotool",$sql)
  //    or die('error: '.mysql_errno().', '.mysql_error());

  //まあ要らないか
  //mysql_close($GLOBALS[dbHandle]);
  


}//End function