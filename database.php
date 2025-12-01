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

$sql = "CREATE TABLE IF NOT EXISTS testdata_detail(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address VARCHAR(255),
    phone VARCHAR(50),
    dob DATE,
    bio TEXT,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES testdata_2(id) ON DELETE CASCADE
)";

// if($conn->query($sql) === TRUE){
//   echo "table created!";
// }
// else{
//   echo "error". $conn->error;
// }


?>