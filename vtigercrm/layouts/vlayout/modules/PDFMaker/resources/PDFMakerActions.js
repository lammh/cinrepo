/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/
if (typeof(PDFMaker_Actions_Js) == 'undefined') {
    
PDFMaker_Actions_Js = {
    
    getSelectedTemplates: function (){
        var selectedColumnsObj = document.getElementById("use_common_template");
        var selectedColStr = "";
        for (i = 0; i < selectedColumnsObj.options.length; i++){
            if (selectedColumnsObj.options[i].selected){
                selectedColStr += selectedColumnsObj.options[i].value + ";";
            }
        }

        return selectedColStr;
    },

    getPDFDocDivContent: function (rootElm, module, id){
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                    method: 'post',
                    postBody: "module=PDFMaker&return_module=" + module + "&action=PDFMakerAjax&file=docSelect&return_id=" + id,
                    onComplete: function(response){
                        document.getElementById('PDFDocDiv').innerHTML = response.responseText;
                        fnvshobj(rootElm, 'PDFDocDiv');

                        var PDFDoc = document.getElementById('PDFDocDiv');
                        var PDFDocHandle = document.getElementById('PDFDocDivHandle');
                        Drag.init(PDFDocHandle, PDFDoc);
                    }
                }
        );
    },

    getPDFBreaklineDiv: function (rootElm, id){
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                    method: 'post',
                    postBody: "module=PDFMaker&action=PDFMakerAjax&file=breaklineSelect&return_id=" + id,
                    onComplete: function(response){
                        document.getElementById('PDFBreaklineDiv').innerHTML = response.responseText;
                        fnvshobj(rootElm, 'PDFBreaklineDiv');

                        var PDFBreakline = document.getElementById('PDFBreaklineDiv');
                        var PDFBreaklineHandle = document.getElementById('PDFBreaklineDivHandle');
                        Drag.init(PDFBreaklineHandle, PDFBreakline);
                    }
                }
        );
    },

    getPDFImagesDiv: function (rootElm, id){
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                    method: 'post',
                    postBody: "module=PDFMaker&action=PDFMakerAjax&file=imagesSelect&return_id=" + id,
                    onComplete: function(response){
                        document.getElementById('PDFImagesDiv').innerHTML = response.responseText;
                        fnvshobj(rootElm, 'PDFImagesDiv');

                        var PDFImages = document.getElementById('PDFImagesDiv');
                        var PDFImagesHandle = document.getElementById('PDFImagesDivHandle');
                        Drag.init(PDFImagesHandle, PDFImages);
                    }
                }
        );
    },

    sendPDFmail: function (module, idstrings){
        var smodule = document.DetailView.module.value;
        var record = document.DetailView.record.value;

        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                    method: 'post',
                    postBody: "module=PDFMaker&return_module=" + module + "&action=PDFMakerAjax&file=mailSelect&idlist=" + idstrings,
                    onComplete: function(response){
                        if (response.responseText == "Mail Ids not permitted" || response.responseText == "No Mail Ids"){
                            emailhref = 'module=PDFMaker&action=PDFMakerAjax&file=SendPDFMail&language=' + document.getElementById('template_language').value + '&record=' + record + '&relmodule=' + module + '&commontemplateid=' + PDFMaker_Actions_Js.getSelectedTemplates();

                            new Ajax.Request(
                                    'index.php',
                                    {queue: {position: 'end', scope: 'command'},
                                        method: 'post',
                                        postBody: emailhref,
                                        onComplete: function(response2){
                                            openPopUp('xComposeEmail', this, 'index.php?module=Emails&action=EmailsAjax&file=EditView&pmodule=' + module + '&pid=' + idstrings + '&language=' + document.getElementById('template_language').value + '&sendmail=true&attachment=' + response2.responseText + '.pdf', 'createemailWin', 820, 689, 'menubar=no,toolbar=no,location=no,status=no,resizable=no');
                                        }
                                    });
                        } else {
                            document.getElementById('sendpdfmail_cont').innerHTML = response.responseText;
                            var PDFMail = document.getElementById('sendpdfmail_cont');
                            var PDFMailHandle = document.getElementById('sendpdfmail_cont_handle');
                            Drag.init(PDFMailHandle, PDFMail);
                        }
                    }
                }
        );
    },

    sendEMAILMakerPDFmail: function (module, idstrings){
        var smodule = document.DetailView.module.value;
        var record = document.DetailView.record.value;

        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                    method: 'post',
                    postBody: "module=EMAILMaker&return_module=" + module + "&action=EMAILMakerAjax&file=mailSelect&idlist=" + idstrings + '&pdftemplateid=' + PDFMaker_Actions_Js.getSelectedTemplates() + '&language=' + document.getElementById('template_language').value,
                    onComplete: function(response){
                        if (response.responseText == "Mail Ids not permitted" || response.responseText == "No Mail Ids"){
                            ele = Math.floor(new Date().getTime() / 1000);
                            openPopUp('xComposeEmail' + ele, this, 'index.php?module=EMAILMaker&action=EMAILMakerAjax&file=EditView&pmodule=' + module + '&pid=' + idstrings + '&sendmail=true&pdftemplateid=' + PDFMaker_Actions_Js.getSelectedTemplates() + '&language=' + document.getElementById('template_language').value + '&commontemplateid=' + getSelectedEmailTemplates("use_common_email_template"), 'createemailWin' + ele, 1100, 850, 'menubar=no,toolbar=no,location=no,status=no,resizable=no');
                        } else {
                            document.getElementById('sendemakermail_cont').innerHTML = response.responseText;
                            fnvshobj(document.getElementById('template_language'), 'sendemakermail_cont');
                            var EMAKERMail = document.getElementById('sendemakermail_cont');
                            var EMAKERMailHandle = document.getElementById('sendemakermail_cont_handle');
                            Drag.init(EMAKERMailHandle, EMAKERMail);
                        }
                    }
                }
        );
    },



    validate_sendPDFmail: function (idlist, module){
        var smodule = document.DetailView.module.value;
        var record = document.DetailView.record.value;
        var j = 0;
        var chk_emails = document.SendPDFMail.elements.length;
        var oFsendmail = document.SendPDFMail.elements
        email_type = new Array();
        for (var i = 0; i < chk_emails; i++){
            if (oFsendmail[i].type != 'button'){
                if (oFsendmail[i].checked != false){
                    email_type [j++] = oFsendmail[i].value;
                }
            }
        }
        if (email_type != ''){
            var field_lists = email_type.join(':');

            emailhref = 'module=PDFMaker&action=PDFMakerAjax&file=SendPDFMail&language=' + document.getElementById('template_language').value + '&record=' + record + '&relmodule=' + smodule + '&commontemplateid=' + PDFMaker_Actions_Js.getSelectedTemplates();

            new Ajax.Request(
                    'index.php',
                    {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: emailhref,
                        onComplete: function(response2){
                            openPopUp('xComposeEmail', this, 'index.php?module=Emails&action=EmailsAjax&file=EditView&pmodule=' + module + '&idlist=' + idlist + '&field_lists=' + field_lists + '&language=' + document.getElementById('template_language').value + '&sendmail=true&attachment=' + response2.responseText + '.pdf', 'createemailWin', 820, 689, 'menubar=no,toolbar=no,location=no,status=no,resizable=no');
                        }
                    });

            fninvsh('roleLay2');
            return true;
        } else {
            alert(alert_arr.SELECT_MAILID);
        }
    },

    validatePDFDocForm: function (){
        if (document.PDFDocForm.notes_title.value == ''){
            alert_label = document.getElementById('alert_doc_title').innerHTML;
            alert(alert_label);
            return false;
        } else {
            document.PDFDocForm.template_ids.value = PDFMaker_Actions_Js.getSelectedTemplates();
            document.PDFDocForm.language.value = document.getElementById('template_language').value;
            return true;
        }
    },

    savePDFBreakline: function (){
        var record = document.DetailView.record.value;
        var frm = document.PDFBreaklineForm;
        var url = 'module=PDFMaker&action=PDFMakerAjax&file=SavePDFBreakline&pid=' + record + '&breaklines=';
        var url_suf = '';
        var url_suf2 = '';
        if (frm != 'undefined'){
            for (i = 0; i < frm.elements.length; i++){
                if (frm.elements[i].type == 'checkbox'){
                    if (frm.elements[i].name == 'show_header' || frm.elements[i].name == 'show_subtotal'){
                        if (frm.elements[i].checked)
                            url_suf2 += '&' + frm.elements[i].name + '=true';
                        else
                            url_suf2 += '&' + frm.elements[i].name + '=false';
                    } else {
                        if (frm.elements[i].checked)
                            url_suf += frm.elements[i].name + '|';
                    }
                }
            }

            url += url_suf + url_suf2;
            new Ajax.Request(
                    'index.php',
                    {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: url,
                        onComplete: function(response){
                            fninvsh('PDFBreaklineDiv');
                        }
                    }
            );

        }
    },

    savePDFImages: function (){
        var record = document.DetailView.record.value;
        var frm = document.PDFImagesForm;
        var url = 'module=PDFMaker&action=PDFMakerAjax&file=SavePDFImages&pid=' + record;
        var url_suf = '';
        if (frm != 'undefined'){
            for (i = 0; i < frm.elements.length; i++){
                if (frm.elements[i].type == 'radio'){
                    if (frm.elements[i].checked){
                        url_suf += '&' + frm.elements[i].name + '=' + frm.elements[i].value;
                    }
                } else if (frm.elements[i].type == 'text') {
                    url_suf += '&' + frm.elements[i].name + '=' + frm.elements[i].value;
                }
            }

            url += url_suf;
            new Ajax.Request(
                    'index.php',
                    {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: url,
                        onComplete: function(response){
                            fninvsh('PDFImagesDiv');
                        }
                    }
            );
        }
    },

    checkIfAny: function (){
        var frm = document.PDFBreaklineForm;
        if (frm != 'undefined'){
            var j = 0;
            for (i = 0; i < frm.elements.length; i++){
                if (frm.elements[i].type == 'checkbox' && frm.elements[i].name != 'show_header' && frm.elements[i].name != 'show_subtotal'){
                    if (frm.elements[i].checked){
                        j++;
                    }
                }
            }
            if (j == 0){
                frm.show_header.checked = false;
                frm.show_subtotal.checked = false;
                frm.show_header.disabled = true;
                frm.show_subtotal.disabled = true;
            } else {
                frm.show_header.disabled = false;
                frm.show_subtotal.disabled = false;
            }
        }
    },

    getPDFListViewPopup2: function (srcButt, module){
        var thisInstance = this;
        massEditUrl = "index.php?module=PDFMaker&return_module=" + module + "&view=ListViewSelect";

        Vtiger_List_Js.triggerMassAction(massEditUrl, function(container){
            var massEditForm = container.find('#massEdit');
    
            thisInstance.postPDFGenerate(container);
        });
    },

    postPDFGenerate : function(massEditContainer){
            var thisInstance = this;
            
            massEditContainer.find('[name="use_common_template"]').on('click', function(e){
                var selectElement =  jQuery(e.currentTarget);
                var select2Element = app.getSelect2ElementFromSelect(selectElement);
                select2Element.validationEngine('hide');
            })
                
            massEditContainer.find('[name="generateButton"]').on('click', function(e){
                var selectElement =  jQuery('#use_common_template');
                var select2Element = app.getSelect2ElementFromSelect(selectElement);
                var result = Vtiger_MultiSelect_Validator_Js.invokeValidation(selectElement);
                if(result != true){
                    select2Element.validationEngine('showPrompt', result , 'error','topLeft',true);
                    app.formAlignmentAfterValidation(massEditContainer);
                    return false;
                } else {
                    select2Element.validationEngine('hide');

                    var templateids = PDFMaker_Actions_Js.getSelectedTemplates();
                    var pdflanguage =  jQuery('#template_language').val();
                    
                    var generatePDFFormEl  = jQuery("#generatePDFForm");
                    
                    generatePDFFormEl.find('[name="commontemplateid"]').val(templateids);
                    generatePDFFormEl.find('[name="language"]').val(pdflanguage);
                    generatePDFFormEl.submit();
                    
                    app.hideModalWindow();
                }
            });
            
            massEditContainer.find('[name="printButton"]').on('click', function(e){
                var selectElement =  jQuery('#use_common_template');
                var select2Element = app.getSelect2ElementFromSelect(selectElement);
                var result = Vtiger_MultiSelect_Validator_Js.invokeValidation(selectElement);
                if(result != true){
                    select2Element.validationEngine('showPrompt', result , 'error','topLeft',true);
                    app.formAlignmentAfterValidation(massEditContainer);
                    return false;
                } else {
                    select2Element.validationEngine('hide');

                    var templateids = PDFMaker_Actions_Js.getSelectedTemplates();
                    var pdflanguage =  jQuery('#template_language').val();
                    
                    var generatePDFFormEl  = jQuery("#generatePDFForm");
                    
                    generatePDFFormEl.find('[name="commontemplateid"]').val(templateids);
                    generatePDFFormEl.find('[name="language"]').val(pdflanguage);
                    //generatePDFFormEl.submit();

                    formParams = generatePDFFormEl.serializeFormData();
                    
                    var urlString = jQuery.param(formParams);
                    var url = 'index.php?print=true&'+urlString;
                   
                    var actualtime = Math.floor(new Date().getTime() / 1000);
                    window.open(url,'_new' + actualtime);
                    app.hideModalWindow();
                }
            });
    },

    loadPDFCSS: function (filename){
        return;
    },

    downloadNewRelease: function (type, url, alertLbl){
        var ans = confirm(alertLbl);

        if (ans == true){
            new Ajax.Request(
                    'index.php',
                    {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: "module=PDFMaker&action=PDFMakerAjax&file=AjaxRequestHandle&handler=download_release&type=" + type + "&url=" + url,
                        onComplete: function(response){
                            alert(response.responseText);
                            window.location.reload();
                        }
                    }
            );
        }
    },

    pdfmaker_sendPDFmail: function (module, crmid, relmodule, selid){
        Vtiger_Helper_Js.checkServerConfig('Emails').then(function(data){
            if (data == true) {
                var callBackFunction = function(data) {
                    var selectEmailForm = jQuery("#SendEmailFormStep1");

                    selectEmailForm.on('submit', function(e){
                        var form = jQuery(e.currentTarget);
                        var params = form.serializeFormData();

                        PDFMaker_Actions_Js.pdfmaker_openComposeEmail(module,crmid,params);

                        e.preventDefault();
                    });
                }

                var sidvalue = "";
                if (selid != "" && selid != "0"){
                    var sid = selid.split(',');
                    sidvalue = JSON.stringify(sid);

                    var postData = {
                        "selected_ids": sidvalue 
                    };

                    var actionParams = {
                        "type": "POST",
                        "url": 'index.php?module='+relmodule+'&view=MassActionAjax&mode=showComposeEmailForm&step=step1',
                        "dataType": "html",
                        "data": postData
                    };

                    AppConnector.request(actionParams).then(
                            function(data) {
                                if (data) {
                                    data = jQuery(data);
                                    var form = data.find("#SendEmailFormStep1");
                                    var emailFields = form.find('.emailField');
                                                    var length = emailFields.length;

                                    if(length > 1) {
                                        app.showModalWindow(data, {'text-align': 'left'});
                                        if (typeof callBackFunction == 'function') {
                                            callBackFunction(data);
                                        }
                                    } else {
                                        emailFields.attr('checked','checked');
                                                                        var params = form.serializeFormData();
                                        PDFMaker_Actions_Js.pdfmaker_openComposeEmail(module,crmid,params);
                                    }   
                                }
                            },
                            function(error, err) {

                            }
                    );
                } else {

                    PDFMaker_Actions_Js.pdfmaker_openComposeEmail(module,crmid,false);    
                }

            } else {
                alert(app.vtranslate('JS_EMAIL_SERVER_CONFIGURATION'));
            }
        });
    },

    pdfmaker_openComposeEmail: function (module,crmid,params){

        if (!params){
            var form = jQuery("<form></form>");
            var params = form.serializeFormData();
        }
        params['module'] = 'PDFMaker';
        params['view'] = 'SendEmail';
        params['formodule'] = module;
        params['mode'] = 'composeMailData';
        params['record'] = crmid;
        params['language'] = jQuery("#template_language").val();
        params['pdftemplateid'] = PDFMaker_Actions_Js.getSelectedTemplates();
        app.hideModalWindow();
        var popupInstance = Vtiger_Popup_Js.getInstance();
        popupInstance.show(params, "", "composeEmail");
    },
    
    pdfmaker_sendEMAILMakerPDFmail: function (module, crmid, relmodule, selid) {
        
        var pdftemplateid = PDFMaker_Actions_Js.getSelectedTemplates();
        var pdflanguage = jQuery("#template_language").val();
        
        EMAILMaker_Actions_Js.emailmaker_sendMail(module, crmid, pdftemplateid, pdflanguage, crmid);
    }

}
}

