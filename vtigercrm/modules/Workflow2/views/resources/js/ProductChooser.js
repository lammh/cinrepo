var productCounter = 1;

function productChanged(fieldid, productid, productCounter) {
    if(productid == "individual") {
        jQuery("." + fieldid + "_taxes").show();
        var ele = jQuery("#" + fieldid)[0];

        var html = createTemplateTextfield(ele.name.replace("productid", "productid_individual"), ele.id.replace("productid", "productid_individual"), "", {style:'width:500px;'});

        jQuery(".select2-container.productSelect").remove();

        jQuery(ele).replaceWith(html);
    } else {

       loadProductInfo(productid, productCounter);
       /*
        jQuery("." + fieldid + "_taxes").hide();
        jQuery("." + fieldid + "_taxes :checkbox").attr('checked', false);
        jQuery("." + fieldid + "_taxes input[type=text]").attr("disabled", "disabled");
        // jQuery("." + fieldid + "_taxes")


        jQuery.each(taxlist[productid], function(index, value) {
            jQuery("." + fieldid + "_taxes#" + fieldid + "_tax" + value.taxid).show();
            jQuery("." + fieldid + "_taxes#" + fieldid + "_tax" + value.taxid + " :checkbox").attr('checked', true);
            jQuery("." + fieldid + "_taxes#" + fieldid + "_tax" + value.taxid + " input[type=text]").removeAttr("disabled");
        });


        jQuery("#product_" + productCounter + "_description").val(productList[productid]["description"]);
        jQuery("#product_" + productCounter + "_unitprice").val(productList[productid]["unit_price"]);*/
    }
}

function loadProductInfo(product_id, productCounter) {
    if(typeof productCache[product_id] != 'undefined') {
        setProductInfo(productCache[product_id], productCounter);
    } else {
        jQuery.post('index.php', {module:'Workflow2', 'action':'GetProductData', 'product_id':product_id}, function(response) {
            productCache[product_id] = response;
            setProductInfo(productCache[product_id], productCounter);
        }, 'json');
    }
}
function setProductInfo(productData, productCounter) {
    var fieldid = 'product_' + productCounter + '_productid';
    jQuery("." + fieldid + "_taxes").hide();
    jQuery("." + fieldid + "_taxes :checkbox").attr('checked', false);
    jQuery("." + fieldid + "_taxes input[type=text]").attr("disabled", "disabled");

    jQuery.each(productData.tax, function(index, value) {
        jQuery("." + fieldid + "_taxes#" + fieldid + "_" + index).show();
        jQuery("." + fieldid + "_taxes#" + fieldid + "_" + index + " :checkbox").attr('checked', true);
        jQuery("." + fieldid + "_taxes#" + fieldid + "_" + index + " input[type=text]").val(parseFloat(value.percentage)).removeAttr("disabled");
    });

    jQuery("#product_" + productCounter + "_comment").val(productData['data']["description"]);
    jQuery("#product_" + productCounter + "_unitprice").val(productData['data']["unit_price"]);
}

