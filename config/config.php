<?php
# config.php - database connection for CodeGuard Pro

# Database configuration
$host = "localhost";
$user = "root";
$password = "";
$database = " codeguard_pro"; #

# creat connection
$conn = new mysqli($host, $user, $password, $database);

# Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
