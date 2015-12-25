/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/
Vtiger_Edit_Js("PDFMaker_Edit_Js",{

    duplicateCheckCache : {},

    formElement : false,

    getForm : function(){
        if(this.formElement == false){
                this.setForm(jQuery('#EditView'));
        }
        return this.formElement;
    },

    setForm : function(element){
        this.formElement = element;
        return this;
    },
    
    checkDuplicateTemplateName : function(params){        
	var aDeferred = jQuery.Deferred();	
        AppConnector.request(params).then(
                function(data) {
                        var response = data['result'];
                        var result = response['success'];
                        if(result == true) {
                                aDeferred.reject(response);
                        } else {
                                aDeferred.resolve(response);
                        }
                },
                function(error,err){
                        aDeferred.reject();
                }
        );
        return aDeferred.promise();
    },
    
    registerRecordPreSaveEvent : function(form){
		var thisInstance = this;
		if(typeof form == 'undefined'){
			form = this.getForm();
		}
		
		form.on(Vtiger_Edit_Js.recordPreSave, function(e, data){
                    
                        var template_name =jQuery("#filename").val();
                        var template_id = jQuery("#templateid").val();
                        var error = 0;
                        if (template_name == ""){
                            alert(app.vtranslate("LBL_PDF_NAME") + app.vtranslate('CANNOT_BE_EMPTY'));
                            error++;
                        }

                        var pdf_module = jQuery("#modulename").val();
                        if (pdf_module == ""){
                            alert(app.vtranslate("LBL_MODULE_ERROR"));
                            error++;
                        }

                        if (!PDFMaker_EditJs.ControlNumber('margin_top', true) || !PDFMaker_EditJs.ControlNumber('margin_bottom', true) || !PDFMaker_EditJs.ControlNumber('margin_left', true) || !PDFMaker_EditJs.ControlNumber('margin_right', true)){
                            error++;
                        }

                        if (!PDFMaker_EditJs.CheckCustomFormat()){
                            error++;
                        }

                        if (!PDFMaker_EditJs.CheckSharing()){
                            error++;
                        }
                        if (error == 0){
       
                            moduleName = app.getModuleName();

                            var params = {
                            'module' : moduleName,
                            'action' : "IndexAjax",
                            'mode' : "CheckDuplicateTemplateName",
                            'templatename' : template_name,
                            'templateid' : template_id
                            }
                            
                            if(!(template_name in thisInstance.duplicateCheckCache)){
                                thisInstance.checkDuplicateTemplateName(params).then(
                                    function(data){
                                        thisInstance.duplicateCheckCache[template_name] = data['success'];
                                        form.submit();
                                    },
                                    function(data, err){
                                        var message = app.vtranslate('LBL_DUPLICATE_TEMPLATE_CREATION_CONFIRMATION');
                                            Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
                                                function(e){
                                                    thisInstance.duplicateCheckCache[template_name] = false;
                                                    form.submit();
                                                },
                                                function(error, err) {
                                                }
                                            );
                                    }
                                );
                            } else {
				if(thisInstance.duplicateCheckCache[template_name] == true){
					var message = app.vtranslate('LBL_DUPLICATE_TEMPLATE_CREATION_CONFIRMATION');
					Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
						function(e) {
							thisInstance.duplicateCheckCache[template_name] = false;
							form.submit();
						},
						function(error, err) {
							
						}
					);
				} else {
					delete thisInstance.duplicateCheckCache[template_name];
					return true;
				}
                            }
                        } 
                        e.preventDefault();
		})
	},
    
    registerBasicEvents: function(container){
        this._super(container);

        this.registerRecordPreSaveEvent();
    },
    
    registerSubmitEvent: function(){
		var editViewForm = this.getForm();

		editViewForm.submit(function(e){

			//Form should submit only once for multiple clicks also
			if(typeof editViewForm.data('submit') != "undefined"){
				return false;
			} else {

                            editViewForm.data('submit', 'true');
                            //on submit form trigger the recordPreSave event
                            var recordPreSaveEvent = jQuery.Event(Vtiger_Edit_Js.recordPreSave);
                            editViewForm.trigger(recordPreSaveEvent, {'value' : 'edit'});
                            if(recordPreSaveEvent.isDefaultPrevented()) {
                                    //If duplicate record validation fails, form should submit again
                                    editViewForm.removeData('submit');
                                    e.preventDefault();
                            }
			}
		});
	},
        registerEvents: function(){
            var editViewForm = this.getForm();
            var statusToProceed = this.proceedRegisterEvents();
            if(!statusToProceed){
                    return;
            }
            this.registerBasicEvents(this.getForm());
            this.registerSubmitEvent();
            
            if (typeof this.registerLeavePageWithoutSubmit == 'function'){
                this.registerLeavePageWithoutSubmit(editViewForm);
            }             
        }
        
    
});


