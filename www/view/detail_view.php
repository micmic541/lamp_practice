<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once VIEW_PATH . 'templates/head.php'; ?>
    <title>購入明細</title>
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'histories.css'); ?>">
</head>
<body>
    <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
    <div class="container">
    <h1>購入明細</h1>

        <!-- エラーMSG -->
        <?php include VIEW_PATH . 'templates/messages.php'; ?>

        <!-- 購入明細 -->
        <table>
            <thead>
                <tr>
                    <th>注文番号</th>
                    <th>購入日時</th>
                    <th>合計金額</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php print($order_id); ?></td>
                    <td><?php print($history['created']); ?></td>
                    <td><?php print($history['total']); ?></td>
                </tr>
            </tbody>
        </table>

        <!-- 購入明細 -->
        <table>
            <thead>
                <tr>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>購入数</th>
                    <th>小計</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($details as $detail){ ?>
                <td><?php print($detail['name']); ?></td>
                <td><?php print($detail['price']); ?></td>
                <td><?php print($detail['amount']); ?></td>
                <td><?php print($detail['subtotal']); ?></td>
            </tbody>
            <?php } ?>
        </table>
    </div>

</body>
</html>