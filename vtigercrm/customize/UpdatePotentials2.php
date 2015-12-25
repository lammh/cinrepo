<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('Potentials');

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(1);





/* 原価 */
$field = new Vtiger_Field();
$field->name = 'costAll';
$field->table = $module->basetable;
$field->column = 'costAll';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* 紹介料 */
$field = new Vtiger_Field();
$field->name = 'costinfo';
$field->table = $module->basetable;
$field->column = 'costinfo';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* 初期費用 */
$field = new Vtiger_Field();
$field->name = 'costNew';
$field->table = $module->basetable;
$field->column = 'costNew';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* 媒体 */
$field = new Vtiger_Field();
$field->name = 'baitai';
$field->table = $module->basetable;
$field->column = 'baitai';
$field->columntype =  'VARCHAR(240)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ("TW","TW社員","TWモバS","an","フロムＡナビ","バイトル","マイナビバイト","アイデム","DOMO","リクナビネクスト","マイナビ転職","とらばーゆ"
,"はたらいく","DODA","リクナビ新卒","マイナビ新卒","イーキャリア","@type","女の転職","その他","キックバック手数料"

) );

