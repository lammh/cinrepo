<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskStart extends \Workflow\Task {
    /**
     * @var \Workflow\Preset\FormGenerator
     */
    private $_formgenerator = null;

    public function init() {
        $this->addPreset("Condition", "condition");
        $this->_formgenerator = $this->addPreset("FormGenerator", "fields", array(
                'module' => $this->getModuleName()
            ));
    }

    /**
     * @param $context \Workflow\VTEntity
     * @return array|string
     */
    public function handleTask(&$context) {
        if($this->get("start") == "asynchron" && !$this->isContinued()) {
            return array("delay" => time() + 1, "checkmode" => "static");
        }

        $workflowSettings = $this->getWorkflow()->getSettings();

        if(!empty($workflowSettings["startfields"])) {
            if($this->getWorkflow()->isSubWorkflow()) {
                $this->addStat('RequestValue Task in SubWorkflow currently not supported!');
                return 'start';
            }

            if(!$this->getWorkflow()->hasRequestValues('startfields')) {

                $export = $this->_formgenerator->exportUserQueue($this->_settings, $context);

                $this->getWorkflow()->requestValues('startfields', $export, $this, getTranslatedString('LBL_ENTER_VALUES_TO_START', 'Workflow2'), $context, true, false);
                return false;
            }
        }

        $startvalues = $context->getEnvironment("value");
        if($startvalues !== false) {
            $this->addStat("requested values:");
            foreach($startvalues as $key => $value) {
                $this->addStat("'".$key."' = '".$value."'");
            }
        }

        return "start";
    }

    public function beforeSave(&$values) {
        global $adb;
        $values2 = array();
        $columns = array();

        if(isset($values["runtime"])) {
            $values2[] = $values["runtime"];
            $columns[] = "`trigger` = ?";
        }

        $values2[] = !empty($values["execute_only_once_per_record"]) ? 1 : 0;
        $columns[] = "`once_per_record` = ?";

        $values2[] = !empty($values["withoutrecord"]) ? 1 : 0;
        $columns[] = "`withoutrecord` = ?";

        if(isset($values["runtime2"])) {
            $values2[] = $values["runtime2"];
            $columns[] = "`simultan` = ?";
        }
        if(isset($values["execution_user"])) {
            $values2[] = $values["execution_user"];
            $columns[] = "`execution_user` = ?";
        }
        if(isset($values["fields"]) && count($values["fields"]) > 0) {
            $values2[] = serialize($values["fields"]);
            $columns[] = "`startfields` = ?";
        } else {
            $values2[] = "";
            $columns[] = "`startfields` = ?";
        }

        if(isset($_POST["task"]["condition"])) {
            $values2[] = Zend_Json::encode($values["condition"]);
            $columns[] = "`view_condition` = ?";
            unset($values["task"]["condition"]);
        } else {
            $values2[] = '';
            $columns[] = "`view_condition` = ?";
        }

        $sql = "UPDATE vtiger_wf_settings SET ".implode(",", $columns)." WHERE id = ".$this->getWorkflowId();
        $adb->pquery($sql, array($values2));

    }

    public function beforeGetTaskform($viewer) {
        global $adb;

        if($this->get("trigger") == -1) {
            $sql = "SELECT `trigger` FROM vtiger_wf_settings WHERE id = ".$this->getWorkflowId();
            $result = $adb->query($sql);
            $this->set("runtime", $adb->query_result($result, 0, "trigger"));
        }
        $sql = "SELECT * FROM vtiger_wf_trigger WHERE deleted = 0 ORDER BY custom, `module`, `label`";
        $result = $adb->query($sql);

        $trigger = array();
        while($row = $adb->fetchByAssoc($result)) {
            $trigger[$row["custom"]=="1"?getTranslatedString("LBL_CUSTOM_TRIGGER", "Settings:Workflow2"):getTranslatedString("LBL_SYS_TRIGGER", "Settings:Workflow2")][$row["key"]] = getTranslatedString($row["label"], "Settings:".$row["module"]);
            if($this->get("runtime") == $row["id"]) {
                $this->set("runtime", $row["key"]);
            }
        }

        foreach($trigger as $key => $value) {
            asort($trigger[$key]);
        }
        $viewer->assign("trigger", $trigger);
    }

    public function getEnvironmentVariables() {
        $variables = array();

        $fields = $this->get('fields');
        if(!empty($fields) && $fields !== -1) {
            foreach($fields as $field) {
                $variables[] = "['value']['".$field['name']."'";
            }
            return $variables;
        }

        return array();
    }
}