<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

/* vt6 Ready 2014/04/09 */
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskSetter extends \Workflow\Task
{
    /**
     * @var \Workflow\Preset\FieldSetter
     */
    private $fieldSetter = false;

    public function init() {
        $moduleModel = Vtiger_Module_Model::getInstance("Workflow2");

        $className = "S"."WE"."xt"."ension_"."Workflow2_"."f54fc8d8ea40c20ed7b"."ded50c0d717b52c263a9f";
        $asdf = new $className("Workflow2", $moduleModel->version);

        $this->fieldSetter = $this->addPreset("FieldSetter", "setter", array(
            'fromModule' => $this->getModuleName(),
            'toModule' => $this->getModuleName(),
            'refFields' => $asdf->g1dd63e9ab62a68ac02f481ed3ba709207cb145ae()=='pr'.'o'
        ));
    }

    /**
     * @param $context \Workflow\VTEntity
     */
    public function handleTask(&$context) {
        $setterMap = $this->get("setter");

        $this->fieldSetter->apply($context, $setterMap, null, $this);

        return "yes";
    }

}
