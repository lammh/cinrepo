{*
/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/
*}
<div id="PDFMakerContainer" class="modelContainer" style="min-width:500px;">
	<div class="modal-header">
		<button data-dismiss="modal" class="close" title="{vtranslate('LBL_CLOSE')}">x</button>
		<h3>{vtranslate('LBL_PDF_ACTIONS','PDFMaker')}</h3>
	</div>
        <form id="generatePDFForm" method="post" action="index.php">
        <input type="hidden" name="module" value="PDFMaker" />
        <input type="hidden" name="relmodule" value="{$relmodule}" />
        <input type="hidden" name="action" value="CreatePDFFromTemplate" />
        <input type="hidden" name="idslist" value="{$idslist}">
        <input type="hidden" name="commontemplateid" value="">
        <input type="hidden" name="language" value="">
        </form>
	<form class="form-horizontal contentsBackground" id="massSave" method="post" action="index.php">
		<input type="hidden" name="module" value="PDFMaker" />
		<input type="hidden" name="relmodule" value="{$relmodule}" />
		<input type="hidden" name="action" value="CreatePDFFromTemplate" />
		<input type="hidden" name="idslist" value="{$idslist}">
                <div class="modal-body tabbable"> 
                    <div class="control-group">
                            <span class="control-label">
                                    {vtranslate('LBL_PDF_TEMPLATE', 'PDFMaker')}
                            </span>
                            <div class="controls">
                                    <div class="row-fluid">
                                            <span class="span10">
                                                    {$templates_select}
                                            </span>
                                    </div>
                            </div>
                    </div>
                    <div class="control-group">
                        <span class="control-label">
                                {vtranslate('LBL_PDF_LANGUAGE', 'PDFMaker')}
                        </span>
                        <div class="controls">
                                <div class="row-fluid">
                                        <span class="span10">
                                                {$languages_select}
                                        </span>
                                </div>
                        </div>
                    </div>
      
		</div>
		<div class="modal-footer">
			<div class=" pull-right cancelLinkContainer">
				<a class="cancelLink" type="reset" data-dismiss="modal">{vtranslate('LBL_CLOSE')}</a>
			</div>
                        <button class="btn btn-success" type="button" id="printButton" name="printButton"><strong>{vtranslate('LBL_PRINT','PDFMaker')}</strong></button>
                        <button class="btn btn-success" type="button" id="generateButton" name="generateButton"><strong>{vtranslate('LBL_EXPORT_TO_PDF','PDFMaker')}</strong></button>
		</div>
	</form>
</div>