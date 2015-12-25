<table border="0" cellpadding="5" cellspacing="0" width="100%" class="small">
	<tr>
        <td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_DEACTIVATE_REDIRECT}</td>
              <td class='dvtCellInfo' style="width:25px;">
                  <input type="radio" name="task[redirect_type]" value="none" {if empty($task.redirect_type) || $task.redirect_type == "none"}checked='checked'{/if}>
              </td>
      		<td class='dvtCellInfo'>

      		</td>
      	</tr>

   	<tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_REDIRECT_TO_URL}</td>
        <td class='dvtCellInfo' style="width:25px;">
            <input type="radio" name="task[redirect_type]" value="url" {if $task.redirect_type == "url"}checked='checked'{/if}>
        </td>
		<td class='dvtCellInfo'>
            <div class="insertTextfield" data-name="task[redirection]" data-id="redirection" data-options=''>{$task.redirection}</div>
		</td>
	</tr>
	<tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_REDIRECT_TO_PDFMAKER}</td>
        <td class='dvtCellInfo' style="width:25px;">
            <input type="radio" {if $ENABLE_PDFMAKER eq false}disabled="disabled"{/if} name="task[redirect_type]" value="pdfmaker" {if $task.redirect_type == "pdfmaker"}checked='checked'{/if}>
        </td>
		<td class='dvtCellInfo'>
            <select {if $ENABLE_PDFMAKER eq false}disabled="disabled"{/if} id="task-template" class="chzn-select" name="task[pdftemplate]" style="width:300px;">
                <option value="">none</option>
                {html_options options=$pdfmaker_templates selected=$task.pdftemplate}
            </select>
		</td>
	</tr>
</table>
<br>
<table border="0" cellpadding="5" cellspacing="0" width="100%" class="small">
	<tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_REDIRECT_TO_URL_TARGET}</td>
		<td class='dvtCellInfo' colspan=2>
            <select name="task[target]">
                <option value="same" {if $task.target eq "same"}selected='selected'{/if}>same Window</option>
                <option value="new" {if $task.target eq "new"}selected='selected'{/if}>new Window (PopUp)</option>
            </select>
            <br>
            {$MOD.LBL_REDIRECT_TO_URL_TARGET_DESCR}
		</td>
	</tr>
</table>