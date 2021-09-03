<?php
// 他のmodelファイル読み込み
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
// 購入履歴取得
$histories = get_histories($db, $user['user_id']);

// order_id取得
$order_id = get_post('order_id');
// 購入明細取得
$details = get_details($db, $order_id);

// viewファイル読み込み
include_once VIEW_PATH . 'detail_view.php';