<?php /* Smarty version Smarty-3.1.7, created on 2015-12-24 05:01:58
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/PDFMaker/TemplateTools.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1269440171567b7c4640d1e9-54426568%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '69f22d00cae0ecf68b965f48460296553c497466' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/PDFMaker/TemplateTools.tpl',
      1 => 1450267291,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1269440171567b7c4640d1e9-54426568',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ALLOW_SET_AS' => 0,
    'IS_ACTIVE' => 0,
    'TEMPLATEID' => 0,
    'DEFAULT_BUTTON' => 0,
    'ACTIVATE_BUTTON' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_567b7c4645efa',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_567b7c4645efa')) {function content_567b7c4645efa($_smarty_tpl) {?>
<?php if ($_smarty_tpl->tpl_vars['ALLOW_SET_AS']->value=='yes'){?>
 
    <div class="recordNamesList">
        <div class="row-fluid">
            <div class="span10">
                <ul class="nav nav-list">
                    <?php if ($_smarty_tpl->tpl_vars['ALLOW_SET_AS']->value=='yes'){?>
                    <?php if ($_smarty_tpl->tpl_vars['IS_ACTIVE']->value!=vtranslate('Inactive','PDFMaker')){?>
                    <li><a href="javascript:void(0);" onClick="PDFMaker_Detail_Js.changeActiveOrDefault('<?php echo $_smarty_tpl->tpl_vars['TEMPLATEID']->value;?>
','default');"><?php echo $_smarty_tpl->tpl_vars['DEFAULT_BUTTON']->value;?>
</a></li>
                    <?php }?>
                    <li><a href="javascript:void(0);" onClick="PDFMaker_Detail_Js.changeActiveOrDefault('<?php echo $_smarty_tpl->tpl_vars['TEMPLATEID']->value;?>
','active');"><?php echo $_smarty_tpl->tpl_vars['ACTIVATE_BUTTON']->value;?>
</a></li>
                    <?php }?>
            </div>
        </div>
    </div>
<?php }?><?php }} ?>