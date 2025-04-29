<?php

// staff register.php
// staff_id 	user_id 	staff_type 	staff_number 	full_name 	date_of_birth 	gender 	marital_status 	national_id 	profile_photo_path 	phone_number 	
// personal_email 	residential_address 	department_id 	designation 	hire_date 	employment_type 	supervisor 	created_at 	updated_at 	
//fetch data from role table
session_start();
include 'dbconnect.php';


// Check if user is not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

//fecth data from roles table
$sql = "SELECT * FROM roles";
$result = mysqli_query($conn, $sql);
if ($result) {
    $roles = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($conn);
}

//fetch data from departments table
$sql = "SELECT * FROM departments";
$result = mysqli_query($conn, $sql);
if ($result) {
    $departments = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($conn);
}


// Initialize all variables in one line
$staff_type = $username = $email = $role_name = $access_level = $password_hash = $confirmPassword = '';
$full_name = $date_of_birth = $gender = $marital_status = $national_id = $profile_photo_path = '';
$phone_number = $personal_email = $residential_address = '';
$degree = $institution = $major = $graduation_year = $certification_path = '';
$staff_number = $designation = $hire_date = $employment_type = $supervisor = '';
$bank_name = $account_number = $tax_id = $tin_number = $salary_scale = $payment_frequency = '';
$document_path = $document_type = $document_number = $expiry_date = $document_description = '';
$terms_consent = $data_consent = $update_consent = $digital_signature = $digital_date = '';

