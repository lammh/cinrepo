<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');
 
$moduleInstance = Vtiger_Module::getInstance('Invoice');

$field = Vtiger_Field::getInstance('cost_customer', $moduleInstance);
$field->delete();

?>