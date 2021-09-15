<?php
// 他のmodelファイル読み込み
require_once '../conf/const.php';
require_once MODEL_PATH. 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'histories.php';

// セッションスタート
session_start();

// ログイン確認
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}
  
// データベース接続
$db = get_db_connect();
// ユーザー情報取得
$user = get_login_user($db);

// get_postユーザー関数：postのデータを得る
$csrf_token = get_post('csrf_token');
// トークンのマッチを確認：できなければエラーMSG
if (is_valid_csrf_token($csrf_token) !== TRUE){
  // エラーMSG
  set_error('不正な操作が行われました');
  // index.phpへリダイレクト
  redirect_to(HOME_URL);
}

// order_id取得
$order_id = get_post('order_id');

// 管理者か確認
if(is_admin($user) === TRUE){
    // 管理者であれば全ての情報取得
    $history = get_one_admin_history($db, $order_id);
    // 購入明細取得
    $details = get_admin_details($db, $order_id);
} else {
    // 購入履歴取得
    $history = get_one_history($db, $order_id, $user['user_id']);
    // 購入明細取得
    $details = get_details($db, $order_id, $user['user_id']);
}

// var_dump($e); fetch_query()のエラー確認

// viewファイル読み込み
include_once VIEW_PATH . 'detail_view.php';