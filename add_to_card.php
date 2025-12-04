<?php
session_start();
require_once "database.php";


$product_id = $_POST['id'] ?? '';
$user_id = $_SESSION['id'];
$stmt_add_card = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
$stmt_add_card->bind_param('ii', $user_id, $product_id);
$stmt_add_card->execute();

//------get sum of qty from cart to display in dashboard------
$stmt_count_qty = $conn->prepare("SELECT SUM(qty) FROM cart WHERE user_id = ?");
$stmt_count_qty->bind_param('i', $user_id);
$stmt_count_qty->execute();
$stmt_count_qty->bind_result($cart_Count);
$stmt_count_qty->fetch();

echo $cart_Count;
exit;

?>