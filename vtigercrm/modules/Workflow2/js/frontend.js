/** DetailView **/
function startWorkflowById(workflow, crmid, async) {
    if(typeof async == "undefined") {
        async = true;
    }
    if(async != true) {
        async = false;
    }

    if(typeof crmid == "undefined") {
        crmid = "0";
    }
    if(typeof workflow == "undefined") {
        return false;
    }

    var html = "<div id='workflow_executer' style='width:150px;height:150px;background-image:url(modules/Workflow2/icons/modal_white.png);border:1px solid #777777; box-shadow:0 0 2px #ccc; position:absolute;top:100px;right:300px;text-align:center;'><br><br><img src='modules/Workflow2/icons/sending.gif'><br><br><strong>Executing Workflow ...</strong></div>";
    jQuery("body").append(html);
    jQuery.ajax("index.php", {
        async: async,
        cache: false,
        data:{
            "module" : "Workflow2",
            "action" : "Workflow2Ajax",
            "file"   : "ajaxExecuteWorkflow",

            "crmid" : crmid,
            "workflow" : workflow
        },
        type: 'POST',
        dataType: 'json'
    }).always(function( response ) {
            jQuery("#workflow_executer").remove();

            if(response["result"] == "startfields") {
                var html = "<div style='position:absolute;background-color:#fff;border:3px solid #5890c9;box-shadow:0px 0px 5px #777;border-radius:3px;top:-100px;left:0px;width:200px;padding:5px;'><form method='POST' onsubmit='submitStartfields(" + '"' + response["workflow"] + '","' + crmid + '","' + module + '"' + ");return false;' id='wf_startfields'>";
                html += "<img src='modules/Workflow2/icons/cross-button.png' style='position:absolute;right:-6px;top:-6px;cursor:pointer;' onclick='jQuery(\"#startfieldsContainer\").fadeOut(\"fast\");'>";
                html += "<div class='small'>These Workflow requests some values.</div>";

                jQuery.each(response["fields"], function(index, value) {
                    var inputField = "";
                    var fieldName = '' + value.name + '';

                    switch(value["type"]) {
                        case "TEXT":

                            inputField = '<input type="text" style="width:180px;" name="' + fieldName + '" value="' + value["default"] + '">';
                            break;
                        case "CHECKBOX":
                                if(value["default"] === null) {
                                    value["default"] = "off";
                                }
                                inputField = '<input type="checkbox" name="' + fieldName + '" ' + (value["default"].toLowerCase()=="on"?"checked='checked'":"") + ' value="on">';
                            break;
                        case "SELECT":
                            var splitValues = value["default"].split("\n");

                            inputField = '<select style="width:183px;" name="' + fieldName + '">';
                            jQuery.each(splitValues, function(index, value) {
                                var fieldValue = value;
                                var fieldKey = value;

                                if(value.indexOf("#~#") != -1) {
                                    var parts = value.split("#~#");
                                    fieldValue = parts[0];
                                    fieldKey = parts[1];
                                }

                                inputField += "<option value='" + fieldKey + "'>" + fieldValue + "</option>";
                            });
                            inputField += '</select>';

                            break;
                        case "DATE":
                            inputField = '<input style="width:130px;" type="text" name="' + fieldName + '" id="'+fieldName+'" value="' + value["default"] + '">';
                            inputField += '<img src="modules/Workflow2/icons/calenderButton.png" style="margin-bottom:-8px;cursor:pointer;" id="jscal_trigger_' + fieldName + '">';
                            inputField += '<script type="text/javascript">Calendar.setup ({inputField : "' + fieldName + '", ifFormat : "%Y-%m-%d", button:"jscal_trigger_' + fieldName + '",showsTime : false, singleClick : true, step : 1});</script>';

                            break;
                    }

                    html += "<label><div style='overflow:hidden;min-height:26px;padding:2px 0;'><div style='" + (value["type"]=="CHECKBOX"?"float:left;":"") + "'><strong>"+ value.label + "</strong></div><div style='text-align:right;'>" + inputField + "</div></div></label>";
                });
                html += "<input type='submit' name='submitStartField' value='Execute Workflow' class='button small edit'>";
                html += "</form></div>";

                jQuery("#startfieldsContainer").hide();
                jQuery("#startfieldsContainer").html(html);
                jQuery("#startfieldsContainer").fadeIn("fast");
            }
    });

}

