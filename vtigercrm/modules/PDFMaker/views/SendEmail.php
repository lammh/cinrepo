<?php

/* * *******************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class PDFMaker_SendEmail_View extends Vtiger_ComposeEmail_View {

    public function checkPermission(Vtiger_Request $request) {
            $moduleName = "Emails";

            if (!Users_Privileges_Model::isPermitted($moduleName, 'EditView')) {
                    throw new AppException('LBL_PERMISSION_DENIED');
            }
    }
    
    /**
     * Function which will construct the compose email
     * This will handle the case of attaching the invoice pdf as attachment
     * @param Vtiger_Request $request 
     */
 
    public function composeMailData(Vtiger_Request $request) {
		
        parent::composeMailData($request);

        $viewer = $this->getViewer($request);
        $inventoryRecordId = $request->get('record');
   
        $mpdf = "";

        $Records = array($inventoryRecordId);

        $language = $request->get('language');
        $moduleName = $request->get('formodule');
        
        //$pdftemplateid = rtrim($request->get('pdftemplateid'),';');
        $pdftemplateid = rtrim($request->get('pdftemplateid'),';');
        $Templateids = explode(";",$pdftemplateid);
 
        $PDFMaker = new PDFMaker_PDFMaker_Model();

        $name = $PDFMaker->GetPreparedMPDF($mpdf, $Records, $Templateids, $moduleName, $language);
        $name = $PDFMaker->generate_cool_uri($name);

        if ($name != "")
            $fileName = $name . ".pdf";
        else
            $fileName = $moduleName."_".$inventoryRecordId.'.pdf';
       
        $current_user = $cu_model = Users_Record_Model::getCurrentUserModel();
        
        if (!is_dir("storage/PDFMaker/")) {
            mkdir("storage/PDFMaker/");
        }

	if (!is_dir("storage/PDFMaker/" . $current_user->id)) {
            mkdir("storage/PDFMaker/".$current_user->id);
        }
        
        $path = 'storage/PDFMaker/'.$current_user->id;
        $pdfFilePath = $path.'/'.$fileName;
        
        $mpdf->Output($pdfFilePath);
        
        $attachmentDetails = array(array(
            'attachment' =>$fileName,
            'path' => $path,
            'size' => filesize($pdfFilePath),
            'type' => 'pdf',
            'nondeletable' => true
        ));
   
        $viewer->assign('ATTACHMENTS', $attachmentDetails);
        echo $viewer->view('ComposeEmailForm.tpl', 'Emails', true);
    }     
}  