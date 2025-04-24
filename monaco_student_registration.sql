-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 02:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monaco_student_registration`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `register_new_student` (IN `p_first_name` VARCHAR(50), IN `p_middle_name` VARCHAR(50), IN `p_surname` VARCHAR(50), IN `p_date_of_birth` DATE, IN `p_gender` VARCHAR(20), IN `p_profile_photo_path` VARCHAR(255), IN `p_nationality` VARCHAR(50), IN `p_course_id` INT, IN `p_department` VARCHAR(100), IN `p_enrollment_year` INT, IN `p_created_by` INT, OUT `p_student_id` VARCHAR(50), OUT `p_status` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET p_status = @errno;
        ROLLBACK;
    END;
    
    CREATE TABLE notices (
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
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `decrypt_data` (`p_ciphertext` VARBINARY(255), `p_key` VARCHAR(255)) RETURNS VARCHAR(255) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC BEGIN
    DECLARE v_plaintext VARCHAR(255);
    
    -- Decrypt using AES
    SET v_plaintext = AES_DECRYPT(p_ciphertext, p_key);
    
    -- Return plaintext
    RETURN v_plaintext;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `encrypt_data` (`p_plaintext` VARCHAR(255), `p_key` VARCHAR(255)) RETURNS VARBINARY(255) DETERMINISTIC BEGIN
    DECLARE v_ciphertext VARBINARY(255);
    
    -- Encrypt using AES with just the key (no IV)
    SET v_ciphertext = AES_ENCRYPT(p_plaintext, p_key);
    
    -- Return ciphertext
    RETURN v_ciphertext;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `generate_student_id` (`p_department` VARCHAR(100), `p_enrollment_year` INT) RETURNS VARCHAR(20) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC BEGIN
    DECLARE v_dept_code CHAR(3);
    DECLARE v_unique_code VARCHAR(4);
    
    -- Extract first 3 letters of department (e.g., "Business" -> "BUS")
    SET v_dept_code = UPPER(SUBSTRING(p_department, 1, 3));
    
    -- Generate random 4-digit number (1000-9999)
    SET v_unique_code = LPAD(FLOOR(RAND() * 9000) + 1000, 4, '0');
    
    -- Return formatted ID: MI-DEP-YYYY-XXXX
    RETURN CONCAT('MI-', v_dept_code, '-', p_enrollment_year, '-', v_unique_code);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `audit_id` int(11) NOT NULL,
  `table_affected` varchar(50) NOT NULL,
  `record_id` int(11) NOT NULL,
  `action_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `change_details` text NOT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `performed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Audit trail for tracking changes';

-- --------------------------------------------------------

--
-- Table structure for table `billing_addresses`
--

CREATE TABLE `billing_addresses` (
  `billing_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state_province` varchar(50) NOT NULL,
  `zip_postal_code` varchar(20) NOT NULL,
  `country` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Billing addresses for students';

-- --------------------------------------------------------

--
-- Table structure for table `consent_records`
--

CREATE TABLE `consent_records` (
  `consent_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `terms_agreed` tinyint(1) NOT NULL DEFAULT 0,
  `terms_agreed_version` varchar(20) NOT NULL,
  `terms_agreed_date` datetime DEFAULT NULL,
  `policy_agreed` tinyint(1) NOT NULL DEFAULT 0,
  `policy_agreed_version` varchar(20) NOT NULL,
  `policy_agreed_date` datetime DEFAULT NULL,
  `marketing_opt_in` tinyint(1) NOT NULL DEFAULT 0,
  `data_sharing_consent` tinyint(1) DEFAULT 0,
  `emergency_contact_consent` tinyint(1) DEFAULT 1,
  `photo_release_consent` tinyint(1) DEFAULT 0,
  `digital_signature` varchar(255) DEFAULT NULL,
  `signature_date` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Detailed consent records with version tracking';

-- --------------------------------------------------------

--
-- Table structure for table `contact_details`
--

