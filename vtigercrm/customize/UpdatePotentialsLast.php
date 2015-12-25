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





/** START 区分 ****************/
$field = new Vtiger_Field();
$field->name = 'tranlist';  #columnname fieldname
$field->table = $module->basetable; 
$field->column = 'tranlist';
$field->columntype =  'VARCHAR(255)';
$field->uitype = 15;  #リストを選ぶ
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
$field->setPicklistValues( array ("admin cin","有馬 海人","青木 優","伊藤 裕哉","稲垣 義弘","馬谷 義広","坂井 拓哉","高橋 翔","斎藤 正憲","早川 博通","垪和 千鶴","関 琴未","柳田 千尋","嶋崎 真太郎") );
/** END  区分 ****************/



?>