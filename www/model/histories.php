<?php
// 他のmodelファイル読み込み
require_once MODEL_PATH. 'functions.php';
require_once MODEL_PATH. 'db.php';

// 全ての購入履歴取得
function get_all_histories($db){
    $sql = "
        SELECT
            histories.order_id,
            histories.created,
            SUM(details.price * details.amount) AS total
        FROM
            histories
        JOIN
            details
        ON 
            histories.order_id = details.order_id
        GROUP BY
            order_id
        ORDER BY
            created desc
    ";
    return fetch_all_query($db, $sql);
}

// ユーザー毎の購入履歴
function get_histories($db, $user_id){
    $sql = "
        SELECT
            histories.order_id,
            histories.created,
            SUM(details.price * details.amount) AS total
        FROM
            histories
        JOIN
            details
        ON
            histories.order_id = details.order_id
        WHERE
            user_id = ?
        GROUP BY 
            order_id
        ORDER BY
            created desc
    ";
    return fetch_all_query($db, $sql, array($user_id));
}

// 注文番号一列の情報取得
function get_one_history($db, $order_id, $user_id){
    $sql = "
        SELECT
            histories.user_id,
            histories.created,
            SUM(details.price * details.amount) AS total
        FROM
            histories
        JOIN
            details
        ON
            histories.order_id = details.order_id
        WHERE
            order_id = ?
        AND
            histories.user_id = ?
    ";
    return fetch_query($db, $sql, [$order_id, $user_id]);
}

function get_details($db, $order_id){
    $sql = "
        SELECT
            details.price,
            details.amount,
            details.created,
            SUM(details.price * details.amount) AS subtotal,
            items.name
        FROM
            details
        JOIN
            items
        ON
            details.item_id = items.item_id
        WHERE
            order_id = ?
        GROUP BY
            details.price, details.amount, details.created, items.name
    ";
    return fetch_all_query($db, $sql, array($order_id));
}