<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Vtiger_Relation_Model extends Vtiger_Base_Model{
	protected $parentModule = false;
	protected $relatedModule = false;
	protected $relationType = false;

	//one to many
	const RELATION_DIRECT = 1;

	//Many to many and many to one
	const RELATION_INDIRECT = 2;
	
	/**
	 * Function returns the relation id
	 * @return <Integer>
	 */
	public function getId(){
		return $this->get('relation_id');
	}

	/**
	 * Function sets the relation's parent module model
	 * @param <Vtiger_Module_Model> $moduleModel
	 * @return Vtiger_Relation_Model
	 */
	public function setParentModuleModel($moduleModel){
        global $log;
		$this->parentModule = $moduleModel;
		return $this;
	}

	/**
	 * Function that returns the relation's parent module model
	 * @return <Vtiger_Module_Model>
	 */
	public function getParentModuleModel(){
        global $log;
		if(empty($this->parentModule)){
			$this->parentModule = Vtiger_Module_Model::getInstance($this->get('tabid'));
		}
		return $this->parentModule;
	}

	public function getRelationModuleModel(){
        global $log;
		if(empty($this->relatedModule)){
			$this->relatedModule = Vtiger_Module_Model::getInstance($this->get('related_tabid'));
		}
		return $this->relatedModule;
	}
    
    public function getRelationModuleName() {
        global $log;
        $relationModuleName = $this->get('relatedModuleName');
        if(!empty($relationModuleName)) {
            return $relationModuleName;
        }
        return $this->getRelationModuleModel()->getName();
    }

	public function getListUrl($parentRecordModel) {
        global $log;
		return 'module='.$this->getParentModuleModel()->get('name').'&relatedModule='.$this->get('modulename').
            '&view=Detail&record='.$parentRecordModel->getId().'&mode=showRelatedList';
	}

	public function setRelationModuleModel($relationModel){
        global $log;
		$this->relatedModule = $relationModel;
		return $this;
	}

	public function isActionSupported($actionName){
        global $log;
		$actionName = strtolower($actionName);
		$actions = $this->getActions();
		foreach($actions as $action) {
			if(strcmp(strtolower($action), $actionName)== 0){
				return true;
			}
		}
		return false;
	}

	public function isSelectActionSupported() {
        global $log;
		return $this->isActionSupported('select');
	}

	public function isAddActionSupported() {
        global $log;
		return $this->isActionSupported('add');
	}

	public function getActions(){
        global $log;
		$actionString = $this->get('actions');
		$label = $this->get('label');
		// No actions for Activity history
		if($label == 'Activity History') {
			return array();
		}

		return explode(',', $actionString);
	}

	public function getQuery($parentRecord, $actions=false){
        global $log;
		$parentModuleModel = $this->getParentModuleModel();
		$relatedModuleModel = $this->getRelationModuleModel();
		$parentModuleName = $parentModuleModel->getName();
		$relatedModuleName = $relatedModuleModel->getName();
		$functionName = $this->get('name');
		$query = $parentModuleModel->getRelationQuery($parentRecord->getId(), $functionName, $relatedModuleModel);

		return $query;
	}

	public function addRelation($sourcerecordId, $destinationRecordId) {
        global $log;
		$sourceModule = $this->getParentModuleModel();
		$sourceModuleName = $sourceModule->get('name');
		$sourceModuleFocus = CRMEntity::getInstance($sourceModuleName);
		$destinationModuleName = $this->getRelationModuleModel()->get('name');
		relateEntities($sourceModuleFocus, $sourceModuleName, $sourcerecordId, $destinationModuleName, $destinationRecordId);
	}

	public function deleteRelation($sourceRecordId, $relatedRecordId){
        global $log;
		$sourceModule = $this->getParentModuleModel();
		$sourceModuleName = $sourceModule->get('name');
		$destinationModuleName = $this->getRelationModuleModel()->get('name');
		$destinationModuleFocus = CRMEntity::getInstance($destinationModuleName);
		DeleteEntity($destinationModuleName, $sourceModuleName, $destinationModuleFocus, $relatedRecordId, $sourceRecordId);
		return true;
	}

	public function isDirectRelation() {
        global $log;
		return ($this->getRelationType() == self::RELATION_DIRECT);
	}

	public function getRelationType(){
        global $log;
		if(empty($this->relationType)){
			$this->relationType = self::RELATION_INDIRECT;
			if ($this->getRelationField()) {
				$this->relationType = self::RELATION_DIRECT;
			}
		}
		return $this->relationType;
	}
    
    /**
     * Function which will specify whether the relation is editable
     * @return <Boolean>
     */
    public function isEditable() {
        global $log;
        return $this->getRelationModuleModel()->isPermitted('EditView');
    }
    
    /**
     * Function which will specify whether the relation is deletable
     * @return <Boolean>
     */
    public function isDeletable() {
        global $log;
        return $this->getRelationModuleModel()->isPermitted('Delete');
    }

	public static function getInstance($parentModuleModel, $relatedModuleModel, $label=false) {
        global $log;
        $log->debug("Entering ./models/Relation.php::staticgetInstance");
		$db = PearDatabase::getInstance();
		$query = 'SELECT vtiger_relatedlists.*,vtiger_tab.name as modulename FROM vtiger_relatedlists
					INNER JOIN vtiger_tab on vtiger_tab.tabid = vtiger_relatedlists.related_tabid AND vtiger_tab.presence != 1
					WHERE vtiger_relatedlists.tabid = ? AND related_tabid = ?';
		$params = array($parentModuleModel->getId(), $relatedModuleModel->getId());

		if(!empty($label)) {
			$query .= ' AND label = ?';
			$params[] = $label;
		}
		
		$result = $db->pquery($query, $params);
		if($db->num_rows($result)) {
			$row = $db->query_result_rowdata($result, 0);
			$relationModelClassName = Vtiger_Loader::getComponentClassName('Model', 'Relation', $parentModuleModel->get('name'));
			$relationModel = new $relationModelClassName();
			$relationModel->setData($row)->setParentModuleModel($parentModuleModel)->setRelationModuleModel($relatedModuleModel);
            $log->debug("Exiting ./models/Relation.php::staticgetInstance");
			return $relationModel;
		}
        $log->debug("Exiting ./models/Relation.php::staticgetInstance");
		return false;
	}

	public static function getAllRelations($parentModuleModel, $selected = true, $onlyActive = true) {
        global $log;
        $log->debug("Entering ./models/Relation.php::staticgetAllRelations");
		$db = PearDatabase::getInstance();
		$skipReltionsList = array('get_history');
        $query = 'SELECT vtiger_relatedlists.*,vtiger_tab.name as modulename FROM vtiger_relatedlists 
                    INNER JOIN vtiger_tab on vtiger_relatedlists.related_tabid = vtiger_tab.tabid
                    WHERE vtiger_relatedlists.tabid = ? AND related_tabid != 0';

		if ($selected) {
			$query .= ' AND vtiger_relatedlists.presence <> 1';
		}
        if($onlyActive){
            $query .= ' AND vtiger_tab.presence <> 1 ';
        }
        $query .= ' AND vtiger_relatedlists.name NOT IN ('.generateQuestionMarks($skipReltionsList).') ORDER BY sequence'; // TODO: Need to handle entries that has related_tabid 0
        $result = $db->pquery($query, array($parentModuleModel->getId(), $skipReltionsList));
		$relationModels = array();
		$relationModelClassName = Vtiger_Loader::getComponentClassName('Model', 'Relation', $parentModuleModel->get('name'));
		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
			//$relationModuleModel = Vtiger_Module_Model::getCleanInstance($moduleName);
			// Skip relation where target module does not exits or is no permitted for view.
			if (!Users_Privileges_Model::isPermitted($row['modulename'],'DetailView')) {
				continue;
			}
			$relationModel = new $relationModelClassName();
			$relationModel->setData($row)->setParentModuleModel($parentModuleModel)->set('relatedModuleName',$row['modulename']);
			$relationModels[] = $relationModel;
		}
        $log->debug("Exiting ./models/Relation.php::staticgetAllRelations");
		return $relationModels;
	}

	/**
	 * Function to get relation field for relation module and parent module
	 * @return Vtiger_Field_Model
	 */
	public function getRelationField() {
        global $log;
		$relationField = $this->get('relationField');
		if (!$relationField) {
			$relationField = false;
			$relatedModel = $this->getRelationModuleModel();
			$parentModule = $this->getParentModuleModel();
			$relatedModelFields = $relatedModel->getFields();

			foreach($relatedModelFields as $fieldName => $fieldModel) {
				if($fieldModel->getFieldDataType() == Vtiger_Field_Model::REFERENCE_TYPE) {
					$referenceList = $fieldModel->getReferenceList();
					if(in_array($parentModule->getName(), $referenceList)) {
						$this->set('relationField', $fieldModel);
						$relationField = $fieldModel;
						break;
					}
				}
			}
		}
		return $relationField;
	}
    
    public static  function updateRelationSequenceAndPresence($relatedInfoList, $sourceModuleTabId) {
        global $log;
        $log->debug("Entering ./models/Relation.php::staticupdateRelationSequenceAndPresence");
        $db = PearDatabase::getInstance();
        $query = 'UPDATE vtiger_relatedlists SET sequence=CASE ';
        $relation_ids = array();
        foreach($relatedInfoList as $relatedInfo){
            $relation_id = $relatedInfo['relation_id'];
            $relation_ids[] = $relation_id;
            $sequence = $relatedInfo['sequence'];
            $presence = $relatedInfo['presence'];
            $query .= ' WHEN relation_id='.$relation_id.' THEN '.$sequence;
        }
        $query.= ' END , ';
        $query.= ' presence = CASE ';
        foreach($relatedInfoList as $relatedInfo){
            $relation_id = $relatedInfo['relation_id'];
            $relation_ids[] = $relation_id;
            $sequence = $relatedInfo['sequence'];
            $presence = $relatedInfo['presence'];
            $query .= ' WHEN relation_id='.$relation_id.' THEN '.$presence;
        }
        $query .= ' END WHERE tabid=? AND relation_id IN ('.  generateQuestionMarks($relation_ids).')';
        $result = $db->pquery($query, array($sourceModuleTabId,$relation_ids));
        $log->debug("Exiting ./models/Relation.php::staticupdateRelationSequenceAndPresence");
    }
	
	public function isActive() {
        global $log;
		return $this->get('presence') == 0 ? true : false;
	}
}
