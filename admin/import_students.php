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


// Check if file was uploaded without errors
if(isset($_FILES["csvFile"]) && $_FILES["csvFile"]["error"] == 0) {
    $allowed = array("csv" => "text/csv", "csv" => "application/vnd.ms-excel");
    $filename = $_FILES["csvFile"]["name"];
    $filetype = $_FILES["csvFile"]["type"];
    $filesize = $_FILES["csvFile"]["size"];

    // Validate file extension
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if(!array_key_exists($ext, $allowed)) {
        $_SESSION['import_error'] = "Error: Please select a valid CSV file.";
        header("Location: student.php");
        exit;
    }

    // Validate file size - 5MB maximum
    $maxsize = 5 * 1024 * 1024;
    if($filesize > $maxsize) {
        $_SESSION['import_error'] = "Error: File size is larger than the allowed limit (5MB).";
        header("Location: student.php");
        exit;
    }

    // Validate mime type
    if(in_array($filetype, $allowed)) {
        // Check if the file exists before uploading it
        if(file_exists("uploads/" . $filename)) {
            $_SESSION['import_error'] = "Error: File " . $filename . " already exists.";
            header("Location: student.php");
            exit;
        } else {
            // Move the temp file to uploads directory
            if(move_uploaded_file($_FILES["csvFile"]["tmp_name"], "uploads/" . $filename)) {
                // Parse CSV file
                $file = fopen("uploads/" . $filename, "r");
                $header = fgetcsv($file); // Get header row
                
                $imported_count = 0;
                $error_count = 0;
                
                while(($data = fgetcsv($file)) !== FALSE) {
                    // Map CSV columns to database fields
                    $first_name = mysqli_real_escape_string($conn, $data[0]);
                    $last_name = mysqli_real_escape_string($conn, $data[1]);
                    $student_id = mysqli_real_escape_string($conn, $data[2]);
                    $email = mysqli_real_escape_string($conn, $data[3]);
                    $gender = mysqli_real_escape_string($conn, $data[4]);
                    $course = mysqli_real_escape_string($conn, $data[5]);
                    $status = mysqli_real_escape_string($conn, $data[6]);
                    $balance = mysqli_real_escape_string($conn, $data[7]);
                    
                    // Check if student ID already exists
                    $check_query = "SELECT * FROM students WHERE student_id = '$student_id'";
                    $check_result = mysqli_query($conn, $check_query);
                    
                    if(mysqli_num_rows($check_result) > 0) {
                        // Update existing student
                        $sql = "UPDATE students SET 
                                first_name = '$first_name',
                                last_name = '$last_name',
                                email = '$email',
                                gender = '$gender',
                                course = '$course',
                                status = '$status',
                                balance = '$balance'
                                WHERE student_id = '$student_id'";
                    } else {
                        // Insert new student
                        $sql = "INSERT INTO students (first_name, last_name, student_id, email, gender, course, status, balance)
                                VALUES ('$first_name', '$last_name', '$student_id', '$email', '$gender', '$course', '$status', '$balance')";
                    }
                    
                    if(mysqli_query($conn, $sql)) {
                        $imported_count++;
                    } else {
                        $error_count++;
                    }
                }
                
                fclose($file);
                
                $_SESSION['import_success'] = "Successfully imported $imported_count student records. Errors: $error_count";
                header("Location: student.php");
                exit;
            } else {
                $_SESSION['import_error'] = "Error: There was a problem uploading your file.";
                header("Location: student.php");
                exit;
            }
        }
    } else {
        $_SESSION['import_error'] = "Error: There was a problem with the uploaded file.";
        header("Location: student.php");
        exit;
    }
} else {
    $_SESSION['import_error'] = "Error: No file uploaded.";
    header("Location: student.php");
    exit;
}
?>