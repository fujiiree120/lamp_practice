<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
//model/item.phpに関数$items(配列)にopenの商品を格納する

$items_order = get_get("items_order", "created_desc");

$items = get_open_items($db, $items_order);

$token = get_csrf_token();

header('X-FRAME-OPTIONS: DENY');

include_once '../view/index_view.php';