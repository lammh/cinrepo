<?
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');



$module = Vtiger_Module::getInstance('SalesOrder');

//$module = Vtiger_Module::getInstance('ModComments');

$function_name = 'get_related_list';
$relate_module = 'ModComments';
//$relate_module = 'SalesOrder';
$module->setRelatedList(Vtiger_Module::getInstance($relate_module), $relate_module, Array('select', 'add'), $function_name);
?>