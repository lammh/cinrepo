<p>
    {vtranslate('LBL_PUSHOVER_INTRO','Settings:Workflow2')}
</p>
<div>
    <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="dvtCellLabel" align="right">{vtranslate('LBL_PUSHOVER_USERID','Settings:Workflow2')}</td>
                <td class="dvtCellInfo" align="left" style="padding:5px;">
                    <div class="insertTextfield" data-name="task[userkey]" data-id="number">{$task.userkey}</div>
                </td>
            </tr>
        <tr>
            <td class="dvtCellLabel" align="right">{vtranslate('LBL_PUSHOVER_TARGET_DEVICE','Settings:Workflow2')} (default=all)</td>
            <td class="dvtCellInfo" align="left" style="padding:5px;">
                <div class="insertTextfield" data-name="task[device]" data-id="number">{$task.device}</div>
            </td>
        </tr>

            <tr>
                <td class="dvtCellLabel" align="right">{vtranslate('LBL_EMAIL_SUBJECT','Settings:Workflow2')}</td>
                <td class="dvtCellInfo" align="left" style="padding:5px;">
                    <div class="insertTextfield" data-name="task[subject]" data-id="number">{$task.subject}</div>
                </td>
            </tr>
            <tr>
                <td class="dvtCellLabel" align="right">{vtranslate('LBL_MESSAGE','Settings:Workflow2')}</td>
                <td class="dvtCellInfo" align="left" style="padding:5px;">
                    <div class="insertTextarea" data-name="task[content]" data-id="sms_text">{$task.content|@stripslashes}</div>
                </td>
            </tr>
        <tr>
            <td colspan=2><br/><br/></td>
        </tr>
        <tr>
            <td class="dvtCellLabel" align="right"><em>{vtranslate('LBL_PUSHOVER_APPKEY_OPTIONAL','Settings:Workflow2')}</em></td>
            <td class="dvtCellInfo" align="left" style="padding:5px;">
                <div class="insertTextfield" data-name="task[appkey]" data-id="number">{$task.appkey}</div>
            </td>
        </tr>

            <tr>
                <td class="dvtCellLabel" align="right"></td>
                <td class="dvtCellInfo" align="left" style="padding:5px;" id="buchstabenCounter">
                </td>
            </tr>

    </table>
</div>
