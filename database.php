<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "loginform";

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected!";

$sql = "CREATE TABLE IF NOT EXISTS testdata_2 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    gmail VARCHAR(50),
    password VARCHAR(255),
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
// if($conn->query($sql) === TRUE){
//   echo "table created!";
// }
// else{
//   echo "error". $conn->error;
// }


?>