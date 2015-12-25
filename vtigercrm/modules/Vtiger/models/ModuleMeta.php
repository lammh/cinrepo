<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Vtiger_ModuleMeta_Model extends Vtiger_Base_Model {

	var $moduleName = false;
	
	var $webserviceMeta = false;

	var $user;

	static $_cached_module_meta;

	/**
	 * creates an instance of Vtiger_ModuleMeta_Model
	 * @param <String> $name - module name
	 * @param <Object> $user - Users Object
	 * @return Vtiger_ModuleMeta_Model
	 */
	public static function getInstance($name, $user) {

global $log;
$log->debug("Entering ./models/ModuleMeta.php::staticgetInstance");
		$self = new Vtiger_ModuleMeta_Model();
		$self->moduleName = $name;
		$self->user = $user;

		if(!empty(self::$_cached_module_meta[$name][$user->id])) {
			$self->webserviceMeta = self::$_cached_module_meta[$name][$user->id];
			return $self;
		}

		$handler = vtws_getModuleHandlerFromName($self->moduleName, $user);
		$self->webserviceMeta = $handler->getMeta();
		self::$_cached_module_meta[$name][$user->id] = $self->webserviceMeta;
		return $self;
	}

	/**
	 * Functions returns webservices meta object
	 * @return webservices meta
	 */
	public function getMeta() {

global $log;
$log->debug("Entering ./models/ModuleMeta.php::getMeta");
		return $this->webserviceMeta;
	}

	/**
	 * Function returns list of fields based on type
	 * @param <type> $type
	 * @return <type>
	 */
	public function getFieldListByType($type) {

global $log;
$log->debug("Entering ./models/ModuleMeta.php::getFieldListByType");
		$meta = $this->getMeta();
		return $meta->getFieldListByType($type);
	}

	/**
	 * Function returns accessible fields in a module
	 * @return <Array of Vtiger_Field>
	 */
	public function getAccessibleFields() {

global $log;
$log->debug("Entering ./models/ModuleMeta.php::getAccessibleFields");

		$meta = self::$_cached_module_meta[$this->moduleName][$this->user->id];
		$moduleFields = $meta->getModuleFields();
		$accessibleFields = array();
		foreach($moduleFields as $fieldName => $fieldInstance) {
			if($fieldInstance->getPresence() === 1) {
				continue;
			}
			$accessibleFields[$fieldName] = $fieldInstance;
		}
		return $accessibleFields;
	}

	/**
	 * Function returns mergable fields in the module
	 * @return <Array of Vtiger_field>
	 */
	public function getMergableFields() {

global $log;
$log->debug("Entering ./models/ModuleMeta.php::getMergableFields");
		$accessibleFields = $this->getAccessibleFields($this->moduleName);
		$mergableFields = array();
		foreach($accessibleFields as $fieldName => $fieldInstance) {
			if($fieldInstance->getPresence() === 1) {
				continue;
			}
			// We need to avoid Last Modified by or any such User reference field
			// for now as Query Generator is not handling it well enough.
			// The case in which query generator is failing to generate right query is,
			// Assigned User field is not there either in the selected fields list or in the conditions
			// and condition is added on the User reference field
			// TODO - Cleanup this once Query Generator support is corrected
			if($fieldInstance->getFieldDataType() == 'reference') {
				$referencedModules = $fieldInstance->getReferenceList();
				if($referencedModules[0] == 'Users') {
					continue;
				}
			}
			$mergableFields[$fieldName] = $fieldInstance;
		}
		return $mergableFields;
	}

	/**
	 * Function returns mandatory importable fields
	 * @return <Array of Vtiger_Field>
	 */
	public function getMandatoryImportableFields() {

global $log;
$log->debug("Entering ./models/ModuleMeta.php::getMandatoryImportableFields");

		$focus = CRMEntity::getInstance($this->moduleName);
		if(method_exists($focus, 'getMandatoryImportableFields')) {
			$mandatoryFields = $focus->getMandatoryImportableFields();
		} else {
			$moduleFields = $this->getAccessibleFields($this->moduleName);
			$mandatoryFields = array();
			foreach($moduleFields as $fieldName => $fieldInstance) {
				if($fieldInstance->isMandatory() && $fieldInstance->getFieldDataType() != 'owner'
						&& $this->isEditableField($fieldInstance)) {
					$mandatoryFields[$fieldName] = $fieldInstance->getFieldLabelKey();
				}
			}
		}
		return $mandatoryFields;
	}

	/**
	 * Function returns importable fields
	 * @return <Array of Vtiger_Field>
	 */
	public function getImportableFields() {

global $log;
$log->debug("Entering ./models/ModuleMeta.php::getImportableFields");
		$focus = CRMEntity::getInstance($this->moduleName);
		if(method_exists($focus, 'getImportableFields')) {
			$importableFields = $focus->getImportableFields();
		} else {
			$moduleFields = $this->getAccessibleFields($moduleName);
			$importableFields = array();
			foreach($moduleFields as $fieldName => $fieldInstance) {
				if(($this->isEditableField($fieldInstance)
							&& ($fieldInstance->getTableName() != 'vtiger_crmentity' || $fieldInstance->getColumnName() != 'modifiedby')
						) || ($fieldInstance->getUIType() == '70' && $fieldName != 'modifiedtime')) {
					$importableFields[$fieldName] = $fieldInstance;
				}
			}
		}
		return $importableFields;
	}

	/**
	 * Function returns Entity Name fields
	 * @return <Array of Vtiger_Field>
	 */
	public function getEntityFields() {

global $log;
$log->debug("Entering ./models/ModuleMeta.php::getEntityFields");
		$moduleFields = $this->getAccessibleFields($this->moduleName);
		$entityColumnNames = vtws_getEntityNameFields($this->moduleName);
		$entityNameFields = array();
		foreach($moduleFields as $fieldName => $fieldInstance) {
			$fieldColumnName = $fieldInstance->getColumnName();
			if(in_array($fieldColumnName, $entityColumnNames)) {
				$entityNameFields[$fieldName] = $fieldInstance;
			}
		}
		return $entityNameFields;
	}

	/**
	 * Function checks if the field is editable
	 * @param type $fieldInstance
	 * @return boolean
	 */
	public function isEditableField($fieldInstance) {

global $log;
$log->debug("Entering ./models/ModuleMeta.php::isEditableField");
		if(((int)$fieldInstance->getDisplayType()) === 2 ||
				in_array($fieldInstance->getPresence(), array(1,3)) ||
				strcasecmp($fieldInstance->getFieldDataType(),"autogenerated") ===0 ||
				strcasecmp($fieldInstance->getFieldDataType(),"id") ===0 ||
				$fieldInstance->isReadOnly() == true ||
				$fieldInstance->getUIType() ==  70 ||
				$fieldInstance->getUIType() ==  4) {

			return false;
		}
		return true;
	}

	/**
	 * Function returns list of mandatory fields
	 * @return <Array of Vtiger_Field>
	 */
	public function getMandatoryFields() {

global $log;
$log->debug("Entering ./models/ModuleMeta.php::getMandatoryFields");
		$focus = CRMEntity::getInstance($this->moduleName);
		if(method_exists($focus, 'getMandatoryImportableFields')) {
			$mandatoryFields = $focus->getMandatoryImportableFields();
		} else {
			$moduleFields = $this->getAccessibleFields($this->moduleName);
			$mandatoryFields = array();
			foreach($moduleFields as $fieldName => $fieldInstance) {
				if($fieldInstance->isMandatory()
						&& $fieldInstance->getFieldDataType() != 'owner'
						&& $this->isEditableField($fieldInstance)) {
					$mandatoryFields[$fieldName] = vtranslate($fieldInstance->getFieldLabelKey(), $this->moduleName);
				}
			}
		}
		return $mandatoryFields;
	}
}
