jQuery.loadScript = function (url, arg1, arg2) {
  var cache = false, callback = null;
  //arg1 and arg2 can be interchangable
  if ($.isFunction(arg1)){
    callback = arg1;
    cache = arg2 || cache;
  } else {
    cache = arg1 || cache;
    callback = arg2 || callback;
  }

  var load = true;
  //check all existing script tags in the page for the url
  jQuery('script[type="text/javascript"]')
    .each(function () {
      return load = (url != $(this).attr('src'));
    });
  if (load){
    //didn't find it in the page, so load it
    jQuery.ajax({
      type: 'GET',
      url: url,
      success: callback,
      dataType: 'script',
      cache: cache
    });
  } else {
    //already loaded so just call the callback
    if (jQuery.isFunction(callback)) {
      callback.call(this);
    };
  };
};

jQuery.loadScript("modules/Workflow2/views/resources/js/jquery.form.min.js");

var RequestValuesForm = function () {
    "use strict";

    this.callback           = null;
    this.fieldsKey          = null;

    this.getKey = function() {
        return this.fieldsKey;
    }

    this.show = function (windowContent, fieldsKey, message, callback, stoppable, pausable) {
        if(typeof pausable == 'undefined') {
            pausable = true;
        }

        this.callback = callback;
        this.fieldsKey = fieldsKey;
        this.windowContent = windowContent;

        var html = "<div style='width:550px;'>";
        html += '<link rel="stylesheet" href="modules/Workflow2/views/resources/Modal.css?' + new Date().getTime() + '" type="text/css" media="screen" />';
        html += "<form method='POST' onsubmit='return false;' id='wf_startfields' enctype='multipart/form-data'>";
        html += "<div id='workflow_startfield_executer' style='display:none;width:100%;height:100%;top:0px;left:0px;background-image:url(modules/Workflow2/icons/modal_white.png);border:1px solid #777777;  position:absolute;text-align:center;'><img src='modules/Workflow2/icons/sending.gif'><br><br><strong>Executing Workflow ...</strong></div>";

        html += '<div class="modal-header contentsBackground">';
        html += '	<button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="Close">x</button>';
        html += '   <h3>ワークフローの実行</h3>';
        html += '</div>';

        html += "<div class='wfLimitHeightContainer requestValueForm'>";
        html += "<strong>" + message + "</strong><br/>";

        html += windowContent['html'];

        html += "</div>"; // .wfLimitHeightContainer

        html += "<div class='clearfix'></div>";
        html += '<div class="modal-footer quickCreateActions">';

        if(typeof stoppable != 'undefined' && stoppable == true) {
            var execId = response.execId.split('##');execId = execId[0];
            //html += "<button type='button' name='submitStartField' class='pull-left btn btn-danger' style='float:left;' onclick='stopWorkflow(\"" + execId + "\",\"" + response.crmId + "\",\"" + response.blockId + "\", true);app.hideModalWindow();'><i class='icon-remove'></i> stop Workflow</button>";
  			html += "<button type='button' name='submitStartField' class='pull-left btn btn-danger' style='float:left;' onclick='stopWorkflow(\"" + execId + "\",\"" + response.crmId + "\",\"" + response.blockId + "\", true);app.hideModalWindow();'><i class='icon-remove'></i> 処理中止</button>";
  
        }

        //html += "<button type='submit' name='submitStartField' class='btn btn-success pull-right'><i class='icon-ok' style='color: #ffffff;'></i> Execute Workflow</button>";
		html += "<button type='submit' name='submitStartField' class='btn btn-success pull-right'><i class='icon-ok' style='color: #ffffff;'></i> 実行</button>";



        if(pausable == true) {
            //html += "<button name='submitStartField' type='button' class='btn pull-right' onclick='app.hideModalWindow(function() { reloadWFDWidget(); });'><i class='icon-remove'></i> enter values later</button>";
        	html += "<button name='submitStartField' type='button' class='btn pull-right' onclick='app.hideModalWindow(function() { reloadWFDWidget(); });'><i class='icon-remove'></i> 処理中断</button>";
        
        }

        html += '</div>'
        html += "</form></div>";

        app.showModalWindow(html, jQuery.proxy(function(data) {
            createReferenceFields('.requestValueForm');
            if(pausable == false) {
                jQuery(".blockUI").off("click");
            }

            if(this.windowContent['script'] != '') {
                jQuery.globalEval( this.windowContent['script'] )
            }

            var quickCreateForm = jQuery('form#wf_startfields');
            jQuery('.wfLimitHeightContainer', quickCreateForm).css('maxHeight', (jQuery( window ).height() - 250) + 'px');

            jQuery('#wf_startfields').on('submit', jQuery.proxy(function() {
                if(typeof this.callback == 'function') {
                    app.hideModalWindow();
                    this.callback.call(this, this.getKey(), jQuery("#wf_startfields").serializeArray(), jQuery("#wf_startfields").serialize(), jQuery("#wf_startfields"));
                }
                return false;
            }, this));

        }, this));

    };
};

function createReferenceFields(parentEle) {
    jQuery('.insertReferencefield', parentEle).each(function(index, value) {
        var ele = jQuery(value);
        if(ele.data('parsed') == '1') {
            return;
        }

        ele.data('parsed', '1');

        var fieldName = ele.data('name');

        if(typeof fieldId == 'undefined') {
            fieldId = fieldName.replace(/[^a-zA-Z0-9]/g, '');
        }

        var valueID = ele.data('crmid');
        var valueLabel = ele.data('label');
        if(typeof valueID == 'undefined') {
            valueID = '';
            valueLabel = '';
        }

        //value = value.replace(/\<\!--\?/g, '<?').replace(/\?--\>/g, '?>');

        var html = '';

        html += '<input name="' + fieldName + '" type="hidden" value="' + valueID + '" class="sourceField" data-displayvalue="' + valueLabel + '" />';
        html += '<div class="row-fluid input-prepend input-append"><span class="add-on clearReferenceSelection cursorPointer"><i class="icon-remove-sign SWremoveReferenc" title="Clear"></i></span>';
        html += '<input id="' + fieldName + '_display" name="' + fieldName + '_display" type="text" class="marginLeftZero autoComplete" ' + (valueID != ''?'readonly="true"':'') + ' value="' + valueLabel + '" placeholder="Search" />';
        html += '<span class="add-on relatedPopup cursorPointer"><i class="icon-search relatedPopup" title="Select" ></i></span>';

        ele.html(html);

        jQuery('.relatedPopup', ele).on('click', function(e){
            var editViewObj = new Vtiger_Edit_Js();
            editViewObj.openPopUp(e);
            return false;
        });

    });
}
