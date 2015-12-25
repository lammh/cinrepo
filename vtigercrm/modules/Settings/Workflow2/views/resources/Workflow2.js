/**
 * Created by JetBrains PhpStorm.
 * User: Stefan Warnat <support@stefanwarnat.de>
 * Date: 19.12.13 11:08
 * You must not use this file without permission.
 */
var endpoints = {};
var topAnchor;
var leftAnchor = [
    [0, 0.5,    -1, 0,  0, 0],
    /*[1, 0.5,    1, 0,  2, 0],*/
    [0, 0,    -0.5, 1,  -2, 0],
    [0, 1,    -0.5, 1,  -2, 0],
    [1, 1,    0.5, 1,  2, 0],
    [1, 0,    0.5, 1,  2, 0],
];
var bottomAnchor = [
    [0.5,   1,  0,  1,  -5, 0]
];

var rightAnchor = {
    "0":[
        [
            [1, 0.5,   1,  0,  0,  0]
        ]
    ],
    "1":[
        [
            [1, 0.75,   1,  0,  2,  0], [0, 0.75,   -1,  0,  0, 0]
        ]
    ],
    "2":[
        [
            [1, 0.25,   1,  0,  2,  0], [0, 0.25,   -1,  0,  0, 0]
        ],[
            [1, 0.75,   1,  0,  2,  0], [0, 0.75,   -1,  0,  0, 0]
        ]
    ],
    "3":[
        [1, 0.2,    1,  0,  2,  0],
        [1, 0.5,    1,  0,  2,  0],
        [1, 0.8,    1,  0,  2,  0]
    ]
};

var statColor = [
    [""],
    ["#31be00"],
    ["#da9a00", "#31be00"],
    ["#da6700", "#da9a00", "#31be00"]
];
var inputPointOptions  = { anchor:leftAnchor, maxConnections:25};

var currentColorPicker = false;
var lastColors = [];
var lastColorsInit = [];

var _listeners = function(e) {
    e.bind("mouseover", function(ep) {
        ep.showOverlay("lbl");
    });
    e.bind("mouseout", function(ep) {
        ep.hideOverlay("lbl");
    });
};

function setLicense() {
    var license = prompt("Your License-Code:");
    var params = {
        module: 'Workflow2',
        action: 'SetLicense',
        parent: 'Settings',
        dataType: 'json',
        license: license
    };

    if(license != null) {
        jQuery.blockUI({
            'message' : 'Please wait'
        });

        AppConnector.request(params).then(function(data) {
            if(data.result.success == true) {
                jQuery.blockUI({
                    'message' : 'We refresh the list of Tasks you could use.'
                });
                jQuery.post('index.php', {module:'Workflow2', parent:'Settings', action:'RefreshTypes'}, function() {
                    window.location.reload();
                });
            }
            if(data.result.success == false) alert(data.result["error"]);
        });
    }
}

function connectEndpoints(sourceID, targetID) {
    jsPlumbInstance.connect({
        source: endpoints[sourceID],
        target: endpoints[targetID]
//        connector:[ "Bezier", {
//            curviness:70
//        }
//        ]
    });
}
function initInputPoint(pointID) {
    endpoints[pointID + "__input"] = jsPlumbInstance.addEndpoint(pointID,
        {
            ConnectorZIndex:10,
            uuid:pointID + "__input",
            anchor:leftAnchor,
            maxConnections:maxConnections
        },
        jQuery.extend(getInput("modules/Workflow2/icons/input.png", "flowChart", false, true, false), {parameters:{ "in":pointID + "__input" }})
    );
}
function initOutputPoint(pointID, key, label, ConnectorLabel, counter, index) {
    endpoints[pointID + "__" + key] =
        jsPlumbInstance.addEndpoint(
            pointID, {
                anchor:rightAnchor[label=="Start"?0:counter][index],
                maxConnections:maxConnections,
                uuid:pointID + "__" + key,
                connectorOverlays:[[ "Label", { location:0.5, cssClass:"connectionLabel",visible:(ConnectorLabel!='Next'&&ConnectorLabel!='Start'?true:false),label:ConnectorLabel, id:"label"}]],
                overlays:getOverlay(label)
            },
            jQuery.extend(getInput("modules/Workflow2/icons/output.png", "flowChart", true, false, false), { parameters:{ out:pointID + "__" + key }})
        );


    _listeners(endpoints[pointID + "__" + key]);
}
function initPersonInputPoint(pointID, key, label, counter, index) {
    endpoints[pointID + "__" + key] =
        jsPlumbInstance.addEndpoint(
            pointID, {
                anchor:topAnchor[counter][index],
                maxConnections:maxConnections,
                overlays:getOverlay(label, "personLabel")
            },
            jQuery.extend(getInput("modules/Workflow2/icons/peopleInput.png", "person", false, true, true), {parameters:{ "in":pointID + "__" + key }}));
   _listeners(endpoints[pointID + "__" + key]);
}

