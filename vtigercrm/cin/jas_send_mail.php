<?
ini_set("include_path", "../");

require('send_mail.php');
require_once('config.php');
require_once('include/utils/utils.php');


print "<p>OK</p>";
exit;
// Email Setup
#global $adb;
#$emailresult = $adb->pquery("SELECT email1 from vtiger_users", array());
#$emailid = $adb->fetch_array($emailresult);
#$emailaddress = $emailid[0];
#$mailserveresult = $adb->pquery("SELECT server,server_username,server_password,smtp_auth FROM vtiger_systems where server_type = ?", array('email'));
#$mailrow = $adb->fetch_array($mailserveresult);
#$mailserver = $mailrow[0];
#$mailuname = $mailrow[1];
#$mailpwd = $mailrow[2];
#$smtp_auth = $mailrow[3];
// End Email Setup
