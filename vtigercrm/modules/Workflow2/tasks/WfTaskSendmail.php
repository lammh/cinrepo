<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));
require_once('modules/Emails/mail.php');

/* vt6 ready 2014/04/09 */
class WfTaskSendmail extends \Workflow\Task {
    protected $_envSettings = array("sendmail_result");
    private $_mailRecord = null;

    public function beforeGetTaskform($viewer) {
        global $adb;
        $connected = $this->getConnectedObjects("Absender");

        if(count($connected) > 0) {
            $viewer->assign("from", array(
                "from_mail" => $connected[0]->get("email1"),
                "from_name" => trim($connected[0]->get("first_name")." ".$connected[0]->get("last_name")),
                "from_readonly" => true,
            ));
        }

//        $smtpServer = \Workflow\ConnectionProvider::getAvailableConfigurations('smtp');
//        var_dump($smtpServer);exit();


        $connected = $this->getConnectedObjects("BCC");

        $bccs = $connected->get("email1");
        $viewer->assign("bccs", $bccs);

        $from_email = $this->get("from_mail");
        if($from_email === -1) {
            global $current_user;

            $from_email = $current_user->column_fields["email1"];

            $from_name = $current_user->column_fields["first_name"]." ".$current_user->column_fields["last_name"];
            $this->set("from_mail", $from_email);
            $this->set("from_name", $from_name);
        }

        $availableSpecialAttachments = \Workflow\Attachment::getAvailableOptions($this->getModuleName());
        $attachmentHTML = array();
        $attachmentJAVASCRIPT = array();

        foreach($availableSpecialAttachments as $item) {
            $attachmentHTML[] = '<div>'.$item['html'].'</div>';
            $attachmentJAVASCRIPT[] = !empty($item['script'])?$item['script']:'';
        }

        // implode the array to one string
        $viewer->assign('attachmentsHTML', implode("\n", $attachmentHTML));
        // transmit array to create single script tags
        $viewer->assign('attachmentsJAVASCRIPT', $attachmentJAVASCRIPT);

        if(vtlib_isModuleActive('Emails')) {
            $availableAttachments = \Workflow\InterfaceFiles::getAvailableFiles($this->getModuleName());
        } else {
            throw new \Exception('To use this task, you need to activate the "Emails" module.');
        }

        $jsList = array();
        foreach($availableAttachments as $title => $group) {
            foreach($group as $index => $value) {
                $jsList[$index] = $value;
            }
        }
        $viewer->assign("jsAttachmentsList", $jsList);
        $viewer->assign("available_attachments", $availableAttachments);

        if($this->get("attachments") == -1) {
            $this->set("attachments", '{}');
        }
        if($this->get("attachments") == "") {
            $this->set("attachments", '{}');
        }

        $sql = "SELECT * FROM vtiger_emailtemplates WHERE deleted = 0";
        $result = $adb->query($sql);
        $mailtemplates = array();
        while($row = $adb->fetchByAssoc($result)) {
            $mailtemplates['Email Templates'][$row["templateid"]] = $row["templatename"];
        }

        if(vtlib_isModuleActive('EMAILMaker') && class_exists('EMAILMaker_Module_Model')) {
            $emailmaker = new \EMAILMaker_Module_Model();
            if(method_exists($emailmaker, 'GetAvailableTemplates')) {
                $templates = $emailmaker->GetAvailableTemplates($this->getModuleName());

                foreach($templates as $categoryTitle => $category) {
                    if(!is_array($category)) {
                        $mailtemplates['EMAILMaker']['s#emailmaker#'.$categoryTitle] = $category;
                    } else {
                        foreach($category as $templateid => $template) {
                            $mailtemplates['EMAILMaker '.$categoryTitle]['s#emailmaker#'.$templateid] = $template;
                        }
                    }
                }
            }
        }

        $viewer->assign("MAIL_TEMPLATES", $mailtemplates);
        $viewer->assign("fields", \VtUtils::getFieldsWithBlocksForModule($this->getModuleName(), true));

        if(defined("WF_DEMO_MODE") && constant("WF_DEMO_MODE") == true) {
            echo "<p style='text-align:center;margin:0;padding:5px 0;background-color:#fbcb09;font-weight:bold;'>The sendmail Task won't work on demo.stefanwarnat.de</p>";
        }
    }

