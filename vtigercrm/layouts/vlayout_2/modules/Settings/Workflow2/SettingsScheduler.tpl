<div class="container-fluid" id="moduleManagerContents">

    <div class="widget_header row-fluid">
        <div class="span12">
            <h3>
                <b>
                    <a href="index.php?module=Workflow2&view=Index&parent=Settings">{vtranslate('Workflow2', 'Workflow2')}</a> &raquo;
                    {vtranslate('LBL_SETTINGS_SCHEDULER','Settings:Workflow2')}
                </b>
            </h3>
        </div>
    </div>
    <hr>

<br>
{if count($schedules) eq 0}
No Schedules configured
{else}
<table cellspacing="0" class='table'>
    <tr class="listViewHeaders">
        <th></th>
        <th>Workflow</th>
        <th>Minute</th>
        <th>Hour</th>
        <th>Day of Month</th>
        <th>Month</th>
        <th>Day Of Week</th>
        <th>Year</th>
        <th></th>
    </tr>

{foreach from=$schedules item=cron}
    <tr class="cronRow cronRow_{$cron.id}">
        <td style="width: 100px;vertical-align: middle;">
            <select data-sid='{$cron.id}' data-field='active' style="width: 100px;margin: 0;">
                <option value='0' {if $cron.active eq '0'}selected="selected"{/if}>Inactive</option>
                <option value='1' {if $cron.active eq '1'}selected="selected"{/if}>Active</option>
            </select>
        </td>
        <td style="width: 300px;vertical-align: middle;">
            <select data-sid='{$cron.id}' data-field='workflow_id' class="select2" style="width: 300px;">
                <option value='0' {if $workflow_id eq '0'}selected="selected"{/if}>no Workflow</option>
                {foreach from=$workflows item=module key=module_name}
                    <optgroup label='{$module_name}'>
                    {foreach from=$module item=workflow key=workflow_id}
                        <option value='{$workflow_id}' {if $workflow_id eq $cron.workflow_id}selected="selected"{/if}>{$workflow}</option>
                    {/foreach}
                    </optgroup>
                {/foreach}
            </select>
        </td>
        <td style="vertical-align: middle;"><input type="text" style='width: 80px;margin: 0;' data-sid='{$cron.id}' data-field='minute' value='{$cron.minute}'></td>
        <td style="vertical-align: middle;"><input type="text" style='width: 80px;margin: 0;' data-sid='{$cron.id}' data-field='hour' value='{$cron.hour}'></td>
        <td style="vertical-align: middle;"><input type="text" style='width: 80px;margin: 0;' data-sid='{$cron.id}' data-field='dom' value='{$cron.dom}'></td>
        <td style="vertical-align: middle;"><input type="text" style='width: 80px;margin: 0;' data-sid='{$cron.id}' data-field='month' value='{$cron.month}'></td>
        <td style="vertical-align: middle;"><input type="text" style='width: 80px;margin: 0;' data-sid='{$cron.id}' data-field='dow' value='{$cron.dow}'></td>
        <td style="vertical-align: middle;"><input type="text" style='width: 80px;margin: 0;' data-sid='{$cron.id}' data-field='year' value='{$cron.year}'></td>
        <td style="width: 60px;vertical-align: middle;"><a href="#" onclick='Scheduler.delScheduler({$cron.id});return false;'>delete</a></td>
    </tr>
{/foreach}
</table>
{/if}
<strong>To enter specific hours, you need to use UTC timezone! (Current time in UTC: {$currentUTCTime})</strong>
<br/>
<br/>
<button class='btn btn-primary' type="button" onclick='Scheduler.newScheduler();'><strong>New Entry</strong></button>
</div>