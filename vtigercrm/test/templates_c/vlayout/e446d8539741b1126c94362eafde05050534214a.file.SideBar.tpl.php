<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 11:25:31
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/SideBar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15682899265679332b90ecc4-38978103%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e446d8539741b1126c94362eafde05050534214a' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/SideBar.tpl',
      1 => 1450267295,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15682899265679332b90ecc4-38978103',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5679332b9c0dd',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5679332b9c0dd')) {function content_5679332b9c0dd($_smarty_tpl) {?>

<div class="sideBarContents"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SideBarLinks.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<div class="clearfix"></div><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SideBarWidgets.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><?php }} ?>