<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('SalesOrder');

$blockInstance = new Vtiger_Block();
// label–¼‚ÅŠù‘¶ƒuƒƒbƒN‚ðŽæ“¾‚·‚é
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(61);



/* ‘e—˜ */
$field = new Vtiger_Field();
$field->name = 'amount';
$field->table = $module->basetable;
$field->column = 'amount';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);

?>