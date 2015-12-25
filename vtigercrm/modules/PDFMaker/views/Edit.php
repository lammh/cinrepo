<?php

/* * *******************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class PDFMaker_Edit_View extends Vtiger_Index_View {

    public $cu_language = ""; 
    private $ModuleFields = array();
    private $All_Related_Modules = array();
    
    public function preProcess(Vtiger_Request $request, $display = true) {
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
        $viewer->assign('QUALIFIED_MODULE', $moduleName);
        Vtiger_Basic_View::preProcess($request, false);
        $viewer = $this->getViewer($request);

        $moduleName = $request->getModule();
        if (!empty($moduleName)) {
            //$moduleModel = PDFMaker_PDFMaker_Model::getInstance($moduleName);
            $moduleModel = new PDFMaker_PDFMaker_Model('PDFMaker');
            $currentUser = Users_Record_Model::getCurrentUserModel();
            $userPrivilegesModel = Users_Privileges_Model::getInstanceById($currentUser->getId());
            $permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
            $viewer->assign('MODULE', $moduleName);

            if (!$permission) {
                $viewer->assign('MESSAGE', 'LBL_PERMISSION_DENIED');
                $viewer->view('OperationNotPermitted.tpl', $moduleName);
                exit;
            }

            $linkParams = array('MODULE' => $moduleName, 'ACTION' => $request->get('view'));
            $linkModels = $moduleModel->getSideBarLinks($linkParams);

            $viewer->assign('QUICK_LINKS', $linkModels);
        }

        $viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
        $viewer->assign('CURRENT_VIEW', $request->get('view'));
        if ($display) {
            $this->preProcessDisplay($request);
        }
    }
    
    public function process(Vtiger_Request $request) {
        PDFMaker_Debugger_Model::GetInstance()->Init();

        $PDFMaker = new PDFMaker_PDFMaker_Model();
        $viewer = $this->getViewer($request);
  
        if ($request->has('templateid') && !$request->isEmpty('templateid')) {
            $templateid = $request->get('templateid');
            $pdftemplateResult = $PDFMaker->GetEditViewData($templateid);

            $select_module = $pdftemplateResult["module"];
            $select_format = $pdftemplateResult["format"];
            $select_orientation = $pdftemplateResult["orientation"];
            $nameOfFile = $pdftemplateResult["file_name"];
            $is_portal = $pdftemplateResult["is_portal"];
            $is_listview = $pdftemplateResult["is_listview"];
            $is_active = $pdftemplateResult["is_active"];
            $is_default = $pdftemplateResult["is_default"];
            $order = $pdftemplateResult["order"];
            $owner = $pdftemplateResult["owner"];
            $sharingtype = $pdftemplateResult["sharingtype"];
            $sharingMemberArray = $PDFMaker->GetSharingMemberArray($templateid);
            $disp_header = $pdftemplateResult["disp_header"];
            $disp_footer = $pdftemplateResult["disp_footer"];
            
            if(!$pdftemplateResult["permissions"]["edit"])
                $PDFMaker->DieDuePermission();
            
        } else {

            $templateid = "";

            if ($request->has("return_module") && !$request->isEmpty("return_module"))
                $select_module = $request->get("return_module");
            else
                $select_module = "";

            $select_format = "A4";
            $select_orientation = "portrait";
            //$select_encoding = "utf-8";
            $nameOfFile = "";
            $is_portal = "0";
            $is_listview = "0";
            $is_active = "1";
            $is_default = "0";
            $order = "1";
            $owner = $cu_model->id;
            $sharingtype = "public";
            $sharingMemberArray = array();
            $disp_header = "3";
            $disp_footer = "7";
            
            $PDFMaker->CheckTemplatePermissions($select_module, $templateid);
        }

        if ($PDFMaker->GetVersionType() == "professional")
            $type = "professional";
        else
            $type = "basic";
        
        $viewer->assign("TYPE", $type);

        if ($request->has("isDuplicate") && $request->get("isDuplicate") == "true") {
            $viewer->assign("FILENAME", "");
            $viewer->assign("DUPLICATE_FILENAME", $pdftemplateResult["filename"]);
        }
        else
            $viewer->assign("FILENAME", $pdftemplateResult["filename"]);

        $viewer->assign("DESCRIPTION", $pdftemplateResult["description"]);

        if (!$request->has("isDuplicate") OR ($request->has("isDuplicate") && $request->get("isDuplicate") != "true"))
            $viewer->assign("SAVETEMPLATEID", $templateid);
        if ($templateid != "")
            $viewer->assign("EMODE", "edit");

        $viewer->assign("TEMPLATEID", $templateid);
        $viewer->assign("MODULENAME", vtranslate($select_module,$select_module));
        $viewer->assign("SELECTMODULE", $select_module);

        $viewer->assign("BODY", $pdftemplateResult["body"]);

        $cu_model = Users_Record_Model::getCurrentUserModel();
       
        $this->cu_language = $cu_model->get('language');
        
        $viewer->assign("THEME", $theme);
        $viewer->assign("IMAGE_PATH", $image_path);
        $app_strings_big = Vtiger_Language_Handler::getModuleStringsFromFile($this->cu_language);
        $app_strings = $app_strings_big['languageStrings'];
        $viewer->assign("APP", $app_strings);
        $viewer->assign("PARENTTAB", getParentTab());

        $modArr = $PDFMaker->GetAllModules();
        $Modulenames = $modArr[0];
        $ModuleIDS = $modArr[1];
        
        $viewer->assign("MODULENAMES", $Modulenames);

// ******************************************   Company and User information: **********************************


        $CUI_BLOCKS["Account"] = vtranslate("LBL_COMPANY_INFO",'PDFMaker');
        $CUI_BLOCKS["Assigned"] = vtranslate("LBL_USER_INFO",'PDFMaker');
        $CUI_BLOCKS["Logged"] = vtranslate("LBL_LOGGED_USER_INFO",'PDFMaker');
        $viewer->assign("CUI_BLOCKS", $CUI_BLOCKS);

        $adb = PearDatabase::getInstance();
        $sql = "SELECT * FROM vtiger_organizationdetails";
        $result = $adb->pquery($sql, array());

        $organization_logoname = decode_html($adb->query_result($result, 0, 'logoname'));
        $organization_header = decode_html($adb->query_result($result, 0, 'headername'));
        $organization_stamp_signature = $adb->query_result($result, 0, 'stamp_signature');

        global $site_URL;
        $path = $site_URL . "/test/logo/";

        if (isset($organization_logoname)) {
            $organization_logo_img = "<img src=\"" . $path . $organization_logoname . "\">";
            $viewer->assign("COMPANYLOGO", $organization_logo_img);
        }
        if (isset($organization_stamp_signature)) {
            $organization_stamp_signature_img = "<img src=\"" . $path . $organization_stamp_signature . "\">";
            $viewer->assign("COMPANY_STAMP_SIGNATURE", $organization_stamp_signature_img);
        }
        if (isset($organization_header)) {
            $organization_header_img = "<img src=\"" . $path . $organization_header . "\">";
            $viewer->assign("COMPANY_HEADER_SIGNATURE", $organization_header_img);
        }

        $Acc_Info = array('' => vtranslate("LBL_PLS_SELECT",'PDFMaker'),
            "COMPANY_NAME" => vtranslate("LBL_COMPANY_NAME",'PDFMaker'),
            "COMPANY_LOGO" => vtranslate("LBL_COMPANY_LOGO",'PDFMaker'),
            "COMPANY_ADDRESS" => vtranslate("LBL_COMPANY_ADDRESS",'PDFMaker'),
            "COMPANY_CITY" => vtranslate("LBL_COMPANY_CITY",'PDFMaker'),
            "COMPANY_STATE" => vtranslate("LBL_COMPANY_STATE",'PDFMaker'),
            "COMPANY_ZIP" => vtranslate("LBL_COMPANY_ZIP",'PDFMaker'),
            "COMPANY_COUNTRY" => vtranslate("LBL_COMPANY_COUNTRY",'PDFMaker'),
            "COMPANY_PHONE" => vtranslate("LBL_COMPANY_PHONE","PDFMaker"),
            "COMPANY_FAX" => vtranslate("LBL_COMPANY_FAX",'PDFMaker'),
            "COMPANY_WEBSITE" => vtranslate("LBL_COMPANY_WEBSITE",'PDFMaker')
        );

        $viewer->assign("ACCOUNTINFORMATIONS", $Acc_Info);

        if (getTabId('MultiCompany4you') && vtlib_isModuleActive('MultiCompany4you')) {
            $MultiAcc_info = Array('' => vtranslate("LBL_PLS_SELECT",'PDFMaker'),
                "MULTICOMPANY_COMPANYNAME" => vtranslate("LBL_COMPANY_NAME", 'MultiCompany4you'),
                "MULTICOMPANY_STREET" => vtranslate("Street", 'MultiCompany4you'),
                "MULTICOMPANY_CITY" => vtranslate("City", 'MultiCompany4you'),
                "MULTICOMPANY_CODE" => vtranslate("Code", 'MultiCompany4you'),
                "MULTICOMPANY_STATE" => vtranslate("State", 'MultiCompany4you'),
                "MULTICOMPANY_COUNTRY" => vtranslate("Country", 'MultiCompany4you'),
                "MULTICOMPANY_PHONE" => vtranslate("phone", 'MultiCompany4you'),
                "MULTICOMPANY_FAX" => vtranslate("Fax", 'MultiCompany4you'),
                "MULTICOMPANY_EMAIL" => vtranslate("email", 'MultiCompany4you'),
                "MULTICOMPANY_WEBSITE" => vtranslate("Website", 'MultiCompany4you'),
                "MULTICOMPANY_LOGO" => vtranslate("Logo", 'MultiCompany4you'),
                "MULTICOMPANY_STAMP" => vtranslate("Stamp", 'MultiCompany4you'),
                "MULTICOMPANY_BANKNAME" => vtranslate("BankName", 'MultiCompany4you'),
                "MULTICOMPANY_BANKACCOUNTNO" => vtranslate("BankAccountNo", 'MultiCompany4you'),
                "MULTICOMPANY_IBAN" => vtranslate("IBAN", 'MultiCompany4you'),
                "MULTICOMPANY_SWIFT" => vtranslate("SWIFT", 'MultiCompany4you'),
                "MULTICOMPANY_REGISTRATIONNO" => vtranslate("RegistrationNo", 'MultiCompany4you'),
                "MULTICOMPANY_VATNO" => vtranslate("VATNo", 'MultiCompany4you'),
                "MULTICOMPANY_TAXID" => vtranslate("TaxId", 'MultiCompany4you'),
                "MULTICOMPANY_ADDITIONALINFORMATIONS" => vtranslate("AdditionalInformations", 'MultiCompany4you'),
            );
            $viewer->assign("MULTICOMPANYINFORMATIONS", $MultiAcc_info);
            $viewer->assign("LBL_MULTICOMPANY", vtranslate('MultiCompany', 'MultiCompany4you'));
        }

        $sql_user_block = "SELECT blockid, blocklabel FROM vtiger_blocks WHERE tabid = ? ORDER BY sequence ASC";
        $res_user_block = $adb->pquery($sql_user_block,array('29'));
        $user_block_info_arr = array();
        while ($row_user_block = $adb->fetch_array($res_user_block)) {
            $sql_user_field = "SELECT fieldid, uitype FROM vtiger_field WHERE block = ? and (displaytype != ? OR uitype = ?) ORDER BY sequence ASC";
            $res_user_field = $adb->pquery($sql_user_field,array($row_user_block['blockid'],'3','55'));
            $num_user_field = $adb->num_rows($res_user_field);

            if ($num_user_field > 0) {
                $user_field_id_array = array();

                while ($row_user_field = $adb->fetch_array($res_user_field)) {
                    $user_field_id_array[] = $row_user_field['fieldid'];
                }

                $user_block_info_arr[$row_user_block['blocklabel']] = $user_field_id_array;
            }
        }

        $user_mod_strings = $this->getModuleLanguageArray("Users"); 

        $b = 0;

        foreach ($user_block_info_arr AS $block_label => $block_fields) {
            $b++;

            if (isset($user_mod_strings[$block_label]) AND $user_mod_strings[$block_label] != "")
                $optgroup_value = $user_mod_strings[$block_label];
            else
                $optgroup_value = vtranslate($block_label,'PDFMaker');

            if (count($block_fields) > 0) {
                $sql1 = "SELECT * FROM vtiger_field WHERE fieldid IN (" . generateQuestionMarks($block_fields) . ")";
                $result1 = $adb->pquery($sql1, $block_fields);

                while ($row1 = $adb->fetchByAssoc($result1)) {
                    $fieldname = $row1['fieldname'];
                    $fieldlabel = $row1['fieldlabel'];

                    $option_key = strtoupper("Users" . "_" . $fieldname);

                    if (isset($current_mod_strings[$fieldlabel]) AND $current_mod_strings[$fieldlabel] != "")
                        $option_value = $current_mod_strings[$fieldlabel];
                    elseif (isset($app_strings[$fieldlabel]) AND $app_strings[$fieldlabel] != "")
                        $option_value = $app_strings[$fieldlabel];
                    else
                        $option_value = $fieldlabel;

                    $User_Info[$optgroup_value][$option_key] = $option_value;
                    $Logged_User_Info[$optgroup_value]["R_" . $option_key] = $option_value;
                }
            }

            //variable RECORD ID added
            if ($b == 1) {
                $option_value = "Record ID";
                $option_key = strtoupper("USERS_CRMID");
                $User_Info[$optgroup_value][$option_key] = $option_value;
                $Logged_User_Info[$optgroup_value]["R_" . $option_key] = $option_value;
            }
            //end
        }

// ****************************************** END: Company and User information **********************************

        $viewer->assign("USERINFORMATIONS", $User_Info);
        $viewer->assign("LOGGEDUSERINFORMATION", $Logged_User_Info);

        $Invterandcon = array("" => vtranslate("LBL_PLS_SELECT",'PDFMaker'),
            "TERMS_AND_CONDITIONS" => vtranslate("LBL_TERMS_AND_CONDITIONS",'PDFMaker'));

        $viewer->assign("INVENTORYTERMSANDCONDITIONS", $Invterandcon);

//custom functions
        $customFunctions = $this->getCustomFunctionsList();
        $viewer->assign("CUSTOM_FUNCTIONS", $customFunctions);
//labels
        
        $global_lang_labels = @array_flip($app_strings);
        $global_lang_labels = @array_flip($global_lang_labels);
        asort($global_lang_labels);
        $viewer->assign("GLOBAL_LANG_LABELS", $global_lang_labels);

        $module_lang_labels = array();
        if ($select_module != "") {
            $mod_lang = $this->getModuleLanguageArray($select_module);
            $module_lang_labels = @array_flip($mod_lang);
            $module_lang_labels = @array_flip($module_lang_labels);
            asort($module_lang_labels);
        }
        else
            $module_lang_labels[""] = vtranslate("LBL_SELECT_MODULE_FIELD",'PDFMaker');

        
        $viewer->assign("MODULE_LANG_LABELS", $module_lang_labels);

        list($custom_labels, $languages) = $PDFMaker->GetCustomLabels();
        $currLangId = "";
        foreach ($languages as $langId => $langVal) {
            if ($langVal["prefix"] == $current_language) {
                $currLangId = $langId;
                break;
            }
        }

        $vcustom_labels = array();
        if (count($custom_labels) > 0) {

            foreach ($custom_labels as $oLbl) {
                $currLangVal = $oLbl->GetLangValue($currLangId);
                if ($currLangVal == "")
                    $currLangVal = $oLbl->GetFirstNonEmptyValue();

                $vcustom_labels[$oLbl->GetKey()] = $currLangVal;
            }
            asort($vcustom_labels);
        } else {
            $vcustom_labels = vtranslate("LBL_SELECT_MODULE_FIELD",'PDFMaker');
        }
        $viewer->assign("CUSTOM_LANG_LABELS", $vcustom_labels);

        $Header_Footer_Strings = array("" => vtranslate("LBL_PLS_SELECT",'PDFMaker'),
            "PAGE" => $app_strings["Page"],
            "PAGES" => $app_strings["Pages"],
        );

        $viewer->assign("HEADER_FOOTER_STRINGS", $Header_Footer_Strings);

//PDF FORMAT SETTINGS

        $Formats = array("A3" => "A3",
            "A4" => "A4",
            "A5" => "A5",
            "A6" => "A6",
            "Letter" => "Letter",
            "Legal" => "Legal",
            "Custom" => "Custom");     // ITS4YOU VlZa

        $viewer->assign("FORMATS", $Formats);
        if (strpos($select_format, ";") > 0) {
            $tmpArr = explode(";", $select_format);

            $select_format = "Custom";
            $custom_format["width"] = $tmpArr[0];
            $custom_format["height"] = $tmpArr[1];
            $viewer->assign("CUSTOM_FORMAT", $custom_format);
        }

        $viewer->assign("SELECT_FORMAT", $select_format);

//PDF ORIENTATION SETTINGS

        $Orientations = array("portrait" => vtranslate("portrait",'PDFMaker'),
            "landscape" => vtranslate("landscape",'PDFMaker'));

        $viewer->assign("ORIENTATIONS", $Orientations);

        $viewer->assign("SELECT_ORIENTATION", $select_orientation);

//PDF STATUS SETTINGS
        $Status = array("1" => $app_strings["Active"],
            "0" => vtranslate("Inactive",'PDFMaker'));
        $viewer->assign("STATUS", $Status);
        $viewer->assign("IS_ACTIVE", $is_active);
        if ($is_active == "0") {
            $viewer->assign("IS_DEFAULT_DV_CHECKED", 'disabled="disabled"');
            $viewer->assign("IS_DEFAULT_LV_CHECKED", 'disabled="disabled"');
        } elseif ($is_default > 0) {
            $is_default_bin = str_pad(base_convert($is_default, 10, 2), 2, "0", STR_PAD_LEFT);
            $is_default_lv = substr($is_default_bin, 0, 1);
            $is_default_dv = substr($is_default_bin, 1, 1);
            if ($is_default_lv == "1")
                $viewer->assign("IS_DEFAULT_LV_CHECKED", 'checked="checked"');
            if ($is_default_dv == "1")
                $viewer->assign("IS_DEFAULT_DV_CHECKED", 'checked="checked"');
        }

        $viewer->assign("ORDER", $order);

        if ($is_portal == "1")
            $viewer->assign("IS_PORTAL_CHECKED", 'checked="checked"');

        if ($is_listview == "1")
            $viewer->assign("IS_LISTVIEW_CHECKED", 'checked="checked"');

//PDF MARGIN SETTINGS
        if ($request->has("templateid") && !$request->isEmpty("templateid")) {
            $Margins = array("top" => $pdftemplateResult["margin_top"],
                "bottom" => $pdftemplateResult["margin_bottom"],
                "left" => $pdftemplateResult["margin_left"],
                "right" => $pdftemplateResult["margin_right"]);

            $Decimals = array("point" => $pdftemplateResult["decimal_point"],
                "decimals" => $pdftemplateResult["decimals"],
                "thousands" => ($pdftemplateResult["thousands_separator"] != "sp" ? $pdftemplateResult["thousands_separator"] : " ")
            );
        } else {
            $Margins = array("top" => "2", "bottom" => "2", "left" => "2", "right" => "2");
            $Decimals = array("point" => ",", "decimals" => "2", "thousands" => " ");
        }
        $viewer->assign("MARGINS", $Margins);
        $viewer->assign("DECIMALS", $Decimals);

//PDF HEADER / FOOTER
        $header = "";
        $footer = "";
        if ($request->has("templateid") && !$request->isEmpty("templateid")) {
            $header = $pdftemplateResult["header"];
            $footer = $pdftemplateResult["footer"];
        }
        $viewer->assign("HEADER", $header);
        $viewer->assign("FOOTER", $footer);

        $hfVariables = array("##PAGE##" => vtranslate("LBL_CURRENT_PAGE",'PDFMaker'),
            "##PAGES##" => vtranslate("LBL_ALL_PAGES",'PDFMaker'),
            "##PAGE##/##PAGES##" => vtranslate("LBL_PAGE_PAGES",'PDFMaker'));

        $viewer->assign("HEAD_FOOT_VARS", $hfVariables);

        $dateVariables = array("##DD.MM.YYYY##" => vtranslate("LBL_DATE_DD.MM.YYYY",'PDFMaker'),
            "##DD-MM-YYYY##" => vtranslate("LBL_DATE_DD-MM-YYYY",'PDFMaker'),
            "##MM-DD-YYYY##" => vtranslate("LBL_DATE_MM-DD-YYYY",'PDFMaker'),
            "##YYYY-MM-DD##" => vtranslate("LBL_DATE_YYYY-MM-DD",'PDFMaker'));

        $viewer->assign("DATE_VARS", $dateVariables);

//PDF FILENAME FIELDS
        $filenameFields = array("#TEMPLATE_NAME#" => vtranslate("LBL_PDF_NAME",'PDFMaker'),
            "#DD-MM-YYYY#" => vtranslate("LBL_CURDATE_DD-MM-YYYY",'PDFMaker'),
            "#MM-DD-YYYY#" => vtranslate("LBL_CURDATE_MM-DD-YYYY",'PDFMaker'),
            "#YYYY-MM-DD#" => vtranslate("LBL_CURDATE_YYYY-MM-DD",'PDFMaker')
        );
        $viewer->assign("FILENAME_FIELDS", $filenameFields);
        $viewer->assign("NAME_OF_FILE", $nameOfFile);

//Sharing
        $template_owners = get_user_array(false);
        $viewer->assign("TEMPLATE_OWNERS", $template_owners);
        $viewer->assign("TEMPLATE_OWNER", $owner);

        $sharing_types = Array("public" => vtranslate("PUBLIC_FILTER",'PDFMaker'),
            "private" => vtranslate("PRIVATE_FILTER",'PDFMaker'),
            "share" => vtranslate("SHARE_FILTER",'PDFMaker'));
        $viewer->assign("SHARINGTYPES", $sharing_types);
        $viewer->assign("SHARINGTYPE", $sharingtype);

        $cmod = $this->getModuleLanguageArray("Settings");
        //$cmod = return_specified_module_language($current_language, "Settings");
        $viewer->assign("CMOD", $cmod);
//Constructing the Role Array
        $roleDetails = getAllRoleDetails();
        $i = 0;
        $roleIdStr = "";
        $roleNameStr = "";
        $userIdStr = "";
        $userNameStr = "";
        $grpIdStr = "";
        $grpNameStr = "";

        foreach ($roleDetails as $roleId => $roleInfo) {
            if ($i != 0) {
                if ($i != 1) {
                    $roleIdStr .= ", ";
                    $roleNameStr .= ", ";
                }
                $roleName = $roleInfo[0];
                $roleIdStr .= "'" . $roleId . "'";
                $roleNameStr .= "'" . addslashes(decode_html($roleName)) . "'";
            }
            $i++;
        }
//Constructing the User Array
        $l = 0;
        $userDetails = getAllUserName();
        foreach ($userDetails as $userId => $userInfo) {
            if ($l != 0) {
                $userIdStr .= ", ";
                $userNameStr .= ", ";
            }
            $userIdStr .= "'" . $userId . "'";
            $userNameStr .= "'" . $userInfo . "'";
            $l++;
        }
//Constructing the Group Array
        $parentGroupArray = array();

        $m = 0;
        $grpDetails = getAllGroupName();
        foreach ($grpDetails as $grpId => $grpName) {
            if (!in_array($grpId, $parentGroupArray)) {
                if ($m != 0) {
                    $grpIdStr .= ", ";
                    $grpNameStr .= ", ";
                }
                $grpIdStr .= "'" . $grpId . "'";
                $grpNameStr .= "'" . addslashes(decode_html($grpName)) . "'";
                $m++;
            }
        }
        $viewer->assign("ROLEIDSTR", $roleIdStr);
        $viewer->assign("ROLENAMESTR", $roleNameStr);
        $viewer->assign("USERIDSTR", $userIdStr);
        $viewer->assign("USERNAMESTR", $userNameStr);
        $viewer->assign("GROUPIDSTR", $grpIdStr);
        $viewer->assign("GROUPNAMESTR", $grpNameStr);

        if (count($sharingMemberArray) > 0) {
            $outputMemberArr = array();
            foreach ($sharingMemberArray as $setype => $shareIdArr) {
                foreach ($shareIdArr as $shareId) {
                    switch ($setype) {
                        case "groups":
                            
                            $groupInfo = getGroupName($shareId);
		                    $memberName = $groupInfo[0];
                            
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

                    $outputMemberArr[] = $setype . "::" . $shareId;
                    $outputMemberArr[] = $memberDisplay . $memberName;
                }
            }
            $viewer->assign("MEMBER", array_chunk($outputMemberArr, 2));
        }

//Ignored picklist values
        $pvsql = "SELECT value FROM vtiger_pdfmaker_ignorepicklistvalues";
        $pvresult = $adb->pquery($pvsql,array());
        $pvvalues = "";
        while ($pvrow = $adb->fetchByAssoc($pvresult))
            $pvvalues.=$pvrow["value"] . ", ";
        $viewer->assign("IGNORE_PICKLIST_VALUES", rtrim($pvvalues, ", "));

        $More_Fields = array(/* "SUBTOTAL"=>vtranslate("LBL_VARIABLE_SUM",'PDFMaker'), */
            "CURRENCYNAME" => vtranslate("LBL_CURRENCY_NAME",'PDFMaker'),
            "CURRENCYSYMBOL" => vtranslate("LBL_CURRENCY_SYMBOL",'PDFMaker'),
            "CURRENCYCODE" => vtranslate("LBL_CURRENCY_CODE",'PDFMaker'),
            "TOTALWITHOUTVAT" => vtranslate("LBL_VARIABLE_SUMWITHOUTVAT",'PDFMaker'),
            "TOTALDISCOUNT" => vtranslate("LBL_VARIABLE_TOTALDISCOUNT",'PDFMaker'),
            "TOTALDISCOUNTPERCENT" => vtranslate("LBL_VARIABLE_TOTALDISCOUNT_PERCENT",'PDFMaker'),
            "TOTALAFTERDISCOUNT" => vtranslate("LBL_VARIABLE_TOTALAFTERDISCOUNT",'PDFMaker'),
            "VAT" => vtranslate("LBL_VARIABLE_VAT",'PDFMaker'),
            "VATPERCENT" => vtranslate("LBL_VARIABLE_VAT_PERCENT",'PDFMaker'),
            "VATBLOCK" => vtranslate("LBL_VARIABLE_VAT_BLOCK",'PDFMaker'),
            "TOTALWITHVAT" => vtranslate("LBL_VARIABLE_SUMWITHVAT",'PDFMaker'),
            "SHTAXTOTAL" => vtranslate("LBL_SHTAXTOTAL",'PDFMaker'),
            "SHTAXAMOUNT" => vtranslate("LBL_SHTAXAMOUNT",'PDFMaker'),
            "ADJUSTMENT" => vtranslate("LBL_ADJUSTMENT",'PDFMaker'),
            "TOTAL" => vtranslate("LBL_VARIABLE_TOTALSUM",'PDFMaker')
        );

