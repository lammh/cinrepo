{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
{strip}
<div class="container-fluid" id="moduleManagerContents">
    <div class="widget_header row-fluid">
  			<div class="span6"><h3>{vtranslate('Workflow Designer', 'Workflow2')}</h3></div>
  		</div>
  		<hr>

	<div class="listViewContentDiv" id="listViewContents">
        {if empty($ERROR_HANDLER_VALUE)}
            <div class="alert alert-error">
                {vtranslate('Please configure Log Management to receive errors!', 'Settings:Workflow2')}&nbsp;&nbsp;&nbsp;<a class="btn" href="index.php?module=Workflow2&view=SettingsLogging&parent=Settings">{vtranslate('open','Settings:Workflow2')|ucfirst} {vtranslate('LBL_SETTINGS_LOGGING','Settings:Workflow2')}</strong></a>
            </div>
        {/if}

                        <div class="row" style="margin-left:0;">
                            <span class='pull-left'>
                                <button class="btn addButton" onclick="window.location.href='index.php?module=Workflow2&view=Index&parent=Settings&workflow=999&act=create';"><i class="icon-plus"></i> <strong>{vtranslate("Create new workflow","Settings:Workflow2")}</strong></button>
                                <button class="btn addButton" onclick="importWorkflow();"><i class="icon-file"></i> <strong>{vtranslate("import Workflow","Settings:Workflow2")}</strong></button>
                            </span>

                            {if $is_admin eq true}
                                <div class='btn-group pull-right'>
                                    <button class="btn btn-success" onclick="window.location.href='index.php?module=Workflow2&view=Upgrade&parent=Settings';"><i class=" icon-white icon-asterisk"></i> <strong>{vtranslate("LBL_UPDATE_MODULE","Settings:Workflow2")}</strong></button>
                                </div>
                            {/if}
                        </div>

                        {foreach from=$workflows item=workflowArray key=moduleName}
                            <div  class="blockHeader workflowModuleHeader" data-target="{$workflowArray[0]['module_name']}">
                                {*<span class="pull-right"><a style='color:white;' id="toggleSidebarButton_{$workflowArray[0]['module_name']}" onclick="toggleSidebar('{$workflowArray[0]['module_name']}','toggleSidebarButton_{$workflowArray[0]['module_name']}');return false;" href='#'>{if $workflowArray[0]['sidebar_active'] eq false}{vtranslate('LBL_ACTIVATE_SIDEBAR', 'Settings:Workflow2')}{else}{vtranslate('LBL_DEACTIVATE_SIDEBAR', 'Settings:Workflow2')}{/if}</a></span>*}
                                <div style="float:left;font-weight:bold;text-transform:uppercase;">
                                    <img src="modules/Workflow2/icons/toggle_minus.png" class="toggleImageCollapse toggleImage"  style="{if $visibility[$workflowArray[0]['module_name']] eq false}display:none;{/if}" />
                                    <img src="modules/Workflow2/icons/toggle_plus.png" class="toggleImageExpand toggleImage" style="{if $visibility[$workflowArray[0]['module_name']] eq true}display:none;{/if}" />
                                    &nbsp;<b>&nbsp;{$moduleName}</b> ({count($workflowArray)})
                                </div>
                            </div>

                            <table width="100%" cellspacing="0" cellpadding="4" style="{if $visibility[$workflowArray[0]['module_name']] eq false}display:none;{/if}border-collapse:collapse;" id="workflowList{$workflowArray[0]['module_name']}" data-visible="{if $visibility[$workflowArray[0]['module_name']] eq true}1{else}0{/if}">
                            {foreach from=$workflowArray item=workflow}
                                <tr class='workflowOverview' style="background-color:{if $workflow["active"]=="0"}#ffffff{else}#F0FFEB{/if};">
                                    <td width="10" style="padding:0;margin:0;font-size:1px;background-color:transparent !important;">&nbsp;</td>
                                    <td class="dvtCellInfo buttonbar" style="width:30px;text-align:center;">
                                        {if $workflow["active"]=="0"}
                                            <img src='modules/Workflow2/icons/play.png' style="cursor:pointer;" alt="{vtranslate("Activate","Settings:Workflow2")}" title="{vtranslate("Activate","Settings:Workflow2")}" onclick="window.location.href='index.php?module=Workflow2&view=Index&parent=Settings&workflow={$workflow.id}&act=activate';" >
                                            <!--<input type="button" class="button green"value="" />-->
                                        {else}
                                            <img src='modules/Workflow2/icons/stop.png' style="cursor:pointer;" alt="{vtranslate("Deactivate","Settings:Workflow2")}" title="{vtranslate("Deactivate","Settings:Workflow2")}" onclick="window.location.href='index.php?module=Workflow2&view=Index&parent=Settings&workflow={$workflow.id}&act=deactivate';">
                                            <!--<input type="button" class="button red" value="<?php echo getTranslatedString("Deactivate", "Workflow2") ?>" />-->
                                        {/if}
                                    </td>
                                    <td class="dvtCellInfo" style="{if $workflow.active=="0"}font-weight:bold;{/if}"><span style="cursor: pointer;" onclick="window.location.href='index.php?module=Workflow2&view=Config&parent=Settings&workflow={$workflow.id}'">{$workflow.title}</span><span style='float:right;color:#aaa;font-style:normal;'>{$workflow.startCondition}</span></span></td>
                                    <td class="dvtCellInfo {if $workflow.active=="1"}activeWorkflow{else}inactiveWorkflow{/if}">{if $workflow.active=="0"}{vtranslate("LBL_INACTIVE","Settings:Workflow2")}{else}{vtranslate("LBL_ACTIVE","Settings:Workflow2")}{/if}</td>
                                    <td class="dvtCellInfo" style="background-color:#fff;width:500px;text-align:center;">
                                        <div class="buttonbar inline" style="float: left;margin-right:20px;">
                                            <input type="button" class="btn green" onclick="window.location.href='index.php?module=Workflow2&view=Config&parent=Settings&workflow={$workflow.id}';" value="{vtranslate("Edit","Settings:Workflow2")}" />
                                        </div>
                                        <div class="btn-group inline" style="float: left;">
                                            <input type="button" class="btn yellow" onclick="window.location.href='index.php?module=Workflow2&view=Statistic&parent=Settings&workflow={$workflow.id}';" value="{vtranslate("Statistics","Settings:Workflow2")}" />
                                            <input type="button" class="btn yellow" onclick="window.location.href='index.php?module=Workflow2&view=Authmanager&parent=Settings&workflow={$workflow.id}';" value="{vtranslate("BTN_AUTH_MANAGEMENT","Settings:Workflow2")}" />
                                            <input type="button" class="btn yellow" onclick="var passwd = prompt('{vtranslate("You could set a password to protect the export file.","Settings:Workflow2")}'); window.location.href='index.php?module=Workflow2&action=Export&parent=Settings&workflow={$workflow.id}&passwd=' + (passwd==null?'':passwd);" value="{vtranslate("Export","Settings:Workflow2")}" />
                                        </div>
                                            <img src='modules/Workflow2/icons/multiple.png' alt='<?php echo getTranslatedString("LBL_DUPLICATE", "Workflow2") ?>' title='<?php echo getTranslatedString("LBL_DUPLICATE", "Workflow2") ?>' style="margin-bottom:-12px;cursor:pointer;" onclick="window.location.href='index.php?module=Workflow2&view=Index&parent=Settings&workflow={$workflow.id}&act=duplicate';" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <img src='modules/Workflow2/icons/delete.png' alt='<?php echo getTranslatedString("Delete", "Workflow2") ?>' title='<?php echo getTranslatedString("Delete", "Workflow2") ?>' style="margin-bottom:-12px;cursor:pointer;" onclick="if(confirm('Wirklich löschen?\n\nMomentan laufende Prozesse werden abgebrochen!')) window.location.href='index.php?module=Workflow2&view=Index&parent=Settings&workflow={$workflow.id}&act=delete';" >
                                            <!--<input type="button" class="button red" value="" />-->
                                    </td>
                                    <td width="10" style="padding:0;margin:0;font-size:1px;background-color:transparent !important;">&nbsp;</td>
                                </tr>
                            {/foreach}
                            </table>
                        {/foreach}
                    <?

                        <?php } ?>


                    <div style="margin-top:5px;text-align:center;"><?php echo getTranslatedString("This Workflow administration needs IE9+, Google Chrome, Firefox or Safari!", "Workflow2"); ?><br><strong><?php echo getTranslatedString("Don't open a Workflow with IE < 9!"); ?></strong></div>


    <link href="modules/Workflow2/views/resources/js/notifications/main.css" rel="stylesheet" type="text/css" media="screen" />
    <script src="modules/Workflow2/views/resources/js/notifications/js/notification-min.js"></script>
{/strip}
