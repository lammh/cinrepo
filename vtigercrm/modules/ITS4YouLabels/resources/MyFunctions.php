<?php

/* * *******************************************************************************
 * The content of this file is subject to the Labels 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class ITS4YouLabels_MyFunctions_Resources {

    public static function its4You_is_in_vtiger_php($language, $key, $module = '') {
//    $moduleStrings = array();
        $is_in_vtiger = 0; //if $key is in global->vtiger.php return 1(true)

        $module = str_replace(':', '.', $module);
        $moduleStrings = Vtiger_Language_Handler::getModuleStringsFromFile($language, $module);
        if (!empty($moduleStrings['languageStrings'][$key])) {
            //return $moduleStrings['languageStrings'][$key];
            return $is_in_vtiger = 0;
        }

        $commonStrings = Vtiger_Language_Handler::getModuleStringsFromFile($language);
        if (!empty($commonStrings['languageStrings'][$key])) {
            //return $commonStrings['languageStrings'][$key];
            return $is_in_vtiger = 1;
        }

        return $is_in_vtiger;
    }

}
