<table border="0" cellpadding="5" cellspacing="0" width="100%" class="small newTable">
    <tr>
   		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_COMMENT_RECORD}</td>
   		<td class='dvtCellInfo'>
   			<select name="task[relRecord]" class="chzn-select" onchange="if(this.value == 'custom') { jQuery('#customRecordContainer').show(); } else { jQuery('#customRecordContainer').hide(); }  ">
   				<option value="" {if $task.baseTime eq "now()"}selected='selected'{/if}>{$MOD.LBL_THIS_RECORD}</option>
                   {foreach from=$references item=item key=key}
                       <option value="{$item->name}" {if $item->name eq $task.relRecord}selected='selected'{/if}>{$item->label} [{$item->targetModule}]</option>
                   {/foreach}

                   <option value="custom" {if $task.relRecord eq 'custom'}selected='selected'{/if}>{vtranslate('custom RecordID', 'Settings:Workflow2')}</option>
   			</select>
   		</td>
   	</tr>
    <tr id="customRecordContainer" style="{if $task.relRecord neq 'custom'}display:none;{/if}">
   		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{vtranslate('generate custom recordID', 'Settings:Workflow2')}</td>
   		<td class='dvtCellInfo'>
               <div class="insertTextfield" data-name="task[customid]" data-id="customid">{$task.customid}</div>
   		</td>
   	</tr>

    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_CREATE_COMMENT_TEXT}</td>
		<td class='dvtCellInfo'>
            <textarea name="task[comment]" id="commentText" class="textfield span6" rows="7">{$task.comment}</textarea>
            <img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-7px;cursor:pointer;' onclick="insertTemplateField('commentText','([source] : ([module]) [destination])', true)"><br>
		</td>
	</tr>
</table>

