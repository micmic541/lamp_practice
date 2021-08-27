<?php

// データベースに接続する関数
function get_db_connect(){
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
 
  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    // エラーをthrow
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // SQLインジェクション対策※セミコロンでつなげたSQLを禁止
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // SQLインジェクション対策※レコード列名をキーとして取得させる
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }

  return $dbh;
}

// データベース特定情報一行のみ取得の関数
// ※$params = array() : 無いと空配列が登録される
function fetch_query($db, $sql, $params = array()){
  try{
    // SQL実行準備※プリペアドステートメント
    $statement = $db->prepare($sql);
    // SQLの実行
    $statement->execute($params);
    // 返り値：配列を一行取得する
    return $statement->fetch();
  }catch(PDOException $e){
    // エラーMSG
    set_error('データ取得に失敗しました。');
  }
  // データ取得不可の場合falseを返す
  return false;
}

// データベース指定情報全て取得の関数
function fetch_all_query($db, $sql, $params = array()){
  try{
    // SQL実行準備※プリペアドステートメント
    $statement = $db->prepare($sql);
    // SQLの実行
    $statement->execute($params);
    // 返り値：指定配列を全て取得する
    return $statement->fetchAll();
  }catch(PDOException $e){
    // エラーMSG
    set_error('データ取得に失敗しました。');
  }
  // データ取得不可の場合falseを返す
  return false;
}

// SQL文実行の関数
function execute_query($db, $sql, $params = array()){
  try{
    // SQL実行準備※プリペアドステートメント
    $statement = $db->prepare($sql);
    // 返り値：SQL文を実行する
    return $statement->execute($params);
  }catch(PDOException $e){
    // エラーを投げる
    throw $e;
  }
  // 不可の場合falseを返す
  return false;
}