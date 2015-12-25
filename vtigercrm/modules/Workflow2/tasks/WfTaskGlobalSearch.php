<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

/* vt6 ready 2014/04/28 */
class WfTaskGlobalSearch extends \Workflow\Task {

    protected $_envSettings = array(
        "count_results" => 'Number of found results'
    );

    public function init() {
        $search_module = $this->get("search_module");

        if(!empty($_POST["task"]["search_module"])) {
            $toModule = $_POST["task"]["search_module"];
        } elseif(!empty($search_module) && $search_module != -1) {
            $toModule = $search_module;
        }

        if(isset($toModule)) {
            $parts = explode("#~#", $toModule);

            $this->addPreset("Condition", "condition", array(
                'fromModule' => $this->getModuleName(),
                'toModule' => $parts[0]
            ));
        }
    }

    public function handleTask(&$context) {
        global $adb;

        if($this->get("search_module") == -1) {
            return "no";
        }

        $found_rows = $this->get("found_rows");
        if(empty($found_rows) || $found_rows == -1) {
            $found_rows = 1;
        }

        $parts = explode("#~#", $this->get("search_module"));
        $functionName = $parts[0];
        $related_module = VtUtils::getModuleName($parts[1]);

        require_once('modules/Workflow2/VTConditionMySql.php');

        $logger = new \Workflow\ConditionLogger();

        $objMySQL = new \Workflow\ConditionMysql($related_module, $context);
        $objMySQL->setLogger($logger);

        $main_module = CRMEntity::getInstance($related_module);
        #$sqlTables = $main_module->generateReportsQuery($related_module);

        $sqlCondition = $objMySQL->parse($this->get("condition"));

        if(strlen($sqlCondition) > 3) {
            $sqlCondition .= "AND vtiger_crmentity.deleted = 0";
        } else {
            $sqlCondition .= "vtiger_crmentity.deleted = 0";
        }

        $logs = $logger->getLogs();
        $this->setStat($logs);

        $sqlTables = $objMySQL->generateTables();

        $idColumn = $main_module->table_name.".".$main_module->table_index;
        $sqlQuery = "SELECT $idColumn as idCol ".$sqlTables." WHERE ".(strlen($sqlCondition) > 3?$sqlCondition:"").' GROUP BY vtiger_crmentity.crmid';

        $this->addStat("MySQL Query: ".$sqlQuery);

        $result = $adb->query($sqlQuery);

        if($adb->database->ErrorMsg() != "") {
            $this->addStat($adb->database->ErrorMsg());
        }

        $context->setEnvironment('count_results', $adb->num_rows($result), $this);

        $this->addStat("num Rows: ".$adb->num_rows($result));
        $this->addStat("have to at least x rows: ".$found_rows);

        $resultEnv = $this->get('resultEnv');
        if(!empty($resultEnv) && $resultEnv != -1) {
            $ids = array();
            while($row = $adb->fetchByAssoc($result)) {
                $ids[] = $row['idcol'];
            }

            $context->setEnvironment($resultEnv, array('ids' => $ids, 'moduleName' => $related_module));
        }

        if($adb->num_rows($result) >= $found_rows) {
            $return = "yes";
        } else {
            return "no";
        }

        return $return;

    }

    public function beforeGetTaskform($viewer) {
        global $current_language, $mod_strings;

        $viewer->assign("related_modules", VtUtils::getEntityModules(true));
        $search_module = $this->get("search_module");

        if(!empty($_POST["task"]["search_module"])) {
            $parts = explode("#~#", $_POST["task"]["search_module"]);
        } elseif(!empty($search_module)) {
            if($search_module != -1) {
                $parts = explode("#~#", $search_module);
            }
        } else {
            return;
        }

        if(!empty($parts)) {
            $viewer->assign("related_tabid", $parts[1]);
        }
    }

    public function beforeSave(&$data) {
        $data["found_rows"] = preg_replace("/[^0-9]/", "", $data["found_rows"]);
    }

}