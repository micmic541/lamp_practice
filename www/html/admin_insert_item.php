<?php
// モデルファイルの呼び出し
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

// セッションスタート
session_start();

// ログインしているか確認
if(is_logined() === false){
  // してなければリダイレクト
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();

// ユーザー情報配列を取得
$user = get_login_user($db);

// 管理者か確認
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// ↑共通した処理

// get_postユーザー関数：postのデータを得る
$csrf_token = get_post('csrf_token');
// トークンのマッチを確認：できなければエラーMSG
if (is_valid_csrf_token($csrf_token) !== TRUE){
  // エラーMSG
  set_error('不正な操作が行われました');
  // リダイレクト
  redirect_to(ADMIN_URL);
}

$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');

$image = get_file('image');

if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}


redirect_to(ADMIN_URL);