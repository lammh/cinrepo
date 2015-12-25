<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('Potentials');

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(1);  #ヨミ案件の詳細

/** 事務担当 *******/
$field = new Vtiger_Field();
$field->name = 'office_work_member';
$field->table = $module->basetable;
$field->column = 'office_work_member';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;
$blockInstance->addField($field);
/** 事務担当 *******/

#/** ライティング担当 *******/
#$field = new Vtiger_Field();
#$field->name = 'writing_member';
#$field->table = $module->basetable;
#$field->column = 'writing_member';
#$field->columntype = 'INT(19)';
#$field->uitype = 53;
#$field->displaytype= 1;
#$field->typeofdata = 'V';
#$field->label = $field->column;
#$blockInstance->addField($field);
#/** ライティング担当 *******/

/** 粗利率 *****/
#$field = new Vtiger_Field();
#$field->name = 'gross_margin';  #fieldname 日本語ダメ
#$field->table = $module->basetable; #vtiger_potential
#$field->column = 'gross_margin'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
#$field->columntype = 'INT(11)';
#$field->uitype = 7;  #7_数値
#$field->typeofdata = 'I~O';
#$field->displaytype= 1;
#$field->label = $field->column;
#$blockInstance->addField($field);
/** 粗利率 *****/