<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');
 
$moduleInstance = Vtiger_Module::getInstance('vtiger_crmentity');

$field = Vtiger_Field::getInstance('charge1', $moduleInstance);
$field->delete();
$field = Vtiger_Field::getInstance('charge2', $moduleInstance);
$field->delete();
$field = Vtiger_Field::getInstance('charge3', $moduleInstance);
$field->delete();
?>