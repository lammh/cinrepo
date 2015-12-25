<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 11:25:41
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/EditCustom.tpl" */ ?>
<?php /*%%SmartyHeaderCode:59660514256793335b99686-98136303%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a97083b15ce5328fcff553adb1af1c532ddaff57' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/EditCustom.tpl',
      1 => 1450267294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '59660514256793335b99686-98136303',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'DATE_FORMAT' => 0,
    'SEC_MODULE' => 0,
    'RECORDID' => 0,
    'FOLDERID' => 0,
    'relmodulesstring' => 0,
    'MODE' => 0,
    'isDuplicate' => 0,
    'cancel_btn_url' => 0,
    'REPORTTYPE' => 0,
    'CREATE_MODE' => 0,
    'DUPLICATE_REPORTNAME' => 0,
    'MOD' => 0,
    'REPORTNAME' => 0,
    'MODULE' => 0,
    'steps_display' => 0,
    'REPORT_CUSTOMSQL' => 0,
    'REPORT_SHARING' => 0,
    'REPORT_SCHEDULER' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_56793335c8c63',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56793335c8c63')) {function content_56793335c8c63($_smarty_tpl) {?>


<script language="JAVASCRIPT" type="text/javascript" src="layouts/vlayout/modules/ITS4YouReports/resources/ITS4YouReports.js"></script>

<?php echo $_smarty_tpl->tpl_vars['DATE_FORMAT']->value;?>
	
<script> var none_lang = "<?php echo vtranslate('LBL_NONE');?>
"; </script><style type="text/css">.table-report th{border-bottom:1px solid #DDD;}.table-report td{border:0px;}.table-report tr td {background: none !important;}.table-bordered tr td{border:0px;vertical-align: middle;}.table-bordered input{vertical-align: middle;margin:auto;}</style><form name="NewReport" id="NewReport" action="index.php" method="POST" enctype="multipart/form-data" onsubmit="return changeSteps();"><input type="hidden" name="module" value="ITS4YouReports"><input type="hidden" name='secondarymodule' id='secondarymodule' value="<?php echo $_smarty_tpl->tpl_vars['SEC_MODULE']->value;?>
"/><input type="hidden" name="record" id="record" value="<?php echo $_smarty_tpl->tpl_vars['RECORDID']->value;?>
"><input type="hidden" name='modulesString' id='modulesString' value=''/><input type="hidden" name='reload' id='reload' value='true'/><input type="hidden" name='action' id='action' value='Save'/><input type="hidden" name='file' id='file' value=''/><input type="hidden" name='folder' id='folder' value="<?php echo $_smarty_tpl->tpl_vars['FOLDERID']->value;?>
"/><input type="hidden" name='relatedmodules' id='relatedmodules' value='<?php echo $_smarty_tpl->tpl_vars['relmodulesstring']->value;?>
'/><input type="hidden" name='mode' id='mode' value='<?php echo $_smarty_tpl->tpl_vars['MODE']->value;?>
' /><input type="hidden" name='isDuplicate' id='isDuplicate' value='<?php echo $_smarty_tpl->tpl_vars['isDuplicate']->value;?>
' /><input type="hidden" name='SaveType' id='SaveType' value='' /><input type="hidden" name='actual_step' id='actual_step' value='1' /><input type="hidden" name='cancel_btn_url' id='cancel_btn_url' value='<?php echo $_smarty_tpl->tpl_vars['cancel_btn_url']->value;?>
' /><input type="hidden" name="reporttype" id="reporttype" value="<?php echo $_smarty_tpl->tpl_vars['REPORTTYPE']->value;?>
"><!-- DISPLAY --><table border=0 cellspacing=0 cellpadding=5 width=100%<?php ?>><tr><?php if ($_smarty_tpl->tpl_vars['CREATE_MODE']->value=='edit'){?><?php if ($_smarty_tpl->tpl_vars['DUPLICATE_REPORTNAME']->value==''){?><td class=heading2 valign=bottom>&nbsp;&nbsp;<b><?php echo $_smarty_tpl->tpl_vars['MOD']->value['LBL_EDIT'];?>
 &quot;<?php echo $_smarty_tpl->tpl_vars['REPORTNAME']->value;?>
&quot; </b></td><?php }else{ ?><td class=heading2 valign=bottom>&nbsp;&nbsp;<b><?php echo $_smarty_tpl->tpl_vars['MOD']->value['LBL_DUPLICATE'];?>
 &quot;<?php echo $_smarty_tpl->tpl_vars['DUPLICATE_REPORTNAME']->value;?>
&quot; </b></td><?php }?><?php }else{ ?><td class=heading2 valign=bottom>&nbsp;&nbsp;<b><?php echo $_smarty_tpl->tpl_vars['MOD']->value['LBL_NEW_TEMPLATE'];?>
</b></td><?php }?></tr></table><div class="contents tabbable ui-sortable"><ul class="nav nav-tabs layoutTabs massEditTabs" id="reportTabs" style="margin-left:0.6em;" ><li class="r4you_step active" id="rtypestep1"><a data-toggle="tab" data-step="1" href="#"><strong><?php echo vtranslate('LBL_REPORT_DETAILS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><li class="r4you_step relatedListTab" id="rtypestep12" ><a data-toggle="tab" data-step="12" href="#relatedTabReport"><strong><?php echo vtranslate('LBL_REPORT_SQL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><li class="r4you_step relatedListTab" id="rtypestep9" ><a data-toggle="tab" data-step="9" href="#relatedTabReport"><strong><?php echo vtranslate('LBL_SHARING',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><li class="r4you_step relatedListTab" id="rtypestep10" ><a data-toggle="tab" data-step="10" href="#relatedTabReport"><strong><?php echo vtranslate('LBL_LIMIT_SCHEDULER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li></ul></div><div class="tab-content layoutContent padding20 themeTableColor overflowVisible"><div class="tab-pane active" id="detailViewLayout"><table width="100%" align="center" border="0" cellpadding="0" cellspacing="0"><tr><td class="small" style="text-align:center;padding:0px 0px 10px 0px;"><input type="button" name="back_rep_top" id="back_rep_top" value=" &nbsp;&lt;&nbsp;<?php echo vtranslate('LBL_BACK',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp; " disabled="disabled" class="btn" onClick="">&nbsp;&nbsp;<div  id="submitbutton" style="display:<?php if ($_smarty_tpl->tpl_vars['MODE']->value!='edit'){?>none<?php }else{ ?>inline<?php }?>;" ><button class="btn btn-success" type="button" id="savebtn" onclick=""><strong><?php echo vtranslate('LBL_SAVE_BUTTON_LABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><button type="button" class="btn btn-danger backStep" id="cancelbtn" onclick=""><strong><?php echo vtranslate('LBL_CANCEL_BUTTON_LABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;<button class="btn btn-success" type="button" id="saverunbtn" onclick=""><strong><?php echo vtranslate('LBL_SAVE_RUN_BUTTON_LABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button></div><div  id="submitbutton0T" style="display:<?php if ($_smarty_tpl->tpl_vars['MODE']->value!='edit'){?>inline<?php }else{ ?>none<?php }?>;" ><button type="button" class="btn btn-danger backStep" id="cancelbtn0T" onclick=""><strong><?php echo vtranslate('LBL_CANCEL_BUTTON_LABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;</div><input type="button" name="next" id="next_rep_top" value=" &nbsp;<?php echo vtranslate('LNK_LIST_NEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;&rsaquo;&nbsp; " onClick="" class="btn">&nbsp;&nbsp;</td></tr><tr><td align="left" valign="top"><div class="reportTab" id="step1"><?php echo $_smarty_tpl->getSubTemplate ('modules/ITS4YouReports/ReportsCustomStep1.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><div class="<?php echo $_smarty_tpl->tpl_vars['steps_display']->value;?>
" id="step12"><?php echo $_smarty_tpl->tpl_vars['REPORT_CUSTOMSQL']->value;?>
</div><div class="<?php echo $_smarty_tpl->tpl_vars['steps_display']->value;?>
" id="step9"><?php echo $_smarty_tpl->tpl_vars['REPORT_SHARING']->value;?>
</div><div class="<?php echo $_smarty_tpl->tpl_vars['steps_display']->value;?>
" id="step10"><?php echo $_smarty_tpl->tpl_vars['REPORT_SCHEDULER']->value;?>
</div></td></tr></table><table width="100%"  border="0" cellspacing="0" cellpadding="5" ><tr><td class="small" style="text-align:center;padding:10px 0px 10px 0px;" colspan="3"><input type="button" name="back_rep_top" id="back_rep_top2" value=" &nbsp;&lt;&nbsp;<?php echo vtranslate('LBL_BACK',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp; " disabled="disabled" class="btn" onClick="">&nbsp;&nbsp;<div  id="submitbutton2" style="display:<?php if ($_smarty_tpl->tpl_vars['MODE']->value!='edit'){?>none<?php }else{ ?>inline<?php }?>;" ><button class="btn btn-success" type="button" id="savebtn2" onclick=""><strong><?php echo vtranslate('LBL_SAVE_BUTTON_LABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><button type="button" class="btn btn-danger backStep" id="cancelbtn2" onclick=""><strong><?php echo vtranslate('LBL_CANCEL_BUTTON_LABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;<button class="btn btn-success" type="button" id="saverunbtn2" onclick=""><strong><?php echo vtranslate('LBL_SAVE_RUN_BUTTON_LABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button></div><div  id="submitbutton0B" style="display:<?php if ($_smarty_tpl->tpl_vars['MODE']->value!='edit'){?>inline<?php }else{ ?>none<?php }?>;" ><button type="button" class="btn btn-danger backStep" id="cancelbtn0B" onclick=""><strong><?php echo vtranslate('LBL_CANCEL_BUTTON_LABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;</div><input type="button" name="next" id="next_rep_top2" value=" &nbsp;<?php echo vtranslate('LNK_LIST_NEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;&rsaquo;&nbsp; " onClick="" class="btn">&nbsp;&nbsp;</td></tr></table></div></div></form><?php echo $_smarty_tpl->getSubTemplate ('modules/ITS4YouReports/EditScript.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>