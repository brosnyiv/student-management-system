<?php
session_start(); // Start the session
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'dbconnect.php'; // Include the database connection file

// Check if user is not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


$user_id = $_SESSION['user_id'];

// Mark all notifications as read
$update_query = "UPDATE notifications SET is_read = 1 WHERE user_id = $user_id";
mysqli_query($conn, $update_query);

// Redirect back to the dashboard
header('Location: dash.php');
exit;
?>