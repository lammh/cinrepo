<?php /* Smarty version Smarty-3.1.7, created on 2015-12-22 04:55:45
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/dashboards/DashBoardWidgetContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20281849765678d7d110d0c8-54045139%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e5beb0579a87b5a98d7a962a84a3a7797d536d1f' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/vlayout/modules/ITS4YouReports/dashboards/DashBoardWidgetContents.tpl',
      1 => 1450267294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20281849765678d7d110d0c8-54045139',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'display_widget_header' => 0,
    'MODULE_NAME' => 0,
    'DATA' => 0,
    'recordid' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5678d7d1207f7',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5678d7d1207f7')) {function content_5678d7d1207f7($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['display_widget_header']->value=='true'){?>
    <script type="text/javascript" src="layouts/vlayout/modules/ITS4YouReports/resources/Getreports.js"></script>

    <script type="text/javascript">
            //Vtiger_Barchat_Widget_Js('Vtiger_Getreports_Widget_Js',{},{});
            Vtiger_Getreports_Widget_Js('Vtiger_Getreports_Widget_Js',{},{});
    </script>
    
    <div class="dashboardWidgetHeader">
        <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("dashboards/WidgetHeader.tpl",$_smarty_tpl->tpl_vars['MODULE_NAME']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    </div>
<?php }?>
<div style="height:5px;"></div>
<input class="widgetData" type='hidden' value='<?php echo Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($_smarty_tpl->tpl_vars['DATA']->value));?>
' /><input id="widgetReports4YouId" type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['recordid']->value;?>
' /><div id="reports4you_widget_<?php echo $_smarty_tpl->tpl_vars['recordid']->value;?>
" style="height:83%;width:95%;margin:auto;" ></div><?php echo $_smarty_tpl->tpl_vars['DATA']->value;?>


<?php }} ?>