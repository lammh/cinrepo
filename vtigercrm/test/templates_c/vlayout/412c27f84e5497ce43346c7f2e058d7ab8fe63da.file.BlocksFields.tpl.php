<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 09:58:48
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Settings/ModuleDesigner/BlocksFields.tpl" */ ?>
<?php /*%%SmartyHeaderCode:136189089256791ed8d5b7e4-97395653%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '412c27f84e5497ce43346c7f2e058d7ab8fe63da' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Settings/ModuleDesigner/BlocksFields.tpl',
      1 => 1450267293,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '136189089256791ed8d5b7e4-97395653',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_56791ed8ea740',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56791ed8ea740')) {function content_56791ed8ea740($_smarty_tpl) {?><table id="md-block-fields">
<tr>
<td>
<div id="md-fields-toolbar">
	<h2><?php echo vtranslate('LBL_UITYPE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</h2>
	<ul id="md-fields-list" class="droptrue">
	<!-- Fields list generated with JS -->
	</ul>
</div>
</td>
<td>

<div id="md-add-block-btn">
	<img src="layouts/vlayout/modules/Settings/<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
/assets/images/block.png" alt="<?php echo vtranslate('LBL_ADD_BLOCK_ALT',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
"/> <a href="#" onclick="md_addBlock(); return false;"><?php echo vtranslate('LBL_ADD_BLOCK',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</a>
</div>

<div>
<ul id="md-blocks-ul">
<!-- Blocks added with JS -->
</ul>
</div>
</td>
</table><?php }} ?>