<?php /* Smarty version Smarty-3.1.7, created on 2015-12-24 00:24:48
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Vtiger/DocumentsSummaryWidgetContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1713523767567b3b5085e123-71283561%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3522169e7b1d4c140127dfccf794758962acdf58' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Vtiger/DocumentsSummaryWidgetContents.tpl',
      1 => 1450267290,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1713523767567b3b5085e123-71283561',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'RELATED_RECORDS' => 0,
    'RELATED_RECORD' => 0,
    'MODULE' => 0,
    'RELATED_MODULE' => 0,
    'DOWNLOAD_STATUS' => 0,
    'NUMBER_OF_RECORDS' => 0,
    'MODULE_NAME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_567b3b5099a1a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_567b3b5099a1a')) {function content_567b3b5099a1a($_smarty_tpl) {?>
<div class="row-fluid"><span class="span7"><strong><?php echo vtranslate('Title','Documents');?>
</strong></span><span class="span4 horizontalLeftSpacingForSummaryWidgetHeader"><span class="pull-right"><strong><?php echo vtranslate('File Name','Documents');?>
</strong></span></span></div><?php  $_smarty_tpl->tpl_vars['RELATED_RECORD'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['RELATED_RECORD']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['RELATED_RECORDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['RELATED_RECORD']->key => $_smarty_tpl->tpl_vars['RELATED_RECORD']->value){
$_smarty_tpl->tpl_vars['RELATED_RECORD']->_loop = true;
?><?php if ($_smarty_tpl->tpl_vars['RELATED_RECORD']->value!=null){?><?php $_smarty_tpl->tpl_vars['DOWNLOAD_FILE_URL'] = new Smarty_variable($_smarty_tpl->tpl_vars['RELATED_RECORD']->value->getDownloadFileURL(), null, 0);?><?php $_smarty_tpl->tpl_vars['DOWNLOAD_STATUS'] = new Smarty_variable($_smarty_tpl->tpl_vars['RELATED_RECORD']->value->get('filestatus'), null, 0);?><?php $_smarty_tpl->tpl_vars['DOWNLOAD_LOCATION_TYPE'] = new Smarty_variable($_smarty_tpl->tpl_vars['RELATED_RECORD']->value->get('filelocationtype'), null, 0);?><div class="recentActivitiesContainer" id="relatedDocuments"><ul class="unstyled"><li><div class="row-fluid" id="documentRelatedRecord"><span class="span7 textOverflowEllipsis"><a href="<?php echo $_smarty_tpl->tpl_vars['RELATED_RECORD']->value->getDetailViewUrl();?>
" id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['RELATED_MODULE']->value;?>
_Related_Record_<?php echo $_smarty_tpl->tpl_vars['RELATED_RECORD']->value->get('id');?>
" title="<?php echo $_smarty_tpl->tpl_vars['RELATED_RECORD']->value->getDisplayValue('notes_title');?>
"><?php echo $_smarty_tpl->tpl_vars['RELATED_RECORD']->value->getDisplayValue('notes_title');?>
</a></span><span class="span5 textOverflowEllipsis" id="DownloadableLink"><?php if ($_smarty_tpl->tpl_vars['DOWNLOAD_STATUS']->value==1){?><?php echo $_smarty_tpl->tpl_vars['RELATED_RECORD']->value->getDisplayValue('filename',$_smarty_tpl->tpl_vars['RELATED_RECORD']->value->getId(),$_smarty_tpl->tpl_vars['RELATED_RECORD']->value);?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['RELATED_RECORD']->value->get('filename');?>
<?php }?></span></div></li></ul></div><?php }?><?php } ?><?php $_smarty_tpl->tpl_vars['NUMBER_OF_RECORDS'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['RELATED_RECORDS']->value), null, 0);?><?php if ($_smarty_tpl->tpl_vars['NUMBER_OF_RECORDS']->value==5){?><div class="row-fluid"><div class="pull-right"><a class="moreRecentDocuments cursorPointer"><?php echo vtranslate('LBL_MORE',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</a></div></div><?php }?><?php }} ?>