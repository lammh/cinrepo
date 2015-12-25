/**
 * Created by JetBrains PhpStorm.
 * User: Stefan
 * Date: 18.07.12
 * Time: 23:02
 * To change this template use File | Settings | File Templates.
 */
var wsModuleFieldOptions;
var countValues = 0;
var setFieldValueSelectvalue = false;

// Production steps of ECMA-262, Edition 5, 15.4.4.14
// Reference: http://es5.github.io/#x15.4.4.14
if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function(searchElement, fromIndex) {

    var k;

    // 1. Let O be the result of calling ToObject passing
    //    the this value as the argument.
    if (this == null) {
      throw new TypeError('"this" is null or not defined');
    }

    var O = Object(this);

    // 2. Let lenValue be the result of calling the Get
    //    internal method of O with the argument "length".
    // 3. Let len be ToUint32(lenValue).
    var len = O.length >>> 0;

    // 4. If len is 0, return -1.
    if (len === 0) {
      return -1;
    }

    // 5. If argument fromIndex was passed let n be
    //    ToInteger(fromIndex); else let n be 0.
    var n = +fromIndex || 0;

    if (Math.abs(n) === Infinity) {
      n = 0;
    }

    // 6. If n >= len, return -1.
    if (n >= len) {
      return -1;
    }

    // 7. If n >= 0, then Let k be n.
    // 8. Else, n<0, Let k be len - abs(n).
    //    If k is less than 0, then let k be 0.
    k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

    // 9. Repeat, while k < len
    while (k < len) {
      // a. Let Pk be ToString(k).
      //   This is implicit for LHS operands of the in operator
      // b. Let kPresent be the result of calling the
      //    HasProperty internal method of O with argument Pk.
      //   This step can be combined with c
      // c. If kPresent is true, then
      //    i.  Let elementK be the result of calling the Get
      //        internal method of O with the argument ToString(k).
      //   ii.  Let same be the result of applying the
      //        Strict Equality Comparison Algorithm to
      //        searchElement and elementK.
      //  iii.  If same is true, return k.
      if (k in O && O[k] === searchElement) {
        return k;
      }
      k++;
    }
    return -1;
  };
}

function loadModuleFields(module_name) {
    if(typeof extraWsModuleFieldOptions != "undefined") {
        wsModuleFieldOptions = extraWsModuleFieldOptions;
    } else {
        wsModuleFieldOptions = {};
    }

    for(var a = 0; a < setter_fields.length; a++) {
        wsModuleFieldOptions[setter_fields[a]["name"]] = setter_fields[a];
    }
    if(typeof wsModuleFieldOptions["assigned_user_id"] == 'undefined' && wsModuleFieldOptions["smownerid"] != 'undefined') {
        wsModuleFieldOptions["assigned_user_id"] = wsModuleFieldOptions["smownerid"];
    }
    if(typeof wsModuleFieldOptions["assigned_user_id"] != "undefined") {
        wsModuleFieldOptions["assigned_user_id"]["type"]["name"] = "picklist";
        wsModuleFieldOptions["assigned_user_id"]["type"]["picklistValues"] = {};

        wsModuleFieldOptions["assigned_user_id"]["type"]["picklistValues"]['$assigned_user_id'] = '$assigned_user_id';
        wsModuleFieldOptions["assigned_user_id"]["type"]["picklistValues"]['$current_user_id'] = '$currentUser';

        for(var a = 0; a < available_users["user"].length; a++) {
            wsModuleFieldOptions["assigned_user_id"]["type"]["picklistValues"][available_users["user"][a]["id"]] = available_users["user"][a]["user_name"];
        }
        for(var a = 0; a < available_users["group"].length; a++) {
            wsModuleFieldOptions["assigned_user_id"]["type"]["picklistValues"][available_users["group"][a]["groupid"]] = "Group: " + available_users["group"][a]["groupname"];
        }
    }

    wsModuleFieldOptions["DEFAULTFIELD"] = {
        type: {
            name:'text'
        }
    };
    wsModuleFieldOptions["smownerid"] = wsModuleFieldOptions["assigned_user_id"];

    jQuery("#setter_container").html(initValues(setter_values));

    app.registerEventForDatePickerFields();
}

