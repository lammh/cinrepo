{*/*<!--
/*********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/
-->*/*}

{strip}
	<div id="addFolderContainer" class="modelContainer">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" title="{vtranslate('LBL_CLOSE')}">x</button>
			<h3>{vtranslate('LBL_ADD_NEW_FOLDER', $MODULE)}</h3>
		</div>
		<form class="form-horizontal contentsBackground" id="addFolder" method="post" action="index.php">
			<input type="hidden" name="module" value="{$MODULE}" />
			<input type="hidden" name="action" value="Folder" />
			<input type="hidden" name="mode" value="save" />
			<input type="hidden" name="folderid" value="{$FOLDER_MODEL->getId()}" />
			<div class="modal-body">
				<div class="row-fluid verticalBottomSpacing">
					<span class="span4">{vtranslate('LBL_FOLDER_NAME', $MODULE)}<span class="redColor">*</span></span>
					<span class="span7 row-fluid"><input data-validation-engine='validate[required]' id="foldername" name="foldername" class="span12" type="text" value="{vtranslate($FOLDER_MODEL->getName(), $MODULE)}"/></span>
				</div>
				<div class="row-fluid">
					<span class="span4">{vtranslate('LBL_FOLDER_DESCRIPTION', $MODULE)}</span>
					<span class="span7 row-fluid">
						<textarea class="span12" name="description" placeholder="{vtranslate('LBL_WRITE_YOUR_DESCRIPTION_HERE', $MODULE)}">{vtranslate($FOLDER_MODEL->getDescription(), $MODULE)}</textarea>
					</span>
				</div>
			</div>
			{include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
		</form>
	</div>
{/strip}