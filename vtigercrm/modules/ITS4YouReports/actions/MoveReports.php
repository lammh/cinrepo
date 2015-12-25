<?php

/*+********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

class ITS4YouReports_MoveReports_Action extends Vtiger_Mass_Action {

	public function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = ITS4YouReports_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Vtiger_Request $request) {
		$parentModule = 'ITS4YouReports';
		$reportIdsList = ITS4YouReports_Record_Model::getRecordsListFromRequest($request);
		$folderId = $request->get('folderid');

		if (!empty ($reportIdsList)) {
			foreach ($reportIdsList as $reportId) {
				$reportModel = ITS4YouReports_Record_Model::getInstanceById($reportId);
                                $reportModel->setId($reportId);
				if (!$reportModel->isDefault() && $reportModel->isEditable()) {
                                    $return = $reportModel->move($folderId);
				} else {
					$reportsMoveDenied[] = vtranslate($reportModel->getName(), $parentModule);
				}
			}
		}
		$response = new Vtiger_Response();
		if (empty ($reportsMoveDenied)) {
			$response->setResult(array(vtranslate('LBL_REPORTS_MOVED_SUCCESSFULLY', $parentModule)));
		} else {
			$response->setError($reportsMoveDenied, vtranslate('LBL_DENIED_REPORTS', $parentModule));
		}

		$response->emit();
	}
}