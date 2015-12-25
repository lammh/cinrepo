var jsPlumbInstance = null;
function initJsPlumb(afterFunction) {

    jsPlumbInstance = jsPlumb.getInstance({
        // default drag options
        DragOptions : { cursor: "pointer", zIndex:2000 },
        ConnectionOverlays: [
            [ "Arrow",
                {
                    location: 0.7,
                    width:15,
                    length:15,
                    paintStyle: {
                        fillStyle:"#3ac91c",
                        strokeStyle:"#3ac91c",
                    },
                    cssClass:'directionArrow'
                }
            ],
            [ "Arrow",
                {
                    location: 0.3,
                    width:15,
                    length:15,
                    paintStyle: {
                        fillStyle:"#3ac91c",
                        strokeStyle:"#3ac91c",
                    },
                    cssClass:'directionArrow'
                }
            ]
        ]
    });

    jsPlumbInstance.importDefaults({
        ConnectorZIndex:5
      });

    jQuery('#workflowObjectsContainer').trigger('designer:init', [jsPlumbInstance]);

    jsPlumbInstance.batch(function() {
        afterFunction(jsPlumbInstance);
    });
    jsPlumbInstance.repaintEverything();

    //jsPlumbInstance
    jQuery('.wfBlock').bind( "dblclick", onDblClickBlock);
    jQuery('#workflowActiveSwitch').on('change', function(e) {
        var checked = jQuery(this).prop('checked');
        saveWorkflowActivationStatus(checked);
    });
    jQuery('.colorLayer, .idLayer').bind( "dblclick", function(event) { jQuery(event.target).parent().trigger("dblclick"); });

    jQuery(".wfBlock img.settingsIcon").bind( "click", function() {
        onDblClickBlock({target:{id:jQuery(this).parent().attr("id")}});
    });

    jQuery("#mainModelWindow").hide();

    jQuery('#stopAllRunningInstances').on('click', function() {
        jQuery.post('index.php', {parent:'Settings', module:'Workflow2', action:'StopAllRunning', workflowID:workflow_id}, function() {
            jQuery('#runningWarning').slideUp('fast');
            jQuery('.overviewStatisticNumber').html("0");
        });
    });

}

/* Event Handler */
function onDblClickBlock(event) {
    var targetID = event.target.id;

    parts = targetID.split("__");

    if(parts[0] == "block") {
        jQuery('#workflowObjectsContainer').trigger('block:dblclick', [parts[1], targetID]);
    } else if(parts[0] == "person") {
        jQuery('#workflowObjectsContainer').trigger('object:dblclick', ['Users', parts[1], targetID]);
    }
}

function onDragStopBlock(params) {
    var ele = params.el;
    if(jQuery(ele).hasClass("colorLayer")) {
        ele = jQuery(ele).parent();
    } else {
        ele = jQuery(ele);
    }

    jQuery('span.blockDescription', ele).show();
    jQuery('img.settingsIcon', ele).show();
    //jsPlumbInstance.setSuspendDrawing(false, true);
    jQuery('#workflowObjectsContainer').trigger('block:dragstop', [jQuery(ele), params]);
}
function onDragStartBlock(params) {
    consoole.log('start');
}

function filterGetInput(value) { return value }
function getInput(iconSrc, scope, isSource, isTarget, isPerson, backgroundColor) {
    if(isPerson == true) {
        gradients = {stops:[[0, "#4034b2"], [1, "#4034b2"]]};
        dashStyle = "2 2";
        lineWidth = 1;
    } else {
        gradients = backgroundColor !== undefined ? backgroundColor : {stops:[[0, "#FF7878"], [1, "#02E002"]]};
//            gradients = backgroundColor !== undefined ? backgroundColor : {stops:[[0, "#f00"], [1, "#0f0"]]};
        dashStyle = "2 0";
        lineWidth = 2;
    }

    if(isPerson === true) {
        var paintStyle = {};
        var endPoint = [
                    "Image", {
                        src:iconSrc,
                        hoverClass:'hoverClass',
                        cssClass:'defaultClass' + (isPerson?" personConnector":"") + (isTarget?" InputEndpoint":"") + (isSource?" OutputEndpoint":"")
                    }
                ];
    } else {
        if(isTarget === false) {

            // Output
            var paintStyle = {
                gradient : {
                    stops:[ [0, "#c83c3c"], [1, "#f57878"] ],
                    offset:37.5,
                    innerRadius:2
                },
                radius:6
            };
        } else {

            // Input
            var paintStyle = {
                gradient : {
                    stops:[ [0, "#5fc22c"], [1, "#89e35b"] ],
                    offset:37.5,
                    innerRadius:2
                },
                radius:6
            };
        }
        var endPoint = '';
    }

    return filterGetInput({
        endpoint:endPoint,
        //endpoint:'Rectangle',
        paintStyle:paintStyle,
        hoverClass:'hoverClass',
        cssClass:'defaultClass' + (isPerson?" personConnector":"") + (isTarget?" InputEndpoint":"") + (isSource?" OutputEndpoint":""),
        isSource:(isReadonly ? false : isSource),
        reattach:(isReadonly ? false : isSource),
        isTarget:(isReadonly ? false : isTarget),
        scope:scope,
        connector:[
            "Bezier", {
                curviness:70
            }
        ],
        connectorStyle : {
            gradient:gradients,
            strokeStyle:"#00f",
            lineWidth:3,
            outlineColor:"#fafafb",
            outlineWidth:3

        },
        hoverPaintStyle:{
    		fillStyle:"#216477",
    		strokeStyle:"#216477",
            zIndex:9999
    	},
        connectorHoverStyle:{
            gradient:{stops:[[0, '#216477']]},
            lineWidth:4,
            strokeStyle:"#216477",
            outlineWidth:5,
            outlineColor:"fafafb",
            zIndex:9999
        },
        beforeDrop:function(params) {
            return true;
        }
    });
}

