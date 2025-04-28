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
    
    if($result){
        //get the last inserted user id
        $user_id=mysqli_insert_id($conn);
        //insert into staff table
        $sql="insert into staff() values('$user_id','$staff_type','$staff_number','$full_name','$date_of_birth','$gender','$marital_status','$national_id','$profile_photo_path','$phone_number',
        '$personal_email','$residential_address','$department_id','$designation','$hire_date','$employment_type','$supervisor',now(),now())";
        $result=mysqli_query($conn,$sql);
        if($result){
            //get the last inserted staff id
            $staff_id=mysqli_insert_id($conn);
            //insert into academic qualifications table
            $sql="insert into academic_qualifications(staff_id,degree,institution,major,graduation_year,certification_path) values('$staff_id','$degree','$institution','$major','$graduation_year','$certification_path')";
            $result=mysqli_query($conn,$sql);
            if($result){
                //insert into payroll and bank details table
                $sql="insert into payroll_and_bank_details(staff_id,bank_name,account_number,tax_id,tin_number,salary_scale,payment_frequency) values('$staff_id','$bank_name','$account_number','$tax_id','$tin_number','$salary_scale','$payment_frequency')";
                $result=mysqli_query($conn,$sql);
                if($result){
                    //insert into document uploads table
                    $sql="insert into document_uploads(staff_id,document_path,document_type,document_number,expiry_date,document_description) values('$staff_id','$document_path','$document_type','$document_number','$expiry_date','$document_description')";
                    $result=mysqli_query($conn,$sql);
                    if($result){
                        //insert into consent and declaration table
                        $sql="insert into staff_consent()values('$staff_id','$terms_consent','$data_consent','$update_consent','$digital_signature','$digital_date')";
                        $result=mysqli_query($conn,$sql);
                        
                    }else{
                        echo "Error: ".mysqli_error($conn);
                    }
                }else{
                    echo "Error: ".mysqli_error($conn);
                }
            }else{
                echo "Error: ".mysqli_error($conn);
            }
    }
}


}}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration Portal</title>
    <link rel="stylesheet" href="staff register.css">
    
    <style>
    

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
                <img src="logo.png" School Logo/>
            </div>
        </div>
    </header>
    
    <!-- Staff Type Selection -->
    <div class="container" id="staffTypeContainer">
        <div class="form-container">
            <h1>Staff Registration</h1>
            <div class="staff-type-selection">
                <h2>Please select your staff type:</h2>
                <div class="staff-type-options">
                    <div class="staff-type-card" id="teachingStaff">
                        <div class="staff-icon">👨‍🏫</div>
                        <h3>Teaching Staff</h3>
                        <p>For teachers, lecturers, professors and other academic staff</p>
                    </div>
                    <div class="staff-type-card" id="nonTeachingStaff">
                        <div class="staff-icon">👩‍💼</div>
                        <h3>Non-Teaching Staff</h3>
                        <p>For administrative, support, maintenance and other non-academic staff</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Registration Form (Initially Hidden) -->
    <div class="container" id="registrationFormContainer" style="display: none;">
        <div class="form-container">
            <h1 id="formTitle">Staff Registration Form</h1>
            
            <div class="form-steps">
                <div class="step active">1</div>
                <div class="step">2</div>
                <div class="step">3</div>
                <div class="step">4</div>
            </div>
            
            <form id="staffRegistrationForm" action="" method="POST">
                <!-- Staff Type Hidden Field -->
                <input type="hidden" id="staff_type" name="staff_type" value="">
                
                <!-- Account Setup Section (Only for Teaching Staff) -->
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
                                            <?php foreach($roles as $r): ?>
                                                <option value="<?= $r['role_id']; ?>"><?= $r['role_name']; ?></option>
                                            <?php endforeach; ?>
                                        </option>
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
                            <textarea id="address" name="residential_address" rows="3"></textarea>
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
                                    <input type="text" id="degree1" name="degree">
                                </div>
                                
                                <div class="form-group">
                                    <label for="institution1" class="required">Institution</label>
                                    <input type="text" id="institution1" name="institution" >
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="major1" class="required">Major/Area of Specialization</label>
                                    <input type="text" id="major1" name="major" >
                                </div>
                                
                                <div class="form-group">
                                    <label for="year1" class="required">Year of Graduation</label>
                                    <input type="number" id="year1" name="graduation_year" min="1950" max="2030">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group-full">
                                    <label for="certificate1" class="required">Upload Certificate</label>
                                    <div class="image-upload" id="certificateUploadWrapper1">
                                        <div class="upload-icon">📄</div>
                                        <div>Click to upload certificate</div>
                                        <div class="help-text">PDF, JPEG or PNG, max 5MB</div>
                                        <input type="file" id="certificate1" name="certification_path" accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
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
                                    <label for="userRole">Department</label>
                                    <select id="userRole" name="department" required>
                                        <option value="">Select a department</option>
                                            <?php foreach($departments as $r): ?>
                                                <option value="<?= $r['department_id']; ?>"><?= $r['department_name']; ?></option>
                                            <?php endforeach; ?>
                                        </option>
                                    </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="designation" class="required">Designation</label>
                            <select id="designation" name="designation" required>
                                <option value="">Select Designation</option>
                                <!-- Teaching Staff Options -->
                                <option class="teaching-option" value="lecturer">Lecturer</option>
                                <option class="teaching-option" value="assistant-professor">Assistant Professor</option>
                                <option class="teaching-option" value="associate-professor">Associate Professor</option>
                                <option class="teaching-option" value="professor">Professor</option>
                                <option class="teaching-option" value="hod">Head of Department</option>
                                <option class="teaching-option" value="dean">Dean</option>
                                <option class="teaching-option" value="teacher">Teacher</option>
                                <option class="teaching-option" value="instructor">Instructor</option>
                                
                                <!-- Non-Teaching Staff Options -->
                                <option class="non-teaching-option" value="admin-assistant">Administrative Assistant</option>
                                <option class="non-teaching-option" value="accountant">Accountant</option>
                                <option class="non-teaching-option" value="librarian">Librarian</option>
                                <option class="non-teaching-option" value="it-support">IT Support</option>
                                <option class="non-teaching-option" value="security-officer">Security Officer</option>
                                <option class="non-teaching-option" value="janitor">Janitor</option>
                                <option class="non-teaching-option" value="coordinator">Coordinator</option>
                                <option class="non-teaching-option" value="manager">Manager</option>
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
                
                <!-- Teaching Load & Timetable Section (Only for Teaching Staff) -->
                <div class="form-section teaching-only-section">
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
                
                <!-- Working Hours Section (Only for Non-Teaching Staff) -->
                <div class="form-section non-teaching-only-section">
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
                    <div class="form-group"> <!-- non-teaching-only-field -->
                            <label for="work_area">Work Area/Office</label>
                            <input type="text" id="work_area" name="work_area" placeholder="e.g., Admin Block, Finance Office">
                        </div>
                </div>
                
                <!-- System Access & Permissions Section -->
                <!-- <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">🔐</div>
                        <div class="section-title">6. System Access & Permissions</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="role_id" class="required">Portal Role</label>
                            <select id="role_id" name="role_id" required>
                                <option value="">Select Role</option>
                                <option class="teaching-option" value="instructor">Instructor</option>
                                <option class="teaching-option" value="course-coordinator">Course Coordinator</option>
                                <option class="teaching-option" value="department-admin">Department Admin</option>
                                <option class="non-teaching-option" value="staff">Staff</option>
                                <option class="non-teaching-option" value="department-staff">Department Staff</option>
                                <option class="non-teaching-option" value="support-staff">Support Staff</option>
                                <option value="system-admin">System Admin</option>
                            </select>
                        </div>
                        
                        <div class="form-group teaching-only-field">
                            <label for="assigned_classes">Assigned Classes/Rooms</label>
                            <input type="text" id="assigned_classes" name="assigned_classes" placeholder="e.g., Room 101, Lab 3">
                        </div>
                        
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="access_level">System Access Level</label>
                            <select id="access_level" name="access_level">
                                <option value="basic">Basic (View only)</option>
                                <option value="standard" selected>Standard (View & Edit own data)</option>
                                <option value="advanced">Advanced (Department level access)</option>
                                <option value="admin">Administrative (Full system access)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="access_start">Access Start Date</label>
                            <input type="date" id="access_start" name="access_start">
                        </div>
                    </div>
                </div> -->
                
                <!-- Payroll & Bank Details Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">💰</div>
                        <div class="section-title">7. Payroll & Bank Details</div>
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
                            <select id="frequency" name="payment_frequency">
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
                        <div class="section-title">8. Document Uploads</div>
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
                                        <input type="file" id="path" name="document_path" accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="type" class="required">Document Type</label>
                                    <select id="type" name="document_type">
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
                                    <input type="text" id="number" name="document_number">
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
                        <div class="section-title">9. Consent & Declaration</div>
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
                        <button type="button" class="btn btn-primary" id="prevStep" style="margin-right: 10px;">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextStep">Next</button>
                        <button type="submit" class="btn btn-success" id="submitForm" style="display: none;" name="submit">Submit Registration</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
    // Staff Type Selection
    const teachingStaffCard = document.getElementById('teachingStaff');
    const nonTeachingStaffCard = document.getElementById('nonTeachingStaff');
    const staffTypeContainer = document.getElementById('staffTypeContainer');
    const registrationFormContainer = document.getElementById('registrationFormContainer');
    const staffTypeInput = document.getElementById('staff_type');
    const accountSetupSection = document.getElementById('accountSetupSection');
    const formTitle = document.getElementById('formTitle');
    
    // Handle teaching staff selection
    teachingStaffCard.addEventListener('click', function() {
        staffTypeInput.value = 'teaching';
        staffTypeContainer.style.display = 'none';
        registrationFormContainer.style.display = 'block';
        accountSetupSection.style.display = 'block';
        formTitle.textContent = 'Teaching Staff Registration Form';
        
        // Show teaching-only sections, hide non-teaching sections
        document.querySelectorAll('.teaching-only-section').forEach(section => {
            section.style.display = 'block';
        });
        document.querySelectorAll('.non-teaching-only-section').forEach(section => {
            section.style.display = 'none';
        });

        // Show teaching options in selects, hide non-teaching options
        document.querySelectorAll('.teaching-option').forEach(option => {
            option.style.display = '';
        });
        document.querySelectorAll('.non-teaching-option').forEach(option => {
            option.style.display = 'none';
        });
        document.querySelectorAll('.non-teaching-only-field').forEach(field => {
            field.style.display = 'none';
        });
        document.querySelectorAll('.teaching-only-field').forEach(field => {
            field.style.display = 'block';
        });
    });

    // Handle non-teaching staff selection
    nonTeachingStaffCard.addEventListener('click', function() {
        staffTypeInput.value = 'non-teaching';
        staffTypeContainer.style.display = 'none';
        registrationFormContainer.style.display = 'block';
        accountSetupSection.style.display = 'none';
        formTitle.textContent = 'Non-Teaching Staff Registration Form';
        
        // Show non-teaching only sections, hide teaching sections
        document.querySelectorAll('.teaching-only-section').forEach(section => {
            section.style.display = 'none';
        });
        document.querySelectorAll('.non-teaching-only-section').forEach(section => {
            section.style.display = 'block';
        });
        
        // Show non-teaching options in selects, hide teaching options
        document.querySelectorAll('.non-teaching-option').forEach(option => {
            option.style.display = '';
        });
        document.querySelectorAll('.teaching-option').forEach(option => {
            option.style.display = 'none';
        });
        document.querySelectorAll('.teaching-only-field').forEach(field => {
            field.style.display = 'none';
        });
        document.querySelectorAll('.non-teaching-only-field').forEach(field => {
            field.style.display = 'block';
        });
    });

    // Form multi-step functionality
    const formSections = document.querySelectorAll('.form-section');
    const formSteps = document.querySelectorAll('.step');
    const prevStepBtn = document.getElementById('prevStep');
    const nextStepBtn = document.getElementById('nextStep');
    const submitFormBtn = document.getElementById('submitForm');
    let currentStep = 0;

    // Initialize form steps
    function updateFormSteps() {
        formSections.forEach((section, index) => {
            if (index < currentStep * 2 || index > currentStep * 2 + 1) {
                section.style.display = 'none';
            } else {
                section.style.display = 'block';
            }
        });
        
        // Update step indicators
        formSteps.forEach((step, index) => {
            if (index <= currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
        
        // Show/hide buttons
        if (currentStep === 0) {
            prevStepBtn.style.display = 'none';
        } else {
            prevStepBtn.style.display = 'inline-block';
        }
        
        if (currentStep === formSteps.length - 1) {
            nextStepBtn.style.display = 'none';
            submitFormBtn.style.display = 'inline-block';
        } else {
            nextStepBtn.style.display = 'inline-block';
            submitFormBtn.style.display = 'none';
        }
    }

    // Initialize form
    updateFormSteps();

    // Next button click
    nextStepBtn.addEventListener('click', function() {
        // Validate current sections before proceeding
        const currentSections = document.querySelectorAll(`.form-section:nth-child(${currentStep * 2 + 1}), .form-section:nth-child(${currentStep * 2 + 2})`);
        let isValid = true;
        
        currentSections.forEach(section => {
            const requiredFields = section.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value) {
                    field.classList.add('invalid');
                    isValid = false;
                } else {
                    field.classList.remove('invalid');
                }
            });
        });
        
        if (isValid) {
            currentStep++;
            if (currentStep >= formSteps.length) {
                currentStep = formSteps.length - 1;
            }
            updateFormSteps();
            window.scrollTo(0, 0);
        } else {
            alert('Please fill in all required fields before proceeding.');
        }
    });

    // Previous button click
    prevStepBtn.addEventListener('click', function() {
        currentStep--;
        if (currentStep < 0) {
            currentStep = 0;
        }
        updateFormSteps();
        window.scrollTo(0, 0);
    });

    // Handle file uploads
    const profilePhotoUpload = document.getElementById('profilePhotoUpload');
    const photoPath = document.getElementById('photo_path');

    if (profilePhotoUpload && photoPath) {
        profilePhotoUpload.addEventListener('click', function() {
            photoPath.click();
        });

        photoPath.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                profilePhotoUpload.innerHTML = `
                    <div class="upload-icon">✓</div>
                    <div>${fileName}</div>
                    <div class="help-text">File selected</div>
                `;
                profilePhotoUpload.classList.add('file-selected');
            }
        });
    }

    // Handle all image uploads
    document.querySelectorAll('.image-upload').forEach(upload => {
        const input = upload.querySelector('input[type="file"]');
        if (input) {
            upload.addEventListener('click', function() {
                input.click();
            });
            
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const fileName = this.files[0].name;
                    upload.innerHTML = `
                        <div class="upload-icon">✓</div>
                        <div>${fileName}</div>
                        <div class="help-text">File selected</div>
                    `;
                    upload.classList.add('file-selected');
                }
            });
        }
    });

    // Add another qualification
    const qualificationsContainer = document.getElementById('qualifications-container');
    const addQualificationBtn = document.getElementById('addQualification');

    if (addQualificationBtn && qualificationsContainer) {
        addQualificationBtn.addEventListener('click', function() {
            const qualificationCount = qualificationsContainer.querySelectorAll('.qualification-row').length;
            const newRow = document.createElement('div');
            newRow.className = 'qualification-row';
            
            newRow.innerHTML = `
                <button type="button" class="remove-row">×</button>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="degree${qualificationCount + 1}" class="required">Degree/Qualification</label>
                        <input type="text" id="degree${qualificationCount + 1}" name="qualifications[${qualificationCount}][degree]" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="institution${qualificationCount + 1}" class="required">Institution</label>
                        <input type="text" id="institution${qualificationCount + 1}" name="qualifications[${qualificationCount}][institution]" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="major${qualificationCount + 1}" class="required">Major/Area of Specialization</label>
                        <input type="text" id="major${qualificationCount + 1}" name="qualifications[${qualificationCount}][major]" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="gradYear${qualificationCount + 1}" class="required">Year of Graduation</label>
                        <input type="number" id="gradYear${qualificationCount + 1}" name="qualifications[${qualificationCount}][gradYear]" min="1950" max="2030" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group-full">
                        <label for="certificateUpload${qualificationCount + 1}" class="required">Upload Certificate</label>
                        <div class="image-upload" id="certificateUploadWrapper${qualificationCount + 1}">
                            <div class="upload-icon">📄</div>
                            <div>Click to upload certificate</div>
                            <div class="help-text">PDF, JPEG or PNG, max 5MB</div>
                            <input type="file" id="certificateUpload${qualificationCount + 1}" name="qualifications[${qualificationCount}][certificate]" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" required>
                        </div>
                    </div>
                </div>
            `;
            
            qualificationsContainer.appendChild(newRow);
            
            // Add event listeners for the new file upload
            const newUpload = newRow.querySelector('.image-upload');
            const newInput = newRow.querySelector('input[type="file"]');
            
            newUpload.addEventListener('click', function() {
                newInput.click();
            });
            
            newInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const fileName = this.files[0].name;
                    newUpload.innerHTML = `
                        <div class="upload-icon">✓</div>
                        <div>${fileName}</div>
                        <div class="help-text">File selected</div>
                    `;
                    newUpload.classList.add('file-selected');
                }
            });
            
            // Add event listener for remove button
            const removeBtn = newRow.querySelector('.remove-row');
            removeBtn.addEventListener('click', function() {
                qualificationsContainer.removeChild(newRow);
            });
        });
    }

    // Add employment field
    const employmentFieldsContainer = document.getElementById('employment-additional-fields');
    const addEmploymentFieldBtn = document.getElementById('addEmploymentField');

    if (addEmploymentFieldBtn && employmentFieldsContainer) {
        addEmploymentFieldBtn.addEventListener('click', function() {
            const fieldCount = employmentFieldsContainer.querySelectorAll('.additional-field-row').length;
            
            const fieldName = prompt('Enter field name:');
            if (fieldName) {
                const newRow = document.createElement('div');
                newRow.className = 'additional-field-row';
                
                newRow.innerHTML = `
                    <div class="form-row">
                        <div class="form-group">
                            <label for="additionalField${fieldCount + 1}">${fieldName}</label>
                            <input type="text" id="additionalField${fieldCount + 1}" name="additionalFields[${fieldCount}][value]">
                            <input type="hidden" name="additionalFields[${fieldCount}][name]" value="${fieldName}">
                        </div>
                        <button type="button" class="remove-field-btn">Remove</button>
                    </div>
                `;
                
                employmentFieldsContainer.appendChild(newRow);
                
                // Add event listener for remove button
                const removeBtn = newRow.querySelector('.remove-field-btn');
                removeBtn.addEventListener('click', function() {
                    employmentFieldsContainer.removeChild(newRow);
                });
            }
        });
    }

    // Add document upload
    const documentsContainer = document.getElementById('documents-container');
    const addDocumentBtn = document.getElementById('addDocument');

    if (addDocumentBtn && documentsContainer) {
        addDocumentBtn.addEventListener('click', function() {
            const documentCount = documentsContainer.querySelectorAll('.document-row').length;
            
            const newRow = document.createElement('div');
            newRow.className = 'document-row';
            
            newRow.innerHTML = `
                <button type="button" class="remove-row">×</button>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="document${documentCount + 1}" class="required">Document</label>
                        <div class="image-upload" id="documentUpload${documentCount + 1}">
                            <div class="upload-icon">📄</div>
                            <div>Click to upload document</div>
                            <div class="help-text">PDF, JPEG or PNG, max 5MB</div>
                            <input type="file" id="document${documentCount + 1}" name="documents[${documentCount}][file]" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="documentType${documentCount + 1}" class="required">Document Type</label>
                        <select id="documentType${documentCount + 1}" name="documents[${documentCount}][type]" required>
                            <option value="employment">Employment Contract</option>
                            <option value="certificate">Academic Certificate</option>
                            <option value="reference">Reference Letter</option>
                            <option value="medical">Medical Certificate</option>
                            <option value="other">Other Document</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="documentDescription${documentCount + 1}">Document Description</label>
                        <input type="text" id="documentDescription${documentCount + 1}" name="documents[${documentCount}][description]">
                    </div>
                    
                    <div class="form-group">
                        <label for="documentExpiry${documentCount + 1}">Expiry Date (if applicable)</label>
                        <input type="date" id="documentExpiry${documentCount + 1}" name="documents[${documentCount}][expiry]">
                    </div>
                </div>
            `;
            
            documentsContainer.appendChild(newRow);
            
            // Add event listeners for the new file upload
            const newUpload = newRow.querySelector('.image-upload');
            const newInput = newRow.querySelector('input[type="file"]');
            
            newUpload.addEventListener('click', function() {
                newInput.click();
            });
            
            newInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const fileName = this.files[0].name;
                    newUpload.innerHTML = `
                        <div class="upload-icon">✓</div>
                        <div>${fileName}</div>
                        <div class="help-text">File selected</div>
                    `;
                    newUpload.classList.add('file-selected');
                }
            });
            
            // Add event listener for remove button
            const removeBtn = newRow.querySelector('.remove-row');
            removeBtn.addEventListener('click', function() {
                documentsContainer.removeChild(newRow);
            });
        });
    }

    // // Form submission
    // // const staffRegistrationForm = document.getElementById('staffRegistrationForm');
    // // const saveAsDraftBtn = document.getElementById('saveAsDraft');

    // // if (staffRegistrationForm) {
    // //     staffRegistrationForm.addEventListener('submit', function(e) {
    // //         e.preventDefault();
    // //         console.log("Form submission triggered");
            
    // //         // Validate form before submission
    // //         const requiredFields = document.querySelectorAll('[required]');
    // //         let isValid = true;
            
    // //         requiredFields.forEach(field => {
    // //             if (!field.value) {
    // //                 field.classList.add('invalid');
    // //                 isValid = false;
    // //                 console.log("Invalid field:", field.id);
    // //             } else {
    // //                 field.classList.remove('invalid');
    // //             }
    // //         });
            
    // //         if (isValid) {
    // //             console.log("Form is valid, preparing to submit");
    // //             // Collect form data
    // //             const formData = new FormData(this);
                
    // //             // Add file uploads to form data
    // //             const photoPathInput = document.getElementById('photo_path');
    // //             if (photoPathInput && photoPathInput.files.length > 0) {
    // //                 formData.append('profilePhoto', photoPathInput.files[0]);
    // //             }
                
    // //             // Log formData keys for debugging
    // //             for (let key of formData.keys()) {
    // //                 console.log("FormData includes key:", key);
    // //             }
                
    //             // Submit via AJAX with better error handling
    //             // fetch('submit_staff.php', {
    //             //     method: 'POST',
    //             //     body: formData
    //             // })
    //             // .then(response => {
    //             //     console.log("Response status:", response.status);
    //             //     return response.json();
    //             // })
    //             // .then(data => {
    //             //     console.log("Server response:", data);
    //             //     if (data.success) {
    //             //         alert('Registration submitted successfully! Staff ID: ' + data.staff_id);
    //             //         // Redirect to confirmation page or clear form
    //             //         // window.location.href = 'confirmation.html?staff_id=' + data.staff_id;
    //             //     } else {
    //             //         alert('Error: ' + data.message);
    //             //     }
    //             // })
    //             // .catch(error => {
    //             //     console.error('Error:', error);
    //             //     alert('An error occurred while submitting the form.');
    //             // });
    //         // } else {
    //         //     alert('Please fill in all required fields before submitting.');
    //         // }
    //     });
    // }

    // Save as draft functionality
    if (saveAsDraftBtn) {
        saveAsDraftBtn.addEventListener('click', function() {
            // In a real application, you would save the current state to localStorage or server
            alert('Your registration has been saved as draft. You can continue later.');
        });
    }

    // Remove qualification row functionality
    const firstRemoveRowBtn = document.querySelector('.qualification-row .remove-row');
    if (firstRemoveRowBtn) {
        firstRemoveRowBtn.addEventListener('click', function() {
            const qualificationRows = qualificationsContainer.querySelectorAll('.qualification-row');
            if (qualificationRows.length > 1) {
                qualificationsContainer.removeChild(this.parentNode);
            } else {
                alert('At least one qualification is required.');
            }
        });
    }

    // Password validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');

    if (password && confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            if (password.value !== this.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // Initialize date fields with current date
    const today = new Date().toISOString().split('T')[0];
    const dateField = document.getElementById('date');
    if (dateField) {
        dateField.value = today;
    }
});
</script>
</body>
</html>