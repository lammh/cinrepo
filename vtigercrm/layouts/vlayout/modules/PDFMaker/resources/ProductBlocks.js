/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

if (typeof(PDFMaker_ProductBlocks_Js) == 'undefined') {
    /*
     * Namespaced javascript class for Import
     */
    PDFMaker_ProductBlocks_Js = {
	/*
	 * function to Save the Outgoing Server Details
	 */
	saveProductBlock : function(form) {
		
            
            var thisInstance = this;
		var data = form.serializeFormData();
		var progressIndicatorElement = jQuery.progressIndicator({
				'position' : 'html',
				'blockInfo' : {
					'enabled' : true
				}
			});
		
		if(typeof data == 'undefined' ) {
			data = {};
		}
		data.module = app.getModuleName();
		data.action = 'IndexAjax';
                data.mode = 'SaveProductBlock';
          
	},

	/*
	 * function to register the events in editView
	 */
	registerEditViewEvents : function() {
		var thisInstance = this;
		var form = jQuery('#EditView');
		
		//register validation engine
		var params = app.validationEngineOptions;
		params.onValidationComplete = function(form, valid){
			
                    if(valid) {
                        //thisInstance.saveProductBlock(form);
				return valid;
			}
		}
		form.validationEngine(params);
		
		form.submit(function(e) {
			//e.preventDefault();
		})
	},
	
	
	
	registerEvents: function() {
		var thisInstance = this;

		thisInstance.registerEditViewEvents();
   
	}

}
}
