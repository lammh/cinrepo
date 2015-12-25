<h4>{vtranslate('execute expression on products, that match', 'Settings:Workflow2')}</h4>
<hr/>
{$conditionalContent}
<br/>
<h4>{vtranslate('execute this expression', 'Settings:Workflow2')}</h4>
<hr/>
<textarea name="task[expression]" id="custom_expression" rows="6" class='customFunction textfield'>{$task.expression}</textarea>
<br/><input type="button" class='btn btn-primary'  onclick="insertTemplateField('custom_expression', '[source]->[module]->[destination]', false, false,  {ldelim}module: 'Products'{rdelim});" value="{vtranslate('LBL_INSERT_TEMPLATE_VARIABLE', 'Settings:Workflow2')}"/>
<span> {vtranslate('read documentation for more information', 'Workflow2')}</span>
