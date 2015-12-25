<?php /* Smarty version Smarty-3.1.7, created on 2015-12-24 05:01:57
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/PDFMaker/SettingsList.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1657332261567b7c45e4e151-71591538%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd7f71c56c21b29d7fa9dea443b36dcb8d6d75c66' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/PDFMaker/SettingsList.tpl',
      1 => 1450267292,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1657332261567b7c45e4e151-71591538',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUICK_LINKS' => 0,
    'SIDEBARLINK' => 0,
    'SIDE_LINK_URL' => 0,
    'EXPLODED_PARSE_URL' => 0,
    'COUNT_OF_EXPLODED_URL' => 0,
    'EXPLODED_URL' => 0,
    'CURRENT_PVIEW' => 0,
    'CURRENT_LINK_VIEW' => 0,
    'PARSE_URL' => 0,
    'MODULE' => 0,
    'CURRENT_MODULE_NAME' => 0,
    'LINK_LIST_VIEW' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_567b7c45f24c4',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_567b7c45f24c4')) {function content_567b7c45f24c4($_smarty_tpl) {?>
<div  id="settingsQuickWidgetContainer" style="margin:0px;padding:0px">
        <div class="widgetContainer" id="Settings_sideBar_LBL_OTHER_SETTINGS" > 
        <?php  $_smarty_tpl->tpl_vars['SIDEBARLINK'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['SIDEBARLINK']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['QUICK_LINKS']->value['SIDEBARLINK']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['SIDEBARLINK']->key => $_smarty_tpl->tpl_vars['SIDEBARLINK']->value){
$_smarty_tpl->tpl_vars['SIDEBARLINK']->_loop = true;
?>
            <?php $_smarty_tpl->tpl_vars['SIDE_LINK_URL'] = new Smarty_variable(decode_html($_smarty_tpl->tpl_vars['SIDEBARLINK']->value->getUrl()), null, 0);?>
            <?php $_smarty_tpl->tpl_vars["EXPLODED_PARSE_URL"] = new Smarty_variable(explode('?',$_smarty_tpl->tpl_vars['SIDE_LINK_URL']->value), null, 0);?>
            <?php $_smarty_tpl->tpl_vars["COUNT_OF_EXPLODED_URL"] = new Smarty_variable(count($_smarty_tpl->tpl_vars['EXPLODED_PARSE_URL']->value), null, 0);?>
            <?php if ($_smarty_tpl->tpl_vars['COUNT_OF_EXPLODED_URL']->value>1){?>
                <?php $_smarty_tpl->tpl_vars["EXPLODED_URL"] = new Smarty_variable($_smarty_tpl->tpl_vars['EXPLODED_PARSE_URL']->value[$_smarty_tpl->tpl_vars['COUNT_OF_EXPLODED_URL']->value-1], null, 0);?>
            <?php }?>
            <?php $_smarty_tpl->tpl_vars["PARSE_URL"] = new Smarty_variable(explode('&',$_smarty_tpl->tpl_vars['EXPLODED_URL']->value), null, 0);?>
            <?php $_smarty_tpl->tpl_vars["CURRENT_LINK_VIEW"] = new Smarty_variable(('view=').($_smarty_tpl->tpl_vars['CURRENT_PVIEW']->value), null, 0);?>
            <?php $_smarty_tpl->tpl_vars["LINK_LIST_VIEW"] = new Smarty_variable(in_array($_smarty_tpl->tpl_vars['CURRENT_LINK_VIEW']->value,$_smarty_tpl->tpl_vars['PARSE_URL']->value), null, 0);?>
            <?php $_smarty_tpl->tpl_vars["CURRENT_MODULE_NAME"] = new Smarty_variable(('module=').($_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?>
            <?php $_smarty_tpl->tpl_vars["IS_LINK_MODULE_NAME"] = new Smarty_variable(in_array($_smarty_tpl->tpl_vars['CURRENT_MODULE_NAME']->value,$_smarty_tpl->tpl_vars['PARSE_URL']->value), null, 0);?>
            <div class="<?php if ($_smarty_tpl->tpl_vars['LINK_LIST_VIEW']->value){?> selectedMenuItem selectedListItem<?php }?>" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem"  data-actionurl="">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['SIDE_LINK_URL']->value;?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_settingsBar_link_<?php echo Vtiger_Util_Helper::replaceSpaceWithUnderScores($_smarty_tpl->tpl_vars['SIDEBARLINK']->value->getLabel());?>
" class="textOverflowEllipsis span9 menuItemLabel" data-menu-item="true" ><?php echo vtranslate($_smarty_tpl->tpl_vars['SIDEBARLINK']->value->getLabel(),$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        <?php } ?>
    </div>
</div><?php }} ?>