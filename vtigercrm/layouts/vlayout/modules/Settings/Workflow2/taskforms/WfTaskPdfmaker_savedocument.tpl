{if $disable neq true}
<table border="0" cellpadding="5" cellspacing="0" width="100%" class="small">
	<tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><strong>PDFMaker Version:</strong></td>
		<td class='dvtCellInfo'>
            <strong>{$PDFMAKER_VERSION}</strong>
		</td>
	</tr>
	<tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_DOCUMENT_TITLE}:</td>
		<td class='dvtCellInfo'>
            <div class="insertTextfield" data-name="task[documenttitle]" data-id="documenttitle">{$task.documenttitle}</div>
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_DOCUMENT_DESCR}:</td>
		<td class='dvtCellInfo'>
            <textarea name="task[documentdescr]" id="documentdescr">{$task.documentdescr}</textarea>
            <img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-7px;cursor:pointer;' onclick="insertTemplateField('documentdescr')">
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_FOLDER}:</td>
		<td class='dvtCellInfo'>
            <select style="vertical-align:top;" name='task[folderid]'>
               {foreach from=$folders key=label item=field}
                   <option value='{$field.folderid}'  {if $task.folderid eq $field.folderid}selected='selected'{/if}>{$field.foldername}</option>
               {/foreach}
           </select>
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_PDFTEMPLATE}:</td>
		<td class='dvtCellInfo'>
            <select style="vertical-align:top;" name='task[template]'>
               <option value=''>--- Choose Template ---</option>
               {foreach from=$templates key=label item=field}
                   <option value='{$field.templateid}'  {if $task.template eq $field.templateid}selected='selected'{/if}>{$field.filename}</option>
               {/foreach}
           </select>
		</td>
	</tr>
    <tr>
   		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_OVERWRITE_FILENAME}:</td>
   		<td class='dvtCellInfo'>
               <div class="insertTextfield" data-name="task[filename]" data-id="filename">{$task.filename}</div>
   		</td>
   	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_CREATE_RELATION}:</td>
		<td class='dvtCellInfo'>
            <input type="checkbox" name="task[createrel]" value="1" {if $task.createrel eq "1"}checked='checked'{/if}>
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$MOD.LBL_FORCE_WORKFLOW}:</td>
		<td class='dvtCellInfo'>
            <select style="vertical-align:top;" name='task[workflow]'>
               <option value=''>none</option>
               {foreach from=$workflows item=workflow}
                   <option value='{$workflow.id}' {if $task.workflow eq $workflow.id}selected='selected'{/if}>{$workflow.title}</option>
               {/foreach}
           </select>
		</td>
	</tr>
</table>
{else}
<br/>
<br/>
<strong>PDFMaker Extension not found</strong>
<br/>
<br/>
<br/>

{/if}