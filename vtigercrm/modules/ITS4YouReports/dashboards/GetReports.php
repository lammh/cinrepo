<?php

/*+********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

class ITS4YouReports_GetReports_Dashboard extends Vtiger_IndexAjax_View {
    
    function getSearchParams($value,$assignedto,$dates) {
        $listSearchParams = array();
        $conditions = array(array('leadstatus','e',$value));
        if($assignedto != '') array_push($conditions,array('assigned_user_id','e',getUserFullName($assignedto)));
        if(!empty($dates)){
            array_push($conditions,array('createdtime','bw',$dates['start'].' 00:00:00,'.$dates['end'].' 23:59:59'));
        }
        $listSearchParams[] = $conditions;
        return '&search_params='. json_encode($listSearchParams);
    }

	public function process(Vtiger_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
                
		$moduleName = "ITS4YouReports";
                
                $recordId = $request->get("record");
                $viewer->assign('recordid', $recordId);

		$linkId = $request->get('linkid');
		$data = $request->get('data');
		
		$createdTime = $request->get('createdtime');
		
		//Date conversion from user to database format
		if(!empty($createdTime)) {
			$dates['start'] = Vtiger_Date_UIType::getDBInsertedValue($createdTime['start']);
			$dates['end'] = Vtiger_Date_UIType::getDBInsertedValue($createdTime['end']);
		}
		
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
                
                $recordModel = ITS4YouReports_Record_Model::getInstanceById($recordId);
                
		$data = $moduleModel->getReports4You($recordId,$request->get('smownerid'),$dates);
                
                $detailViewUrl = 'index.php?module=ITS4YouReports&view=Detail&record='.$recordId;
                $viewer->assign('detailViewUrl', $detailViewUrl);
//echo "<pre>";print_r("<textarea>".$data."</textarea>");echo "</pre>";

		$widget = Vtiger_Widget_Model::getInstance($linkId, $currentUser->getId());
                $widget->set('title', $recordModel->getName());

		//Include special script and css needed for this widget
		$viewer->assign('WIDGET', $widget);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('DATA', $data);
		$viewer->assign('CURRENTUSER', $currentUser);

		$accessibleUsers = $currentUser->getAccessibleUsersForModule('Leads');
		$viewer->assign('ACCESSIBLE_USERS', $accessibleUsers);
        
        $viewer->assign('SETTING_EXIST', false);
        
        $content = $request->get('content');
		if(!empty($content)) {
            $display_widget_header = false;
        }else{
            $display_widget_header = true;
        }
        $viewer->assign('display_widget_header', $display_widget_header);
        
        $viewer->view('dashboards/DashBoardWidgetContents.tpl', $moduleName);
	}
        
}