function reloadWFDWidget() {
    var widgetContainer = jQuery('div.widgetContainer#' + jQuery("#module").val() + '_sideBar_Workflows');
    var key = widgetContainer.attr('id');
    app.cacheSet(key, 0);
    widgetContainer.html('');

    Vtiger_Index_Js.loadWidgets(widgetContainer);
}

function continueWorkflow(execid, crmid, block_id) {
    var Execution = new WorkflowExecution();
    Execution.setContinue(execid, block_id);
    Execution.execute();

}

function stopWorkflow(execid, crmid, taskid, direct) {
    if(typeof direct == 'undefined' || direct != true) {
        if(!confirm("stop Workflow?"))
            return;
    }

    jQuery.post("index.php?module=Workflow2&action=QueueStop", {
            "crmid" : crmid,
            "execID" : execid,
            "taskID" : taskid
        },
        function(response) {
            reloadWFDWidget();
        }
    );

    return false;
}

/** ListView **/
function executeWorkflow(button, module, selection) {
    var selectedIDs = "";

    if(typeof selection == "undefined") {
        selectedIDs = jQuery('#allselectedboxes').val().split(";");
        selectedIDs = selectedIDs.join(";");
    } else {
        selectedIDs = selection.join(";");
    }

    if (jQuery("#Wf2ListViewPOPUP").length == 0)
    {
        var div = document.createElement('div');
        div.setAttribute('id','Wf2ListViewPOPUP');
        div.setAttribute('style','display:none;width:350px; position:absolute;');
        div.innerHTML = 'Loading';
        document.body.appendChild(div);

        //      for IE7 compatiblity we can not use setAttribute('style', <val>) as well as setAttribute('class', <val>)
        newdiv = document.getElementById('Wf2ListViewPOPUP');
        newdiv.style.display = 'none';
        newdiv.style.width = '400px';
        newdiv.style.position = 'absolute';
    }

    jQuery('#status').show();

    currentListViewPopUpContent = "#wf2popup_wf_execute";

    jQuery.post("index.php", {
        "module" : "Workflow2",
        "action" : "Workflow2Ajax",
        "file"   : "ListViewPopup",

        "return_module" : module,
        "record_ids"    : selectedIDs
    }, function(response) {
        jQuery("#Wf2ListViewPOPUP").html(response);

        fnvshobj(button,'Wf2ListViewPOPUP');

        var EMAILListview = document.getElementById('Wf2ListViewPOPUP');
        var EMAILListviewHandle = document.getElementById('Workflow2ViewDivHandle');
        Drag.init(EMAILListviewHandle,EMAILListview);

        jQuery('#status').hide();

    });

}

var currentListViewPopUpContent = "#wf2popup_wf_execute";
function showWf2PopupContent(id) {
    jQuery(currentListViewPopUpContent + "_TAB").addClass("deactiveWf2Tab");
    jQuery(id + "_TAB").removeClass("deactiveWf2Tab");
    jQuery(currentListViewPopUpContent).hide();
    jQuery(id).show();
    currentListViewPopUpContent= id;

    if(id == "wf2popup_wf_importer") {
        jQuery("#execute_mode").val("execute");
    } else {
        jQuery("#execute_mode").val("import");
    }
}

function executeLVWorkflow() {
    if(jQuery("#execute_mode").val() == "import") {
        return true;
    }

    var record_ids = jQuery("#WFLV_record_ids").val();
    var return_module = jQuery("#WFLV_return_module").val();
    var workflow = jQuery("#exec_this_workflow").val();
    var parallel = jQuery("#exec_workflow_parallel").attr("checked")=="checked"?1:0;

    var ids = record_ids.split("#~#");

    jQuery("#executionProgress_Value").html("0 / " + ids.length);
    jQuery("#executionProgress").show();

    jQuery.ajaxSetup({async:false});
    var counter = 0;

    jQuery.each(ids, function(index, value) {
        jQuery.post("index.php?module=Workflow2&action=Workflow2Ajax&file=ajaxExecuteWorkflow", {
                "crmid" : value,
                "return_module" : return_module,
                "workflow" : workflow,
                "allow_parallel" : parallel
           }
        );
        counter = counter + 1;
        jQuery("#executionProgress_Value").html(counter + " / " + ids.length);
    });
    jQuery.ajaxSetup({async:true});

    jQuery("#executionProgress_Value").html("100%");

    if(currentListViewPopUpContent == "#wf2popup_wf_execute") {
        return false;
    }
}
var ENABLEredirectionOrReloadAfterFinish = true;

