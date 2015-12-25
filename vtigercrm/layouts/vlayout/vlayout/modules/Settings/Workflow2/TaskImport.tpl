<div class="modelContainer" style="width:750px;">
    <form method="POST" id="popupForm" action="index.php?module=Workflow2&parent=Settings&action=TaskImport" enctype="multipart/form-data">
        <div class="modal-header contentsBackground">
            <button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="{vtranslate('LBL_CLOSE')}">x</button>
            <h3>{vtranslate('LBL_TASK_IMPORT_FILE', 'Settings:Workflow2')}</h3>
        </div>
        <div style="padding: 10px;">{* Content Start *}
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td class='dvtCellLabel' style="width:300px;">{vtranslate('LBL_CHOOSE_TASKFILE','Settings:Workflow2')}</td>
                    <td class='dvtCellInfo'><input type="file" name="file"></td>
                </tr>
                <tr>
                    <td class='dvtCellLabel'>{vtranslate('LBL_UPGRADE_EXISTING','Settings:Workflow2')}</td>
                    <td class='dvtCellInfo'><input type="checkbox" name="enableUpgrade"></td>
                </tr>
                <tr>
                    <td class='dvtCellLabel'>{vtranslate('LBL_UPGRADE_EVEN_OLDER','Settings:Workflow2')}</td>
                    <td class='dvtCellInfo'><input type="checkbox" name="enableDowngrade"></td>
                </tr>
            </table>
        </div> {* Content Ende *}
        <div class="modal-footer quickCreateActions">
                <a class="cancelLink cancelLinkContainer pull-right" type="reset" data-dismiss="modal">{vtranslate('LBL_CLOSE', $MODULE)}</a>
            <button class="btn btn-success" type="submit" disabled="disabled" id="modalSubmitButton" ><strong>{vtranslate('start import', $MODULE)}</strong></button>
        </div>
    </form>
</div>



