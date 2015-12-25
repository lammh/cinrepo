<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('Potentials'); #ヨミ案件

$blockInstance = new Vtiger_Block();
//  label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(132);  #ヨミ案件のWS



#月(受注/失注)
$field = new Vtiger_Field();
$field->name = 'month_orders_loss';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_potential
$field->column = 'month_orders_loss'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype =  'VARCHAR(255)';
$field->uitype = 15;  #15はリスト型
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ('january')); #リストの項目を追加する


###連絡(予定日)
#$field = new Vtiger_Field();
#$field->name = 'contact_date';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'contact_date'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(100)';
#$field->typeofdata = 'D~O';
#$field->uitype = 5;  #5_日付
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);


###先方決裁(予定日)
#$field = new Vtiger_Field();
#$field->name = 'other_party_approval';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'other_party_approval'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(100)';
#$field->typeofdata = 'D~O';
#$field->uitype = 5;  #5_日付
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);

##理由(角度)
#$field = new Vtiger_Field();
#$field->name = 'reason';   #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'reason';    #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(255)'; //カラム名の型
#$field->uitype = 1;  #1_テキスト入力
#$field->typeofdata = 'V~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);


###可能性(角度)
#$field = new Vtiger_Field();
#$field->name = 'possibility';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'possibility'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);



##時間軸(角度)
#$field = new Vtiger_Field();
#$field->name = 'time_axis';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'time_axis'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype =  'VARCHAR(255)';
#$field->uitype = 15;  #15はリスト型
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#$field->setPicklistValues( array ('next_week')); #リストの項目を追加する

##対応月(角度)
#$field = new Vtiger_Field();
#$field->name = 'corresponding_month';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'corresponding_month'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype =  'VARCHAR(255)';
#$field->uitype = 15;  #15はリスト型
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#$field->setPicklistValues( array ('next_week')); #リストの項目を追加する


##経緯
#/** START リスト型 ****************/
#$field = new Vtiger_Field();
#$field->name = 'background';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'background'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype =  'VARCHAR(255)';
#$field->uitype = 15;  #15はリスト型
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#$field->setPicklistValues( array ('new_introduction')); #リストの項目を追加する
#/** END   リスト型 ****************/
#


#/** START 最終接触日 ***/
#$field = new Vtiger_Field();
#$field->name = 'last_contact_date';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'last_contact_date'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(100)';
#$field->typeofdata = 'D~O';
#$field->uitype = 5;  #5_日付
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 最終接触日 ***/
#
#/** START 初期訪問日 ***/
#$field = new Vtiger_Field();
#$field->name = 'initial_visit_date';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'initial_visit_date'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'VARCHAR(100)';
#$field->typeofdata = 'D~O';
#$field->uitype = 5;  #5_日付
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 初期訪問日 ***/

#/** START WS サービスリスト **********************/
#$field = new Vtiger_Field();
#$field->name = 'ws_service_list';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'ws_service_list'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype =  'VARCHAR(255)';
#$field->uitype = 15;  #15はリスト型
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#$field->setPicklistValues( array ('seo','listing') ); #リストの項目を追加する
#/** END WS サービスリスト  **********************/


#/** START 成果報酬 *******/
#$field = new Vtiger_Field();
#$field->name = 'result_fee';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'result_fee'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 成果報酬 *******/

#/** START 継続固定 *******/
#$field = new Vtiger_Field();
#$field->name = 'continuation_fee';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'continuation_fee'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** END 継続固定 *******/

#/** START 単月固定 *******/
#$field = new Vtiger_Field();
#$field->name = 'monthly_fee';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'monthly_fee'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
/** END 単月固定 *******/

#/** START 初期費用 *******/
#$field = new Vtiger_Field();
#$field->name = 'first_fee';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'first_fee'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
/** END 初期費用 *******/