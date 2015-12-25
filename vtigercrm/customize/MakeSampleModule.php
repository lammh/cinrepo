<?php
chdir('..');
$Vtiger_Utils_Log = true;

include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');
include_once('include/utils/CommonUtils.php');

$module_name = 'Sample';
$table_name = 'vtiger_sample';
$main_name = "titlename";
$main_id = "sampleid";

//module作成
$module = new Vtiger_Module();
$module->name = $module_name;
$module->save();
$module->initTables($table_name, $main_id);

$menu = Vtiger_Menu::getInstance('Tools');
$menu->addModule($module);

// block作成
$blockInstance = new Vtiger_Block();
$blockInstance->label = 'LBL_SAMPLE_INFORMATION';
$module->addBlock($blockInstance);

// field作成
$field = new Vtiger_Field();
$field->name = 'TitleName';
$field->table = 'vtiger_sample';
$field->column = 'titlename';
$field->columntype = 'VARCHAR(255)';
$field->uitype = 1;
$field->typeofdata = 'V~M';
$blockInstance->addField($field);
$filter_field1 = $field;

/*
 * モジュール内でキーとなるカラム1つに対して実行
 * 複数回は実施しないこと
 */
$module->setEntityIdentifier($field);

/** 全モジュール共通必要項目 Start **/

/* 担当 */
$field = new Vtiger_Field();
$field->name = 'assigned_user_id';
$field->label = 'Assigned To';
$field->table = 'vtiger_crmentity';
$field->column = 'smownerid';
$field->uitype = 53;
$field->typeofdata = 'V~M';
$blockInstance->addField($field);
$filter_field2 = $field;

// 作成日時
$field = new Vtiger_Field();
$field->name = 'CreatedTime';
$field->label= 'Created Time';
$field->table = 'vtiger_crmentity';
$field->column = 'createdtime';
$field->uitype = 70;
$field->typeofdata = 'T~O';
$field->displaytype= 2;
$blockInstance->addField($field);
$filter_field3 = $field;

// 更新日時
$field = new Vtiger_Field();
$field->name = 'ModifiedTime';
$field->label= 'Modified Time';
$field->table = 'vtiger_crmentity';
$field->column = 'modifiedtime';
$field->uitype = 70;
$field->typeofdata = 'T~O';
$field->displaytype= 2;
$blockInstance->addField($field);
$filter_field4 = $field;

/** 全モジュール共通必要項目 End **/

// 関連付対応
$function_name = 'get_attachments';
$relate_module = 'Documents';
$module->setRelatedList(Vtiger_Module::getInstance($relate_module), $relate_module, Array('select', 'add'), $function_name);

//set filter
$filter = new Vtiger_Filter();
$filter->name = 'All';
$filter->isdefault = true;
$module->addFilter($filter);
$filter->addField($filter_field1)->
addField($filter_field2, 1)->
addField($filter_field3, 2)->
addField($filter_field4, 3);

$module->initWebservice();

// vtlibは新規モジュールのbase tableにインデックスを貼らないため
// 下記Queryで代替する。
global $adb;
$query = "ALTER TABLE vtiger_sample ADD PRIMARY KEY (sampleid)";
$adb->query($query);

// 初期共有設定を行う
// 本設定はモジュール内全てのデータを公開
$module->setDefaultSharing('Public_ReadWriteDelete');


