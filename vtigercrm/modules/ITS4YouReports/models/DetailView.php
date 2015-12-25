<?php

/*+********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

error_reporting(0);

class ITS4YouReports_DetailView_Model extends Vtiger_DetailView_Model {
	/**
	 * Function to get the instance
	 * @param <String> $moduleName - module name
	 * @param <String> $recordId - record id
	 * @return <Vtiger_DetailView_Model>
	 */
	public static function getInstance($moduleName,$recordId) {
		$modelClassName = Vtiger_Loader::getComponentClassName('Model', 'DetailView', $moduleName);
		$instance = new $modelClassName();

		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$recordModel = ITS4YouReports_Record_Model::getCleanInstance($recordId, $moduleName);

		return $instance->setModule($moduleModel)->setRecord($recordModel);
	}
        
        public function exportPDFAvailable() {
            $PDFMakerInstalled = vtlib_isModuleActive("PDFMaker");
            if($PDFMakerInstalled===true && file_exists('modules/PDFMaker/resources/mpdf/mpdf.php')===true){
                $PDFMakerInstalled = true;
            }else{
                $PDFMakerInstalled = false;
            }
            return $PDFMakerInstalled;
	}
        
        public function isTestWriteAble(){
            $is_writable = is_writable("test");
            return $is_writable;
        }

	/**
	 * Function to get the detail view links (links and widgets)
	 * @param <array> $linkParams - parameters which will be used to calicaulate the params
	 * @return <array> - array of link models in the format as below
	 *                   array('linktype'=>list of link models);
	 */
	public function getDetailViewLinks($linkParams='') {
		$currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		$moduleModel = $this->getModule();
		$recordModel = $this->getRecord();
		$moduleName = $moduleModel->getName();

		$detailViewLinks = array();
		$detailViewLinks[] = array(
			'linklabel' => vtranslate('LBL_REPORT_PRINT', $moduleName),
			//'linkurl' => $recordModel->getReportPrintURL(),
                        'linkurl' => '',
                        'onClick' => 'printDiv()',
			'linkicon' => 'print.png'
		);

		/*
                $detailViewLinks[] = array(
			'linklabel' => vtranslate('LBL_REPORT_CSV', $moduleName),
			'linkurl' => $recordModel->getReportCSVURL(),
			'linkicon' => 'csv.png'
		);
                */
                
		$detailViewLinks[] = array(
			'linklabel' => vtranslate('LBL_REPORT_EXPORT_EXCEL', $moduleName),
			'linkurl' => $recordModel->getReportExcelURL(),
                        //'onClick'=>"ExportXLS();",
                        'id' => "XLSExport",
			'linkicon' => 'xlsx.png'
		);
                
                $PDFMakerInstalled = $this->exportPDFAvailable();
                $is_test_write_able = $this->isTestWriteAble();

                $detailViewLinks[] = array(
			'linklabel' => vtranslate('LBL_EXPORTPDF_BUTTON', $moduleName),
			'linkurl' => "",
                        'id' => "btnExport",
                        'onClick'=>"generatePDF(".$this->getRecord()->getId().", '$PDFMakerInstalled','$is_test_write_able', 'activate_pdfmaker');",
                        'linkicon' => 'pdf.png'
		);
//<input class="crmbutton small create" id="btnExport" name="btnExport" value="{$MOD.LBL_EXPORTPDF_BUTTON}" type="button" onClick="generatePDF({$REPORTID}, '{$PDFMakerActive}','{$IS_TEST_WRITE_ABLE}', 'activate_pdfmaker_bot');" title="{$MOD.LBL_EXPORTPDF_BUTTON}">
/*
                $detailViewLinks[] = array(
			'linklabel' => vtranslate('LBL_SAVE_CHARTIMG', $moduleName),
			'linkurl' => "",
                        'id' => "chimgExport",
                        'style' => "visibility :hidden;",
                        'linkicon' => 'pdf.png'
		);
*/
//<input class="crmbutton small create" id="" name="chimgExport" value="{$MOD.LBL_SAVE_CHARTIMG}" type="button" onClick="" title="{$MOD.LBL_SAVE_CHARTIMG}" style="visibility :hidden;" >

                $linkModelList = array();
		foreach($detailViewLinks as $detailViewLinkEntry) {
			$linkModelList[] = Vtiger_Link_Model::getInstanceFromValues($detailViewLinkEntry);
		}

		return $linkModelList;
	}



	/**
	 * Function to get the detail view widgets
	 * @return <Array> - List of widgets , where each widget is an Vtiger_Link_Model
	 */
	public function getWidgets() {
		$moduleModel = $this->getModule();
		$widgets = array();

		if($moduleModel->isTrackingEnabled()) {
			$widgets[] = array(
				'linktype' => 'DETAILVIEWWIDGET',
				'linklabel' => 'LBL_RECENT_ACTIVITIES',
				'linkurl' => 'module='.$this->getModuleName().'&view=Detail&record='.$this->getRecord()->getId().
					'&mode=showRecentActivities&page=1&limit=5',
			);
		}

		$widgetLinks = array();
		foreach ($widgets as $widgetDetails){
			$widgetLinks[] = Vtiger_Link_Model::getInstanceFromValues($widgetDetails);
		}
		return $widgetLinks;
	}

}
