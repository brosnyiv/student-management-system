<?php
session_start();
include 'dbconnect.php';

// Check if user is not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Initialize variables
$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Begin transaction
        mysqli_begin_transaction($conn);
        
        // 1. Get user_id from session (assuming the user is already logged in)
        $user_id = $_SESSION['user_id'];
        
        // 2. Insert into staff table
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status']);
        $national_id = mysqli_real_escape_string($conn, $_POST['national_id']);
        $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $personal_email = mysqli_real_escape_string($conn, $_POST['personal_email']);
        $residential_address = mysqli_real_escape_string($conn, $_POST['residential_address']);
        $department_id = intval($_POST['department']);
        $designation = mysqli_real_escape_string($conn, $_POST['designation']);
        $hire_date = mysqli_real_escape_string($conn, $_POST['hire_date']);
        $employment_type = mysqli_real_escape_string($conn, $_POST['employment_type']);
        $supervisor = isset($_POST['supervisor']) ? mysqli_real_escape_string($conn, $_POST['supervisor']) : null;
        
        // Handle profile photo upload
        $profile_photo_path = '';
        if (isset($_FILES['profile_photo_path']) && $_FILES['profile_photo_path']['error'] == UPLOAD_ERR_OK) {
            $photo_name = basename($_FILES['profile_photo_path']['name']);
            $photo_tmp = $_FILES['profile_photo_path']['tmp_name'];
            $photo_ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));
            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
            
            if (in_array($photo_ext, $allowed_extensions)) {
                $new_photo_name = uniqid('profile_', true) . '.' . $photo_ext;
                $upload_dir = 'uploads/staff/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $profile_photo_path = $upload_dir . $new_photo_name;
                
                if (!move_uploaded_file($photo_tmp, $profile_photo_path)) {
                    throw new Exception("Failed to upload profile photo");
                }
            } else {
                throw new Exception("Invalid profile photo format. Only JPG, JPEG, PNG, GIF are allowed.");
            }
        }
        
        // Generate staff number
        $nameletter = strtoupper(substr($full_name, 0, 1));
        $random = rand(0, 1000);
        $staff_number = 'ST' . $random . $nameletter;
        
        // Insert into staff table
        $sql = "INSERT INTO staff (
            user_id, staff_type, staff_number, full_name, date_of_birth, gender, 
            marital_status, national_id, profile_photo_path, phone_number, 
            personal_email, residential_address, department_id, designation, 
            hire_date, employment_type, supervisor
        ) VALUES (
            '$user_id', 'non-teaching', '$staff_number', '$full_name', '$date_of_birth', '$gender', 
            '$marital_status', '$national_id', '$profile_photo_path', '$phone_number', 
            '$personal_email', '$residential_address', $department_id, '$designation', 
            '$hire_date', '$employment_type', " . ($supervisor ? "'$supervisor'" : "NULL") . "
        )";
        
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error inserting staff record: " . mysqli_error($conn));
        }
        
        $staff_id = mysqli_insert_id($conn);
        
        // 3. Insert into non_teaching_staff table
        $working_days = mysqli_real_escape_string($conn, $_POST['working_days']);
        $working_hours = mysqli_real_escape_string($conn, $_POST['working_hours']);
        $schedule_notes = isset($_POST['schedule_notes']) ? mysqli_real_escape_string($conn, $_POST['schedule_notes']) : null;
        $work_area = isset($_POST['work_area']) ? mysqli_real_escape_string($conn, $_POST['work_area']) : null;
        
        $sql = "INSERT INTO non_teaching_staff (
            staff_id, working_days, working_hours, schedule_notes, work_area
        ) VALUES (
            $staff_id, '$working_days', '$working_hours', " . 
            ($schedule_notes ? "'$schedule_notes'" : "NULL") . ", " . 
            ($work_area ? "'$work_area'" : "NULL") . "
        )";
        
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error inserting non-teaching staff record: " . mysqli_error($conn));
        }
        
        // 4. Insert academic qualifications
        if (isset($_POST['degree']) && is_array($_POST['degree'])) {
            for ($i = 0; $i < count($_POST['degree']); $i++) {
                $degree = mysqli_real_escape_string($conn, $_POST['degree'][$i]);
                $institution = mysqli_real_escape_string($conn, $_POST['institution'][$i]);
                $major = mysqli_real_escape_string($conn, $_POST['major'][$i]);
                $graduation_year = intval($_POST['graduation_year'][$i]);
                
                // Handle certificate file upload
                $certificate_path = '';
                if (isset($_FILES['certification_path']['name'][$i]) && $_FILES['certification_path']['error'][$i] == UPLOAD_ERR_OK) {
                    $cert_name = basename($_FILES['certification_path']['name'][$i]);
                    $cert_tmp = $_FILES['certification_path']['tmp_name'][$i];
                    $cert_ext = strtolower(pathinfo($cert_name, PATHINFO_EXTENSION));
                    $allowed_extensions = array('pdf', 'jpg', 'jpeg', 'png');
                    
                    if (in_array($cert_ext, $allowed_extensions)) {
                        $new_cert_name = uniqid('cert_', true) . '.' . $cert_ext;
                        $upload_dir = 'uploads/qualifications/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        $certificate_path = $upload_dir . $new_cert_name;
                        
                        if (!move_uploaded_file($cert_tmp, $certificate_path)) {
                            throw new Exception("Failed to upload certificate file");
                        }
                    } else {
                        throw new Exception("Invalid certificate format. Only PDF, JPG, JPEG, PNG are allowed.");
                    }
                }
                
                $sql = "INSERT INTO staff_qualifications (
                    staff_id, degree, institution, major, graduation_year, certificate_path
                ) VALUES (
                    $staff_id, '$degree', '$institution', '$major', $graduation_year, '$certificate_path'
                )";
                
                if (!mysqli_query($conn, $sql)) {
                    throw new Exception("Error inserting qualification record: " . mysqli_error($conn));
                }
            }
        }
        
        // 5. Insert bank details
        $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
        $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
        $tax_id = mysqli_real_escape_string($conn, $_POST['tax_id']);
        $tin_number = mysqli_real_escape_string($conn, $_POST['tin_number']);
        $salary_scale = mysqli_real_escape_string($conn, $_POST['salary_scale']);
        $payment_frequency = mysqli_real_escape_string($conn, $_POST['payment_frequency']);
        
        $sql = "INSERT INTO bank_details (
            staff_id, bank_name, account_number, tax_id, tin_number, salary_scale, payment_frequency
        ) VALUES (
            $staff_id, '$bank_name', '$account_number', '$tax_id', '$tin_number', '$salary_scale', '$payment_frequency'
        )";
        
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error inserting bank details: " . mysqli_error($conn));
        }
        
        // 6. Insert documents
        if (isset($_POST['document_type']) && is_array($_POST['document_type'])) {
            for ($i = 0; $i < count($_POST['document_type']); $i++) {
                $document_type = mysqli_real_escape_string($conn, $_POST['document_type'][$i]);
                $document_number = mysqli_real_escape_string($conn, $_POST['document_number'][$i]);
                $expiry_date = !empty($_POST['expiry_date'][$i]) ? mysqli_real_escape_string($conn, $_POST['expiry_date'][$i]) : null;
                $document_description = isset($_POST['document_description'][$i]) ? mysqli_real_escape_string($conn, $_POST['document_description'][$i]) : null;
                
                // Handle document file upload
                $document_path = '';
                if (isset($_FILES['document_path']['name'][$i]) && $_FILES['document_path']['error'][$i] == UPLOAD_ERR_OK) {
                    $doc_name = basename($_FILES['document_path']['name'][$i]);
                    $doc_tmp = $_FILES(['document_path']['tmp_name'][$i]);
                    $doc_ext = strtolower(pathinfo($doc_name, PATHINFO_EXTENSION));
                    $allowed_extensions = array('pdf', 'jpg', 'jpeg', 'png');
                    
                    if (in_array($doc_ext, $allowed_extensions)) {
                        $new_doc_name = uniqid('doc_', true) . '.' . $doc_ext;
                        $upload_dir = 'uploads/documents/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        $document_path = $upload_dir . $new_doc_name;
                        
                        if (!move_uploaded_file($doc_tmp, $document_path)) {
                            throw new Exception("Failed to upload document file");
                        }
                    } else {
                        throw new Exception("Invalid document format. Only PDF, JPG, JPEG, PNG are allowed.");
                    }
                }
                
                $sql = "INSERT INTO staff_documents (
                    staff_id, document_type, document_path, document_number, expiry_date, document_description
                ) VALUES (
                    $staff_id, '$document_type', '$document_path', '$document_number', " . 
                    ($expiry_date ? "'$expiry_date'" : "NULL") . ", " . 
                    ($document_description ? "'$document_description'" : "NULL") . "
                )";
                
                if (!mysqli_query($conn, $sql)) {
                    throw new Exception("Error inserting document record: " . mysqli_error($conn));
                }
            }
        }
        
        // 7. Insert consents
        $terms_consent = isset($_POST['terms_consent']) ? 1 : 0;
        $data_consent = isset($_POST['data_consent']) ? 1 : 0;
        $update_consent = isset($_POST['update_consent']) ? 1 : 0;
        $digital_signature = mysqli_real_escape_string($conn, $_POST['digital_signature']);
        $digital_date = mysqli_real_escape_string($conn, $_POST['digital_date']);
        
        $sql = "INSERT INTO staff_consents (
            staff_id, terms_consent, data_consent, update_consent, digital_signature, signature_date
        ) VALUES (
            $staff_id, $terms_consent, $data_consent, $update_consent, '$digital_signature', '$digital_date'
        )";
        
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error inserting consent record: " . mysqli_error($conn));
        }
        
        // Commit transaction
        mysqli_commit($conn);
        $success = "Non-teaching staff registration completed successfully!";
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch departments for dropdown
$departments = array();
$sql = "SELECT department_id, department_name FROM departments";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $departments[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Non-Teaching Staff Registration Portal</title>
    <link rel="stylesheet" href="staffreg.css">
    <style>
        .error { color: red; font-weight: bold; }
        .success { color: green; font-weight: bold; }
        .form-group { margin-bottom: 15px; }
        .required:after { content: " *"; color: red; }
        .image-upload { border: 2px dashed #ccc; padding: 20px; text-align: center; cursor: pointer; }
        .qualification-row, .document-row { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; position: relative; }
        .remove-row { position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; border-radius: 50%; cursor: pointer; }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo-address">
                <div class="logo">School Staff Portal</div>
                <div class="address">123 Education Avenue, Learning City, ED 12345</div>
            </div>
            <div class="school-logo">
                <img src="logo.png" alt="School Logo"/>
            </div>
        </div>
    </header>
    
    <div class="container" id="registrationFormContainer">
        <div class="form-container">
            <h1 id="formTitle">Non-Teaching Staff Registration Form</h1>
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="form-steps">
                <div class="step active">1</div>
                <div class="step">2</div>
                <div class="step">3</div>
                <div class="step">4</div>
            </div>
            
            <form id="staffRegistrationForm" action="" method="POST" enctype="multipart/form-data">
                <!-- Staff Type Hidden Field -->
                <input type="hidden" id="staff_type" name="staff_type" value="non-teaching">
                
                <!-- Personal Details Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">🧑‍🏫</div>
                        <div class="section-title">1. Personal Details</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name" class="required">Full Name (as per ID)</label>
                            <input type="text" id="full_name" name="full_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="dob" class="required">Date of Birth</label>
                            <input type="date" id="dob" name="date_of_birth" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="gender" class="required">Gender</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                                <option value="prefer-not-to-say">Prefer not to say</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="marital_status">Marital Status</label>
                            <select id="marital_status" name="marital_status">
                                <option value="">Select Status</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="divorced">Divorced</option>
                                <option value="widowed">Widowed</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="national_id" class="required">National ID/Passport Number</label>
                            <input type="text" id="national_id" name="national_id" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="photo_path" class="required">Profile Photo</label>
                            <div class="image-upload"> <!-- id="profilePhotoUpload" -->
                                <div class="upload-icon">📷</div>
                                <div>Click to upload photo</div>
                                <div class="help-text">JPEG or PNG, max 2MB</div>
                                <input type="file" id="photo_path" name="profile_photo_path" style="" required>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">📞</div>
                        <div class="section-title">2. Contact Information</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone" class="required">Phone Number</label>
                            <input type="text" id="phone" name="phone_number" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="personal_email">Personal Email Address</label>
                            <input type="email" id="personal_email" name="personal_email">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="address" class="required">Residential Address</label>
                            <textarea id="address" name="residential_address" rows="3" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Academic Qualifications Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">🎓</div>
                        <div class="section-title">3. Academic Qualifications</div>
                    </div>
                    
                    <div id="qualifications-container">
                        <div class="qualification-row">
                            <button type="button" class="remove-row">×</button>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="degree1" class="required">Degree/Qualification</label>
                                    <input type="text" id="degree1" name="degree[]" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="institution1" class="required">Institution</label>
                                    <input type="text" id="institution1" name="institution[]" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="major1" class="required">Major/Area of Specialization</label>
                                    <input type="text" id="major1" name="major[]" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="year1" class="required">Year of Graduation</label>
                                    <input type="number" id="year1" name="graduation_year[]" min="1950" max="2030" required>
                                </div>
                            </div>
                            
                          
                            <div class="form-row">
                                <div class="form-group-full">
                                    <label for="certificate1" class="required">Upload Certificate</label>
                                    <div class="image-upload" id="certificateUploadWrapper1">
                                        <div class="upload-icon">📄</div>
                                        <div>Click to upload certificate</div>
                                        <div class="help-text">PDF, JPEG or PNG, max 5MB</div>
                                        <input type="file" id="certificate1" name="certification_path" accept=".pdf,.jpg,.jpeg,.png" style="" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="add-row-btn" id="addQualification">
                        <i>+</i> Add Another Qualification
                    </button>
                </div>

                <!-- Employment Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">💼</div>
                        <div class="section-title">4. Employment Information</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="staff_number">Staff ID/Employee Number</label>
                            <input type="text" id="staff_number" name="staff_number">
                            <div class="help-text">If already assigned</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="department" class="required">Department</label>
                            <select id="department" name="department" required>
                                <option value="">Select a department</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo $dept['department_id']; ?>"><?php echo $dept['department_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="designation" class="required">Designation</label>
                            <select id="designation" name="designation" required>
                                <option value="">Select Designation</option>
                                <option value="admin-assistant">Administrative Assistant</option>
                                <option value="accountant">Accountant</option>
                                <option value="librarian">Librarian</option>
                                <option value="it-support">IT Support</option>
                                <option value="security-officer">Security Officer</option>
                                <option value="janitor">Janitor</option>
                                <option value="coordinator">Coordinator</option>
                                <option value="manager">Manager</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="hire_date" class="required">Date of Hire</label>
                            <input type="date" id="hire_date" name="hire_date" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="employment_type" class="required">Employment Type</label>
                            <select id="employment_type" name="employment_type" required>
                                <option value="">Select Type</option>
                                <option value="full-time">Full-time</option>
                                <option value="part-time">Part-time</option>
                                <option value="visiting">Visiting</option>
                                <option value="contract">Contract</option>
                                <option value="temporary">Temporary</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="supervisor">Supervisor/Department Head</label>
                            <input type="text" id="supervisor" name="supervisor">
                        </div>
                    </div>
                </div>
                
                <!-- Working Hours Section (Only for Non-Teaching Staff) -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">⏰</div>
                        <div class="section-title">5. Working Hours & Schedule</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="working_days" class="required">Working Days</label>
                            <select id="working_days" name="working_days" required>
                                <option value="">Select Working Days</option>
                                <option value="monday-friday">Monday to Friday</option>
                                <option value="monday-saturday">Monday to Saturday</option>
                                <option value="rotational">Rotational Shift</option>
                                <option value="custom">Custom Schedule</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="working_hours" class="required">Working Hours</label>
                            <input type="text" id="working_hours" name="working_hours" placeholder="e.g., 8:00 AM - 5:00 PM" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="schedule_notes">Schedule Notes</label>
                            <textarea id="schedule_notes" name="schedule_notes" rows="3" placeholder="Any additional information about your working schedule"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="work_area">Work Area/Office</label>
                        <input type="text" id="work_area" name="work_area" placeholder="e.g., Admin Block, Finance Office">
                    </div>
                </div>
                
                <!-- Payroll & Bank Details Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">💰</div>
                        <div class="section-title">6. Payroll & Bank Details</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bank" class="required">Bank Name</label>
                            <input type="text" id="bank" name="bank_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="account" class="required">Account Number</label>
                            <input type="text" id="account" name="account_number" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tax_id" class="required">National Tax ID/Social Security</label>
                            <input type="text" id="tax_id" name="tax_id" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tin" class="required">TIN Number</label>
                            <input type="text" id="tin" name="tin_number" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="salary" class="required">Salary Scale/Grade</label>
                            <input type="text" id="salary" name="salary_scale" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="frequency" class="required">Payment Frequency</label>
                            <select id="frequency" name="payment_frequency" required>
                                <option value="">Select Frequency</option>
                                <option value="monthly">Monthly</option>
                                <option value="bi-weekly">Bi-weekly</option>
                                <option value="weekly">Weekly</option>
                                <option value="quarterly">Quarterly</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                  <!-- Document Uploads Section -->
                  <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">📑</div>
                        <div class="section-title">7. Document Uploads</div>
                    </div>
                    
                    <div id="documents-container">
                        <div class="document-row">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="path" class="required">ID/Passport Scan</label>
                                    <div class="image-upload" id="idPassportScanUpload">
                                        <div class="upload-icon">📄</div>
                                        <div>Click to upload document</div>
                                        <div class="help-text">PDF, JPEG or PNG, max 5MB</div>
                                        <input type="file" id="path" name="document_path" accept=".pdf,.jpg,.jpeg,.png" style="" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="type" class="required">Document Type</label>
                                    <select id="type" name="document_type" required>
                                        <option value="id">National ID</option>
                                        <option value="passport">Passport</option>
                                        <option value="residencePermit">Residence Permit</option>
                                        <option value="workVisa">Work Visa</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="number" class="required">Document Number</label>
                                    <input type="text" id="number" name="document_number" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="expiry">Expiry Date (if applicable)</label>
                                    <input type="date" id="expiry" name="expiry_date">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group-full">
                                    <label for="description">Document Description</label>
                                    <input type="text" id="description" name="document_description">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="add-row-btn" id="addDocument">
                        <i>+</i> Add Another Document
                    </button>
                </div>
                
                <!-- Consent & Declaration Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">✓</div>
                        <div class="section-title">8. Consent & Declaration</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group-full">
                            <div class="checkbox-row">
                                <input type="checkbox" id="terms" name="terms_consent" required>
                                <label for="terms">
                                    I hereby declare that the information provided in this form is true and correct to the best of my knowledge. I understand that providing false information may result in termination of employment.
                                </label>
                            </div>
                             <div class="checkbox-row">
                                <input type="checkbox" id="data" name="data_consent" required>
                                <label for="data">
                                    I consent to the collection, processing, and storage of my personal data for employment and administrative purposes in accordance with the school's data privacy policy.
                                </label>
                            </div>
                            
                            <div class="checkbox-row">
                                <input type="checkbox" id="update" name="update_consent" required>
                                <label for="update">
                                    I agree to update my information whenever there are changes to ensure the accuracy of records maintained by the school.
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="signature" class="required">Digital Signature</label>
                            <input type="text" id="signature" name="digital_signature" placeholder="Type your full name as signature" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="date" class="required">Date</label>
                            <input type="date" id="date" name="digital_date" required>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="action-row">
                    <button type="button" class="btn btn-secondary" id="saveAsDraft">Save as Draft</button>
                    <div>
                        <button type="button" class="btn btn-primary" id="prevStep" style="display: none;">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextStep">Next</button>
                        <input type="submit" class="btn btn-success" id="submitForm" value="Submit Registration">
                    </div>
                </div>
            </form>
        </div>
    </div>
        <script src="staffreg.jks"></script>


    </script>
</body>
</html>