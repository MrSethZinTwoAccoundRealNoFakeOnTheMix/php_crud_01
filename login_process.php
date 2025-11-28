<?php
session_start();
require_once "database.php";
$validation = [];
$old = [
  'username' => $_POST['userInput']
];
$userInput = $_POST['userInput'];
// $passInput = password_hash($_POST['passInput'], PASSWORD_DEFAULT);
$passInput = $_POST['passInput'];
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
$sql = "SELECT password, username FROM testdata_2 WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userInput);
$stmt->execute();
$result = $stmt->get_result();
  if($result->num_rows > 1){
    $row = $result->fetch_assoc();
    $hashedPasswordFromDB = $row['password']; 
    if(password_verify($passInput, $hashedPasswordFromDB)){
      header("Location: dashboard.php");
      exit;
    }else{
      $_SESSION['login_fail'] = "Incorrect Password";
      header("Location: login.php");
      exit;
    }
  }else{
    $_SESSION['login_fail'] = "Username not found";
    header("Location: login.php");
    exit;
  }




?>