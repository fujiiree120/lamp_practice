<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
$token = get_post('csrf_token');
if(is_valid_csrf_token($token) === false){
  set_error('不正な処理が発生しました。');
  redirect_to(CART_URL);
}
$db = get_db_connect();
$user = get_login_user($db);
//model/cart.php $cartsにユーザーのカート情報を入れる
$carts = get_user_carts($db, $user['user_id']);
//cart.phpに関数　エラーが出ればメッセージを出し、成功すればカートページへ

if(purchase_carts($db, $carts, $user['user_id']) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 
//model/cart.phpカート内の商品の合計価格を$total_priceに入れる
$total_price = sum_carts($carts);

$token = get_csrf_token();
header('X-FRAME-OPTIONS: DENY');

include_once '../view/finish_view.php';