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





/* エリア */
$field = new Vtiger_Field();
$field->name = 'closingarea';
$field->table = $module->basetable;
$field->column = 'closingarea';
$field->columntype ='VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ("東京","大阪","その他") );




/* 媒体 */
$field = new Vtiger_Field();
$field->name = 'accountingForm';
$field->table = $module->basetable;
$field->column = 'accountingForm';
$field->columntype =  'VARCHAR(240)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ("ストック","ショット") );

