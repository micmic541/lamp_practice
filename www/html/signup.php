<?php
// modelファイルを取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

// セッション接続
session_start();

// ログイン確認
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// トークン発行
$token = get_csrf_token();

include_once VIEW_PATH . 'signup_view.php';



