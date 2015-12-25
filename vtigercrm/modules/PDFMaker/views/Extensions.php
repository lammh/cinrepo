<?php

/* * *******************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class PDFMaker_Extensions_View extends Vtiger_Index_View {

    
    public function preProcess(Vtiger_Request $request, $display = true) {
        
        $PDFMaker = new PDFMaker_PDFMaker_Model();
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
        $viewer->assign('QUALIFIED_MODULE', $moduleName);
        Vtiger_Basic_View::preProcess($request, false);
        $viewer = $this->getViewer($request);

        $moduleName = $request->getModule();
        
        $linkParams = array('MODULE' => $moduleName, 'ACTION' => $request->get('view'));
        $linkModels = $PDFMaker->getSideBarLinks($linkParams);
        $viewer->assign('QUICK_LINKS', $linkModels);
        
        $viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
        $viewer->assign('CURRENT_VIEW', $request->get('view'));
        
        if ($display) {
            $this->preProcessDisplay($request);
        }
    }
    
    public function process(Vtiger_Request $request) {
        PDFMaker_Debugger_Model::GetInstance()->Init();

        $adb = PearDatabase::getInstance();
        

        $viewer = $this->getViewer($request);
        
        $extensions = Array();

        $link = "index.php?module=PDFMaker&action=IndexAjax&mode=downloadFile&parenttab=Tools&extid=";
        
        $extname = "CustomerPortal";
        $extensions[$extname]["label"] = "LBL_CUSTOMERPORTAL";
        $extensions[$extname]["desc"] = "LBL_CUSTOMERPORTAL_DESC";
        $extensions[$extname]["exinstall"] = "LBL_CP_EXPRESS_INSTAL_EXT";
        $extensions[$extname]["manual"] = $link.$extname."&type=manual";
        $extensions[$extname]["download"] = $link.$extname."&type=download";
        $extensions[$extname]["install"] = "";
        
        $extname = "Workflow";
        $extensions[$extname]["label"] = "LBL_WORKFLOW";
        $extensions[$extname]["desc"] = "LBL_WORKFLOW_DESC";
        $extensions[$extname]["exinstall"] = "";
        $extensions[$extname]["manual"] = "";
        $extensions[$extname]["download"] = "";
        
        $PDFMaker = new PDFMaker_PDFMaker_Model();
        $control = $PDFMaker->controlWorkflows();
        
        if ($control)
        {
            $extensions[$extname]["install_info"] = vtranslate("LBL_WORKFLOWS_ARE_ALREADY_INSTALLED","PDFMaker");
            $extensions[$extname]["install"] = "";
        } else {
            $extensions[$extname]["install_info"] = "";
            $extensions[$extname]["install"] = $link.$extname."&type=install";
        }   
        
        
 
        $viewer->assign("EXTENSIONS_ARR", $extensions);

        $download_error = $request->get('download_error');   
        if (isset($download_error) && $download_error != "") { 
            $viewer->assign("ERROR", "true");
        }
        
        $viewer->view('Extensions.tpl', 'PDFMaker');         
    }
    
    
    
    function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

        $jsFileNames = array(
            'modules.Vtiger.resources.Vtiger',
            "modules.$moduleName.resources.$moduleName",
            'modules.PDFMaker.resources.Extensions',
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }
}     