<?php
require_once 'dbconnect.php';

// Check if user is logged in
if(!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

try {
    // Case-sensitive table name matching your schema
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows !== 1) {
        // Invalid session - clear it and redirect
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
    
    // Fetch student data if needed later
    $student = $result->fetch_assoc();
    
} catch(Exception $e) {
    error_log("Login verification error: " . $e->getMessage());
    header("Location: login.php");
    exit();
}
?>