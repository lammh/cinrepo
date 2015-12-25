Array.prototype.last = function() {
    return this[this.length-1];
}

var WFCondition = {
    vtinst:false,
    loadedRefModules: [],
    describeModule:false,
    haveToLoadRefModules: 0,
    moduleFields: {},
    condition:{},
    tmpGroups: {},
    incrementalStage: 0,
    available_users: {},
    availCurrency: {},
    wsModuleFieldOptions: {},
    recordId: "",
    recordName: "",
    recordIndex: 0,
    hasChanged: true,
    parentId: "",
    parentName: "",
    parentIndex: "",
    lastGroupIndex: "",

    prevType:false,
    prevGroupRecordName: false,
    tmpJoinStack:[],

    columnsRewrites: {
        "assigned_user_id" : "smownerid"
    },
    /**
        Should the join selects created visibly
     */
    startJoinSelects: false,
    preSelectJoin: "and",

    operationOptions: {
        "picklist": ['equal'/*,['has_changed','has Changed'] */],
        "multipicklist": ['equal'/*, 'contains'/*,['has_changed','has Changed'] */],
        "boolean": ['is_checked'/*,['has_changed','has Changed'] */],
        "number": ['equal', 'bigger', 'lower'],
        "date": ['date_empty','equal', 'bigger', 'lower'],
        "text" : ['equal', 'contains', 'starts_with', 'ends_with', 'is_empty', 'is_numeric' ]
    },

    init: function(eleId) {
        WFCondition.condition_container = jQuery("#" + eleId);

        if(WFCondition.hasChanged == true) {
            WFCondition.operationOptions["text"].push("has_changed");
            WFCondition.operationOptions["date"].push("has_changed");
            WFCondition.operationOptions["number"].push("has_changed");
            WFCondition.operationOptions["boolean"].push("has_changed");
            WFCondition.operationOptions["picklist"].push("has_changed");
            WFCondition.operationOptions["multipicklist"].push("has_changed");
        }

        WFCondition.vtinst = new VtigerWebservices("webservice.php");

        if(WFCondition.describeModule === false && WFCondition.moduleFields === false) {
            WFCondition.vtinst.extendSession(handleError(function(result) {
                WFCondition.loadModuleFields(condition_module, true);
            }));
        } else {
            WFCondition.loadModuleFields(condition_module, true);
        }

    },
    import: function(value) {
        WFCondition.condition = value;
    },
    initAfterRefModules: function() {
        if(WFCondition.haveToLoadRefModules > 0) {
            window.setTimeout("WFCondition.initAfterRefModules();", "1000");
            return;
        }

        WFCondition.condition_container.fadeOut("fast", function() {
            WFCondition.condition_container.html(WFCondition.addTestGroup(WFCondition.condition));

            WFCondition.condition_container.fadeIn("fast", function() {
                jQuery(".condition_field", WFCondition.condition_container).chosen();
                jQuery("select.condition_value", WFCondition.condition_container).chosen();
            });
            InitAutocompleteText();
        });

    },
    addTestGroup: function(conditional) {
        var html = "";
        WFCondition.tmpGroups[WFCondition.recordId] = {'content': false, 'incrementalStage':WFCondition.incrementalStage };

        var groupRecordIndex = WFCondition.recordIndex;
        var groupstartJoinSelects = WFCondition.startJoinSelects;
        html += "<div class='conditional_group'  id='group_" + WFCondition.recordIndex + "_container' style='background-color:#" + WFCondition.backgroundColors[WFCondition.incrementalStage] + "'><div id='group_" + WFCondition.recordIndex + "'>";
        var lastGroupJoinName = WFCondition.prevGroupRecordName;

        WFCondition.recordIndex = WFCondition.recordIndex + 1;
        if(conditional !== undefined) {
            html += WFCondition.parseGroupItems(conditional);
        }

        WFCondition.startJoinSelects = groupstartJoinSelects;

        html += "</div>";
        html += "<div class='btn-toolbar' style='margin: 5px 0 0 0;'><div class='btn-group'><button type='button' class='btn btn-info' onclick=\"WFCondition.addGroup('" + WFCondition.recordId + "', '" + WFCondition.recordName + "', '" + groupRecordIndex + "');\"><i class='icon-folder-open icon-white'></i>&nbsp;&nbsp;" + MOD["LBL_ADD_GROUP"] + "</button>";
        html += "<button type='button' class='btn btn-primary' onclick=\"WFCondition.addCondition('" + WFCondition.recordId + "', '" + WFCondition.recordName + "', '" + groupRecordIndex + "');\"><i class='icon-plus-sign icon-white'></i>&nbsp;&nbsp;" + MOD["LBL_ADD_CONDITION"] + "</button></div>";

        if(groupRecordIndex != '0') {
            html += "<div class='btn-group pull-right'><button type='button' class='btn btn-danger' onclick=\"WFCondition.removeGroup('" + WFCondition.recordId + "', '" + WFCondition.recordName + "', '" + groupRecordIndex + "');\"><i class='icon-remove icon-white'></i>&nbsp;&nbsp;" + MOD["LBL_REMOVE_GROUP"] + "</button></div>";
        }
        html += "</div>";
        html += "</span></div>";

        html += WFCondition.addJoinSelect(WFCondition.parentId, WFCondition.parentName, groupRecordIndex, lastGroupJoinName);

        return html;
    },
    parseGroupItems: function(conditional) {
        var html = "";
        var counter = 0;
        var doneJoin = false;
        for(var i in conditional) {

            WFCondition.preSelectJoin = conditional[i].join;
            if(doneJoin === false) {

                WFCondition.tmpJoinStack.push(conditional[i].join);
                doneJoin = true;
            }


            if(counter < jQuery.assocArraySize(conditional) - 1) {
                WFCondition.startJoinSelects = true;
            } else {
                WFCondition.startJoinSelects = false;
            }

            if(counter == jQuery.assocArraySize(conditional)) {
                break;
            }

            counter++;
            if(conditional[i].type == "group") {
                var oldRecordId = parentId = WFCondition.recordId;
                var oldRecordName = parentName = WFCondition.recordName;

                WFCondition.recordId += "_g" + i;
                WFCondition.recordName += "[g" + i + "]";
                WFCondition.incrementalStage += 1;

                WFCondition.prevType = "group";
                WFCondition.prevGroupRecordName = "g" + i;

                html += WFCondition.addTestGroup(conditional[i].childs);

                WFCondition.incrementalStage -= 1;
                WFCondition.recordId = oldRecordId;
                WFCondition.recordName = oldRecordName;
                WFCondition.tmpGroups[WFCondition.recordId].content = true;
            } else {
                WFCondition.prevType = "record";
                WFCondition.prevGroupRecordName = WFCondition.recordIndex;

                html += WFCondition.parseTestRecord(conditional[i]);

                WFCondition.tmpGroups[WFCondition.recordId].content = true;
            }

        }
        WFCondition.tmpJoinStack.pop();

        WFCondition.preSelectJoin = "and";
        WFCondition.startJoinSelects = false;

        return html;
    },
    loadModuleFields: function(module_name, loadReferences, namePrefix) {
        var vtinst = WFCondition.vtinst;

        if(namePrefix == undefined) namePrefix = "";
        if(loadReferences == undefined) namePrefix = false
            if(WFCondition.moduleFields !== false) {
                WFCondition.describeModule = {};
                jQuery.each(WFCondition.moduleFields, function(index, value) {
                    jQuery.each(value, function(fieldIndex, fieldValue) {
                        WFCondition.describeModule[fieldValue.name] = fieldValue;
                    });
                });
                WFCondition.initAfterRefModules();
            } else {
                vtinst.describeObject(module_name, handleError(function(result) {

                    for(var a = 0; a < result.fields.length; a++) {
                        if(WFCondition.columnsRewrites[result.fields[a]["name"]] !== undefined) {
                            result.fields[a]["name"] = WFCondition.columnsRewrites[result.fields[a]["name"]];
                        }

                        if(result.fields[a]["type"]["name"] == "reference" && loadReferences == true) {
                            jQuery.each(result.fields[a]["type"]["refersTo"], function(index, value) {
                               if(WFCondition.loadedRefModules.indexOf(value) > -1) return;

                                WFCondition.haveToLoadRefModules = WFCondition.haveToLoadRefModules + 1;
                                WFCondition.loadModuleFields(value, false, "(" + result.fields[a]["name"]+": ("+value+")) ");
                                WFCondition.loadedRefModules.push(value);
                            });
                        }

                        var ifRefField = namePrefix.match(/\((.*): \((.*)\)\)/);
                        if(ifRefField == null) {
                            fieldName = namePrefix + result.fields[a]["name"];
                        } else {
                            fieldName = "("+ifRefField[1]+": ("+ifRefField[2]+") "+result.fields[a]["name"]+")";
                        }

                        WFCondition.describeModule[fieldName] = result.fields[a];
                    }

                    if(loadReferences == true) {
                        WFCondition.initAfterRefModules();
                    } else {
                        WFCondition.haveToLoadRefModules = WFCondition.haveToLoadRefModules - 1;
                    }
                }));
            }

            WFCondition.describeModule["smownerid"]["type"]["picklistValues"] = {};

            //WFCondition.describeModule["smownerid"] = WFCondition.describeModule["smownerid"];
            WFCondition.describeModule["smownerid"]["type"]["name"] = "picklist";
            WFCondition.describeModule["smownerid"]["type"]["picklistValues"] = {};

            WFCondition.describeModule["smownerid"]["type"]["picklistValues"]['$current_user_id'] = '$currentUser';

            if(WFCondition.available_users !== null) {
                jQuery.each(WFCondition.available_users["user"], function(value, label) {
                    WFCondition.describeModule["smownerid"]["type"]["picklistValues"][value] = label;
                });

                jQuery.each(WFCondition.available_users["group"], function(value, label) {
                    WFCondition.describeModule["smownerid"]["type"]["picklistValues"][value] = "Group: " + label;
                });

            }


            WFCondition.describeModule["DEFAULTFIELD"] = {
                type: {
                    name:'text'
                }
            };

        InitAutocompleteText();
    },
    importCurrency: function(currency) {
        WFCondition.availCurrency = currency;
    },
    importUser: function(user) {
        WFCondition.available_users = user;
    },
    initChosen:function(tmpIndex) {
        jQuery('#group_' + tmpIndex + " .condition_field").chosen();
    },
    backgroundColors: ['f9f9f9','e7f4fe', 'd9d9d9','e7f4fe','b9b9b9','d9d9d9','e9e9e9','f9f9f9'],
    addCondition: function(TMPrecordId, TMPrecordName, TMPrecordIndex) {
        WFCondition.recordId = TMPrecordId;
        WFCondition.recordName = TMPrecordName;
        var tmpHtml = WFCondition.addConditionRow();

        WFCondition.tmpGroups[WFCondition.recordId].content = true;

        prevGroupRecordName = WFCondition.recordIndex-1;

        var preSetJoin = jQuery('#group_' + TMPrecordIndex + ' > .conditional_join select').val();

        jQuery('#group_' + TMPrecordIndex + ' > .conditional_join', WFCondition.condition_container).css("display", "inline");
        jQuery('#group_' + TMPrecordIndex, WFCondition.condition_container).append(tmpHtml);
        jQuery('#group_' + TMPrecordIndex + ' > .conditional_join select', WFCondition.condition_container).val(preSetJoin);

        WFCondition.initChosen(TMPrecordIndex);
    },
    parseTestRecord: function(cond) {

        return html = WFCondition.addConditionRow(cond);

    },
    setModuleFields: function(value) {
        WFCondition.moduleFields = value;
    },
    addConditionRow:function(cond) {
        var tmpHtml = "";
        // old format to new format
        if(typeof cond != "undefined" && cond.field.match(/\((\S+): \((\S+)\)\) (\S+)/)) {
            var parts = cond.field.match(/\((\S+): \((\S+)\)\) (\S+)/);

            cond.field = "("+parts[1] + ": ("+parts[2]+") "+parts[3]+")";
        }

        if(typeof cond != "undefined" && WFCondition.describeModule[cond.field] === undefined) {
            return "";
        }

        tmpHtml += "<div class='conditional_record' onmouseover=\"jQuery('.conditional_record_hover').removeClass('conditional_record_hover');jQuery('.conditional_group_hover').removeClass('conditional_group_hover'); jQuery(this).addClass('conditional_record_hover');jQuery(this).parent().parent().addClass('conditional_group_hover');\" id='record_" + WFCondition.recordIndex + "'>";

        tmpHtml += "<img class='condition_remove' src='modules/Workflow2/cross-button.png' alt='delete' onclick=\"WFCondition.removeCondition('" + WFCondition.recordId + "', '" + WFCondition.recordName + "', '" + WFCondition.recordIndex + "');\">";
        var fieldOptions = "";

        if(cond !== undefined) {
            oldFormat = cond.field.match(/\((.*) ?: \((.*)\)\) (.*)/);
            if(oldFormat !== null) {
                cond.field = "(" + oldFormat[1] + ": (" + oldFormat[2] + ") " + oldFormat[3] + ")"
            }

        }

        jQuery.each(WFCondition.moduleFields, function(key, value) {
            fieldOptions += "<optgroup label='" + key + "'>";
                for(var i = 0; i < WFCondition.moduleFields[key].length;i++) {
                    if(WFCondition.columnsRewrites[WFCondition.moduleFields[key][i].name] !== undefined) {
                        WFCondition.moduleFields[key][i].name = WFCondition.columnsRewrites[WFCondition.moduleFields[key][i].name];
                    }

                    fieldOptions += "<option value='" + WFCondition.moduleFields[key][i].name + "' " + (cond != undefined && cond.field == WFCondition.moduleFields[key][i].name ? 'selected="selected"' : '') + ">" + WFCondition.moduleFields[key][i].label + "</option>";
                }
            fieldOptions += "</optgroup>";
        });

        tmpHtml += "<select class='condition_field' onchange=\"WFCondition.updateFieldValue('" + WFCondition.recordId + "', '" + WFCondition.recordName + "', '" + WFCondition.recordIndex + "');\" name='task[condition]" + WFCondition.recordName + "[" + WFCondition.recordIndex + "][field]' id='records_" + WFCondition.recordId + "_field" + WFCondition.recordIndex + "'>" + fieldOptions+ "</select>";

        var operationHtml = WFCondition.getOperations(WFCondition.recordId, WFCondition.recordName, WFCondition.recordIndex, cond !== undefined ? cond.field : false, cond !== undefined ? cond.operation : "");

        tmpHtml += "<select style='margin-left:10px;' class='condition_not' name='task[condition]" + WFCondition.recordName + "[" + WFCondition.recordIndex + "][not]'><option value='0'>-</option><option value='1' " + (cond != undefined && cond.not == "1" ? 'selected="selected"' : '') + ">" + MOD["LBL_NOT"] + "</option></select>";

        tmpHtml += "<select class='condition_operation' name='task[condition]" + WFCondition.recordName + "[" + WFCondition.recordIndex + "][operation]' id='records_" + WFCondition.recordId + "_operation" + WFCondition.recordIndex + "'>" + operationHtml + "</select>";

        //if(describeModule[cond.field].type["name"] != "boolean") {
            tmpHtml += '<select class="condition_mode" '+(cond !== undefined && WFCondition.describeModule[cond.field].type["name"] != "boolean" ? "" : "disabled='disabled'")+' onchange=\'WFCondition.updateFieldValue("' + WFCondition.recordId + '", "' + WFCondition.recordName + '", "' + WFCondition.recordIndex + '");\'  name="task[condition]' + WFCondition.recordName + '[' + WFCondition.recordIndex + '][mode]" id="records_' + WFCondition.recordId + '_mode'+WFCondition.recordIndex+'"><option value="value" ' + (cond != undefined && cond.mode == "value" ? 'selected="selected"' : '') + '>'+MOD.LBL_STATIC_VALUE+'</option><option value="function" ' + (cond != undefined && cond.mode == "function" ? 'selected="selected"' : '') + '>'+MOD.LBL_FUNCTION_VALUE+'</option></select>';
        //}

        tmpHtml += "<span id='conditionContainer_" + WFCondition.recordId + "_" + WFCondition.recordIndex + "'>" + WFCondition.getConditionInput(WFCondition.recordId, WFCondition.recordName, WFCondition.recordIndex, cond !== undefined ? cond.field : false, cond !== undefined ? cond.rawvalue : "", cond !== undefined ? cond.mode : "") + "</span>";
        tmpHtml += "</div>";

        tmpHtml += WFCondition.addJoinSelect(WFCondition.recordId, WFCondition.recordName, WFCondition.recordIndex, WFCondition.recordIndex);

        WFCondition.recordIndex = WFCondition.recordIndex + 1;
        return tmpHtml;
    },
    setDescribeModule: function(describe) {
        WFCondition.describeModule = describe;
    },
    getOperations: function(recordId, recordName, recordIndex, field, operation) {
        if(WFCondition.describeModule[field] !== undefined) {
            fieldTypeName = WFCondition.describeModule[field].type["name"];
        } else {
            fieldTypeName = "text";
        }

        switch(fieldTypeName) {
            case "integer":
            case "currency":
                recordOperationOptions = WFCondition.operationOptions["number"];
            break;
            case "date":
            case "datetime":
                recordOperationOptions = WFCondition.operationOptions["date"];
            break;
            case "multipicklist":
            case "picklist":
            case "boolean":
                recordOperationOptions = WFCondition.operationOptions[fieldTypeName];
            break;
            default:
                recordOperationOptions = WFCondition.operationOptions["text"];
            break;
        }

        var operationHtml;
        for(var i = 0; i < recordOperationOptions.length; i++) {
            operationHtml += "<option value='" + recordOperationOptions[i] + "' " + (operation != undefined && operation == recordOperationOptions[i] ? 'selected="selected"' : '') + ">" + MOD["LBL_COND_" + recordOperationOptions[i].toUpperCase()] + "</option>";
        }

        return operationHtml;
    },
    addJoinSelect: function(recordId, recordName, recordIndex, joinName) {
        if(joinName === false) return "";

        var selectedJoin = WFCondition.tmpJoinStack.last();

        //return "<div class='conditional_join' style='display:" + (WFCondition.startJoinSelects ? "inline":"none") + ";' id='conditional_join_" + (recordIndex) + "'><select class='joinSelector' onchange='WFCondition.changeSelector(this);' name='join[" + joinName + "]'><option value='and' " + (WFCondition.preSelectJoin=="and"?"selected='selected'":"") + ">" + MOD["LBL_AND"].toUpperCase() + "</option><option value='or' " + (WFCondition.preSelectJoin=="or"?"selected='selected'":"") + ">" + MOD["LBL_OR"].toUpperCase() + "</option></select></div>";
        return "<div class='conditional_join' style='display:" + (WFCondition.startJoinSelects ? "inline":"none") + ";' id='conditional_join_" + (recordIndex) + "'><select class='joinSelector' onchange='WFCondition.changeSelector(this);' name='join[" + joinName + "]'><option value='and' " + (selectedJoin=="and"?"selected='selected'":"") + ">" + MOD["LBL_AND"].toUpperCase() + "</option><option value='or' " + (selectedJoin=="or"?"selected='selected'":"") + ">" + MOD["LBL_OR"].toUpperCase() + "</option></select></div>";
    },
    getConditionInput: function(precordId, precordName, precordIndex, pfield, pvalue, pmode) {

        var selField = pfield
        var currentValue = (typeof pvalue == "undefined"?"":pvalue);

        var fieldId = "records_" + precordId + "_rawvalue"+precordIndex+"";
        var fieldName = "task[condition]" + precordName + "[" + precordIndex + "][rawvalue]";

        if(typeof pmode == "undefined") {
            pmode = jQuery("#records_" + precordId + "_mode" + precordIndex).val();
        }

        if(pmode == "function") {
            var html = "<textarea class='customFunction' style='width:300px;' name='" + fieldName + "' id='" + fieldId + "'>" + currentValue + "</textarea>";
            html += "<span id='" + fieldId + "_iconspan'><img src='modules/Workflow2/icons/templatefieldPHP.png' style='margin-bottom:-2px;cursor:pointer;' onclick=\"insertTemplateField('" + fieldId + "', '[source]->[module]->[destination]', false)\">";
            return html;
        }

        if(WFCondition.describeModule[selField] !== undefined) {
            fieldTypeName = WFCondition.describeModule[selField].type["name"];
        } else {
            fieldTypeName = "text";
        }
        html = "";

        jQuery('#records_' + precordId + '_mode' + precordIndex).removeAttr("disabled");

        switch(fieldTypeName) {
            case "multipicklist":
            case "picklist":
                var html = "<select class='condition_value select' name='" + fieldName + "' id='" + fieldId + "'>";
                html += "<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;&nbsp;"+MOD.LBL_EMPTY_VALUE+"</option>";

                html += "<optgroup label='"+MOD.LBL_VALUES+"'>";

                jQuery.each(WFCondition.describeModule[selField].type.picklistValues, function(index, value) {
                    html += "<option " + (currentValue ==  index ? "selected='selected'" : "") + " value='" + index + "'>" + value + "</option>";
                });

                html += "</optgroup>";

                html += "</select>";
            break;
            case "boolean":
                jQuery('#records_' + precordId + '_mode'+precordIndex).attr("disabled", "disabled");
                var html = '<span id="' + fieldId + '"></span>';
                break;
            case "date":
            case "datetime":
                html = createTemplateDatefield(fieldName, fieldId, currentValue, {"showTime": fieldTypeName=="datetime", "format": '%Y-%m-%d'});
                return html;
                break;
            case "reference":
                var referTo = WFCondition.describeModule[selField].type.refersTo[0];
                if(referTo == "Currency" && typeof WFCondition.availCurrency !== "undefined") {
                    var html = "<select class='condition_value select' name='" + fieldName + "' id='" + fieldId + "'>";

                    for(var a = 0;a < WFCondition.availCurrency.length; a++) {
                        html += "<option " + (currentValue ==  WFCondition.availCurrency[a].curid ? "selected='selected'" : "") + " value='" + WFCondition.availCurrency[a].curid + "'>" + WFCondition.availCurrency[a].currencylabel + "</option>";
                    }

                    html += "</select>";

                    return html;
                }
                var html = createTemplateTextfield(fieldName, fieldId, currentValue, { refFields: true, module: condition_fromModule });
                return html;
                break;
            default:
                html = createTemplateTextfield(fieldName, fieldId, currentValue, { module: condition_fromModule});
            break;
        }

        return html;

    },
    enableHasChanged: function(value) {
        WFCondition.hasChanged = value;
    },
    changeSelector: function(ele) {
        jQuery(ele).parent().parent().children(".conditional_join").children("select").val(ele.value);
    },
    addGroup: function(TMPrecordId, TMPrecordName, TMPrecordIndex) {
        var oldRecordId = parentId = WFCondition.recordId;
        var oldRecordName = parentName = WFCondition.recordName;
        WFCondition.recordId = TMPrecordId + "_g" + WFCondition.recordIndex;
        WFCondition.recordName = TMPrecordName + "[g" + WFCondition.recordIndex + "]";

        WFCondition.prevGroupRecordName = "g" + WFCondition.recordIndex;

        WFCondition.incrementalStage = WFCondition.tmpGroups[TMPrecordId].incrementalStage + 1;

        var tmpHtml = WFCondition.addTestGroup();

        WFCondition.recordId = oldRecordId;
        WFCondition.recordName = oldRecordName;

        WFCondition.tmpGroups[parentId].content = true;

        jQuery('#group_' + TMPrecordIndex + ' > .conditional_join', WFCondition.condition_container).css("display", "inline");
        jQuery('#group_' + TMPrecordIndex, WFCondition.condition_container).append(tmpHtml);

        jQuery('#group_' + TMPrecordIndex + ' > .conditional_join select', WFCondition.condition_container).val(preSetJoin);



    },
    removeGroup: function(precordId, precordName, precordIndex) {
        if(confirm("Delete this group?") == false) return;

        jQuery("#conditional_join_" + precordIndex + "").remove();
        jQuery('#group_' + precordIndex).parent().parent().children(".conditional_join:last").css("display", "none");
        jQuery("#group_" + precordIndex + "_container").slideUp('fast', function() { jQuery(this).remove(); });
    },
    removeCondition: function(precordId, precordName, precordIndex) {
        // console.log(jQuery('#record_' + recordIndex).parent().children(".conditional_join:last"));

        jQuery('#conditional_join_' + precordIndex).remove();
        jQuery('#record_' + precordIndex).parent().children(".conditional_join:last").css("display", "none");
        jQuery('#record_' + precordIndex).slideUp('fast', function() { jQuery(this).remove(); });

    },
    updateFieldValue: function(precordId, precordName, precordIndex) {
        var field = jQuery("#records_" + precordId + "_field" + precordIndex).val();
        var oldvalue = jQuery("#records_" + precordId + "_rawvalue" + precordIndex).val();
        var oldvalueOperation = jQuery("#records_" + precordId + "_operation" + precordIndex).val();

        var html = "<span id='conditionContainer_" + precordId + "_" + precordIndex + "'>" + WFCondition.getConditionInput(precordId, precordName, precordIndex, field, oldvalue) + "</span>";

        var htmlOperations = WFCondition.getOperations(precordId, precordName, precordIndex, field, oldvalueOperation);

    //    jQuery("#records_" + recordId + "_rawvalue"+recordIndex + "_iconspan").remove();
        jQuery("#conditionContainer_" + precordId + "_" + precordIndex + "", WFCondition.condition_container).replaceWith(html);
        jQuery("#conditionContainer_" + precordId + "_" + precordIndex + " select.condition_value", WFCondition.condition_container).chosen();

        jQuery("#records_" + precordId + "_operation" + precordIndex, WFCondition.condition_container).html(htmlOperations);
    }

};
