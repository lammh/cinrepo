<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan Warnat <support@stefanwarnat.de>
 * Date: 20.09.14 23:15
 * You must not use this file without permission.
 */
namespace Workflow\Plugins\Mailattachments;

class Filestore extends \Workflow\Attachment {

    public function getConfigurations($moduleName) {
        $configuration = array(
            'html' => '<a href="#" onclick="attachFromFilestoreStart(); ">Attach File from temporarily filestore</a>
                <div id="attachFromFilestoreContainer" style="display: none;"><div class="insertTextfield" style="display:inline;" data-placeholder="Filestore ID" data-name="attachFromFilestoreValue" data-style="width:150px;" data-id="attachFromFilestoreValue"></div>&nbsp;<button type="button" class="btn btn-primary" onclick="attachFromFilestoreFinish();">add</button><input type="button" class="btn btn-warning" onclick="jQuery(\'#attachFromFilestoreContainer\').hide();" value="Cancel"> </div>

            ',
            'script' => "
            function attachFromFilestoreStart() {
                jQuery('#attachFromFilestoreContainer').show();
            }
            function attachFromFilestoreFinish() {
                var value = jQuery('#attachFromFilestoreValue').val();
                Attachments.addAttachment('s#filestore#' + value, '<strong>' + value + '</strong> from filestore', '',{val:value});
                jQuery('#attachFromFilestoreContainer').hide();
            }        ");

        $return  = array($configuration);


        return $return;
    }

    /**
     * @param $key
     * @param $value
     * @param $context \Workflow\VTEntity
     * @return array|void
     */
    public function generateAttachments($key, $value, $context) {
        global $current_user;
        $adb = \PearDatabase::getInstance();

        $filestoreid = $value[2]['val'];
        $filestoreid = \Workflow\VTTemplate::parse($filestoreid, $context);

        $file = $context->getTempFiles($filestoreid);

        $filename = preg_replace('/[^A-Za-z0-9-_.]/', '_', $file['name']);

        if($this->_mode === self::MODE_NOT_ADD_NEW_ATTACHMENTS) {
            $this->addAttachmentRecord('PATH', $file['path'], $filename);
            return;
        }

        $upload_file_path = decideFilePath();

        $next_id = $adb->getUniqueID("vtiger_crmentity");

        copy($file['path'], $upload_file_path . $next_id . "_" . $filename);

        $filetype = "application/octet-stream";

        $sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?, ?, ?, ?, ?, ?, ?)";
        $params1 = array($next_id, $current_user->id, $current_user->id, "Documents Attachment",'Documents Attachment', date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));

        $adb->pquery($sql1, $params1);

        $sql2 = "insert into vtiger_attachments(attachmentsid, name, description, type, path) values(?, ?, ?, ?, ?)";
        $params2 = array($next_id, $filename,'', $filetype, $upload_file_path);
        $adb->pquery($sql2, $params2);

        $this->addAttachmentRecord('ID', $next_id);
    }

}

\Workflow\Attachment::register('filestore', '\Workflow\Plugins\Mailattachments\Filestore');