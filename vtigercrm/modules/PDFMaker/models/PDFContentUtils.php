<?php
/* * *******************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class PDFMaker_PDFContentUtils_Model extends Vtiger_Base_Model {
  
    public function getOwnerNameCustom($id){
        $db = PearDatabase::getInstance();
        if ($id != ""){
            $result = $db->pquery("SELECT user_name FROM vtiger_users WHERE id=?", array($id));
            $ownername = $db->query_result($result, 0, "user_name");
        }
        if ($ownername == ""){
            $result = $db->pquery("SELECT groupname FROM vtiger_groups WHERE groupid=?", array($id));
            $ownername = $db->query_result($result, 0, "groupname");
        } else {
            $ownername = getUserFullName($id);
        }
        return $ownername;
    }
    public function getAccountNo($account_id){
        $accountno = "";
        if ($account_id != '') {
            $db = PearDatabase::getInstance();
            $sql = "SELECT account_no FROM vtiger_account WHERE accountid=?";
            $result = $db->pquery($sql, array($account_id));
            $accountno = $db->query_result($result, 0, "account_no");
        }
        return $accountno;
    }
    public function convertListViewBlock($content){
        $simple_html_dom_file = $this->getSimpleHtmlDomFile();
        require_once($simple_html_dom_file);
        $html = str_get_html($content);
        if (is_array($html->find("td"))) {
            foreach ($html->find("td") as $td) {
                if (trim($td->plaintext) == "#LISTVIEWBLOCK_START#")
                    $td->parent->outertext = "#LISTVIEWBLOCK_START#";
    
                if (trim($td->plaintext) == "#LISTVIEWBLOCK_END#")
                    $td->parent->outertext = "#LISTVIEWBLOCK_END#";
            }
            $content = $html->save();
        }   
        return $content;
    }
    public function convertVatBlock($content){
        $simple_html_dom_file = $this->getSimpleHtmlDomFile();
        require_once($simple_html_dom_file);
        $html = str_get_html($content);
        if (is_array($html->find("td"))) {
            foreach ($html->find("td") as $td) {
                if (trim($td->plaintext) == "#VATBLOCK_START#") {
                    $td->parent->outertext = "#VATBLOCK_START#";
                }
                if (trim($td->plaintext) == "#VATBLOCK_END#") {
                    $td->parent->outertext = "#VATBLOCK_END#";
                }
            }
            $content = $html->save();
        } 
        return $content;
    }
    public function getUserValue($name,$data){    
        if (is_object($data)){
            return $data->column_fields[$name]; 
        } else {
            return $data[$name]; 
        }
    }   
    public function getSimpleHtmlDomFile(){        
        $simple_html_dom_file = "include/simplehtmldom/simple_html_dom.php";
        if(file_exists($simple_html_dom_file)){
            return $simple_html_dom_file; 
        } else {
            return "modules/PDFMaker/resources/classes/simple_html_dom.php";  
        }
    }
    public function getUITypeName($uitype){        
        $type = "";
        switch ($uitype) {
            case '19':
            case '20':
            case '21':
            case '24':
                $type = "textareas";
                break;
            case '5':
            case '6':
            case '23':
            case '70':
                $type = "datefields";
                break;
            case '15':
                $type = "picklists";
                break;
            case '56':
                $type = "checkboxes";
                break;
            case '33':
                $type = "multipicklists";
                break;
            case '71':
                $type = "currencyfields";
                break;
            case '9':
            case '72':
            case '83':
                $type = "numberfields";
                break;
            case '53':
            case '101':
                $type = "userfields";
                break;
            case '52':
                $type = "userorotherfields";
                break;
            case '10':
                $type = "related";
                break;
            case '7':
                if (substr($row["typeofdata"],0,1) == "N"){
                    $type = "numberfields";
                }
                break;
        }                
        return $type;
    } 
    public function getDOMElementAtts($elm){
        $atts_string = "";
        if ($elm != null) {
            foreach ($elm->attr as $attName => $attVal) {
                $atts_string .= $attName . '="' . $attVal . '" ';
            }
        }
        return $atts_string;
    }    
    public function GetFieldModuleRel(){
        $db = PearDatabase::getInstance();
        $sql = "SELECT fieldid, relmodule FROM vtiger_fieldmodulerel";
        $result = $db->pquery($sql, array());
        $fieldModRel = array();
        while ($row = $db->fetchByAssoc($result)){
            $fieldModRel[$row["fieldid"]][] = $row["relmodule"];
        }
        
        return $fieldModRel;
    }
    public function replaceBarcode($content){
        $simple_html_dom_file = $this->getSimpleHtmlDomFile();
        require_once($simple_html_dom_file);
        $html = str_get_html($content);
        if (is_array($html->find("barcode"))) {
            foreach ($html->find("barcode") as $barcode) {
                $params = explode("|", $barcode->plaintext);
                list($type, $code) = explode("=", $params[0], 2);
                $barcodeAtts = 'code="' . $code . '" type="' . $type . '" ';
                for ($i = 1; $i < count($params); $i++) {
                    list($attName, $attVal) = explode("=", $params[$i], 2);
                    $barcodeAtts .= strtolower($attName) . '="' . $attVal . '" ';
                }    
                $barcode->outertext = '<barcode ' . $barcodeAtts . '/>';
            }    
            $content = $html->save();
        }    
        return $content;
    }
    public function fixImg($content){
        $i = "site_URL";
        $surl = vglobal($i);
        $simple_html_dom_file = $this->getSimpleHtmlDomFile();
        require_once($simple_html_dom_file);

        $html = str_get_html($content);
        if (is_array($html->find("img"))) {
            foreach ($html->find("img") as $img) {
                if ($surl[strlen($surl) - 1] != "/")
                    $surl = $surl . "/";
    
                if (strpos($img->src, $surl) === 0) {
                    $newPath = str_replace($surl, "", $img->src);
                    if (file_exists($newPath))
                        $img->src = $newPath;
                }
            }                                            
            $content = $html->save();
        }
        return $content;
    }
    public function getInventoryBreaklines($id){
        $db = PearDatabase::getInstance();
        $sql = "SELECT productid, sequence, show_header, show_subtotal FROM vtiger_pdfmaker_breakline WHERE crmid=?";
        $res = $db->pquery($sql, array($id));
        $products = array();
        $show_header = 0;
        $show_subtotal = 0;
        while ($row = $db->fetchByAssoc($res)){
            $products[$row["productid"] . "_" . $row["sequence"]] = $row["sequence"];
            $show_header = $row["show_header"];
            $show_subtotal = $row["show_subtotal"];
        }
        $output["products"] = $products;
        $output["show_header"] = $show_header;
        $output["show_subtotal"] = $show_subtotal;
        return $output;
    }    
    public function getUserImage($id){
        if (isset($id) AND $id != ""){
            
            $db = PearDatabase::getInstance();
            $sql = "select vtiger_attachments.* from vtiger_attachments left join vtiger_salesmanattachmentsrel on vtiger_salesmanattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid where vtiger_salesmanattachmentsrel.smid=?";
            $image_res = $db->pquery($sql, array($id));
            $image_id = $db->query_result($image_res, 0, 'attachmentsid');
            $image_path = $db->query_result($image_res, 0, 'path');
            $image_name = $db->query_result($image_res, 0, 'name');
            $imgpath = $image_path . $image_id . "_" . $image_name;
            if ($image_name != '') {
                $image = '<img src="' . $imgpath . '" width="250px" border="0">';
            } else {
                $image = '';
            }
            return $image;
        } else {
            return "";
        }
    }
    public function getSettingsForId($templateid){
        $db = PearDatabase::getInstance();
        $sql = "SELECT (margin_top * 10) AS margin_top,
                     (margin_bottom * 10) AS margin_bottom,
                     (margin_left * 10) AS margin_left,
                     (margin_right*10) AS margin_right,
                     format,
                     orientation,
                     encoding,
                     disp_header, disp_footer
              FROM vtiger_pdfmaker_settings WHERE templateid = ?";
        $result = $db->pquery($sql, array($templateid));
        return $db->fetchByAssoc($result, 1);
    }
}