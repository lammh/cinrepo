<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 04:37:21
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Workflow2/SidebarWidget.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16860094955678d3819811f3-15976391%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6e2b1e88494b697474cd18c25ab3ad875a149442' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/Workflow2/SidebarWidget.tpl',
      1 => 1450267295,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16860094955678d3819811f3-15976391',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'show_listview' => 0,
    'workflows' => 0,
    'workflow' => 0,
    'crmid' => 0,
    'isAdmin' => 0,
    'buttons' => 0,
    'button' => 0,
    'waiting' => 0,
    'messages' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5678d381a7df2',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5678d381a7df2')) {function content_5678d381a7df2($_smarty_tpl) {?><div style="text-align:left;position:relative;padding:5px;">
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
            <button class="btn btn-success"  onclick="runSidebarWorkflow('<?php echo $_smarty_tpl->tpl_vars['crmid']->value;?>
');"name='runWorkfow' ><?php echo vtranslate('execute','Workflow2');?>
</button>
        <?php }else{ ?>
            <span style="color:#777;font-style:italic;"><?php echo vtranslate('LBL_NO_WORKFLOWS','Workflow2');?>
</span>
        <?php }?>
    <?php }?>

    <?php if ($_smarty_tpl->tpl_vars['isAdmin']->value==true){?>
    <a class="pull-right" href="#" onclick="showEntityData('<?php echo $_smarty_tpl->tpl_vars['crmid']->value;?>
');return false;" name='showEntityData'><?php echo vtranslate('BTN_SHOW_ENTITYDATA','Workflow2');?>
</a>
    <?php }?>
    <div id="startfieldsContainer" style="position:relative;"></div>
    <?php  $_smarty_tpl->tpl_vars['button'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['button']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['buttons']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['button']->key => $_smarty_tpl->tpl_vars['button']->value){
$_smarty_tpl->tpl_vars['button']->_loop = true;
?>
    <button type="button" data-crmid="<?php echo $_smarty_tpl->tpl_vars['crmid']->value;?>
" class="btn" onclick="var workflow = new Workflow();workflow.execute(<?php echo $_smarty_tpl->tpl_vars['button']->value['workflow_id'];?>
, <?php echo $_smarty_tpl->tpl_vars['crmid']->value;?>
);" alt="execute this workflow"  title="execute this workflow" style="text-shadow:none;color:<?php echo $_smarty_tpl->tpl_vars['button']->value['textcolor'];?>
; background-color: <?php echo $_smarty_tpl->tpl_vars['button']->value['color'];?>
;margin-top:2px;width:100%;"><?php echo $_smarty_tpl->tpl_vars['button']->value['label'];?>
</button><br/>
    <?php } ?>

    <?php if (count($_smarty_tpl->tpl_vars['waiting']->value)>0){?>
        <p><strong><?php echo vtranslate("running Workflows with this record","Workflow2");?>
:</strong></p>
        <table width='238' cellspacing=0  style="font-size:10px;">
            <?php  $_smarty_tpl->tpl_vars['workflow'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['workflow']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['waiting']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['workflow']->key => $_smarty_tpl->tpl_vars['workflow']->value){
$_smarty_tpl->tpl_vars['workflow']->_loop = true;
?>
                <tr>
                    <?php if ($_smarty_tpl->tpl_vars['isAdmin']->value==true){?>
                        <td style='border-top:1px solid #ccc;' colspan=2><a href='index.php?module=Workflow2&view=Config&parent=Settings&workflow=<?php echo $_smarty_tpl->tpl_vars['workflow']->value['workflow_id'];?>
'><?php echo $_smarty_tpl->tpl_vars['workflow']->value['title'];?>
</a></td>
                    <?php }else{ ?>
                        <td style='border-top:1px solid #ccc;' colspan=2><?php echo $_smarty_tpl->tpl_vars['workflow']->value['title'];?>
</td>
                    <?php }?>
                </tr>
                <tr>
                    <td colspan=2><strong><?php echo $_smarty_tpl->tpl_vars['workflow']->value['text'];?>
</strong></td>
                </tr>
                <tr>
                    <td style='border-bottom:1px solid #ccc;'>
                        <a href='#' onclick='return stopWorkflow("<?php echo $_smarty_tpl->tpl_vars['workflow']->value['execid'];?>
","<?php echo $_smarty_tpl->tpl_vars['workflow']->value['crmid'];?>
","<?php echo $_smarty_tpl->tpl_vars['workflow']->value['block_id'];?>
");return false;'>del</a> |
                        <a href='#' onclick='return continueWorkflow("<?php echo $_smarty_tpl->tpl_vars['workflow']->value['execid'];?>
","<?php echo $_smarty_tpl->tpl_vars['workflow']->value['crmid'];?>
","<?php echo $_smarty_tpl->tpl_vars['workflow']->value['block_id'];?>
");return false;'>continue</a>
                    </td>
                    <td style='text-align:right;border-bottom:1px solid #ccc;'><?php echo DateTimeField::convertToUserFormat(VtUtils::convertToUserTZ($_smarty_tpl->tpl_vars['workflow']->value['nextsteptime']));?>
</td>
                </tr>
            <?php } ?>
        </table>
    <?php }?>
</div>
<script type="text/javascript">var WorkflowRecordMessages = <?php echo json_encode($_smarty_tpl->tpl_vars['messages']->value);?>
; var WFUserIsAdmin = <?php if ($_smarty_tpl->tpl_vars['isAdmin']->value==true){?>true<?php }else{ ?>false<?php }?>;</script>
<script type="text/javascript">jQuery(window).trigger('workflow.detail.sidebar.ready');</script>
<?php }} ?>