function initValues() {
    jQuery("#setter_container").html("");

    if(setter_values == -1 || setter_values === false) {
        return;
    }
    for(var i in setter_values) {
        addRow(i, setter_values[i].field);

        jQuery("#setter_" + i + "_field").select2('val',setter_values[i].field);
        jQuery("#setter_" + i + "_mode").select2('val',setter_values[i].mode);

        jQuery("#setter_" + i + "_field, #setter_" + i + "_field_chosen").attr("alt", setter_values[i].field);
        jQuery("#setter_" + i + "_field, #setter_" + i + "_field_chosen").attr("title", setter_values[i].field);


        if(setter_values[i].fixed != undefined && setter_values[i].fixed == true) {
            jQuery("#setter_" + i + "_field").attr("disabled", true).select2('disable');
            jQuery("#setter_" + i + "_field").attr("name","dummy");
            jQuery("<input type='hidden' name='task[setter]["+i+"][field]' value='"+setter_values[i].field+"'><input type='hidden' name='task[setter]["+i+"][fixed]' value='1'>").insertBefore(jQuery("#setter_" + i + "_field"));
        }

        // jQuery("#setter_" + i + "_value").val();
        var inputHTML = getValueInput(i, setter_values[i].value);
        jQuery("#value_" + i + "_container").html(inputHTML);

        if(setFieldValueSelectvalue !== false)  {
            jQuery("select#setter_" + i + "_value").val(setFieldValueSelectvalue);
            setFieldValueSelectvalue = false;
        }
    }

    jQuery('.blockContainer').sortDivs();

    InitAutocompleteText();
}

function onChangeField(event) {
    parts = jQuery(this).attr("id").split("_");
    rowID = parts[1];

    if(';;;delete;;;' == jQuery("#setter_" + rowID + "_field").val()) {
        jQuery('#setterRow_' + rowID).remove();
        return;
    }

    var inputHTML = getValueInput(rowID);
    jQuery("#value_" + rowID + "_container").html(inputHTML);

    if(setFieldValueSelectvalue !== false)  {
        jQuery("select#setter_" + rowID + "_value").val(setFieldValueSelectvalue);
        setFieldValueSelectvalue = false;
    }

    jQuery("select#setter_" + rowID + "_value").select2();

    jQuery("#setter_" + rowID + "_field_chosen").attr("alt", jQuery("#setter_" + rowID + "_field").val());
    jQuery("#setter_" + rowID + "_field_chosen").attr("title", jQuery("#setter_" + rowID + "_field").val());
}

