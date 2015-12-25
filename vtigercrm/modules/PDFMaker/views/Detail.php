<?php

/* * *******************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class PDFMaker_Detail_View extends Vtiger_Index_View {

    public function preProcess(Vtiger_Request $request, $display = true) {
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
        $viewer->assign('QUALIFIED_MODULE', $moduleName);
        Vtiger_Basic_View::preProcess($request, false);
        $viewer = $this->getViewer($request);

        $moduleName = $request->getModule();
        if (!empty($moduleName)) {
            //$moduleModel = PDFMaker_PDFMaker_Model::getInstance($moduleName);
            $moduleModel = new PDFMaker_PDFMaker_Model('PDFMaker');
            $currentUser = Users_Record_Model::getCurrentUserModel();
            $userPrivilegesModel = Users_Privileges_Model::getInstanceById($currentUser->getId());
            $permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
            $viewer->assign('MODULE', $moduleName);

            if (!$permission) {
                $viewer->assign('MESSAGE', 'LBL_PERMISSION_DENIED');
                $viewer->view('OperationNotPermitted.tpl', $moduleName);
                exit;
            }

            $linkParams = array('MODULE' => $moduleName, 'ACTION' => $request->get('view'));
            $linkModels = $moduleModel->getSideBarLinks($linkParams);

            $viewer->assign('QUICK_LINKS', $linkModels);
        }

        $viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
        $viewer->assign('CURRENT_VIEW', $request->get('view'));
        if ($display) {
            $this->preProcessDisplay($request);
        }
    }
    
    public function process(Vtiger_Request $request) {
        PDFMaker_Debugger_Model::GetInstance()->Init();

        $PDFMaker = new PDFMaker_PDFMaker_Model();
        if ($PDFMaker->CheckPermissions("DETAIL") == false)
            $PDFMaker->DieDuePermission();

        $viewer = $this->getViewer($request);

        if ($request->has('templateid') && !$request->isEmpty('templateid')) {
            $pdftemplateResult = $PDFMaker->GetDetailViewData($_REQUEST['templateid']);

            $viewer->assign("FILENAME", $pdftemplateResult["filename"]);
            $viewer->assign("DESCRIPTION", $pdftemplateResult["description"]);
            $viewer->assign("TEMPLATEID", $pdftemplateResult["templateid"]);
            $viewer->assign("MODULENAME", getTranslatedString($pdftemplateResult["module"]));
            $viewer->assign("BODY", decode_html($pdftemplateResult["body"]));
            $viewer->assign("HEADER", decode_html($pdftemplateResult["header"]));
            $viewer->assign("FOOTER", decode_html($pdftemplateResult["footer"]));

            $viewer->assign("IS_ACTIVE", $pdftemplateResult["is_active"]);
            $viewer->assign("IS_DEFAULT", $pdftemplateResult["is_default"]);
            $viewer->assign("ACTIVATE_BUTTON", $pdftemplateResult["activateButton"]);
            $viewer->assign("DEFAULT_BUTTON", $pdftemplateResult["defaultButton"]);
        }

        $version_type = $PDFMaker->GetVersionType();

        $viewer->assign("VERSION", $version_type . " " . PDFMaker_Version_Helper::$version);

        if ($pdftemplateResult["permissions"]["edit"]) {
        //if ($PDFMaker->CheckPermissions("EDIT")) {
            $viewer->assign("EXPORT", "yes");
        }
        
        if ($PDFMaker->GetVersionType() != "deactivate") {
            if ($pdftemplateResult["permissions"]["edit"]) {
            //if ($PDFMaker->CheckPermissions("EDIT") && $PDFMaker->GetVersionType() != "deactivate") {
                $viewer->assign("EDIT", "permitted");
                $viewer->assign("IMPORT", "yes");
            }

            if ($pdftemplateResult["permissions"]["delete"]) {
                $viewer->assign("DELETE", "permitted");
            }
        }
        //$tool_buttons = Button_Check('PDFMaker');
        //$viewer->assign('CHECK', $tool_buttons);

        $category = getParentTab();
        $viewer->assign("CATEGORY", $category);
        $viewer->view('Detail.tpl', 'PDFMaker');
    } 
    
    function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

        $jsFileNames = array(
            "layouts.vlayout.modules.PDFMaker.resources.Detail"
        );
        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }
}