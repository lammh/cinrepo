var isReadonly = false;

jQuery('#workflowObjectsContainer').on('block:dragstop', function(event, ele, ui) {
    var params = {
        module: 'Workflow2',
        action: 'BlockMove',
        parent: 'Settings',
        workflow:workflow_id,
        blockid:ele.attr("id"),
        left:ui.pos[0],
        top:ui.pos[1]
    };

    AppConnector.request(params);
});

jQuery('#workflowObjectsContainer').on('block:dblclick', function(event, taskId) {
    window.open("index.php?module=Workflow2&parent=Settings&view=TaskConfig&taskid=" + taskId, "Config", "width=1154,height=750").focus();
});

jQuery('#workflowObjectsContainer').on('object:dblclick', function(event, module, taskId) {

    jQuery.post("index.php?module=Workflow2&action=RecordList&parent=Settings" , { module_name:module, objectID: taskId }, function(response) {

        app.showModalWindow(response, function() {
            jQuery('#modalSubmitButton').on('click', function() {
                app.hideModalWindow();
                saveObjectRecord(jQuery('#workflowObjectId').val());
                jQuery( this ).dialog( "close" );
                return false;
            });
        });
        return;
    });

});

function initDragBlock(ele) {
    jsPlumbInstance.draggable(jsPlumb.getSelector(ele), {
        start:function(params) {
            jQuery('span.blockDescription', params.el).hide();
            jQuery('img.settingsIcon', params.el).hide();
            //jsPlumbInstance.setSuspendDrawing(true);
        },
        drag:function(params) {
            if(params.pos[1] > currentWorkSpaceHeight - 150) {
                jQuery('#mainWfContainer').css('height', (currentWorkSpaceHeight + 100) + 'px');
                currentWorkSpaceHeight = currentWorkSpaceHeight + 100;
            }
            if(params.pos[0] > currentWorkSpaceWidth - 200) {
                jQuery('#mainWfContainer').css('width', (currentWorkSpaceWidth + 250) + 'px');
                currentWorkSpaceWidth = currentWorkSpaceWidth + 100;
            }
        },
        stop:onDragStopBlock
    });
}

jQuery('#workflowObjectsContainer').on('designer:init', function() {
    if(jQuery(".wfBlock").length > 0) {
        initDragBlock('.wfBlock');
    }

    jsPlumbInstance.bind("click", function(c, origEvent) {
        jsPlumb.detach(c);
    });

    jsPlumbInstance.bind("connectionMoved", function(c, origEvent) {
        var parameters = c.connection.getParameters();

        var params = {
            module: 'Workflow2',
            action: 'ConnectionDel',
            parent: 'Settings',
            workflow: workflow_id,
            destination:c.originalTargetId + '__input',
            source:parameters["out"]
        };

        AppConnector.request(params);
    });

    jsPlumbInstance.bind("connection", function(params) {
        var parameters = params.connection.getParameters();

        var params = {
            module: 'Workflow2',
            action: 'ConnectionAdd',
            parent: 'Settings',
            workflow: workflow_id,
            destination:parameters["in"],
            source:parameters["out"]
        };

        AppConnector.request(params);
    });

    jsPlumbInstance.bind("connectionDetached", function(params) {
        var parameters = params.connection.getParameters();

        var params = {
            module: 'Workflow2',
            action: 'ConnectionDel',
            parent: 'Settings',
            workflow: workflow_id,
            destination:parameters["in"],
            source:parameters["out"]
        };

        AppConnector.request(params);
    });

    jQuery(".workflowDesignerObject_text").bind("dblclick", editTextObject);
    jQuery(".workflowDesignerObject_text").bind("contextmenu", removeObject);
});

jsPlumb.bind("ready", function() {
    jQuery(".colorLayer.colored").each(function(index, value) {
         var ele = jQuery(value);
         var color = ele.data("color").substr(1);

         if(color != '' && color != 'FFFFFF' && lastColors.indexOf(color) == -1) {
             lastColors.push(color);
         }

         if(lastColors.length == 6) {
             return false;
         }
     });

     jQuery.contextMenu({
         selector: '.context-wfBlock',
         callback: function(key, options) {
             if(jQuery(options.$trigger.get(0)).hasClass("startBlock")) {
                 return;
             }
             switch(key) {
                 case "config":
                     onDblClickBlock({target:{id:options.$trigger.get(0).id}});
                     break;
                 case "copy":
                     addBlock(0, 0, options.$trigger.get(0).id.replace("block__", ""));
                     break;
                 case "removecolor":
                     setColorLayer(options.$trigger.get(0).id, "FFFFFF");
                         break;
                 case "color":
                     currentColorPicker = [options.$trigger.get(0).id];
                     var position = options.$menu.position();

                     jQuery("#colorPickerElement").css({"position":"absolute", "left": "300px", "top":"200px"}).unbind("change").unbind("removepicker").bind("change", function() {
                      setColorLayer(currentColorPicker[0], this.value);

                     }).bind("removepicker", function() {
                         if(this.value != 'FFFFFF' && lastColors.indexOf(this.value) == -1) {
                             if(lastColors.length >= 6) {
                                 lastColors.shift();
                             }
                             lastColors.push(this.value);
                         }
                     });

                     var myPicker = new jscolor.color(document.getElementById('colorPickerElement'), {pickerClosable:true, showlast:lastColors});
                     myPicker.fromString(jQuery("#" + options.$trigger.get(0).id + " .colorLayer").data("color").substr(1))  // now you can access API via 'myPicker' variable
                     myPicker.drawPicker2(position.left, position.top);
                     currentColorPicker[1] = myPicker;

                     break;
                 case "delete":
                     if(confirm("Realy delete this block?") == false)
                         return;

                     removeBlock(options.$trigger.get(0).id);
                     break;
             }
         },
         items: {
             "config": {name: "Config", icon: "edit"},
             "copy": {name: app.vtranslate('LBL_DUPLICATE_BLOCK'), icon: "copy"},
             "sep1": "---------",
             "color": {name: app.vtranslate('LBL_CHANGE_BLOCKCOLOR'), icon: "color"},
             "removecolor": {name: app.vtranslate('LBL_REMOVE_BLOCKCOLOR'), icon: "colorremove"},
             "sep2": "---------",
             "delete": {name: app.vtranslate('LBL_DELETE_BLOCK'), icon: "delete"}
         }
     });
});

