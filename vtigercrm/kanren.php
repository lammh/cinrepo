<?
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');


//$module = Vtiger_Module::getInstance('SalesOrder');
//$module = Vtiger_Module::getInstance('Vendors');
$module = Vtiger_Module::getInstance('Accounts');

$function_name = 'get_campaigns';
//$function_name = 'get_related_list';
//$relate_module = 'Vendors';
//$relate_module = 'SalesOrder';
$relate_module = 'Sample';
$target_module_instance = Vtiger_Module::getInstance('Sample');
$module->unsetRelatedList($target_module_instance);
$module->setRelatedList(
    Vtiger_Module::getInstance($relate_module), 
    $relate_module, 
    Array('select', 'add'), 
    $function_name
);
?>