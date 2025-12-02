<?php
require_once "database.php";
session_start();

$username = $_SESSION['old']['username'] ?? '';
$gmail = $_SESSION['old']['gmail'] ?? '';
$success = $_SESSION['success'] ?? [];
$errors = $_SESSION['errors'] ?? [];
$old =  $_SESSION['old'] ?? [];


unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['success']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col justify-center items-center h-screen">
  <form action="register_process.php" method="post" class="p-8 bg-slate-700 w-[400px] min-h-[400px] rounded-xl flex flex-col items-center text-white gap-4">
    <h1 class="text-lg text-white mb-8">Register Form</h1>
    <?php 
    if(isset($errors) && !empty($errors)){
      foreach($errors as $error){
    ?>
      <p class="text-red-500"><?php echo $error ?></p>
    <?php 
      
      }
    }
    ?>
    <?php foreach($success as $suc): ?>
      <p class="text-green-500"><?= $suc ?></p>
    <?php endforeach; ?>
  <?php if($success): ?>
    <p class="text-green-500"><?= $success ?></p>
  <?php endif; ?>
    
    <div class="flex flex-row items-center justify-between w-full">
      <label for="name">Name:</label>
      <input type="text" name="username" class="border px-4 py-2 rounded-md" placeholder="Username" value="<?= htmlspecialchars($username) ?>">
    </div>
    
    <div class="flex flex-row items-center justify-between w-full">
      <label for="name">Gmail:</label>
      <input type="text" name="gmail" class="border px-4 py-2 rounded-md" placeholder="Gmail" value="<?= htmlspecialchars($gmail) ?>">
    </div>
    <div class="flex flex-row items-center justify-between w-full">
      <label for="name">Password:</label>
      <input type="text" name="password" class="border px-4 py-2 rounded-md" placeholder="Username">
    </div>
    <div class="flex flex-row items-center justify-between w-full mt-4">
      <!-- <a href="index.php" class="text-amber-300 underline">Go back</a> -->
      <a href="login.php" class="text-amber-300 underline">Already have an account? login</a>
      <input type="submit" value="Register" class="px-4 py-2 bg-amber-300 border-2 border-amber-300 text-black hover:bg-transparent hover:text-white transition duration-300 ease-in-out rounded-lg cursor-pointer">
    </div>
    <a href="index.php" class="text-amber-300 underline">Go back</a>
  </form>
</body>
</html>