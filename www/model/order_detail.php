<?php 
require_once 'functions.php';
require_once 'db.php';
require_once 'user.php';


function insert_order_detail($db, $order_log_id, $item_id, $amount, $price){
    $sql = "
      INSERT INTO
        order_details(
           order_log_id,
           item_id,
           amount,
           purchase_price
        )
      VALUES(:order_log_id, :item_id, :amount, :purchase_price)
    ";
    $params = array(
      ':order_log_id' => $order_log_id, 
      ':item_id' => $item_id, 
      ':amount' => $amount, 
      ':purchase_price' => $price,
    );
    return execute_query($db, $sql, $params);
  }

  function get_order_detail($db, $order_log_id){
    $sql = "
    SELECT
        order_details.order_log_id,
        order_details.order_detail_id,
        order_details.item_id,
        order_details.created,
        order_details.amount,
        order_details.purchase_price, 
        SUM(order_details.purchase_price * order_details.amount) AS subtotal_price,
        items.name,
        order_logs.user_id
    FROM
        order_details
    JOIN
        items
    ON
        order_details.item_id = items.item_id
    JOIN
        order_logs
    ON
        order_details.order_log_id = order_logs.order_log_id
    WHERE
        order_details.order_log_id = :order_log_id
    GROUP BY
        order_detail_id
    ";
    $params = array(':order_log_id' => $order_log_id);
    return fetch_all_query($db, $sql, $params);
  }

  function checked_user_id($order_detail_user_id, $user_id, $type){
    if($type === 1){
      return true;
    }
    if($order_detail_user_id === $user_id){
      return true;
    }
    return false;
  }

  function sum_purchase_detail($order_details){
    $total_price = 0;
    foreach($order_details as $order_detail){
      $total_price += $order_detail['purchase_price'] * $order_detail['amount'];
    }
    return $total_price;
  }