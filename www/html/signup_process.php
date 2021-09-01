<?php
// modelファイル読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

// セッションスタート
session_start();

// すでにログインしている人か確認
if(is_logined() === true){
  // index.phpへリダイレクト
  redirect_to(HOME_URL);
}

// POST送信された名前やPWを取得
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');

// データベース接続
$db = get_db_connect();

// get_postユーザー関数：postのデータを得る
$csrf_token = get_post('csrf_token');
// トークンのマッチを確認：できなければエラーMSG
if (is_valid_csrf_token($csrf_token) !== TRUE){
  // エラーMSG
  set_error('不正な操作が行われました');
  // SIGNUP_URLへリダイレクト
  redirect_to(SIGNUP_URL);
}

// ユーザー登録処理
try{
  // データが不正でなければ、データベースにinsert
  $result = regist_user($db, $name, $password, $password_confirmation);
  // 不適切な場合の処理
  if( $result=== false){
    // エラーMSG
    set_error('ユーザー登録に失敗しました。');
    // サインアップ画面へリダイレクト
    redirect_to(SIGNUP_URL);
  }
  // 他の何かしらの原因でエラーの場合
}catch(PDOException $e){
  // エラーMSG
  set_error('ユーザー登録に失敗しました。');
  // サインアップ画面へリダイレクト
  redirect_to(SIGNUP_URL);
}

// 完了MSG
set_message('ユーザー登録が完了しました。');
// ログイン処理
login_as($db, $name, $password);
// index.phpにリダイレクト
redirect_to(HOME_URL);