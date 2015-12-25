{*<!--
/*********************************************************************************
* The content of this file is subject to the PDF Maker license.
* ("License"); You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
* Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
* All Rights Reserved.
********************************************************************************/
-->*}
<script type="text/javascript" src="layouts/vlayout/modules/PDFMaker/resources/ProductBlocks.js"></script>
{include file='JSResources.tpl'|@vtemplate_path}
<div class='editViewContainer'>
    <form class="form-horizontal" id="EditView" name="EditView" method="post" action="index.php" enctype="multipart/form-data" data-detail-url="?module=PDFMaker&view=ProductBlocks">
    <input type="hidden" name="module" value="PDFMaker" />
    <input type="hidden" name="action" value="IndexAjax" />
    <input type="hidden" name="mode" value="SaveProductBlock" /> 
        <input type="hidden" name="tplid" value="{$EDIT_TEMPLATE.id}">
        <div class="contentHeader row-fluid">
            {if $EMODE eq 'edit'}
                {if $MODE neq "duplicate"}
                    <span class="span8 font-x-x-large textOverflowEllipsis" title="{vtranslate('LBL_EDIT','PDFMaker')} &quot;{$FILENAME}&quot;">{vtranslate('LBL_EDIT','PDFMaker')} &quot;{$EDIT_TEMPLATE.name}&quot;</span>
                {else}
                    <span class="span8 font-x-x-large textOverflowEllipsis" title="{vtranslate('LBL_DUPLICATE','PDFMaker')} &quot;{$DUPLICATE_FILENAME}&quot;">{vtranslate('LBL_DUPLICATE','PDFMaker')} &quot;{$EDIT_TEMPLATE.name}&quot;</span>
                {/if}
            {else}
                <span class="span8 font-x-x-large textOverflowEllipsis">{vtranslate('LBL_NEW_TEMPLATE','PDFMaker')}</span>
            {/if}
            <span class="pull-right">
                {*<button class="btn btn-success" type="submit" onclick="if(PDFMaker_EditJs.savePDF()) this.form.submit();"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>*}
                <button class="btn btn-success saveButton" type="submit"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
                {if $smarty.request.applied eq 'true'}
                    <a class="cancelLink" type="reset" onclick="window.location.href = 'index.php?action=DetailViewPDFTemplate&module=PDFMaker&templateid={$SAVETEMPLATEID}&parenttab=Tools';">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                {else}
                    <a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                {/if}            			
            </span>
        </div>
                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div class="modal-body tabbable" style="padding:0px;">
                                <ul class="nav nav-pills" style="margin-bottom:0px; padding-left:5px;">
                                    <li class="active" id="properties_tab" onclick="PDFMaker_EditJs.showHideTab('properties');"><a data-toggle="tab" href="javascript:void(0);">{vtranslate('LBL_PROPERTIES_TAB','PDFMaker')}</a></li>
                                    <li id="labels_tab" onclick="PDFMaker_EditJs.showHideTab('labels');"><a data-toggle="tab" href="javascript:void(0);">{vtranslate('LBL_LABELS','PDFMaker')}</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">
                            {********************************************* PROPERTIES DIV*************************************************}
                            <div  class="contents row-fluid" style="display:block;" id="properties_div">
                               
                                <table class="table table-bordered table-condensed themeTableColor">                        
                                    <tbody>
                                    <tr>
                                        <td width="25%"><font color="red">*</font><strong>{vtranslate('LBL_PDF_NAME','PDFMaker')}:</strong></td>
                                        <td><input name="template_name" id="template_name" type="text" value="{if $MODE neq "duplicate"}{$EDIT_TEMPLATE.name}{/if}" class="detailedViewTextBox" tabindex="1" data-validation-engine='validate[required]'>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="25%"><strong>{vtranslate('LBL_ARTICLE','PDFMaker')}:</strong></td>
                                        <td>
                                            <select name="articelvar" id="articelvar" class="classname">
                                                {html_options  options=$ARTICLE_STRINGS}
                                            </select>
                                            <input type="button" value="{vtranslate('LBL_INSERT_TO_TEXT','PDFMaker')}" class="crmButton small create" onclick="InsertIntoTemplate('articelvar');">
                                        </td>
                                    </tr>
                                    {* insert products & services fields into text *}
                                    <tr>
                                        <td width="25%"><strong>*{vtranslate('LBL_PRODUCTS_AVLBL','PDFMaker')}:</strong></td>
                                        <td>
                                            <select name="psfields" id="psfields" class="classname">
                                                {html_options  options=$SELECT_PRODUCT_FIELD}
                                            </select>
                                            <input type="button" value="{vtranslate('LBL_INSERT_TO_TEXT','PDFMaker')}" class="crmButton small create" onclick="InsertIntoTemplate('psfields');">            						
                                        </td>
                                    </tr>
                                    {* products fields *}                                
                                    <tr>
                                        <td width="25%"><strong>*{vtranslate('LBL_PRODUCTS_FIELDS','PDFMaker')}:</strong></td>
                                        <td>
                                            <select name="productfields" id="productfields" class="classname">
                                                {html_options  options=$PRODUCTS_FIELDS}
                                            </select>
                                            <input type="button" value="{vtranslate('LBL_INSERT_TO_TEXT','PDFMaker')}" class="crmButton small create" onclick="InsertIntoTemplate('productfields');">            						
                                        </td>
                                    </tr>
                                    {* services fields *}                                
                                    <tr>
                                        <td width="25%"><strong>*{vtranslate('LBL_SERVICES_FIELDS','PDFMaker')}:</strong></td>
                                        <td>
                                            <select name="servicesfields" id="servicesfields" class="classname">
                                                {html_options  options=$SERVICES_FIELDS}
                                            </select>
                                            <input type="button" value="{vtranslate('LBL_INSERT_TO_TEXT','PDFMaker')}" class="crmButton small create" onclick="InsertIntoTemplate('servicesfields');">            						
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><small>{vtranslate('LBL_PRODUCT_FIELD_INFO','PDFMaker')}</small></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            {********************************************* Labels DIV *************************************************}
                            <div style="display:none;" id="labels_div">
                                <table class="table table-bordered table-condensed">
                                    <tr>
                                        <td width="200px"><strong>{vtranslate('LBL_GLOBAL_LANG','PDFMaker')}:</strong></td>
                                        <td>
                                            <select name="global_lang" id="global_lang" class="classname" style="width:80%">
                                                {html_options  options=$GLOBAL_LANG_LABELS}
                                            </select>
                                            <input type="button" value="{vtranslate('LBL_INSERT_TO_TEXT','PDFMaker')}" class="crmButton small create" onclick="InsertIntoTemplate('global_lang');">
                                        </td>
                                    </tr>
                                    {if $TYPE eq "professional"}
                                        <tr>
                                            <td><strong>{vtranslate('LBL_CUSTOM_LABELS','PDFMaker')}:</strong></td>
                                            <td>
                                                <select name="custom_lang" id="custom_lang" class="classname" style="width:80%">
                                                    {html_options  options=$CUSTOM_LANG_LABELS}
                                                </select>
                                                <input type="button" value="{vtranslate('LBL_INSERT_TO_TEXT','PDFMaker')}" class="crmButton small create" onclick="InsertIntoTemplate('custom_lang');">
                                            </td>
                                        </tr>
                                    {/if}
                                </table>
                            </div>

                            {************************************** END OF TABS BLOCK *************************************}                         
                        </td>
                    </tr>
                </table>

                {*********************************************BODY DIV*************************************************}
                <textarea name="body" id="body" style="width:90%;height:700px" class=small tabindex="5">{$EDIT_TEMPLATE.body}</textarea>


                <script type="text/javascript">
                    {literal} CKEDITOR.replace('body', {height:'1000'});{/literal}                        
                </script>

                <div class="contentHeader row-fluid">
                    <span class="pull-right">
                        <button class="btn btn-success" type="submit" onclick="if(PDFMaker_EditJs.savePDF()) this.form.submit();"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
                        {if $smarty.request.applied eq 'true'}
                            <a class="cancelLink" type="reset" onclick="window.location.href = 'index.php?action=DetailViewPDFTemplate&module=PDFMaker&templateid={$SAVETEMPLATEID}&parenttab=Tools';">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                        {else}
                            <a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                        {/if}            			
                    </span>
                </div>

            <div align="center" class="small" style="color: rgb(153, 153, 153);">{vtranslate('PDF_MAKER','PDFMaker')} {$VERSION} {vtranslate('COPYRIGHT','PDFMaker')}</div>


    </form>
</div>
<script type="text/javascript">
 
    var selectedTab = "properties";
 
    function InsertIntoTemplate(element){ldelim}

    selectField = document.getElementById(element).value;
           
    var oEditor = CKEDITOR.instances.body;
          
            if (element == "articelvar" || selectField == "LISTVIEWBLOCK_START" || selectField == "LISTVIEWBLOCK_END")
                insert_value = '#' + selectField + '#';
            else if (element == "relatedmodulefields")
                insert_value = '$R_' + selectField + '$';
            else if (element == "productbloctpl" || element == "productbloctpl2")
                insert_value = selectField;
            else if (element == "global_lang")
                insert_value = '%G_' + selectField + '%';
            else if (element == "custom_lang")
                insert_value = '%' + selectField + '%';
            else
                insert_value = '$' + selectField + '$';

            oEditor.insertHtml(insert_value);
      
    {rdelim}
    PDFMaker_ProductBlocks_Js.registerEvents();
</script>
