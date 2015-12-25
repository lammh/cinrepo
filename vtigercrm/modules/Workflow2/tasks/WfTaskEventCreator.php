<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension

 * Last Change: 2012-12-06 1.6 swarnat
**/

require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

require_once('WfTaskCustomCreator.php');
/* vt6 ready */
class WfTaskEventCreator extends WfTaskCustomCreator
{
    protected $_fields = array("subject", "description", "eventstatus", "activitytype", "date_start", "due_date", "time_start", "time_end", "sendnotification",  "assigned_user_id", 'visibility');
    protected $_customModule = "Events";
    protected $_activityType = 'Event';

    protected $_hiddenValues = array("duration_hours" => "0");

    public function beforeGetTaskform($viewer) {
        global $adb, $vtiger_current_version;

        if(version_compare($vtiger_current_version, '5.3.0', '>=')) {
            // I have to respect Users timezone
            $setter = $this->get("setter");

            if(!empty($setter) && is_array($setter)) {
                foreach($setter as $key => $field) {
                    if(strpos($field["value"], "$") === false && ($field["field"] == "time_start" || $field["field"] == "time_end")) {
                        $date = DateTimeField::convertToUserTimeZone(date("Y-m-d")." ".$field["value"]);
                        $setter[$key]["value"] = $date->format("H:i");
                    }
                }
            }

            $this->set("setter", $setter);
        }

        parent::beforeGetTaskform($viewer);
    }

    public function beforeSave(&$values) {
        global $adb, $vtiger_current_version;

        if(version_compare($vtiger_current_version, '5.3.0', '>=')) {
            foreach($values["setter"] as $key => $field) {

    //             I have to respect Users timezone
                if(strpos($field["value"], "$") === false && ($field["field"] == "time_start" || $field["field"] == "time_end")) {
                    $date = DateTimeField::convertToDBTimeZone(date("Y-m-d")." ".$field["value"]);
                    $values["setter"][$key]["value"] = date("H:i", $date->format('U'));
                }
            }
        }

        parent::beforeSave($values);
    }

    public function handleTask(&$context) {
        $setter = $this->get("setter");
        $this->set("new_module", $this->_customModule);

        $reminderTime = null;

        if($setter != -1 && is_array($setter)) {
            foreach($setter as $field) {
                if($field['field'] == 'reminder_time') {
                    $reminderTime = $field['value'];
                    break;
                }
            }
        }

        if(!empty($reminderTime)) {
            $this->_hiddenValues['set_reminder'] = 'Yes';

            $reminder = $reminderTime;

            $minutes = (int)($reminder)%60;
            $hours = (int)($reminder/(60))%24;
            $days =  (int)($reminder/(60*24));

            //at vtiger there cant be 0 minutes reminder so we are setting to 1
            if($minutes == 0){
                    $minutes = 1;
            }

            $this->_hiddenValues['remmin'] = $minutes;
            $this->_hiddenValues['remhrs'] = $hours;
            $this->_hiddenValues['remdays'] = $days;
        }

        parent::handleTask($context);
        return 'yes';
    }
}
