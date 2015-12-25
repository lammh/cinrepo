<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

/* vt6 Ready 2014/04/09 */
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskSaveDocument extends \Workflow\Task
{
    protected $_envSettings = array("new_record_id");

    /**
     * @param $context VTEntity
     */
    public function handleTask(&$context) {
        if(!getTabid('PDFMaker') || !vtlib_isModuleActive('PDFMaker')) {
            return 'yes';
        }

        global $adb, $current_user,$log, $root_directory;
        // PDFMaker greift auf Datenbank zurück. Daher zuerst speichern!
        $context->save();

        $userId = $context->get('assigned_user_id');
        if($userId===null){
            $userId = vtws_getWebserviceEntityId('Users', 1);;
        }

        $moduleName = $context->getModuleName();

        $id = $context->getId();

        list($id2, $assigned_user_id) = explode("x",$userId);
        $parentid = $id;

        require_once('modules/Documents/Documents.php');
        $focus = new \Documents();

        $focus->parentid = $parentid;

        $modFocus = $context->getInternalObject();

        $templateid = $this->template;
        $this->folder = 1;

        $foldername=$adb->getOne("SELECT foldername FROM vtiger_attachmentsfolder WHERE folderid='".$this->folder."'",0,"foldername");

        $fieldname=$adb->getOne("SELECT fieldname FROM vtiger_field WHERE uitype=4 AND tabid=".getTabId($moduleName),0,"fieldname");
        /* new PDFMaker Routine */
        $PDFMaker = new PDFMaker_PDFMaker_Model();

        if(isset($modFocus->column_fields[$fieldname]) && $modFocus->column_fields[$fieldname]!="")
        {
            $file_name = $PDFMaker->generate_cool_uri($modFocus->column_fields[$fieldname]).".pdf";
        }
        else
        {
            $file_name = generate_cool_uri($foldername."_".$templateid.$focus->parentid.date("ymdHi")).".pdf";
        }

        $this->addStat("Attach Document '".$file_name."'");

        $docTitle = $this->get("documenttitle", $context);
        $docDescr = $this->get("documentdescr", $context);

        $focus->column_fields['notes_title'] = $docTitle;
        $focus->column_fields['assigned_user_id'] = $assigned_user_id;
        $focus->column_fields['filename'] = $file_name;
        $focus->column_fields['notecontent'] = $docDescr;
        $focus->column_fields['filetype'] = 'application/pdf';
        $focus->column_fields['filesize'] = '';
        $focus->column_fields['filelocationtype'] = 'I';
        $focus->column_fields['fileversion'] = '';
        $focus->column_fields['filestatus'] = 'on';
        $focus->column_fields['folderid'] = $this->get("folderid");

      	$focus->save('Documents');

        $language = $current_user->language;

        $request = $_REQUEST;
        $_REQUEST['search'] = true;
        $_REQUEST['submode'] = true;

        if($current_user->is_admin != "on") {
            $useUser = Users::getActiveAdminUser();
        } else {
            $useUser = $current_user;
        }
        $oldCurrentUser = $current_user;
        $current_user = $useUser;

        $dummyRequest = new Vtiger_Request(array());
        $PDFMaker->createPDFAndSaveFile($dummyRequest, $this->get("template"),$focus,$modFocus,$file_name,$this->getModuleName(),$language);

        $current_user = $oldCurrentUser;

        $_REQUEST = $request;
        /* new PDFMaker Routine */

        $overwriteFilename = $this->get("filename", $context);

        if($overwriteFilename != -1 && !empty($overwriteFilename)) {
            global $root_directory;
            $sql = "SELECT attachmentsid FROM vtiger_seattachmentsrel WHERE crmid = ".$focus->id." ORDER BY attachmentsid DESC LIMIT 1";
            $result = $adb->query($sql);
            if($adb->num_rows($result) > 0) {
                $attachmentsid = $adb->query_result($result, 0, "attachmentsid");
                $attRst = $adb->query("SELECT * FROM vtiger_attachments WHERE attachmentsid = ".$attachmentsid);
                $attachment = $adb->fetchByAssoc($attRst);

                $oldFilename = $root_directory."/".$attachment["path"].$attachmentsid."_".$attachment["name"];
                $newFilename = $root_directory."/".$attachment["path"].$attachmentsid."_".$overwriteFilename;
                @rename($oldFilename, $newFilename);

                $adb->pquery("UPDATE vtiger_attachments SET name = ? WHERE attachmentsid = ".$attachmentsid, array($overwriteFilename));
                $adb->pquery("UPDATE vtiger_notes SET filename = ? WHERE notesid = ".$focus->id, array($overwriteFilename));
            }
            $file_name = $foldername."_".$overwriteFilename;
        }


        $_REQUEST = $request;

        if($this->get("createrel") === "1") {
            $sql = "INSERT INTO vtiger_senotesrel SET crmid = ".$context->getId().", notesid = ".$focus->id;
            $adb->query($sql);
        } else {
            $sql = "DELETE FROM vtiger_senotesrel WHERE crmid = ".$context->getId()." AND notesid = ".$focus->id;
            $adb->query($sql);
        }

        $newContext = \Workflow\VTEntity::getForId($focus->id, "Documents");

        if($this->get("workflow") !== "") {
            $objWorkflow = new \Workflow\Main($this->get("workflow"), false, $context->getUser());

            $objWorkflow->setContext($newContext);
            $objWorkflow->isSubWorkflow(true);

            $objWorkflow->start();
        }

        $context->setEnvironment("new_record_id", $newContext->getWsId(), $this);

        return "yes";
    }

    public function beforeGetTaskform($viewer) {
        global $adb;

        if(!getTabid('PDFMaker') || !vtlib_isModuleActive('PDFMaker')) {
            $viewer->assign('disable', true);
            $this->addConfigHint(getTranslatedString('LBL_FOR_THIS_YOU_NEED_PDFMAKER','Settings:Workflow2'));
            return;
        }

        require_once('modules/PDFMaker/helpers/Version.php');
        $viewer->assign('PDFMAKER_VERSION', PDFMaker_Version_Helper::$version);

        $sql = "SELECT folderid, foldername FROM vtiger_attachmentsfolder ORDER BY sequence";
        $result = $adb->query($sql);

        $folders = array();
        while($row = $adb->fetch_array($result)) {
            $folders[] = $row;
        }

        $viewer->assign("folders", $folders);

        $sql = "SELECT templateid, filename, description FROM vtiger_pdfmaker WHERE module = '".$this->getModuleName()."'";
        $result = $adb->query($sql);

        $templates = array();
        while($row = $adb->fetch_array($result)) {
            $templates[] = $row;
        }

        $viewer->assign("templates", $templates);

        $workflows = \Workflow2::getWorkflowsForModule("Documents", 1);
        $viewer->assign("workflows", $workflows);
    }

    public function beforeSave(&$values) {

    }}
