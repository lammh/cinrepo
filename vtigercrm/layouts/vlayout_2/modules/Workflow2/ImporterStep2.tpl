<div class="container-fluid" id="moduleManagerContents">

    <div class="widget_header row-fluid">
        <div class="span12">
            <h3>
                <b>
                    File Import Step 2/3
                </b>
            </h3>
        </div>
    </div>
    <hr />
    <form method="POST" action="#" class="form-horizontal">
        <input type="hidden" name="import_process" id="import_process" value="{$import_process}" />
        <input type="hidden" name="import_hash" id="import_hash" value="{$hash}" />
        <div class="control-group">
            <label class="control-label" for="inputDelimiter">Delimiter</label>
            <div class="controls">
              <input type="text" id="inputDelimiter" name="param[delimiter]" placeholder="Delimiter" value="{$importParams.delimiter}"> Default: ,
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputDelimiter">skip first row</label>
            <div class="controls">
              <input type="checkbox" id="skipfirst" name="param[skipfirst]" {if $importParams.skipfirst eq '1'}checked="checked"{/if} value="1">
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <strong>{$found_rows}</strong> rows found to process<br/>
                <button type="submit" name="set_config">{vtranslate('BTN_SET_IMPORT_CONFIG','Workflow2')}</button>
            </div>
          </div>
    </form>
    <br />
    <br />
    <h4>{vtranslate('HINT_FILE_IMPORT_PREVIEW','Workflow2')}</h4>
    <p>{vtranslate('HINT_FILE_IMPORT_PREVIEW_DESCR','Workflow2')}</p>
    <table class="table table-bordered table-condensed" cellspacing='0'>
        {foreach from=$rows item=row}
            {if $row neq ''}
            <tr>
                {foreach from=$row item=field}
                    <td style='padding:10px;border-left:1px solid #dddddd;border-right:1px solid #dddddd;'>{$field}</td>
                {/foreach}
            </tr>
            {/if}
        {/foreach}
    </table>
    <br/>

    <input type="hidden" name="import_process" value="{$import_process}" />
    <input type="hidden" name="import_hash" value="{$hash}" />

    <button type="button"  id="startImportProcess" class="btn btn-success" >{vtranslate('BTN_START_IMPORT','Workflow2')}</button>
</div>