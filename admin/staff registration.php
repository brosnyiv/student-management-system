<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration Portal</title>
    <link rel="stylesheet" href="satff register.css">
    
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
            
            <form id="staffRegistrationForm" action="submit_staff.php" method="POST">
                <!-- Staff Type Hidden Field -->
                <input type="hidden" id="staffType" name="staffType" value="">
                
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
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="required">Password</label>
                            <input type="password" id="password" name="password" required>
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
                            <label for="fullName" class="required">Full Name (as per ID)</label>
                            <input type="text" id="fullName" name="fullName" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="dateOfBirth" class="required">Date of Birth</label>
                            <input type="date" id="dateOfBirth" name="dateOfBirth" required>
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
                            <label for="maritalStatus">Marital Status</label>
                            <select id="maritalStatus" name="maritalStatus">
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
                            <label for="nationalId" class="required">National ID/Passport Number</label>
                            <input type="text" id="nationalId" name="nationalId" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="profilePhoto" class="required">Profile Photo</label>
                            <div class="image-upload" id="profilePhotoUpload">
                                <div class="upload-icon">📷</div>
                                <div>Click to upload photo</div>
                                <div class="help-text">JPEG or PNG, max 2MB</div>
                                <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*" style="display: none;" required>
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
                            <label for="phoneNumber" class="required">Phone Number</label>
                            <input type="tel" id="phoneNumber" name="phoneNumber" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="personalEmail">Personal Email Address</label>
                            <input type="email" id="personalEmail" name="personalEmail">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="residentialAddress" class="required">Residential Address</label>
                            <textarea id="residentialAddress" name="residentialAddress" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="emergencyContactName" class="required">Emergency Contact Person Name</label>
                            <input type="text" id="emergencyContactName" name="emergencyContactName" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="emergencyContactPhone" class="required">Emergency Contact Phone</label>
                            <input type="tel" id="emergencyContactPhone" name="emergencyContactPhone" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="emergencyContactRelationship" class="required">Relationship to Emergency Contact</label>
                            <input type="text" id="emergencyContactRelationship" name="emergencyContactRelationship" required>
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
                                    <input type="text" id="degree1" name="qualifications[0][degree]" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="institution1" class="required">Institution</label>
                                    <input type="text" id="institution1" name="qualifications[0][institution]" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="major1" class="required">Major/Area of Specialization</label>
                                    <input type="text" id="major1" name="qualifications[0][major]" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="gradYear1" class="required">Year of Graduation</label>
                                    <input type="number" id="gradYear1" name="qualifications[0][gradYear]" min="1950" max="2030" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group-full">
                                    <label for="certificateUpload1" class="required">Upload Certificate</label>
                                    <div class="image-upload" id="certificateUploadWrapper1">
                                        <div class="upload-icon">📄</div>
                                        <div>Click to upload certificate</div>
                                        <div class="help-text">PDF, JPEG or PNG, max 5MB</div>
                                        <input type="file" id="certificateUpload1" name="qualifications[0][certificate]" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" required>
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
                            <label for="staffId">Staff ID/Employee Number</label>
                            <input type="text" id="staffId" name="staffId">
                            <div class="help-text">If already assigned</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="department" class="required">Department</label>
                            <select id="department" name="department" required>
                                <option value="">Select Department</option>
                                <option value="computer-science">Computer Science</option>
                                <option value="mathematics">Mathematics</option>
                                <option value="physics">Physics</option>
                                <option value="chemistry">Chemistry</option>
                                <option value="biology">Biology</option>
                                <option value="languages">Languages</option>
                                <option value="social-studies">Social Studies</option>
                                <option value="physical-education">Physical Education</option>
                                <option value="arts">Arts</option>
                                <option value="music">Music</option>
                                <option value="admin">Administration</option>
                                <option value="finance">Finance</option>
                                <option value="it">IT Support</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="security">Security</option>
                                <option value="other">Other</option>
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
                            <label for="hireDate" class="required">Date of Hire</label>
                            <input type="date" id="hireDate" name="hireDate" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="employmentType" class="required">Employment Type</label>
                            <select id="employmentType" name="employmentType" required>
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
                            <label for="assignedCourses" class="required">Assigned Course Units</label>
                            <textarea id="assignedCourses" name="assignedCourses" rows="3" placeholder="List your assigned courses, separated by commas" required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="semesterLoad" class="required">Semester Load (Hours)</label>
                            <input type="number" id="semesterLoad" name="semesterLoad" min="1" max="40" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="officeHours" class="required">Office Hours</label>
                            <input type="text" id="officeHours" name="officeHours" placeholder="e.g., Mon 10-12, Wed 2-4" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="availableTimes" class="required">Available Teaching Days/Times</label>
                            <textarea id="availableTimes" name="availableTimes" rows="3" placeholder="Specify your availability for teaching throughout the week" required></textarea>
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
                            <label for="workingDays" class="required">Working Days</label>
                            <select id="workingDays" name="workingDays" required>
                                <option value="">Select Working Days</option>
                                <option value="monday-friday">Monday to Friday</option>
                                <option value="monday-saturday">Monday to Saturday</option>
                                <option value="rotational">Rotational Shift</option>
                                <option value="custom">Custom Schedule</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="workingHours" class="required">Working Hours</label>
                            <input type="text" id="workingHours" name="workingHours" placeholder="e.g., 8:00 AM - 5:00 PM" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="schedulNotes">Schedule Notes</label>
                            <textarea id="schedulNotes" name="schedulNotes" rows="3" placeholder="Any additional information about your working schedule"></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- System Access & Permissions Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">🔐</div>
                        <div class="section-title">6. System Access & Permissions</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="portalRole" class="required">Portal Role</label>
                            <select id="portalRole" name="portalRole" required>
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
                            <label for="assignedClasses">Assigned Classes/Rooms</label>
                            <input type="text" id="assignedClasses" name="assignedClasses" placeholder="e.g., Room 101, Lab 3">
                        </div>
                        
                        <div class="form-group non-teaching-only-field">
                            <label for="workArea">Work Area/Office</label>
                            <input type="text" id="workArea" name="workArea" placeholder="e.g., Admin Block, Finance Office">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="accessLevel">System Access Level</label>
                            <select id="accessLevel" name="accessLevel">
                                <option value="basic">Basic (View only)</option>
                                <option value="standard" selected>Standard (View & Edit own data)</option>
                                <option value="advanced">Advanced (Department level access)</option>
                                <option value="admin">Administrative (Full system access)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="accessStart">Access Start Date</label>
                            <input type="date" id="accessStart" name="accessStart">
                        </div>
                    </div>
                </div>
                
                <!-- Payroll & Bank Details Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">💰</div>
                        <div class="section-title">7. Payroll & Bank Details</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bankName" class="required">Bank Name</label>
                            <input type="text" id="bankName" name="bankName" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="accountNumber" class="required">Account Number</label>
                            <input type="text" id="accountNumber" name="accountNumber" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="taxId" class="required">National Tax ID/Social Security</label>
                            <input type="text" id="taxId" name="taxId" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tinNumber" class="required">TIN Number</label>
                            <input type="text" id="tinNumber" name="tinNumber" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="salaryScale" class="required">Salary Scale/Grade</label>
                            <input type="text" id="salaryScale" name="salaryScale" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="paymentFrequency" class="required">Payment Frequency</label>
                            <select id="paymentFrequency" name="paymentFrequency" required>
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
                                    <label for="idPassportScan" class="required">ID/Passport Scan</label>
                                    <div class="image-upload" id="idPassportScanUpload">
                                        <div class="upload-icon">📄</div>
                                        <div>Click to upload document</div>
                                        <div class="help-text">PDF, JPEG or PNG, max 5MB</div>
                                        <input type="file" id="idPassportScan" name="documents[0][idScan]" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="documentType" class="required">Document Type</label>
                                    <select id="documentType" name="documents[0][type]" required>
                                        <option value="id">National ID</option>
                                        <option value="passport">Passport</option>
                                        <option value="residencePermit">Residence Permit</option>
                                        <option value="workVisa">Work Visa</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="documentNumber" class="required">Document Number</label>
                                    <input type="text" id="documentNumber" name="documents[0][number]" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="documentExpiry">Expiry Date (if applicable)</label>
                                    <input type="date" id="documentExpiry" name="documents[0][expiry]">
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
                                <input type="checkbox" id="termsConsent" name="termsConsent" required>
                                <label for="termsConsent">
                                    I hereby declare that the information provided in this form is true and correct to the best of my knowledge. I understand that providing false information may result in termination of employment.
                                </label>
                            </div>
                            
                            <div class="checkbox-row">
                                <input type="checkbox" id="dataConsent" name="dataConsent" required>
                                <label for="dataConsent">
                                    I consent to the collection, processing, and storage of my personal data for employment and administrative purposes in accordance with the school's data privacy policy.
                                </label>
                            </div>
                            
                            <div class="checkbox-row">
                                <input type="checkbox" id="updateConsent" name="updateConsent" required>
                                <label for="updateConsent">
                                    I agree to update my information whenever there are changes to ensure the accuracy of records maintained by the school.
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="digitalSignature" class="required">Digital Signature</label>
                            <input type="text" id="digitalSignature" name="digitalSignature" placeholder="Type your full name as signature" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="signatureDate" class="required">Date</label>
                            <input type="date" id="signatureDate" name="signatureDate" required>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="action-row">
                    <button type="button" class="btn btn-secondary" id="saveAsDraft">Save as Draft</button>
                    <div>
                        <button type="button" class="btn btn-primary" id="prevStep" style="margin-right: 10px;">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextStep">Next</button>
                        <button type="submit" class="btn btn-success" id="submitForm" style="display: none;">Submit Registration</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
      // JavaScript functionality for the form
