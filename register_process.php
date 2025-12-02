<?php
session_start();
require_once "database.php";

$username = $_POST['username'];
$gmail = $_POST['gmail'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$errors = [];
$old = [
  'username' => $_POST['username'],
  'gmail' => $_POST['gmail'],
];
if(empty($_POST['username'])){
  $errors['username'] = "Username is required!";
}
if(empty($_POST['gmail'])){
  $errors['gmail'] = "Gmail is required!";
}
elseif(!str_contains($_POST['gmail'], '@gmail.com')){
  $errors['gmail'] = "Email must be a @gmail.com!";
}
if(empty($_POST['password'])){
  $errors['password'] = "Password is required!";
}

// $success = [];
//check dupe gmail
$sql = "SELECT gmail FROM testdata_2 WHERE gmail = ?";
// $result = $conn->query($sql);
// $data = $result->fetch_assoc();
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $gmail);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0){
  $errors['gmail'] = "Gmail already exists";
}
if($errors){
  $_SESSION['errors'] = $errors;
  $_SESSION['old'] = $old;
  header("Location: register.php");
  exit();
}




$stmt = $conn->prepare("INSERT INTO testdata_2 (username, gmail, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $_POST['username'], $_POST['gmail'], $password);
$stmt->execute();
$new_user_id = $conn->insert_id;
//---- Create testdata_detail for keeping track of user_id------
$sql_detail = "INSERT INTO testdata_detail (user_id,address,phone,dob,bio) VALUES (?, null, null, null, null)";
$stmt_detail = $conn->prepare($sql_detail);
$stmt_detail->bind_param('i', $new_user_id);
$stmt_detail->execute();

// $success = ["Register successfully!"];
$_SESSION['success'] = "Register successfully!";
header("Location: register.php");
exit;   

?>