function saveWorkflowActivationStatus(isActive) {
    jQuery.post('index.php', { module:'Workflow2', parent:'Settings', action:'WorkflowStatus', value:isActive?1:0, workflow:workflow_id }, function(response) {
        if(response.show_warning == '1') {
            jQuery('#runningWarning').slideDown('fast');
        } else {
            jQuery('#runningWarning').slideUp('fast');
        }
    }, 'json');
}
function setColorLayer(id, color) {
    var ele = jQuery("#" + id + " .colorLayer");
    jQuery("#" + id + " .colorLayer").data("color", color);
    jQuery.post("index.php?module=Workflow2&action=ColorSet&parent=Settings", {block_id:id.replace("block__", ""), color:color});
    if(color == "FFFFFF") {
        ele.hide();
        ele.removeClass("colored");
    } else {
        ele.show().css("backgroundColor", "#" + color);
        ele.addClass("colored");
    }

}
function setTaskText(block_id, value) {
    var ele = jQuery("#block__" + block_id + "_description");

    ele.html("<br>" + value);
}
function setBlockActive(block_id, value) {
    var ele = jQuery("#block__" + block_id);

    if(value == true) {
        ele.removeClass("wfBlockDeactive");
    } else {
        ele.addClass("wfBlockDeactive");
    }
}
function saveWorkflowTitle() {
    jQuery("#workflow_title").attr("disabled", "disabled");
    var title = jQuery("#workflow_title").val();

    jQuery.post("index.php", { module: 'Workflow2', action: 'WorkflowSetTitle', 'parent': 'Settings', workflow:workflow_id, title:title }, function() {
        jQuery("#workflow_title").removeAttr("disabled");
    });

}

var workflowDesignerObjects = {};
var workflowDesignerObjectCounter = 0;

function showOptionsContainer() {
    jQuery("#optionsContainer").slideToggle("fast");
}
function refreshBlockIDs() {
    var showBlockIds = jQuery("#optionShowBlockId").prop("checked");
    document.getElementsByClassName = null;

    if(showBlockIds == true) {
        jQuery(".idLayer").show();
    } else {
        jQuery(".idLayer").hide();
    }
}

var currentWorkSpaceWidth = jQuery('#mainWfContainer').width();
function resizeWorkspace() {
    currentWorkSpaceHeight = jQuery('body').height() - 70;
    jQuery('#mainWfContainer').css('height', (jQuery('body').height() - 70) + 'px');
}

var Dnd = false;
jQuery(function() {
    jQuery('.accordion-heading').on('click', resizeWorkspace);
    jQuery('.typeContainer').sortable({
        connectWith: ".typeContainer",
        delay:200,
        distance: 20,
        handle: '.moveTypes',
        placeholder: 'placeholderType',
        helper: 'helperType',
        axis: "y",
        zIndex: 9999,
        stop: function( event, ui ) {
            var block = jQuery(ui.item[0]).closest('.typeContainer').data('block');
            Dnd.finishBlock = block;

            if(Dnd.finishBlock != Dnd.startBlock) {
                reorderCategory(Dnd.startBlock);
            }
            reorderCategory(Dnd.finishBlock);

            Dnd = false;
        },
        start: function(event, ui) {
            Dnd = {};
            var block = jQuery(ui.item[0]).closest('.typeContainer').data('block');
            Dnd.startBlock = block;
        }
    });

    jQuery('.typeSearchBox').on('click', function(e) {
        jQuery(this).select();
    });
    jQuery('.typeSearchBox').on('keyup', function(e) {
        var value = this.value;
        if(value.length >= 3) {
            jQuery('div.taskWidgetContainer').hide();
            jQuery('div.taskWidgetContainer .WorkflowTypeContainer').hide();
            jQuery('div.taskWidgetContainer .WorkflowTypeContainer[data-search*="' + value.toLowerCase() + '"]').show().closest('.taskWidgetContainer').show().find('.accordion-body').addClass('in');
        } else {
            jQuery('div.taskWidgetContainer .WorkflowTypeContainer').show();
            jQuery('div.taskWidgetContainer').show();
        }
    });
});

function reorderCategory(blockKey) {
    var sort = jQuery('.typeContainer[data-block="' + blockKey + '"]').sortable('toArray', { 'attribute': 'data-type'});
    jQuery.post('index.php', {module:'Workflow2', 'parent': 'Settings', action:'TypesSort', sort:sort, block:blockKey});
}

