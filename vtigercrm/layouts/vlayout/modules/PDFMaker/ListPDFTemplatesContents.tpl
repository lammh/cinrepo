{*<!--
/*********************************************************************************
* The content of this file is subject to the PDF Maker license.
* ("License"); You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
* Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
* All Rights Reserved.
********************************************************************************/
-->*}
{if $DIR eq 'asc'}
    {assign var="dir_img" value='<img src="layouts/vlayout/skins/images/upArrowSmall.png" border="0" />'}
{else}
    {assign var="dir_img" value='<img src="layouts/vlayout/skins/images/downArrowSmall.png" border="0" />'}
{/if}

{assign var="name_dir" value="asc"}
{assign var="module_dir" value="asc"}
{assign var="description_dir" value="asc"}
{assign var="order_dir" value="asc"}

{if $ORDERBY eq 'filename' && $DIR eq 'asc'}
    {assign var="name_dir" value="desc"}
{elseif $ORDERBY eq 'module' && $DIR eq 'asc'}
    {assign var="module_dir" value="desc"}
{elseif $ORDERBY eq 'description' && $DIR eq 'asc'}
    {assign var="description_dir" value="desc"}
{elseif $ORDERBY eq 'order' && $DIR eq 'asc'}
    {assign var="order_dir" value="desc"}
{/if}

{*<div class="contents-topscroll">
<div class="topscroll-div">&nbsp;</div>
</div>*}
<div class="listViewEntriesDiv contents-bottomscroll">
    <div class="bottomscroll-div">

        <table border=0 cellspacing=0 cellpadding=5 width=100% class="table table-bordered listViewEntriesTable">
            <thead>
                <tr class="listViewHeaders">
                    <th width="2%" class="narrowWidthType">#</td>
                    <th width="3%" class="narrowWidthType">{vtranslate("LBL_LIST_SELECT","PDFMaker")}</td>
                    <th width="20%" class="narrowWidthType"><a href="index.php?module=PDFMaker&view=List&orderby=name&dir={$name_dir}">{vtranslate("LBL_PDF_NAME","PDFMaker")}{if $ORDERBY eq 'filename'}{$dir_img}{/if}</a></td>
                    <th width="20%" class="narrowWidthType"><a href="index.php?module=PDFMaker&view=List&orderby=module&dir={$module_dir}">{vtranslate("LBL_MODULENAMES","PDFMaker")}{if $ORDERBY eq 'module'}{$dir_img}{/if}</a></td>
                    <th width="34%" class="narrowWidthType"><a href="index.php?module=PDFMaker&view=List&orderby=description&dir={$description_dir}">{vtranslate("LBL_DESCRIPTION","PDFMaker")}{if $ORDERBY eq 'description'}{$dir_img}{/if}</a></td>
                    {*<th width="6%" class="narrowWidthType" nowrap><a href="index.php?module=PDFMaker&view=List&orderby=order&dir={$order_dir}">{vtranslate("LBL_ORDER","PDFMaker")}{if $ORDERBY eq 'order'}{$dir_img}{/if}</a>&nbsp;&nbsp;<a href="#" onclick="return SaveTemplatesOrder();"><img src="layouts/vlayout/skins/images/SaveAs.png" width="15" border="0" alt="Save" title="{vtranslate("LBL_SAVE_ORDER","PDFMaker")}" /></a></td>*}
                    {if $VERSION_TYPE neq 'deactivate'}<th width="5%" class="narrowWidthType">{vtranslate("Status")}</td>
                    <th width="11%" class="narrowWidthType">{vtranslate("LBL_ACTION","PDFMaker")}</td>{/if}
                </tr>
            </thead>
            <tbody>
            {foreach item=template name=mailmerge from=$PDFTEMPLATES}
                <tr class="listViewEntries" {if $template.status eq 0} style="font-style:italic;" {/if} data-id="{$template.templateid}" data-recordurl="index.php?module=PDFMaker&view=Detail&templateid={$template.templateid}" id="PDFMaker_listView_row_{$template.templateid}">
                    <td class="narrowWidthType" valign=top>{$smarty.foreach.mailmerge.iteration}</td>
                    <td class="narrowWidthType" valign=top><input type="checkbox" class=small name="selected_id" value="{$template.templateid}"></td>
                    <td class="narrowWidthType" valign=top>{$template.filename}</td>
                    <td class="narrowWidthType" valign=top {if $template.status eq 0} style="color:#888;" {/if}>{$template.module}</a></td>
                    <td class="narrowWidthType" valign=top {if $template.status eq 0} style="color:#888;" {/if}>{$template.description}&nbsp;</td>
                    {*<td class="narrowWidthType" valign=top align=center><input {if $template.status eq 0}disabled="disabled"{/if} type="text" class="txtBox" style="width:30px;z-index:10;" maxlength="4" name="tmpl_order_{$template.templateid}" value="{$template.order}"/></td>*}
                {if $VERSION_TYPE neq 'deactivate'}<td class="narrowWidthType" valign=top {if $template.status eq 0} style="color:#888;" {/if}>{$template.status_lbl}&nbsp;</td>
                    <td class="narrowWidthType" valign=top nowrap>{$template.edit}</td>{/if}
                </tr>
            {foreachelse}
                <tr>
                    <td style="background-color:#efefef;height:340px" align="center" colspan="6">
                        <div style="border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 45%; position: relative; z-index: 10000000;">
                            <table border="0" cellpadding="5" cellspacing="0" width="98%">
                                <tr><td rowspan="2" width="25%"><img src="{'empty.jpg'|@vtiger_imageurl:$THEME}" height="60" width="61"></td>
                                    <td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%" align="left">
                                        <span class="genHeaderSmall">{vtranslate("LBL_NO")} {vtranslate("LBL_TEMPLATE","PDFMaker")} {vtranslate("LBL_FOUND","PDFMaker")}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="small" align="left" nowrap="nowrap">
                                        &nbsp;&nbsp;-<a href="index.php?module=PDFMaker&view=Edit">{vtranslate("LBL_CREATE_NEW")} {vtranslate("LBL_TEMPLATE","PDFMaker")}</a><br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>

    </div> {*bottomscroll-div*}
</div> {*listViewEntriesDiv contents-bottomscroll*}
<br>
<div align="center" class="small" style="color: rgb(153, 153, 153);">{vtranslate("PDF_MAKER","PDFMaker")} {$VERSION} {vtranslate("COPYRIGHT","PDFMaker")}</div>