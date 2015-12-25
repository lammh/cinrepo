<table border="0" cellpadding="5" cellspacing="0" width="100%" class="small">
	<tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.CHECK_THIS_QUERY}:</td>
		<td class='dvtCellInfo'>
			<textarea name="task[query]" class="span7" rows="4" id="commentText" >{$task.query}</textarea>
            <img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-7px;cursor:pointer;' onclick="insertTemplateField('commentText','([source] : ([module]) [destination])', true)"><br>
		</td>
	</tr>
    <tr>
   		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_MYSQL_QUERY_ENV_VARIABLE}:</td>
   		<td class='dvtCellInfo'>
   			$env["<input type="text" required="required" name="task[envvar]" value="{$task.envvar}" />"] = mysql_fetch_assoc($result);
   		</td>
   	</tr>
</table>