function getProductSelect(fieldName, fieldId, product, productCounter) {
    if(typeof product.productid_individual != "undefined" && product.productid_individual.length > 0) {
        var html = createTemplateTextfield(fieldName.replace("productid", "productid_individual"), fieldId.replace("productid", "productid_individual"), product.productid_individual, {style:'width:500px;'});

        return html;
    } else {
        if(product === false) {
            selected = -1;
        } else {
            selected = product.productid;
        }

        var html = "<input type='hidden' class='productSelect span8' value='" + (selected!=-1?selected:'') + "' onchange='productChanged(\"" + fieldId + "\", this.value, " + productCounter + ");' style='' name='" + fieldName + "' id='" + fieldId + "'>";
/*
        var html = "<select class='chzn-select span8' onchange='productChanged(\"" + fieldId + "\", this.value, " + productCounter + ");' style='' name='" + fieldName + "' id='" + fieldId + "'>";
            html += '<option value="">' + MOD.LBL_CHOOSE + '</option>';
        jQuery.each(productList, function(index, value) {
            html += '<option value="' + value.productid + '" ' + (selected !== undefined && selected == value.productid?"selected='selected'":"") + '>' + value.productname + '</option>';
        });
        html += '<option value="individual">+++ ' + MOD.LBL_SELECT_INPUT_INDIVIDUAL_VALUE + '</option>';

        html += "</select>";*/
        return html;
    }
}
function removeProduct(productCounter) {
    jQuery("#productChooser_" + productCounter).remove();
}
function addProduct(product) {
    html = "<div class='productChooserContainer' id='productChooser_" + productCounter + "'>";
        html += "<div style='float:right;width:230px;'>";
            html += "<div class='buttonbar' style='text-align:right;margin:0 0 10px 0;'><input type='button' class='btn btn-danger' onclick='removeProduct(" + productCounter + ");' value='"+MOD.LBL_REMOVE_RECORD+"' /></div>";

            html += "<span>Discount:</span><select onchange='jQuery(\"#discount_value_" + productCounter + "_container\").css(\"display\",this.value==\"\"?\"none\":\"block\")' name='task[product][" + productCounter + "][discount_mode]'><option value=''>-</option><option value='amount' " + (typeof product != "undefined" && product.discount_mode=='amount'?'selected="selected"':'') + ">Amount</option><option value='percentage' " + (typeof product != "undefined" && product.discount_mode=='percentage'?'selected="selected"':'') + ">Percentage</option></select>";
            html += "<div id='discount_value_" + productCounter + "_container' style='display:" + (typeof product != "undefined" && product.discount_mode==""?"none":"") + ";'>Discount Value:<br>";
            html += createTemplateTextfield("task[product][" + productCounter + "][discount_value]", "product_" + productCounter + "_discount_value",(product !== undefined?product.discount_value:""), {style:'width:80px;'})
            html += "</div>";
            jQuery.each(availTaxes, function(index, value) {
/*                showTax = false;
                if(typeof product != "undefined" && product.productid != "") {
                    jQuery.each(taxlist[product.productid], function(taxIndex, taxValue) {
                        if(taxValue.taxid == value.taxid) {
                            showTax = true;
                            return false;
                        }
                    });
                } else {
                    showTax = false;
                } */

                html += "<div style='overflow:hidden;' class='product_" + productCounter + "_productid_taxes' id='product_" + productCounter + "_productid_tax" + value.taxid + "'>";
                html += "<span><span style='display:block;float:left;width:80px;'><input type='checkbox' id='' " + (typeof product !== "undefined" && product["tax" + value.taxid + "_enable"]=="1"?"checked='checked'":"") + " name='task[product][" + productCounter + "][tax" + value.taxid + "_enable]' value='1' onclick='if(!jQuery(this).prop(\"checked\")) { jQuery(\"#product_" + productCounter + "_tax" + value.taxid + "\").attr(\"disabled\",\"disabled\");} else { jQuery(\"#product_" + productCounter + "_tax" + value.taxid + "\").removeAttr(\"disabled\"); } '>"+ value.taxlabel + ":</span></span>";
                html += createTemplateTextfield("task[product][" + productCounter + "][tax" + value.taxid + "]", "product_" + productCounter + "_tax" + value.taxid + "",(product !== undefined && typeof product["tax" + value.taxid] !== "undefined"?product["tax" + value.taxid]:value.percentage), {style:'width:50px;', disabled:typeof product === "undefined" || product["tax" + value.taxid + "_enable"] != "1"});
                html += "</div>";
            });

        html += "</div>";

        html += "<div style='width:760px;'>";
//        html += "<div><label style='width: 60px;display: inline-block;vertical-align: middle;'>Product:</label><span class='productChooserContainer_productid' id='productID_display_" + productCounter + "'>Choose Product</span></div><br>";
        html += "<div style='margin-bottom:5px;overflow: hidden;'><label style='width: 80px;display: inline-block;vertical-align: middle;float:left;'>Product:</label>" + getProductSelect("task[product][" + productCounter + "][productid]","product_" + productCounter + "_productid",(product !== undefined?product:false), productCounter) + "</div>";
        html += "<div style='overflow:hidden;margin-bottom:5px;'>";
        html += "<div style='width:300px;float:left;line-height:28px;'><label style='width: 80px;margin:0;display: inline-block;vertical-align: middle;'>Quantity:</label>" + createTemplateTextfield("task[product][" + productCounter + "][quantity]", "product_" + productCounter + "_quantity",(product !== undefined?product.quantity:""), {style:'width:80px;',title:MOD.LBL_DOUBLE_CLICK_TO_INCREASE_SIZE}) + "</div>";
        html += "<div style='width:250px;float:left;line-height:28px;'><label style='width: 70px;margin:0;display: inline-block;vertical-align: middle;'>Unit Price:</label>" + createTemplateTextfield("task[product][" + productCounter + "][unitprice]", "product_" + productCounter + "_unitprice",(product !== undefined?product.unitprice:""), {style:'width:80px;',title:MOD.LBL_DOUBLE_CLICK_TO_INCREASE_SIZE}) + "</div>";
        html += "</div>";

        html += "<label style='width: 80px;display: inline-block;vertical-align: middle;'>Description:</label><textarea rows='5' id='product_" + productCounter + "_comment' class='span8' style='' name='task[product][" + productCounter + "][comment]'>" + (product !== undefined?product.comment:"") + "</textarea>";
        html += "<img src='modules/Workflow2/icons/templatefield.png' style='margin-bottom:-8px;cursor:pointer;' onclick=\"insertTemplateField('product_" + productCounter + "_comment','([source]: ([module]) [destination])', true)\">";
        jQuery.each(additionalProductFields, function(fieldName, fieldData) {
            html += "<div style='line-height:28px;'><label style='width: 80px;margin:0;display: inline-block;vertical-align: middle;'>" + app.vtranslate(fieldData.label) + ":</label>" + createTemplateTextfield("task[product][" + productCounter + "][" + fieldName + "]", "product_" + productCounter + "_" + fieldName + "",(product !== undefined?product[fieldName]:"")) + "</div>";
        });
        html += "</div>";

    html += "</div>";
    html += '<script type="text/javascript">initProductChooser(' + productCounter + ');</script>';

    jQuery("#product_chooser").append(html);

    if(typeof product.productid_individual  == 'undefined') {
        jQuery(".product_" + productCounter + "_productid_taxes").hide();
    }

    if(typeof product !== "undefined" && typeof product.productid !== "undefined" && product.productid != "" && product.productid != -1) {
        // jQuery("." + fieldid + "_taxes")

        jQuery.each(productCache[product.productid].tax, function(index, value) {
            jQuery(".product_" + productCounter + "_productid_taxes#product_" + productCounter + "_productid_" + index).show();
        });

    }

    productCounter++;
}
function initProductChooser(productCounter) {
    jQuery("#productChooser_" + productCounter + " .productSelect").select2({
        placeholder: "search for a Product/Service",
        minimumInputLength: 1,
        initSelection: function (element, callback) {
            callback({
                id: jQuery(element).val(),
                text: productCache[jQuery(element).val()]['label']
            });
        },
        query: function (query) {

            var data = {
                query: query.term,
                page: query.page,
                pageLimit: 25
            };

            jQuery.post("index.php?module=Workflow2&action=ProductChooser", data, function (results) {
                results.results.push({id:'individual',text:'+++ ' + MOD.LBL_SELECT_INPUT_INDIVIDUAL_VALUE});
                query.callback(results);
            }, 'json');

        }
    });
}

