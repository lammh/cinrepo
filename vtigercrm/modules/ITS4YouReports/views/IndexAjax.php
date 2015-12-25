<?php

/*+********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

class ITS4YouReports_IndexAjax_View extends Vtiger_Index_View {

	function __construct() {
		parent::__construct();
		//$this->exposeMethod('showActiveRecords');
                $this->exposeMethod('showSettingsList');
                $this->exposeMethod('editLicense');
	}

	function preProcess(Vtiger_Request $request) {
		return true;
	}

	function postProcess(Vtiger_Request $request) {
		return true;
	}

	function process(Vtiger_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	/*
	 * Function to show the recently modified or active records for the given module
	 */
	function showActiveRecords(Vtiger_Request $request) {
            $viewer = $this->getViewer($request);
            $moduleName = $request->getModule();

            $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
            $recentRecords = $moduleModel->getRecentRecords();
            $viewer->assign('MODULE', $moduleName);
            $viewer->assign('RECORDS', $recentRecords);
            echo $viewer->view('RecordNamesList.tpl', $moduleName, true);
	}
        
        function showSettingsList(Vtiger_Request $request){
            //$ITS4YouReports = new ITS4YouReports_ITS4YouReports_Model();
            $viewer = $this->getViewer($request);
            $moduleName = $request->getModule();
            
            $moduleModel = Vtiger_Module_Model::getInstance($moduleName);

            $viewer->assign('MODULE', $moduleName);

            $linkParams = array('MODULE' => $moduleName, 'ACTION' => $request->get('view'), 'MODE' => $request->get('mode'));
            $linkModels = $moduleModel->getSideBarLinks($linkParams);
            $viewer->assign('QUICK_LINKS', $linkModels);
                    
            $parent_view = $request->get('pview');

            $viewer->assign('CURRENT_PVIEW', $parent_view);

            echo $viewer->view('SettingsList.tpl', $moduleName, true);

        }

	function getRecordsListFromRequest(Vtiger_Request $request) {
		$cvId = $request->get('cvid');
		$selectedIds = $request->get('selected_ids');
		$excludedIds = $request->get('excluded_ids');

		if(!empty($selectedIds) && $selectedIds != 'all') {
			if(!empty($selectedIds) && count($selectedIds) > 0) {
				return $selectedIds;
			}
		}

		$customViewModel = CustomView_Record_Model::getInstanceById($cvId);
		if($customViewModel) {
            $searchKey = $request->get('search_key');
            $searchValue = $request->get('search_value');
            $operator = $request->get('operator');
            if(!empty($operator)) {
                $customViewModel->set('operator', $operator);
                $customViewModel->set('search_key', $searchKey);
                $customViewModel->set('search_value', $searchValue);
            }
			return $customViewModel->getRecordIds($excludedIds);
		}
	}
            
        function editLicense(Vtiger_Request $request) {

            $ITS4YouReports = new ITS4YouReports_ITS4YouReports_Model();

            $viewer = $this->getViewer($request);

            $moduleName = $request->getModule();

            $type = $request->get('type');
            $viewer->assign("TYPE", $type);

            $key = $request->get('key');
            $viewer->assign("LICENSEKEY", $key);

            echo $viewer->view('EditLicense.tpl', 'ITS4YouReports', true);
        }
        
}