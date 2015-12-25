{*<!--
/*********************************************************************************
* The content of this file is subject to the PDF Maker license.
* ("License"); You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
* Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
* All Rights Reserved.
********************************************************************************/
-->*}
{strip}
<span class="pull-right listViewActions">
{if $LISTVIEW_LINKS['LISTVIEWSETTING']|@count gt 0}
        <span class="btn-group">
                <button class="btn dropdown-toggle" href="#" data-toggle="dropdown"><img class="alignMiddle" src="{vimage_path('tools.png')}" alt="{vtranslate('LBL_SETTINGS', $MODULE)}" title="{vtranslate('LBL_SETTINGS', $MODULE)}">&nbsp;&nbsp;<i class="caret"></i></button>
                <ul class="listViewSetting dropdown-menu">
                        {foreach item=LISTVIEW_SETTING from=$LISTVIEW_LINKS['LISTVIEWSETTING']}
                                <li><a href={$LISTVIEW_SETTING->getUrl()}>{vtranslate($LISTVIEW_SETTING->getLabel(), $MODULE)}</a></li>
                        {/foreach}
                </ul>
        </span>
{/if}
</span>
<div class="clearfix"></div>
<input type="hidden" id="recordsCount" value=""/>
<input type="hidden" id="selectedIds" name="selectedIds" />
<input type="hidden" id="excludedIds" name="excludedIds" />
{/strip}