{foreach from=$sources item=source}
<h4><input type="radio" name="" value="condition" {if $selected_source eq $source.id}checked="true"{/if} />{vtranslate($source.title,'Settings:Workflow2')}</h4>
{/foreach}

<hr/>
<div id="conditionRecords">
{$conditionalContent}
</div>
<h4><input type="radio" name="" value="customview" {if $task.recordsource eq 'customview'}checked="true"{/if} /> {vtranslate('get Records from CustomView','Settings:Workflow2')}</h4>
