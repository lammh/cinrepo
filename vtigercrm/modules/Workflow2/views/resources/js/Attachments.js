/**
 * Created by JetBrains PhpStorm.
 * User: Stefan Warnat <support@stefanwarnat.de>
 * Date: 06.12.14 15:37
 * You must not use this file without permission.
 */
var AttachmentsList = {
    attachmentFiles: {},
    fileNames: {},
    init: function(values) {
        console.log(typeof values);
        if(typeof values == 'object') {
            AttachmentsList.attachmentFiles = values;
        }
        AttachmentsList.repaint();
    },
    repaint: function() {
        var html = "";

        var result = {};

        jQuery.each(AttachmentsList.attachmentFiles, function(index, value) {
            if(value == false) return;
            if(typeof(value) == 'string') { value = [value, false]; }

            result[index] = value;

            if(typeof AttachmentsList.fileNames[index] != 'undefined') {
                fileTitle = AttachmentsList.fileNames[index];
            } else {
                fileTitle = value[0];
            }
            html += "<div style='padding:2px 0;'><img src='modules/Workflow2/icons/cross-button.png' style='margin-bottom:-3px;' onclick='AttachmentsList.remove(\"" + index + "\");'>&nbsp;" + fileTitle + "</div>";
        });

        jQuery("#mail_files").html(html);
        jQuery("#task-attachments").val(JSON.stringify(result));
    },
    setFilenames: function(value) {
        AttachmentsList.fileNames = value;
    },
    add: function(id, title, filename, options) {
       if(typeof options == 'undefined') {
           options = {};
       }
       if(typeof filename == 'undefined') {
           filename = title;
       }

       AttachmentsList.attachmentFiles[id] = [title, filename, options];
       AttachmentsList.repaint();
    },
    remove: function(index) {
        AttachmentsList.attachmentFiles[index] = false;
        AttachmentsList.repaint();
    }
};

var Attachments = {
    addAttachment:function(id, title, filename, options) {
        AttachmentsList.add(id, title, filename, options);
    }
}