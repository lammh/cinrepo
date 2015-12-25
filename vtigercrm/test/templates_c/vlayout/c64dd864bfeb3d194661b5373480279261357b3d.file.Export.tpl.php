<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 09:58:49
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Settings/ModuleDesigner/Export.tpl" */ ?>
<?php /*%%SmartyHeaderCode:106510164356791ed90a4f68-39692031%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c64dd864bfeb3d194661b5373480279261357b3d' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Settings/ModuleDesigner/Export.tpl',
      1 => 1450267293,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '106510164356791ed90a4f68-39692031',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_56791ed90ec56',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56791ed90ec56')) {function content_56791ed90ec56($_smarty_tpl) {?><button onclick="md_makePackage(false)"><?php echo vtranslate('LBL_MAKE_PACKAGE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</button><br /><br />
<button onclick="md_makePackage(true)"><?php echo vtranslate('LBL_CREATE_AND_INSTALL_PACKAGE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</button><?php }} ?>