/*********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

//Vtiger_Widget_Js('Vtiger_Getreports_Widget_Js',{},{
/*
jQuery.Class("Vtiger_Getreports_Widget_Js",{
    self: false,
    getInstance: function() {
        if (this.self != false) {
            return this.self;
        }
        this.self = new Vtiger_Getreports_Widget_Js();
//        this.self.registerInstallEvents();
        return this.self;
    },
    
    postLoadWidget: function() {
alert("hura tu som 0")
         this._super();
        var thisInstance = this;

		this.getContainer().on('jqplotDataClick', function(ev, gridpos, datapos, neighbor, plot) {
                        var jData = thisInstance.getContainer().find('.widgetData').val();
			var data = JSON.parse(jData);
			var linkUrl = data[datapos]['links'];
			if(linkUrl) window.location.href = linkUrl;
		});

		this.getContainer().on("jqplotDataHighlight", function(evt, seriesIndex, pointIndex, neighbor) {
			$('.jqplot-event-canvas').css( 'cursor', 'pointer' );
		});
		this.getContainer().on("jqplotDataUnhighlight", function(evt, seriesIndex, pointIndex, neighbor) {
			$('.jqplot-event-canvas').css( 'cursor', 'auto' );
		});
    },

} , {

	generateChartData : function() {
		var container = this.getContainer();

		var jData = container.find('.widgetData').val();
alert(jData);
		var data = JSON.parse(jData);

		var chartData = [];
		var xLabels = new Array();
		var yMaxValue = 0;
		for(var index in data) {
			var row = data[index];
			row[0] = parseInt(row[0]);
			xLabels.push(app.getDecodedValue(row[1]))
			chartData.push(row[0]);
			if(parseInt(row[0]) > yMaxValue){
				yMaxValue = parseInt(row[0]);
			}
		}
        // yMaxValue Should be 25% more than Maximum Value
		yMaxValue = yMaxValue + 2 + (yMaxValue/100)*25;
		return {'chartData':[chartData], 'yMaxValue':yMaxValue, 'labels':xLabels};
	},
    
     postLoadWidget: function() {
alert("hura tu som")
         this._super();
        var thisInstance = this;

		this.getContainer().on('jqplotDataClick', function(ev, gridpos, datapos, neighbor, plot) {
                        var jData = thisInstance.getContainer().find('.widgetData').val();
			var data = JSON.parse(jData);
			var linkUrl = data[datapos]['links'];
			if(linkUrl) window.location.href = linkUrl;
		});

		this.getContainer().on("jqplotDataHighlight", function(evt, seriesIndex, pointIndex, neighbor) {
			$('.jqplot-event-canvas').css( 'cursor', 'pointer' );
		});
		this.getContainer().on("jqplotDataUnhighlight", function(evt, seriesIndex, pointIndex, neighbor) {
			$('.jqplot-event-canvas').css( 'cursor', 'auto' );
		});
    },

	loadChart : function() {
		var data = this.generateChartData();
alert("nononono")
                this.getPlotContainer(false).jqplot(data['chartData'] , {
			title: data['title'],
			animate: !$.jqplot.use_excanvas,
			seriesDefaults:{
				renderer:jQuery.jqplot.BarRenderer,
				rendererOptions: {
					showDataLabels: true,
					dataLabels: 'value',
					barDirection : 'vertical'
				},
				pointLabels: {show: true,edgeTolerance: -15}
			},
			 axes: {
				xaxis: {
					  tickRenderer: jQuery.jqplot.CanvasAxisTickRenderer,
					  renderer: jQuery.jqplot.CategoryAxisRenderer,
					  ticks: data['labels'],
					  tickOptions: {
						angle: -45
					  }
				},
				yaxis: {
					min:0,
					max: data['yMaxValue'],
					tickOptions: {
						formatString: '%d'
					},
					pad : 1.2
				}
			},
			legend: {
                show		: (data['data_labels']) ? true:false,
                location	: 'e',
                placement	: 'outside',
				showLabels	: (data['data_labels']) ? true:false,
				showSwatch	: (data['data_labels']) ? true:false,
				labels		: data['data_labels']
            }
		});
//		this.getPlotContainer(false).on('jqPlotDataClick', function(){
//			console.log('here');
//		});
//		jQuery.jqplot.eventListenerHooks.push(['jqPlotDataClick', myClickHandler]);
	}

//	registerSectionClick : function() {
//		this.getPlotContainer(false);
//	}
});

var Vtiger_Getreports_Widget_Js  = Vtiger_Getreports_Widget_Js.getInstance();
*/

