<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan Warnat <support@stefanwarnat.de>
 * Date: 20.09.14 23:15
 * You must not use this file without permission.
 */
namespace Workflow\Plugins\Mailattachments;

class External extends \Workflow\Attachment {

    public function getConfigurations($moduleName) {
        $availableAttachments = \Workflow\InterfaceFiles::getAvailableFiles($moduleName);

        $selectHTML = '<select id="attachFromExternalModuleValue" name="attachFromExternalModuleValue" class="select2" style="width:300px;">';
        foreach($availableAttachments as $title => $group) {
            $selectHTML .= '<optgroup label="'.$title.'">';
                foreach($group as $key => $name) {
                    $selectHTML .= '<option value="'.$key.'">'.$name.'</option>';
                }
            $selectHTML .= '</optgroup>';
        }
        $selectHTML .= '</select>';

        $configuration = array(
            'html' => '<a href="#" onclick="attachFromExternalModuleStart();" title="like PDFMaker/SQLReports">use external Module to generate File</a>
                <div id="attachFromExternalModuleContainer" style="display: none;">'.$selectHTML.'&nbsp;<br/><button type="button" class="btn btn-primary" onclick="attachFromExternalModuleFinish();">add</button><input type="button" class="btn btn-warning" onclick="jQuery(\'#attachFromExternalModuleContainer\').hide();" value="Cancel"> </div>

            ',
            'script' => "
            function attachFromExternalModuleStart() {
                jQuery('#attachFromExternalModuleContainer').show();
            }
            function attachFromExternalModuleFinish() {
                var value = jQuery('#attachFromExternalModuleValue').val();
                var title = jQuery('#attachFromExternalModuleValue option:selected').text();

                Attachments.addAttachment('s#external#' + value, title, '',{val: value});
                jQuery('#attachFromExternalModuleContainer').hide();
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

        $file = \Workflow\InterfaceFiles::getFile($value[2]['val'], $context->getModuleName(), $context->getId());

        if($this->_mode === self::MODE_NOT_ADD_NEW_ATTACHMENTS) {
            $this->addAttachmentRecord('PATH', $file['path'], $file['name']);
            return;
        }

        $upload_file_path = decideFilePath();

        $next_id = $adb->getUniqueID("vtiger_crmentity");

        copy($file['path'], $upload_file_path . $next_id . "_" . $file['name']);

        $filetype = "application/octet-stream";

        $sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?, ?, ?, ?, ?, ?, ?)";
        $params1 = array($next_id, $current_user->id, $current_user->id, "Workflow Attachment",'Workflow Attachment', date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));

        $adb->pquery($sql1, $params1);

        $sql2 = "insert into vtiger_attachments(attachmentsid, name, description, type, path) values(?, ?, ?, ?, ?)";
        $params2 = array($next_id, $file['name'],'', $filetype, $upload_file_path);
        $adb->pquery($sql2, $params2);

        $this->addAttachmentRecord('ID', $next_id);
    }

}

\Workflow\Attachment::register('external', '\Workflow\Plugins\Mailattachments\External');