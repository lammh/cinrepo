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
$blockInstance = $blockInstance->getInstance(85);  #ストック繰り返し計上情報

/** START 更新間隔 ****************/
$field = new Vtiger_Field();
$field->name = 'update_interval';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'update_interval'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype =  'VARCHAR(255)';
$field->uitype = 15;  #15はリスト型
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ('0','1','2','3','4','5', '6', '7', '8', '9', '10', '11', '12') ); #リストの項目を追加する
/** END   更新間隔 *******************/
exit;
/** START 更新数値設定(日)　**********************/
$field = new Vtiger_Field();
$field->name = 'update_notification';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'update_notification'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)';
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END 更新数値設定(日)　**********************/

?>