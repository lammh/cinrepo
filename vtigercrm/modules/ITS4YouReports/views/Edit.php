<?php

/* +********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

Class ITS4YouReports_Edit_View extends Vtiger_Edit_View {

    function __construct() {
        parent::__construct();
        $this->exposeMethod('editReport');
        $this->exposeMethod('ReportGrouping');
        $this->exposeMethod('ReportColumns');
        $this->exposeMethod('ChangeSteps');
    }

    public function checkPermission(Vtiger_Request $request) {
        $moduleName = $request->getModule();
        $moduleModel = ITS4YouReports_ITS4YouReports_Model::getInstance($moduleName);

        $currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        if (!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
            throw new AppException('LBL_PERMISSION_DENIED');
        }

        $record = $request->get('record');
        if ($record) {
            $reportModel = ITS4YouReports_Record_Model::getCleanInstance($record);
            if (!$reportModel->isEditable()) {
                throw new AppException('LBL_PERMISSION_DENIED');
            }
        }
    }

    public function preProcess(Vtiger_Request $request) {
        $mode = $request->get('mode');

        if ($mode == "ChangeSteps")
            $display = false;
        else
            $display = true;

        parent::preProcess($request, $display);
        $viewer = $this->getViewer($request);
        $record = $request->get('record');

        $reportModel = ITS4YouReports_Record_Model::getCleanInstance($record);
        $primaryModule = $reportModel->getPrimaryModule();
        $primaryModuleModel = Vtiger_Module_Model::getInstance($primaryModule);
        if ($primaryModuleModel) {
            $currentUser = Users_Record_Model::getCurrentUserModel();
            $userPrivilegesModel = Users_Privileges_Model::getInstanceById($currentUser->getId());
            $permission = $userPrivilegesModel->hasModulePermission($primaryModuleModel->getId());

            if (!$permission) {
                $viewer->assign('MODULE', $primaryModule);
                $viewer->assign('MESSAGE', 'LBL_PERMISSION_DENIED');
                $viewer->view('OperationNotPermitted.tpl', $primaryModule);
                exit;
            }
        }

        $viewer->assign('RECORD_MODE', $mode);
    }

    public function process(Vtiger_Request $request) {
        $mode = $request->getMode();
        if (!empty($mode)) {
            echo $this->invokeExposedMethod($mode, $request);
            exit;
        }
        $this->editReport($request);
    }

    public function ChangeSteps(Vtiger_Request $request) {
        $viewer = $this->getViewer($request);
        $step = $request->get("step");

        $viewer->assign('RECORD_MODE', $request->getMode());

        if ($request->has("reporttype") && !$request->isEmpty("reporttype")) {
            $reporttype = $request->get('reporttype');
            $viewer->assign('REPORTTYPE', $reporttype);
        }

        if ($step == "step1") {
            echo ITS4YouReports_EditView_Model::ReportsStep1($request, $viewer);
        } elseif ($step == "step4") {
            echo ITS4YouReports_EditView_Model::ReportGrouping($request, $viewer);
        } elseif ($step == "step5") {
            echo ITS4YouReports_EditView_Model::ReportColumns($request, $viewer);
        } elseif ($step == "step6") {
            echo ITS4YouReports_EditView_Model::ReportColumnsTotal($request, $viewer);
        } elseif ($step == "step7") {
            echo ITS4YouReports_EditView_Model::ReportLabels($request, $viewer);
        } elseif ($step == "step8") {
            echo ITS4YouReports_EditView_Model::ReportFiltersAjax($request, $viewer);
        } elseif ($step == "step9") {
            echo ITS4YouReports_EditView_Model::ReportSharing($request, $viewer);
        } elseif ($step == "step11") {
            echo ITS4YouReports_EditView_Model::ReportGraphs($request, $viewer);
        }
    }

    function editReport(Vtiger_Request $request) {
        $viewer = $this->getViewer($request);
//ITS4YouReports::define_rt_vars(true,true);

//ITS4YouReports::getR4UDifTime(1);

        $moduleName = $request->getModule();
        $record = $request->get('record');

        $reportModel = ITS4YouReports_Record_Model::getCleanInstance($record);
        if (!$reportModel->has('folderid')) {
            $reportModel->set('folderid', $request->get('folder'));
        }
        $data = $request->getAll();
        foreach ($data as $name => $value) {
            $reportModel->set($name, $value);
        }

        if ($request->has("reporttype") && !$request->isEmpty("reporttype")) {
            $reportModel->set('reporttype', $request->get('reporttype'));
        }

        if ($record != "") {
            $viewer->assign('MODE', 'edit');
            $reporttype = $reportModel->getReportType();
        } else {
            $viewer->assign('MODE', 'create');
            $reporttype = $request->get('reporttype');
        }

        $viewer->assign('REPORTTYPE', $reporttype);

        global $current_user;
        $is_admin_user = is_admin($current_user);
        $viewer->assign('IS_ADMIN_USER', $is_admin_user);

        $viewer->assign("steps_display", "reportTab hide");
        //$viewer->assign("steps_display","reportTab");
        /* global $current_user;if($current_user->id=="1"){
          $viewer->assign("steps_display","reportTab");
          //ITS4YouReports::sshow($ReportColumnsTotal);
          } */

        $viewer->assign("cancel_btn_url", $reportModel->getCancelViewUrl());

