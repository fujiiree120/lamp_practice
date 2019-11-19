Order_logsのＳＱＬ
CREATE TABLE order_logs (
  order_log_id INT AUTO_INCREMENT,
  user_id INT,
  created DATETIME,
  updated DATETIME,
  primary_key(order_log_id)
);

Order_detailsのＳＱＬ
CREATE TABLE order_details (
  order_detail_id INT AUTO_INCREMENT,
  order_log_id INT,
  item_id INT,
  created DATETIME,
  updated DATETIME,
  amount INT,
  purchase_price INT,
  primary_key(order_detail_id)
);