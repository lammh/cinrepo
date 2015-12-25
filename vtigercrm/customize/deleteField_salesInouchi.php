<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');
 
$moduleInstance = Vtiger_Module::getInstance('Products');

$field = Vtiger_Field::getInstance('division', $moduleInstance);
$field->delete();
