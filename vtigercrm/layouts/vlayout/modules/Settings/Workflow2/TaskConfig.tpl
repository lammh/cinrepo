<link href='//fonts.googleapis.com/css?family=Source+Code+Pro' rel='stylesheet' type='text/css'>

<div class="TaskConfigContainer">
    <div id="pageOverlay" onclick="closePageOverlay();" style='cursor:url("modules/Workflow2/icons/cross-button.png"), auto;position:fixed;z-index:20000;top:0;left:0;display:none;height:100%;width:100%;background-image:url("modules/Workflow2/icons/modal.png");'><div id='pageOverlayContent' style='position:fixed;cursor:default;top:100px;margin:auto;left:50%;padding:10px;background-color:#ffffff;'>&nbsp;</div></div>
		<form method="POST" action="#" onsubmit="return checkForm();" id="mainTaskForm" name="mainTaskForm" accept-charset="UTF-8" enctype="multipart/form-data">
            {$csrf}

			<input type="hidden" id="save_workflow_id" name="editID" value="{$DATA.id}">

            <div id="fixedHeader">
			<table class="tableHeading" id="ConfigHeadline" width="100%" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td class="big blockHeader" nowrap="nowrap">
						<strong>{$MOD.LBL_ZUSAMMENFASSUNG}</strong>
					</td>
					<td class="small buttonbar blockHeader" align="right" style="padding:5px 5px 10px 10px;">
                        {if $helpUrl neq ""}
                        <div class="buttonbar inline" style="margin-right:50px;">
                            <input type="button" onclick="window.open('{$helpUrl}');" name="help_page" class="btn green" value="{vtranslate("LBL_DOCUMENTATION", "Settings:Workflow2")}" id="help_page">
                        </div>
                        {/if}
                        {if $DATA.type neq "start"}
                        <div class="buttonbar inline" style="margin-right:10px;">
                            <input type="button" id="edittask_duplicate_button" onclick="duplicateBlock({$block_id});" class="btn" value="{vtranslate("LBL_DUPLICATE_BLOCK", "Settings:Workflow2")}">
                            <input type="button" id="edittask_remove_button" onclick="removeBlock({$block_id});" class="btn" value="{vtranslate("LBL_DELETE_BLOCK", "Settings:Workflow2")}">
                        </div>
                        {/if}
                        <div class="buttonbar inline">
                            <input type="submit" name="save" class="btn green" value="{vtranslate("LBL_SAVE", "Settings:Workflow2")}" id="save">
                            <input type="button" onclick="window.close();" id="edittask_cancel_button" class="btn" value="{vtranslate("LBL_CLOSE", "Settings:Workflow2")}">
                        </div>
					</td>
				</tr>
			</table>
            </div>
        {foreach item=message from=$hint}
            {if $message neq ""}
                <div style='background-color:#fed22f;padding:5px;text-align:center;'>{$message}</div>
            {/if}
        {/foreach}
		<table border="0" cellpadding="5" cellspacing="0" width="100%" class="newTable">
            {if $DATA.type neq "start"}
			<tr>
				<td class="dvtCellLabel" align=right width=15% nowrap="nowrap"><b><font color="red">*</font> {vtranslate("LBL_AUFGABENBEZEICHNUNG", "Settings:Workflow2")}</b></td>
				<td class="dvtCellInfo" align="left" ><input type="text" class="detailedViewTextBox textfield taskTitle" name="taskSettings[text]" onblur="saveTaskText(this.value);" value="{$DATA.text|htmlentities}" id="task_text">&nbsp;<img src='modules/Workflow2/icons/save-indicator.gif' style="display:none;margin-left:5px;margin-bottom:-5px;" id="text_save_indicator"></td>
			</tr>
            {/if}
			<tr>
				<td class="dvtCellLabel" align=right width=15% nowrap="nowrap"><b>{vtranslate("LBL_STATUS", "Settings:Workflow2")}</b></td>
				<td class="dvtCellInfo" align="left">
					<select name="active" class="small chzn-select-nosearch" id="taskSelectActive" style="width:100px;">
						<option value="true">{vtranslate("LBL_ACTIVE", "Settings:Workflow2")}</option>
						<option value="false" {if $DATA.active eq "0"}selected='selected'{/if}>{vtranslate("LBL_INACTIVE", "Settings:Workflow2")}</option>
					</select> {helpurl url="workflowdesigner:tasks" height=28}
				</td>
			</tr>
		</table>

	<script src="modules/Workflow2/resources/jquery.timepicker.js" type="text/javascript" charset="utf-8"></script>

	<script src="modules/Workflow2/resources/functional.js" type="text/javascript" charset="utf-8"></script>
	<script src="modules/Workflow2/resources/VTUtils.js" type="text/javascript" charset="utf-8"></script>
	<script src="modules/Workflow2/resources/json2.js" type="text/javascript" charset="utf-8"></script>
	<script src="modules/Workflow2/resources/fieldvalidator.js" type="text/javascript" charset="utf-8"></script>
	<script src="modules/Workflow2/resources/edittaskscript.js?v={$smarty.const.WORKFLOW2_VERSION}" type="text/javascript" charset="utf-8"></script>
    <script src="modules/Workflow2/resources/vtigerwebservices.js" type="text/javascript" charset="utf-8"></script>
    <script src="libraries/jquery/jquery_windowmsg.js?&v=6.0.0" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="modules/Workflow2/views/resources/js/complexecondition.js?v={$CURRENT_VERSION}"></script>
    <script type="text/javascript" src="modules/Workflow2/views/resources/js/jquery.form.min.js?v={$CURRENT_VERSION}"></script>
    <script type="text/javascript" src="modules/Settings/Workflow2/views/resources/ConditionPopup.js?v={$CURRENT_VERSION}"></script>

	<script type="text/javascript" charset="utf-8">

		var returnUrl = '/index.php?module=Workflow2&action=Workflow2Ajax&file=configBlock&id={$DATA.id}';
		var validator;
		edittaskscript(jQuery);
        function handleError(fn){
        	return function(status, result){
        		if(status){
        			fn(result);
        		} else {
        		    // alert('Failure:'+result);
                    wsFields = {};
        		}
        	};
        }
        function initChosen() {ldelim}
            jQuery(".chzn-select").chosen();
            {*jQuery("#taskSelectActive").chosen({ldelim}disable_search_threshold: 3{rdelim});*}
            jQuery(".chzn-select-nosearch").chosen({ldelim}disable_search_threshold: 20{rdelim});
        {rdelim}

        function saveTaskText(text) {ldelim}
            jQuery("#text_save_indicator").show();
            jQuery.post("index.php?module=Workflow2&parent=Settings&action=TaskSaveTitle", {ldelim} ajaxaction:'setTaskText', block_id:'{$block_id}', text:text{rdelim}, function() {ldelim}
                jQuery("#text_save_indicator").hide();
            {rdelim});
        {rdelim}

        jQuery(function() {ldelim}
            initChosen();


    jQuery(document).keydown(function(e) {ldelim}
               if ( (e.which == '115' || e.which == '83' ) && (e.ctrlKey || e.metaKey) )
               {ldelim}
                   e.preventDefault();
                   jQuery("#mainTaskForm").trigger("submit");
                   return false;
               {rdelim}
               else
               {ldelim}
                   return true;
               {rdelim}
           });
        {rdelim});

	</script>
	<script type="text/javascript">
        var moduleName = '{$workflow_module_name}';
	</script>
    <script type="text/javascript">
        var MOD = {$MOD|@json_encode};
    </script>
    <script type="text/javascript">
        var oldTask = {$task|@json_encode};
    </script>
    <script type="text/javascript">
        /** For various field **/
        var dateFormat = '{$current_user.date_format}';
        var workflowID = {$workflowID};
        var workflowModuleName = moduleName;
    </script>
    <script type="text/javascript">
        {$additionalInlineJS}
    </script>
    {if $envSettings|@count gt 0}
    <div id="contEnvironmental" style="display:none;">
        <table class="tableHeading" border="0"  width="100%" cellspacing="0" cellpadding="5">
            <tr>
                <td class="big blockHeader" nowrap="nowrap">
                    <strong>{vtranslate('LBL_ENVIRONMENTAL_VARS_HEAD', 'Settings:Workflow2')}</strong>
                </td>
            </tr>
        </table>
        <p style="font-style:italic;margin: 10px;">
          {$MOD.LBL_ENVIRONMENTAL_DESCRIPTION}
        </p>
        <table border="0" cellpadding="5" cellspacing="0" width="100%" class="small newTable">
            {foreach key=key item=value from=$envSettings}
            <tr>
                <td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$value}</td>
                <td class='dvtCellInfo'><input type="text" name="task[env][{$key}]" value="{$task.env.$key}" class="form_input textfield" style='margin-right:10px;float:left;width: 250px;'></td>
            </tr>
            {/foreach}
        </table>
    </div>
    {/if}
		<table class="tableHeading" border="0"  width="100%" cellspacing="0" cellpadding="5">
			<tr>
				<td class="big blockHeader" nowrap="nowrap">
                {if $envSettings|@count gt 0}<a style="float:right;" href="#" onclick="jQuery('#contEnvironmental').slideToggle('fast');return false;">{$MOD.LBL_ENVIRONMENTAL_VARS_HEAD}</a>{/if}
                    <strong>{$MOD.LBL_AUSGABENBEZEICHNUNG}</strong>
				</td>
			</tr>
		</table>
			{$CONTENT}
		</form>
</div>