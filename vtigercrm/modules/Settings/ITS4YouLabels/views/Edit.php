<?php

/* * *******************************************************************************
 * The content of this file is subject to the Labels 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class Settings_ITS4YouLabels_Edit_View extends Settings_Vtiger_Index_View {

    public function process(Vtiger_Request $request) {
        //$this->fieldsIntoTable($request);
        $this->showBlocksAndFields($request);
    }

    public function showBlocksAndFields(Vtiger_Request $request) {
        $sourceModuleId = $request->get('moduleId');
        $language = $request->get('lang');
        $currentModule = $request->get('module');
        $parent = $request->get('parent');
        $settings_block = $request->get('block');
        $settings_fieldid = $request->get('fieldid');
        $adb = PearDatabase::getInstance();
        $sourceModule = getTabModuleName($sourceModuleId); 
        
        $moduleModel = Settings_LayoutEditor_Module_Model::getInstanceByName($sourceModule);
        $fieldModels = $moduleModel->getFields();
        $blockModels = $moduleModel->getBlocks();

        $blockIdFieldMap = array();
        $inactiveFields = array();
        foreach ($fieldModels as $fieldModel) {
            $blockIdFieldMap[$fieldModel->getBlockId()][$fieldModel->getName()] = $fieldModel;
            if (!$fieldModel->isActiveField()) {
                $inactiveFields[$fieldModel->getBlockId()][$fieldModel->getId()] = vtranslate($fieldModel->get('label'), $sourceModule);
            }
        }

        foreach ($blockModels as $blockLabel => $blockModel) {
            $fieldModelList = $blockIdFieldMap[$blockModel->get('id')];
            $blockModel->setFields($fieldModelList);
        }
        
        $query = "SELECT * from its4you_labels where tabid = ? and lang =?";
        $result = $adb->pquery($query,array($sourceModuleId, $language));
        while($row = $adb->fetchByAssoc($result))
        {
                $Fields_Labels[ $row['fieldid']] = $row['translate_label'];
        }
        
        $qualifiedModule = $request->getModule(false);

        $viewer = $this->getViewer($request);
        $viewer->assign('SELECTED_MODULE_NAME', $sourceModule);
        $viewer->assign('SELECTED_MODULE_MODEL', $moduleModel);
        $viewer->assign('BLOCKS', $blockModels);
        $viewer->assign('ADD_SUPPORTED_FIELD_TYPES', $moduleModel->getAddSupportedFieldTypes());
        $viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModule);
        $viewer->assign('LANGUAGE', $language);
        $viewer->assign('CURRENT_MODULE', $currentModule);
        $viewer->assign('PARENT', $parent);
        $viewer->assign('FIELDS_LABELS', $Fields_Labels);
        $viewer->assign('SETTINGS_FIELDID', $settings_fieldid);
        $viewer->assign('SETTINGS_BLOCK', $settings_block);
        
        echo $viewer->view('EditView.tpl', $qualifiedModule, true);
    }
//    function fieldsIntoTable(Vtiger_Request $request){
//        $sourceModuleId = $request->get('moduleId');
//        $language = $request->get('lang');
//        $sourceModule = getTabModuleName($sourceModuleId);
//        
//        $tablename = "its4you_".$language."_".$sourceModule;
//        
//        if (!Vtiger_Utils::CheckTable($tablename)) {
//            Vtiger_Utils::CreateTable($tablename, '(id int(3) NOT NULL PRIMARY KEY,field_id int(19) NOT NULL, lang char(5) NOT NULL,label varchar(255) NOT NULL, isString int(1) NOT NULL)', true);
//        }
//        //else bud vymazat tabulku alebo len vymazat z nej jej udaje + its4you_en_us_accounts_seq odznova..
//        else {
//            mysql_query("Delete from $tablename");
//            mysql_query("drop table $tablename"."_seq");
//        }
//        
//        $moduleModel = Settings_LayoutEditor_Module_Model::getInstanceByName($sourceModule);
//        $fieldModels = $moduleModel->getFields();
//        
//        $adb = PearDatabase::getInstance();
//        foreach ($fieldModels as $fieldModel) {
//            $adb->pquery('INSERT INTO '. $tablename .' VALUES(?,?,?,?,?)',
//                                                      array($adb->getUniqueID($tablename),$fieldModel->getId(), $language, Vtiger_Language_Handler::getTranslatedString($fieldModel->get('label'), $sourceModule, $language), 0));
//        }
//    }

    function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

        $jsFileNames = array(
            'modules.Vtiger.resources.Vtiger',
            'modules.Settings.Vtiger.resources.Vtiger',
            'modules.Settings.Vtiger.resources.Edit',
            "modules.Settings.$moduleName.resources.$moduleName",
            "modules.Settings.$moduleName.resources.Edit",
            'modules.Settings.Vtiger.resources.Index',
            "modules.Settings.$moduleName.resources.Index",
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }

}