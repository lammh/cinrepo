<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');
 
$moduleInstance = Vtiger_Module::getInstance('Potentials');

$field = Vtiger_Field::getInstance('create_status', $moduleInstance);
$field->delete();





?>