<table border="0" cellpadding="5" cellspacing="0" width="100%" class="small">
    <tr>
   		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{vtranslate('LBL_CALENDAR', 'Settings:Workflow2')}:</td>
   		<td class='dvtCellInfo'>
               {html_options name="task[calendar]" options=$calendar selected=$task.calendar}
   		</td>
   	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{vtranslate('LBL_EVENT_TITLE', 'Settings:Workflow2')}:</td>
		<td class='dvtCellInfo'>
            <input type="text" name="task[eventtitle]" id="eventtitle" value="{$task.eventtitle|@htmlentities}">
            <img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-7px;cursor:pointer;' onclick="insertTemplateField('eventtitle')">
		</td>
	</tr>

    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{vtranslate('LBL_EVENT_DESCR', 'Settings:Workflow2')}:</td>
		<td class='dvtCellInfo'>
            <textarea name="task[eventdescr]" id="eventdescr">{$task.eventdescr}</textarea>
            <img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-7px;cursor:pointer;' onclick="insertTemplateField('eventdescr')">
		</td>
	</tr>

    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{vtranslate('LBL_EVENT_START_DATE', 'Settings:Workflow2')}:</td>
		<td class='dvtCellInfo'>
            <input type="text" name="task[eventstartdate]" id="eventstartdate" value="{$task.eventstartdate|@htmlentities}">
            <img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-7px;cursor:pointer;' onclick="insertTemplateField('eventstartdate')">
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{vtranslate('LBL_EVENT_START_TIME', 'Settings:Workflow2')}:</td>
		<td class='dvtCellInfo'>
            <input type="text" name="task[eventstarttime]" id="eventstarttime" value="{$task.eventstarttime|@htmlentities}">
            <img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-7px;cursor:pointer;' onclick="insertTemplateField('eventstarttime')">
		</td>
	</tr>
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{vtranslate('LBL_EVENT_DURATION', 'Settings:Workflow2')}:</td>
		<td class='dvtCellInfo'>
            <input type="text" name="task[eventduration]" id="eventduration" value="{$task.eventduration|@htmlentities}">
            <img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-7px;cursor:pointer;' onclick="insertTemplateField('eventduration')">
		</td>
	</tr>
    <tr>
   		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{vtranslate('LBL_PRIVACY', 'Settings:Workflow2')}:</td>
   		<td class='dvtCellInfo'>
               {html_options name="task[privacy]" options=$privacySettings selected=$task.privacy}
   		</td>
   	</tr>

<!--    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">{$tMOD.LBL_EVENT_END_TIME}:</td>
		<td class='dvtCellInfo'>
            <input type="checkbox" name="task[createrel]" value="1" {if $task.createrel eq "1"}checked='checked'{/if}>
		</td>
	</tr>
-->
</table>
