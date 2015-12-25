<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskMysqlquery extends \Workflow\Task {

    /***
     * @param $context \Workflow\VTEntity
     * @return string
     */
    public function handleTask(&$context) {
        global $adb;

        if(defined("WF_DEMO_MODE") && constant("WF_DEMO_MODE") == true) {
            return "yes";
        }

        $query = $this->get("query", $context);
        $envVar = $this->get("envvar", $context);

        if(empty($envVar)) {
            Workflow2::error_handler(E_NONBREAK_ERROR, "You must configure an environment variable in this block to get the correct result.");
        }

        $oldDieOnError = $adb->dieOnError;

        $adb->dieOnError = false;
        $this->addStat($query);
        try {
            $result = $adb->query($query, false);
            $errorNo = $adb->database->ErrorNo();
            if(!empty($errorNo)) {
                Workflow2::error_handler(E_NONBREAK_ERROR, $adb->database->ErrorMsg());
            } else {
                if(!empty($envVar)) {
                    if($adb->num_rows($result) > 0) {
                        $row = $adb->fetchByAssoc($result);
                        $context->setEnvironment($envVar, $row);
                    }
                }
            }

            # need vtiger Database to reset Selected DB in the case the query changed this
            global $dbconfig;
            $adb->database->SelectDB($dbconfig['db_name']);
        } catch (Exception $exp) {
            Workflow2::error_handler(E_NONBREAK_ERROR, $exp->getMessage());
        }

        $adb->dieOnError = $oldDieOnError;

        return "yes";
    }

    public function beforeGetTaskform($viewer) {
        if(defined("WF_DEMO_MODE") && constant("WF_DEMO_MODE") == true) {
            echo "<p style='text-align:center;margin:0;padding:5px 0;background-color:#fbcb09;font-weight:bold;'>This Task won't work on demo.stefanwarnat.de</p>";
        }
    }

    public function beforeSave(&$data) {

        //echo "<pre>";var_dump($data);echo "</pre>";
//        echo "RESULT\n";var_dump($data);echo "</pre>";
    }



}