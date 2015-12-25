<div class="container-fluid" id="moduleManagerContents">

    <div class="widget_header row-fluid">
        <div class="span12">
            <h3>
                <b>
                    <a href="index.php?module=Workflow2&view=Index&parent=Settings">{vtranslate('Workflow2', 'Workflow2')}</a> &raquo;
                    {vtranslate('Frontend Manager', 'Settings:Workflow2')}
                </b>
                <div class="pull-right">
                    <select class="select2" id="addWorkflow" style="width:400px;" data-placeholder="{vtranslate('choose a Workflow','Settings:Workflow2')}">
                        <option value=""></option>
                        {foreach from=$workflows item=workflowList key=moduleName}
                            <optgroup label="{$moduleName}">
                                {foreach from=$workflowList item=workflow}
                                    <option value="{$workflow.id}">{$workflow.title}</option>
                                {/foreach}
                            </optgroup>
                        {/foreach}
                    </select>
                    <button type="submit" id="addWorkflowButton" class="btn btn-primary" style="margin-top:0;vertical-align:top;">{vtranslate('add Workflow', 'Settings:Workflow2')}</button>
                </div>
            </h3>
        </div>
    </div>
    <hr>

    <div>

        {foreach from=$links item=linkArray key=moduleName}
                <div class="blockHeader" style="overflow:hidden;line-height:30px;border-bottom:1px solid #ddd;" data-target="{$linkArray[0]['module_name']}">
                    <div style="float:left;font-weight:bold;text-transform:uppercase;">
                        <img src="modules/Workflow2/icons/toggle_minus.png" class="toggleImageCollapse toggleImage" style="display: none;" />
                        <img src="modules/Workflow2/icons/toggle_plus.png" class="toggleImageExpand toggleImage"/>

                        <b>&nbsp;{$moduleName} ({count($linkArray)})</b>
                    </div>
                </div>

        <table class="table frontendManagerTable" cellspacing="0" cellpadding="4" style="border-collapse:collapse;display:none;" id="workflowList{$linkArray[0]['module_name']}" >
            <thead>
            <tr style="background-color: #eee;">
                <th align="left"></th>
                <th align="left">Workflow</th>
                <th align="left">Label</th>
                <th align="left">Include Type</th>
                <th align="left">Button Color</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$linkArray item=link}
            <tr class="hoverTR" data-index="{$link.id}">
                <td>
                    <img src="modules/Workflow2/icons/delete.png" data-id="{$link.id}" class="removeFrontendManagerOnClick" width="16" />
                </td>
                <td>{$link.title}</td>
                <td><input type="text" class="saveOnBlur" data-field="label" data-id="{$link.id}" value="{$link.label}" style="margin-bottom:0;" /></td>
                <td>
                    <select data-field="position" data-id="{$link.id}" class="saveOnBlur" style="margin-bottom:0;">
                        <option value="none" {if $link.position eq 'none'}selected="selected"{/if}>hidden</option>
                        <option value="sidebar" {if $link.position eq 'sidebar'}selected="selected"{/if}>Sidebar Button</option>
                        {*<option value="more" {if $link.position eq 'more'}selected="selected"{/if}>More Menu</option>*}
                    </select>
                </td>
                <td>
                    <input type="text" style="margin-bottom:0;{if $link.position neq 'sidebar'}display: none;{/if}" id="config_{$link.id}_color"  class="saveOnBlur color {ldelim}hash:true}" data-field="color" data-id="{$link.id}" value="{if $link.color eq ''}#3D57FF{else}{$link.color}{/if}" />
                </td>
            </tr>
            {/foreach}
            </tbody>
            <tfoot>
            <tr style="background-color: #eee;">
                <td></td>
                <td colspan="4">{vtranslate('disable complete Workflow List in Sidebar', 'Settings:Workflow2')}:&nbsp;&nbsp;&nbsp;<input type="checkbox" class="SaveConfigOnBlur" data-field="hide_listview" data-module="{$linkArray[0]['module_name']}" {if isset($frontendConfig[$linkArray[0]['module_name']]) && $frontendConfig[$linkArray[0]['module_name']]['hide_listview'] eq '1'}checked="checked"{/if} /> </td>
            </tr>
            </tfoot>
        </table>
        {/foreach}

    </div>
</div>