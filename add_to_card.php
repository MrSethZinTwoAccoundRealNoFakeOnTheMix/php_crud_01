<?php
session_start();
require_once "database.php";


$product_id = $_POST['id'] ?? '';
$user_id = $_SESSION['id'];
$product_qty = $_POST['product_qty'] ?? 1;

$check_dupe_product = $conn->prepare("SELECT qty FROM cart WHERE user_id = ? AND product_id = ?");
$check_dupe_product->bind_param('ii', $user_id, $product_id);
$check_dupe_product->execute();
$result = $check_dupe_product->get_result();
//if exit update just update qty from table 
if($result->num_rows > 0){
  $update = $conn->prepare("UPDATE cart SET qty = qty + ? WHERE user_id = ? AND product_id = ?");
  $update->bind_param('iii', $product_qty, $user_id, $product_id);
  $update->execute();
}
else{
  $stmt_add_card = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
  $stmt_add_card->bind_param('ii', $user_id, $product_id);
  $stmt_add_card->execute();
}






//------get sum of qty from cart to display in dashboard------
$stmt_count_qty = $conn->prepare("SELECT SUM(qty) FROM cart WHERE user_id = ?");
$stmt_count_qty->bind_param('i', $user_id);
$stmt_count_qty->execute();
$stmt_count_qty->bind_result($cart_Count);
$stmt_count_qty->fetch();
if($cart_Count <= 0){
  $_SESSION['cart_count'] = '';
  echo $cart_Count;
  exit;
}
$_SESSION['cart_count'] = $cart_Count ?? '';
echo $cart_Count;
exit;

?>