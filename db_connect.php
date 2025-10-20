<?php
$DB_HOST = '127.0.0.1';
$DB_PORT = 3307;       
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'grading_portal';

// 2. Create connection
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

// 3. Check connection
if ($conn->connect_error) {
    // If connection fails, stop the script and show an error message
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset to avoid encoding problems
$conn->set_charset("utf8mb4");

// Now $conn is ready — include this file in other PHP pages with:
// include 'db_connect.php';
?>
