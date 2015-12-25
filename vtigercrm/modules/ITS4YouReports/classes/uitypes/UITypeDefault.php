<?php

/*+********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

class UITypeDefault extends UITypes {

    public function getStJoinSQL(&$join_array, &$columns_array) {
        return "";
    }

    public function getJoinSQL(&$join_array, &$columns_array) {
        $old_oth_as = $old_oth_fieldid = $mif_as = "";
        if ($this->params["old_oth_as"] != "") {
            $old_oth_as = $this->params["old_oth_as"];
        }
        if ($this->params["old_oth_fieldid"] != "") {
            if ($this->params["old_oth_fieldid"] == "mif") {
                $mif_as = "_" . $this->params["fieldtabid"];
            }
            $old_oth_fieldid = "_" . $this->params["old_oth_fieldid"];
        }

        $join_tablename = " " . $this->params["using_array"]["join"]["tablename"];
        $clear_tablename = $this->params["tablename"];
        $join_columnname = $this->params["using_array"]["join"]["columnname"];

        $join_tablename_alias = $this->params["join_tablename_alias"] = $clear_tablename . $old_oth_as . $old_oth_fieldid . $mif_as;
        $join_alias = " " . $clear_tablename . " AS " . $join_tablename_alias . " ";
        if ($this->params["primary_table_name"]!=$join_tablename_alias && isset($this->params["using_array"]) && !empty($this->params["using_array"]["using"]) && $clear_tablename != "vtiger_crmentity") {
            $using_tablename = $this->params["using_array"]["using"]["tablename"];
            $using_columnname = $this->params["using_array"]["using"]["columnname"];
            if ($join_tablename != $this->params["primary_table_name"] && $using_tablename != "" && $using_columnname != "") {
                $join_array[$join_alias]["joincol"] = $join_tablename_alias . "." . $join_columnname;
                $join_array[$join_alias]["using"] = $using_tablename . "." . $using_columnname;
            }
        }
        $fld_alias = $this->params["columnname"] . $old_oth_fieldid;

        if($this->params["columnname"]=="access_count"){
                if ($this->params["columnname"]=="access_count") {

                        if (!array_key_exists(" vtiger_email_track AS vtiger_email_track_mif_$rel_tabid ", $join_array)) {
                $join_array[" vtiger_email_track AS vtiger_email_track "]["joincol"] = "vtiger_email_track.mailid";
                $join_array[" vtiger_email_track AS vtiger_email_track "]["using"] = "vtiger_crmentity.crmid";
            }
                if(array_key_exists(" vtiger_email_track AS vtiger_email_track ", $join_array)){
                $access_count_sql .= " IF(vtiger_email_track.access_count IS NOT NULL, vtiger_email_track.access_count,0) ";
            }
                        $fld_alias = "access_count";
            if($access_count_sql!=""){
                $fld_cond = " $access_count_sql ";
                $access_count_col_sql = "$fld_cond AS $fld_alias ";
            }else{
                $access_count_col_sql = " 0 AS $fld_alias ";
            }
            if(!in_array($access_count_col_sql,$columns_array)){
                $columns_array[] = $access_count_col_sql;
                $columns_array[$this->params["fld_string"]]["fld_alias"] = $fld_alias;
                $columns_array[$this->params["fld_string"]]["fld_sql_str"] = $access_count_col_sql;
                $columns_array[$this->params["fld_string"]]["fld_cond"] = $fld_cond;
                $columns_array["uitype_$fld_alias"] = $this->params["field_uitype"];
                $columns_array[$fld_alias] = $this->params["fld_string"];
            }
                }			
        }elseif ($this->params["tablename"] == "vtiger_activity" && (in_array($this->params["columnname"], array("status", "eventstatus")))) {
            $fld_cond = " IF($join_tablename_alias.activitytype = 'Task',$join_tablename_alias.status,$join_tablename_alias.eventstatus) ";
            $columns_array_value = $fld_cond . " AS " . $this->params["columnname"] . $old_oth_fieldid;
        } elseif ($this->params["tablename"] == "vtiger_activity" && (in_array($this->params["columnname"], array("date_start", "time_start")))) {
            if (array_key_exists(" concat($join_tablename_alias.date_start,' ',$join_tablename_alias.time_start) ", $columns_array))
                return "";
            $fld_cond = " concat($join_tablename_alias.date_start,'  ',$join_tablename_alias.time_start) ";
            $columns_array_value = $fld_cond . " AS " . $this->params["columnname"] . $old_oth_fieldid;
        }elseif ($this->params["tablename"] == "vtiger_activity" && (in_array($this->params["columnname"], array("due_date", "time_end")))) {
            if (array_key_exists(" concat($join_tablename_alias.due_date,' ',$join_tablename_alias.time_end) ", $columns_array))
                return "";
            $fld_cond = " concat($join_tablename_alias.due_date,'  ',$join_tablename_alias.time_end) ";
            $columns_array_value = $fld_cond . " AS " . $this->params["columnname"] . $old_oth_fieldid;
        }elseif ($this->params["tablename"] == "vtiger_notes" && (in_array($this->params["columnname"], array("folderid")))) {
            $clear_foldertablename = "vtiger_attachmentsfolder";
            $folder_columnname = "folderid";
            $fld_alias = "foldername" . $old_oth_fieldid;
            $alias_foldertablename = $clear_foldertablename . $old_oth_as . $old_oth_fieldid . $mif_as;
            $join_alias = " " . $clear_foldertablename . " AS " . $alias_foldertablename . " ";
            if (!array_key_exists($join_alias, $join_array) && $this->params["primary_table_name"]!=$clear_foldertablename) {
                if(isset($this->params["using_aliastablename"]) && $this->params["using_aliastablename"]!=""){
                    $using_tablename = $this->params["using_aliastablename"];
                }else{
                    $using_tablename = $this->params["tablename"];
                }
                $using_columnname = $this->params["columnname"];
                $join_array[$join_alias]["joincol"] = $alias_foldertablename . "." . $folder_columnname;
                $join_array[$join_alias]["using"] = $using_tablename . "." . $using_columnname;
            }

            $fld_cond = " $alias_foldertablename.$fld_alias ";
            $columns_array_value = $fld_cond . " AS $fld_alias" . $old_oth_fieldid;
        }elseif ($this->params["tablename"] == "vtiger_activity" && in_array($this->params["columnname"], array("activitytype")) && $this->params["fieldtabid"]!==$this->params["primary_tableid"]) {
            $fld_cond = "IF(".$join_tablename_alias . "." . $this->params["columnname"]." IS NOT NULL,".$join_tablename_alias.".".$this->params["columnname"].",".$join_tablename_alias.".".$this->params["columnname"].")";
            $columns_array_value = "$fld_cond AS ".$this->params["columnname"] . $old_oth_fieldid;
        } else {
            $fld_cond = $join_tablename_alias . "." . $this->params["columnname"];
            $columns_array_value = $fld_cond . " AS " . $this->params["columnname"] . $old_oth_fieldid;
        }

        $columns_array[] = $columns_array_value;
        $columns_array[$this->params["fld_string"]]["fld_alias"] = $fld_alias;
        $columns_array[$this->params["fld_string"]]["fld_sql_str"] = $columns_array_value;
        $columns_array[$this->params["fld_string"]]["fld_cond"] = $fld_cond;
        $columns_array["uitype_$fld_alias"] = $this->params["field_uitype"];
        $columns_array[$fld_alias] = $this->params["fld_string"];
        if ($fld_hrefid != "") {
            $columns_array[] = $fld_hrefid;
        }
    }

    public function getInventoryJoinSQL(&$join_array, &$columns_array) {
        $fieldid_alias = "";
        if ($this->params["fieldid"] != "") {
            $fieldid_alias = "_" . $this->params["fieldid"];
        }

        if (isset($this->params["using_array"]) && !empty($this->params["using_array"]["using"])) {
            $using_tablename = $this->params["using_array"]["using"]["tablename"];
            $using_columnname = $this->params["using_array"]["using"]["columnname"];
        }else{
            $using_tablename = $this->params["using_aliastablename"];
            $using_columnname = $this->params["using_columnname"];
        }
        
        if(isset($this->params["formodule"])){
            $fieldid_alias = "_".$this->params["formodule"].$fieldid_alias;
        }
        if ($using_tablename != "" && $using_columnname != "") {
            $join_array[" vtiger_inventoryproductrel AS vtiger_inventoryproductrel" . $fieldid_alias . " "]["joincol"] = "vtiger_inventoryproductrel" . $fieldid_alias . ".id";
            $join_array[" vtiger_inventoryproductrel AS vtiger_inventoryproductrel" . $fieldid_alias . " "]["using"] = $using_tablename . "." . $using_columnname;
        }
        $join_array[" vtiger_products AS vtiger_products_inv" . $fieldid_alias . " "]["joincol"] = "vtiger_products_inv" . $fieldid_alias . ".productid";
        $join_array[" vtiger_products AS vtiger_products_inv" . $fieldid_alias . " "]["using"] = "vtiger_inventoryproductrel" . $fieldid_alias . ".productid";
        $join_array[" vtiger_crmentity AS vtiger_crmentity_products_inv" . $fieldid_alias . " "]["joincol"] = "vtiger_crmentity_products_inv" . $fieldid_alias . ".crmid";
        $join_array[" vtiger_crmentity AS vtiger_crmentity_products_inv" . $fieldid_alias . " "]["using"] = "vtiger_products_inv" . $fieldid_alias . ".productid AND vtiger_crmentity_products_inv" . $fieldid_alias . ".deleted=0 ";

        $join_array[" vtiger_service AS vtiger_service_inv" . $fieldid_alias . " "]["joincol"] = "vtiger_service_inv" . $fieldid_alias . ".serviceid";
        $join_array[" vtiger_service AS vtiger_service_inv" . $fieldid_alias . " "]["using"] = "vtiger_inventoryproductrel" . $fieldid_alias . ".productid";
        $join_array[" vtiger_crmentity AS vtiger_crmentity_service_inv" . $fieldid_alias . " "]["joincol"] = "vtiger_crmentity_service_inv" . $fieldid_alias . ".crmid";
        $join_array[" vtiger_crmentity AS vtiger_crmentity_service_inv" . $fieldid_alias . " "]["using"] = "vtiger_service_inv" . $fieldid_alias . ".serviceid AND vtiger_crmentity_service_inv" . $fieldid_alias . ".deleted=0 ";

        $column_tablename = "vtiger_inventoryproductrel";

        if ($this->params["columnname"] != "") {
            $column_tablename_alias = $this->params["tablename"];
            if ($column_tablename_alias == "vtiger_crmentity") {
                $column_tablename_alias = $column_tablename . "_" . strtolower($this->params["fieldmodule"]) . "_inv";
            }

            $fld_cond = $this->getInventoryColumnFldCond($this->params["columnname"],$column_tablename_alias,$fieldid_alias);
            $columns_array_value = $this->getColumnsArrayValue($fld_cond,$fieldid_alias);
            $fld_alias = $this->params["columnname"] . $fieldid_alias;

            if(!in_array(" vtiger_inventoryproductrel" . $fieldid_alias . ".lineitem_id AS lineitem_id" . $fieldid_alias . " ",$columns_array)){
                $columns_array[] = " vtiger_inventoryproductrel" . $fieldid_alias . ".lineitem_id AS lineitem_id" . $fieldid_alias . " ";
            }
            if($using_tablename!="" && $using_columnname!=""){
                $columns_array[] = " $using_tablename.$using_columnname AS record_id" . $fieldid_alias . " ";
            }
            $columns_array[] = $columns_array_value;
            $columns_array[$this->params["fld_string"]]["fld_alias"] = $fld_alias;
            $columns_array[$this->params["fld_string"]]["fld_sql_str"] = $columns_array_value;
            $columns_array[$this->params["fld_string"]]["fld_cond"] = $fld_cond;
            $columns_array["uitype_$fld_alias"] = $this->params["field_uitype"];
            $columns_array[$fld_alias] = $this->params["fld_string"];
        }
        return;
    }

    // ITS4YOU-CR SlOl 17. 10. 2013 12:00:47
    public function getMoreInfoJoinSQL(&$join_array, &$columns_array) {
        $uifactory = new UIFactory($this->params);
        $rel_array = $uifactory->getRelationTables($join_array, $columns_array);
    }

    public function getModulesByUitype($tablename, $columnname) {
        $modulename = array();
        return $modulename;
    }

    public function getSelectedFieldCol($selectedfields) {
        $return = array();
        $fieldid_alias = "";
        /* if($this->params["fieldid"]!=""){
          $fieldid_alias = "_".$this->params["fieldid"];
          } */
        $return = $selectedfields[0] . $fieldid_alias . "." . $selectedfields[1];

        return $return;
    }

}

?>