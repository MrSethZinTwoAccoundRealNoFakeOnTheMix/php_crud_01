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
$sql = "SELECT role,username,gmail,password,create_at,update_at FROM testdata_2 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result_main = $stmt->get_result();
if($result_main->num_rows === 1){
  $user_profile = $result_main->fetch_assoc();
  
}
$role = $user_profile['role'];
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

if($user_profile['role'] === 'admin'){
  $sql_admin = "SELECT id,role,username,gmail,create_at FROM testdata_2";
  $results_admin = $conn->query($sql_admin);

}
//-----retrive product data-----------
$product_list = $conn->query("SELECT * FROM products_list");



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
        <p class="text-white hover:underline hover:text-amber-300 cursor-pointer">Account Role,   <?= $user_profile['role']; ?></p>
      </div>
      <div class="viewCart flex items-center">
        <p id="cartCount" class="cartCount absolute top-0 right-0 p-2 rounded-full bg-grey-500"></p>
        <a href="add_to_card.php" class="px-4 py-2 bg-amber-300 border-transparent border-2 text-black font-semibold rounded-lg hover:bg-transparent hover:text-white hover:border-red-500 transition duration-300 ease-in-out cursor-pointer ">View Cart</a>
      </div>
      <div class="logout flex items-center">
        <a href="logout.php" class="px-4 py-2 bg-red-600 border-transparent border-2 text-black font-semibold rounded-lg hover:bg-transparent hover:text-white hover:border-red-500 transition duration-300 ease-in-out cursor-pointer ">Logout</a>
      </div>
    </nav>
  </header>
  <!-- ----------------aside dashboard navigation-------------- -->
  <aside class="fixed w-64 bg-slate-900 top-16 h-full p-4 flex flex-col space-y-2">
    <h1 class="text-amber-300 font-semibold text-xl mb-6">Dashboard</h1>
    <button class="dashboard_nav bg-slate-700 hover:bg-slate-700 rounded p-2 text-left cursor-pointer" >Home</button>
    <button class="dashboard_nav hover:bg-slate-700 rounded p-2 text-left cursor-pointer" >Product</button>
    <button class="dashboard_nav hover:bg-slate-700 rounded p-2 text-left cursor-pointer" >Profile</button>
    <button class="dashboard_nav hover:bg-slate-700 rounded p-2 text-left cursor-pointer" >Setting</button>

    <?php if($user_profile['role'] === 'admin'): ?>
      <button class="dashboard_nav hover:bg-slate-700 rounded p-2 text-left cursor-pointer" >Add Products</button>
    <?php endif; ?>
    <?php if($user_profile['role'] === 'admin'): ?>
      <button class="dashboard_nav hover:bg-slate-700 rounded p-2 text-left cursor-pointer" >All User Data</button>
    <?php endif; ?>
  </aside>
  <main class="flex-1 ml-64 mt-16 overflow-auto p-6">
    <?php if(!empty($success)): ?>
    <p class="success text-green-500"><?= $success ?></p>
    <?php endif; ?>
    <!-- User_profiles -->
    <div id="home" class="panel p-6 flex">
      <p for="home" class="flex flex-row text-center justify-center w-full mr-64">Welcome, <span class="text-amber-300 font-semibold"> <?= htmlspecialchars($user_profile['username']); ?></span></p>
    </div>
    <!-- view product -->
    <div id="product" class="panel p-6 hidden">
      <div class="card-container flex flex-row flex-wrap gap-8 justify-center">
        <?php if($product_list->num_rows > 0): ?>
          <?php while($product = $product_list->fetch_assoc()): ?>

            <div class="product-card w-64 bg-slate-600 shadow-xl rounded-lg overflow-hidden transition duration-300 hover:shadow-2xl">

              <div class="img h-48 overflow-hidden">
                <img src="<?= htmlspecialchars($product['image']); ?>" 
                    alt="<?= htmlspecialchars($product['name']); ?>"
                    class="w-full h-full object-cover">
              </div>

              <div class="text p-4">
                <h1 class="productName font-bold text-xl text-amber-300 mb-1">
                  <?= htmlspecialchars($product['name']); ?><?= htmlspecialchars($product['id']); ?>
                </h1>
                
                <div class="flex justify-between items-center mt-2">
                  <h3 class="productPrice text-2xl font-semibold text-green-500">
                    $<?= htmlspecialchars($product['price']); ?>
                  </h3>
                  <p class="productQty text-sm text-white/80">
                    In Stock: <?= htmlspecialchars($product['qty']); ?>
                  </p>
                </div>
                
                <!-- <form action="add_to_cart.php" method="post" class="mt-4">
                  <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']); ?>">
                  <button type="submit" class="w-full bg-amber-300 text-black hover:text-white border-2 border-transparent hover:border-amber-300 py-2 rounded-md hover:bg-transparent transition duration-300 cursor-pointer">
                    Add to Cart
                  </button>
                </form> -->

                <!-- ------add to card button----- -->
                <button type="submit" class="addToCardBtn w-full bg-amber-300 text-black hover:text-white border-2 border-transparent hover:border-amber-300 py-2 rounded-md hover:bg-transparent transition duration-300 cursor-pointer"
                data-id = "<?= htmlspecialchars($product['id']); ?>">
                    Add to Cart
                </button>

              </div>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </div>



    <!-- profile -->
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
      <!-- --------Add product for admin--------- -->
    <div id="addProduct" class="panel p-6 hidden">
      <form action="product_process.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 items-center w-full ">

        
        <div class="flex flex-row gap-8 justify-between w-full">
          <p class="px-12 py-2 w-64">Product Name: </p>
          <input type="text" name="productName" class="border px-4 w-full" placeholder="Add Product Name"> 
        </div>
        <div class="flex flex-row gap-8 justify-between w-full">
          <p class="px-12 py-2 w-64">Product Qty: </p>
          <input type="text" name="productQty" class="border px-4 w-full" placeholder="Add Product Quantity"> 
        </div>
        <div class="flex flex-row gap-8 justify-between w-full">
          <p class="px-12 py-2 w-64">Product Price: </p>
          <input type="number" name="productPrice" class="border px-4 w-full" placeholder="Add Product Price"> 
        </div>
        <div class="flex flex-row gap-8 justify-between w-full">
          <p class="px-12 py-2 w-64">Product Description: </p>
          <input type="text" name="productDescription" class="border px-4 w-full" placeholder="Add Product Description"> 
        </div>
        <div class="flex flex-col items-center justify-center space-x-4 w-full">
                <!-- 2. The Styled Label (the button) -->
            <div class="flex items-center justify-center flex-row space-x-4">
              <input type="file" name="image" id="image" class="px-4 py-2 rounded-lg bg-amber-300">

            </div>
                
            </div>
        <button type="submit" class="px-4 py-2 rounded-lg bg-amber-300 border-2 border-amber-300 text-black hover:text-white hover:bg-transparent transition duration-300 ease-in-out cursor-pointer">Upload</button>
      </form>

    </div>

    <!-- --------------data table for admin role--------------- -->
    <div id="dataTable" class="panel ml-32 p-6 hidden">
      <?php if(!empty($success)): ?>
    <p class="text-green-500"><?php echo "$success" ?></p>
  <?php endif;?>
  <?php if(!empty($pass_fail)){
    echo "<p class='text-red-500'><?php $pass_fail ?></p>";
  }
  ?>
  <table class="border border-collapse text-center mt-12 ">
    <tr class="border border-collapse ">
      <th class="border px-4 py-2">ID</th>
      <th class="border px-4 py-2">Username</th>
      <th class="border px-4 py-2">Gmail</th>
      <th class="border px-4 py-2">Action</th>
      <th class="border px-4 py-2">create_at</th>
    </tr>
    <?php if($results_admin && $results_admin->num_rows > 0): ?>
      <?php while($data = $results_admin->fetch_assoc()): ?>
      <tr class="border border-collapse px-4 py-2">
        <td class="border px-4 py-2"><?php echo $data['id']; ?></td>
        <td class="border px-4 py-2"><?php echo $data['username']; ?></td>
        <td class="border px-4 py-2"><?php echo $data['gmail']; ?></td>
        
        <td class=" px-4 py-2 flex flex-row justify-between w-full">
          <input type="submit" name="actions" value="Edit" class="editBtn px-4 py-1 rounded-lg bg-amber-300 cursor-pointer" 
          data-username ="<?= htmlspecialchars($data['username']); ?>" 
          data-gmail ="<?= htmlspecialchars($data['gmail']); ?>" 
          data-id="<?= htmlspecialchars($data['id']); ?>"
          data-role="<?= htmlspecialchars($data['role']) ?>">

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
      <a href="index.php" class=" px-4 py-2 rounded-lg bg-amber-300 border-2 border-amber-300 text-black hover:text-white hover:bg-transparent transition duration-300 ease-in-out cursor-pointer">Back</a>
    <!-- ----------------------------------Edit form ------------------------------>
    <div class="overlay hidden h-screen w-screen bg-black/30 fixed top-0 items-center justify-center">
      <form action="action.php" method="post" class="editForm absolute p-8 bg-slate-600 w-[400px] min-h-[400px] flex flex-col  items-center rounded-xl z-20 ">
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
          <option value="user" class="bg-slate-800 text-white hover:bg-slate-600" >User</option>
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
    //-----------admin data table script----------
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
        document.getElementById('role').value = btn.dataset.role;
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        
      });
    });
    
    backBtn.addEventListener('click', (e) =>{
      e.preventDefault();
      overlay.classList.add('hidden');
    });

    //-----image upload style---------
    document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file-upload');
            const fileNameDisplay = document.getElementById('file-name');

            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    fileNameDisplay.textContent = fileInput.files[0].name;
                    fileNameDisplay.classList.remove('text-gray-500', 'italic');
                    fileNameDisplay.classList.add('text-gray-800', 'font-medium');
                } else {
                    fileNameDisplay.textContent = 'No file selected';
                    fileNameDisplay.classList.remove('text-gray-800', 'font-medium');
                    fileNameDisplay.classList.add('text-gray-500', 'italic');
                }
            });
        });



    //----------add to card button-----
    document.querySelectorAll('.addToCardBtn').forEach(btn =>{
      btn.addEventListener('click', async () =>{
        const product_id  = btn.dataset.id;

        const formData = new FormData();
        formData.append('id', product_id);
        
        const res = await fetch('add_to_card.php', {
          method: 'POST',
          body: formData
        });
        const cartCount = await res.text();

        console.log(cartCount);
        document.getElementById('cartCount').innerHTML = cartCount;
      });
    });
  </script>
</body>
</html>