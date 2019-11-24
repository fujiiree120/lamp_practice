<?php 
require_once 'functions.php';
require_once 'db.php';


function insert_order_log($db, $user_id){
    $sql = "
      INSERT INTO
        order_logs(
          user_id
        )
      VALUES(:user_id)
    ";
    $params = array(':user_id' => $user_id);
    return execute_query($db, $sql, $params);
  }

  function get_order_logs($db, $user_id){
    $sql = "
      SELECT
          order_logs.order_log_id,
          order_logs.user_id,
          order_logs.created,
          SUM(order_details.amount * order_details.purchase_price) AS total_price
      FROM
          order_logs
      JOIN
          order_details
      ON
          order_logs.order_log_id = order_details.order_log_id
      WHERE
          order_logs.user_id = :user_id
      GROUP BY
          order_log_id
      ";
    $params = array(':user_id' => $user_id);
    return fetch_all_query($db, $sql, $params);
}
