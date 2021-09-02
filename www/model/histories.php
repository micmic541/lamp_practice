<?php
// 他のmodelファイル読み込み
require_once MODEL_PATH. 'function.php';
require_once MODEL_PATH. 'db.php';

// ユーザー毎の購入履歴
function get_histories($db, $user_id){
    $sql = "
        SELECT
            histories.order_id,
            histories.created,
            SUM(details.amount * details.price) AS total
        FROM
            histories
        JOIN
            details
        ON
            histories.order_id = details.order_id
        WHERE
            user_id = ?
        GROUP BY 
            user_id
        ORDER BY
            created desc
    ";
    return fetch_all_query($db, $sql, array($user_id));
}