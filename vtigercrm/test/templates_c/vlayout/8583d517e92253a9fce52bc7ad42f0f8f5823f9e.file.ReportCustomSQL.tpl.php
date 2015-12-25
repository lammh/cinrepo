<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 11:25:41
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ReportCustomSQL.tpl" */ ?>
<?php /*%%SmartyHeaderCode:67341447356793335a97cf1-87689058%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8583d517e92253a9fce52bc7ad42f0f8f5823f9e' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ReportCustomSQL.tpl',
      1 => 1450267294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '67341447356793335a97cf1-87689058',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'custom_style' => 0,
    'REPORT_CUSTOM_SQL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_56793335b9458',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56793335b9458')) {function content_56793335b9458($_smarty_tpl) {?>

<div class="row-fluid"><div class="span9"><div class="row-fluid"><table class="table table-bordered table-report"><thead><tr class="blockHeader"><th><?php echo vtranslate('LBL_REPORT_SQL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</th></tr></thead><tbody><tr style="height:25px"><td align="left" <?php echo $_smarty_tpl->tpl_vars['custom_style']->value;?>
 ><textarea name="reportcustomsql" id="reportcustomsql" class="txtBox" rows="12"><?php echo $_smarty_tpl->tpl_vars['REPORT_CUSTOM_SQL']->value;?>
</textarea></td></tr></tbody></table></div></div><div class="span4" style="width: 20%;"><div class="row-fluid"><table class="table table-bordered table-report"><thead><tr class="blockHeader"><th colspan="2"><i class="icon-info-sign"></i>&nbsp;<?php echo vtranslate('LBL_REPORT_SQL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<br></th></tr></thead><tbody><tr style="height:25px"><td><div class="padding1per"><span><?php echo vtranslate('LBL_CUSTOMSTEP12_INFO',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div></td></tr></tbody></table></div></div></div><?php }} ?>