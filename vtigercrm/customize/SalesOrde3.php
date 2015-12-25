<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('SalesOrder');

$blockInstance = new Vtiger_Block();
// label���Ŋ����u���b�N���擾����
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(61);


$field = new Vtiger_Field();
$field->name = 'relatedfield';
$field->table = $module->basetable;
$field->column = 'relatedfield';
$field->columntype = 'INT(10)';
$field->uitype = 10;
$field->displaytype= 1;
$field->typeofdata =  'V~O';
$field->label = $field->column;
$blockInstance->addField($field);

$field->setRelatedModules(Array('Vendors', 'PurchaseOrder')); //�֘A���W���[�����w��






?>