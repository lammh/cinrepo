<div id="FileAction{$field}Chooser" style="width:{$width}px;">
    <select name="task[{$field}][option]" id="checkAction_{$field}" class="select2" style="width:100%;" data-placeholder="{vtranslate('What to to with the file', 'Workflow2')}">
    <option value=""></option>
    {foreach from=$availableFileActions key=index item=action}
        <option value="{$action.id}" {if $task.$field.option eq $action.id}selected="selected"{/if} >
            {vtranslate($action.title, 'Settings:Workflow2')}
        </option>
    {/foreach}
    </select>
    <br/>
    {foreach from=$availableFileActions key=index item=action}
        <div class="FileActionsContainer FileActionContainer_{$action.id}" style="{if $task.$field.option neq $action.id}display: none;{/if}width:{$width}px;">
            {if count($action.options) > 0}
            <fieldset>
                <legend style="font-size: 13px; font-weight:bold; margin-bottom: 0;">{vtranslate('configure', 'Settings:Workflow2')} "{vtranslate($action.title, 'Settings:Workflow2')}"</legend>
                <table style="width:100%;">
                    {foreach from=$action.options key=variable item=config}
                        <tr>
                            <td style="width:200px;">{vtranslate($config.label, 'Settings:Workflow2')}:</td>
                            <td>
                                {if $config.type eq 'templatefield'}
                                    <div class="insertTextfield" data-style="width:{$width - 320}px;" data-name="task[{$field}][config][{$variable}]" data-id="{$field}_{$variable}" data-placeholder="{$config.placeholder}">{$task.$field.config.$variable}</div>
                                {elseif $config.type eq 'templatearea'}
                                    <textarea name="task[{$field}][config][{$variable}]" id="{$field}_{$variable}" style="width:{$width - 250}px;">{$task.$field.config.$variable}</textarea>
                                    <img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-7px;cursor:pointer;' onclick="insertTemplateField('{$field}_{$variable}')">
                                {elseif $config.type eq 'picklist'}
                                    <select name="task[{$field}][config][{$variable}]" id="{$field}_{$variable}">
                                        {html_options options=$config.options selected=$task.$field.config.$variable}
                                    </select>
                                {elseif $config.type eq 'checkbox'}
                                    <input type="checkbox" name="task[{$field}][config][{$variable}]" value="{$config.value}" {if $task.$field.config.$variable eq $config.value}checked='checked'{/if}>
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </fieldset>
            {/if}

        </div>
    {/foreach}
</div>


<script type="text/javascript">jQuery(function() { FileActions.init("{$field}"); });</script>