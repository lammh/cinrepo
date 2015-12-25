<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');

//Module名を入力
$module = Vtiger_Module::getInstance('Invoice'); #受注モジュール

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);

//ブロックIDを入力
$blockInstance = $blockInstance->getInstance(128);  #SC


/** START マージン率 ****************/
$field = new Vtiger_Field();
$field->name = 'margin_rate';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'margin_rate'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)';
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END マージン率 ******************/
?>