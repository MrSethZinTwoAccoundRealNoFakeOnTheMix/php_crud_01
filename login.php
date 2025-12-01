<?php
session_start();
require_once "database.php";

// $sql = "SELECT id,username,gmail,password FROM testdata_2";
// $results = $conn->query($sql);
$validation = $_SESSION['validation'] ?? [];
$username = $_SESSION['old']['username'] ?? '';
$old = $_SESSION['old'] ?? [];
$login_fail = $_SESSION['login_fail'] ?? '';


unset($_SESSION['validation'], $_SESSION['username'], $_SESSION['old'], $_SESSION['login_fail']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col items-center">
  <form action="login_process.php" method="post" class="text-white w-[400px] min-h-[350px] flex items-center flex-col mt-12 p-8 rounded-xl bg-slate-800 gap-4">
    <h1 class="font-semibold mb-12">Login Form</h1>
    <?php if(!empty($validation)): ?>
      <?php foreach($validation as $error): ?>
        <ul>
          <p class="text-red-500"><?= $error ?></p>
        </ul>
      <?php endforeach; ?>
    <?php endif; ?>
    <?php if (!empty($login_fail)): ?>

      <?php if (is_array($login_fail)): ?>
          <?php foreach ($login_fail as $fail): ?>
              <p class="text-red-500"><?= htmlspecialchars($fail) ?></p>
          <?php endforeach; ?>
      <?php else: ?>
          <p class="text-red-500"><?= htmlspecialchars($login_fail) ?></p>
      <?php endif; ?>

    <?php endif; ?>

    <div class="flex flex-row items-center justify-between w-full ">
      <label for="name">Username:</label>
      <input type="text" name="userInput" class="border px-4 py-2 rounded-md" placeholder="Username" value="<?= htmlspecialchars($username) ?>">
    </div>
    <div class="flex flex-row items-center justify-between w-full">
      <label for="name">Passowrd:</label>
      <input type="text" name="passInput" class="border px-4 py-2 rounded-md" placeholder="Password">
    </div>
    <div class="flex flex-row items-center justify-between w-full">
      <a href="register.php" class="text-amber-300 hover:underline">Don't have an account? Register</a>
      <input type="submit" value="Login" class="px-4 py-2 bg-amber-300 border-2 border-amber-300 text-black hover:bg-transparent hover:text-white transition duration-300 ease-in-out rounded-lg cursor-pointer">
    </div>
    <a href="index.php" class="text-amber-300 underline">Go back</a>
  </form>
</body>
</html>