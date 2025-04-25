<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['userid'];

// Mark all notifications as read
$update_query = "UPDATE notifications SET is_read = 1 WHERE user_id = $user_id";
mysqli_query($conn, $update_query);

// Redirect back to the dashboard
header('Location: dash.php');
exit;
?>