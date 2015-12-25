<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('Contacts');

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);

//ブロックIDを指定
$blockInstance = $blockInstance->getInstance(4);




/* rcm_id */
$field = new Vtiger_Field();
$field->name = 'rcm_id';
$field->table = $module->basetable;
$field->column = 'rcm_id';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 20;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;
$blockInstance->addField($field);

?>
