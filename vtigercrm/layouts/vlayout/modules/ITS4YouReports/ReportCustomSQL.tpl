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
                       <th>
                            {vtranslate('LBL_REPORT_SQL',$MODULE)}
                       </th>
                   </tr>
                </thead>
                <tbody>    
                    <tr style="height:25px">
                        <td align="left" {$custom_style} ><textarea name="reportcustomsql" id="reportcustomsql" class="txtBox" rows="12">{$REPORT_CUSTOM_SQL}</textarea></td>
                    </tr>
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
                        <i class="icon-info-sign"></i>&nbsp;{vtranslate('LBL_REPORT_SQL',$MODULE)}<br>
                       </th>
                   </tr>
                </thead>
                <tbody>    
                    <tr style="height:25px">
                        <td>
                            <div class="padding1per">
                              <span>
                                {vtranslate('LBL_CUSTOMSTEP12_INFO',$MODULE)}
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