function runListViewWorkflow(workflowId, couldStartWithoutRecord) {
    console.log(workflowId, couldStartWithoutRecord);
    if(typeof couldStartWithoutRecord == 'undefined') {
        couldStartWithoutRecord = false;
    }

    var listInstance = Vtiger_List_Js.getInstance();
    var selectedIds = listInstance.readSelectedIds(false).slice();
    var excludedIds = listInstance.readExcludedIds(false).slice();
    var cvId = listInstance.getCurrentCvId();

    jQuery.blockUI({
        title: 'Executing ... ',
        message: '<span id="workflowDesignerDone">0</span> of <span id="workflowDesignerTotal">' + (selectedIds!='all'?selectedIds.length:'?') + '</span> done',
        theme: false,
        onBlock: function() {
            var counter = -1;

            if(selectedIds == 'all') {
                jQuery.ajaxSetup({async:false});
                var parameter = listInstance.getDefaultParams();
                parameter.module = 'Workflow2';
                parameter.view = undefined;
                parameter.action = 'GetSelectedIds';

                jQuery.post('index.php', parameter, function(response) {
                    selectedIds =  response.ids;
                }, 'json');
                jQuery('#workflowDesignerTotal').html(selectedIds.length);
                jQuery.ajaxSetup({async:true});
            }

            if(selectedIds.length > 1) {
                ENABLEredirectionOrReloadAfterFinish = false;
            }

            var withoutrecordMode = false;

            if(selectedIds.length == 0 && couldStartWithoutRecord != true) {
                jQuery.unblockUI();
                return;
            }
            if(selectedIds.length == 0) {
                withoutrecordMode = true;
            }

            function _executeCallback() {
                counter = counter + 1;
                jQuery('#workflowDesignerDone').html(counter);
                var crmid = selectedIds.shift();

                if(withoutrecordMode === true) {
                    crmid = 0;
                    withoutrecordMode = false;
                }

                if(typeof crmid != 'undefined') {
                    var workflow = new Workflow();
                    workflow.execute(workflowId, crmid, _executeCallback);
                } else {
                    jQuery.unblockUI();
                    window.location.reload();
                }
            }

            _executeCallback();
        }
    });

}
function runListViewSidebarWorkflow() {
    runListViewWorkflow(jQuery("#workflow2_workflowid").val(), jQuery("#workflow2_workflowid option:selected").data('withoutrecord') == '1');
}
function runSidebarWorkflow(crmid) {
    if(jQuery("#workflow2_workflowid").val() == "") {
        return;
    }

    var workflow = new Workflow();
    workflow.execute(jQuery("#workflow2_workflowid").val(), crmid);
}

