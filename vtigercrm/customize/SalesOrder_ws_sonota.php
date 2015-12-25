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
$blockInstance = $blockInstance->getInstance(129);  #WS


/** START 承認依頼日 ******/
$field = new Vtiger_Field();
$field->name = 'conf1_dates';  #columnname fieldname
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'conf1_dates';
$field->columntype = 'VARCHAR(100)';
$field->typeofdata = 'D~O';
$field->uitype = 5;  #5_日付
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END  承認依頼日  ******/


/** START 上長承認日 ******/
$field = new Vtiger_Field();
$field->name = 'conf2_dates';  #columnname fieldname
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'conf2_dates';
$field->columntype = 'VARCHAR(100)';
$field->typeofdata = 'D~O';
$field->uitype = 5;  #5_日付
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END  上長承認日  ******/


/** START 専務承認日 ******/
$field = new Vtiger_Field();
$field->name = 'conf3_dates';  #columnname fieldname
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'conf3_dates';
$field->columntype = 'VARCHAR(100)';
$field->typeofdata = 'D~O';
$field->uitype = 5;  #5_日付
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END  専務承認日  ******/

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

?>