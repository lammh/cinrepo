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
$blockInstance = $blockInstance->getInstance(121);  #HR

/** START 窓口 *****/
$field = new Vtiger_Field();
$field->name = 'madoguchi';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = '';    #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)'; //カラム名の型
$field->uitype = 1;  #1_テキスト入力
$field->typeofdata = 'V~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   窓口 ****/

/** START エリア **************/
$field = new Vtiger_Field();
$field->name = 'closing_Area';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = '';    #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)'; //カラム名の型
$field->uitype = 1;  #1_テキスト入力
$field->typeofdata = 'V~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   エリア **************/

/** START プラン(サイズ) **************/
$field = new Vtiger_Field();
$field->name = 'planname';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = '';    #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)'; //カラム名の型
$field->uitype = 1;  #1_テキスト入力
$field->typeofdata = 'V~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   プラン(サイズ) **************/

/** START 掲載日 ******/
$field = new Vtiger_Field();
$field->name = 'expected_close_date';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'expected_close_date'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(100)';
$field->typeofdata = 'D~O';
$field->uitype = 5;  #5_日付
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   掲載日 ******/

?>