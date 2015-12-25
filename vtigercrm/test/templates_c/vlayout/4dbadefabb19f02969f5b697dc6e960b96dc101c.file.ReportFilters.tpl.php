<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 11:29:06
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ReportFilters.tpl" */ ?>
<?php /*%%SmartyHeaderCode:38585132856793402873ef4-04828191%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4dbadefabb19f02969f5b697dc6e960b96dc101c' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ReportFilters.tpl',
      1 => 1450267294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '38585132856793402873ef4-04828191',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'REL_FIELDS' => 0,
    'BLOCKJS_STD' => 0,
    'MODULE' => 0,
    'REPORTTYPE' => 0,
    'display_summaries_filter' => 0,
    'SUMMARIES_CRITERIA' => 0,
    'COLUMN_INDEX' => 0,
    'COLUMN_CRITERIA' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_56793402ab599',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56793402ab599')) {function content_56793402ab599($_smarty_tpl) {?>

<script type="text/javascript">
    var advft_column_index_count = -1;
    var advft_group_index_count = 0;
    var column_index_array = [];
    var group_index_array = [];

    var gf_advft_column_index_count = -1;
    var gf_advft_group_index_count = 0;
    var gf_column_index_array = [];
    var gf_group_index_array = [];
    var rel_fields = <?php echo $_smarty_tpl->tpl_vars['REL_FIELDS']->value;?>
;
</script>

<?php echo $_smarty_tpl->getSubTemplate ('modules/ITS4YouReports/AdvanceFilter.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php echo $_smarty_tpl->tpl_vars['BLOCKJS_STD']->value;?>


<input type="hidden" name="advft_criteria" id="advft_criteria" value="" />
<input type="hidden" name="advft_criteria_groups" id="advft_criteria_groups" value="" />
<input type="hidden" name="groupft_criteria" id="groupft_criteria" value="" />


<div class="row-fluid">       
    <div class="span9">
        <div class="row-fluid">
            <table class="table table-bordered table-report">
                <thead>
                    <tr class="blockHeader">
                        <th style="vertical-align: middle;">
                             <?php echo vtranslate('LBL_ADVANCED_FILTER',$_smarty_tpl->tpl_vars['MODULE']->value);?>

                             
                             
                        </th>
                    </tr>
                </thead>
                <tbody> 
                
                    <tr>
                        <td>
                            <div class="filterContainer">
                                <div style="display:block" id='adv_filter_div' name='adv_filter_div'>
                                    
                                    <?php echo $_smarty_tpl->getSubTemplate ('modules/ITS4YouReports/FiltersCriteria.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

                                    
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            

            
            <?php $_smarty_tpl->tpl_vars["display_summaries_filter"] = new Smarty_variable("display:block;", null, 0);?>
            <?php if ($_smarty_tpl->tpl_vars['REPORTTYPE']->value=="tabular"){?>
              <?php $_smarty_tpl->tpl_vars["display_summaries_filter"] = new Smarty_variable("display:none;", null, 0);?>
            <?php }?>

            <div style="width:100%;<?php echo $_smarty_tpl->tpl_vars['display_summaries_filter']->value;?>
" id='group_filter_div' name='group_filter_div' class="paddingTop20">

                <table class="table table-bordered table-report">
                    <thead>
                        <tr class="blockHeader">
                            <th>
                                 <?php echo vtranslate('LBL_GROUP_FILTER',$_smarty_tpl->tpl_vars['MODULE']->value);?>

                            </th>
                        </tr>
                    </thead>
                </table>
                <table class="table table-bordered table-report" id='conditiongrouptable_0'>
                    <tr id='ggroupfooter_0'>
                        <td colspan='5' align='left'>
                            
                            <button type='button' class='btn' style='float:left;' onclick='addGroupConditionRow("0")'><strong><?php echo vtranslate('LBL_NEW_CONDITION',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>
                        </td>
                    </tr>
                </table>
                <table class="table">
                    <tr><td align='center' id='groupconditionglue_0'>
                        </td></tr>
                </table>

                <?php  $_smarty_tpl->tpl_vars['COLUMN_CRITERIA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->_loop = false;
 $_smarty_tpl->tpl_vars['COLUMN_INDEX'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SUMMARIES_CRITERIA']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->key => $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value){
$_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->_loop = true;
 $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value = $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->key;
?>
                    <script type="text/javascript">
                        addGroupConditionRow('0');

                        document.getElementById('ggroupop<?php echo $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value;?>
').value = '<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['comparator'];?>
';
                        var conditionColumnRowElement = document.getElementById('ggroupcol<?php echo $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value;?>
');
                        conditionColumnRowElement.value = '<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['columnname'];?>
';
                        
                        addRequiredElements('g', '<?php echo $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value;?>
');
                        var columnvalue = '<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['value'];?>
';
                        document.getElementById('ggroupval<?php echo $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value;?>
').value = columnvalue;
                        
                    </script>
                <?php } ?>
                <?php  $_smarty_tpl->tpl_vars['COLUMN_CRITERIA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->_loop = false;
 $_smarty_tpl->tpl_vars['COLUMN_INDEX'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SUMMARIES_CRITERIA']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->key => $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value){
$_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->_loop = true;
 $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value = $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->key;
?>
                    <script type="text/javascript">
                        if (document.getElementById('gcon<?php echo $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value;?>
'))
                            document.getElementById('gcon<?php echo $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value;?>
').value = '<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['column_condition'];?>
';
                    </script>
                <?php } ?>
            </div>

            
                
       </div>
    </div>
    <div class="span4" style="width: 20%;">
        <div class="row-fluid">           
            <table class="table table-bordered table-report">
                <thead>
                    <tr class="blockHeader">
                       <th colspan="2">
                        <i class="icon-info-sign"></i>&nbsp;<?php echo vtranslate('LBL_FILTERS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<br>
                       </th>
                   </tr>
                </thead>
                <tbody>    
                    <tr style="height:25px">
                        <td>
                            <?php echo vtranslate('LBL_STEP8_INFO',$_smarty_tpl->tpl_vars['MODULE']->value);?>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> 

<?php }} ?>