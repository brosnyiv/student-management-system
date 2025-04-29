<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Non-Teaching Staff Registration Portal</title>
    <link rel="stylesheet" href="staffreg.css">
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
            
            <div class="form-steps">
                <div class="step active">1</div>
                <div class="step">2</div>
                <div class="step">3</div>
                <div class="step">4</div>
            </div>
            
            <form id="staffRegistrationForm" action="" method="POST">
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
                                <?php foreach($roles as $r): ?>
                                    <option value="<?= $r['department_id']; ?>"><?= $r['department']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="designation" class="required">Designation</label>
                            <select id="designation" name="designation" required>
                                <option value="">Select Designation</option>
                                <!-- Non-Teaching Staff Options -->
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
                    
                    <div id="employment-additional-fields"></div>
                    
                    <button type="button" class="add-row-btn" id="addEmploymentField">
                        <i>+</i> Add New Field
                    </button>
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