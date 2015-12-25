<?php
/* * *******************************************************************************
 * The content of this file is subject to the Labels 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */


/*
 * Settings List View Model Class
 */
class Settings_ITS4YouLabels_ListView_Model extends Vtiger_Base_Model {

    public function getModule() {
		return $this->module;
	}

	public function setModule($name) {
		$modelClassName = Vtiger_Loader::getComponentClassName('Model', 'Module', $name);
		$this->module = new $modelClassName();
		return $this;
	}

}