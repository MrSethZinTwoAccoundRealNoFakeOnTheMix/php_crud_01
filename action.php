<?php
session_start();
require_once "database.php";

// $actions = $_POST['actions'];
// $username =$_POST['username'];
// $gmail = $_POST['gmail'];
// $getId = $_POST['getId'];
// $oldPass = $_POST['old_password'];
// $newPass = $_POST['new_password'];

$actions = $_POST['actions'] ?? '';
$id = $_POST['getId'] ?? '';
$username = $_POST['username'] ?? '';
$gmail = $_POST['gmail'] ?? '';
$oldPass = $_POST['old_password'] ?? '';
$newPass = $_POST['new_password'] ?? '';
$role = $_POST['role'] ?? '';
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
  //---------create dynamic update if there's no value change nothing-------
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
  if ($role !== '') {
    $fields[] = "role = ?";
    $params[] = $role;
    $types .= "s";
  }
  // if($oldPass !== ''){
  //   $fields[] = ' = ?';
  //   $params[] = $username;
  //   $type .= 's';
  // }
  // if($newPass !== ''){
  //   $fields[] = 'username = ?';
  //   $params[] = $username;
  //   $type .= 's';
  // }
  
  if($oldPass !== '' && $newPass !== ''){
    $sql = "SELECT password FROM testdata_2 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($hashedPasswordFromDB);
    $stmt->fetch();
    $stmt->close();
    if(password_verify($oldPass, $hashedPasswordFromDB)){
      $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);
      $fields[] = 'password = ?';
      $params[] = $hashedNewPass;
      $types .= 's';

      $sql = "UPDATE testdata_2 SET " . implode(',', $fields) . " WHERE id = ?";
      $types .= 'i';
      $fields[] = $id;
      $stmt = $conn->prepare($sql);
      $stmt->bind_param($types,...$params);
      $stmt->execute();
      $_SESSION['success'] = "Item edited Successfully!";
      header("Location: dashboard.php");
      exit;
    }else{
      $_SESSION['pass_fail'] = "Incorrect Password! Update failed!";
      header("Location: dashboard.php");
      exit;
    }
  }

  $sql_no_pass = "UPDATE testdata_2 SET " . implode(',', $fields) . " WHERE id = ?";
  $types .= 'i';
  $params[] = $id;
  $stmt_no_pass = $conn->prepare($sql_no_pass);
  $stmt_no_pass->bind_param($types, ...$params);
  $stmt_no_pass->execute();
  $_SESSION['success'] = "Item edited Successfully!";
  header("Location: dashboard.php");
  exit;

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