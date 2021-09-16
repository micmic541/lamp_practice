<?php
// modelファイルの情報取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

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
// 商品情報取得
$items = get_open_items($db);
//上位3位アイテム情報取得
$rankings = get_ranking($db);

var_dump($rankings);

// トークン発行
$token = get_csrf_token();

// viewファイル読み込み
include_once VIEW_PATH . 'index_view.php';