<?php
// modelファイルを取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

// セッションスタート
session_start();

// ログイン確認
if(is_logined() === false){
  // ログイン失敗の場合ログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();
// ユーザー情報を取得
$user = get_login_user($db);

// トークン発行
$token = get_csrf_token();

// ユーザーのカートを取得
$carts = get_user_carts($db, $user['user_id']);
// 合計金額を算出する
$total_price = sum_carts($carts);

include_once VIEW_PATH . 'cart_view.php';