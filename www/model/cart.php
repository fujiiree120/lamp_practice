<?php 
require_once 'functions.php';
require_once 'db.php';
//ユーザーのカート情報すべてを取得するsql文
function get_user_carts($db, $user_id){
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
      carts.user_id = :user_id
  ";
  $params = array(':user_id' => $user_id);
  return fetch_all_query($db, $sql, $params);
}
//ユーザーの特定の商品情報を取得するsql文
function get_user_cart($db, $user_id, $item_id){
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
      carts.user_id = :user_id
    AND
      items.item_id = :item_id
  ";
  $params = array('user_id' => $user_id, ':item_id' => $item_id);
  return fetch_query($db, $sql, $params);

}
//insert_cartで商品追加,update_cartで商品を更新する関数をまとめたもの
function add_cart($db, $item_id, $user_id) {
  $cart = get_user_cart($db, $item_id, $user_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}
//カートに商品を追加するsql文（数量は１)
function insert_cart($db, $item_id, $user_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";
  $params = array(':item_id' => $item_id,':user_id' => $user_id, ':amount' => $amount);
  return execute_query($db, $sql, $params);
}
//数量を変更するsql文
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = :amount
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  $params = array(':amount' => $amount, ':cart_id' => $cart_id);
  return execute_query($db, $sql, $params);
}
//カートを削除するsql
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  $params = array(':cart_id' => $cart_id);
  return execute_query($db, $sql, $params);
}
/*validate_cart_purchaseで商品買えるかのチェック、
update_item?stockでstock - amountしエラーを確認
エラーがなければユーザーのカートテーブルを削除
*/
function purchase_carts($db, $carts, $user_id){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  //beginTransaction
  $db->beginTransaction();
   //ここにpurchase_logs関数
  if(insert_order_log($db, $user_id) === false){
    $db->rollback(); 
      return false;
   }

  $order_log_id = $db->lastInsertId();

  foreach($carts as $cart){ 
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }

    if(insert_order_detail($db, $order_log_id, $cart['item_id'], $cart['amount'], $cart['price']) === false){
      $db->rollback(); 
      return false;
    }
  }
    
  if(delete_user_carts($db, $carts[0]['user_id']) === false){
    $db->rollback(); 
    return false;
   }

  $db->commit();
  return true;
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";
  $params = array(':user_id' => $user_id);
  execute_query($db, $sql, $params);
}

//カート内の合計金額をtotal_priceに格納する関数
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}
//商品が非公開、在庫が足らない場合falseを返す関数
function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}
