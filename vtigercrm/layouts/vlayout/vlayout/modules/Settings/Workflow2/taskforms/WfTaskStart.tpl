<table border="0" cellpadding="5" cellspacing="0" width="100%" class="small">
	<tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_RUNTIME_WORKFLOW}</td>
		<td class='dvtCellInfo'>
			<select name="task[start]" class="chzn-select" style="width:250px;">
				<option value="synchron" {if $task.start eq "synchron"}selected='selected'{/if}>{$MOD.LBL_SYNCHRONOUS}</option>
				<option value="asynchron" {if $task.start eq "asynchron"}selected='selected'{/if}>{$MOD.LBL_ASYNCHRONOUS}</option>
			</select>
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_START_CONDITION}</td>
		<td class='dvtCellInfo'>
			<select name="task[runtime]" class="chzn-select" style="width:250px;">
                {html_options options=$trigger selected=$task.runtime}
			</select>
            <img src='modules/Workflow2/icons/add.png' style="height:18px;margin-top:3px;margin-left:5px;cursor:pointer;" onclick="window.open('index.php?module=Workflow2&action=settingsTrigger&parenttab=Settings');">
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_PARALLEL_ALLOWED}</td>
		<td class='dvtCellInfo'>
			<select name="task[runtime2]" class="chzn-select" style="width:250px;">
				<option value="2" {if $task.runtime2 eq "2"}selected='selected'{/if}>{$MOD.LBL_PARALLEL_NOT_ALLOW}</option>
				<option value="1" {if $task.runtime2 eq "1"}selected='selected'{/if}>{$MOD.LBL_PARALLEL_ALLOW}</option>
			</select>
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><input type="checkbox" name="task[execute_only_once_per_record]" value="1" {if $task.execute_only_once_per_record eq true}checked="checked"{/if} /></td>
		<td class='dvtCellInfo'>
			{vtranslate('LBL_EXECUTE_WORKFLOW_ONLY_ONCE_PER_RECORD','Settings:Workflow2')}
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><input type="checkbox" name="task[withoutrecord]" value="1" {if $task.withoutrecord eq true}checked="checked"{/if} /></td>
		<td class='dvtCellInfo'>
			{vtranslate('allow execution without a related record','Settings:Workflow2')} (<strong>{vtranslate('read documentation for more information', 'Settings:Workflow2')}</strong>)
		</td>
	</tr>
<!--
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_USER_EXECUTE}</td>
		<td class='dvtCellInfo'>
			<select name="task[execution_user]">
                <option value="0x0" {if $task.execution_user eq "0x0"}selected='selected'{/if}>{$MOD.LBL_START_USER}</option>
			</select>
		</td>
	</tr>
    -->
    </table>
<div class="big blockHeader" style="padding:5px;"><strong>{$MOD.HEAD_STARTVARIABLE_REQUEST}</strong></div>
<p style="margin:5px;" class="small">{$MOD.INFO_STARTVARIABLE}</p>
<div style='margin:2px;border:1px solid #ccc;padding:3px;'>
{$formGenerator}
</div>

<div class="big blockHeader" style="padding:5px;"><strong>{$MOD.HEAD_VISIBLE_CONDITION}</strong></div>

{$conditionalContent}