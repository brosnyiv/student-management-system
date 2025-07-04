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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate a unique student ID
    $student_id = uniqid('STU');

    // Sanitize and retrieve form inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = isset($_POST['middle_name']) ? mysqli_real_escape_string($conn, $_POST['middle_name']) : null;
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $nationality = mysqli_real_escape_string($conn, $_POST['nationality']);
    $course_id = isset($_POST['course_id']) ? mysqli_real_escape_string($conn, $_POST['course_id']) : null;
    $created_by = 'Admin'; // Default created_by value
    $status = 'Active'; // Default status value
    $created_at = date('Y-m-d H:i:s'); // Current timestamp
    $updated_at = date('Y-m-d H:i:s'); // Current timestamp

    // Handle profile photo upload
    $profile_photo_path = null;
    if (isset($_FILES['profile_photo_path']) && $_FILES['profile_photo_path']['error'] === UPLOAD_ERR_OK) {
        $photo_tmp_name = $_FILES['profile_photo_path']['tmp_name'];
        $photo_name = uniqid('photo_') . '_' . $_FILES['profile_photo_path']['name'];
        $upload_dir = 'uploads/students/';
        $profile_photo_path = $upload_dir . $photo_name;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($photo_tmp_name, $profile_photo_path)) {
            die('Error uploading profile photo.');
        }
    }

    // Insert into the `students` table
    $query = "
        INSERT INTO students (
            student_id, first_name, middle_name, surname, date_of_birth, gender,
            profile_photo_path, nationality, course_id, created_at, updated_at,
            created_by, updated_by, status
        ) VALUES (
            '$student_id', '$first_name', '$middle_name', '$surname', '$date_of_birth', '$gender',
            '$profile_photo_path', '$nationality', '$course_id', '$created_at', '$updated_at',
            '$created_by', NULL, '$status'
        )
    ";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'Student registered successfully!';
    } else {
        echo 'Error inserting student: ' . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form</title>
    <link rel="stylesheet" href="student registration.css">

    <style>
      
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="logo.png" alt="School Logo" />
            </div>
            <div class="address">
                <p>Monoco Institute<br>
                Kibuli-Mbogo- Rd<br>
                Kampala-Uganda<br>
                Phone: (256) 754-7879-09<br>
                Email: monaco-institute.ac</p>
            </div>
        </div>
        
        <div class="form-container">
            <div class="title">
                <h1>Monaco Student Registration Form</h1>
                <p>Please complete all required fields </p>
            </div>
            
            <div class="progress-bar" id="progressBar">
                <div class="progress-step active" data-step="1">
                    <div class="step-icon">1</div>
                    <div class="step-text">Personal</div>
                </div>
                <div class="progress-step" data-step="2">
                    <div class="step-icon">2</div>
                    <div class="step-text">Contact</div>
                </div>
                <div class="progress-step" data-step="3">
                    <div class="step-icon">3</div>
                    <div class="step-text">Academic</div>
                </div>
                <div class="progress-step" data-step="4">
                    <div class="step-icon">4</div>
                    <div class="step-text">Payment</div>
                </div>
                <div class="progress-step" data-step="5">
                    <div class="step-icon">5</div>
                    <div class="step-text">Documents</div>
                </div>
                <!-- <div class="progress-step" data-step="6">
                    <div class="step-icon">6</div>
                    <div class="step-text">Confirmation</div>
                     </div> -->
                </div>
            
            <!-- Toast notification -->
            <div class="toast" id="toast">
                <span class="toast-icon">✓</span>
                <div class="toast-message">Form data saved successfully!</div>
                <button class="toast-close" aria-label="Close notification">×</button>
            </div>
            
            <!-- Autosave indicator -->
            <div class="autosave-indicator" id="autosaveIndicator">
                <div class="autosave-spinner"></div>
                <div class="autosave-text">Saving...</div>
            </div>
        
            <form id="studentRegistration" action="submit_student.php" method="POST" >
            <div class="section" data-section="1">
                    <div class="section-title">🔹 1. Personal Information</div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="first_name" class="required">First Name (as on ID)</label>
                                <input type="text" id="first_name" name="first_name" required>
                                <div id="first_nameError" class="error-message">Please enter your first name</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="surname" class="required">Surname</label>
                                <input type="text" id="surname" name="surname" required>
                                <div id="surnameError" class="error-message">Please enter your surname</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="middle_name">Middle Name (if any)</label>
                                <input type="text" id="middle_name" name="middle_name">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="date_of_birth" class="required">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" required>
                                <div id="dobError" class="error-message">Please enter your date of birth</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="gender" class="required">Gender</label>
                                <select id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                    <option value="Prefer not to say">Prefer not to say</option>
                                </select>
                                <div id="genderError" class="error-message">Please select your gender</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_photo_path" class="required">Profile Photo</label>
                        <div class="file-upload-container">
                            <div class="file-upload-text">Click to upload or drag and drop</div>
                            <small>Maximum file size: 5MB. Accepted formats: JPG, PNG</small>
                            <input type="file" id="profile_photo_path" name="profile_photo_path" accept="image/*" required>
                            <div id="profilePhotoError" class="error-message">Please upload a profile photo</div>
                        </div>
                        <div class="file-preview" id="profilePhotoPreview">
                            <img src="" alt="Profile photo preview">
                            <div class="file-name"></div>
                            <button type="button" class="file-remove" aria-label="Remove file">×</button>
                        </div>
                    </div>
                    
                    <!-- <div class="button-container">
                        <div></div>
                        <button type="button" class="btn-next" data-next="2">Next: Contact Details</button>
                    </div> -->
                </div>
        
                <!-- 2. Contact Details -->
                <div class="section" data-section="2">
                    <div class="section-title">🔹 2. Contact Details</div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="email" class="required">Email Address</label>
                                <input type="email" id="email" name="email" required>
                                <small>This will be used for portal access and notifications</small>
                                <div id="emailError" class="error-message">Please enter a valid email address</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="phone" class="required">Phone Number</label>
                                <input type="tel" id="phone" name="phone" placeholder="e.g., +256 754 787909" required>
                                <div id="phoneError" class="error-message">Please enter a valid phone number</div>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label for="alt_phone">Alternative Phone Number</label>
                                <input type="tel" id="alt_phone" name="alt_phone" placeholder="e.g., +256 754 787909">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address" class="required">Current Address</label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                        <div id="addressError" class="error-message">Please enter your current address</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="city" class="required">City</label>
                                <input type="text" id="city" name="city" required>
                                <div id="cityError" class="error-message">Please enter your city</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="state_province" class="required">State/Province</label>
                                <input type="text" id="state_province" name="state_province" required>
                                <div id="stateError" class="error-message">Please enter your state/province</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="zip_postal_code" class="required">Zip/Postal Code</label>
                                <input type="text" id="zip_postal_code" name="zip_postal_code" required>
                                <div id="zipCodeError" class="error-message">Please enter a valid zip/postal code</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="country" class="required">Country</label>
                                <select id="country" name="country" required>
                                    <option value="">Select Country</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div id="countryError" class="error-message">Please select your country</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="nationality" class="required">Nationality</label>
                                <input type="text" id="nationality" name="nationality" required>
                                <div id="nationalityError" class="error-message">Please enter your nationality</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Emergency Contact</label>
                        <div class="emergency-contact">
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="emergency_contact_name" class="required">Contact Name</label>
                                        <input type="text" id="emergency_contact_name" name="emergency_contact_name" required>
                                        <div id="emergencyNameError" class="error-message">Please enter emergency contact name</div>
                                    </div>
                                </div>
                                
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="emergency_relationship" class="required">Relationship</label>
                                        <select id="emergency_relationship" name="emergency_relationship" required>
                                            <option value="">Select Relationship</option>
                                            <option value="Parent">Parent</option>
                                            <option value="Guardian">Guardian</option>
                                            <option value="Sibling">Sibling</option>
                                            <option value="Spouse">Spouse</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div id="emergencyRelationError" class="error-message">Please enter your relationship</div>
                                    </div>
                                </div>
                                
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="emergency_phone" class="required">Phone Number</label>
                                        <input type="tel" id="emergency_phone" name="emergency_phone" required>
                                        <div id="emergencyPhoneError" class="error-message">Please enter a valid phone number</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- <div class="button-container">
                        <button type="button" class="btn-prev" data-prev="1">Previous: Personal Information</button>
                        <button type="button" class="btn-next" data-next="3">Next: Academic Information</button>
                    </div> -->
                </div>
                
                <!-- 3. Academic Information -->
                <div class="section" data-section="3">
                    <div class="section-title">🔹 3. Academic Information</div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="program_level" class="required">Program Level</label>
                                <select id="program_level" name="program_level" required>
                                    <option value="">Select Program Level</option>
                                    <option value="Certificate">Certificate</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="Bachelor">Bachelor</option>
                                    <option value="Master">Master</option>
                                    <option value="PhD">PhD</option>
                                </select>
                                <div id="programLevelError" class="error-message">Please select a program level</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="department" class="required">Department</label>
                                <select id="department" name="department" required>
                                    <option value="">Select Department</option>
                                    <option value="Information Technology">Information Technology</option>
                                    <option value="Business">Business</option>
                                    <option value="Design">Design</option>
                                    <option value="Marketing">Marketing</option>
                                </select>
                                <div id="departmentError" class="error-message">Please select a department</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="major" class="required">Major/Program</label>
                                <input type="text" id="major" name="major" required>
                                <div id="majorError" class="error-message">Please enter your major/program</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="year_level" class="required">Year Level</label>
                                <select id="year_level" name="year_level" required>
                                    <option value="">Select Year Level</option>
                                    <option value="1">First Year</option>
                                    <option value="2">Second Year</option>
                                    <option value="3">Third Year</option>
                                    <option value="4">Fourth Year</option>
                                    <option value="5">Fifth Year</option>
                                </select>
                                <div id="yearLevelError" class="error-message">Please select your year level</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="expected_start_date" class="required">Expected Start Date</label>
                                <input type="date" id="expected_start_date" name="expected_start_date" required>
                                <div id="startDateError" class="error-message">Please enter your expected start date</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="expected_end_date">Expected End Date</label>
                                <input type="date" id="expected_end_date" name="expected_end_date">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="previous_institution" class="required">Previous Institution</label>
                        <input type="text" id="previous_institution" name="previous_institution" required>
                        <div id="previousSchoolError" class="error-message">Please enter your previous institution</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="previous_gpa" class="required">Previous Grades/GPA</label>
                        <input type="text" id="previous_gpa" name="previous_gpa" placeholder="e.g., 3.5/4.0" required>
                        <div id="gradesError" class="error-message">Please enter your previous grades/GPA</div>
                    </div>
                    
                    <!-- <div class="button-container">
                        <button type="button" class="btn-prev" data-prev="2">Previous: Contact Details</button>
                        <button type="button" class="btn-next" data-next="4">Next: Payment Information</button>
                    </div> -->
                </div>
                
                <!-- 4. Payment Information -->
                <div class="section" data-section="4">
                    <div class="section-title">🔹 4. Payment Information</div>
                    
                    <div class="form-group">
                        <label for="paymentMethod" class="required">Payment Method</label>
                        <select id="paymentMethod" name="paymentMethod" required aria-describedby="paymentMethodError">
                            <option value="">Select Payment Method</option>
                            <option value="credit">Credit Card</option>
                            <option value="debit">Debit Card</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="scholarship">Scholarship</option>
                            <option value="loan">Student Loan</option>
                            <option value="other">Other</option>
                        </select>
                        <div id="paymentMethodError" class="error-message" role="alert">Please select a payment method</div>
                    </div>
                    
                    <div id="creditCardInfo" class="form-row" style="display: none;">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="cardName" class="required">Name on Card</label>
                                <input type="text" id="cardName" name="cardName" aria-describedby="cardNameError">
                                <div id="cardNameError" class="error-message" role="alert">Please enter the name on card</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="cardNumber" class="required">Card Number</label>
                                <input type="text" id="cardNumber" name="cardNumber" placeholder="XXXX XXXX XXXX XXXX" aria-describedby="cardNumberError">
                                <div id="cardNumberError" class="error-message" role="alert">Please enter a valid card number</div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="cardDetails" class="form-row" style="display: none;">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="expiryDate" class="required">Expiry Date</label>
                                <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY" aria-describedby="expiryDateError">
                                <div id="expiryDateError" class="error-message" role="alert">Please enter a valid expiry date</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="cvv" class="required">CVV</label>
                                <input type="text" id="cvv" name="cvv" placeholder="XXX" aria-describedby="cvvError">
                                <div id="cvvError" class="error-message" role="alert">Please enter a valid CVV</div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="bankInfo" class="form-row" style="display: none;">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="bankName" class="required">Bank Name</label>
                                <input type="text" id="bankName" name="bankName" aria-describedby="bankNameError">
                                <div id="bankNameError" class="error-message" role="alert">Please enter your bank name</div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="accountNumber" class="required">Account Number</label>
                                <input type="text" id="accountNumber" name="accountNumber" aria-describedby="accountNumberError">
                                <div id="accountNumberError" class="error-message" role="alert">Please enter your account number</div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="scholarshipInfo" class="form-group" style="display: none;">
                        <label for="scholarshipName" class="required">Scholarship Name</label>
                        <input type="text" id="scholarshipName" name="scholarshipName" aria-describedby="scholarshipNameError">
                        <div id="scholarshipNameError" class="error-message" role="alert">Please enter your scholarship name</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Billing Address</label>
                        <div class="form-check">
                            <label class="checkbox-container">
                                <input type="checkbox" id="sameAddress" name="sameAddress" checked>
                                <span class="checkmark"></span>
                                Same as contact address
                            </label>
                        </div>
                    </div>
                    
                    <div id="billingAddressSection" style="display: none;">
                        <div class="form-group">
                            <label for="billingAddress" class="required">Address</label>
                            <textarea id="billingAddress" name="billingAddress" rows="3" aria-describedby="billingAddressError"></textarea>
                            <div id="billingAddressError" class="error-message" role="alert">Please enter your billing address</div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="billingCity" class="required">City</label>
                                    <input type="text" id="billingCity" name="billingCity" aria-describedby="billingCityError">
                                    <div id="billingCityError" class="error-message" role="alert">Please enter your city</div>
                                </div>
                            </div>
                            
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="billingState" class="required">State/Province</label>
                                    <input type="text" id="billingState" name="billingState" aria-describedby="billingStateError">
                                    <div id="billingStateError" class="error-message" role="alert">Please enter your state/province</div>
                                </div>
                            </div>
                            
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="billingZipCode" class="required">Zip/Postal Code</label>
                                    <input type="text" id="billingZipCode" name="billingZipCode" aria-describedby="billingZipCodeError">
                                    <div id="billingZipCodeError" class="error-message" role="alert">Please enter a valid zip/postal code</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- <div class="button-container">
                        <button type="button" class="btn-prev" data-prev="3">Previous: Academic Information</button>
                        <button type="button" class="btn-next" data-next="5">Next: Required Documents</button>
                    </div> -->
                </div>
                
                <!-- 5. Required Documents -->
                <div class="section" data-section="5">
                    <div class="section-title">🔹 5. Required Documents</div>
                    
                   
                    <div class="form-group">
                        <label for="transcripts" class="required">Academic Transcripts</label>
                        <div class="file-upload-container">
                            <div class="file-upload-text">Click to upload or drag and drop</div>
                            <small>Maximum file size: 10MB. Accepted formats: PDF, JPG, PNG</small>
                            <input type="file" id="transcripts" name="transcripts" accept=".pdf,.jpg,.jpeg,.png" required aria-describedby="transcriptsError">
                            <div id="transcriptsError" class="error-message" role="alert">Please upload your academic transcripts</div>
                        </div>
                        <div class="file-preview" id="transcriptsPreview">
                            <img src="" alt="Transcripts preview">
                            <div class="file-name"></div>
                            <button type="button" class="file-remove" aria-label="Remove file">×</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="idProof" class="required">ID Proof / Passport</label>
                        <div class="file-upload-container">
                            <div class="file-upload-text">Click to upload or drag and drop</div>
                            <small>Maximum file size: 5MB. Accepted formats: PDF, JPG, PNG</small>
                            <input type="file" id="idProof" name="idProof" accept=".pdf,.jpg,.jpeg,.png" required aria-describedby="idProofError">
                            <div id="idProofError" class="error-message" role="alert">Please upload your ID proof/passport</div>
                        </div>
                        <div class="file-preview" id="idProofPreview">
                            <img src="" alt="ID proof preview">
                            <div class="file-name"></div>
                            <button type="button" class="file-remove" aria-label="Remove file">×</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="addmisionProof">Proof of addmision (if applicable)</label>
                        <div class="file-upload-container">
                            <div class="file-upload-text">Click to upload or drag and drop</div>
                            <small>Maximum file size: 5MB. Accepted formats: PDF, JPG, PNG</small>
                            <input type="file" id="addmisionProof" name="addmisionProof" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="file-preview" id="addmisionProofPreview">
                            <img src="" alt="addmision proof preview">
                            <div class="file-name"></div>
                            <button type="button" class="file-remove" aria-label="Remove file">×</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="additionalDocs">Additional Documents (if any)</label>
                        <div class="file-upload-container">
                            <div class="file-upload-text">Click to upload or drag and drop</div>
                            <small>Maximum file size: 10MB. Accepted formats: PDF, DOC, DOCX, JPG, PNG</small>
                            <input type="file" id="additionalDocs" name="additionalDocs" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                        </div>
                        <div class="file-preview" id="additionalDocsPreview">
                            <div class="file-name"></div>
                            <button type="button" class="file-remove" aria-label="Remove file">×</button>
                        </div>
                    </div>
                    
                    <!-- <div class="button-container">
                        <button type="button" class="btn-prev" data-prev="4">Previous: Payment Information</button>
                        <button type="button" class="btn-next" data-next="6">Next: Review & Submit</button>
                    </div> -->
                </div>
                
                <!-- 6. Review & Submit -->
                <div class="section" data-section="6">
                    <div class="section-title">🔹 6. Review & Submit</div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <label class="checkbox-container required">
                                <input type="checkbox" id="terms_agreed" name="terms_agreed" required>
                                <span class="checkmark"></span>
                                I confirm that all information provided is accurate and complete.
                            </label>
                            <div id="termsAgreeError" class="error-message">You must agree to the terms to proceed</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <label class="checkbox-container required">
                                <input type="checkbox" id="policy_agreed" name="policy_agreed" required>
                                <span class="checkmark"></span>
                                I have read and agree to the privacy policy and terms of service.
                            </label>
                            <div id="policyAgreeError" class="error-message">You must agree to the policy to proceed</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <label class="checkbox-container">
                                <input type="checkbox" id="marketing_opt_in" name="marketing_opt_in">
                                <span class="checkmark"></span>
                                I would like to receive updates about programs, events, and opportunities (optional).
                            </label>
                        </div>
                    </div>
                    
                    <div class="button-container final-step">
                        <button type="button" class="btn-prev" data-prev="5">Previous: Required Documents</button>
                        <button type="submit" id="submitButton">Submit Registration</button>
                        <button type="reset" id="resetButton">Clear Form</button>
                    </div>
                </div>
                
                <!--  7. Confirmation (hidden by default) 
                <div class="section" data-section="7" style="display: none;">
                    <div class="section-title">🔹 Registration Complete</div>
                    
                    <div class="confirmation-message">
                        <div class="confirmation-icon">✓</div>
                        <h2>Registration Submitted Successfully!</h2>
                        <p>Thank you for completing the registration form. Your student ID will be generated and sent to your email shortly.</p>
                        
                        <div class="student-id-display">
                            <h3>Your Student ID:</h3>
                            <div class="student-id-value" id="generatedStudentId">Loading...</div>
                            <small>Please save this number for future reference</small>
                        </div>
                        
                       
                    </div>
                </div>  -->
            </form>
        </div>
    </div>
    

    
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission handler
    const form = document.getElementById('studentRegistration');
    form.addEventListener('submit', function(e) {
        // Form will submit normally to submit_student.php
    });

    // Payment method selection handler
    document.getElementById('paymentMethod').addEventListener('change', function() {
        const method = this.value;
        
        // Hide all payment info sections
        document.getElementById('creditCardInfo').style.display = 'none';
        document.getElementById('cardDetails').style.display = 'none';
        document.getElementById('bankInfo').style.display = 'none';
        document.getElementById('scholarshipInfo').style.display = 'none';
        
        // Show relevant section based on selection
        if (method === 'credit' || method === 'debit') {
            document.getElementById('creditCardInfo').style.display = 'flex';
            document.getElementById('cardDetails').style.display = 'flex';
        } else if (method === 'bank') {
            document.getElementById('bankInfo').style.display = 'flex';
        } else if (method === 'scholarship') {
            document.getElementById('scholarshipInfo').style.display = 'block';
        }
    });

    // Billing address toggle handler
    document.getElementById('sameAddress').addEventListener('change', function() {
        document.getElementById('billingAddressSection').style.display = 
            this.checked ? 'none' : 'block';
    });

    // Make all sections visible on page load
    document.querySelectorAll('.section').forEach(section => {
        section.style.display = 'block';
    });
});
</script>
    
</body>
</html>