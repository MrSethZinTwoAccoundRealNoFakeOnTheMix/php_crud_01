<?php
session_start();
require_once "database.php";
$validation = [];
$old = [
  'username' => $_POST['userInput']
];
$userInput = trim($_POST['userInput']) ?? '';
// $passInput = password_hash($_POST['passInput'], PASSWORD_DEFAULT);
$passInput = $_POST['passInput'] ?? '';
if(empty($userInput)){
  $validation[] = "Username is required!";
}
if(empty($_POST['passInput'])){
  $validation[] = "Password is required!";
}
if($validation){
  $_SESSION['validation'] = $validation;
  $_SESSION['old'] = $old;
  header('Location: login.php');
  exit();
}
$_SESSION['user'] = [];
$sql = "SELECT id,password FROM testdata_2 WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userInput);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 1){
  $user = $result->fetch_assoc();
  $hashedPasswordFromDB = $user['password']; 
  if(password_verify($passInput, $hashedPasswordFromDB)){
    session_regenerate_id(true);
    $_SESSION['id'] = $user['id'];
    $_SESSION['LAST_ACTIVITY'] = time();
    header("Location: dashboard.php");
    exit;
  }else{
    $_SESSION['login_fail'] = "Incorrect Password";
    header("Location: login.php");
    exit;
  }
}

$_SESSION['login_fail'] = "Invalid username or Password!";
header("Location: login.php");
exit;




?>