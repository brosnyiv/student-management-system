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