if (typeof(PDFMaker_EditJs) == 'undefined'){
    /*
     * Namespaced javascript class for Import
     */
    PDFMaker_EditJs = {
        reportsColumnsList : false,
        advanceFilterInstance : false,
        availListObj : false,
        selectedColumnsObj : false,
    
        clearRelatedModuleFields: function(){
            second = document.getElementById("relatedmodulefields");
            lgth = second.options.length - 1;
            second.options[lgth] = null;
            if (second.options[lgth])
                optionTest = false;
            if (!optionTest)
                return;
            var box2 = second;
            var optgroups = box2.childNodes;
            for (i = optgroups.length - 1; i >= 0; i--){
                box2.removeChild(optgroups[i]);
            }

            objOption = document.createElement("option");
            objOption.innerHTML = app.vtranslate("LBL_SELECT_MODULE_FIELD");
            objOption.value = "";
            box2.appendChild(objOption);
        },
        change_relatedmodulesorce: function(first, second_name){
            second = document.getElementById(second_name);
            optionTest = true;
            lgth = second.options.length - 1;
            second.options[lgth] = null;
            if (second.options[lgth])
                optionTest = false;
            if (!optionTest)
                return;
            var box = first;
            var number = box.options[box.selectedIndex].value;
            if (!number)
                return;
            var box2 = second;
            var optgroups = box2.childNodes;
            for (i = optgroups.length - 1; i >= 0; i--){
                box2.removeChild(optgroups[i]);
            }

            var list = all_related_modules[number];
            for (i = 0; i < list.length; i += 2){
                objOption = document.createElement("option");
                objOption.innerHTML = list[i];
                objOption.value = list[i + 1];
                box2.appendChild(objOption);
            }

            PDFMaker_EditJs.clearRelatedModuleFields();
        },
        change_relatedmodule: function(first, second_name){
            second = document.getElementById(second_name);
            optionTest = true;
            lgth = second.options.length - 1;
            second.options[lgth] = null;
            if (second.options[lgth])
                optionTest = false;
            if (!optionTest)
                return;
            var box = first;
            var number = box.options[box.selectedIndex].value;
            if (!number)
                return;
            var box2 = second;
            var optgroups = box2.childNodes;
            for (i = optgroups.length - 1; i >= 0; i--){
                box2.removeChild(optgroups[i]);
            }

            if (number == "none"){
                objOption = document.createElement("option");
                objOption.innerHTML = app.vtranslate("LBL_SELECT_MODULE_FIELD");
                objOption.value = "";
                box2.appendChild(objOption);
            } else {
                var tmpArr = number.split('|', 2);
                var moduleName = tmpArr[0];
                number = tmpArr[1];
                var blocks = module_blocks[moduleName];
                for (b = 0; b < blocks.length; b += 2){
                    var list = related_module_fields[moduleName + '|' + blocks[b + 1]];
                    if (list.length > 0){
                        optGroup = document.createElement('optgroup');
                        optGroup.label = blocks[b];
                        box2.appendChild(optGroup);
                        for (i = 0; i < list.length; i += 2){
                            objOption = document.createElement("option");
                            objOption.innerHTML = list[i];
                            var objVal = list[i + 1];
                            var newObjVal = objVal.replace(moduleName.toUpperCase() + '_', number.toUpperCase() + '_');
                            objOption.value = newObjVal;
                            optGroup.appendChild(objOption);
                        }
                    }
                }
            }
        },
        change_acc_info: function(element){
            switch (element.value){
                case "Assigned":
                    document.getElementById('acc_info_div').style.display = 'none';
                    document.getElementById('user_info_div').style.display = 'inline';
                    document.getElementById('logged_user_info_div').style.display = 'none';
                    break;
                case "Logged":
                    document.getElementById('acc_info_div').style.display = 'none';
                    document.getElementById('user_info_div').style.display = 'none';
                    document.getElementById('logged_user_info_div').style.display = 'inline';
                    break;
                default:
                    document.getElementById('acc_info_div').style.display = 'inline';
                    document.getElementById('user_info_div').style.display = 'none';
                    document.getElementById('logged_user_info_div').style.display = 'none';
                    break;
            }
        },
        savePDF: function(form){
            var template_name = document.getElementById("filename").value;
            var template_id = document.getElementById("templateid").value;
            var error = 0;
            if (template_name == ""){
                alert(app.vtranslate("LBL_PDF_NAME") + app.vtranslate('CANNOT_BE_EMPTY'));
                error++;
            }

            var pdf_module = document.getElementById("modulename").value;
            if (pdf_module == ""){
                alert(app.vtranslate("LBL_MODULE_ERROR"));
                error++;
            }

            if (!PDFMaker_EditJs.ControlNumber('margin_top', true) || !PDFMaker_EditJs.ControlNumber('margin_bottom', true) || !PDFMaker_EditJs.ControlNumber('margin_left', true) || !PDFMaker_EditJs.ControlNumber('margin_right', true)){
                error++;
            }

            if (!PDFMaker_EditJs.CheckCustomFormat()){
                error++;
            }

            if (!PDFMaker_EditJs.CheckSharing()){
                error++;
            }
            if (error == 0){
		moduleName = app.getModuleName();

		var params = {
		'module' : moduleName,
		'action' : "IndexAjax",
                'mode' : "CheckDuplicateTemplateName",
		'templatename' : template_name,
		'templateid' : template_id
		}
		AppConnector.request(params).then(
                    function(data) {
                        var response = data['result'];
                        var result = response['success'];
                        if(result == true) {
                            form.submit();
                        } else {
                            var message = app.vtranslate('LBL_DUPLICATE_TEMPLATE_CREATION_CONFIRMATION');
                            Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
                                function(e) {
                                    form.submit();
                                },
                                function(error, err) {
                                }
                            );
                        }
                    },
                    function(error,err){
                        form.submit();
                    }
		);
            }
            

           
        },
        ControlNumber: function(elid, final){
            var control_number = document.getElementById(elid).value;
            var re = new Array();
            re[1] = new RegExp("^([0-9])");
            re[2] = new RegExp("^[0-9]{1}[.]$");
            re[3] = new RegExp("^[0-9]{1}[.][0-9]{1}$");
            if (control_number.length > 3 || !re[control_number.length].test(control_number) || (final == true && control_number.length == 2)){
                alert(app.vtranslate("LBL_MARGIN_ERROR"));
                document.getElementById(elid).focus();
                return false;
            } else {
                return true;
            }

        },
        showHideTab: function(tabname){
            var selectedTab = jQuery("#selectedTab").val();
            document.getElementById(selectedTab + '_tab').className = "";
            document.getElementById(tabname + '_tab').className = 'active';
            document.getElementById(selectedTab + '_div').style.display = 'none';
            document.getElementById(tabname + '_div').style.display = '';
            var formerTab = selectedTab;
            var selectedTab = tabname;
            jQuery("#selectedTab").val(selectedTab);
        },
        showHideTab2: function(tabname){
            var selectedTab2 = jQuery("#selectedTab2").val();
            document.getElementById(selectedTab2 + '_tab2').className = "dvtUnSelectedCell";
            document.getElementById(tabname + '_tab2').className = 'dvtSelectedCell';
            if (tabname == 'body'){
                document.getElementById('body_variables').style.display = '';
                document.getElementById('related_block_tpl_row').style.display = '';
                document.getElementById('listview_block_tpl_row').style.display = '';
                if (document.getElementById('headerfooter_div').style.display == 'block')
                    PDFMaker_EditJs.showHideTab('properties');
            } else {
                document.getElementById('header_variables').style.display = '';
                document.getElementById('body_variables').style.display = 'none';
                document.getElementById('related_block_tpl_row').style.display = 'none';
                document.getElementById('listview_block_tpl_row').style.display = 'none';
                if (document.getElementById('headerfooter_div').style.display == 'none')
                    PDFMaker_EditJs.showHideTab('headerfooter');
            }
            document.getElementById(selectedTab2 + '_div2').style.display = 'none';
            document.getElementById(tabname + '_div2').style.display = 'block';
            box = document.getElementById('modulename')
            var module = box.options[box.selectedIndex].value;
            var formerTab = selectedTab2;
            selectedTab2 = tabname;
            jQuery("#selectedTab2").val(selectedTab2);
        },
        showHideTab3: function(tabname){
            var selectedTab2 = jQuery("#selectedTab2").val();
            document.getElementById(selectedTab2 + '_tab2').className = "";
            document.getElementById(tabname + '_tab2').className = 'active';
            if (tabname == 'body'){
                document.getElementById('body_variables').style.display = '';
                document.getElementById('related_block_tpl_row').style.display = '';
                document.getElementById('listview_block_tpl_row').style.display = '';
                if (document.getElementById('headerfooter_div').style.display == 'block')
                    PDFMaker_EditJs.showHideTab('properties');
            } else {
                document.getElementById('header_variables').style.display = '';
                document.getElementById('body_variables').style.display = 'none';
                document.getElementById('related_block_tpl_row').style.display = 'none';
                document.getElementById('listview_block_tpl_row').style.display = 'none';
                if (document.getElementById('headerfooter_div').style.display == 'none')
                    PDFMaker_EditJs.showHideTab('headerfooter');
            }

            document.getElementById(selectedTab2 + '_div2').style.display = 'none';
            document.getElementById(tabname + '_div2').style.display = 'block';
            box = document.getElementById('modulename')
            var module = box.options[box.selectedIndex].value;
            var formerTab = selectedTab2;
            selectedTab2 = tabname;
            jQuery("#selectedTab2").val(selectedTab2);
        },
        fill_module_lang_array: function(module){
            var ajax_url = 'index.php?module=PDFMaker&action=AjaxRequestHandle&handler=fill_lang&langmod=' + module;
            jQuery.ajax(ajax_url).success(function(response){
                var module_lang = document.getElementById('module_lang');
                module_lang.length = 0;
                var map = response.split('|@|');
                var keys = map[0].split('||');
                var values = map[1].split('||');
                for (i = 0; i < values.length; i++){
                    var item = document.createElement('option');
                    item.text = values[i];
                    item.value = keys[i];
                    try {
                        module_lang.add(item, null);
                    } catch (ex){
                        module_lang.add(item);
                    }
                }
            }).error(function(){
                alert('error');
            });
        },
        fill_related_blocks_array: function(module, selected){
            var ajax_url = 'index.php?module=PDFMaker&action=AjaxRequestHandle&handler=fill_relblocks&selmod=' + module;
            jQuery.ajax(ajax_url).success(function(response){
                var related_block = document.getElementById('related_block');
                related_block.length = 0;
                var map = response.split('|@|');
                var keys = map[0].split('||');
                var values = map[1].split('||');
                for (i = 0; i < values.length; i++){
                    var item = document.createElement('option');
                    item.text = values[i];
                    item.value = keys[i];
                    if (selected != undefined && keys[i] == selected)
                        item.selected = true;
                    try {
                        related_block.add(item, null);
                    } catch (ex){
                        related_block.add(item);
                    }
                }
            }).error(function(){
                alert('error');
            });
        },
        fill_module_product_fields_array: function(module){
            var ajax_url = 'index.php?module=PDFMaker&action=AjaxRequestHandle&handler=fill_module_product_fields&productmod=' + module;
            jQuery.ajax(ajax_url).success(function(response){
                var product_fields = document.getElementById('psfields');
                product_fields.length = 0;
                var map = response.split('|@|');
                var keys = map[0].split('||');
                var values = map[1].split('||');
                for (i = 0; i < values.length; i++){
                    var item = document.createElement('option');
                    item.text = values[i];
                    item.value = keys[i];
                    try {
                        product_fields.add(item, null);
                    } catch (ex){
                        product_fields.add(item);
                    }
                }
            }).error(function(){
                alert('error');
            });
        },
        refresh_related_blocks_array: function(selected){
            var module = document.getElementById('modulename').value;
            PDFMaker_EditJs.fill_related_blocks_array(module, selected);
        },
        InsertRelatedBlock: function(){
            var relblockid = document.getElementById('related_block').value;
            if (relblockid == '')
                return false;
            var oEditor = CKEDITOR.instances.body;
            var ajax_url = 'index.php?module=PDFMaker&action=AjaxRequestHandle&handler=get_relblock&relblockid=' + relblockid;
            jQuery.ajax(ajax_url).success(function(response){
                oEditor.insertHtml(response);
            }).error(function(){
                alert('error');
            });
        },
        EditRelatedBlock: function(){
            var relblockid = document.getElementById('related_block').value;
            if (relblockid == ''){
                alert(app.vtranslate('LBL_SELECT_RELBLOCK'));
                return false;
            }

            var popup_url = 'index.php?module=PDFMaker&view=EditRelatedBlock&record=' + relblockid;
            window.open(popup_url, "Editblock", "width=1230,height=700,scrollbars=yes");
        },
        CreateRelatedBlock: function(){
            var pdf_module = document.getElementById("modulename").value;
            if (pdf_module == ''){
                alert(app.vtranslate("LBL_MODULE_ERROR"));
                return false;
            }
            var popup_url = 'index.php?module=PDFMaker&view=EditRelatedBlock&pdfmodule=' + pdf_module;
            window.open(popup_url, "Editblock", "width=1230,height=700,scrollbars=yes");
        },
        DeleteRelatedBlock: function(){
            var relblockid = document.getElementById('related_block').value;
            var result = false;
            if (relblockid == ''){
                alert(app.vtranslate('LBL_SELECT_RELBLOCK'));
                return false;
            } else {
                result = confirm(app.vtranslate('LBL_DELETE_RELBLOCK_CONFIRM') + jQuery("#related_block option:selected").text());
            }

            if (result){
                var ajax_url = 'index.php?module=PDFMaker&action=AjaxRequestHandle&handler=delete_relblock&relblockid=' + relblockid;
                jQuery.ajax(ajax_url).success(function(){
                    PDFMaker_EditJs.refresh_related_blocks_array();
                }).error(function(){
                    alert('error');
                });
            }
        },
        insertFieldIntoFilename: function(val){
            if (val != '')
                document.getElementById('nameOfFile').value += '$' + val + '$';
        },
        CustomFormat: function(){
            var selObj;
            selObj = document.getElementById('pdf_format');

            if (selObj.value == 'Custom'){
                document.getElementById('custom_format_table').style.display = 'table';
            } else {
                document.getElementById('custom_format_table').style.display = 'none';
            }
        },
        ConfirmIsPortal: function(oCheck){
            var module = document.getElementById('modulename').value;
            var curr_templatename = document.getElementById('filename').value;

            if (oCheck.defaultChecked == true && oCheck.checked == false){
                return confirm(app.vtranslate('LBL_UNSET_PORTAL') + '\n' + app.vtranslate('ARE_YOU_SURE'));
            } else if (oCheck.defaultChecked == false && oCheck.checked == true){
                var ajax_url = 'index.php?module=PDFMaker&action=AjaxRequestHandle&handler=confirm_portal&langmod=' + module + '&curr_templatename=' + curr_templatename;
                jQuery.ajax(ajax_url).success(function(){
                    if (confirm(response.responseText + '\n' + app.vtranslate('ARE_YOU_SURE')) == false)
                        oCheck.checked = false;
                }).error(function(){
                    alert('error');
                });
                return true;
            }
        },
        isLvTmplClicked: function(source){
            var oTrigger = document.getElementById('isListViewTmpl');
            var oButt = document.getElementById('listviewblocktpl_butt');
            var oDlvChbx = document.getElementById('is_default_dv');

            document.getElementById('listviewblocktpl').disabled = !(oTrigger.checked);
            oButt.disabled = !(oTrigger.checked);

            if (source != 'init'){
                oDlvChbx.checked = false;
            }
            
            oDlvChbx.disabled = oTrigger.checked;
        },
        hf_checkboxes_changed: function(oChck, oType){
            var prefix;
            var optionsArr;
            if (oType == 'header'){
                prefix = 'dh_';
                optionsArr = new Array('allid', 'firstid', 'otherid');
            } else {
                prefix = 'df_';
                optionsArr = new Array('allid', 'firstid', 'otherid', 'lastid');
            }

            var tmpArr = oChck.id.split("_");
            var sufix = tmpArr[1];
            var i;
            if (sufix == 'allid'){
                for (i = 0; i < optionsArr.length; i++){
                    document.getElementById(prefix + optionsArr[i]).checked = oChck.checked;
                }
            } else {
                var allChck = document.getElementById(prefix + 'allid');
                var allChecked = true;
                for (i = 1; i < optionsArr.length; i++){
                    if (document.getElementById(prefix + optionsArr[i]).checked == false){
                        allChecked = false;
                        break;
                    }
                }
                allChck.checked = allChecked;
            }
        },
        templateActiveChanged: function(activeElm){
            var is_defaultElm1 = document.getElementById('is_default_dv');
            var is_defaultElm2 = document.getElementById('is_default_lv');
            //var is_portalElm = document.getElementById('is_portal');
            if (activeElm.value == '1'){
                is_defaultElm1.disabled = false;
                is_defaultElm2.disabled = false;
            } else {
                is_defaultElm1.checked = false;
                is_defaultElm1.disabled = true;
                is_defaultElm2.checked = false;
                is_defaultElm2.disabled = true;
            }
        },
        CheckCustomFormat: function(){
            if (document.getElementById('pdf_format').value == 'Custom'){
                var pdfWidth = document.getElementById('pdf_format_width').value;
                var pdfHeight = document.getElementById('pdf_format_height').value;
                if (pdfWidth > 2000 || pdfHeight > 2000 || pdfWidth < 1 || pdfHeight < 1 || isNaN(pdfWidth) || isNaN(pdfHeight)){
                    alert(app.vtranslate('LBL_CUSTOM_FORMAT_ERROR'));
                    document.getElementById('pdf_format_width').focus();
                    return false;
                }
            }
            return true;
        },
        CheckSharing: function(){
            if (document.getElementById('sharing').value == 'share'){
                var selColStr = '';
                var selColObj = document.getElementById('sharingSelectedColumns');
                for (i = 0; i < selColObj.options.length; i++){
                    selColStr += selColObj.options[i].value + ';';
                }

                if (selColStr == ''){
                    alert(app.vtranslate('LBL_SHARING_ERROR'));
                    document.getElementById('sharingAvailList').focus();
                    return false;
                }
                document.getElementById('sharingSelectedColumnsString').value = selColStr;
            }

            return true;
        },
        sharing_changed: function(){
            var selectedValue = document.getElementById('sharing').value;
            if (selectedValue != 'share'){
                document.getElementById('sharing_share_div').style.display = 'none';
            } else {
                document.getElementById('sharing_share_div').style.display = 'block';
                PDFMaker_EditJs.setSharingObjects();
                PDFMaker_EditJs.showSharingMemberTypes();
            }
        },
        showSharingMemberTypes: function(){
            var selectedOption = document.getElementById('sharingMemberType').value;
            //Completely clear the select box
            document.getElementById('sharingAvailList').options.length = 0;
            if (selectedOption == 'groups'){
                PDFMaker_EditJs.constructSelectOptions('groups', grpIdArr, grpNameArr);
            } else if (selectedOption == 'roles'){
                PDFMaker_EditJs.constructSelectOptions('roles', roleIdArr, roleNameArr);
            } else if (selectedOption == 'rs'){
                PDFMaker_EditJs.constructSelectOptions('rs', roleIdArr, roleNameArr);
            } else if (selectedOption == 'users'){
                PDFMaker_EditJs.constructSelectOptions('users', userIdArr, userNameArr);
            }
        },
        constructSelectOptions: function(selectedMemberType, idArr, nameArr){
            var i;
            var findStr = document.getElementById('sharingFindStr').value;
            if (findStr.replace(/^\s+/g, '').replace(/\s+$/g, '').length != 0){
                var k = 0;
                for (i = 0; i < nameArr.length; i++){
                    if (nameArr[i].indexOf(findStr) == 0){
                        constructedOptionName[k] = nameArr[i];
                        constructedOptionValue[k] = idArr[i];
                        k++;
                    }
                }
            } else {
                constructedOptionValue = idArr;
                constructedOptionName = nameArr;
            }

//Constructing the selectoptions
            var j;
            var nowNamePrefix;
            for (j = 0; j < constructedOptionName.length; j++){
                if (selectedMemberType == 'roles'){
                    nowNamePrefix = 'Roles::';
                } else if (selectedMemberType == 'rs'){
                    nowNamePrefix = 'RoleAndSubordinates::';
                } else if (selectedMemberType == 'groups'){
                    nowNamePrefix = 'Group::';
                } else if (selectedMemberType == 'users'){
                    nowNamePrefix = 'User::';
                }

                var nowName = nowNamePrefix + constructedOptionName[j];
                var nowId = selectedMemberType + '::' + constructedOptionValue[j]
                document.getElementById('sharingAvailList').options[j] = new Option(nowName, nowId);
            }
//clearing the array
            constructedOptionValue = new Array();
            constructedOptionName = new Array();
        },
        sharingAddColumn: function(){
            for (i = 0; i < this.selectedColumnsObj.length; i++){
                this.selectedColumnsObj.options[i].selected = false;
            }

            for (i = 0; i < this.availListObj.length; i++){
                if (this.availListObj.options[i].selected == true){
                    var rowFound = false;
                    var existingObj = null;
                    for (j = 0; j < this.selectedColumnsObj.length; j++){
                        if (this.selectedColumnsObj.options[j].value == this.availListObj.options[i].value){
                            rowFound = true;
                            existingObj = this.selectedColumnsObj.options[j];
                            break;
                        }
                    }

                    if (rowFound != true){
                        var newColObj = document.createElement("OPTION");
                        newColObj.value = this.availListObj.options[i].value;
                        /*if (browser_ie)
                         newColObj.innerText = this.availListObj.options[i].innerText;
                         else if (browser_nn4 || browser_nn6)*/
                        newColObj.text = this.availListObj.options[i].text;
                        this.selectedColumnsObj.appendChild(newColObj);
                        this.availListObj.options[i].selected = false;
                        newColObj.selected = true;
                        rowFound = false;
                    } else {
                        if (existingObj != null)
                            existingObj.selected = true;
                    }
                }
            }
        },
        sharingDelColumn: function(){
            for (i = this.selectedColumnsObj.options.length; i > 0; i--){
                if (this.selectedColumnsObj.options.selectedIndex >= 0)
                    this.selectedColumnsObj.remove(this.selectedColumnsObj.options.selectedIndex);
            }
        },
        setSharingObjects: function(){
            this.availListObj = document.getElementById("sharingAvailList");
            this.selectedColumnsObj = document.getElementById("sharingSelectedColumns");
        },
        checkDuplicateTemplateName: function(details) {
            return aDeferred.promise();
	}
    }    
}
PDFMaker_EditJs.setSharingObjects();