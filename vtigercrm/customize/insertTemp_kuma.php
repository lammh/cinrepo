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

#WS_123, HR_121
$intBlockId = 121;

//ブロックIDを入力
$blockInstance = $blockInstance->getInstance($intBlockId);

/** START リスト型 ****************/
$field = new Vtiger_Field();
$field->name = '';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = ''; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype =  'VARCHAR(255)';
$field->uitype = 15;  #15はリスト型
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ('','','','') ); #リストの項目を追加する
/** END   リスト型 ****************/


/** START 日付型 **************/
$field = new Vtiger_Field();
$field->name = '';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = ''; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(100)';
$field->typeofdata = 'D~O';
$field->uitype = 5;  #5_日付
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   日付型 **************/


/** START INT型 数字入力 *******/
$field = new Vtiger_Field();
$field->name = '';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = ''; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)';
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   INT型 数字入力 *******/


/** START 既存情報を使用する ************/
$field = new Vtiger_Field();
$field->name = '';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = ''; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)';
$field->uitype = 10;  #関連モジュール
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setRelatedModules(Array('Vendors')); //関連モジュールを指定 Vendorsは発注先
/** END 既存情報を使用する ************/



/** START テキスト入力フォーム *******************/
$field = new Vtiger_Field();
$field->name = '';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = ''; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'TEXT'; //カラム名の型
$field->uitype = 19;         //UIタイプ 19_TEXT
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   テキスト入力フォーム *******************/

/** START 文字列入力 *****************************/
$field = new Vtiger_Field();
$field->name = '';   #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = '';    #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)'; //カラム名の型
$field->uitype = 1;  #1_テキスト入力
$field->typeofdata = 'V~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END 文字列入力 *****************************/


/** START 社内メンバー 担当 リスト型 ****/
$field = new Vtiger_Field();
$field->name = '';
$field->table = $module->basetable;
$field->column = '';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;
$blockInstance->addField($field);
/** END 社内メンバー 担当 リスト型 ****/