Vtiger_Widget_Js('Vtiger_Getreports_Widget_Js',{},{

	generateChartData : function() {
		var container = this.getContainer();

		var jData = container.find('.widgetData').val();
		var data = JSON.parse(jData);

		var chartData = [];
		var xLabels = new Array();
		var yMaxValue = 0;
		for(var index in data) {
			var row = data[index];
			row[0] = parseInt(row[0]);
			xLabels.push(app.getDecodedValue(row[1]))
			chartData.push(row[0]);
			if(parseInt(row[0]) > yMaxValue){
				yMaxValue = parseInt(row[0]);
			}
		}
        // yMaxValue Should be 25% more than Maximum Value
		yMaxValue = yMaxValue + 2 + (yMaxValue/100)*25;
		return {'chartData':[chartData], 'yMaxValue':yMaxValue, 'labels':xLabels};
	},
    
     postLoadWidget: function() {
        this._super();
        var thisInstance = this;
        
        this.getContainer().on('jqplotDataClick', function(ev, gridpos, datapos, neighbor, plot) {
                var jData = thisInstance.getContainer().find('.widgetData').val();
                var data = JSON.parse(jData);
                var linkUrl = data[datapos]['links'];
                if(linkUrl) window.location.href = linkUrl;
        });
        
        this.getContainer().on("jqplotDataHighlight", function(evt, seriesIndex, pointIndex, neighbor) {
                $('.jqplot-event-canvas').css( 'cursor', 'pointer' );
        });
        this.getContainer().on("jqplotDataUnhighlight", function(evt, seriesIndex, pointIndex, neighbor) {
                $('.jqplot-event-canvas').css( 'cursor', 'auto' );
        });
    },

	loadChart : function() {
            $(function () {
                /*
                $('#reports4you_widget_'+jQuery('#widgetReports4YouId').val()).highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Monthly Average Rainfall'
                    },
                    subtitle: {
                        text: 'Source: WorldClimate.com'
                    },
                    xAxis: {
                        categories: [
                            'Jan',
                            'Feb',
                            'Mar',
                            'Apr',
                            'May',
                            'Jun',
                            'Jul',
                            'Aug',
                            'Sep',
                            'Oct',
                            'Nov',
                            'Dec'
                        ]
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Rainfall (mm)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: 'Tokyo',
                        data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

                    }, {
                        name: 'New York',
                        data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

                    }, {
                        name: 'London',
                        data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

                    }, {
                        name: 'Berlin',
                        data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

                    }]
                });
                */
            });
/*
		var data = this.generateChartData();

                this.getPlotContainer(false).jqplot(data['chartData'] , {
			title: data['title'],
			animate: !$.jqplot.use_excanvas,
			seriesDefaults:{
				renderer:jQuery.jqplot.BarRenderer,
				rendererOptions: {
					showDataLabels: true,
					dataLabels: 'value',
					barDirection : 'vertical'
				},
				pointLabels: {show: true,edgeTolerance: -15}
			},
			 axes: {
				xaxis: {
					  tickRenderer: jQuery.jqplot.CanvasAxisTickRenderer,
					  renderer: jQuery.jqplot.CategoryAxisRenderer,
					  ticks: data['labels'],
					  tickOptions: {
						angle: -45
					  }
				},
				yaxis: {
					min:0,
					max: data['yMaxValue'],
					tickOptions: {
						formatString: '%d'
					},
					pad : 1.2
				}
			},
			legend: {
                show		: (data['data_labels']) ? true:false,
                location	: 'e',
                placement	: 'outside',
				showLabels	: (data['data_labels']) ? true:false,
				showSwatch	: (data['data_labels']) ? true:false,
				labels		: data['data_labels']
            }
		});
                */
//		this.getPlotContainer(false).on('jqPlotDataClick', function(){
//			console.log('here');
//		});
//		jQuery.jqplot.eventListenerHooks.push(['jqPlotDataClick', myClickHandler]);
	}

//	registerSectionClick : function() {
//		this.getPlotContainer(false);
//	}
});

