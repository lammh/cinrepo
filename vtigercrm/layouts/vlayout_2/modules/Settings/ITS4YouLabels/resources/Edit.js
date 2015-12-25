/*********************************************************************************
 * The content of this file is subject to the Labels 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

Settings_Vtiger_Edit_Js("Settings_ITS4YouLabels_Edit_Js",{
	registerSaveButtonEvent : function() {
  jQuery('.btn-success').on('click',function(e){ 
          var form = jQuery('#moduleBlocksAndFields');
          var formData = form.serializeFormData();
//          alert(formData.toSource());
          if(typeof formData !== undefined){
          var progressIndicatorElement = jQuery.progressIndicator({
           'position' : 'html',
           'blockInfo' : {
            'enabled' : true
           }
          });
          var actionParams = {
                        "type": "POST",
                        "url": 'index.php?module=ITS4YouLabels&parent=Settings&action=Save',
                        "data": formData,
                        "dataType" : 'json'
                    };
          AppConnector.request(actionParams).then(
           function(data) {

            progressIndicatorElement.progressIndicator({
             'mode' : 'hide'
            });
            var params = {
                        text: data.result.message,
                        type: 'info'
                        };
                                                                
                         Vtiger_Helper_Js.showMessage(params);  
           },
           function(error,err){
        
           }
          );
  }
         })
    },	
    
	registerBackLink : function(){
        jQuery('#backLink').on('click',function(e){
                window.history.back();
            });		
	},
	
	registerEvents : function(){
            this.registerBackLink();
            this.registerSaveButtonEvent(); 
	}
});

