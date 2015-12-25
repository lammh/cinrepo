<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
/*************************************************************************************
 * Description:  Defines the Japanese language pack
 * Contributor(s): 
 *    vtiger CRM Japanese project (http://forge.vtiger.com/projects/japaneselang/),
 *    h.hokawa <hhokawa @nospam@ gmail.com>,
 *    fan site - http://www.vtigercrm.jp, toshi, hime etc.
 * License: MPL1.1.
 * All Rights Reserved.
 *************************************************************************************/

$languageStrings = array(
	'Reports'=>'レポート',
	'SINGLE_Reports' => 'レポート',

 // Basic Strings
	'LBL_FOLDER_NOT_EMPTY' => 'フォルダが空ではありません',
	'LBL_MOVE_REPORT' => 'レポートの移動',
	'LBL_CUSTOMIZE' => 'カスタマイズ',
	'LBL_REPORT_EXPORT_EXCEL' => 'Excel へエクスポート',
	'LBL_REPORT_PRINT' => 'レポートの印刷',
	'LBL_STEP_1' => 'ステップ 1 ',
	'LBL_STEP_2' => 'ステップ 2 ',
	'LBL_STEP_3' => 'ステップ 3 ',
	'LBL_REPORT_DETAILS' => 'レポートの詳細',
	'LBL_SELECT_COLUMNS' => 'カラムの選択',
	'LBL_FILTERS' => 'フィルタ',
	'LBL_FOLDERS' => 'レポート フォルダ',
	'LBL_ADD_NEW_FOLDER' => '新規レポート フォルダの作成',
	'LBL_FOLDER_NAME' => 'フォルダ名',
	'LBL_FOLDER_DESCRIPTION' => 'フォルダの説明',
	'LBL_WRITE_YOUR_DESCRIPTION_HERE' => '説明を入力します',
	'LBL_DUPLICATES_EXIST' => 'レポート名が既に存在します',
	'LBL_FOLDERS_LIST' => 'フォルダの一覧',
	'LBL_DENIED_REPORTS' => '拒否されたレポート',
	'LBL_NO_OF_RECORDS' => 'レコード数： ',
 //ListView Actions
	'LBL_ADD_RECORD' => 'レポートの追加',
	'LBL_ADD_FOLDER' => 'フォルダの追加',
	'LBL_REPORT_DELETE_DENIED' => 'レポートを削除する権限がありません',

 //Folder Actions
	'LBL_FOLDER_NOT_EMPTY' => '削除しようとしたフォルダは空ではありません。フォルダ内のレポートを移動または削除してください。',
	'LBL_FOLDER_CAN_NOT_BE_DELETED' => 'このフォルダは削除できません',

 //Mass Actions
	'LBL_REPORTS_LIST' => 'レポートの一覧',

 //Step1 Strings
	'LBL_REPORT_NAME' => 'レポート名',
	'LBL_REPORT_FOLDER' => 'レポート フォルダ',
	'LBL_DESCRIPTION' => '説明',
	'PRIMARY_MODULE' => '主要モジュール',
	'LBL_SELECT_RELATED_MODULES' => '関連モジュールの選択',
	'LBL_MAX' => '最大：',
	'LBL_NEXT' => '次へ',
	'LBL_REPORTS' => 'レポートの一覧',

 //Step2 Strings
	'LBL_GROUP_BY' => 'グループ化',
	'LBL_SORT_ORDER' => '並べ替え順',
	'LBL_ASCENDING' => '昇順',
	'LBL_DESCENDING' => '降順',
	'LBL_CALCULATIONS' =>'計算',
	'LBL_COLUMNS' => 'カラム',
	'LBL_SUM_VALUE' => '合計',
	'LBL_AVERAGE' => '平均',
	'LBL_LOWEST_VALUE' => '最低値',
	'LBL_HIGHEST_VALUE' => '最大値',

 //Step3 Strings
	'LBL_GENERATE_REPORT' => 'レポートの生成',

 //DetailView
	'LBL_SUM' => '合計',
	'LBL_AVG' => '平均',
	'LBL_MAX' => '最大',
	'LBL_MIN' => '最小',
	'LBL_FIELD_NAMES' => 'フィールド名',
	'LBL_REPORT_CSV' => 'CSV エクスポート',
	'LBL_VIEW_DETAILS' => '詳細の表示',
	'LBL_GENERATE_NOW' => '実行',

 //List View Headers
	'Report Name' => 'レポート名',

 //Default Folders Names, Report Names and Description
	'Account and Contact Reports'=>'顧客企業と顧客担当者のレポート',
	'Lead Reports'=>'見込み客レポート',
	'Potential Reports'=>'案件レポート',
	'Activity Reports'=>'活動レポート',
	'HelpDesk Reports'=>'サポート依頼レポート',
	'Product Reports'=>'製品レポート',
	'Quote Reports'=>'見積りレポート',
	'PurchaseOrder Reports'=>'購買発注レポート',
	'SalesOrder Reports'=>'販売受注レポート', //Added for SO
	'Invoice Reports'=>'請求書レポート',
	'Campaign Reports'=>'マーケティングレポート', //Added for Campaigns
	'Contacts by Accounts'=>'顧客企業別の顧客担当者',
	'Contacts without Accounts'=>'顧客企業なしの顧客担当者',
	'Contacts by Potentials'=>'案件別の顧客担当者',
	'Contacts related to Accounts'=>'顧客企業に関連した顧客担当者',
	'Contacts not related to Accounts'=>'顧客企業に関連しない顧客担当者',
	'Contacts related to Potentials'=>'案件に関連した顧客担当者',
	'Lead by Source'=>'紹介元別の見込み客',
	'Lead Status Report'=>'見込み客のステータス レポート',
	'Potential Pipeline'=>'案件パイプライン',
	'Closed Potentials'=>'完了済み案件',
	'Potential that have Won'=>'受注獲得案件',
	'Tickets by Products'=>'製品別のサポート依頼',
	'Tickets by Priority'=>'優先度別のサポート依頼',
	'Open Tickets'=>'未解決のサポート依頼',
	'Tickets related to Products'=>'製品に関連したサポート依頼',
	'Tickets that are Open'=>'未解決のサポート依頼',
	'Product Details'=>'製品の詳細',
	'Products by Contacts'=>'顧客担当者別の製品',
	'Product Detailed Report'=>'製品の詳細レポート',
	'Products related to Contacts'=>'顧客担当者に関連した製品',
	'Open Quotes'=>'未完了の見積り',
	'Quotes Detailed Report'=>'見積りの詳細レポート',
	'Quotes that are Open'=>'未完了の見積り',
	'PurchaseOrder by Contacts'=>'顧客担当者別の購買発注',
	'PurchaseOrder Detailed Report'=>'購買発注の詳細レポート',
	'PurchaseOrder related to Contacts'=>'顧客担当者に関連した購買発注',
	'Invoice Detailed Report'=>'請求書の詳細レポート',
	'Last Month Activities'=>'先月の活動',
	'This Month Activities'=>'今月の活動',
	'Campaign Expectations and Actuals'=>'マーケティングの予測と実際', //Added for Campaigns
	'SalesOrder Detailed Report'=>'販売受注の詳細レポート', //Added for SO

	'SMSNotifier' => 'SMS 通知',
	'Unit Price'=>'単価',
	'Commission Rate'=>'手数料率 (%)',
	'Qty/Unit'=>'数量/ユニット',
	'Employees' => '従業員数',
	'Amount' => '金額',
	'Probability' => '可能性',
	'Forecast Amount' => '予想金額',
	'Qty In Stock'=>'在庫数',
	'Reorder Level'=>'再発注レベル',
	'Qty In Demand'=>'需要数',
	'Pre Tax Total' => '税引き前の合計',
	'Sales Commission' => '営業手数料',
	'Received' => '入金済み',
	'Balance' => 'バランス',
	'Send Reminder' => '事前に電子メールを送信する',

	'Email Reports'=>'電子メールのレポート',
	'Contacts Email Report'=>'顧客担当者への電子メールのレポート',
	'Accounts Email Report'=>'顧客企業への電子メールのレポート',
	'Leads Email Report'=>'見込み客への電子メールのレポート',
	'Vendors Email Report'=>'納入業者への電子メールのレポート',

	'Emails sent to Contacts' => '顧客担当者へ送信された電子メール',
	'Emails sent to Organizations' => '顧客企業への電子メールのレポート',
	'Emails sent to Leads' => '見込み客へ送信された電子メール',
	'Emails sent to Vendors' => '納入業者へ送信された電子メール',

	'LBL_PRINT_REPORT' => 'レポートの印刷',
	'LBL_RECORDS' => 'レコード',
	'LBL_LIMIT_EXCEEDED' => '1000 + レコードのみが表示されます。 すべてのレポートを見るには CSV または Excel エクスポートを使用してください。',
	'LBL_TOP' => 'トップの',
	'LBL_ALL_REPORTS' => 'すべてのレポート',
	'LBL_CALCULATION_CONVERSION_MESSAGE' => '計算は CRM の基準通貨に基づいて行われます',
);
$jsLanguageStrings = array(
	'JS_DUPLICATE_RECORD' => 'レポートの複製',
	'JS_CALCULATION_LINE_ITEM_FIELDS_SELECTION_LIMITATION' => '制限事項：  ライン アイテムのフィールド ( 定価、割引 & 数量 ) は、他の計算フィールドが選択されていない場合にのみ使用できます',
);
