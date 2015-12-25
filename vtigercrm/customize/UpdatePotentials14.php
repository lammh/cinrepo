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




/*処理者 */
$field = new Vtiger_Field();
$field->name = 'transactor';
$field->table = $module->basetable;
$field->column = 'transactor';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;

$blockInstance->addField($field);






?>