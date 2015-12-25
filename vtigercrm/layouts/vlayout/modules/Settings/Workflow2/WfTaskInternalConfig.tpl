{foreach key=title item=GROUP from=$CONFIG_FIELDS}
{if $title neq ""}
<table class="tableHeading" border="0"  width="100%" cellspacing="0" cellpadding="5">
	<tr>
		<td class="big" nowrap="nowrap">
              <strong>{$title}</strong>
		</td>
	</tr>
</table>
{/if}

<table border="0" cellpadding="5" cellspacing="0" width="100%" class="small">
    {foreach item=field from=$GROUP}
        <tr>
            <td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$field.label}:</td>
            <td class='dvtCellInfo'>
                {if $field.type eq "templatefield"}
                    <div class="insertTextfield" style="display: inline-block;" data-name="task[{$field.key}]" data-id="{$field.key}">{$task[$field.key]}</div>
                {elseif $field.type eq "templatearea"}
                    <script type="text/javascript">document.write(createTemplateTextarea("task[{$field.key}]", "btn_accept", "{$task[$field.key]|replace:"\"":"\\\""}"));</script>
                {elseif $field.type eq "datefield"}
                    <script type="text/javascript">document.write(createTemplateDatefield("task[{$field.key}]", "btn_accept", "{$task[$field.key]|replace:"\"":"\\\""}"));</script>
                {elseif $field.type eq "envvar"}
                    $env[<input type="text"name="task[{$field.key}]" value="{$task[$field.key]|htmlentities}">]
                {/if}
            </td>
        </tr>
    {/foreach}
</table>

{/foreach}
