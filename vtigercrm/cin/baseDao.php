<?php
/**
 * Created on 2010/11/25
 * Encoding:UTF-8
 * Make:Daiki Arihara
 * LICENSED MATERIAL OF JOKEY GENE CO., LTD.
 * ALL RIGHTS RESERVED,COPYRIGHT(C) JOKEY GENE CO., LTD.
 * 
 * 
 * データベースとの接続用スーパークラス
 */
class baseDao {

  //---------------------------
  // 変数の宣言
  //---------------------------
  var $m_Con;
  var $m_HostName;
  var $m_UserName;
  var $m_Password;
  var $m_Database;
  var $m_Rows;

  //---------------------------
  // コンストラクタ
  //---------------------------
  function baseDao(){
    $this->m_UserName = "cin";
    $this->m_Password = "cincin";
    $this->m_HostName = "124.33.241.201";
//    $this->m_HostName = "192.168.1.207";
    $this->m_Database = "_googlemap2";

    //MYSQLへ接続
    $this->m_con = mysql_connect($this->m_HostName, $this->m_UserName, $this->m_Password);
    if ($this->m_con == false) {
      die("MYSQLの接続に失敗しました。");
    }//if

    //データベースへ接続
    if (!mysql_select_db($this->m_Database, $this->m_con)) {
      die("データベースの接続に失敗しました。DB:{$this->m_Database}");
    }//if

    //文字コード
    mysql_query("SET NAMES utf8", $this->m_con);
  }

  //---------------------------
  // 検索結果
  //---------------------------
  function query($sql) {
    $this->m_Rows = mysql_query($sql, $this->m_con);
    if ($this->m_Rows == false){
      return false;
    }
    return $this->m_Rows;
  }

  //---------------------------
  // 変更された行の数を得る
  //---------------------------
  function affected_rows() {
    return mysql_affected_rows();
  }

  //---------------------------
  // 検索結果をfetch
  //---------------------------
  function fetch() {
    return mysql_fetch_array($this->m_Rows);
  }

  //---------------------------
  // 列数
  //---------------------------
  function cols() {
    return mysql_num_fields($this->m_Rows);
  }

  //---------------------------
  // 行数
  //---------------------------
  function rows() {
    return mysql_num_rows($this->m_Rows);
  }

  //---------------------------
  // 検索結果の開放
  //---------------------------
  function free() {
    mysql_free_result($this->m_Rows);
  }

  //---------------------------
  // MySQLをクローズ
  //---------------------------
  function close() {
    mysql_close($this->m_con);
  }

  //---------------------------
  // エラー
  //---------------------------
  function errors() {
    return mysql_errno().": ".mysql_error();
  }

  //---------------------------
  // エラーナンバー
  //---------------------------
  function errorno() {
    return mysql_errno();
  }

}//Class