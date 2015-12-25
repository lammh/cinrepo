<?php

/*+********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

class ITS4YouReports_Folder_Action extends Vtiger_Action_Controller {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('save');
		$this->exposeMethod('delete');
	}

	public function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = ITS4YouReports_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	public function process(Vtiger_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	/**
	 * Function that saves/updates the Folder
	 * @param Vtiger_Request $request
	 */
	function save(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$folderModel = ITS4YouReports_Folder_Model::getInstance();
		$folderId = $request->get('folderid');

		if(!empty($folderId)) {
			$folderModel->set('folderid', $folderId);
		}

		$folderModel->set('foldername', $request->get('foldername'));
		$folderModel->set('description', $request->get('description'));

		if ($folderModel->checkDuplicate()) {
			throw new AppException(vtranslate('LBL_DUPLICATES_EXIST', $moduleName));
		}

		$folderModel->save();
		$result = array('success' => true, 'message' => vtranslate('LBL_FOLDER_SAVED', $moduleName), 'info' => $folderModel->getInfoArray());

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}

	/**
	 * Function that deletes the Folder
	 * @param Vtiger_Request $request
	 */
	function delete(Vtiger_Request $request) {
		$folderId = $request->get('folderid');
		$moduleName = $request->getModule();

		if ($folderId) {
			$folderModel = ITS4YouReports_Folder_Model::getInstanceById($folderId);

			if ($folderModel->isDefault()) {
				throw new AppException(vtranslate('LBL_FOLDER_CAN_NOT_BE_DELETED', $moduleName));
			} else {
				if ($folderModel->hasReports()) {
					throw new AppException(vtranslate('LBL_FOLDER_NOT_EMPTY', $moduleName));
				}
			}

			$folderModel->delete();
			$result = array('success'=>true, 'message'=>vtranslate('LBL_FOLDER_DELETED', $moduleName));

			$response = new Vtiger_Response();
			$response->setResult($result);
			$response->emit();
		}
	}
        
        public function validateRequest(Vtiger_Request $request) { 
            $request->validateWriteAccess(); 
        }
}