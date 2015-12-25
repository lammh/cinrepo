<?php

/* * *******************************************************************************
 * The content of this file is subject to the Labels 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class Settings_ITS4YouLabels_Save_Action extends Settings_Vtiger_Basic_Action {

    function process(Vtiger_Request $request) {
        error_reporting(false);
        $language = $request->get('Language');
        $module = $request->get('Module');

        require_once 'modules/ITS4YouLabels/resources/MyFunctions.php';
        require_once 'languages/' . $language . "/" . $module . ".php";

        $filename_module = "languages/" . $language . "/" . $module . ".php";
        $filename_vtiger = "languages/" . $language . "/Vtiger.php";

        $module_id = Vtiger_Functions::getModuleId($module);

        $adb = PearDatabase::getInstance();

        $query1 = "select * from its4you_labels where tabid= ? and lang= ?";
        $result = $adb->pquery($query1, array($module_id, $language));
        while ($row = $adb->fetchByAssoc($result)) {
            $array_from_its4you_labels_table[$row['id']] = $row;
        }
        $Post_Data = $request->getAll();
        foreach ($Post_Data as $key => $value) {
            $rest = trim(substr($key, 0, 5));
            if ($rest === "field") {
                $substring = explode("_", $key);
                $field_id = $substring[1];
                $query = "select fieldlabel from vtiger_field where fieldid = ?";
                $result = $adb->pquery($query, array($field_id));
                while ($row = $adb->fetchByAssoc($result)) {
                    $fieldlabel = $row['fieldlabel'];
                }

                $translated_label = vtranslate($fieldlabel, $module, $language);
            } else {
                if ($key == "Language" || $key == "Module" || $key == "settings_block" || $key == "settings_fieldid") {
                    break;
                }
            }

            $fieldlabel_from_post = $value;
            if ($fieldlabel_from_post != $translated_label) {

                if (!empty($array_from_its4you_labels_table)) {
                    foreach ($array_from_its4you_labels_table as $array_key => $array_value) {
                        $fieldid_from_its4you_labels = $array_from_its4you_labels_table[$array_key]['fieldid'];
                        $sql = "select count(id) from its4you_labels where tabid=? and fieldid=? and lang=?";
                        $result = $adb->pquery($sql, array($module_id, $field_id, $language));
                        while ($row = $adb->fetchByAssoc($result)) {
                            $count = $row['count(id)'];
                        }
                        if ($count > 0) {
                            if ($field_id == $fieldid_from_its4you_labels) {
                                $sql = "update its4you_labels set translate_label=? where id=? and lang=?";
                                $adb->pquery($sql, array($fieldlabel_from_post, $array_key, $language));
                                unset($array_from_its4you_labels_table[$array_key]);
                                break;
                            }
                        }
                        if ($count < 1) {
                            $is_in_vtiger = ITS4YouLabels_MyFunctions_Resources::its4You_is_in_vtiger_php($language, $fieldlabel, $module);
                            $query = "insert into its4you_labels values (?, ?, ?, ?,?, ?)";
                            $adb->pquery($query, array(NULL, $module_id, $field_id, $language, $fieldlabel_from_post, $is_in_vtiger));
                        }
                    }
                } else {
                    $is_in_vtiger = ITS4YouLabels_MyFunctions_Resources::its4You_is_in_vtiger_php($language, $fieldlabel, $module);
                    $query = "insert into its4you_labels values (?, ?, ?, ?, ?, ?)";
                    $adb->pquery($query, array(NULL, $module_id, $field_id, $language, $fieldlabel_from_post, $is_in_vtiger));
                }
            }
        }

        $filename_module = "languages/" . $language . "/" . $module . ".php";
        $filename_vtiger = "languages/" . $language . "/Vtiger.php";
        $filename_help = "languages/" . $language . "/my_help.php";
        $filename_new_module = "languages/" . $language . "/new_module.php";

        $help = fopen($filename_help, "a");
        $new_module = fopen($filename_new_module, "a");

        ftruncate($help, 0);
        ftruncate($new_module, 0);

        $moduleModel = Settings_LayoutEditor_Module_Model::getInstanceByName($module);
        $Fields = $moduleModel->getFields();

        foreach ($Post_Data as $key => $value) {
            $rest = trim(substr($key, 0, 5));
            if ($rest === "field") {
                $substring = explode("_", $key);
                $field_id = $substring[1];
                foreach ($Fields as $field) {
                    $id = $field->get('id');
                    if ($field_id == $id) {
                        $fieldlabel = $field->get('label');
                    }
                }
                $translate_label = vtranslate($fieldlabel, $module, $language);
                $fieldlabel_from_post = $value;
                if ($translate_label == $fieldlabel_from_post) {
                    
                } else {
                    $new_array_for_module[trim($fieldlabel)] = trim($fieldlabel_from_post);
                }
            } else {
                if ($key == "Language" || $key == "Module" || $key == "settings_block" || $key == "settings_fieldid") {
                    
                }
            }
        }


        foreach ($new_array_for_module as $key => $value) {
            foreach ($languageStrings as $keyword => $translateString) {
                if (trim($keyword) === trim($key)) {
                    $languageStrings[trim($keyword)] = trim($value);
                    unset($new_array_for_module[$key]);
                }
            }
        }

        $file = fopen($filename_module, "r");
        while (!feof($file)) {
            $contents = fgets($file);
            if ((strpos($contents, 'languageStrings'))) {
                break;
            }
            fwrite($help, $contents);
        }

        $Comments = file($filename_help);
        foreach ($Comments as $comment) {
            fwrite($new_module, $comment);
        }

        fwrite($new_module, '$languageStrings = array(' . PHP_EOL);
        foreach ($languageStrings as $keyword => $translateString) {
            $line = '"' . trim($keyword) . '"' . ' => ' . '"' . trim($translateString) . '",' . PHP_EOL;
            fwrite($new_module, $line);
        }

        if (!empty($new_array_for_module)) {
            foreach ($new_array_for_module as $key => $value) {
                $line = '"' . trim($key) . '"' . ' => ' . '"' . trim($value) . '",' . PHP_EOL;
                fwrite($new_module, $line);
            }
        }
        fwrite($new_module, ");");

        fwrite($new_module, PHP_EOL . PHP_EOL . '$jsLanguageStrings = array(' . PHP_EOL);
        foreach ($jsLanguageStrings as $key => $value) {
            $string = '"' . $key . '"' . ' => ' . '"' . $value . '",' . PHP_EOL;
            fwrite($new_module, $string);
        }
        fwrite($new_module, ");");

        unlink($filename_module);
        rename($filename_new_module, $filename_module);

        $result = array('success' => true, 'message' => 'Data has been successfully saved. If you refresh the page you will see changes.');
        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }
}
