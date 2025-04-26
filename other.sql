-- Create database with proper character set and collation
CREATE DATABASE IF NOT EXISTS monaco_student_registration 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE monaco_student_registration;

-- ======================
-- CORE TABLES
-- ======================

-- Roles table (needs to be first as it's referenced by users)
CREATE TABLE IF NOT EXISTS roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL,
    role_description TEXT,
    is_teaching_role BOOLEAN DEFAULT FALSE
);

-- Users table (for login credentials)
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    access_level ENUM('basic', 'standard', 'advanced', 'admin') DEFAULT 'standard',
    access_start_date DATE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP,
    last_message_check TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

-- Faculties table
CREATE TABLE IF NOT EXISTS faculties (
    faculty_id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_name VARCHAR(100) NOT NULL,
    faculty_code VARCHAR(20) UNIQUE NOT NULL,
    faculty_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Departments table (without department_head initially)
CREATE TABLE IF NOT EXISTS departments (
    department_id INT PRIMARY KEY AUTO_INCREMENT,
    department_name VARCHAR(100) NOT NULL,
    department_code VARCHAR(20) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample department data
INSERT INTO departments (department_id, department_name, department_code) VALUES 
(1, 'Information Technology', 'it'),
(2, 'Business', 'business'),
(3, 'Design', 'design'),
(4, 'Marketing', 'marketing')
ON DUPLICATE KEY UPDATE department_name = VALUES(department_name), department_code = VALUES(department_code);

-- Staff table (common info for all staff)
CREATE TABLE IF NOT EXISTS staff (
    staff_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE,
    staff_type ENUM('teaching', 'non-teaching') NOT NULL,
    staff_number VARCHAR(20) UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female', 'other', 'prefer-not-to-say') NOT NULL,
    marital_status ENUM('single', 'married', 'divorced', 'widowed', 'other'),
    national_id VARCHAR(50) NOT NULL,
    profile_photo_path VARCHAR(255),
    phone_number VARCHAR(20) NOT NULL,
    personal_email VARCHAR(100),
    residential_address TEXT NOT NULL,
    department_id INT NOT NULL,
    designation VARCHAR(100) NOT NULL,
    hire_date DATE NOT NULL,
    employment_type ENUM('full-time', 'part-time', 'visiting', 'contract', 'temporary') NOT NULL,
    supervisor VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

-- Now we can alter the departments table to add department_head
ALTER TABLE departments 
ADD COLUMN IF NOT EXISTS department_head INT NULL,
ADD CONSTRAINT fk_department_head FOREIGN KEY (department_head) REFERENCES staff(staff_id);

-- Teaching staff details
CREATE TABLE IF NOT EXISTS teaching_staff (
    teaching_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL UNIQUE,
    title VARCHAR(20) NOT NULL,
    assigned_courses TEXT NOT NULL,
    semester_load INT NOT NULL,
    office_hours VARCHAR(100) NOT NULL,
    available_times TEXT NOT NULL,
    assigned_classes VARCHAR(255),
    is_faculty_leader BOOLEAN DEFAULT FALSE,
    academic_rank VARCHAR(50),
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Non-teaching staff details
CREATE TABLE IF NOT EXISTS non_teaching_staff (
    non_teaching_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL UNIQUE,
    working_days ENUM('monday-friday', 'monday-saturday', 'rotational', 'custom') NOT NULL,
    working_hours VARCHAR(100) NOT NULL,
    schedule_notes TEXT,
    work_area VARCHAR(255),
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Map departments to faculties
CREATE TABLE IF NOT EXISTS department_faculty (
    department_id INT NOT NULL,
    faculty_id INT NOT NULL,
    PRIMARY KEY (department_id, faculty_id),
    FOREIGN KEY (department_id) REFERENCES departments(department_id),
    FOREIGN KEY (faculty_id) REFERENCES faculties(faculty_id)
);

-- Faculty leaders table
CREATE TABLE IF NOT EXISTS faculty_leaders (
    faculty_leader_id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_id INT NOT NULL,
    staff_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    end_date DATE,
    is_current BOOLEAN DEFAULT TRUE,
    leadership_role VARCHAR(100) NOT NULL,
    responsibilities TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculties(faculty_id),
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id),
    CONSTRAINT faculty_current_leader_unique UNIQUE (faculty_id, is_current, leadership_role)
);

-- Emergency contacts for staff
CREATE TABLE IF NOT EXISTS emergency_contacts (
    contact_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    contact_name VARCHAR(100) NOT NULL,
    contact_phone VARCHAR(20) NOT NULL,
    relationship VARCHAR(50) NOT NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Academic qualifications
CREATE TABLE IF NOT EXISTS qualifications (
    qualification_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    degree VARCHAR(100) NOT NULL,
    institution VARCHAR(100) NOT NULL,
    major VARCHAR(100) NOT NULL,
    graduation_year INT NOT NULL,
    certificate_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Bank details
CREATE TABLE IF NOT EXISTS bank_details (
    bank_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL UNIQUE,
    bank_name VARCHAR(100) NOT NULL,
    account_number VARCHAR(50) NOT NULL,
    tax_id VARCHAR(50) NOT NULL,
    tin_number VARCHAR(50) NOT NULL,
    salary_scale VARCHAR(50) NOT NULL,
    payment_frequency ENUM('monthly', 'bi-weekly', 'weekly', 'quarterly') NOT NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Document uploads for staff
CREATE TABLE IF NOT EXISTS staff_documents (
    document_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    document_type VARCHAR(50) NOT NULL,
    document_path VARCHAR(255) NOT NULL,
    document_number VARCHAR(100),
    document_description TEXT,
    expiry_date DATE,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Leadership documents
CREATE TABLE IF NOT EXISTS leadership_documents (
    leadership_doc_id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_leader_id INT NOT NULL,
    document_type VARCHAR(50) NOT NULL,
    document_title VARCHAR(100) NOT NULL,
    document_path VARCHAR(255) NOT NULL,
    issue_date DATE NOT NULL,
    expiry_date DATE,
    notes TEXT,
    FOREIGN KEY (faculty_leader_id) REFERENCES faculty_leaders(faculty_leader_id)
);

-- Custom employment fields
CREATE TABLE IF NOT EXISTS employment_custom_fields (
    field_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    field_name VARCHAR(100) NOT NULL,
    field_value TEXT,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Consent and declarations
CREATE TABLE IF NOT EXISTS staff_consents (
    consent_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL UNIQUE,
    terms_consent BOOLEAN NOT NULL,
    data_consent BOOLEAN NOT NULL,
    update_consent BOOLEAN NOT NULL,
    digital_signature VARCHAR(100) NOT NULL,
    signature_date DATE NOT NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Form drafts (for save as draft functionality)
CREATE TABLE IF NOT EXISTS form_drafts (
    draft_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_type ENUM('teaching', 'non-teaching') NOT NULL,
    form_data JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ======================
-- COURSE-RELATED TABLES
-- ======================

-- Course levels table
CREATE TABLE IF NOT EXISTS course_levels (
    level_id VARCHAR(20) PRIMARY KEY,
    level_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert course levels
INSERT INTO course_levels (level_id, level_name) VALUES
('certificate', 'Certificate'),
('diploma', 'Diploma')
ON DUPLICATE KEY UPDATE level_name = VALUES(level_name);

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    level_id VARCHAR(20) NOT NULL,
    department_id INT NOT NULL,
    duration INT NOT NULL COMMENT 'Duration in years',
    max_capacity INT NOT NULL,
    faculty_leader_id INT NOT NULL,
    status ENUM('active', 'upcoming', 'archived') DEFAULT 'upcoming',
    description TEXT,
    course_fee DECIMAL(10, 2) NOT NULL,
    start_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (level_id) REFERENCES course_levels(level_id),
    FOREIGN KEY (department_id) REFERENCES departments(department_id),
    FOREIGN KEY (faculty_leader_id) REFERENCES teaching_staff(teaching_id)
);

-- Academic years table
CREATE TABLE IF NOT EXISTS academic_years (
    academic_year_id INT PRIMARY KEY AUTO_INCREMENT,
    year_number INT NOT NULL,
    course_id INT NOT NULL,
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    UNIQUE KEY (year_number, course_id)
);

-- Semesters table
CREATE TABLE IF NOT EXISTS semesters (
    semester_id INT PRIMARY KEY AUTO_INCREMENT,
    academic_year_id INT NOT NULL,
    semester_number INT NOT NULL COMMENT '1 or 2 for first or second semester',
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(academic_year_id) ON DELETE CASCADE,
    UNIQUE KEY (academic_year_id, semester_number)
);

-- Course units table
CREATE TABLE IF NOT EXISTS course_units (
    unit_id INT PRIMARY KEY AUTO_INCREMENT,
    unit_name VARCHAR(100) NOT NULL,
    unit_code VARCHAR(20) NOT NULL,
    semester_id INT NOT NULL,
    instructor_id INT NOT NULL,
    credits INT NOT NULL DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (semester_id) REFERENCES semesters(semester_id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES teaching_staff(teaching_id),
    UNIQUE KEY (unit_code, semester_id)
);

-- Rooms table (for class sessions)
CREATE TABLE IF NOT EXISTS rooms (
    room_id INT PRIMARY KEY AUTO_INCREMENT,
    room_name VARCHAR(50) NOT NULL,
    room_code VARCHAR(20) UNIQUE NOT NULL,
    capacity INT NOT NULL,
    room_type ENUM('classroom', 'lab', 'lecture_hall', 'meeting_room') NOT NULL,
    building VARCHAR(50) NOT NULL,
    floor INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Class sessions table
CREATE TABLE IF NOT EXISTS class_sessions (
    session_id INT PRIMARY KEY AUTO_INCREMENT,
    course_unit_id INT NOT NULL,
    room_id INT NOT NULL,
    day_of_week ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('scheduled', 'completed', 'canceled', 'rescheduled') DEFAULT 'scheduled',
    cancellation_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_unit_id) REFERENCES course_units(unit_id),
    FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);

-- ======================
-- STUDENT-RELATED TABLES
-- ======================

-- Create sequence table with better locking mechanism
CREATE TABLE IF NOT EXISTS student_id_sequence (
    sequence_name VARCHAR(50) PRIMARY KEY,
    department_code VARCHAR(3),
    next_val BIGINT NOT NULL DEFAULT 1,
    increment_by INT NOT NULL DEFAULT 1,
    max_val BIGINT NOT NULL DEFAULT 999999,
    cycle BOOLEAN NOT NULL DEFAULT FALSE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE INDEX uk_dept_code (department_code)
) ENGINE=InnoDB;

-- Initialize sequence with starting value
INSERT INTO student_id_sequence (sequence_name, next_val) VALUES ('student_id', 1)
ON DUPLICATE KEY UPDATE next_val = next_val;

-- Insert department codes
INSERT INTO student_id_sequence (sequence_name, department_code, next_val) VALUES 
('BUS', 'BUS', 1),
('ENG', 'ENG', 1),
('MED', 'MED', 1),
('LAW', 'LAW', 1),
('ART', 'ART', 1)
ON DUPLICATE KEY UPDATE next_val = next_val;

-- Students table with improved constraints
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    surname VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other', 'Prefer not to say') NOT NULL,
    profile_photo_path VARCHAR(255) NOT NULL,
    nationality VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    status ENUM('Active', 'Inactive', 'Suspended', 'Graduated') DEFAULT 'Active',
    INDEX idx_students_name (surname, first_name),
    INDEX idx_students_status (status)
) COMMENT='Core student information table';

-- Create trigger to validate age instead of using CHECK constraint
DELIMITER //
CREATE TRIGGER IF NOT EXISTS before_student_insert
BEFORE INSERT ON students
FOR EACH ROW
BEGIN
    IF NEW.date_of_birth > DATE_SUB(CURDATE(), INTERVAL 17 YEAR) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Student must be at least 17 years old';
    END IF;
END //

CREATE TRIGGER IF NOT EXISTS before_student_update
BEFORE UPDATE ON students
FOR EACH ROW
BEGIN
    IF NEW.date_of_birth > DATE_SUB(CURDATE(), INTERVAL 17 YEAR) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Student must be at least 17 years old';
    END IF;
END //
DELIMITER ;

-- Enhanced contact details for students with validation
CREATE TABLE IF NOT EXISTS contact_details (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    alt_phone VARCHAR(20),
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state_province VARCHAR(50) NOT NULL,
    zip_postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(50) NOT NULL,
    is_primary BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    UNIQUE KEY uk_student_email (student_id, email),
    INDEX idx_contact_email (email)
) COMMENT='Student contact information';

-- Create email validation trigger instead of CHECK constraint
DELIMITER //
CREATE TRIGGER IF NOT EXISTS before_contact_insert
BEFORE INSERT ON contact_details
FOR EACH ROW
BEGIN
    IF NEW.email NOT REGEXP '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}$' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Invalid email format';
    END IF;
END //

CREATE TRIGGER IF NOT EXISTS before_contact_update
BEFORE UPDATE ON contact_details
FOR EACH ROW
BEGIN
    IF NEW.email NOT REGEXP '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}$' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Invalid email format';
    END IF;
END //
DELIMITER ;

-- Improved emergency contacts table for students
CREATE TABLE IF NOT EXISTS student_emergency_contacts (
    emergency_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    contact_name VARCHAR(100) NOT NULL,
    relationship ENUM('Parent', 'Guardian', 'Sibling', 'Spouse', 'Other') NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    alt_phone_number VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    is_primary BOOLEAN DEFAULT TRUE,
    notes TEXT,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    INDEX idx_emergency_primary (student_id, is_primary)
) COMMENT='Emergency contact information for students';

-- Enhanced academic info with more fields
CREATE TABLE IF NOT EXISTS academic_info (
    academic_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    course_id INT NOT NULL,
    program_level ENUM('Certificate', 'Diploma', 'Bachelor', 'Master', 'PhD') NOT NULL,
    year_level INT NOT NULL,
    expected_start_date DATE NOT NULL,
    expected_end_date DATE,
    previous_institution VARCHAR(255) NOT NULL,
    previous_gpa DECIMAL(3,2),
    current_gpa DECIMAL(3,2),
    enrollment_status ENUM('Full-time', 'Part-time', 'Leave of Absence', 'Withdrawn') DEFAULT 'Full-time',
    advisor_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    INDEX idx_academic_course (course_id),
    INDEX idx_academic_status (enrollment_status)
) COMMENT='Academic information for students';

-- Create academic triggers for validations
DELIMITER //
CREATE TRIGGER IF NOT EXISTS before_academic_insert
BEFORE INSERT ON academic_info
FOR EACH ROW
BEGIN
    -- Check start date
    IF NEW.expected_start_date < CURDATE() THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Expected start date cannot be in the past';
    END IF;
    
    -- Check end date relative to start date
    IF NEW.expected_end_date < NEW.expected_start_date THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Expected end date cannot be before the start date';
    END IF;
    
    -- Validate GPA range
    IF NEW.previous_gpa IS NOT NULL AND (NEW.previous_gpa < 0 OR NEW.previous_gpa > 4.0) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'GPA must be between 0 and 4.0';
    END IF;
    
    -- Validate dates again (this seems redundant with the check above, but preserving the logic)
    IF NEW.expected_end_date IS NOT NULL AND NEW.expected_start_date >= NEW.expected_end_date THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'End date must be after start date';
    END IF;
END //
DELIMITER ;

-- Enrollments table (for tracking students enrolled in courses)
CREATE TABLE IF NOT EXISTS enrollments (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id VARCHAR(50) NOT NULL,
    course_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('active', 'completed', 'withdrawn', 'deferred') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    UNIQUE KEY (student_id, course_id)
);

-- Unit enrollments (for tracking individual unit enrollments)
CREATE TABLE IF NOT EXISTS unit_enrollments (
    unit_enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_id INT NOT NULL,
    unit_id INT NOT NULL,
    grade VARCHAR(2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(enrollment_id) ON DELETE CASCADE,
    FOREIGN KEY (unit_id) REFERENCES course_units(unit_id),
    UNIQUE KEY (enrollment_id, unit_id)
);

-- Class session attendance
CREATE TABLE IF NOT EXISTS class_session_attendance (
    attendance_id INT PRIMARY KEY AUTO_INCREMENT,
    session_id INT NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') NOT NULL,
    notes TEXT,
    FOREIGN KEY (session_id) REFERENCES class_sessions(session_id),
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Attendance records
CREATE TABLE IF NOT EXISTS attendance_records (
    record_id INT PRIMARY KEY AUTO_INCREMENT,
    date DATE NOT NULL,
    instructor_id INT NOT NULL,
    course_id INT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES staff(staff_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

-- Student attendance
CREATE TABLE IF NOT EXISTS student_attendance (
    attendance_id INT PRIMARY KEY AUTO_INCREMENT,
    record_id INT NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') NOT NULL,
    time_in TIME,
    notes TEXT,
    FOREIGN KEY (record_id) REFERENCES attendance_records(record_id),
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Secure payment information table
CREATE TABLE IF NOT EXISTS payment_info (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    payment_method ENUM('credit_card', 'bank_transfer', 'scholarship', 'other') NOT NULL,
    -- Credit/Debit card details (properly encrypted)
    card_name VARCHAR(255),
    card_number_encrypted VARBINARY(255),
    card_number_iv VARBINARY(16),
    expiry_date_encrypted VARBINARY(255),
    expiry_date_iv VARBINARY(16),
    cvv_encrypted VARBINARY(255),
    cvv_iv VARBINARY(16),
    -- Bank details (properly encrypted)
    bank_name VARCHAR(100),
    account_number_encrypted VARBINARY(255),
    account_number_iv VARBINARY(16),
    routing_number_encrypted VARBINARY(255),
    routing_number_iv VARBINARY(16),
    -- Scholarship details
    scholarship_name VARCHAR(255),
    scholarship_id VARCHAR(50),
    use_contact_address BOOLEAN NOT NULL DEFAULT TRUE,
    payment_status ENUM('Pending', 'Completed', 'Failed', 'Refunded') DEFAULT 'Pending',
    last_payment_date DATETIME,
    next_payment_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    INDEX idx_payment_status (payment_status),
    INDEX idx_payment_method (payment_method)
) COMMENT='Student payment information with proper encryption';

-- Payment receipts
CREATE TABLE IF NOT EXISTS payment_receipts (
    receipt_id INT PRIMARY KEY AUTO_INCREMENT,
    receipt_number VARCHAR(20) UNIQUE NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    course_id INT,
    payment_date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'bank_transfer', 'online') NOT NULL,
    payment_type ENUM('tuition', 'exam', 'registration', 'library', 'other') NOT NULL,
    description TEXT,
    processed_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (processed_by) REFERENCES staff(staff_id),
    INDEX idx_receipt_student (student_id),
    INDEX idx_receipt_date (payment_date)
) COMMENT='Payment receipts shown in recipt.html';

-- Payment method details
CREATE TABLE IF NOT EXISTS payment_method_details (
    detail_id INT PRIMARY KEY AUTO_INCREMENT,
    receipt_id INT NOT NULL,
    card_name VARCHAR(100),
    card_last_four VARCHAR(4),
    bank_name VARCHAR(100),
    transaction_reference VARCHAR(100),
    online_platform VARCHAR(50),
    FOREIGN KEY (receipt_id) REFERENCES payment_receipts(receipt_id) ON DELETE CASCADE
) COMMENT='Additional payment method details';

-- Billing addresses with better structure
CREATE TABLE IF NOT EXISTS billing_addresses (
    billing_id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id INT NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(50) NOT NULL,
    state_province VARCHAR(50) NOT NULL,
    zip_postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id) REFERENCES payment_info(payment_id) ON DELETE CASCADE,
    INDEX idx_billing_active (payment_id, is_active)
) COMMENT='Billing addresses for students';

-- Enhanced documents table for students with better tracking
CREATE TABLE IF NOT EXISTS student_documents (
    document_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    document_type ENUM('academic_transcript', 'id_proof', 'admission_proof', 'additional', 'financial', 'medical') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_size INT NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    verification_status ENUM('Pending', 'Verified', 'Rejected') DEFAULT 'Pending',
    verified_by INT,
    verification_date DATETIME,
    verification_notes TEXT,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expiry_date DATE,
    is_archived BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    INDEX idx_document_type (document_type),
    INDEX idx_verification_status (verification_status),
    INDEX idx_document_expiry (expiry_date)
) COMMENT='Student documents with verification tracking';

-- Enhanced consent records for students
CREATE TABLE IF NOT EXISTS consent_records (
    consent_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    terms_agreed BOOLEAN NOT NULL DEFAULT FALSE,
    terms_agreed_version VARCHAR(20) NOT NULL,
    terms_agreed_date DATETIME,
    policy_agreed BOOLEAN NOT NULL DEFAULT FALSE,
    policy_agreed_version VARCHAR(20) NOT NULL,
    policy_agreed_date DATETIME,
    marketing_opt_in BOOLEAN NOT NULL DEFAULT FALSE,
    data_sharing_consent BOOLEAN DEFAULT FALSE,
    emergency_contact_consent BOOLEAN DEFAULT TRUE,
    photo_release_consent BOOLEAN DEFAULT FALSE,
    digital_signature VARCHAR(255),
    signature_date DATETIME,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    INDEX idx_consent_dates (terms_agreed_date, policy_agreed_date)
) COMMENT='Detailed consent records with version tracking';

-- Enhanced registration status tracking
CREATE TABLE IF NOT EXISTS registration_status (
    status_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('draft', 'submitted', 'under_review', 'incomplete', 'approved', 'rejected', 'waitlisted') NOT NULL DEFAULT 'draft',
    admin_notes TEXT,
    reviewed_by INT,
    review_date DATETIME,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    next_action_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    INDEX idx_registration_status (status),
    INDEX idx_review_dates (review_date, next_action_date)
) COMMENT='Registration status with review tracking';

-- ======================
-- COMMUNICATION TABLES
-- ======================

-- Messages between users
CREATE TABLE IF NOT EXISTS messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message_content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(user_id),
    FOREIGN KEY (receiver_id) REFERENCES users(user_id),
    INDEX idx_message_conversation (sender_id, receiver_id),
    INDEX idx_message_timeline (created_at)
) COMMENT='Private messages between users';

-- Message attachments
CREATE TABLE IF NOT EXISTS message_attachments (
    attachment_id INT PRIMARY KEY AUTO_INCREMENT,
    message_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(100) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size INT NOT NULL,
    FOREIGN KEY (message_id) REFERENCES messages(message_id) ON DELETE CASCADE
) COMMENT='Attachments for messages';

-- Broadcast messages
CREATE TABLE IF NOT EXISTS broadcast_messages (
    broadcast_id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    recipient_group ENUM('all_students', 'all_staff', 'specific_course', 'specific_department') NOT NULL,
    recipient_ids JSON COMMENT 'Specific IDs if not sent to all',  
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_count INT DEFAULT 0,
    total_recipients INT NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES users(user_id)
) COMMENT='Broadcast messages shown in the messages.html interface';

-- Broadcast recipients
CREATE TABLE IF NOT EXISTS broadcast_recipients (
    recipient_id INT PRIMARY KEY AUTO_INCREMENT,
    broadcast_id INT NOT NULL,
    status ENUM('delivered', 'read', 'failed') DEFAULT 'delivered',
    read_at TIMESTAMP,
    FOREIGN KEY (broadcast_id) REFERENCES broadcast_messages(broadcast_id),
    FOREIGN KEY (recipient_id) REFERENCES users(user_id)
) COMMENT='Individual recipient status for broadcast messages';

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) COMMENT='User notifications shown in the messages.html interface';

-- Notification settings
CREATE TABLE IF NOT EXISTS notification_settings (
    setting_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    email_notifications BOOLEAN DEFAULT TRUE,
    sms_notifications BOOLEAN DEFAULT FALSE,
    push_notifications BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) COMMENT='User notification settings';

-- Notices
CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    source_office VARCHAR(100),
    category VARCHAR(100),
    post_date DATE,
    expiry_date DATE,
    urgent BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'published') DEFAULT 'draft',
    attachments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================
-- EVENT AND ATTENDANCE TABLES
-- ======================

-- Events
CREATE TABLE IF NOT EXISTS events (
    event_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    location VARCHAR(100) NOT NULL,
    organizer_id INT NOT NULL,
    event_type ENUM('workshop', 'seminar', 'masterclass', 'lecture', 'meeting', 'other') NOT NULL,
    is_recurring BOOLEAN DEFAULT FALSE,
    recurrence_pattern ENUM('daily', 'weekly', 'monthly'),
    recurrence_end_date DATE,
    max_participants INT,
    resources_needed TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES staff(staff_id)
);

-- Event participants
CREATE TABLE IF NOT EXISTS event_participants (
    participant_id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    participant_type ENUM('student', 'staff', 'external') NOT NULL,
    participant_student_id VARCHAR(50),
    participant_staff_id INT,
    external_name VARCHAR(100),
    external_email VARCHAR(100),
    attended BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (event_id) REFERENCES events(event_id),
    FOREIGN KEY (participant_student_id) REFERENCES students(student_id),
    FOREIGN KEY (participant_staff_id) REFERENCES staff(staff_id)
);

-- Staff attendance
CREATE TABLE IF NOT EXISTS staff_attendance (
    attendance_id INT PRIMARY KEY AUTO_INCREMENT,
    date DATE NOT NULL,
    department_id INT NOT NULL,
    supervisor_id INT NOT NULL,
    additional_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(department_id),
    FOREIGN KEY (supervisor_id) REFERENCES staff(staff_id)
);

-- Staff attendance records
CREATE TABLE IF NOT EXISTS staff_attendance_records (
    record_id INT PRIMARY KEY AUTO_INCREMENT,
    attendance_id INT NOT NULL,
    staff_id INT NOT NULL,
    status ENUM('present', 'absent', 'late', 'leave', 'wfh') NOT NULL,
    time_in TIME,
    time_out TIME,
    notes TEXT,
    FOREIGN KEY (attendance_id) REFERENCES staff_attendance(attendance_id),
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- ======================
-- FINANCIAL TABLES
-- ======================

-- Expense categories
CREATE TABLE IF NOT EXISTS expense_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE
);

-- Expenses (completing the cut-off table)
CREATE TABLE IF NOT EXISTS expenses (
    expense_id INT PRIMARY KEY AUTO_INCREMENT,
    date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    category_id INT NOT NULL,
    vendor VARCHAR(100) NOT NULL,
    department_id INT NOT NULL,
    staff_id INT NOT NULL,
    description TEXT,
    status ENUM('pending', 'approved', 'rejected', 'paid') DEFAULT 'pending',
    payment_method ENUM('cash', 'check', 'bank_transfer', 'credit_card') NOT NULL,
    receipt_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES expense_categories(category_id),
    FOREIGN KEY (department_id) REFERENCES departments(department_id),
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Expense approvals
CREATE TABLE IF NOT EXISTS expense_approvals (
    approval_id INT PRIMARY KEY AUTO_INCREMENT,
    expense_id INT NOT NULL,
    approver_id INT NOT NULL,
    status ENUM('approved', 'rejected') NOT NULL,
    comments TEXT,
    approval_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expense_id) REFERENCES expenses(expense_id),
    FOREIGN KEY (approver_id) REFERENCES staff(staff_id)
);

-- Update the broadcast_messages table to modify the recipient_ids column
ALTER TABLE broadcast_messages 
MODIFY COLUMN recipient_ids JSON COMMENT 'Specific IDs if not sent to all';

-- Create Notices Table
CREATE TABLE notices (
    notice_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    source_office VARCHAR(50) NOT NULL,
    category VARCHAR(50) NOT NULL,
    post_date DATE NOT NULL,
    expiry_date DATE,
    is_urgent BOOLEAN DEFAULT FALSE,
    status ENUM('published', 'draft', 'expired') NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NOT NULL
);

-- Create Notice Attachments Table (for managing multiple attachments per notice)
CREATE TABLE notice_attachments (
    attachment_id INT PRIMARY KEY AUTO_INCREMENT,
    notice_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (notice_id) REFERENCES notices(notice_id) ON DELETE CASCADE
);

-- Add indexes for better performance
CREATE INDEX idx_notices_category ON notices(category);
CREATE INDEX idx_notices_source_office ON notices(source_office);
CREATE INDEX idx_notices_status ON notices(status);
CREATE INDEX idx_notices_post_date ON notices(post_date);
CREATE INDEX idx_notices_created_by ON notices(created_by);