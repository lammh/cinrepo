
<div class="modelContainer" style="width:550px;">
    <form method="POST" id="WorkflowImportForm" action="index.php?module=Workflow2&parent=Settings&action=WorkflowImport" enctype="multipart/form-data">
<div class="modal-header contentsBackground">
	<button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="{vtranslate('LBL_CLOSE')}">x</button>
    <h3>Workflow Import</h3>
</div>
    <div style="padding: 10px;">

            <label>{vtranslate("LBL_SELECT_FILE", "Settings:Workflow2")}:</label>
            <input type="file" name="import">
            <label>{vtranslate("LBL_IMPORT_PASSWORD", "Settings:Workflow2")}: (optional)</label>
            <input type="text" id="password" name="password">
            <fieldset style="border: 1px solid #ccc;border-radius:5px;padding: 5px;">
                <label for="name">{vtranslate("new workflow name", "Settings:Workflow2")}:</label>
                <input type="text" id="workflow_name" name="workflow_name">
                <span>Leer lassen für Orignalnamen</span>
                <label for="name">{vtranslate("LBL_IMPORT_OVERWRITE_MODULE", "Settings:Workflow2")}:</label>
                <select name="workflow_module" id="workflow_module" disabled="disabled">
                    <option value="">Original module</option>
                    {foreach from=$modules item=label key=tabid}
                        <option value="{$label[0]}">{$label[1]}</option>
                    {/foreach}
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="workflow_checkbox" value="1" onclick="if(jQuery(this).prop('checked')) { jQuery('#workflow_module').removeAttr('disabled'); } else {  jQuery('#workflow_module').attr('disabled','disabled');  } ">
            {vtranslate("LBL_IMPORT_OVERWRITE_MODULE_ACTIVATE", "Settings:Workflow2")}
            </fieldset>
    </div>
    <div class="modal-footer quickCreateActions">
            <a class="cancelLink cancelLinkContainer pull-right" type="reset" data-dismiss="modal">{vtranslate('LBL_CLOSE', $MODULE)}</a>
        <button class="btn btn-success" type="submit" disabled="disabled" id="modalSubmitButton" ><strong>{vtranslate('start import', $MODULE)}</strong></button>
   	</div>
    </form>
</div>


