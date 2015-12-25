<input type="hidden" name="task[{$field}_version]" value="2" />
<div id="formgenerator"></div>

<script type="text/javascript">
    var FGDAT = {};
    var fieldTypes = {$fieldTypes|@json_encode};

    jQuery(function() {
        initFormGenerator('formgenerator', '{$field}', {$formFields|@json_encode});
    });
</script>

{foreach from=$fields item=fieldConfig}
    <script type="text/template" id="fieldtemplate_{$fieldConfig.id|@strtolower}">
            <table>
            {foreach from=$fieldConfig.config key=variable item=config}
                <tr>
                    <td style='padding:0;border-top:1px solid #fff;padding-right:5px;line-height:29px;'>{vtranslate($config.label, 'Settings:Workflow2')}:</td>
                    <td style='padding:0;border-top:1px solid #fff;'>
                        {if $config.type eq 'templatefield'}
                            <div class="configField insertTextfield" data-style="width:{$width - 320}px;" data-type="{$config.type}" data-name="task[{$field}][##FIELDNAME##][config][{$variable}]" data-id="task_{$field}_##FIELDNAME##_config_{$variable}" data-placeholder="{$config.placeholder}"></div>
                        {elseif $config.type eq 'templatearea'}
                            <textarea class="configField" name="task[{$field}][##FIELDNAME##][config][{$variable}]"  data-id="task_{$field}_##FIELDNAME##_config_{$variable}" id="task_{$field}_##FIELDNAME##_config_{$variable}" data-type="{$config.type}" style="width:{$width - 250}px;"></textarea>
                            <img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-7px;cursor:pointer;' onclick="insertTemplateField('task_{$field}_##FIELDNAME##_config_{$variable}')">
                        {elseif $config.type eq 'picklist'}
                            <select name="task[{$field}][##FIELDNAME##][config][{$variable}]" data-nomodify="{$config.nomodify}" data-variable="{$variable}" data-id="task_{$field}_##FIELDNAME##_config_{$variable}" id="task_{$field}_##FIELDNAME##_config_{$variable}" data-type="{$config.type}" class="configField" >
                                {html_options options=$config.options}
                            </select>
                        {elseif $config.type eq 'condition'}
                            <input type="hidden" class="configField" name="task[{$field}][##FIELDNAME##][config][{$variable}]" data-variable="{$variable}" data-id="task_{$field}_##FIELDNAME##_config_{$variable}" id="task_{$field}_##FIELDNAME##_config_{$variable}" data-type="hidden" />
                            <button class="btn btn-primary" type="button" onclick="ConditionPopup.open('#task_{$field}_##FIELDNAME##_config_{$variable}', '#task_{$field}_##FIELDNAME##_config_{$config.moduleField}', 'LBL_FILTER_RECORDS_2_SELECT');">{vtranslate('Bedingung', 'Settings:Workflow2')}</button>
                        {elseif $config.type eq 'checkbox'}
                            <input class="configField" type="checkbox" data-type="{$config.type}" data-variable="{$variable}" name="task[{$field}][##FIELDNAME##][config][{$variable}]"  data-id="task_{$field}_##FIELDNAME##_config_{$variable}" id="task_{$field}_##FIELDNAME##_config_{$variable}" value="{$config.value}">
                        {/if}
                    </td>
                </tr>
            {/foreach}
            </table>
    </script>
{/foreach}