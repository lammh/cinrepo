{foreach from=$attachmentsJAVASCRIPT item=script}<script type="text/javascript">{$script}</script>{/foreach}
<div>
    <input type="hidden" id="task-attachments" name="task[{$attachmentsField}]" value="">
    <div id='mail_files' style="margin-top:5px;"></div>
    {$attachmentsHTML}
</div>

<script type="text/javascript">
    var SetAttachmentList = {$SetAttachmentList};

    jQuery(function() {
        AttachmentsList.init(SetAttachmentList);
    });
</script>