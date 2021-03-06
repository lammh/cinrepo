{*/*<!--
/*********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/
-->*/*}

<script>
var moveupLinkObj,moveupDisabledObj,movedownLinkObj,movedownDisabledObj;
</script>
<div class="row-fluid">       
    <div class="span9">
        <div class="row-fluid">  
            <table class="table table-bordered table-report" style="width:100%;">
                <thead>
                    <tr class="blockHeader">
                       <th >
                            {vtranslate('LBL_AVAILABLE_FIELDS',$MODULE)}
                       </th>
                   </tr>
                </thead>
                <tbody> 
                    <tr>
                        <td >
                            <div class="row-fluid padding1per">
                                <div class="span12">  
                                    <div style="height:40px;">{vtranslate('LBL_SELECT_MODULE',$MODULE)}
                                        <select id="availModules" name="availModules" onchange='defineModuleFields(this)' class="txtBox" style="width:auto;margin:auto;">
                                            {foreach item=modulearr from=$availModules}
                                                <option value={$modulearr.id} {if $modulearr.checked == "checked"}selected="selected"{/if} >{$modulearr.name}</option>
                                            {/foreach}
                                        </select>
                                        <input type="text" id="search_input" onkeyup="getFieldsOptionsSearch(this)" placeholder="{vtranslate('LBL_Search_column',$MODULE)}" style="margin:auto;">
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <div id="availModValues" style="display:none;">{$ALL_FIELDS_STRING}</div>
                            <table style="width:100%;">
                    <tr>
                        <td style="width: 45%;">
                            <select id="availList" multiple size="20" name="availList" class="txtBox" ondblclick="addOndblclick(this)" style="width:100%;" >
                            {$BLOCK1}
                            </select>
                        </td>
                        <td style="width: 5%;">
                            <div class="span4" style="width: 100%;">
                                <input name="add" value=" {vtranslate('LBL_ADD_ITEM',$MODULE)} &gt&gt " class="btn" type="button" onClick="addColumn('selectedColumns');">
                            </div>
                        </td>
                        <td style="width: 45%;">
                            <input type="hidden" name="selectedColumnsString" id="selectedColumnsString">
                            <select id="selectedColumns" size="20" name="selectedColumns" onchange="selectedColumnClick(this);" multiple class="txtBox" style="width:100%;" >
                            {$BLOCK2}
                            </select>
                        </td>
                        <td style="width: 5%;">
                            <div class="span2">
                                <br><br>
                                <div class="row-fluid">
                                    <div class="span3">&nbsp;</div>
                                    <div class="span6">
                                        <div class="padding5per"><button type="button" class="btn btn-mini vtButton arrowUp row-fluid" onclick="moveUp('selectedColumns')"><img src="layouts/vlayout/skins/images/Arrow-up.png"></img></button></div>
                                        <div class="padding5per"><button type="button" class="btn btn-mini vtButton arrowDown row-fluid"  onclick="delColumn('selectedColumns')"><img src="layouts/vlayout/skins/images/no.png"></img></button></div>
                                        <div class="padding5per"><button type="button" class="btn btn-mini vtButton arrowDown row-fluid"  onclick="moveDown('selectedColumns')"><img src="layouts/vlayout/skins/images/Arrow-down.png"></img></button></div>   
                                    </div>
                                    <div class="span3">&nbsp;</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table class="table table-bordered table-report">
                <thead>
                    <tr class="blockHeader">
                       <th colspan="6">
                            {vtranslate('LBL_LIMIT',$MODULE)} {vtranslate('LBL_AND',$MODULE)} {vtranslate('LBL_SORT_ORDER',$MODULE)}
                       </th>
                   </tr>
                </thead>
                <tbody>  
                    <tr>
                        <td style="text-align: right;vertical-align: middle;">
                            {vtranslate('LBL_SORT_FIELD',$MODULE)}&nbsp;
                        </td>
                        <td style="text-align: left;vertical-align: middle;">
                            <select id="SortByColumn" name="SortByColumn" class="txtBox" style="width:auto;margin:auto;" > 
                            <option value="none">{vtranslate('LBL_NONE',$MODULE)}</option>	
                            {$BLOCK3}
                            </select>
                            &nbsp;&nbsp;
                            {$COLUMNASCDESC}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;vertical-align: middle;">
                            {vtranslate('SET_LIMIT',$MODULE)}
                        </td>
                        <td style="text-align: left;vertical-align: middle;">
                            <input type="text" id="columns_limit" name="columns_limit" value="{if $COLUMNS_LIMIT!="0"}{$COLUMNS_LIMIT}{/if}" class="txtBox" style="width:100px;margin:auto;">&nbsp;&nbsp;<small>{vtranslate('SET_EMPTY_FOR_ALL',$MODULE)}</small>
                        </td>
                    </tr>
                    <tbody>
                </table>
            </div>
        </div>
    <div class="span4" style="width: 20%;">
        <div class="row-fluid">           
            <table class="table table-bordered table-report">
                <thead>
                    <tr class="blockHeader">
                       <th colspan="2">
                        <i class="icon-info-sign"></i>&nbsp;{vtranslate('LBL_SELECT_COLUMNS',$MODULE)}<br>
                       </th>
                   </tr>
                </thead>
                <tbody>    
                    <tr style="height:25px">
                        <td>
                            <div class="padding1per">
                              <span>
                                {vtranslate('LBL_STEP5_INFO',$MODULE)}
                              </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> 
