/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery( function() {

  //売上額を入力したとき
  jQuery('#Potentials_editView_fieldName_salesamount') . change( function () {
    getGrossCost(); //粗利処理
  } );

  //社外原価を入力したとき
  jQuery('#Potentials_editView_fieldName_costall') . change( function () {
    getGrossCost(); //粗利処理
  } );

  //社内原価を入力したとき
  jQuery('#Potentials_editView_fieldName_costsyanai') . change( function () {
    getGrossCost(); //粗利処理
  } );

  //紹介料を入力したとき
  jQuery('#Potentials_editView_fieldName_costinfo') . change( function () {
    getGrossCost(); //粗利処理
  } );

  //粗利率を入力したとき
  jQuery('#Potentials_editView_fieldName_gross_margin') . change( function () {
    getGrossRate(); //粗利処理
  } );

  //案件名を入力したとき
  jQuery('#Potentials_editView_fieldName_potentialname') . change( function () {
    console.log('fdsa');
    var baitai = $('[name=baitai]').val();  //媒体名
    if(baitai){
      baitai = ' '+baitai;
    } else {
      baitai = '';
    }//End if
//    console.log(baitai);
    var planname = $('#Potentials_editView_fieldName_planname').val();  //プラン名
    if(planname) {
      planname = ' '+planname;
    } else {
      planname = '';
    }//End if
//    console.log(planname);
    var potentialname = $('#Potentials_editView_fieldName_potentialname').val();  //案件名
    $('#Potentials_editView_fieldName_potentialname').val(potentialname+baitai+planname); //案件名
  } );  

  //媒体を選択したとき
  jQuery('[name=baitai]') . change( function () {
//    var potentialname = $('#Potentials_editView_fieldName_potentialname').val();  //案件名
//    if(!potentialname) {
//      sessionStorage.removeItem('potentialname_value');
//    }//End if
    inputPotentialName(); //ヨミ案件自動反映
  } );

  //プランを入力したとき
  jQuery('#Potentials_editView_fieldName_planname') . change( function () {
    inputPotentialName(); //ヨミ案件自動反映
  } );

  //ヨミ案件自動反映
  function inputPotentialName() {
    var baitai = $('[name=baitai]').val();  //媒体名
    if(baitai) baitai = ' '+baitai;
    var planname = $('#Potentials_editView_fieldName_planname').val();  //プラン名
    if(planname) planname = ' '+planname;
    var potentialname = $('#Potentials_editView_fieldName_potentialname').val();  //案件名
//    var potentialname = sessionStorage.getItem('potentialname_value');
//    if(!potentialname) potentialname = "";
    $('#Potentials_editView_fieldName_potentialname').val(potentialname+baitai+planname); //案件名
  }//End function

  //粗利率処理
  function getGrossRate() {

    var salesamount = $('#Potentials_editView_fieldName_salesamount').val();  //売上額
    salesamount = Number(salesamount.split(",").join(""));
    if(isNaN(salesamount)) salesamount = 0; //数字じゃなかったら0にする

    var gross_margin = $('#Potentials_editView_fieldName_gross_margin').val();  //粗利率
    gross_margin = Number(gross_margin.split(",").join(""));
    if(isNaN(gross_margin)) gross_margin = 0; //数字じゃなかったら0にする

    amount = salesamount*(gross_margin/100); //粗利計算
    $('#Potentials_editView_fieldName_amount').val(amount); //粗利反映

  }//End function

  //粗利処理
  function getGrossCost() {

    var salesamount = $('#Potentials_editView_fieldName_salesamount').val();  //売上額
    salesamount = Number(salesamount.split(",").join(""));
    if(isNaN(salesamount)) salesamount = 0; //数字じゃなかったら0にする

    var costall     = $('#Potentials_editView_fieldName_costall').val(); //社外原価
    costall = Number(costall.split(",").join(""));
    if(isNaN(costall)) costall = 0; //数字じゃなかっtら0にする

    var costsyanai  = $('#Potentials_editView_fieldName_costsyanai').val();  //社内原価
    costsyanai = Number(costsyanai.split(",").join(""));
    if(isNaN(costsyanai)) costsyanai = 0; //数字じゃなかっtら0にする

    var introduction_fee = $('#Potentials_editView_fieldName_costinfo').val(); //紹介料
    introduction_fee = Number(introduction_fee.split(",").join(""));
    if(isNaN(introduction_fee)) introduction_fee = 0; //数字じゃなかっtら0にする

    amount = salesamount-(costall+costsyanai+introduction_fee); //粗利計算
    var amount = Math.round(amount);
    $('#Potentials_editView_fieldName_amount').val(amount); //粗利反映

  }//End function

} );












Vtiger_Edit_Js("Potentials_Edit_Js",{ },{
    
    
    /**
	 * Function to get popup params
	**/
    getPopUpParams : function(container) {
        var params = this._super(container);
        var sourceFieldElement = jQuery('input[class="sourceField"]',container);

        if(sourceFieldElement.attr('name') == 'contact_id' ) {
        
            var form = this.getForm();
            var parentIdElement  = form.find('[name="related_to"]');
        
            if(parentIdElement.length > 0 && parentIdElement.val().length > 0 && parentIdElement.val() != 0) {
                var closestContainer = parentIdElement.closest('td');
                params['related_parent_id'] = parentIdElement.val();
                params['related_parent_module'] = closestContainer.find('[name="popupReferenceModule"]').val();
            }
        }
     
        return params;
    },
    
    
    registerEvents: function(){
        this._super();
		
    }

});