/****

jQuery.Class("ITS4YouReports_License_Js", {
        self: false,
        getInstance: function() {
            if (this.self != false) {
                return this.self;
            }
            this.self = new ITS4YouReports_License_Js();
            this.self.registerInstallEvents();
            return this.self;
        },

    /**
        * Function to get the advance filter values
        * This will call the base to get the values and dont send group condition if there is not condition
        * exists in the next condition group
        *
        * @params cleanGroupConditions <Boolean> - states whether to clean group conditions or not -- default true
        *   this will remove group condition if next condition group dont have any conditions
        * /
       getValues : function (cleanGroupConditions) {

               if(typeof cleanGroupConditions == 'undefined'){
                       cleanGroupConditions = true;
               }

               var values = this._super();

               if(!cleanGroupConditions) {
                       return values;
               }

               for(var key in values){
                       var conditionGroupInfo = values[key];
                       var nextConditionGroupInfo = values[parseInt(key)+1]

                       //there is not next condition group so no need to perform the caliculation
                       if(typeof nextConditionGroupInfo == 'undefined'){
                               continue;
                       }
                       var nextConditionColumns = nextConditionGroupInfo['columns'];

                       // if you dont have conditions in next group we should not send group condition in current condition group
                       if(jQuery.isEmptyObject(nextConditionColumns)){
                               delete conditionGroupInfo['condition']
                       }
               }
               return values;
       }
}, {
    initialize: function() {
    },

    editLicense : function($type) {
        var aDeferred = jQuery.Deferred();
        var thisInstance = this;

        var progressIndicatorElement = jQuery.progressIndicator({
            'position' : 'html',
            'blockInfo' : {
                    'enabled' : true
            }
        });

        var license_key = jQuery('#license_key_val').val();
        url = "index.php?module=ITS4YouReports&view=IndexAjax&mode=editLicense&type="+$type+"&key="+license_key;

        AppConnector.request(url).then(
            function(data) {
                var callBackFunction = function(data) {
                        //cache should be empty when modal opened 
                        var form = jQuery('#editLicense');

                        var params = app.validationEngineOptions;
                        params.onValidationComplete = function(form, valid){
                                if(valid) {

                                        thisInstance.saveLicenseKey(form,false);
                                        return valid;
                                }
                        }
                        form.validationEngine(params);

                        form.submit(function(e) {
                                e.preventDefault();
                        })
                }

                progressIndicatorElement.progressIndicator({'mode':'hide'});
                app.showModalWindow(data,function(data){
                        if(typeof callBackFunction == 'function'){
                                callBackFunction(data);
                        }
                }, {'width':'500px'});
            },
            function(error) {
                //TODO : Handle error
                aDeferred.reject(error);
            }
        );
        return aDeferred.promise();
    },
    /*
    * Function to Save the CustomLabel Details
    * /

    saveLicenseKey : function(form,is_install) {
            var thisInstance = this;
            if (is_install)
            {
                var licensekey_val = jQuery('#licensekey').val()
                //params = "index.php?module=ITS4YouReports&action=License&mode=editLicense&type=activate&licensekey="+licensekey_val;
                params = {};
                params.module = "ITS4YouReports";
                params.licensekey = licensekey_val;
                params.action = "License";
                params.mode = "editLicense";
                params.type = "activate"; 
                //params.dataType='json';

            }
            else
            {
                if(typeof params == 'undefined' ) {
                    params = {};
                }

                var params = form.serializeFormData();
            }    

            thisInstance.validateLicenseKey(params).then(
                    function(data) {

                        if (!is_install) 
                        {
                            app.hideModalWindow();

                            var params = {
                                    text: app.vtranslate(data['message'])
                            };
                            thisInstance.showMessage(params);

                            jQuery('#license_key_val').val(data.licensekey);
                            jQuery('#license_key_label').html(data.licensekey);

                            jQuery('#divgroup1').hide();
                            jQuery('#divgroup2').show();
                        }   
                        else
                        {
                            jQuery('#step1').hide();
                            jQuery('#step2').show();

                            jQuery('#steplabel1').removeClass("active");
                            jQuery('#steplabel2').addClass("active");
                        }    
                    },
                    function(data,err) {
                    }
            );
    },


    saveCustomLabelValues : function(form) {
        var thisInstance = this;
        var params = form.serializeFormData();

        if(typeof params == 'undefined' ) {
            params = {};
        }

        var progressIndicatorElement = jQuery.progressIndicator({
            'position' : 'html',
            'blockInfo' : {
                'enabled' : true
            }
        });

        params.module = app.getModuleName();
        params.action = 'IndexAjax';
        params.mode = 'SaveCustomLabelValues';

        AppConnector.request(params).then(
            function(data) {
                progressIndicatorElement.progressIndicator({'mode':'hide'});
                app.hideModalWindow();

                var params = {
                        text: app.vtranslate(data)
                };
                thisInstance.showMessage(params);
            }
        );

    },

    validateLicenseKey : function(data) {

        var thisInstance = this;
        var aDeferred = jQuery.Deferred();

        var form = jQuery('#editLicense');
        var CustomLabelElement = form.find('[name="licensekey"]');

            thisInstance.checkLicenseKey(data).then(
                function(data){
                    aDeferred.resolve(data);
                },
                function(data, err){
                    CustomLabelElement.validationEngine('showPrompt', data['message'] , 'error','bottomLeft',true);
                    aDeferred.reject(data);
                }
            );

        return aDeferred.promise();

    },

    /*
     * Function to check Duplication of Tax Name
     * /

checkLicenseKey : function(params) {
            var aDeferred = jQuery.Deferred();
            /*
             var progressIndicatorElement = jQuery.progressIndicator({
                'position' : 'html',
                'blockInfo' : {
                        'enabled' : true
                }
            });
            * /
//alert(JSON.stringify(params));
            AppConnector.request(params).then(
                    function(data) {
                            var response = data['result'];
                            var result = response['success'];

                            if(result == true) {
                                aDeferred.resolve(response);
                            } else {

                                aDeferred.reject(response);
                            }
                    },
                    function(error,err){
                            aDeferred.reject();
                    }
            );

            //progressIndicatorElement.progressIndicator({'mode':'hide'});
            return aDeferred.promise();
    },

    registerActions : function() {

        var thisInstance = this;
            var container = jQuery('#LicenseContainer');

            jQuery('#activate_license_btn').click(function(e) {
                    thisInstance.editLicense('activate');
            });

            jQuery('#reactivate_license_btn').click(function(e) {
                    thisInstance.editLicense('reactivate');
            });

            jQuery('#deactivate_license_btn').click(function(e) {
                    thisInstance.deactivateLicense();
            });


    },

    deactivateLicense: function() {

        var progressIndicatorElement = jQuery.progressIndicator({
            'position' : 'html',
            'blockInfo' : {
                    'enabled' : true
            }
        });


        var license_key = jQuery('#license_key_val').val();
        var deactivateActionUrl = 'index.php?module=ITS4YouReports&action=License&mode=deactivateLicense&key='+license_key;
        AppConnector.request(deactivateActionUrl + '&type=control').then(
                        function(data) {
                            if (data.success == true) {
                                progressIndicatorElement.progressIndicator({'mode':'hide'});
                                if (data.result.success)
                                {
                                    var message = app.vtranslate('LBL_DEACTIVATE_QUESTION','ITS4YouReports');
                                    Vtiger_Helper_Js.showConfirmationBox({'message': message}).then(function(data) {

                                        var progressIndicatorElement = jQuery.progressIndicator({
                                            'position' : 'html',
                                            'blockInfo' : {
                                                    'enabled' : true
                                            }
                                        });
                                        AppConnector.request(deactivateActionUrl).then(
                                        function(data2) {

                                            if (data2.result.success == true) {
                                                var params2 = {
                                                text: data2.result.deactivate,
                                                type: 'info'
                                                };

                                                jQuery('#license_key_val').val("");
                                                jQuery('#license_key_label').html("");

                                                jQuery('#divgroup1').show();
                                                jQuery('#divgroup2').hide();
                                            } else {
                                                var params2 = {
                                                title : app.vtranslate('JS_ERROR'),
                                                text: data2.result.deactivate,
                                                type: 'error'
                                                };
                                            }
                                            progressIndicatorElement.progressIndicator({'mode':'hide'});
                                            Vtiger_Helper_Js.showMessage(params2);
                                        });
                                    },
                                        function(error, err) {
                                            progressIndicatorElement.progressIndicator({'mode':'hide'});
                                        }
                                    );
                                }
                                else
                                {    
                                    var params = {
                                    title : app.vtranslate('JS_ERROR'),
                                    text: data.result.deactivate,
                                    type: 'error'
                                    };
                                    Vtiger_Helper_Js.showMessage(params);
                                }




                            } else {
                                progressIndicatorElement.progressIndicator({'mode':'hide'});
                                Vtiger_Helper_Js.showPnotify(data.error.message);
                            }
                        });
    },

    registerEvents: function() {
            this.registerActions();
    },

    registerInstallEvents: function() {
        var thisInstance = this;

        this.registerInstallActions();

        var form = jQuery('#editLicense');
        var params = app.validationEngineOptions;
        params.onValidationComplete = function(form, valid){
            if(valid) {
                thisInstance.saveLicenseKey(form,true);
                return valid;
            }
        }
        form.validationEngine(params);
        form.submit(function(e) {
                e.preventDefault();
        })
    },

    registerInstallActions : function() {
        var thisInstance = this;

        jQuery('#download_button').click(function(e) {
                thisInstance.downloadMPDF();
        });

        jQuery('#next_button').click(function(e) {
                window.location.href = "index.php?module=ITS4YouReports&view=List";
        });


    },

     downloadMPDF : function() {

         var progressIndicatorElement = jQuery.progressIndicator({
            'position' : 'html',
            'blockInfo' : {
                    'enabled' : true
            }
        });

        var url = "index.php?module=ITS4YouReports&action=IndexAjax&mode=downloadMPDF"; 
        AppConnector.request(url).then(
            function(data) {

                progressIndicatorElement.progressIndicator({'mode':'hide'});

                var response = data['result'];
                var result = response['success'];

                if(result == true) {

                    jQuery('#step2').hide();
                    jQuery('#step3').show();

                    jQuery('#steplabel2').removeClass("active");
                    jQuery('#steplabel3').addClass("active");


                } else {
                    alert(response['message']); 
                    var params = {
                                text: app.vtranslate(response['message'])
                        };
                    Vtiger_Helper_Js.showPnotify(params);
                }
            },
            function(error,err){
                progressIndicatorElement.progressIndicator({'mode':'hide'});
            }
        );
     },

    showMessage : function(customParams){
            var params = {};
            params.animation = "show";
            params.type = 'info';
            params.title = app.vtranslate('JS_MESSAGE');

            if(typeof customParams != 'undefined') {
                    var params = jQuery.extend(params,customParams);
            }
            Vtiger_Helper_Js.showPnotify(params);
    }
});
//if(jQuery('#currentView').val()=='List'){
//    var ITS4YouReports_License_Js  = ITS4YouReports_License_Js.getInstance();
//} 
 */