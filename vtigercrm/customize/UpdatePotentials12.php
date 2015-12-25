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



/*版１ */
$field = new Vtiger_Field();
$field->name = 'han1';
$field->table = $module->basetable;
$field->column = 'han1';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);


/*版２ */
$field = new Vtiger_Field();
$field->name = 'han2';
$field->table = $module->basetable;
$field->column = 'han2';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);


/*版３ */
$field = new Vtiger_Field();
$field->name = 'han3';
$field->table = $module->basetable;
$field->column = 'han3';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);



?>