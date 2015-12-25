<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('Accounts');

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(9);

/* 資本金区分 */
$field = new Vtiger_Field();
$field->name = 'capital_type';
$field->table = $module->basetable;
$field->column = 'capital_type';
$field->columntype = 'VARCHAR(50)';
$field->uitype = 15;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);
$array = array("a_0～1000万円","b_1001万～3000万円", "c_3001万～5000万円", "d_5001万～1億円", "e_1億1円以上", "z_不明");
$field->setPicklistValues( $array );

/* 決算月 */
$field = new Vtiger_Field();
$field->name = 'closing_month';
$field->table = $module->basetable;
$field->column = 'closing_month';
$field->columntype = 'INT(10)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12) );

/* クライアントタイプ */
$field = new Vtiger_Field();
$field->name = 'client_type';
$field->table = $module->basetable;
$field->column = 'client_type';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);
$array = array("直顧客","代理店","グループ","その他");
$field->setPicklistValues( $array );


/* 請求締 */
$field = new Vtiger_Field();
$field->name = 'close_request';
$field->table = $module->basetable;
$field->column = 'close_request';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);
$array = array("月末〆","10日〆","20日〆");
$field->setPicklistValues( $array );




/* 支払日 */
$field = new Vtiger_Field();
$field->name = 'payday';
$field->table = $module->basetable;
$field->column = 'payday';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);
$array = array("翌月10日","翌月20日","翌月末(30日)","翌々月10日(40日)","翌々月15日(45日)","翌々月20日(50日)","翌々月末日(60日)");
$field->setPicklistValues( $array );



/* 備考 */
$field = new Vtiger_Field();
$field->name = 'biko';
$field->table = $module->basetable;
$field->column = 'biko';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 21;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);

/* 初回取引日 */
$field = new Vtiger_Field();
$field->name = 'first_trading';
$field->table = $module->basetable;
$field->column = 'first_trading';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 5;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'D~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* JGID */
$field = new Vtiger_Field();
$field->name = 'jgid';
$field->table = $module->basetable;
$field->column = 'jgid';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 20;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;
$blockInstance->addField($field);


/* KintoneID */
$field = new Vtiger_Field();
$field->name = 'Kintoneid';
$field->table = $module->basetable;
$field->column = 'Kintoneid';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 20;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;
$blockInstance->addField($field);



/* Scode */
$field = new Vtiger_Field();
$field->name = 'scode';
$field->table = $module->basetable;
$field->column = 'scode';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 20;
$field->displaytype= 1;
$field->typeofdata = 'V';
$field->label = $field->column;
$blockInstance->addField($field);
