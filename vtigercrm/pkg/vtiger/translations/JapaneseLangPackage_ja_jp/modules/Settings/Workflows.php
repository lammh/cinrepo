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
 //Basic Field Names
	'LBL_NEW' => '新規',
	'LBL_WORKFLOW' => 'ワークフロー',
	'LBL_CREATING_WORKFLOW' => 'ワークフローの作成',
	'LBL_EDITING_WORKFLOW' => 'ワークフローの編集',
	'LBL_NEXT' => '次へ',

 //Edit view
	'LBL_STEP_1' => 'ステップ 1 ',
	'LBL_ENTER_BASIC_DETAILS_OF_THE_WORKFLOW' => 'ワークフローの基本内容を入力します',
	'LBL_SPECIFY_WHEN_TO_EXECUTE' => 'このワークフローをいつ実行するか指定します',
	'ON_FIRST_SAVE' => '最初の保存時のみ',
	'ONCE' => '条件が初めて真になるまで',
	'ON_EVERY_SAVE' => 'レコード保存時に毎回',
	'ON_MODIFY' => 'レコード変更時に毎回',
	'MANUAL' => 'システム',
	'SCHEDULE_WORKFLOW' => 'ワークフローの実行時期',
	'ADD_CONDITIONS' => '条件の追加',
	'ADD_TASKS' => 'タスクの追加',

 //Step2 edit view
	'LBL_EXPRESSION' => '表現式',
	'LBL_FIELD_NAME' => 'フィールド',
	'LBL_SET_VALUE' => '値の設定',
	'LBL_USE_FIELD' => '-- フィールド値を使用 --',
	'LBL_USE_FUNCTION' => '-- 関数を使用 --',
	'LBL_RAW_TEXT' => 'RAW テキスト',
	'LBL_ENABLE_TO_CREATE_FILTERS' => 'フィルタを作成するには有効にします',
	'LBL_CREATED_IN_OLD_LOOK_CANNOT_BE_EDITED' => 'このワークフローは旧方式にて作成されました 旧方式で作成された条件は編集できません。 条件を再作成するか、既存の条件を変更なしに使用します。',
	'LBL_USE_EXISTING_CONDITIONS' => '既存の条件を使用する',
	'LBL_RECREATE_CONDITIONS' => '条件を再作成する',
	'LBL_SAVE_AND_CONTINUE' => '保存 & 続行',

 //Step3 edit view
	'LBL_ACTIVE' => '有効',
	'LBL_TASK_TYPE' => 'タスクのタイプ',
	'LBL_TASK_TITLE' => 'タスクのタイトル',
	'LBL_ADD_TASKS_FOR_WORKFLOW' => 'ワークフローにタスクを追加します',
	'LBL_TASK_TYPE' => 'タスクのタイプ',
	'LBL_EXECUTE_TASK' => 'タスクを実行する',
	'LBL_SELECT_OPTIONS' => 'オプションの選択',
	'LBL_ADD_FIELD' => 'フィールドの追加',
	'LBL_ADD_TIME' => '時間の追加',
	'LBL_TITLE' => 'タイトル',
	'LBL_PRIORITY' => '優先度',
	'LBL_ASSIGNED_TO' => '担当',
	'LBL_TIME' => '時刻',
	'LBL_DUE_DATE' => '締切日',
	'LBL_THE_SAME_VALUE_IS_USED_FOR_START_DATE' => '開始日に同じ値が使用',
	'LBL_EVENT_NAME' => '予定名',
	'LBL_TYPE' => 'タイプ',
	'LBL_METHOD_NAME' => 'メソッド名',
	'LBL_RECEPIENTS' => '受取人',
	'LBL_ADD_FIELDS' => 'フィールドの追加',
	'LBL_SMS_TEXT' => 'SMS テキスト',
	'LBL_SET_FIELD_VALUES' => 'フィールド値の設定',
	'LBL_ADD_FIELD' => 'フィールドの追加',
	'LBL_IN_ACTIVE' => '有効',
	'LBL_SEND_NOTIFICATION' => '通知を送信する',
	'LBL_START_TIME' => '開始時間',
	'LBL_START_DATE' => '開始日',
	'LBL_END_TIME' => '終了時間',
	'LBL_END_DATE' => '終了日',
	'LBL_ENABLE_REPEAT' => '周期的に実施する',
	'LBL_NO_METHOD_IS_AVAILABLE_FOR_THIS_MODULE' => 'このモジュールには利用できるメソッドがありません',
	'LBL_FINISH' => '完了',
	'LBL_NO_TASKS_ADDED' => 'タスクがありません',
	'LBL_CANNOT_DELETE_DEFAULT_WORKFLOW' => 'デフォルトのワークフローは削除できません',
	'LBL_MODULES_TO_CREATE_RECORD' => 'レコードを作成するモジュール',
	'LBL_EXAMPLE_EXPRESSION' => '表現式',
	'LBL_EXAMPLE_RAWTEXT' => 'RAW テキスト',
	'LBL_VTIGER' => 'Vtiger',
	'LBL_EXAMPLE_FIELD_NAME' => 'フィールド',
	'LBL_NOTIFY_OWNER' => 'notify_owner',
	'LBL_ANNUAL_REVENUE' => 'annual_revenue',
	'Workflows' => 'ワークフロー',
	'Summary' => '説明',
	'Module' => 'モジュール',
	'Execution Condition' => '実行条件',
	'LBL_EXPRESSION_EXAMPLE2' => "if mailingcountry == 'India' then concat(firstname,' ',lastname) else concat(lastname,' ',firstname) end",
	'Send Mail' => '電子メールの送信',
	'Invoke Custom Function' => '起動カスタム関数',
	'Create Todo' => '作業の作成',
	'Create Event'=> '予定の作成',
	'Update Fields' => 'フィールドの更新',
	'Create Entity' => 'レコード作成',
	'SMS Task' => 'SMSを送信',
	'UpdateInventoryProducts On Every Save' => 'すべてのセーブで製品を更新',
	'Send Email to user when Notifyowner is True' => '担当に通知が はい の場合、ユーザーに電子メールを送信する',
	'Send Email to user when Portal User is True' => 'ポータル ユーザーが はい の場合、ユーザーに電子メールを送信する',
	'Send Email to users on Potential creation' => '案件作成時にユーザーに電子メールを送信',
	'Workflow for Contact Creation or Modification' => '顧客担当者の作成または更新のワークフロー',
	'Ticket Creation From Portal : Send Email to Record Owner and Contact' => 'ポータルからチケット作成：担当と顧客担当者にメールを送信',
	'Send Email to Contact on Ticket Update' => 'チケットの更新についての顧客担当者に電子メールを送信',
	'Workflow for Events when Send Notification is True' => '予定の通知を送信するが はい のワークフロー',
	'Workflow for Calendar Todos when Send Notification is True' => '作業の通知を送信するが はい のワークフロー',
	'Calculate or Update forecast amount' => '予想金額の計算または更新',
	'Comment Added From CRM : Send Email to Organization' => 'CRMからコメントを追加：組織に電子メールを送信',
	'Update Inventory Products On Every Save' => 'すべてのセーブで製品を更新',
	'Comment Added From Portal : Send Email to Record Owner' => 'ポータルからコメントを追加：担当に電子メールを送信',
	'Comment Added From CRM : Send Email to Contact, where Contact is not a Portal User' => 'CRMからコメントを追加：顧客担当者がポータルユーザでない場合には、顧客担当者へ電子メールを送信',
	'Comment Added From CRM : Send Email to Contact, where Contact is Portal User' => 'CRMからコメントを追加：顧客担当者がポータルユーザである場合、顧客担当者へ電子メールを送信',
	'Send Email to Record Owner on Ticket Update' => 'チケット更新で担当に電子メールを送信',
	'Ticket Creation From CRM : Send Email to Record Owner' => 'CRMからチケット作成：担当に電子メールを送信',
	'Invoice Date' => '請求書日時',
	'Optional' => '任意',
	'Current Date' => '現在日付',
	'Current Time' => '現在時刻',
	'System Timezone' => 'システムタイムゾーン',
	'User Timezone' => 'ユーザータイムゾーン',
	'CRM Detail View URL' => 'CRMの詳細画面のURL',
	'Portal Detail View URL' => 'ポータル詳細画面のURL',
	'Site Url' => 'サイトのURL',
	'Portal Url' => 'ポータルのURL',
	'Record Id' => 'レコードID',
	'UpdateInventory' => '製品を更新',

);

$jsLanguageStrings = array(
	'JS_STATUS_CHANGED_SUCCESSFULLY' => 'ステータスが正しく変更されました',
	'JS_TASK_DELETED_SUCCESSFULLY' => 'タスクが正しく削除されました',
	'JS_SAME_FIELDS_SELECTED_MORE_THAN_ONCE' => '同一のフィールドが複数回選択されました',
	'JS_WORKFLOW_SAVED_SUCCESSFULLY' => 'ワークフローが正しく保存されました'
);
