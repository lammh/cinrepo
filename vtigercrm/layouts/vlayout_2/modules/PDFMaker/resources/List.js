/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/
if (typeof(PDFMaker_ListJs) == 'undefined') {

    PDFMaker_ListJs = {

        massDeleteTemplates : function() {
	
            var listInstance = PDFMaker_ListJs;

            var validationResult = listInstance.checkListTemplatesSelected();
                if(validationResult == true){

                    var selectedIds = document.massdelete.idlist.value;

                    var message = app.vtranslate('LBL_MASS_DELETE_CONFIRMATION');
                    Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
                            function(e) {

                                var deleteURL = 'module=PDFMaker&action=DeletePDFTemplate&&ajaxDelete=true&idlist='+selectedIds;

                                var deleteMessage = app.vtranslate('JS_RECORDS_ARE_GETTING_DELETED');
                                var progressIndicatorElement = jQuery.progressIndicator({
                                        'message' : deleteMessage,
                                        'position' : 'html',
                                        'blockInfo' : {
                                                'enabled' : true
                                        }
                                });

                                AppConnector.request(deleteURL).then(
                                        function() {
                                                progressIndicatorElement.progressIndicator({
                                                        'mode' : 'hide'
                                                })
                                                listInstance.postMassDeleteTemplates();
                                        }
                                    );
                                },
                                function(error, err){
                                listInstance.clearList();
                                })
                } else {
                    listInstance.noRecordSelectedAlert();
                }
    
        },

        checkListTemplatesSelected : function(){
  
            if (typeof(document.massdelete.selected_id) == 'undefined')
                return false;
            x = document.massdelete.selected_id.length;
            idstring = "";

            if (x == undefined)
            {
                if (document.massdelete.selected_id.checked)
                {
                    document.massdelete.idlist.value = document.massdelete.selected_id.value + ';';
                    xx = 1;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                xx = 0;
                for (i = 0; i < x; i++)
                {
                    if (document.massdelete.selected_id[i].checked)
                    {
                        idstring = document.massdelete.selected_id[i].value + ";" + idstring
                        xx++
                    }
                }

                if (xx != 0)
                {
                    document.massdelete.idlist.value = idstring;
                    return true;
                }
                else
                {
                    return false;
                }
            }
            return true;

        },
        
        
        noRecordSelectedAlert : function(){
		return alert(app.vtranslate('JS_PLEASE_SELECT_ONE_RECORD'));
	},
        
        clearList : function() {
		jQuery('#deSelectAllMsg').trigger('click');
		jQuery("#selectAllMsgDiv").hide();
	},
        
        postMassDeleteTemplates : function() {
		var aDeferred = jQuery.Deferred();
		app.hideModalWindow();
		var module = app.getModuleName();
		var params = this.getDefaultParams();
                
		AppConnector.request(params).then(
			function(data) {
  
                            var listViewContainer = jQuery('#listViewContents');
                            listViewContainer.html(data);

                            aDeferred.resolve();
		});

		return aDeferred.promise();
	},
        
        getDefaultParams : function() {

		var module = app.getModuleName();
		var orderBy = jQuery('#orderBy').val();
		var sortOrder = jQuery("#sortOrder").val();
		var params = {
			'module': module,
			'view' : "List",
			'orderby' : orderBy,
			'dir' : sortOrder,
                        'ajax' : true
		}
		return params;
	},
    }
    
}


