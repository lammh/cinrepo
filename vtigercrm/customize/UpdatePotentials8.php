<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('Potentials');

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(1);




/* 確認済み */

$field = new Vtiger_Field();
$field->name = 'chackok';
$field->table = $module->basetable;
$field->column = 'chackok';
$field->columntype = 'INT(10)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata =  'C~O';
$field->label = $field->column;
$blockInstance->addField($field);

/* キャンセル */


$field = new Vtiger_Field();
$field->name = 'cancelkubun';
$field->table = $module->basetable;
$field->column = 'cancelkubun';
$field->columntype = 'INT(10)';
$field->uitype = 53;
$field->displaytype= 1;
$field->typeofdata =  'C~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* UP希望日 */
$field = new Vtiger_Field();
$field->name = 'up_hope_date';
$field->table = $module->basetable;
$field->column = 'up_hope_date';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 5;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'D~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* 入稿日　 */
$field = new Vtiger_Field();
$field->name = 'draft_date';
$field->table = $module->basetable;
$field->column = 'draft_date';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 5;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'D~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* 制作担当　 */
$field = new Vtiger_Field();
$field->name = 'draft_date';
$field->table = $module->basetable;
$field->column = 'draft_date';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 5;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'D~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* 制作内容 */
$field = new Vtiger_Field();
$field->name = 'create_kubun';
$field->table = $module->basetable;
$field->column = 'create_kubun';
$field->columntype = 'INT(10)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ("新規原稿", "完全流用", "一部流用") );


/* Sコード */
$field = new Vtiger_Field();
$field->name = 'scode';
$field->table = $module->basetable;
$field->column = 'scode';
$field->columntype = 'VARCHAR(100)';
$field->uitype = 1;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);




?>