//formatable VATBLOCK content
        $vatblock_table = '<table border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;">
                		<tr>
                            <td>' . $app_strings["Name"] . '</td>
                            <td>' . vtranslate("LBL_VATBLOCK_VAT_PERCENT",'PDFMaker') . '</td>
                            <td>' . vtranslate("LBL_VATBLOCK_SUM",'PDFMaker') . '</td>
                            <td>' . vtranslate("LBL_VATBLOCK_VAT_VALUE",'PDFMaker') . '</td>
                        </tr>
                		<tr>
                            <td colspan="4">#VATBLOCK_START#</td>
                        </tr>
                		<tr>
                			<td>$VATBLOCK_LABEL$</td>
                			<td>$VATBLOCK_VALUE$</td>
                			<td>$VATBLOCK_NETTO$</td>
                			<td>$VATBLOCK_VAT$</td>
                		</tr>
                		<tr>
                            <td colspan="4">#VATBLOCK_END#</td>
                        </tr>
                    </table>';

        $vatblock_table = str_replace(array("\r\n", "\r", "\n", "\t"), "", $vatblock_table);
        $vatblock_table = ereg_replace(" {2,}", ' ', $vatblock_table);
        $viewer->assign("VATBLOCK_TABLE", $vatblock_table);

        $ModCommentsModules = array();
        /*
        $ModComments = is_numeric(getTabId("ModComments"));
        if ($ModComments == true) {
            $sql = "SELECT relmodule FROM vtiger_fieldmodulerel WHERE module='ModComments' AND relmodule != 'ModComments'";
            $result = $adb->pquery($sql,array());
            while ($row = $adb->fetchByAssoc($result))
                $ModCommentsModules[$row["relmodule"]] = $row["relmodule"];
        }
*/
        foreach ($ModuleIDS as $module => $module_id) {
            $this->getModuleFields($module, $module_id);
        } 
               
