<?php
session_start();

if(!isset($_SESSION['LAST_ACTIVITY']) || (time() - $_SESSION['LAST_ACTIVITY'] > 6000)){
  session_regenerate_id(true);
  session_unset();
  // session_destroy();

  // session_start();
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
$user_id = $_SESSION['id'];
$sql = "SELECT username,gmail,password,create_at,update_at FROM testdata_2 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result_main = $stmt->get_result();
if($result_main->num_rows === 1){
  $user_profile = $result_main->fetch_assoc();
}
$sql_detail = "SELECT address,phone,dob,bio FROM testdata_detail WHERE user_id = ? LIMIT 1";
$stmt_detail = $conn->prepare($sql_detail);
$stmt_detail->bind_param('i', $user_id);
$stmt_detail->execute();
$result_detail = $stmt_detail->get_result()->fetch_assoc();

//---- Merge Table ----
$user_profile = array_merge($user_profile, [
    'address' => $result_detail['address'] ?? '',
    'phone' => $result_detail['phone'] ?? '',
    'dob' => $result_detail['dob'] ?? '',
    'bio' => $result_detail['bio'] ?? ''
]);
$success = $_SESSION['success'] ?? '';


unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="flex bg-slate-800 text-white">
  <header class="fixed w-full top-0 z-10 shadow">
    <nav class="mx-auto flex max-w-screen-xl items-center justify-between px-4 py-3">
      <div class="dashboard flex items-center">
        <p class="font-semibold cursor-pointer text-amber-300"><?= htmlspecialchars($user_profile['username']); ?></p>
      </div>
      <div class="profile flex items-center">
        <p class="text-white hover:underline hover:text-amber-300 cursor-pointer"><?= $user_profile['gmail']; ?></p>
      </div>
      <div class="logout flex items-center">
        <a href="logout.php" class="px-4 py-2 bg-red-600 border-2 text-black font-semibold rounded-lg hover:bg-transparent hover:text-white hover:border-red-500 transition duration-300 ease-in-out cursor-pointer ">Logout</a>
      </div>
    </nav>
  </header>
  <aside class="fixed w-64 bg-slate-900 top-16 h-full p-4 flex flex-col space-y-2">
    <h1 class="text-amber-300 font-semibold text-xl mb-6">Dashboard</h1>
    <button class="dashboard_nav bg-slate-700 hover:bg-slate-700 rounded p-2 text-left cursor-pointer" >Home</button>
    <button class="dashboard_nav hover:bg-slate-700 rounded p-2 text-left cursor-pointer" >Profile</button>
    <button class="dashboard_nav hover:bg-slate-700 rounded p-2 text-left cursor-pointer" >Setting</button>
  </aside>
  <main class="flex-1 ml-64 mt-16 overflow-auto p-6">
    <?php if(!empty($success)): ?>
    <p class="success text-green-500"><?= $success ?></p>
    <?php endif; ?>
    <!-- User_profiles -->
    <div id="home" class="panel p-6 flex">
      <p for="home" class="flex flex-row text-center justify-center w-full mr-64">Welcome, <span class="text-amber-300 font-semibold"> <?= htmlspecialchars($user_profile['username']); ?></span></p>
    </div>
    <div id="profile" class="panel p-6 hidden ">
      <form action="update.php" method="post" class="flex flex-col gap-4 items-center w-full">
        <div class="flex flex-row gap-8 justify-between w-full">
          <p class="px-12 py-2 w-64 ">Username: </p>
          <input name="username" type="text" value="<?= htmlspecialchars($user_profile['username']) ?>" class="border px-4 w-full">
        </div>
        <div class="flex flex-row gap-8 justify-between w-full">
          <p class="px-12 py-2 w-64 ">Gamil: </p>
          <input name="gmail" type="text" value="<?= htmlspecialchars($user_profile['gmail']) ?>" class="border px-4 w-full">
        </div>
        <div class="flex flex-row gap-8 justify-between w-full">
          <p class="px-12 py-2 w-64 ">Address: </p>
          <input name="address" type="text" value="<?= htmlspecialchars($user_profile['address']) ?>" class="border px-4 w-full">
        </div>
        <div class="flex flex-row gap-8 justify-between w-full">
          <p class="px-12 py-2 w-64 ">Phone Number: </p>
          <input name="phone" type="text" value="<?= htmlspecialchars($user_profile['phone']) ?>" class="border px-4 w-full">
        </div>
        <div class="flex flex-row gap-8 justify-between w-full">
          <p class="px-12 py-2 w-64 ">Date of Birth: </p>
          <input id="dob" name="dob" type="date" placeholder="Click to Select" value="<?= htmlspecialchars($user_profile['dob']) ?>" class="border px-4 w-full">
        </div>
        <div class="flex flex-row gap-8 justify-between w-full">
          <p class="px-12 py-2 w-64 ">Bio: </p>
          <input name="bio" type="text" value="<?= htmlspecialchars($user_profile['bio']) ?>" class="border px-4 w-full">
        </div>
        <input type="submit" value="Save Change" onclick="return confirm('Are you sure want to change?');" class="px-4 py-2 rounded-lg bg-amber-300 border-2 border-amber-300 text-black hover:text-white hover:bg-transparent transition duration-300 ease-in-out cursor-pointer">
      </form>
    </div>
    <div id="setting" class="panel  p-6 hidden">
      setting
    </div>
  </main>
  <script>
    const dash_nav = document.querySelectorAll('.dashboard_nav');
    const panels = document.querySelectorAll('.panel')
    dash_nav.forEach((nav, index) => {
      nav.addEventListener('click', () =>{
        dash_nav.forEach(b => b.classList.remove('bg-slate-700'));
        nav.classList.add('bg-slate-700');
        panels.forEach(p => p.classList.add('hidden'));
        panels[index].classList.remove('hidden');
      })
    });
    flatpickr("#dob", {
    dateFormat: "Y-m-d",   // matches your database format
    defaultDate: "<?= htmlspecialchars($user_profile['dob']) ?>",
    });

    setTimeout(() => {
      const message = document.querySelector('.success').classList.add('hidden'); 
    }, 3000);
    
  </script>
</body>
</html>