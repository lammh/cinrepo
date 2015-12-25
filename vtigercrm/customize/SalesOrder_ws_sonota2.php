<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');

//Module名を入力
$module = Vtiger_Module::getInstance('SalesOrder'); #受注モジュール

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);

//ブロックIDを入力
$blockInstance = $blockInstance->getInstance(129);  #WS



/** START 承認依頼者 ******/
/* charge1 */
$field = new Vtiger_Field();
$field->name = 'charge1';
$field->table =$module->basetable;
$field->column = 'charge1';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata ='V~M';
$field->label = $field->column;

$blockInstance->addField($field);

/** START 承認者 ******/
/* charge2 */
$field = new Vtiger_Field();
$field->name = 'charge2';
$field->table =$module->basetable;
$field->column = 'charge2';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata ='V~M';
$field->label = $field->column;

$blockInstance->addField($field);

/** START よびよび ******/
/* charge3 */
$field = new Vtiger_Field();
$field->name = 'charge3';
$field->table =$module->basetable;
$field->column = 'charge3';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata ='V~M';
$field->label = $field->column;

$blockInstance->addField($field);

?>