//Permissions are taken into consideration when dealing with realted modules
        $AllowedRelMods = array();
        if (count($this->All_Related_Modules) > 0)
        {
            foreach ($this->All_Related_Modules as $Mod => $RelMods) {
                foreach ($RelMods as $RelModKey => $RelMod) {
                    $RelModName = $RelMod[2];

                    if (isPermitted($RelModName, '') == "yes")
                        $AllowedRelMods[$Mod][$RelModKey] = $RelMod;
                }
            }
        }
        $this->All_Related_Modules = $AllowedRelMods;

// Fix of emtpy selectbox in case of selected module does not have any related modules
        foreach ($Modulenames as $key => $value) {
            if (!isset($this->All_Related_Modules[$key]))
                $this->All_Related_Modules[$key] = array();
        }
        $viewer->assign("ALL_RELATED_MODULES", $this->All_Related_Modules);

        if ($select_module != "" && count($this->All_Related_Modules[$select_module]) > 0) {
            foreach ($this->All_Related_Modules[$select_module] AS $RelModArr) {
                $Related_Modules[$RelModArr[2] . "|" . $RelModArr[0]] = vtranslate($RelModArr[2]) . " (" . $RelModArr[1] . ")";
            }
        }
        $viewer->assign("RELATED_MODULES", $Related_Modules);

        $tacModules = array();
        $tac4you = is_numeric(getTabId("Tac4you"));
        if ($tac4you == true) {
            $sql = "SELECT tac4you_module FROM vtiger_tac4you_module WHERE presence = ?";
            $result = $adb->pquery($sql,array('1'));
            while ($row = $adb->fetchByAssoc($result))
                $tacModules[$row["tac4you_module"]] = $row["tac4you_module"];
        }

        $desc4youModules = array();
        $desc4you = is_numeric(getTabId("Descriptions4you"));
        if ($desc4you == true) {
            $sql = "SELECT b.name FROM vtiger_links AS a INNER JOIN vtiger_tab AS b USING (tabid) WHERE linktype = ? AND linkurl = ?";
            $result = $adb->pquery($sql,array('DETAILVIEWWIDGET', 'block://ModDescriptions4you:modules/Descriptions4you/ModDescriptions4you.php'));
            while ($row = $adb->fetchByAssoc($result))
                $desc4youModules[$row["name"]] = $row["name"];
        }

        $Settings_Profiles_Record_Model = new Settings_Profiles_Record_Model();
        
        foreach ($this->ModuleFields AS $module => $Blocks) {
            $Optgroupts = array();
            $current_mod_strings = $this->getModuleLanguageArray($module); 
            $moduleModel = Vtiger_Module_Model::getInstance($module);
            $b = 0;
            if ($module == 'Calendar') {
                $b++;
                $Optgroupts[] = '"' . vtranslate('Calendar') . '","' . $b . '"';
                $Convert_ModuleFields['Calendar|1'] .= ',"Record ID","CALENDAR_CRMID"';
                $SelectModuleFields['Calendar'][vtranslate('Calendar')]["CALENDAR_CRMID"] = "Record ID";

                $EventModel = Vtiger_Module_Model::getInstance('Events');
            }
            

            foreach ($Blocks AS $block_label => $block_fields) {
                $b++;

                $Options = array();

                if ($block_label != "TEMP_MODCOMMENTS_BLOCK") {
                    
                    $optgroup_value = vtranslate($block_label,$module);
                    
                    if ($optgroup_value == $block_label)
                        $optgroup_value = vtranslate($block_label,'PDFMaker');

                }
                else {
                    $optgroup_value = vtranslate("LBL_MODCOMMENTS_INFORMATION",'PDFMaker');
                }

                $Optgroupts[] = '"' . $optgroup_value . '","' . $b . '"';

                if (count($block_fields) > 0) {
                    $sql1 = "SELECT * FROM vtiger_field WHERE fieldid IN (" . generateQuestionMarks($block_fields) . ")";
                    $result1 = $adb->pquery($sql1, $block_fields);

                    while ($row1 = $adb->fetchByAssoc($result1)) {
                        $fieldname = $row1['fieldname'];
                        $fieldlabel = $row1['fieldlabel'];

                        $fieldModel = Vtiger_Field_Model::getInstance($fieldname,$moduleModel);
                          
                        if (!$fieldModel || !$fieldModel->getPermissions('readonly')) {
            				if ($module == 'Calendar') {
                                $eventFieldModel = Vtiger_Field_Model::getInstance($fieldname,$EventModel);
                                if (!$eventFieldModel || !$eventFieldModel->getPermissions('readonly')) {
                                    continue;
                                }
                            } else {
                               continue;
                            }
                		}
                        
                        $option_key = strtoupper($module . "_" . $fieldname);

                        if (isset($current_mod_strings[$fieldlabel]) AND $current_mod_strings[$fieldlabel] != "")
                            $option_value = $current_mod_strings[$fieldlabel];
                        elseif (isset($app_strings[$fieldlabel]) AND $app_strings[$fieldlabel] != "")
                            $option_value = $app_strings[$fieldlabel];
                        else
                            $option_value = $fieldlabel;

                        $option_value = nl2br($option_value);
                        
                        if ($module == 'Calendar') {
                            if ($option_key == 'CALENDAR_ACTIVITYTYPE' || $option_key == 'CALENDAR_DUE_DATE') {
                                $Convert_ModuleFields['Calendar|1'] .= ',"' . $option_value . '","' . $option_key . '"';
                                $SelectModuleFields['Calendar'][vtranslate('Calendar')][$option_key] = $option_value;
                                continue;
                            } elseif (!isset($Existing_ModuleFields[$option_key])) {
                                $Existing_ModuleFields[$option_key] = $optgroup_value;
                            } else {
                                $Convert_ModuleFields['Calendar|1'] .= ',"' . $option_value . '","' . $option_key . '"';
                                $SelectModuleFields['Calendar'][vtranslate('Calendar')][$option_key] = $option_value;
                                $Unset_Module_Fields[] = '"' . $option_value . '","' . $option_key . '"';
                                unset($SelectModuleFields['Calendar'][$Existing_ModuleFields[$option_key]][$option_key]);
                                continue;
                            }
                        }
                        $Options[] = '"' . $option_value . '","' . $option_key . '"';
                        $SelectModuleFields[$module][$optgroup_value][$option_key] = $option_value;
                    }
                }

                //variable RECORD ID added
                if ($b == 1) {
                    $option_value = "Record ID";
                    $option_key = strtoupper($module . "_CRMID");
                    $Options[] = '"' . $option_value . '","' . $option_key . '"';
                    $SelectModuleFields[$module][$optgroup_value][$option_key] = $option_value;
                    $option_value = vtranslate('Created Time') . ' (' . vtranslate('Due Date & Time') . ')';
                    $option_key = strtoupper($module . "_CREATEDTIME_DATETIME");
                    $Options[] = '"' . $option_value . '","' . $option_key . '"';
                    $SelectModuleFields[$module][$optgroup_value][$option_key] = $option_value;
                    $option_value = vtranslate('Modified Time') . ' (' . vtranslate('Due Date & Time') . ')';
                    $option_key = strtoupper($module . "_MODIFIEDTIME_DATETIME");
                    $Options[] = '"' . $option_value . '","' . $option_key . '"';
                    $SelectModuleFields[$module][$optgroup_value][$option_key] = $option_value;
                }
                //end

                if ($block_label == "LBL_TERMS_INFORMATION" && isset($tacModules[$module])) {
                    $option_value = vtranslate("LBL_TAC4YOU",'PDFMaker');
                    $option_key = strtoupper($module . "_TAC4YOU");
                    $Options[] = '"' . $option_value . '","' . $option_key . '"';
                    $SelectModuleFields[$module][$optgroup_value][$option_key] = $option_value;
                }

                if ($block_label == "LBL_DESCRIPTION_INFORMATION" && isset($desc4youModules[$module])) {
                    $option_value = vtranslate("LBL_DESC4YOU",'PDFMaker');
                    $option_key = strtoupper($module . "_DESC4YOU");
                    $Options[] = '"' . $option_value . '","' . $option_key . '"';
                    $SelectModuleFields[$module][$optgroup_value][$option_key] = $option_value;
                }
                //ModComments support
                if ($block_label == "TEMP_MODCOMMENTS_BLOCK" && in_array($module, $ModCommentsModules) == true) {
                    $option_value = vtranslate("LBL_MODCOMMENTS",'PDFMaker');
                    $option_key = strtoupper($module . "_MODCOMMENTS");
                    $Options[] = '"' . $option_value . '","' . $option_key . '"';
                    $SelectModuleFields[$module][$optgroup_value][$option_key] = $option_value;
                }
                $Convert_RelatedModuleFields[$module . "|" . $b] = implode(",", $Options);

                $OptionsRelMod = array();
                if (($block_label == "LBL_DETAILS_BLOCK" || $block_label == "LBL_ITEM_DETAILS") && ($module == "Quotes" || $module == "Invoice" || $module == "SalesOrder" || $module == "PurchaseOrder" || $module == "Issuecards" || $module == "Receiptcards" || $module == "Creditnote" || $module == "StornoInvoice")) {
                    $Set_More_Fields = $More_Fields;
                    if ($module == "Invoice"){
                        $Set_More_Fields[strtoupper($module)."_RECEIVED"] =  vtranslate("Received",$module);
                    }
                    if ($module == "Invoice" || $module == "PurchaseOrder"){
                        $Set_More_Fields[strtoupper($module)."_BALANCE"] =  vtranslate("Balance",$module);
                    }
                    
                    foreach ($Set_More_Fields AS $variable => $variable_name) {
                        $variable_key = strtoupper($variable);
                        $Options[] = '"' . $variable_name . '","' . $variable_key . '"';
                        $SelectModuleFields[$module][$optgroup_value][$variable_key] = $variable_name;
                        if ($variable_key != "VATBLOCK")
                            $OptionsRelMod[] = '"' . $variable_name . '","' . strtoupper($module) . '_' . $variable_key . '"';
                    }
                }
                //this concatenation is because of need to have extra Details block in Inventory modules which are as related modules
                $Convert_RelatedModuleFields[$module . "|" . $b] .= implode(',', $OptionsRelMod);

                $Convert_ModuleFields[$module . "|" . $b] = implode(",", $Options);
            }

            if ($module == 'Calendar') {
                $Convert_ModuleFields['Calendar|1'] = str_replace(',"Record ID","CALENDAR_CRMID",', "", $Convert_ModuleFields['Calendar|1']);
                $Convert_ModuleFields['Calendar|1'] .= ',"Record ID","CALENDAR_CRMID"';
                unset($SelectModuleFields['Calendar'][vtranslate('Calendar')]["CALENDAR_CRMID"]);
                $SelectModuleFields['Calendar'][vtranslate('Calendar')]["CALENDAR_CRMID"] = "Record ID";
            }
            
            $Convert_ModuleBlocks[$module] = implode(",", $Optgroupts);
        }
        
        foreach ($Convert_ModuleFields as $cmf_key => $cmf_value) {
            if (substr($cmf_key, 0, 9) == 'Calendar|' && $cmf_key != 'Calendar|1') {
                foreach ($Unset_Module_Fields as $to_unset) {
                    $cmf_value = str_replace($to_unset, '', $cmf_value);
                    $cmf_value = str_replace(",,", ',', $cmf_value);
                    $Convert_ModuleFields[$cmf_key] = trim($cmf_value, ',');
                }
            }
        }

        $viewer->assign("MODULE_BLOCKS", $Convert_ModuleBlocks);

        $viewer->assign("RELATED_MODULE_FIELDS", $Convert_RelatedModuleFields);

        $viewer->assign("MODULE_FIELDS", $Convert_ModuleFields);

