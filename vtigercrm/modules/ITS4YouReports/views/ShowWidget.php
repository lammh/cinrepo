<?php

/*+********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

class ITS4YouReports_ShowWidget_View extends Vtiger_IndexAjax_View {

	function checkPermission(Vtiger_Request $request) {
		return true;
	}

	function process(Vtiger_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
//error_reporting(63);ini_set("display_errors",1);
		$moduleName = $request->getModule();
		$componentName = $request->get('name');
		$linkId = $request->get('linkid');
		if(!empty($componentName)) {
			$className = Vtiger_Loader::getComponentClassName('Dashboard', $componentName, $moduleName);
			if(!empty($className)) {
				$widget = NULL;
				if(!empty($linkId)) {
					$widget = new Vtiger_Widget_Model();
					$widget->set('linkid', $linkId);
					$widget->set('userid', $currentUser->getId());
					$widget->set('filterid', $request->get('filterid', NULL));
                    if ($request->has('data')) {
						$widget->set('data', $request->get('data'));
					}
					$widget->add();
				}
				$classInstance = new $className();
				$classInstance->process($request, $widget);
				return;
			}
                }

		$response = new Vtiger_Response();
		$response->setResult(array('success'=>false,'message'=>  vtranslate('NO_DATA')));
		$response->emit();
	}
        
        public function validateRequest(Vtiger_Request $request) { 
            $request->validateWriteAccess(); 
        }
/*        
        function getHeaderScripts(Vtiger_Request $request) {
            $headerScriptInstances = parent::getHeaderScripts($request);
            $moduleName = $request->getModule();
echo "<pre>";print_r($moduleName);echo "</pre>";
            $jsFileNames = array(
                'modules.Vtiger.resources.Vtiger',
                "modules.$moduleName.resources.$moduleName",
            );

            $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
            $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
            return $headerScriptInstances;
	}
*/
}