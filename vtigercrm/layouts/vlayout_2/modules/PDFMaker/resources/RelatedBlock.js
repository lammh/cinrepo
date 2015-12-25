/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

if (typeof(PDFMaker_RelatedBlockJs) == 'undefined') {
    /*
     * Namespaced javascript class for Import
     */
    PDFMaker_RelatedBlockJs = {
        formElement : false,
        container : false,
        relatedblockColumnsList: false,
        stepContainer : false,
        advanceFilterInstance : false,
        selectedFields : false,
        
        changeSteps: function(){
            actual_step = document.getElementById('step').value * 1;
            next_step = actual_step + 1;

            if (next_step == "2") {
                 PDFMaker_RelatedBlockJs.changeSecOptions();
            } else if (next_step == "6") {                
                var editViewForm = this.getForm();

                var selectElement2 = jQuery('input[name="blockname"]');
                var control = selectElement2.val();

                if(control == ""){
                    var result = app.vtranslate('JS_REQUIRED_FIELD');
                    selectElement2.validationEngine('showPrompt', result , 'error','bottomLeft',true);
                    return;
                } else {
                    selectElement2.validationEngine('hide');
                }                
                editViewForm.submit();
            } else {                
                if (next_step == "3"){
                    if(!this.isFormValidate()) return false;
                    
                    var selectedFields = this.getSelectedColumns();
                    this.getSelectedFields().val(JSON.stringify(selectedFields));
                    this.createRelatedBlockTable();
                } 
                
                jQuery("#steplabel"+ actual_step).removeClass('active');
                jQuery("#steplabel"+ next_step).addClass('active');
                jQuery("#step"+ actual_step).hide();
                jQuery("#step"+ next_step).show();
            }
            
            document.getElementById('back_rep').disabled = false;
            document.getElementById('step').value = next_step;
        },
        changeStepsback: function(mode){
            actual_step = document.getElementById('step').value * 1;
            last_step = actual_step - 1;

            jQuery("#steplabel"+ actual_step).removeClass('active');
            jQuery("#steplabel"+ last_step).addClass('active');

            jQuery("#step"+ actual_step).hide();
            jQuery("#step"+ last_step).show();   
 
            if ((last_step == 1 && mode == "create") || (last_step == 3 && mode == "edit"))
                document.getElementById('back_rep').disabled = true;

            document.getElementById('step').value = last_step;
        },
        changeSecOptions: function(){            
            primodule = document.NewBlock.primarymodule.value;
            secmodule = PDFMaker_RelatedBlockJs.getCheckedValue(document.NewBlock.secondarymodule);
            
            saved_secmodule = jQuery("#saved_secmodule").val();
            
            if (saved_secmodule != secmodule){
                jQuery("#saved_secmodule").val(secmodule);
                
                thisElement = this;
                
                var params = {
                                'module' : 'PDFMaker',
                                'action' : 'GetRelatedBlockColumns',
                                'mode' : 'columns',
                                'secmodule' : secmodule,
                                'primodule':primodule
                             }; 

                AppConnector.request(params).then(
                    function(data){
                        jQuery('#filter_columns').html("<option value=''>"+app.vtranslate("LBL_NONE","PDFMaker")+"</option>" + data.result);
                        jQuery('#selectScolrow_1').html("<option value='' selected='selected'>"+app.vtranslate("LBL_NONE","PDFMaker")+"</option>" + data.result);
                        jQuery('#selectScolrow_2').html("<option value='' selected='selected'>"+app.vtranslate("LBL_NONE","PDFMaker")+"</option>" + data.result);
                        jQuery('#selectScolrow_3').html("<option value='' selected='selected'>"+app.vtranslate("LBL_NONE","PDFMaker")+"</option>" + data.result);
                        jQuery('#relatedblockColumnsList').html(data.result);

                        thisElement.initialize();
                        
                        jQuery("#steplabel1").removeClass('active');
                        jQuery("#steplabel2").addClass('active');
                        
                        jQuery("#step1").hide();
                        jQuery("#step2").show();
                    }
                 );

                var params2 = {
                                'module' : 'PDFMaker',
                                'view' : 'GetRelatedBlockFilters',
                                'secmodule' : secmodule,
                                'primodule' : primodule
                               }; 

                AppConnector.request(params2).then(
                    function(data) {
                        jQuery('#step3').html(data);
                        PDFMaker_RelatedBlockJs.registerEvents2();
                    }
                 );            
            } else {                
                this.initialize(); 
                jQuery("#steplabel1").removeClass('active');
                jQuery("#steplabel2").addClass('active');
                
                jQuery("#step1").hide();
                jQuery("#step2").show();
            }
        },
        getCheckedValue: function (radioObj){
            if (!radioObj)
                return "";
            var radioLength = radioObj.length;
            if (radioLength == undefined)
                if (radioObj.checked)
                    return radioObj.value;
                else
                    return "";
            for (var i = 0; i < radioLength; i++) {
                if (radioObj[i].checked) {
                    return radioObj[i].value;
                }
            }
            return "";
        },        
        registerSelect2ElementForRelatedBlockColumns : function(){
            var selectElement = this.getRelatedBlockColumnsList();
            app.changeSelectElementView(selectElement, 'select2', {dropdownCss : {'z-index' : 0}});

        },    
        getRelatedBlockColumnsList : function(){
            if(this.relatedblockColumnsList == false){
                    this.relatedblockColumnsList = jQuery('#relatedblockColumnsList');
            }
            return this.relatedblockColumnsList;
        },    
        getSelectedFields : function(){
            if(this.selectedFields == false){
                    this.selectedFields = jQuery('#seleted_fields');
            }
            return this.selectedFields;
        },            
        arrangeSelectChoicesInOrder : function(){
            var selectElement = this.getRelatedBlockColumnsList();
            var chosenElement = app.getSelect2ElementFromSelect(selectElement);
            var choicesContainer = chosenElement.find('ul.select2-choices');
            var choicesList = choicesContainer.find('li.select2-search-choice');

            var selectedOptions = selectElement.find('option:selected');
            var selectedOrder = JSON.parse(this.getSelectedFields().val());
            var selectedOrderKeys = [];
            for(var key in selectedOrder){
                    if(selectedOrder.hasOwnProperty(key)){
                            selectedOrderKeys.push(key);
                    }
            }
            for(var index=selectedOrderKeys.length ; index > 0 ; index--){
                    var selectedValue = selectedOrder[selectedOrderKeys[index-1]];
                    var option = selectedOptions.filter('[value="'+selectedValue+'"]');
                    choicesList.each(function(choiceListIndex,element){
                            var liElement = jQuery(element);
                            if(liElement.find('div').html() == option.html()){
                                    choicesContainer.prepend(liElement);
                                    return false;
                            }
                    });
            }
	}, 
        makeColumnListSortable : function(){
		var thisInstance = this;
		var selectElement = thisInstance.getRelatedBlockColumnsList();
		var select2Element = app.getSelect2ElementFromSelect(selectElement);
		//TODO : peform the selection operation in context this might break if you have multi select element in advance filter
		//The sorting is only available when Select2 is attached to a hidden input field.
		var chozenChoiceElement = select2Element.find('ul.select2-choices');
		chozenChoiceElement.sortable({
                containment: 'parent',
                start: function() {thisInstance.getSelectedFields().select2("onSortStart");},
                update: function() {thisInstance.getSelectedFields().select2("onSortEnd");}
            });
	},
        isFormValidate : function(){
		var thisInstance = this;
		var selectElement = this.getRelatedBlockColumnsList();
		var select2Element = app.getSelect2ElementFromSelect(selectElement);
		var result = Vtiger_MultiSelect_Validator_Js.invokeValidation(selectElement);
		if(result != true){
			select2Element.validationEngine('showPrompt', result , 'error','bottomLeft',true);
			var form = thisInstance.getForm();
			app.formAlignmentAfterValidation(form);
			return false;
		} else {
			select2Element.validationEngine('hide');
			return true;
		}
	},
        initialize : function(container){
            this.relatedblockColumnsList = false;
            this.selectedFields = false;

            var sort_selectbox1 = jQuery('#selectScolrow_1');
            var sort_selectbox2 = jQuery('#selectScolrow_2');
            var sort_selectbox3 = jQuery('#selectScolrow_3');

            sort_selectbox1.addClass("chzn-select");
            sort_selectbox2.addClass("chzn-select");
            sort_selectbox3.addClass("chzn-select");
            
            app.changeSelectElementView(sort_selectbox1)
            app.changeSelectElementView(sort_selectbox2);
            app.changeSelectElementView(sort_selectbox3);
	},
	getForm : function(){
		if(this.formElement == false){
			this.setForm(jQuery('#NewBlock'));
		}
		return this.formElement;
	},
	setForm : function(element){
		this.formElement = element;
		return this;
	},        
	calculateValues : function(){
		//handled advanced filters saved values.
		var advfilterlist = this.advanceFilterInstance.getValues();
		jQuery('#advanced_filter').val(JSON.stringify(advfilterlist));
                
                var selectedSortOrderFields = new Array();
		var selectedSortFieldsRows = jQuery('.sortFieldRow');
		jQuery.each(selectedSortFieldsRows,function(index,element){
			var currentElement = jQuery(element);
			var field = currentElement.find('.selectedSortFields').val();
			var order = currentElement.find('.sortOrder').filter(':checked').val();
			//TODO: need to handle sort type for Reports
			var type = currentElement.find('.sortType').val();
			selectedSortOrderFields.push([field,order,type]);
		});
		jQuery('#selected_sort_fields').val(JSON.stringify(selectedSortOrderFields));
	},	
	registerSubmitEvent : function(){
            editViewForm = this.getForm();
            var thisInstance = this;
            editViewForm.submit(function(e){
                    thisInstance.calculateValues();
            });
	},       
        registerFieldTypeChangeEvent : function(form){
		var thisInstance = this;
		
		//register the change event for field types
		jQuery('input[name="blockname"]').on('click', function(e) {
                    var currentTarget = jQuery(e.currentTarget);
			currentTarget.validationEngine('hide');
		})
	},
        registerEvents : function(){
            editViewForm = this.getForm();
            this.registerFieldTypeChangeEvent(editViewForm);  
            this.relatedblockColumnsList = false;
            this.selectedFields = false;
            this.registerSelect2ElementForRelatedBlockColumns();
            this.arrangeSelectChoicesInOrder();
            this.makeColumnListSortable();
            this.registerSubmitEvent();
	},
        registerEvents2 : function(){
            this.advanceFilterInstance = Vtiger_AdvanceFilter_Js.getInstance(jQuery('.filterContainer'));
            CKEDITOR.replace('relatedblock', {height:'280px'}); 
	},
        registerEditEvents : function(){
            this.initialize();
            editViewForm = this.getForm();
            this.registerFieldTypeChangeEvent(editViewForm);  
            this.registerEvents2(); 
            this.registerSubmitEvent();
	},        
        createRelatedBlockTable: function (){
            var selectedColumns = JSON.parse(this.getSelectedFields().val());
            var oEditor = CKEDITOR.instances.relatedblock;

            table = "<table border='1' cellpadding='3' cellspacing='0' style='border-collapse: collapse;'>";
            //header
            table += "<tr>";
            
            for(var key in selectedColumns) {
                tmpArr = selectedColumns[key].split(":");
                tmpLbl = tmpArr[2];
                var idx = tmpLbl.indexOf("_");
                var module = tmpLbl.slice(0, idx).toUpperCase();
                label = tmpLbl.slice(idx + 1).replace(/_/g, " ");
                label = label.replace(/@~@/g, "_");          //because of PriceBook listprice field header that contains '_'
                label = "%R_" + module + "_" + label + "%";
                table += "<td>";
                table += label;
                table += "</td>";
            }
            
            table += "</tr>";

            //separator Start
            table += "<tr>";
            table += "<td colspan='50'>#RELBLOCK_START#</td>";
            table += "</tr>";

            table += "<tr>";
            for(var key in selectedColumns) {
                coldata = selectedColumns[key].split(":");
                table += "<td>";
                table += "$" + coldata[3] + "$";
                table += "</td>";
            }
           
            table += "</tr>";

            //separator End
            table += "<tr>";
            table += "<td colspan='50'>#RELBLOCK_END#</td>";
            table += "</tr>";

            table += "</table>";

            oEditor.setData(table);
        },
	getSelectedColumns : function(){
            var columnListSelectElement = this.getRelatedBlockColumnsList();
            var select2Element = app.getSelect2ElementFromSelect(columnListSelectElement);

            var selectedValuesByOrder = new Array();
            var selectedOptions = columnListSelectElement.find('option:selected');

            var orderedSelect2Options = select2Element.find('li.select2-search-choice').find('div');
            orderedSelect2Options.each(function(index,element){
                var chosenOption = jQuery(element);
                var choiceElement = chosenOption.closest('.select2-search-choice');
                var choiceValue = choiceElement.data('select2Data').id;
                selectedOptions.each(function(optionIndex, domOption){
                    var option = jQuery(domOption);
                    if(option.val() == choiceValue) {
                            selectedValuesByOrder.push(option.val());
                            return false;
                    }
                });
            });
            return selectedValuesByOrder;
	}
    }    
}


