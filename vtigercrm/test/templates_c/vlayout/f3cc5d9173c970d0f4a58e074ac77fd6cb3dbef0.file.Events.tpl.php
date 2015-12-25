<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 09:58:48
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Settings/ModuleDesigner/Events.tpl" */ ?>
<?php /*%%SmartyHeaderCode:201043997856791ed8f1c253-48862954%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f3cc5d9173c970d0f4a58e074ac77fd6cb3dbef0' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Settings/ModuleDesigner/Events.tpl',
      1 => 1450267293,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '201043997856791ed8f1c253-48862954',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_56791ed904613',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56791ed904613')) {function content_56791ed904613($_smarty_tpl) {?><table id="md-events-table">
<tr>
<td>
<div id="md-events-toolbar">
	<h2><?php echo vtranslate('LBL_EVENTS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</h2>
	
	<ul id="md-events-list">
	<li><?php echo vtranslate('vtiger.entity.beforesave.modifiable',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</li>
	<li><?php echo vtranslate('vtiger.entity.beforesave',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</li>
	<li><?php echo vtranslate('vtiger.entity.beforesave.final',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</li>
	<li><?php echo vtranslate('vtiger.entity.aftersave',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</li>
	<li><?php echo vtranslate('vtiger.entity.aftersave.final',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</li>
	<li><?php echo vtranslate('vtiger.entity.beforedelete',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</li>
	<li><?php echo vtranslate('vtiger.entity.afterdelete',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</li>
	<li><?php echo vtranslate('vtiger.entity.afterrestore',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</li>
	</ul>
</div>
</td>
<td>
<div>
<ul id="md-events-ul" class="md-events-ul">
<!-- Events added with JS -->
</ul>
</div>
</td>
</table><?php }} ?>