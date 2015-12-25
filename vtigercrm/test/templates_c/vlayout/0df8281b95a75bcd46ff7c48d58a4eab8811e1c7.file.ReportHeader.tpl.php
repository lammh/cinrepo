<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 11:29:06
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ReportHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:69553651556793402ee8b99-69534944%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0df8281b95a75bcd46ff7c48d58a4eab8811e1c7' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ReportHeader.tpl',
      1 => 1450267295,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '69553651556793402ee8b99-69534944',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'LEFTPANELHIDE' => 0,
    'DATE_FILTERS' => 0,
    'REPORTTYPE' => 0,
    'REPORT_MODEL' => 0,
    'MODULE' => 0,
    'COUNT' => 0,
    'PDFMakerActive' => 0,
    'IS_TEST_WRITE_ABLE' => 0,
    'DETAILVIEW_LINKS' => 0,
    'DETAILVIEW_LINK' => 0,
    'LINKNAME' => 0,
    'RECORD_ID' => 0,
    'PRIMARY_MODULE' => 0,
    'PRIMARY_MODULE_RECORD_STRUCTURE' => 0,
    'BLOCK_LABEL' => 0,
    'LINEITEM_FIELD_IN_CALCULATION' => 0,
    'key' => 0,
    'BLOCK_FIELDS' => 0,
    'SECONDARY_MODULE_RECORD_STRUCTURES' => 0,
    'MODULE_LABEL' => 0,
    'SECONDARY_MODULE_RECORD_STRUCTURE' => 0,
    'checkDashboardWidget' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5679340322369',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5679340322369')) {function content_5679340322369($_smarty_tpl) {?>

<link rel="stylesheet" type="text/css" media="all" href="modules/ITS4YouReports/classes/Reports4YouDefault.css">

<script type="text/javascript" 
  src="modules/ITS4YouReports/highcharts/js/angular.js">
</script>

<script type="text/javascript" 
  src="modules/ITS4YouReports/highcharts/js/highcharts-ng.js">
</script> 

<script type="text/javascript" 
  src="modules/ITS4YouReports/highcharts/js/rgbcolor.js">
</script> 

<script type="text/javascript" 
  src="modules/ITS4YouReports/highcharts/js/StackBlur.js">
</script>

<script type="text/javascript" 
  src="modules/ITS4YouReports/highcharts/js/canvg.js">
</script> 

<div id="toggleButton" class="toggleButton" title="<?php echo vtranslate('LBL_LEFT_PANEL_SHOW_HIDE','Vtiger');?>
"><i id="tButtonImage" class="<?php if ($_smarty_tpl->tpl_vars['LEFTPANELHIDE']->value!='1'){?>icon-chevron-left<?php }else{ ?>icon-chevron-right<?php }?>"></i></div><div class="container-fluid no-print"><div class="row-fluid reportsDetailHeader"><input type="hidden" name="date_filters" data-value='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['DATE_FILTERS']->value);?>
' /><input type="hidden" name="report_filename" id="report_filename" value="" /><input type="hidden" name="export_pdf_format" id="export_pdf_format" value="" /><input type="hidden" name="pdf_file_name" id="pdf_file_name" value="" /><input type="hidden" name="ch_image_name" id="ch_image_name" value="" /><form id="detailView" onSubmit="return false;"  method="POST"><input type="hidden" name="date_filters" data-value='<?php echo Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($_smarty_tpl->tpl_vars['DATE_FILTERS']->value));?>
' /><input type="hidden" name="advft_criteria" id="advft_criteria" value='' /><input type="hidden" name="advft_criteria_groups" id="advft_criteria_groups" value='' /><input type="hidden" name="groupft_criteria" id="groupft_criteria" value='' /><input type="hidden" name="reload" id="reload" value='' /><input type="hidden" name="currentMode" id="currentMode" value='generate' /><input type="hidden" name="reporttype" id="reporttype" value='<?php echo $_smarty_tpl->tpl_vars['REPORTTYPE']->value;?>
' /><br><div class="reportHeader row-fluid"><div class="span3"><div class="btn-toolbar"><?php if ($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->isEditable()==true){?><div class="btn-group"><button onclick='window.location.href="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->getEditViewUrl();?>
"' type="button" class="cursorPointer btn"><strong><?php echo vtranslate('LBL_CUSTOMIZE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong>&nbsp;<i class="icon-pencil"></i></button></div><div class="btn-group"><button onclick='window.location.href="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->getDuplicateRecordUrl();?>
"' type="button" class="cursorPointer btn"><strong><?php echo vtranslate('LBL_DUPLICATE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button></div><?php }?></div></div><div class='span5 textAlignCenter'><h3><?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->getName();?>
</h3><div id="noOfRecords"><?php echo vtranslate('LBL_NO_OF_RECORDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <span id="countValue"><?php echo $_smarty_tpl->tpl_vars['COUNT']->value;?>
</span><?php if ($_smarty_tpl->tpl_vars['COUNT']->value>1000){?><span class="redColor" id="moreRecordsText"> (<?php echo vtranslate('LBL_MORE_RECORDS_TXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
)</span><?php }else{ ?><span class="redColor hide" id="moreRecordsText"> (<?php echo vtranslate('LBL_MORE_RECORDS_TXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
)</span><?php }?></div><div id='activate_pdfmaker' style="display:block;"><?php if ($_smarty_tpl->tpl_vars['PDFMakerActive']->value!==true){?><?php echo vtranslate('Please_Install_PDFMaker',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['IS_TEST_WRITE_ABLE']->value!==true){?><?php echo vtranslate('Test_Not_WriteAble',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }?></div></div><div class='span4'><span class="pull-right"><div class="btn-toolbar"><?php  $_smarty_tpl->tpl_vars['DETAILVIEW_LINK'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['DETAILVIEW_LINKS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->key => $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value){
$_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['LINKNAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->getLabel(), null, 0);?><div class="btn-group"><button class="btn reportActions" name="<?php echo $_smarty_tpl->tpl_vars['LINKNAME']->value;?>
" data-href="<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->getUrl();?>
" <?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('id')!=''){?>id="<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('id');?>
"<?php }?> <?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('style')!=''){?>style="<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('style');?>
"<?php }?> <?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('onClick')!=''){?>onClick="<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('onClick');?>
"<?php }?> ><strong><?php echo $_smarty_tpl->tpl_vars['LINKNAME']->value;?>
</strong></button></div><?php } ?></div></span></div></div><br><div class="row-fluid"><input type="hidden" id="recordId" value="<?php echo $_smarty_tpl->tpl_vars['RECORD_ID']->value;?>
" /><input type="hidden" id="widgetReports4YouId" value="<?php echo $_smarty_tpl->tpl_vars['RECORD_ID']->value;?>
" /><?php if ($_smarty_tpl->tpl_vars['REPORTTYPE']->value!="custom_report"){?><?php $_smarty_tpl->tpl_vars['RECORD_STRUCTURE'] = new Smarty_variable(array(), null, 0);?><?php $_smarty_tpl->tpl_vars['PRIMARY_MODULE_LABEL'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['PRIMARY_MODULE']->value,$_smarty_tpl->tpl_vars['PRIMARY_MODULE']->value), null, 0);?><?php  $_smarty_tpl->tpl_vars['BLOCK_FIELDS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->_loop = false;
 $_smarty_tpl->tpl_vars['BLOCK_LABEL'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['PRIMARY_MODULE_RECORD_STRUCTURE']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->key => $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->value){
$_smarty_tpl->tpl_vars['BLOCK_FIELDS']->_loop = true;
 $_smarty_tpl->tpl_vars['BLOCK_LABEL']->value = $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->key;
?><?php $_smarty_tpl->tpl_vars['PRIMARY_MODULE_BLOCK_LABEL'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['BLOCK_LABEL']->value,$_smarty_tpl->tpl_vars['PRIMARY_MODULE']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['key'] = new Smarty_variable(($_smarty_tpl->tpl_vars['PRIMARY_MODULE_LABEL']->value)." ".($_smarty_tpl->tpl_vars['PRIMARY_MODULE_BLOCK_LABEL']->value), null, 0);?><?php if ($_smarty_tpl->tpl_vars['LINEITEM_FIELD_IN_CALCULATION']->value==false&&$_smarty_tpl->tpl_vars['BLOCK_LABEL']->value=='LBL_ITEM_DETAILS'){?><?php }else{ ?><?php $_smarty_tpl->createLocalArrayVariable('RECORD_STRUCTURE', null, 0);
$_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value[$_smarty_tpl->tpl_vars['key']->value] = $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->value;?><?php }?><?php } ?><?php  $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->_loop = false;
 $_smarty_tpl->tpl_vars['MODULE_LABEL'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->key => $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->value){
$_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->_loop = true;
 $_smarty_tpl->tpl_vars['MODULE_LABEL']->value = $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->key;
?><?php $_smarty_tpl->tpl_vars['SECONDARY_MODULE_LABEL'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['MODULE_LABEL']->value,$_smarty_tpl->tpl_vars['MODULE_LABEL']->value), null, 0);?><?php  $_smarty_tpl->tpl_vars['BLOCK_FIELDS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->_loop = false;
 $_smarty_tpl->tpl_vars['BLOCK_LABEL'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->key => $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->value){
$_smarty_tpl->tpl_vars['BLOCK_FIELDS']->_loop = true;
 $_smarty_tpl->tpl_vars['BLOCK_LABEL']->value = $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->key;
?><?php $_smarty_tpl->tpl_vars['SECONDARY_MODULE_BLOCK_LABEL'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['BLOCK_LABEL']->value,$_smarty_tpl->tpl_vars['MODULE_LABEL']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['key'] = new Smarty_variable(($_smarty_tpl->tpl_vars['SECONDARY_MODULE_LABEL']->value)." ".($_smarty_tpl->tpl_vars['SECONDARY_MODULE_BLOCK_LABEL']->value), null, 0);?><?php $_smarty_tpl->createLocalArrayVariable('RECORD_STRUCTURE', null, 0);
$_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value[$_smarty_tpl->tpl_vars['key']->value] = $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->value;?><?php } ?><?php } ?><?php echo $_smarty_tpl->getSubTemplate ('modules/ITS4YouReports/AdvanceFilter.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<div class="allConditionContainer conditionGroup contentsBackground well"><?php echo $_smarty_tpl->getSubTemplate ('modules/ITS4YouReports/FiltersCriteria.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><?php }?><div class="row-fluid"><div class="textAlignCenter"><button class="btn generateReport" data-mode="generate" value="<?php echo vtranslate('LBL_GENERATE_NOW',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"/><strong><?php echo vtranslate('LBL_GENERATE_NOW',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;<?php if ($_smarty_tpl->tpl_vars['REPORTTYPE']->value!="custom_report"&&$_smarty_tpl->tpl_vars['REPORT_MODEL']->value->isEditable()==true){?><button class="btn btn-success generateReport" data-mode="save" value="<?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"/><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><?php }?><?php if ($_smarty_tpl->tpl_vars['checkDashboardWidget']->value!=''&&$_smarty_tpl->tpl_vars['checkDashboardWidget']->value!="Exist"){?><button class="btn addWidget" data-mode="addwidget" value="<?php echo vtranslate('LBL_ADD_WIDGET',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"/><strong><?php echo vtranslate('LBL_ADD_WIDGET',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><?php }?></div></div><br></div></form></div></div>
<form method="post" action="IndexAjax" target="_blank">
    <input type="hidden" name="module" value="ITS4YouReports"/>  
    <input type="hidden" name="action" value="IndexAjax"/>  
    <input type="hidden" name="mode" value="ExportXLS"/>  
    <input type="hidden" name="filename" value="Test.xls"/>  
    <input type="hidden" name="report_html" id="report_html" value=""/>
</form>
<form method="post" action="index.php" name="GeneratePDF" id="GeneratePDF" target="_blank">
    <input type="hidden" name="module" value="ITS4YouReports"/>  
    <input type="hidden" name="action" value="GeneratePDF"/>  
    <input type="hidden" name="form_export_pdf_format" id="form_export_pdf_format" value=""/>  
    <input type="hidden" name="form_filename" id="form_filename" value=""/>
    <input type="hidden" name="form_report_name" id="form_report_name" value=""/>  
    <input type="hidden" name="form_report_html" id="form_report_html" value=""/>
    <input type="hidden" name="form_chart_canvas" id="form_chart_canvas" value=""/>
</form>
<form method="post" action="index.php" name="GenerateXLS" id="GenerateXLS" target="_blank">
    <input type="hidden" name="module" value="ITS4YouReports"/>  
    <input type="hidden" name="view" value="ExportReport"/> 
    <input type="hidden" name="mode" value="GetXLS"/> 
    <input type="hidden" name="record" value="<?php echo $_smarty_tpl->tpl_vars['RECORD_ID']->value;?>
"/> 
</form>
<?php }} ?>