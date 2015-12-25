<?
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');



$module = Vtiger_Module::getInstance('Accounts');



$function_name = 'get_related_list';
$relate_module = 'PaymentManagement';

$module->setRelatedList(Vtiger_Module::getInstance($relate_module), $relate_module, Array('select', 'add'), $function_name);
?>