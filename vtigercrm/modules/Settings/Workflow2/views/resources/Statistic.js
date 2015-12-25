var startDate = "";
var endDate = "";
var connectionStatistik = {};
var overlayStatistik = {};

var recordExecutionCache = {};
var currentStatistikExecID = false;
var isReadonly = true;
var connectionDetailPage = 1;
var rightAnchor = {
    "0":[
        [
            [1, 0.5,   1,  0,  0,  0]
        ]
    ],
    "1":[
        [
            [1, 0.75,   1,  0,  0,  0], [0, 0.75,   -1,  0,  0, 0]
        ]
    ],
    "2":[
        [
            [1, 0.25,   1,  0,  0,  0], [0, 0.25,   -1,  0,  0, 0]
        ],[
            [1, 0.75,   1,  0,  0,  0], [0, 0.75,   -1,  0,  0, 0]
        ]
    ],
    "3":[
        [1, 0.25,   1,  0,  0,  0],
        [1, 0.5,    1,  0,  0,  0],
        [1, 0.75,   1,  0,  0,  0]
    ]
};

jQuery('#workflowObjectsContainer').on('block:dblclick', function(event, taskId) {
    var params = {
        module: 'Workflow2',
        view: 'StatistikPopup',
        parent: 'Settings',
        dataType: 'json',
        id:taskId,
        execId:(currentStatistikExecID!==false?currentStatistikExecID:"")
    };

    AppConnector.request(params).then(function(data) {
        app.showModalWindow(data, function(data) {
            drawBlockChart();
            jQuery( ".blockUI.blockMsg.blockPage" ).resizable( { handles: "w,e,s,se", alsoResize: "#StatistikPopup" } );
            jQuery( ".blockUI.blockMsg.blockPage" ).draggable({ handle: ".dragHandle" });
        });

    });
    //window.open("index.php?module=Workflow2&parent=Settings&view=TaskConfig&taskid=" + taskId, "Config", "width=1024,height=700").focus();
});

function filterGetInput(value) {
    value['reattach'] = false;
    value['connectorStyle'] = undefined;
    return value;
}

jQuery('#workflowObjectsContainer').on('designer:init', function() {
    if(jQuery(".wfBlock").length > 0) {
        jsPlumbInstance.draggable(jsPlumb.getSelector(".wfBlock"));
    }

    refreshData();
});

var getStatistikOverlay = function(absolute, percent) {
    return [
        "Label", {
            label:percent > 0 ? Math.round(percent * 100) + "%" + " <span style='font-size:9px;'>/ " + absolute + "</span>" : "0 %",
            location:0.2,
            width:100,
            height:30,
            cssClass:'statOverlay',
            id: "percentageOverlay",
            events:{
                click:function(labelOverlay, originalEvent) {
                    var parameters = labelOverlay.component.getParameters();
                    readConnectionDetails(parameters["in"], parameters["out"],  startDate, endDate);
                }
            }
        }
    ];
};

var statistikPlot = false;

function loadConnectionChart(dest, source, startTime, endTime) {
    /* @TODO */
    jQuery.post("index.php?module=Workflow2&parent=Settings&action=StatistikConnectionChartData", {
                        destination: connectionDetailParameter['dest'],
                        source: connectionDetailParameter['source'],
                        startDate: connectionDetailParameter['startTime'],
                        endDate: connectionDetailParameter['endTime'],
                        module_name : workflow_data['module_name']
                    },
        function(response) {
            response = response.result.data;
            jQuery('.statUsageShow').show();
            jQuery('#innerstatContainer').slideDown('fast');
            jQuery('#statChartContainer').fadeIn('fast');
            jQuery('#jqPlotContainer').html("");
            statistikPlot = jQuery.jqplot('jqPlotContainer', [response.data], {
                  title:app.vtranslate("HEAD_USAGE_OF_THIS_CONNECTION"),

                    axes:{
                        xaxis:{
                            renderer:jQuery.jqplot.DateAxisRenderer,
                            labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer,
                            tickRenderer: jQuery.jqplot.CanvasAxisTickRenderer,
                            min:startDate,
                            max:endDate,
                            label:app.vtranslate("LBL_DATE"),
                            tickInterval: response.Yinterval + ' day',
                            tickOptions:{
                                labelPosition: 'start',
                                formatString:'%d-%m-%Y',
                                angle: 90,
                                fontSize: '11px'
                            }
                        },
                        yaxis:{
                            min:0,

                            max:response.max,
                            tickInterval: response.Xinterval,
                          pad:0,
                            tickOptions:{
                              padMin:0,
                              pad:0,
                              padMax:0
//                                    formatString:'$%.2f'
                            }
                        }
                      },
                      highlighter: {
                        show: true,
                        sizeAdjust: 7.5
                      },
                      cursor: {
                        show: false
                      }
                  });
        }, "json"
    );

}

