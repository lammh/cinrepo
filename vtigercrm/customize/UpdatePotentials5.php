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



/* 社内原価区分 */
$field = new Vtiger_Field();
$field->name = 'costkubun';
$field->table = $module->basetable;
$field->column = 'costkubun';
$field->columntype ='VARCHAR(200)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ("1:技術","2:マーケ","3:制作","4:ディレクション","5:風評技術") );



/* 社内原価 */
$field = new Vtiger_Field();
$field->name = 'costsyanai';
$field->table = $module->basetable;
$field->column = 'costsyanai';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* 社内原価区分 */
$field = new Vtiger_Field();
$field->name = 'costsyanaikb';
$field->table = $module->basetable;
$field->column = 'costsyanaikb';
$field->columntype ='VARCHAR(200)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ("1:技術","2:マーケ","3:制作","4:ディレクション","5:風評技術") );
?>