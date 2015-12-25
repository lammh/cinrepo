<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/
/* vt6 ready */
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

require_once(dirname(__FILE__).'/../VTComplexeCondition.php');

class WfTaskConditional extends Workflow\Task {

    public function init() {
        $this->addPreset("Condition", "condition");
    }
    public function handleTask(&$context) {
        $conditions = $this->get("condition");

        require_once('modules/Workflow2/VTConditionCheck.php');

        $checked = new \Workflow\ConditionCheck();
        $logger = new \Workflow\ConditionLogger();

        $checked->setLogger($logger);
        $return = $checked->check($conditions, $context);

        $logs = $logger->getLogs();
        $logs[] = "Complete Result: ".intval($return);
        $this->setStat($logs);

        // Debug Mode
        if(isset($_COOKIE["stefanDebug"]) && $_COOKIE["stefanDebug"] >= "2") {
            echo "<pre>";
            /* ONLY DEBUG*/ var_dump($conditions);
            echo "</pre>";
        }

        if($return === true) {
            return "yes";
        } else {
            return "no";
        }

    }

}