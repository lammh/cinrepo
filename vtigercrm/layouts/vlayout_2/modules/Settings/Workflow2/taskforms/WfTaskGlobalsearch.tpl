<div>
    <table width="100%" cellspacing="0" cellpadding="5" class="newTable">
        <tr>
            <td class="dvtCellLabel" align="right" width="25%">{$MOD.LBL_SEARCH_IN_MODULE}</td>
            <td class="dvtCellInfo" align="left">
                    <select class="chzn-select" name='task[search_module]' style="width:250px;" onchange="jQuery('#search_module_hidden').val(jQuery(this).val());document.forms['hidden_search_form'].submit();">
                        <option {if $related_tabid == 0}selected='selected'{/if} value="0">{$MOD.LBL_CHOOSE}</option>
                    {foreach from=$related_modules item=module key=tabid}
                        <option {if $related_tabid == $tabid}selected='selected'{/if} value="{$module.0}#~#{$tabid}">{$module.1}</option>
                    {/foreach}
                    </select>
            </td>
        </tr>
        <tr>
            <td class="dvtCellLabel" align="right" width="15%">{$MOD.LBL_FOUND_ROWS}</td>
            <td class="dvtCellInfo" align="left">
                <input type='text' name='task[found_rows]' class="textfield" id='found_rows' value="{$task.found_rows}" style="width:50px;">
            </td>
        </tr>
        <tr>
            <td class="dvtCellLabel" align="right" width="15%">{vtranslate('Store result Records<br>in the following Environment Variable')}</td>
            <td class="dvtCellInfo" align="left">
                <input type='text' name='task[resultEnv]' class="textfield" id='found_rows' value="{$task.resultEnv}" style="width:350px;">
            </td>
        </tr>
    </table>
</div>

{if !empty($related_tabid)}
{$conditionalContent}

<script type="text/javascript">
    var deposit = document.getElementById("found_rows");

    deposit.onkeyup = function() {ldelim}
        var PATTERN = /\d$/;

        if (!deposit.value.match(PATTERN)) {ldelim}
            deposit.value = deposit.value.replace(deposit.value.slice(-1), "");
        {rdelim}
    {rdelim}
</script>
{/if}
</form>

<form method="POST" name="hidden_search_form" action="#" onsubmit="">
    <input type="hidden" name="task[search_module]" id='search_module_hidden' value=''>
</form>