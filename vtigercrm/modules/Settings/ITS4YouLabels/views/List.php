<?php
/*********************************************************************************
 * The content of this file is subject to the Labels 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

class Settings_ITS4YouLabels_List_View extends Settings_Vtiger_Index_View {

    public function process(Vtiger_Request $request) {
        
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
        $qualifiedModuleName = $request->getModule(false);
        $settings_block = $request->get('block');
        $settings_fieldid = $request->get('fieldid');
                
        $viewer->assign('LANGUAGES', Vtiger_Language_Handler::getAllLanguages());
        $viewer->assign('ALL_MODULES', Vtiger_Module_Model::getEntityModules());
        $viewer->assign('MODULE', $moduleName);
        $viewer->assign('SETTINGS_BLOCK', $settings_block);
        $viewer->assign('SETTINGS_FIELDID', $settings_fieldid);
        
        echo $viewer->view('List.tpl', $qualifiedModuleName,true);
    }
    
    function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

        $jsFileNames = array(
            'modules.Vtiger.resources.Vtiger',
            'modules.Settings.Vtiger.resources.Vtiger',
            "modules.Settings.$moduleName.resources.List",
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }
}