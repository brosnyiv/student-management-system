<?php

include('dbconnect.php');

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate required fields
    $required_fields = [
        'courseName', 
        'courseCode', 
        'courseLevel', 
        'courseDepartment',
        'courseDuration',
        'courseCapacity',
        'courseLeadInstructor',
        'courseStatus',
        'courseFee',
        'courseStartDate'
    ];
    
    $errors = [];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "Field $field is required";
        }
    }
    
    // If there are validation errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: new_course.php");
        exit;
    }
    
    // Sanitize input
    $courseName = $conn->real_escape_string($_POST['courseName']);
    $courseCode = $conn->real_escape_string($_POST['courseCode']);
    $courseLevel = $conn->real_escape_string($_POST['courseLevel']);
    $courseDepartment = $conn->real_escape_string($_POST['courseDepartment']);
    $courseDuration = intval($_POST['courseDuration']);
    $courseCapacity = intval($_POST['courseCapacity']);
    $courseLeadInstructor = intval($_POST['courseLeadInstructor']);
    $courseStatus = $conn->real_escape_string($_POST['courseStatus']);
    $courseDescription = $conn->real_escape_string($_POST['courseDescription'] ?? '');
    $courseFee = floatval($_POST['courseFee']);
    $courseStartDate = $conn->real_escape_string($_POST['courseStartDate']);

    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Insert course into the database
        $insertCourse = "INSERT INTO courses (
            course_name, 
            course_code, 
            level, 
            department, 
            duration, 
            capacity, 
            lead_instructor_id, 
            status, 
            description, 
            fee, 
            start_date,
            created_at
        ) VALUES (
            '$courseName', 
            '$courseCode', 
            '$courseLevel', 
            '$courseDepartment', 
            $courseDuration, 
            $courseCapacity, 
            $courseLeadInstructor, 
            '$courseStatus', 
            '$courseDescription', 
            $courseFee, 
            '$courseStartDate',
            NOW()
        )";
        
        $conn->query($insertCourse);
        $courseId = $conn->insert_id;
        
        // If there was an error inserting the course
        if ($courseId <= 0) {
            throw new Exception("Failed to insert course: " . $conn->error);
        }
        
        // Process course units
        if (isset($_POST['units'])) {
            $unitsData = json_decode($_POST['units'], true);
            
            if ($unitsData) {
                foreach ($unitsData as $semesterId => $semesterData) {
                    // Parse the semester ID to get year and semester
                    preg_match('/semester(\d+)year(\d+)/', $semesterId, $matches);
                    $semester = $matches[1] ?? 1;
                    $year = $matches[2] ?? 1;
                    
                    // Loop through each unit in this semester
                    $unitCount = count($semesterData['unitName']);
                    for ($i = 0; $i < $unitCount; $i++) {
                        $unitName = $conn->real_escape_string($semesterData['unitName'][$i]);
                        $unitCode = $conn->real_escape_string($semesterData['unitCode'][$i]);
                        $instructor = intval($semesterData['instructor'][$i]);
                        $credits = intval($semesterData['credits'][$i]);
                        
                        // Skip if unit name or code is empty
                        if (empty($unitName) || empty($unitCode)) {
                            continue;
                        }
                        
                        $insertUnit = "INSERT INTO course_units (
                            course_id,
                            unit_name,
                            unit_code,
                            instructor_id,
                            credits,
                            year,
                            semester,
                            created_at
                        ) VALUES (
                            $courseId,
                            '$unitName',
                            '$unitCode',
                            $instructor,
                            $credits,
                            $year,
                            $semester,
                            NOW()
                        )";
                        
                        $conn->query($insertUnit);
                        
                        if ($conn->error) {
                            throw new Exception("Failed to insert unit: " . $conn->error);
                        }
                    }
                }
            }
        }
        
        // Commit the transaction
        $conn->commit();
        
        // Set success message and redirect
        $_SESSION['success_message'] = "Course '$courseName' has been successfully created.";
        header("Location: courses.php");
        exit;
        
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        
        // Log the error and show a user-friendly message
        error_log($e->getMessage());
        $_SESSION['error_message'] = "Failed to create course. Please try again or contact support.";
        header("Location: new_course.php");
        exit;
    }
    
} else {
    // If not a POST request, redirect to the form page
    header("Location: new_course.php");
    exit;
}
?>