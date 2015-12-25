<div class="StatistikPopup" id="StatistikPopup">
    <form method="POST" action="#">
        <input type="hidden" id="save_workflow_id" name="editID" value="{$DATA.id}">
        <table class="tableHeading" width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td class="big dragHandle" nowrap="nowrap" >
                    <strong>Auswertung dieses Blockes</strong>
                </td>
                <td class="small" align="right">
                    {*<input type="button" data-url="index.php?module=Workflow2&view=StatistikPopup&parent=Settings&id={$taskId}&execId={$execId}" onclick="openAsPopup(this);" class="crmbutton small cancel" value="Popup">*}
                    <input type="button" id="edittask_cancel_button" onclick="app.hideModalWindow();" class="crmbutton small cancel" value="Schlie&szlig;en">
                </td>
            </tr>
        </table>
        <div style='height:160px;margin:auto;' id="durationBlock"></div>
        <script type="text/javascript">
          var durations = {$durations|@json_encode};
          var maxValue = {$maxValue};
        </script>
        <div style='height:160px;margin:auto;' id="extraLogInformation">
            {$LogInformation}
        </div>
</div>