document.addEventListener('DOMContentLoaded', function() {
    // Staff Type Selection
    const teachingStaffCard = document.getElementById('teachingStaff');
    const nonTeachingStaffCard = document.getElementById('nonTeachingStaff');
    const staffTypeContainer = document.getElementById('staffTypeContainer');
    const registrationFormContainer = document.getElementById('registrationFormContainer');
    const staffTypeInput = document.getElementById('staffType');
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
    const profilePhoto = document.getElementById('profilePhoto');

    profilePhotoUpload.addEventListener('click', function() {
        profilePhoto.click();
    });

    profilePhoto.addEventListener('change', function() {
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

    // Handle certificate uploads
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

    // Add employment field
    const employmentFieldsContainer = document.getElementById('employment-additional-fields');
    const addEmploymentFieldBtn = document.getElementById('addEmploymentField');

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

    // Add document upload
    const documentsContainer = document.getElementById('documents-container');
    const addDocumentBtn = document.getElementById('addDocument');

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

// Form submission
const staffRegistrationForm = document.getElementById('staffRegistrationForm');
const saveAsDraftBtn = document.getElementById('saveAsDraft');

staffRegistrationForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate form before submission
    const requiredFields = document.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value) {
            field.classList.add('invalid');
            isValid = false;
        } else {
            field.classList.remove('invalid');
        }
    });
    
    if (isValid) {
        // In a real application, you would collect form data and submit via AJAX
        const formData = new FormData(this);
        
        // For demo purposes, show success message
        alert('Registration submitted successfully!');
        
        // Redirect to confirmation page (in a real app)
        // window.location.href = 'confirmation.html';
    } else {
        alert('Please fill in all required fields before submitting.');
    }
});

    // Save as draft
    saveAsDraftBtn.addEventListener('click', function() {
        // In a real application, you would save the current state to localStorage or server
        alert('Your registration has been saved as draft. You can continue later.');
    });

    // Remove first qualification row (if needed)
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

    confirmPassword.addEventListener('input', function() {
        if (password.value !== this.value) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });

