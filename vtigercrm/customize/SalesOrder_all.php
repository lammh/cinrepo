<?php
//受注モジュール対してフィールドを追加する

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
$blockInstance = $blockInstance->getInstance(61);  #受注実績の詳細

/** START 技術担当者 ****/
$field = new Vtiger_Field();
$field->name = 'technique_representative';
$field->table = $module->basetable;
$field->column = 'technique_representative';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);
/** END 技術担当者 ****/

/** START 申込日 ***/
#$field = new Vtiger_Field();
#$field->name = 'submission_date';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'submission_date'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(100)';
#$field->typeofdata = 'D~O';
#$field->uitype = 5;  #5_日付
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
/** END 申込日 ***/

#/** START 紹介料 *******/
#$field = new Vtiger_Field();
#$field->name = 'introduction_fee';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'introduction_fee'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 紹介料 *******/


#/** START 紹介先 社内 ****/
#$field = new Vtiger_Field();
#$field->name = 'introduction_member';
#$field->table = $module->basetable;
#$field->column = 'introduction_member';
#$field->columntype = 'INT(19)';
#$field->uitype = 53;
#$field->displaytype= 1;
#$field->typeofdata = 'V';
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 紹介先 社内 ****/
#

#/** START 紹介先会社 ****/
#$field = new Vtiger_Field();
#$field->name = 'introduction_customers';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'introduction_customers'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(255)';
#$field->uitype = 10;  #関連モジュール
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#$field->setRelatedModules(Array('Accounts')); //関連モジュールを指定 Vendorsは発注先
#/** END 紹介先会社 ****/


#/** START 契約（掲載）開始日 ***/
#$field = new Vtiger_Field();
#$field->name = 'contract_startdate';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'contract_startdate'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(100)';
#$field->typeofdata = 'D~O';
#$field->uitype = 5;  #5_日付
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** START 契約（掲載）開始日 ***/
#
#/** START 契約（掲載）終了日 ***/
#$field = new Vtiger_Field();
#$field->name = 'contract_enddate';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'contract_enddate'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(100)';
#$field->typeofdata = 'D~O';
#$field->uitype = 5;  #5_日付
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 契約（掲載）終了日 ***/
#
#/** START 支払い期限 ***/
#$field = new Vtiger_Field();
#$field->name = 'payment_date';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'payment_date'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(100)';
#$field->typeofdata = 'D~O';
#$field->uitype = 5;  #5_日付
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
/** END 支払い期限 ***/




?>