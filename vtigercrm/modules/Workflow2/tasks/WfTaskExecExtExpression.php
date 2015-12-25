<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

/* vt6 ready 2014/04/12 */
class WfTaskExecExtExpression extends \Workflow\Task {

    #protected $_envSettings = array("result_environments");

    public function init() {
        if(-1 != $this->get("search_module") || !empty($_POST["task"]["search_module"])) {
            $module = !empty($_POST["task"]["search_module"]) ? $_POST["task"]["search_module"] : $this->get("search_module");
            $parts = explode('#~#', $module);
            $this->addPreset("Condition", "condition", array(
                'toModule' => VtUtils::getModuleName($parts[1])
            ));
        }

    }

    /**
     * @param $context \Workflow\VTEntity
     * @return string
     */
    public function handleTask(&$context) {
        global $adb;
        $currentTime = microtime(true);
        $benchmark = array();

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

        $logger = new \Workflow\ConditionLogger();

        $objMySQL = new \Workflow\ConditionMysql($related_module, $context);
        $objMySQL->setLogger($logger);

        $main_module = \CRMEntity::getInstance($related_module);
        #$sqlTables = $main_module->generateReportsQuery($related_module);

        if($related_module == "Calendar") {
            #$sqlTables .= " LEFT JOIN vtiger_seactivityrel ON(vtiger_seactivityrel.activityid = vtiger_crmentity.crmid)";
        }

        $sqlCondition = $objMySQL->parse($this->get("condition"));

        $newTime = microtime(true);
        $benchmark[] = round(($newTime - $currentTime), 3);$currentTime = $newTime;

        $sqlTables = $objMySQL->generateTables();
        if(strlen($sqlCondition) > 3) {
            $sqlCondition .= " AND vtiger_crmentity.deleted = 0";
        } else {
            $sqlCondition .= " vtiger_crmentity.deleted = 0";
        }

        $logs = $logger->getLogs();
        $this->setStat($logs);

        $sqlCondition .= " GROUP BY vtiger_crmentity.crmid ";
        $idColumn = $main_module->table_name.".".$main_module->table_index;
        $sqlQuery = "SELECT $idColumn as `idCol` ".$sqlTables." WHERE ".(strlen($sqlCondition) > 3?$sqlCondition:"");

        $sortField = $this->get("sort_field");
        if(!empty($sortField) && $sortField != -1) {
            $sortField = VtUtils::getColumnName($sortField);
            $sortDirection = $this->get("sortDirection");

            $sqlQuery .= " ORDER BY ".$sortField." ".$sortDirection;
        }

        $numRows = $this->get("found_rows");
        if(!empty($numRows) && $numRows != -1) {
            $sqlQuery .= " LIMIT ".$found_rows;
        }
#var_dump(nl2br($sqlQuery));exit();
        $this->addStat("MySQL Query: ".$sqlQuery);

        $result = $adb->query($sqlQuery);

        $newTime = microtime(true);
        $benchmark[] = round(($newTime - $currentTime), 3);$currentTime = $newTime;

        $this->addStat("num Rows: ".$adb->num_rows($result));
        # If no records are found, fo other way
        if($adb->num_rows($result) == 0) {
            return "no";
        }

        $environment = $context->getEnvironment();

        while($row = $adb->fetchByAssoc($result)) {
            $expression = $this->get("expression");
            if(!empty($expression)) {
                $tmpContext = \Workflow\VTEntity::getForId($row["idcol"], $related_module);
                $tmpContext->loadEnvironment($environment);

                $parser = new \Workflow\ExpressionParser($expression, $tmpContext, false); # Last Parameter = DEBUG

                try {
                    $parser->run();
                } catch(ExpressionException $exp) {
                    Workflow2::error_handler(E_EXPRESSION_ERROR, $exp->getMessage(), "", "");
                }

                $environment = $tmpContext->getEnvironment();
            }

        } # while

        $newTime = microtime(true);
        $benchmark[] = round(($newTime - $currentTime), 3);$currentTime = $newTime;

        $context->loadEnvironment($environment);
        $this->addStat("Benchmark: " . implode("/", $benchmark));

        return "yes";
    }

    public function beforeGetTaskform($viewer) {
        global $adb, $current_language, $mod_strings;

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
            $viewer->assign("search_module", $parts[0]);
            $viewer->assign("related_tabid", $parts[1]);

            $search_module_name = VtUtils::getModuleName($parts[1]);
            #$workflowSettings = $this->getWorkflow()->getSettings();

            $fields = VtUtils::getFieldsWithBlocksForModule($search_module_name);
            $viewer->assign("sort_fields", $fields);
        }
    }

    public function beforeSave(&$data) {
        $data["found_rows"] = preg_replace("/[^0-9]/", "", $data["found_rows"]);
    }

}