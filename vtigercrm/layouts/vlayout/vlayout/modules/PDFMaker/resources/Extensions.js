/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

jQuery.Class("PDFMaker_Extensions_Js", {}, {
	
	registerActions : function() {
            
            var aDeferred = jQuery.Deferred();
            jQuery('#install_Workflow_btn').click(function(e) {
                    //thisInstance.editLicense('activate');
                    
                    var extname = jQuery(e.currentTarget).data('extname');
 
                    var progressIndicatorElement = jQuery.progressIndicator({
                        'position' : 'html',
                        'blockInfo' : {
                                'enabled' : true
                        }
                    });
                    
                    //jQuery('#w_install_loading_icon').removeClass('hide').show();
                    //jQuery(e.currentTarget).hide();
                    
                    var params = {
                        'module': 'PDFMaker',
                        'action': 'IndexAjax',
                        'mode': 'installExtension',
                        'extname': extname
                    };
                    
                    AppConnector.request(params).then(
			function(data) {
                            var response = data['result'];
                            var result = response['success'];
                            progressIndicatorElement.progressIndicator({'mode':'hide'});
                             
                            if(result == true) {

                                jQuery(e.currentTarget).hide();
                                jQuery('#install_' + extname + '_info').html(response['message']);
                                jQuery('#install_' + extname + '_info').removeClass('hide');

                                var params = {
                                text: response['message'],
                                type: 'info'
                                };
                               
                                Vtiger_Helper_Js.showMessage(params);  

                            } else {
                                var ismodal = jQuery(response['message']).find('div');
                                if (ismodal.length > 0) {
                                    app.showModalWindow(response['message']);
                                } else {
                                    var params = {
                                    text: response['message'],
                                    type: 'error'
                                    };
                                    Vtiger_Helper_Js.showMessage(params);  
                                }
                            }
			},
			function(error,err){
                            aDeferred.reject();

                            progressIndicatorElement.progressIndicator({'mode':'hide'});
			}
		);
                    
                    
                    
            });
	},

	registerEvents: function() {
		this.registerActions();
	},
        
        showMessage : function(customParams){
		var params = {};
		params.animation = "show";
		params.type = 'info';
		params.title = app.vtranslate('JS_MESSAGE');
		
		if(typeof customParams != 'undefined') {
			var params = jQuery.extend(params,customParams);
		}
		Vtiger_Helper_Js.showPnotify(params);
                
                
	}
});
