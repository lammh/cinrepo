{*<!--
/*********************************************************************************
* The content of this file is subject to the PDF Maker license.
* ("License"); You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
* Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
* All Rights Reserved.
********************************************************************************/
-->*}
<script>
    function ExportTemplates()
    {ldelim}
        window.location.href = "index.php?module=PDFMaker&action=ExportPDFTemplate&templates={$TEMPLATEID}";
    {rdelim}
</script>
<form id="detailView" method="post" action="index.php" name="etemplatedetailview" onsubmit="VtigerJS_DialogBox.block();">  
    <input type="hidden" name="action" value="">
    <input type="hidden" name="view" value="">
    <input type="hidden" name="module" value="PDFMaker">
    <input type="hidden" name="retur_module" value="PDFMaker">
    <input type="hidden" name="return_action" value="PDFMaker">
    <input type="hidden" name="return_view" value="Detail">
    <input type="hidden" name="templateid" value="{$TEMPLATEID}">
    <input type="hidden" name="parenttab" value="{$PARENTTAB}">
    <input type="hidden" name="isDuplicate" value="false">
    <input type="hidden" name="subjectChanged" value="">
    <input id="recordId" value="{$TEMPLATEID}" type="hidden">
    <div class="detailViewContainer">
        <div class="row-fluid detailViewTitle">
            <div class="row-fluid">
                <div class="span7">
                    <div class="row-fluid">
                        <span class="span2"></span>
                        <span class="span8 margin0px">
                            <span class="row-fluid">
                                <span class="recordLabel font-x-x-large textOverflowEllipsis pushDown span" title="{$FILENAME}">
                                    <span class="templatename">{$FILENAME}</span>
                                </span>
                            </span>
                            <span class="row-fluid">
                                <span class="modulename_label">{vtranslate('LBL_MODULENAMES','PDFMaker')}:</span>
                                &nbsp;{$MODULENAME}
                            </span>
                        </span>
                    </div>
                </div>
                <div class="span5">
                    <div class="pull-right detailViewButtoncontainer">
                        <div class="btn-toolbar">
                            {if $EDIT eq 'permitted'}
                                <span class="btn-group">
                                  <button class="btn" id="PDFMaker_detailView_basicAction_LBL_EDIT" onclick="window.location.href = 'index.php?module={$MODULE}&view=Edit&templateid={$TEMPLATEID}&return_view=Detail';
        return false;"><strong>{vtranslate('LBL_EDIT')}</strong></button>
                                </span>
                                <span class="btn-group">
                                    <button class="btn" id="PDFMaker_detailView_basicAction_LBL_DUPLICATE" onclick="window.location.href = 'index.php?module={$MODULE}&view=Edit&templateid={$TEMPLATEID}&isDuplicate=true&return_view=Detail';
        return false;"><strong>{vtranslate('LBL_DUPLICATE')}</strong></button>
                                </span>
                            {/if}
                            {if $DELETE eq 'permitted'}
                                <span class="btn-group">
                                    <button class="btn" id="PDFMaker_detailView_basicAction_Delete" onclick="deleteRecord('index.php?module=PDFMaker&action=DeletePDFTemplate&templateid={$TEMPLATEID}'); return false;" style="font-weight:bold" ><strong>{vtranslate('LBL_DELETE')}</strong></button>
                                </span>
                            {/if}
                            {*<span class="btn-group">
                            <button class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);"><strong>More</strong>&nbsp;&nbsp;<i class="caret"></i></button>
                            <ul class="dropdown-menu pull-right">
                            <li id="PDFMaker_detailView_moreAction_LBL_DELETE"><a href="javascript:Vtiger_Detail_Js.deleteRecord(&quot;index.php?module=Contacts&amp;action=Delete&amp;record=24&quot;)">Delete</a></li>
                            <li id="PDFMaker_detailView_moreAction_LBL_ADD_NOTE"><a href="index.php?module=Documents&amp;view=Edit&amp;sourceModule=Contacts&amp;return_action=DetailView&amp;sourceRecord=24&amp;parent_id=24&amp;relationOperation=true">Add Document</a></li>
                            <li id="PDFMaker_detailView_moreAction_LBL_SEND_SMS"><a href="javascript:Vtiger_Detail_Js.triggerSendSms(&quot;index.php?module=Contacts&amp;view=MassActionAjax&amp;mode=showSendSMSForm&quot;,&quot;SMSNotifier&quot;);">Send SMS</a></li>
                            <li id="PDFMaker_detailView_moreAction_LBL_ADD_EVENT"><a href="index.php?module=Calendar&amp;view=Edit&amp;mode=Events&amp;contact_id=24">Add Event</a></li>
                            <li id="PDFMaker_detailView_moreAction_LBL_ADD_TASK"><a href="index.php?module=Calendar&amp;view=Edit&amp;mode=Calendar&amp;contact_id=24">Add Task</a></li>
                            </ul>
                            </span>*}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="detailViewInfo row-fluid">
            <div class="details">
                <div style="position: relative;" class="contents">
                    <table class="table table-bordered equalSplit detailview-table">
                        <thead>
                            <tr>
                                <th class="blockHeader" colspan="2">{vtranslate('LBL_TEMPLATE_INFORMATIONS','PDFMaker')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fieldLabel narrowWidthType" style="width:25%;"><label class="muted pull-right marginRight10px">{vtranslate('LBL_PDF_NAME','PDFMaker')}</label></td>
                                <td class="fieldValue narrowWidthType" style="width:75%;">{$FILENAME}</td>
                            </tr>
                            <tr>
                                <td class="fieldLabel narrowWidthType"><label class="muted pull-right marginRight10px">{vtranslate('LBL_DESCRIPTION','PDFMaker')}</label></td>
                                <td class="fieldValue narrowWidthType" valign=top>{$DESCRIPTION}</td>
                            </tr>
                            <tr>
                                <td class="fieldLabel narrowWidthType"><label class="muted pull-right marginRight10px">{vtranslate('LBL_MODULENAMES','PDFMaker')}</label></td>
                                <td class="fieldValue narrowWidthType" valign=top>{$MODULENAME}</td>
                            </tr>
                            <tr>
                                <td class="fieldLabel narrowWidthType"><label class="muted pull-right marginRight10px">{vtranslate('Status')}</label></td>
                                <td class="fieldValue narrowWidthType" valign=top>{$IS_ACTIVE}</td>
                            </tr>
                            <tr>
                                <td class="fieldLabel narrowWidthType"><label class="muted pull-right marginRight10px">{vtranslate('LBL_SETASDEFAULT','PDFMaker')}</label></td>
                                <td class="fieldValue narrowWidthType" valign=top>{$IS_DEFAULT}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered equalSplit detailview-table">
                        <thead>
                            <tr>
                                <th class="blockHeader" colspan="2">{vtranslate('LBL_PDF_TEMPLATE','PDFMaker')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td valign="top" style="width:5%;">{vtranslate('LBL_HEADER_TAB','PDFMaker')}</td>
                                <td style="width:95%;">{$HEADER}</td>
                            </tr>

                            <tr>
                                <td valign="top">{vtranslate('LBL_BODY','PDFMaker')}</td>
                                <td>{$BODY}</td>
                            </tr>

                            <tr>
                                <td valign="top">{vtranslate('LBL_FOOTER_TAB','PDFMaker')}</td>
                                <td>{$FOOTER}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <center style="color: rgb(153, 153, 153);">{vtranslate('PDF_MAKER','PDFMaker')} {$VERSION} {vtranslate('COPYRIGHT','PDFMaker')}</center>
</form>

{literal}
    <script type="text/javascript">
        function deleteRecord(deleteRecordActionUrl) {
            var message = app.vtranslate('LBL_DELETE_CONFIRMATION');
            Vtiger_Helper_Js.showConfirmationBox({'message': message}).then(function(data) {
                AppConnector.request(deleteRecordActionUrl + '&ajaxDelete=true').then(
                        function(data) {
                            if (data.success == true) {
                                window.location.href = 'index.php?module=PDFMaker&view=List';
                            } else {
                                Vtiger_Helper_Js.showPnotify(data.error.message);
                            }
                        });
            },
                    function(error, err) {
                    }
            );
        }
    </script>
{/literal}