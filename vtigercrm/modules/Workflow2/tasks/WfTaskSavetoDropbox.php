<?php
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskSavetoDropbox extends \Workflow\Task
{
    /**
     * @var \Dropbox\API
     */
    private static $dropbox = false;

    public function handleTask(&$context) {
		/* Insert here source code to execute the task */

        global $adb;

        $documentId = $context->get("id");
        if(strpos($documentId, 'x') !== false) {
            $TMP = explode("x", $documentId);
            $documentId = $TMP[1];
        }

        $context->save();

        $sql = "SELECT attachmentsid FROM vtiger_seattachmentsrel WHERE crmid = ".$documentId;
        $result = $adb->query($sql, true);
        $fileid = $adb->query_result($result, 0, "attachmentsid");

        $pathQuery = $adb->pquery("select path, name from vtiger_attachments where attachmentsid = ?", array($fileid), true);
        $filepath = "../../../".$adb->query_result($pathQuery,0,'path');

        $filename = $adb->query_result($pathQuery,0,'name');

        $saved_filename = $fileid."_".utf8_encode(html_entity_decode(html_entity_decode($filename)));

        $path = realpath(dirname(__FILE__)."/".$filepath.$saved_filename);

        $create_dir = $this->get("create_dir");

        #var_dump($path);exit();
//        $sql = "SELECT * FROM
//                vtiger_attachments
//            INNER JOIN vtiger_seattachmentsrel ON(vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid)
//            INNER JOIN vtiger_notes ON(vtiger_notes.notesid = vtiger_seattachmentsrel.crmid)
//        WHERE vtiger_attachments.attachmentsid = ".intval($attachmentid);
//        $result = $adb->query($sql);
        try {
            $this->initDropbox();



            #$tmp = tempnam('/tmp', 'dropbox');
            #$data = 'This file was uploaded using the Dropbox API!';
            #file_put_contents($tmp, $data);

            $directory = $this->get("filepath");
            self::$dropbox->setRoot("dropbox");

            if(!empty($create_dir)) {
                $parser = new VTWfExpressionParser($create_dir, $context, false); # Last Parameter = DEBUG

                try {
                    $parser->run();
                } catch(\Workflow\ExpressionException $exp) {
                    Workflow2::error_handler(E_EXPRESSION_ERROR, $exp->getMessage(), "", "");
                }
                $newDir = $parser->getReturn();

                $found = self::$dropbox->search($newDir, $directory);

                if(empty($found["body"]) || count($found["body"]) == 0) {
                    self::$dropbox->create($directory);
                }
                $directory = $directory."/".$newDir;
            }

            $put = self::$dropbox->putFile($path, $newDir."/".$filename, $directory);
        } catch(Exception $exp) {
            Workflow2::error_handler(E_NONBREAK_ERROR, $exp->getMessage(), $exp->getFile(), $exp->getLine());
        }

		return "yes";
    }
	
    public function beforeGetTaskform($viewer) {
		/* Insert here source code to create custom configurations pages */

        if (!extension_loaded('curl')) {
            echo "cURL extension is required!";
            exit;
        }

        $this->initDropbox();
    }	
    public function beforeSave(&$values) {
		/* Insert here source code to modify the values the user submit on configuration */
    }

    protected function initDropbox() {
        if(self::$dropbox !== false)
            return;

        $userID = $this->getBlockId();

        /* @var $dropbox */
        include_once(self::getAdditionalPath('savetodropbox')."/php/bootstrap.php");

        self::$dropbox = $dropbox;
    }

}
