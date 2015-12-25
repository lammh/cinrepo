<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 11:25:31
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ListViewContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12111357565679332bd32c20-64387389%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a6c790c32d9aeaf1bd695f4049dbbc9d3ab69347' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ListViewContents.tpl',
      1 => 1450267294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12111357565679332bd32c20-64387389',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'PAGING_MODEL' => 0,
    'LISTVIEW_ENTRIES_COUNT' => 0,
    'LISTVIEW_COUNT' => 0,
    'PAGE_NUMBER' => 0,
    'MODULE' => 0,
    'ORDER_BY' => 0,
    'SORT_ORDER' => 0,
    'CURRENT_USER_MODEL' => 0,
    'WIDTHTYPE' => 0,
    'LISTVIEW_HEADERS' => 0,
    'COLUMN_NAME' => 0,
    'LISTVIEW_HEADER_KEY' => 0,
    'NEXT_SORT_ORDER' => 0,
    'LISTVIEW_HEADER' => 0,
    'SORT_IMAGE' => 0,
    'LISTVIEW_NAME' => 0,
    'PICKLIST_ARR' => 0,
    'LISTVIEW_ENTRIES' => 0,
    'LISTVIEW_ENTRY' => 0,
    'LINK' => 0,
    'QUALIFIED_MODULE' => 0,
    'MODULE_MODEL' => 0,
    'VIEWNAME' => 0,
    'SINGLE_MODULE' => 0,
    'VERSION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5679332c07939',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5679332c07939')) {function content_5679332c07939($_smarty_tpl) {?>

