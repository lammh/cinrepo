<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
//include_once 'vtlib/Vtiger/Field.php';
include_once 'modules/Vtiger/models/Field.php';

/**
 * Vtiger Field Model Class
 */
class Vtiger_Field_Model_sale extends Vtiger_Field_Model {


	/**
	 * Function to check whether field is ajax editable'
	 * @return <Boolean>
	 */
	public function isAjaxEditable() {
		//print("Vtiger");
	
		//$ajaxRestrictedFields = array('4', '72');
		//if(!$this->isEditable() || in_array($this->get('uitype'), $ajaxRestrictedFields)) {
		//	return false;
		//}
		//return true;
		return false;
	}



}