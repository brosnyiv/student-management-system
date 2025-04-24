<?php
// Database connection
include('dbconnect.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start transaction
    $pdo->beginTransaction();

    try {
        // 1. Insert into users table (for teaching staff only)
        $userId = null;
        if ($_POST['staffType'] === 'teaching') {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role_id, access_level, access_start_date, is_active) 
                                  VALUES (:username, :email, :password, :role_id, :access_level, :access_start, 1)");
            
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $roleId = ($_POST['portalRole'] === 'instructor') ? 2 : 3; // Assuming 2 is instructor role, 3 is admin
            
            $stmt->execute([
                ':username' => $_POST['username'],
                ':email' => $_POST['email'],
                ':password' => $hashedPassword,
                ':role_id' => $roleId,
                ':access_level' => $_POST['accessLevel'],
                ':access_start' => $_POST['accessStart'] ?? date('Y-m-d')
            ]);
            
            $userId = $pdo->lastInsertId();
        }

        // 2. Insert into staff table
        $stmt = $pdo->prepare("INSERT INTO staff (
            user_id, staff_type, staff_number, full_name, date_of_birth, gender, marital_status, 
            national_id, profile_photo_path, phone_number, personal_email, residential_address, 
            department_id, designation, hire_date, employment_type, supervisor
        ) VALUES (
            :user_id, :staff_type, :staff_number, :full_name, :dob, :gender, :marital_status, 
            :national_id, :photo_path, :phone, :personal_email, :address, 
            :dept_id, :designation, :hire_date, :employment_type, :supervisor
        )");
        
        $stmt->execute([
            ':user_id' => $userId,
            ':staff_type' => $_POST['staffType'],
            ':staff_number' => $_POST['staffId'] ?? null,
            ':full_name' => $_POST['fullName'],
            ':dob' => $_POST['dateOfBirth'],
            ':gender' => $_POST['gender'],
            ':marital_status' => $_POST['maritalStatus'] ?? null,
            ':national_id' => $_POST['nationalId'],
            ':photo_path' => $_FILES['profilePhoto']['name'] ?? null,
            ':phone' => $_POST['phoneNumber'],
            ':personal_email' => $_POST['personalEmail'] ?? null,
            ':address' => $_POST['residentialAddress'],
            ':dept_id' => $_POST['department'],
            ':designation' => $_POST['designation'],
            ':hire_date' => $_POST['hireDate'],
            ':employment_type' => $_POST['employmentType'],
            ':supervisor' => $_POST['supervisor'] ?? null
        ]);
        
        $staffId = $pdo->lastInsertId();

        // 3. Insert into specific staff type table
        if ($_POST['staffType'] === 'teaching') {
            $stmt = $pdo->prepare("INSERT INTO teaching_staff (
                staff_id, title, assigned_courses, semester_load, office_hours, 
                available_times, assigned_classes, is_faculty_leader, academic_rank
            ) VALUES (
                :staff_id, :title, :courses, :load, :hours, 
                :times, :classes, :is_leader, :rank
            )");
            
            $stmt->execute([
                ':staff_id' => $staffId,
                ':title' => $_POST['designation'],
                ':courses' => $_POST['assignedCourses'],
                ':load' => $_POST['semesterLoad'],
                ':hours' => $_POST['officeHours'],
                ':times' => $_POST['availableTimes'],
                ':classes' => $_POST['assignedClasses'] ?? null,
                ':is_leader' => (strpos($_POST['designation'], 'Head') !== false) ? 1 : 0,
                ':rank' => $_POST['designation']
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO non_teaching_staff (
                staff_id, working_days, working_hours, schedule_notes, work_area
            ) VALUES (
                :staff_id, :days, :hours, :notes, :area
            )");
            
            $stmt->execute([
                ':staff_id' => $staffId,
                ':days' => $_POST['workingDays'],
                ':hours' => $_POST['workingHours'],
                ':notes' => $_POST['schedulNotes'] ?? null,
                ':area' => $_POST['workArea'] ?? null
            ]);
        }

        // 4. Insert emergency contact
        $stmt = $pdo->prepare("INSERT INTO emergency_contacts (
            staff_id, contact_name, contact_phone, relationship
        ) VALUES (
            :staff_id, :name, :phone, :relationship
        )");
        
        $stmt->execute([
            ':staff_id' => $staffId,
            ':name' => $_POST['emergencyContactName'],
            ':phone' => $_POST['emergencyContactPhone'],
            ':relationship' => $_POST['emergencyContactRelationship']
        ]);

        // 5. Insert qualifications
        if (!empty($_POST['qualifications'])) {
            foreach ($_POST['qualifications'] as $qualification) {
                $stmt = $pdo->prepare("INSERT INTO qualifications (
                    staff_id, degree, institution, major, graduation_year, certificate_path
                ) VALUES (
                    :staff_id, :degree, :institution, :major, :year, :certificate
                )");
                
                $stmt->execute([
                    ':staff_id' => $staffId,
                    ':degree' => $qualification['degree'],
                    ':institution' => $qualification['institution'],
                    ':major' => $qualification['major'],
                    ':year' => $qualification['gradYear'],
                    ':certificate' => $qualification['certificate']['name'] ?? null
                ]);
            }
        }

        // 6. Insert bank details
        $stmt = $pdo->prepare("INSERT INTO bank_details (
            staff_id, bank_name, account_number, tax_id, tin_number, salary_scale, payment_frequency
        ) VALUES (
            :staff_id, :bank, :account, :tax_id, :tin, :salary, :frequency
        )");
        
        $stmt->execute([
            ':staff_id' => $staffId,
            ':bank' => $_POST['bankName'],
            ':account' => $_POST['accountNumber'],
            ':tax_id' => $_POST['taxId'],
            ':tin' => $_POST['tinNumber'],
            ':salary' => $_POST['salaryScale'],
            ':frequency' => $_POST['paymentFrequency']
        ]);

        // 7. Insert documents
        if (!empty($_POST['documents'])) {
            foreach ($_POST['documents'] as $document) {
                $stmt = $pdo->prepare("INSERT INTO staff_documents (
                    staff_id, document_type, document_path, document_number, document_description, expiry_date
                ) VALUES (
                    :staff_id, :type, :path, :number, :description, :expiry
                )");
                
                $stmt->execute([
                    ':staff_id' => $staffId,
                    ':type' => $document['type'],
                    ':path' => $document['file']['name'] ?? null,
                    ':number' => $document['number'] ?? null,
                    ':description' => $document['description'] ?? null,
                    ':expiry' => $document['expiry'] ?? null
                ]);
            }
        }

        // 8. Insert custom fields
        if (!empty($_POST['additionalFields'])) {
            foreach ($_POST['additionalFields'] as $field) {
                $stmt = $pdo->prepare("INSERT INTO employment_custom_fields (
                    staff_id, field_name, field_value
                ) VALUES (
                    :staff_id, :name, :value
                )");
                
                $stmt->execute([
                    ':staff_id' => $staffId,
                    ':name' => $field['name'],
                    ':value' => $field['value']
                ]);
            }
        }

        // 9. Insert consents
        $stmt = $pdo->prepare("INSERT INTO staff_consents (
            staff_id, terms_consent, data_consent, update_consent, digital_signature, signature_date
        ) VALUES (
            :staff_id, :terms, :data, :update, :signature, :date
        )");
        
        $stmt->execute([
            ':staff_id' => $staffId,
            ':terms' => isset($_POST['termsConsent']) ? 1 : 0,
            ':data' => isset($_POST['dataConsent']) ? 1 : 0,
            ':update' => isset($_POST['updateConsent']) ? 1 : 0,
            ':signature' => $_POST['digitalSignature'],
            ':date' => $_POST['signatureDate']
        ]);

        // Handle file uploads
        $uploadDir = 'uploads/staff/' . $staffId . '/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Profile photo
        if (!empty($_FILES['profilePhoto']['name'])) {
            $profilePhotoPath = $uploadDir . basename($_FILES['profilePhoto']['name']);
            move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $profilePhotoPath);
        }

        // Certificates
        if (!empty($_FILES['qualifications'])) {
            foreach ($_FILES['qualifications']['tmp_name']['certificate'] as $index => $tmpName) {
                if (!empty($tmpName)) {
                    $certPath = $uploadDir . 'certificates/' . basename($_FILES['qualifications']['name']['certificate'][$index]);
                    if (!file_exists(dirname($certPath))) {
                        mkdir(dirname($certPath), 0777, true);
                    }
                    move_uploaded_file($tmpName, $certPath);
                }
            }
        }

        // Documents
        if (!empty($_FILES['documents'])) {
            foreach ($_FILES['documents']['tmp_name']['file'] as $index => $tmpName) {
                if (!empty($tmpName)) {
                    $docPath = $uploadDir . 'documents/' . basename($_FILES['documents']['name']['file'][$index]);
                    if (!file_exists(dirname($docPath))) {
                        mkdir(dirname($docPath), 0777, true);
                    }
                    move_uploaded_file($tmpName, $docPath);
                }
            }
        }

        // Commit transaction
        $pdo->commit();

        // Success response
        echo json_encode([
            'success' => true,
            'message' => 'Staff registration completed successfully',
            'staff_id' => $staffId
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        
        // Error response
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error processing registration: ' . $e->getMessage()
        ]);
    }
} else {
    // Not a POST request
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}
?>