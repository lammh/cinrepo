<div style="overflow:hidden;padding:5px;line-height:25px;">
    <a href="index.php?module=Workflow2&view=Index&parent=Settings" class="btn btn-inverse pull-right"><i class='icon-white icon-arrow-left'></i>&nbsp;&nbsp;{vtranslate("Back", "Settings:Workflow2")}</a>
    <strong>{vtranslate("Workflow2", "Workflow2")}</strong>
</div>
<div class="quickWidgetContainer accordion" id="WFconfigQuickWidgetContainer">
    <div class="quickWidget">
        <div class="accordion-heading accordion-toggle quickWidgetHeader" data-parent="#settingsQuickWidgetContainer" data-target="#Settings_sideBar_Settings"
            data-toggle="collapse" data-parent="#quickWidgets">
            <span class="pull-left"><img class="imageElement" data-rightimage="{vimage_path('rightArrowWhite.png')}" data-downimage="{vimage_path('downArrowWhite.png')}" src="{vimage_path('downArrowWhite.png')}" /></span>
            <h5 class="title paddingLeft10px widgetTextOverflowEllipsis" title="{vtranslate("Settings", "Settings:Workflow2")}">{vtranslate("Settings", "Settings:Workflow2")}</h5>
            <div class="clearfix"></div>
        </div>
        <div style="overflow:visible;" class="widgetContainer accordion-body in collapse" id="Settings_sideBar_Settings">
            <div style="padding:5px;background-color:#ffffff;" id='settingsCont'>
                <form method="POST" action="#" {if $workflowData.module_name neq ""}onsubmit='return false'{/if} role="form">
                    <input type="hidden" name="workflow[id]" value="{$workflowID}">
                    {if $workflowData.module_name eq ""}
                        <div>{vtranslate("Main Module", "Settings:Workflow2")}</div>
                        <div><select class="chzn-select" style="float:right;height:25px;width:199px;" name="workflow[mainmodule]">
                            {foreach from=$module key=key item=moduleName}
                                <option value="{$key}">{$moduleName}</option>
                            {/foreach}
                        </select></div>
                    {else}
                        <div>{vtranslate("Main module", "Settings:Workflow2")}</div>
                        <div style="text-align:right;font-weight:bold;">{vtranslate($workflowData.module_name, $workflowData.module_name)}</div>

                        <div class="form-group" style="overflow:hidden;">
                            <label for="workflow_title">{vtranslate("Title", "Settings:Workflow2")}</label>
                            <input type="text" class="form-control" style="float:right;width:85%;" onblur="saveWorkflowTitle();" id="workflow_title" name="workflow[title]" value="{$workflowData.title}">
                        </div>
                        <div class="form-group" style="overflow:hidden;">
                            <label style="float:left;line-height: 22px;">{vtranslate("LBL_WORKFLOW_IS_ACTIVE", "Settings:Workflow2")}</label>
                            <div class="switch">
                              <input id="workflowActiveSwitch" class="cmn-toggle cmn-toggle-round" type="checkbox" {if $workflowData.active eq '1'}checked="checked"{/if} value='1'>
                              <label for="workflowActiveSwitch"></label>
                            </div>
                        </div>
                    {/if}
                    <br>
                    <div class="buttonbar center" style="clear:both;margin-bottom:10px;">
                        {if $workflowData.module_name eq ""}
                            <input type="submit"  style=";" onclick="saveSetings({$workflowID});"  name="submitSettings" class="btn btn-primary" value="{vtranslate("Save Settings", "Settings:Workflow2")}">
                        {else}
                            <a href="index.php?module=Workflow2&view=Statistic&parent=Settings&workflow={$workflowID}" class="btn btn-info" target="_blank">{vtranslate("Statistics", "Settings:Workflow2")}</a>
                        {/if}
                    </div>
                    {if $workflowData.module_name neq ""}
                        <div class="center" style="margin-top:5px;padding-top:10px;color:#999999;height:40px;text-align:center;">
                            <div style='text-align:center;width:48%;float:left;' alt='<?php echo getTranslatedString("LBL_CURRENTLY_RUNNING_DESCR", "Workflow2"); ?>' title='<?php echo getTranslatedString("LBL_CURRENTLY_RUNNING_DESCR", "Workflow2"); ?>'>
                                <span class='overviewStatisticNumber'>{$runningCounter}</span>
                                <br>
                                    {vtranslate("LBL_CURRENTLY_RUNNING", "Settings:Workflow2")}
                            </div>
                            <div onclick='{if $errorCounter > 0}window.open("index.php?module=Workflow2&view=ErrorLog&parent=Settings&workflow_id={$workflowID}", "", "width=700,height=800");{/if}' style='float:left;cursor:pointer;text-align:center;width:48%;' alt='{vtranslate("LBL_LAST_ERRORS_DESCR", "Settings:Workflow2")}' title='{vtranslate("LBL_LAST_ERRORS_DESCR", "Settings:Workflow2")}'>
                                <span class='overviewStatisticNumber'>{$errorCounter}</span>
                                <br>
                                    {vtranslate("LBL_LAST_ERRORS", "Settings:Workflow2")}
                            </div>
                        </div>

                        <div id="runningWarning" style="display:{if $runningCounter > 0 && $workflowData.active neq '1'}block{else}none{/if};">
                            <br/>
                            <p>{vtranslate('You have deactivate the workflow. But already running instances will be executed nevertheless.', 'Settings:Workflow2')}</p>
                            <button type="button" id="stopAllRunningInstances" class="btb btn-warning">{vtranslate('stop all running instances','Settings:Workflow2')}</button>
                        </div>

                        <!-- if(!empty($_SESSION["mWFB"])) {
                            echo "<div style='background-color:#eeeeee;border:1px solid #777777;text-align:center;padding:5px 0;'>Your license only allows ".$_SESSION["mWFB"]." Blocks</div>";
                        } -->
                        <br><a href='#' onclick='showOptionsContainer();return false;'>+ {vtranslate("LBL_OPTIONEN", "Settings:Workflow2")}</a><br>
                        <div id='optionsContainer' style="display:none;">
                            <p>
                                <label>
                                    <input class="pull-left" type="checkbox" onclick="refreshBlockIDs();" name="optionShowBlockId" id="optionShowBlockId">&nbsp;&nbsp;&nbsp;show BlockIDs
                                </label>
                            </p>
                        </div>
                    {/if}
                </form>
            </div>
    </div>
