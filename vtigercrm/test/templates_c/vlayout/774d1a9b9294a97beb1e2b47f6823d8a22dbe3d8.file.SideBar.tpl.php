<?php /* Smarty version Smarty-3.1.7, created on 2015-12-24 05:01:55
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/PDFMaker/SideBar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2122860877567b7c43aa7810-35426477%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '774d1a9b9294a97beb1e2b47f6823d8a22dbe3d8' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/PDFMaker/SideBar.tpl',
      1 => 1450267292,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2122860877567b7c43aa7810-35426477',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_567b7c43ad266',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_567b7c43ad266')) {function content_567b7c43ad266($_smarty_tpl) {?>
<div class="sideBarContents"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SideBarLinks.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<div class="clearfix"></div><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SideBarWidgets.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><?php }} ?>