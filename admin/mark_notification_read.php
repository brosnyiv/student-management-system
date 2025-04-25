<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

// Check if notification ID is provided
if (empty($_POST['notification_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'No notification ID provided']);
    exit;
}

$notification_id = intval($_POST['notification_id']);
$user_id = $_SESSION['userid'];

// Update notification as read
$update_query = "UPDATE notifications SET is_read = 1 WHERE id = $notification_id AND user_id = $user_id";
$result = mysqli_query($conn, $update_query);

if ($result) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>