function addBlock(block, styleClass, duplicateId) {
    if(duplicateId === undefined) duplicateId = 0;

    var params = {
        module: 'Workflow2',
        action: 'BlockAdd',
        parent: 'Settings',
        dataType: 'json',
        workflow:workflow_id,
        duplicateId: duplicateId,
        blockid:block,
        left:350,
        top:80 + jQuery(window).scrollTop()
    };

    AppConnector.request(params).then(function(response) {
        if(response == false) {
            return;
        }
        if(typeof response == 'string' && response.indexOf('Invalid') != -1) {
            alert('Your Security Token was expired. The site needs to be reloaded at first.');
            window.location.reload();
            return;
        }

        var html = response.html;

        jQuery("#WFTaskContainer").append(html);
        jQuery("#block__" + response.blockID).fadeIn("fast");
        jQuery('.WorkflowTypeContainer[data-type="'+block+'"]').effect('transfer', { to: jQuery("#block__" + response.blockID), className: "ui-effects-transfer" });

        jQuery("#block__" + response.blockID).bind( "dblclick", onDblClickBlock);

        jQuery("#block__" + response.blockID + ' .colorLayer').bind( "dblclick", function(event) { jQuery(event.target).parent().trigger("dblclick"); });

        initInputPoint("block__" + response.blockID);
        jQuery.each(response.outputPoints, function(index, value) {
            initOutputPoint("block__" + response.blockID, value[0],value[1],typeof value[2] != "undefined"?value[2]:"", jQuery(response.outputPoints).length, index);
        });
        jQuery.each(response.personPoints, function(index, value) {
            initPersonInputPoint("block__" + response.blockID,value[0],value[1], jQuery(response.personPoints).length, index);
        });

        initDragBlock('.wfBlock#block__' + response.blockID);
        /*
        var styleClass = response.styleClass;
        var element_id = response.element_id;
        var topPos = response.topPos;
        var leftPos = response.leftPos;
        var styleExtra = response.styleExtra;
        var typeText = response.typeText;

        var blockText = (response["blockText"].length > 2 ?'<br><span style="font-weight:bold;">' + response["blockText"] + '</span>':'');
        var html = '<div class="context-wfBlock wfBlock ' + styleClass + '" id="' + element_id+ '" style="display:none;top:' + topPos + 'px;left:' + leftPos + 'px;' + styleExtra + '"><span class="blockDescription">' + typeText + blockText + '</span><div data-color="" class="colorLayer">&nbsp;</div><img class="settingsIcon" src="modules/Workflow2/icons/settings.png"></div>';

        jQuery("#workflowDesignContainer").append(html);



        endpoints[element_id + "__input"] = jsPlumb.addEndpoint(element_id, inputPointOptions, jQuery.extend(getInput('modules/Workflow2/icons/input.png', "flowChart", false, true, false), {parameters:{ "in":element_id + '__input' }}));

        for(var i = 0; i < response.personPoints.length; i++) {
            var pointKey = response.personPoints[i][0];
            endpoints[element_id + "__" + pointKey] = jsPlumb.addEndpoint(element_id, { anchor:topAnchor[response.personPoints.length][i], maxConnections:maxConnections, overlays:getOverlay(response.personPoints[i][1], 'personLabel')  }, jQuery.extend(getInput('modules/Workflow2/icons/peopleInput.png', "person", false, true, true), {parameters:{ "in":element_id + '__' + pointKey }}));
            _listeners(endpoints[element_id + "__" + pointKey]);
        }
        for(var i = 0; i < response.outputPoints.length; i++) {
            var pointKey = response.outputPoints[i][0];
            endpoints[element_id + "__" + pointKey] = jsPlumb.addEndpoint(element_id, { anchor:rightAnchor[response.outputPoints.length][i], maxConnections:maxConnections, overlays:getOverlay(response.outputPoints[i][1]) }, jQuery.extend(getInput('modules/Workflow2/icons/output.png', "flowChart", true, false, false), {parameters:{ out:element_id + '__' + pointKey }}));

            _listeners(endpoints[element_id + "__" + pointKey]);
        }

*/
    }).fail(function(response) {
            if(typeof response == 'string' && response == 'parsererror') {
                alert('Your Security Token was expired. The site needs to be reloaded at first.');
                window.location.reload();
                return;
            }
        }) ;
}