function loadStatistikRecord(execID) {
    currentStatistikExecID = execID;

    if(recordExecutionCache[execID] !== undefined) {
        readRecordExecutionDetails(execID);
    } else {
        var params = {
            module: 'Workflow2',
            action: 'StatistikRecord',
            parent: 'Settings',
            dataType: 'json',
            execID : execID
        };

        AppConnector.request(params).then(function(tmp) {
            var response = tmp['result'];
            recordExecutionCache[response["execID"]] = response["data"];
            readRecordExecutionDetails(response["execID"]);
        }, 'json');
    }
}

function setAllConnectionsEmpty() {
    jsPlumbInstance.detachEveryConnection({fireEvent: false});
    jQuery('.wfBlock').css("visibility", "hidden");
    jQuery('._jsPlumb_endpoint_anchor').css("visibility", "hidden");
    //jsPlumbInstance.repaintEverything();
}
function readRecordExecutionDetails(execID) {
    var execution = recordExecutionCache[execID];
    setAllConnectionsEmpty();

    jQuery.each(execution, function(index, value) {
        if(endpoints["block__" + value[0] + "__input"] != undefined && endpoints["block__" + value[1] + "__" + value[2]] != undefined) {
            jQuery("#block__" + value[1]).css("visibility", "visible");
            jQuery("#block__" + value[0]).css("visibility", "visible");

            tmp = jsPlumbInstance.connect({
                source:endpoints["block__" + value[1] + "__" + value[2]],
                target:endpoints["block__" + value[0] + "__input"],
                paintStyle:getConnectorStatStyle("#00bb00", 2),
                scope:'onlineConnection',
                connector:[ "Bezier", { curviness:70,margin:10,proximityLimit :150  } ],
                overlays:[
                    [
                    "Label", {
                            label:value["timestamp"],
                            location:0.3,
                            width:70,
                            height:30,
                            cssClass:'statOverlay'
                        }
                    ],
                    [ "Arrow", { foldback:0.2,width:15, height:10 }]
                ]
            });
        }
    });
}

var connectionDetailParameter = {
    'dest': '',
    'source': '',
    'startTime': '',
    'endTime': ''
}

function readConnectionDetails(dest, source, startTime, endTime) {
    connectionDetailParameter['dest'] = dest;
    connectionDetailParameter['source'] = source;
    connectionDetailParameter['startTime'] = startTime;
    connectionDetailParameter['endTime'] = endTime;

    loadConnectionDetails();
    loadConnectionChart();
}

function openConnectionDetailPage(value) {
    connectionDetailPage = value;
    loadConnectionDetails();
}
function loadConnectionDetails() {
    jQuery("#statConnDetails").html("lade Daten ...");
    jQuery("#statDetails").css("style", "block");

    var params = {
        module: 'Workflow2',
        action: 'StatistikDetails',
        parent: 'Settings',
        dataType: 'json',
        destination: connectionDetailParameter['dest'],
        source: connectionDetailParameter['source'],
        startDate: connectionDetailParameter['startTime'],
        endDate: connectionDetailParameter['endTime'],
        page: connectionDetailPage,
        module_name : workflow_data['module_name']
    };

    AppConnector.request(params).then(function(tmp) {
        var response = tmp['result'];
        var html = "";

        if(response.totalpages > 1) {
            html += '<div class="statPaginator">' + app.vtranslate('page') + ': ';
            var from = 1;
            if(response.page > 3) {
                from = response.page - 3;
            }

            var to = response.totalpages;
            if(response.page < response.totalpages - 3) {
                to = response.totalpages - 3;
            }

            for(i = from;i <= to;i++) {
                if(i != connectionDetailPage) {
                    html += '<a href="#" onclick="openConnectionDetailPage(' + i + '); return false;" style="padding:0 2px;">' + i + '</a>';
                } else {
                    html += '<span href="#" onclick="openConnectionDetailPage(' + i + '); return false;" style="padding:0 2px;">' + i + '</span>';
                }

            }
            html += '</div>';
        }

        jQuery.each(response["data"], function(index, value) {
            if(value["title"] !== false) {
                html += "<div><a style='font-weight:bold;' target='_blank' href='"+ value["url"] +"'>" + value["title"] + "</a> <em>[" + value["crmid"] + "]</em></div>";
                html += "<div style='margin-bottom:3px;padding-left:10px;'><span class='statDetailRecord' onclick='loadStatistikRecord(\"" + value["execID"] + "\");jQuery(this).hide();jQuery(this).next().show();' style='float:right;'>&raquo;&raquo;</span><span class='statDetailRecord' onclick='readConnectionStatistik(); jQuery(this).hide();jQuery(this).prev().show();' style='float:right;font-weight:bold;display:none;font-size:14px;'>&laquo;&laquo;</span>" + value["timestamp"] + "</div>";
            } else {
                html += "<div><em>no Record</em></div>";
                html += "<div style='margin-bottom:3px;padding-left:10px;'><span class='statDetailRecord' onclick='loadStatistikRecord(\"" + value["execID"] + "\");jQuery(this).hide();jQuery(this).next().show();' style='float:right;'>&raquo;&raquo;</span><span class='statDetailRecord' onclick='readConnectionStatistik(); jQuery(this).hide();jQuery(this).prev().show();' style='float:right;font-weight:bold;display:none;font-size:14px;'>&laquo;&laquo;</span>" + value["timestamp"] + "</div>";
            }
        });


        jQuery("#statConnDetails").html(html);
        jQuery("#statDetails").slideDown("fast");

    }, 'json');

}

