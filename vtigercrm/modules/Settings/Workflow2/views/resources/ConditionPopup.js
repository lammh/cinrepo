var ConditionPopup = {
    currentConfiguration: {},
    currentModule: '',
    open: function(inputEle, moduleEle, title) {
        if(jQuery(inputEle).length == 0) {
            console.log('no inputEle for Condition Popup');
        }

        ConditionPopup.inputEle = jQuery(inputEle);

        if(ConditionPopup.inputEle.val() != '') {
            ConditionPopup.currentConfiguration = ConditionPopup.inputEle.val();
        } else {
            ConditionPopup.currentConfiguration = '';
            ConditionPopup.currentModule = jQuery(moduleEle).val();
        }



        jQuery.post('index.php', {
            module:'Workflow2',
            parent:'Settings',
            'title':title,
            'action':'ConditionPopup',
            fromModule:moduleName,
            toModule:ConditionPopup.currentModule,
            configuration:ConditionPopup.currentConfiguration
        }, function(response) {
            app.showModalWindow(response);

            jQuery('#PopupConditionForm').ajaxForm(function(response) {
                ConditionPopup.inputEle.val(response);
                app.hideModalWindow();
            });
        });
    },
    loadFields: function() {

    }
};