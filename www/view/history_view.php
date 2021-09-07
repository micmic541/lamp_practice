<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include_once VIEW_PATH . 'templates/head.php'; ?>
    <title>購入履歴</title>
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'bella.css'); ?>">
</head>
<body>
    <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
    
    <div class="container">
    <h1>購入履歴</h1>

        <!-- エラーMSG -->
        <?php include VIEW_PATH . 'templates/messages.php'; ?>

        <!-- 購入履歴 -->
        <?php if(!empty($histories)){ ?>
        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th>注文番号</th>
                    <th>購入日時</th>
                    <th>合計金額</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($histories as $history){ ?>
                <tr>
                    <td><?php print($history['order_id']); ?></td>
                    <td><?php print($history['created']); ?></td>
                    <td><?php print($history['total']); ?></td>
                    <td class="detailBtn">
                        <form method="post" action="details.php">
                            <!-- トークンの生成 -->
                            <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                            <input class="stretched-link" type="submit" value="購入明細表示">
                            <input type="hidden" name="order_id" value="<?php print($history['order_id']); ?>">
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php }else{ ?>
        <p>購入履歴がありません</p>
        <?php } ?>
    </div>
    
</body>
</html>