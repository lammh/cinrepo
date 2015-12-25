<?php
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskGenerateRecordlist extends \Workflow\Task
{
    public function init() {
        if(!empty($_POST['changeModule'])) {
            $this->set('fields', array());
        }
    }

    public function handleTask(&$context) {
		$envId = $this->get('envId');

        if(empty($envId)) {
            throw new \Exception('You must configure the generate Recordlist Block and set a Environment ID.');
        }

        $fields = $this->get('fields');
        $env = $context->getEnvironment($envId);

        $moduleName = $env['moduleName'];
        $ids = $env['ids'];
        $tabid = getTabid($moduleName);

        $search_module = $this->get("search_module");

        if(!empty($search_module)) {
            if($search_module != -1) {
                $parts = explode("#~#", $search_module);
            }
        }

        if($moduleName != $parts[0]) {
            throw new \Exception('The generate RecordList Block use the wrong Module. You must set '.$moduleName.' or create another block.');
        }

        $adb = \PearDatabase::getInstance();

        $html = '<table border=1 cellpadding=2 cellspacing=0>';
            $html .= '<thead><tr>';
                foreach($fields as $field) {
                    $html .= '<th style="width:'.$field['width'].';text-align:left;background-color:#ccc;">'.$field['label'].'</th>';
                }
            $html .= '</tr></thead>';
            foreach($ids as $id) {
                $html .= '<tr>';
                $record = \Workflow\VTEntity::getForId($id, $moduleName);
                foreach($fields as $field) {
                    if($field['field'] == 'link') {
                        $value = '<a href="'.vglobal('site_URL').'/index.php?module='.$record->getModuleName().'&view=Detail&record='.$id.'">Link</a>';
                    } else {
                        $value = \Workflow\VTTemplate::parse($field['field'], $record);
                    }
                    $html .= '<td>'.$value.'</td>';
                }
                $html .= '</tr>';
            }
        $html .= '</table>';

		$env['html'] = $html;
        $context->setEnvironment($envId, $env);

		return "yes";
    }

    public function getFromFields() {
        if($this->_fromFields === null) {
            $search_module = $this->get("search_module");

            if(!empty($search_module)) {
                if($search_module != -1) {
                    $parts = explode("#~#", $search_module);
                }
            } else {
                return;
            }


            $this->_fromFields = VtUtils::getFieldsWithBlocksForModule($parts[0], true);
        }

        return $this->_fromFields;
    }

    public function beforeGetTaskform($viewer) {
        $fields = $this->get('fields');
        if(empty($fields) || $fields == -1) {
            $fields = array();
        }


        $viewer->assign("StaticFieldsField", 'fields');
        $viewer->assign("fields", $fields);
        $viewer->assign("fromFields", $this->getFromFields());

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
    public function beforeSave(&$values) {
        unset($values['fields']['##SETID##']);
        return $values;
		/* Insert here source code to modify the values the user submit on configuration */
    }	
}
