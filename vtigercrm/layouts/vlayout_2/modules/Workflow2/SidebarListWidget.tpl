<div style="text-align:left;position:relative;padding:5px;">
    <input type="hidden" id="WFD_CURRENT_MODULE" value="{$source_module}" />
    <div id='workflow_layer_executer' style='display:none;width:100%;height:100%;top:0px;left:0px;background-image:url(modules/Workflow2/icons/modal_white.png);font-size:12px;letter-spacing:1px;border:1px solid #777777;  position:absolute;text-align:center;'><br><img src='modules/Workflow2/icons/sending.gif'><br><br><strong>Executing Workflow ...</strong><br><a href='#' onclick='jQuery("#workflow_layer_executer").hide();return false;'>Close</a></a></div>
    {if $show_listview eq true}
        {if count($workflows) gt 0}
            {vtranslate('LBL_FORCE_EXECUTION','Workflow2')}
            <select name="workflow2_workflowid" id="workflow2_workflowid" size=7 class="detailedViewTextBox" style="width:100%;">
                <!--<option value='0'><?php echo getTranslatedString("LBL_CHOOSE", "Workflow2"); ?></option>-->
                {foreach from=$workflows item=workflow}
                    <option value='{$workflow.id}' data-withoutrecord="{$workflow.withoutrecord}">{$workflow.title}</option>
                {/foreach}
            </select>
            <div id="executionProgress_Value" style="text-align:center;font-weight:bold;display:none;"></div>
            <button class="btn btn-success"  onclick="runListViewSidebarWorkflow();"name='runWorkfow' >{vtranslate('execute','Settings:Workflow2')}</button>
        {else}
            <span style="color:#777;font-style:italic;">{vtranslate('LBL_NO_WORKFLOWS','Workflow2')}</span>
        {/if}
    {/if}
    {foreach from=$buttons item=button}
    <button type="button" data-crmid="{$crmid}" data-withoutrecord="{$button.withoutrecord}" class="btn" onclick="runListViewWorkflow({$button.workflow_id}, jQuery(this).data('withoutrecord') == '1');" alt="execute this workflow"  title="execute this workflow" style="text-shadow:none;color:{$button.textcolor}; background-color: {$button.color};margin-top:2px;width:100%;">{$button.label}</button><br/>
    {/foreach}

    <div id="startfieldsContainer" style="position:relative;"></div>
    {if $hide_importer neq true}
    <hr>
    <button class="btn btn-info" onclick="WorkflowHandler.startImport();">Import Prozess starten</button>
    {/if}
</div>
<script type="text/javascript">var WorkflowRecordMessages = {$messages|json_encode}; var WFUserIsAdmin = {if $isAdmin eq true}true{else}false{/if};</script>
<script type="text/javascript">jQuery(window).trigger('workflow.list.sidebar.ready');</script>
{*
        <?php foreach($workflows as $row) {
                if($row["trigger"] == "WF2_IMPORTER") continue;
            $objWorkflow = new Workflow_Main($row["id"]);

        if($row["authmanagement"] == "0" || $objWorkflow->checkAuth("view")) {
        ?>
        <?php }

        }
        ?>
*}