function getOverlay(label, cls) {
   if(cls === undefined) cls = "";

   return [
        [ "Label", { cssClass:"labelClass " + cls, label:label, id:"lbl" } ]
    ];
}
function refreshWorkflowList() {
    var params = {
        module: 'Workflow2',
        view: 'Index',
        parent: 'Settings'
    };
    AppConnector.request(params).then(function(data) {
        jQuery(jQuery(".contentsDiv")[0]).html(data);
    });
}

function importWorkflow() {
    jQuery.post('index.php', {module:'Workflow2', parent:'Settings', view:'WorkflowImporter'}, function(response) {
        app.showModalWindow(response, function(data) {
            jQuery.getScript('modules/Workflow2/views/resources/js/jquery.form.min.js', function() {
                var options = {
                    //target:        '#output1',   // target element(s) to be updated with server response
                    //beforeSubmit:  showRequest,  // pre-submit callback
                    success:       function(response) {

                        if(response.result == 'ok') {
                            jQuery.notification(
                                {
                                    title: "Workflow Import",
                                    timeout: 5000,
                                    color: '#2cae35',
                                    icon: 'W',
                                    content: 'Workflow was imported'
                                }
                            );
                            app.hideModalWindow();

                            refreshWorkflowList();
                        }
                        if(response.result == 'error') {
                            jQuery.notification(
                                {
                                    title: "Error during Import",
                                    error: true,
                                    timeout: 5000,
                                    content: response.message
                                }
                            );
                        }
                    },  // post-submit callback

                    // other available options:
                    //url:       ''         // override for form's 'action' attribute
                    //type:      type        // 'get' or 'post', override for form's 'method' attribute
                    dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type)
                    //clearForm: true        // clear all form fields after successful submit
                    //resetForm: true        // reset the form after successful submit

                    // $.ajax options can be used here too, for example:
                    //timeout:   3000
                };

                // bind form using 'ajaxForm'
                jQuery('#WorkflowImportForm').ajaxForm(options);

                jQuery('#modalSubmitButton').removeAttr('disabled');

            });
        });
    });
}

function setAllPermissionsTo(className, value) {
    jQuery('.' + className + ' select').val(value);
}

function toggleSidebar(moduleName, ele) {
    jQuery.blockUI({
        'message' : app.vtranslate('LBL_MANAGE_SIDEBARTOOGLE') + '...'
    });

    jQuery.post('index.php', {module:'Workflow2', parent: 'Settings', action:'SidebarToggle', 'workflowModule': moduleName}, function(response) {
        jQuery.unblockUI();
        jQuery('#' + ele).html(response);
    });
}

jQuery(function() {
    jQuery('.workflowModuleHeader .toggleImage').on('click', function() {
        var header = jQuery(this).closest('.workflowModuleHeader');
        var target = header.data('target');
        var visible = jQuery('#workflowList' + target).data('visible');

        if(visible == '0') {
            jQuery('#workflowList' + target).show();
            jQuery('.toggleImageExpand', header).hide();
            jQuery('.toggleImageCollapse', header).show();
            jQuery('#workflowList' + target).data('visible', 1);

            jQuery.post('index.php', {module:'Workflow2', 'action':'SetTabVisibility','parent':'Settings', 'target':target, visible:1});
        } else {
            jQuery('#workflowList' + target).hide();
            jQuery('.toggleImageExpand', header).show();
            jQuery('.toggleImageCollapse', header).hide();
            jQuery('#workflowList' + target).data('visible', 0);

            jQuery.post('index.php', {module:'Workflow2', 'action':'SetTabVisibility','parent':'Settings', 'target':target, visible:0});
        }

    });
});