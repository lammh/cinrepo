<?php
/*********************************************************************************
 * The content of this file is subject to the Labels 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

require_once 'modules/Webforms/model/WebformsModel.php';
require_once 'include/Webservices/DescribeObject.php';

class ITS4YouLabels {

    var $LBL_MODULE_NAME='Labels 4 You';
    var $LBL_MODULE_NAME_OLD='Labels 4 You';

    // Cache to speed up describe information store
    protected static $moduleDescribeCache = array();

    function __construct() {
        global $log, $currentModule;

        $this->db = PearDatabase::getInstance();
        $this->log = $log;
    }
    
    function vtlib_handler($moduleName, $eventType) {
		if($eventType == 'module.postinstall') {
                $this->updateSettings();
                
        } else if($eventType == 'module.disabled') {
        // TODO Handle actions when this module is disabled.
                $this->db->pquery('UPDATE vtiger_settings_field SET active= 1  WHERE  name= ?',array($this->LBL_MODULE_NAME));
        } else if($eventType == 'module.enabled') {
        // TODO Handle actions when this module is enabled.
                $this->db->pquery('UPDATE vtiger_settings_field SET active= 0  WHERE  name= ?',array($this->LBL_MODULE_NAME));
        } else if($eventType == 'module.preuninstall') {
        // TODO Handle actions when this module is about to be deleted.
        } else if($eventType == 'module.preupdate') {
        // TODO Handle actions before this module is updated.
        } else if($eventType == 'module.postupdate') {
        // TODO Handle actions after this module is updated.
                $this->updateSettings();
        }
    }

    function updateSettings(){
	
		$image = 'modules/ListViewColors/images/colored_listview.gif';
        $description = 'Edit your language labels...';
        $linkto = 'index.php?module=ITS4YouLabels&parent=Settings&view=List';

        $result1=$this->db->pquery('SELECT 1 FROM vtiger_settings_field WHERE name=?',array($this->LBL_MODULE_NAME_OLD));
        if($this->db->num_rows($result1)){
            $this->db->pquery('UPDATE vtiger_settings_field SET name=?, iconpath=?, description=?, linkto=? WHERE name=?',array($this->LBL_MODULE_NAME, $image, $description, $linkto, $this->LBL_MODULE_NAME_OLD));
        }

        $result2=$this->db->pquery('SELECT 1 FROM vtiger_settings_field WHERE name=?',array($this->LBL_MODULE_NAME));
        if(!$this->db->num_rows($result2)){

            $fieldid = $this->db->getUniqueID('vtiger_settings_field');
            $blockid = getSettingsBlockId('LBL_OTHER_SETTINGS');
            $seq_res = $this->db->pquery("SELECT max(sequence) AS max_seq FROM vtiger_settings_field WHERE blockid = ?", array($blockid));
            if ($this->db->num_rows($seq_res) > 0) {
                    $cur_seq = $this->db->query_result($seq_res, 0, 'max_seq');
                    if ($cur_seq != null)	$seq = $cur_seq + 1;
            }

            $this->db->pquery('INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence) VALUES (?,?,?,?,?,?,?)', array($fieldid, $blockid, $this->LBL_MODULE_NAME , $image, $description, $linkto, $seq));
        }	
    }

    static function checkAdminAccess($user) {
        
    }

    static function getModuleDescribe($module) {
        
    }

    static function getFieldInfo($module, $fieldname) {
        
    }

    static function getFieldInfos($module) {
        
    }
}