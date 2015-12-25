{*<!--
/*********************************************************************************
* The content of this file is subject to the PDF Maker license.
* ("License"); You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
* Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
* All Rights Reserved.
********************************************************************************/
-->*}

<form enctype="multipart/form-data" name="importBasic" method="POST" action="index.php">
    <input type="hidden" name="module" value="PDFMaker">
    <input type="hidden" name="action" value="Import">
    <table style="margin-left: auto;margin-right: auto;width: 100%;" class="searchUIBasic" cellspacing="12">
        <tbody>
            <tr>
                <td class="font-x-large" colspan="2" align="left"><strong>{vtranslate('LBL_PDFMAKER_IMPORT','PDFMaker')}</strong></td>
            </tr>
            <tr>
                <td class="leftFormBorder1 importContents" valign="top" width="40%">
                    <table cellpadding="2" cellspacing="0" width="100%">
                        <tbody><tr>
                                <td><strong>{vtranslate('LBL_SELECT_XML','PDFMaker')}:</strong></td>
                                <td class="big">{vtranslate('LBL_SELECT_XML_TEXT','PDFMaker')}</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td data-import-upload-size="3145728">
                                    <input name="type" value="xml" type="hidden">
                                    <input name="import_file" id="import_file" onchange="PDFMaker_ImportJs.checkFileType()" type="file">
                                    <!-- input type="hidden" name="userfile_hidden" value=""/ -->
                                </td>
                            </tr>
                        </tbody>                    </table>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <button type="submit" name="next" class="btn btn-success" onclick="return PDFMaker_ImportJs.uploadAndParse();"><strong>{vtranslate('LBL_NEXT','PDFMaker')}</strong></button> &nbsp;&nbsp;
                    <a name="cancel" class="cursorPointer cancelLink" value="Cancel" onclick="location.href = 'index.php?module=Contacts&amp;view=List'">{vtranslate('Cancel')}</a>
                </td>
            </tr>
        </tbody>
    </table>
</form>
