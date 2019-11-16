<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();
//sessionがセットされてれば直接ＨＰへ
if(is_logined() === true){
  redirect_to(HOME_URL);
}

$token = get_csrf_token();

header('X-FRAME-OPTIONS: DENY');

include_once '../view/login_view.php';