</div>
{if $workflowData.module_name neq ""}
    <input type="text" class="typeSearchBox" style="width:100%;box-sizing: border-box;height:30px;" placeholder="{vtranslate('search in available types', 'Settings:Workflow2')}"/>
    {foreach from=$typesCat key=blockKey item=typekey}
    <div class="quickWidget taskWidgetContainer">
        <div class="accordion-heading accordion-toggle quickWidgetHeader" data-parent="#settingsQuickWidgetContainer" data-target="#Settings_sideBar_{$blockKey|replace:' ':'_'}"
            data-toggle="collapse" data-parent="#quickWidgets">
            <span class="pull-left"><img class="imageElement" data-rightimage="{vimage_path('rightArrowWhite.png')}" data-downimage="{vimage_path('downArrowWhite.png')}" src="{vimage_path('rightArrowWhite.png')}" /></span>
            <h5 class="title paddingLeft10px widgetTextOverflowEllipsis" title="{$blockKey}">{$blockKey}</h5>
            <div class="clearfix"></div>
        </div>

        <div style="" class="widgetContainer accordion-body collapse typeContainer" id="Settings_sideBar_{$blockKey|replace:' ':'_'}" data-block="{$typekey.0.0}">
            {foreach from=$typekey item=typeVal key=blockType}
                {assign var=typeVal value=$typeVal.1}
                {assign var=type value=$types.$typeVal}
                <div class="WorkflowTypeContainer" data-search="{$typeVal}{$type->get("text")|@htmlentities|@strtolower}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'  onclick="addBlock('{$typeVal}');return false;" data-type="{$typeVal}" data-default="b">
                    <div class="row-fluid menuItem">
                        <a href="#" data-id="#" class="span11 menuItemLabel" data-menu-item="true">{$type->get("text")}</a>
                        <span class="span1 moveTypes"><img src="modules/Workflow2/icons/drag.png"/></span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
    {/foreach}
    <div class="quickWidget taskWidgetContainer">
        <div class="accordion-heading accordion-toggle quickWidgetHeader" data-parent="#settingsQuickWidgetContainer" data-target="#Settings_sideBar_extraObjects"
            data-toggle="collapse" data-parent="#quickWidgets">
            <span class="pull-left"><img class="imageElement" data-rightimage="{vimage_path('rightArrowWhite.png')}" data-downimage="{vimage_path('downArrowWhite.png')}" src="{vimage_path('rightArrowWhite.png')}" /></span>
            <h5 class="title paddingLeft10px widgetTextOverflowEllipsis" title="extra Objects">extra Objects</h5>
            <div class="clearfix"></div>
        </div>
        <div style="" class="widgetContainer accordion-body collapse" id="Settings_sideBar_extraObjects">
            <div class="" style='padding-left:10px;border-top:0px;padding-bottom: 5px'  onclick="addRecord('Users');">
                <div class="row-fluid menuItem">
                    <a href="#" data-id="#" class="span9 menuItemLabel" data-menu-item="true" >User</a>
                    <span class="span1">&nbsp;</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="" style='padding-left:10px;border-top:0px;padding-bottom: 5px'  onclick="addObject('Text');">
                <div class="row-fluid menuItem">
                    <a href="#" data-id="#" class="span9 menuItemLabel" data-menu-item="true" >Text</a>
                    <span class="span1">&nbsp;</span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
{/if}
</div>
