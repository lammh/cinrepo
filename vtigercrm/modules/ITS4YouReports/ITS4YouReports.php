<?php

/* +********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

require_once('include/database/PearDatabase.php');
require_once('data/CRMEntity.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/ITS4YouReports/GenerateObj.php');
require_once('modules/ITS4YouReports/helpers/Version.php');

function compareDeepValue($val1, $val2) {
    return strcmp($val1['value'], $val2['value']);
}

class ITS4YouReports extends CRMEntity {

    private $profilesActions;
    var $secondarymodules;
    var $relatedmodulesarray = array();
    var $record = '';
    var $currentModule = 'ITS4YouReports';
    // vtranslate($modulename, $modulename)
    var $Date_Filter_Values = array("custom" => "Custom",
        "todayless" => "Less than today",
        "yesterday" => "Yesterday",
        "today" => "Today",
        "tomorrow" => "Tomorrow",
        "todaymore" => "More than today",
        "lastweek" => "Last Week",
        "thisweek" => "Current Week",
        "nextweek" => "Next Week",
        "lastmonth" => "Last Month",
        "thismonth" => "Current Month",
        "nextmonth" => "Next Month",
        "last7days" => "Last 7 Days",
        "last15days" => "Last 15 Days",
        "last30days" => "Last 30 Days",
        "last60days" => "Last 60 Days",
        "last90days" => "Last 90 Days",
        "last120days" => "Last 120 Days",
        "next7days" => "Next 7 Days",
        "next15days" => "Next 15 Days",
        "next30days" => "Next 30 Days",
        "next60days" => "Next 60 Days",
        "next90days" => "Next 90 Days",
        "next120days" => "Next 120 Days",
        "older1days" => "Older than 1 day",
        "older7days" => "Older than 7 day",
        "older15days" => "Older than 15 day",
        "older30days" => "Older than 30 day",
        "older90days" => "Older than 90 day",
        "older120days" => "Older than 120 day",
        "prevfq" => "Previous FQ",
        "thisfq" => "Current FQ",
        "nextfq" => "Next FQ",
        "prevfy" => "Previous FY",
        "thisfy" => "Current FY",
        "nextfy" => "Next FY",
        "isn" => "is empty",
        "isnn" => "is not empty",
    );
    public static $customRelationTypes = array("INV", "MIF");
    
    public $calculation_type_array = array("count", "sum", "avg", "min", "max");
    // new + users fields
    public static $s_users_uitypes = array("52", "53", "77",);
    //public static $s_uitypes = array("15", "16", "26", "33", "52", "53", "77",);
    public static $s_uitypes = array("15", "16", "26", "33", "52", "53", "56", "77",);
    public $std_filter_columns = array();
    public $pri_module_columnslist = array();
    public static $adv_filter_options = array("e" => "EQUALS",
        "n" => "NOT_EQUALS_TO",
        "s" => "STARTS_WITH",
        "ew" => "ENDS_WITH",
        "c" => "CONTAINS",
        "k" => "DOES_NOT_CONTAINS",
        "l" => "LESS_THAN",
        "g" => "GREATER_THAN",
        "m" => "LESS_OR_EQUALS",
        "h" => "GREATER_OR_EQUALS",
        "bw" => "BETWEEN",
        //"nbw" => "not between",
        "a" => "AFTER",
        "b" => "BEFORE",
    );
    public static $inventory_modules = array("Quotes",
        "PurchaseOrder",
        "SalesOrder",
        "Invoice");
    // ITS4YOU-UP SlOl | 24.8.2015 16:24
    // I will call this new inventory fields ps_variablename to get better chance that i will have got unique alias
    // celkova cena -> PRODUCTTOTAL -> ps_producttotal
    // celkom po zlave -> PRODUCTSTOTALAFTERDISCOUNT -> ps_productstotalafterdiscount
    // DPH -> PRODUCTVATSUM -> ps_productvatsum
    // celkova cena s DPH -> PRODUCTTOTALSUM -> ps_producttotalsum
    // kategoria -> PRODUCTCAREGORY -> ps_productcategory
    // cislo produktu/sluzby -> PRODUCTNO -> ps_productno
    public static $intentory_fields = array('prodname', 'quantity', 'listprice', 'discount', 'comment', 'ps_producttotal', 'ps_productstotalafterdiscount', 'ps_productvatsum', 'ps_producttotalsum', 'ps_productcategory', 'ps_productno',);
    // see GenerateObj.php definition too
    public static $sp_date_options = array("todaymore", "todayless", "older1days", "older7days", "older15days", "older30days", "older60days", "older90days", "older120days");
    public $adv_sel_fields = array();
    private $current_language = "";
    private $app_strings = array();
    private $current_user_profileGlobalPermission = "";

    // constructor of Reports4You class
    function ITS4YouReports($run_construct = true, $reportid = "") {
        $this->executeWidgetLinks();
        
        GenerateObj::checkInstallationMemmoryLimit();

        if ($_REQUEST["view"] != "Detail") {
            $run_construct = true;
        }

        if ($run_construct === true && (!isset($_REQUEST["mode"]) || $_REQUEST["mode"] != "ajax")) {
            $this->setITS4YouReport($reportid);
        }

        // WIDGET LINKS FIX !!! s
        $this->fix_widget_labels();
        // WIDGET LINKS FIX !!! e
        
    }

    public function setAppLanguage() {
        if (R_DEBUG) {
            $this->sshow("START RT");
            $this->getR4UDifTime(0);
        }
        $this->current_language = (isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != "" ? $_SESSION['authenticated_user_language'] : "en_us");
        if (isset($this->current_language) && $this->current_language != "") {
            $this->app_strings = return_application_language($this->current_language);
        }
    }

    protected function setITS4YouReport($reportid) {
        global $default_charset;
        $this->define_rt_vars(false);
        $this->setAppLanguage();
        if (R_DEBUG) {
            $this->getR4UDifTime(1);
        }
        $this->db = PearDatabase::getInstance();
        global $current_user;
        if (isset($current_user)) {
            $this->current_user = $current_user;
        }

        if (R_DEBUG) {
            $this->getR4UDifTime(2);
        }

        $user_privileges_path = 'user_privileges/user_privileges_' . $this->current_user->id . '.php';

        if (R_DEBUG) {
            $this->getR4UDifTime(3);
        }
        if (file_exists($user_privileges_path)) {
            require($user_privileges_path);
            $this->current_user_parent_role_seq = $current_user_parent_role_seq;
            $this->current_user_profileGlobalPermission = $profileGlobalPermission;
        }

        // array of action names used in profiles permissions
        $this->profilesActions = array("EDIT" => "EditView", // Create/Edit
            "DETAIL" => "DetailView", // View
                // MASS Delete Canceled "DELETE" => "Delete", // Delete
        );
        $this->profilesPermissions = array();

        if (isset($_REQUEST["record"]) && $_REQUEST['record'] != '') {
            $reportid = vtlib_purify($_REQUEST["record"]);
        }
        if (R_DEBUG) {
            $this->getR4UDifTime(4);
        }
        if (isset($reportid) && $reportid != '') {
            $this->record = $reportid;
            $rep_sql = "SELECT * FROM its4you_reports4you 
                            LEFT JOIN its4you_reports4you_modules ON its4you_reports4you_modules.reportmodulesid = its4you_reports4you.reports4youid 
                            INNER JOIN its4you_reports4you_settings ON its4you_reports4you.reports4youid = its4you_reports4you_settings.reportid 
                            WHERE its4you_reports4you.reports4youid = ?";
            $rep_result = $this->db->pquery($rep_sql, array($this->record));
            $report = array();
            $report = $this->db->fetchByAssoc($rep_result, 0);

            if (R_DEBUG) {
                $this->getR4UDifTime(5);
            }
            // ITS4YOU-BF SlOl 18. 2. 2014 9:29:51 if there are duplicated modules saved in secondary modules string 
            $new_arr_relatedmodules = array();
            $to_check = explode(":", $report["secondarymodules"]);
            foreach ($to_check as $key => $modulestr) {
                if (!in_array($modulestr, $new_arr_relatedmodules)) {
                    $new_arr_relatedmodules[] = $modulestr;
                }
            }
            $report["secondarymodules"] = implode(":", $new_arr_relatedmodules);
            // ITS4YOU-END 18. 2. 2014 9:29:55

            if (R_DEBUG) {
                $this->getR4UDifTime(6);
            }
            $selectcolumn_sql = "SELECT * FROM its4you_reports4you_selectcolumn WHERE queryid = ?";
            $selectcolumn_result = $this->db->pquery($selectcolumn_sql, array($this->record));
            $selectedColumnsString = "";
            while ($selectcolumn_val = $this->db->fetchByAssoc($selectcolumn_result)) {
                $selectedColumnsString .= $selectcolumn_val["columnname"] . ";";
            }
            $report["selectedColumnsString"] = $selectedColumnsString;

            if (R_DEBUG) {
                $this->getR4UDifTime(7);
            }
            $selectQFcolumn_sql = "SELECT * FROM its4you_reports4you_selectqfcolumn WHERE queryid = ?";
            $selectQFcolumn_result = $this->db->pquery($selectQFcolumn_sql, array($this->record));
            $selectedQFColumnsString = "";
            while ($selectQFcolumn_val = $this->db->fetchByAssoc($selectQFcolumn_result)) {
                $selectedQFColumnsString .= $selectQFcolumn_val["columnname"] . ";";
            }
            $report["selectedQFColumnsString"] = $selectedQFColumnsString;
            $sort1 = $sort2 = $sort3 = array();

            if (R_DEBUG) {
                $this->getR4UDifTime(8);
            }
            $sort_by1sql = "SELECT * FROM its4you_reports4you_sortcol WHERE SORTCOLID=? AND REPORTID=?";
            $sort_by1result = $this->db->pquery($sort_by1sql, array('1', $this->record));
            $sort1 = $this->db->fetchByAssoc($sort_by1result, 0);
            if (!empty($sort1)) {
                $report["Group1"] = $sort1["columnname"];
                $report["Sort1"] = $sort1["sortorder"];
                $report["timeline_columnstr1"] = $sort1["timeline_columnstr"] . "@vlv@" . $sort1["timeline_columnfreq"];
                $report["timeline_columnfreq1"] = $sort1["timeline_columnfreq"];
                $report["timeline_type1"] = $sort1["timeline_type"];
            }

            if (R_DEBUG) {
                $this->getR4UDifTime(9);
            }
            $sort_by2sql = "SELECT * FROM its4you_reports4you_sortcol WHERE SORTCOLID=? AND REPORTID=?";
            $sort_by2result = $this->db->pquery($sort_by2sql, array('2', $this->record));
            $sort2 = $this->db->fetchByAssoc($sort_by2result, 0);
            if (!empty($sort2)) {
                $report["Group2"] = $sort2["columnname"];
                $report["Sort2"] = $sort2["sortorder"];
                $report["timeline_columnstr2"] = $sort2["timeline_columnstr"] . "@vlv@" . $sort2["timeline_columnfreq"];
                $report["timeline_type2"] = $sort2["timeline_type"];
            }

            if (R_DEBUG) {
                $this->getR4UDifTime(10);
            }
            $sort_by3sql = "SELECT * FROM its4you_reports4you_sortcol WHERE SORTCOLID=? AND REPORTID=?";
            $sort_by3result = $this->db->pquery($sort_by3sql, array('3', $this->record));
            $sort3 = $this->db->fetchByAssoc($sort_by3result, 0);
            if (!empty($sort3)) {
                $report["Group3"] = $sort3["columnname"];
                $report["Sort3"] = $sort3["sortorder"];
                $report["timeline_columnstr3"] = $sort3["timeline_columnstr"] . "@vlv@" . $sort3["timeline_columnfreq"];
                $report["timeline_type3"] = $sort3["timeline_type"];
            }

            // in case display report type 2 is cols and 3 is rows I will Switch to R-R-C Report
            if ($_REQUEST["view"] == "Detail" && $report["timeline_type2"] == "cols" && $report["timeline_type3"] == "rows" && $report["Group3"] != "" && $report["Group3"] != "none") {
                $cols_type = $report["timeline_type2"];
                $cols_Group = $report["Group2"];
                $cols_Sort = $report["Sort2"];
                $cols_ColumnStr = $report["timeline_columnstr2"];

                $rows_type = $report["timeline_type3"];
                $rows_Group = $report["Group3"];
                $rows_Sort = $report["Sort3"];
                $rows_ColumnStr = $report["timeline_columnstr3"];
                $report["timeline_type2"] = $rows_type;
                $report["Group2"] = $rows_Group;
                $report["Sort2"] = $rows_Sort;
                $report["timeline_columnstr2"] = $rows_ColumnStr;
                $report["timeline_type3"] = $cols_type;
                $report["Group3"] = $cols_Group;
                $report["Sort3"] = $cols_Sort;
                $report["timeline_columnstr3"] = $cols_ColumnStr;
            }
            // in case display report type 2 is cols and 3 is rows

            if (R_DEBUG) {
                $this->getR4UDifTime(11);
            }
            $sort_by_columnsql = "SELECT * FROM its4you_reports4you_sortcol WHERE SORTCOLID=? AND REPORTID=?";
            $sort_by_columnresult = $this->db->pquery($sort_by_columnsql, array(4, $this->record));
            $sort_order = $this->db->fetchByAssoc($sort_by_columnresult, 0);
            $report["SortByColumn"] = $sort_order["columnname"];
            $report["SortOrderColumn"] = $sort_order["sortorder"];

            $datefilter_sql = "SELECT * FROM its4you_reports4you_datefilter WHERE datefilterid=?";
            $datefilter_result = $this->db->pquery($datefilter_sql, array($this->record));
            $datefilter = $this->db->fetchByAssoc($datefilter_result, 0);
            $report["stdDateFilterField"] = $datefilter["datecolumnname"];
            $report["stdDateFilter"] = $datefilter["datefilter"];
            $report["startdate"] = $datefilter["startdate"];
            $report["enddate"] = $datefilter["enddate"];

            if (R_DEBUG) {
                $this->getR4UDifTime(12);
            }
            $summary_sql = "SELECT * FROM its4you_reports4you_summary WHERE reportsummaryid=?";
            $summary_result = $this->db->pquery($summary_sql, array($this->record));
            while ($summary = $this->db->fetchByAssoc($summary_result)) {
                $report["columnstototal"][] = $summary;
            }

            if (R_DEBUG) {
                $this->getR4UDifTime(13);
            }
            $advft_criteria_sql = "SELECT * FROM its4you_reports4you_relcriteria WHERE queryid=?";
            $advft_criteria_result = $this->db->pquery($advft_criteria_sql, array($this->record));
            while ($advft_criteria = $this->db->fetchByAssoc($advft_criteria_result)) {
                $report["advft_criteria"][] = $advft_criteria;
            }

            if (R_DEBUG) {
                $this->getR4UDifTime(14);
            }
            $advft_criteria_groups_sql = "SELECT * FROM its4you_reports4you_relcriteria_grouping WHERE queryid=?";
            $advft_criteria_groups_result = $this->db->pquery($advft_criteria_groups_sql, array($this->record));
            while ($advft_criteria_groups = $this->db->fetchByAssoc($advft_criteria_groups_result)) {
                $report["advft_criteria_groups"][] = $advft_criteria_groups;
            }

            if (R_DEBUG) {
                $this->getR4UDifTime(15);
            }
            $summaries_sql = "SELECT * FROM its4you_reports4you_summaries WHERE reportsummaryid=?";
            $summaries_result = $this->db->pquery($summaries_sql, array($this->record));
            while ($summaries = $this->db->fetchByAssoc($summaries_result)) {
                $report["summaries_columns"][] = $summaries;
            }

            if (R_DEBUG) {
                $this->getR4UDifTime(16);
            }
            $charts_sql = "SELECT * FROM its4you_reports4you_charts WHERE reports4youid=?";
            $charts_result = $this->db->pquery($charts_sql, array($this->record));
            $charttype = $dataseries = $charttitle = "";
            if ($this->db->num_rows($charts_result) > 0) {
                while ($charts_row = $this->db->fetchByAssoc($charts_result)) {
                    $report["charts"][$charts_row["chart_seq"]] = $charts_row;
                }
                /*
                  $charttype = ($charts_row["charttype"]!=""?$charts_row["charttype"]:"none");
                  $dataseries = $charts_row["dataseries"];
                  $charttitle = $charts_row["charttitle"];
                 */
            }
            /* $report["charts"]["charttype"] = $charttype;
              $report["charts"]["dataseries"] = $dataseries;
              $report["charts"]["charttitle"] = $charttitle; */

            if (R_DEBUG) {
                $this->getR4UDifTime(17);
            }
            $summaries_orderby_sql = "SELECT * FROM  its4you_reports4you_summaries_orderby WHERE reportid=?";
            $summaries_orderby_result = $this->db->pquery($summaries_orderby_sql, array($this->record));
            while ($summaries_orderby = $this->db->fetchByAssoc($summaries_orderby_result)) {
                $this->reportinformations["summaries_orderby_columns"][0] = array("column" => $summaries_orderby["summaries_orderby"], "type" => $summaries_orderby["summaries_orderby_type"]);
            }

            if (R_DEBUG) {
                $this->getR4UDifTime(18);
            }
            $sharing_sql = "SELECT * FROM its4you_reports4you_settings WHERE reportid=?";
            $sharing_result = $this->db->pquery($sharing_sql, array($this->record));
            $sharing = $this->db->fetchByAssoc($sharing_result, 0);
            $report["template_owner"] = $sharing["owner"];
            $report["sharing"] = $sharing["sharingtype"];
            if ($report["sharing"] == "share") {
                $share_sql = "SELECT shareid, setype FROM  its4you_reports4you_sharing WHERE reports4youid = ? ORDER BY setype ASC";
                $share_result = $this->db->pquery($share_sql, array($this->record));
                $memberArray = array();
                while ($share_row = $this->db->fetchByAssoc($share_result)) {
                    $memberArray[] = $share_row["setype"] . "::" . $share_row["shareid"];
                }
                $this->reportinformations["members_array"] = $memberArray;
            }

            if (R_DEBUG) {
                $this->getR4UDifTime(19);
            }
            foreach ($report as $key => $value) {
                $this->reportinformations[$key] = $value;
            }

            if (R_DEBUG) {
                $this->getR4UDifTime(20);
            }
            // ITS4YOU-CR SlOl 1/12/2014 12:16:43 PM TEST GET FILTERS INTO REPORTS4YOU OBJECT
            $this->getSelectedStandardCriteria($this->record);
            // ITS4YOU-END 1/12/2014 12:16:45 PM
            $this->selected_columns_list_arr = $this->getSelectedColumnListArray($this->record);
            //$this->selected_columns_list_arr = explode(";", html_entity_decode($selectedColumnsString, ENT_QUOTES, $default_charset));
        }

        if (R_DEBUG) {
            $this->getR4UDifTime(22);
        }
        if (!isset($this->module_list) || empty($this->module_list)) {
            $this->initListOfModules();
        }
        if (R_DEBUG) {
            $this->getR4UDifTime(23);
        }
        // ITS4YOU-CR SlOl 30. 8. 2013 13:51:52
        if (isset($this->reportinformations["reports4youname"]) && $this->reportinformations["reports4youname"] != '') {
            $this->reportname = $this->reportinformations["reports4youname"];
        }
        if (isset($this->reportinformations["reporttype"]) && $this->reportinformations["reporttype"] != '') {
            $this->reporttype = $this->reportinformations["reporttype"];
        }

        if (isset($this->reportinformations["description"]) && $this->reportinformations["description"] != '') {
            $this->reportdesc = $this->reportinformations["description"];
        }

        if (R_DEBUG) {
            $this->getR4UDifTime(24);
        }
        if (isset($this->reportinformations["primarymodule"]) && $this->reportinformations["primarymodule"] != '') {
            $this->primarymoduleid = $this->reportinformations["primarymodule"];
            $this->primarymodule = vtlib_getModuleNameById($this->reportinformations["primarymodule"]);
            $p_focus = CRMEntity::getInstance($this->primarymodule);
            $this->reportinformations["list_link_field"] = $p_focus->list_link_field;
            $this->getPriModuleColumnsList($this->primarymodule);
        }
        if (R_DEBUG) {
            $this->getR4UDifTime(25);
        }
        $this->folder = ((isset($this->reportinformations["folderid"]) && $this->reportinformations["folderid"] != '') ? $this->reportinformations["folderid"] : '');

        if (R_DEBUG) {
            $this->getR4UDifTime(26);
        }
        $subordinate_users = $this->getSubOrdinateUsersArray();

        // Update subordinate user information for re-use
        $edit_all = $this->current_user_profileGlobalPermission[1];
        if ($is_admin == true || $edit_all == 0 || in_array($report["template_owner"], $subordinate_users) || $report["template_owner"] == $this->current_user->id) {
            $this->is_editable = 'true';
        } else {
            $this->is_editable = 'false';
        }

        $this->Group1 = ((isset($this->reportinformations["Group1"]) && $this->reportinformations["Group1"] != '') ? $this->reportinformations["Group1"] : '');
        $this->Sort1 = ((isset($this->reportinformations["Sort1"]) && $this->reportinformations["Sort1"] != '') ? $this->reportinformations["Sort1"] : '');
        $this->Group2 = ((isset($this->reportinformations["Group2"]) && $this->reportinformations["Group2"] != '') ? $this->reportinformations["Group2"] : '');
        $this->Sort2 = ((isset($this->reportinformations["Sort2"]) && $this->reportinformations["Sort2"] != '') ? $this->reportinformations["Sort2"] : '');
        $this->Group3 = ((isset($this->reportinformations["Group3"]) && $this->reportinformations["Group3"] != '') ? $this->reportinformations["Group3"] : '');
        $this->Sort3 = ((isset($this->reportinformations["Sort3"]) && $this->reportinformations["Sort3"] != '') ? $this->reportinformations["Sort3"] : '');

        if (R_DEBUG) {
            $this->getR4UDifTime(27);
        }
        if (isset($this->reportinformations["secondarymodules"]) && $this->reportinformations["secondarymodules"] != '') {
            $this->relatedmodulesstring = trim($this->reportinformations["secondarymodules"], ':');
            $arr_relatedmodules = explode(':', $this->relatedmodulesstring);

            $this->relatedmodulesarray = (!empty($arr_relatedmodules)) ? $arr_relatedmodules : array();

            $this->getSecModuleColumnsList($this->relatedmodulesstring);
        }
        // ITS4YOU-END 30. 8. 2013 13:51:54 

        if (R_DEBUG) {
            $this->getR4UDifTime(28);
        }
        if (isset($_REQUEST['reportname']) && $_REQUEST['reportname'] != '') {
            $this->reportname = $_REQUEST['reportname'];
        }
        if (isset($_REQUEST['reportdesc']) && $_REQUEST['reportdesc'] != '') {
            $this->reportdesc = $_REQUEST['reportdesc'];
        }

        if (isset($_REQUEST['primarymodule']) && $_REQUEST['primarymodule'] != '') {
            $this->primarymoduleid = $_REQUEST['primarymodule'];
            $this->primarymodule = vtlib_getModuleNameById($this->primarymoduleid);

            $this->getPriModuleColumnsList($this->primarymodule);
        }

        if (R_DEBUG) {
            $this->getR4UDifTime(29);
        }
        $this->folder = (isset($_REQUEST['folderid']) && $_REQUEST['folderid'] != '') ? $_REQUEST['folderid'] : '';

        if (isset($_REQUEST['relatedmodules']) && $_REQUEST['relatedmodules'] != '') {
            $this->relatedmodulesstring = trim($_REQUEST['relatedmodules'], ':');
            $arr_relatedmodules = explode(':', $this->relatedmodulesstring);
            $this->relatedmodulesarray = (!empty($arr_relatedmodules)) ? $arr_relatedmodules : array();

            $this->getSecModuleColumnsList($this->relatedmodulesstring);
        }
        if (R_DEBUG) {
            $this->getR4UDifTime(30);
        }
        
        if($this->reportinformations["reporttype"]=="custom_report"){
            global $current_user;
            if (!is_admin($current_user)) {
                ITS4YouReports::DieDuePermission();
                exit;
            }
            if (R_DEBUG) {
                $this->getR4UDifTime(31);
            }
            $custom_sql_qry = "SELECT * FROM its4you_reports4you_customsql WHERE reports4youid=?";
            $custom_sql_result = $this->db->pquery($custom_sql_qry, array($this->record));
            $custom_sql = $this->db->fetchByAssoc($custom_sql_result, 0);
            $this->reportinformations["custom_sql"] = $custom_sql["custom_sql"];
        }
        
        if (isset($this->record) && $this->record != "") {
            $r4u_sesstion_name = $this->getITS4YouReportStoreName();
            //session_start();
            //session_register($r4u_sesstion_name);
            $_SESSION[$r4u_sesstion_name] = serialize($this);
        }
    }

    function vtlib_handler($modulename, $event_type) {
        switch ($event_type) {
            case "module.postinstall":
                $this->executeSql();
                $this->RegisterReports4YouScheduler();
                $this->executeWidgetLinks();
                break;
            case "module.disabled":
                break;
            case "module.enabled":
                break;
            case "module.preuninstall":
                // TODO Handle actions when this module is about to be deleted.
                break;
            case "module.preupdate":
                // TODO Handle actions before this module is updated.
                break;
            case "module.postupdate":
                // TODO Handle actions after this module is updated
                $this->executeNewTables();
                break;
            case "module.license_activated":
                break;
            case "module.license_deactivated":
                break;
        }
    }

    public function executeSql() {
        $adb = PEARDatabase::getInstance();
        if ($adb->num_rows($adb->query("SELECT id FROM its4you_reports4you_selectquery_seq")) < 1) {
            $adb->query("INSERT INTO its4you_reports4you_selectquery_seq VALUES('0')");
        }
    }
    
    private function executeNewTables(){
        $adb = PEARDatabase::getInstance();
        $adb->query("CREATE TABLE IF NOT EXISTS `its4you_reports4you_customsql` (
                      `reports4youid` int(11) NOT NULL,
                      `custom_sql` text NOT NULL,
                      PRIMARY KEY (`reports4youid`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    private function executeWidgetLinks() {
        require_once('vtlib/Vtiger/Module.php');
        
		$moduleName = "Home";
		
        $adb = PEARDatabase::getInstance();
        
        $link_module = Vtiger_Module::getInstance($moduleName);
//global $adb;$adb->setDebug(true);
        // ITS4YouReportsHighcharts
        $link_label = "ITS4YouReportsHighcharts";
        $result1 = $adb->pquery("SELECT linkid FROM vtiger_links WHERE linklabel=?", array($link_label));
        $exist1 = $adb->num_rows($result1);
        if ($exist1 <= 0){        
    		$link_url = 'modules/ITS4YouReports/highcharts/js/highcharts.js';
    		$link_module->addLink('HEADERSCRIPT',$link_label,$link_url,'','');
        }
        
        // ITS4YouReportsHighchartsFunnel
        $link_label = "ITS4YouReportsHighchartsFunnel";
		$result2 = $adb->pquery("SELECT linkid FROM vtiger_links WHERE linklabel=?", array($link_label));
        $exist2 = $adb->num_rows($result2);
        if ($exist2 <= 0){        
        	$link_url = 'modules/ITS4YouReports/highcharts/js/modules/funnel.js';
    		$link_module->addLink('HEADERSCRIPT',$link_label,$link_url,'','');
        }
        
        // ITS4YouReportsHighchartsExporting
        $link_label = "ITS4YouReportsHighchartsExporting";
		$result3 = $adb->pquery("SELECT linkid FROM vtiger_links WHERE linklabel=?", array($link_label));
        $exist3 = $adb->num_rows($result3);
        if ($exist3 <= 0){        
        	$link_url = 'modules/ITS4YouReports/highcharts/js/modules/exporting.js';
    		$link_module->addLink('HEADERSCRIPT',$link_label,$link_url,'','');
        }
//$adb->setDebug(false);
        return true;
    }

    public function define_rt_vars($r_defug = false, $directDebug = false) {
        if ($r_defug || $directDebug === true) {
            //define("RT_START",time());
            list($usec, $sec) = explode(' ', microtime());
            define("RT_START", $sec + $usec);
            if ($directDebug === true) {
                ITS4YouReports::sshow("START- " . RT_START);
            }
        }
        if ($directDebug === false) {
            define("R_DEBUG", $r_defug);
        }
        define("R2_DEBUG", true);
    }

    public function getR4UDifTime($t_txt = "", $directDebug = false) {

        if (R2_DEBUG || $directDebug == true) {
            if ($t_txt != "") {
                $t_txt .= " ";
            }
            //$c_time = time();
            list($usec, $sec) = explode(' ', microtime());
            $c_time = $sec + $usec;
            echo "<pre>" . $t_txt . "TIME: " . ($c_time - RT_START) . "</pre>";
        }
        return true;
    }

    public function unsetITS4YouReportsSerialize($ses_name = "") {
        if ($ses_name != "") {
            unset($_SESSION[$ses_name]);
        } else {
            foreach ($_SESSION as $ses_name => $ses_arr) {
                if (strpos($ses_name, "ITS4You") !== false) {
                    unset($_SESSION[$ses_name]);
                }
            }
        }
        return true;
    }

    public function getITS4YouReportStoreName() {
        global $current_user;
        $r4u_sesstion_name = "";
        if (isset($_REQUEST["record"]) && $_REQUEST["record"] != "") {
            $reportid = vtlib_purify($_REQUEST["record"]);
            //ITS4YouReports::unsetITS4YouReportsSerialize();exit;
            $r4u_sesstion_name = "ITS4YouReport_" . $reportid;
            if (isset($current_user)) {
                $r4u_sesstion_name .= "_" . $current_user->id;
            }
            $input_args = func_get_args();
            if (!empty($input_args)) {
                foreach ($input_args as $input) {
                    $r4u_sesstion_name .= "_$input";
                }
            }
        }
        return $r4u_sesstion_name;
    }

    public function getStoredITS4YouReport() {
        $r4u_sesstion_name = ITS4YouReports::getITS4YouReportStoreName();
        $request = new Vtiger_Request($_REQUEST, $_REQUEST);
        // used to unlink sessioned reports !
        if ($request->has("refresh") && $request->get('refresh') === "true") {
            ITS4YouReports::sshow(ITS4YouReports::unsetITS4YouReportsSerialize($r4u_sesstion_name));
        }
        // to unlink all
        if ($request->has("mode") && $request->get('mode') === "ChangeSteps") {
            $run_construct = false;
        } else {
            if ($request->has("view") && $request->get('view') === "Edit" && isset($_SESSION[$r4u_sesstion_name])) {
                $run_construct = false;
            } else {
                $run_construct = true;
            }
        }
        $return_obj = new ITS4YouReports($run_construct);
        if (ITS4YouReports::isStoredITS4YouReport() === true) {
            $return_obj = unserialize($_SESSION[$r4u_sesstion_name]);
        }
        if (isset($return_obj->reportinformations["deleted"]) && $return_obj->reportinformations["deleted"] !== 0 && $return_obj->reportinformations["deleted"] !== "0") {
            die("<br><br><center>" . vtranslate('LBL_RECORD_DELETE') . " <a href='javascript:window.history.back()'>" . vtranslate('LBL_GO_BACK') . ".</a></center>");
        }
        if ($request->has("record") && !$request->isEmpty("record")) {
            $return_obj->primarymoduleid = $return_obj->reportinformations["primarymodule"];
            $return_obj->primarymodule = vtlib_getModuleNameById($return_obj->primarymoduleid);
        }

        return $return_obj;
    }

    public function isStoredITS4YouReport() {
        $r4u_sesstion_name = ITS4YouReports::getITS4YouReportStoreName();
        if ($r4u_sesstion_name != "" && isset($_SESSION[$r4u_sesstion_name]) && !empty($_SESSION[$r4u_sesstion_name])) {
            return true;
        }
        return false;
    }

    public function sshow($variable = array()) {
        echo "<pre>";
        print_r($variable);
        echo "</pre>";
    }

    function getPrimaryModules() {
        $request = new Vtiger_Request($_REQUEST, $_REQUEST);
        if ($request->has("primarymodule") && $request->get('primarymodule') !== "") {
            $this->primarymoduleid = $request->get("primarymodule");
        }

        $p_key = 0;
        if (!isset($this->module_list) || empty($this->module_list)) {
            $this->initListOfModules();
        }
        foreach ($this->module_list as $modulename => $moduleblocks) {
            $moduleid = getTabid($modulename);
            $m_return = array();
            $m_return['id'] = $moduleid;
            /* if (in_array($modulename, array("Calendar", "PBXManager"))) {
              $module_lbl = vtranslate($modulename, $modulename);
              } else {
              $module_lbl = vtranslate("SINGLE_$modulename", $modulename);
              } */
            $module_lbl = vtranslate($modulename, $modulename);
            $m_return['module'] = $module_lbl;
            $m_return['selected'] = ($this->primarymoduleid == $moduleid) ? 'selected' : '';
            $t_return[$module_lbl] = $m_return;
            $p_key++;
        }
        ksort($t_return);
        $return = array();
        $t_i = 0;
        foreach ($t_return as $m_arr) {
            $return[$t_i] = $m_arr;
            $t_i++;
        }

        if ($this->primarymoduleid == "") {
            $return[0]['selected'] = "selected";
        }

        return $return;
    }

    function getReportFolders() {
        $adb = PearDatabase::getInstance();
        $sql = "select * from  its4you_reports4you_folder";
        $result = $adb->query($sql);
        $return = array();
        while ($row = $adb->fetchByAssoc($result)) {
            if (isset($_REQUEST['folderid'])) {
                $selected_folderid = vtlib_purify($_REQUEST['folderid']);
            } else {
                $selected_folderid = $this->reportinformations["folderid"];
            }
            $row['selected'] = ( isset($selected_folderid) && $selected_folderid == $row['folderid']) ? 'selected' : '';
            $return[] = $row;
        }
        return $return;
    }

    function getFolderName($folderid = "") {
        $adb = PearDatabase::getInstance();
        if ($folderid != "") {
            $sql = "select * from  its4you_reports4you_folder WHERE folderid=?";
            $result = $adb->pquery($sql, array($folderid));
            $return = array();
            while ($row = $adb->fetchByAssoc($result)) {
                $row['selected'] = (isset($_REQUEST['folderid']) && $_REQUEST['folderid'] == $row['folderid']) ? 'selected' : '';
                $return = $row["foldername"];
            }
        } else {
            $return = vtranslate("LBL_NONE");
        }
        return $return;
    }

    public function GetListviewData($orderby = "reports4youid", $dir = "asc") {
        global $mod_strings;
        global $current_user;

        $adb = PearDatabase::getInstance();

        $status_sql = "SELECT * FROM its4you_reports4you_userstatus
                         INNER JOIN  its4you_reports4you ON its4you_reports4you.reports4youid = its4you_reports4you_userstatus.reportid
                         WHERE userid=?";
        $status_res = $adb->pquery($status_sql, array($this->current_user->id));
        $status_arr = array();
        while ($status_row = $adb->fetchByAssoc($status_res)) {
            $status_arr[$status_row["reports4youid"]]["sequence"] = $status_row["sequence"];
        }

        $originOrderby = $orderby;
        $originDir = $dir;
        if ($orderby == "order") {
            $orderby = "sequence";
        }
        $then_order_by = "";
        if ($orderby == "primarymodule") {
            $then_order_by = " ,sequence ASC ";
        }

        $sql = "SELECT reports4youid, reports4youname, primarymodule, description,  folderid, owner, tablabel, IF(its4you_reports4you_userstatus.sequence IS NOT NULL,its4you_reports4you_userstatus.sequence,1) AS sequence, IF(its4you_reports4you_scheduled_reports.reportid IS NOT NULL,1,0) AS scheduling 
				FROM its4you_reports4you 
				INNER JOIN its4you_reports4you_modules ON its4you_reports4you_modules.reportmodulesid = its4you_reports4you.reports4youid 
				INNER JOIN its4you_reports4you_folder USING(folderid) 
				INNER JOIN vtiger_tab ON vtiger_tab.tabid = its4you_reports4you_modules.primarymodule 
                                INNER JOIN its4you_reports4you_settings ON its4you_reports4you_settings.reportid = its4you_reports4you.reports4youid 
                                LEFT JOIN its4you_reports4you_userstatus ON its4you_reports4you.reports4youid = its4you_reports4you_userstatus.reportid 
                                LEFT JOIN its4you_reports4you_scheduled_reports ON its4you_reports4you.reports4youid = its4you_reports4you_scheduled_reports.reportid ";
        $current_user_id = $this->current_user->id;

        $sql .= " WHERE its4you_reports4you.deleted=0  ";

        if ($current_user->is_admin != "on") {
            // primarymodule!=29 -> primarymodule != Users for nonAdminUsers !!!
            $sql .= " AND primarymodule!=29 ";
        }

        if (!is_admin($this->current_user)) {
            $subordinate_users = $this->getSubOrdinateUsersArray(true);
            $subordinate_users_sql = implode("','", $subordinate_users);
            $sql .= " AND (sharingtype IN ('public','share') OR owner IN ('$subordinate_users_sql') ) ";
        }

        if (isset($_REQUEST["search_field"]) && $_REQUEST["search_field"] != "" && $_REQUEST["search_field"] != "primarymodule" && isset($_REQUEST["search_text"]) && $_REQUEST["search_text"] != "") {
            $where_cond = " AND " . vtlib_purify($_REQUEST["search_field"]) . " LIKE '%" . vtlib_purify($_REQUEST["search_text"]) . "%' ";
            $sql .= $where_cond;
        }
        $sql .= "ORDER BY $orderby $dir $then_order_by";

//echo "<br/><br/><br/><br/><br/><br/>";
//$adb->setDebug(true);
        $result = $adb->pquery($sql, array());
//$adb->setDebug(false);
        $edit = "Edit  ";
        $del = "Del  ";
        $bar = "  | ";
        $cnt = 1;

        $return_data = Array();
        $num_rows = $adb->num_rows($result);

        while ($row = $adb->fetchByAssoc($result)) {
            $currModule = $row['primarymodule'];
            $reports4youid = $row['reports4youid'];

            $view_all = $this->current_user_profileGlobalPermission[0];
            if (!is_admin($this->current_user) || $view_all != 0){
                //in case of template module is not permitted for current user then skip it in list
                if ($this->CheckReportPermissions($currModule, $reports4youid, false) === false)
                    continue;
            }

            $reportsarray = array();
            $reportsarray['status'] = 1;
            $reportsarray['order'] = $row["sequence"];

            $reportsarray['status_lbl'] = ($reportsarray['status'] == 1 ? $this->app_strings["Active"] : $this->app_strings["Inactive"]);
            $reportsarray['reports4youid'] = $reports4youid;
            $reportsarray['description'] = $row['description'];
            $reportsarray['owner'] = getUserFullName($row['owner']);
            $translated_module = vtranslate(vtlib_getModuleNameById($currModule), $currModule);
            $reportsarray['module'] = $translated_module;
            $folderid = $row['folderid'];
            $foldername = $this->getFolderName($folderid);
            $reportsarray['foldername'] = $foldername;
            $reportsarray['filename'] = "<a href=\"index.php?action=resultGenerate&module=ITS4YouReports&record=" . $reports4youid . "&parenttab=Tools\">" . $row['reports4youname'] . "</a>";
            $reportsarray['scheduling'] = $row['scheduling'];

//if ($is_admin == true || $edit_all==0 || in_array($report["template_owner"], $subordinate_users) || $report["template_owner"] == $this->current_user->id)

            if (is_admin($this->current_user) || $edit_all == 0 || in_array($row['owner'], $subordinate_users)) {
                $reportsarray['edit'] = "<a href=\"index.php?action=EditReports4You&module=ITS4YouReports&record=" . $reports4youid . "&parenttab=Tools\">" . strtolower($this->app_strings["LBL_EDIT_BUTTON"]) . "</a> | "
                        . "<a href=\"index.php?action=EditReports4You&module=ITS4YouReports&record=" . $reports4youid . "&isDuplicate=true&parenttab=Tools\">" . strtolower($this->app_strings["LBL_DUPLICATE_BUTTON"]) . "</a> | "
                        . "<a href=\"javascript:deleteSingleReport('$reports4youid');\">" . $this->app_strings["LNK_DELETE"] . "</a>";
            }
            if (isset($_REQUEST["search_field"]) && $_REQUEST["search_field"] != "" && $_REQUEST["search_field"] == "primarymodule" && $_REQUEST["search_text"] != "") {
                if (!is_numeric(strpos(strtolower($translated_module), strtolower(vtlib_purify($_REQUEST["search_text"]))))) {
                    continue;
                }
            }
            if ($orderby == "primarymodule") {
                $return_data[$translated_module][] = $reportsarray;
            } else {
                $return_data[] = $reportsarray;
            }
        }

        if ($orderby == "primarymodule") {
            if ($originDir == "asc") {
                ksort($return_data);
            } else {
                krsort($return_data);
            }
            foreach ($return_data as $tmodule => $return_data_tm) {
                foreach ($return_data_tm as $return_data_tmg)
                    $return_data_togo[] = $return_data_tmg;
            }
            $return_data = $return_data_togo;
        }

        return $return_data;
    }

    public function DieDuePermission($type = "", $die_columns = array()) {
        global $current_user, $app_strings, $default_theme;
        if (isset($_SESSION['vtiger_authenticated_user_theme']) && $_SESSION['vtiger_authenticated_user_theme'] != '')
            $theme = $_SESSION['vtiger_authenticated_user_theme'];
        else {
            if (!empty($current_user->theme)) {
                $theme = $current_user->theme;
            } else {
                $theme = $default_theme;
            }
        }
        
        if(isset($this) && isset($this->currentModule)){
            $sCurrentModule = $this->currentModule;
        }else{
            global $currentModule;
            $sCurrentModule = $currentModule;
        }

        switch ($type) {
            case "columns":
                $type_info = "<br />" . vtranslate("LBL_COLUMNS_ERROR", $sCurrentModule);
                if (!empty($die_columns)) {
                    $type_info .= "<br />(" . implode(", ", $die_columns) . ")";
                }
                break;
            case "values":
                $type_info = "<br />" . vtranslate("LBL_FVALUES_ERROR", $sCurrentModule);
                break;
            default:
                if($type!=""){
                    $type_info = "<br />" . vtranslate($type, $sCurrentModule);
                }else{
                    $type_info = "";
                }
                break;
        }

        $output = "<link rel='stylesheet' type='text/css' href='themes/$theme/style.css'>";
        $output .= "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
        $output .= "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 55%; position: relative; z-index: 10000000;'>
      		<table border='0' cellpadding='5' cellspacing='0' width='98%'>
      		<tbody><tr>
      		<td rowspan='2' width='11%'><img src='layouts/vlayout/skins/images/denied.gif' ></td>
      		<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span class='genHeaderSmall'>" . vtranslate("LBL_PERM_DENIED", "ITS4YouReports") . $type_info . "</span></td>
      		</tr>
      		<tr>
      		<td class='small' align='right' nowrap='nowrap'>
      		<a href='javascript:window.history.back();'>" . vtranslate("LBL_GO_BACK") . "</a><br></td>
      		</tr>
      		</tbody></table>
      		</div>";
        $output .= "</td></tr></table>";
        echo $output;
        exit;
    }

    //Method for getting the array of profiles permissions to Reports4You actions.
    public function GetProfilesPermissions() {
        if (count($this->profilesPermissions) == 0) {
            $adb = PearDatabase::getInstance();
            $profiles = getAllProfileInfo();
            $sql = "SELECT * FROM its4you_reports4you_profilespermissions";
            $res = $adb->query($sql);
            $permissions = array();
            while ($row = $adb->fetchByAssoc($res)) {
                //      in case that profile has been deleted we need to set permission only for active profiles
                if (isset($profiles[$row["profileid"]]))
                    $permissions[$row["profileid"]][$row["operation"]] = $row["permissions"];
            }

            foreach ($profiles as $profileid => $profilename) {
                foreach ($this->profilesActions as $actionName) {
                    $actionId = getActionid($actionName);
                    if (!isset($permissions[$profileid][$actionId])) {
                        $permissions[$profileid][$actionId] = "0";
                    }
                }
            }

            ksort($permissions);
            $this->profilesPermissions = $permissions;
        }

        return $this->profilesPermissions;
    }

    //Method for checking the permissions, whether the user has privilegies to perform specific action on PDF Maker.
    public function CheckPermissions($actionKey) {
        $profileid = fetchUserProfileId($this->current_user->id);
        $result = false;

        if (isset($this->profilesActions[$actionKey])) {
            $actionid = getActionid($this->profilesActions[$actionKey]);
            $permissions = $this->GetProfilesPermissions();

            if (isset($permissions[$profileid][$actionid]) && $permissions[$profileid][$actionid] == "0")
                $result = true;
        }

        return $result;
    }

    public function CheckReportPermissions($selected_module, $reports4youid, $die = true) {
        $result = true;

        global $current_user;
        if ($selected_module == "Users" && $current_user->is_admin != "on") {
            $result = false;
        } elseif ($selected_module != "" && isPermitted($selected_module, 'DetailView') != "yes") {
            $result = false;
        } elseif ($reports4youid != "" && $this->CheckSharing($reports4youid) === false) {
            $result = false;
        }

        if ($die === true && $result === false) {
            $this->DieDuePermission();
        }

        return $result;
    }

    public function CheckSharing($reports4youid) {
        //  if this template belongs to current user
        $adb = PearDatabase::getInstance();
        $sql = "SELECT owner, sharingtype FROM its4you_reports4you_settings WHERE reportid = ?";
        $result = $adb->pquery($sql, array($reports4youid));
        $row = $adb->fetchByAssoc($result);

        $owner = $row["owner"];
        $sharingtype = $row["sharingtype"];

        $result = false;

        if ($owner == $this->current_user->id || $this->current_user->is_admin == "on") {
            $result = true;
        } else {
            switch ($sharingtype) {
                //available for all
                case "public":
                    $result = true;
                    break;
                //available only for superordinate users of template owner, so we get list of all subordinate users of the current user and if template
                //owner is one of them then template is available for current user
                case "private":
                    $subordinateUsers = $this->getSubRoleUserIds($this->current_user->roleid);
                    if (!empty($subordinateUsers) && count($subordinateUsers) > 0) {
                        $result = in_array($owner, $subordinateUsers);
                    } else
                        $result = false;
                    break;
                //available only for those that are in share list
                case "share":
                    $subordinateUsers = $this->getSubRoleUserIds($this->current_user->roleid);
                    if (!empty($subordinateUsers) && count($subordinateUsers) > 0 && in_array($owner, $subordinateUsers))
                        $result = true;
                    else {
                        $member_array = $this->GetSharingMemberArray($reports4youid);
                        if (isset($member_array["users"]) && in_array($this->current_user->id, $member_array["users"]))
                            $result = true;
                        elseif (isset($member_array["roles"]) && in_array($this->current_user->roleid, $member_array["roles"]))
                            $result = true;
                        else {
                            if (isset($member_array["rs"])) {
                                foreach ($member_array["rs"] as $roleid) {
                                    $roleAndsubordinateRoles = getRoleAndSubordinatesRoleIds($roleid);
                                    if (in_array($this->current_user->roleid, $roleAndsubordinateRoles)) {
                                        $result = true;
                                        break;
                                    }
                                }
                            }

                            if ($result == false && isset($member_array["groups"])) {
                                $current_user_groups = explode(",", fetchUserGroupids($this->current_user->id));
                                $res_array = array_intersect($member_array["groups"], $current_user_groups);
                                if (!empty($res_array) && count($res_array) > 0)
                                    $result = true;
                                else
                                    $result = false;
                            }
                        }
                    }
                    break;
            }
        }

        return $result;
    }

    private function getSubRoleUserIds($roleid) {
        $subRoleUserIds = array();
        $subordinateUsers = $this->getRoleAndSubordinateUserIds($roleid);
        if (!empty($subordinateUsers) && count($subordinateUsers) > 0) {
            $currRoleUserIds = getRoleUserIds($roleid);
            $subRoleUserIds = array_diff($subordinateUsers, $currRoleUserIds);
        }

        return $subRoleUserIds;
    }

    public function GetSharingMemberArray($reports4youid) {
        $adb = PearDatabase::getInstance();
        $sql = "SELECT shareid, setype FROM  its4you_reports4you_sharing WHERE reports4youid = ? ORDER BY setype ASC";
        $result = $adb->pquery($sql, array($reports4youid));
        $memberArray = array();
        while ($row = $adb->fetchByAssoc($result)) {
            $memberArray[$row["setype"]][] = $row["shareid"];
        }

        return $memberArray;
    }

    public function getPriModuleColumnsList($module) {
        if (is_numeric($module)) {
            $module = vtlib_getModuleNameById($module);
        }
        if (!isset($this->module_list) || !isset($this->module_list[$module]) || empty($this->module_list)) {
            $this->initListOfModules();
        }
        global $default_charset;
        foreach ($this->module_list[$module] as $key => $value) {
            if (is_numeric($value) && !is_numeric($key)) {
                $key_s = $key;
                $key = $value;
                $value = $key_s;
            }
            $temp = $this->getColumnsListbyBlock($module, $key);
            //$value = html_entity_decode($value, ENT_QUOTES, $default_charset);
            if (!empty($ret_module_list[$module][$value])) {
                if (!empty($temp)) {
                    $ret_module_list[$module][$value] = array_merge($ret_module_list[$module][$value], $temp);
                }
            } else {
                // $ret_module_list[$module][$value] = $this->getColumnsListbyBlock($module, $key);
                $ret_module_list[$module][$value] = $temp;
            }
        }
        $this->pri_module_columnslist[$module] = $ret_module_list[$module];
        return $ret_module_list;
    }

    /** Function to get the Related module list in vtiger_reports
     *  This function generates the list of secondary modules in vtiger_reports
     *  and returns the related module as an Array
     */
    function getReportRelatedModules($moduleid) {
        global $app_list_strings;
        global $mod_strings;
        $module = vtlib_getModuleNameById($moduleid);

        $optionhtml = Array();
        if (vtlib_isModuleActive($module)) {
            if (!empty($this->related_modules[$module])) {
                foreach ($this->related_modules[$module] as $rel_modules) {
                    $relmod_lang = $rel_modules["name"];
                    $relmod_str = $rel_modules["id"];
                    $relmod_arr = explode("x", $relmod_str);
                    $relmod_id = $relmod_arr[0];
                    $relmod_name = vtlib_getModuleNameById($relmod_id);

                    if (isPermitted($relmod_name, 'index') == "yes") {
                        $rel_tabid = getTabid($relmod_name);
                        $optionhtml [] = array('id' => $relmod_str,
                            'name' => $relmod_lang,
                            'checked' => (in_array($relmod_str, $this->relatedmodulesarray)) ? 'checked' : '',
                        );
                    }
                }
                if ($module == "Calendar") {
                    $this->init_list_for_module("Events");
                    foreach ($this->related_modules["Events"] as $rel_modules) {
                        $relmod_lang = $rel_modules["name"] . " " . vtranslate("Events", $module);
                        $relmod_str = $rel_modules["id"];
                        $relmod_arr = explode("x", $relmod_str);
                        $relmod_id = $relmod_arr[0];
                        $relmod_name = vtlib_getModuleNameById($relmod_id);

                        if (isPermitted($relmod_name, 'index') == "yes") {
                            $rel_tabid = getTabid($relmod_name);
                            $optionhtml [] = array('id' => $relmod_str,
                                'name' => $relmod_lang,
                                'checked' => (in_array($relmod_str, $this->relatedmodulesarray)) ? 'checked' : '',
                            );
                        }
                    }
                }
            }
        }

        return $optionhtml;
    }

    function in_multiarray($elem, $array, $field = "name") {
        $top = sizeof($array) - 1;
        $bottom = 0;
        if (!empty($array)) {
            while ($bottom <= $top) {
                if ($array[$bottom][$field] == $elem) {
                    return true;
                } else {
                    if (is_array($array[$bottom][$field])) {
                        if (in_multiarray($elem, ($array[$bottom][$field]))) {
                            return true;
                        }
                    }
                }
                $bottom++;
            }
        }
        return false;
    }

    private function init_list_for_module($module) {
        global $old_related_modules;
        global $app_strings;
        $adb = PearDatabase::getInstance();
        $tabid = getTabid($module);
        // special related fields 
        $standard_fields_res = $adb->pquery("SELECT DISTINCT fieldid, fieldlabel, uitype FROM vtiger_field WHERE uitype IN (51,57,58,59,68,73,75,76,78,80,81) AND tabid=?", array($tabid));

        $related_fields_array = array();
        if ($adb->num_rows($standard_fields_res)) {
            while ($st_rel_row = $adb->fetch_array($standard_fields_res)) {
                $field_id = $st_rel_row["fieldid"];
                $field_rel = $st_rel_row["fieldlabel"];
                $field_rel = vtranslate($field_rel);
                if (!in_array($field_id, $related_fields_array)) {
                    $related_fields_array[] = $field_id;
                    switch ($st_rel_row["uitype"]) {
                        case "51":
                            if (vtlib_isModuleActive("Accounts")) {
                                //$relmodule_lbl = vtranslate("Accounts","Accounts")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Accounts", "Accounts") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Accounts") . "x$field_id");
                                }
                            }
                            break;
                        case "57":
                            if (vtlib_isModuleActive("Contacts")) {
                                $field_rel = vtranslate($field_rel);
                                //$relmodule_lbl = vtranslate("Contacts","Contacts")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Contacts", "Contacts") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Contacts") . "x$field_id");
                                }
                            }
                            break;
                        case "58":
                            if (vtlib_isModuleActive("Campaigns")) {
                                //$relmodule_lbl = vtranslate("Campaigns","Campaigns")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Campaigns", "Campaigns") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Campaigns") . "x$field_id");
                                }
                            }
                            break;
                        case "59":
                            if (vtlib_isModuleActive("Products")) {
                                //$relmodule_lbl = vtranslate("Products","Products")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Products", "Products") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Products") . "x$field_id");
                                }
                            }
                            break;
                        case "68":
                            if (vtlib_isModuleActive("Accounts")) {
                                //$relmodule_lbl = vtranslate("Accounts","Accounts")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Accounts", "Accounts") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Accounts") . "x$field_id");
                                }
                            }
                            if (vtlib_isModuleActive("Contacts")) {
                                //$relmodule_lbl = vtranslate("Contacts","Contacts")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Contacts", "Contacts") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Contacts") . "x$field_id");
                                }
                            }
                            break;
                        case "73":
                            if (vtlib_isModuleActive("Accounts")) {
                                //$relmodule_lbl = vtranslate("Accounts","Accounts")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Accounts", "Accounts") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Accounts") . "x$field_id");
                                }
                            }
                            break;
                        case "75":
                            if (vtlib_isModuleActive("Vendors")) {
                                //$relmodule_lbl = vtranslate("Vendors","Vendors")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Vendors", "Vendors") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Vendors") . "x$field_id");
                                }
                            }
                            break;
                        case "76":
                            if (vtlib_isModuleActive("Potentials")) {
                                //$relmodule_lbl = vtranslate("Potentials","Potentials")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Potentials", "Potentials") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Potentials") . "x$field_id");
                                }
                            }
                            break;
                        case "78":
                            if (vtlib_isModuleActive("Quotes")) {
                                //$relmodule_lbl = vtranslate("Quotes","Quotes")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Quotes", "Quotes") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Quotes") . "x$field_id");
                                }
                            }
                            break;
                        case "80":
                            if (vtlib_isModuleActive("SalesOrder")) {
                                //$relmodule_lbl = vtranslate("SalesOrder","SalesOrder")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("SalesOrder", "SalesOrder") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("SalesOrder") . "x$field_id");
                                }
                            }
                            break;
                        case "81":
                            if (vtlib_isModuleActive("Vendors")) {
                                //$relmodule_lbl = vtranslate("Vendors","Vendors")." ($field_rel)";
                                $relmodule_lbl = $field_rel . " (" . vtranslate("Vendors", "Vendors") . ")";
                                if (!$this->in_multiarray($relmodule_lbl, $this->related_modules[$module])) {
                                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid("Vendors") . "x$field_id");
                                }
                            }
                            break;
                    }
                }
            }
        }
    }

    // Initializes the module list for listing columns for report creation.
    public function initListOfModules() {
        global $old_related_modules;
        $adb = PearDatabase::getInstance();

        $this->inventory_modules = self::$inventory_modules;
        // $restricted_modules = array('Emails', 'Events', 'Webmails');
        $restricted_modules = array('Emails', 'Events', 'Webmails');
        global $current_user;
        if ($current_user->is_admin != "on") {
            $restricted_modules[] = "Users";
        }

        $restricted_blocks = array('LBL_IMAGE_INFORMATION', 'LBL_COMMENTS', 'LBL_COMMENT_INFORMATION');

        $this->module_id = array();
        $this->module_list = array();

        // Prefetch module info to check active or not and also get list of tabs
        $modulerows = vtlib_prefetchModuleActiveInfo(false);

        $cachedInfo = VTCacheUtils::lookupReport_ListofModuleInfos();

        if ($cachedInfo !== false) {
            $this->module_list = $cachedInfo['module_list'];
            $this->related_modules = $cachedInfo['related_modules'];
        } else {
            if ($modulerows) {
                foreach ($modulerows as $resultrow) {
                    if ($resultrow['presence'] == '1')
                        continue;      // skip disabled modules


                        
// ITS4YOU-UP SlOl 21. 2. 2014 11:38:13 add Assigned to Users module
                    if ($resultrow['isentitytype'] != '1' && $resultrow['name'] != "Users")
                        continue;  // skip extension modules
                    if (in_array($resultrow['name'], $restricted_modules)) { // skip restricted modules
                        continue;
                    }
                    if ($resultrow['name'] != 'Calendar') {
                        $this->module_id[$resultrow['tabid']] = $resultrow['name'];
                    } else {
                        $this->module_id[9] = $resultrow['name'];
                        $this->module_id[16] = $resultrow['name'];
                    }
                    // ITS4YOU-CR SlOl  2. 12. 2013 8:42:40 
                    $this->init_list_for_module($resultrow['name']);
                    // ITS4YOU-END 2. 12. 2013 8:42:43
                    $this->module_list[$resultrow['name']] = array();
                }

                $moduleids = array_keys($this->module_id);
                //$adb->setDebug(true);
                $reportblocks = $adb->pquery("SELECT blockid, blocklabel, tabid FROM vtiger_blocks WHERE tabid IN (" . generateQuestionMarks($moduleids) . ")", array($moduleids));
                // $reportblocks = $adb->pquery("SELECT blockid, blocklabel, tabid FROM vtiger_blocks WHERE tabid IN (9,16)", array());
                //$adb->setDebug(false);
                $prev_block_label = '';
                if ($adb->num_rows($reportblocks) > 0) {
                    while ($resultrow = $adb->fetch_array($reportblocks)) {
                        $blockid = $resultrow['blockid'];
                        $blocklabel = $resultrow['blocklabel'];
                        $module = $this->module_id[$resultrow['tabid']];

                        if (in_array($blocklabel, $restricted_blocks) ||
                                in_array($blockid, $this->module_list[$module]) ||
                                isset($this->module_list[$module][vtranslate($blocklabel, $module)])
                        ) {
                            continue;
                        }
                        if ($blocklabel != "") {
                            if ($module == 'Calendar' && $blocklabel == 'LBL_CUSTOM_INFORMATION') {
                                $this->module_list[$module][$blockid] = vtranslate($blocklabel, $module);
                            } elseif ($module == 'Calendar' && in_array($blocklabel, array("LBL_RECURRENCE_INFORMATION", "LBL_RELATED_TO"))) {
                                $this->module_list[$module][$blockid] = vtranslate($blocklabel, "Events");
                            } else {
                                $this->module_list[$module][$blockid] = vtranslate($blocklabel, $module);
                            }
                            $prev_block_label = $blocklabel;
                            // ak je blocklabel prazdny spustat toto ??? zistit !!!
                        } else {
                            $this->module_list[$module][$blockid] = vtranslate($prev_block_label, $module);
                        }
                    }
                }

                // tvorba vazby cez ui10 a pridanie stlpcov k danemu modulu
//    $adb->setDebug(true);
                $relatedmodules_pf = $adb->pquery("
                                    SELECT uitype,fieldid, CONCAT(fieldlabel,' (',relmodule,') ') AS name, vtiger_tab.tabid, relmodule FROM vtiger_fieldmodulerel
                                    INNER JOIN vtiger_tab on vtiger_tab.name = vtiger_fieldmodulerel.module 
                                    INNER JOIN vtiger_field USING (fieldid) 
                                    WHERE vtiger_tab.isentitytype = 1
                                    AND vtiger_tab.name NOT IN(" . generateQuestionMarks($restricted_modules) . ")
                                    AND vtiger_tab.presence = 0 AND vtiger_field.uitype='10' ", array($restricted_modules));
                //AND vtiger_tab.presence = 0 AND vtiger_field.uitype='10'  GROUP BY relmodule ", array($restricted_modules));
//    $adb->setDebug(false);
                if ($adb->num_rows($relatedmodules_pf) > 0) {
                    $related_fields_array = array();
                    while ($resultrow = $adb->fetch_array($relatedmodules_pf)) {
                        $tabid = $resultrow['tabid'];
                        $module = $this->module_id[$tabid];

                        if (!isset($this->related_modules[$module])) {
                            $this->related_modules[$module] = array();
                        }
                        $this->init_list_for_module($module);
                        $field_rel = $st_rel_row["fieldlabel"];
                        if (is_numeric(strpos($resultrow["name"], $resultrow['relmodule']))) {
                            $reltabid = getTabid($resultrow["relmodule"]);
                            $relmodule_lbl = vtranslate($resultrow['name']);
                            $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => $reltabid . "x" . $resultrow['fieldid']);
                        }
                    }
                }

                // pridanie modulu Users pre assigned user id ui type 53 na vyber poloziek
                // ITS4YOU-CR SlOl 21. 2. 2014 11:02:16
                foreach ($this->related_modules as $modulename => $related_array) {
                    $module_id = getTabid($modulename);
//$adb->setDebug(true);
                    $relatedmodules_at = $adb->pquery("SELECT fieldlabel, fieldid FROM vtiger_field WHERE uitype = ? AND tabid = ? AND presence IN (0,2)", array(53, $module_id));
//$adb->setDebug(false);
                    while ($row = $adb->fetchByAssoc($relatedmodules_at)) {
                        $user_module = "Users";
                        $user_module_lbl = vtranslate($user_module);
                        $this->related_modules[$modulename][] = array("name" => vtranslate($row['fieldlabel']) . " (" . $user_module_lbl . ")", "id" => getTabid($user_module) . "x" . $row['fieldid']);
                    }
                }
                // ITS4YOU-END 21. 2. 2014 11:02:18 
                // related modules with MIF signature (more_info relation)
                $relatedmodules_mi = $adb->pquery(
                        "SELECT vtiger_tab.name AS name, vtiger_relatedlists.tabid FROM vtiger_tab
                            INNER JOIN vtiger_relatedlists on vtiger_tab.tabid=vtiger_relatedlists.related_tabid
                            WHERE vtiger_tab.isentitytype=1
                            AND vtiger_tab.name NOT IN(" . generateQuestionMarks($restricted_modules) . ") 
                            AND vtiger_tab.presence = 0 AND vtiger_relatedlists.label!='Activity History'
                        UNION 
                        SELECT module, vtiger_tab.tabid 
                            FROM vtiger_fieldmodulerel 
                            INNER JOIN vtiger_tab on vtiger_tab.name = vtiger_fieldmodulerel.relmodule 
                            INNER JOIN vtiger_field ON vtiger_field.fieldid = vtiger_fieldmodulerel.fieldid AND vtiger_field.uitype != '10' 
                            WHERE vtiger_tab.isentitytype = 1 AND vtiger_tab.name NOT IN('Emails','Events','Webmails') AND vtiger_tab.presence = 0   
                        ", array($restricted_modules));
//$adb->setDebug(true);
                while ($resultrow = $adb->fetch_array($relatedmodules_mi)) {
                    $tabid = $resultrow['tabid'];
                    $module = $this->module_id[$tabid];
                    $relmodule_lbl = vtranslate($resultrow['name']);
                    $this->related_modules[$module][] = array("name" => $relmodule_lbl, "id" => getTabid($resultrow['name']) . "xMIF");
                }
//$adb->setDebug(false);
                // inventory modules related modules Services Products with INV signature (inventory relation)
                foreach ($this->related_modules as $module => $rel_array) {
                    foreach ($rel_array as $r_key => $r_d_array) {
                        if (in_array($module, $this->inventory_modules)) {
                            if (!in_array("Products", $r_d_array) && !$this->in_multiarray(vtranslate("Products"), $this->related_modules[$module])) {
                                $related_modules[$module][] = "Products";
                                $this->related_modules[$module][] = array("name" => vtranslate("Products"), "id" => getTabid("Products") . "xINV");
                                $r_d_array[] = "Products";
                            }
                            if (!in_array("Services", $r_d_array) && !$this->in_multiarray(vtranslate("Services"), $this->related_modules[$module])) {
                                $related_modules[$module][] = "Services";
                                $this->related_modules[$module][] = array("name" => vtranslate("Services"), "id" => getTabid("Services") . "xINV");
                                $r_d_array[] = "Services";
                            }
                        }
                    }
                }
                // Put the information in cache for re-use
                VTCacheUtils::updateReport_ListofModuleInfos($this->module_list, $this->related_modules);
            }
        }
    }

    //<<<<<<<<advanced filter>>>>>>>>>>>>>>

    public function getAdvanceFilterOptionsJSON($primarymodule) {
        $Options = array();

        $Options_json = "";
        global $default_charset;
        if ($primarymodule != "") {
            $p_options = getPrimaryColumns($Options, $primarymodule, true, $this);

            if (isset($_REQUEST["selectedColumnsStr"]) && $_REQUEST["selectedColumnsStr"] != "") {
                $selectedColumnsStr = vtlib_purify($_REQUEST["selectedColumnsStr"]);
                $selectedColumnsStringDecoded = html_entity_decode($selectedColumnsStr, ENT_QUOTES, $default_charset);
                $selectedColumns_arr = explode("<_@!@_>", $selectedColumnsStringDecoded);
            } else {
                $selectedColumnsStr = $this->reportinformations["selectedColumnsString"];
                $selectedColumnsStringDecoded = html_entity_decode($selectedColumnsStr, ENT_QUOTES, $default_charset);
                $selectedColumns_arr = explode(";", $selectedColumnsStringDecoded);
            }
            if ($selectedColumnsStr != "") {
                $opt_label = vtranslate("LBL_Filter_SelectedColumnsGroup", $this->currentModule);
                foreach ($selectedColumns_arr as $sc_key => $sc_col_str) {
                    if ($sc_col_str != "") {
                        $in_options = false;
                        foreach ($Options as $opt_group => $opt_array) {
                            if ($this->in_multiarray($sc_col_str, $opt_array, "value") === true) {
                                $in_options = true;
                                continue;
                            }
                        }
                        if ($in_options) {
                            continue;
                        } else {
                            list($sc_tablename, $sc_columnname, $sc_modulestr) = explode(':', $sc_col_str);
                            list($sc_module) = explode('_', $sc_modulestr);
                            $sc_module_id = getTabid($sc_module);
                            $sc_tablename = trim(strtolower($sc_tablename), "_mif");
                            $adb = PearDatabase::getInstance();
                            //$adb->setDebug(true);
                            $sc_field_row = $adb->fetchByAssoc($adb->pquery("SELECT uitype FROM vtiger_field WHERE tablename = ? and columnname = ? and tabid=?", array($sc_tablename, $sc_columnname, $sc_module_id)), 0);
                            //$adb->setDebug(false);
                            $sc_field_uitype = $sc_field_row["uitype"];
                            if (in_array($sc_field_uitype, ITS4YouReports::$s_uitypes)) {
                                $this->adv_sel_fields[$sc_col_str] = true;
                            }
                            $Options[$opt_label][] = array("value" => $sc_col_str, "text" => $this->getColumnStr_Label($sc_col_str));
                        }
                    }
                }
            }
            $secondarymodules = Array();
            if (!empty($this->related_modules[$primarymodule])) {
                foreach ($this->related_modules[$primarymodule] as $key => $value) {
                    $exploded_mid = explode("x", $value["id"]);
                    if (strtolower($exploded_mid[1]) != "mif") {
                        $secondarymodules[] = $value["id"];
                    }
                }
            }
            if (!empty($secondarymodules)) {
                $secondarymodules_str = implode(":", $secondarymodules);
                $this->getSecModuleColumnsList($secondarymodules_str);
                $Options_sec = getSecondaryColumns(array(), $secondarymodules_str, $this);
                if (!empty($Options_sec)) {
                    foreach ($Options_sec as $moduleid => $sec_options) {
                        $Options = array_merge($Options, $sec_options);
                    }
                }
            }
            foreach ($Options AS $optgroup => $optionsdata) {
                if ($Options_json != "")
                    $Options_json .= "(|@!@|)";
                $Options_json .= $optgroup;
                $Options_json .= "(|@|)";
                $Options_json .= Zend_JSON::encode($optionsdata);
            }
        }
        return $Options_json;
    }

    /** Function to get the list of its4you_reports4you folders when Save and run  the its4you_reports4you
     *  This function gets the its4you_reports4you folders from database and form
     *  a combo values of the folders and return 
     *  HTML of the combo values
     */
    function sgetRptFldrSaveReport() {
        $adb = PearDatabase::getInstance();
        $sql = "select * from its4you_reports4you_folder order by folderid";
        $result = $adb->pquery($sql, array());
        $reportfldrow = $adb->fetch_array($result);
        $x = 0;
        do {
            $shtml .= "<option value='" . $reportfldrow['folderid'] . "'>" . $reportfldrow['foldername'] . "</option>";
        } while ($reportfldrow = $adb->fetch_array($result));

        return $shtml;
    }

    /** Function to get the column to total vtiger_fields in Reports 
     *  This function gets columns to total vtiger_field 
     *  and generated the html for that vtiger_fields
     *  It returns the HTML of the vtiger_fields along with the check boxes
     */
    function sgetColumntoTotal($primarymoduleid, $secondarymodule) {
        $options = Array();

        $options [] = $this->sgetColumnstoTotalHTML($primarymoduleid, 0);
        if (!empty($secondarymodule)) {
            for ($i = 0; $i < count($secondarymodule); $i++) {
                $options [] = $this->sgetColumnstoTotalHTML(vtlib_getModuleNameById($secondarymodule[$i]), ($i + 1));
            }
        }
        return $options;
    }

    /** Function to get the selected columns of total vtiger_fields in Reports
     *  This function gets selected columns of total vtiger_field
     *  and generated the html for that vtiger_fields
     *  It returns the HTML of the vtiger_fields along with the check boxes
     */
    function sgetColumntoTotalSelected($primarymodule, $secondarymodule, $reportid) {
        $adb = PearDatabase::getInstance();
        $options = Array();
        if ($reportid != "") {
            // if (!isset($this->columnssummary) && $_REQUEST["file"] != "ChangeSteps")
            $ssql = "select its4you_reports4you_summary.* from its4you_reports4you_summary inner join its4you_reports4you on its4you_reports4you.reports4youid = its4you_reports4you_summary.reportsummaryid where its4you_reports4you.reports4youid=?";
            $result = $adb->pquery($ssql, array($reportid));
            if ($result) {
                $reportsummaryrow = $adb->fetch_array($result);

                do {
                    $this->columnssummary[] = $reportsummaryrow["columnname"];
                } while ($reportsummaryrow = $adb->fetch_array($result));
            }
        }
        $options [] = $this->sgetColumnstoTotalHTML($primarymodule, 0);
        if (!empty($secondarymodule)) {
            for ($i = 0; $i < count($secondarymodule); $i++) {
                // ITS4YOU-UP SlOl 1. 10. 2013 13:42:25
                $options [] = $this->sgetColumnstoTotalHTML($secondarymodule[$i], ($i + 1));
            }
        }

        return $options;
    }

    public static function getColumnsTotalRow($tabid) {
        $adb = PearDatabase::getInstance();
        $ret_result = "";
        if ($tabid != "") {
            $sparams = array($tabid);

            global $current_user;
            $user_privileges_path = 'user_privileges/user_privileges_' . $current_user->id . '.php';
            if (file_exists($user_privileges_path)) {
                require($user_privileges_path);
            } else {
                $profileGlobalPermission = array(0 => 1, 1 => 1);
            }
            $j_ssql = $w_ssql = "";
            if ($is_admin != true || $profileGlobalPermission[1] != 0 || $profileGlobalPermission[2] != 0) {
                $profileList = getCurrentUserProfileList();
                if (count($profileList) > 0) {
                    $w_ssql .= " AND vtiger_profile2field.profileid IN ('" . join("'", $profileList) . "')";
                }
                $j_ssql .= " INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid=vtiger_field.fieldid 
                            INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid=vtiger_field.fieldid ";
            }
            $ssql = "SELECT * FROM vtiger_field 
INNER JOIN vtiger_tab ON vtiger_tab.tabid = vtiger_field.tabid  
$j_ssql
WHERE 
(vtiger_field.uitype in (7,9,71,72) OR (vtiger_field.uitype = 1 AND (vtiger_field.typeofdata LIKE 'N%' OR vtiger_field.typeofdata LIKE 'I%'))) 
AND vtiger_field.tabid=$tabid AND vtiger_field.displaytype in (1,2,3) AND vtiger_field.presence IN (0,2) $w_ssql ";
            $ssql .= " ORDER BY vtiger_field.block asc, vtiger_field.sequence ASC";

            $result = $adb->pquery($ssql, array());
            if ($result) {
                $no_rows = $adb->num_rows($result);
                if ($no_rows > 0) {
                    $ret_result = $result;
                }
            }
        }
        return $ret_result;
    }

    /** Function to form the HTML for columns to total	
     *  This function formulates the HTML format of the
     *  vtiger_fields along with four checkboxes
     *  It returns the HTML of the vtiger_fields along with the check boxes
     */
    function sgetColumnstoTotalHTML($moduleid) {
        $mod_arr = explode("x", $moduleid);
        $module = vtlib_getModuleNameById($mod_arr[0]);
        $fieldidstr = "";
        if (isset($mod_arr[1]) && $mod_arr[1] != "") {
            $fieldidstr = ":" . $mod_arr[1];
        }
        //retreive the vtiger_tabid	

        $adb = PearDatabase::getInstance();
        $user_privileges_path = 'user_privileges/user_privileges_' . $this->current_user->id . '.php';
        if (file_exists($user_privileges_path)) {
            require($user_privileges_path);
        }
        $tabid = getTabid($module);
        $escapedchars = Array('_SUM', '_AVG', '_MIN', '_MAX', '_CNT');

        $result = self::getColumnsTotalRow($tabid);
        $options_list = Array();
        if ($adb->num_rows($result) > 0) {
            do {
                $typeofdata = explode("~", $columntototalrow["typeofdata"]);

                //if ($typeofdata[0] == "N" || $typeofdata[0] == "I" || $typeofdata[0] == "NN") {
                $options = Array();
                if (isset($this->columnssummary)) {
                    $selectedcolumn = "";
                    $selectedcolumn1 = "";

                    for ($i = 0; $i < count($this->columnssummary); $i++) {
                        $selectedcolumnarray = explode(":", $this->columnssummary[$i]);
                        $selectedcolumn = $selectedcolumnarray[1] . ":" . $selectedcolumnarray[2] . ":" . str_replace($escapedchars, "", $selectedcolumnarray[3]);

                        if ($selectedcolumn != $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel']) {
                            $selectedcolumn = "";
                        } else {
                            $selectedcolumn1[$selectedcolumnarray[4]] = $this->columnssummary[$i];
                        }
                    }

                    if (isset($_REQUEST["record"]) && $_REQUEST["record"] != '') {
                        $options['label'][] = vtranslate($columntototalrow['tablabel'], $columntototalrow['tablabel']) . ' -' . vtranslate($columntototalrow['fieldlabel'], $columntototalrow['tablabel']);
                    }

                    $common_fieldlabel = $columntototalrow['fieldlabel'];
                    $columntototalrow['fieldlabel'] = $columntototalrow['fieldlabel'];
                    $options [] = vtranslate($columntototalrow['tablabel'], $columntototalrow['tablabel']) . ' - ' . vtranslate($columntototalrow['fieldlabel'], $columntototalrow['tablabel']);
                    if ($selectedcolumn1[2] == "cb:" . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . "_SUM:2" . $fieldidstr) {
                        $options [] = '<input checked name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . '_SUM:2' . $fieldidstr . '" type="checkbox" value="">';
                    } else {
                        $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . '_SUM:2' . $fieldidstr . '" type="checkbox" value="">';
                    }
                    if ($selectedcolumn1[3] == "cb:" . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . "_AVG:3" . $fieldidstr) {
                        $options [] = '<input checked name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . '_AVG:3' . $fieldidstr . '" type="checkbox" value="">';
                    } else {
                        $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . '_AVG:3' . $fieldidstr . '" type="checkbox" value="">';
                    }

                    if ($selectedcolumn1[4] == "cb:" . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . "_MIN:4" . $fieldidstr) {
                        $options [] = '<input checked name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . '_MIN:4' . $fieldidstr . '" type="checkbox" value="">';
                    } else {
                        $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . '_MIN:4' . $fieldidstr . '" type="checkbox" value="">';
                    }

                    if ($selectedcolumn1[5] == "cb:" . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . "_MAX:5" . $fieldidstr) {
                        $options [] = '<input checked name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . '_MAX:5' . $fieldidstr . '" type="checkbox" value="">';
                    } else {
                        $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . '_MAX:5' . $fieldidstr . '" type="checkbox" value="">';
                    }

                    if ($selectedcolumn1[6] == "cb:" . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . "_CNT:6" . $fieldidstr) {
                        $options [] = '<input checked name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . '_CNT:6' . $fieldidstr . '" type="checkbox" value="">';
                    } else {
                        $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'] . '_CNT:6' . $fieldidstr . '" type="checkbox" value="">';
                    }
                } else {
                    if (isset($_REQUEST["record"]) && $_REQUEST["record"] != '') {
                        $options['label'][] = vtranslate($columntototalrow['tablabel'], $columntototalrow['tablabel']) . ' -' . vtranslate($columntototalrow['fieldlabel'], $columntototalrow['tablabel']);
                    }

                    $options [] = vtranslate($columntototalrow['tablabel'], $columntototalrow['tablabel']) . ' - ' . vtranslate($common_fieldlabel, $columntototalrow['tablabel']);

                    $option_name = 'cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'];
                    $options [] = '<input name="' . $option_name . '_SUM:2' . $fieldidstr . '" type="checkbox" value="">';
                    $options [] = '<input name="' . $option_name . '_AVG:3' . $fieldidstr . '" type="checkbox" value="">';
                    $options [] = '<input name="' . $option_name . '_MIN:4' . $fieldidstr . '" type="checkbox" value="">';
                    $options [] = '<input name="' . $option_name . '_MAX:5' . $fieldidstr . '" type="checkbox" value="">';
                    $options [] = '<input name="' . $option_name . '_CNT:6' . $fieldidstr . '" type="checkbox" value="">';
                }
                $options_list [] = $options;
                //}
            } while ($columntototalrow = $adb->fetch_array($result));
        }

        return $options_list;
    }

    function getGroupFilterList($reportid) {
        global $modules;
        global $default_charset;

        $adb = PearDatabase::getInstance();
        $groupft_criteria = array();

        $sql = 'SELECT * FROM  its4you_reports4you_relcriteria_grouping WHERE queryid = ? AND groupid = 0 ORDER BY groupid';
        $groupsresult = $adb->pquery($sql, array($reportid));

        //$j = 0;
        while ($relcriteriagroup = $adb->fetch_array($groupsresult)) {
            $groupId = $relcriteriagroup["groupid"];
            $groupCondition = $relcriteriagroup["group_condition"];

            $ssql = 'select  its4you_reports4you_relcriteria.* from its4you_reports4you 
						inner join  its4you_reports4you_relcriteria on  its4you_reports4you_relcriteria.queryid = its4you_reports4you.reports4youid
						left join  its4you_reports4you_relcriteria_grouping on  its4you_reports4you_relcriteria.queryid =  its4you_reports4you_relcriteria_grouping.queryid 
								and  its4you_reports4you_relcriteria.groupid =  its4you_reports4you_relcriteria_grouping.groupid';
            $ssql.= " where its4you_reports4you.reports4youid = ? AND  its4you_reports4you_relcriteria.groupid = ? order by  its4you_reports4you_relcriteria.columnindex";

            $result = $adb->pquery($ssql, array($reportid, $groupId));
            $noOfColumns = $adb->num_rows($result);
            if ($noOfColumns <= 0)
                continue;
            while ($relcriteriarow = $adb->fetch_array($result)) {
                $columnIndex = $relcriteriarow["columnindex"];
                $criteria = array();
                $criteria['columnname'] = html_entity_decode($relcriteriarow["columnname"], ENT_QUOTES, $default_charset);
                $criteria['comparator'] = $relcriteriarow["comparator"];
                $advfilterval = $relcriteriarow["value"];
                $col = explode(":", $relcriteriarow["columnname"]);
                $temp_val = explode(",", $relcriteriarow["value"]);
                if ($col[4] == 'D' || ($col[4] == 'T' && $col[1] != 'time_start' && $col[1] != 'time_end') || ($col[4] == 'DT')) {
                    $val = Array();
                    for ($x = 0; $x < count($temp_val); $x++) {
                        list($temp_date, $temp_time) = explode(" ", $temp_val[$x]);
                        $temp_date = getValidDisplayDate(trim($temp_date));
                        if (trim($temp_time) != '')
                            $temp_date .= ' ' . $temp_time;
                        $val[$x] = $temp_date;
                    }
                    $advfilterval = implode(",", $val);
                }
                $criteria['value'] = decode_html($advfilterval);
                $criteria['column_condition'] = $relcriteriarow["column_condition"];

                $groupft_criteria[$this->j] = $criteria;
                $this->j++;
            }
        }
        $this->groupft_criteria = $groupft_criteria;
        return true;
    }

    /** Function to form a javascript to determine the start date and end date for a standard filter 
     *  This function is to form a javascript to determine
     *  the start date and End date from the value selected in the combo lists
     */
    function getCriteriaJS() {
        $today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $tomorrow = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
        $yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));

        $currentmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m"), "01", date("Y")));
        $currentmonth1 = date("Y-m-t");
        $lastmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, "01", date("Y")));
        $lastmonth1 = date("Y-m-t", strtotime("-1 Month"));
        $nextmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, "01", date("Y")));
        $nextmonth1 = date("Y-m-t", strtotime("+1 Month"));

        global $current_user;
        $dayoftheweek = $current_user->column_fields["dayoftheweek"];
        //ITS4YouReports::sshow("DOW2 $dayoftheweek");

        $lastweek0 = date("Y-m-d", strtotime("-2 week $dayoftheweek"));
        $lastweek1 = date("Y-m-d", strtotime("-1 week $dayoftheweek -1 day"));

        $thisweek0 = date("Y-m-d", strtotime("-1 week $dayoftheweek"));
        $thisweek1 = date("Y-m-d", strtotime("this $dayoftheweek -1 day"));

        $nextweek0 = date("Y-m-d", strtotime("this $dayoftheweek"));
        $nextweek1 = date("Y-m-d", strtotime("+1 week $dayoftheweek -1 day"));

        $next7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 6, date("Y")));
        $next15days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 14, date("Y")));
        $next30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 29, date("Y")));
        $next60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 59, date("Y")));
        $next90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 89, date("Y")));
        $next120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 119, date("Y")));

        $last7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")));
        $last15days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 14, date("Y")));
        $last30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 29, date("Y")));
        $last60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 59, date("Y")));
        $last90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 89, date("Y")));
        $last120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 119, date("Y")));

        $currentFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
        $currentFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")));
        $lastFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") - 1));
        $lastFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y") - 1));
        $nextFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") + 1));
        $nextFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y") + 1));

        $todaymore_start = $today;
        $todayless_end = $today;
        $older1days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
        $older7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));
        $older15days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")));
        $older30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 30, date("Y")));
        $older60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 60, date("Y")));
        $older90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 90, date("Y")));
        $older120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 120, date("Y")));

        if (date("m") <= 3) {
            $cFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y")));
            $nFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
            $pFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y") - 1));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y") - 1));
        } else if (date("m") > 3 and date("m") <= 6) {
            $pFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y")));
            $cFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
            $nFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
        } else if (date("m") > 6 and date("m") <= 9) {
            $nFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y")));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));
            $pFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
            $cFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
        } else if (date("m") > 9 and date("m") <= 12) {
            $nFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") + 1));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y") + 1));
            $pFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
            $cFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));
        }

        $sjsStr = '<script language="JavaScript" type="text/javaScript">
			function showDateRange(s_obj, e_obj, st_obj, et_obj, type )
			{
				if (type!="custom")
				{
					s_obj.readOnly=true;
					e_obj.readOnly=true;
					st_obj.style.visibility="hidden";
					et_obj.style.visibility="hidden";
				}
				else
				{
					s_obj.readOnly=false;
					e_obj.readOnly=false;
					st_obj.style.visibility="visible";
					et_obj.style.visibility="visible";
				}
				if( type == "today" )
				{

					s_obj.value = "' . getValidDisplayDate($today) . '";
					e_obj.value = "' . getValidDisplayDate($today) . '";
				}
				else if( type == "yesterday" )
				{

					s_obj.value = "' . getValidDisplayDate($yesterday) . '";
					e_obj.value = "' . getValidDisplayDate($yesterday) . '";
				}
				else if( type == "tomorrow" )
				{

					s_obj.value = "' . getValidDisplayDate($tomorrow) . '";
					e_obj.value = "' . getValidDisplayDate($tomorrow) . '";
				}        
				else if( type == "thisweek" )
				{

					s_obj.value = "' . getValidDisplayDate($thisweek0) . '";
					e_obj.value = "' . getValidDisplayDate($thisweek1) . '";
				}                
				else if( type == "lastweek" )
				{

					s_obj.value = "' . getValidDisplayDate($lastweek0) . '";
					e_obj.value = "' . getValidDisplayDate($lastweek1) . '";
				}                
				else if( type == "nextweek" )
				{

					s_obj.value = "' . getValidDisplayDate($nextweek0) . '";
					e_obj.value = "' . getValidDisplayDate($nextweek1) . '";
				}                

				else if( type == "thismonth" )
				{

					s_obj.value = "' . getValidDisplayDate($currentmonth0) . '";
					e_obj.value = "' . getValidDisplayDate($currentmonth1) . '";
				}                

				else if( type == "lastmonth" )
				{

					s_obj.value = "' . getValidDisplayDate($lastmonth0) . '";
					e_obj.value = "' . getValidDisplayDate($lastmonth1) . '";
				}             
				else if( type == "nextmonth" )
				{

					s_obj.value = "' . getValidDisplayDate($nextmonth0) . '";
					e_obj.value = "' . getValidDisplayDate($nextmonth1) . '";
				}           
				else if( type == "next7days" )
				{

					s_obj.value = "' . getValidDisplayDate($today) . '";
					e_obj.value = "' . getValidDisplayDate($next7days) . '";
				}                
				else if( type == "next15days" )
				{

					s_obj.value = "' . getValidDisplayDate($today) . '";
					e_obj.value = "' . getValidDisplayDate($next15days) . '";
				}                
				else if( type == "next30days" )
				{

					s_obj.value = "' . getValidDisplayDate($today) . '";
					e_obj.value = "' . getValidDisplayDate($next30days) . '";
				}                
				else if( type == "next60days" )
				{

					s_obj.value = "' . getValidDisplayDate($today) . '";
					e_obj.value = "' . getValidDisplayDate($next60days) . '";
				}                
				else if( type == "next90days" )
				{

					s_obj.value = "' . getValidDisplayDate($today) . '";
					e_obj.value = "' . getValidDisplayDate($next90days) . '";
				}        
				else if( type == "next120days" )
				{

					s_obj.value = "' . getValidDisplayDate($today) . '";
					e_obj.value = "' . getValidDisplayDate($next120days) . '";
				}        
				else if( type == "last7days" )
				{

					s_obj.value = "' . getValidDisplayDate($last7days) . '";
					e_obj.value =  "' . getValidDisplayDate($today) . '";
				}          
				else if( type == "last15days" )
				{

					s_obj.value = "' . getValidDisplayDate($last15days) . '";
					e_obj.value =  "' . getValidDisplayDate($today) . '";
				}                        
				else if( type == "last30days" )
				{

					s_obj.value = "' . getValidDisplayDate($last30days) . '";
					e_obj.value = "' . getValidDisplayDate($today) . '";
				}                
				else if( type == "last60days" )
				{

					s_obj.value = "' . getValidDisplayDate($last60days) . '";
					e_obj.value = "' . getValidDisplayDate($today) . '";
				}        
				else if( type == "last90days" )
				{

					s_obj.value = "' . getValidDisplayDate($last90days) . '";
					e_obj.value = "' . getValidDisplayDate($today) . '";
				}        
				else if( type == "last120days" )
				{

					s_obj.value = "' . getValidDisplayDate($last120days) . '";
					e_obj.value = "' . getValidDisplayDate($today) . '";
				}        
				else if( type == "thisfy" )
				{

					s_obj.value = "' . getValidDisplayDate($currentFY0) . '";
					e_obj.value = "' . getValidDisplayDate($currentFY1) . '";
				}                
				else if( type == "prevfy" )
				{

					s_obj.value = "' . getValidDisplayDate($lastFY0) . '";
					e_obj.value = "' . getValidDisplayDate($lastFY1) . '";
				}                
				else if( type == "nextfy" )
				{

					s_obj.value = "' . getValidDisplayDate($nextFY0) . '";
					e_obj.value = "' . getValidDisplayDate($nextFY1) . '";
				}                
				else if( type == "nextfq" )
				{

					s_obj.value = "' . getValidDisplayDate($nFq) . '";
					e_obj.value = "' . getValidDisplayDate($nFq1) . '";
				}                        
				else if( type == "prevfq" )
				{

					s_obj.value = "' . getValidDisplayDate($pFq) . '";
					e_obj.value = "' . getValidDisplayDate($pFq1) . '";
				}                
				else if( type == "thisfq" )
				{
					s_obj.value = "' . getValidDisplayDate($cFq) . '";
					e_obj.value = "' . getValidDisplayDate($cFq1) . '";
				}        
                                else if( type == "todaymore" )
				{
					s_obj.value = "' . getValidDisplayDate($todaymore_start) . '";
					e_obj.value = "";
				}
                                else if( type == "todayless" )
				{
					s_obj.value = "";
					e_obj.value = "' . getValidDisplayDate($todayless_end) . '";
				}
                                else if( type == "older1days" )
				{
					s_obj.value = "";
					e_obj.value = "' . getValidDisplayDate($older1days) . '";
				}
                                else if( type == "older7days" )
				{
					s_obj.value = "";
					e_obj.value = "' . getValidDisplayDate($older7days) . '";
				}
                                else if( type == "older15days" )
				{
					s_obj.value = "";
					e_obj.value = "' . getValidDisplayDate($older15days) . '";
				}
                                else if( type == "older30days" )
				{
					s_obj.value = "";
					e_obj.value = "' . getValidDisplayDate($older30days) . '";
				}
                                else if( type == "older60days" )
				{
					s_obj.value = "";
					e_obj.value = "' . getValidDisplayDate($older60days) . '";
				}
                                else if( type == "older90days" )
				{
					s_obj.value = "";
					e_obj.value = "' . getValidDisplayDate($older90days) . '";
				}
                                else if( type == "older120days" )
				{
					s_obj.value = "";
					e_obj.value = "' . getValidDisplayDate($older120days) . '";
				}
				else
				{
//					s_obj.value = "";
//					e_obj.value = "";
				}        
			}        
		</script>';
        return $sjsStr;
    }

    /** Function to get the combo values for the standard filter
     *  This function get the combo values for the standard filter for the given its4you_reports4you
     *  and return a HTML string 
     */
    function getSelectedStdFilterCriteria($selecteddatefilter = "") {

        foreach ($this->Date_Filter_Values AS $key => $value) {
            if ($selecteddatefilter == $key)
                $selected = "selected";
            else
                $selected = "";

            $sshtml .= "<option value='" . $key . "' " . $selected . ">" . vtranslate($value, $this->currentModule) . "</option>";
        }
        return $sshtml;
    }

    function getModulePrefix($module) {
        $adb = PearDatabase::getInstance();
        $secmodule_arr = explode("x", $module);
        $module_id = $secmodule_arr[0];
        $field_id = (isset($secmodule_arr[1]) && $secmodule_arr[1] != "" ? $secmodule_arr[1] : "");

        $fieldname = "";
        if ($field_id != "" && !in_array($field_id, ITS4YouReports::$customRelationTypes)) {
            $fieldname_row = $adb->fetchByAssoc($adb->pquery("SELECT fieldlabel,uitype FROM vtiger_field WHERE fieldid=?", array($field_id)), 0);
            $fieldname = " " . $fieldname_row["fieldlabel"];
        } elseif ($field_id == "INV") {
            $fieldname = " Inventory";
        } elseif ($field_id == "MIF") {
            $fieldname = " More Information";
        }
        return $fieldname;
    }

    public function getSecModuleColumnsList($module) {
        if ($module != "") {
            $adb = PearDatabase::getInstance();
            $secmodule = explode(":", $module);
            for ($i = 0; $i < count($secmodule); $i++) {
                $secmodule_arr = explode("x", $secmodule[$i]);
                $module_id = $secmodule_arr[0];
                $field_id = (isset($secmodule_arr[1]) && $secmodule_arr[1] != "" ? $secmodule_arr[1] : "");

                $fieldname = $this->getModulePrefix($secmodule[$i]);

                $modulename = vtlib_getModuleNameById($module_id);
                if ($modulename != "") {
                    if (!isset($this->module_list[$modulename])) {
                        $this->initListOfModules();
                    }
                    if ($this->module_list[$modulename]) {
                        foreach ($this->module_list[$modulename] as $key => $value) {
                            /* $temp = $this->getColumnsListbyBlock($modulename, $key, $field_id);
                              if (!empty($ret_module_list[$modulename . $fieldname][$value])) {
                              if (!empty($temp)) {
                              $ret_module_list[$modulename . $fieldname][$value] = array_merge($ret_module_list[$modulename . $fieldname][$value], $temp);
                              }
                              } else {
                              $ret_module_list[$modulename . $fieldname][$value] = $this->getColumnsListbyBlock($modulename, $key, $field_id);
                              } */
//ITS4YouReports::getR4UDifTime("SEC COl List 03 $modulename -> $key -> $field_id");
                            $ret_module_list[$modulename . $fieldname][$value] = $this->getColumnsListbyBlock($modulename, $key, $field_id);
//ITS4YouReports::getR4UDifTime("SEC COl List 04");
                        }
                        $this->sec_module_columnslist[$modulename . $fieldname] = $ret_module_list[$modulename . $fieldname];
                    }
                }
            }
        }

        return $ret_module_list;
    }

    /** Function to get vtiger_fields for the given module and block
     *  This function gets the vtiger_fields for the given module
     *  It accepts the module and the block as arguments and 
     *  returns the array column lists
     *  Array module_columnlist[ vtiger_fieldtablename:fieldcolname:module_fieldlabel1:fieldname:fieldtypeofdata]=fieldlabel
     */
    function getColumnsListbyBlock($module, $block, $relfieldid = "") {
        $r4u_columnlist_name = ITS4YouReports::getITS4YouReportStoreName($module, $block);
        $r4u_rel_fields_name = ITS4YouReports::getITS4YouReportStoreName("adv_rel_fields");
        $r4u_sel_fields_name = ITS4YouReports::getITS4YouReportStoreName("adv_sel_fields");
        unset($_SESSION[$r4u_columnlist_name]);
        unset($_SESSION[$r4u_rel_fields_name]);
        unset($_SESSION[$r4u_sel_fields_name]);
//return false;

        if ($r4u_columnlist_name != "" && isset($_SESSION[$r4u_columnlist_name]) && !empty($_SESSION[$r4u_columnlist_name])) {
            $module_columnlist = unserialize($_SESSION[$r4u_columnlist_name]);
            $this->adv_rel_fields = unserialize($_SESSION[$r4u_rel_fields_name]);
            $this->adv_sel_fields = unserialize($_SESSION[$r4u_sel_fields_name]);

            return $module_columnlist;
        } else {
            unset($_SESSION[$r4u_columnlist_name]);
            //unset($_SESSION[$r4u_rel_fields_name]);
            //unset($_SESSION[$r4u_sel_fields_name]);
            $adb = PearDatabase::getInstance();

            if (is_string($block))
                $block = explode(",", $block);

            $tabid = getTabid($module);
            if ($module == 'Calendar') {
                $tabid = array('9', '16');
            }
            $params = array($tabid, $block);

            $user_privileges_path = 'user_privileges/user_privileges_' . $this->current_user->id . '.php';
            if (file_exists($user_privileges_path)) {
                require($user_privileges_path);
            }

            //Security Check 
            if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
                $sql = "select * from vtiger_field where vtiger_field.tabid in (" . generateQuestionMarks($tabid) . ") and vtiger_field.block in (" . generateQuestionMarks($block) . ") and vtiger_field.displaytype in (1,2,3) and vtiger_field.presence in (0,2) ";

                //fix for Ticket #4016
                if ($module == "Calendar")
                    $sql.=" group by vtiger_field.fieldlabel order by sequence";
                else
                    $sql.=" order by sequence";
            }
            else {

                $profileList = getCurrentUserProfileList();
                $sql = "select * from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where vtiger_field.tabid in (" . generateQuestionMarks($tabid) . ")  and vtiger_field.block in (" . generateQuestionMarks($block) . ") and vtiger_field.displaytype in (1,2,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_field.presence in (0,2)";
                if (count($profileList) > 0) {
                    $sql .= " and vtiger_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
                    array_push($params, $profileList);
                }

                //fix for Ticket #4016
                if ($module == "Calendar")
                    $sql.=" group by vtiger_field.fieldid,vtiger_field.fieldlabel order by sequence";
                else
                    $sql.=" group by vtiger_field.fieldid order by sequence";
            }
//$adb->setDebug(true);
            $f_result = $adb->pquery($sql, $params);
//$adb->setDebug(false);

            $noofrows = $adb->num_rows($f_result);

            $lead_converted_added = false;
            for ($i = 0; $i < $noofrows; $i++) {

                //$_SESSION_r4u_rel_fields = unserialize($_SESSION[$r4u_rel_fields_name]);
                //$_SESSION_r4u_sel_fields = unserialize($_SESSION[$r4u_sel_fields_name]);

                $fieldid = $adb->query_result($f_result, $i, "fieldid"); // ITS4YOU-UP SlOl 1. 10. 2013 10:46:35
                $fieldtablename = $adb->query_result($f_result, $i, "tablename");
                if ($this->primarymodule != $module) {
                    $fieldtablename = $fieldtablename;
                    if ($relfieldid != "") {
                        $fieldtablename .= "_$relfieldid";
                    }
                }
                $fieldcolname = $adb->query_result($f_result, $i, "columnname");
                $fieldname = $adb->query_result($f_result, $i, "fieldname");
                $fieldtype = $adb->query_result($f_result, $i, "typeofdata");
                $uitype = $adb->query_result($f_result, $i, "uitype");
                $fieldtype = explode("~", $fieldtype);
                // Fix to get table alias for orderby and groupby sql
                /* if ($relfieldid == "" && in_array($uitype, array("10",))) {
                  $relfieldid = $fieldid;
                  } */

                $fieldtypeofdata = $fieldtype[0];
                //Here we Changing the displaytype of the field. So that its criteria will be displayed correctly in Reports Advance Filter.
                $fieldtypeofdata = ChangeTypeOfData_Filter($fieldtablename, $fieldcolname, $fieldtypeofdata);
                if ($uitype == 68 || $uitype == 59) {
                    $fieldtypeofdata = 'V';
                }

                $fieldlabel = $adb->query_result($f_result, $i, "fieldlabel");
                $fieldlabel1 = $fieldlabel;

                if ($relfieldid != "") {
                    $relfieldid_str = $relfieldid;
                }
                // this is defining module id for uitype 10
                if ($relfieldid != "" && $this->primarymodule != $module) {
                    $rel_field_row = $adb->fetchByAssoc($adb->pquery("SELECT uitype FROM vtiger_field WHERE fieldid = ? ", array($relfieldid)), 0);
                    $rel_field_uitype = $rel_field_row["uitype"];
                    if ($rel_field_uitype == 10) {
                        $relfieldid_str = getTabid($module) . ":" . $relfieldid;
                    }
                }
                $module_lbl = vtranslate($module, $module);

                $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $fieldlabel1 . ":" . $fieldname . ":" . $fieldtypeofdata . ($relfieldid != "" ? ":" . $relfieldid_str : "");

                // $optionvalue = $fieldtablename.":".$fieldcolname.":".$module."_".$fieldlabel1.":".$fieldname.":".$fieldtypeofdata;
                $adv_rel_field_val = '$' . $module . '#' . $fieldname . '$' . "::" . $module_lbl . " " . $fieldlabel;
                $this->adv_rel_fields[$fieldtypeofdata][] = $adv_rel_field_val;
                // ITS4YOU-CR SlOl 26. 3. 2014 10:57:41
                if (in_array($uitype, ITS4YouReports::$s_uitypes) && !array_key_exists($optionvalue, $this->adv_sel_fields) && !in_array($module, array("Users",))) {
                    $this->adv_sel_fields[$optionvalue] = true;
                }
                // ITS4YOU-END 26. 3. 2014 10:57:44 
                $translate_module = $module;
                //added to escape attachments fields in Reports as we have multiple attachments
                if ($module != 'HelpDesk' || $fieldname != 'filename') {
                    $module_columnlist[$optionvalue] = vtranslate($fieldlabel, $translate_module);
                }

                // ITS4YOU-CR SlOl - IS CONVERTED FIELD FOR LEADS 
                if ($module == "Leads" && $block == 13 && $i == ($noofrows - 1) && $lead_converted_added != true) {
                    $sc_col_str = "vtiger_leaddetails:converted:" . $module . "_Converted:converted:C";
                    $this->adv_sel_fields[$sc_col_str] = true;
                    $lead_converted_added = true;
                    $module_columnlist[$sc_col_str] = vtranslate("Converted", $module);
                }
                // CONVERTED END
                unset($_SESSION[$r4u_rel_fields_name]);
                $_SESSION[$r4u_rel_fields_name] = serialize($this->adv_rel_fields);
                unset($_SESSION[$r4u_sel_fields_name]);
                $_SESSION[$r4u_sel_fields_name] = serialize($this->adv_sel_fields);
            }
            $blockname = getBlockName($block);

            if ($blockname == 'LBL_RELATED_PRODUCTS' && in_array($module, self::$inventory_modules)) {
                if ($relfieldid != "") {
                    $rel_field_row = $adb->fetchByAssoc($adb->pquery("SELECT uitype FROM vtiger_field WHERE fieldid = ? ", array($relfieldid)), 0);
                    $rel_field_uitype = $rel_field_row["uitype"];
                    if ($rel_field_uitype == 10) {
                        $relfieldid_str = ":" . getTabid($module) . ":" . $relfieldid;
                    } else {
                        $relfieldid_str = ":" . $relfieldid;
                    }
                }

                $fieldtablename = 'vtiger_inventoryproductrel';
                if ($relfieldid != "") {
                    $fieldtablename .= "_$relfieldid";
                }
                $fields = array('prodname' => $module_lbl . " " . vtranslate('LBL_PRODUCT_SERVICE_NAME', $this->currentModule),
                    'ps_productcategory' => $module_lbl . " " . vtranslate('LBL_ITEM_CATEGORY', $this->currentModule),
                    'ps_productno' => $module_lbl . " " . vtranslate('LBL_ITEM_NO', $this->currentModule),
                    'comment' => $module_lbl . " " . vtranslate('Comments', $module),
                    'quantity' => $module_lbl . " " . vtranslate('Quantity', $module),
                    'listprice' => $module_lbl . " " . vtranslate('List Price', $module),
                    'ps_producttotal' => $module_lbl . " " . vtranslate('LBL_PRODUCT_TOTAL', $this->currentModule),
                    'discount' => $module_lbl . " " . vtranslate('Discount', $module),
                    'ps_productstotalafterdiscount' => $module_lbl . " " . vtranslate('LBL_PRODUCTTOTALAFTERDISCOUNT', $this->currentModule),
                    'ps_productvatsum' => $module_lbl . " " . vtranslate('LBL_PRODUCT_VAT_SUM', $this->currentModule),
                    'ps_producttotalsum' => $module_lbl . " " . vtranslate('LBL_PRODUCT_TOTAL_VAT', $this->currentModule),
                );
                $fields_datatype = array('prodname' => 'V',
                    'ps_productcategory' => 'V',
                    'ps_productno' => 'V',
                    'comment' => 'V',
                    'prodname' => 'V',
                    'quantity' => 'I',
                    'listprice' => 'I',
                    'ps_producttotal' => 'I',
                    'discount' => 'I',
                    'ps_productstotalafterdiscount' => 'I',
                    'ps_productvatsum' => 'I',
                    'ps_producttotalsum' => 'I',
                );
                $module_lbl = vtranslate($module, $module);
                foreach ($fields as $fieldcolname => $label) {
                    $fieldtypeofdata = $fields_datatype[$fieldcolname];
                    $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $label . ":" . $fieldcolname . ":" . $fieldtypeofdata . $relfieldid_str;
                    $module_columnlist[$optionvalue] = $label; // $module_lbl." ".
                }
            }

            $_SESSION[$r4u_columnlist_name] = serialize($module_columnlist);
            return $module_columnlist;
        }
    }

    function getSMOwnerIDColumn($module, $relfieldid = "") {
        $adb = PearDatabase::getInstance();

        $tabid = getTabid($module);
        if ($module == 'Calendar') {
            $tabid = array('9', '16');
        }
        $params = array($tabid);

        $user_privileges_path = 'user_privileges/user_privileges_' . $this->current_user->id . '.php';
        if (file_exists($user_privileges_path)) {
            require($user_privileges_path);
        }
        $sql = "select * from vtiger_field where vtiger_field.tabid in (" . generateQuestionMarks($tabid) . ") and vtiger_field.displaytype in (1,2,3) and vtiger_field.presence in (0,2) AND columnname = 'smownerid'";

        //fix for Ticket #4016
        if ($module == "Calendar")
            $sql.=" group by vtiger_field.fieldlabel order by sequence";
        else
            $sql.=" order by sequence";

        $result = $adb->pquery($sql, $params);
        $noofrows = $adb->num_rows($result);
        for ($i = 0; $i < $noofrows; $i++) {
            $fieldid = $adb->query_result($result, $i, "fieldid"); // ITS4YOU-UP SlOl 1. 10. 2013 10:46:35
            $fieldtablename = $adb->query_result($result, $i, "tablename") . $relfieldid;
            $fieldcolname = $adb->query_result($result, $i, "columnname");
            $fieldname = $adb->query_result($result, $i, "fieldname");
            $fieldtype = $adb->query_result($result, $i, "typeofdata");
            $uitype = $adb->query_result($result, $i, "uitype");
            $fieldtype = explode("~", $fieldtype);
            $fieldtypeofdata = $fieldtype[0];

            //Here we Changing the displaytype of the field. So that its criteria will be displayed correctly in Reports Advance Filter.
            $fieldtypeofdata = ChangeTypeOfData_Filter($fieldtablename, $fieldcolname, $fieldtypeofdata);
            if ($uitype == 68 || $uitype == 59) {
                $fieldtypeofdata = 'V';
            }

            $fieldlabel = $adb->query_result($result, $i, "fieldlabel");
            $fieldlabel1 = $fieldlabel;


            // this is defining module id for uitype 10
            if ($relfieldid != "") {
                $rel_field_row = $adb->fetchByAssoc($adb->pquery("SELECT uitype FROM vtiger_field WHERE fieldid = ? ", array($relfieldid)), 0);
                $rel_field_uitype = $rel_field_row["uitype"];
                if ($rel_field_uitype == 10) {
                    $relfieldid = getTabid($module) . ":" . $relfieldid;
                }
            }
            $module_lbl = vtranslate($module, $module);
            $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $fieldlabel1 . ":" . $fieldname . ":" . $fieldtypeofdata . ($relfieldid != "" ? ":" . $relfieldid : "");
            // $optionvalue = $fieldtablename.":".$fieldcolname.":".$module."_".$fieldlabel1.":".$fieldname.":".$fieldtypeofdata;
            $this->adv_rel_fields[$fieldtypeofdata][] = '$' . $module . '#' . $fieldname . '$' . "::" . $module_lbl . " " . $fieldlabel;
            //added to escape attachments fields in Reports as we have multiple attachments
            if ($module != 'HelpDesk' || $fieldname != 'filename')
                $module_columnlist[$optionvalue] = $fieldlabel; // $module_lbl." ".
        }
        return $module_columnlist;
    }

    function getAdvancedFilterList($reportid) {
        global $modules;
        global $default_charset;
        $adb = PearDatabase::getInstance();
        $advft_criteria = array();

        $sql = 'SELECT * FROM  its4you_reports4you_relcriteria_grouping WHERE queryid = ? AND groupid != 0 ORDER BY groupid';
        $groupsresult = $adb->pquery($sql, array($reportid));

        $std_filter_columns = $this->getStdFilterColumns();

        $i = 1;
        //$j = 0;
        while ($relcriteriagroup = $adb->fetch_array($groupsresult)) {
            $groupId = $relcriteriagroup["groupid"];
            $groupCondition = $relcriteriagroup["group_condition"];

            $ssql = 'select  its4you_reports4you_relcriteria.* from its4you_reports4you 
						inner join  its4you_reports4you_relcriteria on  its4you_reports4you_relcriteria.queryid = its4you_reports4you.reports4youid
						left join  its4you_reports4you_relcriteria_grouping on  its4you_reports4you_relcriteria.queryid =  its4you_reports4you_relcriteria_grouping.queryid 
								and  its4you_reports4you_relcriteria.groupid =  its4you_reports4you_relcriteria_grouping.groupid';
            $ssql.= " where its4you_reports4you.reports4youid = ? AND  its4you_reports4you_relcriteria.groupid = ? order by  its4you_reports4you_relcriteria.columnindex";

            $result = $adb->pquery($ssql, array($reportid, $groupId));
            $noOfColumns = $adb->num_rows($result);
            if ($noOfColumns <= 0)
                continue;

            $this->j = 0;
            while ($relcriteriarow = $adb->fetch_array($result)) {
                $columnIndex = $relcriteriarow["columnindex"];
                $criteria = array();
                $criteria['columnname'] = html_entity_decode($relcriteriarow["columnname"], ENT_QUOTES, $default_charset);
                $criteria['comparator'] = $relcriteriarow["comparator"];
                $advfilterval = html_entity_decode($relcriteriarow["value"], ENT_QUOTES, $default_charset);
                $col = explode(":", $relcriteriarow["columnname"]);

                if (in_array($criteria['columnname'], $std_filter_columns)) {
                    $f_date = array();
                    $temp_date = explode("<;@STDV@;>", $advfilterval);
                    $f_date[] = DateTimeField::convertToUserFormat($temp_date[0]);
                    $f_date[] = DateTimeField::convertToUserFormat($temp_date[1]);
                    $advfilterval = implode("<;@STDV@;>", $f_date);
                } else {
                    $temp_val = explode(",", $relcriteriarow["value"]);
                    if ($col[4] == 'D' || ($col[4] == 'T' && $col[1] != 'time_start' && $col[1] != 'time_end') || ($col[4] == 'DT')) {
                        $val = Array();
                        for ($x = 0; $x < count($temp_val); $x++) {
                            list($temp_date, $temp_time) = explode(" ", $temp_val[$x]);
                            $temp_date = getValidDisplayDate(trim($temp_date));
                            if (trim($temp_time) != '')
                                $temp_date .= ' ' . $temp_time;
                            $val[$x] = $temp_date;
                        }
                        $advfilterval = implode(",", $val);
                    }
                }
                $criteria['value'] = decode_html($advfilterval);
                $criteria['column_condition'] = $relcriteriarow["column_condition"];

                $advft_criteria[$i]['columns'][$this->j] = $criteria;
                $advft_criteria[$i]['condition'] = $groupCondition;
                $this->j++;
            }
            $i++;
        }

        $this->advft_criteria = $advft_criteria;
        return $advft_criteria;
    }

    function getSummariesFilterList($reportid) {
        global $modules;
        global $default_charset; //ITS4YOU VlMe Fix 
        $adb = PearDatabase::getInstance();
        $summaries_criteria = array();

        $ssql = 'select  its4you_reports4you_relcriteria_summaries.* from its4you_reports4you 
                        inner join  its4you_reports4you_relcriteria_summaries on  its4you_reports4you_relcriteria_summaries.reportid = its4you_reports4you.reports4youid';
        $ssql.= " where its4you_reports4you.reports4youid = ? order by  its4you_reports4you_relcriteria_summaries.columnindex";

        $result = $adb->pquery($ssql, array($reportid));
        $noOfColumns = $adb->num_rows($result);
        if ($noOfColumns > 0) {
            $this->j = 0;
            while ($relcriteriarow = $adb->fetch_array($result)) {
                $columnIndex = $relcriteriarow["columnindex"];
                $criteria = array();
                $criteria['columnname'] = html_entity_decode($relcriteriarow["columnname"], ENT_QUOTES, $default_charset); //ITS4YOU VlMe Fix 
                $criteria['comparator'] = $relcriteriarow["comparator"];
                $advfilterval = $relcriteriarow["value"];
                $col = explode(":", $relcriteriarow["columnname"]);
                $temp_val = explode(",", $relcriteriarow["value"]);
                if ($col[4] == 'D' || ($col[4] == 'T' && $col[1] != 'time_start' && $col[1] != 'time_end') || ($col[4] == 'DT')) {
                    $val = Array();
                    for ($x = 0; $x < count($temp_val); $x++) {
                        list($temp_date, $temp_time) = explode(" ", $temp_val[$x]);
                        $temp_date = getValidDisplayDate(trim($temp_date));
                        if (trim($temp_time) != '')
                            $temp_date .= ' ' . $temp_time;
                        $val[$x] = $temp_date;
                    }
                    $advfilterval = implode(",", $val);
                }
                $criteria['value'] = decode_html($advfilterval);
                $criteria['column_condition'] = $relcriteriarow["column_condition"];

                /* $summaries_criteria['columns'][$this->j] = $criteria;
                  $summaries_criteria['condition'] = $groupCondition; */
                $summaries_criteria[$this->j] = $criteria;
                $this->j++;
            }
        }
        $this->summaries_criteria = $summaries_criteria;
        return true;
    }

    function getSelectedColumnListArray($reportid, $select_columname = "") {
        $adb = PearDatabase::getInstance();
        $default_charset = vglobal("default_charset");
        $sarray = array();

        $ssql = "SELECT its4you_reports4you_selectcolumn.* FROM  its4you_reports4you 
				INNER JOIN  its4you_reports4you_selectquery ON  its4you_reports4you_selectquery.queryid =  its4you_reports4you.reports4youid";
        $ssql .= " LEFT JOIN its4you_reports4you_selectcolumn ON its4you_reports4you_selectcolumn.queryid =  its4you_reports4you_selectquery.queryid";
        $ssql .= " WHERE  its4you_reports4you.reports4youid = ?";
        $ssql .= " ORDER BY its4you_reports4you_selectcolumn.columnindex";
//$adb->setDebug(true);
        $result = $adb->pquery($ssql, array($reportid));
//$adb->setDebug(false);
        $permitted_fields = Array();

        $selected_mod = $this->relatedmodulesarray;
        array_push($selected_mod, $this->primarymoduleid);

        while ($columnslistrow = $adb->fetch_array($result)) {
            $fieldname = "";
            $fieldcolname = $columnslistrow["columnname"];
            $fieldcolname = html_entity_decode(trim($fieldcolname), ENT_QUOTES, $default_charset);

            // ITS4YOU-UP SlOl 21. 2. 2014 14:57:45 tru changed to false, because do not make any sense in my code ...
            // $selmod_field_disabled = true;
            $selmod_field_disabled = false;
            foreach ($selected_mod as $smod) {
                $smod = vtlib_getModuleNameById($smod);
                if ((stripos($fieldcolname, ":" . $smod . "_") > -1) && vtlib_isModuleActive($smod)) {
                    $selmod_field_disabled = false;
                    break;
                }
            }

            if ($selmod_field_disabled == false) {
                list($tablename, $colname, $module_field, $fieldname, $single) = split(":", $fieldcolname);
                $user_privileges_path = 'user_privileges/user_privileges_' . $this->current_user->id . '.php';
                if (file_exists($user_privileges_path)) {
                    require($user_privileges_path);
                }
                list($module, $field) = split("_", $module_field);
                if (sizeof($permitted_fields) == 0 && $is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1) {
                    $permitted_fields = $this->getaccesfield($module);
                }
                $querycolumns = $this->getEscapedColumns($selectedfields);
                // $fieldlabel = trim(str_replace($module, " ", $module_field));
                $mod_arr = explode('_', $module_field);
                $mod = ($mod_arr[0] == '') ? $module : $mod_arr[0];
                $fieldlabel = trim($mod_arr[1]);
                //modified code to support i18n issue 
                $mod_lbl = vtranslate($mod, $module); //module
                // ITS4YOU-UP SlOl  4. 9. 2013 15:38:30
                if (in_array($fieldlabel, array("campaignrelstatus", "access_count"))) {
                    $fld_lbl = vtranslate($fieldlabel, $this->currentModule); //fieldlabel
                } elseif ($fieldlabel == "LBL PRODUCT SERVICE NAME") {
                    $fld_lbl = vtranslate($fieldlabel, $this->currentModule); //fieldlabel
                } else {
                    $fld_lbl = vtranslate($fieldlabel, $module); //fieldlabel
                }
                // ITS4YOU-END 4. 9. 2013 15:38:34
                $fieldlabel = $fld_lbl . " ($mod_lbl)";

                if ($single == "SUM" || $single == "AVG" || $single == "MIN" || $single == "MAX" || $single == "COUNT")
                    $fieldlabel .= " (" . $single . ")";
                if (CheckFieldPermission($fieldname, $mod) == 'true' || ($colname == "converted") || ($colname == "campaignrelstatus" && getFieldVisibilityPermission($module, $this->current_user->id, $colname) == 0) || ($colname == "access_count" && getFieldVisibilityPermission("Emails", $this->current_user->id, $colname) == 0) || $colname == "crmid" || in_array($fieldname, self::$intentory_fields)) {
                    if ($select_columname == $fieldcolname)
                        $selected = "selected";
                    else
                        $selected = "";
                    $sarray[] = array("fieldcolname" => $fieldcolname,
                        "selected" => $selected,
                        "fieldlabel" => $fieldlabel);
                }
                // ITS4YOU-END 4. 9. 2013 15:33:15
            }
        }

        return $sarray;
    }

    //public function getSelectedColumnsList($reportid, $select_columname = "") {
    public function getSelectedColumnsList($sarray, $select_columname = "") {
        // $sarray = $this->getSelectedColumnListArray($reportid, $select_columname);
        //$sarray = $this->selected_columns_list_arr;
        $shtml = "";
        if (!is_array($sarray)) {
            $sarray = $this->getSelectedColumnListArray($sarray);
        }

        foreach ($sarray as $s_values) {
            $selecte_col = "";
            if ($s_values["fieldcolname"] == $select_columname) {
                $selecte_col = "selected";
            }
            $shtml .= "<option permission='yes' value=\"" . $s_values["fieldcolname"] . "\" $selecte_col>" . $s_values["fieldlabel"] . "</option>";
        }
        return $shtml;
    }

    function getEscapedColumns($selectedfields) {
        $fieldname = $selectedfields[3];
        if ($fieldname == "parent_id") {
            if ($this->primarymodule == "HelpDesk" && $selectedfields[0] == "vtiger_crmentityRelHelpDesk") {
                $querycolumn = "case vtiger_crmentityRelHelpDesk.setype when 'Accounts' then vtiger_accountRelHelpDesk.accountname when 'Contacts' then vtiger_contactdetailsRelHelpDesk.lastname End" . " '" . $selectedfields[2] . "', vtiger_crmentityRelHelpDesk.setype 'Entity_type'";
                return $querycolumn;
            }
            if ($this->primarymodule == "Products" || $this->secondarymodule == "Products") {
                $querycolumn = "case vtiger_crmentityRelProducts.setype when 'Accounts' then vtiger_accountRelProducts.accountname when 'Leads' then vtiger_leaddetailsRelProducts.lastname when 'Potentials' then vtiger_potentialRelProducts.potentialname End" . " '" . $selectedfields[2] . "', vtiger_crmentityRelProducts.setype 'Entity_type'";
            }
            if ($this->primarymodule == "Calendar" || $this->secondarymodule == "Calendar") {
                $querycolumn = "case vtiger_crmentityRelCalendar.setype when 'Accounts' then vtiger_accountRelCalendar.accountname when 'Leads' then vtiger_leaddetailsRelCalendar.lastname when 'Potentials' then vtiger_potentialRelCalendar.potentialname when 'Quotes' then vtiger_quotesRelCalendar.subject when 'PurchaseOrder' then vtiger_purchaseorderRelCalendar.subject when 'Invoice' then vtiger_invoiceRelCalendar.subject End" . " '" . $selectedfields[2] . "', vtiger_crmentityRelCalendar.setype 'Entity_type'";
            }
        }
        return $querycolumn;
    }

    /* function getaccesfield($module) {
      global $current_user;
      $access_fields = Array();
      $adb = PearDatabase::getInstance();
      $profileList = getCurrentUserProfileList();
      $query = "select vtiger_field.fieldname from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where";
      $params = array();
      if ($module == "Calendar") {
      $query .= " vtiger_field.tabid in (9,16) and vtiger_field.displaytype in (1,2,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_field.presence in (0,2)";
      if (count($profileList) > 0) {
      $query .= " and vtiger_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
      array_push($params, $profileList);
      }
      $query .= " group by vtiger_field.fieldid order by block,sequence";
      } else {
      array_push($params, $this->primarymoduleid, $this->relatedmodulesarray);
      $query .= " vtiger_field.tabid in (select tabid from vtiger_tab where vtiger_tab.name in (?,?)) and vtiger_field.displaytype in (1,2,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_field.presence in (0,2)";
      if (count($profileList) > 0) {
      $query .= " and vtiger_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
      array_push($params, $profileList);
      }
      $query .= " group by vtiger_field.fieldid order by block,sequence";
      }
      $result = $adb->pquery($query, $params);


      while ($collistrow = $adb->fetch_array($result)) {
      $access_fields[] = $collistrow["fieldname"];
      }
      return $access_fields;
      } */

    function getaccesfield($module) {
        $access_fields = Array();

        $adb = PearDatabase::getInstance();

        $profileList = getCurrentUserProfileList();
        $query = "select vtiger_field.fieldname from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where";
        $params = array();
        if ($module == "Calendar") {
            if (count($profileList) > 0) {
                $query .= " vtiger_field.tabid in (9,16) and vtiger_field.displaytype in (1,2,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0
                                                and vtiger_field.presence IN (0,2) and vtiger_profile2field.profileid in (" . generateQuestionMarks($profileList) . ") group by vtiger_field.fieldid order by block,sequence";
                array_push($params, $profileList);
            } else {
                $query .= " vtiger_field.tabid in (9,16) and vtiger_field.displaytype in (1,2,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0
                                                and vtiger_field.presence IN (0,2) group by vtiger_field.fieldid order by block,sequence";
            }
        } else {
            array_push($params, $module);
            if (count($profileList) > 0) {
                $query .= " vtiger_field.tabid in (select tabid from vtiger_tab where vtiger_tab.name in (?)) and vtiger_field.displaytype in (1,2,3,5) and vtiger_profile2field.visible=0
                                                and vtiger_field.presence IN (0,2) and vtiger_def_org_field.visible=0 and vtiger_profile2field.profileid in (" . generateQuestionMarks($profileList) . ") group by vtiger_field.fieldid order by block,sequence";
                array_push($params, $profileList);
            } else {
                $query .= " vtiger_field.tabid in (select tabid from vtiger_tab where vtiger_tab.name in (?)) and vtiger_field.displaytype in (1,2,3,5) and vtiger_profile2field.visible=0
                                                and vtiger_field.presence IN (0,2) and vtiger_def_org_field.visible=0 group by vtiger_field.fieldid order by block,sequence";
            }
        }
        $result = $adb->pquery($query, $params);

        while ($collistrow = $adb->fetch_array($result)) {
            $access_fields[] = $collistrow["fieldname"];
        }
        //added to include ticketid for Reports module in select columnlist for all users
        if ($module == "HelpDesk")
            $access_fields[] = "ticketid";
        return $access_fields;
    }

    // ITS4YOU-CR SlOl  3. 9. 2013 9:01:13
    /** Function to set the standard filter vtiger_fields for the given its4you_reports4you
     *  This function gets the standard filter vtiger_fields for the given its4you_reports4you
     *  and set the values to the corresponding variables
     *  It accepts the repordid as argument 
     */
    function getSelectedStandardCriteria($reportid) {
        $adb = PearDatabase::getInstance();

        if (isset($_REQUEST["stdDateFilterField"]) && $_REQUEST["stdDateFilterField"] != "") {
            $this->stdselectedcolumn = vtlib_purify($_REQUEST["stdDateFilterField"]);
            $this->stdselectedfilter = vtlib_purify($_REQUEST["stdDateFilter"]);
            $this->startdate = vtlib_purify($_REQUEST["startdate"]);
            $this->enddate = vtlib_purify($_REQUEST["enddate"]);
        } else {

            $sSQL = "select  its4you_reports4you_datefilter.* from  its4you_reports4you_datefilter inner join  its4you_reports4you on  its4you_reports4you.reports4youid =  its4you_reports4you_datefilter.datefilterid where  its4you_reports4you.reports4youid=?";
            $result = $adb->pquery($sSQL, array($reportid));
            $selectedstdfilter = $adb->fetch_array($result);

            $this->stdselectedcolumn = $selectedstdfilter["datecolumnname"];
            $this->stdselectedfilter = $selectedstdfilter["datefilter"];
            if ($selectedstdfilter["datefilter"] == "custom") {
                if ($selectedstdfilter["startdate"] != "0000-00-00") {
                    $this->startdate = $selectedstdfilter["startdate"];
                }
                if ($selectedstdfilter["enddate"] != "0000-00-00") {
                    $this->enddate = $selectedstdfilter["enddate"];
                }
            }
        }
    }

    function getSelectedQFColumnsArray($reportid) {
        global $modules;
        $adb = PearDatabase::getInstance();
        $ssql = "select  its4you_reports4you_selectqfcolumn.* from its4you_reports4you";
        $ssql .= " left join  its4you_reports4you_selectqfcolumn on  its4you_reports4you_selectqfcolumn.queryid = its4you_reports4you.reports4youid";
        $ssql .= " where its4you_reports4you.reports4youid = ?";
        $ssql .= " order by  its4you_reports4you_selectqfcolumn.columnindex";
        $result = $adb->pquery($ssql, array($reportid));
        $permitted_fields = Array();

        $selected_mod = split(":", $this->relatedmodulesstring);
        array_push($selected_mod, $this->primarymoduleid);

        $sarray = array();
        while ($columnslistrow = $adb->fetch_array($result)) {
            $fieldname = "";
            $fieldcolname = $columnslistrow["columnname"];

            $selmod_field_disabled = true;
            foreach ($selected_mod as $smod) {
                $smodule = vtlib_getModuleNameById($smod);
                if ((stripos($fieldcolname, ":" . $smodule . "_") > -1) && vtlib_isModuleActive($smodule)) {
                    $selmod_field_disabled = false;
                    break;
                }
            }
            if ($selmod_field_disabled == false) {
                list($tablename, $colname, $module_field, $fieldname, $single) = split(":", $fieldcolname);
                $user_privileges_path = 'user_privileges/user_privileges_' . $this->current_user->id . '.php';
                if (file_exists($user_privileges_path)) {
                    require($user_privileges_path);
                }
                list($module, $field) = split("_", $module_field);

                if (sizeof($permitted_fields) == 0 && $is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1) {
                    $permitted_fields = $this->getaccesfield($module);
                }
                $querycolumns = $this->getEscapedColumns($selectedfields);
                $fieldlabel = trim(str_replace($module, " ", $module_field));
                $mod_arr = explode('_', $fieldlabel);
                $mod = ($mod_arr[0] == '') ? $module : $mod_arr[0];
                $fieldlabel = trim($fieldlabel);
                //modified code to support i18n issue 
                $mod_lbl = vtranslate($mod, $module); //module
                $fld_lbl = vtranslate($fieldlabel, $module); //fieldlabel
                $fieldlabel = $mod_lbl . " " . $fld_lbl;

                // ITS4YOU-UP SlOl 4. 9. 2013 15:32:14 disabled options changed / we will remove options which are users not permited to view
                /* if(CheckFieldPermission($fieldname,$mod) != 'true' && $colname!="crmid" && !in_array($fieldname,array('prodname','quantity','listprice','discount','comment'))
                  {
                  $shtml .= "<option permission='no' value=\"".$fieldcolname."\" disabled = 'true'>".$fieldlabel."</option>";
                  }
                  else
                  {
                  $shtml .= "<option permission='yes' value=\"".$fieldcolname."\" ".$selected.">".$fieldlabel."</option>";
                  } */
                if (CheckFieldPermission($fieldname, $mod) == 'true' || $colname == "crmid" || in_array($fieldname, self::$intentory_fields)) {
                    $selected = "";
                    $sarray[] = array("fieldcolname" => $fieldcolname,
                        "selected" => $selected,
                        "fieldlabel" => $fieldlabel);
                }
                // ITS4YOU-END 4. 9. 2013 15:33:15
            }
            //end
        }
        return $sarray;
    }

    function getSelectedQFColumnsList($reportid) {
        $shtml = "<option permission='yes' value=\"none\" >" . vtranslate("LBL_NONE") . "</option>";
        $sarray = $this->getSelectedQFColumnsArray($reportid);
        foreach ($sarray as $key => $sarray_value) {
            $shtml .= "<option permission='yes' value=\"" . $sarray_value["fieldcolname"] . "\" >" . $sarray_value["fieldlabel"] . "</option>";
        }
        /* 		
          global $modules;
          global $log,$current_user;
          $adb = PearDatabase::getInstance();
          $ssql = "select  its4you_reports4you_selectqfcolumn.* from its4you_reports4you";
          $ssql .= " left join  its4you_reports4you_selectqfcolumn on  its4you_reports4you_selectqfcolumn.queryid = its4you_reports4you.reports4youid";
          $ssql .= " where its4you_reports4you.reports4youid = ?";
          $ssql .= " order by  its4you_reports4you_selectqfcolumn.columnindex";
          $result = $adb->pquery($ssql, array($reportid));
          $permitted_fields = Array();

          $selected_mod = split(":",$this->relatedmodulesstring);
          array_push($selected_mod,$this->primarymoduleid);

          while($columnslistrow = $adb->fetch_array($result))
          {
          $fieldname ="";
          $fieldcolname = $columnslistrow["columnname"];

          $selmod_field_disabled = true;
          foreach($selected_mod as $smod){
          $smodule = vtlib_getModuleNameById($smod);
          if((stripos($fieldcolname,":".$smodule."_")>-1) && vtlib_isModuleActive($smodule)){
          $selmod_field_disabled = false;
          break;
          }
          }
          if($selmod_field_disabled==false){
          list($tablename,$colname,$module_field,$fieldname,$single) = split(":",$fieldcolname);
          require('user_privileges/user_privileges_'.$current_user->id.'.php');
          list($module,$field) = split("_",$module_field);

          if(sizeof($permitted_fields) == 0 && $is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
          {
          $permitted_fields = $this->getaccesfield($module);
          }
          $querycolumns = $this->getEscapedColumns($selectedfields);
          $fieldlabel = trim(str_replace($module," ",$module_field));
          $mod_arr=explode('_',$fieldlabel);
          $mod = ($mod_arr[0] == '')?$module:$mod_arr[0];
          $fieldlabel = trim(str_replace("_"," ",$fieldlabel));
          //modified code to support i18n issue
          $mod_lbl = vtranslate($mod,$module); //module
          $fld_lbl = vtranslate($fieldlabel,$module); //fieldlabel
          $fieldlabel = $mod_lbl." ".$fld_lbl;

          global $intentory_fields;
          if(CheckFieldPermission($fieldname,$mod) == 'true' || $colname=="crmid" || in_array($fieldname,$intentory_fields))
          {
          $shtml .= "<option permission='yes' value=\"".$fieldcolname."\" ".$selected.">".$fieldlabel."</option>";
          }
          // ITS4YOU-END 4. 9. 2013 15:33:15
          }
          //end
          } */
        return $shtml;
    }

    public function getSubOrdinateUsersArray($add_current_userid = false) {
        $adb = PearDatabase::getInstance();
        if (!isset($this->current_user)) {
            global $current_user;
            $this->current_user = $current_user;
        }
        $su_query = $adb->pquery("select userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '" . $this->current_user_parent_role_seq . "::%'", array());
        $subordinate_users = Array();
        if ($add_current_userid) {
            $subordinate_users[] = $this->current_user->id;
        }
        for ($i = 0; $i < $adb->num_rows($su_query); $i++) {
            $subordinate_users[] = $adb->query_result($su_query, $i, 'userid');
        }
        return $subordinate_users;
    }

    public function deleteSingleReports4You() {
        $die = true;
        if ($this->record != "") {
            $adb = PearDatabase::getInstance();
            $subordinate_users = array();
            $is_admin = is_admin($this->current_user);
            if (!$is_admin) {
                $subordinate_users = $this->getSubOrdinateUsersArray(true);
            }
            if (($is_admin) || (in_array($this->reportinformations["template_owner"], $subordinate_users))) {
                $adb->pquery("UPDATE its4you_reports4you SET deleted=? WHERE reports4youid = ?", array(1, $this->record));
                $die = false;
            } else {
                $die = true;
            }
        }
        if ($die === true) {
            $this->DieDuePermission();
        }
        return $die;
    }

    public function deleteReports4You($reportid) {
        echo "MASS Delete canceled !!!";
        exit;
        /*
          $adb = $this->db;

          $checkSql = "SELECT primarymodule FROM its4you_reports4you_modules WHERE reportmodulesid=?";
          $checkRes = $adb->pquery($checkSql, array($reportid));
          $checkRow = $adb->fetchByAssoc($checkRes);
          $primary_module = vtlib_getModuleNameById($checkRow["primarymodule"]);
          //if we are trying to delete template that is not allowed for current user then die because user should not be able to see the template
          $this->CheckReportPermissions($primary_module, $reportid);

          $d_reportsql = "DELETE FROM its4you_reports4you  WHERE reports4youid = ?";
          $d_reportsqlresult = $adb->pquery($d_reportsql, array($reportid));

          $d_reportsql = "DELETE FROM its4you_reports4you_settings  WHERE reportid = ?";
          $d_reportsqlresult = $adb->pquery($d_reportsql, array($reportid));

          $d_querysql = "DELETE FROM its4you_reports4you_selectquery WHERE queryid = ?";
          $d_queryresult = $adb->pquery($d_querysql, array($reportid));

          $d_selectedcolumns = "DELETE FROM its4you_reports4you_selectcolumn WHERE queryid = ?";
          $d_columnsqlresult = $adb->pquery($d_selectedcolumns, array($reportid));

          $d_selectedqfcolumns = "DELETE FROM its4you_reports4you_selectqfcolumn WHERE queryid = ?";
          $d_columnsqlqfresult = $adb->pquery($d_selectedqfcolumns, array($reportid));

          $d_shared = "DELETE FROM its4you_reports4you_sharing WHERE reports4youid = ?";
          $d_sharedresult = $adb->pquery($d_shared, array($reportid));

          $d_modules = "DELETE FROM its4you_reports4you_modules WHERE reportmodulesid = ?";
          $d_modulesresult = $adb->pquery($d_modules, array($reportid));

          $d_limit = "DELETE FROM its4you_reports4you_limit WHERE reportid = ?";
          $d_limitresult = $adb->pquery($d_limit, array($reportid));

          $d_sortcol_all = "DELETE FROM its4you_reports4you_sortcol WHERE reportid = ?";
          $d_sortcol_all_result = $adb->pquery($d_sortcol_all, array($reportid));

          $d_datefilter = "DELETE FROM its4you_reports4you_datefilter WHERE datefilterid = ?";
          $d_datefilter_result = $adb->pquery($d_datefilter, array($reportid));

          $d_summary = "DELETE FROM its4you_reports4you_summary WHERE reportsummaryid = ?";
          $d_summary_result = $adb->pquery($d_summary, array($reportid));

          $d_adv_criteria = "DELETE FROM its4you_reports4you_relcriteria WHERE queryid = ?";
          $d_adv_criteria_result = $adb->pquery($d_adv_criteria, array($reportid));

          $d_adv_criteria_grouping = "DELETE FROM its4you_reports4you_relcriteria_grouping WHERE queryid = ?";
          $d_adv_criteria_grouping_result = $adb->pquery($d_adv_criteria_grouping, array($reportid));

          $deleteScheduledReportSql = "DELETE FROM its4you_reports4you_scheduled_reports WHERE reportid=?";
          $adb->pquery($deleteScheduledReportSql, array($reportid));
         */
        return true;
    }

    // END
    /** Function to get the Reports inside each modules
     *  This function accepts the folderid
     *  This Generates the Reports under each Reports module
     *  This Returns a HTML sring
     */
    function sgetRptsforFldr($rpt_fldr_id, $paramsList = false, $search_params = array()) {
        $srptdetails = "";
        $adb = PearDatabase::getInstance();
        global $mod_strings;
        $returndata = Array();

        if (!isset($this->current_user)) {
            global $current_user;
            $this->current_user = $current_user;
        }

        require_once('include/utils/UserInfoUtil.php');
        /*
          $sql = "SELECT reports4youid, reports4youname, primarymodule, reporttype, its4you_reports4you.description,  folderid, foldername, owner, vtiger_tab.tablabel as tablabel, IF(its4you_reports4you_userstatus.sequence IS NOT NULL,its4you_reports4you_userstatus.sequence,1) AS sequence, IF(its4you_reports4you_scheduled_reports.reportid IS NOT NULL,1,0) AS scheduling
          FROM its4you_reports4you
          INNER JOIN its4you_reports4you_modules ON its4you_reports4you_modules.reportmodulesid = its4you_reports4you.reports4youid
          INNER JOIN its4you_reports4you_folder USING(folderid)
          INNER JOIN vtiger_tab ON vtiger_tab.tabid = its4you_reports4you_modules.primarymodule
          INNER JOIN its4you_reports4you_settings ON its4you_reports4you_settings.reportid = its4you_reports4you.reports4youid
          LEFT JOIN its4you_reports4you_userstatus ON its4you_reports4you.reports4youid = its4you_reports4you_userstatus.reportid
          LEFT JOIN its4you_reports4you_scheduled_reports ON its4you_reports4you.reports4youid = its4you_reports4you_scheduled_reports.reportid
          WHERE its4you_reports4you.deleted=0 ";
         */
        $sql = "SELECT DISTINCT reports4youid, reports4youname, primarymodule, reporttype, its4you_reports4you.description,  folderid, foldername, owner, vtiger_tab.tablabel as tablabel, IF(its4you_reports4you_userstatus.sequence IS NOT NULL,its4you_reports4you_userstatus.sequence,1) AS sequence, IF(its4you_reports4you_scheduled_reports.reportid IS NOT NULL,1,0) AS scheduling 
				FROM its4you_reports4you 
				LEFT JOIN its4you_reports4you_modules ON its4you_reports4you_modules.reportmodulesid = its4you_reports4you.reports4youid 
				INNER JOIN its4you_reports4you_folder USING(folderid) 
				LEFT JOIN vtiger_tab ON vtiger_tab.tabid = its4you_reports4you_modules.primarymodule 
                INNER JOIN its4you_reports4you_settings ON its4you_reports4you_settings.reportid = its4you_reports4you.reports4youid 
                LEFT JOIN its4you_reports4you_userstatus ON its4you_reports4you.reports4youid = its4you_reports4you_userstatus.reportid 
                LEFT JOIN its4you_reports4you_scheduled_reports ON its4you_reports4you.reports4youid = its4you_reports4you_scheduled_reports.reportid 
				WHERE its4you_reports4you.deleted=0 ";

        $params = array();

        // If information is required only for specific report folder?
        if ($rpt_fldr_id !== false) {
            $sql .= " AND its4you_reports4you_folder.folderid=?";
            $params[] = $rpt_fldr_id;
        }
        
        // reportname -> reports4youname
        // reporttype -> reporttype
        // tablabel -> vtiger_tab.name 
        // foldername -> foldername
        // owner -> its4you_reports4you_settings.owner
        // description -> description
//ini_set("display_errors",1);error_reporting(63);
        if(!empty($search_params)){
            $searchArray = ITS4YouReports_List_Header::getSearchParamsArray($search_params);
            foreach($searchArray as $headerColumn => $searchValue){
                $hinfo = new ITS4YouReports_List_Header($headerColumn);
                $search_sql_array[] = $hinfo->getHeaderColumnSql($headerColumn,$searchValue);
            }
            if(!empty($search_sql_array)){
                $search_sql = implode(" AND ", $search_sql_array);
                $sql .= " AND $search_sql ";
            }
        }

        $user_privileges_path = 'user_privileges/user_privileges_' . $this->current_user->id . '.php';
        if (file_exists($user_privileges_path)) {
            require($user_privileges_path);
        }
        require_once('include/utils/GetUserGroups.php');
        $userGroups = new GetUserGroups();
        $userGroups->getAllUserGroups($this->current_user->id);
        $user_groups = $userGroups->user_groups;
        if (!empty($user_groups) && $is_admin == false) {
            $user_group_query = " (shareid IN (" . generateQuestionMarks($user_groups) . ") AND setype='groups') OR";
            array_push($params, $user_groups);
        }
        // BF Roles FIX S
        if (isset($this->current_user->column_fields['roleid']) && $is_admin == false) {
            $user_group_query .= " (reports4youid IN (SELECT reports4youid FROM its4you_reports4you_sharing WHERE shareid = ? ) AND setype='roles') OR";
            array_push($params, $this->current_user->column_fields['roleid']);
        }
        // BF Roles FIX E
        $non_admin_query = " its4you_reports4you.reports4youid IN (SELECT reports4youid from its4you_reports4you_sharing WHERE $user_group_query (shareid=? AND setype='users'))";

        if ($is_admin == false) {
            $sql .= " and ( (" . $non_admin_query . ") or its4you_reports4you_settings.sharingtype='Public' or its4you_reports4you_settings.owner = ? or its4you_reports4you_settings.owner in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '" . $current_user_parent_role_seq . "::%'))";
            array_push($params, $this->current_user->id);
            array_push($params, $this->current_user->id);
        }

        if ($paramsList) {
            $startIndex = $paramsList['startIndex'];
            $pageLimit = $paramsList['pageLimit'];
            $orderBy = $paramsList['orderBy'];
            $sortBy = $paramsList['sortBy'];
            if ($orderBy) {
                $sql .= " ORDER BY $orderBy $sortBy";
            } else {
                $sql .= " ORDER BY its4you_reports4you.reports4youid DESC ";
            }
            $sql .= " LIMIT $startIndex, $pageLimit";
        }
        $query = $adb->pquery("select userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '" . $current_user_parent_role_seq . "::%'", array());
        $subordinate_users = Array();
        for ($i = 0; $i < $adb->num_rows($query); $i++) {
            $subordinate_users[] = $adb->query_result($query, $i, 'userid');
        }
//ITS4YouReports::sshow("<br/><br/><br/><br/><br/>");
//$adb->setDebug(true);
        $result = $adb->pquery($sql, $params);
//$adb->setDebug(false);

        $report = $adb->fetch_array($result);
        if (count($report) > 0) {
            do {
                $report_details = Array();
                // $report_details ['customizable'] = $report["customizable"];
                $report_details ['reportid'] = $report["reports4youid"];
                $report_details ['primarymodule'] = $report["primarymodule"];
                $report_details ['tablabel'] = $report["tablabel"];
                $report_details ['owner'] = getUserFullName($report["owner"]);
                $report_details ['foldername'] = $report["foldername"];
                // $report_details ['state'] = $report["state"];
                $reporttype = $report["reporttype"];
                $report_details ['reporttype'] = vtranslate($reporttype, "ITS4YouReports");
                $report_details ['state'] = "SAVED";
                $report_details ['description'] = $report["description"];

                $reports4youname = $report["reports4youname"];
                if ($report["scheduling"] == "1") {
                    $reports4youname .= '&nbsp;<image src="modules/ITS4YouReports/img/Cron.png" style="vertical-align: middle;height:15px;" />';
                }
                $report_details ['reportname'] = $reports4youname;
                $report_details ['sharingtype'] = $report["sharingtype"];

                if ($reporttype == "custom_report") {
                    if ($is_admin == true) {
                        $report_details['editable'] = 'false';
                    } else {
                        $report_details['editable'] = 'false';
                    }
                } elseif ($is_admin == true || in_array($this->current_user->id, $subordinate_users) || $report["owner"] == $this->current_user->id) {
                    $report_details ['editable'] = 'true';
                } else {
                    $report_details['editable'] = 'false';
                }
                $primarymodule = vtlib_getModuleNameById($report["primarymodule"]);
                if ($reporttype == "custom_report" || isPermitted($primarymodule, 'index') == "yes") {
                    if ($rpt_fldr_id !== false) {
                        $returndata[$report["folderid"]][] = $report_details;
                    } else {
                        $returndata[][] = $report_details;
                    }
                }
            } while ($report = $adb->fetch_array($result));
        }
//echo "<pre><br/><br/><br/><br/><br/><br/>";print_r($returndata);echo "</pre>";

        if ($rpt_fldr_id !== false) {
            $returndata = $returndata[$rpt_fldr_id];
        }
        return $returndata;
    }

    // ITS4YOU-CR SlOl 24. 2. 2014 14:25:52
    function sgetNewColumntoTotal($Objects) {
        $options = Array();

        $options [] = $this->sgetColumnstoTotalHTML($primarymoduleid, 0);
        if (!empty($secondarymodule)) {
            for ($i = 0; $i < count($secondarymodule); $i++) {
                $options [] = $this->sgetColumnstoTotalHTML(vtlib_getModuleNameById($secondarymodule[$i]), ($i + 1));
            }
        }
        return $options;
    }

    // ITS4YOU-CR SlOl 27. 2. 2014 10:57:16
    function getSelectedColumnsToTotal($reportid) {
        $adb = PearDatabase::getInstance();
        $this->columnssummary = array();
        $sgetNewColumntoTotalSelected = array();
        $ssql = "select distinct its4you_reports4you_summary.* from its4you_reports4you_summary inner join its4you_reports4you on its4you_reports4you.reports4youid = its4you_reports4you_summary.reportsummaryid where its4you_reports4you.reports4youid=?";
//$adb->setDebug(true);
        $result = $adb->pquery($ssql, array($reportid));
//$adb->setDebug(false);
        if ($result) {
            do {
                if ($reportsummaryrow["columnname"] != "") {
                    $sgetNewColumntoTotalSelected[] = $reportsummaryrow["columnname"];
                    $this->columnssummary[] = $reportsummaryrow["columnname"];
                }
            } while ($reportsummaryrow = $adb->fetch_array($result));
        }
        return $sgetNewColumntoTotalSelected;
    }

    // ITS4YOU-CR SlOl 26. 2. 2014 8:33:25
    function sgetNewColumntoTotalSelected($reportid, $R_Objects, $sgetNewColumntoTotalSelected = array()) {
        $adb = PearDatabase::getInstance();
        $default_charset = vglobal("default_charset");
        $options_list = Array();
        /* if ($reportid != "") {
          if ($_REQUEST["file"] != "ChangeSteps")
          $sgetNewColumntoTotalSelected = ITS4YouReports::getSelectedColumnsToTotal($reportid);
          } */
        if (!empty($sgetNewColumntoTotalSelected)) {
            foreach ($sgetNewColumntoTotalSelected as $sgNKey => $sgNVal) {
                $new_sget_array[$sgNKey] = html_entity_decode(trim($sgNVal), ENT_QUOTES, $default_charset);
            }
            $sgetNewColumntoTotalSelected = $new_sget_array;
        }

        foreach ($this->columnssummary as $key => $sum_column) {
            $sum_column = html_entity_decode(trim($sum_column), ENT_QUOTES, $default_charset);
            $options = array();
            $sum_col_array = explode(":", $sum_column);
            $lbl_array = explode("_", $sum_col_array[3]);
            $module_lbl = $lbl_array[0];
            unset($lbl_array[0]);
            $column_lbl = implode(" ", $lbl_array);

            $option_label = vtranslate("SINGLE_" . $module_lbl, $module_lbl) . ' - ' . vtranslate($column_lbl, $module_lbl);

            $type_col_array = explode("_", $sum_col_array[5]);
            $typeofdata = $type_col_array[0];
            $calculation_type = $type_col_array[1];

            $last_key = count($sum_col_array) - 1;
            $fieldid = "";
            if ((is_numeric($sum_col_array[$last_key]) && is_numeric($sum_col_array[($last_key - 1)])) || in_array($sum_col_array[$last_key], ITS4YouReports::$customRelationTypes)) {
                $fieldid = ":" . $sum_col_array[$last_key];
            }
            $selected_col = $sum_col_array[1] . ":" . $sum_col_array[2] . ":" . $sum_col_array[3] . ":" . $sum_col_array[4] . ":" . $typeofdata . $fieldid;
            if (in_array($selected_col, $R_Objects)) {
                if (!isset($options_list[$option_label]) || !in_array($sum_column, $options_list[$option_label])) {
                    $checked = "";
                    if (in_array($sum_column, $sgetNewColumntoTotalSelected)) {
                        $checked = "checked='checked'";
                    }
                    $options_list[$option_label][] = array("name" => $sum_column, "checked" => $checked);
                }
            }
        }

        return $options_list;
    }

    // ITS4YOU-CR SlOl 26. 2. 2014 16:10:21 
    function getQuickFiltersHTML($quick_columns_array, $quick_columns_arraySelected = array()) {
        $adb = PearDatabase::getInstance();
        $options_list = Array();
        // $this->record

        if ($this->record != "") {
            // if (!isset($this->columnssummary) && $_REQUEST["file"] != "ChangeSteps")
            $ssql = "SELECT * FROM its4you_reports4you_selectqfcolumn WHERE queryid = ?";
            $result = $adb->pquery($ssql, array($this->record));
            if ($result) {
                do {
                    if ($reportqfrow["columnname"] != "" && !in_array($reportqfrow["columnname"], $quick_columns_arraySelected)) {
                        $quick_columns_arraySelected[] = $reportqfrow["columnname"];
                        // $this->columnssummary[] = $reportsummaryrow["columnname"];
                    }
                } while ($reportqfrow = $adb->fetch_array($result));
            }
        }
        foreach ($quick_columns_array as $key => $sum_column) {
            $options = array();
            $sum_col_array = explode(":", $sum_column);

            $lbl_array = explode("_", $sum_col_array[2]);

            $module_lbl = $lbl_array[0];
            unset($lbl_array[0]);
            $column_lbl = implode(" ", $lbl_array);

            if ($module_lbl == "Calendar") {
                $option_label = vtranslate($module_lbl, $module_lbl) . ' - ' . vtranslate($column_lbl, $module_lbl);
            } else {
                $option_label = vtranslate("SINGLE_" . $module_lbl, $module_lbl) . ' - ' . vtranslate($column_lbl, $module_lbl);
            }

            $type_col_array = explode("_", $sum_col_array[4]);
            $typeofdata = $type_col_array[0];
            $calculation_type = $type_col_array[1];

            $last_key = count($sum_col_array) - 1;
            $fieldid = "";
            if (in_array($sum_col_array[$last_key], ITS4YouReports::$customRelationTypes)) {
                $fieldid = ":" . $sum_col_array[$last_key];
            }
            $selected_col = $sum_col_array[0] . ":" . $sum_col_array[1] . ":" . $sum_col_array[2] . ":" . $sum_col_array[3] . ":" . $typeofdata . $fieldid;

            if (!isset($options_list[$option_label]) || empty($options_list[$option_label])) {
                if (!in_array($sum_column, $options_list[$option_label])) {
                    $checked = "";
                    if (in_array($sum_column, $quick_columns_arraySelected)) {
                        $checked = "checked='checked'";
                    }
                    $options_list[$option_label][] = array("name" => $sum_column, "checked" => $checked);
                }
            }
        }
        return $options_list;
    }

    // ITS4YOU-CR SlOl 27. 2. 2014 15:06:27
    function getStdCriteriaByModule($module) {
        $adb = PearDatabase::getInstance();
        $tabid = getTabid($module);

        $blockids = $params = $profileList = array();

        $user_privileges_path = 'user_privileges/user_privileges_' . $this->current_user->id . '.php';
        if (file_exists($user_privileges_path)) {
            require($user_privileges_path);
        }

        $module_info = $this->getCustomViewModuleInfo($module);
        if (!isset($this->module_list) || empty($this->module_list)) {
            $this->initListOfModules();
        }
        foreach ($this->module_list[$module] as $key => $blockid) {
            $blockids[] = $blockid;
        }
        if (is_array($blockids)) {
            $blocks_params = implode(",", $blockids);
        } else {
            $blocks_params = $blockids;
        }

        if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
            $sql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid ";
            $sql.= " where vtiger_field.tabid=$tabid and vtiger_field.block in ($blocks_params)
                        and vtiger_field.uitype in (5,6,23,70)";
            $sql.= " and vtiger_field.presence in (0,2) order by vtiger_field.sequence";
        } else {
            $profileList = getCurrentUserProfileList();
            $sql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid inner join  vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid ";
            $sql.= " where vtiger_field.tabid=$tabid and vtiger_field.block in ($blocks_params) and vtiger_field.uitype in (5,6,23,70)";
            $sql.= " and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_field.presence in (0,2)";

            if (count($profileList) > 0) {
                $sql.= " and vtiger_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
                array_push($params, $profileList);
            }

            $sql.= " order by vtiger_field.sequence";
        }
        $result = $adb->pquery($sql, $profileList);
        while ($criteriatyperow = $adb->fetch_array($result)) {
            $fieldtablename = $criteriatyperow["tablename"];
            $fieldcolname = $criteriatyperow["columnname"];
            $fieldlabel = $criteriatyperow["fieldlabel"];
            $fieldname = $criteriatyperow["fieldname"];
            $fieldlabel1 = $fieldlabel;
            $typeofdata = explode("~", $criteriatyperow["typeofdata"]);
            $typeofdata = $typeofdata[0];
//             $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $fieldlabel1 . ":" . $fieldname . ":" . $typeofdata;
            $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $fieldlabel1 . ":" . $fieldname . ":" . $typeofdata;
            $stdcriteria_list[$optionvalue] = $fieldlabel;
        }
        return $stdcriteria_list;
    }

    // ITS4YOU-CR SlOl 27. 2. 2014 15:07:44
    function getCustomViewModuleInfo($module) {
        $adb = PearDatabase::getInstance();
        global $current_language;
        $this->module_list = array();
        if ($module == "Events") {
            $module = "Calendar";
        }

        if (vtlib_isModuleActive($module)) {
            $current_mod_strings = return_specified_module_language($current_language, $module);
            $block_info = Array();
            $modules_list = explode(",", $module);
            if ($module == "Calendar") {
                $module = "Calendar','Events";
                $modules_list = array('Calendar', 'Events');
            }

            // Tabid mapped to the list of block labels to be skipped for that tab.
            $skipBlocksList = array(
                getTabid('Contacts') => array('LBL_IMAGE_INFORMATION'),
                getTabid('HelpDesk') => array('LBL_COMMENTS'),
                getTabid('Products') => array('LBL_IMAGE_INFORMATION'),
                getTabid('Faq') => array('LBL_COMMENT_INFORMATION'),
                getTabid('Quotes') => array('LBL_RELATED_PRODUCTS'),
                getTabid('PurchaseOrder') => array('LBL_RELATED_PRODUCTS'),
                getTabid('SalesOrder') => array('LBL_RELATED_PRODUCTS'),
                getTabid('Invoice') => array('LBL_RELATED_PRODUCTS')
            );

            $Sql = "select distinct block,vtiger_field.tabid,name,blocklabel from vtiger_field inner join vtiger_blocks on vtiger_blocks.blockid=vtiger_field.block inner join vtiger_tab on vtiger_tab.tabid=vtiger_field.tabid where displaytype != 3 and vtiger_tab.name in (" . generateQuestionMarks($modules_list) . ") and vtiger_field.presence in (0,2) order by block";
            $result = $adb->pquery($Sql, array($modules_list));
            if ($module == "Calendar','Events")
                $module = "Calendar";

            $pre_block_label = '';
            while ($block_result = $adb->fetch_array($result)) {
                $block_label = $block_result['blocklabel'];
                $tabid = $block_result['tabid'];
                // Skip certain blocks of certain modules
                if (array_key_exists($tabid, $skipBlocksList) && in_array($block_label, $skipBlocksList[$tabid]))
                    continue;

                if (trim($block_label) == '') {
                    $block_info[$pre_block_label] = $block_info[$pre_block_label] . "," . $block_result['block'];
                } else {
                    $lan_block_label = $current_mod_strings[$block_label];
                    if (isset($block_info[$lan_block_label]) && $block_info[$lan_block_label] != '') {
                        $block_info[$lan_block_label] = $block_info[$lan_block_label] . "," . $block_result['block'];
                    } else {
                        $block_info[$lan_block_label] = $block_result['block'];
                    }
                }
                $pre_block_label = $lan_block_label;
            }
            $this->module_list[$module] = $block_info;
        }
        return $this->module_list;
    }

    // ITS4YOU-CR SlOl 3. 3. 2014 14:28:15
    function getRequestCriteria($sel_fields = array()) {
        $r_conditions = array();
        global $default_charset;
        //<<<<<<<advancedfilter>>>>>>>>
        $request = new Vtiger_Request($_REQUEST, $_REQUEST);
        
        $advft_criteria = $request->get("advft_criteria");
        $advft_criteria_groups = $request->get("advft_criteria_groups");
        /*
        $json = new Zend_Json();
        $advft_criteria = vtlib_purify($_REQUEST['advft_criteria']);
        $advft_criteria = $json->decode($advft_criteria);
        $advft_criteria_groups = vtlib_purify($_REQUEST['advft_criteria_groups']);
        $advft_criteria_groups = $json->decode($advft_criteria_groups);
        */
        if (!is_array($sel_fields)) {
            $sel_fields = Zend_Json::decode($sel_fields);
        }
        //<<<<<<<advancedfilter>>>>>>>>
        echo '<script> var r_sel_fields = new Array(); </script>';
        // $to_echo = "";
        $r_sel_fields_echo = array();
        $minus_gi = 0;
        foreach ($advft_criteria as $f_fol_i => $condition_array) {
            if (!empty($condition_array)) {
                if (array_key_exists(trim($condition_array["columnname"]), $sel_fields)) {
                    $r_sel_fields = $condition_array["value"];
                    /* $r_sql_farray = array($condition_array["columnname"],$r_sel_fields);
                      $r_sql_farray = $json->encode($r_sql_farray); */
                    $r_sel_fields_echo[$condition_array["columnname"] . '_' . $condition_array["comparator"]] = $r_sel_fields;
                    // $to_echo = '<script> r_sel_fields["'.$condition_array["columnname"].'_'.$condition_array["comparator"].'"] = "'.$r_sel_fields.'"; </script>';
                    if (is_array($condition_array["value"])) {
                        $condition_array["value"] = implode(",", $condition_array["value"]);
                    }
                    if (isset($condition_array["value"]) && is_array($condition_array["value"])) {
                        //	$condition_array["value"] = implode(",", $condition_array["value"]);
                    }
                }
                $group_i = ($condition_array["groupid"] - $minus_gi);

                $r_conditions[$group_i]["columns"][] = array("columnname" => $condition_array["columnname"],
                    "comparator" => $condition_array["columnname"],
                    "comparator" => $condition_array["comparator"],
                    "value" => html_entity_decode($condition_array["value"], ENT_QUOTES, $default_charset),
                    "column_condition" => $condition_array["column_condition"],
                );
                $r_conditions[$group_i]["condition"] = $advft_criteria_groups[$condition_array["groupid"]]["groupcondition"];
            } else {
                $minus_gi++;
            }
        }
        $c_g_i = 1;
        if (!empty($r_conditions)) {
            foreach ($r_conditions as $c_g_key => $c_g_array) {
                $n_r_conditions[$c_g_i] = $c_g_array;
                $c_g_i++;
            }
            $r_conditions = $n_r_conditions;
        }

        $to_echo_str = "";
        if (!empty($r_sel_fields_echo)) {
            foreach ($r_sel_fields_echo as $ckey => $cvalues) {
                $to_echo_str[] = $ckey . "<;@#@;>" . $cvalues;
            }
            $to_echo_str = implode("<;@B#B@;>", $to_echo_str);
        }
        echo '<script> var r_sel_fields = "' . $to_echo_str . '"; </script>';

        return $r_conditions;
    }

    // ITS4YOU-END 3. 3. 2014 14:28:17
    public function getLabelsHTML($columns_array, $type = "SC", $lbl_url_selected = array(), $decode = false) {
        $default_charset = vglobal("default_charset");
        $return = array();
        $calculation_type = "";
        global $currentModule;
        if (!empty($columns_array)) {
            foreach ($columns_array as $key => $TP_column_str) {
                $key = ($key);
                $TP_column_str = ($TP_column_str);
                $input_id = $key . "_" . $type . "_lLbLl_" . $TP_column_str;
                if ($type == "SM") {
                    $TP_column_str_arr = explode(":", $TP_column_str);
                    $calculation_type = $TP_column_str_arr[(count($TP_column_str_arr) - 1)];
                }
                $translated_lbl_key = $this->getColumnStr_Label($TP_column_str, $type, $lbl_url_selected);
                $translated_key = $this->getColumnStr_Label($TP_column_str, "key", $lbl_url_selected);
                $translated_key = str_replace("@AMPKO@", "&", $translated_key);
                if ($translated_lbl_key != "") {
                    $decoded_translated_lbl_key = html_entity_decode($decoded_translated_lbl_key, ENT_QUOTES, $default_charset);
                    $calculation_type = vtranslate($calculation_type, $currentModule);
                    $translated_key = $calculation_type . " " . $translated_key;
                    if ($decode) {
                        global $default_charset;
                        $decoded_translated_lbl_key = htmlspecialchars($translated_lbl_key, ENT_QUOTES, $default_charset);
                    } else {
                        $decoded_translated_lbl_key = $translated_lbl_key;
                    }

                    $translate_html = "<input type='text' id='$input_id' size='50' value='" . $decoded_translated_lbl_key . "' onblur='checkEmptyLabel(\"$input_id\")'><input type='hidden' id='hidden_$input_id' value='" . $decoded_translated_lbl_key . "'>";
                    $return[$translated_key] = $translate_html;
                }
            }
        }
        return $return;
    }

    public function getColumnStr_Label($column_str, $type = "SC", $lbl_url_selected = array()) {
        $translated_value = "";
        global $default_charset;
        $adb = PearDatabase::getInstance();
        global $currentModule;

        if ($column_str != "" && $column_str != "none") {
            $column_str = urldecode($column_str);
            global $current_language;
            $col_arr = explode(":", $column_str);
            $calculation_type = "";
            $lbl_arr = explode("_", $col_arr[2], 2);
            $lbl_module = $lbl_arr[0];
            $lbl_value = $lbl_arr[1];
            $lbl_value_sp = $lbl_value;
            if ($type == "SM" ) {
                // COUNT ... SUM AVG MIN MAX
                if (is_numeric($col_arr[5])) {
                    $calculation_type = $col_arr[6] . " ";
                } else {
                    $calculation_type = $col_arr[5] . " ";
                }
            }
            
            $lbl_mod_strings = array();
            
            if (!isset($lbl_url_selected[$type])) {
                $lbl_url_selected[$type] = array();
            }
            if ((array_key_exists($column_str, $lbl_url_selected[$type]) || array_key_exists(html_entity_decode($column_str, ENT_COMPAT, $default_charset), $lbl_url_selected[$type])) && $type != "key") {
                $translated_value = ($lbl_url_selected[$type][$column_str] != '') ? $lbl_url_selected[$type][$column_str] : $lbl_url_selected[$type][html_entity_decode($column_str, ENT_COMPAT, $default_charset)];
            } else {
                $numlabels = 0;
                if ($type != "key") {
                    $labelsql = "SELECT columnlabel FROM its4you_reports4you_labels WHERE reportid = ? and type = ? AND columnname=?";
                    $column_str = html_entity_decode($column_str,ENT_QUOTES,$default_charset);
                    $labelres = $adb->pquery($labelsql, array($this->record, $type, str_replace("@AMPKO@", "&", $column_str)));
                    $numlabels = $adb->num_rows($labelres);
                }
                if ($numlabels > 0) {
                    while ($row = $adb->fetchByAssoc($labelres)) {
                        $translated_value = $row["columnlabel"];
                    }
                } else {
                    if ($lbl_value == "campaignrelstatus") {
                        $translated_lbl = vtranslate($lbl_value, $this->currentModule) . " " . vtranslate($lbl_module, $lbl_module);
                    }
                    if ($lbl_value == "access_count") {
                        $translated_lbl = vtranslate($lbl_value, "Emails");
                    }
                    if($lbl_value=="LBL_RECORDS"){
                        $translated_lbl = vtranslate($lbl_value, "ITS4YouReports");
                    }else{
                        $translated_lbl = vtranslate($lbl_value, $lbl_module);
                    }
                    $calculation_type = trim($calculation_type);
                    if($calculation_type!=""){
                        $calculation_type = vtranslate($calculation_type, $currentModule)." ";
                    }
                    $translated_value = $calculation_type.$translated_lbl;
                }
            }
        }

        return $translated_value;
    }

    // ITS4YOU-CR SlOl | 14.5.2014 10:21 
    public function getTimeLineColumnHTML($group_i = "@NMColStr", $tl_col_str = "") {
        global $mod_strings;
        $TimeLineColumnD = $TimeLineColumnW = $TimeLineColumnM = $TimeLineColumnY = $TimeLineColumnQ = "";

        $tl_col_val = "";
        if ($tl_col_str != "") {
            $frequency_arr = explode("@vlv@", $tl_col_str);
            $tl_col_val = $frequency_arr[0];
            $frequency_option = $frequency_arr[1];
            switch ($frequency_option) {
                case "DAYS":
                    $TimeLineColumnD = ' selected="selected" ';
                    break;
                case "WEEK":
                    $TimeLineColumnW = ' selected="selected" ';
                    break;
                case "MONTH":
                    $TimeLineColumnM = ' selected="selected" ';
                    break;
                case "YEAR":
                    $TimeLineColumnY = ' selected="selected" ';
                    break;
                case "QUARTER":
                    $TimeLineColumnQ = ' selected="selected" ';
                    break;
                case "HALFYEAR":
                    $TimeLineColumnH = ' selected="selected" ';
                    break;
                default:
                    $TimeLineColumnD = ' selected="selected" ';
                    break;
            }
        } else {
            $TimeLineColumnD = ' selected="selected" ';
        }

        if ($group_i != "@NMColStr") {
            $group_i = "_Group$group_i";
        }
        /*
          $timelinecolumn = "<table width='20%' style='float:right;' border='0' >
          <tr>
          <td width='16%' style='border:0px;' nowrap >" . vtranslate('TL_DAYS',"ITS4YouReports"). "</td>
          <td width='16%' style='border:0px;' nowrap >" . vtranslate('TL_WEEKS',"ITS4YouReports") . "</td>
          <td width='17%' style='border:0px;' nowrap >" . vtranslate('TL_MONTHS',"ITS4YouReports") . "</td>
          <td width='17%' style='border:0px;' nowrap >" . vtranslate('TL_QUARTERS',"ITS4YouReports") . "</td>
          <td width='17%' style='border:0px;' nowrap >" . vtranslate('TL_HALF_YEARS',"ITS4YouReports") . "</td>
          <td width='17%' style='border:0px;' nowrap >" . vtranslate('TL_YEARS',"ITS4YouReports") . "</td>
          </tr>
          <tr>
          <td align='center' style='border:0px;text-align:center;'><input type='radio' name='TimeLineColumn$group_i' id='TimeLineColumnD' $TimeLineColumnD value='$tl_col_val@vlv@DAYS' ></td>
          <td align='center' style='border:0px;text-align:center;'><input type='radio' name='TimeLineColumn$group_i' id='TimeLineColumnW' $TimeLineColumnW value='$tl_col_val@vlv@WEEK' ></td>
          <td align='center' style='border:0px;text-align:center;'><input type='radio' name='TimeLineColumn$group_i' id='TimeLineColumnM' $TimeLineColumnM value='$tl_col_val@vlv@MONTH' ></td>
          <td align='center' style='border:0px;text-align:center;'><input type='radio' name='TimeLineColumn$group_i' id='TimeLineColumnQ' $TimeLineColumnQ value='$tl_col_val@vlv@QUARTER' ></td>
          <td align='center' style='border:0px;text-align:center;'><input type='radio' name='TimeLineColumn$group_i' id='TimeLineColumnH' $TimeLineColumnH value='$tl_col_val@vlv@HALFYEAR' ></td>
          <td align='center' style='border:0px;text-align:center;'><input type='radio' name='TimeLineColumn$group_i' id='TimeLineColumnY' $TimeLineColumnY value='$tl_col_val@vlv@YEAR' ></td>
          </tr>
          </table>";
         */
        $timelinecolumn = '<select id="TimeLineColumn' . $group_i . '" name="TimeLineColumn' . $group_i . '" class="txtBox" style="float:left;width:7em;margin:auto;" >
                                        <option value="' . $tl_col_val . '@vlv@DAYS" ' . $TimeLineColumnD . ' >' . vtranslate('TL_DAYS', "ITS4YouReports") . '</option>
                                        <option value="' . $tl_col_val . '@vlv@WEEK" ' . $TimeLineColumnW . ' >' . vtranslate('TL_WEEKS', "ITS4YouReports") . '</option>
                                        <option value="' . $tl_col_val . '@vlv@MONTH" ' . $TimeLineColumnM . ' >' . vtranslate('TL_MONTHS', "ITS4YouReports") . '</option>
                                        <option value="' . $tl_col_val . '@vlv@QUARTER" ' . $TimeLineColumnQ . ' >' . vtranslate('TL_QUARTERS', "ITS4YouReports") . '</option>
                                        <option value="' . $tl_col_val . '@vlv@HALFYEAR" ' . $TimeLineColumnH . ' >' . vtranslate('TL_HALF_YEARS', "ITS4YouReports") . '</option>
                                        <option value="' . $tl_col_val . '@vlv@YEAR" ' . $TimeLineColumnY . ' >' . vtranslate('TL_YEARS', "ITS4YouReports") . '</option>
                                    </select>';

        return $timelinecolumn;
    }

    // ITS4YOU-END 14.5.2014 10:21 
    // ITS4YOU-CR SlOl | 23.6.2014 10:57 
    public function getReportHeaderInfo($noofrows = 0) {
        $final_return = $return = $return_name = $return_val = "";
        $return_arr = $col_order_arr = $summ_order_arr = array();
        $colspan = 2;
        global $default_charset;
        if (isset($this->reportinformations["reports4youid"]) && $this->reportinformations["reports4youid"] != "") {
            /*
              if (isset($this->reportinformations["reports4youid"]) && $this->reportinformations["reports4youid"] != "") {
              $return_val = $this->reportinformations["reports4youname"];
              } else {
              $return_val = vtranslate("LBL_REPORT_NAME", $this->currentModule);
              }
              $return_arr["reportname"] =  array("val"=>$return_val,"colspan"=>$colspan);
             */

            $return_val = "<b>" . vtranslate("LBL_Module", $this->currentModule) . ": </b>";
            $primarymodule_id = $this->reportinformations["primarymodule"];
            $primarymodule = vtlib_getModuleNameById($primarymodule_id);
            $return_val .= vtranslate($primarymodule, $primarymodule);
            $return_arr[] = array("val" => $return_val, "colspan" => "");

            $return_val = "<b>" . vtranslate("LBL_TOTAL", $this->currentModule) . ": </b><span id='_reportrun_total'>$noofrows</span> " . vtranslate("LBL_RECORDS", "ITS4YouReports");
            $return_arr[] = array("val" => $return_val, "colspan" => "");


            $return_val = "<b>" . vtranslate("LBL_TEMPLATE_OWNER", $this->currentModule) . ": </b>";
            $return_val .= getUserFullName($this->reportinformations["owner"]);
            $return_arr[] = array("val" => $return_val, "colspan" => "");
            $return_val = "<b>" . vtranslate("LBL_GroupBy", $this->currentModule) . ": </b>";
            for ($gi = 1; $gi < 4; $gi++) {
                if (isset($this->reportinformations["Group$gi"]) && $this->reportinformations["Group$gi"] != "" && $this->reportinformations["Group$gi"] != "none") {
                    $group_col_info = array();
                    // columns visibility control !!!!!! 
                    if ($this->getColumnVisibilityPerm($this->reportinformations["Group$gi"]) == 0) {
                        $gp_column_lbl = $this->getColumnStr_Label($this->reportinformations["Group$gi"], "SC");
                        $group_col_info[] = $gp_column_lbl;
                        $group_col_info[] = vtranslate($this->reportinformations["Sort$gi"]);
                        if (isset($this->reportinformations["timeline_columnstr$gi"]) && $this->reportinformations["timeline_columnstr$gi"] != "" && $this->reportinformations["timeline_columnstr$gi"] != "@vlv@") {
                            $tr_arr = explode("@vlv@", $this->reportinformations["timeline_columnstr$gi"]);
                            $tl_option = "TL_" . $tr_arr[1];
                            $group_col_info[] = vtranslate("LBL_BY", $this->currentModule);
                            $group_col_info[] = vtranslate($tl_option, $this->currentModule);
                        }
                        $group_cols[] = implode(" ", $group_col_info);
                    }
                }
            }
            if (empty($group_cols)) {
                $group_cols[] = vtranslate("LBL_NONE", $this->currentModule);
            }
            $return_val .= implode(", ", $group_cols);
            $return_arr[] = array("val" => $return_val, "colspan" => "");

            $return_val = "<b>" . vtranslate("LBL_Sharing", $this->currentModule) . ": </b>";
            if (isset($this->reportinformations["sharingtype"]) && $this->reportinformations["sharingtype"] != "") {
                $sharingtype = vtranslate($this->reportinformations["sharingtype"]);
            }
            $sharing_with = "";
            if ($this->reportinformations["sharingtype"] == "share") {
                $sharingMemberArray = $this->reportinformations["members_array"];
                $sharingMemberArray = array_unique($sharingMemberArray);
                if (count($sharingMemberArray) > 0) {
                    $outputMemberArr = array();
                    foreach ($sharingMemberArray as $setype => $shareIdArr) {
                        $shareIdArr = explode("::", $shareIdArr);
                        $shareIdArray[$shareIdArr[0]] = $shareIdArr[1];
                        foreach ($shareIdArray as $shareType => $shareId) {
                            switch ($shareType) {
                                case "groups":
                                    $groupName_array = getGroupName($shareId);
                                    $memberName = $groupName_array[0];
                                    $memberDisplay = "Group::";
                                    break;

                                case "roles":
                                    $memberName = getRoleName($shareId);
                                    $memberDisplay = "Roles::";
                                    break;

                                case "rs":
                                    $memberName = getRoleName($shareId);
                                    $memberDisplay = "RoleAndSubordinates::";
                                    break;

                                case "users":
                                    $memberName = getUserName($shareId);
                                    $memberDisplay = "User::";
                                    break;
                            }
                            // $outputMemberArr[] = $shareType."::".$shareId;
                            $outputMemberArr[] = $memberDisplay . $memberName;
                        }
                    }
                    $outputMemberArr = array_unique($outputMemberArr);
                }
                if (!empty($outputMemberArr)) {
                    $sharing_with = " (" . implode(", ", $outputMemberArr) . ")";
                }
            }

            $return_val .= $sharingtype . $sharing_with;
            $return_arr[] = array("val" => $return_val, "colspan" => "");

            $return_val = "<b>" . vtranslate("LBL_Schedule", $this->currentModule) . ": </b>";
            $adb = PearDatabase::getInstance();
            require_once 'modules/ITS4YouReports/ScheduledReports4You.php';
            $scheduledReport = new ITS4YouScheduledReport($adb, $this->current_user, $this->record);
            $scheduledReport->getReportScheduleInfo();
            $is_scheduled = $scheduledReport->isScheduled;
            $schedule_info = "";
            $scheduled_arr = array(1 => "HOURLY",
                2 => "DAILY",
                3 => "WEEKLY",
                4 => "BIWEEKLY",
                5 => "MONTHLY",
                6 => "ANNUALLY",);

            if ($is_scheduled) {
                $schtypeid = $scheduledReport->scheduledInterval["scheduletype"];
                // $schedule_info_arr[] = vtranslate("LBL_SCHEDULE_EMAIL", $this->currentModule);
                $schedulerFormatArr = explode(";", $scheduledReport->scheduledFormat);
                if (!empty($schedulerFormatArr)) {
                    foreach ($schedulerFormatArr as $format_str) {
                        $translated_schedulerFormat[] = vtranslate($format_str, $this->currentModule);
                    }
                }
                // $schedule_info_arr[] = $scheduledReport->scheduledFormat;
                $schedule_info_arr[] = implode(", ", $translated_schedulerFormat);
                $schedule_info_arr[] = vtranslate($scheduled_arr[$schtypeid], $this->currentModule);

                $schtime = $scheduledReport->scheduledInterval['time'];
                $schday = $scheduledReport->scheduledInterval['day'];
                $schweek = $scheduledReport->scheduledInterval['day'];
                $schmonth = $scheduledReport->scheduledInterval['month'];

                $WEEKDAY_STRINGS = vtranslate("WEEKDAY_STRINGS", $this->currentModule);
                $MONTH_STRINGS = vtranslate("MONTH_STRINGS", $this->currentModule);

                switch ($schtypeid) {
                    case 1:
                        $schedule_info_arr[] = vtranslate("LBL_SCH_AT", $this->currentModule) . " $schtime";
                        break;
                    case 2:
                        $schedule_info_arr[] = vtranslate("LBL_SCH_AT", $this->currentModule) . " $schtime";
                        break;
                    case 3:
                        $schedule_info_arr[] = vtranslate("LBL_SCH_ON", $this->currentModule) . " " . $WEEKDAY_STRINGS[$schday];
                        $schedule_info_arr[] = vtranslate("LBL_SCH_AT", $this->currentModule) . " $schtime";
                        break;
                    case 4:
                        $schedule_info_arr[] = vtranslate("LBL_SCH_ON", $this->currentModule) . " " . $WEEKDAY_STRINGS[$schday];
                        $schedule_info_arr[] = vtranslate("LBL_SCH_AT", $this->currentModule) . " $schtime";
                        break;
                    case 5:
                        $schedule_info_arr[] = vtranslate("LBL_SCH_ON", $this->currentModule) . " $schday. " . vtranslate("LBL_SCHEDULE_EMAIL_DAY", $this->currentModule);
                        $schedule_info_arr[] = vtranslate("LBL_SCH_AT", $this->currentModule) . " $schtime";
                        break;
                    case 6:
                        $schedule_info_arr[] = vtranslate("LBL_SCH_IN", $this->currentModule) . " " . $MONTH_STRINGS[$schmonth];
                        $schedule_info_arr[] = vtranslate("LBL_SCH_ON", $this->currentModule) . " $schday. " . vtranslate("LBL_SCHEDULE_EMAIL_DAY", $this->currentModule);
                        $schedule_info_arr[] = vtranslate("LBL_SCH_AT", $this->currentModule) . " $schtime";
                        break;
                }
                $schedule_info = implode(" ", $schedule_info_arr);
            } else {
                $schedule_info = vtranslate("LBL_NONE", $this->currentModule);
            }
            $return_val .= $schedule_info;
            $return_arr[] = array("val" => $return_val, "colspan" => "");

            $return_val = "<b>" . vtranslate("LBL_LIMIT", $this->currentModule) . ": </b>";
            if (!empty($this->reportinformations["summaries_columns"])) {
                $return_val .= vtranslate("LBL_Summaries", $this->currentModule) . " ";
                if (isset($this->reportinformations["summaries_limit"]) && $this->reportinformations["summaries_limit"] != "0") {
                    $summ_limit_str = $this->reportinformations["summaries_limit"] . " " . vtranslate("LBL_RECORDS", $this->currentModule);
                } else {
                    $summ_limit_str = vtranslate("LBL_ALL", $this->currentModule) . " " . strtolower(vtranslate("LBL_RECORDS", $this->currentModule));
                }
                $return_val .= $summ_limit_str;
                if ($this->reportinformations["selectedColumnsString"] != "") {
                    $return_val .= ", " . vtranslate("LBL_Details", $this->currentModule) . " ";
                }
            }
            if ($this->reportinformations["selectedColumnsString"] != "") {
                if (isset($this->reportinformations["columns_limit"]) && $this->reportinformations["columns_limit"] != "0") {
                    $limit_str = $this->reportinformations["columns_limit"] . " " . vtranslate("LBL_RECORDS", $this->currentModule);
                } else {
                    $limit_str = vtranslate("LBL_ALL", $this->currentModule) . " " . strtolower(vtranslate("LBL_RECORDS", $this->currentModule));
                }
                $return_val .= $limit_str;
            }
            $return_arr[] = array("val" => $return_val, "colspan" => "");

            $return_val = "<b>" . vtranslate("LBL_CHART_INFO", $this->currentModule) . ": </b>";

            $ch_column_populated = false;
            $new_ch_info = $n_ch_info = $ch_column_str = "";
            if (!empty($this->reportinformations["charts"])) {
                foreach ($this->reportinformations["charts"] as $chi => $ch_array) {
                    $ch_type = $ch_array["charttype"];

                    if ($ch_column_populated != true) {
                        if ($ch_array["x_group"] == "group1") {
                            $ch_column_lbl = GenerateObj::getHeaderLabel($this->record, "SC", "", $this->reportinformations["Group1"]);
                            if (is_array($ch_column_lbl) && $ch_column_lbl["lbl"] != "") {
                                $ch_column_lbl = $ch_column_lbl["lbl"];
                            }
                            $ch_column[] = $ch_column_lbl;
                        } elseif ($ch_array["x_group"] == "group2") {
                            $ch_column_lbl = GenerateObj::getHeaderLabel($this->record, "SC", "", $this->reportinformations["Group1"]);
                            if (is_array($ch_column_lbl) && $ch_column_lbl["lbl"] != "") {
                                $ch_column_lbl = $ch_column_lbl["lbl"];
                            }
                            $ch_column[] = $ch_column_lbl;
                            $ch_column_lbl = GenerateObj::getHeaderLabel($this->record, "SC", "", $this->reportinformations["Group1"]);
                            if (is_array($ch_column_lbl) && $ch_column_lbl["lbl"] != "") {
                                $ch_column_lbl = $ch_column_lbl["lbl"];
                            }
                            $ch_column[] = $ch_column_lbl;
                        }
                        $ch_column_str = implode(", ", $ch_column);
                        $ch_column_populated = true;
                    }
                    $ch_dataseries = GenerateObj::getHeaderLabel($this->record, "SM", "", $ch_array["dataseries"]);
                    if (is_array($ch_dataseries) && $ch_dataseries["lbl"] != "") {
                        $ch_dataseries = $ch_dataseries["lbl"];
                    }
                    $n_ch_info[] = vtranslate("LBL_CHART_$ch_type", $this->currentModule) . " " . vtranslate("LBL_CHART", $this->currentModule) . " ($ch_dataseries)";
                }
                $new_ch_info = vtranslate("LBL_CHART_DataSeries", $this->currentModule) . " " . $ch_column_str . " [" . implode(", ", $n_ch_info) . "]";
            } else {
                $new_ch_info = " <small><i>(" . vtranslate("LBL_NO_CHARTS", $this->currentModule) . ")</i></small>";
            }
            $return_val .= $new_ch_info;
            /*
              if(isset($this->reportinformations["charts"]) && $this->reportinformations["charts"]["charttype"]=="" && isset($this->reportinformations["charts"]["dataseries"]) && $this->reportinformations["charts"]["dataseries"]!=""){
              $ch_info = " <small><i>(".vtranslate("LBL_NO_CHARTS", $this->currentModule).")</i></small>";
              }elseif(isset($this->reportinformations["Group1"]) && $this->reportinformations["Group1"]!="none" && isset($this->reportinformations["charts"]) && $this->reportinformations["charts"]["charttype"]!="none" && isset($this->reportinformations["charts"]["dataseries"]) && $this->reportinformations["charts"]["dataseries"]!=""){
              // columns visibility control !!!!!!
              if($this->getColumnVisibilityPerm($this->reportinformations["Group1"])==0 && $this->getColumnVisibilityPerm($this->reportinformations["charts"]["dataseries"])==0){
              $ch_column = GenerateObj::getHeaderLabel($this->record,"SC","",$this->reportinformations["Group1"]);
              if(is_array($ch_column) && $ch_column["lbl"]!=""){
              $ch_column = $ch_column["lbl"];
              }
              $ch_dataseries = GenerateObj::getHeaderLabel($this->record,"SM","",$this->reportinformations["charts"]["dataseries"]);
              if(is_array($ch_dataseries) && $ch_dataseries["lbl"]!=""){
              $ch_dataseries = $ch_dataseries["lbl"];
              }
              $ch_type = $this->reportinformations["charts"]["charttype"];
              $ch_info = vtranslate("LBL_CHART_$ch_type", $this->currentModule)." ".vtranslate("LBL_CHART", $this->currentModule)." ($ch_column, $ch_dataseries)";
              }
              }else{
              $ch_info = " <small><i>(".vtranslate("LBL_CHARE", $this->currentModule)." ".vtranslate("LBL_IGNORED", $this->currentModule)."!)</i></small>";
              }
              $return_val .= $ch_info;
             */
            $return_arr[] = array("val" => $return_val, "colspan" => "");

            $return_val = "<b>" . vtranslate("LBL_Columns", $this->currentModule) . ": </b>";

            $col_order_str = "";
            if (isset($this->reportinformations["selectedColumnsString"]) && $this->reportinformations["selectedColumnsString"] != "") {
                $selected_column_string = $this->reportinformations["selectedColumnsString"];
                $selected_column_array = explode(";", html_entity_decode($selected_column_string, ENT_QUOTES, $default_charset));
                if (!empty($selected_column_array)) {
                    foreach ($selected_column_array as $column_str) {
                        if ($column_str != "" && $column_str != "none") {
                            // columns visibility control !!!!!! 
                            if ($this->getColumnVisibilityPerm($column_str) == 0) {
                                $column_lbl = $this->getColumnStr_Label($column_str, "SC");
                                $columns[] = $column_lbl;
                            }
                        }
                    }
                }
                if (isset($this->reportinformations["SortByColumn"]) && $this->reportinformations["SortByColumn"] != "none") {
                    $col_order_arr[] = vtranslate("LBL_SORT_FIELD", $this->currentModule);
                    $col_order_column = $this->getColumnStr_Label($this->reportinformations["SortByColumn"], "SC");
                    $col_order_arr[] = $col_order_column;
                    if ($this->reportinformations["SortOrderColumn"] == "DESC") {
                        $col_order_arr[] = vtranslate("Descending");
                    } else {
                        $col_order_arr[] = vtranslate("Ascending");
                    }
                    $col_order_str = implode(" ", $col_order_arr);
                }
            }
            if (empty($columns)) {
                $columns[] = vtranslate("LBL_NONE", $this->currentModule);
            }
            $return_val .= implode(", ", $columns);
            if ($this->reportinformations["timeline_type2"] == "cols" || $this->reportinformations["timeline_type3"] == "cols") {
                $return_val .= " <small><i>(" . vtranslate("LBL_NOT_A", $this->currentModule) . " " . vtranslate("LBL_IGNORED", $this->currentModule) . "!)</i></small>";
            }
            if ($col_order_str != "") {
                $return_val .= " ($col_order_str)";
            }
            $return_arr[] = array("val" => $return_val, "colspan" => "2");

            $return_val = "<b>" . vtranslate("LBL_SummariesColumns", $this->currentModule) . ": </b>";
            $summ_order_str = "";
            if (isset($this->reportinformations["summaries_columns"]) && !empty($this->reportinformations["summaries_columns"])) {
                foreach ($this->reportinformations["summaries_columns"] as $column_arr) {
                    $column_str = $column_arr["columnname"];
                    // columns visibility control !!!!!! 
                    if ($this->getColumnVisibilityPerm($column_str) == 0) {
                        $sm_column_lbl = $this->getColumnStr_Label($column_str, "SM");
                        $summaries_columns[] = $sm_column_lbl;
                    }
                }
                if (isset($this->reportinformations["summaries_orderby_columns"]) && !empty($this->reportinformations["summaries_orderby_columns"])) {
                    if ($this->reportinformations["summaries_orderby_columns"][0]["column"] != "none") {
                        $summ_order_arr[] = vtranslate("LBL_SORT_FIELD", $this->currentModule);
                        $summ_order_column = $this->getColumnStr_Label($this->reportinformations["summaries_orderby_columns"][0]["column"], "SM");
                        $summ_order_arr[] = $summ_order_column;
                        if ($this->reportinformations["summaries_orderby_columns"][0]["type"] == "DESC") {
                            $summ_order_arr[] = vtranslate("Descending");
                        } else {
                            $summ_order_arr[] = vtranslate("Ascending");
                        }
                        $summ_order_str = implode(" ", $summ_order_arr);
                    }
                }
            }
            if (empty($summaries_columns)) {
                $summaries_columns[] = vtranslate("LBL_NONE", $this->currentModule);
            }
            $return_val .= implode(", ", $summaries_columns);
            if ($summ_order_str != "") {
                $return_val .= " ($summ_order_str)";
            }
            $return_arr[] = array("val" => $return_val, "colspan" => "2");

            $return_val = "<b>" . vtranslate("LBL_Filters", $this->currentModule) . ": </b>";

            $std_filter_columns = $this->getStdFilterColumns();

            if (isset($_REQUEST["reload"])) {
                $tmp = $this->getAdvanceFilterOptionsJSON($this->primarymodule);
                $criteria_columns = $this->getRequestCriteria($this->adv_sel_fields);
                if (!empty($criteria_columns)) {
                    if (isset($_REQUEST["reload"])) {
                        foreach ($criteria_columns as $group_id => $group_arr) {
                            $criteria_columns = $group_arr["columns"];
                            if (!empty($criteria_columns)) {
                                foreach ($criteria_columns as $criteria_groups_arr) {
                                    if ($criteria_groups_arr["columnname"] != "") {
                                        // columns visibility control !!!!!! 
                                        if ($this->getColumnVisibilityPerm($criteria_groups_arr["columnname"]) == 0) {
                                            $column_condition = "";
                                            if ($criteria_groups_arr["column_condition"] != "") {
                                                $column_condition = $criteria_groups_arr["column_condition"];
                                            }
                                            if (in_array($criteria_groups_arr["columnname"], $std_filter_columns)) {
                                                $comparator = $criteria_groups_arr["comparator"];
                                                $comparator_val = $this->Date_Filter_Values[$comparator];
                                                $comparator_info = vtranslate($comparator_val, $this->currentModule);
                                                if ($comparator == "custom") {
                                                    $comparator_info_arr = explode("<;@STDV@;>", html_entity_decode(trim($criteria_groups_arr["value"]), ENT_QUOTES, $default_charset));
                                                    if ($comparator_info_arr[0] != "" && $comparator_info_arr[1] != "") {
                                                        $comparator_info .= vtranslate("BETWEEN", $this->currentModule) . " ";
                                                        $comparator_info .= $comparator_info_arr[0] . " ";
                                                        $comparator_info .= vtranslate("LBL_AND", $this->currentModule) . " ";
                                                        $comparator_info .= $comparator_info_arr[1];
                                                    } elseif ($comparator_info_arr[0] != "") {
                                                        $comparator_info .= vtranslate("LBL_IS", $this->currentModule) . " ";
                                                        $comparator_info .= $comparator_info_arr[0];
                                                    } elseif ($comparator_info_arr[1] != "") {
                                                        $comparator_info .= vtranslate("LBL_IS", $this->currentModule) . " ";
                                                        $comparator_info .= $comparator_info_arr[1];
                                                    }
                                                }
                                                $criteria_info_value = "";
                                            } else {
                                                $comparator = self::$adv_filter_options[$criteria_groups_arr["comparator"]];
                                                $comparator_info = vtranslate($comparator, $this->currentModule);
                                                $criteria_info_value = $criteria_groups_arr["value"];
                                            }
                                            $ft_column_lbl = $this->getColumnStr_Label($criteria_groups_arr["columnname"]);
                                            $condition_info = $ft_column_lbl . " " . $comparator_info . " " . $criteria_info_value . " " . $column_condition;
                                            $conditions_arr[$group_id][] = $condition_info;
                                        }
                                    }
                                }
                            }
                            $group_conditions[$group_id] = $group_arr["condition"];
                        }
                    }
                }
            } else {
                $criteria_columns = $this->reportinformations["advft_criteria"];
                $criteria_groups = $this->reportinformations["advft_criteria_groups"];
                if (!empty($criteria_groups) && !empty($criteria_columns)) {
                    foreach ($criteria_groups as $criteria_groups_arr) {
                        $group_id = $criteria_groups_arr["groupid"];
                        $group_condition = $criteria_groups_arr["group_condition"];
                        $group_conditions[$group_id] = $group_condition;
                    }
                    foreach ($criteria_columns as $criteria_groups_arr) {
                        if ($criteria_groups_arr["columnname"] != "") {
                            // filter columns and values visibility control !!!!!! start
                            if ($this->getColumnVisibilityPerm($criteria_groups_arr["columnname"]) == 0) {
                                if (array_key_exists($criteria_groups_arr["columnname"], $this->adv_sel_fields)) {
                                    $this->getColumnValuesVisibilityPerm($criteria_groups_arr["value"], $this->adv_sel_fields[$criteria_groups_arr["columnname"]]);
                                }
                            }
                            // filter columns and values visibility control !!!!!! end
                            $column_condition = "";
                            if ($criteria_groups_arr["column_condition"] != "") {
                                $column_condition = $criteria_groups_arr["column_condition"];
                            }
                            if (in_array($criteria_groups_arr["columnname"], $std_filter_columns)) {
                                $comparator = $criteria_groups_arr["comparator"];
                                $comparator_val = $this->Date_Filter_Values[$comparator];
                                $comparator_info = vtranslate($comparator_val, $this->currentModule);
                                if ($comparator == "custom") {
                                    $comparator_info_arr = explode("<;@STDV@;>", html_entity_decode(trim($criteria_groups_arr["value"]), ENT_QUOTES, $default_charset));
                                    if ($comparator_info_arr[0] != "" && $comparator_info_arr[1] != "") {
                                        $comparator_info .= vtranslate("BETWEEN", $this->currentModule) . " ";
                                        $comparator_info .= $comparator_info_arr[0] . " ";
                                        $comparator_info .= vtranslate("LBL_AND", $this->currentModule) . " ";
                                        $comparator_info .= $comparator_info_arr[1];
                                    } elseif ($comparator_info_arr[0] != "") {
                                        $comparator_info .= vtranslate("LBL_IS", $this->currentModule) . " ";
                                        $comparator_info .= $comparator_info_arr[0];
                                    } elseif ($comparator_info_arr[1] != "") {
                                        $comparator_info .= vtranslate("LBL_IS", $this->currentModule) . " ";
                                        $comparator_info .= $comparator_info_arr[1];
                                    }
                                }
                                $criteria_info_value = "";
                            } else {
                                $comparator = self::$adv_filter_options[$criteria_groups_arr["comparator"]];
                                $comparator_info = vtranslate($comparator, $this->currentModule);
                                $criteria_info_value = $criteria_groups_arr["value"];
                            }
                            $ft_column_lbl = $this->getColumnStr_Label($criteria_groups_arr["columnname"]);
                            $conditions_arr[$criteria_groups_arr["groupid"]][] = $ft_column_lbl . " " . $comparator_info . " " . $criteria_info_value . " " . $column_condition;
                        }
                    }
                }
            }
            $filters_str = "";
            if (!empty($group_conditions)) {
                foreach ($group_conditions as $g_condition_id => $g_condition) {
                    if (isset($conditions_arr[$g_condition_id]) && !empty($conditions_arr[$g_condition_id])) {
                        $filters_str .= " (" . trim(implode(" ", $conditions_arr[$g_condition_id])) . ") ";
                        if ($g_condition != "") {
                            $filters_str .= " " . vtranslate($g_condition, $this->currentModule) . " ";
                        }
                    }
                }
            }
            if ($filters_str == "") {
                $filters_str .= vtranslate("LBL_NONE", $this->currentModule);
            }
            $return_val .= $filters_str;
            $return_arr[] = array("val" => $return_val, "colspan" => "2");

            $td_i = 0;
            foreach ($return_arr as $ra_key => $ra_arr) {
                if (isset($ra_arr["colspan"]) && $ra_arr["colspan"] != "") {
                    $ra_colspan = $ra_arr["colspan"];
                } else {
                    $ra_colspan = 1;
                }
                $ra_val = $ra_arr["val"];
                if ($ra_key === "reportname") {
                    $return_name .= "<tr>";
                    $return_name .= "<td colspan='$ra_colspan' class='rpt4youGrpHeadInfoText' width='100%' style='border:0px;'>";
                    $return_name .= "$ra_val";
                    $return_name .= "</td>";
                    $return_name .= "</tr>";
                } else {
                    if ($td_i == 0) {
                        $return .= "<tr>";
                    }
                    $return .= "<td colspan='$ra_colspan' class='rpt4youGrpHeadInfo' style='text-align:left;padding-left:20px;width:50%;'>";
                    $return .= $ra_val;
                    $return .= "</td>";
                    $td_i += $ra_colspan;
                    if ($td_i == $colspan) {
                        $return .= "</tr>";
                        $td_i = 0;
                    }
                }
                /* $ra_val = $ra_arr["val"];
                  if(isset($ra_arr["colspan"]) && $ra_arr["colspan"]!=""){
                  $ra_colspan = $ra_arr["colspan"];
                  }else{
                  $ra_colspan = 1;
                  }
                  if($td_i==0){
                  $return .= "<tr>";
                  }
                  if(is_numeric($ra_key)){
                  $return .= "<td colspan='$ra_colspan' class='rpt4youGrpHeadInfo' style='text-align:left;padding-left:10px;width:50%;'>";
                  }else{
                  $return .= "<td colspan='$ra_colspan' class='rpt4youGrpHeadInfo' style='text-align:center;font-size:25px;width:100%;'>";
                  }
                  $return .= $ra_val;
                  $return .= "</td>";
                  $td_i += $ra_colspan;
                  if($td_i==$colspan){
                  $return .= "</tr>";
                  $td_i = 0;
                  } */
            }
            //$final_return = "<table class='rpt4youTableText' style='margin-top:1em;' width='100%'>";
            $final_return = "<table class='rpt4youTableText' width='100%'>";
            $final_return .= $return_name;
            $final_return .= "</table>";
            $final_return .= "<table width='100%' ><tr><td align='center'>";
            $final_return .= "<table class='rpt4youTable' width='98%'>";
            $final_return .= $return;
            $final_return .= "</table>";
            $final_return .= "</td></tr></table>";
            // ITS4YOU-UP SlOl 4. 12. 2014 13:56:43
            // ADD PAGE BREAK AFTER HEADER INFO disabled - remove // to enable it please
            // $final_return .= "<div style='page-break-after:always'></div>"; 
        }
        return $final_return;
    }

    // ITS4YOU-END 23.6.2014 10:57 
    // ITS4YOU-CR SlOl | 22.7.2014 16:34 
    public function getStdFilterColumns() {
        if (isset($this->std_filter_columns) && !empty($this->std_filter_columns)) {
            $std_filter_columns = $this->std_filter_columns;
        } else {
            $std_filter_columns = array();
            $std_filter_array[] = getPrimaryStdFilter($this->primarymodule, $this);
            $rel_modules = $this->getReportRelatedModules($this->primarymoduleid);
            if (!empty($rel_modules)) {
                foreach ($rel_modules as $r_m_key => $r_m_array) {
                    $s_std_arr = getSecondaryStdFilter($r_m_array, array(), $this);
                    if (!empty($s_std_arr)) {
                        $std_filter_array[] = $s_std_arr;
                    }
                }
            }
            if (!empty($std_filter_array)) {
                foreach ($std_filter_array as $just_key => $std_m_array) {
                    foreach ($std_m_array as $j_key => $std_m_v_array) {
                        $std_filter_columns[] = $std_m_v_array["value"];
                    }
                }
            }
            $this->std_filter_columns = $std_filter_columns;
        }
        return $std_filter_columns;
    }

    // ITS4YOU-END 22.7.2014 16:34
    // ITS4YOU-CR SlOl | 28.7.2014 15:31 
    private function getColumnVisibilityPerm($column_str = "") {
        $return = 0;
        $adb = PearDatabase::getInstance();
        $die_columns = array();

        $user_privileges_path = 'user_privileges/user_privileges_' . $this->current_user->id . '.php';
        if (file_exists($user_privileges_path)) {
            require($user_privileges_path);
        }

        if (file_exists($user_privileges_path) && ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)) {
            return $return;
        } else {
            if ($column_str != "") {
                $current_user_id = $this->current_user->id;
                $column_array = explode(":", $column_str);
                $calculation_type_tmp = $column_array[(count($column_array) - 1)];
                $calculation_type = strtolower($calculation_type_tmp);
                if (!in_array($calculation_type, $this->calculation_type_array)) {
                    $calculation_type = "";
                }
                if ($calculation_type == "count") {
                    $return = 0;
                } else {
                    $column_name = $column_array[1];
                    if (!in_array($column_name, self::$intentory_fields)) {
                        $module_array = explode("_", $column_array[2]);
                        $module_name = $module_array[0];
                        if ($module_name == "Calendar") {
                            $f_p_sql = "SELECT tabid FROM vtiger_field WHERE columnname=?";
                            $f_p_result = $adb->pquery($f_p_sql, array($column_name));
                            if ($adb->num_rows($f_p_result) > 0) {
                                $f_p_row = $adb->fetchByAssoc($f_p_result);
                                $f_p_tabid = $f_p_row["tabid"];
                                $module_name = vtlib_getModuleNameById($f_p_tabid);
                            }
                        }
                        $return = getColumnVisibilityPermission($current_user_id, $column_name, $module_name);
                        /* if($return==1){
                          $die_columns[] = $column_name;
                          } */
                    }
                }
            }
            if ($return == 1) {
                $this->DieDuePermission("columns", $die_columns);
            }
        }
        return $return;
    }

    // ITS4YOU-CR SlOl | 29.7.2014 9:41 \
    private function getColumnValuesVisibilityPerm($values_str = "", $available_values_arr = array()) {
        $permitted_array = array();
        $return = 0;

        $user_privileges_path = 'user_privileges/user_privileges_' . $this->current_user->id . '.php';
        if (file_exists($user_privileges_path)) {
            require($user_privileges_path);
        }

        if (file_exists($user_privileges_path) && ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)) {
            return $return;
        } else {
            // ITS4YOU-UP SlOl 27. 11. 2014 11:08:32 + is_array($available_values_arr) into condition
            if ($values_str != "" && !empty($available_values_arr) && is_array($available_values_arr)) {
                global $default_charset;
                $values_arr = explode(",", $values_str);
                foreach ($available_values_arr as $k => $val_arr) {
                    $permitted_array[] = $val_arr["value"];
                }
                foreach ($values_arr as $check_val) {
                    if (!in_array($check_val, $permitted_array)) {
                        $return = 1;
                    }
                }
            }
            if ($return == 1) {
                $this->DieDuePermission("values");
            }
        }
        return $return;
    }

    // ITS4YOU-END

    public function RegisterReports4YouScheduler() {
        $adb = PearDatabase::getInstance();
//        $adb->setDebug(true);
        include_once 'vtlib/Vtiger/Cron.php';
        Vtiger_Cron::register('Schedule Reports4You', 'modules/ITS4YouReports/ScheduleReports4You.service', 900, 'ITS4YouReports', '', '', 'Recommended frequency for ScheduleReports4You is 15 mins');
        $adb->pquery("UPDATE `vtiger_cron_task` SET `status` = '1' WHERE `name` = 'Schedule Reports4You';", array());
//        $adb->setDebug(false);
        return true;
    }

    // ITS4YOU-CR SlOl | 8.8.2014 20:38 
    // function used in scheduling to get User info of recipient User
    public function getReports4YouOwnerUser($user_id = "") {
        global $current_user;
        if ($user_id != "") {
            $user = new Users();
            $user->retrieveCurrentUserInfoFromFile($user_id);
        } else {
            $user = Users::getActiveAdminUser();
        }
        $current_user = $user;
        return $user;
    }

    function revertSchedulerUser() {
        global $current_user;
        $current_user = null;
        return $current_user;
    }

    // ITS4YOU-END 8.8.2014 20:38
    public function getSelectedValuesToSmarty($smarty_obj = "", $step_name = "") {
        if ($smarty_obj != "" && $step_name != "") {
            global $app_strings;
            global $mod_strings;
            global $default_charset;
            global $current_language;
            global $image_path;
            global $theme;
            $theme_path = "themes/" . $theme . "/";
            $image_path = $theme_path . "images/";
            $smarty_obj->assign("THEME", $theme_path);
            $smarty_obj->assign("IMAGE_PATH", $image_path);

            $adb = PEARDatabase::getInstance();
            $get_all_steps = "all";

            if (isset($_REQUEST["record"]) && $_REQUEST['record'] != '') {
                $recordid = vtlib_purify($_REQUEST["record"]);
            } else {
                $recordid = "";
            }
            $smarty_obj->assign("RECORDID", $recordid);

            $smarty_obj->assign("DISPLAY_FILTER_HEADER", false);

            if (in_array($step_name, array("ReportsStep1"))) {
                if (isset($_REQUEST["reportname"]) && $_REQUEST["reportname"] != "") {
                    $reportname = htmlspecialchars(vtlib_purify($_REQUEST["reportname"]));
                } else {
                    $reportname = $this->reportinformations["reports4youname"];
                }
                $smarty_obj->assign("REPORTNAME", $reportname);
                if (isset($_REQUEST["reportdesc"]) && $_REQUEST["reportdesc"] != "") {
                    $reportdesc = htmlspecialchars(vtlib_purify($_REQUEST["reportdesc"]));
                } else {
                    $reportdesc = $this->reportinformations["reportdesc"];
                }
                $smarty_obj->assign("REPORTDESC", $reportdesc);
                $smarty_obj->assign("REP_MODULE", $this->reportinformations["primarymodule"]);
                $smarty_obj->assign("PRIMARYMODULES", $this->getPrimaryModules());
                $smarty_obj->assign("REP_FOLDERS", $this->getReportFolders());
                if (isset($this->primarymodule) && $this->primarymodule != '') {
                    $rel_modules = $this->getReportRelatedModules($this->primarymoduleid);
                    foreach ($rel_modules as $key => $relmodule) {
                        $restricted_modules .= $relmodule['id'] . ":";
                    }
                    $smarty_obj->assign("REL_MODULES_STR", trim($restricted_modules, ":"));

                    $smarty_obj->assign("RELATEDMODULES", $rel_modules);
                }
                $smarty_obj->assign("FOLDERID", vtlib_purify($_REQUEST['folder']));
            }
            if (in_array($step_name, array("ReportsStep2", $get_all_steps))) {
                if (isset($this->primarymodule) && $this->primarymodule != '') {
                    $rel_modules = $this->getReportRelatedModules($this->primarymoduleid);
                    foreach ($rel_modules as $key => $relmodule) {
                        $restricted_modules .= $relmodule['id'] . ":";
                    }
                    $smarty_obj->assign("REL_MODULES_STR", trim($restricted_modules, ":"));
                    $smarty_obj->assign("RELATEDMODULES", $rel_modules);
                }
            }
            if (in_array($step_name, array("ReportGrouping", $get_all_steps))) {
                // TIMELINE COLUMNS DEFINITION CHANGED New Code 13.5.2014 11:58 
                // ITS4YOU-CR SlOl | 13.5.2014 11:53
                if (isset($_REQUEST["primarymodule"]) && $_REQUEST["primarymodule"] != "") {
                    $primary_moduleid = $_REQUEST["primarymodule"];
                    $primary_module = vtlib_getModuleNameById($_REQUEST["primarymodule"]);
                    if (vtlib_isModuleActive($primary_module)) {
                        $primary_df_arr = getPrimaryTLStdFilter($primary_module, $this);
                    }
                } else {
                    $primary_module = $this->primarymodule;
                    $primary_moduleid = $this->primarymoduleid;
                    $primary_df_arr = getPrimaryTLStdFilter($primary_module, $this);
                }

                $date_options = array();
                if (!empty($primary_df_arr)) {
                    foreach ($primary_df_arr as $val_arr) {
                        foreach ($val_arr as $val_dtls) {
                            $date_options[] = $val_dtls["value"];
                        }
                    }
                }
                $date_options_json = Zend_JSON::encode($date_options);
                $smarty_obj->assign("date_options_json", $date_options_json);

                $timelinecolumn = $this->getTimeLineColumnHTML();
                $smarty_obj->assign("timelinecolumn", $timelinecolumn);
                // ITS4YOU-END 13.5.2014 11:53

                if (isset($_REQUEST["record"]) && $_REQUEST['record'] != '') {
                    $reportid = vtlib_purify($_REQUEST["record"]);

                    $secondarymodule = '';
                    $secondarymodules = Array();

                    if (!empty($this->related_modules[$primary_module])) {
                        foreach ($this->related_modules[$primary_module] as $key => $value) {
                            if (isset($_REQUEST["secondarymodule_" . $value]))
                                $secondarymodules [] = vtlib_purify($_REQUEST["secondarymodule_" . $value]);
                        }
                    }
                    if ($primary_moduleid == getTabid('Invoice')) {
                        $secondarymodules[] = getTabid('Products');
                        $secondarymodules[] = getTabid('Services');
                    }
                    $secondarymodule = implode(":", $secondarymodules);
                    if ($secondarymodule != '') {
                        $this->secondarymodules .= $secondarymodule;
                    }
                    if (isset($_REQUEST["summaries_limit"])) {
                        $summaries_limit = vtlib_purify($_REQUEST["summaries_limit"]);
                    } else {
                        $summaries_limit = $this->reportinformations["summaries_limit"];
                    }
                } else {
                    $secondarymodule = '';
                    $secondarymodules = Array();

                    $this->getPriModuleColumnsList($primary_module);
                    foreach ($this->secondarymodules as $key => $secmodid) {
                        $this->getSecModuleColumnsList(vtlib_getModuleNameById($secmodid));
                    }
                    $summaries_limit = "20";
                }
                $smarty_obj->assign("SUMMARIES_MAX_LIMIT", $summaries_limit);
                for ($tc_i = 1; $tc_i < 4; $tc_i++) {
                    $timelinecol = $selected_timeline_column = "";
                    if (isset($_REQUEST["group$tc_i"]) && $_REQUEST["group$tc_i"] != "" && $step_name != "ReportGrouping") {
                        $group = vtlib_purify($_REQUEST["group$tc_i"]);
                        if (isset($_REQUEST["timeline_column$tc_i"]) && $_REQUEST["timeline_column$tc_i"] != "") {
                            $selected_timeline_column = vtlib_purify($_REQUEST["timeline_column$tc_i"]);
                        }
                    } else {
                        $group = $this->reportinformations["Group$tc_i"];
                        $selected_timeline_column = $this->reportinformations["timeline_columnstr$tc_i"];
                    }
                    if (isset($selected_timeline_column) && !in_array($selected_timeline_column, array("", "none", "@vlv@"))) {
                        $timelinecol = $this->getTimeLineColumnHTML($tc_i, $selected_timeline_column);
                        $smarty_obj->assign("timelinecolumn" . $tc_i . "_html", $timelinecol);
                    }

                    $RG_BLOCK = getPrimaryColumns_GroupingHTML($primary_module, $group, $this);
                    $smarty_obj->assign("RG_BLOCK$tc_i", $RG_BLOCK);

                    if ($tc_i > 1) {
                        if (isset($_REQUEST["timeline_type$tc_i"]) && $_REQUEST["timeline_type$tc_i"] != "") {
                            $timeline_type = vtlib_purify($_REQUEST["timeline_type$tc_i"]);
                        } else {
                            $timeline_type = $this->reportinformations["timeline_type$tc_i"];
                        }
                        $smarty_obj->assign("timeline_type$tc_i", $timeline_type);
                    }
                }

                for ($sci = 1; $sci < 4; $sci++) {
                    if (isset($_REQUEST["sort" . $sci]) && $_REQUEST["sort" . $sci] != "") {
                        $sortorder = vtlib_purify($_REQUEST["sort" . $sci]);
                    } else {
                        $sortorder = $this->reportinformations["Sort" . $sci];
                    }
                    $sa = $sd = "";

                    if ($sortorder != "Descending") {
                        $sa = "checked";
                    } else {
                        $sd = "checked";
                    }

                    $shtml = '<input type="radio" id="Sort' . $sci . 'a" name="Sort' . $sci . '" value="Ascending" ' . $sa . '>' . vtranslate('Ascending') . ' &nbsp; 
				              <input type="radio" id="Sort' . $sci . 'd" name="Sort' . $sci . '" value="Descending" ' . $sd . '>' . vtranslate('Descending');
                    $smarty_obj->assign("ASCDESC" . $sci, $shtml);
                }

                // ITS4YOU-CR SlOl 5. 3. 2014 14:50:45 SUMMARIES START
                $module_id = $primary_moduleid;
                $modulename_prefix = "";
                $module_array["module"] = $primary_module;
                $module_array["id"] = $module_id;
                $selectedmodule = $module_array["id"];

                $modulename = $module_array["module"];
                $modulename_lbl = vtranslate($modulename, $modulename);

                $availModules[$module_array["id"]] = $modulename_lbl;
                $modulename_id = $module_array["id"];
                if (isset($selectedmodule)) {
                    $secondarymodule_arr = $this->getReportRelatedModules($module_array["id"]);
                    $this->getSecModuleColumnsList($selectedmodule);
                    $RG_BLOCK4 = sgetSummariesHTMLOptions($module_array["id"], $module_id);
                    $available_modules[] = array("id" => $module_id, "name" => $modulename_lbl, "checked" => "checked");
                    foreach ($secondarymodule_arr as $key => $value) {
                        $exploded_mid = explode("x", $value["id"]);
                        if (strtolower($exploded_mid[1]) != "mif") {
                            $available_modules[] = array("id" => $value["id"], "name" => "- " . $value["name"], "checked" => "");
                        }
                    }
                    $smarty_obj->assign("RG_BLOCK4", $RG_BLOCK4);
                }
                $smarty_obj->assign("SummariesModules", $available_modules);
                $SumOptions = sgetSummariesOptions($selectedmodule);
                if (empty($SumOptions)) {
                    $SumOptions = vtranslate("NO_SUMMARIES_COLUMNS", $this->currentModule);
                }

                $SPSumOptions[$module_array["id"]][$module_array["id"]] = $SumOptions;
                $smarty_obj->assign("SUMOPTIONS", $SPSumOptions);

                if (isset($_REQUEST["selectedSummariesString"])) {
                    $selectedSummariesString = vtlib_purify($_REQUEST["selectedSummariesString"]);
                    $selectedSummariesArr = explode(";", $selectedSummariesString);
                    $summaries_orderby = vtlib_purify($_REQUEST["summaries_orderby"]);
                    $RG_BLOCK6 = sgetSelectedSummariesHTMLOptions($selectedSummariesArr, $summaries_orderby);
                } else {
                    if (!empty($this->reportinformations["summaries_columns"])) {
                        foreach ($this->reportinformations["summaries_columns"] as $key => $summaries_columns_arr) {
                            $selectedSummariesArr[] = $summaries_columns_arr["columnname"];
                        }
                    }
                    $selectedSummariesString = implode(";", $selectedSummariesString);
                    $summaries_orderby = "";
                    if (isset($this->reportinformations["summaries_orderby_columns"][0]) && $this->reportinformations["summaries_orderby_columns"][0] != "") {
                        $summaries_orderby = $this->reportinformations["summaries_orderby_columns"][0];
                    }
                    $RG_BLOCK6 = sgetSelectedSummariesHTMLOptions($selectedSummariesArr, $summaries_orderby);
                }

                // sum_group_columns for group filters start
                $sm_arr = sgetSelectedSummariesOptions($selectedSummariesArr);
                $sm_str = "";
                foreach ($sm_arr as $key => $opt_arr) {
                    if ($sm_str != "") {
                        $sm_str .= "(|@!@|)";
                    }
                    $sm_str .= $opt_arr["value"] . "(|@|)" . $opt_arr["text"];
                }
                $smarty_obj->assign("sum_group_columns", $sm_str);
                // sum_group_columns for group filters end
                $smarty_obj->assign("selectedSummariesString", $selectedSummariesString);
                $smarty_obj->assign("RG_BLOCK6", $RG_BLOCK6);

                $RG_BLOCKx2 = array();
                $all_fields_str = "";
                foreach ($SPSumOptions AS $module_key => $SumOptions) {
                    $RG_BLOCKx2 = "";
                    $r_modulename = vtlib_getModuleNameById($module_key);
                    $r_modulename_lbl = vtranslate($r_modulename, $r_modulename);

                    foreach ($SumOptions as $SumOptions_key => $SumOptions_value) {
                        if (is_array($SumOptions_value)) {
                            foreach ($SumOptions_value AS $optgroup => $optionsdata) {
                                if ($RG_BLOCKx2 != "")
                                    $RG_BLOCKx2 .= "(|@!@|)";
                                $RG_BLOCKx2 .= $optgroup;
                                $RG_BLOCKx2 .= "(|@|)";

                                $RG_BLOCKx2 .= Zend_JSON::encode($optionsdata);
                            }
                        }else {
                            $RG_BLOCKx2 .= $SumOptions_value;
                            $RG_BLOCKx2 .= "(|@|)";
                            $optionsdata[] = array("value" => "none", "text" => vtranslate("LBL_NONE", $this->currentModule));
                            $RG_BLOCKx2 .= Zend_JSON::encode($optionsdata);
                        }
                        $all_fields_str .= $module_key . "(!#_ID@ID_#!)" . $r_modulename_lbl . "(!#_ID@ID_#!)" . $RG_BLOCKx2;
                    }
                }
                $smarty_obj->assign("ALL_FIELDS_STRING", $all_fields_str);
                // ITS4YOU-END 5. 3. 2014 14:50:47  SUMMARIES END

                if (isset($_REQUEST["summaries_orderby"]) && $_REQUEST["summaries_orderby"] != "" && isset($_REQUEST["summaries_orderby_type"]) && $_REQUEST["summaries_orderby_type"] != "") {
                    $summaries_orderby = vtlib_purify($_REQUEST["summaries_orderby"]);
                    $summaries_orderby_type = vtlib_purify($_REQUEST["summaries_orderby_type"]);
                } elseif (isset($this->reportinformations["summaries_orderby_columns"]) && !empty($this->reportinformations["summaries_orderby_columns"])) {
                    $summaries_orderby = $this->reportinformations["summaries_orderby_columns"][0]["column"];
                    $summaries_orderby_type = $this->reportinformations["summaries_orderby_columns"][0]["type"];
                } else {
                    $summaries_orderby = "none";
                    $summaries_orderby_type = "ASC";
                }
                $smarty_obj->assign("summaries_orderby", $summaries_orderby);
                $smarty_obj->assign("summaries_orderby_type", $summaries_orderby_type);
            }
            if (in_array($step_name, array("ReportColumns", $get_all_steps))) {
                if (isset($_REQUEST["record"]) && $_REQUEST['record'] != '') {
                    $RC_BLOCK1 = getPrimaryColumnsHTML($this->primarymodule);

                    $secondarymodule = '';
                    $secondarymodules = Array();

                    if (!empty($this->related_modules[$this->primarymodule])) {
                        foreach ($this->related_modules[$this->primarymodule] as $key => $value) {
                            if (isset($_REQUEST["secondarymodule_" . $value]))
                                $secondarymodules [] = $_REQUEST["secondarymodule_" . $value];
                        }
                    }
                    $secondarymodule = implode(":", $secondarymodules);

                    $RC_BLOCK2 = $this->getSelectedColumnsList($this->selected_columns_list_arr);
                    $smarty_obj->assign("RC_BLOCK1", $RC_BLOCK1);
                    $smarty_obj->assign("RC_BLOCK2", $RC_BLOCK2);

                    $sreportsortsql = "SELECT columnname, sortorder FROM  its4you_reports4you_sortcol WHERE reportid =? AND sortcolid = 4";
                    $result_sort = $adb->pquery($sreportsortsql, array($recordid));
                    $num_rows = $adb->num_rows($result_sort);

                    if ($num_rows > 0) {
                        $columnname = $adb->query_result($result_sort, 0, "columnname");
                        $sortorder = $adb->query_result($result_sort, 0, "sortorder");
                        $RC_BLOCK3 = $this->getSelectedColumnsList($this->selected_columns_list_arr, $columnname);
                    } else {
                        $RC_BLOCK3 = $RC_BLOCK2;
                    }
                    $smarty_obj->assign("RC_BLOCK3", $RC_BLOCK3);

                    $this->secmodule = $secondarymodule;

                    $RC_BLOCK4 = "";
                    $RC_BLOCK4 = getSecondaryColumnsHTML($this->relatedmodulesstring, $this);

                    $smarty_obj->assign("RC_BLOCK4", $RC_BLOCK4);
                } else {
                    $primarymodule = vtlib_purify($_REQUEST["primarymodule"]);
                    $RC_BLOCK1 = getPrimaryColumnsHTML($primarymodule);
                    if (!empty($this->related_modules[$primarymodule])) {
                        foreach ($this->related_modules[$primarymodule] as $key => $value) {
                            $RC_BLOCK1 .= getSecondaryColumnsHTML($_REQUEST["secondarymodule_" . $value], $this);
                        }
                    }
                    $smarty_obj->assign("RC_BLOCK1", $RC_BLOCK1);

                    $this->reportinformations["columns_limit"] = "20";
                }
                $smarty_obj->assign("MAX_LIMIT", $this->reportinformations["columns_limit"]);

                if ($sortorder != "DESC") {
                    $shtml = '<input type="radio" name="SortOrderColumn" value="ASC" checked>' . vtranslate('Ascending') . ' &nbsp; 
								<input type="radio" name="SortOrderColumn" value="DESC">' . vtranslate('Descending');
                } else {
                    $shtml = '<input type="radio" name="SortOrderColumn" value="ASC">' . vtranslate('Ascending') . ' &nbsp; 
								<input type="radio" name="SortOrderColumn" value="DESC" checked>' . vtranslate('Descending');
                }
                $smarty_obj->assign("COLUMNASCDESC", $shtml);

                $timelinecolumns .= '<input type="radio" name="TimeLineColumn" value="DAYS" checked>' . $mod_strings['TL_DAYS'] . ' ';
                $timelinecolumns .= '<input type="radio" name="TimeLineColumn" value="WEEK" >' . $mod_strings['TL_WEEKS'] . ' ';
                $timelinecolumns .= '<input type="radio" name="TimeLineColumn" value="MONTH" >' . $mod_strings['TL_MONTHS'] . ' ';
                $timelinecolumns .= '<input type="radio" name="TimeLineColumn" value="YEAR" >' . $mod_strings['TL_YEARS'] . ' ';
                $timelinecolumns .= '<input type="radio" name="TimeLineColumn" value="QUARTER" >' . $mod_strings['TL_QUARTERS'] . ' ';
                $smarty_obj->assign("TIMELINE_FIELDS", $timelinecolumns);

                // ITS4YOU-CR SlOl  19. 2. 2014 16:30:20 
                $SPSumOptions = $availModules = array();
                $RC_BLOCK0 = "";

                $smarty_obj->assign("availModules", $availModules);
                $smarty_obj->assign("ALL_FIELDS_STRING", $RC_BLOCK0);
                // ITS4YOU-END 19. 2. 2014 16:30:23
                $smarty_obj->assign("currentModule", $this->currentModule);
            }
            if (in_array($step_name, array("ReportColumnsTotal", $get_all_steps))) {
                $Objects = array();

                $curl_array = array();
                if (isset($_REQUEST["curl"])) {
                    $curl = vtlib_purify($_REQUEST["curl"]);
                    $curl_array = explode('$_@_$', $curl);
                    $selectedColumnsString = str_replace("@AMPKO@", "&", $_REQUEST["selectedColumnsStr"]);
                    $R_Objects = explode("<_@!@_>", $selectedColumnsString);
                } else {
                    $curl_array = $this->getSelectedColumnsToTotal($this->record);
                    $curl = implode('$_@_$', $curl_array);
                    $selectedColumnsString = str_replace("@AMPKO@", "&", $this->reportinformations["selectedColumnsString"]);
                    $R_Objects = explode(";", $selectedColumnsString);
                }
                $smarty_obj->assign("CURL", $curl);

                $Objects = sgetNewColumnstoTotalHTMLScript($R_Objects);
                $this->columnssummary = $Objects;
                $CT_BLOCK1 = $this->sgetNewColumntoTotalSelected($recordid, $R_Objects, $curl_array);
                $smarty_obj->assign("CT_BLOCK1", $CT_BLOCK1);

                //added to avoid displaying "No data avaiable to total" when using related modules in report.
                $rows_count = 0;
                $rows_count = count($CT_BLOCK1);
                $smarty_obj->assign("ROWS_COUNT", $rows_count);
            }
            if (in_array($step_name, array("ReportLabels", $get_all_steps))) {
                // selected labels from url
                $lbl_url_string = html_entity_decode(vtlib_purify($_REQUEST["lblurl"]), ENT_QUOTES, $default_charset);
                if ($lbl_url_string != "") {
                    $lbl_url_arr = explode('$_@_$', $lbl_url_string);
                    foreach ($lbl_url_arr as $key => $lbl_value) {
                        if (strpos($lbl_value, 'hidden_') === false) {
                            if (strpos($lbl_value, '_SC_lLbLl_') !== false) {
                                $temp = explode('_SC_lLbLl_', $lbl_value);
                                $temp_lbls = explode('_lLGbGLl_', $temp[1]);
                                $lbl_key = trim($temp_lbls[0]);
                                $lbl_value = trim($temp_lbls[1]);
                                $lbl_url_selected["SC"][$lbl_key] = $lbl_value;
                            }
                            if (strpos($lbl_value, '_SM_lLbLl_') !== false) {
                                $temp = explode('_SM_lLbLl_', $lbl_value);
                                $temp_lbls = explode('_lLGbGLl_', $temp[1]);
                                $lbl_key = trim($temp_lbls[0]);
                                $lbl_value = trim($temp_lbls[1]);
                                $lbl_url_selected["SM"][$lbl_key] = $lbl_value;
                            }

                            if (strpos($lbl_value, '_CT_lLbLl_') !== false) {
                                $temp = explode('_CT_lLbLl_', $lbl_value);
                                $temp_lbls = explode('_lLGbGLl_', $temp[1]);
                                $lbl_key = trim($temp_lbls[0]);
                                $lbl_value = trim($temp_lbls[1]);
                                $lbl_url_selected["CT"][$lbl_key] = $lbl_value;
                            }
                        }
                    }
                }
                // COLUMNS labeltype SC
                if (isset($_REQUEST["selectedColumnsStr"]) && $_REQUEST["selectedColumnsStr"] != "") {
                    $selectedColumnsString = vtlib_purify($_REQUEST["selectedColumnsStr"]);
                    $selectedColumnsString = html_entity_decode($selectedColumnsString, ENT_QUOTES, $default_charset);
                    $selected_columns_array = explode("<_@!@_>", $selectedColumnsString);
                    $decode_labels = true;
                } else {
                    $selectedColumnsString = html_entity_decode($this->reportinformations["selectedColumnsString"], ENT_QUOTES, $default_charset);
                    $selected_columns_array = explode(";", $selectedColumnsString);
                    $decode_labels = false;
                }
                $labels_html["SC"] = $this->getLabelsHTML($selected_columns_array, "SC", $lbl_url_selected, $decode_labels);
                // SUMMARIES labeltype SM
                $selectedSummariesString = vtlib_purify($_REQUEST["selectedSummariesString"]);
                if ($selectedSummariesString != "") {
                    $selectedSummaries_array = explode(";", trim($selectedSummariesString, ";"));
                } else {
                    foreach ($this->reportinformations["summaries_columns"] as $key => $sum_arr) {
                        $selectedSummaries_array[] = $sum_arr["columnname"];
                    }
                }
                $labels_html["SM"] = $this->getLabelsHTML($selectedSummaries_array, "SM", $lbl_url_selected, $decode_labels);
                $smarty_obj->assign("labels_html", $labels_html);

                $smarty_obj->assign("LABELS", $curl);

                //added to avoid displaying "No data avaiable to total" when using related modules in report.
                $rows_count = count($labels_html);
                foreach ($labels_html as $key => $labels_type_arr) {
                    $rows_count += count($labels_type_arr);
                }
                $smarty_obj->assign("ROWS_COUNT", $rows_count);
            }
            if (in_array($step_name, array("ReportFilters", $get_all_steps))) {
                require_once('modules/ITS4YouReports/FilterUtils.php');

                if (isset($_REQUEST["primarymodule"]) && $_REQUEST["primarymodule"] != "") {
                    $primary_moduleid = $_REQUEST["primarymodule"];
                    $primary_module = vtlib_getModuleNameById($_REQUEST["primarymodule"]);
                } else {
                    $primary_module = $this->primarymodule;
                    $primary_moduleid = $this->primarymoduleid;
                }

                // NEW ADVANCE FILTERS START
                $this->getGroupFilterList($this->record);
                $this->getAdvancedFilterList($this->record);
                $this->getSummariesFilterList($this->record);

                $sel_fields = Zend_Json::encode($this->adv_sel_fields);
                $smarty_obj->assign("SEL_FIELDS", $sel_fields);
                if (isset($_REQUEST["reload"])) {
                    $criteria_groups = $this->getRequestCriteria($sel_fields);
                } else {
                    $criteria_groups = $this->advft_criteria;
                }
                $smarty_obj->assign("CRITERIA_GROUPS", $criteria_groups);
                $smarty_obj->assign("EMPTY_CRITERIA_GROUPS", empty($criteria_groups));
                $smarty_obj->assign("SUMMARIES_CRITERIA", $this->summaries_criteria);
                $FILTER_OPTION = getAdvCriteriaHTML();
                $smarty_obj->assign("FOPTION", $FILTER_OPTION);

                $COLUMNS_BLOCK_JSON = $this->getAdvanceFilterOptionsJSON($primary_module);
                $smarty_obj->assign("COLUMNS_BLOCK", $COLUMNS_BLOCK);
                if ($mode != "ajax") {
                    echo "<textarea style='display:none;' id='filter_columns'>" . $COLUMNS_BLOCK_JSON . "</textarea>";
                    $smarty_obj->assign("filter_columns", $COLUMNS_BLOCK_JSON);
                    $sel_fields = Zend_Json::encode($this->adv_sel_fields);
                    $smarty_obj->assign("SEL_FIELDS", $sel_fields);
                    global $default_charset;
                    $std_filter_columns = $this->getStdFilterColumns();
                    $std_filter_columns_js = implode("<%jsstdjs%>", $std_filter_columns);
                    $std_filter_columns_js = html_entity_decode($std_filter_columns_js, ENT_QUOTES, $default_charset);
                    $smarty_obj->assign("std_filter_columns", $std_filter_columns_js);
                    $std_filter_criteria = Zend_Json::encode($this->Date_Filter_Values);
                    $smarty_obj->assign("std_filter_criteria", $std_filter_criteria);
                }
                $rel_fields = $this->adv_rel_fields;
                $smarty_obj->assign("REL_FIELDS", Zend_Json::encode($rel_fields));
                // NEW ADVANCE FILTERS END

                $BLOCKJS = $this->getCriteriaJS();
                $smarty_obj->assign("BLOCKJS_STD", $BLOCKJS);
            }
            if (in_array($step_name, array("ReportSharing", $get_all_steps))) {
                $roleid = $this->current_user->column_fields['roleid'];
                $user_array = getRoleAndSubordinateUsers($roleid);
                $userIdStr = "";
                $userNameStr = "";
                $m = 0;
                foreach ($user_array as $userid => $username) {
                    if ($userid != $this->current_user->id) {
                        if ($m != 0) {
                            $userIdStr .= ",";
                            $userNameStr .= ",";
                        }
                        $userIdStr .="'" . $userid . "'";
                        $userNameStr .="'" . escape_single_quotes(decode_html($username)) . "'";
                        $m++;
                    }
                }

                require_once('include/utils/GetUserGroups.php');

                // ITS4YOU-UP SlOl 26. 4. 2013 9:47:59
                $template_owners = get_user_array(false);
                if (isset($this->reportinformations["owner"]) && $this->reportinformations["owner"] != "") {
                    $selected_owner = $this->reportinformations["owner"];
                } else {
                    $selected_owner = $this->current_user->id;
                }
                $smarty_obj->assign("TEMPLATE_OWNERS", $template_owners);
                $owner = (isset($_REQUEST['template_owner']) && $_REQUEST['template_owner'] != '') ? $_REQUEST['template_owner'] : $selected_owner;
                $smarty_obj->assign("TEMPLATE_OWNER", $owner);

                $sharing_types = Array("public" => vtranslate("PUBLIC_FILTER"),
                    "private" => vtranslate("PRIVATE_FILTER"),
                    "share" => vtranslate("SHARE_FILTER"));
                $smarty_obj->assign("SHARINGTYPES", $sharing_types);

                $sharingtype = "public";
                if (isset($_REQUEST['sharing']) && $_REQUEST['sharing'] != '') {
                    $sharingtype = $_REQUEST['sharing'];
                } elseif (isset($this->reportinformations["sharingtype"]) && $this->reportinformations["sharingtype"] != "") {
                    $sharingtype = $this->reportinformations["sharingtype"];
                }

                $smarty_obj->assign("SHARINGTYPE", $sharingtype);

                $cmod = return_specified_module_language($current_language, "Settings");
                $smarty_obj->assign("CMOD", $cmod);

                $sharingMemberArray = array();
                if (isset($_REQUEST['sharingSelectedColumns']) && $_REQUEST['sharingSelectedColumns'] != '') {
                    $sharingMemberArray = explode("|", trim($_REQUEST['sharingSelectedColumns'], "|"));
                } elseif (isset($this->reportinformations["members_array"]) && !empty($this->reportinformations["members_array"])) {
                    $sharingMemberArray = $this->reportinformations["members_array"];
                }

                $sharingMemberArray = array_unique($sharingMemberArray);
                if (count($sharingMemberArray) > 0) {
                    $outputMemberArr = array();
                    foreach ($sharingMemberArray as $setype => $shareIdArr) {
                        $shareIdArr = explode("::", $shareIdArr);
                        $shareIdArray = array();
                        $shareIdArray[$shareIdArr[0]] = $shareIdArr[1];
                        foreach ($shareIdArray as $shareType => $shareId) {
                            switch ($shareType) {
                                case "groups":
                                    $memberName = fetchGroupName($shareId);
                                    $memberDisplay = "Group::";
                                    break;
                                case "roles":
                                    $memberName = getRoleName($shareId);
                                    $memberDisplay = "Roles::";
                                    break;
                                case "rs":
                                    $memberName = getRoleName($shareId);
                                    $memberDisplay = "RoleAndSubordinates::";
                                    break;
                                case "users":
                                    $memberName = getUserFullName($shareId);
                                    $memberDisplay = "User::";
                                    break;
                            }
                            $outputMemberArr[] = $shareType . "::" . $shareId;
                            $outputMemberArr[] = $memberDisplay . $memberName;
                        }
                    }
                    $smarty_obj->assign("MEMBER", array_chunk($outputMemberArr, 2));
                }
                // ITS4YOU-END

                $userGroups = new GetUserGroups();
                $userGroups->getAllUserGroups($this->current_user->id);
                $user_groups = $userGroups->user_groups;
                $groupIdStr = "";
                $groupNameStr = "";
                $l = 0;
                foreach ($user_groups as $i => $grpid) {
                    $grp_details = getGroupDetails($grpid);
                    if ($l != 0) {
                        $groupIdStr .= ",";
                        $groupNameStr .= ",";
                    }
                    $groupIdStr .= "'" . $grp_details[0] . "'";
                    $groupNameStr .= "'" . escape_single_quotes(decode_html($grp_details[1])) . "'";
                    $l++;
                }

                $visiblecriteria = getVisibleCriteria();
                $smarty_obj->assign("VISIBLECRITERIA", $visiblecriteria);

                $smarty_obj->assign("GROUPNAMESTR", $groupNameStr);
                $smarty_obj->assign("USERNAMESTR", $userNameStr);
                $smarty_obj->assign("GROUPIDSTR", $groupIdStr);
                $smarty_obj->assign("USERIDSTR", $userIdStr);
            }
            if (in_array($step_name, array("ReportScheduler", $get_all_steps))) {
                // SEE ReportScheduler.php for this step for a reason of problem with incomplemete ReportScheduler object
            }
            if (in_array($step_name, array("ReportGraphs", $get_all_steps))) {
                if (isset($_REQUEST["chart_type"]) && $_REQUEST["chart_type"] != "" && $_REQUEST["chart_type"] != "none") {
                    $selected_chart_type = vtlib_purify($_REQUEST["chart_type"]);
                } else {
                    $selected_chart_type = $this->reportinformations["charts"]["charttype"];
                }
                $smarty_obj->assign("IMAGE_PATH", $chart_type);
                if (isset($_REQUEST["data_series"]) && $_REQUEST["data_series"] != "" && $_REQUEST["data_series"] != "none") {
                    $selected_data_series = vtlib_purify($_REQUEST["data_series"]);
                } else {
                    $selected_data_series = $this->reportinformations["charts"]["dataseries"];
                }
                if (isset($_REQUEST["charttitle"]) && $_REQUEST["charttitle"] != "") {
                    $selected_charttitle = htmlspecialchars(vtlib_purify($_REQUEST["charttitle"]));
                } else {
                    $selected_charttitle = $this->reportinformations["charts"]["charttitle"];
                }
                $chart_type["horizontal"] = array("value" => vtranslate("LBL_CHART_horizontal", $this->currentModule), "selected" => ($selected_chart_type == "horizontal" ? "selected" : ""));
                $chart_type["vertical"] = array("value" => vtranslate("LBL_CHART_vertical", $this->currentModule), "selected" => ($selected_chart_type == "vertical" ? "selected" : ""));
                $chart_type["linechart"] = array("value" => vtranslate("LBL_CHART_linechart", $this->currentModule), "selected" => ($selected_chart_type == "linechart" ? "selected" : ""));
                $chart_type["pie"] = array("value" => vtranslate("LBL_CHART_pie", $this->currentModule), "selected" => ($selected_chart_type == "pie" ? "selected" : ""));
                $chart_type["pie3d"] = array("value" => vtranslate("LBL_CHART_pie3D", $this->currentModule), "selected" => ($selected_chart_type == "pie3d" ? "selected" : ""));
                $chart_type["funnel"] = array("value" => vtranslate("LBL_CHART_funnel", $this->currentModule), "selected" => ($selected_chart_type == "funnel" ? "selected" : ""));
                $smarty_obj->assign("CHART_TYPE", $chart_type);

                // selected labels from url
                if (isset($_REQUEST["lblurl"])) {
                    global $default_charset;
                    $lbl_url_string = html_entity_decode(vtlib_purify($_REQUEST["lblurl"]), ENT_QUOTES, $default_charset);
                }

                $lbl_url_string = str_replace("@AMPKO@", "&", $lbl_url_string);
                if ($lbl_url_string != "") {
                    $lbl_url_arr = explode('$_@_$', $lbl_url_string);
                    foreach ($lbl_url_arr as $key => $lbl_value) {
                        if (strpos($lbl_value, 'hidden_') === false) {
                            if (strpos($lbl_value, '_SC_lLbLl_') !== false) {
                                $temp = explode('_SC_lLbLl_', $lbl_value);
                                $temp_lbls = explode('_lLGbGLl_', $temp[1]);
                                $lbl_key = $temp_lbls[0];
                                $lbl_value = $temp_lbls[1];
                                $lbl_url_selected["SC"][$lbl_key] = $lbl_value;
                            }
                            if (strpos($lbl_value, '_SM_lLbLl_') !== false) {
                                $temp = explode('_SM_lLbLl_', $lbl_value);
                                $temp_lbls = explode('_lLGbGLl_', $temp[1]);
                                $lbl_key = $temp_lbls[0];
                                $lbl_value = $temp_lbls[1];
                                $lbl_url_selected["SM"][$lbl_key] = $lbl_value;
                            }

                            if (strpos($lbl_value, '_CT_lLbLl_') !== false) {
                                $temp = explode('_CT_lLbLl_', $lbl_value);
                                $temp_lbls = explode('_lLGbGLl_', $temp[1]);
                                $lbl_key = $temp_lbls[0];
                                $lbl_value = $temp_lbls[1];
                                $lbl_url_selected["CT"][$lbl_key] = $lbl_value;
                            }
                        }
                    }
                }
                $selectedSummariesString = vtlib_purify($_REQUEST["selectedSummariesString"]);
                if ($selectedSummariesString != "") {
                    $selectedSummariesArray = explode(";", $selectedSummariesString);
                    if (!empty($selectedSummariesArray)) {
                        foreach ($selectedSummariesArray as $column_str) {
                            if ($column_str != "") {
                                if (isset($lbl_url_selected["SM"][$column_str]) && $lbl_url_selected["SM"][$column_str] != "") {
                                    $column_lbl = $lbl_url_selected["SM"][$column_str];
                                } else {
                                    $column_str_arr = explode(":", $column_str);
                                    $translate_arr = explode("_", $column_str_arr[2]);
                                    $translate_module = $translate_arr[0];
                                    unset($translate_arr[0]);
                                    $translate_str = implode("_", $translate_arr);
                                    $translate_mod_str = return_module_language($current_language, $translate_module);
                                    if (isset($translate_mod_str[$translate_str])) {
                                        $column_lbl = $translate_mod_str[$translate_str];
                                    } else {
                                        $column_lbl = $translate_str;
                                    }
                                }
                                $data_series[$column_str] = array("value" => $column_lbl, "selected" => ($column_str == $selected_data_series ? "selected" : ""));
                            }
                        }
                    }
                }
                if (empty($data_series) && $selected_data_series != "") {
                    $column_lbl = $this->getColumnStr_Label($selected_data_series, "SM");
                    $data_series[$selected_data_series] = array("value" => $column_lbl, "selected" => "selected");
                }
                $smarty_obj->assign("DATA_SERIES", $data_series);
                $smarty_obj->assign("CHART_TITLE", $selected_charttitle);
            }
            return $smarty_obj;
        }
    }

    // ITS4YOU-CR SlOl 24. 9. 2014 9:47:08
    public function cleanITS4YouReportsCacheFiles() {
        global $current_user;
        if (is_admin($current_user)) {
            foreach (glob("test/ITS4YouReports/*.png") as $filename) {
                unlink($filename);
            }
            foreach (glob("test/ITS4YouReports/*.pdf") as $filename) {
                unlink($filename);
            }
            foreach (glob("test/ITS4YouReports/*.xls") as $filename) {
                unlink($filename);
            }
            $return = "<pre>" . vtranslate("LBL_DONE", $this->currentModule) . vtranslate("LBL_DOTS", $this->currentModule) . "</pre>";
        } else {
            $return = "<pre>" . vtranslate("LBL_ONLY_ADMIN", $this->currentModule) . vtranslate("LBL_DOTS", $this->currentModule) . "</pre>";
        }
        return $return;
    }

    // ITS4YOU-END 24. 9. 2014 9:47:10
    // ITS4YOU-CR SlOl 24. 9. 2014 10:23:42
    public function GetReports4YouForImport() {
        global $current_user;
        $reports_to_import = array();
        $is_admin = false;
        $user_privileges_path = 'user_privileges/user_privileges_' . $current_user->id . '.php';
        if (file_exists($user_privileges_path)) {
            require($user_privileges_path);
        }
        if ($is_admin === true) {
            $adb = PearDatabase::getInstance();
            $imported_reports = $to_merge = array();
            foreach (glob("modules/ITS4YouReports/reports/imported/*.php") as $imported_file) {
                $imported_reports[] = str_replace("reports/imported/", "reports/", $imported_file);
            }
            foreach (glob("modules/ITS4YouReports/reports/*.php") as $report_file) {
                if (!in_array($report_file, $imported_reports)) {
                    $name_arr_temp = explode("/", $report_file);
                    $name_arr = explode("_", $name_arr_temp[((count($name_arr_temp) - 1))]);
                    $name_arr_id_temp = explode(".", $name_arr[1]);
                    $name_arr_id = $name_arr_id_temp[0];
                    if (is_numeric($name_arr_id)) {
                        $reports_to_import[$name_arr_id] = $report_file;
                    } else {
                        $to_merge[] = $report_file;
                    }
                }
            }
            ksort($reports_to_import);
            $reports_to_import = array_merge($reports_to_import, $to_merge);
        }
        /* else{
          ITS4YouReports::DieDuePermission();
          } */
        return $reports_to_import;
    }

    public function ImportReports4You($file_to_import = "", $debug = false) {
//echo "<pre>";print_r($file_to_import);echo "</pre>";

        $adb = PEARDatabase::getInstance();

//$debug = true;
        if ($debug) {
            $adb->setDebug(true);
        }
        if ($file_to_import != "" && file_exists($file_to_import) && substr($file_to_import, -4) == ".php") {
            $result = $adb->pquery("SELECT @reports4youid:=(IF(reports4youid IS NOT NULL,max(reports4youid)+1,1)) AS reports4youid FROM its4you_reports4you;", array());

            $row = $adb->fetchByAssoc($result);
            $report_id = $row["reports4youid"];
            $ReportSql = array();

            global $default_charset;

            require_once $file_to_import;
            $report_array_json = str_replace("@reportid", $report_id, $report_array_json);
            $ReportSql = Zend_Json::decode($report_array_json);
            if (isset($ReportSql["its4you_reports4you_settings"]) && !empty($ReportSql["its4you_reports4you_settings"])) {
                $its4you_reports4you_settings = vtlib_purify($ReportSql["its4you_reports4you_settings"]);
                $adb->pquery("INSERT INTO `its4you_reports4you_settings` (`reportid`, `owner`, `sharingtype`) VALUES (?,?,?)", array($its4you_reports4you_settings));
            }
            if (isset($ReportSql["its4you_reports4you_selectcolumn"]) && !empty($ReportSql["its4you_reports4you_selectcolumn"])) {
                $its4you_reports4you_selectcolumn = vtlib_purify($ReportSql["its4you_reports4you_selectcolumn"]);
                // $adb->pquery("INSERT INTO its4you_reports4you_selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) VALUES (?,?,?)",array($selected_column));
                $sql = "INSERT INTO its4you_reports4you_selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) VALUES ";
                $Qarray = Array();
                $Values = Array();
                foreach ($its4you_reports4you_selectcolumn as $c_key => $selected_column) {
                    $Qarray[] = '(?,?,?)';
                    $Values = array_merge($Values, $selected_column);
                }
                $adb->pquery($sql . implode(', ', $Qarray), $Values);
            }
            if (isset($ReportSql["its4you_reports4you_summaries"]) && !empty($ReportSql["its4you_reports4you_summaries"])) {
                $its4you_reports4you_summaries = vtlib_purify($ReportSql["its4you_reports4you_summaries"]);
                //$adb->pquery("INSERT INTO its4you_reports4you_summaries (reportsummaryid,summarytype,columnname) VALUES (?,?,?)",array($summaries_array));
                $sql = "INSERT INTO its4you_reports4you_summaries (reportsummaryid, summarytype, columnname) VALUES ";
                $Qarray = Array();
                $Values = Array();
                foreach ($its4you_reports4you_summaries as $s_key => $summaries_array) {
                    $Qarray[] = '(?,?,?)';
                    $Values = array_merge($Values, $summaries_array);
                }
                $adb->pquery($sql . implode(', ', $Qarray), $Values);
            }
            if (isset($ReportSql["its4you_reports4you_summaries_orderby"]) && !empty($ReportSql["its4you_reports4you_summaries_orderby"])) {
                $its4you_reports4you_summaries_orderby = vtlib_purify($ReportSql["its4you_reports4you_summaries_orderby"]);
                $adb->pquery("INSERT INTO its4you_reports4you_summaries_orderby (reportid,columnindex,summaries_orderby,summaries_orderby_type) VALUES (?,?,?,?)", array($its4you_reports4you_summaries_orderby));
            }
            if (isset($ReportSql["its4you_reports4you_labels"]) && !empty($ReportSql["its4you_reports4you_labels"])) {
                $its4you_reports4you_labels = vtlib_purify($ReportSql["its4you_reports4you_labels"]);
                //$adb->pquery("INSERT INTO its4you_reports4you_labels (reportid,type,columnname,columnlabel) VALUES (?,?,?,?)",array($labels_array));
                $sql = "INSERT INTO its4you_reports4you_labels (reportid,type,columnname,columnlabel) VALUES ";
                $Qarray = Array();
                $Values = Array();
                foreach ($its4you_reports4you_labels as $l_key => $labels_array) {
                    $Qarray[] = '(?,?,?,?)';
                    $Values = array_merge($Values, $labels_array);
                }
                $adb->pquery($sql . implode(', ', $Qarray), $Values);
            }
            if (isset($ReportSql["its4you_reports4you"]) && !empty($ReportSql["its4you_reports4you"])) {
                $its4you_reports4you = vtlib_purify($ReportSql["its4you_reports4you"]);
                /*
                  $folder_result = $adb->pquery("SELECT IF(folderid IS NOT NULL, folderid, count(folderid)+1) AS folderid FROM `its4you_reports4you_folder` WHERE foldername=?", array($its4you_reports4you[3]));
                  $folder_row = $adb->fetchByAssoc($folder_result);
                  $its4you_reports4you[3] = $folder_row["folderid"];
                 */
                $adb->pquery("INSERT INTO its4you_reports4you (reports4youid,reports4youname,description,folderid,reporttype,deleted,columns_limit,summaries_limit) VALUES (?,?,?,?,?,?,?,?)", array($its4you_reports4you));
            }
            if (isset($ReportSql["its4you_reports4you_summary"]) && !empty($ReportSql["its4you_reports4you_summary"])) {
                $its4you_reports4you_summary = vtlib_purify($ReportSql["its4you_reports4you_summary"]);
                //$adb->pquery("INSERT INTO its4you_reports4you_summary (REPORTSUMMARYID,SUMMARYTYPE,COLUMNNAME) VALUES (?,?,?);",array($summary_array));
                $sql = "INSERT INTO its4you_reports4you_summary (REPORTSUMMARYID,SUMMARYTYPE,COLUMNNAME) VALUES ";
                $Qarray = Array();
                $Values = Array();
                foreach ($its4you_reports4you_summary as $sm_key => $summary_array) {
                    $Qarray[] = '(?,?,?)';
                    $Values = array_merge($Values, $summary_array);
                }
                $adb->pquery($sql . implode(', ', $Qarray), $Values);
            }
            if (isset($ReportSql["its4you_reports4you_modules"]) && !empty($ReportSql["its4you_reports4you_modules"])) {
                $its4you_reports4you_modules = vtlib_purify($ReportSql["its4you_reports4you_modules"]);
                $adb->pquery("INSERT INTO its4you_reports4you_modules (REPORTMODULESID,PRIMARYMODULE,SECONDARYMODULES) VALUES (?,?,?)", array($its4you_reports4you_modules));
            }
            if (isset($ReportSql["its4you_reports4you_sortcol"]) && !empty($ReportSql["its4you_reports4you_sortcol"])) {
                $its4you_reports4you_sortcol = vtlib_purify($ReportSql["its4you_reports4you_sortcol"]);
                //$adb->pquery("INSERT INTO its4you_reports4you_sortcol (sortcolid,reportid,columnname,sortorder,timeline_type,timeline_columnstr,timeline_columnfreq) VALUES (?,?,?,?,?,?,?)",array($its4you_reports4you_sortcol_arr));
                $sql = "INSERT INTO its4you_reports4you_sortcol (sortcolid,reportid,columnname,sortorder,timeline_type,timeline_columnstr,timeline_columnfreq) VALUES ";
                $Qarray = Array();
                $Values = Array();
                foreach ($its4you_reports4you_sortcol as $sortcol_i => $its4you_reports4you_sortcol_arr) {
                    $Qarray[] = '(?,?,?,?,?,?,?)';
                    if ($sortcol_i == "3") {
                        /* if($its4you_reports4you_sortcol_arr[$sortcol_i]=="ASC"){
                          $its4you_reports4you_sortcol_arr[$sortcol_i] = "Ascending";
                          }else{
                          $its4you_reports4you_sortcol_arr[$sortcol_i] = "Descending";
                          } */
                        $its4you_reports4you_sortcol_arr[4] = "rows";
                        $its4you_reports4you_sortcol_arr[5] = "";
                        $its4you_reports4you_sortcol_arr[6] = "";
                    }
                    $Values = array_merge($Values, $its4you_reports4you_sortcol_arr);
                }
                $adb->pquery($sql . implode(', ', $Qarray), $Values);
            }
            if (isset($ReportSql["its4you_reports4you_relcriteria"]) && !empty($ReportSql["its4you_reports4you_relcriteria"])) {
                $its4you_reports4you_relcriteria = vtlib_purify($ReportSql["its4you_reports4you_relcriteria"]);
                //$adb->pquery("INSERT INTO its4you_reports4you_relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE,GROUPID,COLUMN_CONDITION) VALUES (?,?,?,?,?,?,?)",array($its4you_reports4you_relcriteria_arr));
                $sql = "INSERT INTO its4you_reports4you_relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE,GROUPID,COLUMN_CONDITION) VALUES ";
                $Qarray = Array();
                $Values = Array();
                foreach ($its4you_reports4you_relcriteria as $its4you_reports4you_relcriteria_arr) {
                    $Qarray[] = '(?,?,?,?,?,?,?)';
                    foreach ($its4you_reports4you_relcriteria_arr as $rc_k => $rc_v) {
                        $its4you_reports4you_relcriteria_arr[$rc_k] = html_entity_decode($rc_v, ENT_QUOTES, $default_charset);
                    }
                    $Values = array_merge($Values, $its4you_reports4you_relcriteria_arr);
                }
                $adb->pquery($sql . implode(', ', $Qarray), $Values);
            }
            if (isset($ReportSql["its4you_reports4you_relcriteria_grouping"]) && !empty($ReportSql["its4you_reports4you_relcriteria_grouping"])) {
                $its4you_reports4you_relcriteria_grouping = vtlib_purify($ReportSql["its4you_reports4you_relcriteria_grouping"]);
                //$adb->pquery("INSERT INTO its4you_reports4you_relcriteria_grouping(GROUPID,QUERYID,GROUP_CONDITION,CONDITION_EXPRESSION) VALUES (?,?,?,?)",array($its4you_reports4you_relcriteria_grouping_arr));
                $sql = "INSERT INTO its4you_reports4you_relcriteria_grouping(GROUPID,QUERYID,GROUP_CONDITION,CONDITION_EXPRESSION) VALUES ";
                $Qarray = Array();
                $Values = Array();
                foreach ($its4you_reports4you_relcriteria_grouping as $its4you_reports4you_relcriteria_grouping_arr) {
                    $Qarray[] = '(?,?,?,?)';
                    $Values = array_merge($Values, $its4you_reports4you_relcriteria_grouping_arr);
                }
                $adb->pquery($sql . implode(', ', $Qarray), $Values);
            }
            if (isset($ReportSql["its4you_reports4you_datefilter"]) && !empty($ReportSql["its4you_reports4you_datefilter"])) {
                $its4you_reports4you_datefilter = vtlib_purify($ReportSql["its4you_reports4you_datefilter"]);
                $adb->pquery("INSERT INTO its4you_reports4you_datefilter (DATEFILTERID,DATECOLUMNNAME,DATEFILTER,STARTDATE,ENDDATE) VALUES (?,?,?,?,?)", array($its4you_reports4you_datefilter));
            }
            if (isset($ReportSql["its4you_reports4you_charts"]) && !empty($ReportSql["its4you_reports4you_charts"])) {
                foreach ($ReportSql["its4you_reports4you_charts"] as $its4you_reports4you_charts) {
                    $adb->pquery("INSERT INTO its4you_reports4you_charts (reports4youid,charttype,dataseries,charttitle,chart_seq,x_group) VALUES (?,?,?,?,?,?)", array($its4you_reports4you_charts));
                }
            }
            $adb->pquery("INSERT INTO its4you_reports4you_selectquery (`queryid`, `startindex`, `numofobjects`) VALUES (?, '0', '0')", array($report_id));
            $adb->pquery("UPDATE its4you_reports4you_selectquery_seq SET id = ?", array($report_id));

            $new_imported_file = str_replace("reports/", "reports/imported/", $file_to_import);
            if (!copy($file_to_import, $new_imported_file)) {
                $return = vtranslate("LBL_COPPY_FAILED", $this->currentModule) . " $file_to_import" . vtranslate("LBL_DOTS", $this->currentModule) . "<br />";
            } else {
                $return = vtranslate("LBL_IMPORT_SUCCESS", $this->currentModule) . " $new_imported_file" . vtranslate("LBL_DOTS", $this->currentModule) . "<br />";
            }
//$adb->setDebug(false);
        } else {
            $return = vtranslate("LBL_FILE_NOT_SUPPORTED", $this->currentModule) . vtranslate("LBL_DOTS", $this->currentModule) . "<br />";
        }
        if ($debug) {
            $adb->setDebug(false);
        }
        return $return;
    }

    // ITS4YOU-END 24. 9. 2014 10:23:44  

    /** Function to get the vtiger_role and subordinate user ids
     * taken from vtiger 540
     * @param $roleid -- RoleId :: Type varchar
     * @returns $roleSubUserIds-- Role and Subordinates Related Users Array in the following format:
     *       $roleSubUserIds=Array($userId1,$userId2,........,$userIdn);
     */
    function getRoleAndSubordinateUserIds($roleId) {
        global $adb;
        $roleInfoArr = getRoleInformation($roleId);
        $parentRole = $roleInfoArr[$roleId][1];
        $query = "select vtiger_user2role.*,vtiger_users.user_name from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like ?";
        $result = $adb->pquery($query, array($parentRole . "%"));
        $num_rows = $adb->num_rows($result);
        $roleRelatedUsers = Array();
        for ($i = 0; $i < $num_rows; $i++) {
            $roleRelatedUsers[] = $adb->query_result($result, $i, 'userid');
        }
        return $roleRelatedUsers;
    }

    // WIDGET LINKS FIX !!! s
    private function fix_widget_labels() {
        $adb = PearDatabase::getInstance();
        $w_sql = "SELECT linkid,linklabel,linkurl FROM vtiger_links WHERE linklabel='WIDGETLABEL' AND tabid='3'";
        $w_result = $adb->pquery($w_sql, array());
        if ($adb->num_rows($w_result) > 0) {
            while ($w_row = $adb->fetchByAssoc($w_result)) {
                $link_array = explode("record=", $w_row["linkurl"]);
                if (isset($link_array[1]) && $link_array[1] != "") {
                    $w_reportid = $link_array[1];
                    $w_name_res = $adb->pquery("SELECT reports4youname FROM  its4you_reports4you WHERE reports4youid = ?", array($w_reportid));
                    if ($adb->num_rows($w_name_res) > 0) {
                        $w_name_row = $adb->fetchByAssoc($w_name_res, 0);
                        $w_name = $w_name_row["reports4youname"];
                        //$adb->setDebug(true);
                        $adb->pquery("UPDATE vtiger_links SET linklabel=? WHERE linkid=?", array($w_name, $w_row["linkid"]));
                        //$adb->setDebug(false);
                    }
                }
            }
        }
    }
    // WIDGET LINKS FIX !!! e
    // ITS4YOU-CR SlOl | 20.8.2015 12:25 
    public function querySpecialControl($sql_query, $black_list, $offset=0) {
        $black_list = array("delete ", "insert ", "update ", "drop ", "create ");
        foreach($black_list as $black_value) {
            if (stripos($sql_query, $black_value) !== false) {
                return true;
            }
        }
        return false;
    }
    // ITS4YOU-CR SlOl | 20.8.2015 12:25 
    public function validateCustomSql($sql_query,$type="check"){
        $adb = PearDatabase::getInstance();
        if($sql_query!=""){
            global $default_charset;
            $sql_query = html_entity_decode($sql_query, ENT_QUOTES, $default_charset);
            
            $sql_count_test = explode(";",$sql_query);
            if(ITS4YouReports::querySpecialControl($sql_query)==true){
                ITS4YouReports::DieDuePermission("LBL_WRONG_QUERY_STRINGS_SQL");
                exit;                
            }elseif(count($sql_count_test)>1){
                ITS4YouReports::DieDuePermission("LBL_TOO_MANY_SQL");
                exit;
            }
            
            if($type=='run'){
                $sql_query = str_replace("\n", "", $sql_query);
                //$sql_query = $adb->sql_escape_string($sql_query);
            }
        }
        
        return $sql_query;
    }
    // ITS4YOU-END
    
    // ITS4YOU-CR SlOl 17. 9. 2015 13:41:48
    // OPRAVA CHYBNEHO PREPISU FIELDLABEL V COLUMNNAME Z VERZIE 5.X !!!
    public function repairColumnStrings($reportid=""){
        
        // SET UP ALTER TABLE
        //ITS4YouReports::repairReportsTables();
        
        // SET UP MODULES REPORT TYPES
        //ITS4YouReports::repairReportsTypes($reportid);
        
        // SELECTED COLUMNS REPAIR
        //ITS4YouReports::repairReportsSelectedColumns($reportid);
        
        // FILTER CONDITIONS REPAIR
        //ITS4YouReports::repairReportsRelCriteria($reportid);
        
        // FILTER SUMMARIES CONDITIONS REPAIR
        //ITS4YouReports::repairReportsRelCriteriaSummaries($reportid);
        
        // SELECTED SORT COL REPAIR
        //ITS4YouReports::repairReportsSelectedSortCol($reportid);
        
        // SELECTED SUMMARIES COLUMNS REPAIR
        //ITS4YouReports::repairReportsSelectedSummaries($reportid);
        
        // SELECTED SUMMARIES ORDER BY REPAIR
        //ITS4YouReports::repairReportsSummariesOrderBy($reportid);
        
        // SELECTED LABELS REPAIR
        //ITS4YouReports::repairReportsLabels($reportid);
        
    }
    // ITS4YOU-CR SlOl 18. 9. 2015 9:24:03
    public function repairReportsTables(){
        $adb = PearDatabase::getInstance();
        
        $queryDebug = false;
        $queryDebug = true;
        
        $queryDebug==true?$adb->setDebug(true):"";
        
        //$adb->query("ALTER TABLE its4you_reports4you_charts ADD chart_seq INT( 11 ) NOT NULL");
        //$adb->query("ALTER TABLE its4you_reports4you_charts ADD x_group varchar( 255 ) NOT NULL");
        
        $adb->query("ALTER TABLE its4you_reports4you_folder ADD description varchar( 250 ) DEFAULT ''");
        $adb->query("ALTER TABLE its4you_reports4you_folder ADD state varchar( 50 ) DEFAULT 'SAVED'");
        $adb->query("ALTER TABLE its4you_reports4you_folder ADD ownerid int( 11 ) NOT NULL");
        
        $queryDebug==true?$adb->setDebug(false):"";
        
        return true;
    }
    // ITS4YOU-CR SlOl 18. 9. 2015 9:24:03
    public function repairReportsTypes($reportid=""){
        $adb = PearDatabase::getInstance();
        global $default_charset;
        
        $queryDebug = false;
        $queryDebug = true;
        
        $where = "";
        if($reportid!=""){
            $where = " WHERE its4you_reports4you.reports4youid = ? ";
            $params[] = $reportid;
        }
        
        $ssql = "SELECT 
                reports4youid, 
                reporttype,
                its4you_reports4you_sortcol.sortcolid, 
                its4you_reports4you_sortcol.timeline_type, 
                its4you_reports4you_sortcol.columnname columnname_sc , 
                its4you_reports4you_selectcolumn.columnname columnname_dt 
                
                FROM its4you_reports4you 
                
                LEFT JOIN its4you_reports4you_sortcol ON its4you_reports4you_sortcol.reportid = its4you_reports4you.reports4youid AND its4you_reports4you_sortcol.columnname!= 'none' AND its4you_reports4you_sortcol.sortcolid!= '4' 
                LEFT JOIN its4you_reports4you_summaries ON its4you_reports4you_summaries.reportsummaryid = its4you_reports4you.reports4youid 
                LEFT JOIN its4you_reports4you_selectcolumn ON its4you_reports4you_selectcolumn.queryid = its4you_reports4you.reports4youid
                 
                $where 
                 
                GROUP BY 
                its4you_reports4you.reports4youid , 
                its4you_reports4you_sortcol.columnname
                 
                ORDER BY 
                its4you_reports4you.reports4youid ASC, 
                its4you_reports4you_sortcol.sortcolid ASC, 
                its4you_reports4you_selectcolumn.columnname ASC ";
//WHERE its4you_reports4you.reports4youid IN (57, 58, 4, 11, 15, 34, 9, 36, 30, 32) 
                
        $params = array();
        if($reportid!=""){
            $ssql .= ' WHERE queryid = ? ';
            $params[] = $reportid;
        }
        
        $result = $adb->pquery($ssql, $params);
        $noOfColumns = $adb->num_rows($result);
        $check_array = array();
        
        if($noOfColumns>0){
            while ($reportrow = $adb->fetchByAssoc($result)) {
                $reporttype = "";
                
                $reportid = $reportrow["reports4youid"];
                
                $timeline_type = $reportrow["timeline_type"];
                
                $columnname_sc = $reportrow["columnname_sc"];
                $columnname_dt = $reportrow["columnname_dt"];
                                
                if($columnname_sc!=""){
                    $check_array[$reportid]["columnname_sc"][] = $columnname_sc;
                }
                if($columnname_dt!=""){
                    $check_array[$reportid]["columnname_dt"][] = $columnname_dt;
                }
                if($timeline_type!=""){
                    $check_array[$reportid]["timeline_type"][] = $timeline_type;
                }
            }
            
            if(!empty($check_array)){
                foreach($check_array as $reportid => $report_array){
                    $timeline_type = $report_array["timeline_type"];
                    $columnname_dt = $report_array["columnname_dt"];
                    $columnname_sc = $report_array["columnname_sc"];
/*
ITS4YouReports::sshow("START TYPIZATION");
ITS4YouReports::sshow($reportid);                    
ITS4YouReports::sshow($report_array);
*/
                    if(empty($timeline_type)){
                        // tabular
                        $reporttype = "tabular";
                        //ITS4YouReports::sshow($reporttype);
                        ITS4YouReports::repairReportType($reportid,$reporttype);
                        continue;
                    }
                    if(count($timeline_type)==1 && !empty($columnname_dt)){
                        $reporttype = "summaries_w_details";
                        //ITS4YouReports::sshow($reporttype);
                        ITS4YouReports::repairReportType($reportid,$reporttype);
                        continue;
                    }
                    if(in_array("cols",$timeline_type)){
                        $reporttype = "summaries_matrix";
                        //ITS4YouReports::sshow($reporttype);
                        ITS4YouReports::repairReportType($reportid,$reporttype);
                        continue;
                    }
                    $reporttype = "summaries";
                    //ITS4YouReports::sshow($reporttype);
                    ITS4YouReports::repairReportType($reportid,$reporttype);
                }
            }

            
        }
        return true;
    }
    public function repairReportType($reportid,$reporttype){
        $adb = PearDatabase::getInstance();
        
        $queryDebug = false;
        $queryDebug = true;
        
        if($reportid!="" && $reporttype!=""){
            $queryDebug==true?$adb->setDebug(true):"";
            $adb->pquery("UPDATE its4you_reports4you SET reporttype = ? WHERE reports4youid = ? ",array($reporttype,$reportid));
            $queryDebug==true?$adb->setDebug(false):"";
        }  
        return true;
    }
    // ITS4YOU-CR SlOl 17. 9. 2015 13:41:48
    public function repairReportsSelectedColumns($reportid=""){
        $adb = PearDatabase::getInstance();
        global $default_charset;
        
        $queryDebug = false;
        $queryDebug = true;
        
        $ssql = 'select queryid, columnindex, columnname from its4you_reports4you_selectcolumn ';
        $params = array();
        if($reportid!=""){
            $ssql .= ' WHERE queryid = ? ';
            $params[] = $reportid;
        }
        
        $result = $adb->pquery($ssql, $params);
        $noOfColumns = $adb->num_rows($result);
        if($noOfColumns>0){
            while ($columnrow = $adb->fetchByAssoc($result)) {
                $queryid = $columnrow["queryid"];
                $columnindex = $columnrow["columnindex"];
                $columnname = $oldColumnname = $columnrow["columnname"];
                $columnnameArr = explode(":", $columnname);
                $columnLabelArr = explode("_", $columnnameArr[2]);
                $fixedLabel = $columnModule = "";
                foreach($columnLabelArr as $labelKey => $labelPiece){
                    if($labelKey==0){
                        $columnModule = $labelPiece;
                    }else{
                        if($fixedLabel!=""){
                            $fixedLabel .= " ";
                        }
                        $fixedLabel .= $labelPiece;
                    }
                }
                $newLabelCheckout = $adb->num_rows($adb->pquery("SELECT fieldid FROM vtiger_field WHERE tabid = ? AND fieldlabel = ?",array(getTabid($columnModule),$fixedLabel)));
                if($newLabelCheckout>0){
                    $columnnameArr[2] = $columnModule."_".$fixedLabel;
                }
                $columnname = implode(":",$columnnameArr);
                $columnname = html_entity_decode($columnname,ENT_QUOTES,$default_charset);
                $queryDebug==true?$adb->setDebug(true):"";
                $adb->pquery("UPDATE its4you_reports4you_selectcolumn SET columnname = ? WHERE queryid = ? AND columnindex = ? ",array($columnname,$queryid,$columnindex));
                $queryDebug==true?$adb->setDebug(false):"";
            }
        }
        return true;
    }
    // ITS4YOU-CR SlOl 17. 9. 2015 13:41:48
    public function repairReportsRelCriteria($reportid=""){
        $adb = PearDatabase::getInstance();
        global $default_charset;
        
        $queryDebug = false;
        $queryDebug = true;
        
        $ssql = 'select queryid, columnindex, columnname from its4you_reports4you_relcriteria ';
        $params = array();
        if($reportid!=""){
            $ssql .= ' WHERE queryid = ? ';
            $params[] = $reportid;
        }
        
        $result = $adb->pquery($ssql, $params);
        $noOfColumns = $adb->num_rows($result);
        if($noOfColumns>0){
            while ($columnrow = $adb->fetchByAssoc($result)) {
                $queryid = $columnrow["queryid"];
                $columnindex = $columnrow["columnindex"];
                $columnname = $oldColumnname = $columnrow["columnname"];
                $columnnameArr = explode(":", $columnname);
                $columnLabelArr = explode("_", $columnnameArr[2]);
                $fixedLabel = $columnModule = "";
                foreach($columnLabelArr as $labelKey => $labelPiece){
                    if($labelKey==0){
                        $columnModule = $labelPiece;
                    }else{
                        if($fixedLabel!=""){
                            $fixedLabel .= " ";
                        }
                        $fixedLabel .= $labelPiece;
                    }
                }
                $newLabelCheckout = $adb->num_rows($adb->pquery("SELECT fieldid FROM vtiger_field WHERE tabid = ? AND fieldlabel = ?",array(getTabid($columnModule),$fixedLabel)));
                if($newLabelCheckout>0){
                    $columnnameArr[2] = $columnModule."_".$fixedLabel;
                }
                $columnname = implode(":",$columnnameArr);
                $columnname = html_entity_decode($columnname,ENT_QUOTES,$default_charset);
                $queryDebug==true?$adb->setDebug(true):"";
                $adb->pquery("UPDATE its4you_reports4you_relcriteria SET columnname = ? WHERE queryid = ? AND columnindex = ? ",array($columnname,$queryid,$columnindex));
                $queryDebug==true?$adb->setDebug(false):"";
            }
        }
        return true;
    }
    // ITS4YOU-CR SlOl 17. 9. 2015 13:41:48
    public function repairReportsRelCriteriaSummaries($reportid=""){
        $adb = PearDatabase::getInstance();
        global $default_charset;
        
        $queryDebug = false;
        $queryDebug = true;
        
        $ssql = 'select reportid, columnindex, columnname from its4you_reports4you_relcriteria_summaries ';
        $params = array();
        if($reportid!=""){
            $ssql .= ' WHERE reportid = ? ';
            $params[] = $reportid;
        }
        
        $result = $adb->pquery($ssql, $params);
        $noOfColumns = $adb->num_rows($result);
        if($noOfColumns>0){
            while ($columnrow = $adb->fetchByAssoc($result)) {
                $queryid = $columnrow["reportid"];
                $columnindex = $columnrow["columnindex"];
                $columnname = $oldColumnname = $columnrow["columnname"];
                $columnnameArr = explode(":", $columnname);
                $columnLabelArr = explode("_", $columnnameArr[2]);
                $fixedLabel = $columnModule = "";
                foreach($columnLabelArr as $labelKey => $labelPiece){
                    if($labelKey==0){
                        $columnModule = $labelPiece;
                    }else{
                        if($fixedLabel!=""){
                            $fixedLabel .= " ";
                        }
                        $fixedLabel .= $labelPiece;
                    }
                }
                $newLabelCheckout = $adb->num_rows($adb->pquery("SELECT fieldid FROM vtiger_field WHERE tabid = ? AND fieldlabel = ?",array(getTabid($columnModule),$fixedLabel)));
                if($newLabelCheckout>0){
                    $columnnameArr[2] = $columnModule."_".$fixedLabel;
                }
                $columnname = implode(":",$columnnameArr);
                $columnname = html_entity_decode($columnname,ENT_QUOTES,$default_charset);
                $queryDebug==true?$adb->setDebug(true):"";
                $adb->pquery("UPDATE its4you_reports4you_relcriteria_summaries SET columnname = ? WHERE reportid = ? AND columnindex = ? ",array($columnname,$queryid,$columnindex));
                $queryDebug==true?$adb->setDebug(false):"";
            }
        }
        return true;
    }
    // ITS4YOU-CR SlOl 17. 9. 2015 13:41:48
    public function repairReportsSelectedSortCol($reportid=""){
        $adb = PearDatabase::getInstance();
        global $default_charset;
        
        $queryDebug = false;
        $queryDebug = true;
        
        $ssql = 'select sortcolid, reportid, columnname from its4you_reports4you_sortcol ';
        $params = array();
        if($reportid!=""){
            $ssql .= ' WHERE reportid = ? ';
            $params[] = $reportid;
        }
        
        $result = $adb->pquery($ssql, $params);
        $noOfColumns = $adb->num_rows($result);
        if($noOfColumns>0){
            while ($columnrow = $adb->fetchByAssoc($result)) {
                $sreportid = $columnrow["reportid"];
                $sortcolid = $columnrow["sortcolid"];
                $columnname = $oldColumnname = $columnrow["columnname"];
                $columnnameArr = explode(":", $columnname);
                $columnLabelArr = explode("_", $columnnameArr[2]);
                $fixedLabel = $columnModule = "";
                foreach($columnLabelArr as $labelKey => $labelPiece){
                    if($labelKey==0){
                        $columnModule = $labelPiece;
                    }else{
                        if($fixedLabel!=""){
                            $fixedLabel .= " ";
                        }
                        $fixedLabel .= $labelPiece;
                    }
                }
                $newLabelCheckout = $adb->num_rows($adb->pquery("SELECT fieldid FROM vtiger_field WHERE tabid = ? AND fieldlabel = ?",array(getTabid($columnModule),$fixedLabel)));
                if($newLabelCheckout>0){
                    $columnnameArr[2] = $columnModule."_".$fixedLabel;
                }
                $columnname = implode(":",$columnnameArr);
                $columnname = html_entity_decode($columnname,ENT_QUOTES,$default_charset);
                $queryDebug==true?$adb->setDebug(true):"";
                $adb->pquery("UPDATE its4you_reports4you_sortcol SET columnname = ? WHERE reportid = ? AND sortcolid = ? ",array($columnname,$sreportid,$sortcolid));
                $queryDebug==true?$adb->setDebug(false):"";
            }
        }
        return true;
    }
    // ITS4YOU-CR SlOl 17. 9. 2015 13:41:48
    public function repairReportsSelectedSummaries($reportid=""){
        $adb = PearDatabase::getInstance();
        global $default_charset;
        
        $queryDebug = false;
        $queryDebug = true;
        
        $ssql = 'select reportsummaryid, summarytype, columnname from its4you_reports4you_summaries ';
        $params = array();
        if($reportid!=""){
            $ssql .= ' WHERE reportsummaryid = ? ';
            $params[] = $reportid;
        }
        
        $result = $adb->pquery($ssql, $params);
        $noOfColumns = $adb->num_rows($result);
        if($noOfColumns>0){
            while ($columnrow = $adb->fetchByAssoc($result)) {
                $sreportid = $columnrow["reportsummaryid"];
                $summarytype = $columnrow["summarytype"];
                $columnname = $oldColumnname = $columnrow["columnname"];
                $columnnameArr = explode(":", $columnname);
                $columnLabelArr = explode("_", $columnnameArr[2]);
                $fixedLabel = $columnModule = "";
                foreach($columnLabelArr as $labelKey => $labelPiece){
                    if($labelKey==0){
                        $columnModule = $labelPiece;
                    }else{
                        if($fixedLabel!=""){
                            $fixedLabel .= " ";
                        }
                        $fixedLabel .= $labelPiece;
                    }
                }
                $newLabelCheckout = $adb->num_rows($adb->pquery("SELECT fieldid FROM vtiger_field WHERE tabid = ? AND fieldlabel = ?",array(getTabid($columnModule),$fixedLabel)));
                if($newLabelCheckout>0){
                    $columnnameArr[2] = $columnModule."_".$fixedLabel;
                }
                $columnname = implode(":",$columnnameArr);
                $columnname = html_entity_decode($columnname,ENT_QUOTES,$default_charset);
                $queryDebug==true?$adb->setDebug(true):"";
                $adb->pquery("UPDATE its4you_reports4you_summaries SET columnname = ? WHERE reportsummaryid = ? AND summarytype = ? ",array($columnname,$sreportid,$summarytype));
                $queryDebug==true?$adb->setDebug(false):"";
            }
        }
        return true;
    }
    // ITS4YOU-CR SlOl 17. 9. 2015 13:41:48
    public function repairReportsSummariesOrderBy($reportid=""){
        $adb = PearDatabase::getInstance();
        global $default_charset;
        
        $queryDebug = false;
        $queryDebug = true;
        
        $ssql = 'select reportid, columnindex, summaries_orderby from its4you_reports4you_summaries_orderby ';
        $params = array();
        if($reportid!=""){
            $ssql .= ' WHERE reportid = ? ';
            $params[] = $reportid;
        }
        
        $result = $adb->pquery($ssql, $params);
        $noOfColumns = $adb->num_rows($result);
        if($noOfColumns>0){
            while ($columnrow = $adb->fetchByAssoc($result)) {
                $sreportid = $columnrow["reportid"];
                $columnindex = $columnrow["columnindex"];
                $columnname = $oldColumnname = $columnrow["summaries_orderby"];
                $columnnameArr = explode(":", $columnname);
                $columnLabelArr = explode("_", $columnnameArr[2]);
                $fixedLabel = $columnModule = "";
                foreach($columnLabelArr as $labelKey => $labelPiece){
                    if($labelKey==0){
                        $columnModule = $labelPiece;
                    }else{
                        if($fixedLabel!=""){
                            $fixedLabel .= " ";
                        }
                        $fixedLabel .= $labelPiece;
                    }
                }
                $newLabelCheckout = $adb->num_rows($adb->pquery("SELECT fieldid FROM vtiger_field WHERE tabid = ? AND fieldlabel = ?",array(getTabid($columnModule),$fixedLabel)));
                if($newLabelCheckout>0){
                    $columnnameArr[2] = $columnModule."_".$fixedLabel;
                }
                $columnname = implode(":",$columnnameArr);
                $columnname = html_entity_decode($columnname,ENT_QUOTES,$default_charset);
                $queryDebug==true?$adb->setDebug(true):"";
                $adb->pquery("UPDATE its4you_reports4you_summaries_orderby SET summaries_orderby = ? WHERE reportid = ? AND columnindex = ? ",array($columnname,$sreportid,$columnindex));
                $queryDebug==true?$adb->setDebug(false):"";
            }
        }
        return true;
    }
    // ITS4YOU-CR SlOl 17. 9. 2015 13:41:48
    public function repairReportsLabels($reportid=""){
        $adb = PearDatabase::getInstance();
        global $default_charset;
        
        $queryDebug = false;
        $queryDebug = true;
        
        $ssql = 'select reportid, type, columnlabel, columnname from its4you_reports4you_labels ';
        $params = array();
        if($reportid!=""){
            $ssql .= ' WHERE reportid = ? ';
            $params[] = $reportid;
        }
        
        $result = $adb->pquery($ssql, $params);
        $noOfColumns = $adb->num_rows($result);
        if($noOfColumns>0){
            while ($columnrow = $adb->fetchByAssoc($result)) {
                $sreportid = $columnrow["reportid"];
                $stype = $columnrow["type"];
                $columnlabel = html_entity_decode($columnrow["columnlabel"],ENT_QUOTES,$default_charset);
                $columnname = $oldColumnname = $columnrow["columnname"];
                $columnnameArr = explode(":", $columnname);
                $columnLabelArr = explode("_", $columnnameArr[2]);
                $fixedLabel = $columnModule = "";
                foreach($columnLabelArr as $labelKey => $labelPiece){
                    if($labelKey==0){
                        $columnModule = $labelPiece;
                    }else{
                        if($fixedLabel!=""){
                            $fixedLabel .= " ";
                        }
                        $fixedLabel .= $labelPiece;
                    }
                }
                $newLabelCheckout = $adb->num_rows($adb->pquery("SELECT fieldid FROM vtiger_field WHERE tabid = ? AND fieldlabel = ?",array(getTabid($columnModule),$fixedLabel)));
                if($newLabelCheckout>0){
                    $columnnameArr[2] = $columnModule."_".$fixedLabel;
                }
                $columnname = implode(":",$columnnameArr);
                $columnname = html_entity_decode($columnname,ENT_QUOTES,$default_charset);
                $queryDebug==true?$adb->setDebug(true):"";
                $adb->pquery("UPDATE its4you_reports4you_labels SET columnname = ? WHERE reportid = ? AND type = ?  AND columnlabel = ? ",array($columnname,$sreportid,$stype,$columnlabel));
                $queryDebug==true?$adb->setDebug(false):"";
            }
        }
        return true;
    }
    // ITS4YOU-END  
}
/*
172	0	vtiger_crmentity:modifiedtime:Potentials_Modified Time:modifiedtime:DT	thisfy	2015-01-01<;@STDV@;>2015-12-31	1	and
172	1	vtiger_crmentity:smownerid:Potentials_Assigned To:assigned_user_id:V	c	Kopecka	1	and
172	2	vtiger_potentialscf:cf_558:Potentials_Sample_Requested:cf_558:V	n	 	1	 
*/
// ITS4YOU-CR SlOl FUNCTIONS
/** Function to get the combo values for the Primary module Columns 
 *  @ param $module(module name) :: Type String
 *  @ param $selected (<selected or ''>) :: Type String
 *  This function generates the combo values for the columns  for the given module 
 *  and return a HTML string 
 */
function getPrimaryColumns_GroupingHTML($module, $selected = "", $ogReport = "") {
    global $app_list_strings, $current_language;
    if ($ogReport == "") {
        if (ITS4YouReports::isStoredITS4YouReport() === true) {
            $ogReport = ITS4YouReports::getStoredITS4YouReport();
        } else {
            $ogReport = new ITS4YouReports();
        }
    }
    $id_added = false;
    $shtml = "";
    if (vtlib_isModuleActive($module)) {
        $mod_strings = return_module_language($current_language, $module);

        $block_listed = array();
        $selected = decode_html($selected);

        if (!isset($ogReport->module_list) || empty($ogReport->module_list)) {
            $ogReport->initListOfModules();
        }
        // ITS4YOU-CR SlOl 3. 3. 2014 10:43:03
        if (!isset($ogReport->pri_module_columnslist[$module]) || $ogReport->pri_module_columnslist[$module] == "") {
            $ogReport_pri_module_columnslist = $ogReport->getPriModuleColumnsList($module);
        }
        // ITS4YOU-END 3. 3. 2014 10:43:06
//echo "<pre>";print_r($selected);echo "</pre>";
        foreach ($ogReport->module_list[$module] as $key => $value) {
            if (isset($ogReport->pri_module_columnslist[$module][$value]) && !($block_listed[$value])) {
                $block_listed[$value] = true;
                $shtml .= "<optgroup label=\"" . $app_list_strings['moduleList'][$module] . " " . vtranslate($value) . "\" class=\"select\" style=\"border:none\">";
                if ($id_added == false) {
                    $is_selected = '';
                    if ($selected == "vtiger_crmentity:crmid:" . $module . "_ID:crmid:I") {
                        $is_selected = 'selected';
                    }
                    $shtml .= "<option value=\"vtiger_crmentity:crmid:" . $module . "_ID:crmid:I\" {$is_selected}>" . vtranslate(vtranslate($module) . ' ID') . "</option>";
                    $id_added = true;
                }
                foreach ($ogReport->pri_module_columnslist[$module][$value] as $field => $fieldlabel) {
                    if (isset($mod_strings[$fieldlabel])) {
                        if ($selected == decode_html($field)) {
                            $shtml .= "<option selected value=\"" . $field . "\">" . $mod_strings[$fieldlabel] . "</option>";
                        } else {
                            $shtml .= "<option value=\"" . $field . "\">" . $mod_strings[$fieldlabel] . "</option>";
                        }
                    } else {
                        if ($selected == decode_html($field)) {
                            $shtml .= "<option selected value=\"" . $field . "\">" . $fieldlabel . "</option>";
                        } else {
                            $shtml .= "<option value=\"" . $field . "\">" . $fieldlabel . "</option>";
                        }
                    }
                }
                $shtml .= "</optgroup>";
            }
        }
    }
    return $shtml;
}

/** Function to get the combo values for the Secondary module Columns 
 *  @ param $module(module name) :: Type String
 *  @ param $selected (<selected or ''>) :: Type String
 *  This function generates the combo values for the columns for the given module 
 *  and return a HTML string 
 */
function getSecondaryColumns_GroupingHTML($moduleid, $selected = "", $ogReport = "") {
    global $app_list_strings;
    global $current_language;
    $adb = PearDatabase::getInstance();
    if ($ogReport == "") {
        if (ITS4YouReports::isStoredITS4YouReport() === true) {
            $ogReport = ITS4YouReports::getStoredITS4YouReport();
        } else {
            $ogReport = new ITS4YouReports($recordid);
        }
    }
    
    $secmodule_arr = explode("x", $moduleid);
    $module_id = $secmodule_arr[0];
    $field_id = (isset($secmodule_arr[1]) && $secmodule_arr[1] != "" ? $secmodule_arr[1] : "");

    $fieldname = $fieldname_lbl = "";
    if ($field_id != "" && !in_array($field_id, ITS4YouReports::$customRelationTypes)) {
        $fieldname_row = $adb->fetchByAssoc($adb->pquery("SELECT fieldlabel,uitype FROM vtiger_field WHERE fieldid=?", array($field_id)), 0);
        $fieldname = " " . $fieldname_row["fieldlabel"];
        $fieldname_lbl = " " . vtranslate($fieldname_row["fieldlabel"], $ogReport->primarymodule);
    } elseif ($field_id == "INV") {
        $fieldname = " Inventory";
        $fieldname_lbl = " ".  vtranslate("Inventory", "ITS4YouReports");
    } elseif ($field_id == "MIF") {
        $fieldname = " More Information";
        $fieldname_lbl = " ".  vtranslate("More Information", "ITS4YouReports");
    }
    $module = vtlib_getModuleNameById($module_id);
        
    $sec_options = array();
    $selected = decode_html($selected);
    if ($module != "") {
        $secmodule = explode(":", $module);
        for ($i = 0; $i < count($secmodule); $i++) {
            //$mod_strings = return_module_language($current_language, $secmodule[$i]);
            if (vtlib_isModuleActive($secmodule[$i])) {
                $block_listed = array();
                foreach ($ogReport->module_list[$secmodule[$i]] as $key => $value) {
                    if (isset($ogReport->sec_module_columnslist[$secmodule[$i] . $fieldname][$value])) {
                        $block_listed[$value] = true;

                        // ITS4YOU-UP SlOl 18. 2. 2014 12:13:53
                        $optgroup_lbl = vtranslate($value, $secmodule[$i]);
                        $optgroup_lbl .= " ($fieldname_lbl)";
//ITS4YouReports::sshow(vtranslate($fieldname, $ogReport->primarymodule)." - ".$ogReport->primarymodule);
                        $shtml .= "<optgroup label=\"" . $optgroup_lbl . "\" class=\"select\" style=\"border:none\">";
                        // ITS4YOU-END 18. 2. 2014 12:13:59
//ITS4YouReports::sshow("NXTS ".$optgroup_lbl);
                        foreach ($ogReport->sec_module_columnslist[$secmodule[$i] . $fieldname][$value] as $field => $fieldlabel) {
                            if ($selected == decode_html($field)) {
                                $shtml .= "<option selected value=\"" . $field . "\">" . vtranslate($fieldlabel, $secmodule[$i]) . "</option>";
                            } else {
                                $shtml .= "<option value=\"" . $field . "\">" . vtranslate($fieldlabel, $secmodule[$i]) . "</option>";
                            }
//ITS4YouReports::sshow($shtml);
                        }
                        $shtml .= "</optgroup>";
                    }
                }
            }
        }
    }
    return $shtml;
}

/** Function to formulate the vtiger_fields for the primary modules 
 *  This function accepts the module name 
 *  as arguments and generates the vtiger_fields for the primary module as
 *  a HTML Combo values
 */
function getPrimaryColumnsHTML($module, $ogReport = "") {
    if ($ogReport == "") {
        if (ITS4YouReports::isStoredITS4YouReport() === true) {
            $ogReport = ITS4YouReports::getStoredITS4YouReport();
        } else {
            $ogReport = new ITS4YouReports($recordid);
        }
    }
    global $app_list_strings;
    global $app_strings;
    global $current_language;
    $id_added = false;
    $block_listed = array();
    foreach ($ogReport->module_list[$module] as $key => $value) {
        // ITS4YOU-CR SlOl 3. 3. 2014 10:43:03
        if (isset($ogReport->pri_module_columnslist[$module]) || $ogReport->pri_module_columnslist[$module] == "") {
            $ogReport->getPriModuleColumnsList($module);
        }
        // ITS4YOU-END 3. 3. 2014 10:43:06
        if (isset($ogReport->pri_module_columnslist[$module][$value]) && !$block_listed[$value]) {
            $block_listed[$value] = true;
            $translate_module = $module;
            if ($module == "Calendar" && in_array($value, array("LBL_RECURRENCE_INFORMATION", "LBL_RELATED_TO"))) {
                $translate_module = "Events";
            }
            $shtml .= "<optgroup label=\"" . $app_list_strings['moduleList'][$module] . " " . vtranslate($value, $translate_module) . "\" class=\"select\" style=\"border:none\">";
            if ($id_added == false) {
                $shtml .= "<option value=\"vtiger_crmentity:crmid:" . $module . "_ID:crmid:I\">" . vtranslate(vtranslate($module, $translate_module) . ' ID') . "</option>";
                $id_added = true;
            }
            foreach ($ogReport->pri_module_columnslist[$module][$value] as $field => $fieldlabel) {
                $shtml .= "<option value=\"" . $field . "\">" . vtranslate($fieldlabel, $translate_module) . "</option>";
            }
            $shtml .= "</optgroup>";
        }
    }
    return $shtml;
}

/** Function to formulate the vtiger_fields for the secondary modules
 *  This function accepts the module name
 *  as arguments and generates the vtiger_fields for the secondary module as
 *  a HTML Combo values
 */
function getSecondaryColumnsHTML($module, $ogReport = "") {
    if ($ogReport == "") {
        if (ITS4YouReports::isStoredITS4YouReport() === true) {
            $ogReport = ITS4YouReports::getStoredITS4YouReport();
        } else {
            $ogReport = new ITS4YouReports($recordid);
        }
    }
    global $app_list_strings, $app_strings;
    global $current_language;

    if ($module != "") {
        $secmodule = explode(":", $module);
        for ($i = 0; $i < count($secmodule); $i++) {
            $modulename = vtlib_getModuleNameById($secmodule[$i]);
            $mod_strings = return_module_language($current_language, $modulename);
            if (vtlib_isModuleActive($modulename)) {
                $block_listed = array();
                foreach ($ogReport->module_list[$modulename] as $key => $value) {
                    if (isset($ogReport->sec_module_columnslist[$modulename][$value]) && !$block_listed[$value]) {
                        $block_listed[$value] = true;
                        $shtml .= "<optgroup label=\"" . $app_list_strings['moduleList'][$modulename] . " " . vtranslate($value) . "\" class=\"select\" style=\"border:none\">";
                        foreach ($ogReport->sec_module_columnslist[$modulename][$value] as $field => $fieldlabel) {
                            if (isset($mod_strings[$fieldlabel])) {
                                $shtml .= "<option value=\"" . $field . "\">" . $mod_strings[$fieldlabel] . "</option>";
                            } else {
                                $shtml .= "<option value=\"" . $field . "\">" . $fieldlabel . "</option>";
                            }
                        }
                        $shtml .= "</optgroup>";
                    }
                }
            }
        }
    }
    return $shtml;
}

function sgetColumnstoTotalHTMLScript($Objects, $tabid) {
    $mod_arr = explode("x", $tabid);
    $module = vtlib_getModuleNameById($mod_arr[0]);
    $fieldidstr = "";
    if (isset($mod_arr[1]) && $mod_arr[1] != "") {
        $fieldidstr = ":" . $mod_arr[1];
    }
    //retreive the vtiger_tabid	
    global $current_user;
    $adb = PearDatabase::getInstance();
    $user_privileges_path = 'user_privileges/user_privileges_' . $current_user->id . '.php';
    if (file_exists($user_privileges_path)) {
        require($user_privileges_path);
    }

    $result = self::getColumnsTotalRow($tabid);

    /* $adb->setDebug(true);
      $adb->setDebug(false);
      echo "<pre>";print_r($RG_BLOCK4);echo "</pre>"; */
    if ($adb->num_rows($result) > 0) {
        do {
            $typeofdata = explode("~", $columntototalrow["typeofdata"]);

            //if ($typeofdata[0] == "N" || $typeofdata[0] == "I" || $typeofdata[0] == "NN") {
            $object_name = 'cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $module . "_" . $columntototalrow['fieldlabel'];

            $Objects[] = $object_name . "_SUM:2" . $fieldidstr;
            $Objects[] = $object_name . "_AVG:3" . $fieldidstr;
            $Objects[] = $object_name . "_MIN:4" . $fieldidstr;
            $Objects[] = $object_name . "_MAX:5" . $fieldidstr;
            /* $Objects[] = $object_name . "_COUNT:6" . $fieldidstr; */
            //}
        } while ($columntototalrow = $adb->fetch_array($result));
    }
    return $Objects;
}

// ITS4YOU-CR SlOl 24. 2. 2014 13:57:34
function sgetNewColumnstoTotalHTMLScript($Objects) {
    $returnObjects = array();
    foreach ($Objects as $key => $ObjectRow) {
        $ObjectRow_array = explode(":", $ObjectRow);
        $last_key = count($ObjectRow_array) - 1;
        $fieldidstr = $fieldid = $clear_tablename = "";
        if (is_numeric($ObjectRow_array[$last_key]) || in_array($ObjectRow_array[$last_key], ITS4YouReports::$customRelationTypes)) {
            $fieldid = $ObjectRow_array[$last_key];
            $fieldidstr = ":" . $fieldid;
            $typeofdata = $ObjectRow_array[$last_key - 1];
            array_pop($ObjectRow_array);
            $clear_tablename = trim($ObjectRow_array[0], "_$fieldid");
            $ObjectRow = implode(":", $ObjectRow_array);
        } else {
            $ObjectRow = implode(":", $ObjectRow_array);
            $typeofdata = $ObjectRow_array[$last_key];
        }

        list($sc_tablename, $sc_columnname, $sc_modulestr) = explode(':', $ObjectRow);
        if ($clear_tablename == "") {
            $clear_tablename = $sc_tablename;
        }
        list($sc_module) = explode('_', $sc_modulestr);
        $sc_module_id = getTabid($sc_module);
        //$sc_tablename = trim(strtolower($sc_tablename), "_mif");
        $adb = PearDatabase::getInstance();
        if ($clear_tablename != "") {
            $sc_field_row = $adb->fetchByAssoc($adb->pquery("SELECT uitype FROM vtiger_field WHERE tablename = ? and columnname = ? and tabid=?", array($clear_tablename, $sc_columnname, $sc_module_id)), 0);
            //if ($typeofdata[0] == "N" || $typeofdata[0] == "I" || $typeofdata[0] == "NN") {
            if (in_array($sc_field_row["uitype"], array('7', '9', '71', '72')) || ($sc_field_row["uitype"] == '1' && ($typeofdata[0] == "N" || $typeofdata[0] == "I" || $typeofdata[0] == "NN"))) {
                $object_name = 'cb:' . $ObjectRow;

                $returnObjects[] = $object_name . "_SUM:2" . $fieldidstr;
                $returnObjects[] = $object_name . "_AVG:3" . $fieldidstr;
                $returnObjects[] = $object_name . "_MIN:4" . $fieldidstr;
                $returnObjects[] = $object_name . "_MAX:5" . $fieldidstr;
                /* $returnObjects[] = $object_name . "_COUNT:6" . $fieldidstr; */
            }
        }
        //}
    }
    return $returnObjects;
}

// ITS4YOU-END 24. 2. 2014 13:57:36 
function getPrimaryColumns($Options, $module, $id_added = false, $ogReport = "") {
//    if ($ogReport == "") {
    if (ITS4YouReports::isStoredITS4YouReport() === true) {
        $ogReport = ITS4YouReports::getStoredITS4YouReport();
    } else {
        $ogReport = new ITS4YouReports();
    }
//    }

    global $app_list_strings;
    global $app_strings;
    global $current_language;
    $mod_strings = return_module_language($current_language, $module);
    $block_listed = array();
    // ITS4YOU-CR SlOl 3. 3. 2014 10:43:03
    if (!isset($ogReport->pri_module_columnslist[$module]) || empty($ogReport->pri_module_columnslist[$module]) || $ogReport->pri_module_columnslist[$module] == "") {
        $ogReport->getPriModuleColumnsList($module);
    }
    if (!isset($ogReport->module_list[$module]) || empty($ogReport->module_list[$module])) {
        $ogReport->initListOfModules();
    }

    $lead_converted_added = false;

    // ITS4YOU-END 3. 3. 2014 10:43:06pri_module_columnslist[$module]
    if ($module == "Calendar") {
        $calendar_block = vtranslate($module, $module);
        $cal_options = $cal_options_f[$calendar_block] = array();
        $skip_fields = array("eventstatus", "status");
        $status_arr = array();
        foreach ($ogReport->pri_module_columnslist[$module] as $block_key => $field_array) {
            foreach ($field_array as $column_str => $column_label) {
                $column_arr = explode(":", $column_str);
                if (!in_array($column_arr[1], $skip_fields)) {
                    $cal_options[$block_key][$column_str] = $column_label;
                } elseif (empty($status_arr)) {
                    $status_arr = array("value" => $column_str, "text" => $column_label);
                }
            }
            $count_arri = 0;
            $due_date_populated = $duration_minutes_populated = $duration_hours_populated = false;
            foreach ($cal_options as $b => $inter) {
                $count_arri++;
                if ($block_key != "Custom Information") {
                    if (!empty($intersect)) {
                        $intersect = array_intersect_assoc($intersect, $cal_options[$block_key]);
                        $Dintersect1 = array_diff($cal_options[$block_key], $intersect);
                        $Dintersect2 = array_diff($cal_options[$prev_block_key], $intersect);
                        foreach ($intersect as $field => $fieldlabel) {
                            if (isset($mod_strings[$fieldlabel]))
                                $fieldlabel = $mod_strings[$fieldlabel];
                            if (!$ogReport->in_multiarray($field, $cal_options_f[$calendar_block], "value")) {
                                $cal_options_f[$calendar_block][] = array("value" => $field, "text" => $fieldlabel);
                            }
                        }
                        foreach ($Dintersect1 as $field => $fieldlabel) {
                            // FIX FOR DUAL CALENDAR OPTIONS !!! S
                            if ($ogReport->in_multiarray($field, $cal_options_f[$calendar_block], "value") == true) {
                                continue;
                            }
                            if (strpos($field, "vtiger_activity:due_date:") !== false) {
                                $cal_options_f[$calendar_block][] = array("value" => $field, "text" => $fieldlabel);
                                continue;
                            }
                            if (strpos($field, "vtiger_activity:duration_minutes:Calendar_Duration_Minutes:duration_minutes:") !== false) {
                                $cal_options_f[$calendar_block][] = array("value" => $field, "text" => $fieldlabel);
                                continue;
                            }
                            if (strpos($field, "vtiger_activity:duration_hours:Calendar_Duration:duration_hours:") !== false) {
                                $cal_options_f[$calendar_block][] = array("value" => $field, "text" => $fieldlabel);
                                continue;
                            }
                            if (isset($mod_strings[$fieldlabel]))
                                $fieldlabel = $mod_strings[$fieldlabel];
                            // FIX FOR DUAL CALENDAR OPTIONS !!! E
                            if (!$ogReport->in_multiarray($field, $cal_options_f[$block_key], "value")) {
                                $cal_options_f[$block_key][] = array("value" => $field, "text" => $fieldlabel);
                            }
                        }
                        foreach ($Dintersect2 as $field => $fieldlabel) {
                            // FIX FOR DUAL CALENDAR OPTIONS !!! S
                            if (strpos($field, "vtiger_activity:due_date:") !== false) {
                                continue;
                            }
                            if (strpos($field, "vtiger_activity:duration_minutes:Calendar_Duration_Minutes:duration_minutes:") !== false) {
                                continue;
                            }
                            if (strpos($field, "vtiger_activity:duration_hours:Calendar_Duration:duration_hours:") !== false) {
                                continue;
                            }
                            if (isset($mod_strings[$fieldlabel]))
                                $fieldlabel = $mod_strings[$fieldlabel];
                            // FIX FOR DUAL CALENDAR OPTIONS !!! E
                            if (!$ogReport->in_multiarray($field, $cal_options_f[$prev_block_key], "value")) {
                                $cal_options_f[$prev_block_key][] = array("value" => $field, "text" => $fieldlabel);
                            }
                        }
                    } else {
                        $intersect = $cal_options[$block_key];
                    }
                    if ($block_key != $prev_block_key) {
                        $prev_block_key = $block_key;
                    }
                } else {
                    foreach ($field_array as $field => $fieldlabel) {
                        if (isset($mod_strings[$fieldlabel]))
                            $fieldlabel = $mod_strings[$fieldlabel];
                        if (!$ogReport->in_multiarray($field, $cal_options_f[$block_key], "value")) {
                            $cal_options_f[$block_key][] = array("value" => $field, "text" => $fieldlabel);
                        }
                    }
                }
            }
        }
        $access_count_listed = false;
        if (in_array($module, array("Calendar",)) && $access_count_listed !== true) {
            $optgroup = $app_list_strings['moduleList'][$module] . " - " . vtranslate("Email Information", "ITS4YouReports");
            $access_count_option = "access_count:access_count:" . $module . "_access_count:Access Count:V";
            $access_count_label = vtranslate("Emails") . " " . vtranslate("Access Count");
            $cal_options_f[$optgroup][] = array("value" => $access_count_option, "text" => $access_count_label);
            $access_count_listed = true;
        }
        if (!empty($status_arr)) {
            $cal_options_f[$calendar_block][] = $status_arr;
        }
        ksort($cal_options_f);
        $Options = array_merge($Options, $cal_options_f);
    } else {
        foreach ($ogReport->module_list[$module] as $key => $value) {
            if (isset($ogReport->pri_module_columnslist[$module][$value]) && !$block_listed[$value]) {
                $block_listed[$key] = true;
                $optgroup = $app_list_strings['moduleList'][$module] . " - " . vtranslate($value);
                if ($id_added == false) {
                    $Options[$optgroup]["vtiger_crmentity:crmid:" . $module . "_ID:crmid:I"] = vtranslate(vtranslate($module) . ' ID');
                    $id_added = true;
                }
                foreach ($ogReport->pri_module_columnslist[$module][$value] as $field => $fieldlabel) {
                    if (isset($mod_strings[$fieldlabel]))
                        $fieldlabel = $mod_strings[$fieldlabel];
                    $Options[$optgroup][] = array("value" => $field, "text" => $fieldlabel);
                }
            }
        }
    }

    return $Options;
}

function getSecondaryColumns($Options, $module, $ogReport = "") {
    if ($ogReport == "") {
        if (ITS4YouReports::isStoredITS4YouReport() === true) {
            $ogReport = ITS4YouReports::getStoredITS4YouReport();
        } else {
            $ogReport = new ITS4YouReports($recordid);
        }
    }
    global $app_list_strings, $app_strings;
    global $current_language;
    $adb = PearDatabase::getInstance();

    if (!isset($ogReport->module_list) || empty($ogReport->module_list)) {
        $ogReport->initListOfModules();
    }
    if ($module != "") {
        $secmodule = explode(":", $module);

        for ($i = 0; $i < count($secmodule); $i++) {
            $module_prefix = $secmodule[$i];

            $secmodule_arr = explode("x", $secmodule[$i]);
            $module_id = $secmodule_arr[0];
            $field_id = (isset($secmodule_arr[1]) && $secmodule_arr[1] != "" ? $secmodule_arr[1] : "");
            $fieldname = $opt_fieldname = "";
            $modulename = vtlib_getModuleNameById($module_id);
            if ($field_id != "" && is_numeric($field_id)) {
                $fieldname_row = $adb->fetchByAssoc($adb->pquery("SELECT fieldlabel FROM vtiger_field WHERE fieldid=?", array($field_id)), 0);
                $fieldname = " " . $fieldname_row["fieldlabel"];
                $opt_fieldname = " (" . vtranslate($fieldname_row["fieldlabel"], $ogReport->primarymodule) . ")";
            } elseif ($field_id == "INV") {
                $fieldname = " Inventory";
                $opt_fieldname = " (Inventory)";
            } elseif ($field_id == "MIF") {
                $fieldname = " More Information";
                $opt_fieldname = " (" . vtranslate('LBL_MORE_INFORMATION', "Users") . ")";
            }

            //$mod_strings = return_module_language($current_language, $modulename);
            if (vtlib_isModuleActive($modulename)) {
                $block_listed = array();
                if (isset($_REQUEST["primarymoduleid"]) && $_REQUEST["primarymoduleid"] == 26) {
                    $campaignstatus_listed = false;
                }
                foreach ($ogReport->module_list[$modulename] as $key => $value) {
                    if (!isset($ogReport->sec_module_columnslist)) {
                        $ogReport->getSecModuleColumnsList($module);
                    }
                    if (isset($ogReport->sec_module_columnslist[$modulename . $fieldname][$value]) && !$block_listed[$value]) {
                        $block_listed[$value] = true;
                        $optgroup = $app_list_strings['moduleList'][$modulename] . " - " . vtranslate($value) . $opt_fieldname;
                        foreach ($ogReport->sec_module_columnslist[$modulename . $fieldname][$value] as $field => $fieldlabel) {
                            //if (isset($mod_strings[$fieldlabel]))
                            //    $fieldlabel = $mod_strings[$fieldlabel];
                            $fieldlabel = vtranslate($fieldlabel, $modulename);
                            // s$Options[$module_id][$optgroup][] = array("value" => $field, "text" => $fieldlabel);
                            $Options[$module_prefix][$optgroup][] = array("value" => $field, "text" => $fieldlabel);
                        }
                        if ($campaignstatus_listed !== true && isset($_REQUEST["primarymoduleid"]) && $_REQUEST["primarymoduleid"] == 26 && in_array($modulename, array("Leads", "Contacts", "Accounts",))) {
                            $campaignrelstatus_option = "vtiger_campaignrelstatus_$field_id:campaignrelstatus:" . $modulename . "_campaignrelstatus:Status:V:$field_id";
                            $campaignrelstatus_label = vtranslate("Campaign") . " " . vtranslate("Status");
                            $Options[$module_prefix][$optgroup][] = array("value" => $campaignrelstatus_option, "text" => $campaignrelstatus_label);
                            $campaignstatus_listed = true;
                        }
                    }
                }
                $access_count_listed = false;
                if ($_REQUEST["primarymoduleid"] != $_REQUEST["selectedmodule"] && $access_count_listed !== true && in_array($modulename, array("Calendar",))) {
                    $optgroup = $app_list_strings['moduleList'][$modulename] . " - " . vtranslate("Email Information", "ITS4YouReports");
                    $access_count_option = "access_count_$field_id:access_count:" . $modulename . "_access_count:Access Count:V:$field_id";
                    $access_count_label = vtranslate("Emails") . " " . vtranslate("Access Count");
                    $Options[$module_prefix][$optgroup][] = array("value" => $access_count_option, "text" => $access_count_label);
                    $access_count_listed = true;
                } elseif ($access_count_listed !== true && in_array($modulename, array("Calendar",))) {
                    $optgroup = $app_list_strings['moduleList'][$modulename] . " - " . vtranslate("Email Information", "ITS4YouReports");
                    $access_count_option = "access_count:access_count:" . $modulename . "_access_count:Access Count:V";
                    $access_count_label = vtranslate("Emails") . " " . vtranslate("Access Count");
                    $Options[$module_prefix][$optgroup][] = array("value" => $access_count_option, "text" => $access_count_label);
                    $access_count_listed = true;
                }
            }
        }
    }
    return $Options;
}

function sgetColumntoTotalOptions($Options, $primarymodule, $secondarymodules) {
    $SOptions = sgetColumnstoTotalObjectsOptions($Options, $primarymodule);
    if (!empty($secondarymodules)) {
        //$secondarymodule = explode(":",$secondarymodule);
        for ($i = 0; $i < count($secondarymodules); $i++) {
            $SOptions = sgetColumnstoTotalObjectsOptions($Options, $secondarymodules[$i]);
        }
    }
    return $SOptions;
}

/* function sgetColumnstoTotalHTMLOptions($module) {
  //retreive the vtiger_tabid
  global $log;
  global $current_user;
  $adb = PearDatabase::getInstance();
  require('user_privileges/user_privileges_' . $current_user->id . '.php');
  $tabid = getTabid($module);

  $sparams = array($tabid);
  if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
  $ssql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid where vtiger_field.uitype != 50 and vtiger_field.tabid=? and vtiger_field.displaytype in (1,2,3) and vtiger_field.presence in (0,2) ";
  } else {
  $profileList = getCurrentUserProfileList();
  $ssql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid  where vtiger_field.uitype != 50 and vtiger_field.tabid=? and vtiger_field.displaytype in (1,2,3) and vtiger_def_org_field.visible=0 and vtiger_profile2field.visible=0 and vtiger_field.presence in (0,2)";
  if (count($profileList) > 0) {
  $ssql .= " and vtiger_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
  array_push($sparams, $profileList);
  }
  }

  //Added to avoid display the Related fields (Account name,Vandor name,product name, etc) in Report Calculations(SUM,AVG..)
  switch ($tabid) {
  case 2://Potentials
  //ie. Campaign name will not displayed in Potential's report calcullation
  $ssql.= " and vtiger_field.fieldname not in ('campaignid')";
  break;
  case 4://Contacts
  $ssql.= " and vtiger_field.fieldname not in ('account_id')";
  break;
  case 6://Accounts
  $ssql.= " and vtiger_field.fieldname not in ('account_id')";
  break;
  case 9://Calandar
  $ssql.= " and vtiger_field.fieldname not in ('parent_id','contact_id')";
  break;
  case 13://Trouble tickets(HelpDesk)
  $ssql.= " and vtiger_field.fieldname not in ('parent_id','product_id')";
  break;
  case 14://Products
  $ssql.= " and vtiger_field.fieldname not in ('vendor_id','product_id','handler')";
  break;
  case 20://Quotes
  $ssql.= " and vtiger_field.fieldname not in ('potential_id','assigned_user_id1','account_id','currency_id')";
  break;
  case 21://Purchase Order
  $ssql.= " and vtiger_field.fieldname not in ('contact_id','vendor_id','currency_id')";
  break;
  case 22://SalesOrder
  $ssql.= " and vtiger_field.fieldname not in ('potential_id','account_id','contact_id','quote_id','currency_id')";
  break;
  case 23://Invoice
  $ssql.= " and vtiger_field.fieldname not in ('salesorder_id','contact_id','account_id','currency_id')";
  break;
  case 26://Campaigns
  $ssql.= " and vtiger_field.fieldname not in ('product_id')";
  break;
  }

  $ssql.= " order by sequence";

  $result = $adb->pquery($ssql, $sparams);
  $columntototalrow = $adb->fetch_array($result);
  $options = "";
  do {
  $typeofdata = explode("~", $columntototalrow["typeofdata"]);

  if ($typeofdata[0] == "N" || $typeofdata[0] == "I" || $typeofdata[0] == "NN") {
  //vtiger_crmentity:crmid:Accounts_ID:crmid:I
  $optionvalue = $columntototalrow['tablename'] . ":" . $columntototalrow['columnname'] . ":" . $module . "_" . $columntototalrow['fieldlabel'] . ":" . $columntototalrow['fieldname'];

  $options .= '<optgroup label="' . vtranslate($columntototalrow['tablabel'], $columntototalrow['tablabel']) . ' - ' . vtranslate($columntototalrow['fieldlabel'], $columntototalrow['tablabel']) . '">';
  $fieldidstr = ($columntototalrow['fieldid'] != "" ? ":" . $columntototalrow['fieldid'] : "");
  $options .= '<option value="' . $optionvalue . ':SUM' . $fieldidstr . '">' . $columntototalrow['fieldlabel'] . ' (SUM)</option>';
  $options .= '<option value="' . $optionvalue . ':AVG' . $fieldidstr . '">' . $columntototalrow['fieldlabel'] . ' (AVG)</option>';
  $options .= '<option value="' . $optionvalue . ':MIN' . $fieldidstr . '">' . $columntototalrow['fieldlabel'] . ' (MIN)</option>';
  $options .= '<option value="' . $optionvalue . ':MAX' . $fieldidstr . '">' . $columntototalrow['fieldlabel'] . ' (MAX)</option>';
  $options .= '<option value="' . $optionvalue . ':COUNT' . $fieldidstr . '">' . $columntototalrow['fieldlabel'] . ' (COUNT)</option>';
  $options .= '</optgroup>';
  }
  } while ($columntototalrow = $adb->fetch_array($result));


  global $inventory_modules;
  if (in_array($module, $inventory_modules)) {
  $fieldtablename = 'vtiger_inventoryproductrel' . $tabid;
  $fields = array('listprice' => vtranslate('List Price', $module),
  'discount' => vtranslate('Discount', $module),
  'quantity' => vtranslate('Quantity', $module)
  );
  $fields_datatype = array('listprice' => 'I',
  'discount' => 'I',
  'quantity' => 'I'
  );
  foreach ($fields as $fieldcolname => $label) {
  $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $label . ":" . $fieldcolname;

  $options .= '<optgroup label="' . vtranslate($module, $module) . ' ' . vtranslate("Product", $module) . ' / ' . vtranslate("Service", $module) . ' - ' . $label . '">';
  $options .= '<option value="' . $optionvalue . ':SUM">' . $label . ' (SUM)</option>';
  $options .= '<option value="' . $optionvalue . ':AVG">' . $label . ' (AVG)</option>';
  $options .= '<option value="' . $optionvalue . ':MIN">' . $label . ' (MIN)</option>';
  $options .= '<option value="' . $optionvalue . ':MAX">' . $label . ' (MAX)</option>';
  $options .= '<option value="' . $optionvalue . ':COUNT">' . $label . ' (COUNT)</option>';
  $options .= '</optgroup>';
  }
  }


  $log->info("Reports :: Successfully returned sgetColumnstoTotalHTML");
  return $options;
  } */

/* function getStdCriteriaByModule($moduleid) {
  global $current_user;
  $mod_arr = explode("x", $moduleid);
  $module = vtlib_getModuleNameById($mod_arr[0]);
  $fieldidstr = "";
  if (isset($mod_arr[1]) && $mod_arr[1] != "") {
  $fieldidstr = ":" . $mod_arr[1];
  }

  $adb = PearDatabase::getInstance();
  $user_privileges_path = 'user_privileges/user_privileges_' . $current_user->id . '.php';
  if(file_exists($user_privileges_path)){
  require($user_privileges_path);
  }


  $tabid = getTabid($module);
  foreach ($this->module_list[$module] as $key => $blockid) {
  $blockids[] = $blockid;
  }
  $blockids = implode(",", $blockids);

  $params = array($tabid, $blockids);
  if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
  //uitype 6 and 23 added for start_date,EndDate,Expected Close Date
  $sql = "select * from vtiger_field where vtiger_field.tabid=? and (vtiger_field.uitype =5 or vtiger_field.uitype = 6 or vtiger_field.uitype = 23 or vtiger_field.displaytype=2) and vtiger_field.block in (" . generateQuestionMarks($block) . ") and vtiger_field.presence in (0,2) order by vtiger_field.sequence";
  } else {
  $profileList = getCurrentUserProfileList();
  $sql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid  where vtiger_field.tabid=? and (vtiger_field.uitype =5 or vtiger_field.displaytype=2) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_field.block in (" . generateQuestionMarks($block) . ") and vtiger_field.presence in (0,2)";
  if (count($profileList) > 0) {
  $sql .= " and vtiger_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
  array_push($params, $profileList);
  }
  $sql .= " order by vtiger_field.sequence";
  }

  $result = $adb->pquery($sql, $params);

  while ($criteriatyperow = $adb->fetch_array($result)) {
  $fieldid = $criteriatyperow["fieldid"];
  $fieldtablename = $criteriatyperow["tablename"];
  $fieldcolname = $criteriatyperow["columnname"];
  $fieldlabel = $criteriatyperow["fieldlabel"];

  if ($fieldtablename == "vtiger_crmentity") {
  $fieldtablename = $fieldtablename . $module;
  }
  $fieldlabel1 = str_replace(" ", "_", $fieldlabel);
  // $fieldidstr = ($fieldid != "" ? ":" . $fieldid : "");
  $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . $fieldidstr . "_" . $fieldlabel1;
  $stdcriteria_list[$optionvalue] = $fieldlabel;
  }

  return $stdcriteria_list;
  } */

// ITS4YOU-CR SlOl 1. 10. 2013 14:50:37 getfieldid
function get_field_id($key) {
    $fieldid = "";
    if ($key != "") {
        $adb = PearDatabase::getInstance();
        $key_arr = explode(":", $key);
        $key_subarr = explode("_", $key_arr[3]);
        $key_tabid = getTabid($key_subarr[0]);
        $sql = "SELECT fieldid FROM vtiger_field WHERE tablename=? AND fieldname=? AND tabid=?";
        $result = $adb->pquery($sql, array($key_arr[0], $key_arr[1], $key_tabid));
        while ($row = $adb->fetchByAssoc($result)) {
            $fieldid = ":" . $row["fieldid"];
        }
    }
    return $fieldid;
}

// ITS4YOU-UP SlOl 1. 10. 2013 14:56:39 fieldid
function getPrimaryStdFilter($module, $ogReport = "") {
    global $app_list_strings;
    if ($ogReport == "") {
        if (ITS4YouReports::isStoredITS4YouReport() === true) {
            $ogReport = ITS4YouReports::getStoredITS4YouReport();
        } else {
            $ogReport = new ITS4YouReports($recordid);
        }
    }
    global $current_language;
    $Options = array();
    if (vtlib_isModuleActive($module)) {
        $ogReport->oCustomView = new CustomView();
        // $result = $ogReport->oCustomView->getStdCriteriaByModule($module);
        $result = $ogReport->getStdCriteriaByModule($module);

        $mod_strings = return_module_language($current_language, $module);

        if (isset($result)) {
            foreach ($result as $key => $value) {
                /* $fieldid = get_field_id($key) */;
                $fieldid = "";
                if (isset($mod_strings[$value])) {
                    $Options[] = array("value" => $key . "$fieldid", 'text' => vtranslate($module, $module) . " - " . vtranslate($value, $secmodule[$i]));
                } else {
                    $Options[] = array("value" => $key . "$fieldid", 'text' => vtranslate($module, $module) . " - " . $value);
                }
            }
        }
    }
    return $Options;
}

function getSecondaryStdFilter($module_arr, $Options, $ogReport = "") {
    global $app_list_strings;
    global $current_language;
    if ($ogReport == "") {
        if (ITS4YouReports::isStoredITS4YouReport() === true) {
            $ogReport = ITS4YouReports::getStoredITS4YouReport();
        } else {
            $ogReport = new ITS4YouReports($recordid);
        }
    }
    $adb = PearDatabase::getInstance();

    $moduleid = $module_arr["id"];
    $modulename = $module_arr["name"];

    $mod_arr = explode("x", $moduleid);
    $sec_module_id = $mod_arr[0];
    $module = vtlib_getModuleNameById($mod_arr[0]);
    if (vtlib_isModuleActive($module)) {
        $field_id = "";
        if (isset($mod_arr[1]) && $mod_arr[1] != "") {
            $field_id = $mod_arr[1];
        }
        if (!in_array($field_id, ITS4YouReports::$customRelationTypes)) {
            $ogReport->oCustomView = new CustomView();
            $result = $ogReport->getStdCriteriaByModule($module);
            $mod_strings = return_module_language($current_language, $module);
            if (isset($result)) {
                foreach ($result as $key => $value) {
                    $option_val_arr = explode(":", $key);
                    $tablename = $option_val_arr[0];
                    if ($field_id != "") {
                        // $ogReport->ui10_related_modules
                        $field_ui_type = "";
                        $field_uitype_sql = "SELECT uitype FROM vtiger_field WHERE fieldid = ?";
                        $field_uitype_result = $adb->pquery($field_uitype_sql, array($field_id));
                        if (($field_uitype_result) && $adb->num_rows($field_uitype_result) > 0) {
                            $field_uitype_row = $adb->fetchByAssoc($field_uitype_result);
                            $field_ui_type = $field_uitype_row["uitype"];
                        }
                        $tablename .= "_" . $field_id;
                        $field_id_str = $field_id;
                        if ($field_ui_type == "10") {
                            $field_id_str = "$sec_module_id:$field_id_str";
                        }
                        $option_val_arr[] = $field_id_str;
                    }
                    $option_val_arr[0] = $tablename;
                    $option_str = implode(":", $option_val_arr);
                    if (isset($mod_strings[$value])) {
                        $Options[] = array("value" => $option_str, 'text' => $modulename . " - " . vtranslate($value, $module));
                    } else {
                        $Options[] = array("value" => $option_str, 'text' => $modulename . " - " . $value);
                    }
                }
            }
        }
    }
    return $Options;
}

// TIMELINE Columns START
function getPrimaryTLStdFilter($module, $ogReport = "") {
    global $app_list_strings;
    if ($ogReport == "") {
        if (ITS4YouReports::isStoredITS4YouReport() === true) {
            $ogReport = ITS4YouReports::getStoredITS4YouReport();
        } else {
            $ogReport = new ITS4YouReports();
        }
    }
    global $current_language;
    $Options = array();
    if (vtlib_isModuleActive($module)) {
        /*
          $ogReport->oCustomView = new CustomView();
          $result = $ogReport->oCustomView->getStdCriteriaByModule($module);
         */
        $result = getR4UStdCriteriaByModule($module);

        //$mod_strings = return_module_language($current_language, $module);

        if (isset($result)) {
            foreach ($result as $key => $value) {
                /* $fieldid = get_field_id($key) */
                $fieldid = "";
                $Options[vtranslate($module, $module)][] = array("value" => $key . "$fieldid", 'text' => $value . " - " . vtranslate($value, $secmodule[$i]));
            }
        }
    }

    return $Options;
}

function getSecondaryTLStdFilter($moduleid, $Options, $ogReport = "") {
    global $app_list_strings;
    if ($ogReport != "") {
        if (ITS4YouReports::isStoredITS4YouReport() === true) {
            $ogReport = ITS4YouReports::getStoredITS4YouReport();
        } else {
            $ogReport = new ITS4YouReports($recordid);
        }
    }
    global $current_language;

    $adb = PEARDatabase::getInstance();
    $module_arr = explode("x", $moduleid);
    $module = vtlib_getModuleNameById($module_arr[0]);
    $module_lbl = vtranslate($module, $module);
    $fieldlabel = "";
    if (isset($module_arr[1]) && !empty($module_arr[1]) && is_numeric($module_arr[1])) {
        $field_sql = "SELECT fieldlabel FROM vtiger_field WHERE fieldid=?";
        $field_result = $adb->pquery($field_sql, array($module_arr[1]));
        $field_row = $adb->fetchByAssoc($field_result, 0);
        $fieldlabel = $field_row["fieldlabel"];
        if ($fieldlabel != "") {
            if (vtlib_isModuleActive($module)) {
                $fieldlabel = vtranslate($fieldlabel, $module);
            } else {
                $fieldlabel = vtranslate($fieldlabel);
            }
        }
    }
    if ($fieldlabel != "") {
        $optgroup_key .= "$fieldlabel ($module_lbl)";
    } else {
        $optgroup_key .= $module_lbl;
    }

    if (vtlib_isModuleActive($module)) {
        $ogReport->oCustomView = new CustomView();
        if ($module != "") {
            $secmodule = explode(":", $module);
            $module_sarr = explode("x", $moduleid);
            if (isset($module_sarr[1]) && !empty($module_sarr[1])) {
                $fieldid = ":" . $module_sarr[1];
            }
            for ($i = 0; $i < count($secmodule); $i++) {
                $result = $ogReport->getStdCriteriaByModule($secmodule[$i]);
                $mod_strings = return_module_language($current_language, $secmodule[$i]);

                if (isset($result)) {
                    foreach ($result as $key => $value) {
                        //$fieldid = get_field_id($key);
                        if (isset($mod_strings[$value])) {
                            $Options[$optgroup_key][] = array("value" => $key . "$fieldid", 'text' => vtranslate($value, $secmodule[$i]));
                        } else {
                            $Options[vtranslate($secmodule[$i], $secmodule[$i])][] = array("value" => $key . "$fieldid", 'text' => $value);
                        }
                    }
                }
            }
        }
    }
    return $Options;
}

// TIMELINE Columns END
function sgetColumnstoTotalObjectsOptions($Options, $module) {
    $relmod_arr = explode("x", $module);
    if (is_numeric($relmod_arr[0])) {
        $tabid = $relmod_arr[0];
        $module = vtlib_getModuleNameById($tabid);
        $r_fieldid = (isset($relmod_arr[1]) && $relmod_arr[1] != "" ? $relmod_arr[1] : "");
    } else {
        $tabid = getTabid($relmod_arr[0]);
        $r_fieldid = "";
    }
    //retreive the vtiger_tabid	
    $adb = PearDatabase::getInstance();
    global $current_user;
    $user_privileges_path = 'user_privileges/user_privileges_' . $current_user->id . '.php';
    if (file_exists($user_privileges_path)) {
        require($user_privileges_path);
    }

    $result = self::getColumnsTotalRow($tabid);

    $columntototalrow = $adb->fetch_array($result);

    $Options = "";
    if ($adb->num_rows($result) > 0) {
        do {
            $typeofdata = explode("~", $columntototalrow["typeofdata"]);
            //if ($typeofdata[0] == "N" || $typeofdata[0] == "I" || $typeofdata[0] == "NN") {
            //vtiger_crmentity:crmid:Accounts_ID:crmid:I
            $optionvalue = $columntototalrow['tablename'] . ":" . $columntototalrow['columnname'] . ":" . $module . "_" . $columntototalrow['fieldlabel'] . ":" . $columntototalrow['fieldname'];

            $optgroup = vtranslate($columntototalrow['tablabel'], $columntototalrow['tablabel']) . ' - ' . vtranslate($columntototalrow['fieldlabel'], $columntototalrow['tablabel']);
            $fieldidstr = ($r_fieldid != "" ? ":" . $r_fieldid : "");
            $Options[$optgroup][] = array("value" => $optionvalue . ':SUM' . $fieldidstr, 'text' => $columntototalrow['fieldlabel'] . " (SUM)");
            $Options[$optgroup][] = array("value" => $optionvalue . ':AVG' . $fieldidstr, 'text' => $columntototalrow['fieldlabel'] . " (AVG)");
            $Options[$optgroup][] = array("value" => $optionvalue . ':MIN' . $fieldidstr, 'text' => $columntototalrow['fieldlabel'] . " (MIN)");
            $Options[$optgroup][] = array("value" => $optionvalue . ':MAX' . $fieldidstr, 'text' => $columntototalrow['fieldlabel'] . " (MAX)");
            $Options[$optgroup][] = array("value" => $optionvalue . ':COUNT' . $fieldidstr, 'text' => $columntototalrow['fieldlabel'] . " (COUNT)");
            //}
        } while ($columntototalrow = $adb->fetch_array($result));
    }

    if (in_array($module, self::$inventory_modules)) {
        $fieldtablename = 'vtiger_inventoryproductrel' . $tabid;
        $fields = array('listprice' => vtranslate('List Price', $module),
            'quantity' => vtranslate('Quantity', $module),
            'ps_producttotal' => $module_lbl . " " . vtranslate('LBL_PRODUCT_TOTAL', $this->currentModule),
            'discount' => vtranslate('Discount', $module),
            'ps_productstotalafterdiscount' => $module_lbl . " " . vtranslate('LBL_PRODUCTTOTALAFTERDISCOUNT', $this->currentModule),
            'ps_productvatsum' => $module_lbl . " " . vtranslate('LBL_PRODUCT_VAT_SUM', $this->currentModule),
            'ps_producttotalsum' => $module_lbl . " " . vtranslate('LBL_PRODUCT_TOTAL_VAT', $this->currentModule),
        );
        $fields_datatype = array('listprice' => 'I',
            'quantity' => 'I',
            'ps_producttotal' => 'I',
            'discount' => 'I',
            'ps_productstotalafterdiscount' => 'I',
            'ps_productvatsum' => 'I',
            'ps_producttotalsum' => 'I',
        );
        foreach ($fields as $fieldcolname => $label) {
            $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $label . ":" . $fieldcolname;

            $optgroup = vtranslate($module, $module) . ' ' . vtranslate("Product", $module) . ' / ' . vtranslate("Service", $module) . ' - ' . $label;
            $Options[$optgroup][] = array("value" => $optionvalue . ':SUM', 'text' => $label . " (SUM)");
            $Options[$optgroup][] = array("value" => $optionvalue . ':AVG', 'text' => $label . " (AVG)");
            $Options[$optgroup][] = array("value" => $optionvalue . ':MIN', 'text' => $label . " (MIN)");
            $Options[$optgroup][] = array("value" => $optionvalue . ':MAX', 'text' => $label . " (MAX)");
            $Options[$optgroup][] = array("value" => $optionvalue . ':COUNT', 'text' => $label . " (COUNT)");
        }
    }
    return $Options;
}

// ITS4YOU-END FUNTIONS 
// ITS4YOU-CR SlOl 5. 3. 2014 15:48:43
// ITS4YOU-END SUMMARIES FIELDS START
function sgetSummariesHTMLOptions($moduleid, $primarymoduleid = "") {
    //retreive the vtiger_tabid	
    global $current_user;
    $relmod_arr = explode("x", $moduleid);
    if (is_numeric($relmod_arr[0])) {
        $tabid = $relmod_arr[0];
        $module = vtlib_getModuleNameById($tabid);
        $fieldid = $relmod_arr[1];
        $fieldidstr = (isset($fieldid) && $fieldid != "" ? ":" . $fieldid : "");
    } else {
        $tabid = getTabid($relmod_arr[0]);
        $fieldid = $fieldidstr = "";
    }
    //$module_lbl = vtranslate(vtlib_getModuleNameById($tabid), vtlib_getModuleNameById($tabid));
    $adb = PearDatabase::getInstance();
    $user_privileges_path = 'user_privileges/user_privileges_' . $current_user->id . '.php';
    if (file_exists($user_privileges_path)) {
        require($user_privileges_path);
    }
//global $adb;$adb->setDebug(true);        
    $result = ITS4YouReports::getColumnsTotalRow($tabid);
//$adb->setDebug(false);
    global $default_charset;

    $options = "";
    // ITS4YOU-CR SlOl 7. 3. 2014 11:05:30 
    /* $option_details = explode(":", $optionvalue_count);
      $option_lbl_arr = explode("_", $option_details[2], 2);
      $calculation_type = $option_details[4];
      $count_module_lbl = $count_module_lbl = "";
      if (vtlib_isModuleActive($option_lbl_arr[0])) {
      $count_module = $option_lbl_arr[0];
      $module_lbl = vtranslate($count_module, $count_module);
      $count_module_lbl = " " . vtranslate("LBL_OF", $currentModule) . " " . $module_lbl;
      }
      if (!isset($options[vtranslate("COUNT_GROUP", $currentModule)])) {
      $options .= '<option value="' . $optionvalue_count . ':COUNT' . $fieldidstr . '">COUNT ' . vtranslate("LBL_RECORDS") . $count_module_lbl . '</option>';
      } */
    // ITS4YOU-END 7. 3. 2014 11:05:37
    $currentModuleName = "ITS4YouReports";
    // ITS4YOU-CR SlOl 7. 3. 2014 11:05:30 
    if (!isset($options[vtranslate("COUNT_GROUP", $currentModuleName)])) {
        if (vtlib_isModuleActive($module)) {
            $c_focus = CRMEntity::getInstance($module);
            // $optionvalue_count = $columntototalrow['tablename'] . ":" . $columntototalrow['columnname'] . ":" . $module . "_" . str_replace(" ", "_", $columntototalrow['fieldlabel']) . ":" . $columntototalrow['fieldname'];
            $optionvalue_count = "vtiger_crmentity:crmid:" . $module . "_" . 'LBL_RECORDS' . $count_module_lbl . ":" . $module . "_count";
            $option_details = explode(":", $optionvalue_count);
            $option_lbl_arr = explode("_", $option_details[2], 2);

            $calculation_type = $option_details[4];
            $count_module_lbl = "";
            $count_module = $option_lbl_arr[0];
            $count_module_lbl = " " . vtranslate("LBL_OF", $currentModuleName) . " " . vtranslate($count_module, $count_module);
        }
        $options .= '<option value="' . $optionvalue_count . ':V:COUNT' . $fieldidstr . '">' . vtranslate("LBL_COUNT", $currentModuleName) . ' ' . vtranslate("LBL_RECORDS", $currentModuleName) . $count_module_lbl . '</option>';
    }
    // ITS4YOU-END 7. 3. 2014 11:05:37

    if ($adb->num_rows($result) > 0) {
        do {
            $typeofdata = explode("~", $columntototalrow["typeofdata"]);

            if ($columntototalrow['columnname'] != "") {
                //if ($typeofdata[0] == "N" || $typeofdata[0] == "I" || $typeofdata[0] == "NN") {
                //vtiger_crmentity:crmid:Accounts_ID:crmid:I
                $typeofdata_val = ":" . $typeofdata[0];
                $optionvalue = $columntototalrow['tablename'] . ":" . $columntototalrow['columnname'] . ":" . $module . "_" . $columntototalrow['fieldlabel'] . ":" . $columntototalrow['fieldname'] . $typeofdata_val;
                $optionvalue = str_replace("&", "@AMPKO@", html_entity_decode($optionvalue,ENT_QUOTES,$default_charset));

                //$options .= '<optgroup label="' . $module_lbl . " - " . $columntototalrow['fieldlabel'] . '">';
                $options .= '<optgroup label=" - ' . vtranslate($columntototalrow['fieldlabel'],$module) . '">';
                
                // $fieldidstr = ($columntototalrow['fieldid'] != "" ? ":" . $columntototalrow['fieldid'] : "");
                $options .= '<option value="' . $optionvalue . ':SUM' . $fieldidstr . '">'.vtranslate("SUM", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . vtranslate($columntototalrow['fieldlabel'],$module) . '</option>';
                $options .= '<option value="' . $optionvalue . ':AVG' . $fieldidstr . '">'.vtranslate("AVG", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . vtranslate($columntototalrow['fieldlabel'],$module) . '</option>';
                $options .= '<option value="' . $optionvalue . ':MIN' . $fieldidstr . '">'.vtranslate("MIN", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . vtranslate($columntototalrow['fieldlabel'],$module) . '</option>';
                $options .= '<option value="' . $optionvalue . ':MAX' . $fieldidstr . '">'.vtranslate("MAX", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . vtranslate($columntototalrow['fieldlabel'],$module) . '</option>';
                $options .= '</optgroup>';
                //}
            }
        } while ($columntototalrow = $adb->fetch_array($result));
    }

    if (in_array($module, ITS4YouReports::$inventory_modules)) {
        /*$inventorytab_tabid = "";
        if ($tabid != $primarymoduleid) {
            $inventorytab_tabid = $tabid;
        }*/
        $fieldtablename = 'vtiger_inventoryproductrel' . $fieldid;
        $fields = array('listprice' => vtranslate('List Price', $module),
            'quantity' => vtranslate('Quantity', $module),
            'ps_producttotal' => $module_lbl . " " . vtranslate('LBL_PRODUCT_TOTAL', $currentModuleName),
            'discount' => vtranslate('Discount', $module),
            'ps_productstotalafterdiscount' => $module_lbl . " " . vtranslate('LBL_PRODUCTTOTALAFTERDISCOUNT', $currentModuleName),
            'ps_productvatsum' => $module_lbl . " " . vtranslate('LBL_PRODUCT_VAT_SUM', $currentModuleName),
            'ps_producttotalsum' => $module_lbl . " " . vtranslate('LBL_PRODUCT_TOTAL_VAT', $currentModuleName),
        );
        $fields_datatype = array('listprice' => 'I',
            'quantity' => 'I',
            'ps_producttotal' => 'I',
            'discount' => 'I',
            'ps_productstotalafterdiscount' => 'I',
            'ps_productvatsum' => 'I',
            'ps_producttotalsum' => 'I',
        );
        foreach ($fields as $fieldcolname => $label) {
            $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $label . ":" . $fieldcolname . ":I".$fieldidstr;
            //$options .= '<optgroup label="' . vtranslate($module, $module) . ' ' . vtranslate("Product", $module) . ' / ' . vtranslate("Service", $module) . ' - ' . $label . '">';
            //$options .= '<optgroup label="' . vtranslate($module, $module) . ' - ' . $label . '">';
            $options .= '<optgroup label="' . $module_lbl . ' - ' . $label . '">';
            $options .= '<option value="' . $optionvalue . ':SUM">'.vtranslate("SUM", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . $label . '</option>';
            $options .= '<option value="' . $optionvalue . ':AVG">'.vtranslate("AVG", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . $label . '</option>';
            $options .= '<option value="' . $optionvalue . ':MIN">'.vtranslate("MIN", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . $label . '</option>';
            $options .= '<option value="' . $optionvalue . ':MAX">'.vtranslate("MAX", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . $label . '</option>';
            // $options .= '<option value="'.$optionvalue.':COUNT">COUNT '.$label.'</option>';
            $options .= '</optgroup>';
        }
    }
    return $options;
}

function sgetSelectedSummariesOptions($options_array) {
    $options = array();
    if (!empty($options_array)) {
        foreach ($options_array as $key => $option_string) {
            if ($option_string != "") {
                $option_string_dc = str_replace("@AMPKO@", "&", $option_string);

                $option_details = explode(":", $option_string_dc);
                $option_lbl_arr = explode("_", $option_details[2], 2);

                if (is_numeric($option_details[5]) || in_array($option_details[5], ITS4YouReports::$customRelationTypes)) {
                    $calculation_type = $option_details[6];
                } else {
                    $calculation_type = $option_details[5];
                }

                $count_module_lbl = "";
                if (vtlib_isModuleActive($option_lbl_arr[0])) {
                    $count_module = $option_lbl_arr[0];
                    $module_lbl = vtranslate($count_module, $count_module);
                    //$count_module_lbl = " " . vtranslate("LBL_OF", "ITS4YouReports") . " " . $module_lbl;
                    $count_module_lbl = " (" . $module_lbl. ")";
                }
                if ($calculation_type == "COUNT") {
                    $fieldlabel = vtranslate("LBL_RECORDS", "ITS4YouReports") . $count_module_lbl;
                } else {
                    if (vtlib_isModuleActive($option_lbl_arr[0])) {
                        $fieldlabel = vtranslate($option_lbl_arr[1], $option_lbl_arr[0]);
                    } else {
                        $fieldlabel = vtranslate($option_lbl_arr[1]);
                    }
                    $fieldlabel .= " ($module_lbl)";
                }
                $options[] = array("value" => $option_string, "text" => vtranslate($calculation_type, "ITS4YouReports").' ' . vtranslate("LBL_OF", "ITS4YouReports") . " " . $fieldlabel);
            }
        }
    }
    return $options;
}

function sgetSelectedSummariesHTMLOptions($options_array, $summaries_orderby = "") {
    $options_arr = sgetSelectedSummariesOptions($options_array);
    $options_html = "";
    foreach ($options_arr as $key => $option_arr) {
        $selected_option = "";
        if ($option_arr["value"] == $summaries_orderby) {
            $selected_option = " selected ";
        }
        $options_html .= '<option value="' . $option_arr["value"] . '" ' . $selected_option . ' >' . $option_arr["text"] . '</option>';
    }
    return $options_html;
}

function sgetSummariesOptions($module) {
    $options = array();
    if ($module != "") {
        global $current_user;
        $adb = PearDatabase::getInstance();
        $user_privileges_path = 'user_privileges/user_privileges_' . $current_user->id . '.php';
        if (file_exists($user_privileges_path)) {
            require($user_privileges_path);
        }

        $relmod_arr = explode("x", $module);
        if (is_numeric($relmod_arr[0])) {
            $tabid = $relmod_arr[0];
            $module = vtlib_getModuleNameById($tabid);
            $fieldid = $relmod_arr[1];
            $fieldidstr = (isset($fieldid) && $fieldid != "" ? $fieldid : "");
        } else {
            $tabid = getTabid($relmod_arr[0]);
            $fieldid = $fieldidstr = "";
        }
        $fieldidstr = "";
        if ($fieldid != "") {
            $fieldidstr = ":$fieldid";
        }

        $result = ITS4YouReports::getColumnsTotalRow($tabid);
        
        global $default_charset;
        
        $currentModuleName = "ITS4YouReports";

        $options = array();
        // ITS4YOU-CR SlOl 7. 3. 2014 11:05:30 
        if (!isset($options[vtranslate("COUNT_GROUP", $currentModuleName)])) {
            if (vtlib_isModuleActive($module)) {
                $c_focus = CRMEntity::getInstance($module);
                // $optionvalue_count = $columntototalrow['tablename'] . ":" . $columntototalrow['columnname'] . ":" . $module . "_" . str_replace(" ", "_", $columntototalrow['fieldlabel']) . ":" . $columntototalrow['fieldname'];
                $optionvalue_count = "vtiger_crmentity:crmid:" . $module . "_" . 'LBL_RECORDS' . $count_module_lbl . ":" . $module . "_count";
                $option_details = explode(":", $optionvalue_count);
                $option_lbl_arr = explode("_", $option_details[2], 2);

                $calculation_type = $option_details[4];
                $count_module_lbl = "";
                $count_module = $option_lbl_arr[0];
                $count_module_lbl = " " . vtranslate("LBL_OF", $currentModuleName) . " " . vtranslate($count_module, $count_module);
            }
            $options[vtranslate("COUNT_GROUP", $currentModuleName)][] = array("value" => $optionvalue_count . ':V'.$fieldidstr.':COUNT' , "text" => vtranslate("LBL_COUNT", $currentModuleName) . ' ' . vtranslate("LBL_RECORDS", $currentModuleName) . $count_module_lbl);
        }
        // ITS4YOU-END 7. 3. 2014 11:05:37
        if ($adb->num_rows($result) > 0) {
            do {
                $typeofdata = explode("~", $columntototalrow["typeofdata"]);

                global $current_user;
                
                $typeofdata_val = ":" . $typeofdata[0];
                
                $optionvalue = $columntototalrow['tablename'] . ":" . $columntototalrow['columnname'] . ":" . $module . "_" . $columntototalrow['fieldlabel'] . ":" . $columntototalrow['fieldname'] . $typeofdata_val . $fieldidstr;
                $optionvalue = str_replace("&", "@AMPKO@", html_entity_decode($optionvalue,ENT_QUOTES,$default_charset));

                $group_key = vtranslate($columntototalrow['fieldlabel'], $columntototalrow['tablabel']);
                $options[$group_key][] = array("value" => $optionvalue . ':SUM', "text" => vtranslate("SUM", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . vtranslate($columntototalrow['fieldlabel'],$columntototalrow['tablabel']));
                $options[$group_key][] = array("value" => $optionvalue . ':AVG', "text" => vtranslate("AVG", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . vtranslate($columntototalrow['fieldlabel'],$columntototalrow['tablabel']));
                $options[$group_key][] = array("value" => $optionvalue . ':MIN', "text" => vtranslate("MIN", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . vtranslate($columntototalrow['fieldlabel'],$columntototalrow['tablabel']));
                $options[$group_key][] = array("value" => $optionvalue . ':MAX', "text" => vtranslate("MAX", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . vtranslate($columntototalrow['fieldlabel'],$columntototalrow['tablabel']));
                //}
            } while ($columntototalrow = $adb->fetch_array($result));
        }

        if (in_array($module, ITS4YouReports::$inventory_modules)) {

            $fieldtablename = 'vtiger_inventoryproductrel' . $fieldid;
            $fields = array('listprice' => vtranslate('List Price', $module),
                'quantity' => vtranslate('Quantity', $module),
                'ps_producttotal' => $module_lbl . " " . vtranslate('LBL_PRODUCT_TOTAL', $currentModuleName),
                'discount' => vtranslate('Discount', $module),
                'ps_productstotalafterdiscount' => $module_lbl . " " . vtranslate('LBL_PRODUCTTOTALAFTERDISCOUNT', $currentModuleName),
                'ps_productvatsum' => $module_lbl . " " . vtranslate('LBL_PRODUCT_VAT_SUM', $currentModuleName),
                'ps_producttotalsum' => $module_lbl . " " . vtranslate('LBL_PRODUCT_TOTAL_VAT', $currentModuleName),
            );
            $fields_datatype = array('listprice' => 'I',
                'quantity' => 'I',
                'ps_producttotal' => 'I',
                'discount' => 'I',
                'ps_productstotalafterdiscount' => 'I',
                'ps_productvatsum' => 'I',
                'ps_producttotalsum' => 'I',
            );
            foreach ($fields as $fieldcolname => $label) {
                $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $label . ":" . $fieldcolname . ":I".$fieldidstr;

                $group_key = vtranslate($module, $module) . ' ' . vtranslate("Product", $module) . ' / ' . vtranslate("Service", $module) . ' - ' . $label;
                $options[$group_key][] = array("value" => $optionvalue . ':SUM' , "text" => vtranslate("SUM", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . $label);
                $options[$group_key][] = array("value" => $optionvalue . ':AVG' , "text" => vtranslate("AVG", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . $label);
                $options[$group_key][] = array("value" => $optionvalue . ':MIN' , "text" => vtranslate("MIN", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . $label);
                $options[$group_key][] = array("value" => $optionvalue . ':MAX' , "text" => vtranslate("MAX", $currentModuleName).' ' . vtranslate("LBL_OF", $currentModuleName) . " " . $label);
                // $options[$group_key][] = array("value"=>$optionvalue.':COUNT'.$fieldidstr,"text"=>'COUNT '.$label);
            }
        }
    }
    return $options;
}

// ITS4YOU-END SUMMARIES FIELDS END
// ITS4YOU-CR SlOl | 13.5.2014 13:22 
function getR4UMeta($module, $user) {
    $db = PearDatabase::getInstance();
    if (empty($moduleMetaInfo[$module])) {
        $handler = vtws_getModuleHandlerFromName($module, $user);
        $meta = $handler->getMeta();
        $moduleMetaInfo[$module] = $meta;
    }
    return $moduleMetaInfo[$module];
}

function getR4UColumnsListbyBlock($module, $block) {
    global $mod_strings, $app_strings;
    $block_ids = explode(",", $block);
    $tabid = getTabid($module);
    $adb = PearDatabase::getInstance();

    global $current_user;
    $user_privileges_path = 'user_privileges/user_privileges_' . $current_user->id . '.php';
    if (file_exists($user_privileges_path)) {
        require($user_privileges_path);
    }

    if (empty($meta) && $module != 'Calendar') {
        $meta = getR4UMeta($module, $current_user);
    }

    if ($tabid == 9)
        $tabid = "9,16";
    $display_type = " vtiger_field.displaytype in (1,2,3)";

    if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
        $tab_ids = explode(",", $tabid);
        $sql = "select * from vtiger_field ";
        $sql.= " where vtiger_field.tabid in (" . generateQuestionMarks($tab_ids) . ") and vtiger_field.block in (" . generateQuestionMarks($block_ids) . ") and vtiger_field.presence in (0,2) and";
        $sql.= $display_type;
        if ($tabid == 9 || $tabid == 16) {
            $sql.= " and vtiger_field.fieldname not in('notime','duration_minutes','duration_hours')";
        }
        $sql.= " order by sequence";
        $params = array($tab_ids, $block_ids);
    } else {
        $tab_ids = explode(",", $tabid);
        $profileList = getCurrentUserProfileList();
        $sql = "select * from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid ";
        $sql.= " where vtiger_field.tabid in (" . generateQuestionMarks($tab_ids) . ") and vtiger_field.block in (" . generateQuestionMarks($block_ids) . ") and";
        $sql.= "$display_type and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_field.presence in (0,2)";

        $params = array($tab_ids, $block_ids);

        if (count($profileList) > 0) {
            $sql.= "  and vtiger_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
            array_push($params, $profileList);
        }
        if ($tabid == 9 || $tabid == 16) {
            $sql.= " and vtiger_field.fieldname not in('notime','duration_minutes','duration_hours')";
        }

        $sql.= " group by columnname order by sequence";
    }
    if ($tabid == '9,16')
        $tabid = "9";
    $result = $adb->pquery($sql, $params);
    $noofrows = $adb->num_rows($result);
    //Added on 14-10-2005 -- added ticket id in list
    if ($module == 'HelpDesk' && $block == 25) {
        $module_columnlist['vtiger_crmentity:crmid::HelpDesk_Ticket_ID:I'] = 'Ticket ID';
    }
    //Added to include vtiger_activity type in vtiger_activity vtiger_customview list
    if ($module == 'Calendar' && $block == 19) {
        $module_columnlist['vtiger_activity:activitytype:activitytype:Calendar_Activity_Type:V'] = 'Activity Type';
    }

    if ($module == 'SalesOrder' && $block == 63)
        $module_columnlist['vtiger_crmentity:crmid::SalesOrder_Order_No:I'] = vtranslate('Order No');

    if ($module == 'PurchaseOrder' && $block == 57)
        $module_columnlist['vtiger_crmentity:crmid::PurchaseOrder_Order_No:I'] = vtranslate('Order No');

    if ($module == 'Quotes' && $block == 51)
        $module_columnlist['vtiger_crmentity:crmid::Quotes_Quote_No:I'] = vtranslate('Quote No');
    if ($module != 'Calendar') {
        $moduleFieldList = $meta->getModuleFields();
    }
    for ($i = 0; $i < $noofrows; $i++) {
        $fieldtablename = $adb->query_result($result, $i, "tablename");
        $fieldcolname = $adb->query_result($result, $i, "columnname");
        $fieldname = $adb->query_result($result, $i, "fieldname");
        $fieldtype = $adb->query_result($result, $i, "typeofdata");
        $fieldtype = explode("~", $fieldtype);
        $fieldtypeofdata = $fieldtype[0];
        $fieldlabel = $adb->query_result($result, $i, "fieldlabel");
        $field = $moduleFieldList[$fieldname];

        if (!empty($field) && $field->getFieldDataType() == 'reference') {
            $fieldtypeofdata = 'V';
        } else {
            //Here we Changing the displaytype of the field. So that its criteria will be
            //displayed Correctly in Custom view Advance Filter.
            $fieldtypeofdata = ChangeTypeOfData_Filter($fieldtablename, $fieldcolname, $fieldtypeofdata);
        }
        if ($fieldlabel == "Related To") {
            $fieldlabel = "Related to";
        }
        if ($fieldlabel == "Start Date & Time") {
            $fieldlabel = "Start Date";
            if ($module == 'Calendar' && $block == 19)
                $module_columnlist['vtiger_activity:time_start::Calendar_Start_Time:I'] = 'Start Time';
        }
        $fieldlabel1 = $fieldlabel;
        $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $fieldname . ":" . $module . "_" .
                $fieldlabel1 . ":" . $fieldtypeofdata;
        //added to escape attachments fields in customview as we have multiple attachments
        $fieldlabel = vtranslate($fieldlabel); //added to support i18n issue
        if ($module != 'HelpDesk' || $fieldname != 'filename')
            $module_columnlist[$optionvalue] = $fieldlabel;
        if ($fieldtype[1] == "M") {
            $mandatoryvalues[] = "'" . $optionvalue . "'";
            $showvalues[] = $fieldlabel;
            $data_type[$fieldlabel] = $fieldtype[1];
        }
    }
    return $module_columnlist;
}

function getR4UModuleColumnsList($module) {

    $module_info = getR4UCustomViewModuleInfo($module);
    foreach ($module_info[$module] as $key => $value) {
        $columnlist = getR4UColumnsListbyBlock($module, $value);

        if (isset($columnlist)) {
            $ret_module_list[$module][$key] = $columnlist;
        }
    }
    return $ret_module_list;
}

function getR4UStdCriteriaByModule($module) {
    $r4u_stdcriteria_name = ITS4YouReports::getITS4YouReportStoreName("stdcriteria");
    if ($r4u_stdcriteria_name != "" && isset($_SESSION[$r4u_stdcriteria_name]) && !empty($_SESSION[$r4u_stdcriteria_name])) {
        $stdcriteria_list = $_SESSION[$r4u_stdcriteria_name];
    } else {
        $adb = PearDatabase::getInstance();
        $tabid = getTabid($module);

        global $current_user;
        $user_privileges_path = 'user_privileges/user_privileges_' . $current_user->id . '.php';
        if (file_exists($user_privileges_path)) {
            require($user_privileges_path);
        }

        $module_info = getR4UCustomViewModuleInfo($module);
        $module_list = getR4UModuleColumnsList($module);
        foreach ($module_info[$module] as $key => $blockid) {
            $blockids[] = $blockid;
        }

        if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
            $sql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid ";
            $sql.= " where vtiger_field.tabid=? and vtiger_field.block in (" . generateQuestionMarks($blockids) . ")
	                and vtiger_field.uitype in (5,6,23,70)";
            $sql.= " and vtiger_field.presence in (0,2) order by vtiger_field.sequence";
            $params = array($tabid, $blockids);
        } else {
            $profileList = getCurrentUserProfileList();
            $sql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid inner join  vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid ";
            $sql.= " where vtiger_field.tabid=? and vtiger_field.block in (" . generateQuestionMarks($blockids) . ") and vtiger_field.uitype in (5,6,23,70)";
            $sql.= " and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_field.presence in (0,2)";

            $params = array($tabid, $blockids);

            if (count($profileList) > 0) {
                $sql.= " and vtiger_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
                array_push($params, $profileList);
            }

            $sql.= " order by vtiger_field.sequence";
        }
        $result = $adb->pquery($sql, $params);

        while ($criteriatyperow = $adb->fetch_array($result)) {
            $fieldtablename = $criteriatyperow["tablename"];
            $fieldcolname = $criteriatyperow["columnname"];
            $fieldfieldname = $criteriatyperow["fieldname"];
            $fieldlabel = $criteriatyperow["fieldlabel"];
            $fieldname = $criteriatyperow["fieldname"]; // oldoldo
            $typeofdata_val = "";
            $typeofdata = explode("~", $criteriatyperow["typeofdata"]);
            $typeofdata_val = ":" . $typeofdata[0];
            $fieldlabel1 = $fieldlabel;
            // old $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $fieldname . ":" . $module . "_" . $fieldlabel1;
            $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $fieldlabel1 . ":" . $fieldfieldname . $typeofdata_val;
            // vtiger_potential:closingdate:closingdate:Potentials_Expected_Close_Date
            $stdcriteria_list[$optionvalue] = $fieldlabel;
        }
        $_SESSION[$r4u_stdcriteria_name] = $stdcriteria_list;
    }
    return $stdcriteria_list;
}

function getR4UCustomViewModuleInfo($module) {
    $adb = PearDatabase::getInstance();
    global $current_language;
    if ($module == "Events") {
        $current_mod_strings = return_specified_module_language($current_language, "Calendar");
    } else {
        $current_mod_strings = return_specified_module_language($current_language, $module);
    }
    $block_info = Array();
    $modules_list = explode(",", $module);
    if (in_array($module, array("Calendar", "Events"))) {
        $module = "Calendar','Events";
        $modules_list = array('Calendar', 'Events');
    }

    // Tabid mapped to the list of block labels to be skipped for that tab.
    $skipBlocksList = array(
        getTabid('Contacts') => array('LBL_IMAGE_INFORMATION'),
        getTabid('HelpDesk') => array('LBL_COMMENTS'),
        getTabid('Products') => array('LBL_IMAGE_INFORMATION'),
        getTabid('Faq') => array('LBL_COMMENT_INFORMATION'),
        getTabid('Quotes') => array('LBL_RELATED_PRODUCTS'),
        getTabid('PurchaseOrder') => array('LBL_RELATED_PRODUCTS'),
        getTabid('SalesOrder') => array('LBL_RELATED_PRODUCTS'),
        getTabid('Invoice') => array('LBL_RELATED_PRODUCTS')
    );

    $Sql = "select distinct block,vtiger_field.tabid,name,blocklabel from vtiger_field inner join vtiger_blocks on vtiger_blocks.blockid=vtiger_field.block inner join vtiger_tab on vtiger_tab.tabid=vtiger_field.tabid where displaytype != 3 and vtiger_tab.name in (" . generateQuestionMarks($modules_list) . ") and vtiger_field.presence in (0,2) order by block";
    $result = $adb->pquery($Sql, array($modules_list));
    if ($module == "Calendar','Events")
        $module = "Calendar";

    $pre_block_label = '';
    while ($block_result = $adb->fetch_array($result)) {
        $block_label = $block_result['blocklabel'];
        $tabid = $block_result['tabid'];
        // Skip certain blocks of certain modules
        if (array_key_exists($tabid, $skipBlocksList) && in_array($block_label, $skipBlocksList[$tabid]))
            continue;

        if (trim($block_label) == '') {
            $block_info[$pre_block_label] = $block_info[$pre_block_label] . "," . $block_result['block'];
        } else {
            //$lan_block_label = $current_mod_strings[$block_label];
            $lan_block_label = vtranslate($block_label,$module);
            if (isset($block_info[$lan_block_label]) && $block_info[$lan_block_label] != '') {
                $block_info[$lan_block_label] = $block_info[$lan_block_label] . "," . $block_result['block'];
            } else {
                $block_info[$lan_block_label] = $block_result['block'];
            }
        }
        $pre_block_label = $lan_block_label;
    }
    $module_list[$module] = $block_info;

    return $module_list;
}

/** Function to get visible criteria for a report
 *  This function accepts The reportid as an argument
 *  It returns a array of selected option of sharing along with other options

 */
function getVisibleCriteria($recordid = '') {
    global $mod_strings;
    global $app_strings;
    global $adb, $current_user;
    //print_r("i am here");die;
    $filter = array();
    $selcriteria = "";
    if ($recordid != '') {
        $result = $adb->pquery("SELECT sharingtype FROM its4you_reports4you 
								INNER JOIN its4you_reports4you_settings ON its4you_reports4you.reports4youid = its4you_reports4you_settings.reportid 
								WHERE reports4youid=?", array($recordid));
        $selcriteria = $adb->query_result($result, 0, "sharingtype");
    }
    if ($selcriteria == "") {
        $selcriteria = 'Public';
    }
    $filter_result = $adb->query("select * from its4you_reports4you_reportfilters");
    $numrows = $adb->num_rows($filter_result);
    for ($j = 0; $j < $numrows; $j++) {
        $filter_id = $adb->query_result($filter_result, $j, "filterid");
        $filtername = $adb->query_result($filter_result, $j, "name");
        $name = $filtername;
        if ($filtername == 'Private') {
            $FilterKey = 'Private';
            $FilterValue = vtranslate('PRIVATE_FILTER');
        } elseif ($filtername == 'Shared') {
            $FilterKey = 'Shared';
            $FilterValue = vtranslate('SHARE_FILTER');
        } else {
            $FilterKey = 'Public';
            $FilterValue = vtranslate('PUBLIC_FILTER');
        }
        if ($FilterKey == $selcriteria) {
            $shtml['value'] = $FilterKey;
            $shtml['text'] = $FilterValue;
            $shtml['selected'] = "selected";
        } else {
            $shtml['value'] = $FilterKey;
            $shtml['text'] = $FilterValue;
            $shtml['selected'] = "";
        }
        $filter[] = $shtml;
    }
    return $filter;
}

function getShareInfo($recordid = '') {
    global $adb;
    $member_query = $adb->pquery("SELECT its4you_reports4you_sharing.setype,vtiger_users.id,vtiger_users.user_name FROM its4you_reports4you_sharing INNER JOIN vtiger_users on vtiger_users.id = its4you_reports4you_sharing.shareid WHERE its4you_reports4you_sharing.setype='users' AND its4you_reports4you_sharing.reports4youid = ?", array($recordid));
    $noofrows = $adb->num_rows($member_query);
    if ($noofrows > 0) {
        for ($i = 0; $i < $noofrows; $i++) {
            $userid = $adb->query_result($member_query, $i, 'id');
            $username = $adb->query_result($member_query, $i, 'user_name');
            $setype = $adb->query_result($member_query, $i, 'setype');
            $member_data[] = Array('id' => $setype . "::" . $userid, 'name' => $setype . "::" . $username);
        }
    }

    $member_query = $adb->pquery("SELECT its4you_reports4you_sharing.setype,vtiger_groups.groupid,vtiger_groups.groupname FROM its4you_reports4you_sharing INNER JOIN vtiger_groups on vtiger_groups.groupid = its4you_reports4you_sharing.shareid WHERE its4you_reports4you_sharing.setype='groups' AND its4you_reports4you_sharing.reports4youid = ?", array($recordid));
    $noofrows = $adb->num_rows($member_query);
    if ($noofrows > 0) {
        for ($i = 0; $i < $noofrows; $i++) {
            $grpid = $adb->query_result($member_query, $i, 'groupid');
            $grpname = $adb->query_result($member_query, $i, 'groupname');
            $setype = $adb->query_result($member_query, $i, 'setype');
            $member_data[] = Array('id' => $setype . "::" . $grpid, 'name' => $setype . "::" . $grpname);
        }
    }
    return $member_data;
}

// ITS4YOU-END 13.5.2014 13:23 
?>