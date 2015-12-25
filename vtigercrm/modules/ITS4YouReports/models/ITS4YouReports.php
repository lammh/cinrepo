<?php

/*+********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

error_reporting(0);

class ITS4YouReports_ITS4YouReports_Model extends Vtiger_Module_Model {

    private $version_type;
    private $license_key;
    private $version_no;
    private $basicModules;
    private $pageFormats;
    private $profilesActions;
    private $profilesPermissions;
    var $log;
    var $db;

    // constructor of ITS4YouReports class
    function __construct() {
        $this->log = LoggerManager::getLogger('account');
        $this->db = PearDatabase::getInstance();

        $this->setLicenseInfo();
    }

    //Getters and Setters
    public function GetVersionType() {
        return $this->version_type;
    }

    public function GetLicenseKey() {
        return $this->license_key;
    }

    //PRIVATE METHODS SECTION
    private function setLicenseInfo() {

        $this->version_no = ITS4YouReports_Version_Helper::getVersion();

        $result = $this->db->pquery("SELECT version_type, license_key FROM its4you_reports4you_license", Array());
        if ($this->db->num_rows($result) > 0) {
            $this->version_type = $this->db->query_result($result, 0, "version_type");
            $this->license_key = $this->db->query_result($result, 0, "license_key");
        } else {
            $this->version_type = "";
            $this->license_key = "";
        }
    }
    
    public function GetAvailableSettings() {
        $menu_array = array();
        
        $menu_array["ITS4YouReportsLicense"]["location"] = "index.php?module=ITS4YouReports&view=License";
        $menu_array["ITS4YouReportsLicense"]["image_src"] = Vtiger_Theme::getImagePath('proxy.gif');
        $menu_array["ITS4YouReportsLicense"]["desc"] = "LBL_LICENSE_DESC";
        $menu_array["ITS4YouReportsLicense"]["label"] = "LBL_LICENSE";

        /*
        $menu_array["ITS4YouReportsUninstall"]["location"] = "index.php?module=ITS4YouReports&view=Uninstall";
        $menu_array["ITS4YouReportsUninstall"]["desc"] = "LBL_UNINSTALL_DESC";
        $menu_array["ITS4YouReportsUninstall"]["label"] = "LBL_UNINSTALL";
        */
        
        $menu_array["ITS4YouReportsUpgrade"]["location"] = "index.php?module=ModuleManager&parent=Settings&view=ModuleImport&mode=importUserModuleStep1";
        $menu_array["ITS4YouReportsUpgrade"]["desc"] = "LBL_UPGRADE";
        $menu_array["ITS4YouReportsUpgrade"]["label"] = "LBL_UPGRADE";
        
        return $menu_array;
    }
    
} 