<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));
/* vt6 ready 2014/04/28 */

class WfTaskRelcheck extends \Workflow\Task {

    public function init() {
        $related_module = $this->get("related_module");

        if(!empty($_POST["task"]["related_module"])) {
            $toModule = $_POST["task"]["related_module"];
        } elseif(!empty($related_module) && $related_module != -1) {
            $toModule = $related_module;
        }

        if(isset($toModule)) {
            $parts = explode("#~#", $toModule);
            $related_module_name = VtUtils::getModuleName($parts[1]);

            $this->addPreset("Condition", "condition", array(
                'fromModule' => $this->getModuleName(),
                'toModule' => $related_module_name
            ));
        }
    }

    /**
     * @param $context \Workflow\VTEntity
     * @return string
     */
    public function handleTask(&$context) {
        global $list_max_entries_per_page, $adb, $currentModule;
        $old_list_max_entries_per_page = $list_max_entries_per_page;

        if($this->get("related_module") == -1) {
            return "no";
        }

        $currentModule = $this->getModuleName();

        $found_rows = $this->get("found_rows");
        if(empty($found_rows) || $found_rows == -1) {
            $found_rows = 1;
        }

        $parts = explode("#~#", $this->get("related_module"));
        $functionName = $parts[0];

        $relatedModuleName = \Workflow\VtUtils::getModuleName($parts[1]);
        //$relationListView = Vtiger_RelationListView_Model::getInstance($parentRecordModel, $relatedModuleName, $label);

        $parentRecordModel = Vtiger_Record_Model::getInstanceById($context->getId(), $context->getModuleName());
        /**
         * @var Vtiger_RelationListView_Model $relatedListView
         */
        $relationListView = Vtiger_RelationListView_Model::getInstance($parentRecordModel, $relatedModuleName);

        $query = $relationListView->getRelationQuery();

        $query = preg_replace('/SELECT(.+)FROM/imU', 'SELECT vtiger_crmentity.crmid FROM', $query);

        $logger = new \Workflow\ConditionLogger();
        $relModule = \Workflow\VtUtils::getModuleName($parts[1]);

        $objMySQL = new \Workflow\ConditionMysql($relModule, $context);
        $objMySQL->setLogger($logger);

        $main_module = \CRMEntity::getInstance($relModule);

        $sqlCondition = $objMySQL->parse($this->get("condition"));

        $sqlTables = $objMySQL->generateTables();

        $logs = $logger->getLogs();
        $this->setStat($logs);

        $idColumn = $main_module->table_name.".".$main_module->table_index;
        $sqlQuery = "SELECT $idColumn ".$sqlTables." WHERE $idColumn IN (".$query.")".(strlen($sqlCondition) > 3?" AND ".$sqlCondition:"");

        $this->addStat("MySQL Query: ".$sqlQuery);

        $result = $adb->query($sqlQuery, true);

        $this->addStat("num Rows: ".$adb->num_rows($result));
        $this->addStat("have to at least x rows: ".$found_rows);

        if($adb->num_rows($result) >= $found_rows) {
            return 'yes';
        }

        return "no";
    }

    public function beforeGetTaskform($viewer) {
        global $current_language, $mod_strings;

        $viewer->assign("related_modules", VtUtils::getRelatedModules($this->getModuleName()));
        $related_module = $this->get("related_module");

        if(!empty($_POST["task"]["related_module"])) {
            $parts = explode("#~#", $_POST["task"]["related_module"]);
        } elseif(!empty($related_module)) {
            if($related_module != -1) {
                $parts = explode("#~#", $related_module);
            }
        } else {
            return;
        }

        if(!empty($parts)) {
            $viewer->assign("related_tabid", $parts[1]);
        }

    }

}