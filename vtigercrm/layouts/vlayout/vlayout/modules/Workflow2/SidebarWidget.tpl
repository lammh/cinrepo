<div style="text-align:left;position:relative;padding:5px;">
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
            <button class="btn btn-success"  onclick="runSidebarWorkflow('{$crmid}');"name='runWorkfow' >{vtranslate('execute','Workflow2')}</button>
        {else}
            <span style="color:#777;font-style:italic;">{vtranslate('LBL_NO_WORKFLOWS','Workflow2')}</span>
        {/if}
    {/if}

    {if $isAdmin eq true}
    <a class="pull-right" href="#" onclick="showEntityData('{$crmid}');return false;" name='showEntityData'>{vtranslate('BTN_SHOW_ENTITYDATA','Workflow2')}</a>
    {/if}
    <div id="startfieldsContainer" style="position:relative;"></div>
    {foreach from=$buttons item=button}
    <button type="button" data-crmid="{$crmid}" class="btn" onclick="var workflow = new Workflow();workflow.execute({$button.workflow_id}, {$crmid});" alt="execute this workflow"  title="execute this workflow" style="text-shadow:none;color:{$button.textcolor}; background-color: {$button.color};margin-top:2px;width:100%;">{$button.label}</button><br/>
    {/foreach}

    {if count($waiting) gt 0}
        <p><strong>{vtranslate("running Workflows with this record","Workflow2")}:</strong></p>
        <table width='238' cellspacing=0  style="font-size:10px;">
            {foreach from=$waiting item=workflow}
                <tr>
                    {if $isAdmin eq true}
                        <td style='border-top:1px solid #ccc;' colspan=2><a href='index.php?module=Workflow2&view=Config&parent=Settings&workflow={$workflow.workflow_id}'>{$workflow.title}</a></td>
                    {else}
                        <td style='border-top:1px solid #ccc;' colspan=2>{$workflow.title}</td>
                    {/if}
                </tr>
                <tr>
                    <td colspan=2><strong>{$workflow.text}</strong></td>
                </tr>
                <tr>
                    <td style='border-bottom:1px solid #ccc;'>
                        <a href='#' onclick='return stopWorkflow("{$workflow.execid}","{$workflow.crmid}","{$workflow.block_id}");return false;'>del</a> |
                        <a href='#' onclick='return continueWorkflow("{$workflow.execid}","{$workflow.crmid}","{$workflow.block_id}");return false;'>continue</a>
                    </td>
                    <td style='text-align:right;border-bottom:1px solid #ccc;'>{DateTimeField::convertToUserFormat(VtUtils::convertToUserTZ($workflow.nextsteptime))}</td>
                </tr>
            {/foreach}
        </table>
    {/if}
</div>
<script type="text/javascript">var WorkflowRecordMessages = {$messages|json_encode}; var WFUserIsAdmin = {if $isAdmin eq true}true{else}false{/if};</script>
<script type="text/javascript">jQuery(window).trigger('workflow.detail.sidebar.ready');</script>
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