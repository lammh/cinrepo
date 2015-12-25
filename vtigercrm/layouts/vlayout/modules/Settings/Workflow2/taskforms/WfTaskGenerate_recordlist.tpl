<input type="hidden" name="changeModule" id="changeModule" value="0" />

This Block works only if you previously store some recordIds in the Environment. (Could be done in 'global Search' or 'exist Related record')
<table width="100%" cellspacing="0" cellpadding="5" class="newTable">
    <tr>
        <td class="dvtCellLabel" align="right" width="25%">Records are in Module</td>
        <td class="dvtCellInfo" align="left">
                <select class="chzn-select" name='task[search_module]' style="width:250px;" onchange="jQuery('#changeModule').val('1');jQuery('#save').trigger('click');">
                    <option {if $related_tabid == 0}selected='selected'{/if} value="0">{$MOD.LBL_CHOOSE}</option>
                {foreach from=$related_modules item=module key=tabid}
                    <option {if $related_tabid == $tabid}selected='selected'{/if} value="{$module.0}#~#{$tabid}">{$module.1}</option>
                {/foreach}
                </select>
        </td>
    </tr>
    <tr>
        <td class="dvtCellLabel" align="right" width="25%">Environment ID of records</td>
        <td class="dvtCellInfo" align="left">
            <input type='text' name='task[envId]' class="textfield" id='envId' value="{$task.envId}" style="width:350px;">
        </td>
    </tr>
    {if $task.envId neq ''}
    <tr>
        <td class="dvtCellLabel" align="right" width="25%">to use this list use the following function:</td>
        <td class="dvtCellInfo" align="left" style="font-family: 'Courier New'">
            ${ldelim} return wf_recordlist("{$task.envId}"); {rdelim}{rdelim}>
        </td>
    </tr>
    <tr>
        <td class="dvtCellLabel" align="right" width="25%">To include this list into PDFMaker use:</td>
        <td class="dvtCellInfo" align="left" style="font-family: 'Courier New'">
            [CUSTOMFUNCTION|pdfmaker_recordlist|{$task.envId}|CUSTOMFUNCTION]
        </td>
    </tr>
    {/if}
</table>
<div id="fieldlist"></div>
<input type="button" class="btn btn-primary" onclick="addField()" value="add Field" />
<div id="staticFieldsContainer" style="display:none;">
    <div style="margin:0px 0px;" id="setterRow_##SETID##">
        <select style="vertical-align:top;width:300px;" name='task[{$StaticFieldsField}][##SETID##][field]' id='staticfields_##SETID##_field'>
            <option value=''>{vtranslate('LBL_CHOOSE', 'Workflow2')}</option>
            <option value=';;;delete;;;' class='deleteRow'>{vtranslate('LBL_DELETE_SET_FIELD', 'Workflow2')}</option>
            <option value='link'>{vtranslate('Link to the Record', 'Workflow2')}</option>
            {foreach from=$fromFields key=label item=block}
                <optgroup label="{$label}">
                {foreach from=$block item=field}
                    {if $field->name neq "smownerid"}
                        <option value='${$field->name}'>{$field->label}</option>
                    {else}
                        <option value='$assigned_user_id'>{$field->label}</option>
                    {/if}
                {/foreach}
                </optgroup>
            {/foreach}
        </select>

        <input type='text' style="margin-bottom:0;" id='staticfields_##SETID##_label' name='task[{$StaticFieldsField}][##SETID##][label]' value='' placeholder="Headline of the column" />
        <input type='text' style="margin-bottom:0;" id='staticfields_##SETID##_width' name='task[{$StaticFieldsField}][##SETID##][width]' value='' placeholder="Width of the column" />
    </div>
</div>
<script type="text/javascript">jQuery(function() { initRecordListFields({$fields|@json_encode}); });</script>