    /**
     * @param $context \Workflow\VTEntity
     * @return mixed
     */
    public function handleTask(&$context) {
        global $adb, $current_user;
        global $current_language;

        if(defined("WF_DEMO_MODE") && constant("WF_DEMO_MODE") == true) {
            return "yes";
        }

        if(!class_exists("Workflow_PHPMailer")) {
            require_once("modules/Workflow2/phpmailer/class.phpmailer.php");
        }

        #$result = $adb->query("select user_name, email1, email2 from vtiger_users where id=1");
        #$from_email = "swarnat@praktika.de";
        #$from_name  = "Stefan Warnat";

        $module = $context->getModuleName();

        $et = new \Workflow\VTTemplate($context);
        $to_email = $et->render(trim($this->get("recepient")),","); #

        $connected = $this->getConnectedObjects("Absender");
        if(count($connected) > 0) {
            $from_name = trim($connected[0]->get("first_name")." ".$connected[0]->get("last_name"));
            $from_email = $connected[0]->get("email1");
        } else {
            $from_name = $et->render(trim($this->get("from_name")),","); #
            $from_email = $et->render(trim($this->get("from_mail")),","); #
        }

        $cc = $et->render(trim($this->get("emailcc")),","); #
        $bcc = $et->render(trim($this->get("emailbcc")),","); #

        /**
         * Connected BCC Objects
         * @var $connected
         */
        $connected = $this->getConnectedObjects("BCC");
        $bccs = $connected->get("email1");
        if(count($bccs) > 0) {
            $bcc = array($bcc);

            foreach($bccs as $bccTMP) {
                $bcc[] = $bccTMP;
            }
            $bcc = trim(implode(",",$bcc),",");
        }

        if(strlen(trim($to_email, " \t\n,")) == 0 && strlen(trim($cc, " \t\n,")) == 0 && strlen(trim($bcc, " \t\n,")) == 0) {
            return "yes";
        }
        $storeid = trim($this->get("storeid", $context));
        if(empty($storeid) || $storeid == -1 || !is_numeric($storeid)) {
            $storeid = $context->getId();
        }

        $embeddedImages = array();

        $content = $this->get("content");
        $subject = $this->get("subject");

        #$subject = utf8_decode($subject);
        #$content = utf8_encode($content);

        $content = html_entity_decode(str_replace("&nbsp;", " ", $content), ENT_QUOTES, "UTF-8");
        #$subject = html_entity_decode(str_replace("&nbsp;", " ", $subject), ENT_QUOTES, "UTF-8");

        $subject = $et->render(trim($subject));
        $content = $et->render(trim($content));

        $mailtemplate = $this->get("mailtemplate");
        if(!empty($mailtemplate) && $mailtemplate != -1) {
            if(strpos($mailtemplate, 's#') === false) {
                $sql = "SELECT * FROM vtiger_emailtemplates WHERE templateid = ".intval($mailtemplate);
                $result = $adb->query($sql);
                $mailtemplate = $adb->fetchByAssoc($result);
                $content = str_replace('$mailtext', $content, html_entity_decode($mailtemplate["body"], ENT_COMPAT, 'UTF-8'));
                $content = Vtiger_Functions::getMergedDescription($content, $context->getId(), $context->getModuleName());
            } else {
                $parts = explode('#', $mailtemplate);
                switch($parts[1]) {
                    case 'emailmaker':
                        $templateid = $parts[2];

                        $sql = 'SELECT body, subject FROM vtiger_emakertemplates WHERE templateid = ?';
                        $result = $adb->pquery($sql, array($templateid));

                        $EMAILContentModel = \EMAILMaker_EMAILContent_Model::getInstance($this->getModuleName(), $context->getId(), $current_language, $context->getId(), $this->getModuleName());
                        $EMAILContentModel->setSubject($adb->query_result($result, 0, 'subject'));
                        $EMAILContentModel->setBody($adb->query_result($result, 0, 'body'));
                        $EMAILContentModel->getContent(true);
                        $embeddedImages = $EMAILContentModel->getEmailImages();

                        $subject = $EMAILContentModel->getSubject();
                        $content = $EMAILContentModel->getBody();

                        break;
                }
            }
        }
        #$content = htmlentities($content, ENT_NOQUOTES, "UTF-8");

        if(getTabid('Emails') && vtlib_isModuleActive('Emails')) {
            require_once('modules/Emails/Emails.php');
            $focus = new Emails();

            $focus->column_fields["assigned_user_id"] = \Workflow\VTEntity::getUser()->id;
            $focus->column_fields["activitytype"] = "Emails";
            $focus->column_fields["date_start"] = date("Y-m-d");
            $focus->column_fields["parent_id"] = $storeid;
            $focus->column_fields["email_flag"] = "SAVED";

            $focus->column_fields["subject"] = $subject;
            $focus->column_fields["description"] = $content;
            $focus->column_fields["from_email"] = $from_email;
            $focus->column_fields["saved_toid"] = '["'.str_replace(',','","',trim($to_email,",")).'"]';

            $focus->column_fields["ccmail"] = $cc;
            $focus->column_fields["bccmail"] = $bcc;

            $focus->save("Emails");
            $this->_mailRecord = $focus;

            #error_log("eMail:".$emailID);

            $emailID = $focus->id;
        } else {
            $emailID = "";
        }

        $attachments = json_decode($this->get("attachments"), true);
        if(is_array($attachments) && count($attachments) > 0) {
            // Module greifen auf Datenbank zurück. Daher vorher speichern!
            $context->save();

            foreach($attachments as $key => $value) {
                if($value == false) {
                    continue;
                }

                if(is_string($value)) { $value = array($value, false, array()); }

                // legacy check
                if(strpos($key, 'document#') === 0) {
                    $key = 's#'.$key;
                }

                if(strpos($key, 's#') === 0) {
                    $tmpParts = explode('#', $key, 2);

                    $specialAttachments = \Workflow\Attachment::getAttachments($tmpParts[1], $value, $context, \Workflow\Attachment::MODE_NOT_ADD_NEW_ATTACHMENTS);

                    foreach($specialAttachments as $attachment) {
                        if($attachment[0] === 'ID') {
                            $this->attachByAttachmentId($attachment[1]);
                        } elseif($attachment[0] === 'PATH') {
                            $this->attachFile($attachment[1], $attachment[2], $attachment[3]);
                        }
                    }

                } else {
                    $file = \Workflow\InterfaceFiles::getFile($key, $this->getModuleName(), $context->getId());
                    $this->attachFile($file['path'], $value[1] != false ? $value[1] : $file['name'], $file['type']);

                }
            }

        }

        $receiver = explode(",", $to_email);
        foreach($receiver as $to_email) {
            $to_email = trim($to_email);

            if(empty($to_email))
                continue;

            if(DEMO_MODE == false) {
                // Self using
                $mail = new Workflow_PHPMailer();
                $mail->CharSet = 'utf-8';
                $mail->IsSMTP();

                foreach ($embeddedImages AS $cid => $cdata) {
                    $mail->AddEmbeddedImage($cdata["path"], $cid, $cdata["name"]);
                }

                setMailServerProperties($mail);

                $to_email = trim($to_email,",");
                #setMailerProperties($mail,$subject, $content, $from_email, $from_name, trim($to_email,","), "all", $emailID);

                $mail->Timeout = 60;

                $mail->FromName = $from_name;
                $mail->From = $from_email;
                $this->addStat("From: ".$from_name." &lt;".$from_email."&gt;");

                if($this->get('trackAccess') == '1') {
                    //Including email tracking details
                    global $site_URL, $application_unique_key;
                    $counterUrl = $site_URL.'/modules/Emails/actions/TrackAccess.php?parentId='.$storeid.'&record='.$focus->id.'&applicationKey='.$application_unique_key;
                    $counterHeight = 1;
                    $counterWidth = 1;
                    if(defined('TRACKING_IMG_HEIGHT')) {
                        $counterHeight = TRACKING_IMG_HEIGHT;
                    }
                    if(defined('TRACKING_IMG_WIDTH')) {
                        $counterWidth = TRACKING_IMG_WIDTH;
                    }
                    $content = "<img src='".$counterUrl."' alt='' width='".$counterWidth."' height='".$counterHeight."'>".$content;
                }

                $mail->Subject = $subject;
                $this->addStat("Subject: ".$subject);
                $mail->MsgHTML($content);

                $mail->SMTPDebug = 2;

                $mail->addAddress($to_email);
                $this->addStat("To: ".$to_email);

                setCCAddress($mail,'cc',$cc);
               	setCCAddress($mail,'bcc',$bcc);
                #$mail->IsHTML(true);

                addAllAttachments($mail, $emailID);

                try {
                    ob_start();
                    $mail_return = MailSend($mail);

                    $debug = ob_get_clean();
                    $this->addStat($debug);
                } catch(Workflow_phpmailerException $exp) {
                    Workflow2::error_handler($exp->getCode(), $exp->getMessage(), $exp->getFile(), $exp->getLine());
                }

                #$mail_return = send_mail($module, $to_email,$from_name,$from_email,$subject,$content, $cc, $bcc,'all',$emailID);
            } else {
                $mail_return = 1;
            }

            $this->addStat("Send eMail with following Result:");
            $this->addStat($mail_return);

            if($mail_return != 1) {
                if (empty($mail->ErrorInfo) && empty($mail_return)) {
                    $mail_return = 1;
                }
            }

            $context->setEnvironment("sendmail_result", $mail_return, $this);

            if($mail_return != 1) {
                if($this->isContinued()) $delay = 180; else $delay = 60;
                Workflow2::send_error("Sendmail Task couldn't send an email to ".$to_email."<br>Error: ".var_export($mail->ErrorInfo, true)."<br><br>The Task will be rerun after ".$delay." minutes.", __FILE__, __LINE__);
                Workflow2::error_handler(E_NONBREAK_ERROR, "Sendmail Task couldn't send an email to ".$to_email. "<br>Error: ".var_export($mail->ErrorInfo, true)."<br><br>The Task will be rerun after ".$delay." minutes.", __FILE__, __LINE__);
                return array("delay" => time() + ($delay * 60), "checkmode" => "static");
            }

        }

        // Set Mails as Send
        $sql = "UPDATE vtiger_emaildetails SET email_flag = 'SENT' WHERE emailid = '".$emailID."'";
        $adb->query($sql);

        return "yes";
    }

