<?php
// This file would be the backend endpoint to process the uploaded Excel file
// Save this as process-excel.php

session_start();
include 'dbconnect.php';

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'You must be logged in to perform this action']);
    exit();
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Check if a file was uploaded
if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit();
}

// Check if exam period and course unit were provided
if (!isset($_POST['examPeriod']) || !isset($_POST['courseUnit'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit();
}

$examPeriod = $_POST['examPeriod'];
$courseUnit = $_POST['courseUnit'];

// Get file details
$file = $_FILES['excelFile'];
$fileName = $file['name'];
$fileTmpPath = $file['tmp_name'];
$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

// Validate file is an Excel file
if ($fileExtension !== 'xlsx' && $fileExtension !== 'xls') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid file format. Only Excel files (.xlsx, .xls) are allowed']);
    exit();
}


require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    $spreadsheet = IOFactory::load($fileTmpPath);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    // Skip header row
    array_shift($rows);
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($rows as $row) {
        if (count($row) < 3) continue; // Skip rows with insufficient data
        
        $studentId = trim($row[0]);
        $studentName = trim($row[1]);
        $mark = (float)$row[2];
        
        // Validate data
        if (empty($studentId) || empty($studentName) || $mark < 0 || $mark > 100) {
            $errorCount++;
            continue;
        }
        
        // Calculate grade based on mark
        $grade = '';
        if ($mark >= 90) $grade = 'A+';
        else if ($mark >= 80) $grade = 'A';
        else if ($mark >= 70) $grade = 'B';
        else if ($mark >= 60) $grade = 'C';
        else if ($mark >= 50) $grade = 'D';
        else $grade = 'F';
        
        // Insert or update record in database
        $stmt = $conn->prepare("INSERT INTO student_marks 
                               (student_id, student_name, course_unit_id, exam_period_id, mark, grade, updated_by, updated_at) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                               ON DUPLICATE KEY UPDATE 
                               mark = VALUES(mark), 
                               grade = VALUES(grade),
                               updated_by = VALUES(updated_by),
                               updated_at = VALUES(updated_at)");
        
        $stmt->bind_param("ssidssi", $studentId, $studentName, $courseUnit, $examPeriod, $mark, $grade, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $successCount++;
        } else {
            $errorCount++;
        }
    }
    
    // Add an entry to the audit trail
    $user = $_SESSION['user_name'] . ' (' . $_SESSION['user_role'] . ')';
    $action = "Bulk Upload";
    $details = "Uploaded $successCount marks for course unit $courseUnit in exam period $examPeriod";
    
    $auditStmt = $conn->prepare("INSERT INTO audit_trail (user, action, details, created_at) VALUES (?, ?, ?, NOW())");
    $auditStmt->bind_param("sss", $user, $action, $details);
    $auditStmt->execute();
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true, 
        'message' => "Successfully processed $successCount records with $errorCount errors"
    ]);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error processing file: ' . $e->getMessage()]);
}
*/

