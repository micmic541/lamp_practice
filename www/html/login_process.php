<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

if(is_logined() === true){
  redirect_to(HOME_URL);
}

$name = get_post('name');
$password = get_post('password');

$db = get_db_connect();

// get_postユーザー関数：postのデータを得る
$csrf_token = get_post('crsf_token');
// トークンのマッチを確認：できなければエラーMSG
if (is_valid_csrf_token($csrf_token) !== TRUE){
  // エラーMSG
  set_error('不正な操作が行われました');
  // LOGIN_URLへリダイレクト
  redirect_to(LOGIN_URL);
}

// ログイン処理
// SQL文でログイン情報を取ってきている
$user = login_as($db, $name, $password);
// ユーザーのエラーチェック
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

// 完了MSG
set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
redirect_to(HOME_URL);