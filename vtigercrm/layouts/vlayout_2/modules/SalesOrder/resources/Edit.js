/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/**　▼ ** START 売上✕マージンや粗利率＝粗利金額の計算と入力フォームへの反映 ******************** ▼ **/
jQuery( function() {

	/** ▼ 紹介料の計算 ******************/
	//紹介利率を入力したとき
	jQuery('#SalesOrder_editView_fieldName_costinfo') . change( function () {
    var margin = totalGrossRate();
    var gross  = calcGross(margin);
	} );

	//売上額を入力したとき
	jQuery('#SalesOrder_editView_fieldName_salesamount') . change( function () {
    var margin = totalGrossRate();
    var gross  = calcGross(margin);
	} );

	//紹介料を入力したとき
	jQuery('#SalesOrder_editView_fieldName_introduction_fee') . change( function () {
    var margin = totalGrossRate();
    var gross  = calcGross(margin);
	} );

	//社外原価
	jQuery('#SalesOrder_editView_fieldName_costall') . change( function () {
    var margin = totalGrossRate();
    var gross  = calcGross(margin);
	} );

	//社内原価
	jQuery('#SalesOrder_editView_fieldName_costsyanai') . change( function () {
    var margin = totalGrossRate();
    var gross  = calcGross(margin);
	} );

	/** ▼ WC のブロック　**/
	//営業粗利率
    jQuery( '#SalesOrder_editView_fieldName_sales_gross' ) . change( function () {
    	var wcGrossRate = totalGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );

    //アド粗利率
    jQuery( '#SalesOrder_editView_fieldName_add_gross' ) . change( function () {
    	var wcGrossRate = totalGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );

    //制作粗利率
    jQuery( '#SalesOrder_editView_fieldName_create_gross' ) . change( function () {
    	var wcGrossRate = totalGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );   

    //逆SEO粗利率
    jQuery( '#SalesOrder_editView_fieldName_rseo_gross' ) . change( function () {
    	var wcGrossRate = totalGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );   

    //SEO粗利率
    jQuery( '#SalesOrder_editView_fieldName_seo_gross' ) . change( function () {
    	var wcGrossRate = totalGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );   

    //風評粗利率
    jQuery( '#SalesOrder_editView_fieldName_rumor_gross' ) . change( function () {
    	var wcGrossRate = totalGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );   

    //ディレクション粗利
    jQuery( '#SalesOrder_editView_fieldName_direction' ) . change( function () {
    	var wcGrossRate = totalGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );
	/** ▲ WC のブロック　**/


    /** ▼ HR のブロック　**/
    //ボーナス
    jQuery( '#SalesOrder_editView_fieldName_bonus' ) . change( function () {
    	var hrMargin = totalGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );

    //基本マージン
    jQuery( '#SalesOrder_editView_fieldName_margin_01' ) . change( function () {
    	var hrMargin = totalGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );

    //バイトル基本マージン
    jQuery( '#SalesOrder_editView_fieldName_margin_02' ) . change( function () {
    	var hrMargin = totalGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );

    //新規マージン
    jQuery( '#SalesOrder_editView_fieldName_margin_03' ) . change( function () {
    	var hrMargin = totalGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );

    //特別マージン
    jQuery( '#SalesOrder_editView_fieldName_margin_04' ) . change( function () {
    	var hrMargin = totalGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );

    //リピートマージン
    jQuery( '#SalesOrder_editView_fieldName_margin_05' ) . change( function () {
    	var hrMargin = totalGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );
		
		//割引
  	jQuery( '#SalesOrder_editView_fieldName_hr_discount' ) . change( function () {
    	var hrMargin = totalGrossRate();
    	var hrGross  = calcGross(hrMargin);
  	} );

  	//マージン率合計
  	jQuery( '#SalesOrder_editView_fieldName_margin_rate_total' ) . change( function () {
    	var hrMargin = totalGrossRate();
    	var hrGross  = calcGross(hrMargin);
  	} );
    /** ▲ HR のブロック　**/

	/** ▼  SC のブロック **/
	//マージン率
    jQuery( '#SalesOrder_editView_fieldName_margin_rate' ) . change( function () {
    	var sc_margin_rate = totalGrossRate();
	  	console.log("SCマージン : ");
	  	console.log(sc_margin_rate);
      calcGross(sc_margin_rate);
    } );
	/** ▲  SC のブロック　**/


	function totalGrossRate() {

	  var margin_rate_total = hr_margin_rate_total();

	  //営業粗利やSEO粗利、制作粗利については原価や紹介には入らず粗利には計算されない
//	  var sales_gross = parseFloat($('#SalesOrder_editView_fieldName_sales_gross').val());
//    if(isNaN(sales_gross)) sales_gross = 0;
//	  var margin_rate = parseFloat($('#SalesOrder_editView_fieldName_margin_rate').val());
//      if(isNaN(margin_rate)) margin_rate = 0;
//	  var add_gross = parseFloat($('#SalesOrder_editView_fieldName_add_gross').val());
//      if(isNaN(add_gross)) add_gross = 0;
//	  var create_gross = parseFloat($('#SalesOrder_editView_fieldName_create_gross').val());
//      if(isNaN(create_gross)) create_gross = 0;
//	  var rseo_gross = parseFloat($('#SalesOrder_editView_fieldName_rseo_gross').val());
//      if(isNaN(rseo_gross)) rseo_gross = 0;
//	  var seo_gross = parseFloat($('#SalesOrder_editView_fieldName_seo_gross').val());
//      if(isNaN(seo_gross)) seo_gross = 0;
//	  var rumor_gross = parseFloat($('#SalesOrder_editView_fieldName_rumor_gross').val());
//      if(isNaN(rumor_gross)) rumor_gross = 0;
//	  var direction = parseFloat($('#SalesOrder_editView_fieldName_direction').val());
//      if(isNaN(direction)) direction = 0;


//		var sumGrossRate = 
//		  sales_gross + margin_rate + add_gross + create_gross + rseo_gross + seo_gross + rumor_gross + direction 
//			+ margin_rate_total;

//		return sumGrossRate;
		return margin_rate_total;
	}//function

  /** ▼ ** 粗利計算 *********/
  function calcGross(gross_rate) {

    if(isNaN(gross_rate)) gross_rate = 0;

    var salesamount = $('#SalesOrder_editView_fieldName_salesamount').val();
      console.log("売上");
	  salesamount = Number(salesamount.split(",").join(""));
      console.log(salesamount);
      if(isNaN(salesamount)) salesamount = 0;

		console.log("粗利率");
		console.log(gross_rate);

		if(gross_rate !== 0) {//粗利率が0より上のとき
	  	var amount = Math.round(salesamount*(gross_rate/100));
	  } else {
	  	var amount = salesamount;
	  }//End if

	  //割引+ボーナス
	  var hr_discount_bonus = sum_hr_discount_bonus();
      if(isNaN(hr_discount_bonus)) hr_discount_bonus = 0;

	  console.log("HRの割引・ボーナス");
	  console.log(hr_discount_bonus);

	  if(hr_discount_bonus !== 0) {
		  amount += hr_discount_bonus;
		}//End if

		//紹介利率
		var introduction_fee_rate = parseFloat($('#SalesOrder_editView_fieldName_costinfo').val());
		if(isNaN(introduction_fee_rate)) introduction_fee_rate = 0;

		var introduction_rate_fee = salesamount*(introduction_fee_rate/100);
		if(isNaN(introduction_rate_fee)) introduction_rate_fee = 0;

	  console.log("紹介利率");
	  console.log(introduction_fee_rate);

	  console.log("紹介利率での紹介料");
	  console.log(introduction_rate_fee);

		//紹介料
		var introduction_fee = parseInt($('#SalesOrder_editView_fieldName_introduction_fee').val());
		if(isNaN(introduction_fee)) introduction_fee = 0;

		//社外原価
		var costsyanai       = parseInt($('#SalesOrder_editView_fieldName_costsyanai').val());
		if(isNaN(costsyanai)) costsyanai = 0;
	  console.log("社外原価");
	  console.log(costsyanai);

		//社内原価
		var costall          = parseInt($('#SalesOrder_editView_fieldName_costall').val());
		if(isNaN(costall)) costall = 0;
	  console.log("社内原価");
	  console.log(costall);

		//紹介料と売上✕紹介利率
		introduction_fee += introduction_rate_fee;

	  console.log("紹介料と売上✕紹介利率");
	  console.log(introduction_rate_fee);

	  introduction_fee = parseInt(introduction_fee);

	  if(isNaN(introduction_fee)) introduction_fee = 0;

		$('#SalesOrder_editView_fieldName_introduction_fee').val(introduction_fee);

		var cost_fee = (introduction_fee + costsyanai + costall);
      
      	if(isNaN(cost_fee)) cost_fee = 0;

		if(cost_fee !== 0) {
			amount -= cost_fee;
		}//End if

	  console.log("粗利");
	  console.log(amount);



		$('#SalesOrder_editView_fieldName_amount').val(amount);

//		console.log("salesamount : "+salesamount);
//		console.log("gross_rate : "+gross_rate);
//		console.log("amount : "+amount);
	
  }//End function
  /** ▲ ** 粗利計算 *********/

  //割引+ボーナス
  function sum_hr_discount_bonus() {
  	var hr_discount = parseInt($('#SalesOrder_editView_fieldName_hr_discount').val());
		if(isNaN(hr_discount)) hr_discount = 0;
  	var bonus       = parseInt($('#SalesOrder_editView_fieldName_bonus').val());
		if(isNaN(bonus)) bonus = 0;

	  console.log("HR:割引");
	  console.log(hr_discount);

	  console.log("HR:ボーナス");
	  console.log(bonus);

  	var sum_hr_fee = hr_discount + bonus;

	  console.log("関数:HRの割引・ボーナス");
	  console.log(sum_hr_fee);
  	return sum_hr_fee;
  }//End function

  /** ▼ HRのマージン率合計計算 **/
  function hr_margin_rate_total() {
  	var margin_01 = parseFloat($('#SalesOrder_editView_fieldName_margin_01').val());
	if(isNaN(margin_01)) margin_01 = 0;
  	var margin_02 = parseFloat($('#SalesOrder_editView_fieldName_margin_02').val());
	if(isNaN(margin_02)) margin_02 = 0;
  	var margin_03 = parseFloat($('#SalesOrder_editView_fieldName_margin_03').val());
	if(isNaN(margin_03)) margin_03 = 0;
  	var margin_04 = parseFloat($('#SalesOrder_editView_fieldName_margin_04').val());
	if(isNaN(margin_04)) margin_04 = 0;
  	var margin_05 = parseFloat($('#SalesOrder_editView_fieldName_margin_05').val());
	if(isNaN(margin_05)) margin_05 = 0;
	
	console.log(margin_01);
	console.log(margin_02);
	console.log(margin_03);
	console.log(margin_04);
	console.log(margin_05);

  	margin_rate_total = margin_01 + margin_02 + margin_03 + margin_04 + margin_05;

	if(isNaN(margin_rate_total)) margin_rate_total = 0;

		console.log("HR : マージン率合計");
		console.log(margin_rate_total);

		$('#SalesOrder_editView_fieldName_margin_rate_total').val(margin_rate_total);

		return margin_rate_total;

  }//End function
  /** ▲ HRのマージン率合計計算 **/

} );
/**　▲ ** END 売上✕マージンや粗利率＝粗利金額の計算と入力フォームへの反映 ******************** ▲ **/



Inventory_Edit_Js("SalesOrder_Edit_Js",{},{


	
	/**
	 * Function which will register event for Reference Fields Selection
	 */
	registerReferenceSelectionEvent : function(container) {
//	alert("registerReferenceSelectionEvent");
		this._super(container);
		var thisInstance = this;
		
		jQuery('input[name="account_id"]', container).on(Vtiger_Edit_Js.referenceSelectionEvent, function(e, data){
			thisInstance.referenceSelectionEventHandler(data, container);
		});
	},

	/**
	 * Function to get popup params
	 */
	getPopUpParams : function(container) {
	//alert("getPopUpParams");
		var params = this._super(container);
        var sourceFieldElement = jQuery('input[class="sourceField"]',container);

		if(sourceFieldElement.attr('name') == 'contact_id' || sourceFieldElement.attr('name') == 'potential_id') {
			var form = this.getForm();
			var parentIdElement  = form.find('[name="account_id"]');
			if(parentIdElement.length > 0 && parentIdElement.val().length > 0 && parentIdElement.val() != 0) {
				var closestContainer = parentIdElement.closest('td');
				params['related_parent_id'] = parentIdElement.val();
				params['related_parent_module'] = closestContainer.find('[name="popupReferenceModule"]').val();
			} else if(sourceFieldElement.attr('name') == 'potential_id') {
				parentIdElement  = form.find('[name="contact_id"]');
				if(parentIdElement.length > 0 && parentIdElement.val().length > 0) {
					closestContainer = parentIdElement.closest('td');
					params['related_parent_id'] = parentIdElement.val();
					params['related_parent_module'] = closestContainer.find('[name="popupReferenceModule"]').val();
				}
			}
        }
        return params;
    },

	/**
	 * Function to search module names
	 */
	searchModuleNames : function(params) {
//	alert("searchModuleNames");
		var aDeferred = jQuery.Deferred();

		if(typeof params.module == 'undefined') {
			params.module = app.getModuleName();
		}
		if(typeof params.action == 'undefined') {
			params.action = 'BasicAjax';
		}

		if (params.search_module == 'Contacts' || params.search_module == 'Potentials') {
			var form = this.getForm();
			var parentIdElement  = form.find('[name="account_id"]');
			if(parentIdElement.length > 0 && parentIdElement.val().length > 0) {
				var closestContainer = parentIdElement.closest('td');
				params.parent_id = parentIdElement.val();
				params.parent_module = closestContainer.find('[name="popupReferenceModule"]').val();
			} else if(params.search_module == 'Potentials') {
				parentIdElement  = form.find('[name="contact_id"]');
				if(parentIdElement.length > 0 && parentIdElement.val().length > 0) {
					closestContainer = parentIdElement.closest('td');
					params.parent_id = parentIdElement.val();
					params.parent_module = closestContainer.find('[name="popupReferenceModule"]').val();
				}
			}
		}

		AppConnector.request(params).then(
			function(data){
				aDeferred.resolve(data);
			},
			function(error){
				aDeferred.reject();
			}
		)
		return aDeferred.promise();
	},
	
	/**
	 * Function to register event for enabling recurrence
	 * When recurrence is enabled some of the fields need
	 * to be check for mandatory validation
	 */
	registerEventForEnablingRecurrence : function(){
//		alert("registerEventForEnablingRecurrence");
		var thisInstance = this;
		var form = this.getForm();
		var enableRecurrenceField = form.find('[name="enable_recurring"]');
		var fieldsForValidation = new Array('recurring_frequency','start_period','end_period','payment_duration','invoicestatus');
		enableRecurrenceField.on('change',function(e){
			var element = jQuery(e.currentTarget);
			var addValidation;
			if(element.is(':checked')){
				addValidation = true;
			}else{
				addValidation = false;
			}
			
			//If validation need to be added for new elements,then we need to detach and attach validation
			//to form
			if(addValidation){
				form.validationEngine('detach');
				thisInstance.AddOrRemoveRequiredValidation(fieldsForValidation,addValidation);
				//For attaching validation back we are using not using attach,because chosen select validation will be missed
				form.validationEngine(app.validationEngineOptions);
				//As detach is used on form for detaching validationEngine,it will remove any actions on form submit,
				//so events that are registered on form submit,need to be registered again after validationengine detach and attach
				thisInstance.registerSubmitEvent();
			}else{
				thisInstance.AddOrRemoveRequiredValidation(fieldsForValidation,addValidation);
			}
		})
		if(!enableRecurrenceField.is(":checked")){
			thisInstance.AddOrRemoveRequiredValidation(fieldsForValidation,false);
		}else if(enableRecurrenceField.is(":checked")){
			thisInstance.AddOrRemoveRequiredValidation(fieldsForValidation,true);
		}
	},
	
	/**
	 * Function to add or remove required validation for dependent fields
	 */
	AddOrRemoveRequiredValidation : function(dependentFieldsForValidation,addValidation){
//	alert("AddOrRemoveRequiredValidation");
		var form = this.getForm();
		jQuery(dependentFieldsForValidation).each(function(key,value){
			var relatedField = form.find('[name="'+value+'"]');
			if(addValidation){
			//alert("!!!");
				var validationValue = relatedField.attr('data-validation-engine');
				if (typeof validationValue === "undefined") {}
				else{
				
					if(validationValue.indexOf('[f') > 0){
						relatedField.attr('data-validation-engine','validate[required,funcCall[Vtiger_Base_Validator_Js.invokeValidation]]');
					}
					if(relatedField.is("select")){
						relatedField.attr('disabled',false).trigger("liszt:updated");
					}else{
						relatedField.removeAttr('disabled');
					}
				}	
			}else if(!addValidation){
				if(relatedField.is("select")){
					relatedField.attr('disabled',true).trigger("liszt:updated");
				}else{
					relatedField.attr('disabled',"disabled");
				}
				relatedField.validationEngine('hide');
				if(relatedField.is('select') && relatedField.hasClass('chzn-select')){
					var parentTd = relatedField.closest('td');
					parentTd.find('.chzn-container').validationEngine('hide');
				}
			}
		})
	},

	registerEvents: function(){
		//alert("registerEvents");
		this._super();
		this.registerEventForEnablingRecurrence();
		this.registerForTogglingBillingandShippingAddress();
		this.registerEventForCopyAddress();
	}
});


