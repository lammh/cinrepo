<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan Warnat <support@stefanwarnat.de>
 * Date: 27.04.15 12:26
 * You must not use this file without permission.
 */
if(!function_exists("wf_combine_comments")) {
    function wf_combine_comments($crmid) {
        global $adb, $default_charset;

        $sql = "SELECT *
           FROM
               vtiger_modcomments
           INNER JOIN vtiger_crmentity
               ON (vtiger_crmentity.crmid = vtiger_modcomments.modcommentsid)
           INNER JOIN vtiger_users
               ON (vtiger_users.id = vtiger_crmentity.smownerid)
           WHERE related_to = ".$crmid." AND vtiger_crmentity.deleted = 0 ORDER BY createdtime DESC";
        $result = $adb->query($sql, true);

        $html = "";
        while($row = $adb->fetchByAssoc($result)) {
           if(!empty($row['customer'])) {

           }
            $html .= "<div style='font-size:12px;'><strong>Kommentar von ".(!empty($row['customer'])?Vtiger_Functions::getCRMRecordLabel($row['customer']):$row["first_name"]." ".$row["last_name"])." geschrieben ".date("d.m.Y H:i:s", strtotime($row["createdtime"]))."</strong><br>";
           $html .= nl2br($row["commentcontent"])."</div><br><br>";
        }

        return $html;
    }
}
