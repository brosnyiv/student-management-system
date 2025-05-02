

<?php
header('Content-Type: application/json');

// Database connection
include('dbconnect.php');

// Process form data
try {
    // Basic validation
    $requiredFields = [
        'first_name', 'surname', 'date_of_birth', 'gender', 'email', 
        'phone', 'address', 'city', 'state_province', 'zip_postal_code',
        'country', 'nationality', 'emergency_contact_name', 'emergency_relationship',
        'emergency_phone', 'program_level', 'department', 'major', 'year_level',
        'expected_start_date', 'previous_institution', 'previous_gpa',
        'terms_agreed', 'policy_agreed'
    ];
    
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    // File upload handling
    $profilePhotoPath = '';
    if (isset($_FILES['profile_photo_path'])) {
        $uploadDir = 'uploads/profile_photos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = uniqid() . '_' . basename($_FILES['profile_photo_path']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['profile_photo_path']['tmp_name'], $targetPath)) {
            $profilePhotoPath = $targetPath;
        } else {
            throw new Exception('Failed to upload profile photo');
        }
    }
    
    // Generate student ID
    $department = $_POST['department'];
    $enrollmentYear = date('Y');
    $studentId = generateStudentId($department, $enrollmentYear);
    
    // Begin transaction
    $db->begin_transaction();
    
    // Insert into students table
    $stmt = $db->prepare("
        INSERT INTO students (
            student_id, first_name, middle_name, surname, date_of_birth, gender,
            profile_photo_path, nationality, created_by, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')
    ");
    $stmt->bind_param(
        'ssssssssi',
        $studentId,
        $_POST['first_name'],
        $_POST['middle_name'],
        $_POST['surname'],
        $_POST['date_of_birth'],
        $_POST['gender'],
        $profilePhotoPath,
        $_POST['nationality'],
        $_POST['created_by']
    );
    $stmt->execute();
    $stmt->close();
    
    // Insert into contact_details
    $stmt = $db->prepare("
        INSERT INTO contact_details (
            student_id, email, phone, alt_phone, address, city,
            state_province, zip_postal_code, country, is_primary
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE)
    ");
    $stmt->bind_param(
        'sssssssss',
        $studentId,
        $_POST['email'],
        $_POST['phone'],
        $_POST['alt_phone'],
        $_POST['address'],
        $_POST['city'],
        $_POST['state_province'],
        $_POST['zip_postal_code'],
        $_POST['country']
    );
    $stmt->execute();
    $stmt->close();
    
    // Insert into student_emergency_contacts
    $stmt = $db->prepare("
        INSERT INTO student_emergency_contacts (
            student_id, contact_name, relationship, phone_number, is_primary
        ) VALUES (?, ?, ?, ?, TRUE)
    ");
    $stmt->bind_param(
        'ssss',
        $studentId,
        $_POST['emergency_contact_name'],
        $_POST['emergency_relationship'],
        $_POST['emergency_phone']
    );
    $stmt->execute();
    $stmt->close();
    
    // Insert into academic_info (assuming you have course_id)
    $courseId = 1; // You should determine this based on the selected program/department
    $stmt = $db->prepare("
        INSERT INTO academic_info (
            student_id, course_id, program_level, year_level, expected_start_date,
            expected_end_date, previous_institution, previous_gpa, enrollment_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        'sisssssds',
        $studentId,
        $courseId,
        $_POST['program_level'],
        $_POST['year_level'],
        $_POST['expected_start_date'],
        $_POST['expected_end_date'],
        $_POST['previous_institution'],
        $_POST['previous_gpa'],
        $_POST['enrollment_status']
    );
    $stmt->execute();
    $stmt->close();
    
    // Insert into consent_records
    $stmt = $db->prepare("
        INSERT INTO consent_records (
            student_id, terms_agreed, terms_agreed_version, terms_agreed_date,
            policy_agreed, policy_agreed_version, policy_agreed_date,
            marketing_opt_in, digital_signature, signature_date
        ) VALUES (?, TRUE, ?, NOW(), TRUE, ?, NOW(), ?, 'Digital Signature', NOW())
    ");
    $marketingOptIn = isset($_POST['marketing_opt_in']) ? 1 : 0;
    $stmt->bind_param(
        'sssi',
        $studentId,
        $_POST['terms_agreed_version'],
        $_POST['policy_agreed_version'],
        $marketingOptIn
    );
    $stmt->execute();
    $stmt->close();
    
    // Insert into registration_status
    $stmt = $db->prepare("
        INSERT INTO registration_status (
            student_id, status, submission_date
        ) VALUES (?, 'submitted', NOW())
    ");
    $stmt->bind_param('s', $studentId);
    $stmt->execute();
    $stmt->close();
    
    // Handle document uploads (if any)
    // ... (similar to profile photo handling)
    
    // Commit transaction
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'student_id' => $studentId,
        'message' => 'Registration successful'
    ]);
    
} catch (Exception $e) {
    $db->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function generateStudentId($department, $year) {
    $deptCode = strtoupper(substr($department, 0, 3));
    $randomCode = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    return "MI-{$deptCode}-{$year}-{$randomCode}";
}
?>