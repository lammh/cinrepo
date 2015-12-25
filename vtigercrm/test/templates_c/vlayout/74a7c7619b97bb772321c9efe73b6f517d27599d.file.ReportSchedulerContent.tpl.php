<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 11:25:41
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ReportSchedulerContent.tpl" */ ?>
<?php /*%%SmartyHeaderCode:184731601456793335788288-03513071%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '74a7c7619b97bb772321c9efe73b6f517d27599d' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ReportSchedulerContent.tpl',
      1 => 1450267294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '184731601456793335788288-03513071',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'IS_SCHEDULED' => 0,
    'schtypeid' => 0,
    'MONTH_STRINGS' => 0,
    'mid' => 0,
    'schmonth' => 0,
    'month' => 0,
    'schday' => 0,
    'WEEKDAY_STRINGS' => 0,
    'wid' => 0,
    'schweek' => 0,
    'week' => 0,
    'schtime' => 0,
    'REPORT_FORMAT_PDF' => 0,
    'REPORT_FORMAT_XLS' => 0,
    'SELECTED_RECIPIENTS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_56793335a81f4',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56793335a81f4')) {function content_56793335a81f4($_smarty_tpl) {?>

<table class="table table-bordered table-report">
    <thead>
        <tr class="blockHeader">
           <th colspan="2">
                <?php echo vtranslate('LBL_SCHEDULE_EMAIL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <input type="checkbox" name="isReportScheduled" id="isReportScheduled" <?php if ($_smarty_tpl->tpl_vars['IS_SCHEDULED']->value=='true'){?> checked <?php }?>>
           </th>
       </tr>
    </thead>
    <tbody> 
        <tr>
            <td class="fieldLabel medium"><label class="pull-right marginRight10px"><?php echo vtranslate('LBL_SCHEDULE_FREQUENCY',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td>
            <td>
                <div class="row-fluid">    
                    <div class="span" id="scheduledTypeSpan" >
                        <select class="chzn-select  chzn-done" name="scheduledType" id="scheduledType" onchange="javascript: setScheduleOptions();">
                                <!-- Hourly doesn't make sense on OD as the cron job is running once in 2 hours -->
                                
                                <option id="schtype_1" value="1" <?php if ($_smarty_tpl->tpl_vars['schtypeid']->value==1){?>selected<?php }?>><?php echo vtranslate('Hourly',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option>
                                <option id="schtype_2" value="2" <?php if ($_smarty_tpl->tpl_vars['schtypeid']->value==2){?>selected<?php }?>><?php echo vtranslate('Daily',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option>
                                <option id="schtype_3" value="3" <?php if ($_smarty_tpl->tpl_vars['schtypeid']->value==3){?>selected<?php }?>><?php echo vtranslate('Weekly',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option>
                                <option id="schtype_4" value="4" <?php if ($_smarty_tpl->tpl_vars['schtypeid']->value==4){?>selected<?php }?>><?php echo vtranslate('BiWeekly',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option>
                                <option id="schtype_5" value="5" <?php if ($_smarty_tpl->tpl_vars['schtypeid']->value==5){?>selected<?php }?>><?php echo vtranslate('Monthly',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option>
                                <option id="schtype_6" value="6" <?php if ($_smarty_tpl->tpl_vars['schtypeid']->value==6){?>selected<?php }?>><?php echo vtranslate('Annually',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option>
                        </select>
                    </div>
                    <div class="span" id="scheduledMonthSpan" style="display: <?php if ($_smarty_tpl->tpl_vars['schtypeid']->value==6){?>inline<?php }else{ ?>none<?php }?>;">&nbsp;<strong><?php echo getTranslatedString('LBL_SCHEDULE_EMAIL_MONTH','ITS4YouReports');?>
</strong>:
                            <select class="chzn-select  chzn-done span2" name="scheduledMonth" id="scheduledMonth">
                                    <?php $_smarty_tpl->tpl_vars["MONTH_STRINGS"] = new Smarty_variable(vtranslate('MONTH_STRINGS',$_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?>
                                    <?php  $_smarty_tpl->tpl_vars['month'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['month']->_loop = false;
 $_smarty_tpl->tpl_vars['mid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MONTH_STRINGS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['month']->key => $_smarty_tpl->tpl_vars['month']->value){
$_smarty_tpl->tpl_vars['month']->_loop = true;
 $_smarty_tpl->tpl_vars['mid']->value = $_smarty_tpl->tpl_vars['month']->key;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['mid']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['schmonth']->value==$_smarty_tpl->tpl_vars['mid']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['month']->value;?>
</option>
                                    <?php } ?>
                            </select>
                    </div>

                    <!-- day of month (monthly, annually) -->
                    <div class="span" id="scheduledDOMSpan" style="display: <?php if ($_smarty_tpl->tpl_vars['schtypeid']->value==5||$_smarty_tpl->tpl_vars['schtypeid']->value==6){?>inline<?php }else{ ?>none<?php }?>;">&nbsp;<strong><?php echo vtranslate('LBL_SCHEDULE_EMAIL_DAY',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong>:
                            <select class="chzn-select  chzn-done span1" name="scheduledDOM" id="scheduledDOM">
                                    <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['day'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['day']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['name'] = 'day';
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['start'] = (int)1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['loop'] = is_array($_loop=32) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['step'] = 1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['day']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['day']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['day']['total']);
?>
                                    <option value="<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['day']['iteration'];?>
" <?php if ($_smarty_tpl->tpl_vars['schday']->value==$_smarty_tpl->getVariable('smarty')->value['section']['day']['iteration']){?>selected<?php }?>><?php echo $_smarty_tpl->getVariable('smarty')->value['section']['day']['iteration'];?>
</option>
                                    <?php endfor; endif; ?>
                            </select>
                    </div>

                    <!-- day of week (weekly/bi-weekly) -->
                    <div class="span" id="scheduledDOWSpan" style="display: <?php if ($_smarty_tpl->tpl_vars['schtypeid']->value==3||$_smarty_tpl->tpl_vars['schtypeid']->value==4){?>inline<?php }else{ ?>none<?php }?>;">&nbsp;<strong><?php echo vtranslate('LBL_SCHEDULE_EMAIL_DOW',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong>:
                            <select class="chzn-select  chzn-done span2" name="scheduledDOW" id="scheduledDOW">
                                    <?php $_smarty_tpl->tpl_vars["WEEKDAY_STRINGS"] = new Smarty_variable(vtranslate('WEEKDAY_STRINGS',$_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?>
                                    <?php  $_smarty_tpl->tpl_vars['week'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['week']->_loop = false;
 $_smarty_tpl->tpl_vars['wid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['WEEKDAY_STRINGS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['week']->key => $_smarty_tpl->tpl_vars['week']->value){
$_smarty_tpl->tpl_vars['week']->_loop = true;
 $_smarty_tpl->tpl_vars['wid']->value = $_smarty_tpl->tpl_vars['week']->key;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['wid']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['schweek']->value==$_smarty_tpl->tpl_vars['wid']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['week']->value;?>
</option>
                                    <?php } ?>
                            </select>
                    </div>

                    <!-- time (daily, weekly, bi-weekly, monthly, annully) -->
                    <div class="span" id="scheduledTimeSpan" style="display: <?php if ($_smarty_tpl->tpl_vars['schtypeid']->value>0){?>inline<?php }else{ ?>none<?php }?>;">&nbsp;<strong><?php echo vtranslate('LBL_SCHEDULE_EMAIL_TIME',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong>:
                            <input class="span2" type="text" name="scheduledTime" id="scheduledTime" value="<?php echo $_smarty_tpl->tpl_vars['schtime']->value;?>
" size="5" maxlength="5" />&nbsp;<?php echo vtranslate('LBL_TIME_FORMAT_MSG',$_smarty_tpl->tpl_vars['MODULE']->value);?>

                    </div>
                </div>
                <input type="hidden" name="scheduledIntervalString" value="" />
            </td>
	</tr>
	<tr>
            <td class="fieldLabel medium"><label class="pull-right marginRight10px"><?php echo vtranslate('LBL_REPORT_FORMAT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td>
            <td>
                <?php echo vtranslate('LBL_REPORT_FORMAT_PDF',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <input type="checkbox" name="scheduledReportFormat_pdf" id="scheduledReportFormat_pdf" <?php if ($_smarty_tpl->tpl_vars['REPORT_FORMAT_PDF']->value=='true'){?> checked <?php }?>>&nbsp;
                <?php echo vtranslate('LBL_REPORT_FORMAT_EXCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <input type="checkbox" name="scheduledReportFormat_xls" id="scheduledReportFormat_xls" <?php if ($_smarty_tpl->tpl_vars['REPORT_FORMAT_XLS']->value=='true'){?> checked <?php }?>>&nbsp;
                
            </td>
	</tr>
	<tr>
            <td class="fieldLabel medium"><label class="pull-right marginRight10px"><?php echo vtranslate('LBL_USERS_AVAILABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td>
            <td>
                <?php echo vtranslate('LBL_SELECT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:&nbsp;
                <select id="recipient_type" name="recipient_type" class="small" onChange="showRecipientsOptions()">
                        <option value="users"><?php echo getTranslatedString('LBL_USERS','ITS4YouReports');?>
</option>
                        <option value="groups"><?php echo getTranslatedString('LBL_GROUPS','ITS4YouReports');?>
</option>
                        <option value="roles"><?php echo getTranslatedString('LBL_ROLES','ITS4YouReports');?>
</option>
                        <option value="rs"><?php echo getTranslatedString('LBL_ROLES_SUBORDINATES','ITS4YouReports');?>
</option>
                </select>
                <input type="hidden" name="findStr1" class="small">&nbsp;<br><br>
                <div class="row-fluid">
                    <div class="span5">
                        <strong><?php echo vtranslate('LBL_SELECT_USERS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong><br />
                        <div id="availableRecipientsWrapper" style="width:100%;"></div>
                    </div>
                    <div class="span1" align="center">
                        <br /><br /><br /><br />
                        <a href="#" class="btn addButtonR4You" name="addButton" onClick="addOption()" > >> </a><br /><br />
                        <a href="#" class="btn addButtonR4You" name="delButton" onClick="delOption()" > << </a>
                    </div>
                    <div class="span5">
                        <strong><?php echo vtranslate('LBL_USERS_SELECTED',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong><br />
                        <select id="selectedRecipients" name="selectedRecipients" multiple size="10" class="crmFormList" style="width:100%;">
                        <?php echo $_smarty_tpl->tpl_vars['SELECTED_RECIPIENTS']->value;?>

                        </select>
                        <input type="hidden" name="selectedRecipientsString"/>
                    </div>
                </div>
            </td>
	</tr>
    <tbody>
</table><?php }} ?>