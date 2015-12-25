<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan Warnat <support@stefanwarnat.de>
 * Date: 29.07.13
 * Time: 18:07
 */
if(!function_exists("wf_get_entity")) {
    /**
     * load an array of all fields from a record.
     *
     * Examples:
     *
     * $recordData = wf_get_entity($crmid);
     *
     * $recordData = wf_get_entity($account_id->Accounts->id);
     *
     * @param $entity_id CRMID of the Record you want to load
     * @param bool $module_name If you know, the module name (default=Will be loaded from database)
     *
     * @version 1.0
     * @return array with all values of the record
     */
    function wf_get_entity($entity_id, $module_name = false) {
        $object = \Workflow\VTEntity::getForId($entity_id, $module_name);
        return $object->getData();
    }
}

if(!function_exists("wf_date")) {
    /**
     * Format and modify a given date
     *
     * Examples:
     *
     * $germanDateNextDay = wf_date($dateField, '+1 day', 'd.m.Y')
     *
     * @param $value The date you want to modify (YYYY-MM-DD [HH:II:SS]) Time is optional
     * @param $interval Empty or every Date/Time Interval: Syntax see here: http://php.net/manual/en/datetime.formats.relative.php
     * @param string $format Format you want to return (Placeholder: http://de1.php.net/manual/en/function.date.php)
     * @return string The formated and modified date
     */
    function wf_date($value, $interval, $format = "Y-m-d") {
        if(empty($interval)) {
            $dateValue = strtotime($value);
        } else {
            $dateValue = strtotime($interval, strtotime($value));
        }

        return date($format, $dateValue);
    }
}

if(!function_exists("wf_salutation")) {
    /**
     * Translate the salutation field into your language
     *
     * @param $value The selected value in the Record
     * @param bool $language The language you want to return
     * @return mixed The translated language
     */
    function wf_salutation($value, $language = false) {
        global $adb, $current_language;

        if($language === false) {
            $language = $current_language;
        }

		require("language/".$language."/Contacts.php");
		return $languageStrings[$value];
    }
}

if(!function_exists("wf_log")) {
    /**
     * Log a string to the statistic log to view later
     * @param mixed $value The Object you want to log
     */
    function wf_log($value) {
        Workflow2::$currentBlockObj->addStat($value);
    }
}
if(!function_exists("wf_setfield")) {
    /**
     * Set a value in the current record
     * @param $field The field you want to set
     * @param $value The value you want to set
     */
    function wf_setfield($field, $value) {
        VTWfExpressionParser::$INSTANCE->getContext()->set($field, $value);
    }
}

if(!function_exists("wf_save_record")) {
    /**
     * After you modify a record with wf_setfield, you need to save the record manually
     */
    function wf_save_record() {
        VTWfExpressionParser::$INSTANCE->getContext()->save();
    }
}
if(!function_exists("wf_recordurl")) {
    /**
     * return the URL to a record in your vtigerCRM system
     * @param $crmid The CRMID of the record
     * @return string The URL
     */
    function wf_recordurl($crmid) {
        $crmid = intval($crmid);
        $objTMP = \Workflow\VTEntity::getForId($crmid);
        global $site_URL;
        return $site_URL.'/index.php?action=DetailView&module='.$objTMP->getModuleName().'&record='.$crmid;

    }
}
if(!function_exists("wf_dbquery")) {
    /**
     * Execute a MySQL Query and return the first result of the query
     *
     * Examples:
     *
     * $queryData = wf_dbquery("SELECT * FROM vtiger_crmentity LIMIT 1");
     *
     * @param $query THe MySQL you want to execute
     * @return array
     */
    function wf_dbquery($query) {
        $adb = PearDatabase::getInstance();

        $result = $adb->query($query, false);
        $errorNo = $adb->database->ErrorNo();
        if(!empty($errorNo)) {
            Workflow2::error_handler(E_NONBREAK_ERROR, $adb->database->ErrorMsg());
        } else {
            if($adb->num_rows($result) > 0) {
                $row = $adb->fetchByAssoc($result);
                return $row;
            } else {
                return array();
            }
        }

        # need vtiger Database to reset Selected DB in the case the query changed this
        global $dbconfig;
        $adb->database->SelectDB($dbconfig['db_name']);
    }
}

if(!function_exists("wf_formatcurrency")) {
    /**
     * format a given value as currency for the current user
     *
     * Example:
     *
     * return wf_formatcurrency(12000.5);
     *
     * // Related to the User Settings "12.000,50", '12000.50'
     *
     * @param $value The number you want to format
     */
    function wf_formatcurrency($value) {
        $currencyField = new CurrencyField($value);
        return $currencyField->getDisplayValue(null, true);
    }
}

if(!function_exists("wf_dbSelectAll")) {
    /**
     * Execute a MySQL Query and returns every result row in an array
     *
     * @param $query
     * @return array rows with values
     */
    function wf_dbSelectAll($query) {
        $adb = PearDatabase::getInstance();

        $result = $adb->query($query, false);
        $errorNo = $adb->database->ErrorNo();
        if(!empty($errorNo)) {
            Workflow2::error_handler(E_NONBREAK_ERROR, $adb->database->ErrorMsg());
        } else {
            if($adb->num_rows($result) > 0) {
                $return = array();
                while($row = $adb->fetchByAssoc($result)) {
                    $return[] = $row;
                }
                return $row;
            } else {
                return array();
            }
        }

        # need vtiger Database to reset Selected DB in the case the query changed this
        global $dbconfig;
        $adb->database->SelectDB($dbconfig['db_name']);
    }
}
