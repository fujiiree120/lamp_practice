<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'order_logs.php';
require_once MODEL_PATH . 'order_detail.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$order_log_id = $_GET['order_log_id'];

$db = get_db_connect();
$user = get_login_user($db);

$order_details = get_order_detail($db, $order_log_id);

$total_price = sum_purchase_detail($order_details);

if(checked_user_id($order_details[0]['user_id'], $user['user_id'], $user['type']) === FALSE){
    set_error('不正な処理が発生しました。');
    redirect_to(ORDER_LOGS_URL);
}
header('X-FRAME-OPTIONS: DENY');
include_once '../view/order_detail_view.php';