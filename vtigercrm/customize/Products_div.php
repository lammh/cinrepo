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

/* 部署 */
$field = new Vtiger_Field();
$field->name = 'division';
$field->table = $module->basetable;
$field->column = 'division';
$field->columntype =  'VARCHAR(240)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ("101:求人広告","102:人材紹介","103:人材派遣","201:風評コンサル","202:Webコミュ","203:サロコン","204:Eコマース") );
