<div style="width:80%;margin:auto;border:1px solid #ccc;">
    <p style="text-align:left;padding:5px;margin-top:0;">{$MOD.LBL_EXPCOND_DESCRIPTION}</p>

    <textarea name="task[condition]" id="task_condition" style="height:300px;width: 500px;font-family: 'Courier New';">{$task.condition}</textarea>
    <span id='task_condition_iconspan'><img src='modules/Workflow2/icons/templatefieldPHP.png' style='margin-bottom:-2px;cursor:pointer;' onclick="insertTemplateField('task_condition', '[source]->[module]->[destination]', false);">
</div>
{*<script type="text/javascript">jQuery(function() { enable_customexpression("task_condition", false); });</script>*}
<script type="text/javascript">jQuery("#task_condition").on("insertText", function(e, text) {ldelim}
    customExpressionEditor["task_condition"].replaceSelection(text, "start");
{rdelim});
</script>
<!-- padding:0px;text-align:center;margin:auto;border-radius:3px;margin-top:0px; -->