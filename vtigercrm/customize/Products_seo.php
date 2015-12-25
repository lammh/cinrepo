<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');

//Module名を入力
$module = Vtiger_Module::getInstance('Products'); #受注モジュール

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);

//ブロックIDを入力
$blockInstance = $blockInstance->getInstance(126);  #SEO

/** START 解約フラグ *******/
$field = new Vtiger_Field();
$field->name = 'seo_away_flag';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'seo_away_flag'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(5)';
$field->uitype = 56;  #Boolean
$field->typeofdata = 'V~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   解約フラグ *******/


/** START INT型 数字入力 *******/
#$field = new Vtiger_Field();
#$field->name = 'seo_kwid';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'seo_kwid'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
/** END   INT型 数字入力 *******/



/** START リスト型 ****************/
#$field = new Vtiger_Field();
#$field->name = 'contractform';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'contractform'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype =  'VARCHAR(255)';
#$field->uitype = 15;  #15はリスト型
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#$field->setPicklistValues( array ('monthlyfee','initialcost') ); #リストの項目を追加する
/** END   リスト型 ****************/

#/** START 代理店顧客 ****/
#$field = new Vtiger_Field();
#$field->name = 'seo_agency_customers';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'seo_agency_customers'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(255)';
#$field->uitype = 10;  #関連モジュール
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#$field->setRelatedModules(Array('Accounts')); //関連モジュールを指定 Vendorsは発注先
#/** END 代理店顧客 ****/
#
#/** START セグメント **********************/
#$field = new Vtiger_Field();
#$field->name = 'seo_segment';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'seo_segment'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype =  'VARCHAR(255)';
#$field->uitype = 15;  #15はリスト型
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#$field->setPicklistValues( array ('seo','re_seo','meop','rank_only','internal_instructions','service_site','consulting','text') ); #リストの項目を追加する
#/** END セグメント   **********************/
#
#/** START URL     **************/
#$field = new Vtiger_Field();
#$field->name = 'seo_url';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'seo_url'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'TEXT'; //カラム名の型
#$field->uitype = 19;         //UIタイプ 19_TEXT
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END   URL     **************/
#
#/** START 順位チェック方法 **************/
#$field = new Vtiger_Field();
#$field->name = 'seo_rank_check';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'seo_rank_check'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype =  'VARCHAR(255)';
#$field->uitype = 15;  #15はリスト型
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#$field->setPicklistValues( array ('part','domain','perfect','character') ); #リストの項目を追加する
#/** END   順位チェック方法 **************/
#
#
#/** START キーワード ****/
#$field = new Vtiger_Field();
#$field->name = 'seo_keyword';   #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_salesorder
#$field->column = 'seo_keyword';    #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(255)'; //カラム名の型
#$field->uitype = 1;  #1_テキスト入力
#$field->typeofdata = 'V~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END キーワード   ****/