// Get and sanitize form values if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Account Setup
    $staff_type = filter_input(INPUT_POST, 'staff_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $role_name = filter_input(INPUT_POST, 'role_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $access_level = filter_input(INPUT_POST, 'access_level', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password_hash = filter_input(INPUT_POST, 'password_hash', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmPassword = filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Personal Details
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $date_of_birth = filter_input(INPUT_POST, 'date_of_birth', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $marital_status = filter_input(INPUT_POST, 'marital_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $national_id = filter_input(INPUT_POST, 'national_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $profile_photo_path = filter_input(INPUT_POST, 'profile_photo_path', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Contact Information
    $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $personal_email = filter_input(INPUT_POST, 'personal_email', FILTER_SANITIZE_EMAIL);
    $residential_address = filter_input(INPUT_POST, 'residential_address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Academic Qualifications
    $degree = filter_input(INPUT_POST, 'degree', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $institution = filter_input(INPUT_POST, 'institution', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $major = filter_input(INPUT_POST, 'major', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $graduation_year = filter_input(INPUT_POST, 'graduation_year', FILTER_SANITIZE_NUMBER_INT);
    $certification_path = filter_input(INPUT_POST, 'certification_path', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Employment Information
    $staff_number = filter_input(INPUT_POST, 'staff_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $designation = filter_input(INPUT_POST, 'designation', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $hire_date = filter_input(INPUT_POST, 'hire_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $employment_type = filter_input(INPUT_POST, 'employment_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $supervisor = filter_input(INPUT_POST, 'supervisor', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Payroll & Bank Details
    $bank_name = filter_input(INPUT_POST, 'bank_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $account_number = filter_input(INPUT_POST, 'account_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tax_id = filter_input(INPUT_POST, 'tax_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tin_number = filter_input(INPUT_POST, 'tin_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $salary_scale = filter_input(INPUT_POST, 'salary_scale', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $payment_frequency = filter_input(INPUT_POST, 'payment_frequency', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Document Uploads
    $document_path = filter_input(INPUT_POST, 'document_path', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $document_type = filter_input(INPUT_POST, 'document_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $document_number = filter_input(INPUT_POST, 'document_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $expiry_date = filter_input(INPUT_POST, 'expiry_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $document_description = filter_input(INPUT_POST, 'document_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Consent & Declaration
    $terms_consent = filter_input(INPUT_POST, 'terms_consent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $data_consent = filter_input(INPUT_POST, 'data_consent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $update_consent = filter_input(INPUT_POST, 'update_consent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $digital_signature = filter_input(INPUT_POST, 'digital_signature', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $digital_date = filter_input(INPUT_POST, 'digital_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


//first insert into users table, the username,email, role, access level and password id the password hash is the same as confirm password.
if($password_hash==$confirmPassword){
    $sql="insert into users(suernem,email,password_hash,role_id,access_level) values('$username','$email','$password_hash','$role_name','$access_level')";
    $result=mysqli_query($conn,$sql);
}



}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="staffreg.css">

    <title>Teaching Staff Registration Portal</title>
    <style>
      
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo-address">
                <div class="logo">School Teaching Staff Portal</div>
                <div class="address">123 Education Avenue, Learning City, ED 12345</div>
            </div>
            <div class="school-logo">
                <img src="/api/placeholder/120/60" alt="School Logo"/>
            </div>
        </div>
    </header>
    
    <div class="container" id="registrationFormContainer">
        <div class="form-container">
            <h1 id="formTitle">Teaching Staff Registration Form</h1>
            
            <div class="form-steps">
                <div class="step active">1</div>
                <div class="step">2</div>
                <div class="step">3</div>
                <div class="step">4</div>
            </div>
            
            <form id="staffRegistrationForm" action="" method="POST">
                <!-- Hidden field for staff type -->
                <input type="hidden" id="staff_type" name="staff_type" value="teaching">
                
                <!-- Account Setup Section -->
                <div class="form-section" id="accountSetupSection">
                    <div class="section-header">
                        <div class="section-icon">🔐</div>
                        <div class="section-title">Account Setup</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username" class="required">Username</label>
                            <input type="text" id="username" name="username" required>
                            <div class="help-text">Will be used for logging into the system</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="required">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="userRole">Role</label>
                            <select id="userRole" name="role_name" required>
                                <option value="">Select a role</option>
                                <option value="1">Professor</option>
                                <option value="2">Assistant Professor</option>
                                <option value="3">Lecturer</option>
                                <option value="4">Teaching Assistant</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="userStatus">Access Level</label>
                            <select id="userStatus" required name="access_level">                                        
                                <option value="standard">Standard</option>
                                <option value="basic">Basic</option>
                                <option value="advanced">Advanced</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password" class="required">Password</label>
                            <input type="password" id="password" name="password_hash" required>
                            <div class="help-text">Must be at least 8 characters with numbers and special characters</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmPassword" class="required">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" required>
                        </div>
                    </div>
                </div>
                
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
                            <div class="image-upload" id="profilePhotoUpload">
                                <div class="upload-icon">📷</div>
                                <div>Click to upload photo</div>
                                <div class="help-text">JPEG or PNG, max 2MB</div>
                                <input type="file" id="photo_path" name="profile_photo_path" accept="image/*" style="display: none;" required>
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
                            <input type="tel" id="phone" name="phone_number" required>
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
                                    <input type="text" id="degree1" name="degree" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="institution1" class="required">Institution</label>
                                    <input type="text" id="institution1" name="institution" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="major1" class="required">Major/Area of Specialization</label>
                                    <input type="text" id="major1" name="major" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="year1" class="required">Year of Graduation</label>
                                    <input type="number" id="year1" name="graduation_year" min="1950" max="2030" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group-full">
                                    <label for="certificate1" class="required">Upload Certificate</label>
                                    <div class="image-upload" id="certificateUploadWrapper1">
                                        <div class="upload-icon">📄</div>
                                        <div>Click to upload certificate</div>
                                        <div class="help-text">PDF, JPEG or PNG, max 5MB</div>
                                        <input type="file" id="certificate1" name="certification_path" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" required>
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
                            <label for="department">Department</label>
                            <select id="department" name="department" required>
                                <option value="">Select a department</option>
                                <option value="1">Mathematics</option>
                                <option value="2">Computer Science</option>
                                <option value="3">English</option>
                                <option value="4">History</option>
                                <option value="5">Biology</option>
                                <option value="6">Physics</option>
                                <option value="7">Chemistry</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="designation" class="required">Designation</label>
                            <select id="designation" name="designation" required>
                                <option value="">Select Designation</option>
                                <option value="lecturer">Lecturer</option>
                                <option value="assistant-professor">Assistant Professor</option>
                                <option value="associate-professor">Associate Professor</option>
                                <option value="professor">Professor</option>
                                <option value="hod">Head of Department</option>
                                <option value="dean">Dean</option>
                                <option value="teacher">Teacher</option>
                                <option value="instructor">Instructor</option>
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
                    
                    <div id="employment-additional-fields"></div>
                    
                    <button type="button" class="add-row-btn" id="addEmploymentField">
                        <i>+</i> Add New Field
                    </button>
                </div>
                
                <!-- Teaching Load & Timetable Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">📚</div>
                        <div class="section-title">5. Teaching Load & Timetable</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="assigned_courses" class="required">Assigned Course Units</label>
                            <textarea id="assigned_courses" name="assigned_course_units" rows="3" placeholder="List your assigned courses, separated by commas" required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="semester_load" class="required">Semester Load (Hours)</label>
                            <input type="number" id="semester_load" name="semester_load" min="1" max="40" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="office_hours" class="required">Office Hours</label>
                            <input type="text" id="office_hours" name="office_hours" placeholder="e.g., Mon 10-12, Wed 2-4" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="available_times" class="required">Available Teaching Days/Times</label>
                            <textarea id="available_times" name="available_times" rows="3" placeholder="Specify your availability for teaching throughout the week" required></textarea>
                        </div>
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
                                        <input type="file" id="path" name="document_path" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" required>
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
                        <button type="submit" class="btn btn-success" id="submitForm" style="display: none;">Submit Registration</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

  <script src="staffreg.js"></script>

    </script>
</body>
</html>