function getValueInput(rowID, current_value) {
    if(typeof WfSetterFromModule == "undefined") {
        WfSetterFromModule = "undefined";
    }

    var selField = jQuery("#setter_" + rowID + "_field").val();
    var mode = jQuery("#setter_" + rowID + "_mode").val();
        // function getConditionInput(recordId, recordName, recordIndex, field, value) {

    var currentValue;
    if(current_value === undefined && jQuery("#setter_" + rowID + "_value") !== undefined) {
        currentValue = jQuery("#setter_" + rowID + "_value").val();
    } else {
        currentValue = current_value;
    }

    var fieldId = "setter_" + rowID + "_value";
    var fieldName = "task[setter][" + rowID + "][value]";

    if(mode == "function") {
        var html = "<textarea class='textfield customFunction' style='width:300px;' name='" + fieldName + "' id='" + fieldId + "'>" + currentValue + "</textarea>";
        html += "<img src='modules/Workflow2/icons/templatefieldPHP.png' style='margin-bottom:-2px;cursor:pointer;' onclick=\"insertTemplateField('" + fieldId + "', '[source]->[module]->[destination]', true)\">";
        return html;
    } else if(mode == "field") {
        setFieldValueSelectvalue = currentValue;

        var html = jQuery('#fromFieldsFieldValues').html();
        return html.replace(/##FIELDNAME##/g, fieldName).replace(/##FIELDID##/g, fieldId);
    }

    if(typeof wsModuleFieldOptions[selField] != 'undefined') {
        fieldTypeName = wsModuleFieldOptions[selField].type["name"];
    } else {
        return '<em style="line-height:28px;color:#777777;">-' + app.vtranslate('TXT_CHOOSE_VALID_FIELD') + ' -</em>';
        //fieldTypeName = "text";
    }
    // console.log(fieldTypeName);

    var optionTemplates = {};

    switch(fieldTypeName) {
        case "owner":
            var html = "<select style='width:300px;' class='select2 condition_value select' name='" + fieldName + "' id='" + fieldId + "'>";
                html += "<option " + (currentValue ==  "$current_user_id" ? "selected='selected'" : "") + " value=''>$current_user_id</option>";

            jQuery.each(available_users["user"], function(index, value) {
                html += "<option " + (currentValue ==  index ? "selected='selected'" : "") + " value='" + index + "'>" + value + "</option>";
            });
            html += "</select>";
            return html;
        case "picklist":

            var html = "<select style='width:300px;' class='select2 condition_value select' " + (fieldTypeName == "multipicklist"?"multiple='multiple'":"") + " name='" + fieldName + "' id='" + fieldId + "'>";

            jQuery.each(wsModuleFieldOptions[selField].type.picklistValues, function(index, value) {
                console.log(index, value);
                html += "<option " + (currentValue.indexOf(index) != -1 ? "selected='selected'" : "") + " value='" + index + "'>" + value + "</option>";
            });

            html += "</select>";

            return html;
        break;

        case "multipicklist":

            var html = "<select style='width:300px;' class='select2 condition_value select' " + (fieldTypeName == "multipicklist"?"multiple='multiple'":"") + " name='" + fieldName + "[]' id='" + fieldId + "'>";

            jQuery.each(wsModuleFieldOptions[selField].type.picklistValues, function(index, value) {
                console.log(index, value);
                html += "<option " + (currentValue.indexOf(index) != -1 ? "selected='selected'" : "") + " value='" + index + "'>" + value + "</option>";
            });

            html += "</select>";

            return html;
        break;
        case "boolean":
            var html = '<input name="' + fieldName + '" value="1" id="' + fieldId + '" type="checkbox" ' + (currentValue == "1"?"checked='checked'":"") + '>';
            return html;
            break;
        case "reference":
            var referTo = wsModuleFieldOptions[selField].type.refersTo[0];

            if(referTo == "Currency" && typeof availCurrency !== "undefined") {
                var html = "<select style='width:300px;' class='select2 condition_value select'  name='" + fieldName + "' id='" + fieldId + "'>";

                for(var a = 0;a < availCurrency.length; a++) {
                    html += "<option " + (currentValue ==  availCurrency[a].curid ? "selected='selected'" : "") + " value='" + availCurrency[a].curid + "'>" + availCurrency[a].currencylabel + "</option>";
                }

                html += "</select>";

                return html;
            }
            optionTemplates["refFields"] = true;
            var html = createTemplateTextfield(fieldName, fieldId, currentValue, optionTemplates);
            return html;
            break;
        case "date":
        case "datetime":
            return createTemplateTextfield(fieldName, fieldId, currentValue, {module: WfSetterFromModule, refFields: WfSetterOptions["refFields"]});
            //return createTemplateDatefield(fieldName, fieldId, currentValue);
            //break;
        case "text":
            var html = createTemplateTextarea(fieldName, fieldId, currentValue, {height:'80px',module: WfSetterFromModule, refFields: WfSetterOptions["refFields"]});

            return html;

            break;
        default:
            return createTemplateTextfield(fieldName, fieldId, currentValue, {module: WfSetterFromModule, refFields: WfSetterOptions["refFields"]});
        break;
    }

}

function addRow(num, field) {
    if(typeof field == 'undefined') {
        field = false;
    }
    var blockId = false;
    var sequenceSort = false;

    if(field !== false && typeof wsModuleFieldOptions[field] != 'undefined') {
        blockId = wsModuleFieldOptions[field].blockId;
        sequenceSort = wsModuleFieldOptions[field].sequence;
    }

    if(num === undefined) {
        countValues = Number(countValues) +1;
    } else {
        countValues = Number(num);
    }

    var HTML = jQuery("#settings_base").html();
    HTML = HTML.replace(/##SETID##/g, countValues);
    HTML = jQuery(HTML);

    HTML.find(":disabled").removeAttr("disabled");

    if(blockId !== false) {
        jQuery("#block_" + blockId).append(HTML);
        jQuery("#block_" + blockId).show();
    } else {
        jQuery("#setter_container").append(HTML);
    }

    if(sequenceSort !== false) {
        jQuery('#setterRow_' + countValues).data('sort', sequenceSort);
    }

    jQuery("#setter_" + countValues + "_field").css({
        width: '300px'
    });
    jQuery("#setter_" + countValues + "_mode").css({
        width: '130px'
    });

    jQuery("#setter_" + countValues + "_field").bind("change", onChangeField);

    jQuery("#setter_" + countValues + "_mode").bind("change", onChangeField);

    jQuery("#setter_" + countValues + "_field").select2();
    jQuery("#setter_" + countValues + "_mode").select2({disable_search_threshold: 3});

    jQuery("select#setter_" + countValues + "_value").select2();

    jQuery("#setter_" + countValues + "_field_chosen, #setter_" + countValues + "_mode_chosen").css({
        marginRight: '5px'
    });

    InitAutocompleteText();
}

jQuery(function() {
    loadModuleFields(WfSetterToModule);
});
