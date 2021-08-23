<?php
// 商品管理ページ
// modelのファイルを取り込む
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

// sessionにつなげる
session_start();

// ログインされているか確認
if(is_logined() === false){
  // されていなければリダイレクト
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// IDなどユーザー情報配列を取ってくる
$user = get_login_user($db);

// ↑のユーザーが管理者かどうか確認
if(is_admin($user) === false){
  // でなければリダイレクト
  redirect_to(LOGIN_URL);
}

// トークン発行
$token = get_csrf_token();

$items = get_all_items($db);
include_once VIEW_PATH . '/admin_view.php';
