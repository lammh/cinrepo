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


/*** START 発注先 *************************************/
$field = new Vtiger_Field();
$field->name = 'order_destination_01';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_destination_01'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)';
$field->uitype = 10;  #関連モジュール
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setRelatedModules(Array('Vendors')); //関連モジュールを指定
/* END 発注先 *************************************/

/*** START 発注原価 ********************************/
$field = new Vtiger_Field();
$field->name = 'order_cost_01';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_cost_01'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;         //7_数字
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/*** END   発注原価 ********************************/


/*** START 発注先 *************************************/
$field = new Vtiger_Field();
$field->name = 'order_destination_02';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_destination_02'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)';
$field->uitype = 10;  #関連モジュール
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setRelatedModules(Array('Vendors')); //関連モジュールを指定
/* END 発注先 *************************************/

/*** START 発注原価 ********************************/
$field = new Vtiger_Field();
$field->name = 'order_cost_02';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_cost_02'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;         //7_数字
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/*** END   発注原価 ********************************/

/*** START 発注先 *************************************/
$field = new Vtiger_Field();
$field->name = 'order_destination_03';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_destination_03'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)';
$field->uitype = 10;  #関連モジュール
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setRelatedModules(Array('Vendors')); //関連モジュールを指定
/* END 発注先 *************************************/

/*** START 発注原価 ********************************/
$field = new Vtiger_Field();
$field->name = 'order_cost_03';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_cost_03'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;         //7_数字
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/*** END   発注原価 ********************************/

/*** START 発注先 *************************************/
$field = new Vtiger_Field();
$field->name = 'order_destination_04';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_destination_04'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)';
$field->uitype = 10;  #関連モジュール
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setRelatedModules(Array('Vendors')); //関連モジュールを指定
/* END 発注先 *************************************/

/*** START 発注原価 ********************************/
$field = new Vtiger_Field();
$field->name = 'order_cost_04';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_cost_04'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;         //7_数字
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/*** END   発注原価 ********************************/

/*** START 発注先 *************************************/
$field = new Vtiger_Field();
$field->name = 'order_destination_05';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_destination_05'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)';
$field->uitype = 10;  #関連モジュール
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setRelatedModules(Array('Vendors')); //関連モジュールを指定
/* END 発注先 *************************************/

/*** START 発注原価 ********************************/
$field = new Vtiger_Field();
$field->name = 'order_cost_05';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_cost_05'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;         //7_数字
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/*** END   発注原価 ********************************/

/** START 発注内容 *******************/
$field = new Vtiger_Field();
$field->name = 'order_content';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'order_content'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'TEXT'; //カラム名の型
$field->uitype = 19;         //UIタイプ 1_VARCHAR, 7_数字,5_日付
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END 発注内容 *********************/

/** START アド粗利 ****/
$field = new Vtiger_Field();
$field->name = 'add_gross';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'add_gross'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END アド粗利 ****/

/** START SEO粗利 ****/
$field = new Vtiger_Field();
$field->name = 'seo_gross';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'seo_gross'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END SEO粗利 ****/

/** START 逆SEO粗利 ****/
$field = new Vtiger_Field();
$field->name = 'rseo_gross';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'rseo_gross'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END 逆SEO粗利 ****/

/** START 制作粗利 ****/
$field = new Vtiger_Field();
$field->name = 'create_gross';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'create_gross'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END 制作粗利 ****/

/** START 風評粗利 ****/
$field = new Vtiger_Field();
$field->name = 'rumor_gross';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'rumor_gross'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END 風評粗利 ****/

/** START ディレクション ****/
$field = new Vtiger_Field();
$field->name = 'direction';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'direction'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END ディレクション ****/

/** START 営業粗利 ****/
$field = new Vtiger_Field();
$field->name = 'sales_gross';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'sales_gross'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END 営業粗利 ****/


/** START 成果条件 ****/
$field = new Vtiger_Field();
$field->name = 'result_conditions';   #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'result_conditions';    #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'VARCHAR(255)'; //カラム名の型
$field->uitype = 1;  #1_テキスト入力
$field->typeofdata = 'V~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END 成果条件 ****/

?>