PDFMakerCommon = {
    showproductimages: function(record){
        AppConnector.request('index.php?module=PDFMaker&view=imagesSelect&return_id=' + encodeURIComponent(record)).then(
                function(data) {
                    app.showModalWindow(data);
                }
        );
    },
    saveproductimages: function(record){
        var frm = document.PDFImagesForm;
        var url = 'index.php?module=PDFMaker&action=SavePDFImages&record=' + encodeURIComponent(record);
        var url_suf = '';
        if (frm != 'undefined'){

            for (i = 0; i < frm.elements.length; i++){
                if (frm.elements[i].type == 'radio'){
                    if (frm.elements[i].checked){
                        url_suf += '&' + frm.elements[i].name + '=' + frm.elements[i].value;
                    }
                } else if (frm.elements[i].type == 'text'){
                    url_suf += '&' + frm.elements[i].name + '=' + frm.elements[i].value;
                }
            }

            url += url_suf;
            AppConnector.request(url).then(
                    function(data) {
                        app.hideModalWindow();
                    }
            );
        }
    },
    getPDFDocDivContent: function(rootElm, module, id){
        AppConnector.request('index.php?module=PDFMaker&return_module=' + encodeURIComponent(module) + '&view=docSelect&return_id=' + encodeURIComponent(id)).then(
                function(data) {
                    app.showModalWindow(data);
                }
        );
    },
    savePDFDoc: function(){
        if (PDFMaker_Actions_Js.validatePDFDocForm()){
            
            app.hideModalWindow();
            
            var frm = document.PDFDocForm;
            var pdflanguage = jQuery("#template_language").val();
            var url = 'index.php?module=PDFMaker&action=SaveIntoDocuments&pmodule=' + encodeURIComponent(frm.pmodule.value) + '&pid=' + encodeURIComponent(frm.pid.value) + '&template_ids=' + PDFMaker_Actions_Js.getSelectedTemplates() + '&language=' + pdflanguage + '&notes_title=' + encodeURIComponent(frm.notes_title.value) + '&folderid=' + encodeURIComponent(frm.folderid.value) + '&notecontent=' + encodeURIComponent(frm.notecontent.value);
           
           var progressIndicatorElement = jQuery.progressIndicator({
                'position' : 'html',
                'blockInfo' : {
                        'enabled' : true
                }
            });
           
            AppConnector.request(url).then(
                    function(data) {
                        var response = data['result'];
				
                        var result = response['success'];
                        
                        if(result == true){
                            type_r = 'info';
                        } else {
                            type_r = 'error';
                        }
         
                        var params = {
                        text: response['message'],
                        type: type_r
                        };

                        progressIndicatorElement.progressIndicator({'mode':'hide'});
                        Vtiger_Helper_Js.showMessage(params);  
                    }
            );
        }
    },
    getEditAndExportForm: function(module, id, commontemplateid, language){
        var params = {
            'module': 'PDFMaker',
            'view': 'EditAndExport',
            'formodule': module,
            'mode': 'composeMailData',
            'record': id,
            'language': language,
            'commontemplateid': commontemplateid
        };
        var popupInstance = Vtiger_Popup_Js.getInstance();
        popupInstance.show(params, "", "EditAndExport");
    },
    showPDFBreakline: function(record){
        AppConnector.request('index.php?module=PDFMaker&view=IndexAjax&mode=PDFBreakline&return_id=' + encodeURIComponent(record)).then(
                function(data) {
                    app.showModalWindow(data);
                }
        );
    },
    savePDFBreakline: function(record){
        var frm = document.PDFBreaklineForm;
        var url = 'index.php?module=PDFMaker&action=IndexAjax&mode=savePDFBreakline&record=' + encodeURIComponent(record) + '&breaklines=';
        var url_suf = '';
        var url_suf2 = '';
        if (frm != 'undefined'){
            for (i = 0; i < frm.elements.length; i++){
                if (frm.elements[i].type == 'checkbox'){
                    if (frm.elements[i].name == 'show_header' || frm.elements[i].name == 'show_subtotal'){
                        if (frm.elements[i].checked)
                            url_suf2 += '&' + frm.elements[i].name + '=true';
                        else
                            url_suf2 += '&' + frm.elements[i].name + '=false';
                    } else {
                        if (frm.elements[i].checked) {
                            if (url_suf != "") url_suf += '|';
                            url_suf += frm.elements[i].name;
                        }
                     }
                }
            }
            url += url_suf + url_suf2;
            AppConnector.request(url).then(
                function(data) {
                    app.hideModalWindow();
                }
            );
        }
    },
}
