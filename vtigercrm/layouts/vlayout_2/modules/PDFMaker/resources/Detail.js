/*********************************************************************************
 * The content of this file is subject to the EMAIL Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

jQuery.Class("PDFMaker_Detail_Js",{
    changeActiveOrDefault : function(templateid, type){
        if (templateid != ""){
            var url = 'index.php?module=PDFMaker&action=IndexAjax&mode=ChangeActiveOrDefault&templateid='+ templateid + '&subjectChanged=' + type;
            AppConnector.request(url).then(function(data){
                location.reload(true);
            });
        }
    }
    },{

    registerEvents : function(){
        var thisInstance = this;
    }
});  