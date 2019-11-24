<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order_logs.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($order_logs) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>合計金額</th>
            <th>購入日時</th>
            <th>購入明細</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($order_logs as $order_log){ ?>
          <tr>
            <td><?php print h($order_log['order_log_id']); ?></td>
            <td><?php print h($order_log['total_price']); ?></td>
            <td><?php print h($order_log['created']); ?></td>
            <td>
                <a href="order_detail.php?order_log_id=<?php print h($order_log['order_log_id']); ?>">詳細 </a>
            </td>
          </tr>
          <?php } ?>
          <?php } ?>
        </tbody>
      </table>
  </div>
</body>
</html>