<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 05:34:58
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Workflow2/dashboards/Permissions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18598192955678e10281eec0-97766639%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8c9541237a0fd6b7e17847c346d476801d99ba06' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Workflow2/dashboards/Permissions.tpl',
      1 => 1450267295,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18598192955678e10281eec0-97766639',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE_NAME' => 0,
    'DATA' => 0,
    'block' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5678e1028ebe1',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5678e1028ebe1')) {function content_5678e1028ebe1($_smarty_tpl) {?>
<script type="text/javascript">
	Vtiger_Barchat_Widget_Js('Vtiger_Leadsbyindustry_Widget_Js',{},{});
</script>

<div class="dashboardWidgetHeader">
	<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("dashboards/WidgetHeader.tpl",$_smarty_tpl->tpl_vars['MODULE_NAME']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('SETTING_EXIST'=>false), 0);?>

</div>
<div class="dashboardWidgetContent" style="padding:4%;paddint-top:10px;">
    <strong><?php echo vtranslate('HEADLINE_WORKFLOW2_PERMISSION_PAGE','Workflow2');?>
</strong><br/>
    <table width="100%" border="0">
    <?php  $_smarty_tpl->tpl_vars['block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['block']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['DATA']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['block']->key => $_smarty_tpl->tpl_vars['block']->value){
$_smarty_tpl->tpl_vars['block']->_loop = true;
?>
        <tr onclick="window.location.href='index.php?module=Workflow2&view=List';">
            <td><a href="index.php?module=Workflow2&view=List"><?php echo $_smarty_tpl->tpl_vars['block']->value[0];?>
</a></td>
            <td><?php echo $_smarty_tpl->tpl_vars['block']->value[1];?>
</td>
        </tr>
    <?php } ?>
    </table>
</div><?php }} ?>