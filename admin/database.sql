-- Create database with proper character set and collation
CREATE DATABASE monaco_student_registration 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE monaco_student_registration;


-- Departments table
CREATE TABLE departments (
    department_id VARCHAR(20) PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample department data
INSERT INTO departments (department_id, department_name) VALUES 
('it', 'Information Technology'),
('business', 'Business'),
('design', 'Design'),
('marketing', 'Marketing');

-- Users table (for login credentials)
CREATE TABLE users (
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
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

-- Roles table
CREATE TABLE roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL,
    role_description TEXT,
    is_teaching_role BOOLEAN DEFAULT FALSE
);

-- Staff table (common info for all staff)
CREATE TABLE staff (
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

-- Departments table
CREATE TABLE departments (
    department_id INT PRIMARY KEY AUTO_INCREMENT,
    department_name VARCHAR(100) NOT NULL,
    department_code VARCHAR(20) UNIQUE NOT NULL,
    department_head INT,
    FOREIGN KEY (department_head) REFERENCES staff(staff_id)
);

-- Faculty table for organizing departments
CREATE TABLE faculties (
    faculty_id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_name VARCHAR(100) NOT NULL,
    faculty_code VARCHAR(20) UNIQUE NOT NULL,
    faculty_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Faculty leaders table (assigns teaching staff as faculty leaders)
CREATE TABLE faculty_leaders (
    faculty_leader_id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_id INT NOT NULL,
    staff_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    end_date DATE,
    is_current BOOLEAN DEFAULT TRUE,
    leadership_role VARCHAR(100) NOT NULL, -- e.g., "Dean", "Associate Dean", "Faculty Head"
    responsibilities TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculties(faculty_id),
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id),
    CONSTRAINT faculty_current_leader_unique UNIQUE (faculty_id, is_current, leadership_role)
);

-- Map departments to faculties
CREATE TABLE department_faculty (
    department_id INT NOT NULL,
    faculty_id INT NOT NULL,
    PRIMARY KEY (department_id, faculty_id),
    FOREIGN KEY (department_id) REFERENCES departments(department_id),
    FOREIGN KEY (faculty_id) REFERENCES faculties(faculty_id)
);

-- Emergency contacts
CREATE TABLE emergency_contacts (
    contact_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    contact_name VARCHAR(100) NOT NULL,
    contact_phone VARCHAR(20) NOT NULL,
    relationship VARCHAR(50) NOT NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Academic qualifications
CREATE TABLE qualifications (
    qualification_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    degree VARCHAR(100) NOT NULL,
    institution VARCHAR(100) NOT NULL,
    major VARCHAR(100) NOT NULL,
    graduation_year INT NOT NULL,
    certificate_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Teaching staff details
CREATE TABLE teaching_staff (
    teaching_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL UNIQUE,
    assigned_courses TEXT NOT NULL,
    semester_load INT NOT NULL,
    office_hours VARCHAR(100) NOT NULL,
    available_times TEXT NOT NULL,
    assigned_classes VARCHAR(255),
    is_faculty_leader BOOLEAN DEFAULT FALSE,
    academic_rank VARCHAR(50), -- e.g., "Professor", "Associate Professor", "Assistant Professor"
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Non-teaching staff details
CREATE TABLE non_teaching_staff (
    non_teaching_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL UNIQUE,
    working_days ENUM('monday-friday', 'monday-saturday', 'rotational', 'custom') NOT NULL,
    working_hours VARCHAR(100) NOT NULL,
    schedule_notes TEXT,
    work_area VARCHAR(255),
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Bank details
CREATE TABLE bank_details (
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

-- Document uploads
CREATE TABLE documents (
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

-- Leadership documents (specific documents related to faculty leadership)
CREATE TABLE leadership_documents (
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
CREATE TABLE employment_custom_fields (
    field_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    field_name VARCHAR(100) NOT NULL,
    field_value TEXT,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);

-- Consent and declarations
CREATE TABLE consents (
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
CREATE TABLE form_drafts (
    draft_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_type ENUM('teaching', 'non-teaching') NOT NULL,
    form_data JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Course levels table
CREATE TABLE course_levels (
    level_id VARCHAR(20) PRIMARY KEY,
    level_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert course levels
INSERT INTO course_levels (level_id, level_name) VALUES
('certificate', 'Certificate'),
('diploma', 'Diploma');

-- Courses table
CREATE TABLE courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    level_id VARCHAR(20) NOT NULL,
    department_id VARCHAR(20) NOT NULL,
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
    FOREIGN KEY (faculty_leader_id) REFERENCES instructors(instructor_id)
);

-- Academic years table
CREATE TABLE academic_years (
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
CREATE TABLE semesters (
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
CREATE TABLE course_units (
    unit_id INT PRIMARY KEY AUTO_INCREMENT,
    unit_name VARCHAR(100) NOT NULL,
    unit_code VARCHAR(20) NOT NULL,
    semester_id INT NOT NULL,
    instructor_id INT NOT NULL,
    credits INT NOT NULL DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (semester_id) REFERENCES semesters(semester_id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES instructors(instructor_id),
    UNIQUE KEY (unit_code, semester_id)
);

-- Enrollments table (for tracking students enrolled in courses)
CREATE TABLE enrollments (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('active', 'completed', 'withdrawn', 'deferred') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    UNIQUE KEY (student_id, course_id)
);

-- Unit enrollments (for tracking individual unit enrollments)
CREATE TABLE unit_enrollments (
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

-- View to show complete course information with units
CREATE VIEW course_details_view AS
SELECT 
    c.course_id,
    c.course_name,
    c.course_code,
    cl.level_name,
    d.department_name,
    c.duration,
    c.max_capacity,
    CONCAT(i.title, ' ', i.first_name, ' ', i.last_name) AS faculty_leader,
    c.status,
    c.description,
    c.course_fee,
    c.start_date,
    ay.year_number,
    s.semester_number,
    cu.unit_name,
    cu.unit_code,
    cu.credits,
    CONCAT(ins.title, ' ', ins.first_name, ' ', ins.last_name) AS unit_instructor
FROM 
    courses c
    JOIN course_levels cl ON c.level_id = cl.level_id
    JOIN departments d ON c.department_id = d.department_id
    JOIN instructors i ON c.faculty_leader_id = i.instructor_id
    JOIN academic_years ay ON c.course_id = ay.course_id
    JOIN semesters s ON ay.academic_year_id = s.academic_year_id
    JOIN course_units cu ON s.semester_id = cu.semester_id
    JOIN instructors ins ON cu.instructor_id = ins.instructor_id
ORDER BY 
    c.course_id, ay.year_number, s.semester_number, cu.unit_name;

-- Stored procedure to create a new course with units
DELIMITER //
CREATE PROCEDURE create_new_course(
    IN p_course_name VARCHAR(100),
    IN p_course_code VARCHAR(20),
    IN p_level_id VARCHAR(20),
    IN p_department_id VARCHAR(20),
    IN p_duration INT,
    IN p_max_capacity INT,
    IN p_faculty_leader_id INT,
    IN p_status VARCHAR(20),
    IN p_description TEXT,
    IN p_course_fee DECIMAL(10, 2),
    IN p_start_date DATE,
    IN p_units JSON
)
BEGIN
    DECLARE v_course_id INT;
    DECLARE v_academic_year_id INT;
    DECLARE v_semester_id INT;
    DECLARE v_year INT;
    DECLARE v_semester INT;
    DECLARE v_unit_index INT;
    DECLARE v_units_count INT;
    DECLARE v_unit_data JSON;
    
    -- Start transaction
    START TRANSACTION;
    
    -- Insert course
    INSERT INTO courses (
        course_name,
        course_code,
        level_id,
        department_id,
        duration,
        max_capacity,
        faculty_leader_id,
        status,
        description,
        course_fee,
        start_date
    ) VALUES (
        p_course_name,
        p_course_code,
        p_level_id,
        p_department_id,
        p_duration,
        p_max_capacity,
        p_faculty_leader_id,
        p_status,
        p_description,
        p_course_fee,
        p_start_date
    );
    
    SET v_course_id = LAST_INSERT_ID();
    
    -- Create academic years and semesters
    SET v_year = 1;
    WHILE v_year <= p_duration DO
        -- Create academic year
        INSERT INTO academic_years (
            year_number,
            course_id,
            start_date,
            end_date
        ) VALUES (
            v_year,
            v_course_id,
            DATE_ADD(p_start_date, INTERVAL (v_year - 1) YEAR),
            DATE_ADD(p_start_date, INTERVAL v_year YEAR)
        );
        
        SET v_academic_year_id = LAST_INSERT_ID();
        
        -- Create semesters for the academic year
        SET v_semester = 1;
        WHILE v_semester <= 2 DO
            INSERT INTO semesters (
                academic_year_id,
                semester_number,
                start_date,
                end_date
            ) VALUES (
                v_academic_year_id,
                v_semester,
                DATE_ADD(DATE_ADD(p_start_date, INTERVAL (v_year - 1) YEAR), INTERVAL (v_semester - 1) * 6 MONTH),
                DATE_ADD(DATE_ADD(p_start_date, INTERVAL (v_year - 1) YEAR), INTERVAL v_semester * 6 MONTH)
            );
            
            SET v_semester_id = LAST_INSERT_ID();
            
            -- Get semester key
            SET @semester_key = CONCAT('semester', v_semester, 'year', v_year);
            
            -- Get units for this semester
            SET v_units_count = JSON_LENGTH(JSON_EXTRACT(p_units, CONCAT('$.', @semester_key)));
            
            -- Add units for this semester
            SET v_unit_index = 0;
            WHILE v_unit_index < v_units_count DO
                SET v_unit_data = JSON_EXTRACT(p_units, CONCAT('$.', @semester_key, '[', v_unit_index, ']'));
                
                INSERT INTO course_units (
                    unit_name,
                    unit_code,
                    semester_id,
                    instructor_id,
                    credits
                ) VALUES (
                    JSON_UNQUOTE(JSON_EXTRACT(v_unit_data, '$.name')),
                    JSON_UNQUOTE(JSON_EXTRACT(v_unit_data, '$.code')),
                    v_semester_id,
                    JSON_EXTRACT(v_unit_data, '$.instructor_id'),
                    JSON_EXTRACT(v_unit_data, '$.credits')
                );
                
                SET v_unit_index = v_unit_index + 1;
            END WHILE;
            
            SET v_semester = v_semester + 1;
        END WHILE;
        
        SET v_year = v_year + 1;
    END WHILE;
    
    -- Commit transaction
    COMMIT;
    
    -- Return the course ID
    SELECT v_course_id AS course_id;
END //
DELIMITER ;

-- Create sequence table with better locking mechanism
CREATE TABLE student_id_sequence (
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
INSERT INTO student_id_sequence (sequence_name, next_val) VALUES ('student_id', 1);

-- Students table with improved constraints - Fixed CURDATE() issue
CREATE TABLE students (
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
CREATE TRIGGER before_student_insert
BEFORE INSERT ON students
FOR EACH ROW
BEGIN
    IF NEW.date_of_birth > DATE_SUB(CURDATE(), INTERVAL 17 YEAR) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Student must be at least 17 years old';
    END IF;
END //

CREATE TRIGGER before_student_update
BEFORE UPDATE ON students
FOR EACH ROW
BEGIN
    IF NEW.date_of_birth > DATE_SUB(CURDATE(), INTERVAL 17 YEAR) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Student must be at least 17 years old';
    END IF;
END //
DELIMITER ;

-- Create audit trail table
CREATE TABLE audit_trail (
    audit_id INT AUTO_INCREMENT PRIMARY KEY,
    table_affected VARCHAR(50) NOT NULL,
    record_id INT NOT NULL,
    action_type ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    change_details TEXT NOT NULL,
    performed_by INT,
    performed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_table (table_affected),
    INDEX idx_audit_action (action_type)
) COMMENT='Audit trail for tracking changes';



-- Enhanced contact details with validation
CREATE TABLE contact_details (
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
CREATE TRIGGER before_contact_insert
BEFORE INSERT ON contact_details
FOR EACH ROW
BEGIN
    IF NEW.email NOT REGEXP '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}$' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Invalid email format';
    END IF;
END //

CREATE TRIGGER before_contact_update
BEFORE UPDATE ON contact_details
FOR EACH ROW
BEGIN
    IF NEW.email NOT REGEXP '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}$' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Invalid email format';
    END IF;
END //
DELIMITER ;

-- Improved emergency contacts table
CREATE TABLE emergency_contacts (
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

-- Secure payment information table
CREATE TABLE payment_info (
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

-- Billing addresses with better structure
CREATE TABLE billing_addresses (
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

-- Enhanced documents table with better tracking
CREATE TABLE documents (
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

-- Enhanced consent records
CREATE TABLE consent_records (
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
CREATE TABLE registration_status (
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

-- Improved form autosave data
CREATE TABLE form_autosave (
    autosave_id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(50),
    form_type ENUM('student_registration', 'staff_registration', 'course_application') NOT NULL,
    form_data JSON NOT NULL,
    section_completed INT NOT NULL DEFAULT 1,
    last_saved TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expire_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP + INTERVAL 7 DAY),
    device_info TEXT,
    ip_address VARCHAR(45),
    INDEX idx_autosave_session (session_id),
    INDEX idx_autosave_user (user_id),
    INDEX idx_autosave_expire (expire_at)
) COMMENT='Form autosave data with session tracking';

-- Insert department codes
INSERT INTO student_id_sequence (sequence_name, department_code, next_val) VALUES 
('BUS', 'BUS', 1),
('ENG', 'ENG', 1),
('MED', 'MED', 1),
('LAW', 'LAW', 1),
('ART', 'ART', 1)
ON DUPLICATE KEY UPDATE next_val = next_val;

-- Remove the old student ID procedure if it exists
DROP PROCEDURE IF EXISTS generate_student_id;

-- Create the new student ID generation function
DELIMITER //
CREATE FUNCTION generate_student_id(
    p_department VARCHAR(100),
    p_enrollment_year INT
) 
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN
    DECLARE v_dept_code CHAR(3);
    DECLARE v_unique_code VARCHAR(4);
    
    -- Extract first 3 letters of department (e.g., "Business" -> "BUS")
    SET v_dept_code = UPPER(SUBSTRING(p_department, 1, 3));
    
    -- Generate random 4-digit number (1000-9999)
    SET v_unique_code = LPAD(FLOOR(RAND() * 9000) + 1000, 4, '0');
    
    -- Return formatted ID: MI-DEP-YYYY-XXXX
    RETURN CONCAT('MI-', v_dept_code, '-', p_enrollment_year, '-', v_unique_code);
END //
DELIMITER ;

-- Update the register_new_student procedure
DELIMITER //
CREATE PROCEDURE register_new_student(
    IN p_first_name VARCHAR(50),
    IN p_middle_name VARCHAR(50),
    IN p_surname VARCHAR(50),
    IN p_date_of_birth DATE,
    IN p_gender VARCHAR(20),
    IN p_profile_photo_path VARCHAR(255),
    IN p_nationality VARCHAR(50),
    IN p_course_id INT,
    IN p_department VARCHAR(100),
    IN p_enrollment_year INT,
    IN p_created_by INT,
    OUT p_student_id VARCHAR(50),
    OUT p_status INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET p_status = @errno;
        ROLLBACK;
    END;
    
    START TRANSACTION;
    
    -- Generate student ID using new format
    SET p_student_id = generate_student_id(p_department, p_enrollment_year);
    
    -- Insert the new student
    INSERT INTO students (
        student_id,
        first_name,
        middle_name,
        surname,
        date_of_birth,
        gender,
        profile_photo_path,
        nationality,
        created_by
    ) VALUES (
        p_student_id,
        p_first_name,
        p_middle_name,
        p_surname,
        p_date_of_birth,
        p_gender,
        p_profile_photo_path,
        p_nationality,
        p_created_by
    );
    
    -- Initialize registration status
    INSERT INTO registration_status (student_id, status)
    VALUES (p_student_id, 'incomplete');
    
    -- Initialize consent records
    INSERT INTO consent_records (
        student_id,
        terms_agreed_version,
        policy_agreed_version
    ) VALUES (
        p_student_id,
        '1.0',
        '1.0'
    );
    
    SET p_status = 0;
    COMMIT;
END //
DELIMITER ;

-- FIXED: Encryption function for sensitive data
DELIMITER //
CREATE FUNCTION encrypt_data(
    p_plaintext VARCHAR(255),
    p_key VARCHAR(255)
)
RETURNS VARBINARY(255)
DETERMINISTIC
BEGIN
    DECLARE v_ciphertext VARBINARY(255);
    
    -- Encrypt using AES with just the key (no IV)
    SET v_ciphertext = AES_ENCRYPT(p_plaintext, p_key);
    
    -- Return ciphertext
    RETURN v_ciphertext;
END //
DELIMITER ;

-- FIXED: Decryption function for sensitive data
DELIMITER //
CREATE FUNCTION decrypt_data(
    p_ciphertext VARBINARY(255),
    p_key VARCHAR(255)
)
RETURNS VARCHAR(255)
DETERMINISTIC
BEGIN
    DECLARE v_plaintext VARCHAR(255);
    
    -- Decrypt using AES
    SET v_plaintext = AES_DECRYPT(p_ciphertext, p_key);
    
    -- Return plaintext
    RETURN v_plaintext;
END //
DELIMITER ;

-- Create audit trigger for students table
DELIMITER //
CREATE TRIGGER after_student_update
AFTER UPDATE ON students
FOR EACH ROW
BEGIN
    IF NEW.first_name != OLD.first_name OR NEW.surname != OLD.surname OR NEW.status != OLD.status THEN
        INSERT INTO audit_trail (
            table_affected,
            record_id,
            action_type,
            change_details,
            performed_by
        ) VALUES (
            'students',
            NEW.id,
            'UPDATE',
            CONCAT('Student updated: ', 
                   IF(NEW.first_name != OLD.first_name, CONCAT('First name changed from ', OLD.first_name, ' to ', NEW.first_name, '; '), ''),
                   IF(NEW.surname != OLD.surname, CONCAT('Surname changed from ', OLD.surname, ' to ', NEW.surname, '; '), ''),
                   IF(NEW.status != OLD.status, CONCAT('Status changed from ', OLD.status, ' to ', NEW.status), '')),
            NEW.updated_by
        );
    END IF;
END //
DELIMITER ;

-- Create indexes for better performance
CREATE INDEX idx_students_created ON students(created_at);
CREATE INDEX idx_students_active ON students(status);
CREATE INDEX idx_documents_student ON documents(student_id, document_type);
CREATE INDEX idx_payments_student ON payment_info(student_id, payment_status);
CREATE INDEX idx_registration_dates ON registration_status(submission_date, status);

-- Create view for active students
CREATE VIEW vw_active_students AS
SELECT s.student_id, s.first_name, s.surname, s.date_of_birth, s.gender, s.nationality,
       c.course_name, a.program_level, a.year_level, a.enrollment_status,
       cd.email, cd.phone, cd.city, cd.country
FROM students s
JOIN academic_info a ON s.student_id = a.student_id
JOIN courses c ON a.course_id = c.course_id
JOIN contact_details cd ON s.student_id = cd.student_id
WHERE s.status = 'Active' AND a.enrollment_status = 'Full-time'
AND cd.is_primary = TRUE;

-- Create view for pending documents
CREATE VIEW vw_pending_documents AS
SELECT s.student_id, s.first_name, s.surname, 
       d.document_type, d.file_name, d.upload_date,
       rs.status AS registration_status
FROM students s
JOIN documents d ON s.student_id = d.student_id
JOIN registration_status rs ON s.student_id = rs.student_id
WHERE d.verification_status = 'Pending'
AND rs.status IN ('submitted', 'under_review')
ORDER BY d.upload_date;



-- Enhanced academic info with more fields
CREATE TABLE academic_info (
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
CREATE TRIGGER before_academic_insert
BEFORE INSERT ON academic_info
FOR EACH ROW
BEGIN
    -- Validate GPA range
    IF NEW.previous_gpa IS NOT NULL AND (NEW.previous_gpa < 0 OR NEW.previous_gpa > 4.0) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'GPA must be between 0 and 4.0';
    END IF;
    
    -- Validate dates
    IF NEW.expected_end_date IS NOT NULL AND NEW.expected_start_date >= NEW.expected_end_date THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'End date must be after start date';
    END IF;
END //

CREATE TRIGGER before_academic_update
BEFORE UPDATE ON academic_info
FOR EACH ROW
BEGIN
    -- Validate GPA range
    IF NEW.previous_gpa IS NOT NULL AND (NEW.previous_gpa < 0 OR NEW.previous_gpa > 4.0) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'GPA must be between 0 and 4.0';
    END IF;
    
    -- Validate dates
    IF NEW.expected_end_date IS NOT NULL AND NEW.expected_start_date >= NEW.expected_end_date THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'End date must be after start date';
    END IF;
END //
DELIMITER ;

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    payment_date DATE NOT NULL,
    course VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    payment_type ENUM('tuition', 'exam', 'registration', 'library', 'other') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    payment_method ENUM('cash', 'card', 'bank', 'online') NOT NULL,
    payment_details JSON,
    receipt_number VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add indexes for better performance
CREATE INDEX idx_student_id ON payments(student_id);
CREATE INDEX idx_receipt_number ON payments(receipt_number);
CREATE INDEX idx_payment_date ON payments(payment_date);

