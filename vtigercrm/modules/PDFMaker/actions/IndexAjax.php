<?php

/* * *******************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class PDFMaker_IndexAjax_Action extends Vtiger_Action_Controller {

    function __construct(){
        parent::__construct();
        
        $Methods = array('checkDuplicateKey','SaveCustomLabel','SaveCustomLabelValues','DeleteCustomLabels',
                         'SaveProductBlock','deleteProductBlocks','downloadMPDF','downloadFile','installExtension',
                         'savePDFBreakline','CheckDuplicateTemplateName','ChangeActiveOrDefault');
        
        foreach ($Methods AS $method){
            $this->exposeMethod($method);
        }
    }

    function checkPermission(Vtiger_Request $request) {
		return;
	}
    
    function process(Vtiger_Request $request) {
        $mode = $request->get('mode');
        if(!empty($mode)) {
                $this->invokeExposedMethod($mode, $request);
                return;
        }
        
        $type = $request->get('type');
    }
    
    public function checkDuplicateKey(Vtiger_Request $request){
        
        $adb = PearDatabase::getInstance();
        $lblkey = $request->get('lblkey');

        $sql = "SELECT label_id FROM vtiger_pdfmaker_label_keys WHERE label_key=?";
        $result = $adb->pquery($sql, array("C_".$lblkey));
        $num_rows = $adb->num_rows($result);
        
        if ($num_rows > 0){
             $result = array('success' => true, 'message' => vtranslate('LBL_LABEL_KEY_EXIST', 'PDFMaker'));
        } else {
             $result = array('success' => false);
        }

        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }
	
    function SaveCustomLabel(Vtiger_Request $request){
        
        $adb = PearDatabase::getInstance();
        
        $PDFMaker = new PDFMaker_PDFMaker_Model();
    
        $labelid = $request->get('labelid');
        $langid = $request->get('langid');
        $LblVal = $request->get('LblVal');

        if ($labelid == ""){    
            $LblKey = $request->get('LblKey');
            $label_key = "C_" . $LblKey;
            
            $sql1 = "INSERT IGNORE INTO vtiger_pdfmaker_label_keys (label_key) VALUES (?)";
            $adb->pquery($sql1, array($label_key));

            $sql2 = "SELECT label_id FROM vtiger_pdfmaker_label_keys WHERE label_key=?";
            $labelid = $adb->query_result($adb->pquery($sql2, array($label_key)), 0, "label_id");

            $sql3 = "INSERT IGNORE INTO vtiger_pdfmaker_label_vals (label_id, lang_id, label_value) VALUES (?, ?, ?)";
            $adb->pquery($sql3, array($labelid, $langid, $LblVal));
        } else {
            $sql4 = "UPDATE vtiger_pdfmaker_label_vals SET label_value = ? WHERE label_id = ? AND lang_id = ?";
            $adb->pquery($sql4, array($LblVal, $labelid, $langid));
        }
        
        $response = new Vtiger_Response();
        try {
            $response->setResult(array('labelid' => $labelid,'langid' => $langid,'langid' => $langid,'lblval' => $LblVal,'lblkey'=>$label_key));
            //$response->setResult(array_merge(array('type' => $recordModel->getType()),$recordModel->getData()));
        } catch (Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }
    
    function SaveCustomLabelValues(Vtiger_Request $request){
        $test = "";
        $adb = PearDatabase::getInstance();
        
        $PDFMaker = new PDFMaker_PDFMaker_Model();
    
        $lblkey = $request->get('lblkey');
        
        $sql1 = "SELECT label_id FROM vtiger_pdfmaker_label_keys WHERE label_key = ?";
        $result1 = $adb->pquery($sql1, array($lblkey));
        $labelid = $adb->query_result($result1,0,"label_id");
        
        list($oLabels, $languages) = $PDFMaker->GetCustomLabels();
        $oLbl = $oLabels[$labelid];
        $langValsArr = $oLbl->GetLangValsArr();
        
        foreach ($langValsArr as $langid => $langVal){
            $control = $request->get('LblVal'.$langid);
            
            if ($control == "yes"){
                $langval = $request->get('LblVal'.$langid."Value");
                
                $sql2 = "SELECT * FROM vtiger_pdfmaker_label_vals WHERE label_id = ? AND lang_id = ?";
                $result2 = $adb->pquery($sql1, array($labelid,$langid));
                $num_rows2 = $adb->num_rows($result2);  
                 
                if ($num_rows2 > 0){
                    $sql3 = "UPDATE vtiger_pdfmaker_label_vals SET label_id = ? WHERE lang_id = ?, label_value=?";
                    $adb->pquery($sql3, array($langval, $labelid, $langid));
                } else {                    
                    if ($langval != ""){
                        $sql4 = "INSERT INTO vtiger_pdfmaker_label_vals (label_id,lang_id,label_value) VALUES  (?,?,?)";
                        $adb->pquery($sql4, array($labelid, $langid, $langval));
                    }
                }
            }
        }
        
        $response = new Vtiger_Response();
        try {
            $response->setResult(array('success' => true));
        } catch (Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }
    
    public function DeleteCustomLabels(Vtiger_Request $request){
        $sql1 = "DELETE FROM vtiger_pdfmaker_label_vals WHERE label_id IN (";
        $sql2 = "DELETE FROM vtiger_pdfmaker_label_keys WHERE label_id IN (";
        $params = array();
        foreach ($_REQUEST as $key => $val){
            if (substr($key, 0, 4) == "chx_" && $val == "on") {
                list($dump, $id) = explode("_", $key, 2);
                if (is_numeric($id)){
                    $sql1 .= "?,";
                    $sql2 .= "?,";
                    array_push($params, $id);
                }
            }
        }
        if (count($params) > 0){
            $adb = PearDatabase::getInstance();
            $sql1 = rtrim($sql1, ",") . ")";
            $sql2 = rtrim($sql2, ",") . ")";
            $adb->pquery($sql1, $params);
            $adb->pquery($sql2, $params);
        }
        header("Location:index.php?module=PDFMaker&view=CustomLabels");
    }
    
    public function SaveProductBlock(Vtiger_Request $request){
        PDFMaker_Debugger_Model::GetInstance()->Init();
        $adb = PearDatabase::getInstance();
        
        $tplid = $request->get('tplid');
        $template_name = $request->get('template_name');
        $body = $request->get('body'); 
        
        if (isset($tplid) && $tplid != "") {
            $sql = "UPDATE vtiger_pdfmaker_productbloc_tpl SET name=?, body=? WHERE id=?";
            $adb->pquery($sql, array($template_name, $body, $tplid));
        } else {
            $sql = "INSERT INTO vtiger_pdfmaker_productbloc_tpl(name, body) VALUES(?,?)";
            $adb->pquery($sql, array($template_name, $body));
        }
        header("Location:index.php?module=PDFMaker&view=ProductBlocks");
    }
    
    public function deleteProductBlocks(Vtiger_Request $request) {
        PDFMaker_Debugger_Model::GetInstance()->Init();
        $adb = PearDatabase::getInstance();
        
        $sql = "DELETE FROM vtiger_pdfmaker_productbloc_tpl WHERE id IN (";
        $params = array();
        foreach ($_REQUEST as $key => $val){
            if (substr($key, 0, 4) == "chx_" && $val == "on") {
                list($dump, $id) = explode("_", $key, 2);
                if (is_numeric($id)) {
                    $sql .= "?,";
                    array_push($params, $id);
                }
            }
        }
        if (count($params) > 0){
            $sql = rtrim($sql, ",") . ")";
            $adb->pquery($sql, $params);
        }
        header("Location:index.php?module=PDFMaker&view=ProductBlocks");
    }
    
    public function downloadMPDF(Vtiger_Request $request){
        $error == "";
        $srcZip = "http://www.crm4you.sk/PDFMaker/src/mpdf.zip";
        $trgZip = "modules/PDFMaker/resources/mpdf.zip";
        if (copy($srcZip, $trgZip)){
            require_once('vtlib/thirdparty/dUnzip2.inc.php');
            $unzip = new dUnzip2($trgZip);
            $unzip->unzipAll(getcwd() . "/modules/PDFMaker/resources/");
            if ($unzip)
                $unzip->close();
            if (!is_dir("modules/PDFMaker/resources/mpdf")){
                $error = vtranslate("UNZIP_ERROR", 'PDFMaker');
                $viewer->assign("STEP", "error");
                $viewer->assign("ERROR_TBL", $errTbl);
            } 
        } else {
            $error = vtranslate("DOWNLOAD_ERROR", 'PDFMaker');
        }
        if ($error == ""){
             $result = array('success' => true, 'message' => '');
        } else {
             $result = array('success' => false, 'message' => $error);
        }
        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    } 
    
    public function downloadFile(Vtiger_Request $request){
        $type= $request->get('type');
        $extid= $request->get('extid');
        $fileext = "";
        $ct = "";
        switch ($type) {
            case "manual":
                $fileext = "txt";
                $ct = "text/plain";
                break;
            case "download":
                $fileext = "zip";
                $ct = "application/zip";
                break;
        }

        $filename = $extid . "." . $fileext;
        $fullFileName = "modules/PDFMaker/resources/extensions/" . $filename;
        if (file_exists($fullFileName)){
            $disk_file_size = filesize($fullFileName);
            $filesize = $disk_file_size + ($disk_file_size % 1024);
            $fileContent = fread(fopen($fullFileName, "r"), $filesize);
            header("Content-type: $ct");
            header("Pragma: public");
            header("Cache-Control: private");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Description: PHP Generated Data");
            echo $fileContent;
        } else {
            header("Location: index.php?module=PDFMaker&view=Extensions&parenttab=Settings&download_error=true");
        }
    } 
    
    public function installExtension(Vtiger_Request $request){
        
        $extname = $request->get("extname");
        
        if ($extname == "Workflow"){    
            $Errors = array();
            include_once ('modules/PDFMaker/PDFMaker.php');
            $PDFMaker = new PDFMaker();

            $PDFMakerModel = new PDFMaker_PDFMaker_Model();
            $Workflows = $PDFMakerModel->GetWorkflowsList(); 

            foreach ($Workflows AS $name){   
                $folder_dest1 = "modules/com_vtiger_workflow/tasks/";
                $dest1 = $folder_dest1.$name.".inc";
                
                $source1 = "modules/PDFMaker/workflow/".$name.".inc";
                if (!file_exists($dest1)){
                    if(!copy($source1, $dest1)){
                        $Errors[] = vtranslate("LBL_PERMISSION_ERROR_PART_1","PDFMaker").' "'.$source1.'" '.vtranslate("LBL_PERMISSION_ERROR_PART_2","PDFMaker").' "'.$folder_dest1.'" '.vtranslate("LBL_PERMISSION_ERROR_PART_3","PDFMaker").'.';
                   }        
                }
                
                $folder_dest2 = "layouts/vlayout/modules/Settings/Workflows/Tasks/";
                $dest2 = $folder_dest2.$name.".tpl";
                
                $source2 = "layouts/vlayout/modules/PDFMaker/taskforms/".$name.".tpl";
                if (!file_exists($dest2)){
                    if(!copy($source2, $dest2)){
                        $Errors[] = vtranslate("LBL_PERMISSION_ERROR_PART_1","PDFMaker").' "'.$source2.'" '.vtranslate("LBL_PERMISSION_ERROR_PART_2","PDFMaker").' "'.$folder_dest2.'" '.vtranslate("LBL_PERMISSION_ERROR_PART_3","PDFMaker").'.';
                   }        
                }
            }
            
            if (count($Errors) > 0){    
                $error = '<div class="modelContainer">';
                    $error .= '<div class="modal-header">';
                        $error .= '<button class="close vtButton" data-dismiss="modal">×</button>';
                        $error .= '<h3 class="redColor">';
                        $error .= vtranslate("LBL_INSTALLATION_FAILED","PDFMaker");
                        $error .= '</h3>';
                    $error .= '</div>';
                    $error .= '<div class="modal-body">';
                        $error .= implode("<br>",$Errors);
                        $error .= "<br><br>".vtranslate("LBL_CHANGE_PERMISSION","PDFMaker");
                    $error .= '</div>';
                $error .= '</div>';
            } else {
                $PDFMaker->installWorkflows();
                
                $control = $PDFMakerModel->controlWorkflows();
                
                if (!$control){
                    $error = vtranslate("LBL_INSTALLATION_FAILED","PDFMaker");
                }
            }
            if ($error == ""){
                 $result = array('success' => true, 'message' => vtranslate("LBL_WORKFLOWS_ARE_ALREADY_INSTALLED","PDFMaker"));
            } else {
                 $result = array('success' => false, 'message' => vtranslate($error, 'PDFMaker'));
            }
        } else {
            $result = array('success' => false);
        }   
        
        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }
    
    public function savePDFBreakline(Vtiger_Request $request){
        $crmid = $request->get("record");

        $adb = PearDatabase::getInstance();
        
        $sql1 = "DELETE FROM vtiger_pdfmaker_breakline WHERE crmid=?";
        $adb->pquery($sql1, array($crmid));

        $breaklines = rtrim($request->get("breaklines"), "|");

        if ($breaklines != "") {
            $show_header = $request->get("show_header");
            $show_subtotal = $request->get("show_subtotal");
             
            $sql2 = "INSERT INTO vtiger_pdfmaker_breakline (crmid, productid, sequence, show_header, show_subtotal) VALUES (?,?,?,?,?)";

            $show_header_val = $show_subtotal_val = "0";
            if ($show_header == "true")
                $show_header_val = "1";
            if ($show_subtotal == "true")
                $show_subtotal_val = "1";

            $products = explode("|", $breaklines);
            for ($i = 0; $i < count($products); $i++) {
                list($productid, $sequence) = explode("_", $products[$i], 2);
                $adb->pquery($sql2,array($crmid, $productid, $sequence, $show_header_val, $show_subtotal_val));
            }
        }
        
        $response = new Vtiger_Response();
        $response->setResult(array('success' => true));
        $response->emit();
    }
    
    public function CheckDuplicateTemplateName(Vtiger_Request $request) {
            
        $moduleName = $request->getModule();
        $adb = PearDatabase::getInstance();
        $templateName = $request->get('templatename');
        $templateId = $request->get('templateid');

        $sql = "SELECT templateid FROM vtiger_pdfmaker WHERE filename = ? AND templateid != ? AND deleted = '0'";
        $result = $adb->pquery($sql, array($templateName,$templateId));
        $num_rows = $adb->num_rows($result);
        
        if ($num_rows > 0){
             $result = array('success' => true, 'message' => vtranslate('LBL_DUPLICATES_EXIST', $moduleName));
        } else {
             $result = array('success' => false);
        }
        
        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }
    
    public function ChangeActiveOrDefault(Vtiger_Request $request){
        $current_user = Users_Record_Model::getCurrentUserModel();
        $adb = PearDatabase::getInstance();
        $templateid = $request->get("templateid");
        $subject = $request->get("subjectChanged");

        $sql = "SELECT is_listview FROM vtiger_pdfmaker_settings WHERE templateid=?";
        $result = $adb->pquery($sql, array($templateid));
        if ($adb->query_result($result, 0, "is_listview") == "1")
            $set_default_val = "2";
        else
            $set_default_val = "3";

        $sql = "SELECT *
            FROM vtiger_pdfmaker_userstatus
            WHERE templateid=? AND userid=?";
        $result = $adb->pquery($sql, array($templateid, $current_user->id));

        if ($adb->num_rows($result) > 0) {
            if ($subject == "active")
                $sql = "UPDATE vtiger_pdfmaker_userstatus SET is_active=IF(is_active=0,1,0), is_default=IF(is_active=0,0,is_default) WHERE templateid=? AND userid=?";
            elseif ($subject == "default")
                $sql = "UPDATE vtiger_pdfmaker_userstatus SET is_default=IF(is_default > 0,0," . $set_default_val . ") WHERE templateid=? AND userid=?";
        }
        else {
            if ($subject == "active")
                $sql = "INSERT INTO vtiger_pdfmaker_userstatus(templateid,userid,is_active,is_default) VALUES(?,?,0,0)";
            elseif ($subject == "default")
                $sql = "INSERT INTO vtiger_pdfmaker_userstatus(templateid,userid,is_active,is_default) VALUES(?,?,1," . $set_default_val . ")";
        }
        $adb->pquery($sql, array($templateid, $current_user->id));

        $sql = "SELECT is_default, module
            FROM vtiger_pdfmaker_userstatus
            INNER JOIN vtiger_pdfmaker USING(templateid)
            WHERE templateid=? AND userid=?";
        $result = $adb->pquery($sql, array($templateid, $current_user->id));
        $new_is_default = $adb->query_result($result, 0, "is_default");
        $module = $adb->query_result($result, 0, "module");

        if ($new_is_default == $set_default_val) {
            $sql5 = "UPDATE vtiger_pdfmaker_userstatus 
	       INNER JOIN vtiger_pdfmaker USING(templateid)
	       SET is_default=0
	       WHERE is_default > 0
             AND userid=?
             AND module=?
             AND templateid!=?";
            $adb->pquery($sql5, array($current_user->id, $module, $templateid));
        }

        $response = new Vtiger_Response();
        $response->setResult(array('success' => true));
        $response->emit();
    }
}