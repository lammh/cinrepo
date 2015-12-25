<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 11:25:35
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ITS4YouReportsType.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1997220575679332fbaed12-03184080%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '93ca2fc95da4ee202b076cd050863a1b5aafbf8c' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/ITS4YouReportsType.tpl',
      1 => 1450267294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1997220575679332fbaed12-03184080',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'MODE' => 0,
    'IS_ADMIN_USER' => 0,
    'imgHeight' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5679332fc30e3',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5679332fc30e3')) {function content_5679332fc30e3($_smarty_tpl) {?>

<form class="form-horizontal recordEditView padding1per" id="form_report_types" method="post" action="index.php"><input type="hidden" name="module" value="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
" ><input type="hidden" name="view" value="Edit" ><input type="hidden" name="record" value="" ><input type="hidden" name="reporttype" id="reporttype" value="" ><input type="hidden" name="mode" id="mode" value="<?php echo $_smarty_tpl->tpl_vars['MODE']->value;?>
" ><input type="hidden" name="is_admin_user" id="is_admin_user" value="<?php echo $_smarty_tpl->tpl_vars['IS_ADMIN_USER']->value;?>
" ><?php if ($_smarty_tpl->tpl_vars['IS_ADMIN_USER']->value=="1"){?><?php $_smarty_tpl->tpl_vars["imgHeight"] = new Smarty_variable("height:8em;", null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars["imgHeight"] = new Smarty_variable("height:10em;", null, 0);?><?php }?><div class="padding1per border1px"><div class="row-fluid"><div><div><h4><strong><?php echo vtranslate('LBL_SELECT_REPORT_TYPE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></h4></div><br><div><div><ul class="nav nav-tabs" name="reportypetab" id="reportypetab"  style="text-align:center;font-size:14px;font-weight: bold;margin:0 3%;border:0px"><li class="active marginRight5px" ><a id="tabular" data-toggle="tab"><div><img src="modules/<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
/chart_types/rt1.png" id="rt1" style="border:1px solid #ccc;<?php echo $_smarty_tpl->tpl_vars['imgHeight']->value;?>
"/></div><br><div><?php echo vtranslate('LBL_TABULAR_REPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><li class="marginRight5px"><a id="summaries" data-toggle="tab"><div><img src="modules/<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
/chart_types/rt2.png" id="rt2" style="border:1px solid #ccc;<?php echo $_smarty_tpl->tpl_vars['imgHeight']->value;?>
"/></div><br><div><?php echo vtranslate('LBL_SUMMARIES_REPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><li class="marginRight5px"><a id="summaries_w_details" data-toggle="tab"><div><img src="modules/<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
/chart_types/rt3.png" id="rt3" style="border:1px solid #ccc;<?php echo $_smarty_tpl->tpl_vars['imgHeight']->value;?>
"/></div><br><div><?php echo vtranslate('LBL_SUMMARIES_WITH_DETAILS_REPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><li class="marginRight5px" ><a id="summaries_matrix" data-toggle="tab"><div><img src="modules/<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
/chart_types/rt4.png" id="rt4" style="border:1px solid #ccc;<?php echo $_smarty_tpl->tpl_vars['imgHeight']->value;?>
"/></div><br><div><?php echo vtranslate('LBL_SUMMARIES_MATRIX_REPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><?php if ($_smarty_tpl->tpl_vars['IS_ADMIN_USER']->value=="1"){?><li class="marginRight5px" ><a id="custom_report" data-toggle="tab"><div><img src="modules/<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
/chart_types/rt5.png" id="rt5" style="border:1px solid #ccc;<?php echo $_smarty_tpl->tpl_vars['imgHeight']->value;?>
"/></div><br><div><?php echo vtranslate('LBL_CUSTOM_REPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><?php }?></ul><div class='tab-content contentsBackground' id="reportypeInfoTab" style="height:auto;padding:4%;border:1px solid #ccc;"><div><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ReportsTypeHiddenContents.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div></div></div></div></div></div></div><br><div class="pull-right block padding20px"><button type="button" class="btn btn-danger backStep"><strong><?php echo vtranslate('LBL_BACK',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;&nbsp;<button type="button" class="btn btn-success" id="createReport"><strong><?php echo vtranslate('LBL_NEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;&nbsp;<br></div></form><?php }} ?>