<?php
session_start();
require_once "database.php";
$productName = trim($_POST['productName']);
$productQty = trim($_POST['productQty']);
$productPrice = trim($_POST['productPrice']);
$productDescription = trim($_POST['productDescription']);
$target = "pro_Image/" . basename($_FILES['image']['name']);
move_uploaded_file($_FILES['image']['tmp_name'], $target);

$stmt = $conn->prepare("INSERT INTO products_list (name,qty,price,description,image) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sisss', $productName, $productQty, $productPrice, $productDescription, $target);
$stmt->execute();




?>