<?php /* Smarty version Smarty-3.1.7, created on 2015-12-24 05:01:56
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/PDFMaker/ListPDFTemplatesContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:364813543567b7c443741b4-54274328%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1fb312a46cdf516815a6e5bc58e25c56df7d611f' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/PDFMaker/ListPDFTemplatesContents.tpl',
      1 => 1450267292,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '364813543567b7c443741b4-54274328',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'DIR' => 0,
    'ORDERBY' => 0,
    'name_dir' => 0,
    'dir_img' => 0,
    'module_dir' => 0,
    'description_dir' => 0,
    'VERSION_TYPE' => 0,
    'PDFTEMPLATES' => 0,
    'template' => 0,
    'THEME' => 0,
    'VERSION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_567b7c44465e9',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_567b7c44465e9')) {function content_567b7c44465e9($_smarty_tpl) {?>
<?php if ($_smarty_tpl->tpl_vars['DIR']->value=='asc'){?>
    <?php $_smarty_tpl->tpl_vars["dir_img"] = new Smarty_variable('<img src="layouts/vlayout/skins/images/upArrowSmall.png" border="0" />', null, 0);?>
<?php }else{ ?>
    <?php $_smarty_tpl->tpl_vars["dir_img"] = new Smarty_variable('<img src="layouts/vlayout/skins/images/downArrowSmall.png" border="0" />', null, 0);?>
<?php }?>

<?php $_smarty_tpl->tpl_vars["name_dir"] = new Smarty_variable("asc", null, 0);?>
<?php $_smarty_tpl->tpl_vars["module_dir"] = new Smarty_variable("asc", null, 0);?>
<?php $_smarty_tpl->tpl_vars["description_dir"] = new Smarty_variable("asc", null, 0);?>
<?php $_smarty_tpl->tpl_vars["order_dir"] = new Smarty_variable("asc", null, 0);?>

<?php if ($_smarty_tpl->tpl_vars['ORDERBY']->value=='filename'&&$_smarty_tpl->tpl_vars['DIR']->value=='asc'){?>
    <?php $_smarty_tpl->tpl_vars["name_dir"] = new Smarty_variable("desc", null, 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['ORDERBY']->value=='module'&&$_smarty_tpl->tpl_vars['DIR']->value=='asc'){?>
    <?php $_smarty_tpl->tpl_vars["module_dir"] = new Smarty_variable("desc", null, 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['ORDERBY']->value=='description'&&$_smarty_tpl->tpl_vars['DIR']->value=='asc'){?>
    <?php $_smarty_tpl->tpl_vars["description_dir"] = new Smarty_variable("desc", null, 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['ORDERBY']->value=='order'&&$_smarty_tpl->tpl_vars['DIR']->value=='asc'){?>
    <?php $_smarty_tpl->tpl_vars["order_dir"] = new Smarty_variable("desc", null, 0);?>
<?php }?>


<div class="listViewEntriesDiv contents-bottomscroll">
    <div class="bottomscroll-div">

        <table border=0 cellspacing=0 cellpadding=5 width=100% class="table table-bordered listViewEntriesTable">
            <thead>
                <tr class="listViewHeaders">
                    <th width="2%" class="narrowWidthType">#</td>
                    <th width="3%" class="narrowWidthType"><?php echo vtranslate("LBL_LIST_SELECT","PDFMaker");?>
</td>
                    <th width="20%" class="narrowWidthType"><a href="index.php?module=PDFMaker&view=List&orderby=name&dir=<?php echo $_smarty_tpl->tpl_vars['name_dir']->value;?>
"><?php echo vtranslate("LBL_PDF_NAME","PDFMaker");?>
<?php if ($_smarty_tpl->tpl_vars['ORDERBY']->value=='filename'){?><?php echo $_smarty_tpl->tpl_vars['dir_img']->value;?>
<?php }?></a></td>
                    <th width="20%" class="narrowWidthType"><a href="index.php?module=PDFMaker&view=List&orderby=module&dir=<?php echo $_smarty_tpl->tpl_vars['module_dir']->value;?>
"><?php echo vtranslate("LBL_MODULENAMES","PDFMaker");?>
<?php if ($_smarty_tpl->tpl_vars['ORDERBY']->value=='module'){?><?php echo $_smarty_tpl->tpl_vars['dir_img']->value;?>
<?php }?></a></td>
                    <th width="34%" class="narrowWidthType"><a href="index.php?module=PDFMaker&view=List&orderby=description&dir=<?php echo $_smarty_tpl->tpl_vars['description_dir']->value;?>
"><?php echo vtranslate("LBL_DESCRIPTION","PDFMaker");?>
<?php if ($_smarty_tpl->tpl_vars['ORDERBY']->value=='description'){?><?php echo $_smarty_tpl->tpl_vars['dir_img']->value;?>
<?php }?></a></td>
                    
                    <?php if ($_smarty_tpl->tpl_vars['VERSION_TYPE']->value!='deactivate'){?><th width="5%" class="narrowWidthType"><?php echo vtranslate("Status");?>
</td>
                    <th width="11%" class="narrowWidthType"><?php echo vtranslate("LBL_ACTION","PDFMaker");?>
</td><?php }?>
                </tr>
            </thead>
            <tbody>
            <?php  $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['template']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['PDFTEMPLATES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['mailmerge']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['template']->key => $_smarty_tpl->tpl_vars['template']->value){
$_smarty_tpl->tpl_vars['template']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['mailmerge']['iteration']++;
?>
                <tr class="listViewEntries" <?php if ($_smarty_tpl->tpl_vars['template']->value['status']==0){?> style="font-style:italic;" <?php }?> data-id="<?php echo $_smarty_tpl->tpl_vars['template']->value['templateid'];?>
" data-recordurl="index.php?module=PDFMaker&view=Detail&templateid=<?php echo $_smarty_tpl->tpl_vars['template']->value['templateid'];?>
" id="PDFMaker_listView_row_<?php echo $_smarty_tpl->tpl_vars['template']->value['templateid'];?>
">
                    <td class="narrowWidthType" valign=top><?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['mailmerge']['iteration'];?>
</td>
                    <td class="narrowWidthType" valign=top><input type="checkbox" class=small name="selected_id" value="<?php echo $_smarty_tpl->tpl_vars['template']->value['templateid'];?>
"></td>
                    <td class="narrowWidthType" valign=top><?php echo $_smarty_tpl->tpl_vars['template']->value['filename'];?>
</td>
                    <td class="narrowWidthType" valign=top <?php if ($_smarty_tpl->tpl_vars['template']->value['status']==0){?> style="color:#888;" <?php }?>><?php echo $_smarty_tpl->tpl_vars['template']->value['module'];?>
</a></td>
                    <td class="narrowWidthType" valign=top <?php if ($_smarty_tpl->tpl_vars['template']->value['status']==0){?> style="color:#888;" <?php }?>><?php echo $_smarty_tpl->tpl_vars['template']->value['description'];?>
&nbsp;</td>
                    
                <?php if ($_smarty_tpl->tpl_vars['VERSION_TYPE']->value!='deactivate'){?><td class="narrowWidthType" valign=top <?php if ($_smarty_tpl->tpl_vars['template']->value['status']==0){?> style="color:#888;" <?php }?>><?php echo $_smarty_tpl->tpl_vars['template']->value['status_lbl'];?>
&nbsp;</td>
                    <td class="narrowWidthType" valign=top nowrap><?php echo $_smarty_tpl->tpl_vars['template']->value['edit'];?>
</td><?php }?>
                </tr>
            <?php }
if (!$_smarty_tpl->tpl_vars['template']->_loop) {
?>
                <tr>
                    <td style="background-color:#efefef;height:340px" align="center" colspan="6">
                        <div style="border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 45%; position: relative; z-index: 10000000;">
                            <table border="0" cellpadding="5" cellspacing="0" width="98%">
                                <tr><td rowspan="2" width="25%"><img src="<?php echo vtiger_imageurl('empty.jpg',$_smarty_tpl->tpl_vars['THEME']->value);?>
" height="60" width="61"></td>
                                    <td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%" align="left">
                                        <span class="genHeaderSmall"><?php echo vtranslate("LBL_NO");?>
 <?php echo vtranslate("LBL_TEMPLATE","PDFMaker");?>
 <?php echo vtranslate("LBL_FOUND","PDFMaker");?>
</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="small" align="left" nowrap="nowrap">
                                        &nbsp;&nbsp;-<a href="index.php?module=PDFMaker&view=Edit"><?php echo vtranslate("LBL_CREATE_NEW");?>
 <?php echo vtranslate("LBL_TEMPLATE","PDFMaker");?>
</a><br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    </div> 
</div> 
<br>
<div align="center" class="small" style="color: rgb(153, 153, 153);"><?php echo vtranslate("PDF_MAKER","PDFMaker");?>
 <?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
 <?php echo vtranslate("COPYRIGHT","PDFMaker");?>
</div><?php }} ?>