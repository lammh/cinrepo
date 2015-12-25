<?php
//月額固定の登録・更新
// テスト
#ini_set('include_path', '/var/www/vtigercrm');
#require_once('include/utils/utils.php');
#require_once('include/logging.php');
// テスト
#require_once('cin/defalut.php');
require_once('cin/defalut.php');
require_once('cin/common/client_common.php');

#$accountid = 20519;

jas_account_save($accountid);