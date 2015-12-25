<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

/* vt6 compatible 2014/04/09 */
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskModcomment extends \Workflow\Task
{
    /**
     * @param $context \Workflow\VTEntity
     */
    public function handleTask(&$context) {
        global $current_user;

        $comment = $this->get("comment", $context);
        $relRecord = $this->get("relRecord");

        if(empty($relRecord)) {
            $targetID = array($context->getId());
        } elseif($relRecord === 'custom') {

            $customid = $this->get('customid', $context);
            if(strpos($customid, ',') !== false) {
                $targetID = explode(',', $customid);
            } else {
                $targetID = array($customid);
            }

            foreach($targetID as $index => $value) {
                $targetID[$index] = $context->getCrmId($value);
            }
        } else {
            $targetID = $context->get($relRecord);

            $targetID = array($context->getCrmId($targetID));
        }

        global $currentModule;
        $oldCurrentModule = $currentModule;
        $currentModule = "ModComments";

//        var_dump($targetID);

        foreach($targetID as $id) {
            $recordModel = Vtiger_Record_Model::getCleanInstance("ModComments");
            $recordModel->getData();
            $recordModel->set('mode', '');

            $recordModel->set("commentcontent", $comment);
            $recordModel->set("related_to", $id);

            $recordModel->set("assigned_user_id", $current_user->id);
            $recordModel->set("userid", $current_user->id);

            $recordModel->save();
        }
        $currentModule = $oldCurrentModule;

        return "yes";
    }

    public function beforeGetTaskform($viewer) {
        global $adb, $app_strings;

        $fields = \Workflow\VtUtils::getFieldsForModule($this->getModuleName(), array(51,57,58,59,73,75,81,76,78,80,68,10));

        $references = array();

        foreach($fields as $field) {
            switch ($field->uitype) {
                case "51":
                   $module = "Accounts";
                break;
                case "57":
                   $module = "Contacts";
                   break;
                case "58":
                    $module = "Campaigns";
                   break;
                case "59":
                    $module = "Products";
                   break;
                case "73":
                    $module = "Accounts";
                   break;
                case "75":
                    $module = "Vendors";
                   break;
                case "81":
                    $module = "Vendors";
                   break;
                case "76":
                   $module = "Potentials";
                   break;
                case "78":
                    $module = "Quotes";
                   break;
                case "80":
                    $module = "SalesOrder";
                   break;
                case "68":
                    $module = "Accounts";
                       break;
                case "10": # Possibly multiple relations
                        $result = $adb->pquery('SELECT relmodule FROM `vtiger_fieldmodulerel` WHERE fieldid = ?', array($field->id));
                        while ($data = $adb->fetch_array($result)) {
                            $module = $data["relmodule"];
                        }
                    break;
            }
            $field->targetModule = !empty($app_strings[$module])?$app_strings[$module]:$module;
            $references[] = $field;
        }

        $viewer->assign("references", $references);
    }
}