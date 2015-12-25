<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 11:28:50
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/EditFolder.tpl" */ ?>
<?php /*%%SmartyHeaderCode:352363241567933f2933191-09290059%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dcb51b4741a2dd29104db5915e47a987e6a34835' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/EditFolder.tpl',
      1 => 1450267294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '352363241567933f2933191-09290059',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'FOLDER_MODEL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_567933f29f650',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_567933f29f650')) {function content_567933f29f650($_smarty_tpl) {?>

<div id="addFolderContainer" class="modelContainer"><div class="modal-header"><button data-dismiss="modal" class="close" title="<?php echo vtranslate('LBL_CLOSE');?>
">x</button><h3><?php echo vtranslate('LBL_ADD_NEW_FOLDER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h3></div><form class="form-horizontal contentsBackground" id="addFolder" method="post" action="index.php"><input type="hidden" name="module" value="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
" /><input type="hidden" name="action" value="Folder" /><input type="hidden" name="mode" value="save" /><input type="hidden" name="folderid" value="<?php echo $_smarty_tpl->tpl_vars['FOLDER_MODEL']->value->getId();?>
" /><div class="modal-body"><div class="row-fluid verticalBottomSpacing"><span class="span4"><?php echo vtranslate('LBL_FOLDER_NAME',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<span class="redColor">*</span></span><span class="span7 row-fluid"><input data-validation-engine='validate[required]' id="foldername" name="foldername" class="span12" type="text" value="<?php echo vtranslate($_smarty_tpl->tpl_vars['FOLDER_MODEL']->value->getName(),$_smarty_tpl->tpl_vars['MODULE']->value);?>
"/></span></div><div class="row-fluid"><span class="span4"><?php echo vtranslate('LBL_FOLDER_DESCRIPTION',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span><span class="span7 row-fluid"><textarea class="span12" name="description" placeholder="<?php echo vtranslate('LBL_WRITE_YOUR_DESCRIPTION_HERE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><?php echo vtranslate($_smarty_tpl->tpl_vars['FOLDER_MODEL']->value->getDescription(),$_smarty_tpl->tpl_vars['MODULE']->value);?>
</textarea></span></div></div><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('ModalFooter.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</form></div><?php }} ?>