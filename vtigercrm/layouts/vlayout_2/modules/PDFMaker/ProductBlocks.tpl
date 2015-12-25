{*<!--
/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/
-->*}
<div class="container-fluid" id="ProductBlocksContainer">
    <form name="product_blocks" action="index.php" method="post" class="form-horizontal">
    <input type="hidden" name="module" value="PDFMaker" />
    <input type="hidden" name="view" value="EditProductBlock" />
    <input type="hidden" name="action" value="" />
    <input type="hidden" name="tplid" value="" />
    <input type="hidden" name="mode" value="" />
    <br>
    <label class="pull-left themeTextColor font-x-x-large">{vtranslate('LBL_PRODUCTBLOCKTPL','PDFMaker')}</label>
    <br clear="all">{vtranslate('LBL_PRODUCTBLOCKTPL_DESC','PDFMaker')}
    <hr>
    <br />
    <div class="row-fluid">
        <label class="fieldLabel"><strong>{vtranslate('LBL_DEFINE_PBTPL','PDFMaker')}:</strong></label><br>    
        <div class="row-fluid">           
            <button type="button" class="btn btn-danger span1 marginLeftZero" onclick="confirm_delete();">{vtranslate('LBL_DELETE')}</button>    
            <div class="pull-right btn-group">
                <button type="submit" class="addProductBlock btn addButton marginLeftZero" data-url="?module=PDFMaker&view=IndexAjax&mode=editProductBlock"><i class="icon-plus icon-white"></i>&nbsp;<strong> {vtranslate('LBL_ADD')}</strong></button>
                {*<button type="button" class="btn btn-success span1 marginLeftZero" onClick="validateLblForm(this.form);">{vtranslate('LBL_SAVE')}</button>*}
                <button type="reset" class="btn span1 marginLeftZero" onClick="window.history.back();">{vtranslate('LBL_CANCEL')}</button>
            </div>  
        </div>    
        <div class="pushDownHalfper">    
            <table id="ProductBlocksTable" class="table table-bordered table-condensed ProductBlocksTable" style="padding:0px;margin:0px" id="lbltbl">
            <thead>
                <tr class="blockHeader">
                    <th style="border-left: 1px solid #DDD !important;" width="20px" align="center"><input type="checkbox" name="chx_all" onclick="checkedAll(this);"/></th>
                    <th style="border-left: 1px solid #DDD !important;" width="250px">{vtranslate('LBL_PDF_NAME','PDFMaker')}</th>
                    <th style="border-left: 1px solid #DDD !important;" id="bodyColumn">{vtranslate('LBL_BODY','PDFMaker')}</th>
                    <th style="border-left: 0px solid #DDD !important;" width="150px" nowrap></th>
                </tr>
            </thead>
            <tbody>
            <script type="text/javascript" language="javascript">var existingKeys = new Array();</script>
            {foreach item=arr key=tpl_id item=tpl_value from=$PB_TEMPLATES name=tpl_foreach}
                <tr class="opacity">
                    <td>
                        <input type="checkbox" name="chx_{$tpl_id}" id="chx_{$smarty.foreach.tpl_foreach.index}"/>
                    </td>
                    <td>
                        {$tpl_value.name}
                    </td>
                    <td>
                        <div style="overflow-x:auto; overflow-y:auto; width:100px;" class="bodyCell">
                            {$tpl_value.body}
                        </div>
                    </td>
                    <td style="border-left: none;">
                        <div class="pull-right actions">
                            <div class="btn-group">
                                <button type="submit" class="btn marginLeftZero" onClick="this.form.tplid.value='{$tpl_id}'">
                                        <i title="Edit" class="icon-pencil alignBottom"></i></button><button class="btn marginLeftZero" <button type="submit" class="btn marginLeftZero" onClick="this.form.tplid.value='{$tpl_id}';this.form.mode.value='duplicate'">{vtranslate('LBL_DUPLICATE','PDFMaker')}</button>
                            </div>
                        </div>
                    </td>
                </tr>

            {foreachelse}
                <tr id="noItemFountTr">
                    <td colspan="4" class="cellText" align="center" style="padding:10px;"><strong>{vtranslate('LBL_NO_ITEM_FOUND','PDFMaker')}</strong></td>
                </tr>
            {/foreach}
            </tbody>
        </table>
        </div>
        <div id="otherLangsDiv" style="display:none; width:350px; position:absolute;" class="layerPopup"></div>    
        <div class="row-fluid pushDownHalfper">    
            <button type="button" class="btn btn-danger span1 marginLeftZero" onclick="confirm_delete();">{vtranslate('LBL_DELETE')}</button>    
            <div class="pull-right btn-group">
                <button type="submit" class="addProductBlock btn addButton marginLeftZero" data-url="?module=PDFMaker&view=IndexAjax&mode=editProductBlock"><i class="icon-plus icon-white"></i>&nbsp;<strong> {vtranslate('LBL_ADD')}</strong></button>
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
    var tableName = document.getElementById('ProductBlocksTable');
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
   var tableName = document.getElementById('ProductBlocksTable');
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
                document.product_blocks.view.value = '';
                document.product_blocks.action.value = 'IndexAjax';
                document.product_blocks.mode.value = 'deleteProductBlocks';

                document.product_blocks.submit();
            },
            function(error, err) {
            }
        );
    }
}

 jQuery(document).ready(function() {
            var elmWidth = jQuery("#bodyColumn").width();
            jQuery(".bodyCell").each(function() {
                jQuery(this).css("width", elmWidth + "px");
            });
        });
{/literal}        
//PDFMaker_ProductBlocks_Js.registerEvents();
</script>
