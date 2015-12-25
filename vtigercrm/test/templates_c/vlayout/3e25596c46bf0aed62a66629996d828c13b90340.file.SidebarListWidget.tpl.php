<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 04:37:06
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Workflow2/SidebarListWidget.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20395142505678d3721d2df2-52196290%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3e25596c46bf0aed62a66629996d828c13b90340' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Workflow2/SidebarListWidget.tpl',
      1 => 1450267295,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20395142505678d3721d2df2-52196290',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'source_module' => 0,
    'show_listview' => 0,
    'workflows' => 0,
    'workflow' => 0,
    'buttons' => 0,
    'crmid' => 0,
    'button' => 0,
    'hide_importer' => 0,
    'messages' => 0,
    'isAdmin' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5678d3722844b',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5678d3722844b')) {function content_5678d3722844b($_smarty_tpl) {?><div style="text-align:left;position:relative;padding:5px;">
    <input type="hidden" id="WFD_CURRENT_MODULE" value="<?php echo $_smarty_tpl->tpl_vars['source_module']->value;?>
" />
    <div id='workflow_layer_executer' style='display:none;width:100%;height:100%;top:0px;left:0px;background-image:url(modules/Workflow2/icons/modal_white.png);font-size:12px;letter-spacing:1px;border:1px solid #777777;  position:absolute;text-align:center;'><br><img src='modules/Workflow2/icons/sending.gif'><br><br><strong>Executing Workflow ...</strong><br><a href='#' onclick='jQuery("#workflow_layer_executer").hide();return false;'>Close</a></a></div>
    <?php if ($_smarty_tpl->tpl_vars['show_listview']->value==true){?>
        <?php if (count($_smarty_tpl->tpl_vars['workflows']->value)>0){?>
            <?php echo vtranslate('LBL_FORCE_EXECUTION','Workflow2');?>

            <select name="workflow2_workflowid" id="workflow2_workflowid" size=7 class="detailedViewTextBox" style="width:100%;">
                <!--<option value='0'><<?php ?>?php echo getTranslatedString("LBL_CHOOSE", "Workflow2"); ?<?php ?>></option>-->
                <?php  $_smarty_tpl->tpl_vars['workflow'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['workflow']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['workflows']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['workflow']->key => $_smarty_tpl->tpl_vars['workflow']->value){
$_smarty_tpl->tpl_vars['workflow']->_loop = true;
?>
                    <option value='<?php echo $_smarty_tpl->tpl_vars['workflow']->value['id'];?>
' data-withoutrecord="<?php echo $_smarty_tpl->tpl_vars['workflow']->value['withoutrecord'];?>
"><?php echo $_smarty_tpl->tpl_vars['workflow']->value['title'];?>
</option>
                <?php } ?>
            </select>
            <div id="executionProgress_Value" style="text-align:center;font-weight:bold;display:none;"></div>
            <button class="btn btn-success"  onclick="runListViewSidebarWorkflow();"name='runWorkfow' ><?php echo vtranslate('execute','Settings:Workflow2');?>
</button>
        <?php }else{ ?>
            <span style="color:#777;font-style:italic;"><?php echo vtranslate('LBL_NO_WORKFLOWS','Workflow2');?>
</span>
        <?php }?>
    <?php }?>
    <?php  $_smarty_tpl->tpl_vars['button'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['button']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['buttons']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['button']->key => $_smarty_tpl->tpl_vars['button']->value){
$_smarty_tpl->tpl_vars['button']->_loop = true;
?>
    <button type="button" data-crmid="<?php echo $_smarty_tpl->tpl_vars['crmid']->value;?>
" data-withoutrecord="<?php echo $_smarty_tpl->tpl_vars['button']->value['withoutrecord'];?>
" class="btn" onclick="runListViewWorkflow(<?php echo $_smarty_tpl->tpl_vars['button']->value['workflow_id'];?>
, jQuery(this).data('withoutrecord') == '1');" alt="execute this workflow"  title="execute this workflow" style="text-shadow:none;color:<?php echo $_smarty_tpl->tpl_vars['button']->value['textcolor'];?>
; background-color: <?php echo $_smarty_tpl->tpl_vars['button']->value['color'];?>
;margin-top:2px;width:100%;"><?php echo $_smarty_tpl->tpl_vars['button']->value['label'];?>
</button><br/>
    <?php } ?>

    <div id="startfieldsContainer" style="position:relative;"></div>
    <?php if ($_smarty_tpl->tpl_vars['hide_importer']->value!=true){?>
    <hr>
    <button class="btn btn-info" onclick="WorkflowHandler.startImport();">Import Prozess starten</button>
    <?php }?>
</div>
<script type="text/javascript">var WorkflowRecordMessages = <?php echo json_encode($_smarty_tpl->tpl_vars['messages']->value);?>
; var WFUserIsAdmin = <?php if ($_smarty_tpl->tpl_vars['isAdmin']->value==true){?>true<?php }else{ ?>false<?php }?>;</script>
<script type="text/javascript">jQuery(window).trigger('workflow.list.sidebar.ready');</script>
<?php }} ?>