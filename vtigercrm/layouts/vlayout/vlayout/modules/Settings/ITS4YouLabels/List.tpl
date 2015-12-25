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
    <div class="contentsDiv marginLeftZero" >
        <div class="padding1per">
            <div class="editContainer" style="padding-left: 3%;padding-right: 3%"><h2>{vtranslate('LBL_MODULE_NAME',$QUALIFIED_MODULE)}</h2>    
                <hr>  
                <div class="padding1per" style="border:1px solid #ccc;">
                    <div class="controls">
                        <div>
                            <label><strong>{vtranslate('LBL_WELCOME', $QUALIFIED_MODULE)}</strong></label>
                        </div>
                    </div>
                    <div class="clearfix">
                    </div>
                    <br>
                    <form name="languageAndModul" id="langAndModulForm" method="POST"  class="form-horizontal">
                        <input type="hidden" name="sourceModule" value="{$MODULE}">
                        <input type="hidden" name="{vtranslate('LBL_SETTINGS_BLOCK',$QUALIFIED_MODULE)}" value="{$SETTINGS_BLOCK}">
                        <input type="hidden" name="{vtranslate('LBL_SETTINGS_FIELDID',$QUALIFIED_MODULE)}" value="{$SETTINGS_FIELDID}">
                        <table style="width:100%;"><tr><td>
                                    <span class="pull-right">{vtranslate('LBL_CHOOSE_LANG', $QUALIFIED_MODULE)}:&nbsp;</span>
                                </td><td>
                                    <span class="pull-left"><select name="language" class="chzn-select">
                                            {foreach item=LANGUAGE key=KEY from=$LANGUAGES}
                                                <option value="{$KEY}">{$LANGUAGE}</option>
                                            {/foreach}
                                        </select></span>
                                </td><td>
                                    <span class="pull-right">{vtranslate('LBL_CHOOSE_MODUL', $QUALIFIED_MODULE)}:&nbsp;</span>
                                </td><td>
                                    <span class="pull-left">
                                        <select name="module" class="chzn-select">
                                            {foreach item=MODULE_MODEL key=MODULE_ID from=$ALL_MODULES}
                                                {assign var=MODULE_NAME value=$MODULE_MODEL->get('name')}
                                                <option value="{$MODULE_ID}">{vtranslate($MODULE_NAME, $MODULE_NAME)}</option>
                                            {/foreach}
                                        </select>
                                    </span>
                                </td>
                            </tr>
                            <tr><td colspan="10">
                                    <span class="pull-right">
                                        <button type="button" class="btn btn-success"><strong>{vtranslate('LBL_SUBMIT', $QUALIFIED_MODULE)}</strong></button>
                                    </span>
                                </td></tr>
                        </table>
                                    
                    </form>
                </div>
            </div>
        </div>
    </div>
{/strip}

