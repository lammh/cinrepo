<?php
chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('Users');

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(77);	#User

/** START リスト型 ****************/
$field = new Vtiger_Field();
$field->name = 'division';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'division'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype =  'VARCHAR(255)';
$field->uitype = 15;  #15はリスト型
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ('101:求人広告', '102:人材紹介', '103:人材派遣', '201:風評コンサル', '201:風評コンサル', '203:サロコン', '204:Eコマース') ); #リストの項目を追加する
/** END   リスト型 ****************/