jQuery(function() {
    var globalValuesEl = jQuery("#InventoryGlobalValues");
    if(globalValuesEl.length > 0) {
        var html = "";
        html += "<fieldset style='float:left;width:40%;'><legend style='font-size:12px;margin-bottom:0;'>" + MOD["LBL_GROUP_TAX_IF_ENABLED"] + "</legend>";
        jQuery.each(availTaxes, function(index, value) {
            html += "<div style='overflow:hidden' class='global_taxes' id='global_tax" + value.taxid + "_container'>";
            html += "<span><span style='display:block;float:left;width:80px;'>"+ value.taxlabel + ":</span></span>";
            html += createTemplateTextfield("task[global][tax" + value.taxid + "_group_percentage]", "global_tax" + value.taxid + "",(global_values !== null && typeof global_values["tax" + value.taxid + "_group_percentage"] !== "undefined"?global_values["tax" + value.taxid + "_group_percentage"]:value.percentage), {style:'width:50px;'});
            html += "</div>";
        });
        html += "</fieldset>";

        html += "<fieldset style='float:left;width:40%;'><legend style='font-size:12px;margin-bottom:0;'>" + MOD["LBL_SHIPPING_TAX"] + "</legend>";
        jQuery.each(availTaxes, function(index, value) {
            html += "<div style='overflow:hidden' class='global_taxes' id='global_sh_tax" + value.taxid + "_container'>";
            html += "<span><span style='display:block;float:left;width:80px;'>"+ value.taxlabel + ":</span></span>";
            html += createTemplateTextfield("task[global][tax" + value.taxid + "_sh_percent]", "global_sh_tax" + value.taxid + "",(global_values !== null && typeof global_values["tax" + value.taxid + "_sh_percent"] !== "undefined"?global_values["tax" + value.taxid + "_sh_percent"]:value.percentage), {style:'width:50px;'});
            html += "</div>";
        });
        html += "</fieldset>";

        globalValuesEl.html(html);
    }

    if(oldTask != null && selectedProducts !== undefined && selectedProducts !== null) {
        jQuery.each(selectedProducts, function(index, value) {
            addProduct(value);
        });
    }
});