function readOverlayStatistik() {
    jQuery(".statLayer").html("");
    if(overlayStatistik == null) {
        return;
    }

    jQuery.each(overlayStatistik, function(index, value) {
        jQuery("#block__" + index + " .statLayer").html(value);
    });
}
function readConnectionStatistik() {
    setAllConnectionsEmpty();

    // Connection attach here
    jQuery.each(connectionStatistik, function(startIndex, startValue) {
        jQuery.each(startValue, function(destIndex, destValue) {
            if(endpoints[startIndex + "__" + destValue[0]] != undefined && endpoints[destIndex + "__input"] != undefined) {

                var tmp = false;
                if(destValue[5] == false) {
                    jQuery("#" + startIndex).css("visibility", "visible");
                    jQuery("#" + destIndex).css("visibility", "visible");
                    tmp = jsPlumbInstance.connect(
                        {
                            source:endpoints[startIndex + "__" + destValue[0]],
                            target:endpoints[destIndex + "__input"],
                            endpointStyle:{
                                outlineColor:"white",
                                outlineWidth:1,
                                lineWidth:1
                            },
                        paintStyle:getConnectorStatStyle(destValue[3], destValue[4]),
                        scope: 'onlineConnection',
                        connector:[ "Bezier", { curviness:70,margin:10,proximityLimit :150  } ]
                    });
                } else {
                    if(jQuery("#show_removed").attr("checked") == "checked") {
                        jQuery("#" + startIndex).css("visibility", "visible");
                        jQuery("#" + destIndex).css("visibility", "visible");

                        tmp = jsPlumbInstance.connect(
                            {
                                source:endpoints[startIndex + "__" + destValue[0]],
                                target:endpoints[destIndex + "__input"],
                                endpointStyle:{
                                    outlineColor:"white",
                                    outlineWidth:1,
                                    lineWidth:1
                                },
                                paintStyle:getConnectorStatStyle(destValue[3], destValue[4]),
                                scope: 'onlineConnection',
                                connector:[
                                    "Bezier",
                                    {
                                        curviness:70,
                                        margin:10,
                                        proximityLimit :150
                                    }
                                ],
                                overlays:[]
                        });
                    }
                }

                if(jQuery("#show_percents").attr("checked") == "checked" && tmp != false) {
                    tmp.addOverlay(getStatistikOverlay(destValue[1], destValue[2]));
                }

            }

        });
    });
    jQuery("#loadingScreen").hide();
}


function refreshData() {
    jQuery("#loadingScreen").show();

    var params = {
        module: 'Workflow2',
        action: 'StatistikRefresh',
        parent: 'Settings',
        dataType: 'json',
        workflow_id : workflow_id,
        startDate : jQuery("#statistik_from").val(),
        endDate : jQuery("#statistik_to").val()
    };

    AppConnector.request(params).then(function(tmp) {
            var response = tmp['result'];
            startDate = response["startDate"];
            endDate = response["endDate"];
            jQuery("#rangeDisplayContainer").html('<h2 class="statistikWorkflowTitle">' + workflow_data.title + '</h2>' + response["displayRange"]);
            overlayStatistik = response["overlay"];
            readOverlayStatistik();

            connectionStatistik = response["data"];
            readConnectionStatistik();
        },
        "json"
    );
}

var getConnectorStatStyle = function(color, lineWidth) {
    if(typeof lineWidth == 'undefined') {
        lineWidth = 2;
    }
    return {
        gradient:{stops:[[0, color], [1, color]]},
        lineWidth:lineWidth,
        strokeStyle:"#000",
        dashstyle:"2 0"
    }
};

function drawBlockChart() {
    var plot1 = jQuery.jqplot ('durationBlock', [durations], {
        // Give the plot a title.
        title: 'Runtime of this Block',
        highlighter: {
            show: true,
            sizeAdjust: 7.5,
            tooltipAxes:"y",
            formatString:'%.0f ms'
          },
        cursor: {
            show: false
          },
        // You can specify options for all axes on the plot at once with
        // the axesDefaults object.  Here, we're using a canvas renderer
        // to draw the axis label which allows rotated text.
        axesDefaults: {
            labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer,
            tickRenderer: jQuery.jqplot.CanvasAxisTickRenderer
        },
        // An axes object holds options for all axes.
        // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
        // Up to 9 y axes are supported.
        axes: {
            // options for each axis are specified in seperate option objects.
            xaxis: {
                pad: 0,
                tickInterval:"1"
            },
            yaxis: {

                min:0,
                max:maxValue,
                autoscale:true,
                tickOptions:{
                    formatString:'%.0fms',

                  fontFamily: 'Arial',
                  fontSize: '10px',
                  angle: -30
                },
                label: "Runtime"
            }
        }
    });
}

function openAsPopup(ele) {
    window.open(jQuery(ele).data('url'));
}