// Since we don't have the PhpSpreadsheet library installed, we'll return a placeholder response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => 'File uploaded successfully. In a real implementation, the Excel data would be processed and stored in the database.'
]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Marks & Exams</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="marks-exams.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Additional styles for modals and interactive elements */
        #modalOverlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            display: none;
        }
        
        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1001;
            width: 80%;
            max-width: 900px;
            max-height: 80vh;
            overflow-y: auto;
            display: none;
        }
        
        .modal-active {
            display: block;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #777;
        }
        
        .close-btn:hover {
            color: #333;
        }
        
        .file-upload {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }
        
        .file-label {
            background: #8B1818;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        
        .file-name {
            color: #666;
        }
        
        #csvFile {
            display: none;
        }
        
        .checkbox-container {
            display: block;
            position: relative;
            padding-left: 30px;
            margin-bottom: 12px;
            cursor: pointer;
            user-select: none;
        }
        
        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        
        .checkbox-checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 4px;
        }
        
        .checkbox-container:hover input ~ .checkbox-checkmark {
            background-color: #ccc;
        }
        
        .checkbox-container input:checked ~ .checkbox-checkmark {
            background-color: #8B1818;
        }
        
        .checkbox-checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        
        .checkbox-container input:checked ~ .checkbox-checkmark:after {
            display: block;
        }
        
        .checkbox-container .checkbox-checkmark:after {
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status.ongoing {
            background-color: #FFF3CD;
            color: #856404;
        }
        
        .status.upcoming {
            background-color: #D1ECF1;
            color: #0C5460;
        }
        
        .status.ended {
            background-color: #D4EDDA;
            color: #155724;
        }
        
        .action-icon {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            margin: 0 5px;
            color: #555;
        }
        
        .action-icon:hover {
            color: #8B1818;
        }
        
        .action-icon.edit {
            color: #007BFF;
        }
        
        .action-icon.delete {
            color: #DC3545;
        }
        
        .action-icon.view {
            color: #28A745;
        }
        
        .toggle-btn {
            padding: 8px 15px;
            background: #f0f0f0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        
        .toggle-btn.active {
            background: #8B1818;
            color: white;
        }
        
        #addExamPeriodForm {
            display: none;
            margin-bottom: 20px;
        }
        
        #addMarksForm {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <svg viewBox="0 0 24 24" width="50" height="50">
                    <path fill="#8B1818" d="M12,2L1,8l11,6l9-4.91V17c0,0.55,0.45,1,1,1s1-0.45,1-1V7L12,2z M17,15l-5,3l-5-3V9l5-3l0,0l5,3V15z"/>
                </svg>
            </div>
            <div class="institute-name">MONACO INSTITUTE</div>
            <div class="institute-motto">Empowering Professional Skills</div>
            <button class="support-button"><i class="fas fa-headset"></i> Support</button>
        </div>
        <ul class="sidebar-menu">
            <li onclick="window.location.href='dash.php'"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></li>
            <li onclick="window.location.href='student.php'"><i class="fas fa-user-graduate"></i> <span>Student Management</span></li>
            <li onclick="window.location.href='staff.php'"><i class="fas fa-user-tie"></i> <span>Staff Management</span></li>
            <li onclick="window.location.href='courses.php'"><i class="fas fa-book"></i> <span>Courses</span></li>
            <li onclick="window.location.href='payments.php'"><i class="fas fa-money-bill-wave"></i> <span>Payments Info</span></li>
            <li class="active" onclick="window.location.href='marks&exams.php'"><i class="fas fa-file-alt"></i> <span>Marks & Exams</span></li>
            <li onclick="window.location.href='results.php'"><i class="fas fa-search"></i> <span>Result</span></li>
            <li onclick="window.location.href='notices.php'"><i class="fas fa-bullhorn"></i> <span>Notice</span></li>
            <li onclick="window.location.href='attendence.php'"><i class="fas fa-clipboard-list"></i> <span>Attendance</span></li>
            <li onclick="window.location.href='classes.php'"><i class="fas fa-chalkboard-teacher"></i> <span>Classes</span></li>
            <li onclick="window.location.href='messages.php'"><i class="fas fa-envelope"></i> <span>Messages</span></li>
            <li onclick="window.location.href='settings page.php'"><i class="fas fa-cog"></i> <span>Settings</span></li>
            <li><i class="fas fa-sign-out-alt"></i> <span>Logout</span></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>MARKS & EXAMS</h1>
                <p>Welcome back, John!</p>
                <div class="date-display">
                    <i class="fas fa-calendar-alt"></i> <span id="currentDate"></span>
                    <span class="time-display"><i class="fas fa-clock"></i> <span id="currentTime"></span></span>
                    <div class="weather-widget">
                        <i class="fas fa-sun weather-icon"></i>
                        <span class="temperature">26°C</span>
                    </div>
                </div>
            </div>
            <div class="user-section" style="display:flex; align-items:center;">
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                    <span class="notification-count">3</span>
                </div>
                <div class="user-profile">
                    <div class="user-avatar">J</div>
                    <div class="user-info">
                        John Doe<br>
                        <span class="role">Admin</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Search for students, exams, or courses..." aria-label="Search">
        </div>

        <div class="tabs-container">
            <div class="tab active" data-tab="exam-periods">Exam Period Management</div>
            <div class="tab" data-tab="marks-management">Course Unit Marks Management</div>
        </div>

        <!-- Exam Period Management Tab Content -->
        <div class="tab-content active" id="exam-periods-content">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-calendar-alt"></i> Exam Period Management</div>
                <div class="action-buttons">
                    <button class="add-button" id="showAddExamPeriodForm"><i class="fas fa-plus"></i> Add New Exam Period</button>
                </div>
            </div>

            <!-- Add New Exam Period Form -->
            <div class="card" id="addExamPeriodForm">
                <div class="card-header">
                    <h3><i class="fas fa-plus-circle"></i> Add New Exam Period</h3>
                </div>
                <div class="card-body">
                    <form class="form-grid" id="examPeriodForm">
                        <div class="form-group">
                            <label for="examPeriodName">Exam Period Name</label>
                            <input type="text" id="examPeriodName" placeholder="e.g., Semester 1 - 2025" required>
                        </div>
                        <div class="form-group">
                            <label for="startDate">Start Date</label>
                            <input type="date" id="startDate" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">End Date</label>
                            <input type="date" id="endDate" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="description">Description (optional)</label>
                            <textarea id="description" placeholder="Enter additional details about this exam period"></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="submit-btn">Save Exam Period</button>
                            <button type="reset" class="reset-btn">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Existing Exam Periods Table -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-list"></i> Existing Exam Periods</h3>
                </div>
                <div class="card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Period Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Semester 1 - 2025</td>
                                <td>Apr 1, 2025</td>
                                <td>Apr 20, 2025</td>
                                <td><span class="status ongoing">Ongoing</span></td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Mid-Term Exams - 2025</td>
                                <td>May 15, 2025</td>
                                <td>May 30, 2025</td>
                                <td><span class="status upcoming">Upcoming</span></td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>First Quarter Assessment - 2025</td>
                                <td>Mar 10, 2025</td>
                                <td>Mar 20, 2025</td>
                                <td><span class="status ended">Ended</span></td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Course Unit Marks Management Tab Content -->
        <div class="tab-content" id="marks-management-content">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-graduation-cap"></i> Course Unit Marks Management</div>
                <div class="action-buttons">
                    <button class="add-button" id="showAddMarksForm"><i class="fas fa-plus"></i> Add/Update Marks</button>
                    <button class="add-button" id="showTranscriptGenerator"><i class="fas fa-file-pdf"></i> Generate Transcript</button>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-container">
                <div class="filter-group">
                    <label for="examPeriodFilter">Exam Period:</label>
                    <select id="examPeriodFilter">
                        <option value="">All Periods</option>
                        <option value="1">Semester 1 - 2025</option>
                        <option value="2">Mid-Term Exams - 2025</option>
                        <option value="3">First Quarter Assessment - 2025</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="courseUnitFilter">Course Unit:</label>
                    <select id="courseUnitFilter">
                        <option value="">All Course Units</option>
                        <option value="1">Introduction to Programming</option>
                        <option value="2">Data Structures & Algorithms</option>
                        <option value="3">Database Management</option>
                        <option value="4">Web Development</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="studentFilter">Student:</label>
                    <input type="text" id="studentFilter" placeholder="Student ID or Name">
                </div>
                <div class="filter-actions">
                    <button class="filter-btn"><i class="fas fa-search"></i> Apply Filters</button>
                    <button class="reset-filter-btn"><i class="fas fa-undo"></i> Reset</button>
                </div>
            </div>

            <div class="view-toggle">
                <label>View by:</label>
                <div class="toggle-options">
                    <button class="toggle-btn active" data-view="course">Course Unit</button>
                    <button class="toggle-btn" data-view="student">Student</button>
                </div>
            </div>

            <!-- Add/Update Marks Form -->
            <div class="card" id="addMarksForm" style="display: none;">
                <div class="card-header">
                    <h3><i class="fas fa-plus-circle"></i> Add/Update Marks</h3>
                    <button class="close-btn" id="closeMarksForm"><i class="fas fa-times"></i></button>
                </div>
                <div class="card-body">
                    <div class="tabs-container mark-entry-tabs">
                        <div class="tab active" data-tab="manual-entry">Manual Entry</div>
                        <div class="tab" data-tab="bulk-upload">Bulk Upload</div>

                        
                    </div>
                
                    
                    <div class="tab-content active" id="manual-entry-content">
                        <form class="form-grid" id="manualMarksForm">
                            <div class="form-group">
                                <label for="selectExamPeriod">Exam Period</label>
                                <select id="selectExamPeriod" required>
                                    <option value="">Select Exam Period</option>
                                    <option value="1">Semester 1 - 2025</option>
                                    <option value="2">Mid-Term Exams - 2025</option>
                                    <option value="3">First Quarter Assessment - 2025</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="selectCourseUnit">Course Unit</label>
                                <select id="selectCourseUnit" required>
                                    <option value="">Select Course Unit</option>
                                    <option value="1">Introduction to Programming</option>
                                    <option value="2">Data Structures & Algorithms</option>
                                    <option value="3">Database Management</option>
                                    <option value="4">Web Development</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentId">Student ID</label>
                                <input type="text" id="studentId" placeholder="Enter Student ID" required>
                            </div>
                            <div class="form-group">
                                <label for="studentName">Student Name</label>
                                <input type="text" id="studentName" placeholder="Enter Student Name" required>
                            </div>
                            <div class="form-group">
                                <label for="markValue">Mark (0-100)</label>
                                <input type="number" id="markValue" min="0" max="100" placeholder="Enter mark value" required>
                            </div>
                            <div class="form-group">
                                <label for="gradeValue">Grade (Auto-calculated)</label>
                                <input type="text" id="gradeValue" readonly>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">Save Mark</button>
                                <button type="reset" class="reset-btn">Reset</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Replace the existing bulk upload tab content with this enhanced version -->
<div class="tab-content" id="bulk-upload-content">
    <form class="form-grid" id="bulkMarksForm">
        <div class="form-group">
            <label for="bulkExamPeriod">Exam Period</label>
            <select id="bulkExamPeriod" required>
                <option value="">Select Exam Period</option>
                <option value="1">Semester 1 - 2025</option>
                <option value="2">Mid-Term Exams - 2025</option>
                <option value="3">First Quarter Assessment - 2025</option>
            </select>
        </div>
        <div class="form-group">
            <label for="bulkCourseUnit">Course Unit</label>
            <select id="bulkCourseUnit" required>
                <option value="">Select Course Unit</option>
                <option value="1">Introduction to Programming</option>
                <option value="2">Data Structures & Algorithms</option>
                <option value="3">Database Management</option>
                <option value="4">Web Development</option>
            </select>
        </div>
        <div class="form-group full-width">
            <label for="excelFile">Upload Excel File</label>
            <div class="file-upload">
                <input type="file" id="excelFile" accept=".xlsx, .xls">
                <label for="excelFile" class="file-label"><i class="fas fa-cloud-upload-alt"></i> Choose Excel File</label>
                <span class="file-name">No file chosen</span>
            </div>
        </div>
        <div class="form-group full-width">
            <div class="upload-instructions">
                <p><i class="fas fa-info-circle"></i> Excel file should have columns: Student ID, Student Name, Mark</p>
                <a href="#" class="download-template"><i class="fas fa-download"></i> Download Excel Template</a>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="submit-btn"><i class="fas fa-upload"></i> Upload Marks</button>
            <button type="reset" class="reset-btn">Reset</button>
        </div>
    </form>
</div>
                </div>
            </div>

            <!-- Modal Overlay -->
            <div id="modalOverlay" style="display: none;"></div>

            <!-- Course Students Detail View Modal -->
            <div class="modal" id="courseStudentsDetail">
                <div class="modal-header">
                    <h2>Student Marks for <span id="selectedCourseName"></span></h2>
                    <button class="close-btn" id="closeDetailView"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="course-details">
                        <p><strong>Course Unit:</strong> <span id="detailCourseUnit"></span></p>
                        <p><strong>Teacher:</strong> <span id="detailTeacher"></span></p>
                        <p><strong>Number of Students:</strong> <span id="detailStudentCount"></span></p>
                        <p><strong>Average Mark:</strong> <span id="detailAverageMark"></span></p>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Mark</th>
                                <th>Grade</th>
                                <th>Exam Period</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="courseStudentsTableBody">
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Student Detail View Modal -->
            <div class="modal" id="studentDetailView">
                <div class="modal-header">
                    <h2>Course Marks for <span id="selectedStudentName"></span></h2>
                    <button class="close-btn" id="closeStudentView"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="student-details">
                        <p><strong>Student ID:</strong> <span id="detailStudentId"></span></p>
                        <p><strong>Number of Courses:</strong> <span id="detailCourseCount"></span></p>
                        <p><strong>GPA:</strong> <span id="detailGPA"></span></p>
                        <p><strong>Overall Grade:</strong> <span id="detailOverallGrade"></span></p>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Course Unit</th>
                                <th>Course Name</th>
                                <th>Teacher</th>
                                <th>Mark</th>
                                <th>Grade</th>
                                <th>Exam Period</th>
                            </tr>
                        </thead>
                        <tbody id="studentCoursesTableBody">
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Transcript Generator Form -->
            <div class="card" id="transcriptGeneratorForm" style="display: none;">
                <div class="card-header">
                    <h3><i class="fas fa-id-card"></i> Generate Student Transcript</h3>
                    <button class="close-btn" id="closeTranscriptGenerator"><i class="fas fa-times"></i></button>
                </div>
                <div class="card-body">
                    <form class="form-grid" id="transcriptForm">
                        <div class="form-group">
                            <label for="transcriptStudentId">Student ID</label>
                            <input type="text" id="transcriptStudentId" placeholder="Enter Student ID" required>
                        </div>
                        <div class="form-group">
                            <label for="transcriptExamPeriod">Exam Period (Optional)</label>
                            <select id="transcriptExamPeriod">
                                <option value="">All Periods</option>
                                <option value="1">Semester 1 - 2025</option>
                                <option value="2">Mid-Term Exams - 2025</option>
                                <option value="3">First Quarter Assessment - 2025</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>PDF Options</label>
                            <div class="checkbox-group">
                                <label class="checkbox-container">
                                    <input type="checkbox" checked>
                                    <span class="checkbox-label">Include Institute Logo</span>
                                </label>
                                <label class="checkbox-container">
                                    <input type="checkbox" checked>
                                    <span class="checkbox-label">Include GPA</span>
                                </label>
                                <label class="checkbox-container">
                                    <input type="checkbox" checked>
                                    <span class="checkbox-label">Include Digital Signature</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="submit-btn"><i class="fas fa-file-pdf"></i> Generate Transcript</button>
                            <button type="button" class="email-btn"><i class="fas fa-envelope"></i> Email to Student</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Marks Table -->
            <div class="card" id="courseViewCard">
                <div class="card-header">
                    <h3><i class="fas fa-table"></i> Course Units Record</h3>
                    <div class="card-actions">
                        <button class="export-btn"><i class="fas fa-file-pdf"></i> Export PDF</button>
                        <button class="export-btn"><i class="fas fa-file-excel"></i> Export Excel</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Course Unit</th>
                                <th>Course Name</th>
                                <th>Teacher</th>
                                <th>Number of Students</th>
                                <th>Assignment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CU2025001</td>
                                <td>Introduction to Programming</td>
                                <td>Dr. Robert Chen</td>
                                <td>32</td>
                                <td>Programming Basics</td>
                                <td>
                                    <button class="action-icon view" data-course="Introduction to Programming"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>CU2025002</td>
                                <td>Data Structures & Algorithms</td>
                                <td>Prof. Lisa Watkins</td>
                                <td>28</td>
                                <td>Algorithm Analysis</td>
                                <td>
                                    <button class="action-icon view" data-course="Data Structures & Algorithms"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>CU2025003</td>
                                <td>Database Management</td>
                                <td>Dr. Michael Patel</td>
                                <td>35</td>
                                <td>SQL Fundamentals</td>
                                <td>
                                    <button class="action-icon view" data-course="Database Management"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>CU2025004</td>
                                <td>Web Development</td>
                                <td>Prof. Sarah Johnson</td>
                                <td>40</td>
                                <td>Responsive Layouts</td>
                                <td>
                                    <button class="action-icon view" data-course="Web Development"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>CU2025005</td>
                                <td>Computer Networks</td>
                                <td>Dr. James Wilson</td>
                                <td>25</td>
                                <td>Network Protocols</td>
                                <td>
                                    <button class="action-icon view" data-course="Computer Networks"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="pagination">
                        <button class="pagination-btn prev"><i class="fas fa-chevron-left"></i></button>
                        <span class="pagination-info">Page 1 of 2</span>
                        <button class="pagination-btn next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- Student View Card -->
            <div class="card" id="studentViewCard" style="display: none;">
                <div class="card-header">
                    <h3><i class="fas fa-users"></i> Student View</h3>
                </div>
                <div class="card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Course Units</th>
                                <th>GPA</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ST2025001</td>
                                <td>Jane Smith</td>
                                <td>3</td>
                                <td>3.7</td>
                                <td>
                                    <button class="action-icon view" data-student="ST2025001" data-name="Jane Smith"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>ST2025002</td>
                                <td>Mark Johnson</td>
                                <td>2</td>
                                <td>2.5</td>
                                <td>
                                    <button class="action-icon view" data-student="ST2025002" data-name="Mark Johnson"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>ST2025003</td>
                                <td>Sarah Williams</td>
                                <td>4</td>
                                <td>3.9</td>
                                <td>
                                    <button class="action-icon view" data-student="ST2025003" data-name="Sarah Williams"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>ST2025004</td>
                                <td>Michael Brown</td>
                                <td>3</td>
                                <td>2.1</td>
                                <td>
                                    <button class="action-icon view" data-student="ST2025004" data-name="Michael Brown"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>ST2025005</td>
                                <td>Emily Davis</td>
                                <td>2</td>
                                <td>3.8</td>
                                <td>
                                    <button class="action-icon view" data-student="ST2025005" data-name="Emily Davis"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Audit Trail Section -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-history"></i> Audit Trail</h3>
                </div>
                <div class="card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Apr 15, 2025 - 09:45 AM</td>
                                <td>John Doe (Admin)</td>
                                <td>Added Mark</td>
                                <td>Added mark 89 for ST2025001 in Introduction to Programming</td>
                            </tr>
                            <tr>
                                <td>Apr 14, 2025 - 03:20 PM</td>
                                <td>Sarah Johnson (Teacher)</td>
                                <td>Updated Mark</td>
                                <td>Updated mark for ST2025003 from 88 to 92</td>
                            </tr>
                            <tr>
                                <td>Apr 14, 2025 - 11:10 AM</td>
                                <td>John Doe (Admin)</td>
                                <td>Bulk Upload</td>
                                <td>Uploaded 25 marks for Data Structures & Algorithms</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2025 Monaco Institute. All rights reserved.</p>
        </div>
    </div>
    <script>
        // Display current date and time
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', dateOptions);
            
            const timeOptions = { hour: '2-digit', minute: '2-digit' };
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', timeOptions);
        }

        updateDateTime();
        setInterval(updateDateTime, 60000); // Update every minute

        // Tab functionality
        document.querySelectorAll('.tabs-container .tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and content
                document.querySelectorAll('.tabs-container .tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(tabId + '-content')?.classList.add('active');
            });
        });

        // Toggle marks form visibility
        document.getElementById('showAddMarksForm')?.addEventListener('click', function() {
            const form = document.getElementById('addMarksForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });

        // Close marks form
        document.getElementById('closeMarksForm')?.addEventListener('click', function() {
            document.getElementById('addMarksForm').style.display = 'none';
        });

        // Toggle exam period form visibility
        document.getElementById('showAddExamPeriodForm')?.addEventListener('click', function() {
            const form = document.getElementById('addExamPeriodForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });

        // Toggle transcript generator form visibility
        document.getElementById('showTranscriptGenerator')?.addEventListener('click', function() {
            const form = document.getElementById('transcriptGeneratorForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });

        // Close transcript generator form
        document.getElementById('closeTranscriptGenerator')?.addEventListener('click', function() {
            document.getElementById('transcriptGeneratorForm').style.display = 'none';
        });

        // Auto-calculate grade based on mark
        document.getElementById('markValue')?.addEventListener('input', function() {
            const mark = parseInt(this.value);
            let grade = '';
            
            if (mark >= 90) grade = 'A+';
            else if (mark >= 80) grade = 'A';
            else if (mark >= 70) grade = 'B';
            else if (mark >= 60) grade = 'C';
            else if (mark >= 50) grade = 'D';
            else grade = 'F';
            
            document.getElementById('gradeValue').value = grade;
        });

        // Course Students Detail View functionality
        document.querySelectorAll('.action-icon.view').forEach(button => {
            button.addEventListener('click', function() {
                const courseName = this.getAttribute('data-course');
                if (courseName) {
                    document.getElementById('selectedCourseName').textContent = courseName;
                    
                    // Show the detail view as a modal
                    document.getElementById('courseStudentsDetail').style.display = 'block';
                    document.getElementById('courseStudentsDetail').classList.add('modal-active');
                    document.getElementById('modalOverlay').style.display = 'block';
                    
                    // Populate the table with student data for this course
                    const tableBody = document.getElementById('courseStudentsTableBody');
                    tableBody.innerHTML = ''; // Clear existing data
                    
                    // Sample data mapping
                    const studentData = {
                        'Introduction to Programming': [
                            { id: 'ST2025001', name: 'Jane Smith', mark: 89, grade: 'A', period: 'Semester 1 - 2025' },
                            { id: 'ST2025002', name: 'Mark Johnson', mark: 75, grade: 'B', period: 'Semester 1 - 2025' },
                            { id: 'ST2025006', name: 'Alex Turner', mark: 82, grade: 'A', period: 'Semester 1 - 2025' },
                            { id: 'ST2025010', name: 'Priya Sharma', mark: 95, grade: 'A+', period: 'Semester 1 - 2025' }
                        ],
                        'Data Structures & Algorithms': [
                            { id: 'ST2025003', name: 'Sarah Williams', mark: 92, grade: 'A', period: 'Semester 1 - 2025' },
                            { id: 'ST2025008', name: 'John Parker', mark: 78, grade: 'B', period: 'Semester 1 - 2025' },
                            { id: 'ST2025012', name: 'Emma Richards', mark: 85, grade: 'A', period: 'Semester 1 - 2025' }
                        ],
                        'Database Management': [
                            { id: 'ST2025004', name: 'Michael Brown', mark: 68, grade: 'C', period: 'Mid-Term Exams - 2025' },
                            { id: 'ST2025007', name: 'Lisa Chen', mark: 77, grade: 'B', period: 'Mid-Term Exams - 2025' },
                            { id: 'ST2025011', name: 'David Wilson', mark: 73, grade: 'B', period: 'Mid-Term Exams - 2025' }
                        ],
                        'Web Development': [
                            { id: 'ST2025005', name: 'Emily Davis', mark: 95, grade: 'A+', period: 'First Quarter Assessment - 2025' },
                            { id: 'ST2025009', name: 'Thomas Moore', mark: 88, grade: 'A', period: 'First Quarter Assessment - 2025' },
                            { id: 'ST2025013', name: 'Sophie Grant', mark: 91, grade: 'A', period: 'First Quarter Assessment - 2025' }
                        ],
                        'Computer Networks': [
                            { id: 'ST2025014', name: 'Ryan Martinez', mark: 79, grade: 'B', period: 'Semester 1 - 2025' },
                            { id: 'ST2025015', name: 'Olivia Johnson', mark: 84, grade: 'A', period: 'Semester 1 - 2025' },
                            { id: 'ST2025016', name: 'James Lee', mark: 76, grade: 'B', period: 'Semester 1 - 2025' }
                        ]
                    };
                    
                    // Get the students for the selected course
                    const students = studentData[courseName] || [];
                    
                    // Update the course details
                    document.getElementById('detailCourseUnit').textContent = courseName === 'Introduction to Programming' ? 'CU2025001' : 
                                                           courseName === 'Data Structures & Algorithms' ? 'CU2025002' :
                                                           courseName === 'Database Management' ? 'CU2025003' :
                                                           courseName === 'Web Development' ? 'CU2025004' : 'CU2025005';
                                                   
                    document.getElementById('detailTeacher').textContent = courseName === 'Introduction to Programming' ? 'Dr. Robert Chen' : 
                                                  courseName === 'Data Structures & Algorithms' ? 'Prof. Lisa Watkins' :
                                                  courseName === 'Database Management' ? 'Dr. Michael Patel' :
                                                  courseName === 'Web Development' ? 'Prof. Sarah Johnson' : 'Dr. James Wilson';
                                                  
                    document.getElementById('detailStudentCount').textContent = students.length;
                    
                    // Calculate average mark
                    const totalMarks = students.reduce((sum, student) => sum + student.mark, 0);
                    const averageMark = students.length > 0 ? (totalMarks / students.length).toFixed(1) : '0.0';
                    document.getElementById('detailAverageMark').textContent = averageMark;
                    
                    // Add students to table
                    students.forEach(student => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${student.id}</td>
                            <td>${student.name}</td>
                            <td>${student.mark}</td>
                            <td>${student.grade}</td>
                            <td>${student.period}</td>
                            <td>
                                <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                }
            });
        });

        // View toggle functionality
        document.querySelectorAll('.view-toggle .toggle-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.view-toggle .toggle-btn').forEach(btn => 
                    btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get the view type
                const viewType = this.getAttribute('data-view');
                
                // Hide all modals
                document.getElementById('courseStudentsDetail').style.display = 'none';
                document.getElementById('studentDetailView').style.display = 'none';
                document.getElementById('modalOverlay').style.display = 'none';
                
                // Show the appropriate table based on the selected view
                if (viewType === 'course') {
                    document.getElementById('courseViewCard').style.display = 'block';
                    document.getElementById('studentViewCard').style.display = 'none';
                } else {
                    document.getElementById('courseViewCard').style.display = 'none';
                    document.getElementById('studentViewCard').style.display = 'block';
                }
            });
        });

        // Student detail view functionality
        document.querySelectorAll('#studentViewCard .action-icon.view').forEach(button => {
            button.addEventListener('click', function() {
                const studentId = this.getAttribute('data-student');
                const studentName = this.getAttribute('data-name');
                document.getElementById('selectedStudentName').textContent = studentName;
                
                // Show the detail view as a modal
                document.getElementById('studentDetailView').style.display = 'block';
                document.getElementById('studentDetailView').classList.add('modal-active');
                document.getElementById('modalOverlay').style.display = 'block';
                
                // Populate the table with course data for this student
                const tableBody = document.getElementById('studentCoursesTableBody');
                tableBody.innerHTML = ''; // Clear existing data
                
                // Sample data mapping for students and their courses
                const courseData = {
                    'ST2025001': [
                        { unit: 'CU2025001', name: 'Introduction to Programming', teacher: 'Dr. Robert Chen', mark: 89, grade: 'A', period: 'Semester 1 - 2025' },
                        { unit: 'CU2025002', name: 'Data Structures & Algorithms', teacher: 'Prof. Lisa Watkins', mark: 85, grade: 'A', period: 'Semester 1 - 2025' },
                        { unit: 'CU2025004', name: 'Web Development', teacher: 'Prof. Sarah Johnson', mark: 92, grade: 'A', period: 'First Quarter Assessment - 2025' }
                    ],
                    'ST2025002': [
                        { unit: 'CU2025001', name: 'Introduction to Programming', teacher: 'Dr. Robert Chen', mark: 75, grade: 'B', period: 'Semester 1 - 2025' },
                        { unit: 'CU2025003', name: 'Database Management', teacher: 'Dr. Michael Patel', mark: 70, grade: 'B', period: 'Mid-Term Exams - 2025' }
                    ],
                    'ST2025003': [
                        { unit: 'CU2025001', name: 'Introduction to Programming', teacher: 'Dr. Robert Chen', mark: 88, grade: 'A', period: 'Semester 1 - 2025' },
                        { unit: 'CU2025002', name: 'Data Structures & Algorithms', teacher: 'Prof. Lisa Watkins', mark: 92, grade: 'A', period: 'Semester 1 - 2025' },
                        { unit: 'CU2025003', name: 'Database Management', teacher: 'Dr. Michael Patel', mark: 85, grade: 'A', period: 'Mid-Term Exams - 2025' },
                        { unit: 'CU2025005', name: 'Computer Networks', teacher: 'Dr. James Wilson', mark: 90, grade: 'A', period: 'Semester 1 - 2025' }
                    ],
                    'ST2025004': [
                        { unit: 'CU2025001', name: 'Introduction to Programming', teacher: 'Dr. Robert Chen', mark: 65, grade: 'C', period: 'Semester 1 - 2025' },
                        { unit: 'CU2025003', name: 'Database Management', teacher: 'Dr. Michael Patel', mark: 68, grade: 'C', period: 'Mid-Term Exams - 2025' },
                        { unit: 'CU2025004', name: 'Web Development', teacher: 'Prof. Sarah Johnson', mark: 70, grade: 'B', period: 'First Quarter Assessment - 2025' }
                    ],
                    'ST2025005': [
                        { unit: 'CU2025004', name: 'Web Development', teacher: 'Prof. Sarah Johnson', mark: 95, grade: 'A+', period: 'First Quarter Assessment - 2025' },
                        { unit: 'CU2025005', name: 'Computer Networks', teacher: 'Dr. James Wilson', mark: 92, grade: 'A', period: 'Semester 1 - 2025' }
                    ]
                };
                
                // Get the courses for the selected student
                const courses = courseData[studentId] || [];
                
                // Update the student details
                document.getElementById('detailStudentId').textContent = studentId;
                document.getElementById('detailCourseCount').textContent = courses.length;
                
                // Calculate GPA
                let totalGradePoints = 0;
                courses.forEach(course => {
                    const mark = course.mark;
                    let gradePoint = 0;
                    
                    if (mark >= 90) gradePoint = 4.0;
                    else if (mark >= 80) gradePoint = 3.7;
                    else if (mark >= 70) gradePoint = 3.0;
                    else if (mark >= 60) gradePoint = 2.0;
                    else if (mark >= 50) gradePoint = 1.0;
                    else gradePoint = 0.0;
                    
                    totalGradePoints += gradePoint;
                });
                
                const gpa = courses.length > 0 ? (totalGradePoints / courses.length).toFixed(1) : '0.0';
                document.getElementById('detailGPA').textContent = gpa;
                
                // Determine overall grade based on GPA
                let overallGrade = '';
                const gpaNum = parseFloat(gpa);
                if (gpaNum >= 3.7) overallGrade = 'A';
                else if (gpaNum >= 3.0) overallGrade = 'B';
                else if (gpaNum >= 2.0) overallGrade = 'C';
                else if (gpaNum >= 1.0) overallGrade = 'D';
                else overallGrade = 'F';
                
                document.getElementById('detailOverallGrade').textContent = overallGrade;
                
                // Add courses to table
                courses.forEach(course => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${course.unit}</td>
                        <td>${course.name}</td>
                        <td>${course.teacher}</td>
                        <td>${course.mark}</td>
                        <td>${course.grade}</td>
                        <td>${course.period}</td>
                    `;
                    tableBody.appendChild(row);
                });
            });
        });

        // Close student detail view when X button is clicked
        document.getElementById('closeStudentView')?.addEventListener('click', function() {
            document.getElementById('studentDetailView').style.display = 'none';
            document.getElementById('modalOverlay').style.display = 'none';
        });

        // Close detail view when X button is clicked
        document.getElementById('closeDetailView')?.addEventListener('click', function() {
            document.getElementById('courseStudentsDetail').style.display = 'none';
            document.getElementById('modalOverlay').style.display = 'none';
        });

        // Close modal when clicking on overlay
        document.getElementById('modalOverlay')?.addEventListener('click', function() {
            document.getElementById('courseStudentsDetail').style.display = 'none';
            document.getElementById('studentDetailView').style.display = 'none';
            document.getElementById('modalOverlay').style.display = 'none';
        });

        // Close add marks form when reset button is clicked
        document.querySelectorAll('.reset-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.card');
                if (form.id === 'addMarksForm' || form.id === 'addExamPeriodForm') {
                    form.style.display = 'none';
                }
            });
        });

        // File upload display
        document.getElementById('csvFile')?.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'No file chosen';
            document.querySelector('.file-name').textContent = fileName;
        });

        // Mark entry tabs functionality
        document.querySelectorAll('.mark-entry-tabs .tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and content
                document.querySelectorAll('.mark-entry-tabs .tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('#addMarksForm .tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(tabId + '-content').classList.add('active');
            });
        });

        // Form submission handlers (prevent default for demo)
        document.getElementById('examPeriodForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Exam period saved successfully!');
            this.reset();
            document.getElementById('addExamPeriodForm').style.display = 'none';
        });

        document.getElementById('manualMarksForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Mark saved successfully!');
            this.reset();
            document.getElementById('addMarksForm').style.display = 'none';
        });

        document.getElementById('bulkMarksForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Marks uploaded successfully!');
            this.reset();
            document.querySelector('.file-name').textContent = 'No file chosen';
            document.getElementById('addMarksForm').style.display = 'none';
        });

        document.getElementById('transcriptForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Transcript generated successfully!');
            document.getElementById('transcriptGeneratorForm').style.display = 'none';
        });

        // Initialize the page with exam periods tab active
        document.querySelector('.tabs-container .tab.active').click();

        // Replace or add this JavaScript to handle Excel file uploads

// File upload display - update to handle both CSV and Excel files
document.getElementById('csvFile')?.addEventListener('change', function() {
    const fileName = this.files[0]?.name || 'No file chosen';
    this.closest('.file-upload').querySelector('.file-name').textContent = fileName;
});

document.getElementById('excelFile')?.addEventListener('change', function() {
    const fileName = this.files[0]?.name || 'No file chosen';
    this.closest('.file-upload').querySelector('.file-name').textContent = fileName;
});

// Form submission handler for the bulk marks form
document.getElementById('bulkMarksForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get the selected exam period and course unit
    const examPeriod = document.getElementById('bulkExamPeriod').value;
    const courseUnit = document.getElementById('bulkCourseUnit').value;
    
    // Get the uploaded file
    const fileInput = document.getElementById('excelFile');
    const file = fileInput.files[0];
    
    if (!examPeriod || !courseUnit) {
        alert('Please select both an exam period and course unit.');
        return;
    }
    
    if (!file) {
        alert('Please select an Excel file to upload.');
        return;
    }
    
    // Here you would normally process the Excel file
    // For this demo, we'll just show a success message
    
    alert('Marks uploaded successfully from Excel file!');
    this.reset();
    document.querySelector('.file-name').textContent = 'No file chosen';
    document.getElementById('addMarksForm').style.display = 'none';
});

// Function to handle Excel file processing (placeholder)
function processExcelFile(file) {
    // In a real implementation, you would:
    // 1. Use a library like SheetJS (xlsx) to read the Excel file
    // 2. Extract the student data
    // 3. Validate the data format
    // 4. Submit the data to the server
    
    // This would typically be done with an AJAX request or fetch API
    
    console.log("Processing Excel file:", file.name);
    // Additional processing code would go here
}

    </script>
</body>
</html>