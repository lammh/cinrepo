<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('Sample');

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);

$blockInstance = $blockInstance->getInstance(123);


/* 資本金区分 */
$field = new Vtiger_Field();
$field->name = 'capital_type';
$field->table = $module->basetable;
$field->column = 'capital_type';
$field->columntype = 'VARCHAR(50)';
$field->uitype = 15;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);
$array = array("a_0～1000万円","b_1001万～3000万円", "c_3001万～5000万円", "d_5001万～1億円", "e_1億1円以上", "z_不明");
$field->setPicklistValues( $array );

/* 決算月 */
$field = new Vtiger_Field();
$field->name = 'closing_month';
$field->table = $module->basetable;
$field->column = 'closing_month';
$field->columntype = 'INT(10)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype=1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12) );

/* 業界 */
$field = new Vtiger_Field();
$field->name = 'industry';
$field->table = $module->basetable;
$field->column = 'industry';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);
$array = array("WEB　ITゲーム系","WEB　ITソフト系","WEB　その他","その他");
$field->setPicklistValues( $array );
