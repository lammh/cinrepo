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
$blockInstance = $blockInstance->getInstance(123);  #WS

/** START 対応月(角度) リスト **/
$field = new Vtiger_Field();
$field->name = 'corresponding_month';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'corresponding_month'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype =  'VARCHAR(255)';
$field->uitype = 15;  #15はリスト型
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ('A(翌週)','B(翌々週)','C(当月)','D(次月)') ); #リストの項目を追加する
/** END  対応月(角度) リスト **/

/** START 可能性(角度)(％) 数字 **/
$field = new Vtiger_Field();
$field->name = 'possibility';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'possibility'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)';
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END  可能性(角度)(％) 数字 **/
?>