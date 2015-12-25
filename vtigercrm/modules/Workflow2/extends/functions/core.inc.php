<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan Warnat <support@stefanwarnat.de>
 * Date: 29.07.13
 * Time: 18:07
 */
if(!function_exists("wf_get_entity")) {
    function wf_get_entity($entity_id, $module_name = false) {
        $object = \Workflow\VTEntity::getForId($entity_id, $module_name);
        return $object->getData();
    }
}
if(!function_exists("wf_recordlist")) {
    function wf_recordlist($listId) {
        $context = \Workflow\ExpressionParser::$INSTANCE->getContext();
        $env = $context->getEnvironment($listId);
        $html = $env['html'];

        return $html;
    }
}

if(!function_exists("wf_json_encode")) {
    function wf_json_encode($value) {
        echo json_encode($value);
    }
}

if(!function_exists("wf_getcampaignstatus")) {
    function wf_getcampaignstatus($campaignId, $recordModule, $recordId) {
        if($recordModule == 'Leads') {
            $sql = 'SELECT data.campaignrelstatusid, campaignrelstatus FROM vtiger_campaignleadrel as data LEFT JOIN vtiger_campaignrelstatus ON (vtiger_campaignrelstatus.campaignrelstatusid = data.campaignrelstatusid) WHERE campaignid = ? AND leadid = ?';
        } elseif($recordModule == 'Contacts') {
            $sql = 'SELECT data.campaignrelstatusid, campaignrelstatus FROM vtiger_campaigncontrel as data LEFT JOIN vtiger_campaignrelstatus ON (vtiger_campaignrelstatus.campaignrelstatusid = data.campaignrelstatusid) WHERE campaignid = ? AND contactid = ?';
        } elseif($recordModule == 'Accounts') {
            $sql = 'SELECT data.campaignrelstatusid, campaignrelstatus FROM vtiger_campaignaccountrel as data LEFT JOIN vtiger_campaignrelstatus ON (vtiger_campaignrelstatus.campaignrelstatusid = data.campaignrelstatusid) WHERE campaignid = ? AND accountid = ?';
        } else {
            return 0;
        }

        $adb = \PearDatabase::getInstance();

        $result = $adb->pquery($sql, array(intval($campaignId), $recordId));
        if($adb->num_rows($result) > 0) {
            $data = $adb->fetchByAssoc($result);
            if($data['campaignrelstatusid'] == '1') {
                return '';
            } else {
                return $data['campaignrelstatus'];
            }
        }

        return 0;
    }
}

if(!function_exists("wf_fieldvalue")) {
    function wf_fieldvalue($crmid, $moduleName, $field) {
        $entity = \Workflow\VTEntity::getForId($crmid, $moduleName);

        if($entity === false) {
            throw new \Exception('You try to use wf_fieldvalue with a wrong crmid ('.$crmid.')');
        }

        return $entity->get($field);
    }
}

if(!function_exists("wf_date")) {
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
    function wf_salutation($value, $language = false) {
        global $adb, $current_language;

        if($language === false) {
            $language = $current_language;
        }

		require("modules/Contacts/language/".$language.".lang.php");
		return $mod_strings[$value];
    }
}

if(!function_exists("wf_log")) {
    function wf_log($value) {
        Workflow2::$currentBlockObj->addStat($value);
    }
}
if(!function_exists("wf_setfield")) {
    function wf_setfield($field, $value) {
        VTWfExpressionParser::$INSTANCE->getContext()->set($field, $value);
    }
}

if(!function_exists("wf_save_record")) {
    function wf_save_record() {
        VTWfExpressionParser::$INSTANCE->getContext()->save();
    }
}
if(!function_exists("wf_recordurl")) {
    function wf_recordurl($crmid) {
        $crmid = intval($crmid);
        $objTMP = \Workflow\VTEntity::getForId($crmid);
        global $site_URL;
        return $site_URL.'/index.php?module='.$objTMP->getModuleName().'&view=Detail&record='.$crmid;

    }
}
if(!function_exists("wf_recordlink")) {
    function wf_recordlink($crmid, $text = '') {
        $url = wf_recordurl($crmid);

        return '<a href="'.$url.'">'.$text.'</a>';

    }
}
if(!function_exists("wf_dbquery")) {
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
if(!function_exists("wf_dbSelectAll")) {
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
                return $return;
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
    function wf_formatcurrency($value) {
        $currencyField = new CurrencyField($value);
        return $currencyField->getDisplayValue(null, true);
    }
}
if(!function_exists('wf_oldvalue')) {
    function wf_oldvalue($field, $crmid) {
        if(empty($crmid)) {
            return false;
        }

        $objRecord = \Workflow\VTEntity::getForId($crmid);

        return \Workflow\EntityDelta::getOldValue($objRecord->getModuleName(), $crmid, $field);
    }
}
if(!function_exists('wf_haschanged')) {
    function wf_haschanged($field, $crmid) {
        if(empty($crmid)) {
            return false;
        }

        $objRecord = \Workflow\VTEntity::getForId($crmid);

        return \Workflow\EntityDelta::hasChanged($objRecord->getModuleName(), $crmid, $field);
    }
}
if(!function_exists('wf_changedfields')) {
    function wf_changedfields($crmid, $internalFields = false) {
        if(empty($crmid)) {
            return false;
        }

        $objRecord = \Workflow\VTEntity::getForId($crmid);

        return \Workflow\EntityDelta::changeFields($objRecord->getModuleName(), $crmid, $internalFields);
    }
}
if(!function_exists('wf_fieldlabel')) {
    function wf_fieldlabel($module, $fieldName) {
        if(!is_array($fieldName)) {
            $fieldName = array($fieldName);
            $single = true;
        } else {
            $single = false;
        }
        $tabid = getTabid($module);

        foreach($fieldName as $field) {
            if($field == 'crmid') {
                $fieldLabel = 'CRMID';
            } else {
                $fieldInfo = \Workflow\VtUtils::getFieldInfo($field, $tabid);

                $fieldLabel = $fieldInfo['fieldlabel'];
            }
            if(empty($fieldLabel)) {
                $fieldLabel = $field;
            }

            $return[] = $fieldLabel;
        }

        if($single === true) {
            return $return[0];
        } else {
            return $return;
        }
    }
}
