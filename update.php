<?php
session_start();
require_once 'database.php';
$user_id = $_SESSION['id'];
$username = trim($_POST['username']) ?? '';
$gmail = trim($_POST['gmail']) ?? '';
$address = trim($_POST['address']) ?? '';
$phone = trim($_POST['phone']) ?? '';
$dob = trim($_POST['dob']) ?? '';
$bio = trim($_POST['bio']) ?? '';
//---- SET dynamic update scan if empty not update--------------
$fields = [];
$params = [];
$types = '';
if($username !== ''){
  $fields[] = 'username = ?';
  $params[] = $username;
  $types .= 's';
}
if($gmail !== ''){
  $fields[] = 'gmail = ?';
  $params[] = $gmail;
  $types .= 's';
}
// var_dump($params);
if(!empty($fields)){
  $sql_main = "UPDATE testdata_2 SET " . implode(',', $fields) . " WHERE id = ?";
  $params[] = $user_id;
  $stmt_main = $conn->prepare($sql_main);
  $types .= 'i';
  $stmt_main->bind_param($types,...$params);
  $stmt_main->execute();
}
//----- check if table detail exist or make new one-----

$sql_detail_check = "SELECT user_id FROM testdata_detail WHERE user_id = ?";
$stmt_detail_check = $conn->prepare($sql_detail_check);
$stmt_detail_check->bind_param('i', $user_id);
$stmt_detail_check->execute();
$exist = $stmt_detail_check->get_result()->num_rows > 0;

$fields_detail = [];
$params_detail = [];
$types_detail = '';
if($address !== ''){
  $fields_detail[] = 'address = ?';
  $params_detail[] = $address;
  $types_detail .= 's';
}
if($phone !== ''){
  $fields_detail[] = 'phone = ?';
  $params_detail[] = $phone;
  $types_detail .= 's';
}
if($dob !== ''){
  $fields_detail[] = 'dob = ?';
  $params_detail[] = $dob;
  $types_detail .= 's';
}
if($bio !== ''){
  $fields_detail[] = 'bio = ?';
  $params_detail[] = $bio;
  $types_detail .= 's';
}

if(!empty($fields_detail)){
  if($exist){
    $sql_detail_update = "UPDATE testdata_detail SET " . implode(',', $fields_detail) . " WHERE user_id = ?";
    $params_detail[] = $user_id;
    $types_detail .= 'i';
    $stmt_detail_update = $conn->prepare($sql_detail_update);
    $stmt_detail_update->bind_param($types_detail, ...$params_detail);
    $stmt_detail_update->execute();
    $_SESSION['success'] = "Updated successfully!";
    header("Location: dashboard.php");
    exit();
  }else{
    $sql_detail_insert = "INSERT INTO testdata_detail (user_id, address, phone, dob, bio) VALUE (?,?,?,?,?)";
    $stmt_detail_insert = $conn->prepare($sql_detail_insert);
    $address_ = $address  !== '' ? $address : null;
    $phone_ = $phone !== '' ? $phone : null;
    $dob_ = $dob !== '' ? $dob : null;
    $bio_ = $bio !== '' ? $bio : null;
    $stmt_detail_insert->bind_param(
        'issss',
        $user_id,
        $address_,
        $phone_,
        $dob_,
        $bio_
    );
    $stmt_detail_insert->execute();
    $_SESSION['success'] = "Inserted successfully!";
    header("Location: dashboard.php");
    exit;
  }
}else{
  $_SESSION['success'] = "Nothing Change!";
  header("Location: dashboard.php");
  exit;
}



?>