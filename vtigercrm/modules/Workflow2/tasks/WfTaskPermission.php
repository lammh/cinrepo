<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/
/* vt6 ready 2014/04/27 */
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));
require_once('include/utils/GetGroupUsers.php');

class WfTaskPermission extends \Workflow\Task
{
    protected $_envSettings = array(
        "permission_result" => 'Permission Result (ok, rework, deny)'
    );

    private function _compatible() {
        $bgmode = $this->get("bgmode");

        if('function' == $bgmode) {
            $backgroundcolorFKT = $this->get("backgroundcolorFKT");
            $this->set('backgroundcolor', '<?php '.$backgroundcolorFKT.' ?>');
            $this->set('bgmode', 'value');
        }

        $infomode = $this->get("infomode");

        if('function' == $infomode) {
            $infomessageFKT = $this->get("infomessageFKT");

            $this->set('infomessage', '<?php '.$infomessageFKT.' ?>');
            $this->set('infomode', 'value');
        }

        $targets = $this->get('targets');
        if(!is_array($targets)) {
            $this->set('targets', $targets);
        }
    }

    /**
     * @param $context \Workflow\VTEntity
     */
    public function handleTask(&$context) {
        global $current_user, $adb;

        if($this->isContinued()) {
            $sql = "SELECT id, result, result_user_id FROM vtiger_wf_confirmation WHERE execID = '".$this->getExecId()."' AND result != '' AND visible = 1";
            $result = $adb->query($sql);

            if($adb->num_rows($result) == 0) {
                $timeout = $context->getEnvironment('_permissionTimeout');
                $timeoutValue = $context->getEnvironment('_permissionTimeoutValue');

                if($timeout == true) {
                    if(time() > $timeoutValue) {
                        $this->addStat('Timeout action: '.$this->get('timeout_output'));
                        return $this->get('timeout_output');
                    }

                }

                return array("delay" => time() + (60 * 10), "checkmode" => "static");
            }

            $data = $adb->fetchByAssoc($result);

            $sql = "SELECT user_name FROM vtiger_users WHERE id = ".$data["result_user_id"];
            $resultUser = $adb->query($sql);
            $resultUser = $adb->fetchByAssoc($resultUser);

            $this->addStat("Permission: ".$data["result"]." by ".$resultUser["user_name"]);

            $sql = "UPDATE vtiger_wf_confirmation SET visible = 0 WHERE id = ".$data["id"];
            $adb->query($sql);

            return $data["result"];
        }

        $this->_compatible();

        $connected = $this->getConnectedObjects("assigned_to");
        $targets = array();

        if(!empty($connected)) {
            foreach($connected as $user) {
                if(empty($user))
                    continue;

                $targets['user'][] = intval($user->getId());
            }
        }

        $TMPtargets = $this->get('targets');

        if(is_array($TMPtargets)) {
            foreach($TMPtargets as $value) {
                $parts = explode('##', $value);

                if($parts[0] == 'USER' && !in_array(intval($parts[1]), $targets['user'])) {
                    $targets['user'][] = intval($parts[1]);
                }
                if($parts[0] == 'GROUP') {

                    $focusGrpUsers = new GetGroupUsers();
                    $focusGrpUsers->getAllUsersInGroup($parts[1]);
                    $groupUser = $focusGrpUsers->group_users;

                    if(is_array($groupUser)) {
                        foreach($groupUser as $userId) {
                            if(!in_array($userId, $targets['user'])) {
                                $targets['user'][] = $userId;
                            }
                        }
                    }

                }
                if($parts[0] == 'ROLE') {

                    $focusGrpUsers = new GetGroupUsers();
                    $focusGrpUsers->getAllUsersInGroup($parts[0]);
                    $groupUser = array_keys(getRoleUsers($parts[1]));

                    if(is_array($groupUser)) {
                        foreach($groupUser as $userId) {
                            if(!in_array($userId, $targets['user'])) {
                                $targets['user'][] = $userId;
                            }
                        }
                    }
                }
            }
        }

        $backgroundcolor = $this->get("backgroundcolor");

        $bgmode = $this->get("bgmode");

        if(!empty($bgmode) && $bgmode != -1) {

            if($bgmode == "function") {
                $parser = new VTWfExpressionParser($backgroundcolorFKT, $context, false); # Last Parameter = DEBUG

                try {
                    $parser->run();
                } catch(ExpressionException $exp) {
                    Workflow2::error_handler(E_EXPRESSION_ERROR, $exp->getMessage(), "", "");
                }

                $backgroundcolor = $parser->getReturn();
            } else {

                if(strpos($backgroundcolor, '$') !== false || strpos($backgroundcolor, '?') !== false) {
                    $objTemplate = new VTTemplate($context);
                    $backgroundcolor = $objTemplate->render($backgroundcolor);
                }

            }
        } else {
            $backgroundcolor = "#ffffff";
        }
        if(empty($backgroundcolor)) {
            $backgroundcolor = "#ffffff";
        }

        $infomessage = $this->get("infomessage");
        $infomessageFKT = $this->get("infomessageFKT");
        $infomode = $this->get("infomode");

        if(!empty($infomode) && $infomode != -1) {

            if($infomode == "function") {
                $parser = new VTWfExpressionParser($infomessageFKT, $context, false); # Last Parameter = DEBUG

                try {
                    $parser->run();
                } catch(ExpressionException $exp) {
                    Workflow2::error_handler(E_EXPRESSION_ERROR, $exp->getMessage(), "", "");
                }

                $infomessage = $parser->getReturn();
            } else {

                if(strpos($infomessage, '$') !== false || strpos($infomessage, '?') !== false) {
                    $objTemplate = new VTTemplate($context);
                    $infomessage = $objTemplate->render($infomessage);
                }

            }
        } else {
            $infomessage = "";
        }
        if(empty($infomessage)) {
            $infomessage = "";
        }

        $rundirect = $this->get("rundirect");

        $sql = "INSERT INTO vtiger_wf_confirmation SET crmid = ".$context->getId().", infomessage = ?, backgroundcolor = ?, blockID = '".$this->getBlockId()."', execID = '".$this->getExecId()."', visible = 1, result = '', module = '".$context->getModuleName()."', workflow_id = ".$this->getWorkflowId().", rundirect = ".($rundirect=="1"?1:0).", from_user_id = ".$current_user->id;
        $adb->pquery($sql, array($infomessage, $backgroundcolor));
        $confID = $adb->getLastInsertID();

        foreach($targets['user'] as $user) {
            $sql = "INSERT INTO vtiger_wf_confirmation_user SET confirmation_id = '".$confID."', user_id = ".$user;
            $adb->query($sql);

            $this->addStat("create Permission entry (Block ".$this->getBlockId().") for User ".$user);
        }

        // if we need an timeout than wait until
        $use_timeout = $this->get("use_timeout");
        if($use_timeout == '1') {
            $timeout_value = $this->get("timeout_value");
            $timeout_value_mode = $this->get("timeout_value_mode");

            $timestamp = time();

            switch($timeout_value_mode) {
                case "minutes":
                    $timestamp += (60 * $timeout_value);
                    break;
                case "hours":
                    $timestamp += (60 * 60 * $timeout_value);
                    break;
                case "days":
                    $timestamp += (24 * 60 * 60 * $timeout_value);
                    break;
                case "weeks":
                    $timestamp += (7 * 24 * 60 * 60 * $timeout_value);
                    break;
            }

            $context->setEnvironment('_permissionTimeout', true);
            $context->setEnvironment('_permissionTimeoutValue', $timestamp);
        } else {
            $context->setEnvironment('_permissionTimeout', false);
        }

        // check every 15 minutes
        return array("delay" => time() + (60 * 15), "checkmode" => "static");
    }

