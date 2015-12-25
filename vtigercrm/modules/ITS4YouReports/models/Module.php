<?php

/*+********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

//ini_set("display_errors",1);error_reporting(63);
error_reporting(0);
class ITS4YouReports_Module_Model extends Vtiger_Module_Model {

	/**
	 * Function deletes report
	 * @param Reports_Record_Model $reportModel
	 */
	function deleteRecord(Reports_Record_Model $reportModel) {
		$adb = PearDatabase::getInstance();
                $currentUser = Users_Record_Model::getCurrentUserModel();
		$subOrdinateUsers = $currentUser->getSubordinateUsers();
ITS4YouReports::sshow("deletujes !");
exit;
		$subOrdinates = array();
		foreach($subOrdinateUsers as $id=>$name) {
			$subOrdinates[] = $id;
		}

		$owner = $reportModel->get('owner');

		if($currentUser->isAdminUser() || in_array($owner, $subOrdinates) || $owner == $currentUser->getId()) {
			$reportId = $reportModel->getId();
			$db = PearDatabase::getInstance();

			$db->pquery('DELETE FROM vtiger_selectquery WHERE queryid = ?', array($reportId));

			$db->pquery('DELETE FROM vtiger_report WHERE reportid = ?', array($reportId));

			$db->pquery('DELETE FROM vtiger_scheduled_reports WHERE reportid = ?', array($reportId));

			$result = $db->pquery('SELECT * FROM vtiger_homereportchart WHERE reportid = ?',array($reportId));
			$numOfRows = $db->num_rows($result);
			for ($i = 0; $i < $numOfRows; $i++) {
				$homePageChartIdsList[] = $adb->query_result($result, $i, 'stuffid');
			}
			if ($homePageChartIdsList) {
				$deleteQuery = 'DELETE FROM vtiger_homestuff WHERE stuffid IN (' . implode(",", $homePageChartIdsList) . ')';
				$db->pquery($deleteQuery, array());
			}
			return true;
		}
		return false;
	}

	/**
	 * Function returns quick links for the module
	 * @return <Array of Vtiger_Link_Model>
	 */
        public function getSideBarLinks($linkParams) {
            $request = new Vtiger_Request($_REQUEST, $_REQUEST);
            $currentUserModel = Users_Record_Model::getCurrentUserModel();
            
//ini_set('display_errors',1);error_reporting(63);
            $linkTypes = array('SIDEBARLINK', 'SIDEBARWIDGET');
            $links = Vtiger_Link_Model::getAllByType($this->getId(), $linkTypes, $linkParams);
            $quickLinks = array(
                    array(
                            'linktype' => 'SIDEBARLINK',
                            'linklabel' => 'LBL_REPORTS',
                            'linkurl' => $this->getListViewUrl(),
                            'linkicon' => '',
                    ),
            );
            foreach($quickLinks as $quickLink) {
                    $links['SIDEBARLINK'][] = Vtiger_Link_Model::getInstanceFromValues($quickLink);
            } 
            if($currentUserModel->isAdminUser()) {
                if($request->get('view')=="IndexAjax"){
                    $quickS2Links = array(
                    'linktype' => "SIDEBARWIDGET",
                    'linklabel' => "LBL_SETTINGS",
                    'linkurl' => "index.php?module=ITS4YouReports&view=License",
                    'linkicon' => ''); 
                    $links['SIDEBARWIDGET'][] = Vtiger_Link_Model::getInstanceFromValues($quickS2Links);
                }else{
                    $quickS2Links = array(
                    'linktype' => "SIDEBARWIDGET",
                    'linklabel' => "LBL_SETTINGS",
                    'linkurl' => "module=ITS4YouReports&view=IndexAjax&mode=showSettingsList&pview=".$linkParams["ACTION"],
                    'linkicon' => ''); 
                    $links['SIDEBARWIDGET'][] = Vtiger_Link_Model::getInstanceFromValues($quickS2Links);
                }
            }

//echo "<pre><br><br><br><br><br><br><br><br><br><br>";
//print_r($links);echo "</pre>";
            return $links;
        }

	/**
	 * Function returns the recent created reports
	 * @param <Number> $limit
	 * @return <Array of Reports_Record_Model>
	 */
	function getRecentRecords($limit = 10) {
		$db = PearDatabase::getInstance();

		$result = $db->pquery('SELECT * FROM vtiger_report ORDER BY reportid DESC LIMIT ?', array($limit));
		$rows = $db->num_rows($result);

		$recentRecords = array();
		for($i=0; $i<$rows; ++$i) {
			$row = $db->query_result_rowdata($result, $i);
			$recentRecords[$row['reportid']] = $this->getRecordFromArray($row);
		}
		return $recentRecords;
	}

	/**
	 * Function returns the report folders
	 * @return <Array of Reports_Folder_Model>
	 */
	function getFolders() {
		return ITS4YouReports_Folder_Model::getAll();
	}

	/**
	 * Function to get the url for add folder from list view of the module
	 * @return <string> - url
	 */
	function getAddFolderUrl() {
		return 'index.php?module='.$this->get('name').'&view=EditFolder';
	}
        
	/**
	 * Function returns Leads grouped by Status
	 * @param type $data
	 * @return <Array>
	 */
	function getReports4You($reportid,$owner,$dateFilter) {
		$db = PearDatabase::getInstance();

                require_once 'modules/ITS4YouReports/ITS4YouReports.php';
                require_once 'modules/ITS4YouReports/GenerateObj.php';
                require_once 'include/Zend/Json.php';
                error_reporting(0);ini_set("display_errors",0);
                
                $report4YouRun = Report4YouRun::getInstance($reportid);
                $data = $report4YouRun->GenerateReport($reportid, 'CHARTS');

		return $data;
	}
}