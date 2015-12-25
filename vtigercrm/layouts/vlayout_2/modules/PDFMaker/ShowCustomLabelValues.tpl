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
                    <h3>{$LBLKEY}</h3>
		</div>
		<form id="showCustomLabelValues" action="index.php" method="post" class="form-horizontal contentsBackground">
			<input type="hidden" name="module" value="PDFMaker" />
                        <input type="hidden" name="action" value="SaveCustomLabels" />
                        <input type="hidden" name="lblkey" value="{$LBLKEY}" />
                            <div class="modal-body">
                            <div class="row-fluid">
                                {foreach name=langvals item=langvalsdata key=modulename from=$LANGVALSARR}
                                    <div class="control-group">
                                            <label class="muted control-label">{$langvalsdata.label}</label>
                                            <div class="controls input-append">
                                                <input type="hidden" name="LblVal{$langvalsdata.id}" value="yes"/>
                                                <input type="text" name="LblVal{$langvalsdata.id}Value" class="input-large" placeholder="{vtranslate('LBL_ENTER_CUSTOM_LABEL_VALUE', 'PDFMaker')}" value="{$langvalsdata.value}"/>
                                            </div>	
                                    </div>
                                {/foreach}            
                            </div>
			</div>
                        {if $LABELID eq ""}<input type="hidden" class="addCustomLabelView" value="true" />{/if}                        
			{include file='ModalFooter.tpl'|@vtemplate_path:'Vtiger'}
		</form>
	</div>
{/strip}