// Initialize date fields with current date
const today = new Date().toISOString().split('T')[0];
document.getElementById('signatureDate').value = today;
});

// Form submission
const staffRegistrationForm = document.getElementById('staffRegistrationForm');
const saveAsDraftBtn = document.getElementById('saveAsDraft');

staffRegistrationForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
   // Inside your DOMContentLoaded event listener
// Keep this event listener and remove the duplicate one outside the DOMContentLoaded
staffRegistrationForm.addEventListener('submit', function(e) {
    e.preventDefault();
    console.log("Form submission triggered");
    
    // Validate form before submission
    const requiredFields = document.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value) {
            field.classList.add('invalid');
            isValid = false;
            console.log("Invalid field:", field.id);
        } else {
            field.classList.remove('invalid');
        }
    });
    
    if (isValid) {
        console.log("Form is valid, preparing to submit");
        // Collect form data
        const formData = new FormData(this);
        
        // Add files to form data
        const profilePhoto = document.getElementById('profilePhoto');
        if (profilePhoto.files.length > 0) {
            formData.append('profilePhoto', profilePhoto.files[0]);
        }
        
        // Log formData keys for debugging
        for (let key of formData.keys()) {
            console.log("FormData includes key:", key);
        }
        
        // Submit via AJAX with better error handling
        fetch('submit_staff.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log("Response status:", response.status);
            return response.json();
        })
        .then(data => {
            console.log("Server response:", data);
            if (data.success) {
                alert('Registration submitted successfully! Staff ID: ' + data.staff_id);
                // Redirect to confirmation page or clear form
                // window.location.href = 'confirmation.html?staff_id=' + data.staff_id;
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the form.');
        });
    } else {
        alert('Please fill in all required fields before submitting.');
    }
});


    
    if (isValid) {
        // Collect form data
        const formData = new FormData(this);
        
        // Add files to form data
        const profilePhoto = document.getElementById('profilePhoto');
        if (profilePhoto.files.length > 0) {
            formData.append('profilePhoto', profilePhoto.files[0]);
        }
        
        // Add qualification certificates
        const qualificationCertificates = document.querySelectorAll('input[name^="qualifications["][name$="][certificate]"]');
        qualificationCertificates.forEach((cert, index) => {
            if (cert.files.length > 0) {
                formData.append(`qualifications[${index}][certificate]`, cert.files[0]);
            }
        });
        
        // Add document files
        const documentFiles = document.querySelectorAll('input[name^="documents["][name$="][file]"]');
        documentFiles.forEach((doc, index) => {
            if (doc.files.length > 0) {
                formData.append(`documents[${index}][file]`, doc.files[0]);
            }
        });
        
        // Submit via AJAX
        fetch('submit_staff.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Registration submitted successfully! Staff ID: ' + data.staff_id);
                // Redirect to confirmation page or clear form
                // window.location.href = 'confirmation.html?staff_id=' + data.staff_id;
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the form.');
        });
    } else {
        alert('Please fill in all required fields before submitting.');
    }
});
</script>
</body>
</html>