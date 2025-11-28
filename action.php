<?php
session_start();
require_once "database.php";

// $actions = $_POST['actions'];
// $username =$_POST['username'];
// $gmail = $_POST['gmail'];
// $getId = $_POST['getId'];
// $inputPass = $_POST['old_password'];
// $newPass = $_POST['new_password'];

$actions = $_POST['actions'] ?? '';
$id = $_POST['getId'] ?? '';
$username = $_POST['username'] ?? '';
$gmail = $_POST['gmail'] ?? '';
$inputPass = $_POST['old_password'] ?? '';
$newPass = $_POST['new_password'] ?? '';

if($actions === 'Delete'){
  $sql = "DELETE FROM testdata_2 WHERE gmail = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $gmail);
  $stmt->execute();
  $_SESSION['success'] = "Item deleted Successfully!";
  header("Location: dataTable.php");
  exit;
}
if($actions === 'Save Edit'){
  $sql = "SELECT password FROM testdata_2 where id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->bind_result($hashedPasswordFromDB);
  $stmt->fetch();
  $stmt->close();
  if(password_verify($inputPass, $hashedPasswordFromDB)){
    $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);
    $sql = "UPDATE testdata_2 SET username = ?, gmail = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $username, $gmail, $hashedNewPass, $id);
    $stmt->execute();
    $_SESSION['success'] = "Item edited Successfully!";
    header("Location: dataTable.php");
    exit;
  }else{
    $_SESSION['pass_fail'] = "Incorrect Password!";
    header("Location: dataTable.php");
    exit;
  }
}

?>
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col items-center justify-center">

    <form action="action.php" method="post" class="editForm p-8 bg-slate-600 w-[400px] h-[400px] flex flex-col  items-center rounded-xl mt-8 ">
      <h1 class="text-white text-lg ">Edit item</h1>
      <div class="text-white mt-4 w-full flex justify-between items-center">
        <label for="name">Username:</label>
        <input type="text" name="username" class="border rounded-lg px-4 py-2" placeholder="Username" value="<?php echo htmlspecialchars($username ?? '') ?>">
      </div>
      <div class="text-white mt-4 w-full flex justify-between items-center">
        <label for="name">Gmail:</label>
        <input type="text" name="gmail" class="border rounded-lg px-4 py-2" placeholder="Gmail" value="<?php echo htmlspecialchars($_POST['gmail']) ?>">
      </div>
      <div class="text-white mt-4 w-full flex justify-between items-center">
        <label for="password">Old Password:</label>
        <input type="text" name="password" class="border rounded-lg px-4 py-2" placeholder="Password">
      </div>
      <div class="text-white mt-4 w-full flex justify-between items-center">
        <label for="name">New Password:</label>
        <input type="text" name="new_password" class="border rounded-lg px-4 py-2" placeholder="Password">
      </div>
      <div class="text-white mt-4 w-full flex justify-between items-center">
        <a href="dataTable.php" class="px-4 py-2 rounded-lg bg-amber-300 border-2 border-amber-300 text-black hover:text-white hover:bg-transparent transition duration-300 ease-in-out cursor-pointer">Back</a>
        <input class="px-4 py-2 rounded-lg bg-amber-300 border-2 border-amber-300 text-black hover:text-white hover:bg-transparent transition duration-300 ease-in-out cursor-pointer" type="submit" value="Save Edit">
      </div>
    </form>
    
</body>
</html> -->