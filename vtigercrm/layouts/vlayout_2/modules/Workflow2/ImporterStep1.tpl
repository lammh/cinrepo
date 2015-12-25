<div class="modelContainer" style="width:450px;">
    <form method="POST" id="popupForm" action="index.php?module=Workflow2&view=ImportStep2" enctype="multipart/form-data">
        <div class="modal-header contentsBackground">
            <button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="{vtranslate('LBL_CLOSE')}">x</button>
            <h3>File Import</h3>
        </div>
        <div style="padding: 10px;">{* Content Start *}
            {if count($workflows) eq 0}
                <p>{vtranslate('HINT_NO_ACTIVE_IMPOR_WORKFLOWS', 'Workflow2')}</p>
            {/if}
            {vtranslate('LBL_PLEASE_CHOOSE_IMPORT_WORKFLOW', 'Workflow2')}
            <br/>
            <select id="import_process" name="import_process" class='chzn-select'>
            {foreach from=$workflows item=workflow}
                <option value='{$workflow.id}'>{$workflow.title}</option>
            {/foreach}
            </select>
            <br/>
            <br/>
            {vtranslate('LBL_PLEASE_CHOOSE_IMPORT_FORMAT', 'Workflow2')}<br />
            <select id="import_format" name="import_format" class='chzn-select'>
                <option value='csv'>CSV</option>
            </select>
            <br/>
            <br/>
            {vtranslate('LBL_PLEASE_CHOOSE_IMPORT_FILE', 'Workflow2')}<br />
            <input type="file" name="importfile" />

        </div> {* Content Ende *}
        <div class="modal-footer quickCreateActions">
                <a class="cancelLink cancelLinkContainer pull-right" type="reset" data-dismiss="modal">{vtranslate('LBL_CLOSE', 'Workflow2')}</a>
            <button class="btn btn-success" type="submit" disabled="disabled" id="modalSubmitButton" ><strong>{vtranslate('LBL_START', 'Workflow2')}</strong></button>
        </div>
    </form>
</div>

<script type="text/javascript">

</script>


