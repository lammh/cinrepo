<script src="modules/Workflow2/resources/emailtaskscript.js?v={$smarty.const.WORKFLOW2_VERSION}" type="text/javascript" charset="utf-8"></script>
<script src="modules/Workflow2/resources/newemailworkflow.js?v={$smarty.const.WORKFLOW2_VERSION}" type="text/javascript" charset="utf-8"></script>
{foreach from=$attachmentsJAVASCRIPT item=script}<script type="text/javascript">{$script}</script>{/foreach}
<div style="float:right;width:350px;border: 1px solid #eeeeee;padding:5px;">
    <fieldset>
        <legend style="font-size: 14px;font-weight: bold;margin-bottom:10px;line-height: 24px;">{vtranslate('E-Mail attachments','Settings:Workflow2')}</legend>
        <div>
        {if count($available_attachments) > 0}
                <div>Add Attachment</div>
                <select id="task-template" class="chzn-select" style="width:300px;">
                    <option value="">none</option>
                    {if count($available_attachments) > 0}
                        {foreach from=$available_attachments item=group key=title}
                        <optgroup label="{$title}">
                            {html_options options=$group}
                        </optgroup>
                        {/foreach}
                    {/if}
                </select>
                <img src='modules/Workflow2/icons/add.png' style="margin-top:2px;cursor:pointer;" onclick="addPDFTemplate();" height=20>
            {/if}

            <input type="hidden" id="task-attachments" name="task[attachments]" value="">
            <div id='mail_files' style="margin-top:5px;"></div>
            {$attachmentsHTML}
<!--            <a href="#" onclick="attachURLFile(); ">Attach File from URL</a><br>-->
        </div>

    </fieldset>
