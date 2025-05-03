<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get search query
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Initialize results array
$results = [
    'students' => [],
    'staff' => [],
    'courses' => [],
    'notices' => []
];

// Only search if query is not empty
if (!empty($query)) {
    $search_term = "%{$query}%";
    
    // Search students
    $stmt = $conn->prepare("SELECT * FROM students 
                           WHERE first_name LIKE ? OR surname LIKE ? OR middle_name LIKE ?  OR student_id LIKE ? 
                           LIMIT 5");
    $stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
    $stmt->execute();
    $results['students'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Search staff
    $stmt = $conn->prepare("SELECT * FROM staff 
                           WHERE full_name LIKE ? OR staff_id LIKE ? 
                           LIMIT 5");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $results['staff'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Search courses
    $stmt = $conn->prepare("SELECT * FROM courses 
                           WHERE course_name LIKE ? OR course_code LIKE ? 
                           LIMIT 5");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $results['courses'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Search notices
    $stmt = $conn->prepare("SELECT * FROM notices 
                           WHERE title LIKE ? OR content LIKE ? 
                           LIMIT 5");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $results['notices'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Include the HTML for search results
include 'search_results.php';
?>