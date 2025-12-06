<?php
// db.php
// Database connection using MySQLi

$host   = "localhost";          // Database host
$dbname = "complaints_system";  // Database name
$user   = "root";               // Database username
$pass   = "";                   // Database password

$conn = new mysqli($host, $user, $pass, $dbname);

// تحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
