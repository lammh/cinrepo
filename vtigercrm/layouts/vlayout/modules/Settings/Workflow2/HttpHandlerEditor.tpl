<div class="modelContainer" style="width:750px;">
    <form method="POST" id="popupForm" action="index.php?module=Workflow2&parent=Settings&action=HttpHandlerSave" enctype="multipart/form-data">
        <div class="modal-header contentsBackground">
            <button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="{vtranslate('LBL_CLOSE')}">x</button>
            <h3>Http Handler Editor</h3>
        </div>
        <div style="padding: 10px;">{* Content Start *}
            <input type="hidden" name="edit_id" value='{$editId}'/>
            <table cellspacing="0" cellpadding="0" border="0" align="center" width="98%">
            <tr>
                    <td width="100%" valign="top" style="padding: 10px;" class="showPanelBg">
                        <br>
                        <div class="settingsUI" style="width:95%;padding:10px;margin-left:10px;">
                            <div style="background-color:#ffffff;padding:0px;">
                                    <table cellpadding="0" cellspacing="0">
                                        <tr style="height:25px;">
                                            <td class='dvtCellLabel' style="width:200px;">{vtranslate('LBL_HTTP_LIMIT_URL','Settings:Workflow2')}<br/><br/></td>
                                            <td class='dvtCellInfo' onclick="jQuery(this).selectText();">{$limitData.url}<br/><br/></td>
                                        </tr>
                                        <tr style="height:25px;">
                                            <td class='dvtCellLabel' style="width:200px;">{vtranslate('LBL_HTTP_LIMIT_EDITOR_NAME','Settings:Workflow2')}</td>
                                            <td class='dvtCellInfo'><input type="text" name="limit_name" value="{$limitData.name}" style="width:300px;"></td>
                                        </tr>
                                        <tr>
                                            <td class='dvtCellLabel' style="width:200px;">{vtranslate('LBL_HTTP_LIMIT_EDITOR_IPS','Settings:Workflow2')}</td>
                                            <td class='dvtCellInfo'><textarea area name="limit_ips" style="width:300px;height:100px;">{$ips|implode:"\n"}</textarea></td>
                                        </tr>
                                        <tr style="">
                                            <td class='dvtCellLabel' style="width:200px;">{vtranslate('LBL_HTTP_LIMIT_EDITOR_TRIGGERS','Settings:Workflow2')}</td>
                                            <td class='dvtCellInfo'><select style='width:400px;' multiple="multiple" id="values_trigger" name='values[trigger][]' class='select2'>{foreach from=$trigger item=value key=key}<option value='{$key}'>{$value}</option>{/foreach}</select></td>
                                        </tr>
                                        <tr style="">
                                            <td class='dvtCellLabel' style="width:200px;">{vtranslate('LBL_HTTP_LIMIT_EDITOR_WORKFLOWS','Settings:Workflow2')}</td>
                                            <td class='dvtCellInfo'><select class="select2" style='width:400px;' id="values_workflow" multiple="multiple" name='values[workflow][]'>{foreach from=$workflows item=value key=key}<option value='{$key}'>{$value}</option>{/foreach}</select></td>
                                        </tr>
                                    </table>
                            </div>
                        </div>
                     </td>
             </tr>
            </table>


        </div> {* Content Ende *}
        <div class="modal-footer quickCreateActions">
                <a class="cancelLink cancelLinkContainer pull-right" type="reset" data-dismiss="modal">{vtranslate('LBL_CLOSE', $MODULE)}</a>
            <button class="btn btn-success" type="submit" disabled="disabled" id="modalSubmitButton" ><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
        </div>
    </form>
</div>

<script type="text/javascript">

    var limit_values = {$values|json_encode};

</script>


