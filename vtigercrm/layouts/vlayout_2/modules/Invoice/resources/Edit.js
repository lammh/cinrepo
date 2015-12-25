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
    //紹介利率
    jQuery('#Invoice_editView_fieldName_costinfo') . change( function () {

        //紹介料の計算と反映
        introductionReflection();

    } );

    //売上額
    jQuery('#Invoice_editView_fieldName_salesamount') . change( function () {

        //紹介料の計算と反映
        introductionReflection();

    } );

    /** START 紹介料の計算と反映 ******/
    function introductionReflection() {

        //紹介利率を変数に格納
        var costinfo = parseInt($('#Invoice_editView_fieldName_costinfo').val());

        costinfo = costinfo/100;
        //売上を変数に格納
        var salesamount = $('#Invoice_editView_fieldName_salesamount').val();
        salesamount = parseInt(salesamount.split(",").join(""));
        //紹介先(外部)
        var introduction_customers = $('#introduction_customers_display').val();
        //紹介料
        var introduction_fee;
        //粗利
        var amount;
        //紹介料の計算
        introduction_fee = parseInt(salesamount*costinfo);

        //紹介料の反映
        $('#Invoice_editView_fieldName_introduction_fee').val(introduction_fee);

        //粗利の計算
        amount = salesamount-introduction_fee
        //粗利の反映
        $('#Invoice_editView_fieldName_amount').val(amount);

        console.log("デバッグ : "+amount);

    }//End function
    /** END 紹介料の計算と反映 ******/


    jQuery( '#Invoice_editView_fieldName_subject' ) . change( function () {
        var str = jQuery( this ) . val();
        alert(str);
    } );

	/** ▼  SC のブロック **/
	//マージン率
    jQuery( '#Invoice_editView_fieldName_margin_rate' ) . change( function () {
        var margin = parseInt(jQuery( this ) . val());

        margin = margin/100;
        
        calcGross(margin);

    } );
	/** ▲  SC のブロック　**/

	/** ▼ WC のブロック　**/
	//営業粗利率
    jQuery( '#Invoice_editView_fieldName_sales_gross' ) . change( function () {
    	var wcGrossRate = totalWcGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );

    //アド粗利率
    jQuery( '#Invoice_editView_fieldName_add_gross' ) . change( function () {
    	var wcGrossRate = totalWcGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );

    //制作粗利率
    jQuery( '#Invoice_editView_fieldName_create_gross' ) . change( function () {
    	var wcGrossRate = totalWcGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );   

    //逆SEO粗利率
    jQuery( '#Invoice_editView_fieldName_rseo_gross' ) . change( function () {
    	var wcGrossRate = totalWcGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );   

    //SEO粗利率
    jQuery( '#Invoice_editView_fieldName_seo_gross' ) . change( function () {
    	var wcGrossRate = totalWcGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );   

    //風評粗利率
    jQuery( '#Invoice_editView_fieldName_rumor_gross' ) . change( function () {
    	var wcGrossRate = totalWcGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );   

    //ディレクション粗利
    jQuery( '#Invoice_editView_fieldName_direction' ) . change( function () {
    	var wcGrossRate = totalWcGrossRate();
    	var gross = calcGross(wcGrossRate);
    } );
	/** ▲ WC のブロック　**/


    /** ▼ HR のブロック　**/
    //ボーナス
    jQuery( '#Invoice_editView_fieldName_bonus' ) . change( function () {
    	var hrMargin = totalHrGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );

    //基本マージン
    jQuery( '#Invoice_editView_fieldName_margin_01' ) . change( function () {
    	var hrMargin = totalHrGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );

    //バイトル基本マージン
    jQuery( '#Invoice_editView_fieldName_margin_02' ) . change( function () {
    	var hrMargin = totalHrGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );

    //新規マージン
    jQuery( '#Invoice_editView_fieldName_margin_03' ) . change( function () {
    	var hrMargin = totalHrGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );

    //特別マージン
    jQuery( '#Invoice_editView_fieldName_margin_04' ) . change( function () {
    	var hrMargin = totalHrGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );

    //リピートマージン
    jQuery( '#Invoice_editView_fieldName_margin_05' ) . change( function () {
    	var hrMargin = totalHrGrossRate();
    	var hrGross  = calcGross(hrMargin);
    } );
    /** ▲ HR のブロック　**/

    /** ▼ ** HRのマージンの合計 *********/
    function totalHrGrossRate() {
    	//ボーナス
    	var bonus = parseInt($('#Invoice_editView_fieldName_bonus').val());
		if(isNaN(bonus)) bonus = 0;

		//基本マージン
    	var margin_01 = parseInt($('#Invoice_editView_fieldName_margin_01').val());
		if(isNaN(margin_01)) margin_01 = 0;

		//バイトル基本マージン
		var margin_02 = parseInt($('#Invoice_editView_fieldName_margin_02').val());
		if(isNaN(margin_02)) margin_02 = 0;

		//新規マージン
		var margin_03 = parseInt($('#Invoice_editView_fieldName_margin_03').val());
		if(isNaN(margin_03)) margin_03 = 0;

		//特別マージン
		var margin_04 = parseInt($('#Invoice_editView_fieldName_margin_04').val());
		if(isNaN(margin_04)) margin_04 = 0;

		//リピートマージン
		var margin_05 = parseInt($('#Invoice_editView_fieldName_margin_05').val());
		if(isNaN(margin_05)) margin_05 = 0;

		//マージンの合算
		var sumHrMargin = bonus+margin_01+margin_02+margin_03+margin_04+margin_05;
		//100で割る
		sumHrMargin = sumHrMargin/100;

    	return sumHrMargin;
    }//End function
    /** ▲ ** HRのマージンの合計 *********/

    /** ▼ ** WSの粗利率の合計 *********/
    function totalWcGrossRate() {

    	//営業粗利率
     	var salesGrossRate = parseInt($('#Invoice_editView_fieldName_sales_gross').val());
     	if(isNaN(salesGrossRate)) salesGrossRate = 0;

     	//アド粗利率
    	var addGross       = parseInt($('#Invoice_editView_fieldName_add_gross').val());
     	if(isNaN(addGross)) addGross = 0;

     	//制作粗利率
    	var createGross    = parseInt($('#Invoice_editView_fieldName_create_gross').val());
     	if(isNaN(createGross)) createGross = 0;

     	//逆SEO粗利率
    	var rseoGross      = parseInt($('#Invoice_editView_fieldName_rseo_gross').val());
     	if(isNaN(rseoGross)) rseoGross = 0;

     	//SEO粗利率
    	var seoGross       = parseInt($('#Invoice_editView_fieldName_seo_gross').val());
     	if(isNaN(seoGross)) seoGross = 0;

     	//風評粗利率
    	var rumorGross     = parseInt($('#Invoice_editView_fieldName_rumor_gross').val());
     	if(isNaN(rumorGross)) rumorGross = 0;

     	//ディレクション粗利
    	var firectionGross = parseInt($('#Invoice_editView_fieldName_direction').val());
     	if(isNaN(firectionGross)) firectionGross = 0;

     	//粗利率の合算
    	var sumGrossRate = parseInt(salesGrossRate+addGross+createGross+rseoGross+seoGross+rumorGross+firectionGross);

    	//100で割る
		sumGrossRate = sumGrossRate/100;

    	return sumGrossRate;

    }//End function
    /** ▲ ** WSの粗利率の合計 *********/

    /** ▼ ** 粗利計算 *********/
    function calcGross(grossRate) {
        var salesamount = $('#Invoice_editView_fieldName_salesamount').val();

        if(salesamount == "") {
        	salesamount = 0;
        	amount      = 0;
        } else {
	        salesamount = parseInt(salesamount.split(",").join(""));
	        var amount = Math.round(salesamount*grossRate);

		    $('#Invoice_editView_fieldName_amount').val(amount);

        }//End if

//        alert("salesamount : "+salesamount);
//   	alert("amount : "+amount);

    }//End function
    /** ▲ ** 粗利計算 *********/

} );
/**　▲ ** END 売上✕マージンや粗利率＝粗利金額の計算と入力フォームへの反映 ******************** ▲ **/



Inventory_Edit_Js("Invoice_Edit_Js",{},{

	/**
	 * Function which will register event for Reference Fields Selection
	 */
	registerReferenceSelectionEvent : function(container) {
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
		var params = this._super(container);
        var sourceFieldElement = jQuery('input[class="sourceField"]',container);

		if(sourceFieldElement.attr('name') == 'contact_id') {
			var form = this.getForm();
			var parentIdElement  = form.find('[name="account_id"]');
			if(parentIdElement.length > 0 && parentIdElement.val().length > 0 && parentIdElement.val() != 0) {
				var closestContainer = parentIdElement.closest('td');
				params['related_parent_id'] = parentIdElement.val();
				params['related_parent_module'] = closestContainer.find('[name="popupReferenceModule"]').val();
			}
        }
        return params;
    },

	/**
	 * Function to search module names
	 */
	g : function(params) {
		var aDeferred = jQuery.Deferred();

		if(typeof params.module == 'undefined') {
			params.module = app.getModuleName();
		}
		if(typeof params.action == 'undefined') {
			params.action = 'BasicAjax';
		}

		if (params.search_module == 'Contacts') {
			var form = this.getForm();
			var parentIdElement  = form.find('[name="account_id"]');
			if(parentIdElement.length > 0 && parentIdElement.val().length > 0) {
				var closestContainer = parentIdElement.closest('td');
				params.parent_id = parentIdElement.val();
				params.parent_module = closestContainer.find('[name="popupReferenceModule"]').val();
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

	registerEvents: function(){
		this._super();
		this.registerForTogglingBillingandShippingAddress();
		this.registerEventForCopyAddress();
	}
});


