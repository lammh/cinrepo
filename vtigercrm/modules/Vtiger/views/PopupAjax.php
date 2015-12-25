<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Vtiger_PopupAjax_View extends Vtiger_Popup_View {
	
	function __construct() {

global $log;
$log->debug("Entering ./views/PopupAjax.php::__construct");
		parent::__construct();
		$this->exposeMethod('getListViewCount');
		$this->exposeMethod('getRecordsCount');
		$this->exposeMethod('getPageCount');
	}

	function preProcess(Vtiger_Request $request) {

global $log;
$log->debug("Entering ./views/PopupAjax.php::preProcess");
		return true;
	}

	function postProcess(Vtiger_Request $request) {

global $log;
$log->debug("Entering ./views/PopupAjax.php::postProcess");
		return true;
	}

	function process (Vtiger_Request $request) {

global $log;
$log->debug("Entering ./views/PopupAjax.php::process");
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();

		$this->initializeListViewContents($request, $viewer);

		echo $viewer->view('PopupContents.tpl', $moduleName, true);
	}

}