    public function attachByAttachmentId($attachmentID) {
        $adb = \PearDatabase::getInstance();
        $sql = 'select crmid from vtiger_seattachmentsrel WHERE crmid = ? AND attachmentsid = ?';
        $result = $adb->pquery($sql, array($this->_mailRecord->id,  $attachmentID));
        if($adb->num_rows($result) == 0) {

            $sql3='insert into vtiger_seattachmentsrel values(?,?)';
            $adb->pquery($sql3, array($this->_mailRecord->id,  $attachmentID));

        }
    }
    public function attachFile($filePath, $filename, $filetype) {
        if(null === $this->_mailRecord) {
            return;
        }

        $adb = \PearDatabase::getInstance();
        $current_user = \Users_Record_Model::getCurrentUserModel();

        $upload_file_path = decideFilePath();

        $date_var = date("Y-m-d H:i:s");
        $next_id = $adb->getUniqueID("vtiger_crmentity");

        if(is_array($filename)) {
            if(!empty($filename['filename'])) {
                $filename = $filename['filename'];
            } else {
                $filename = 'unknown-filename.txt';
            }
        }

        rename($filePath, $upload_file_path . $next_id . "_" . $filename);

        $sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?, ?, ?, ?, ?, ?, ?)";
        $params1 = array($next_id, $current_user->id, $current_user->id, "Documents Attachment",'Documents Attachment', date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));

        $adb->pquery($sql1, $params1);

        $sql2 = "insert into vtiger_attachments(attachmentsid, name, description, type, path) values(?, ?, ?, ?, ?)";
        $params2 = array($next_id, $filename, $this->_mailRecord->column_fields["description"], $filetype, $upload_file_path);
        $adb->pquery($sql2, $params2, true);

        $sql3 = 'insert into vtiger_seattachmentsrel values(?,?)';
        $adb->pquery($sql3, array($this->_mailRecord->id, $next_id));
    }

}