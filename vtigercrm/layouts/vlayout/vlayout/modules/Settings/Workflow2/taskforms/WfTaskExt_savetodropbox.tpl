<script type="text/javascript" src="modules/Workflow2/extends/additionally/savetodropbox/js/jstree/jquery.jstree.js"></script>
<table border="0" cellpadding="5" cellspacing="0" width="100%" class="small">
	<tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">Save to folder:</td>
		<td class='dvtCellInfo'>
            <input type="text" name="task[filepath]" id="filepath" value="{$task.filepath}">
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">create/use folder:</td>
		<td class='dvtCellInfo'>
            <textarea class="customFunction" type="text" name="task[create_dir]" id="create_dir">{$task.create_dir}</textarea> <img src='modules/Workflow2/icons/templatefieldPHP.png' style='margin-bottom:-2px;cursor:pointer;' onclick="insertTemplateField('create_dir', '[source]->[module]->[destination]', false)">
		</td>
	</tr>
</table>
<div id="jstree_container"></div>
