{*<!--
/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/
-->*}
<div class="container-fluid" id="licenseContainer">
    
    <form name="profiles_privilegies" action="index.php" method="post" class="form-horizontal">
    <br>
    <label class="pull-left themeTextColor font-x-x-large">{vtranslate('LBL_EXTENSIONS','PDFMaker')}</label>
    <br clear="all">
    <hr>
    <input type="hidden" name="module" value="PDFMaker" />
    <input type="hidden" name="view" value="" />
     <br />
    <div class="row-fluid">
        <label class="fieldLabel"><strong>{vtranslate('LBL_AVAILABLE_EXTENSIONS','PDFMaker')}:</strong></label>
        
        {foreach item=arr key=extname from=$EXTENSIONS_ARR}
            <br>    
            <table class="table table-bordered equalSplit detailview-table">
                <thead>
                    <tr>
                        <th class="blockHeader" colspan="2"><b><div class="pull-left" style="font-size:18px;padding-top:5px">{vtranslate($arr.label, 'PDFMaker')}</div></b>{if $arr.download neq ""}<span class="pull-right"><a class="btn" href="{$arr.download}">{vtranslate('LBL_DOWNLOAD', 'PDFMaker')}</a></span>{/if}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="padding5per">
                            <div class="padding10">
                                {vtranslate($arr.desc, 'PDFMaker')}
                                {if $arr.exinstall neq ""}<br><br>
                                <b>{vtranslate('LBL_INSTAL_EXT', 'PDFMaker')}</b><br>
                                {vtranslate($arr.exinstall, 'PDFMaker')}
                                {/if}
                                {if $arr.manual neq ""}<br><br>
                                {vtranslate('LBL_CUSTOM_INSTAL_EXT', 'PDFMaker')}<b> <a href="{$arr.manual}" style="cursor: pointer">{vtranslate($arr.label, 'PDFMaker')}.txt</a></b>
                                {/if}
                                <br><br>
                                <div id="install_{$extname}_info" class="fontBold{if $arr.install_info eq ""} hide{/if}">{$arr.install_info}</div>
                                {if $arr.install neq ""}
                                <button type="button" id="install_{$extname}_btn" class="btn btn-success" data-extname="{$extname}" data-url="{$arr.install}">{vtranslate('LBL_INSTALL_BUTTON', 'Install')}</button></div>
                                {/if}
                            </div>
                        </td>
                    </tr>
                 </tbody>
            </table>
        {/foreach}                
    </div>
    {if $MODE eq "edit"}        
        <div class="pull-right">
            <button class="btn btn-success" type="submit">{vtranslate('LBL_SAVE',$MODULE)}</button>
            <a class="cancelLink" onclick="javascript:window.history.back();" type="reset">Cancel</a>
        </div> 
    {/if}
    </form>        
</div>
      
<script language="javascript" type="text/javascript">
{if $ERROR eq 'true'}
    alert('{vtranslate('ALERT_DOWNLOAD_ERROR', 'PDFMaker')}');
{/if}    
</script>
   