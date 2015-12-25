<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');

//Module名を入力
$module = Vtiger_Module::getInstance('Products'); #受注モジュール

$blockInstance = new Vtiger_Block();
// label名で既存ブロックを取得する
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);

//ブロックIDを入力
$blockInstance = $blockInstance->getInstance(130);  #HR

/** START Rジャンル ****************/
$field = new Vtiger_Field();
$field->name = 'r_genre';  #fieldname
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'r_genre'; #入力フォームの項目名(columnname, fieldLabel)
$field->columntype =  'VARCHAR(255)';
$field->uitype = 15;  #リストを選ぶ
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ('FM','SM','RB','R以外') );
/** END Rジャンル ****************/

/** START 区分 ****************/
$field = new Vtiger_Field();
$field->name = 'section';  #columnname fieldname
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'section';
$field->columntype =  'VARCHAR(255)';
$field->uitype = 15;  #リストを選ぶ
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ('AP','社員','新卒') );
/** END  区分 ****************/

/** START 版元 ******/
$field = new Vtiger_Field();
$field->name = 'publisher';  #columnname fieldname
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'publisher';
$field->columntype =  'VARCHAR(255)';
$field->uitype = 15;  #リストを選ぶ
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ('リクルート(R請求)','リクルート(R請求)','リクルート(新卒・CIN請求）','インテリジェンス(an/DODA）','マイナビ','ディップ(バイトル)','ネオキャリア(@type/女の転職)','イーキャリア','クリエイト','求人ジャーナル','アスコム','案内広告社(DOMO)','毎日エージェンシー(アイデム)','マージン調整分','その他(備考欄へ)') );
/** END   版元 ******/

/** START 請求希望日 ******/
$field = new Vtiger_Field();
$field->name = 'billing_dates';  #columnname fieldname
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'billing_dates';
$field->columntype = 'VARCHAR(100)';
$field->typeofdata = 'D~O';
$field->uitype = 5;  #5_日付
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END 請求希望日 ******/

/** START 支払予定日  *******/
$field = new Vtiger_Field();
$field->name = 'payment_due_date';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'payment_due_date'; #入力フォームの項目名(columnname, fieldLabel) 日本語ダメ
$field->columntype = 'VARCHAR(100)';
$field->typeofdata = 'D~O';
$field->uitype = 5;  #5_日付
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   支払予定日  *******/

/** START ボーナス ******/
$field = new Vtiger_Field();
$field->name = 'bonus';  #columnname fieldname
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'bonus';
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   ボーナス ******/

/** START R状況 ******/
$field = new Vtiger_Field();
$field->name = 'r_situation';  #fieldname
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'r_situation'; #入力フォームの項目名(columnname, fieldLabel)
$field->columntype =  'VARCHAR(255)';
$field->uitype = 15;  #リストを選ぶ
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ('リピート','新規','抜き') );
/** END   R状況 ******/

/** START 基本マージン ******/
$field = new Vtiger_Field();
$field->name = 'margin_01';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'margin_01'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END 基本マージン ******/

/** START バイトル基本マージン ******/
$field = new Vtiger_Field();
$field->name = 'margin_02';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'margin_02'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   バイトル基本マージン ******/

/** START 新規マージン ******/
$field = new Vtiger_Field();
$field->name = 'margin_03';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'margin_03'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   新規マージン ******/

/** START リピートマージン ******/
$field = new Vtiger_Field();
$field->name = 'margin_04';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'margin_04'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   リピートマージン ******/

/** START 特別マージン ******/
$field = new Vtiger_Field();
$field->name = 'margin_05';  #fieldname 日本語ダメ
$field->table = $module->basetable; #vtiger_salesorder
$field->column = 'margin_05'; #入力フォームの項目名(columnname, fieldLabel)日本語ダメ
$field->columntype = 'INT(11)'; //カラム名の型
$field->uitype = 7;  #7_数値
$field->typeofdata = 'I~O';
$field->displaytype= 1;
$field->label = $field->column;
$blockInstance->addField($field);
/** END   特別マージン ******/

?>