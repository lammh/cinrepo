<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td class="dvtCellLabel" align="right" width="25%">{vtranslate('URL', 'Workflow2')}</td>
        <td width="15"></td>
        <td class="dvtCellInfo" align="left" style="padding:5px;">
            <div class="insertTextfield" data-name="task[url]" data-id="subject">{$task.url}</div>
        </td>
    </tr>
    <tr>
        <td class="dvtCellLabel" align="right" width="25%">{vtranslate('Method', 'Workflow2')}</td>
        <td width="15"></td>
        <td class="dvtCellInfo" align="left" style="padding:5px;">
            <select class="chzn-select" name="task[method]" style="width:300px;">
                {html_options options=$webservice_methods selected=$task.method}
            </select>
        </td>
    </tr>
</table>

<br/>
<button type="button" onclick="addCol();" class="btn btn-primary">add Parameter</button>

<div id="rows"></div>

<script type="text/javascript">
    var cols = {$cols|@json_encode};
</script>