function removePerson(person_id) {
    jQuery.post("index.php" ,  {module:'Workflow2', parent:'Settings', action:'PersonRemove', block_id:person_id, workflow_id:workflow_id });

    jsPlumb.removeAllEndpoints(person_id);
    jQuery("#" + person_id).remove();
}

function removeBlock(block_id) {
    var params = {
        module: 'Workflow2',
        action: 'BlockDel',
        parent: 'Settings',
        workflow: workflow_id,
        blockid: block_id
    };

    AppConnector.request(params);

    jsPlumbInstance.removeAllEndpoints(block_id);
    jQuery("#" + block_id).fadeOut("fast", function() {
        jQuery(this).remove();
    });
}

function addRecord(module_name) {
    jQuery.post("index.php", { module:'Workflow2', parent:'Settings', action:'PersonAdd',workflow:workflow_id, module_name:module_name }, function(response) {
        var element_id = response.element_id;
        var topPos = response.topPos;
        var leftPos = response.leftPos;

        var html = '<div class="wfBlock wfPerson" id="' + element_id + '" style="top:' + topPos + 'px;left:' + leftPos + 'px;">Not connected<img src="modules/Workflow2/icons/cross-button.png" class="removePersonIcon" onclick="removePerson(\'' + element_id + '\');"></div>';

        jQuery("#workflowDesignContainer").append(html);

        endpoints[element_id + "__person"] = jsPlumb.addEndpoint(element_id, { anchor:bottomAnchor, maxConnections:maxConnections }, jQuery.extend(getInput('modules/Workflow2/icons/peopleOutput.png', "person", true, false, true), {parameters:{ out:element_id + '__person' }}));

        jsPlumb.setDraggable("#" + element_id, true);
        jQuery("#" + element_id).bind( "dblclick", onDblClickBlock);

        jQuery("#" + element_id).bind( "dragstop", onDragStopBlock);
    }, 'json');
}
function addObject(type) {
    var html = false;
    type = type.toLowerCase();
    workflowDesignerObjectCounter = Number(workflowDesignerObjectCounter) + 1;

    jQuery.post("index.php", {module:'Workflow2', parent:'Settings', action:'ObjectAdd',type:type, workflow: workflow_id}, function(response) {
        html = response["content"];
        id = response["id"];
//        console.log(html);
        if(html !== false) {
            jQuery("#workflowObjectsContainer").append(html);
            initTextDrag("#" + id);

            jQuery("#" + id).bind("dblclick", editTextObject);
            jQuery("#" + id).bind("contextmenu", removeObject);

        }
    }, "json");
}


function removeObject(event) {
    var currentCKEditorObjectId = this.id.substr(this.id.indexOf("_") + 1);

    jQuery.post("index.php", {module:'Workflow2', parent:'Settings', action:'ObjectRemove', id: currentCKEditorObjectId});
    jQuery(this).fadeOut("fast")
    return false;
}
function editTextObject(id)  {
    var currentCKEditorObjectId = this.id.substr(this.id.indexOf("_") + 1);

    jQuery( "#workflowDesignerObject_" + currentCKEditorObjectId).draggable("destroy");
    jQuery("#workflowDesignerObject_"  + currentCKEditorObjectId).attr("contenteditable", "true");

    var editor = CKEDITOR.inline( document.getElementById( 'workflowDesignerObject_'  + currentCKEditorObjectId) , {startupFocus  :true});
    editor.on("blur", function() {
        editor.destroy();
        jQuery("#workflowDesignerObject_"  + currentCKEditorObjectId).removeAttr("contenteditable");
        initTextDrag("#workflowDesignerObject_" + currentCKEditorObjectId);
        jQuery.post("index.php", { module:'Workflow2', parent:'Settings', action:'ObjectSetText', id: currentCKEditorObjectId, text: jQuery("#workflowDesignerObject_"  + currentCKEditorObjectId).html()});
    });
}

function initTextDrag(objectSelector) {
    jQuery(objectSelector).draggable({
        stop: function(event, ui) {
            var currentCKEditorObjectId = this.id.substr(this.id.indexOf("_") + 1);

            jQuery.post("index.php", { module:'Workflow2', parent:'Settings', action:'ObjectSetPos', id: currentCKEditorObjectId, y: ui.position.top, x: ui.position.left });
        }
    });
}

function saveObjectRecord(objectID) {
    var selected = jQuery('#recordSelector').val();
    jQuery.post("index.php",  {module:'Workflow2', parent:'Settings', action:'ObjectRecordConnection', objectID:objectID, recordID: selected}, function(response) {
        jQuery('#person__' + objectID + " span").html(jQuery('#person__' + objectID + " span").html().replace(jQuery('#person__' + objectID + " span").text(), response));
    });
}

initTextDrag(".workflowDesignerObject_text");