var FGDAT = {};

function initFormGenerator(id, name, fields) {
    var formEle = jQuery("#" + id);
    FGDAT[id] = {};
    FGDAT[id]["ele"] = formEle;
    FGDAT[id]["name"] = name;

    if(formEle.length == 0) {
        console.log("formgenerator useless integration. '#" + id + "' not found!");
    }

    FGDAT[id]["fieldCounter"] = 0;
    var newButton = '<button onclick="addInputField(\'' + id + '\', false);return false;" class="newFieldButton btn addButton"><i class="icon-plus icon-white"></i> <strong>New Value</strong></button>';
    FGDAT[id]["ele"].append(newButton);

    var headHtml = "<thead><tr>";
        headHtml += "<th style='width:25px;'></th>";
        headHtml += "<th style='width:150px;'>Name of $env Variable</th>";
        headHtml += "<th style='width:150px;'>Label</th>";
        headHtml += "<th style='width:100px;'>Inputtype</th>";
        headHtml += "<th style='width:300px;'>Configuration</th>";
    headHtml += "</tr></thead>";
    FGDAT[id]["ele"].append("<br/><br/><table class='table table-condensed formContainer'>" + headHtml + "</table>");

    if(typeof fields != "undefined" && fields !== null) {
        jQuery.each(fields, function(index, value) {
            addInputField(id, value, true);
        });
    }

    createTemplateFields('body');
}

function getDefaultInput(id, currentFieldNumber, newType, value) {
    var configInput = jQuery('#fieldtemplate_' + newType.toLowerCase()).html();

    configInput = configInput.replace(/##FIELDNAME##/g, 'field_' + currentFieldNumber);
    return configInput;
}

function changeType(id, currentFieldNumber, newType) {
    var defaultEle = jQuery('#task_' + FGDAT[id]["name"] + '_field_' + currentFieldNumber + '_default_container');
    var defaultValue = jQuery('#task_' + FGDAT[id]["name"] + '_field_' + currentFieldNumber + '_default').val();

    var newInput = '<span id="task_' + FGDAT[id]["name"] + '_field_' + currentFieldNumber + '_default_container">' + getDefaultInput(id, currentFieldNumber, newType, defaultValue) + "</span>";

    defaultEle.replaceWith(newInput);
    createTemplateFields('#task_' + FGDAT[id]["name"] + '_field_' + currentFieldNumber + '_default_container');
}

function addInputField(id, fieldData, initialize) {
    var currentFieldNumber = FGDAT[id]["fieldCounter"];

    if(typeof fieldData == "undefined" || fieldData == false) {
        fieldData = {
            "name" : "field_" + (Number(currentFieldNumber) + 1),
            "type" : "text",
            'config': {},
            "label" : "Value " + (Number(currentFieldNumber) + 1)
        }
    }
    if(typeof fieldData.config == 'undefined') {
        fieldData.config = {};
    }

    var html = '';
    html += '<tr>';
    html += "<td><img src='modules/Workflow2/icons/cross-button.png' style='cursor:pointer;margin-top:5px;' onclick='jQuery(this).parent().parent().remove();'></td>";
    html += '<td><input style="vertical-align:top;width:90%;" type="text" name="task[' + FGDAT[id]["name"] + '][field_' + currentFieldNumber + '][name]" value="' + fieldData["name"] + '"></td>';
    html += '<td><input style="vertical-align:top;width:90%;" type="text" name="task[' + FGDAT[id]["name"] + '][field_' + currentFieldNumber + '][label]" value="' + htmlEntities(fieldData["label"]) + '"></td>';
    html += '<td><select style="vertical-align:top;width:90%;" onchange="changeType(\'' + id + '\', ' + currentFieldNumber + ', this.value)" name="task[' + FGDAT[id]["name"] + '][field_' + currentFieldNumber + '][type]">';

    jQuery.each(fieldTypes, function(index, value) {
        html += '<option value="' + index + '" ' + (typeof fieldData != 'undefined' && fieldData["type"].toLowerCase() == index.toLowerCase()?"selected='selected'":"") + '>' + value + '</option>';
    });

    html += '</select></td>';
    html += '<td><span id="task_' + FGDAT[id]["name"] + '_field_' + currentFieldNumber + '_default_container">' + getDefaultInput(id, currentFieldNumber, fieldData["type"], fieldData["default"]) + '</span></td>';
    html += '</tr>';

    jQuery('.formContainer', FGDAT[id]["ele"]).append(html);
    FGDAT[id]["fieldCounter"] = Number(FGDAT[id]["fieldCounter"]) + 1;

    jQuery.each(fieldData.config, function(index, value) {

        var configField = jQuery('.configField[data-id="task_' + FGDAT[id]["name"] + '_field_' + currentFieldNumber + '_config_' + index + '"]');
//        console.log(configField, '.configField[data-id="task_' + fieldData.name + '_config_' + index + '"');

        //console.log(index, configField);

        switch(configField.data('type')) {
            case 'checkbox':
                if(value == configField.val()) {
                    configField.prop('checked', true);
                }
                break;
            case 'templatefield':
                configField.html(value);
                break;
            case 'hidden':
            case 'picklist':
            case 'templatearea':
                configField.val(value);
                break;
        }
        if(value != '' && configField.data('nomodify') == '1') {
            configField.attr('readonly', 'readonly');
        }
    });

    if(typeof initialize === 'undefined' || initialize != true) {
        createTemplateFields('#task_' + FGDAT[id]["name"] + '_field_' + currentFieldNumber + '_default_container');
    }
}