//Product block fields start
// Product bloc templates
        $sql = "SELECT * FROM vtiger_pdfmaker_productbloc_tpl";
        $result = $adb->pquery($sql,array());
        $Productbloc_tpl[""] = vtranslate("LBL_PLS_SELECT",'PDFMaker');
        while ($row = $adb->fetchByAssoc($result)) {
            $Productbloc_tpl[$row["body"]] = $row["name"];
        }
        $viewer->assign("PRODUCT_BLOC_TPL", $Productbloc_tpl);

        $ProductBlockFields = $PDFMaker->GetProductBlockFields($select_module);
        foreach ($ProductBlockFields as $viewer_key => $pbFields) {
            $viewer->assign($viewer_key, $pbFields);
        }
//Product block fields end
//Related block postprocessing
        $Related_Blocks = $PDFMaker->GetRelatedBlocks($select_module);
        $viewer->assign("RELATED_BLOCKS", $Related_Blocks);
//Related blocks end

        if ($templateid != "" || $select_module != "") {
            $viewer->assign("SELECT_MODULE_FIELD", $SelectModuleFields[$select_module]);
            $smf_filename = $SelectModuleFields[$select_module];
            if ($select_module == "Invoice" || $select_module == "Quotes" || $select_module == "SalesOrder" || $select_module == "PurchaseOrder" || $select_module == "Issuecards" || $select_module == "Receiptcards" || $select_module == "Creditnote" || $select_module == "StornoInvoice")
                unset($smf_filename["Details"]);
            $viewer->assign("SELECT_MODULE_FIELD_FILENAME", $smf_filename);
        }

