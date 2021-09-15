<?php
// modelファイルの情報取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
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

// データベースに接続
$db = get_db_connect();
// ユーザー情報取得
$user = get_login_user($db);
// 管理者か確認
if(is_admin($user) === TRUE){
    // 管理者であれば全ての情報取得
    $histories = get_all_histories($db);
} else {
    // ユーザー毎の購入履歴取得
    $histories = get_histories($db, $user['user_id']);
}

// トークン発行
$token = get_csrf_token();

// オーダーID取得
$order_id = get_post('order_id');

// viewファイル読み込み
include_once VIEW_PATH . 'history_view.php';