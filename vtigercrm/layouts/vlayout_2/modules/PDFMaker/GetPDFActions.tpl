{*
/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/
*}
{if $ENABLE_EMAILMAKER neq 'true'}
    {assign var="EMAIL_FUNCTION" value="sendPDFmail"}
{else}
    {assign var="EMAIL_FUNCTION" value="sendEMAILMakerPDFmail"}
{/if}

{if $ENABLE_PDFMAKER eq 'true'}
<div class="row-fluid">
	<div class="span10">
  {if $CRM_TEMPLATES_EXIST eq '0'}
		<ul class="nav nav-list row-fluid">
		<li>
  			<select name="use_common_template" id="use_common_template" class="detailedViewTextBox" multiple size="5">
        {foreach name="tplForeach" from="$CRM_TEMPLATES" item="itemArr" key="templateid"}
            {if $itemArr.is_default eq '1' || $itemArr.is_default eq '3'}
                <option value="{$templateid}" selected="selected">{$itemArr.templatename}</option>
            {else}
                <option value="{$templateid}">{$itemArr.templatename}</option>
            {/if}
        {/foreach}
        </select>        
		</li>
		{if $TEMPLATE_LANGUAGES|@sizeof > 1}
			<li>   	
			    <select name="template_language" id="template_language" class="detailedViewTextBox" size="1">
					{html_options  options=$TEMPLATE_LANGUAGES selected=$CURRENT_LANGUAGE}
			    </select>
			</li>
		{else}
			{foreach from="$TEMPLATE_LANGUAGES" item="lang" key="lang_key"}
		    	<input type="hidden" name="template_language" id="template_language" value="{$lang_key}"/>
			{/foreach}
		{/if}
                {* Export to PDF *}
		<li>
		    <a href="javascript:;" onclick="if(PDFMaker_Actions_Js.getSelectedTemplates()=='') alert('{$PDFMAKER_MOD.SELECT_TEMPLATE}'); else if((navigator.userAgent.match(/iPad/i)!= null)||(navigator.userAgent.match(/iPhone/i)!= null)||(navigator.userAgent.match(/iPod/i)!= null)) window.open('index.php?module=PDFMaker&relmodule={$MODULE}&action=CreatePDFFromTemplate&record={$ID}&commontemplateid='+PDFMaker_Actions_Js.getSelectedTemplates()+'&language='+document.getElementById('template_language').value); else document.location.href='index.php?module=PDFMaker&relmodule={$MODULE}&action=CreatePDFFromTemplate&record={$ID}&commontemplateid='+PDFMaker_Actions_Js.getSelectedTemplates()+'&language='+document.getElementById('template_language').value;" class="webMnu" style="padding-left:10px;"><img src="layouts/vlayout/modules/PDFMaker/images/actionGeneratePDF.gif" hspace="5" align="absmiddle" border="0" style="border-radius:3px;" /> {vtranslate('LBL_EXPORT','PDFMaker')}</a>
		</li>
                {* Print PDF *}
                <li>
		    <a href="javascript:;" onclick="if(PDFMaker_Actions_Js.getSelectedTemplates()=='') alert('{$PDFMAKER_MOD.SELECT_TEMPLATE}'); else window.open('index.php?module=PDFMaker&relmodule={$MODULE}&action=CreatePDFFromTemplate&print=true&record={$ID}&commontemplateid='+PDFMaker_Actions_Js.getSelectedTemplates()+'&language='+document.getElementById('template_language').value,'_newtab' + (Math.floor(new Date().getTime() / 1000)) );" class="webMnu" style="padding-left:10px;"><img src="layouts/vlayout/modules/PDFMaker/images/PDF_print.png" hspace="5" align="absmiddle" border="0" style="border-radius:3px;" /> {vtranslate('LBL_PRINT','PDFMaker')}</a>
		</li>

                {* Send Email with PDF *}
                {if $SEND_EMAIL_PDF_ACTION eq "1"}
		<li>
                    <a href="javascript:;" onclick="if(PDFMaker_Actions_Js.getSelectedTemplates()=='') alert('{$PDFMAKER_MOD.SELECT_TEMPLATE}'); else PDFMaker_Actions_Js.pdfmaker_{$EMAIL_FUNCTION}('{$MODULE}','{$ID}','{$RELMODULE}','{$RELMODULE_SELID}');" class="webMnu" style="padding-left:10px;"><img src="layouts/vlayout/modules/PDFMaker/images/PDFMail.gif" hspace="5" align="absmiddle" border="0" style="border-radius:3px;" /> {vtranslate('LBL_SEND_EMAIL')}</a>
		</li>
                {/if}
                {* Edit and Export to PDF *}            
                {if $EDIT_AND_EXPORT_ACTION eq "1"}
		<li>
                    <a href="javascript:;" onclick="if(PDFMaker_Actions_Js.getSelectedTemplates()=='') alert('{$PDFMAKER_MOD.SELECT_TEMPLATE}'); else PDFMakerCommon.getEditAndExportForm('{$MODULE}','{$ID}',PDFMaker_Actions_Js.getSelectedTemplates(),document.getElementById('template_language').value);" class="webMnu" style="padding-left:10px;"><img src="layouts/vlayout/modules/PDFMaker/images/PDF_edit.png" hspace="5" align="absmiddle" border="0" style="border-radius:3px;" /> {vtranslate('LBL_EDIT')} {vtranslate('LBL_AND')} {vtranslate('LBL_EXPORT','PDFMaker')}</a>
		</li>
                {/if}
                {* Save PDF into documents *}
                {if $SAVE_AS_DOC_ACTION eq "1"}
		<li>
                    <a href="javascript:;" onclick="if(PDFMaker_Actions_Js.getSelectedTemplates()=='') alert('{$PDFMAKER_MOD.SELECT_TEMPLATE}'); else PDFMakerCommon.getPDFDocDivContent(this,'{$MODULE}','{$ID}');" class="webMnu" style="padding-left:10px;"><img src="layouts/vlayout/modules/PDFMaker/images/PDFDoc.png" hspace="5" align="absmiddle" border="0" style="border-radius:3px;" /> {vtranslate('LBL_SAVEASDOC','PDFMaker')}</a>
		</li>
                {/if}
                {* PDF product images*}
                {if $MODULE eq 'Invoice' || $MODULE eq 'SalesOrder' || $MODULE eq 'PurchaseOrder' || $MODULE eq 'Quotes' || $MODULE eq 'Receiptcards' || $MODULE eq 'Issuecards'}
                    <li>
                        <a href="javascript:PDFMakerCommon.showPDFBreakline('{$ID}');" class="webMnu" style="padding-left:10px;"><img src="layouts/vlayout/modules/PDFMaker/images/PDF_bl.png" hspace="5" align="absmiddle" border="0" style="border-radius:3px;" />{vtranslate('LBL_PRODUCT_BREAKLINE','PDFMaker')}</a>                
                        <div id="PDFBreaklineDiv" style="display:none; width:350px; position:absolute;" class="layerPopup"></div>                
                    </li>
                {/if}
                {if $MODULE eq 'Invoice' || $MODULE eq 'SalesOrder' || $MODULE eq 'PurchaseOrder' || $MODULE eq 'Quotes' || $MODULE eq 'Receiptcards' || $MODULE eq 'Issuecards' || $MODULE eq 'Products'}
                <li>
                    <a href="javascript:PDFMakerCommon.showproductimages('{$ID}');" class="webMnu" style="padding-left:10px;"><img src="layouts/vlayout/modules/PDFMaker/images/PDF_img.png" hspace="5" align="absmiddle" border="0" style="border-radius:3px;" /> {vtranslate('LBL_PRODUCT_IMAGE', 'PDFMaker')}</a>                
                </li>
                {/if}
                    </ul>
            {else}
                <tr>
  		<td class="rightMailMergeContent">
  		  {$PDFMAKER_MOD.CRM_TEMPLATES_DONT_EXIST}
                  {if $IS_ADMIN eq '1'}
    		  {$PDFMAKER_MOD.CRM_TEMPLATES_ADMIN}
                    <a href="index.php?module=PDFMaker&view=List&return_module={$MODULE}&return_id={$ID}" class="webMnu">{$PDFMAKER_MOD.TEMPLATE_CREATE_HERE}</a>
                  {/if}
                </td>
		</tr>
	{/if}
	</div>
	<br clear="all"/>
 	<div id="alert_doc_title" style="display:none;">{$PDFMAKER_MOD.ALERT_DOC_TITLE}</div>
</div>
{else}
<div class="row-fluid">
	<div class="span10">
		<ul class="nav nav-list">
			<li><a href="index.php?module=PDFMaker&view=List">{vtranslate('LBL_PLEASE_FINISH_INSTALLATION', 'PDFMaker')}</a></li>
		</ul>
	</div>
</div>
{/if}
