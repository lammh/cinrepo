<?php
$GLOBALS[dbHandle] = mysql_connect("localhost","root","cincin");

//DBの接続に失敗した場合はエラー表示をおこない処理中断
if ($GLOBALS[dbHandle] == False) {
	print ("現在、調整中です。申し訳ありません。\n");	
	exit;
}
$dbName ="vtiger";
mysql_query("SET NAMES utf8", $GLOBALS[dbHandle]);


?>
