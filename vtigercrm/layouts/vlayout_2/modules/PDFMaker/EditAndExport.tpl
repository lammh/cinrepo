{*
/*********************************************************************************
* The content of this file is subject to the PDF Maker license.
* ("License"); You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
* Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
* All Rights Reserved.
********************************************************************************/
*}
{*include file='JSResources.tpl'|@vtemplate_path*}
{strip}
    <div class="SendEmailFormStep2" id="composeEmailContainer">
        <form class="form-horizontal" id="massEmailForm" method="post" action="index.php" enctype="multipart/form-data" name="massEmailForm">
            <input type="hidden" name="action" id="action" value='CreatePDFFromTemplate' />
            <input type="hidden" name="module" value="PDFMaker"/>
            <input type="hidden" name="commontemplateid" value='{$COMMONTEMPLATEIDS}' />
            <input type="hidden" name="template_ids" value='{$COMMONTEMPLATEIDS}' />
            <input type="hidden" name="idslist" value="{$RECORDS}" />
            <input type="hidden" name="relmodule" value="{$smarty.request.formodule}" />
            <input type="hidden" name="language" value='{$smarty.request.language}' />
            <input type="hidden" name="pmodule" value="{$smarty.request.formodule}" />
            <input type="hidden" name="pid" value="{$smarty.request.record}" />
            <input type="hidden" name="mode" value="edit" />
            <div id='editTemplate'>
                <div style='padding:10px 0;'>
                    <h3>{vtranslate('LBL_EDIT')} {vtranslate('LBL_AND')} {vtranslate('LBL_EXPORT','PDFMaker')}</h3>
                    <hr style='margin:5px 0;width:100%'>
                </div>
                <div id="topMenus" class="navbar" style="margin-bottom:7px;">
                    <div id="nav-inner" class="navbar-inner">
                        <table class="small" width="100%" border="0" cellpadding="3" cellspacing="0">
                            <tr>
                                <td>
                                    <ul class="nav modulesList" width="100%">
                                        <li class="tabs" id="body_tab"><a href="" onclick="showHideTab('body');
                                                        return false;" id="body_tab_a" class="selected">{vtranslate('LBL_BODY','PDFMaker')}</a></li>
                                        <li class="tabs" id="header_tab"><a href="" onclick="showHideTab('header');
                                                        return false;" id="header_tab_a">{vtranslate('LBL_HEADER_TAB','PDFMaker')}</a></li>
                                        <li class="tabs" id="footer_tab"><a href="" onclick="showHideTab('footer');
                                                        return false;" id="footer_tab_a">{vtranslate('LBL_FOOTER_TAB','PDFMaker')}</a></li>
                                    </ul>
                                </td>
                                <td align="right" style="color:white;">
                                    {vtranslate('LBL_TEMPLATE','PDFMaker')}:&nbsp;{$TEMPLATE_SELECT}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                {$PDF_DIVS}
                <div class="padding-bottom1per row-fluid">
                    <div class="span8">
                        <div class="btn-toolbar">
                            <span class="btn-group">
                                <button class="floatNone btn btn-success" id="PDFExportButton" type="submit" title="{vtranslate('LBL_EXPORT_TO_PDF','PDFMaker')}"><strong>{vtranslate('LBL_EXPORT_TO_PDF','PDFMaker')}</strong></button>&nbsp;&nbsp;
                            </span>
                           {* <span class="btn-group">
                                <button class="floatNone btn btn-success" id="SavePDFAsDocButton" title="{vtranslate('LBL_SAVEASDOC','PDFMaker')}" onclick="showDocSettings();
                                                        return false;"><strong>{vtranslate('LBL_SAVEASDOC','PDFMaker')}</strong></button>
                            </span>*}
                        </div>
                    </div>
                </div>
            </div>
            <div id="docSettings" style="display:none;">
                <div style='padding:10px 0;'>
                    <h3>{vtranslate("LBL_SAVEASDOC",'PDFMaker')}</h3>
                    <hr style='margin:5px 0;width:100%'>
                </div>
                <table border="0" cellspacing="0" cellpadding="5" width="100%" align="center">
                    <tr><td class="small">
                            <table border="0" cellspacing="0" cellpadding="5" width="100%" align="center">
                                <tr>
                                    <td class="dvtCellLabel" width="20%" align="right"><font color="red">*</font>{vtranslate("Title",'Documents')}</td>
                                    <td class="dvtCellInfo" width="80%" align="left"><input name="notes_title" type="text" class="detailedViewTextBox"></td>
                                </tr>
                                <tr>
                                    <td class="dvtCellLabel" width="20%" align="right">{vtranslate("Folder Name",'Documents')}</td>
                                    <td class="dvtCellInfo" width="80%" align="left">
                                        <select name="folderid" class="small">
                                            {$FOLDER_OPTIONS}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="dvtCellLabel" width="20%" align="right">{vtranslate("Note",'Documents')}</td>
                                    <td class="dvtCellInfo" width="80%" align="left"><textarea name="notecontent" class="detailedViewTextBox"></textarea></td>
                                </tr>
                            </table>
                        </td></tr>
                </table>
                <div class="padding-bottom1per row-fluid">
                    <div class="span8">
                        <div class="btn-toolbar">
                            <span class="btn-group">
                                <button class="floatNone btn btn-success" type="submit" title="{vtranslate('LBL_SAVE','PDFMaker')}"><strong>{vtranslate('LBL_SAVE','PDFMaker')}</strong></button>&nbsp;&nbsp;
                            </span>
                            <span class="btn-group">
                                <button class="floatNone btn btn-danger" title="{vtranslate('LBL_CANCEL','PDFMaker')}" onclick="hideDocSettings();
                                                        return false;"><strong>{vtranslate('LBL_CANCEL','PDFMaker')}</strong></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
{/strip}
<script type="text/javascript">
var selectedTab = 'body';
var selectedTemplate = '{$ST}';
document.getElementById('body_div' + selectedTemplate).style.display = 'block';
function changeTemplate(newtemplate){
    document.getElementById(selectedTab + '_div' + selectedTemplate).style.display = 'none';
    document.getElementById(selectedTab + '_div' + newtemplate).style.display = 'block';

    selectedTemplate = newtemplate;
}

function showDocSettings(){
    document.getElementById('editTemplate').style.display = 'none';
    document.getElementById('docSettings').style.display = 'block';
    document.getElementById('action').value = 'SaveIntoDocuments';
}

function hideDocSettings(){
    document.getElementById('editTemplate').style.display = 'block';
    document.getElementById('docSettings').style.display = 'none';
    document.getElementById('action').value = 'CreatePDFFromTemplate';
}

function showHideTab(tabname){
    document.getElementById(selectedTab + '_tab_a').className = '';
    document.getElementById(tabname + '_tab_a').className = 'selected';

    document.getElementById(selectedTab + '_div' + selectedTemplate).style.display = 'none';
    document.getElementById(tabname + '_div' + selectedTemplate).style.display = 'block';

    var formerTab = selectedTab;
    selectedTab = tabname;
}
</script>