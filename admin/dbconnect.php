<?php
// Enable error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

define('DB_USER', 'root');
define('DB_PSWD', ''); 
define('DB_HOST', 'localhost'); 
define('DB_NAME', 'monaco_student_registration'); // Removed extra space

// Establish database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if($conn->connect_error) {
    // Log error instead of displaying to users
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection error. Please try again later.");
}else{
    // Log successful connection
    error_log("Database connected successfully.");
}

// Set charset to utf8mb4 for proper encoding
$conn->set_charset("utf8mb4");
?>