{*<!--
/*********************************************************************************
* The content of this file is subject to the PDF Maker license.
* ("License"); You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
* Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
* All Rights Reserved.
********************************************************************************/
-->*}
<script type="text/javascript" src="layouts/vlayout/modules/PDFMaker/resources/PDFMakerActions.js"></script>
<script type="text/javascript" src="layouts/vlayout/modules/PDFMaker/resources/PDFMaker.js"></script>
<script>
    function ExportTemplates()
    {ldelim}
    if (typeof(document.massdelete.selected_id) == 'undefined')
        return false;
    x = document.massdelete.selected_id.length;
    idstring = "";

    if (x == undefined)
    {ldelim}

            if (document.massdelete.selected_id.checked)
            {ldelim}
                        idstring = document.massdelete.selected_id.value;

                        window.location.href = "index.php?module=PDFMaker&action=ExportPDFTemplate&templates=" + idstring;
                        xx = 1;
            {rdelim}
            else
            {ldelim}
                        alert(app.vtranslate("JS_PLEASE_SELECT_ONE_RECORD"));
                        return false;
            {rdelim}
    {rdelim}
    else
    {ldelim}
        xx = 0;
        for (i = 0; i < x; i++)
        {ldelim}
            if (document.massdelete.selected_id[i].checked)
        {ldelim}
                idstring = document.massdelete.selected_id[i].value + ";" + idstring
                xx++
        {rdelim}
    {rdelim}
        if (xx != 0)
        {ldelim}
            document.massdelete.idlist.value = idstring;

            window.location.href = "index.php?module=PDFMaker&action=ExportPDFTemplate&templates=" + idstring;
        {rdelim}
        else
        {ldelim}
                    alert(app.vtranslate("JS_PLEASE_SELECT_ONE_RECORD"));
                    return false;
        {rdelim}
    {rdelim}

    {rdelim}

    function SaveTemplatesOrder()
    {ldelim}
        $("vtbusy_info").style.display = "inline";
        var tmpl_order = '';

        for (i = 0; i < document.massdelete.elements.length; i++)
    {ldelim}
            var elm = document.massdelete.elements[i];

            if (elm.type == 'text' && elm.name.indexOf('tmpl_order_', 0) == 0)
    {ldelim}
                if ((isNaN(elm.value) == false && elm.Value != ''))
    {ldelim}
                    var templateid = elm.name.split('_', 3)[2];
                    var order = elm.value;
                    tmpl_order += templateid + '_' + order + '#';
    {rdelim}
                else
    {ldelim}
                    alert('{vtranslate("LBL_ORDER_ERROR","PDFMaker")}');
                    elm.focus();
                    $("vtbusy_info").style.display = "none";
                    return false;
    {rdelim}
    {rdelim}

    {rdelim}

    {literal}
            new Ajax.Request(
                    'index.php',
                    {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=PDFMaker&action=AjaxRequestHandle&handler=templates_order&tmpl_order=' + tmpl_order,
                        onComplete: function(response) {
                            if (response.responseText == "ok")
                            {
    {/literal}
                                                         alert('{vtranslate("LBL_ORDER_SAVE_OK","PDFMaker")}');
    {literal}
                                                     }
                                                     else
                                                     {
    {/literal}
                                                         alert('{vtranslate("LBL_ORDER_SAVE_ERROR","PDFMaker")}');
    {literal}
                                                     }
                                                     $("vtbusy_info").style.display = "none";
                                                 }
                                             }
                                     );
    {/literal}

              return true;
    {rdelim}
</script>

<div class="contentsDiv marginLeftZero">
    <div class="listViewPageDiv">
        <form  name="massdelete" method="POST" onsubmit="VtigerJS_DialogBox.block();"> 
        <input name="idlist" type="hidden">
        <input name="module" type="hidden" value="PDFMaker">
        <input name="parenttab" type="hidden" value="Tools">
        <input name="view" type="hidden" value="">
        <input name="action" type="hidden" value="">    
        <input name="orderby" id="orderBy" type="hidden" value="{$ORDERBY}">
        <input name="sortorder" id="sortOrder" type="hidden" value="{$DIR}">  
        
        
        {include file='ListPDFHeader.tpl'|@vtemplate_path:'PDFMaker'}
        <div class="listViewContentDiv" id="listViewContents">
            {include file='ListPDFTemplatesContents.tpl'|@vtemplate_path:'PDFMaker'}
         </div>
        </form>    
    </div>
</div>