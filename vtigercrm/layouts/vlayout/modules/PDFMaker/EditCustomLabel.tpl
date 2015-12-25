{*<!--
/* * *******************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */
-->*}
{strip}
	<div class="CustomLabelModalContainer">
		<div class="modal-header">
			<button class="close vtButton" data-dismiss="modal">×</button>
			{if $LABELID neq ""}
				<h3>{vtranslate('LBL_EDIT_CUSTOM_LABEL', 'PDFMaker')} ({$CURR_LANG.label})</h3>
			{else}
				<h3>{vtranslate('LBL_ADD_NEW_CUSTOM_LABEL', 'PDFMaker')} ({$CURR_LANG.label})</h3>
			{/if}
		</div>
		<form id="editCustomLabel" class="form-horizontal contentsBackground">
			<input type="hidden" name="labelid" value="{$LABELID}" />
			<input type="hidden" name="langid" value="{$LANGID}" />
			<div class="modal-body">
				<div class="row-fluid">
					<div class="control-group">
						<label class="muted control-label">{vtranslate('LBL_KEY', 'PDFMaker')}</label>
						<div class="controls">{if $LABELID eq ""}C_<input type="text" name="LblKey" placeholder="{vtranslate('LBL_ENTER_KEY', 'PDFMaker')}" value="" data-validation-engine='validate[required]' />{else}C_{$CUSTOM_LABEL_KEY}{/if}</div>	
					</div>
					<div class="control-group">
						<label class="muted control-label">{vtranslate('LBL_VALUE', 'PDFMaker')}</label>
						<div class="controls input-append">
							<input type="text" name="LblVal" class="input-large" placeholder="{vtranslate('LBL_ENTER_CUSTOM_LABEL_VALUE', 'PDFMaker')}" value="{$CUSTOM_LABEL_VALUE}" data-validation-engine='validate[required]' />
						</div>	
					</div>
				</div>
			</div>
                        {if $LABELID eq ""}<input type="hidden" class="addCustomLabelView" value="true" />{/if}                        
			{include file='ModalFooter.tpl'|@vtemplate_path:'Vtiger'}
		</form>
	</div>
{/strip}