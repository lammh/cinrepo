<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('Contacts');

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(4);




/* charge1 */
$field = new Vtiger_Field();
$field->name = 'charge1';
$field->table = $module->basetable;
$field->column = 'charge1';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;

$blockInstance->addField($field);


/* charge2 */
$field = new Vtiger_Field();
$field->name = 'charge2';
$field->table = $module->basetable;
$field->column = 'charge2';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;

$blockInstance->addField($field);


/* charge3 */
$field = new Vtiger_Field();
$field->name = 'charge3';
$field->table = $module->basetable;
$field->column = 'charge3';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;

$blockInstance->addField($field);


/* charge4 */
$field = new Vtiger_Field();
$field->name = 'charge4';
$field->table = $module->basetable;
$field->column = 'charge4';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;

$blockInstance->addField($field);



/* charge5 */
$field = new Vtiger_Field();
$field->name = 'charge5';
$field->table = $module->basetable;
$field->column = 'charge5';
$field->columntype = 'INT(19)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;

$blockInstance->addField($field);




/* 取引商材 */
$field = new Vtiger_Field();
$field->name = 'merchandise';
$field->table = $module->basetable;
$field->column = 'merchandise';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 21;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);




/* KintoneID */
$field = new Vtiger_Field();
$field->name = 'Kintoneid';
$field->table = $module->basetable;
$field->column = 'Kintoneid';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 20;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;
$blockInstance->addField($field);




/* kintoneCrlID */
$field = new Vtiger_Field();
$field->name = 'kintoneCrlID';
$field->table = $module->basetable;
$field->column = 'kintoneCrlID';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 20;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;
$blockInstance->addField($field);




/* ふりがな */
$field = new Vtiger_Field();
$field->name = 'kananame';
$field->table = $module->basetable;
$field->column = 'kananame';
$field->columntype = 'VARCHAR(250)';
$field->uitype = 1;
$field->sequence = 20;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;
$blockInstance->addField($field);




/* kesai */
$field = new Vtiger_Field();
$field->name = 'kesai';
$field->table = $module->basetable;
$field->column = 'kesai';
$field->columntype = 'INT(19)';
$field->uitype = 56;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;

$blockInstance->addField($field);
