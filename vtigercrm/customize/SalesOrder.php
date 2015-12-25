<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('SalesOrder');

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(61);


/* 計上週 */
$field = new Vtiger_Field();
$field->name = 'closing_week';
$field->table = $module->basetable;
$field->column = 'closing_week';
$field->columntype = 'INT(10)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array (1, 2, 3, 4, 5, 6) );


/* 部署 */
$field = new Vtiger_Field();
$field->name = 'division';
$field->table = $module->basetable;
$field->column = 'division';
$field->columntype =  'VARCHAR(240)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("101:求人広告","102:人材紹介","103:人材派遣","201:風評コンサル","202:Webコミュ","203:サロコン","204:Eコマース") );

/* 自力紹介 */
$field = new Vtiger_Field();
$field->name = 'selforintro';
$field->table = $module->basetable;
$field->column = 'selforintro';
$field->columntype =  'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("自力","紹介","引継ぎ","その他") );


/* エリア */
$field = new Vtiger_Field();
$field->name = 'closingarea';
$field->table = $module->basetable;
$field->column = 'closingarea';
$field->columntype =  'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("東京","大阪","その他") );





/* 成果基準 */
$field = new Vtiger_Field();
$field->name = 'outcome';
$field->table = $module->basetable;
$field->column = 'outcome';
$field->columntype =  'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("固定","成果") );



/* 売上額 */
$field = new Vtiger_Field();
$field->name = 'salesamount';
$field->table = $module->basetable;
$field->column = 'salesamount';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* 企画 */
$field = new Vtiger_Field();
$field->name = 'kikaku';
$field->table = $module->basetable;
$field->column = 'kikaku';
$field->columntype = 'VARCHAR(250)';
$field->uitype = 1;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* 原価 */
$field = new Vtiger_Field();
$field->name = 'costall';
$field->table = $module->basetable;
$field->column = 'costall';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* 紹介料 */
$field = new Vtiger_Field();
$field->name = 'costinfo';
$field->table = $module->basetable;
$field->column = 'costinfo';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* 初期費用 */
$field = new Vtiger_Field();
$field->name = 'costnew';
$field->table = $module->basetable;
$field->column = 'costnew';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* 媒体 */
$field = new Vtiger_Field();
$field->name = 'baitai';
$field->table = $module->basetable;
$field->column = 'baitai';
$field->columntype =  'VARCHAR(240)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("TW","TW社員","TWモバS","an","フロムＡナビ","バイトル","マイナビバイト","アイデム","DOMO","リクナビネクスト","マイナビ転職","とらばーゆ"
//,"はたらいく","DODA","リクナビ新卒","マイナビ新卒","イーキャリア","@type","女の転職","その他","キックバック手数料"

//) );



/* タイプ */
$field = new Vtiger_Field();
$field->name = 'acfrom';
$field->table = $module->basetable;
$field->column = 'acfrom';
$field->columntype =  'VARCHAR(240)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("ストック","ショット") );





/* 社内原価 */
$field = new Vtiger_Field();
$field->name = 'costsyanai';
$field->table = $module->basetable;
$field->column = 'costsyanai';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* 社内原価区分 */
$field = new Vtiger_Field();
$field->name = 'costsyanaikb';
$field->table = $module->basetable;
$field->column = 'costsyanaikb';
$field->columntype ='VARCHAR(200)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("1:技術","2:マーケ","3:制作","4:ディレクション","5:風評技術") );



/* 目標 */
$field = new Vtiger_Field();
$field->name = 'mokuhyou';
$field->table = $module->basetable;
$field->column = 'mokuhyou';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* 粗利 */
$field = new Vtiger_Field();
$field->name = 'amount';
$field->table = $module->basetable;
$field->column = 'amount';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);

?>