CREATE TABLE `contact_details` (
  `contact_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `alt_phone` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state_province` varchar(50) NOT NULL,
  `zip_postal_code` varchar(20) NOT NULL,
  `country` varchar(50) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student contact information';

--
-- Triggers `contact_details`
--
DELIMITER $$
CREATE TRIGGER `before_contact_insert` BEFORE INSERT ON `contact_details` FOR EACH ROW BEGIN
    IF NEW.email NOT REGEXP '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Invalid email format';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_contact_update` BEFORE UPDATE ON `contact_details` FOR EACH ROW BEGIN
    IF NEW.email NOT REGEXP '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Invalid email format';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `course_levels`
--

CREATE TABLE `course_levels` (
  `level_id` varchar(20) NOT NULL,
  `level_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_levels`
--

INSERT INTO `course_levels` (`level_id`, `level_name`, `created_at`, `updated_at`) VALUES
('certificate', 'Certificate', '2025-04-24 09:20:34', '2025-04-24 09:20:34'),
('diploma', 'Diploma', '2025-04-24 09:20:34', '2025-04-24 09:20:34');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `department_code` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `department_code`, `created_at`, `updated_at`) VALUES
(1, 'Information Technology', 'it', '2025-04-24 09:20:34', '2025-04-24 09:20:34'),
(2, 'Business', 'business', '2025-04-24 09:20:34', '2025-04-24 09:20:34'),
(3, 'Design', 'design', '2025-04-24 09:20:34', '2025-04-24 09:20:34'),
(4, 'Marketing', 'marketing', '2025-04-24 09:20:34', '2025-04-24 09:20:34');

-- --------------------------------------------------------

--
-- Table structure for table `department_faculty`
--

CREATE TABLE `department_faculty` (
  `department_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `faculty_id` int(11) NOT NULL,
  `faculty_name` varchar(100) NOT NULL,
  `faculty_code` varchar(20) NOT NULL,
  `faculty_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `form_autosave`
--

CREATE TABLE `form_autosave` (
  `autosave_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `form_type` enum('student_registration','staff_registration','course_application') NOT NULL,
  `form_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`form_data`)),
  `section_completed` int(11) NOT NULL DEFAULT 1,
  `last_saved` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expire_at` timestamp NOT NULL DEFAULT (current_timestamp() + interval 7 day),
  `device_info` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Form autosave data with session tracking';

-- --------------------------------------------------------

--
-- Table structure for table `form_drafts`
--

CREATE TABLE `form_drafts` (
  `draft_id` int(11) NOT NULL,
  `staff_type` enum('teaching','non-teaching') NOT NULL,
  `form_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`form_data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_info`
--

CREATE TABLE `payment_info` (
  `payment_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `payment_method` enum('credit_card','bank_transfer','scholarship','other') NOT NULL,
  `card_name` varchar(255) DEFAULT NULL,
  `card_number_encrypted` varbinary(255) DEFAULT NULL,
  `card_number_iv` varbinary(16) DEFAULT NULL,
  `expiry_date_encrypted` varbinary(255) DEFAULT NULL,
  `expiry_date_iv` varbinary(16) DEFAULT NULL,
  `cvv_encrypted` varbinary(255) DEFAULT NULL,
  `cvv_iv` varbinary(16) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number_encrypted` varbinary(255) DEFAULT NULL,
  `account_number_iv` varbinary(16) DEFAULT NULL,
  `routing_number_encrypted` varbinary(255) DEFAULT NULL,
  `routing_number_iv` varbinary(16) DEFAULT NULL,
  `scholarship_name` varchar(255) DEFAULT NULL,
  `scholarship_id` varchar(50) DEFAULT NULL,
  `use_contact_address` tinyint(1) NOT NULL DEFAULT 1,
  `payment_status` enum('Pending','Completed','Failed','Refunded') DEFAULT 'Pending',
  `last_payment_date` datetime DEFAULT NULL,
  `next_payment_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student payment information with proper encryption';

-- --------------------------------------------------------

--
-- Table structure for table `registration_status`
--

CREATE TABLE `registration_status` (
  `status_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('draft','submitted','under_review','incomplete','approved','rejected','waitlisted') NOT NULL DEFAULT 'draft',
  `admin_notes` text DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `review_date` datetime DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `next_action_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registration status with review tracking';

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `surname` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other','Prefer not to say') NOT NULL,
  `profile_photo_path` varchar(255) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive','Suspended','Graduated') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Core student information table';

--
-- Triggers `students`
--
DELIMITER $$
CREATE TRIGGER `after_student_update` AFTER UPDATE ON `students` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_student_insert` BEFORE INSERT ON `students` FOR EACH ROW BEGIN
    IF NEW.date_of_birth > DATE_SUB(CURDATE(), INTERVAL 17 YEAR) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Student must be at least 17 years old';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_student_update` BEFORE UPDATE ON `students` FOR EACH ROW BEGIN
    IF NEW.date_of_birth > DATE_SUB(CURDATE(), INTERVAL 17 YEAR) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Student must be at least 17 years old';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `student_documents`
--

CREATE TABLE `student_documents` (
  `document_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `document_type` enum('academic_transcript','id_proof','admission_proof','additional','financial','medical') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `verification_status` enum('Pending','Verified','Rejected') DEFAULT 'Pending',
  `verified_by` int(11) DEFAULT NULL,
  `verification_date` datetime DEFAULT NULL,
  `verification_notes` text DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiry_date` date DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student documents with verification tracking';

-- --------------------------------------------------------

--
-- Table structure for table `student_emergency_contacts`
--

CREATE TABLE `student_emergency_contacts` (
  `emergency_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `relationship` enum('Parent','Guardian','Sibling','Spouse','Other') NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `alt_phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 1,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Emergency contact information for students';

-- --------------------------------------------------------

--
-- Table structure for table `student_id_sequence`
--

CREATE TABLE `student_id_sequence` (
  `sequence_name` varchar(50) NOT NULL,
  `department_code` varchar(3) DEFAULT NULL,
  `next_val` bigint(20) NOT NULL DEFAULT 1,
  `increment_by` int(11) NOT NULL DEFAULT 1,
  `max_val` bigint(20) NOT NULL DEFAULT 999999,
  `cycle` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_id_sequence`
--

INSERT INTO `student_id_sequence` (`sequence_name`, `department_code`, `next_val`, `increment_by`, `max_val`, `cycle`, `updated_at`) VALUES
('ART', 'ART', 1, 1, 999999, 0, '2025-04-24 09:20:35'),
('BUS', 'BUS', 1, 1, 999999, 0, '2025-04-24 09:20:35'),
('ENG', 'ENG', 1, 1, 999999, 0, '2025-04-24 09:20:35'),
('LAW', 'LAW', 1, 1, 999999, 0, '2025-04-24 09:20:35'),
('MED', 'MED', 1, 1, 999999, 0, '2025-04-24 09:20:35'),
('student_id', NULL, 1, 1, 999999, 0, '2025-04-24 09:20:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `user_role` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `user_role`, `fullname`, `email`, `password`, `status`) VALUES
(1, 'Admin', 'charles', 'charles@gmail.com', '$2y$10$Ep9Dn1gR232dAN//bQapxu6gkaCX2JgF5B4ml.1J/3rwdISd5jjei', 'Active'),
(2, 'Admin', 'jae', 'jae@gmail.com', '$2y$10$MpXNPUsDL6f5q3Bv8pMFC.uPa6184wks7dB7yqpPZwpaCKSNFNC2K', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `idx_audit_table` (`table_affected`),
  ADD KEY `idx_audit_action` (`action_type`);

--
-- Indexes for table `billing_addresses`
--
ALTER TABLE `billing_addresses`
  ADD PRIMARY KEY (`billing_id`),
  ADD KEY `idx_billing_active` (`payment_id`,`is_active`);

--
-- Indexes for table `consent_records`
--
ALTER TABLE `consent_records`
  ADD PRIMARY KEY (`consent_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `idx_consent_dates` (`terms_agreed_date`,`policy_agreed_date`);

--
-- Indexes for table `contact_details`
--
ALTER TABLE `contact_details`
  ADD PRIMARY KEY (`contact_id`),
  ADD UNIQUE KEY `uk_student_email` (`student_id`,`email`),
  ADD KEY `idx_contact_email` (`email`);

--
-- Indexes for table `course_levels`
--
ALTER TABLE `course_levels`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_code` (`department_code`);

--
-- Indexes for table `department_faculty`
--
ALTER TABLE `department_faculty`
  ADD PRIMARY KEY (`department_id`,`faculty_id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`faculty_id`),
  ADD UNIQUE KEY `faculty_code` (`faculty_code`);

--
-- Indexes for table `form_autosave`
--
ALTER TABLE `form_autosave`
  ADD PRIMARY KEY (`autosave_id`),
  ADD KEY `idx_autosave_session` (`session_id`),
  ADD KEY `idx_autosave_user` (`user_id`),
  ADD KEY `idx_autosave_expire` (`expire_at`);

--
-- Indexes for table `form_drafts`
--
ALTER TABLE `form_drafts`
  ADD PRIMARY KEY (`draft_id`);

--
-- Indexes for table `payment_info`
--
ALTER TABLE `payment_info`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_payment_method` (`payment_method`);

--
-- Indexes for table `registration_status`
--
ALTER TABLE `registration_status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `idx_registration_status` (`status`),
  ADD KEY `idx_review_dates` (`review_date`,`next_action_date`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `idx_students_name` (`surname`,`first_name`),
  ADD KEY `idx_students_status` (`status`);

--
-- Indexes for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `idx_document_type` (`document_type`),
  ADD KEY `idx_verification_status` (`verification_status`),
  ADD KEY `idx_document_expiry` (`expiry_date`);

--
-- Indexes for table `student_emergency_contacts`
--
ALTER TABLE `student_emergency_contacts`
  ADD PRIMARY KEY (`emergency_id`),
  ADD KEY `idx_emergency_primary` (`student_id`,`is_primary`);

--
-- Indexes for table `student_id_sequence`
--
ALTER TABLE `student_id_sequence`
  ADD PRIMARY KEY (`sequence_name`),
  ADD UNIQUE KEY `uk_dept_code` (`department_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing_addresses`
--
ALTER TABLE `billing_addresses`
  MODIFY `billing_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consent_records`
--
ALTER TABLE `consent_records`
  MODIFY `consent_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_details`
--
ALTER TABLE `contact_details`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `form_autosave`
--
ALTER TABLE `form_autosave`
  MODIFY `autosave_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `form_drafts`
--
ALTER TABLE `form_drafts`
  MODIFY `draft_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_info`
--
ALTER TABLE `payment_info`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registration_status`
--
ALTER TABLE `registration_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_documents`
--
ALTER TABLE `student_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_emergency_contacts`
--
ALTER TABLE `student_emergency_contacts`
  MODIFY `emergency_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billing_addresses`
--
ALTER TABLE `billing_addresses`
  ADD CONSTRAINT `billing_addresses_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment_info` (`payment_id`) ON DELETE CASCADE;

--
-- Constraints for table `consent_records`
--
ALTER TABLE `consent_records`
  ADD CONSTRAINT `consent_records_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_details`
--
ALTER TABLE `contact_details`
  ADD CONSTRAINT `contact_details_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `department_faculty`
--
ALTER TABLE `department_faculty`
  ADD CONSTRAINT `department_faculty_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `department_faculty_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`faculty_id`);

--
-- Constraints for table `payment_info`
--
ALTER TABLE `payment_info`
  ADD CONSTRAINT `payment_info_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `registration_status`
--
ALTER TABLE `registration_status`
  ADD CONSTRAINT `registration_status_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD CONSTRAINT `student_documents_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_emergency_contacts`
--
ALTER TABLE `student_emergency_contacts`
  ADD CONSTRAINT `student_emergency_contacts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
