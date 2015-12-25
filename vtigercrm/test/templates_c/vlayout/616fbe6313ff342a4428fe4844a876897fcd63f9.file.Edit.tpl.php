<?php /* Smarty version Smarty-3.1.7, created on 2015-12-24 05:02:25
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/PDFMaker/Edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1349185147567b7c619ca6a9-87527726%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '616fbe6313ff342a4428fe4844a876897fcd63f9' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/PDFMaker/Edit.tpl',
      1 => 1450267291,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1349185147567b7c619ca6a9-87527726',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'PARENTTAB' => 0,
    'SAVETEMPLATEID' => 0,
    'EMODE' => 0,
    'DUPLICATE_FILENAME' => 0,
    'FILENAME' => 0,
    'MODULE' => 0,
    'DESCRIPTION' => 0,
    'TEMPLATEID' => 0,
    'SELECTMODULE' => 0,
    'MODULENAMES' => 0,
    'SELECT_MODULE_FIELD' => 0,
    'RELATED_MODULES' => 0,
    'RELATED_BLOCKS' => 0,
    'IS_LISTVIEW_CHECKED' => 0,
    'LISTVIEW_BLOCK_TPL' => 0,
    'GLOBAL_LANG_LABELS' => 0,
    'MODULE_LANG_LABELS' => 0,
    'TYPE' => 0,
    'CUSTOM_LANG_LABELS' => 0,
    'CUI_BLOCKS' => 0,
    'ACCOUNTINFORMATIONS' => 0,
    'USERINFORMATIONS' => 0,
    'LOGGEDUSERINFORMATION' => 0,
    'MULTICOMPANYINFORMATIONS' => 0,
    'LBL_MULTICOMPANY' => 0,
    'INVENTORYTERMSANDCONDITIONS' => 0,
    'DATE_VARS' => 0,
    'CUSTOM_FUNCTIONS' => 0,
    'HEAD_FOOT_VARS' => 0,
    'DH_ALL' => 0,
    'DH_FIRST' => 0,
    'DH_OTHER' => 0,
    'DF_ALL' => 0,
    'DF_FIRST' => 0,
    'DF_OTHER' => 0,
    'DF_LAST' => 0,
    'PRODUCT_BLOC_TPL' => 0,
    'ARTICLE_STRINGS' => 0,
    'SELECT_PRODUCT_FIELD' => 0,
    'PRODUCTS_FIELDS' => 0,
    'SERVICES_FIELDS' => 0,
    'NAME_OF_FILE' => 0,
    'FILENAME_FIELDS' => 0,
    'SELECT_MODULE_FIELD_FILENAME' => 0,
    'FORMATS' => 0,
    'SELECT_FORMAT' => 0,
    'CUSTOM_FORMAT' => 0,
    'ORIENTATIONS' => 0,
    'SELECT_ORIENTATION' => 0,
    'IGNORE_PICKLIST_VALUES' => 0,
    'MARGINS' => 0,
    'margin_input_width' => 0,
    'DECIMALS' => 0,
    'STATUS' => 0,
    'IS_ACTIVE' => 0,
    'IS_DEFAULT_DV_CHECKED' => 0,
    'IS_DEFAULT_LV_CHECKED' => 0,
    'ORDER' => 0,
    'IS_PORTAL_CHECKED' => 0,
    'TEMPLATE_OWNERS' => 0,
    'TEMPLATE_OWNER' => 0,
    'SHARINGTYPES' => 0,
    'SHARINGTYPE' => 0,
    'APP' => 0,
    'GROUPNAME' => 0,
    'MEMBER' => 0,
    'element' => 0,
    'BODY' => 0,
    'HEADER' => 0,
    'FOOTER' => 0,
    'VERSION' => 0,
    'MODULE_BLOCKS' => 0,
    'blockname' => 0,
    'moduleblocks' => 0,
    'MODULE_FIELDS' => 0,
    'modulename' => 0,
    'modulefields' => 0,
    'field' => 0,
    'field_val' => 0,
    'ALL_RELATED_MODULES' => 0,
    'relatedmodulename' => 0,
    'related_modules' => 0,
    'module1' => 0,
    'RELATED_MODULE_FIELDS' => 0,
    'relatedmodulefields' => 0,
    'COMPANY_STAMP_SIGNATURE' => 0,
    'COMPANYLOGO' => 0,
    'COMPANY_HEADER_SIGNATURE' => 0,
    'VATBLOCK_TABLE' => 0,
    'ROLEIDSTR' => 0,
    'ROLENAMESTR' => 0,
    'USERIDSTR' => 0,
    'USERNAMESTR' => 0,
    'GROUPIDSTR' => 0,
    'GROUPNAMESTR' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_567b7c6219af0',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_567b7c6219af0')) {function content_567b7c6219af0($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/vtigercrm/libraries/Smarty/libs/plugins/function.html_options.php';
?>


<div class='editViewContainer'>
    <form class="form-horizontal recordEditView" id="EditView" name="EditView" method="post" action="index.php" enctype="multipart/form-data">
        <input type="hidden" name="module" value="PDFMaker">
        <input type="hidden" name="parenttab" value="<?php echo $_smarty_tpl->tpl_vars['PARENTTAB']->value;?>
">
        <input type="hidden" name="templateid" id="templateid" value="<?php echo $_smarty_tpl->tpl_vars['SAVETEMPLATEID']->value;?>
">
        <input type="hidden" name="action" value="SavePDFTemplate">
        <input type="hidden" name="redirect" value="true">
        <input type="hidden" name="return_module" value="<?php echo $_REQUEST['return_module'];?>
">
        <input type="hidden" name="return_view" value="<?php echo $_REQUEST['return_view'];?>
">
        <input type="hidden" name="selectedTab" id="selectedTab" value="properties">
        <input type="hidden" name="selectedTab2" id="selectedTab2" value="body">
        <div class="contentHeader row-fluid">
            <?php if ($_smarty_tpl->tpl_vars['EMODE']->value=='edit'){?>
                <?php if ($_smarty_tpl->tpl_vars['DUPLICATE_FILENAME']->value==''){?>
                    <span class="span8 font-x-x-large textOverflowEllipsis" title="<?php echo vtranslate('LBL_EDIT','PDFMaker');?>
 &quot;<?php echo $_smarty_tpl->tpl_vars['FILENAME']->value;?>
&quot;"><?php echo vtranslate('LBL_EDIT','PDFMaker');?>
 &quot;<?php echo $_smarty_tpl->tpl_vars['FILENAME']->value;?>
&quot;</span>
                <?php }else{ ?>
                    <span class="span8 font-x-x-large textOverflowEllipsis" title="<?php echo vtranslate('LBL_DUPLICATE','PDFMaker');?>
 &quot;<?php echo $_smarty_tpl->tpl_vars['DUPLICATE_FILENAME']->value;?>
&quot;"><?php echo vtranslate('LBL_DUPLICATE','PDFMaker');?>
 &quot;<?php echo $_smarty_tpl->tpl_vars['DUPLICATE_FILENAME']->value;?>
&quot;</span>
                <?php }?>
            <?php }else{ ?>
                <span class="span8 font-x-x-large textOverflowEllipsis"><?php echo vtranslate('LBL_NEW_TEMPLATE','PDFMaker');?>
</span>
            <?php }?>
            <span class="pull-right">
                <button class="btn" type="submit" onclick="document.EditView.redirect.value = 'false';"><strong><?php echo vtranslate('LBL_APPLY','PDFMaker');?>
</strong></button>&nbsp;&nbsp;
                <button class="btn btn-success" type="submit" ><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>
                <?php if ($_REQUEST['return_view']!=''){?>
                    <a class="cancelLink" type="reset" onclick="window.location.href = 'index.php?module=<?php if ($_REQUEST['return_module']!=''){?><?php echo $_REQUEST['return_module'];?>
<?php }else{ ?>PDFMaker<?php }?>&view=<?php echo $_REQUEST['return_view'];?>
<?php if ($_REQUEST['templateid']!=''&&$_REQUEST['return_view']!="List"){?>&templateid=<?php echo $_REQUEST['templateid'];?>
<?php }?>';"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
                <?php }else{ ?>
                    <a class="cancelLink" type="reset" onclick="javascript:window.history.back();"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
                <?php }?>         			
            </span>
       </div>
       
       <div class="modal-body tabbable" style="padding:0px;">
            <ul class="nav nav-pills" style="margin-bottom:0px; padding-left:5px;">
                <li class="active" id="properties_tab" onclick="PDFMaker_EditJs.showHideTab('properties');"><a data-toggle="tab" href="javascript:void(0);"><?php echo vtranslate('LBL_PROPERTIES_TAB','PDFMaker');?>
</a></li>
                <li id="company_tab" onclick="PDFMaker_EditJs.showHideTab('company');"><a data-toggle="tab" href="javascript:void(0);"><?php echo vtranslate('LBL_OTHER_INFO','PDFMaker');?>
</a></li>
                <li id="labels_tab" onclick="PDFMaker_EditJs.showHideTab('labels');"><a data-toggle="tab" href="javascript:void(0);"><?php echo vtranslate('LBL_LABELS','PDFMaker');?>
</a></li>
                <li id="products_tab" onclick="PDFMaker_EditJs.showHideTab('products');"><a data-toggle="tab" href="javascript:void(0);"><?php echo vtranslate('LBL_ARTICLE','PDFMaker');?>
</a></li>
                <li id="headerfooter_tab" onclick="PDFMaker_EditJs.showHideTab('headerfooter');"><a data-toggle="tab" href="javascript:void(0);"><?php echo vtranslate('LBL_HEADER_TAB','PDFMaker');?>
 / <?php echo vtranslate('LBL_FOOTER_TAB','PDFMaker');?>
</a></li>
                <li id="settings_tab" onclick="PDFMaker_EditJs.showHideTab('settings');"><a data-toggle="tab" href="javascript:void(0);"><?php echo vtranslate('LBL_SETTINGS_TAB','PDFMaker');?>
</a></li>
                <li id="sharing_tab" onclick="PDFMaker_EditJs.showHideTab('sharing');"><a data-toggle="tab" href="javascript:void(0);"><?php echo vtranslate('LBL_SHARING_TAB','PDFMaker');?>
</a></li>
            </ul>
        </div>     
  
        
        <table class="table table-bordered blockContainer ">
            <tbody id="properties_div">
                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><span class="redColor">*</span><?php echo vtranslate('LBL_PDF_NAME','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3"><input name="filename" id="filename" type="text" value="<?php echo $_smarty_tpl->tpl_vars['FILENAME']->value;?>
" class="detailedViewTextBox" tabindex="1">&nbsp;
                    
                    <span class="muted">&nbsp;&nbsp;&nbsp;<?php echo vtranslate('LBL_DESCRIPTION','PDFMaker');?>
:&nbsp;</span>
                    
                        <span class="small cellText">
                            <input name="description" type="text" value="<?php echo $_smarty_tpl->tpl_vars['DESCRIPTION']->value;?>
" class="detailedViewTextBox span5" tabindex="2">
                        </span>
                    </td>
                </tr>     					
                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php if ($_smarty_tpl->tpl_vars['TEMPLATEID']->value==''){?><span class="redColor">*</span><?php }?><?php echo vtranslate('LBL_MODULENAMES','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="modulename" id="modulename" class="classname" onChange="change_modulesorce(this, 'modulefields');" >
                            <?php if ($_smarty_tpl->tpl_vars['TEMPLATEID']->value!=''||$_smarty_tpl->tpl_vars['SELECTMODULE']->value!=''){?>
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['MODULENAMES']->value,'selected'=>$_smarty_tpl->tpl_vars['SELECTMODULE']->value),$_smarty_tpl);?>

                            <?php }else{ ?>
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['MODULENAMES']->value),$_smarty_tpl);?>

                            <?php }?>
                        </select>
                        &nbsp;&nbsp;
                        <select name="modulefields" id="modulefields" class="classname">
                            <?php if ($_smarty_tpl->tpl_vars['TEMPLATEID']->value==''&&$_smarty_tpl->tpl_vars['SELECTMODULE']->value==''){?>
                                <option value=""><?php echo vtranslate('LBL_SELECT_MODULE_FIELD','PDFMaker');?>
</option>
                            <?php }else{ ?>
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SELECT_MODULE_FIELD']->value),$_smarty_tpl);?>

                            <?php }?>
                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('modulefields');" ><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                    </td>      						
                </tr>    					
                                					
                <tr id="body_variables">
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_RELATED_MODULES','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">

                        <select name="relatedmodulesorce" id="relatedmodulesorce" class="classname" onChange="PDFMaker_EditJs.change_relatedmodule(this, 'relatedmodulefields');">
                            <option value="none"><?php echo vtranslate('LBL_SELECT_MODULE','PDFMaker');?>
</option>
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['RELATED_MODULES']->value),$_smarty_tpl);?>

                        </select>
                        &nbsp;&nbsp;

                        <select name="relatedmodulefields" id="relatedmodulefields" class="classname">
                            <option><?php echo vtranslate('LBL_SELECT_MODULE_FIELD','PDFMaker');?>
</option>
                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('relatedmodulefields');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                    </td>      						
                </tr>
                
                <tr id="related_block_tpl_row">
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_RELATED_BLOCK_TPL','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="related_block" id="related_block" class="classname" >
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['RELATED_BLOCKS']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="PDFMaker_EditJs.InsertRelatedBlock();"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                        <button type="button" class="btn addButton marginLeftZero" onclick="PDFMaker_EditJs.CreateRelatedBlock();"><i class="icon-plus icon-white"></i>&nbsp;<strong><?php echo vtranslate('LBL_CREATE');?>
</strong></button>
                        <button type="button" class="btn marginLeftZero" onclick="PDFMaker_EditJs.EditRelatedBlock();"><?php echo vtranslate('LBL_EDIT');?>
</button>
                        <button class="btn btn-danger marginLeftZero" class="crmButton small delete" onclick="PDFMaker_EditJs.DeleteRelatedBlock();"><?php echo vtranslate('LBL_DELETE');?>
</button>
                    </td>
                </tr>

                <tr id="listview_block_tpl_row">
                    <td class="fieldLabel">
                        <label class="muted pull-right marginRight10px"><input type="checkbox" name="is_listview" id="isListViewTmpl" <?php echo $_smarty_tpl->tpl_vars['IS_LISTVIEW_CHECKED']->value;?>
 onclick="PDFMaker_EditJs.isLvTmplClicked();" title="<?php echo vtranslate('LBL_LISTVIEW_TEMPLATE','PDFMaker');?>
" />
                        <?php echo vtranslate('LBL_LISTVIEWBLOCK','PDFMaker');?>
:</label>
                    </td>
                    <td class="fieldValue" colspan="3">
                        <select name="listviewblocktpl" id="listviewblocktpl" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['LISTVIEW_BLOCK_TPL']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" id="listviewblocktpl_butt" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('listviewblocktpl');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                    </td>
                </tr>
            </tbody>
            
            <tbody style="display:none;" id="labels_div">
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_GLOBAL_LANG','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="global_lang" id="global_lang" class="classname" style="width:80%">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['GLOBAL_LANG_LABELS']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('global_lang');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                    </td>
                </tr>
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_MODULE_LANG','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="module_lang" id="module_lang" class="classname" style="width:80%">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['MODULE_LANG_LABELS']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('module_lang');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                    </td>
                </tr>
                <?php if ($_smarty_tpl->tpl_vars['TYPE']->value=="professional"){?>
                    <tr>
                        <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_CUSTOM_LABELS','PDFMaker');?>
:</label></td>
                        <td class="fieldValue" colspan="3">
                            <select name="custom_lang" id="custom_lang" class="classname" style="width:80%">
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['CUSTOM_LANG_LABELS']->value),$_smarty_tpl);?>

                            </select>
                            <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('custom_lang');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
            
            <tbody style="display:none;" id="company_div">
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_COMPANY_USER_INFO','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="acc_info_type" id="acc_info_type" class="classname" onChange="PDFMaker_EditJs.change_acc_info(this)">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['CUI_BLOCKS']->value),$_smarty_tpl);?>

                        </select>
                        <div id="acc_info_div" style="display:inline;">
                            <select name="acc_info" id="acc_info" class="classname">
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['ACCOUNTINFORMATIONS']->value),$_smarty_tpl);?>

                            </select>
                            <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('acc_info');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                        </div>
                        <div id="user_info_div" style="display:none;">
                            <select name="user_info" id="user_info" class="classname">
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['USERINFORMATIONS']->value),$_smarty_tpl);?>

                            </select>
                            <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('user_info');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                        </div>
                        <div id="logged_user_info_div" style="display:none;">
                            <select name="logged_user_info" id="logged_user_info" class="classname">
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['LOGGEDUSERINFORMATION']->value),$_smarty_tpl);?>

                            </select>
                            <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('logged_user_info');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                        </div>
                    </td>
                </tr>
                <?php if ($_smarty_tpl->tpl_vars['MULTICOMPANYINFORMATIONS']->value!=''){?>
                    <tr>
                        <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo $_smarty_tpl->tpl_vars['LBL_MULTICOMPANY']->value;?>
:</label></td>
                        <td class="fieldValue" colspan="3">
                            <select name="multicomapny" id="multicomapny" class="classname">
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['MULTICOMPANYINFORMATIONS']->value),$_smarty_tpl);?>

                            </select>
                            <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('multicomapny');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                        </td>
                    </tr>
                <?php }?>
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('TERMS_AND_CONDITIONS','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="invterandcon" id="invterandcon" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['INVENTORYTERMSANDCONDITIONS']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('invterandcon');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                    </td>
                </tr>
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_CURRENT_DATE','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="dateval" id="dateval" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['DATE_VARS']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('dateval');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                    </td>
                </tr>
                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_BARCODES','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="barcodeval" id="barcodeval" class="classname">
                            <optgroup label="<?php echo vtranslate('LBL_BARCODES_TYPE1','PDFMaker');?>
">
                                <option value="EAN13">EAN13</option>
                                <option value="ISBN">ISBN</option>
                                <option value="ISSN">ISSN</option>
                            </optgroup>

                            <optgroup label="<?php echo vtranslate('LBL_BARCODES_TYPE2','PDFMaker');?>
">
                                <option value="UPCA">UPCA</option>
                                <option value="UPCE">UPCE</option>
                                <option value="EAN8">EAN8</option>
                            </optgroup>

                            <optgroup label="<?php echo vtranslate('LBL_BARCODES_TYPE3','PDFMaker');?>
">
                                <option value="EAN2">EAN2</option>
                                <option value="EAN5">EAN5</option>
                                <option value="EAN13P2">EAN13P2</option>
                                <option value="ISBNP2">ISBNP2</option>
                                <option value="ISSNP2">ISSNP2</option>
                                <option value="UPCAP2">UPCAP2</option>
                                <option value="UPCEP2">UPCEP2</option>
                                <option value="EAN8P2">EAN8P2</option>
                                <option value="EAN13P5">EAN13P5</option>
                                <option value="ISBNP5">ISBNP5</option>
                                <option value="ISSNP5">ISSNP5</option>
                                <option value="UPCAP5">UPCAP5</option>
                                <option value="UPCEP5">UPCEP5</option>
                                <option value="EAN8P5">EAN8P5</option>
                            </optgroup>

                            <optgroup label="<?php echo vtranslate('LBL_BARCODES_TYPE4','PDFMaker');?>
">     
                                <option value="IMB">IMB</option>
                                <option value="RM4SCC">RM4SCC</option>
                                <option value="KIX">KIX</option>
                                <option value="POSTNET">POSTNET</option>
                                <option value="PLANET">PLANET</option>
                            </optgroup>

                            <optgroup label="<?php echo vtranslate('LBL_BARCODES_TYPE5','PDFMaker');?>
">    
                                <option value="C128A">C128A</option>
                                <option value="C128B">C128B</option>
                                <option value="C128C">C128C</option>
                                <option value="EAN128C">EAN128C</option>
                                <option value="C39">C39</option>
                                <option value="C39+">C39+</option>
                                <option value="C39E">C39E</option>
                                <option value="C39E+">C39E+</option>
                                <option value="S25">S25</option>
                                <option value="S25+">S25+</option>
                                <option value="I25">I25</option>
                                <option value="I25+">I25+</option>
                                <option value="I25B">I25B</option>
                                <option value="I25B+">I25B+</option>
                                <option value="C93">C93</option>
                                <option value="MSI">MSI</option>
                                <option value="MSI+">MSI+</option>
                                <option value="CODABAR">CODABAR</option>
                                <option value="CODE11">CODE11</option>
                            </optgroup>

                            <optgroup label="<?php echo vtranslate('LBL_QRCODE','PDFMaker');?>
">
                                <option value="QR">QR</option>
                            </optgroup>
                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('barcodeval');"><?php echo vtranslate('LBL_INSERT_BARCODE_TO_TEXT','PDFMaker');?>
</button>&nbsp;&nbsp;<a href="index.php?module=PDFMaker&view=IndexAjax&mode=showBarcodes" target="_new"><i class="icon-info-sign"></i></a>
                    </td>
                </tr>
                
                <?php if ($_smarty_tpl->tpl_vars['TYPE']->value=="professional"){?>
                    <tr>
                        <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('CUSTOM_FUNCTIONS','PDFMaker');?>
:</label></td>
                        <td class="fieldValue" colspan="3">
                            <select name="customfunction" id="customfunction" class="classname">
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['CUSTOM_FUNCTIONS']->value),$_smarty_tpl);?>

                            </select>
                            <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('customfunction');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
            
            <tbody style="display:none;" id="headerfooter_div">
            
                <tr id="header_variables">
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_HEADER_FOOTER_VARIABLES','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="header_var" id="header_var" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['HEAD_FOOT_VARS']->value,'selected'=>''),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('header_var');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                    </td>
                </tr>
                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_DISPLAY_HEADER','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <b><?php echo vtranslate('LBL_ALL_PAGES','PDFMaker');?>
</b>&nbsp;<input type="checkbox" id="dh_allid" name="dh_all" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'header');" <?php echo $_smarty_tpl->tpl_vars['DH_ALL']->value;?>
/>
                        &nbsp;&nbsp;
                        <?php echo vtranslate('LBL_FIRST_PAGE','PDFMaker');?>
&nbsp;<input type="checkbox" id="dh_firstid" name="dh_first" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'header');" <?php echo $_smarty_tpl->tpl_vars['DH_FIRST']->value;?>
/>
                        &nbsp;&nbsp;
                        <?php echo vtranslate('LBL_OTHER_PAGES','PDFMaker');?>
&nbsp;<input type="checkbox" id="dh_otherid" name="dh_other" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'header');" <?php echo $_smarty_tpl->tpl_vars['DH_OTHER']->value;?>
/>
                        &nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_DISPLAY_FOOTER','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <b><?php echo vtranslate('LBL_ALL_PAGES','PDFMaker');?>
</b>&nbsp;<input type="checkbox" id="df_allid" name="df_all" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'footer');" <?php echo $_smarty_tpl->tpl_vars['DF_ALL']->value;?>
/>
                        &nbsp;&nbsp;
                        <?php echo vtranslate('LBL_FIRST_PAGE','PDFMaker');?>
&nbsp;<input type="checkbox" id="df_firstid" name="df_first" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'footer');" <?php echo $_smarty_tpl->tpl_vars['DF_FIRST']->value;?>
/>
                        &nbsp;&nbsp;
                        <?php echo vtranslate('LBL_OTHER_PAGES','PDFMaker');?>
&nbsp;<input type="checkbox" id="df_otherid" name="df_other" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'footer');" <?php echo $_smarty_tpl->tpl_vars['DF_OTHER']->value;?>
/>
                        &nbsp;&nbsp;
                        <?php echo vtranslate('LBL_LAST_PAGE','PDFMaker');?>
&nbsp;<input type="checkbox" id="df_lastid" name="df_last" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'footer');" <?php echo $_smarty_tpl->tpl_vars['DF_LAST']->value;?>
/>
                        &nbsp;&nbsp;
                    </td>
                </tr>
            </tbody>
            
            
            <tbody style="display:none;" id="products_div">
                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_PRODUCT_BLOC_TPL','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="productbloctpl2" id="productbloctpl2" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['PRODUCT_BLOC_TPL']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('productbloctpl2');"/><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                    </td>
                </tr>
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_ARTICLE','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="articelvar" id="articelvar" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['ARTICLE_STRINGS']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('articelvar');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>
                    </td>
                </tr>
                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px">*<?php echo vtranslate('LBL_PRODUCTS_AVLBL','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="psfields" id="psfields" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SELECT_PRODUCT_FIELD']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('psfields');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>            						
                    </td>
                </tr>
                                                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px">*<?php echo vtranslate('LBL_PRODUCTS_FIELDS','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="productfields" id="productfields" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['PRODUCTS_FIELDS']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('productfields');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>            						
                    </td>
                </tr>
                                                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px">*<?php echo vtranslate('LBL_SERVICES_FIELDS','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="servicesfields" id="servicesfields" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SERVICES_FIELDS']->value),$_smarty_tpl);?>

                        </select>
                        <button type="button" class="btn btn-success marginLeftZero" onclick="InsertIntoTemplate('servicesfields');"><?php echo vtranslate('LBL_INSERT_TO_TEXT','PDFMaker');?>
</button>            						
                    </td>
                </tr>
                <tr>
                    <td class="fieldLabel" colspan="4"><label class="muted marginRight10px"><small><?php echo vtranslate('LBL_PRODUCT_FIELD_INFO','PDFMaker');?>
</small></label></td>
                </tr>
            </tbody>   
            
            
            <tbody style="display:none;" id="settings_div">
                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_FILENAME','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <input type="text" name="nameOfFile" value="<?php echo $_smarty_tpl->tpl_vars['NAME_OF_FILE']->value;?>
" id="nameOfFile" class="detailedViewTextBox" style="width:50%;"/>
                        <select name="filename_fields" id="filename_fields" class="classname small" onchange="PDFMaker_EditJs.insertFieldIntoFilename(this.value);">
                            <option value=""><?php echo vtranslate('LBL_SELECT_MODULE_FIELD','PDFMaker');?>
</option>
                            <optgroup label="<?php echo vtranslate('LBL_COMMON_FILEINFO','PDFMaker');?>
">
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['FILENAME_FIELDS']->value),$_smarty_tpl);?>

                            </optgroup>
                            <?php if ($_smarty_tpl->tpl_vars['TEMPLATEID']->value!=''||$_smarty_tpl->tpl_vars['SELECTMODULE']->value!=''){?>
                                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SELECT_MODULE_FIELD_FILENAME']->value),$_smarty_tpl);?>

                            <?php }?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td class="fieldLabel">
                        <label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_PDF_FORMAT','PDFMaker');?>
:</label>
                    </td>
                    <td class="fieldValue" colspan="3">
                        <table style="padding:0px; margin:0px;" cellpadding="0" cellspacing="0">
                            <tr>                                       
                                <td>
                                    <select name="pdf_format" id="pdf_format" class="classname" onchange="PDFMaker_EditJs.CustomFormat();">
                                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['FORMATS']->value,'selected'=>$_smarty_tpl->tpl_vars['SELECT_FORMAT']->value),$_smarty_tpl);?>

                                    </select>
                                </td>
                                <td style="padding:0">
                                    <table class="table showInlineTable" id="custom_format_table" <?php if ($_smarty_tpl->tpl_vars['SELECT_FORMAT']->value!='Custom'){?>style="display:none"<?php }?>>
                                        <tr>
                                            <td align="right" nowrap><?php echo vtranslate('LBL_WIDTH','PDFMaker');?>
</td>
                                            <td>
                                                <input type="text" name="pdf_format_width" id="pdf_format_width" class="detailedViewTextBox" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOM_FORMAT']->value['width'];?>
" style="width:50px">
                                            </td>
                                            <td align="right" nowrap><?php echo vtranslate('LBL_HEIGHT','PDFMaker');?>
</td>
                                            <td>
                                                <input type="text" name="pdf_format_height" id="pdf_format_height" class="detailedViewTextBox" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOM_FORMAT']->value['height'];?>
" style="width:50px">
                                            </td>
                                        </tr>
                                    </table>
                                </td>                                   
                            </tr>
                        </table>

                    </td>
                </tr>
                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_PDF_ORIENTATION','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="pdf_orientation" id="pdf_orientation" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['ORIENTATIONS']->value,'selected'=>$_smarty_tpl->tpl_vars['SELECT_ORIENTATION']->value),$_smarty_tpl);?>

                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td class="fieldLabel" title="<?php echo vtranslate('LBL_IGNORE_PICKLIST_VALUES_DESC','PDFMaker');?>
"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_IGNORE_PICKLIST_VALUES','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3" title="<?php echo vtranslate('LBL_IGNORE_PICKLIST_VALUES_DESC','PDFMaker');?>
"><input type="text" name="ignore_picklist_values" value="<?php echo $_smarty_tpl->tpl_vars['IGNORE_PICKLIST_VALUES']->value;?>
" class="detailedViewTextBox"/></td>
                </tr>
                
                <?php $_smarty_tpl->tpl_vars['margin_input_width'] = new Smarty_variable('50px', null, 0);?>
                <?php $_smarty_tpl->tpl_vars['margin_label_width'] = new Smarty_variable('50px', null, 0);?>
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_MARGINS','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <table>
                            <tr>
                                <td align="right" nowrap><?php echo vtranslate('LBL_TOP','PDFMaker');?>
</td>
                                <td>
                                    <input type="text" name="margin_top" id="margin_top" class="detailedViewTextBox" value="<?php echo $_smarty_tpl->tpl_vars['MARGINS']->value['top'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
" onKeyUp="PDFMaker_EditJs.ControlNumber('margin_top', false);">
                                </td>
                                <td align="right" nowrap><?php echo vtranslate('LBL_BOTTOM','PDFMaker');?>
</td>
                                <td>
                                    <input type="text" name="margin_bottom" id="margin_bottom" class="detailedViewTextBox" value="<?php echo $_smarty_tpl->tpl_vars['MARGINS']->value['bottom'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
" onKeyUp="PDFMaker_EditJs.ControlNumber('margin_bottom', false);">
                                </td>
                                <td align="right" nowrap><?php echo vtranslate('LBL_LEFT','PDFMaker');?>
</td>
                                <td>
                                    <input type="text" name="margin_left"  id="margin_left" class="detailedViewTextBox" value="<?php echo $_smarty_tpl->tpl_vars['MARGINS']->value['left'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
" onKeyUp="PDFMaker_EditJs.ControlNumber('margin_left', false);">
                                </td>
                                <td align="right" nowrap><?php echo vtranslate('LBL_RIGHT','PDFMaker');?>
</td>
                                <td>
                                    <input type="text" name="margin_right" id="margin_right" class="detailedViewTextBox" value="<?php echo $_smarty_tpl->tpl_vars['MARGINS']->value['right'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
" onKeyUp="PDFMaker_EditJs.ControlNumber('margin_right', false);">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                    					
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_DECIMALS','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <table>
                            <tr>
                                <td align="right" nowrap><?php echo vtranslate('LBL_DEC_POINT','PDFMaker');?>
</td>
                                <td><input type="text" maxlength="2" name="dec_point" class="detailedViewTextBox" value="<?php echo $_smarty_tpl->tpl_vars['DECIMALS']->value['point'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
"/></td>

                                <td align="right" nowrap><?php echo vtranslate('LBL_DEC_DECIMALS','PDFMaker');?>
</td>
                                <td><input type="text" maxlength="2" name="dec_decimals" class="detailedViewTextBox" value="<?php echo $_smarty_tpl->tpl_vars['DECIMALS']->value['decimals'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
"/></td>

                                <td align="right" nowrap><?php echo vtranslate('LBL_DEC_THOUSANDS','PDFMaker');?>
</td>
                                <td><input type="text" maxlength="2" name="dec_thousands"  class="detailedViewTextBox" value="<?php echo $_smarty_tpl->tpl_vars['DECIMALS']->value['thousands'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
"/></td>                                       
                            </tr>
                        </table>
                    </td>
                </tr>    					
                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_STATUS','PDFMaker');?>
:</label></td>    					   
                    <td class="fieldValue" colspan="3">
                        <select name="is_active" id="is_active" class="classname" onchange="PDFMaker_EditJs.templateActiveChanged(this);">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['STATUS']->value,'selected'=>$_smarty_tpl->tpl_vars['IS_ACTIVE']->value),$_smarty_tpl);?>
   
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_SETASDEFAULT','PDFMaker');?>
:</label></td>    					   
                    <td class="fieldValue" colspan="3">
                        <?php echo vtranslate('LBL_FOR_DV','PDFMaker');?>
&nbsp;&nbsp;<input type="checkbox" id="is_default_dv" name="is_default_dv" <?php echo $_smarty_tpl->tpl_vars['IS_DEFAULT_DV_CHECKED']->value;?>
/>
                        &nbsp;&nbsp;
                        <?php echo vtranslate('LBL_FOR_LV','PDFMaker');?>
&nbsp;&nbsp;<input type="checkbox" id="is_default_lv" name="is_default_lv" <?php echo $_smarty_tpl->tpl_vars['IS_DEFAULT_LV_CHECKED']->value;?>
/>
                        
                        <input type="hidden" name="tmpl_order" value="<?php echo $_smarty_tpl->tpl_vars['ORDER']->value;?>
" />
                    </td>
                </tr>
                    					
                <tr id="is_portal_row" <?php if ($_smarty_tpl->tpl_vars['SELECTMODULE']->value!="Invoice"&&$_smarty_tpl->tpl_vars['SELECTMODULE']->value!="Quotes"){?>style="display: none;"<?php }?>>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_SETFORPORTAL','PDFMaker');?>
:</label></td>    					   
                    <td class="fieldValue" colspan="3">
                        <input type="checkbox" id="is_portal" name="is_portal" <?php echo $_smarty_tpl->tpl_vars['IS_PORTAL_CHECKED']->value;?>
 onclick="return PDFMaker_EditJs.ConfirmIsPortal(this);"/>
                    </td>
                </tr>    					
            </tbody>
            
            <tbody style="display:none;" id="sharing_div">
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_TEMPLATE_OWNER','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="template_owner" id="template_owner" class="classname">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['TEMPLATE_OWNERS']->value,'selected'=>$_smarty_tpl->tpl_vars['TEMPLATE_OWNER']->value),$_smarty_tpl);?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="fieldLabel"><label class="muted pull-right marginRight10px"><?php echo vtranslate('LBL_SHARING_TAB','PDFMaker');?>
:</label></td>
                    <td class="fieldValue" colspan="3">
                        <select name="sharing" id="sharing" class="classname" onchange="PDFMaker_EditJs.sharing_changed();">
                            <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SHARINGTYPES']->value,'selected'=>$_smarty_tpl->tpl_vars['SHARINGTYPE']->value),$_smarty_tpl);?>

                        </select>

                        <div id="sharing_share_div" style="display:none; border-top:2px dotted #DADADA; margin-top:10px; width:100%;">
                            <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td width="40%" valign=top class="cellBottomDotLinePlain small"><strong><?php echo vtranslate('LBL_MEMBER_AVLBL','PDFMaker');?>
</strong></td>
                                    <td width="10%">&nbsp;</td>
                                    <td width="40%" class="cellBottomDotLinePlain small"><strong><?php echo vtranslate('LBL_MEMBER_SELECTED','PDFMaker');?>
</strong></td>
                                </tr>
                                <tr>
                                    <td valign=top class="small">
                                        <?php echo vtranslate('LBL_ENTITY','PDFMaker');?>
:&nbsp;
                                        <select id="sharingMemberType" name="sharingMemberType" class="small" onchange="PDFMaker_EditJs.showSharingMemberTypes()">
                                            <option value="groups" selected><?php echo $_smarty_tpl->tpl_vars['APP']->value['LBL_GROUPS'];?>
</option>
                                            <option value="roles"><?php echo vtranslate('LBL_ROLES','PDFMaker');?>
</option>
                                            <option value="rs"><?php echo vtranslate('LBL_ROLES_SUBORDINATES','PDFMaker');?>
</option>
                                            <option value="users"><?php echo $_smarty_tpl->tpl_vars['APP']->value['LBL_USERS'];?>
</option>
                                        </select>
                                        <input type="hidden" name="sharingFindStr" id="sharingFindStr">&nbsp;
                                    </td>
                                    <td width="50">&nbsp;</td>
                                    <td class="small">&nbsp;</td>
                                </tr>
                                <tr class="small">
                                    <td valign=top><?php echo vtranslate('LBL_MEMBER_OF','PDFMaker');?>
 <?php echo vtranslate('LBL_ENTITY','PDFMaker');?>
<br>
                                        <select id="sharingAvailList" name="sharingAvailList" multiple size="10" class="small crmFormList"></select>
                                    </td>
                                    <td width="50">
                                        <div align="center">
                                            <input type="button" name="sharingAddButt" value="&nbsp;&rsaquo;&rsaquo;&nbsp;" onClick="PDFMaker_EditJs.sharingAddColumn()" class="crmButton small"/><br /><br />
                                            <input type="button" name="sharingDelButt" value="&nbsp;&lsaquo;&lsaquo;&nbsp;" onClick="PDFMaker_EditJs.sharingDelColumn()" class="crmButton small"/>
                                        </div>
                                    </td>
                                    <td class="small" style="background-color:#ddFFdd" valign=top><?php echo vtranslate('LBL_MEMBER_OF','PDFMaker');?>
 &quot;<?php echo $_smarty_tpl->tpl_vars['GROUPNAME']->value;?>
&quot; <br>
                                        <select id="sharingSelectedColumns" name="sharingSelectedColumns" multiple size="10" class="small crmFormList">
                                            <?php  $_smarty_tpl->tpl_vars['element'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['element']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['MEMBER']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['element']->key => $_smarty_tpl->tpl_vars['element']->value){
$_smarty_tpl->tpl_vars['element']->_loop = true;
?>
                                                <option value="<?php echo $_smarty_tpl->tpl_vars['element']->value[0];?>
"><?php echo $_smarty_tpl->tpl_vars['element']->value[1];?>
</option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="sharingSelectedColumnsString" id="sharingSelectedColumnsString" value="" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

                                
                                        

        <div class="modal-body tabbable" style="padding:0px;">
            <ul class="nav nav-pills" style="margin-bottom:0px; padding-left:5px;">
                <li class="active" id="body_tab2" onclick="PDFMaker_EditJs.showHideTab3('body');"><a data-toggle="tab1" href="javascript:void(0);"><?php echo vtranslate('LBL_BODY','PDFMaker');?>
</a></li>
                <li id="header_tab2" onclick="PDFMaker_EditJs.showHideTab3('header');"><a data-toggle="tab1" href="javascript:void(0);"><?php echo vtranslate('LBL_HEADER_TAB','PDFMaker');?>
</a></li>
                <li id="footer_tab2" onclick="PDFMaker_EditJs.showHideTab3('footer');"><a data-toggle="tab1" href="javascript:void(0);"><?php echo vtranslate('LBL_FOOTER_TAB','PDFMaker');?>
</a></li>
            </ul>
        </div>
         

        
        <div style="display:block;" id="body_div2">
            <textarea name="body" id="body" style="width:90%;height:700px" class=small tabindex="5"><?php echo $_smarty_tpl->tpl_vars['BODY']->value;?>
</textarea>
        </div>

        <script type="text/javascript">
             jQuery(document).ready(function(){ CKEDITOR.replace('body', {height: '1000'}); })                        
        </script>

        
        <div style="display:none;" id="header_div2">
            <textarea name="header_body" id="header_body" style="width:90%;height:200px" class="small"><?php echo $_smarty_tpl->tpl_vars['HEADER']->value;?>
</textarea>
        </div>

        <script type="text/javascript">
             jQuery(document).ready(function(){ CKEDITOR.replace('header_body', {height: '1000'}); }) 
        </script>

        
        <div style="display:none;" id="footer_div2">
            <textarea name="footer_body" id="footer_body" style="width:90%;height:200px" class="small"><?php echo $_smarty_tpl->tpl_vars['FOOTER']->value;?>
</textarea>
        </div>

        <script type="text/javascript">
             jQuery(document).ready(function(){ CKEDITOR.replace('footer_body', {height: '1000'}); }) 
        </script>

        

        <div class="contentHeader row-fluid">
            <span class="pull-right">
                <button class="btn" type="submit" onclick="document.EditView.redirect.value = 'false';" ><strong><?php echo vtranslate('LBL_APPLY','PDFMaker');?>
</strong></button>&nbsp;&nbsp;
                <button class="btn btn-success" type="submit" ><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>
                <?php if ($_REQUEST['return_view']!=''){?>
                    <a class="cancelLink" type="reset" onclick="window.location.href = 'index.php?module=<?php if ($_REQUEST['return_module']!=''){?><?php echo $_REQUEST['return_module'];?>
<?php }else{ ?>PDFMaker<?php }?>&view=<?php echo $_REQUEST['return_view'];?>
<?php if ($_REQUEST['templateid']!=''&&$_REQUEST['return_view']!="List"){?>&templateid=<?php echo $_REQUEST['templateid'];?>
<?php }?>';"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
                <?php }else{ ?>
                    <a class="cancelLink" type="reset" onclick="javascript:window.history.back();"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
                <?php }?>            			
            </span>
        </div>

    <div align="center" class="small" style="color: rgb(153, 153, 153);"><?php echo vtranslate('PDF_MAKER','PDFMaker');?>
 <?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
 <?php echo vtranslate('COPYRIGHT','PDFMaker');?>
</div>

    </form>
</div>
<script type="text/javascript">

    var selectedTab = 'properties';
    var selectedTab2 = 'body';
    var module_blocks = new Array();
    <?php  $_smarty_tpl->tpl_vars['moduleblocks'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['moduleblocks']->_loop = false;
 $_smarty_tpl->tpl_vars['blockname'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MODULE_BLOCKS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['moduleblocks']->key => $_smarty_tpl->tpl_vars['moduleblocks']->value){
$_smarty_tpl->tpl_vars['moduleblocks']->_loop = true;
 $_smarty_tpl->tpl_vars['blockname']->value = $_smarty_tpl->tpl_vars['moduleblocks']->key;
?>
        module_blocks["<?php echo $_smarty_tpl->tpl_vars['blockname']->value;?>
"] = new Array(<?php echo $_smarty_tpl->tpl_vars['moduleblocks']->value;?>
);
    <?php } ?>

    var module_fields = new Array();
    <?php  $_smarty_tpl->tpl_vars['modulefields'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['modulefields']->_loop = false;
 $_smarty_tpl->tpl_vars['modulename'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MODULE_FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['modulefields']->key => $_smarty_tpl->tpl_vars['modulefields']->value){
$_smarty_tpl->tpl_vars['modulefields']->_loop = true;
 $_smarty_tpl->tpl_vars['modulename']->value = $_smarty_tpl->tpl_vars['modulefields']->key;
?>
        module_fields["<?php echo $_smarty_tpl->tpl_vars['modulename']->value;?>
"] = new Array(<?php echo $_smarty_tpl->tpl_vars['modulefields']->value;?>
);
    <?php } ?>

    var selected_module = '<?php echo $_smarty_tpl->tpl_vars['SELECTMODULE']->value;?>
';
    function change_modulesorce(first, second_name) {
        if (selected_module != '') {
            question = confirm(app.vtranslate("LBL_CHANGE_MODULE_QUESTION"));
            if (question) {
                var oEditor = CKEDITOR.instances.body;
                oEditor.setData("");
                oEditor = CKEDITOR.instances.header_body;
                oEditor.setData("");
                oEditor = CKEDITOR.instances.footer_body;
                oEditor.setData("");
                document.getElementById('nameOfFile').value = '';
            } else {
                first.value = selected_module;
                return;
            }
        }
        selected_module = first.value;
        if (selected_module != "")
            document.getElementById('related_block_tpl_row').style.display = 'table-row';
        else
            document.getElementById('related_block_tpl_row').style.display = 'none';
        if (selected_module != "Invoice" && selected_module != "Quotes")
            document.getElementById('is_portal_row').style.display = 'none';
        else
            document.getElementById('is_portal_row').style.display = 'table-row';
        var module = fillModuleFields(first, second_name);
        fillModuleFields(first, 'filename_fields');
        PDFMaker_EditJs.change_relatedmodulesorce(first, 'relatedmodulesorce');
        PDFMaker_EditJs.fill_module_lang_array(first.value);
        PDFMaker_EditJs.fill_related_blocks_array(first.value);
        PDFMaker_EditJs.fill_module_product_fields_array(first.value);
    }
    
    function fillModuleFields(first, second_name) {
        second = document.getElementById(second_name);
        optionTest = true;
        lgth = second.options.length - 1;
        second.options[lgth] = null;
        if (second.options[lgth])
            optionTest = false;
        if (!optionTest)
            return;
        var box = first;
        var module = box.options[box.selectedIndex].value;
        if (!module)
            return;
        var box2 = second;
        var optgroups = box2.childNodes;
        for (i = optgroups.length - 1; i >= 0; i--) {
            box2.removeChild(optgroups[i]);
        }

        var blocks = module_blocks[module];
        var blocks_length = blocks.length;
        if (second_name == 'filename_fields') {
            objOption = document.createElement("option");
            objOption.innerHTML = app.vtranslate('LBL_SELECT_MODULE_FIELD');
            objOption.value = '';
            box2.appendChild(objOption);
            optGroup = document.createElement('optgroup');
            optGroup.label = app.vtranslate('LBL_COMMON_FILEINFO');
            box2.appendChild(optGroup);
            <?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_smarty_tpl->tpl_vars['field_val'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['FILENAME_FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
 $_smarty_tpl->tpl_vars['field_val']->value = $_smarty_tpl->tpl_vars['field']->key;
?>
                objOption = document.createElement("option");
                objOption.innerHTML = '<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
';
                objOption.value = '<?php echo $_smarty_tpl->tpl_vars['field_val']->value;?>
';
                optGroup.appendChild(objOption);
            <?php } ?>
            if (module == 'Invoice' || module == 'Quotes' || module == 'SalesOrder' || module == 'PurchaseOrder' || module == 'Issuecards' || module == 'Receiptcards' || module == "Creditnote" || module == "StornoInvoice")
                blocks_length -= 2;
        }

        for (b = 0; b < blocks_length; b += 2) {
            optGroup = document.createElement('optgroup');
            optGroup.label = blocks[b];
            box2.appendChild(optGroup);
            var list = module_fields[module + '|' + blocks[b + 1]];
            for (i = 0; i < list.length; i += 2) {
                objOption = document.createElement("option");
                objOption.innerHTML = list[i];
                objOption.value = list[i + 1];
                optGroup.appendChild(objOption);
            }
        }

        return module;
    }

    var all_related_modules = new Array();
    <?php  $_smarty_tpl->tpl_vars['related_modules'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['related_modules']->_loop = false;
 $_smarty_tpl->tpl_vars['relatedmodulename'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['ALL_RELATED_MODULES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['related_modules']->key => $_smarty_tpl->tpl_vars['related_modules']->value){
$_smarty_tpl->tpl_vars['related_modules']->_loop = true;
 $_smarty_tpl->tpl_vars['relatedmodulename']->value = $_smarty_tpl->tpl_vars['related_modules']->key;
?>
    all_related_modules["<?php echo $_smarty_tpl->tpl_vars['relatedmodulename']->value;?>
"] = new Array('<?php echo vtranslate("LBL_SELECT_MODULE");?>
','none'<?php  $_smarty_tpl->tpl_vars['module1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['module1']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['related_modules']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['module1']->key => $_smarty_tpl->tpl_vars['module1']->value){
$_smarty_tpl->tpl_vars['module1']->_loop = true;
?>,'<?php echo htmlspecialchars(vtranslate($_smarty_tpl->tpl_vars['module1']->value[2]), ENT_QUOTES, 'UTF-8', true);?>
 (<?php echo $_smarty_tpl->tpl_vars['module1']->value[1];?>
)','<?php echo $_smarty_tpl->tpl_vars['module1']->value[2];?>
|<?php echo $_smarty_tpl->tpl_vars['module1']->value[0];?>
'<?php } ?>);
    <?php } ?>

    var related_module_fields = new Array();
    <?php  $_smarty_tpl->tpl_vars['relatedmodulefields'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['relatedmodulefields']->_loop = false;
 $_smarty_tpl->tpl_vars['relatedmodulename'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['RELATED_MODULE_FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['relatedmodulefields']->key => $_smarty_tpl->tpl_vars['relatedmodulefields']->value){
$_smarty_tpl->tpl_vars['relatedmodulefields']->_loop = true;
 $_smarty_tpl->tpl_vars['relatedmodulename']->value = $_smarty_tpl->tpl_vars['relatedmodulefields']->key;
?>
    related_module_fields["<?php echo $_smarty_tpl->tpl_vars['relatedmodulename']->value;?>
"] = new Array(<?php echo $_smarty_tpl->tpl_vars['relatedmodulefields']->value;?>
);
    <?php } ?>

    function InsertIntoTemplate(element){

        var selectedTab2 = document.getElementById("selectedTab2").value;

        selectField = document.getElementById(element).value;
        if (selectedTab2 == "body")
            var oEditor = CKEDITOR.instances.body;
        else if (selectedTab2 == "header")
            var oEditor = CKEDITOR.instances.header_body;
        else if (selectedTab2 == "footer")
            var oEditor = CKEDITOR.instances.footer_body;
        if (element != 'header_var' && element != 'footer_var' && element != 'hmodulefields' && element != 'fmodulefields' && element != 'dateval'){
        if (selectField != ''){
            if (selectField == 'ORGANIZATION_STAMP_SIGNATURE')
                insert_value = '<?php echo $_smarty_tpl->tpl_vars['COMPANY_STAMP_SIGNATURE']->value;?>
';
            else if (selectField == 'COMPANY_LOGO')
                insert_value = '<?php echo $_smarty_tpl->tpl_vars['COMPANYLOGO']->value;?>
';
            else if (selectField == 'ORGANIZATION_HEADER_SIGNATURE')
                insert_value = '<?php echo $_smarty_tpl->tpl_vars['COMPANY_HEADER_SIGNATURE']->value;?>
';
            else if (selectField == 'VATBLOCK')
                insert_value = '<?php echo $_smarty_tpl->tpl_vars['VATBLOCK_TABLE']->value;?>
';
            else {
                if (element == "articelvar" || selectField == "LISTVIEWBLOCK_START" || selectField == "LISTVIEWBLOCK_END")
                    insert_value = '#' + selectField + '#';
                else if (element == "relatedmodulefields")
                    insert_value = '$R_' + selectField + '$';
                else if (element == "productbloctpl" || element == "productbloctpl2")
                    insert_value = selectField;
                else if (element == "global_lang")
                    insert_value = '%G_' + selectField + '%';
                else if (element == "module_lang")
                    insert_value = '%M_' + selectField + '%';
                else if (element == "custom_lang")
                    insert_value = '%' + selectField + '%';
                else if (element == "barcodeval")
                    insert_value = '[BARCODE|' + selectField + '=YOURCODE|BARCODE]';
                else if (element == "customfunction")
                    insert_value = '[CUSTOMFUNCTION|' + selectField + '|CUSTOMFUNCTION]';
                else
                    insert_value = '$' + selectField + '$';
            }
            oEditor.insertHtml(insert_value);
        }

        } else {

            if (selectField != ''){
                if (element == 'hmodulefields' || element == 'fmodulefields')
                    oEditor.insertHtml('$' + selectField + '$');
                else
                    oEditor.insertHtml(selectField);
            }
        }
    }

    var constructedOptionValue;
    var constructedOptionName;
    var roleIdArr = new Array(<?php echo $_smarty_tpl->tpl_vars['ROLEIDSTR']->value;?>
);
    var roleNameArr = new Array(<?php echo $_smarty_tpl->tpl_vars['ROLENAMESTR']->value;?>
);
    var userIdArr = new Array(<?php echo $_smarty_tpl->tpl_vars['USERIDSTR']->value;?>
);
    var userNameArr = new Array(<?php echo $_smarty_tpl->tpl_vars['USERNAMESTR']->value;?>
);
    var grpIdArr = new Array(<?php echo $_smarty_tpl->tpl_vars['GROUPIDSTR']->value;?>
);
    var grpNameArr = new Array(<?php echo $_smarty_tpl->tpl_vars['GROUPNAMESTR']->value;?>
);

    jQuery(document).ready(function(){
        PDFMaker_EditJs.isLvTmplClicked('init');
        PDFMaker_EditJs.sharing_changed();
    });         

</script>
<?php }} ?>