<input type="hidden" id="pageStartRange" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->getRecordStartRange();?>
" /><input type="hidden" id="pageEndRange" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->getRecordEndRange();?>
" /><input type="hidden" id="previousPageExist" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->isPrevPageExists();?>
" /><input type="hidden" id="nextPageExist" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->isNextPageExists();?>
" /><input type="hidden" id="numberOfEntries" value= "<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES_COUNT']->value;?>
" /><input type="hidden" id="totalCount" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_COUNT']->value;?>
" /><input type='hidden' id='pageNumber' value="<?php echo $_smarty_tpl->tpl_vars['PAGE_NUMBER']->value;?>
" ><input type='hidden' id='pageLimit' value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->getPageLimit();?>
"><input type="hidden" id="noOfEntries" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES_COUNT']->value;?>
"><div id="selectAllMsgDiv" class="alert-block msgDiv"><strong><a id="selectAllMsg"><?php echo vtranslate('LBL_SELECT_ALL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;(<span id="totalRecordsCount"></span>)</a></strong></div><div id="deSelectAllMsgDiv" class="alert-block msgDiv"><strong><a id="deSelectAllMsg"><?php echo vtranslate('LBL_DESELECT_ALL_RECORDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></strong></div><div class="listViewEntriesDiv contents-bottomscroll"><div class="bottomscroll-div"><input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['ORDER_BY']->value;?>
" id="orderBy"><input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['SORT_ORDER']->value;?>
" id="sortOrder"><p class="listViewLoadingMsg hide"><?php echo vtranslate('LBL_LOADING_LISTVIEW_CONTENTS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
........</p><?php $_smarty_tpl->tpl_vars['WIDTHTYPE'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT_USER_MODEL']->value->get('rowheight'), null, 0);?><table class="table table-bordered listViewEntriesTable"><thead><tr class="listViewHeaders"><th class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><input type="checkbox" id="listViewEntriesMainCheckBox"></th><?php  $_smarty_tpl->tpl_vars['LISTVIEW_HEADER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = false;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key => $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = true;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration++;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->last = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration === $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->total;
?><th nowrap <?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->last){?> colspan="2" <?php }?> class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><a href="javascript:void(0);" class="listViewHeaderValues" data-nextsortorderval="<?php if ($_smarty_tpl->tpl_vars['COLUMN_NAME']->value==$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value){?><?php echo $_smarty_tpl->tpl_vars['NEXT_SORT_ORDER']->value;?>
<?php }else{ ?>ASC<?php }?>" data-columnname="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value['name'];?>
&nbsp;&nbsp;<?php if ($_smarty_tpl->tpl_vars['COLUMN_NAME']->value==$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value){?><img class="<?php echo $_smarty_tpl->tpl_vars['SORT_IMAGE']->value;?>
 icon-white"><?php }?></a></th><?php } ?></tr></thead><tr><td></td><?php  $_smarty_tpl->tpl_vars['LISTVIEW_HEADER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = false;
 $_smarty_tpl->tpl_vars['LISTVIEW_NAME'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key => $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = true;
 $_smarty_tpl->tpl_vars['LISTVIEW_NAME']->value = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration++;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->last = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration === $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->total;
?><td><div class="row-fluid"><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value['type']=="user"){?><select class="select2 listSearchContributor span9" name="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_NAME']->value;?>
" multiple style="width:150px;" data-fieldinfo=''><?php  $_smarty_tpl->tpl_vars['PICKLIST_ARR'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['PICKLIST_ARR']->_loop = false;
 $_smarty_tpl->tpl_vars['PICKLIST_KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value['picklistValues']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['PICKLIST_ARR']->key => $_smarty_tpl->tpl_vars['PICKLIST_ARR']->value){
$_smarty_tpl->tpl_vars['PICKLIST_ARR']->_loop = true;
 $_smarty_tpl->tpl_vars['PICKLIST_KEY']->value = $_smarty_tpl->tpl_vars['PICKLIST_ARR']->key;
?><option value="<?php echo $_smarty_tpl->tpl_vars['PICKLIST_ARR']->value[0];?>
" <?php echo $_smarty_tpl->tpl_vars['PICKLIST_ARR']->value[2];?>
 ><?php echo $_smarty_tpl->tpl_vars['PICKLIST_ARR']->value[1];?>
</option><?php } ?></select><?php }elseif($_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value['type']=="picklist"){?><select class="select2 listSearchContributor span9" name="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_NAME']->value;?>
" multiple style="width:150px;" data-fieldinfo=''><?php  $_smarty_tpl->tpl_vars['PICKLIST_ARR'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['PICKLIST_ARR']->_loop = false;
 $_smarty_tpl->tpl_vars['PICKLIST_KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value['picklistValues']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['PICKLIST_ARR']->key => $_smarty_tpl->tpl_vars['PICKLIST_ARR']->value){
$_smarty_tpl->tpl_vars['PICKLIST_ARR']->_loop = true;
 $_smarty_tpl->tpl_vars['PICKLIST_KEY']->value = $_smarty_tpl->tpl_vars['PICKLIST_ARR']->key;
?><option value="<?php echo $_smarty_tpl->tpl_vars['PICKLIST_ARR']->value[0];?>
" <?php echo $_smarty_tpl->tpl_vars['PICKLIST_ARR']->value[2];?>
 ><?php echo vtranslate($_smarty_tpl->tpl_vars['PICKLIST_ARR']->value[1],$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><?php } ?></select><?php }else{ ?><input type="text" name="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_NAME']->value;?>
" class="span9 listSearchContributor" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value['searchValue'];?>
" data-fieldinfo=''/><?php }?></div></td><?php } ?><td><button class="btn" data-trigger="listSearch"><?php echo vtranslate('LBL_SEARCH',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button></td></tr><?php  $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['listview']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->key => $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['listview']['index']++;
?><tr class="listViewEntries" data-id=<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getId();?>
 data-recordUrl='<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getDetailViewUrl();?>
' id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_listView_row_<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['listview']['index']+1;?>
"><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getId();?>
" class="listViewEntriesCheckBox"></td><?php  $_smarty_tpl->tpl_vars['LISTVIEW_HEADER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = false;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key => $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = true;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration++;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->last = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration === $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->total;
?><td nowrap class="listViewEntryValue"  width="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getDetailViewUrl();?>
"><?php echo vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get($_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value),$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->last){?></td><td nowrap width="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><div class="pull-right actions"><span class="actionImages"><?php  $_smarty_tpl->tpl_vars['LINK'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LINK']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getRecordLinks(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['LINK']->key => $_smarty_tpl->tpl_vars['LINK']->value){
$_smarty_tpl->tpl_vars['LINK']->_loop = true;
?><a style="text-shadow: none" <?php if ($_smarty_tpl->tpl_vars['LINK']->value->get('class')!=''){?>class="<?php echo $_smarty_tpl->tpl_vars['LINK']->value->get('class');?>
"<?php }?> <?php if (strpos($_smarty_tpl->tpl_vars['LINK']->value->getUrl(),'javascript:')===0){?> href='javascript:void(0);' onclick='<?php echo substr($_smarty_tpl->tpl_vars['LINK']->value->getUrl(),strlen("javascript:"));?>
;'<?php }else{ ?> href=<?php echo $_smarty_tpl->tpl_vars['LINK']->value->getUrl();?>
 <?php }?>><?php echo vtranslate($_smarty_tpl->tpl_vars['LINK']->value->getLabel(),$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</a>&nbsp;<?php } ?></span></div></td><?php }?></td><?php } ?></tr><?php } ?></table><!--added this div for Temporarily --><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES_COUNT']->value=='0'){?><table class="emptyRecordsDiv"><tbody><tr><td><?php $_smarty_tpl->tpl_vars['SINGLE_MODULE'] = new Smarty_variable("SINGLE_".($_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?><?php echo vtranslate('LBL_NO');?>
 <?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo vtranslate('LBL_FOUND');?>
. <?php echo vtranslate('LBL_CREATE');?>
 <a href="<?php echo $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getCreateRecordUrl();?>
&folderid=<?php echo $_smarty_tpl->tpl_vars['VIEWNAME']->value;?>
"><?php echo vtranslate($_smarty_tpl->tpl_vars['SINGLE_MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></td></tr></tbody></table><?php }?></div></div>
<div align="center" class="small" style="color: rgb(153, 153, 153);"><?php echo vtranslate("ITS4YouReports","ITS4YouReports");?>
 <?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
 <?php echo vtranslate("COPYRIGHT","ITS4YouReports");?>
</div><?php }} ?>