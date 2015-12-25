<?php
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskCallWebservice extends \Workflow\Task
{
    protected $_javascriptFile = "WfTaskCallWebservice.js";

    public function handleTask(&$context) {
        $values = $this->get("cols");

        $query = array();
        $objTemplate = new VTTemplate($context);
        foreach($values["key"] as $index => $value) {
            $keyValue = $objTemplate->render($values["value"][$index]);
            $query[$value] = $keyValue;
        }
        $url = $this->get('url', $context);

        $method = $this->get('method');

        switch($method) {
            case 'POST':
            case 'GET':
                $content = \VtUtils::getContentFromUrl($url, $query, $method);
                break;
        }
//        var_dump($method, $url, $query);
//        var_dump($content);
        return "yes";
    }
	
    public function beforeGetTaskform($viewer) {
        $pause_rows = $this->get("pause_rows");
        if($pause_rows == -1) {
            $this->set("pause_rows", 50);
        }

        $cols = $this->get("cols");

        if($cols == -1) {
            $cols = array();
        }

        foreach($cols["key"] as $index => $col) {
            if(empty($col)) {
                unset($cols["key"][$index]);
                unset($cols["value"][$index]);
            }
        }
        $viewer->assign("cols", $cols);
        $viewer->assign("webservice_methods", array('POST' => 'POST', 'GET' => 'GET'));

		/* Insert here source code to create custom configurations pages */
    }	
    public function beforeSave(&$values) {
		/* Insert here source code to modify the values the user submit on configuration */
    }	
}