</div>
<table border="0" cellpadding="5" cellspacing="0" style="width:600px;float:left;" class="small newTable">
    {if $from.from_readonly == true}
    <tr>
      <td class='dvtCellLabel' align="right" width=15% nowrap="nowrap">* From Name</td>
      <td class='dvtCellInfo'>{$from.from_name}<input type="hidden" name="task[from_name]" value="" id="save_from_name" class="form_input" style='width: 250px;'></td>
    </tr>
    <tr>
      <td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><b><font color=red>*</font> From Mail</b></td>
      <td class='dvtCellInfo'>{$from.from_mail}<input type="hidden" name="task[from_mail]" value="" id="save_from_mail" class="form_input" style='width: 250px;'></td>
    </tr>
    {else}
        <tr>
          <td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><b><span style='color:red;'>*</span> {vtranslate('LBL_SENDER_NAME', 'Settings:Workflow2')}</b></td>
          <td class='dvtCellInfo'>
              <div class="insertTextfield" data-name="task[from_name]" data-id="from_name">{$task.from_name}</div>
        </tr>
        <tr>
          <td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><b><span style='color:red;'>*</span> {vtranslate('LBL_SENDER_MAIL', 'Settings:Workflow2')}</b></td>
          <td class='dvtCellInfo'>
              <div class="insertTextfield" data-name="task[from_mail]" data-id="from_mail" data-options='{ldelim}"type":"email","delimiter":","{rdelim}'>{$task.from_mail}</div>
        </tr>
    {/if}
    <tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><b><span style='color:red;'>*</span> {vtranslate('LBL_EMAIL_RECIPIENT', 'Settings:Workflow2')}</b></td>
		<td class='dvtCellInfo'>
            <div class="insertTextfield" data-name="task[recepient]" data-id="recepient" data-options='{ldelim}"type":"email","delimiter":","{rdelim}'>{$task.recepient}</div>
            <a href="#" onclick="jQuery(this).hide();jQuery('#cc_row').show();" style="{if $task.emailcc neq ''}display: none;{/if}padding-right:30px;">CC</a><a href="#"  onclick="jQuery(this).hide();jQuery('#bcc_row').show();" style="{if $task.emailbcc neq ''}display: none;{/if}">BCC</a>
	</tr>
	<tr id="cc_row"  style="{if $task.emailcc eq ''}display: none;{/if}">
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><b> {vtranslate('LBL_EMAIL_CC', 'Settings:Workflow2')}</b></td>
		<td class='dvtCellInfo'>
            <div class="insertTextfield" data-name="task[emailcc]" data-id="emailcc" data-options='{ldelim}"type":"email","delimiter":","{rdelim}'>{$task.emailcc}</div>
	</tr>
	<tr id="bcc_row" style="{if $task.emailbcc eq ''}display: none;{/if}">
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><b> {vtranslate('LBL_EMAIL_BCC', 'Settings:Workflow2')}</b></td>
		<td class='dvtCellInfo'>
            <div class="insertTextfield" data-name="task[emailbcc]" data-id="emailbcc" data-options='{ldelim}"type":"email","delimiter":","{rdelim}'>{$task.emailbcc}</div>
            {if $bccs|@count gt 0}+ {$bccs|@count} Empfänger{/if}-
        </td>
	</tr>
	<tr id="storeid_row" style="{if $task.storeid eq ''}display: none;{/if}">
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><b> {vtranslate('Store Mail to', 'Settings:Workflow2')}</b></td>
		<td class='dvtCellInfo'>
            <div class="insertTextfield" style="display:inline;" data-name="task[storeid]" data-id="storeid" data-options='{ldelim}"type":"text","delimiter":","{rdelim}'>{$task.storeid}</div>
            <a href="http://shop.stefanwarnat.de/?wf-docu=sendmail#store_mail_to" target="_blank">
                <img src='modules/Workflow2/icons/question.png' border="0">
            </a>

        </td>
	</tr>
	<tr>
		<td class='dvtCellLabel' align="right" width=15% nowrap="nowrap"><b><span style='color:red;'>*</span> {vtranslate('LBL_EMAIL_SUBJECT', 'Settings:Workflow2')}</b></td>
		<td class='dvtCellInfo'>
            <div class="insertTextfield" data-name="task[subject]" data-id="subject">{$task.subject}</div>
        </td>
	</tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="small">
	<tr>
		<td style='padding-top: 10px;width:180px;'>
            <input type="hidden" id="templateVarContainer" value="" />
            <input type="button" class="btn btn-primary" value="{vtranslate('insert Fieldcontent', 'Settings:Workflow2')}" id="btn_insert_variable">
		</td>
        <td style="padding-top: 10px;">
            <ul class="nav nav-pills" style="margin-bottom: 0;{if $task.storeid neq ''}display: none;{/if}">
                          <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                Options
                                <b class="caret"></b>
                              </a>
                            <ul class="dropdown-menu">
                              <li style="{if $task.storeid neq ''}display: none;{/if}"><a href="#" onclick="jQuery(this).hide();jQuery('#storeid_row').show();">change Record to this mail will be stored</a></li>
                            </ul>
                          </li>
                        </ul>

        </td>
        <td>
            <label style="line-height:28px;padding-top:10px;">
                <input style="display:inline;" type="checkbox" name="task[trackAccess]" {if $task.trackAccess eq '1'}checked="checked"{/if} value="1"/>
                {vtranslate('integrate VtigerCRM Access Tracker', 'Settings:Workflow2')}
            </label>
        </td>
		<td style='padding-top: 10px;'>
            <div style="float: right;">
                <b>{vtranslate('LBL_SELECT_MAIL_TEMPLATE', 'Settings:Workflow2')}&nbsp</b>
                <select name="task[mailtemplate]" class="span4 chzn-select">
                    <option value="">- none -</option>
                    {foreach from=$MAIL_TEMPLATES item=category key=title}
                        <optgroup label="{$title}">
                            {foreach from=$category item=templatename key=templateid}
                                <option value="{$templateid}" {if $task.mailtemplate eq $templateid}selected="selected"{/if}>{$templatename}</option>
                            {/foreach}
                        </optgroup>
                    {/foreach}
                </select>
                <a href="http://shop.stefanwarnat.de/?wf-docu=sendmail" target="_blank">
                    <img src='modules/Workflow2/icons/question.png' border="0">
                </a>
            </div>
		</td>
	</tr>
</table>	
<table>
	<tr>
		<td>&nbsp</td>
	</tr>	
	<tr>
		<td><b>{vtranslate('LBL_MESSAGE', 'Settings:Workflow2')}:</b></td>
	</tr>
</table>
<script type="text/javascript" src="libraries/jquery/ckeditor/ckeditor.js"></script>

<textarea style="width:90%;height:200px;" name="task[content]" rows="55" cols="40" id="save_content" class="detailedViewTextBox"> {$task.content} </textarea>

<script type="text/javascript" defer="1">
	var textAreaName = 'save_content';

    CKEDITOR.config.protectedSource.push(/<\?php[\s\S]*?\?>/g);

	CKEDITOR.replace( textAreaName,	{ldelim}
		extraPlugins : 'uicolor',
		uiColor: '#dfdff1',
        protectedSource: [/<tex[\s\S]*?\/tex>/g]
	{rdelim} );

	var oCKeditor = CKEDITOR.instances[textAreaName];
    var available_attachments = {$jsAttachmentsList|@json_encode};
    var attachmentFiles = {$task.attachments};
</script> 
