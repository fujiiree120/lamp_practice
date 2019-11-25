<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order_detail.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <p class="text-left">注文番号: <?php print h($order_details[0]['order_log_id']); ?></p>
    <p class="text-left">合計金額: <?php print h(number_format($total_price)); ?>円</p>
    <p class="text-left">注文日時: <?php print h($order_details[0]['created']); ?></p>
    <?php if(count($order_details) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($order_details as $order_detail){ ?>
          <tr>
            <td><?php print h($order_detail['name']); ?></td>
            <td><?php print h($order_detail['purchase_price']); ?></td>
            <td><?php print h($order_detail['amount']); ?></td>
            <td><?php print h($order_detail['subtotal_price']); ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } ?>
  </div>
</body>
</html>