    public function beforeGetTaskform($viewer) {
        $adb= \PearDatabase::getInstance();
        $this->_compatible();

        if($this->get("btn_accept") == -1) {
            $this->set("btn_accept", "LBL_OK");
        }
        if($this->get("btn_rework") == -1) {
            $this->set("btn_rework", "LBL_REWORK");
        }
        if($this->get("btn_decline") == -1) {
            $this->set("btn_decline", "LBL_DECLINE");
        }

        $presetTargets = $this->get('targets');
        if(!is_array($presetTargets)) {
            $presetTargets = array();
        }

        $targets = array();
        /** Assigned Users */
        $sql = "SELECT id,user_name,first_name,last_name FROM vtiger_users WHERE status = 'Active'";
        $result = $adb->query($sql);
        while($user = $adb->fetchByAssoc($result)) {
            $targets['users']['USER##'.$user["id"]] = array($user['last_name'].' '.$user['first_name'].' ('.$user['user_name'].')', in_array('USER##'.$user["id"], $presetTargets)?true:false);
        }
        $sql = "SELECT groupid,groupname FROM vtiger_groups";
        $result = $adb->query($sql);
        while($user = $adb->fetchByAssoc($result)) {
            $targets['groups']['GROUP##'.$user["groupid"]] = array($user['groupname'], in_array('GROUP##'.$user["groupid"], $presetTargets)?true:false);
        }
        $sql = "SELECT roleid,rolename FROM vtiger_role";
        $result = $adb->query($sql);
        while($user = $adb->fetchByAssoc($result)) {
            $targets['roles']['ROLE##'.$user["roleid"]] = array($user['rolename'], in_array('ROLE##'.$user["roleid"], $presetTargets)?true:false);
        }

        $viewer->assign('targets', $targets);

    }

    public function beforeSave(&$values) {

    }
}
