/*********************************************************************************
 * The content of this file is subject to the Labels 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

jQuery.Class("Settings_ITS4YouLabels_List_Js",{
    
  
    registerSubmitButton : function(){
    jQuery('.btn-success').on('click',function(e){
        var form = jQuery('#langAndModulForm');
        var formData = form.serializeFormData();
            //var editUrl = jQuery(e.currentTarget).find('.icon-pencil').closest('a').attr('href');
            window.location.href = "index.php?module=ITS4YouLabels&parent=Settings&view=Edit&lang="+formData["language"]+"&moduleId="+formData["module"]+"&block="+formData["settings_block"]+"&fieldid="+formData["settings_fieldid"];
            //alert("klikol si"+formData.toSource());
        });    
    } ,   
    registerEvents : function() {
        this.registerSubmitButton();
    }
});