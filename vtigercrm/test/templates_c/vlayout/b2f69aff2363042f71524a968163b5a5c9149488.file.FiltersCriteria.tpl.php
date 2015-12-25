<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 11:29:06
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/FiltersCriteria.tpl" */ ?>
<?php /*%%SmartyHeaderCode:22388760056793402db8965-89119301%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b2f69aff2363042f71524a968163b5a5c9149488' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/FiltersCriteria.tpl',
      1 => 1450267295,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '22388760056793402db8965-89119301',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'std_filter_columns' => 0,
    'std_filter_criteria' => 0,
    'SEL_FIELDS' => 0,
    'BLOCKJS_STD' => 0,
    'REL_FIELDS' => 0,
    'DISPLAY_FILTER_HEADER' => 0,
    'MODULE' => 0,
    'EMPTY_CRITERIA_GROUPS' => 0,
    'CRITERIA_GROUPS' => 0,
    'GROUP_CRITERIA' => 0,
    'GROUP_COLUMNS' => 0,
    'GROUP_ID' => 0,
    'COLUMN_CRITERIA' => 0,
    'FCON_I' => 0,
    'COLUMN_INDEX' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_56793402ebc95',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56793402ebc95')) {function content_56793402ebc95($_smarty_tpl) {?>

<script language="JAVASCRIPT" type="text/javascript" src="layouts/vlayout/modules/ITS4YouReports/resources/ITS4YouReports.js"></script>

<input type="hidden" name="std_filter_columns" id="std_filter_columns" value='<?php echo $_smarty_tpl->tpl_vars['std_filter_columns']->value;?>
' />
<input type="hidden" name="std_filter_criteria" id="std_filter_criteria" value='<?php echo $_smarty_tpl->tpl_vars['std_filter_criteria']->value;?>
' />
<input type="hidden" name="sel_fields" id="sel_fields" value='<?php echo $_smarty_tpl->tpl_vars['SEL_FIELDS']->value;?>
' />


<?php echo $_smarty_tpl->tpl_vars['BLOCKJS_STD']->value;?>


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


<table border=0 cellspacing=0 cellpadding=0 width="100%">
    <?php if ($_smarty_tpl->tpl_vars['DISPLAY_FILTER_HEADER']->value===true){?>
            <tr>
                <td class="detailedViewHeader" nowrap align="left" colspan="8">
                
                    <div style="float:left;min-height: 2.3em;vertical-align: middle;padding-top:0.3em;">  
                        <span class="genHeaderGray" style=""><?php echo vtranslate('LBL_ADVANCED_FILTER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span> &nbsp;
                    </div>
                    <?php if ($_smarty_tpl->tpl_vars['EMPTY_CRITERIA_GROUPS']->value==true){?>
                        <div style="float:left;">  
                            <button type='button' class='btn fgroup_btn' style='float:left;' onclick='addNewConditionGroup("adv_filter_div")'><strong><?php echo vtranslate('LBL_NEW_GROUP',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>
                        </div>
                    <?php }?>
                </td>
            </tr>
    <?php }?>
    <tr>
        <td class="dvtCellLabel" nowrap align="center" style="padding:0px;" colspan="8" >
            <div style="display:block" id='adv_filter_div' name='adv_filter_div'>
                <table class="small" border="0" cellpadding="0" cellspacing="0" width="100%">
                </table>
                <?php $_smarty_tpl->tpl_vars['FCON_I'] = new Smarty_variable("0", null, 0);?>
<script type="text/javascript">var window_onload = "";</script>
                <?php  $_smarty_tpl->tpl_vars['GROUP_CRITERIA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['GROUP_CRITERIA']->_loop = false;
 $_smarty_tpl->tpl_vars['GROUP_ID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CRITERIA_GROUPS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['GROUP_CRITERIA']->key => $_smarty_tpl->tpl_vars['GROUP_CRITERIA']->value){
$_smarty_tpl->tpl_vars['GROUP_CRITERIA']->_loop = true;
 $_smarty_tpl->tpl_vars['GROUP_ID']->value = $_smarty_tpl->tpl_vars['GROUP_CRITERIA']->key;
?>
                    <?php $_smarty_tpl->tpl_vars['GROUP_COLUMNS'] = new Smarty_variable($_smarty_tpl->tpl_vars['GROUP_CRITERIA']->value['columns'], null, 0);?>
                    <script type="text/javascript">
window_onload += addConditionGroup('adv_filter_div');
                    </script>
                    <?php  $_smarty_tpl->tpl_vars['COLUMN_CRITERIA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->_loop = false;
 $_smarty_tpl->tpl_vars['COLUMN_INDEX'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['GROUP_COLUMNS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->key => $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value){
$_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->_loop = true;
 $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value = $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->key;
?>
                        <script type="text/javascript">
window_onload += 
                            addConditionRow('<?php echo $_smarty_tpl->tpl_vars['GROUP_ID']->value;?>
');
                            document.getElementById('fop' + advft_column_index_count).value = '<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['comparator'];?>
';
                            var conditionColumnRowElement = document.getElementById('fcol' + advft_column_index_count);
                            setSelectedCriteriaValue(conditionColumnRowElement,'<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['columnname'];?>
');
                            reports4you_updatefOptions(conditionColumnRowElement, 'fop' + advft_column_index_count, '<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['comparator'];?>
');
                            addRequiredElements('f', advft_column_index_count);
                            updateRelFieldOptions(conditionColumnRowElement, 'fval_' + advft_column_index_count);
                            var columnvalue = '<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['value'];?>
';
                            if ('<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['comparator'];?>
' == 'bw' && columnvalue != '') {
                                    var values = columnvalue.split(",");
                                    document.getElementById('fval' + advft_column_index_count).value = values[0];
                                    if (values.length == 2 && document.getElementById('fval_ext' + advft_column_index_count))
                                        document.getElementById('fval_ext' + advft_column_index_count).value = values[1];
                            } else {
                                document.getElementById('fval' + advft_column_index_count).value = columnvalue;
                            }
                        </script>
                        <?php if ($_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['column_condition']!=''){?>
                          <input type="hidden" name="hfcon_<?php echo $_smarty_tpl->tpl_vars['GROUP_ID']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['FCON_I']->value;?>
" id="hfcon_<?php echo $_smarty_tpl->tpl_vars['GROUP_ID']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['FCON_I']->value;?>
" value='<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['column_condition'];?>
' />
                        <?php }?>
                        <?php $_smarty_tpl->tpl_vars['FCON_I'] = new Smarty_variable($_smarty_tpl->tpl_vars['FCON_I']->value+1, null, 0);?>
                    <?php } ?>
                    <?php  $_smarty_tpl->tpl_vars['COLUMN_CRITERIA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->_loop = false;
 $_smarty_tpl->tpl_vars['COLUMN_INDEX'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['GROUP_COLUMNS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->key => $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value){
$_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->_loop = true;
 $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value = $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->key;
?>
                        <script type="text/javascript">
                            if (document.getElementById('fcon<?php echo $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value;?>
'))
                                document.getElementById('fcon<?php echo $_smarty_tpl->tpl_vars['COLUMN_INDEX']->value;?>
').value = '<?php echo $_smarty_tpl->tpl_vars['COLUMN_CRITERIA']->value['column_condition'];?>
';
                        </script>
                    <?php } ?>
                <?php }
if (!$_smarty_tpl->tpl_vars['GROUP_CRITERIA']->_loop) {
?>
                <?php } ?>
                <?php  $_smarty_tpl->tpl_vars['GROUP_CRITERIA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['GROUP_CRITERIA']->_loop = false;
 $_smarty_tpl->tpl_vars['GROUP_ID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CRITERIA_GROUPS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['GROUP_CRITERIA']->key => $_smarty_tpl->tpl_vars['GROUP_CRITERIA']->value){
$_smarty_tpl->tpl_vars['GROUP_CRITERIA']->_loop = true;
 $_smarty_tpl->tpl_vars['GROUP_ID']->value = $_smarty_tpl->tpl_vars['GROUP_CRITERIA']->key;
?>
                    <script type="text/javascript">
                        if (document.getElementById('gpcon<?php echo $_smarty_tpl->tpl_vars['GROUP_ID']->value;?>
'))
                            document.getElementById('gpcon<?php echo $_smarty_tpl->tpl_vars['GROUP_ID']->value;?>
').value = '<?php echo $_smarty_tpl->tpl_vars['GROUP_CRITERIA']->value['condition'];?>
';
                    </script>
                <?php } ?>
            </div>
        </td>
    </tr>
</table>
<script type="text/javascript">
window.onload = function(){
    //alert(window_onload);
    window_onload;
};
</script>
<?php }} ?>