<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');

//Module名を入力
$module = Vtiger_Module::getInstance('Invoice'); #請求モジュール

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);

//ブロックIDを入力
$blockInstance = $blockInstance->getInstance(67);  #請求書の詳細

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

exit;

/** START 課金形態 *****/
#$field = new Vtiger_Field();
#$field->name = 'acfrom';
#$field->table = $module->basetable;
#$field->column = 'acfrom';
#$field->columntype =  'VARCHAR(240)';
#$field->uitype = 15;
#$field->sequence = 11;
#$field->displaytype= 1;
#$field->typeofdata = 'I~O';
#$field->label = $field->column;
#$blockInstance->addField($field);
/** END 課金形態 *****/

#/* 成果基準 */
#$field = new Vtiger_Field();
#$field->name = 'outcome';
#$field->table = $module->basetable;
#$field->column = 'outcome';
#$field->columntype =  'VARCHAR(100)';
#$field->uitype = 15;
#$field->sequence = 11;
#$field->displaytype= 1;
#$field->typeofdata = 'I~O';
#$field->label = $field->column;
#$blockInstance->addField($field);
#
#/** START 計上週 *****/
#$field = new Vtiger_Field();
#$field->name = 'closing_week';
#$field->table = $module->basetable;
#$field->column = 'closing_week';
#$field->columntype = 'INT(10)';
#$field->uitype = 15;
#$field->sequence = 11;
#$field->displaytype= 1;
#$field->typeofdata = 'I~O';
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 計上週 *****/
#
#/** START 社外 原価 *******/
#$field = new Vtiger_Field();
#$field->name = 'cost_customer';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'cost_customer'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 社外 原価 *******/


/** START 社内 原価 *******/
#$field = new Vtiger_Field();
#$field->name = 'cost_member';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'cost_member'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
/** END 社内 原価 *******/

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


#
#/* 自力紹介 */
#$field = new Vtiger_Field();
#$field->name = 'selforintro';
#$field->table = $module->basetable;
#$field->column = 'selforintro';
#$field->columntype =  'VARCHAR(100)';
#$field->uitype = 15;
#$field->sequence = 11;
#$field->displaytype= 1;
#$field->typeofdata = 'I~O';
#$field->label = $field->column;
#$blockInstance->addField($field);
#
#/** START INT型 数字入力 *******/
#$field = new Vtiger_Field();
#$field->name = 'costinfo';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'costinfo'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END   INT型 数字入力 *******/
#

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
#/** START 紹介料 社内 *******/
#$field = new Vtiger_Field();
#$field->name = 'introduction_house_fee';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'introduction_house_fee'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 紹介料 社内 *******/
#
#
#/** START 紹介料 社外 *******/
#$field = new Vtiger_Field();
#$field->name = 'introduction_external_fee';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'introduction_external_fees'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 紹介料 社外 *******/
#
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



#
#/* 売上額 */
#$field = new Vtiger_Field();
#$field->name = 'salesamount';
#$field->table = $module->basetable;
#$field->column = 'salesamount';
#$field->columntype = 'INT(10)';
#$field->uitype = 71;
#$field->displaytype= 1;
#$field->typeofdata = 'N~O';
#$field->label = $field->column;
#$blockInstance->addField($field);
#
#
#/* 粗利 */
#$field = new Vtiger_Field();
#$field->name = 'amount';
#$field->table = $module->basetable;
#$field->column = 'amount';
#$field->columntype = 'INT(10)';
#$field->uitype = 71;
#$field->displaytype= 1;
#$field->typeofdata = 'N~O';
#$field->label = $field->column;
#$blockInstance->addField($field);
#

#/** START リスト型 ****************/
#$field = new Vtiger_Field();
#$field->name = 'division';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'division'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype =  'VARCHAR(255)';
#$field->uitype = 15;  #15はリスト型
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
##$field->setPicklistValues( array ('job_ads','recruitment','temp_staff','rumor','sc','web_com','ec','hd','fraisier') ); #リストの項目を追加する
#/** END   リスト型 ****************/
#
#
#/** START 成果条件 ****/
#$field = new Vtiger_Field();
#$field->name = 'result_conditions';   #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'result_conditions';    #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(255)'; //カラム名の型
#$field->uitype = 1;  #1_テキスト入力
#$field->typeofdata = 'V~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 成果条件 ****/
