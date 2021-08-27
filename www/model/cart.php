<?php 
// 他のmodelファイル読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// ユーザーカート内情報取得の関数
function get_user_carts($db, $user_id){
  // SQL文：セレクトでユーザーのカート情報取得
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";
  // db.php 42行目：指定配列全て取得の関数＿実行
  return fetch_all_query($db, $sql, [$user_id]);
}

// ユーザーカートある一つの商品情報取得の関数
function get_user_cart($db, $user_id, $item_id){
  // SQL文：セレクトでユーザーのカート情報取得
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";
  // db.php 24行目：指定配列一行取得の関数＿実行
  return fetch_query($db, $sql, [$user_id, $item_id]);

}

// カートに１つ商品入れる（加える）関数
function add_cart($db, $user_id, $item_id ) {
  // 変数に34行目で得た情報を格納
  $cart = get_user_cart($db, $user_id, $item_id);
  // ↑が空だった場合（まだカートにものが入っていない）
  if($cart === false){
    // 77行目：カートに商品を入れるインサート文＿実行
    return insert_cart($db, $user_id, $item_id);
  }
  // （既に入っていたら）カート情報を更新
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

// カートに商品入れるインサート文の関数
function insert_cart($db, $user_id, $item_id, $amount = 1){
  // SQL文：インサートでカート情報にデータを入れる
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES( ?, ?, ?)
  ";
  // ↑のSQL文実行する関数（db.php:60行目）＿実行
  return execute_query($db, $sql, [$item_id, $user_id, $amount]);
}

// カート内情報（個数）更新の関数
function update_cart_amount($db, $cart_id, $amount){
  // SQL文：アップデートでカート内商品の個数変更
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  // ↑のSQL文実行する関数（db.php:60行目）＿実行
  return execute_query($db, $sql, [$amount, $cart_id]);
}

// カートを消去する関数
function delete_cart($db, $cart_id){
  // SQL文：デリートで選択されたカートを消去
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  // ↑のSQL文実行する関数（db.php:60行目）＿実行
  return execute_query($db, $sql, [$cart_id]);
}

// カート内購入処理の関数
function purchase_carts($db, $carts){
  // カート内の購入が可能か確認（160行目）
  if(validate_cart_purchase($carts) === false){
    // 不可であればfalseを返す
    return false;
  }
  // 購入後、カートの中身を消去＆在庫変動＆購入履歴・明細データを挿入
  $db->beginTransaction();

  try{
    // 購入履歴にインサート【historiesテーブル】
    insert_history($db, $carts[0]['user_id']);
    // order_id(購入詳細のため)を取得
    $order_id = $db->lastInsertId();

    // 複数のカートを繰り返し処理で検査
    foreach($carts as $cart){
      // 購入詳細にインサート【detailsテーブル】
      insert_details($db, $order_id, $cart['item_id'], $cart['price'], $cart['amount']);
      // item.php 160行目:ストック情報更新
      // ※ストック＜購入量になった時にエラーMSG
      if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
        ) === false){
          // エラーMSG
          set_error($cart['name'] . 'の購入に失敗しました。');
        }
      }
      // 購入と同時に、ユーザーのカート内を消去
      delete_user_carts($db, $carts[0]['user_id']);
      // コミット処理
      $db->commit();

    }catch(PDOException $e){
      // ロールバック処理
      $db->rollback();
      // エラーMSG
      set_error('更新に失敗しました。');
    }

}

// 購入履歴へインサート
function insert_history($db, $user_id){
  // SQL文：インサートで購入履歴を登録
  $sql = "
    INSERT INTO
      histories(
        user_id,
        created
      )
    VALUES(?, NOW())
  ";
  // 返り値：SQL文実行の関数
  return execute_query($db, $sql, array($user_id));
}

// 購入詳細へインサート
function insert_details($db, $order_id, $item_id, $price, $amount){
  // SQL文：インサートで購入詳細を登録
  $sql = "
    INSERT INTO
      details(
        order_id,
        item_id,
        price,
        amount,
        created
      )
    VALUES(?,?,?,?, NOW())
  ";
  // 返り値：SQL文実行の関数
  return execute_query($db, $sql, array($order_id, $item_id, $price, $amount));
}

// ユーザーのカート内を消去の関数
function delete_user_carts($db, $user_id){
  // SQL文：デリートでユーザーのカート内消去
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";
  // ↑のSQL文実行する関数（db.php:60行目）＿実行
  execute_query($db, $sql, [$user_id]);
}

// 合計金額を算出する関数
function sum_carts($carts){
  // 合計金額の初期値を0円にセット
  $total_price = 0;
  // 複数のカートを繰り返し処理で検査
  foreach($carts as $cart){
    // 合計金額を計算
    $total_price += $cart['price'] * $cart['amount'];
  }
  // 返り値：↑の計算
  return $total_price;
}

// カート内商品の購入が有効か確認の関数
function validate_cart_purchase($carts){
  // カート内を数える
  if(count($carts) === 0){
    // 0であれば、エラーMSGを出す
    set_error('カートに商品が入っていません。');
    // falseを返す
    return false;
  }
  // 複数のカートを繰り返し処理で検査
  foreach($carts as $cart){
    // ステータスを確認
    if(is_open($cart) === false){
      // エラーMSG
      set_error($cart['name'] . 'は現在購入できません。');
    }
    // 在庫数がある商品か確認
    if($cart['stock'] - $cart['amount'] < 0){
      // エラーMSG
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  // 今までにエラーがあったか確認
  if(has_error() === true){
    // falseを返す
    return false;
  }
  // 問題なければtrueを返す
  return true;
}

