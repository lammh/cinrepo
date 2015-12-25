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
$blockInstance = $blockInstance->getInstance(1);



/* 掲載開始日　 */
$field = new Vtiger_Field();
$field->name = 'start_date';
$field->table = $module->basetable;
$field->column = 'start_date';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 5;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'D~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* 掲載終了日　 */
$field = new Vtiger_Field();
$field->name = 'end_date';
$field->table = $module->basetable;
$field->column = 'end_date';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 5;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'D~O';
$field->label = $field->column;
$blockInstance->addField($field);


?>