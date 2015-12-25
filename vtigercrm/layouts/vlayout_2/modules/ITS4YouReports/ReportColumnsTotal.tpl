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
<div class="row-fluid">       
    <div class="span9">
        <div class="row-fluid">
            <table class="table table-bordered table-report">
                <thead>
                   <tr class="blockHeader">
                       <th colspan="5">
                            {vtranslate('LBL_CALCULATIONS',$MODULE)}
                            <input type="hidden" name="curl_to_go" id="curl_to_go" value="{$CURL}">
                       </th>
                   </tr>
                </thead>
                <tbody> 
        
                    <tr style="height:25px">
                            <td class="dvtCellLabel" nowrap width="26%" align="right" ><b>{vtranslate("LBL_COLUMNS", $MODULE)}</b></td>
                            <td class="dvtCellLabel" nowrap width="11%" align="center" ><b>{vtranslate("LBL_COLUMNS_SUM", $MODULE)}</b></td>
                            <td class="dvtCellLabel" nowrap width="11%" align="center" ><b>{vtranslate("LBL_COLUMNS_AVERAGE", $MODULE)}</b></td>
                            <td class="dvtCellLabel" nowrap width="11%" align="center" ><b>{vtranslate("LBL_COLUMNS_LOW_VALUE", $MODULE)}</b></td>
                            <td class="dvtCellLabel" nowrap width="11%" align="center" ><b>{vtranslate("LBL_COLUMNS_LARGE_VALUE", $MODULE)}</b></td>
                            {*<td class="dvtCellLabel" nowrap width="9%" align="center" ><b>{vtranslate("LBL_COLUMNS_COUNT", $MODULE)}</b></td>*}
                    </tr>
                    {foreach key=rowname item=calculations from=$BLOCK1}
                            <tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
                                <td class="dvtCellLabel" align="right" >{$rowname}</td>
                                {foreach item=checkbox from=$calculations}
                                        <td class="dvtCellInfo" align="center" ><input name="{$checkbox.name}" type="checkbox" {$checkbox.checked} value=""></td>
                                {/foreach}
                            </tr>
                    {foreachelse}
                            <tr class="lvtColData" bgcolor="white"><td colspan="5" align="center" style="text-align:center;font-size: 1.5em;width:100%;color:red;" ><b>{vtranslate("NO_CALCULATION_COLUMN", $MODULE)}</b></td></tr>
                    {/foreach}
                </tbody> 
            </table>
        </div>
    </div>
    <div class="span4" style="width: 20%;">
        <div class="row-fluid">           
            <table class="table table-bordered table-report">
                <thead>
                    <tr class="blockHeader">
                       <th colspan="2">
                        <i class="icon-info-sign"></i>&nbsp;{vtranslate('LBL_CALCULATIONS',$MODULE)}<br>
                       </th>
                   </tr>
                </thead>
                <tbody>    
                    <tr style="height:25px">
                        <td>
                            <div class="padding1per">
                              <span>
                                {vtranslate('LBL_STEP6_INFO',$MODULE)}
                              </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> 
{/strip}