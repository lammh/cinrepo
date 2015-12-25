{*<!--
/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/
-->*}
<script type="text/javascript" src="layouts/vlayout/modules/PDFMaker/resources/CustomLabels.js"></script>
<div class="container-fluid" id="CustomLabelsContainer">
    <form name="custom_labels" action="index.php" method="post" class="form-horizontal">
    <input type="hidden" name="module" value="PDFMaker" />
    <input type="hidden" name="action" value="IndexAjax" />
    <input type="hidden" name="mode" value="DeleteCustomLabels" />
    <input type="hidden" name="newItems" value="" />
    <br>
    <label class="pull-left themeTextColor font-x-x-large">{vtranslate('LBL_CUSTOM_LABELS','PDFMaker')}</label>
    <br clear="all">{vtranslate('LBL_CUSTOM_LABELS_DESC','PDFMaker')}
    <hr>
    <br />
    <div class="row-fluid">
        <label class="fieldLabel"><strong>{vtranslate('LBL_DEFINE_CUSTOM_LABELS','PDFMaker')}:</strong></label><br>    
        <div class="row-fluid">           
            <button type="button" class="btn btn-danger span1 marginLeftZero" onclick="confirm_delete();">{vtranslate('LBL_DELETE')}</button>    
            <div class="pull-right btn-group">
                <button type="button" class="addCustomLabel btn addButton marginLeftZero" data-url="?module=PDFMaker&view=IndexAjax&mode=editCustomLabel"><i class="icon-plus icon-white"></i>&nbsp;<strong> {vtranslate('LBL_ADD')}</strong></button>
                {*<button type="button" class="btn btn-success span1 marginLeftZero" onClick="validateLblForm(this.form);">{vtranslate('LBL_SAVE')}</button>*}
                <button type="reset" class="btn span1 marginLeftZero" onClick="window.history.back();">{vtranslate('LBL_CANCEL')}</button>
            </div>  
        </div>    
        <div class="pushDownHalfper">    
            <table id="CustomLabelTable" class="table table-bordered table-condensed CustomLabelTable" style="padding:0px;margin:0px" id="lbltbl">
            <thead>
                <tr class="blockHeader">
                    <th style="border-left: 1px solid #DDD !important;" width="4%" align="center"><input type="checkbox" name="chx_all" onclick="checkedAll(this);"/></th>
                    <th style="border-left: 1px solid #DDD !important;" width="30%">{vtranslate('LBL_KEY','PDFMaker')}</th>
                    <th style="border-left: 1px solid #DDD !important;" width="50%" colspan="2">{vtranslate('LBL_CURR_LANG_VALUE','PDFMaker')} ({$CURR_LANG.label})</th>
                    <th style="border-left: 1px solid #DDD !important;" width="16%" align="center">{vtranslate('LBL_OTHER_LANG_VALUES','PDFMaker')}</th>
                </tr>
            </thead>
            <tbody>
            <script type="text/javascript" language="javascript">var existingKeys = new Array();</script>
            {assign var="lang_id" value=$CURR_LANG.id}
            {foreach key=label_id item=label_value from=$LABELS name=lbl_foreach}
                <tr class="opacity">
                    <td>
                        <input type="checkbox" name="chx_{$label_id}" id="chx_{$smarty.foreach.lbl_foreach.index}"/>
                    </td>
                    <td>
                        <label class="CustomLabelKey textOverflowEllipsis">{$label_value.key}</label>
                    </td>
                    <td>
                        <label class="CustomLabelValue textOverflowEllipsis">{$label_value.lang_values.$lang_id}</label>
                    </td><td style="border-left: none;">
                        <div class="pull-right actions">
                            <a class="editCustomLabel cursorPointer" data-url="?module=PDFMaker&view=IndexAjax&mode=editCustomLabel&labelid={$label_id}&langid={$lang_id}">
                                <i title="Edit" class="icon-pencil alignBottom"></i></a>&nbsp;
                        </div>
                    </td>
                    <td>
                        <a class="showCustomLabelValues textOverflowEllipsis cursorPointer" data-url="?module=PDFMaker&view=IndexAjax&mode=showCustomLabelValues&labelid={$label_id}&langid={$lang_id}">{vtranslate('LBL_OTHER_VALS','PDFMaker')}</a>
                    </td>
                </tr>

            {foreachelse}
                <tr id="noItemFountTr">
                    <td colspan="3" class="cellText" align="center" style="padding:10px;"><strong>{vtranslate('LBL_NO_ITEM_FOUND','PDFMaker')}</strong></td>
                </tr>
            {/foreach}
            </tbody>
        </table>
        </div>
        <div id="otherLangsDiv" style="display:none; width:350px; position:absolute;" class="layerPopup"></div>    
        <div class="row-fluid pushDownHalfper">    
            <button type="button" class="btn btn-danger span1 marginLeftZero" onclick="confirm_delete();">{vtranslate('LBL_DELETE')}</button>    
            <div class="pull-right btn-group">
                <button type="button" class="addCustomLabel btn addButton marginLeftZero" data-url="?module=PDFMaker&view=IndexAjax&mode=editCustomLabel"><i class="icon-plus icon-white"></i>&nbsp;<strong> {vtranslate('LBL_ADD')}</strong></button>
                {*<button type="button" class="btn btn-success span1 marginLeftZero" onClick="validateLblForm(this.form);">{vtranslate('LBL_SAVE')}</button>*}
                <button type="reset" class="btn span1 marginLeftZero" onClick="window.history.back();">{vtranslate('LBL_CANCEL')}</button>
            </div>  
        </div>
    </div>    

</form>
</div>
	

<script type="text/javascript" language="javascript">
{literal}
function checkedAll(oTrigger)
{
    var tableName = document.getElementById('CustomLabelTable');
    totalNoOfRows = tableName.rows.length;
    ControlRows = totalNoOfRows - 1;    
    
    for(i = 0; i < ControlRows; i++)
    {
        var tmpChx = document.getElementById('chx_' + i);
        if(tmpChx != 'undefined')
        {
            tmpChx.checked = oTrigger.checked;
        }
    }
}

function confirm_delete()
{
   var tableName = document.getElementById('CustomLabelTable');
    totalNoOfRows = tableName.rows.length;
    ControlRows = totalNoOfRows - 1;
        
    var toDelete = 0;
    for(i = 0; i < ControlRows; i++)
    {
        var tmpChx = document.getElementById('chx_' + i);
 
        if(tmpChx != 'undefined')
        {
            if(tmpChx.checked == true)
                toDelete++;
        }
    }
    
    if(toDelete > 0)
    {
        var message = app.vtranslate('LBL_MASS_DELETE_CONFIRMATION');
        Vtiger_Helper_Js.showConfirmationBox({'message': message}).then(function(data) {
                document.custom_labels.submit();
            },
            function(error, err) {
            }
        );
    }
}
{/literal}
PDFMaker_CustomLabelsJs.registerEvents();
</script>
