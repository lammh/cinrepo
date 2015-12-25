<?php

/* * *******************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class PDFMaker_ProductBlocks_View extends Vtiger_Index_View {

    function checkPermission(Vtiger_Request $request) {
            $currentUserModel = Users_Record_Model::getCurrentUserModel();
            if(!$currentUserModel->isAdminUser()) {
                    throw new AppException(vtranslate('LBL_PERMISSION_DENIED', 'Vtiger'));
            }
    }
    
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
        
        $PDFMaker = new PDFMaker_PDFMaker_Model();

        $viewer = $this->getViewer($request);

        $currentLanguage = Vtiger_Language_Handler::getLanguage();
  
        $sql = "SELECT * FROM vtiger_pdfmaker_productbloc_tpl";
        $result = $adb->pquery($sql,array());
        while ($row = $adb->fetchByAssoc($result)) {
            $templates[$row["id"]]["name"] = $row["name"];
            $templates[$row["id"]]["body"] = html_entity_decode($row["body"], ENT_QUOTES);
        }
        $viewer->assign("PB_TEMPLATES", $templates);    

        $viewer->view('ProductBlocks.tpl', 'PDFMaker');

    }
}   