<?php

/*+********************************************************************************
 * The content of this file is subject to the Reports 4 You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

class UITypes {

    protected $relModuleName = '';
    protected $oth_as = '';
    protected $params = Array();

    function __construct($params) {
        $this->params = $params;
    }
    
    public function getJoinSQLbyFieldRelation(&$join_array, &$columns_array) {
        $related_focus = CRMEntity::getInstance($this->relModuleName);

        $params_fieldname = $this->params["fieldname"];
        // first join to vtiger module table 
        $this->params["fieldname"] = $related_focus->tab_name_index[$this->params["tablename"]];

        $this->getStJoinSQL($join_array, $columns_array);

        $r_tabid = getTabid($this->relModuleName);
        $adb = PEARDatabase::getInstance();
        $uirel_row = $adb->fetchByAssoc($adb->pquery("SELECT *  FROM vtiger_field WHERE tabid = ? AND fieldname = ?", array($r_tabid, $params_fieldname)), 0);

        $related_table_name = $related_focus->table_name;
        $related_table_index = $related_focus->table_index;
        foreach ($related_focus->tab_name as $other_table) {
            $related_join_array[$other_table] = $related_focus->tab_name_index[$other_table];
        }
        $field_uitype = $uirel_row["uitype"];
        $fieldid = $this->params["fieldid"];
        $oth_as = "";
        if ($uirel_row["tablename"] == "vtiger_crmentity") {
            $oth_as = $this->oth_as;
            $related_table_name = $uirel_row["tablename"];
            $related_table_index = $uirel_row["columnname"];
        }
        $using_aliastablename = $related_table_name . $oth_as . $fieldid;
        $using_columnname = $related_table_index;

        $params = Array('fieldid' => $uirel_row["fieldid"],
            'fieldtabid' => $uirel_row["tabid"],
            'field_uitype' => $field_uitype,
            'fieldname' => $uirel_row["fieldname"],
            'columnname' => $uirel_row["columnname"],
            'tablename' => $uirel_row["tablename"],
            'table_index' => $related_join_array,
            'report_primary_table' => $this->params["report_primary_table"],
            'primary_table_name' => $related_focus->table_name,
            'primary_table_index' => $related_focus->table_index,
            'primary_tableid' => $r_tabid,
            'using_aliastablename' => $using_array["u_tablename"],
            'using_columnname' => $using_array["u_tableindex"],
            'old_oth_as' => $oth_as,
            'old_oth_fieldid' => $fieldid,
            'fld_string' => $this->params["fld_string"],
        );
        $using_array = getJoinInformation($params);
        $params["using_array"] = $using_array;
        $uifactory = new UIFactory($params);
//show("<font color='green'>fielduitype".$field_uitype."_IN_P_".$field_uitype,$related_join_array,$params["using_array"],"</font>");
        $uifactory->getJoinSQL($field_uitype, $join_array, $columns_array);
    }
    
    public function getInventoryJoinSQL(&$join_array, &$columns_array) {
        return;
    }
    
    public function getModulesByUitype($tablename, $columnname) {
        $modulename[] = $this->relModuleName;
        return $modulename;
    }
    
    protected function getInventoryColumnFldCond($columnName,$column_tablename_alias,$fieldid_alias="",$primary_table_name=""){
        switch ($columnName) {
            case 'prodname':
                $fld_cond = " CASE WHEN (vtiger_products_inv" . $fieldid_alias . ".productname IS NOT NULL) THEN vtiger_products_inv" . $fieldid_alias . ".productname ELSE vtiger_service_inv" . $fieldid_alias . ".servicename END ";
                break;
            
            case 'discount':
                //$fld_cond = " CASE WHEN (vtiger_inventoryproductrel" . $fieldid_alias . ".discount_amount != '') THEN vtiger_inventoryproductrel" . $fieldid_alias . ".discount_amount else ROUND((vtiger_inventoryproductrel" . $fieldid_alias . ".listprice * vtiger_inventoryproductrel" . $fieldid_alias . ".quantity * (vtiger_inventoryproductrel" . $fieldid_alias . ".discount_percent/100)),3) END ";
                $fld_cond = $this->getInventorySubColumnSql($columnName,$fieldid_alias,$primary_table_name);
                break;
            
            case 'ps_producttotal':
                $fld_cond = $this->getInventorySubColumnSql($columnName,$fieldid_alias,$primary_table_name);
                break;
            
            case 'ps_productstotalafterdiscount':
                $ps_producttotal = $this->getInventorySubColumnSql('ps_producttotal',$fieldid_alias,$primary_table_name);
                $discount = $this->getInventorySubColumnSql('discount',$fieldid_alias,$primary_table_name);
                $fld_cond = " ( $ps_producttotal - $discount )";
                break;
            
            case 'ps_productvatsum':
                $ps_producttotal = $this->getInventorySubColumnSql('ps_producttotal',$fieldid_alias,$primary_table_name);
                $discount = $this->getInventorySubColumnSql('discount',$fieldid_alias,$primary_table_name);
                $ps_productvatpercent = $this->getInventorySubColumnSql("ps_productvatpercent",$fieldid_alias,$primary_table_name);
                $fld_cond = " ( ( $ps_producttotal - $discount ) * ($ps_productvatpercent/100) ) ";
                break;
            
            // DOKONC
            case 'ps_producttotalsum':
                $ps_producttotal = $this->getInventorySubColumnSql('ps_producttotal',$fieldid_alias,$primary_table_name);
                $discount = $this->getInventorySubColumnSql('discount',$fieldid_alias,$primary_table_name);
                $ps_productvatpercent = $this->getInventorySubColumnSql("ps_productvatpercent",$fieldid_alias,$primary_table_name);
                $fld_cond = " ( ( $ps_producttotal - $discount ) + ( ( $ps_producttotal - $discount ) * ($ps_productvatpercent/100) ) )";
                break;
            
            case 'ps_productcategory':
                $fld_cond = " CASE WHEN (vtiger_products_inv" . $fieldid_alias . ".productid IS NOT NULL) THEN vtiger_products_inv" . $fieldid_alias . ".productcategory ELSE vtiger_service_inv" . $fieldid_alias . ".servicecategory END ";
                break;
            
            case 'ps_productno':
                $fld_cond = " CASE WHEN (vtiger_products_inv" . $fieldid_alias . ".productid IS NOT NULL) THEN vtiger_products_inv" . $fieldid_alias . ".product_no ELSE vtiger_service_inv" . $fieldid_alias . ".service_no END ";
                break;
            
            default:
                $fld_cond = "vtiger_inventoryproductrel".$fieldid_alias . "." . $this->params["columnname"];
                break;
        }
        return $fld_cond;
    }
    
    protected function getColumnsArrayValue($fld_cond,$fieldid_alias=""){
        $columns_array_value = $fld_cond . " AS " . $this->params["columnname"] . $fieldid_alias;
        return $columns_array_value;
    }
    
    protected function getInventorySubColumnSql($columnName="",$fieldid_alias="",$primary_table_name=""){
        $columnSql = "";
        switch ($columnName) {
            case 'discount':
                // old discount sql
                // $columnSql = " CASE WHEN (vtiger_inventoryproductrel" . $fieldid_alias . ".discount_amount != '') THEN vtiger_inventoryproductrel" . $fieldid_alias . ".discount_amount else ROUND((vtiger_inventoryproductrel" . $fieldid_alias . ".listprice * vtiger_inventoryproductrel" . $fieldid_alias . ".quantity * (vtiger_inventoryproductrel" . $fieldid_alias . ".discount_percent/100)),3) END ";
                // new discount sql
                $columnSql = " (CASE WHEN (vtiger_inventoryproductrel" . $fieldid_alias . ".discount_amount != '') THEN vtiger_inventoryproductrel" . $fieldid_alias . ".discount_amount 
                                WHEN (vtiger_inventoryproductrel" . $fieldid_alias . ".discount_percent IS NOT NULL AND vtiger_inventoryproductrel" . $fieldid_alias . ".discount_percent != '') THEN ROUND((vtiger_inventoryproductrel" . $fieldid_alias . ".listprice * vtiger_inventoryproductrel" . $fieldid_alias . ".quantity * (vtiger_inventoryproductrel" . $fieldid_alias . ".discount_percent/100)),3) 
                                ELSE 0 END) ";
                break;
            case 'ps_producttotal':
                $columnSql = " (vtiger_inventoryproductrel".$fieldid_alias . ".quantity * vtiger_inventoryproductrel".$fieldid_alias . ".listprice) ";
                break;
            case 'ps_productvatpercent':
                if($primary_table_name==""){
                    $primary_table_name = $this->params["primary_table_name"].$fieldid_alias;
                }
                $columnSql = " ( 
                                CASE WHEN ($primary_table_name.taxtype = 'individual' AND vtiger_inventoryproductrel" . $fieldid_alias . ".tax1 IS NOT NULL AND vtiger_inventoryproductrel" . $fieldid_alias . ".tax1 != '') THEN vtiger_inventoryproductrel" . $fieldid_alias . ".tax1 ELSE 0 END 
                                 + 
                                CASE WHEN ($primary_table_name.taxtype = 'individual' AND vtiger_inventoryproductrel" . $fieldid_alias . ".tax2 IS NOT NULL AND vtiger_inventoryproductrel" . $fieldid_alias . ".tax2 != '') THEN vtiger_inventoryproductrel" . $fieldid_alias . ".tax2 ELSE 0 END 
                                 + 
                                 CASE WHEN ($primary_table_name.taxtype = 'individual' AND vtiger_inventoryproductrel" . $fieldid_alias . ".tax3 IS NOT NULL AND vtiger_inventoryproductrel" . $fieldid_alias . ".tax3 != '') THEN vtiger_inventoryproductrel" . $fieldid_alias . ".tax3 ELSE 0 END 
                                ) ";
                break;
        }
        return $columnSql;
    }
    
}
?>