//ITS4YouReports::getR4UDifTime(2);
        if ($reporttype == "") {
            $viewer->view('ITS4YouReportsType.tpl', $moduleName);
        } else {
            $reportModuleModel = $reportModel->getModule();

            $viewer->assign("REPORTNAME", $reportModel->getName());
            $viewer->assign("REPORTDESC", $reportModel->getDesc());
            $viewer->assign("REP_FOLDERS", $reportModel->getReportFolders());

            $ReportSharing = ITS4YouReports_EditView_Model::ReportSharing($request, $viewer);
            $viewer->assign("REPORT_SHARING", $ReportSharing);

            $ReportScheduler = ITS4YouReports_EditView_Model::ReportScheduler($request, $viewer);
            $viewer->assign("REPORT_SCHEDULER", $ReportScheduler);

//ITS4YouReports::getR4UDifTime(3);
            if ($reporttype == "custom_report") {
                if ($is_admin_user != 1) {
                    ITS4YouReports::DieDuePermission();
                }
//                    ITS4YouReports::sshow($reporttype);
                $ReportCustomSQL = ITS4YouReports_EditView_Model::ReportCustomSql($request, $viewer);
                $viewer->assign("REPORT_CUSTOMSQL", $ReportCustomSQL);

                $viewer->view('EditCustom.tpl', $moduleName);
            } else {

                if ($request->get('isDuplicate')) {
                    $viewer->assign('isDuplicate', 'true');
                }
                $viewer->assign("PRIMARYMODULES", $reportModel->getPrimaryModules());
                
//ITS4YouReports::getR4UDifTime(4);
                $ReportGrouping = ITS4YouReports_EditView_Model::ReportGrouping($request, $viewer);
                $viewer->assign("REPORT_GROUPING", $ReportGrouping);

//ITS4YouReports::getR4UDifTime(5);
                $ReportColumns = ITS4YouReports_EditView_Model::ReportColumns($request, $viewer);
                $viewer->assign("REPORT_COLUMNS", $ReportColumns);

//ITS4YouReports::getR4UDifTime(6);
                $ReportColumnsTotal = ITS4YouReports_EditView_Model::ReportColumnsTotal($request, $viewer);
                $viewer->assign("REPORT_COLUMNS_TOTAL", $ReportColumnsTotal);

//ITS4YouReports::getR4UDifTime(7);
                $ReportLabels = ITS4YouReports_EditView_Model::ReportLabels($request, $viewer);
                $viewer->assign("REPORT_LABELS", $ReportLabels);

//ITS4YouReports::getR4UDifTime(8);
                $ReportFilters = ITS4YouReports_EditView_Model::ReportFilters($request, $viewer);
                $viewer->assign("REPORT_FILTERS", $ReportFilters);

//ITS4YouReports::getR4UDifTime(9);
                $ReportGraphs = ITS4YouReports_EditView_Model::ReportGraphs($request, $viewer);
                $viewer->assign("REPORT_GRAPHS", $ReportGraphs);
//ITS4YouReports::getR4UDifTime(10);
//exit;
                $viewer->view('Edit.tpl', $moduleName);
            }
        }
    }

    /**
     * Function to get the list of Script models to be included
     * @param Vtiger_Request $request
     * @return <Array> - List of Vtiger_JsScript_Model instances
     */
    function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

        $jsFileNames = array(
            "modules.$moduleName.resources.Edit",
            "modules.$moduleName.resources.ITS4YouReports",
                //"modules.$moduleName.resources.Edit2",
                //"modules.$moduleName.resources.Edit3"
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }

}
