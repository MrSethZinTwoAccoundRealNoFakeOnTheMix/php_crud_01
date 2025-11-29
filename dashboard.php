<?php
session_start();

if(!isset($_SESSION['LAST_ACTIVITY']) || (time() - $_SESSION['LAST_ACTIVITY'] > 20)){
  session_regenerate_id(true);
  session_unset();
  session_destroy();

  session_start();
  $_SESSION['login_fail'] = "Session time out. Please login again.";
  header("Location: login.php");
  exit;
}
$_SESSION['LAST_ACTIVITY'] = time();
const AUTH_SESSION_KEY = 'id';
$user_token = $_SESSION[AUTH_SESSION_KEY] ?? null;

if(empty($user_token)){
  $_SESSION['login_fail'] = 'You must login first!';
  header("Location: login.php");
  exit;
}

require_once 'database.php';
?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>dashboard</title>
</head>
<body>
  fgsdfg
</body>
</html>