
<div class="modelContainer" style="width:1000px;">
    <form method="POST" id="PopupConditionForm" action="index.php?module=Workflow2&parent=Settings&action=ConditionPopupStore">
        <input type="hidden" name="task[module]" value="{$toModule}" />
<div class="modal-header contentsBackground">
	<button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="{vtranslate('LBL_CLOSE')}">x</button>
    <h3>configure Condition</h3>
</div>
    <p style="margin:5px 10px;">
        {$title}
    </p>
    {$conditionalContent}
    <div class="modal-footer quickCreateActions">
            <a class="cancelLink cancelLinkContainer pull-right" type="reset" data-dismiss="modal">{vtranslate('LBL_CLOSE', $MODULE)}</a>
        <button class="btn btn-success" type="submit" id="submitPopupCondition" ><strong>{vtranslate('store condition', $MODULE)}</strong></button>
   	</div>
    </form>
</div>
<script type="text/javascript">
    {$javascript}
</script>
