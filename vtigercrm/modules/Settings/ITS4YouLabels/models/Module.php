<?php

/*********************************************************************************
 * The content of this file is subject to the Labels 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

require_once 'modules/com_vtiger_workflow/include.inc';
require_once 'modules/com_vtiger_workflow/expression_engine/VTExpressionsManager.inc';

class Settings_ITS4YouLabels_Module_Model extends Settings_Vtiger_Module_Model {

	var $baseTable = 'its4you_labels';
	var $baseIndex = 'id';
	var $listFields = array();
	var $name = 'ITS4YouLabels';

        static $metaVariables = array(
		'Current Date' => '(general : (__VtigerMeta__) date) ($_DATE_FORMAT_)',
		'Current Time' => '(general : (__VtigerMeta__) time)',
		'System Timezone' => '(general : (__VtigerMeta__) dbtimezone)',
		'User Timezone' => '(general : (__VtigerMeta__) usertimezone)',
		'CRM Detail View URL' => '(general : (__VtigerMeta__) crmdetailviewurl)',
		'Portal Detail View URL' => '(general : (__VtigerMeta__) portaldetailviewurl)',
		'Site Url' => '(general : (__VtigerMeta__) siteurl)',
		'Portal Url' => '(general : (__VtigerMeta__) portalurl)',
		'Record Id' => '(general : (__VtigerMeta__) recordId)',
		'LBL_HELPDESK_SUPPORT_NAME' => '(general : (__VtigerMeta__) supportName)',
		'LBL_HELPDESK_SUPPORT_EMAILID' => '(general : (__VtigerMeta__) supportEmailid)',
	);
	/**
	 * Function to get the url for default view of the module
	 * @return <string> - url
	 */
	public static function getDefaultUrl() {
		return 'index.php?module=ITS4YouLabels&parent=Settings&view=List';
	}

	/**
	 * Function to get the url for create view of the module
	 * @return <string> - url
	 */
	public static function getCreateViewUrl() {
		return "javascript:Settings_ITS4YouLabels_List_Js.triggerCreate('index.php?module=ITS4YouLabels&parent=Settings&view=Edit')";
	}

	public static function getCreateRecordUrl() {
		return 'index.php?module=ITS4YouLabels&parent=Settings&view=Edit';
	}

	public static function getSupportedModules() {
		$moduleModels = Vtiger_Module_Model::getAll(array(0,2));
		$supportedModuleModels = array();
		foreach($moduleModels as $tabId => $moduleModel) {
			if($moduleModel->isEntityModule() && $moduleModel->getName() != 'Webmails') {
				$supportedModuleModels[$tabId] = $moduleModel;
			}
		}
		return $supportedModuleModels;
	}

        public static function getExpressions() {
		$db = PearDatabase::getInstance();

		$mem = new VTExpressionsManager($db);
		return $mem->expressionFunctions();
	}

	public static function getMetaVariables() {
		return self::$metaVariables;
	}
        
	public function getListFields() {
		if(!$this->listFieldModels) {
			$fields = $this->listFields;
			$fieldObjects = array();
			foreach($fields as $fieldName => $fieldLabel) {
				if($fieldName == 'module_name' || $fieldName == 'execution_condition') {
					$fieldObjects[$fieldName] = new Vtiger_Base_Model(array('name' => $fieldName, 'label' => $fieldLabel, 'sort'=>false));
				} else {
					$fieldObjects[$fieldName] = new Vtiger_Base_Model(array('name' => $fieldName, 'label' => $fieldLabel));
				}
			}
			$this->listFieldModels = $fieldObjects;
		}
		return $this->listFieldModels;
	}
}
