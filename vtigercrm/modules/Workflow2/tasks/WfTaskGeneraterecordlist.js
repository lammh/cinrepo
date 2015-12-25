var currentCol = 0;
function addField(field, label, width) {
    var newColNumber = currentCol + 1;
    if(typeof field == "undefined") {
        field = '';
    }
    if(typeof label == "undefined") {
        label = "";
    }
    if(typeof width == "undefined") {
        width = "150px";
    }

    var HTML = jQuery('#staticFieldsContainer').html();
    HTML = HTML.replace(/##SETID##/g, currentCol);
    HTML = jQuery(HTML);

    HTML.find(":disabled").removeAttr("disabled");

    jQuery("#fieldlist").append(HTML);

    jQuery("#staticfields_" + currentCol + "_field").val(field);
    jQuery("#staticfields_" + currentCol + "_label").val(label);
    jQuery("#staticfields_" + currentCol + "_width").val(width);

    currentCol++;

    return newColNumber;
}

function initRecordListFields(fields) {

    jQuery.each(fields, function(index, value) {
        addField(value.field, value.label, value.width);
    });
}