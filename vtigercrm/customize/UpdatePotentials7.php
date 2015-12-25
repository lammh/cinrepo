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




/* 関連項目の設定 */

$field = new Vtiger_Field();
$field->name = 'relatedfield';
$field->table = $module->basetable;
$field->column = 'relatedfield';
$field->columntype = 'INT(10)';
$field->uitype = 10;
$field->displaytype= 1;
$field->typeofdata =  'V~O';
$field->label = $field->column;
$blockInstance->addField($field);

$field->setRelatedModules(Array('Vendors', 'PurchaseOrder')); //関連モジュールを指定





?>