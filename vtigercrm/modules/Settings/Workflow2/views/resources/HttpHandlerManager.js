/**
 * Created by JetBrains PhpStorm.
 * User: Stefan Warnat <support@stefanwarnat.de>
 * Date: 03.05.14 14:19
 * You must not use this file without permission.
 */
var editId = 0;
function refreshHandlerList() {
    var params = {
        module: 'Workflow2',
        view: 'HttpHandlerManager',
        parent: 'Settings'
    };
    AppConnector.request(params).then(function(data) {
        jQuery(jQuery(".contentsDiv")[0]).html(data);
    });
}
jQuery.fn.selectText = function(){
    var doc = document
        , element = this[0]
        , range, selection
    ;
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();
        range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
};
function addHandler() {
    jQuery.post('index.php', {module:'Workflow2', parent:'Settings', action:'HttpHandlerAdd'}, function(response) {
        editId = response.id;
        refreshHandlerList();
        editHandler(editId);
    }, 'json');
}
function editHandler(editId) {
    jQuery.post('index.php', {module:'Workflow2', parent:'Settings', view:'HttpHandlerEditor', edit_id:editId }, function(response) {
        app.showModalWindow(response, function(data) {
            jQuery("#values_trigger").select2('val',limit_values["trigger"])
            jQuery("#values_workflow").select2('val',limit_values["id"]);

            jQuery.getScript('modules/Workflow2/views/resources/js/jquery.form.min.js', function() {
                var options = {
                    //target:        '#output1',   // target element(s) to be updated with server response
                    //beforeSubmit:  showRequest,  // pre-submit callback
                    success:       function(response) {

                        if(response.result == 'ok') {
                            refreshHandlerList();
                            jQuery.notification(
                                {
                                    title: "Http Handler Editor",
                                    timeout: 5000,
                                    color: '#2cae35',
                                    icon: 'W',
                                    content: 'Your changes are saved'
                                }
                            );
                            app.hideModalWindow();
                        }
                        if(response.result == 'error') {
                            jQuery.notification(
                                {
                                    title: "Error during Save",
                                    error: true,
                                    timeout: 5000,
                                    content: response.message
                                }
                            );
                        }
                    },  // post-submit callback

                    // other available options:
                    //url:       ''         // override for form's 'action' attribute
                    //type:      type        // 'get' or 'post', override for form's 'method' attribute
                    dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type)
                    //clearForm: true        // clear all form fields after successful submit
                    //resetForm: true        // reset the form after successful submit

                    // $.ajax options can be used here too, for example:
                    //timeout:   3000
                };

                // bind form using 'ajaxForm'
                jQuery('#popupForm').ajaxForm(options);

                jQuery('#modalSubmitButton').removeAttr('disabled');
            });
        });
    });
}