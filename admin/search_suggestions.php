<?php
session_start();
include 'dbconnect.php';

header('Content-Type: application/json');

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$results = [];

if (!empty($query) && strlen($query) > 2) {
    $search_term = "%{$query}%";
    
    // Search students
    $stmt = $conn->prepare("SELECT CONCAT(first_name, ' ', last_name) as name FROM students 
                           WHERE first_name LIKE ? OR last_name LIKE ? 
                           LIMIT 5");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Search staff
    $stmt = $conn->prepare("SELECT CONCAT(first_name, ' ', last_name) as name FROM staff 
                           WHERE first_name LIKE ? OR last_name LIKE ? 
                           LIMIT 5");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $staff = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Search courses
    $stmt = $conn->prepare("SELECT course_name as name FROM courses 
                           WHERE course_name LIKE ? 
                           LIMIT 5");
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    $results = array_merge($students, $staff, $courses);
}

echo json_encode($results);
?>