// header / footer display settings
        $disp_optionsArr = array("DH_FIRST", "DH_OTHER");
        $disp_header_bin = str_pad(base_convert($disp_header, 10, 2), 2, "0", STR_PAD_LEFT);
        for ($i = 0; $i < count($disp_optionsArr); $i++) {
            if (substr($disp_header_bin, $i, 1) == "1")
                $viewer->assign($disp_optionsArr[$i], 'checked="checked"');
        }
        if ($disp_header == "3")
            $viewer->assign("DH_ALL", 'checked="checked"');

        $disp_optionsArr = array("DF_FIRST", "DF_LAST", "DF_OTHER");
        $disp_footer_bin = str_pad(base_convert($disp_footer, 10, 2), 3, "0", STR_PAD_LEFT);
        for ($i = 0; $i < count($disp_optionsArr); $i++) {
            if (substr($disp_footer_bin, $i, 1) == "1")
                $viewer->assign($disp_optionsArr[$i], 'checked="checked"');
        }
        if ($disp_footer == "7")
            $viewer->assign("DF_ALL", 'checked="checked"');

        $ListView_Block = array("" => vtranslate("LBL_PLS_SELECT",'PDFMaker'),
            "LISTVIEWBLOCK_START" => vtranslate("LBL_ARTICLE_START",'PDFMaker'),
            "LISTVIEWBLOCK_END" => vtranslate("LBL_ARTICLE_END",'PDFMaker'),
            "CRIDX" => vtranslate("LBL_COUNTER",'PDFMaker'),
        );

        $viewer->assign("LISTVIEW_BLOCK_TPL", $ListView_Block);

        $version_type = ucfirst($PDFMaker->GetVersionType());

        $viewer->assign("VERSION", $version_type . " " . PDFMaker_Version_Helper::$version);

        $category = getParentTab();
        $viewer->assign("CATEGORY", $category);
        $viewer->view('Edit.tpl', 'PDFMaker');
    }

    /**
     * Function to get the list of Script models to be included
     * @param Vtiger_Request $request
     * @return <Array> - List of Vtiger_JsScript_Model instances
     */
    function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

        $jsFileNames = array(
            "modules.PDFMaker.resources.ckeditor.ckeditor",
            "libraries.jquery.ckeditor.adapters.jquery",
            "libraries.jquery.jquery_windowmsg"
        );
        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }
    
    /**
     * Function to get array with module languages
     * @param module name
     * @return <Array> - Array of Module languages
     */
    function getModuleLanguageArray($module) {
    
        if (file_exists("languages/".$this->cu_language."/".$module.".php")) 
            $current_mod_strings_lang = $this->cu_language;
        else
            $current_mod_strings_lang = "en_us";

        $current_mod_strings_big = Vtiger_Language_Handler::getModuleStringsFromFile($current_mod_strings_lang,$module);
        return $current_mod_strings_big['languageStrings'];
    }
    
    /**
     * Function to get array with available custom functions 
     * @param none
     * @return <Array> - Array of Module languages
     */
    function getCustomFunctionsList() { 
        
        $ready = false;
        $function_name = "";
        $function_params = array();
        $functions = array();

        $files = glob('modules/PDFMaker/resources/functions/*.php');
        foreach ($files as $file) {
            $filename = $file;
            $source = fread(fopen($filename, "r"), filesize($filename));
            $tokens = token_get_all($source);
            foreach ($tokens as $token) {
                if (is_array($token)) {
                    if ($token[0] == T_FUNCTION)
                        $ready = true;
                    elseif ($ready) {
                        if ($token[0] == T_STRING && $function_name == "")
                            $function_name = $token[1];
                        elseif ($token[0] == T_VARIABLE)
                            $function_params[] = $token[1];
                    }
                }
                elseif ($ready && $token == "{") {
                    $ready = false;
                    $functions[$function_name] = $function_params;
                    $function_name = "";
                    $function_params = array();
                }
            }
        }

        $customFunctions[""] = vtranslate("LBL_PLS_SELECT",'PDFMaker');
        foreach ($functions as $funName => $params) {
            $parString = implode("|", $params);
            $custFun = trim($funName . "|" . str_replace("$", "", $parString), "|");
            $customFunctions[$custFun] = $funName;
        }
        
        return $customFunctions;                
    }
    
    private function getModuleFields($module, $module_id, $skip_related = false) {
        
        if (isset($this->ModuleFields[$module])) return false;
           
        $adb = PearDatabase::getInstance();
        
        if ($module == 'Calendar')
            $sql1 = "SELECT blockid, blocklabel FROM vtiger_blocks WHERE tabid IN (9,16) ORDER BY sequence ASC";
        elseif ($module == "Quotes" || $module == "Invoice" || $module == "SalesOrder" || $module == "PurchaseOrder" || $module == "Issuecards" || $module == "Receiptcards" || $module == "Creditnote" || $module == "StornoInvoice")
            $sql1 = "SELECT blockid, blocklabel FROM vtiger_blocks WHERE tabid=" . $module_id . " AND blocklabel != 'LBL_DETAILS_BLOCK' AND blocklabel != 'LBL_ITEM_DETAILS' ORDER BY sequence ASC";
        elseif ($module == "Users")
            $sql1 = "SELECT blockid, blocklabel FROM vtiger_blocks INNER JOIN vtiger_tab ON vtiger_tab.tabid = vtiger_blocks.tabid WHERE vtiger_tab.name = 'Users' AND (blocklabel = 'LBL_USERLOGIN_ROLE' OR blocklabel = 'LBL_ADDRESS_INFORMATION' ) ORDER BY sequence ASC";
        else
            $sql1 = "SELECT blockid, blocklabel FROM vtiger_blocks WHERE tabid=" . $module_id . " ORDER BY sequence ASC";
        
        $res1 = $adb->pquery($sql1,array());
        $block_info_arr = array();
        while ($row = $adb->fetch_array($res1)){
            if ($row['blockid'] == '41' && $row['blocklabel'] == '')
                $row['blocklabel'] = 'LBL_EVENT_INFORMATION';
            $sql2 = "SELECT fieldid, uitype, columnname, fieldlabel
             FROM vtiger_field WHERE block= ? AND (displaytype != 3 OR uitype = 55) AND displaytype != 4 AND fieldlabel != 'Add Comment'
             ORDER BY sequence ASC";
            $res2 = $adb->pquery($sql2,array($row['blockid']));
            $num_rows2 = $adb->num_rows($res2);

            if ($num_rows2 > 0) {
                $field_id_array = array();

                while ($row2 = $adb->fetch_array($res2)) {
                    
                    $field_id_array[] = $row2['fieldid'];
                    $tmpArr = Array($row2["columnname"], vtranslate($row2["fieldlabel"], $module));
                    if (!$skip_related){    
                        switch ($row2['uitype']){
                            case "51": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Accounts");
                                break;
                            case "57": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Contacts");
                                break;
                            case "58": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Campaigns");
                                break;
                            case "59": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Products");
                                break;
                            case "73": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Accounts");
                                break;
                            case "75": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Vendors");
                                break;
                            case "81": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Vendors");
                                break;
                            case "76": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Potentials");
                                break;
                            case "78": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Quotes");
                                break;
                            case "80": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "SalesOrder");
                                break;
                            case "101": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Users");
                                $this->getModuleFields("Users", "", true);
                                break;
                            case "68": $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Accounts");
                                $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) "Contacts");
                                break;
                            case "10": $fmrs = $adb->pquery('SELECT relmodule FROM vtiger_fieldmodulerel WHERE fieldid = ?', array($row2['fieldid']));
                                while ($rm = $adb->fetch_array($fmrs)) {
                                    $this->All_Related_Modules[$module][] = array_merge($tmpArr, (array) $rm['relmodule']);
                                }
                                break;
                        }
                    }
                }
                // ITS4YOU MaJu
                //$block_info_arr[$row['blocklabel']] = $field_id_array;
                if (!empty($block_info_arr[$row['blocklabel']])) {
                    foreach ($field_id_array as $field_id_array_value)
                        $block_info_arr[$row['blocklabel']][] = $field_id_array_value;
                }
                else
                    $block_info_arr[$row['blocklabel']] = $field_id_array;
                // ITS4YOU-END
            }
        }

        if ($module == "Quotes" || $module == "Invoice" || $module == "SalesOrder" || $module == "PurchaseOrder" || $module == "Issuecards" || $module == "Receiptcards" || $module == "Creditnote" || $module == "StornoInvoice")
            $block_info_arr["LBL_DETAILS_BLOCK"] = array();

        //ModComments support
        if (in_array($module, $ModCommentsModules)) {
            $block_info_arr["TEMP_MODCOMMENTS_BLOCK"] = array();
        }

        $this->ModuleFields[$module] = $block_info_arr;
    }
}