var WorkflowExecution = function() {
    this._crmid = null;
    this._execId = null;

    this._workflowId = null;

    this._execId = null;
    this._blockID = null;

    this._requestValues = {};
    this._requestValuesKey = null;

    this._callback = null;
    this._allowParallel = false;
    this._allowRedirection = true;

    this.allowParallel = function() {
        this._allowParallel = true;
    }
    this.init = function(crmid) {
        this._crmid = crmid;
    }

    this.setWorkflowById = function(workflow_id) {
        this._workflowId = workflow_id;
    }

    this.setCallback = function(callback) {
        this._callback = callback;
    }

    this.enableRedirection = function(value) {
        this._allowRedirection = value ? true : false;
    }

    this._handleRedirection = function(response) {
        if(this._allowRedirection === true) {
            if(response["redirection_target"] == "same") {
                window.location.href = response["redirection"];
                return true;
            } else {
                window.location.href = response["redirection"];
                return true;
            }
        }
        return false;
    }

    this.setContinue = function(execID, blockID) {
        this._execId = execID;
        this._blockID = blockID;
    }

    this.executeWithForm = function(form) {
        if(typeof jQuery(form).ajaxSubmit == 'undefined') {
            console.error('jquery.forms plugin requuired!');
            return;
        }

        jQuery.blockUI({ message: '<h3 style="padding:5px 0;"><img src="modules/Workflow2/icons/sending.gif" /><br/>Please wait ...</h3>' });

        jQuery(form).ajaxSubmit({
            'url' : "index.php",
            'type': 'post',
            data: {
                "module" : "Workflow2",
                "action" : "ExecuteNew",

                'crmid' : this._crmid,

                'workflowID' : this._workflowId === null ? undefined : this._workflowId,
                'allowParallel': this._allowParallel ? 1 : 0,

                'continueExecId': this._execId === null ? undefined : this._execId,
                'continueBlockId': this._blockID === null ? undefined : this._blockID,
                'requestValues': this._requestValues === null ? undefined : this._requestValues,
                'requestValuesKey': this._requestValuesKey === null ? undefined : this._requestValuesKey
            },
            success:jQuery.proxy(this.executionResponse, this)
        });

    }

    this.execute = function() {
        jQuery.blockUI({ message: '<h3 style="padding:5px 0;"><img src="modules/Workflow2/icons/sending.gif" /><br/>Please wait ...</h3>' });

        jQuery.post("index.php", {
                "module" : "Workflow2",
                "action" : "ExecuteNew",

                'crmid' : this._crmid,

                'workflowID' : this._workflowId === null ? undefined : this._workflowId,
                'allowParallel': this._allowParallel ? 1 : 0,

                'continueExecId': this._execId === null ? undefined : this._execId,
                'continueBlockId': this._blockID === null ? undefined : this._blockID,
                'requestValues': this._requestValues === null ? undefined : this._requestValues,
                'requestValuesKey': this._requestValuesKey === null ? undefined : this._requestValuesKey
            },
            jQuery.proxy(this.executionResponse, this)
        );
    }

    this.executionResponse = function(responseTMP) {
        if(responseTMP.indexOf('Invalid request') !== -1) {
            alert('You did not do any action in VtigerCRM since a long time. The page needs to be reloaded, before you could use the Workflow Designer.');
            window.location.reload();
            return;
        }

        jQuery.unblockUI();

        try {
            response = jQuery.parseJSON(responseTMP);
        } catch(exp) {
            console.log(responseTMP);
            return;
        }

        if(response["result"] == "ready") {
            if(typeof this._callback == 'function') {
                var retVal = this._callback.call(this, response);

                if(typeof retVal != 'undefined' && retVal === false) {
                    return;
                }
            }


            if(typeof response["redirection"] != "undefined") {
                var handleRedirection = this._handleRedirection(response);
                return;
            }

            if(this._allowRedirection === true) {
                window.location.reload();
            }
        } else if(response["result"] == "reqvalues") {
            this._requestValuesKey = response['fields_key'];
            this._execId = response['execId'];

            if(typeof RequestValuesForm == 'undefined') {
                jQuery.getScript('modules/Workflow2/views/resources/js/RequestValuesForm.js', jQuery.proxy(function() {
                    var requestForm = new RequestValuesForm();
                    requestForm.show(response, this._requestValuesKey, response['request_message'], jQuery.proxy(this.submitRequestFields, this), response['stoppable'], response['pausable']);
                }, this));
            } else {
                var requestForm = new RequestValuesForm();
                requestForm.show(response, this._requestValuesKey, response['request_message'], jQuery.proxy(this.submitRequestFields, this), response['stoppable'], response['pausable']);
            }
        } else {
            console.log(response);
        }
    }

    this.submitRequestFields = function(key, values, value2, form) {

        this._requestValues = {};
        this._requestValuesKey = key;

        var html = '';
        jQuery.each(values, jQuery.proxy(function(index, value) {
            this._requestValues[value.name] = value.value;
        }, this));

        if(jQuery('[type="file"]', form).length > 0) {
            var html = '<form action="#" method="POST" onsubmit="return false;">';
                jQuery('input, select, button', form).attr('disabled', 'disabled');
                jQuery('[type="file"]', form).removeAttr('disabled').each(jQuery.proxy(function(index, ele) {
                    var name = jQuery(ele).attr('name');
                    jQuery(ele).attr('name', 'fileUpload[' + name + ']');

                    this._requestValues[name] = jQuery(ele).data('filestoreid');
                }, this));
            html += '</form>';
            this.executeWithForm(form);
            return;
        }
        this.execute();
    }
}

