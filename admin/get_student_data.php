<?php
// Include database connection
include 'dbconnect.php';

// Initialize response array
$response = array(
    'success' => false,
    'data' => null,
    'message' => ''
);

// Check if studentId is provided
if (isset($_POST['studentId'])) {
    $studentId = mysqli_real_escape_string($conn, $_POST['studentId']);
    
    // Query to fetch student data
    $query = "SELECT students.student_name, students.email, students.phone, 
              courses.id as course_id, courses.course_name 
              FROM students 
              LEFT JOIN courses ON students.course_id = courses.id 
              WHERE students.student_id = '$studentId'";
    
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        // Student found
        $student_data = mysqli_fetch_assoc($result);
        $response['success'] = true;
        $response['data'] = $student_data;
    } else {
        // Student not found
        $response['message'] = 'Student not found';
    }
} else {
    // StudentId not provided
    $response['message'] = 'Student ID is required';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close database connection
mysqli_close($conn);
?>