<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');

//Module名を入力
$module = Vtiger_Module::getInstance('Products'); #製品(固定)モジュール

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);

//ブロックIDを入力
$blockInstance = $blockInstance->getInstance(31);  #製品の詳細

/** START 初期費日付 **************/
$field = new Vtiger_Field();
$field->name = 'first_fee_date';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'first_fee_date'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(100)';
$field->typeofdata = 'D~O';
$field->uitype = 5;  #5_日付
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   初期費日付 **************/