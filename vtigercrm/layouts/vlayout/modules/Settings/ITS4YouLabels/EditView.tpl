{*
/*********************************************************************************
* The content of this file is subject to the Labels 4 You license.
* ("License"); You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
* Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
* All Rights Reserved.
********************************************************************************/
*}
{strip}
    <div class="container-fluid">
        <div class="widget_header row-fluid">
            <div class="span8">
                <h2>{vtranslate('LBL_MODULE_NAME', $QUALIFIED_MODULE)}</h2>
            </div>
        </div>
        <hr>
{*        modules/{$PARENT}/{$CURRENT_MODULE}/its_help.php*}
        <form method="post" action="index.php?module=ITS4YouLabels&parent=Settings&action=Save" id="moduleBlocksAndFields">
            <div class="widget_header row-fluid">
                <span class="span8">
                    <strong>
                        <span class="lead span9 marginLeftZero">{vtranslate('LBL_EDIT_LANGUAGE', $QUALIFIED_MODULE)}</span>
                    </strong>
                </span>

                <span class="span4">
                    <span class="pull-right">
                        <button class="btn btn-success" type="button"><strong>{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</strong></button>&nbsp;
                        <a class="cancelLink" id="backLink" type="reset">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
                    </span>
                </span>
            </div>        
            <br>
            <div id="moduleBlocks">
                {foreach key=BLOCK_LABEL_KEY item=BLOCK_MODEL from=$BLOCKS}
                    {assign var=FIELDS_LIST value=$BLOCK_MODEL->getLayoutBlockActiveFields()}
                    {assign var=BLOCK_ID value=$BLOCK_MODEL->get('id')}
                    <div id="block_{$BLOCK_ID}" class="editFieldsTable block_{$BLOCK_ID} marginBottom10px border1px {if $IS_BLOCK_SORTABLE} blockSortable{/if}" data-block-id="{$BLOCK_ID}" data-sequence="{$BLOCK_MODEL->get('sequence')}" style="border-radius: 4px 4px 0px 0px;background: white;">
                        <div class="row-fluid layoutBlockHeader">
                            <div class="blockLabel span5 padding10 marginLeftZero" style="in">
                                <img class="alignMiddle" src="{vimage_path('drag.png')}" />&nbsp;&nbsp;
                                {*                                <strong>{vtranslate($BLOCK_LABEL_KEY, $SELECTED_MODULE_NAME)}</strong>&nbsp;&nbsp;&Tab;*}
                                {assign var=STRING value=$BLOCK_LABEL_KEY}
                                {assign var=TRANSLATEDBLOCKSTRING value=Vtiger_Language_Handler::getLanguageTranslatedString($LANGUAGE,$STRING, $SELECTED_MODULE_NAME)}                                
                                {if $TRANSLATEDBLOCKSTRING eq ""}                                    
                                    {assign var=TRANSLATEDBLOCKSTRING value=vtranslate($BLOCK_LABEL_KEY, $SELECTED_MODULE)}
                                {/if}
                                <span><strong>{$TRANSLATEDBLOCKSTRING}</strong></span>
                            </div>
                        </div>
                        <div class="blockFieldsList {if $SELECTED_MODULE_MODEL->isFieldsSortableAllowed($BLOCK_LABEL_KEY)}blockFieldsSortable {/if} row-fluid" style="padding:5px;min-height: 27px">
                            <ul name="sortable1" class="connectedSortable span6" style="list-style-type: none; float: left;min-height: 1px;padding:2px;">
                                {foreach item=FIELD_MODEL from=$FIELDS_LIST name=fieldlist}
                                    {assign var=FIELD_INFO value=$FIELD_MODEL->getFieldInfo()}
                                    {if $smarty.foreach.fieldlist.index % 2 eq 0}
                                        <li>
                                            <div class="opacity editFields marginLeftZero border1px" data-block-id="{$BLOCK_ID}" data-field-id="{$FIELD_MODEL->get('id')}" data-sequence="{$FIELD_MODEL->get('sequence')}">
                                                <div class="row-fluid padding1per">
                                                    <div class="span11 marginLeftZero" style="word-wrap: break-word;">                                                        
                                                        {assign var=STRING value=$FIELD_MODEL->get('label')}
                                                        {assign var=FIELDID value=$FIELD_MODEL->get('id')}
                                                        {assign var=TRANSLATEDSTRING value=Vtiger_Language_Handler::getLanguageTranslatedString($LANGUAGE,$STRING, $SELECTED_MODULE_NAME)}
                                                        {assign var=FROMLANG value=Vtiger_Language_Handler::getLanguageTranslatedString($LANGUAGE,$STRING, $SELECTED_MODULE_NAME)}
                                                        {if $TRANSLATEDSTRING eq ""}
                                                            {assign var=TRANSLATEDSTRING value=vtranslate($FIELD_MODEL->get('label'), $SELECTED_MODULE)}                                                            
                                                        {/if}
                                                        {foreach foreach name=foreachname item=LABEL key=fieldid from=$FIELDS_LABELS}
                                                            {if $FIELDID eq $fieldid}
                                                                {assign var=TRANSLATEDSTRING value=$LABEL}
{*                                                                break;*}
                                                            {/if}
                                                        {/foreach}
                                                        {if $FROMLANG eq ""}
                                                            {assign var=FROMLANG value=$TRANSLATEDSTRING}
                                                        {/if}
                                                        <table>
                                                            <tr>
                                                                <td style="width: 50%;"><span class="muted pull-left" style="margin-left: 2px;">{$FROMLANG}</span></td>
                                                                {*<td style="width:50%;"></td>*}
                                                                <td style="width:50%;"><span class="pull-right" style="margin-right: 2px;"><input type="text" name="field_{$FIELD_MODEL->get('id')}" value="{$TRANSLATEDSTRING}"></span></td>
                                                            </tr>
                                                        </table>
                                                        {*<span class="muted pull-left">{$TRANSLATEDSTRING}</span>
                                                        <span class="pull-right"><input type="text" name="field_{$FIELD_MODEL->get('id')}" value="{$TRANSLATEDSTRING}"></span>  *}                                                      
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                            <ul {if $SELECTED_MODULE_MODEL->isFieldsSortableAllowed($BLOCK_LABEL_KEY)}name="sortable2"{/if} class="connectedSortable span6" style="list-style-type: none; margin: 0; float: left;min-height: 1px;padding:2px;">
                                {foreach item=FIELD_MODEL from=$FIELDS_LIST name=fieldlist1}
                                    {assign var=FIELD_INFO value=$FIELD_MODEL->getFieldInfo()}
                                    {if $smarty.foreach.fieldlist1.index % 2 neq 0}
                                        <li>
                                            <div class="opacity editFields marginLeftZero border1px" data-block-id="{$BLOCK_ID}" data-field-id="{$FIELD_MODEL->get('id')}" data-sequence="{$FIELD_MODEL->get('sequence')}">
                                                <div class="row-fluid padding1per">
                                                    <div class="span11 marginLeftZero" style="word-wrap: break-word;">                                                        
                                                        {assign var=STRING value=$FIELD_MODEL->get('label')}
                                                        {assign var=FIELDID value=$FIELD_MODEL->get('id')}
                                                        {assign var=TRANSLATEDSTRING value=Vtiger_Language_Handler::getLanguageTranslatedString($LANGUAGE,$STRING, $SELECTED_MODULE_NAME)}
                                                        {assign var=FROMLANG value=Vtiger_Language_Handler::getLanguageTranslatedString($LANGUAGE,$STRING, $SELECTED_MODULE_NAME)}
                                                        {if $TRANSLATEDSTRING eq ""}
                                                            {assign var=TRANSLATEDSTRING value=vtranslate($FIELD_MODEL->get('label'), $SELECTED_MODULE)}                                                             
                                                        {/if}
                                                        {foreach foreach name=foreachname item=LABEL key=fieldid from=$FIELDS_LABELS}
                                                            {if $FIELDID eq $fieldid}
                                                                {assign var=TRANSLATEDSTRING value=$LABEL}
{*                                                                break;*}
                                                            {/if}
                                                        {/foreach}
                                                        <table>
                                                            <tr>
                                                                <td style="width: 50%;"><span class="muted pull-left" style="margin-left: 2px;">{$FROMLANG}</span></td>
                                                                {*<td style="width:50%;"></td>*}
                                                                <td style="width:50%;"><span class="pull-right" style="margin-right: 2px;"><input type="text" name="field_{$FIELD_MODEL->get('id')}" value="{$TRANSLATEDSTRING}"></span></td>
                                                            </tr>
                                                        </table>
                                                        {*<span class="muted pull-left">{$TRANSLATEDSTRING}</span>
                                                        <span class="pull-right"><input type="text" name="field_{$FIELD_MODEL->get('id')}" value="{$TRANSLATEDSTRING}"></span>*} 
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                {/foreach}
            </div>
            <div class="pull-right">
                <button class="btn btn-success" type="button"><strong>{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</strong></button>&nbsp;
                <a class="cancelLink" id="backLink" type="reset">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
            </div>
            <input type="hidden" name="{vtranslate('LBL_LANG', $QUALIFIED_MODULE)}" value="{$LANGUAGE}">
            <input type="hidden" name="{vtranslate('LBL_MODULE', $QUALIFIED_MODULE)}" value="{$SELECTED_MODULE_NAME}">
            <input type="hidden" name="{vtranslate('LBL_SETTINGS_BLOCK',$QUALIFIED_MODULE)}" value="{$SETTINGS_BLOCK}">
            <input type="hidden" name="{vtranslate('LBL_SETTINGS_FIELDID',$QUALIFIED_MODULE)}" value="{$SETTINGS_FIELDID}">
{*            <input type="hidden" name="{vtranslate('LBL_PARENT', $QUALIFIED_MODULE)}" value="{$PARENT}">*}
        </form>
    </div>    
{/strip}