var Workflow = function () {
    this.crmid = 0;
    this._allowParallel = 0;
    this._workflowid = null;
    this._workflowTrigger = null;

    this._currentExecId = null;

    this.ExecutionCallback = null;

    /**
     *
     * @param workflow WorkflowID or Trigger
     * @param crmid Record to use
     */
    this.execute = function(workflow, crmid, callback) {
        this.crmid = crmid;

        if(jQuery.isNumeric(workflow)) {
            this._executeById(workflow, callback);
        } else {
            console.log('not yet implemented');
        }
    }

    this.allowParallel = function(value) {
        this._allowParallel = value?1:0;
    }

    this._executeById = function(workflow_id, ExecutionCallback) {

        var Execution = new WorkflowExecution();
        Execution.init(this.crmid);

        if(this._allowParallel == 1) {
            Execution.allowParallel();
        }
        Execution.enableRedirection(ENABLEredirectionOrReloadAfterFinish);


        if(typeof ExecutionCallback != 'undefined') {
            this._workflowid = workflow_id;
        }

        if(typeof ExecutionCallback != 'undefined') {
            Execution.setCallback(ExecutionCallback);
        }
        Execution.setWorkflowById(workflow_id);
        Execution.execute();

    } /** ExecuteById **/

    this._submitStartfields = function(fields, urlStr) {
        app.hideModalWindow();
        jQuery.blockUI({
            'message' : 'Workflow is executing',
            // disable if you want key and mouse events to be enable for content that is blocked (fix for select2 search box)
            bindEvents: false,

            //Fix for overlay opacity issue in FF/Linux
            applyPlatformOpacityRules : false
        });

        jQuery.post("index.php", {
                "module" : "Workflow2",
                "action" : "Execute",
                "file"   : "ajaxExecuteWorkflow",

                "crmid" : this.crmid,
                "workflow" : this._workflowid,
                allow_parallel: this._allowParallel,
                "startfields": fields
            },
            jQuery.proxy(function(response) {
                jQuery.unblockUI();

                try {
                    response = jQuery.parseJSON(response);
                 } catch (e) {
                   console.log(response);
                   return;
                 }

                if(response["result"] == "ok") {
                    if(ENABLEredirectionOrReloadAfterFinish) {
                        window.location.reload();
                    }
                } else {
                    console.log(response);
                }
            }, this)
        );
    }

    this.closeForceNotification = function(messageId) {
        jQuery.post('index.php?module=Workflow2&action=MessageClose', { messageId:messageId, force: 1 });
    }

    this.parseMessages = function() {
        if(typeof WorkflowRecordMessages != 'object' || WorkflowRecordMessages.length == 0) {
            return;
        }
        this.loadCachedScript('modules/Workflow2/views/resources/js/noty/jquery.noty.packaged.min.js').always(function()
        {
            jQuery.each(WorkflowRecordMessages, function(index, value) {
                   var type = 'alert';
                   switch(value.type) {
                       case 'success':
                           type = 'success';
                           break;
                       case 'info':
                           type = 'alert';
                           break;
                       case 'error':
                           type = 'error';
                           break;
                   }
                   value.message = '<strong>' + value.subject + "</strong><br/>" + value.message;

                   if(value.show_until != '') {
                       value.message += '<br/><span style="font-size:10px;font-style: italic;">' +value.show_until + '</span>';
                   }
                    if(WFUserIsAdmin == true) {
                        value.message += '&nbsp;&nbsp;<a href="#" style="font-size:10px;font-style: italic;" onclick="workflowObj.closeForceNotification(' + value.id + ');">(Remove Message)</a>';
                    }

                    noty({
                            text: value.message,
                            id: 'workflowMessage' + value['id'],
                            type: value.type,
                            timeout: false,
                            'layout':value.position,
                            'messageId': value.id,
                            callback : {
                                "afterClose": function() {
                                    jQuery.post('index.php?module=Workflow2&action=MessageClose', { messageId:this.options.messageId });
                                }
                            }
                        });
            });
        });
    }

    this.loadCachedScript = function( url, options ) {

      // Allow user to set any option except for dataType, cache, and url
      options = jQuery.extend( options || {}, {
        dataType: "script",
        cache: true,
        url: url
      });

      // Use $.ajax() since it is more flexible than $.getScript
      // Return the jqXHR object so we can chain callbacks
      return jQuery.ajax( options );
    };
}

var WorkflowHandler = {
    startImport : function() {
        var source_module = jQuery('#WFD_CURRENT_MODULE').val();
        jQuery.post('index.php?module=Workflow2&view=ImportStep1', { source_module: source_module, currentUrl: window.location.href },  function(html) {
            app.showModalWindow(html, function(data) {
                jQuery('#modalSubmitButton').removeAttr('disabled');
            });
        });
    }
};

function showEntityData(crmid) {
    jQuery.post('index.php?module=Workflow2&view=EntityData', { crmid:crmid },  function(html) {
        app.showModalWindow(html, function(data) {

        });
    });
}
var workflowObj;
jQuery(function() {
    jQuery(window).on('workflow.detail.sidebar.ready', function() {
        var workflow = new Workflow();
        workflowObj = workflow;

        workflow.parseMessages();
    }).on('workflow.list.sidebar.ready', function() {
        var workflow = new Workflow();
        workflowObj = workflow;

        workflow.parseMessages();
    });
    /* jQuery(window).on('workflow.list.sidebar.ready', function() {
        console.log('List-Sidebar ready');
    }); */
});