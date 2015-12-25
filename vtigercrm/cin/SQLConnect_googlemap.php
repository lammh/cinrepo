<?php
//DBへ接続開始 サーバー名--localhost ユーザー名--root パスワード--root
//$GLOBALS[dbHandle] = @mysql_connect("192.168.1.207","root","monchi85");
$GLOBALS[dbHandle] = @mysql_connect("124.33.241.201","cin","cincin");

//DBの接続に失敗した場合はエラー表示をおこない処理中断
if ($GLOBALS[dbHandle] == False) {
	print ("現在、調整中です。申し訳ありません。\n");	
	exit;
}
$dbName ="_googlemap2";
mysql_query("SET NAMES utf8", $GLOBALS[dbHandle]);


?>
