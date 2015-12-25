<table border="0" cellpadding="5" cellspacing="0" width="100%" class="small">
	<tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">execute this Workflow</td>
        <td class='dvtCellInfo' width=50 style="text-align:center;">
            <input type="radio" name="task[wf_chooser]" {if $task.wf_chooser eq '' OR $task.wf_chooser eq "1"}checked='checked'{/if}value="1">
        </td>
		<td class='dvtCellInfo'>
			<select name="task[workflow_id]" class="chzn-select">
				<option value="0" {if $task.workflow_id eq ""}selected='selected'{/if}>-</option>
                {foreach from=$workflows item=item key=key}
                    <option value="{$item.id}" {if $item.id eq $task.workflow_id}selected='selected'{/if}>{$item.title}</option>
                {/foreach}
			</select>
		</td>
	</tr>
    <tr>
   		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">Execute WF by Name</td>
           <td class='dvtCellInfo' width=50 style="text-align:center;">
               <input type="radio" name="task[wf_chooser]" {if $task.wf_chooser eq "2"}checked='checked'{/if}value="2">
           </td>
   		<td class='dvtCellInfo'>
               <div class="insertTextfield" data-name="task[wf_name]" data-id="wf_name">{$task.wf_name}</div>
   		</td>
   	</tr>
</table>