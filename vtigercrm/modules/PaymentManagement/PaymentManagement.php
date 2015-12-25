<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

//require_once('data/CRMEntity.php');
include_once('config.php');
require_once('include/logging.php');
require_once('data/Tracker.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once("include/Zend/Json.php");


class PaymentManagement extends CRMEntity {
    var $module_name = 'PaymentManagement';
	var $table_name = 'vtiger_payment_management';
	var $table_index= 'payment_management_id';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_payment_managementcf', 'payment_management_id');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_payment_management', 'vtiger_payment_managementcf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_payment_management' => 'payment_management_id',
		'vtiger_payment_managementcf'=>'payment_management_id');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'PaymentManagement Name' => Array('vtiger_payment_management', 'titlename'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'PaymentManagement Name' => 'titlename',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'titlename';

	// For Ppup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'PaymentManagement Name' => Array('vtiger_payment_management', 'titlename'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'PaymentManagement Name' => 'titlename',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('titlename');

	// For Alphabetical search
	var $def_basicsearch_col = 'titlename';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'titlename';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('titlename','assigned_user_id');

	var $default_order_by = 'vtiger_payment_management.titlename';
	var $default_sort_order='ASC';



	function PaymentManagement() {
		$this->log = LoggerManager::getLogger('PaymentManagement');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('PaymentManagement');
	}

	function save_module()
	{
		// Update the currency id and the conversion rate for the quotes
		/* $update_query = "update vtiger_quotes set currency_id=?, conversion_rate=? where quoteid=?"; */
		/* $update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id); */
		/* $adb->pquery($update_query, $update_params); */
	}

    // Added by 田尾 (tao) on 11/25/15 -- begin
	/**
	 *  Key-Value 配列を Valueのみの配列に変換する
	 *  @param array $value_table  - key-value 配列
	 *  return 変換された配列
	 */
    static function generateValues($value_table) {
        global $log;
        $retval = array();

        foreach ($value_table as $key=>$value) {
            $retval[] = $value;
        }
        return $retval;
    }
    // Added by 田尾 (tao) on 11/25/15 -- end
	function insertIntoEntityTable($table_name, $module, $fileid = '') {
		global $log;
		global $current_user, $app_strings;
		global $adb;

        $log->debug("Entering PaymentManagement::insertIntoEntityTable(".$table_name.", ".$module.", ".$fileid.") method ...");
        $value_table = array();
		$insertion_mode = $this->mode;

		//Checkin whether an entry is already is present in the vtiger_table to update
		if ($insertion_mode == 'edit') {
			$tablekey = $this->tab_name_index[$table_name];
			// Make selection on the primary key of the module table to check.
			$check_query = "select $tablekey from $table_name where $tablekey=?";
			$check_result = $adb->pquery($check_query, array($this->id));
			$num_rows = $adb->num_rows($check_result);
			if ($num_rows <= 0) {
				$insertion_mode = '';
			}
		}
		$tabid = getTabid($module);
		if ($insertion_mode == 'edit') {
			$update = array();
			$update_params = array();
			checkFileAccessForInclusion('user_privileges/user_privileges_' . $current_user->id . '.php');
			require('user_privileges/user_privileges_' . $current_user->id . '.php');
			if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
				$sql = "select * from vtiger_field where tabid in (" . generateQuestionMarks($tabid) . ") and tablename=? and displaytype in (1,3) and presence in (0,2) group by columnname";
				$params = array($tabid, $table_name);
			} else {
				$profileList = getCurrentUserProfileList();

				if (count($profileList) > 0) {
					$sql = "SELECT *
			  			FROM vtiger_field
			  			INNER JOIN vtiger_profile2field
			  			ON vtiger_profile2field.fieldid = vtiger_field.fieldid
			  			INNER JOIN vtiger_def_org_field
			  			ON vtiger_def_org_field.fieldid = vtiger_field.fieldid
			  			WHERE vtiger_field.tabid = ?
			  			AND vtiger_profile2field.visible = 0 AND vtiger_profile2field.readonly = 0
			  			AND vtiger_profile2field.profileid IN (" . generateQuestionMarks($profileList) . ")
			  			AND vtiger_def_org_field.visible = 0 and vtiger_field.tablename=? and vtiger_field.displaytype in (1,3) and vtiger_field.presence in (0,2) group by columnname";

					$params = array($tabid, $profileList, $table_name);
				} else {
					$sql = "SELECT *
			  			FROM vtiger_field
			  			INNER JOIN vtiger_profile2field
			  			ON vtiger_profile2field.fieldid = vtiger_field.fieldid
			  			INNER JOIN vtiger_def_org_field
			  			ON vtiger_def_org_field.fieldid = vtiger_field.fieldid
			  			WHERE vtiger_field.tabid = ?
			  			AND vtiger_profile2field.visible = 0 AND vtiger_profile2field.readonly = 0
			  			AND vtiger_def_org_field.visible = 0 and vtiger_field.tablename=? and vtiger_field.displaytype in (1,3) and vtiger_field.presence in (0,2) group by columnname";
					$params = array($tabid, $table_name);
				}
			}
		} else {
			$table_index_column = $this->tab_name_index[$table_name];
			if ($table_index_column == 'id' && $table_name == 'vtiger_users') {
				$currentuser_id = $adb->getUniqueID("vtiger_users");
				$this->id = $currentuser_id;
			}
            $columname = $table_index_column;
            $fldvalue = $this->id;
			$column = array($table_index_column);
			$value = array($this->id);
            // vtiger_payment_management のインデックスを登録 (2015/11/26)
            $columname = $table_index_column; // tao
            $fldvalue = $this->id; // tao
            $value_table[$columname] = $fldvalue; // tao
			$sql = "select * from vtiger_field where tabid=? and tablename=? and displaytype in (1,3,4) and vtiger_field.presence in (0,2)";
			$params = array($tabid, $table_name);
		}
        
		// Attempt to re-use the quer-result to avoid reading for every save operation
		// TODO Need careful analysis on impact ... MEMORY requirement might be more
		static $_privatecache = array();
		$cachekey = "{$insertion_mode}-" . implode(',', $params);
		if (!isset($_privatecache[$cachekey])) {
			$result = $adb->pquery($sql, $params);
			$noofrows = $adb->num_rows($result);

			if (CRMEntity::isBulkSaveMode()) {
				$cacheresult = array();
				for ($i = 0; $i < $noofrows; ++$i) {
					$cacheresult[] = $adb->fetch_array($result);
				}
				$_privatecache[$cachekey] = $cacheresult;
			}
		} else { // Useful when doing bulk save
			$result = $_privatecache[$cachekey];
			$noofrows = count($result);
		}

		for ($i = 0; $i < $noofrows; $i++) {
			$fieldname = $this->resolve_query_result_value($result, $i, "fieldname");
			$columname = $this->resolve_query_result_value($result, $i, "columnname");
			$uitype = $this->resolve_query_result_value($result, $i, "uitype");
			$generatedtype = $this->resolve_query_result_value($result, $i, "generatedtype");
			$typeofdata = $this->resolve_query_result_value($result, $i, "typeofdata");
			$typeofdata_array = explode("~", $typeofdata);
			$datatype = $typeofdata_array[0];
			$ajaxSave = false;

            // uitype == 2
			if (($_REQUEST['file'] == 'DetailViewAjax' && $_REQUEST['ajxaction'] == 'DETAILVIEW' &&
            isset($_REQUEST["fldName"]) && $_REQUEST["fldName"] != $fieldname)
            || ($_REQUEST['action'] == 'MassEditSave' && !isset($_REQUEST[$fieldname."_mass_edit_check"]))) {
				$ajaxSave = true;
			}
			if ($uitype == 4 && $insertion_mode != 'edit') {
				$fldvalue = '';
				// Bulk Save Mode: Avoid generation of module sequence number, take care later.
				if (!CRMEntity::isBulkSaveMode())
					$fldvalue = $this->setModuleSeqNumber("increment", $module);
				$this->column_fields[$fieldname] = $fldvalue;
			}
			if (isset($this->column_fields[$fieldname])) {
				if ($uitype == 56) {
					if ($this->column_fields[$fieldname] == 'on' || $this->column_fields[$fieldname] == 1) {
						$fldvalue = '1';
					} else {
						$fldvalue = '0';
					}
				} elseif ($uitype == 15 || $uitype == 16) {
					if ($this->column_fields[$fieldname] == $app_strings['LBL_NOT_ACCESSIBLE']) {
						//If the value in the request is Not Accessible for a picklist,
                        //the existing value will be replaced instead of Not Accessible value.

						$sql = "select $columname from  $table_name where " . $this->tab_name_index[$table_name] . "=?";
						$res = $adb->pquery($sql, array($this->id));
						$pick_val = $adb->query_result($res, 0, $columname);
						$fldvalue = $pick_val;
					} else {
						$fldvalue = $this->column_fields[$fieldname];
					}
				} elseif ($uitype == 33) {
					if (is_array($this->column_fields[$fieldname])) {
						$field_list = implode(' |##| ', $this->column_fields[$fieldname]);
					} else {
						$field_list = $this->column_fields[$fieldname];
					}
					$fldvalue = $field_list;
				} elseif ($uitype == 5 || $uitype == 6 || $uitype == 23) {
					//Added to avoid function call getDBInsertDateValue in ajax save
					if (isset($current_user->date_format) && !$ajaxSave) {
						$fldvalue = getValidDBInsertDateValue($this->column_fields[$fieldname]);
					} else {
						$fldvalue = $this->column_fields[$fieldname];
					}
				} elseif ($uitype == 7) {
					//strip out the spaces and commas in numbers if given ie., in amounts there may be ,
					$fldvalue = str_replace(",", "", $this->column_fields[$fieldname]); //trim($this->column_fields[$fieldname],",");
				} elseif ($uitype == 26) {
					if (empty($this->column_fields[$fieldname])) {
						$fldvalue = 1; //the documents will stored in default folder
					} else {
						$fldvalue = $this->column_fields[$fieldname];
					}
				} elseif ($uitype == 28) {
					if ($this->column_fields[$fieldname] == null) {
						$fileQuery = $adb->pquery("SELECT filename from vtiger_notes WHERE notesid = ?", array($this->id));
						$fldvalue = null;
						if (isset($fileQuery)) {
							$rowCount = $adb->num_rows($fileQuery);
							if ($rowCount > 0) {
								$fldvalue = decode_html($adb->query_result($fileQuery, 0, 'filename'));
							}
						}
					} else {
						$fldvalue = decode_html($this->column_fields[$fieldname]);
					}
				} elseif ($uitype == 8) {
					$this->column_fields[$fieldname] = rtrim($this->column_fields[$fieldname], ',');
					$ids = explode(',', $this->column_fields[$fieldname]);
					$json = new Zend_Json();
					$fldvalue = $json->encode($ids);
				} elseif ($uitype == 12) {
					// Bulk Sae Mode: Consider the FROM email address as specified, if not lookup
					$fldvalue = $this->column_fields[$fieldname];
					if (empty($fldvalue)) {
						$query = "SELECT email1 FROM vtiger_users WHERE id = ?";
						$res = $adb->pquery($query, array($current_user->id));
						$rows = $adb->num_rows($res);
						if ($rows > 0) {
							$fldvalue = $adb->query_result($res, 0, 'email1');
						}
					}
					// END
				} elseif ($uitype == 72 && !$ajaxSave) {
					// Some of the currency fields like Unit Price, Totoal , Sub-total - doesn't need currency conversion during save
					$fldvalue = CurrencyField::convertToDBFormat($this->column_fields[$fieldname], null, true);
				} elseif ($uitype == 71 && !$ajaxSave) {
					$fldvalue = CurrencyField::convertToDBFormat($this->column_fields[$fieldname]);
				} else {
					$fldvalue = $this->column_fields[$fieldname];
				}
				if ($uitype != 33 && $uitype != 8)
					$fldvalue = from_html($fldvalue, ($insertion_mode == 'edit') ? true : false);
			} else {
				$fldvalue = '';
			}
			if ($fldvalue == '') {
				$fldvalue = $this->get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype);
			}
            // key-value 配列にキーと値の組を登録する
            $value_table[$columname] = $fldvalue; // tao
			if ($insertion_mode == 'edit') {
				if ($table_name != 'vtiger_ticketcomments' && $uitype != 4) {
					array_push($update, $columname . "=?");
					array_push($update_params, $fldvalue);
				}
			} else {
				array_push($column, $columname);
				array_push($value, $fldvalue);
			}
		}

		if ($insertion_mode == 'edit') {
            // ADDED by tao on 15/12/04 -- begin
            if ($table_name == 'vtiger_payment_management') {
                // 顧客名が指定されていない場合は、カナ名から顧客名を類推する。 */
                $value_table = $this->insertClientName($value_table);
                $update_params = PaymentManagement::generateValues($value_table);
                if ($value_table['accountname'] != '') {
                    $sql = 'update vtiger_crmentityrel set crmid=? where relcrmid=?';
                    $param = array($value_table['accountname'], $this->id);
                    $adb->pquery($sql, $param);
                }
            }
            // ADDED by tao on 15/12/04 -- end
			//Check done by Don. If update is empty the the query fails
			if (count($update) > 0) {
				$sql1 = "update $table_name set " . implode(",", $update) . " where " . $this->tab_name_index[$table_name] . "=?";
				array_push($update_params, $this->id);
				$adb->pquery($sql1, $update_params, true);
			}
		} else {
            // Added by 田尾 (tao) on 15/11/25 -- begin
            if ($module == 'PaymentManagement' && $table_name == 'vtiger_payment_management') {
                $value_table = $this->insertClientName($value_table);
                if ($value_table['accountname'] != '')
                    $this->save_related_module('Account', $value_table['accountname'], 'PaymentManagement', $value_table['payment_management_id']);
            }
            $value = PaymentManagement::generateValues($value_table);
            // Added by 田尾 (tao) on 15/11/25 -- end
  
            $sql1 = "insert into $table_name(" . implode(",", $column) . ") values(" . generateQuestionMarks($value) . ")";
			$adb->pquery($sql1, $value);
		}
        $log->debug("Exting PaymentManagement::insertIntoEntityTable(".$table_name.", ".$module.", ".$fileid.") method ...");
	}

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb;
 		if($eventType == 'module.postinstall') {
			// TODO Handle actions after this module is installed.
		} else if($eventType == 'module.disabled') {
			// TODO Handle actions before this module is being uninstalled.
		} else if($eventType == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
 	}

	function save_related_module($module, $crmid, $with_module, $with_crmids) {
		$adb = $this->db;

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Products')
				$adb->pquery("insert into vtiger_seproductsrel values(?,?,?)", array($crmid, $with_crmid, $module));
			elseif($with_module == 'Campaigns') {
				$checkResult = $adb->pquery('SELECT 1 FROM vtiger_campaignaccountrel WHERE campaignid = ? AND accountid = ?',
												array($with_crmid, $crmid));
				if($checkResult && $adb->num_rows($checkResult) > 0) {
					continue;
				}
				$adb->pquery("insert into vtiger_campaignaccountrel values(?,?,1)", array($with_crmid, $crmid));
			} else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

	function generateReportsSecQuery($module, $secmodule, $queryPlanner) {}

    // ADDED by tao on 15/12/03 -- begin
	/**
	 *      カナ名と同じ値を持つ顧客口座レコードがあれば、そのレコードの顧客名を設定する。
	 *      @param array $value_table - データテーブル
	 *      return データテーブル
	 */
    function insertClientName($value_table) {
		$adb = PearDatabase::getInstance();
        if ($value_table['accountname'] == '') { // 企業名が空の場合
            // 企業名カナと同じ値を持つレコードが顧客口座データベースにあれば、
            // その企業IDを使用する。
            $query_sql = "SELECT accountid FROM vtiger_client_account WHERE account_kananame=?";
            $query_value = array($value_table['kana']);
            $result = $adb->pquery($query_sql, $query_value);
            $num_rows = $adb->num_rows($result);

            if ($num_rows != 0) {
                while ($resultrow = $adb->fetch_array($result)) {
                    $value_table['accountname'] = $resultrow['accountid']; // 企業コードを表すカラム名がテーブルによって異なる
                    $value_table['association_status'] = 'OK';             // vtiger_client_account: accountid
                    break;  // 最初の一件のみを取得する。                  // 同じカナ名の顧客口座が見つかったので、取込ステータスにOKを設定する
                }
            } else {
                $value_table['association_status'] = 'NG'; // 企業名が明示的に指定されておらず、かつ、
            }                                              // 同名のカナ名を持つ顧客口座がない場合、取込ステータスにNGを設定する
        } else {
            $value_table['association_status'] = 'OK'; // 企業名が明示的に指定されている場合は取込ステータスにOKを設定する
        }
        return $value_table;
    }
    // ADDED by tao on 15/12/03 -- end
}
