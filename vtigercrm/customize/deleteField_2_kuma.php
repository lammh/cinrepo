<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');
 
$moduleInstance = Vtiger_Module::getInstance('SalesOrder');

$field = Vtiger_Field::getInstance('hr_net_price', $moduleInstance);
$field->delete();


#$field = Vtiger_Field::getInstance('hr_claim_flag', $moduleInstance);
#$field->delete();

#$field = Vtiger_Field::getInstance('margin_rate_total', $moduleInstance);
#$field->delete();

#$field = Vtiger_Field::getInstance('hr_discount', $moduleInstance);
#$field->delete();


#$field = Vtiger_Field::getInstance('introduction_member', $moduleInstance);
#$field->delete();

?>