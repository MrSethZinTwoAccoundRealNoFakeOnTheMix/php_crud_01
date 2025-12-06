<?php
session_start();
//-----account authentication---------
$user_token = trim($_SESSION['id'] ?? '');
if(empty($user_token)){
  $_SESSION['login_fail'] = "unknown user please log in again.";
  header("Location: login.php");
  exit;
}

//----------after authentication-----------
require_once "database.php";






?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
  /* Chrome, Edge, Opera */
  input[type="number"]::-webkit-inner-spin-button,
  input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

</style>
</head>
<body class="flex items-center px-36">
  <main class="flex justify-between flex-row mt-32 w-full">
    <form  action="" method="post" class="Cart p-4 rounded-3xl border shadow-md w-2/3 mr-8">
      <!-- top part -->
      <div class="top flex flex-row justify-between ">
        <!-- left top  -->
        <div class="left flex flex-row gap-4">
          <h1 class="font-semibold ">Cart</h1>
          <span class=" text-black/80">(test)</span>
        </div>
        <!-- right top  -->
         <input type="submit" class="text-red-500 cursor-pointer font-semibold" value="x Clear cart">
      </div>
      <div class="middle flex flex-col ">
        <!-- top header value  -->
        <div class="top grid grid-cols-[3fr_1.5fr_1.5fr_0.8fr] mt-8 font-semibold ">
          <h2>Product</h2>
          <h2>Quantity</h2>
          <h2>Price</h2>
          <h2>Action</h2>
        </div>
        <!-- show product in cart  -->
         <form action="cart_edit.php" method="post" class="border rounded-3xl  w-full">
            <!-- inside card info  -->
            <div class="container p-4 grid grid-cols-[2.7fr_1.5fr_2fr_1fr] items-center border border-slate-300 rounded-xl mt-4 ">
              <!-- left side img and product name  -->
               <div class="leftside flex flex-row ">
                <div class="img w-[80px] h-[80px] overflow-hidden"><img class="w-full h-full object-cover rounded-xl" src="pro_Image/funny-profile-pictures-16.jpg" alt=""></div>
                <div class="productName  flex flex-col justify-center ml-4">
                  <p class=" font-semibold text-md">Apple Air Pro</p>
                  <span class="text-black/50 text-sm">White</span>
                </div>
               </div>
              
              <!-- middle section quantity and edit qty  -->
               <div class="quantity flex items-center flex-row gap-x-4 justify-center">
                  <button class="minus px-2 font-bold text-xl rounded-full border border-slate-300 cursor-pointer">-</button>
                  <input type="number" name="qty" class="max-w-16 text-center border border-slate-300" id="product_qty" value="1">
                  <button class="plus px-2 font-bold text-xl rounded-full border border-slate-300 cursor-pointer">+</button>
               </div> 
               <!-- price display  -->
                <div class="price flex justify-center items-center">
                  <p class="font-semibold text-center">12332$</p>
                </div>
                <!-- delete product  -->
                 <div class="delete flex justify-center items-center">
                  <p class="font-bold text-2xl text-red-500 text-center cursor-pointer ">✖</p>
                 </div>
            </div>
         </form>
      </div>
    </form>
    <form action="" method="post" class="Checkout rounded-2xl bg-slate-100  w-1/3 ml-8">

    </form>
  </main>
  


</body>
</html>