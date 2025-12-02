<?php
session_start();
require_once "database.php";
$sql = "SELECT id,username,gmail,create_at FROM testdata_2";
$results = $conn->query($sql);
$success = $_SESSION['success'] ?? '';
$pass_fail = $_SESSION['pass_fail'] ?? '';
$pass_success = $_SESSION['pass_success'] ?? '';

unset($_SESSION['success'], $_SESSION['pass_fail'], $_SESSION['pass_success']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col justify-center items-center relative">
 
  
  <table class="border border-collapse text-center mt-12 ">
     <?php if(!empty($success)): ?>
      <p class="text-green-500"><?php echo "$success" ?></p>
    <?php endif;?>
    <?php if(!empty($pass_fail)): ?>
      <p class="text-red-500"><?= $pass_fail ?></p>
    <?php endif;?>
    <tr class="border border-collapse ">
      <th class="border px-4 py-2">ID</th>
      <th class="border px-4 py-2">Username</th>
      <th class="border px-4 py-2">Gmail</th>
      <th class="border px-4 py-2">Action</th>
      <th class="border px-4 py-2">create_at</th>
    </tr>
    <?php if($results && $results->num_rows > 0): ?>
      <?php while($data = $results->fetch_assoc()): ?>
      <tr class="border border-collapse px-4 py-2">
        <td class="border px-4 py-2"><?php echo $data['id']; ?></td>
        <td class="border px-4 py-2"><?php echo $data['username']; ?></td>
        <td class="border px-4 py-2"><?php echo $data['gmail']; ?></td>
        
        <td class=" px-4 py-2 flex flex-row justify-between w-full">
          <input type="submit" name="actions" value="Edit" class="editBtn px-4 py-1 rounded-lg bg-amber-300 cursor-pointer" data-username ="<?= htmlspecialchars($data['username']); ?>" data-gmail ="<?= htmlspecialchars($data['gmail']); ?>" data-id="<?= htmlspecialchars($data['id']); ?>">
          <form action="action.php" method="post">
            <input type="hidden" name="username" value="<?php echo $data['username']; ?>">
            <input type="hidden" name="gmail" value="<?php echo $data['gmail']; ?>">
            <input type="hidden" name="getId" value="<?php echo $data['id'] ?>">
            <input type="submit" name="actions" value="Delete" onclick="return confirm('Are you sure want to delete this item?');" class="px-4 py-1 rounded-lg bg-red-500 cursor-pointer">
          </form>
        </td>
        <td class="border px-4 py-2"><?php echo $data['create_at']; ?></td>
      </tr>
      <?php endwhile; ?>
    <?php endif; ?>
  </table>
      <a href="index.php" class="px-4 py-2 rounded-lg bg-amber-300 border-2 border-amber-300 text-black hover:text-white hover:bg-transparent transition duration-300 ease-in-out cursor-pointer">Back</a>
  <!-- Edit form -->
  <div class="overlay hidden h-screen w-full bg-black/30 fixed top-0 items-center justify-center">
    <form action="action.php" method="post" class="editForm absolute p-8 bg-slate-600 w-[400px] min-h-[400px] flex flex-col  items-center rounded-xl  ">
      <h1 class="text-white text-lg ">Edit item</h1>
      <div class="text-white mt-4 w-full flex justify-between items-center">
        <label for="name">Username:</label>
        <input type="text" name="username" id="editUsername" class="border rounded-lg px-4 py-2" placeholder="Username">
      </div>
      <div class="text-white mt-4 w-full flex justify-between items-center">
        <label for="name">Gmail:</label>
        <input type="text" name="gmail" id="editGmail" class="border rounded-lg px-4 py-2" placeholder="Gmail">
      </div>
      <div class="text-white mt-4 w-full flex justify-between items-center">
        <label for="password">Old Password:</label>
        <input type="text" name="old_password" class="border rounded-lg px-4 py-2" placeholder="Password">
      </div>
      <div class="text-white mt-4 w-full flex justify-between items-center">
        <label for="name">New Password:</label>
        <input type="text" name="new_password" class="border rounded-lg px-4 py-2" placeholder="Password">
      </div>
      <div class="text-white mt-4 w-full flex justify-between items-center">
      <label for="role">Role:</label>
      <select name="role" id="role" class="border px-12 py-2 rounded-md">
        <option value="user" class="bg-slate-800 text-white hover:bg-slate-600" selected>User</option>
        <option value="admin" class="bg-slate-800 text-white hover:bg-slate-600" >Admin</option>
      </select>
    </div>
      <div class="text-white mt-4 w-full flex justify-between items-center">
        <button class="backBtn px-4 py-2 rounded-lg bg-amber-300 border-2 border-amber-300 text-black hover:text-white hover:bg-transparent transition duration-300 ease-in-out cursor-pointer">Back</button>
        <input class="px-4 py-2 rounded-lg bg-amber-300 border-2 border-amber-300 text-black hover:text-white hover:bg-transparent transition duration-300 ease-in-out cursor-pointer" type="submit" name="actions" value="Save Edit">
      </div>
      <input type="hidden" name="getId" id="getId">
    </form>
      
  </div>
  



  <script>
    const backBtn = document.querySelector('.backBtn');
    const overlay = document.querySelector('.overlay');
    const editUsername = document.getElementById('editUsername');
    const editGmail = document.getElementById('editGmail');
    const getId = document.getElementById('getId');
    const editForm = document.querySelector('.editForm')
    const editBtn = document.querySelectorAll('.editBtn');
    editBtn.forEach(btn=>{
      btn.addEventListener('click', (e) =>{
        e.preventDefault();
        console.log('editBtn press');
        editUsername.value = btn.dataset.username;
        editGmail.value = btn.dataset.gmail;
        getId.value = btn.dataset.id;
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        
      });
    });
    
    backBtn.addEventListener('click', (e) =>{
      e.preventDefault();
      overlay.classList.add('hidden');
    });
  </script>
  
</body>
</html>