<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 09:58:49
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Settings/ModuleDesigner/Custom.tpl" */ ?>
<?php /*%%SmartyHeaderCode:201863973456791ed907b694-17018919%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c9e796c5f032dae8a8df269788f944354d94f5bb' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Settings/ModuleDesigner/Custom.tpl',
      1 => 1450267293,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '201863973456791ed907b694-17018919',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'QUALIFIED_MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_56791ed909efb',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56791ed909efb')) {function content_56791ed909efb($_smarty_tpl) {?><script type="text/javascript" src="layouts/vlayout/modules/Settings/<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
/resources/CustomScript.js"></script>

<h2><?php echo vtranslate('LBL_CUSTOM_VALUES',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</h2>

<table>
<tr>
<td colspan="2">
<?php echo vtranslate('LBL_CUSTOM_VALUES_DESCRIPTION',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>

</td>
</tr>
<tr>
<td><?php echo vtranslate('LBL_MY_VARIABLE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</td>
<td>
<select name="myVariable">
<option value="value1"><?php echo vtranslate('LBL_MY_VALUE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 1</option>
<option value="value2"><?php echo vtranslate('LBL_MY_VALUE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 2</option>
</select>
</td>
</tr>
</table><?php }} ?>