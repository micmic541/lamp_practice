<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

  <div class="container">
    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card mh-auto text-center">
            <div class="card-header">
              <?php print( h($item['name'])); ?>
            </div>
            <figure class="card-body">
              <img class="w-50 rounded" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print(number_format( h($item['price']))); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <!-- トークンの生成 -->
                    <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print( h($item['item_id'])); ?>">
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>

    <!-- ランキング -->
    <div class="rank-deck py-5 mt-5">
        <h1>人気ランキング</h1>

        <div class="column">
        <!-- 順位初期化 -->
        <?php $rank = 0; ?>
        <?php foreach($rankings as $ranking){ ?>
          <div class="col-10 item mx-auto">
            <div class="card mh-auto text-center">
              <div class="card-header">
                <!-- 順位 -->
                <p class="font-weight-bold text-danger"><?php print(++$rank);?>位</p>
                <!-- 商品名 -->
                <?php print( h($ranking['name'])); ?>
              </div>
              <figure class="card-body d-flex flex-row justify-content-around">
                <img class="w-50 h-30 rounded" src="<?php print(IMAGE_PATH . $ranking['image']); ?>">
                <figcaption class="align-self-center">
                  <?php print(number_format( h($ranking['price']))); ?>円
                  <?php if($ranking['stock'] > 0){ ?>
                    <form action="index_add_cart.php" method="post">
                      <!-- トークンの生成 -->
                      <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                      <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                      <input type="hidden" name="item_id" value="<?php print( h($ranking['item_id'])); ?>">
                    </form>
                  <?php } else { ?>
                    <p class="text-danger">現在売り切れです。</p>
                  <?php } ?>
                </figcaption>
              </figure>
            </div>
          </div>
        <?php } ?>
      </div>

    </div>

  </div>
  
</body>
</html>