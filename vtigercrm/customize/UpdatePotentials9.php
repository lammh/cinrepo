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



/* Sコード */
$field = new Vtiger_Field();
$field->name = 'scode';
$field->table = $module->basetable;
$field->column = 'scode';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* 制作担当者 */
$field = new Vtiger_Field();
$field->name = 'creater';
$field->table = $module->basetable;
$field->column = 'creater';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;

$blockInstance->addField($field);



/*制作入稿ステータス */

$field = new Vtiger_Field();
$field->name = 'create_status';
$field->table = $module->basetable;
$field->column = 'create_status';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
$array = array("未処理","制作依頼","制作中","制作完了","入稿完了");



?>