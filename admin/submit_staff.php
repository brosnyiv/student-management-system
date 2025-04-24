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
        if (isset($_POST['staffType']) && $_POST['staffType'] === 'teaching') {
            // Validate required fields
            if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
                throw new Exception("Required account fields missing");
            }
            
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role_id, access_level, access_start_date, is_active) 
                                  VALUES (:username, :email, :password, :role_id, :access_level, :access_start, 1)");
            
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $roleId = (isset($_POST['portalRole']) && $_POST['portalRole'] === 'instructor') ? 2 : 3; // Assuming 2 is instructor role, 3 is admin
            
            $stmt->execute([
                ':username' => htmlspecialchars($_POST['username']),
                ':email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                ':password' => $hashedPassword,
                ':role_id' => $roleId,
                ':access_level' => htmlspecialchars($_POST['accessLevel'] ?? 'standard'),
                ':access_start' => $_POST['accessStart'] ?? date('Y-m-d')
            ]);
            
            $userId = $pdo->lastInsertId();
        }

        // Validate required staff fields
        if (empty($_POST['fullName']) || empty($_POST['dateOfBirth']) || empty($_POST['gender'])) {
            throw new Exception("Required personal details missing");
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
        
        // Handle profile photo upload
        $profilePhotoPath = null;
        if (!empty($_FILES['profilePhoto']['name'])) {
            $profilePhotoName = time() . '_' . basename($_FILES['profilePhoto']['name']);
            $profilePhotoPath = 'uploads/staff/photos/' . $profilePhotoName;
        }
        
        $stmt->execute([
            ':user_id' => $userId,
            ':staff_type' => htmlspecialchars($_POST['staffType'] ?? 'non-teaching'),
            ':staff_number' => htmlspecialchars($_POST['staffId'] ?? null),
            ':full_name' => htmlspecialchars($_POST['fullName']),
            ':dob' => $_POST['dateOfBirth'], 
            ':gender' => htmlspecialchars($_POST['gender']),
            ':marital_status' => htmlspecialchars($_POST['maritalStatus'] ?? null),
            ':national_id' => htmlspecialchars($_POST['nationalId']),
            ':photo_path' => $profilePhotoPath,
            ':phone' => htmlspecialchars($_POST['phoneNumber']),
            ':personal_email' => filter_var($_POST['personalEmail'] ?? null, FILTER_SANITIZE_EMAIL),
            ':address' => htmlspecialchars($_POST['residentialAddress']),
            ':dept_id' => htmlspecialchars($_POST['department']),
            ':designation' => htmlspecialchars($_POST['designation']),
            ':hire_date' => $_POST['hireDate'],
            ':employment_type' => htmlspecialchars($_POST['employmentType']),
            ':supervisor' => htmlspecialchars($_POST['supervisor'] ?? null)
        ]);
        
        $staffId = $pdo->lastInsertId();

        // 3. Insert into specific staff type table
        if (isset($_POST['staffType']) && $_POST['staffType'] === 'teaching') {
            $stmt = $pdo->prepare("INSERT INTO teaching_staff (
                staff_id, title, assigned_courses, semester_load, office_hours, 
                available_times, assigned_classes, is_faculty_leader, academic_rank
            ) VALUES (
                :staff_id, :title, :courses, :load, :hours, 
                :times, :classes, :is_leader, :rank
            )");
            
            $stmt->execute([
                ':staff_id' => $staffId,
                ':title' => htmlspecialchars($_POST['designation']),
                ':courses' => htmlspecialchars($_POST['assignedCourses']),
                ':load' => intval($_POST['semesterLoad']),
                ':hours' => htmlspecialchars($_POST['officeHours']),
                ':times' => htmlspecialchars($_POST['availableTimes']),
                ':classes' => htmlspecialchars($_POST['assignedClasses'] ?? null),
                ':is_leader' => (strpos($_POST['designation'], 'Head') !== false) ? 1 : 0,
                ':rank' => htmlspecialchars($_POST['designation'])
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO non_teaching_staff (
                staff_id, working_days, working_hours, schedule_notes, work_area
            ) VALUES (
                :staff_id, :days, :hours, :notes, :area
            )");
            
            $stmt->execute([
                ':staff_id' => $staffId,
                ':days' => htmlspecialchars($_POST['workingDays']),
                ':hours' => htmlspecialchars($_POST['workingHours']),
                ':notes' => htmlspecialchars($_POST['scheduleNotes'] ?? null), // Fixed variable name
                ':area' => htmlspecialchars($_POST['workArea'] ?? null)
            ]);
        }

        // 4. Insert emergency contact
        if (empty($_POST['emergencyContactName']) || empty($_POST['emergencyContactPhone'])) {
            throw new Exception("Emergency contact information is required");
        }
        
        $stmt = $pdo->prepare("INSERT INTO emergency_contacts (
            staff_id, contact_name, contact_phone, relationship
        ) VALUES (
            :staff_id, :name, :phone, :relationship
        )");
        
        $stmt->execute([
            ':staff_id' => $staffId,
            ':name' => htmlspecialchars($_POST['emergencyContactName']),
            ':phone' => htmlspecialchars($_POST['emergencyContactPhone']),
            ':relationship' => htmlspecialchars($_POST['emergencyContactRelationship'])
        ]);

        // 5. Insert qualifications
        if (!empty($_POST['qualifications'])) {
            foreach ($_POST['qualifications'] as $index => $qualification) {
                // Skip if essential qualification data is missing
                if (empty($qualification['degree']) || empty($qualification['institution'])) {
                    continue;
                }
                
                $stmt = $pdo->prepare("INSERT INTO qualifications (
                    staff_id, degree, institution, major, graduation_year, certificate_path
                ) VALUES (
                    :staff_id, :degree, :institution, :major, :year, :certificate
                )");
                
                // Handle certificate file
                $certificatePath = null;
                if (!empty($_FILES['qualifications']['name']['certificate'][$index])) {
                    $certificateName = time() . '_' . basename($_FILES['qualifications']['name']['certificate'][$index]);
                    $certificatePath = 'uploads/staff/' . $staffId . '/certificates/' . $certificateName;
                }
                
                $stmt->execute([
                    ':staff_id' => $staffId,
                    ':degree' => htmlspecialchars($qualification['degree']),
                    ':institution' => htmlspecialchars($qualification['institution']),
                    ':major' => htmlspecialchars($qualification['major'] ?? null),
                    ':year' => intval($qualification['gradYear'] ?? 0),
                    ':certificate' => $certificatePath
                ]);
            }
        }

        // 6. Insert bank details
        if (empty($_POST['bankName']) || empty($_POST['accountNumber'])) {
            throw new Exception("Bank details are required");
        }
        
        $stmt = $pdo->prepare("INSERT INTO bank_details (
            staff_id, bank_name, account_number, tax_id, tin_number, salary_scale, payment_frequency
        ) VALUES (
            :staff_id, :bank, :account, :tax_id, :tin, :salary, :frequency
        )");
        
        $stmt->execute([
            ':staff_id' => $staffId,
            ':bank' => htmlspecialchars($_POST['bankName']),
            ':account' => htmlspecialchars($_POST['accountNumber']),
            ':tax_id' => htmlspecialchars($_POST['taxId']),
            ':tin' => htmlspecialchars($_POST['tinNumber']),
            ':salary' => htmlspecialchars($_POST['salaryScale']),
            ':frequency' => htmlspecialchars($_POST['paymentFrequency'])
        ]);

        // 7. Insert documents
        if (!empty($_POST['documents'])) {
            foreach ($_POST['documents'] as $index => $document) {
                // Skip if essential document data is missing
                if (empty($document['type'])) {
                    continue;
                }
                
                $stmt = $pdo->prepare("INSERT INTO staff_documents (
                    staff_id, document_type, document_path, document_number, document_description, expiry_date
                ) VALUES (
                    :staff_id, :type, :path, :number, :description, :expiry
                )");
                
                // Handle document file
                $documentPath = null;
                if (!empty($_FILES['documents']['name']['file'][$index])) {
                    $documentName = time() . '_' . basename($_FILES['documents']['name']['file'][$index]);
                    $documentPath = 'uploads/staff/' . $staffId . '/documents/' . $documentName;
                }
                
                $stmt->execute([
                    ':staff_id' => $staffId,
                    ':type' => htmlspecialchars($document['type']),
                    ':path' => $documentPath,
                    ':number' => htmlspecialchars($document['number'] ?? null),
                    ':description' => htmlspecialchars($document['description'] ?? null),
                    ':expiry' => $document['expiry'] ?? null
                ]);
            }
        }

        // 8. Insert custom fields
        if (!empty($_POST['additionalFields'])) {
            foreach ($_POST['additionalFields'] as $field) {
                if (empty($field['name']) || !isset($field['value'])) {
                    continue;
                }
                
                $stmt = $pdo->prepare("INSERT INTO employment_custom_fields (
                    staff_id, field_name, field_value
                ) VALUES (
                    :staff_id, :name, :value
                )");
                
                $stmt->execute([
                    ':staff_id' => $staffId,
                    ':name' => htmlspecialchars($field['name']),
                    ':value' => htmlspecialchars($field['value'])
                ]);
            }
        }

        // 9. Insert consents
        if (empty($_POST['digitalSignature'])) {
            throw new Exception("Digital signature is required");
        }
        
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
            ':signature' => htmlspecialchars($_POST['digitalSignature']),
            ':date' => $_POST['signatureDate'] ?? date('Y-m-d')
        ]);

        // Handle file uploads
        $uploadDir = 'uploads/staff/' . $staffId . '/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Profile photo
        if (!empty($_FILES['profilePhoto']['name'])) {
            $profilePhotoDir = 'uploads/staff/photos/';
            if (!file_exists($profilePhotoDir)) {
                mkdir($profilePhotoDir, 0777, true);
            }
            
            // Move the uploaded file
            if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $profilePhotoPath)) {
                // Success
            } else {
                throw new Exception("Failed to upload profile photo");
            }
        }

        // Certificates
        if (!empty($_FILES['qualifications']['name']['certificate'])) {
            $certDir = $uploadDir . 'certificates/';
            if (!file_exists($certDir)) {
                mkdir($certDir, 0777, true);
            }
            
            foreach ($_FILES['qualifications']['tmp_name']['certificate'] as $index => $tmpName) {
                if (!empty($tmpName)) {
                    $certName = time() . '_' . basename($_FILES['qualifications']['name']['certificate'][$index]);
                    $certPath = $certDir . $certName;
                    move_uploaded_file($tmpName, $certPath);
                }
            }
        }

        // Documents
        if (!empty($_FILES['documents']['name']['file'])) {
            $docDir = $uploadDir . 'documents/';
            if (!file_exists($docDir)) {
                mkdir($docDir, 0777, true);
            }
            
            foreach ($_FILES['documents']['tmp_name']['file'] as $index => $tmpName) {
                if (!empty($tmpName)) {
                    $docName = time() . '_' . basename($_FILES['documents']['name']['file'][$index]);
                    $docPath = $docDir . $docName;
                    move_uploaded_file($tmpName, $docPath);
                }
            }
        }

        // Commit transaction
        $pdo->commit();

        // Success response
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Staff registration completed successfully',
            'staff_id' => $staffId
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        
        // Error response
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error processing registration: ' . $e->getMessage()
        ]);
    }
} else {
    // Not a POST request
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}
?>