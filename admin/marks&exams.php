<?php
// marks&exams.php - Complete implementation

session_start();
include 'dbconnect.php';

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

    


// Process bulk upload if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    $examPeriod = $_POST['examPeriod'];
    $courseUnit = $_POST['courseUnit'];

    // Validate file
    if ($_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
        $uploadError = 'Error uploading file. Please try again.';
    } else {
        $fileTmpPath = $_FILES['excelFile']['tmp_name'];
        $fileName = $_FILES['excelFile']['name'];
        $fileSize = $_FILES['excelFile']['size'];
        $fileType = $_FILES['excelFile']['type'];

        // Check file size (max 5MB)
        if ($fileSize > 5 * 1024 * 1024) {
            $uploadError = 'File size exceeds the maximum limit of 5MB.';
        } else {
            require_once 'vendor/autoload.php'; // Include PHPExcel or PhpSpreadsheet library

            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                // Validate header row
                $header = array_map('strtolower', $rows[0]);
                if ($header !== ['student id', 'student name', 'mark']) {
                    $uploadError = 'Invalid file format. Please use the provided template.';
                } else {
                    $successCount = 0;
                    $errorCount = 0;

                    // Process rows
                    foreach (array_slice($rows, 1) as $row) {
                        $studentId = $row[0];
                        $studentName = $row[1];
                        $mark = (float)$row[2];

                        // Validate data
                        if (empty($studentId) || empty($studentName) || $mark < 0 || $mark > 100) {
                            $errorCount++;
                            continue;
                        }

                        // Calculate grade
                        $grade = '';
                        if ($mark >= 90) $grade = 'A+';
                        else if ($mark >= 80) $grade = 'A';
                        else if ($mark >= 70) $grade = 'B';
                        else if ($mark >= 60) $grade = 'C';
                        else if ($mark >= 50) $grade = 'D';
                        else $grade = 'F';

                        // Insert or update record
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

                    // Add to audit trail
                    $user = $_SESSION['user_name'] . ' (' . $_SESSION['user_role'] . ')';
                    $action = "Bulk Upload";
                    $details = "Uploaded $successCount marks for course unit $courseUnit in exam period $examPeriod";

                    $auditStmt = $conn->prepare("INSERT INTO audit_trail (user, action, details, created_at) VALUES (?, ?, ?, NOW())");
                    $auditStmt->bind_param("sss", $user, $action, $details);
                    $auditStmt->execute();

                    $uploadSuccess = "$successCount marks uploaded successfully. $errorCount errors occurred.";
                }
            } catch (Exception $e) {
                $uploadError = 'Error processing file: ' . $e->getMessage();
            }
        }
    }
}

// Process manual entry if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manual_submit'])) {
    $examPeriod = $_POST['examPeriod'];
    $courseUnit = $_POST['courseUnit'];
    $studentId = $_POST['studentId'];
    $studentName = $_POST['studentName'];
    $mark = (float)$_POST['markValue'];
    $comments = $_POST['comments'];
    
    // Validate data
    if (empty($studentId) || empty($studentName) || $mark < 0 || $mark > 100) {
        $manualError = 'Invalid data. Please check all fields.';
    } else {
        // Calculate grade
        $grade = '';
        if ($mark >= 90) $grade = 'A+';
        else if ($mark >= 80) $grade = 'A';
        else if ($mark >= 70) $grade = 'B';
        else if ($mark >= 60) $grade = 'C';
        else if ($mark >= 50) $grade = 'D';
        else $grade = 'F';
        
        // Insert or update record
        $stmt = $conn->prepare("INSERT INTO student_marks 
                              (student_id, student_name, course_unit_id, exam_period_id, mark, grade, comments, updated_by, updated_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
                              ON DUPLICATE KEY UPDATE 
                              mark = VALUES(mark), 
                              grade = VALUES(grade),
                              comments = VALUES(comments),
                              updated_by = VALUES(updated_by),
                              updated_at = VALUES(updated_at)");
        
        $stmt->bind_param("ssidsssi", $studentId, $studentName, $courseUnit, $examPeriod, $mark, $grade, $comments, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $manualSuccess = "Mark saved successfully for $studentName";
            
            // Add to audit trail
            $user = $_SESSION['user_name'] . ' (' . $_SESSION['user_role'] . ')';
            $action = "Manual Mark Entry";
            $details = "Added mark $mark for $studentId in course unit $courseUnit";
            
            $auditStmt = $conn->prepare("INSERT INTO audit_trail (user, action, details, created_at) VALUES (?, ?, ?, NOW())");
            $auditStmt->bind_param("sss", $user, $action, $details);
            $auditStmt->execute();
        } else {
            $manualError = 'Error saving mark: ' . $conn->error;
        }
    }
}

// Get exam periods for dropdowns
$examPeriods = [];
$result = $conn->query("SELECT exam_period_id, name FROM exam_periods ORDER BY start_date DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $examPeriods[$row['exam_period_id']] = $row['name'];
    }
}

// Get course units for dropdowns
$courseUnits = [];
$result = $conn->query("SELECT unit_id, name FROM course_units ORDER BY name");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courseUnits[$row['id']] = $row['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Marks & Exams</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            position: fixed;
            height: 100%;
            padding: 20px 0;
            transition: all 0.3s;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-logo {
            margin-bottom: 10px;
        }
        
        .institute-name {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .institute-motto {
            font-size: 12px;
            opacity: 0.8;
            margin-bottom: 15px;
        }
        
        .support-button {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            width: 100%;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .support-button:hover {
            background: #c0392b;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }
        
        .sidebar-menu li {
            padding: 10px 20px;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
        }
        
        .sidebar-menu li:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-menu li.active {
            background: #8B1818;
        }
        
        .sidebar-menu li i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-menu li span {
            flex: 1;
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .welcome-banner {
            background: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .welcome-text h1 {
            font-size: 24px;
            color: #8B1818;
            margin-bottom: 5px;
        }
        
        .welcome-text p {
            color: #666;
            margin-bottom: 10px;
        }
        
        .date-display {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #666;
        }
        
        .date-display i {
            margin-right: 5px;
        }
        
        .time-display {
            margin-left: 15px;
        }
        
        .weather-widget {
            display: inline-flex;
            align-items: center;
            margin-left: 15px;
        }
        
        .weather-icon {
            color: #f39c12;
            margin-right: 5px;
        }
        
        .temperature {
            font-weight: 600;
        }
        
        .user-section {
            display: flex;
            align-items: center;
        }
        
        .notification-bell {
            position: relative;
            margin-right: 20px;
            font-size: 18px;
            color: #666;
            cursor: pointer;
        }
        
        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: #8B1818;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
        
        .user-info {
            line-height: 1.3;
        }
        
        .role {
            font-size: 12px;
            color: #666;
        }
        
        /* Search Bar */
        .search-bar {
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        
        .search-bar input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        /* Tabs */
        .tabs-container {
            display: flex;
            border-bottom: 1px solid #ddd;
            padding: 0 20px;
            background: white;
        }
        
        .tab {
            padding: 15px 20px;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .tab:hover {
            color: #333;
        }
        
        .tab.active {
            color: #8B1818;
            border-bottom-color: #8B1818;
        }
        
        /* Tab Content */
        .tab-content {
            padding: 20px;
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h3 {
            font-size: 18px;
            color: #333;
            display: flex;
            align-items: center;
        }
        
        .card-header h3 i {
            margin-right: 10px;
            color: #8B1818;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Forms */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group label.required:after {
            content: '*';
            color: #d32f2f;
            margin-left: 4px;
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        
        .hint {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }
        
        /* File upload */
        .file-upload-container {
            margin-top: 10px;
        }
        
        .file-upload {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .file-label {
            background: #8B1818;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
            display: inline-flex;
            align-items: center;
            transition: background 0.3s;
        }
        
        .file-label:hover {
            background: #6d1212;
        }
        
        .file-label i {
            margin-right: 8px;
        }
        
        .file-name {
            color: #666;
            font-size: 14px;
        }
        
        .file-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .file-requirements p {
            margin: 3px 0;
        }
        
        /* Upload instructions */
        .upload-instructions {
            background: #f8f9fa;
            border-left: 4px solid #8B1818;
            padding: 15px;
            margin-top: 10px;
        }
        
        .upload-instructions h4 {
            margin-top: 0;
            color: #333;
        }
        
        .upload-instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .upload-instructions li {
            margin-bottom: 5px;
        }
        
        .download-template {
            display: inline-block;
            color: #8B1818;
            text-decoration: none;
            font-weight: 600;
            margin-top: 10px;
        }
        
        .download-template:hover {
            text-decoration: underline;
        }
        
        /* Form actions */
        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .submit-btn, .reset-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        
        .submit-btn {
            background: #8B1818;
            color: white;
        }
        
        .submit-btn:hover {
            background: #6d1212;
        }
        
        .reset-btn {
            background: #f0f0f0;
            color: #333;
        }
        
        .reset-btn:hover {
            background: #e0e0e0;
        }
        
        .submit-btn i, .reset-btn i {
            margin-right: 8px;
        }
        
        /* Search button */
        .search-btn {
            background: none;
            border: none;
            color: #8B1818;
            cursor: pointer;
            margin-left: 5px;
            font-size: 16px;
        }
        
        /* Buttons */
        .add-button {
            background: #8B1818;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }
        
        .add-button i {
            margin-right: 8px;
        }
        
        .add-button:hover {
            background: #6d1212;
        }
        
        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        
        .data-table th {
            background: #f8f9fa;
            text-align: left;
            padding: 12px 15px;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #ddd;
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .data-table tr:hover td {
            background: #f5f5f5;
        }
        
        /* Status badges */
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
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
        
        /* Action icons */
        .action-icon {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 0 5px;
            color: #555;
            padding: 5px;
            border-radius: 4px;
        }
        
        .action-icon:hover {
            background: #f0f0f0;
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
        
        /* Close button */
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #777;
            padding: 0 5px;
        }
        
        .close-btn:hover {
            color: #333;
        }
        
        /* Modal */
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
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1001;
            width: 80%;
            max-width: 900px;
            max-height: 80vh;
            overflow-y: auto;
            display: none;
        }
        
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            font-size: 20px;
            color: #333;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        /* Alerts */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        
        .pagination-btn {
            background: #f0f0f0;
            border: none;
            padding: 8px 12px;
            margin: 0 5px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .pagination-btn:hover {
            background: #e0e0e0;
        }
        
        .pagination-info {
            margin: 0 10px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar-header {
                padding: 10px 5px;
            }
            
            .institute-name, .institute-motto, .support-button span {
                display: none;
            }
            
            .support-button {
                padding: 10px;
                text-align: center;
            }
            
            .sidebar-menu li span {
                display: none;
            }
            
            .sidebar-menu li i {
                margin-right: 0;
                font-size: 18px;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .modal {
                width: 95%;
            }
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
            <button class="support-button"><i class="fas fa-headset"></i> <span>Support</span></button>
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
            <li onclick="window.location.href='logout.php'"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>MARKS & EXAMS</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
                <div class="date-display">
                    <i class="fas fa-calendar-alt"></i> <span id="currentDate"></span>
                    <span class="time-display"><i class="fas fa-clock"></i> <span id="currentTime"></span></span>
                    <div class="weather-widget">
                        <i class="fas fa-sun weather-icon"></i>
                        <span class="temperature">26°C</span>
                    </div>
                </div>
            </div>
            <div class="user-section">
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                    <span class="notification-count">3</span>
                </div>
                <div class="user-profile">
                    <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?></div>
                    <div class="user-info">
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?><br>
                        <span class="role"><?php echo htmlspecialchars($_SESSION['user_role']); ?></span>
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
            <div class="card" id="addExamPeriodForm" style="display: none;">
                <div class="card-header">
                    <h3><i class="fas fa-plus-circle"></i> Add New Exam Period</h3>
                </div>
                <div class="card-body">
                    <form class="form-grid" id="examPeriodForm">
                        <div class="form-group">
                            <label for="examPeriodName" class="required">Exam Period Name</label>
                            <input type="text" id="examPeriodName" placeholder="e.g., Semester 1 - 2025" required>
                        </div>
                        <div class="form-group">
                            <label for="startDate" class="required">Start Date</label>
                            <input type="date" id="startDate" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate" class="required">End Date</label>
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
                            <?php
                            $result = $conn->query("SELECT * FROM exam_periods ORDER BY start_date DESC");
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $statusClass = '';
                                    $statusText = '';
                                    $currentDate = date('Y-m-d');
                                    
                                    if ($currentDate >= $row['start_date'] && $currentDate <= $row['end_date']) {
                                        $statusClass = 'ongoing';
                                        $statusText = 'Ongoing';
                                    } elseif ($currentDate < $row['start_date']) {
                                        $statusClass = 'upcoming';
                                        $statusText = 'Upcoming';
                                    } else {
                                        $statusClass = 'ended';
                                        $statusText = 'Ended';
                                    }
                                    
                                    echo "<tr>
                                        <td>{$row['name']}</td>
                                        <td>" . date('M j, Y', strtotime($row['start_date'])) . "</td>
                                        <td>" . date('M j, Y', strtotime($row['end_date'])) . "</td>
                                        <td><span class='status {$statusClass}'>{$statusText}</span></td>
                                        <td>
                                            <button class='action-icon edit' data-id='{$row['id']}'><i class='fas fa-edit'></i></button>
                                            <button class='action-icon delete' data-id='{$row['id']}'><i class='fas fa-trash'></i></button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No exam periods found</td></tr>";
                            }
                            ?>
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

            <?php if (isset($uploadSuccess)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $uploadSuccess; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($uploadError)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $uploadError; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($manualSuccess)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $manualSuccess; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($manualError)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $manualError; ?>
                </div>
            <?php endif; ?>

            <!-- Filters Section -->
            <div class="filters-container">
                <div class="filter-group">
                    <label for="examPeriodFilter">Exam Period:</label>
                    <select id="examPeriodFilter">
                        <option value="">All Periods</option>
                        <?php foreach ($examPeriods as $id => $name): ?>
                            <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="courseUnitFilter">Course Unit:</label>
                    <select id="courseUnitFilter">
                        <option value="">All Course Units</option>
                        <?php foreach ($courseUnits as $id => $name): ?>
                            <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                        <?php endforeach; ?>
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
                    
                    <!-- Manual Entry Tab -->
                    <div class="tab-content active" id="manual-entry-content">
                        <form class="form-grid" id="manualMarksForm" method="POST">
                            <input type="hidden" name="manual_submit" value="1">
                            <div class="form-group">
                                <label for="selectExamPeriod" class="required">Exam Period</label>
                                <select id="selectExamPeriod" name="examPeriod" required>
                                    <option value="">Select Exam Period</option>
                                    <?php foreach ($examPeriods as $id => $name): ?>
                                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="selectCourseUnit" class="required">Course Unit</label>
                                <select id="selectCourseUnit" name="courseUnit" required>
                                    <option value="">Select Course Unit</option>
                                    <?php foreach ($courseUnits as $id => $name): ?>
                                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentId" class="required">Student ID</label>
                                <div style="display: flex;">
                                    <input type="text" id="studentId" name="studentId" placeholder="Enter Student ID" required style="flex: 1;">
                                    <button type="button" class="search-btn" id="searchStudentBtn"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="studentName" class="required">Student Name</label>
                                <input type="text" id="studentName" name="studentName" placeholder="Enter Student Name" required>
                            </div>
                            <div class="form-group">
                                <label for="markValue" class="required">Mark (0-100)</label>
                                <input type="number" id="markValue" name="markValue" min="0" max="100" step="0.01" placeholder="Enter mark value" required>
                                <div class="hint">Enter value between 0 and 100</div>
                            </div>
                            <div class="form-group">
                                <label for="gradeValue" class="required">Grade</label>
                                <input type="text" id="gradeValue" readonly>
                            </div>
                            <div class="form-group full-width">
                                <label for="comments">Comments</label>
                                <textarea id="comments" name="comments" placeholder="Enter any additional comments about this mark"></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Save Mark</button>
                                <button type="reset" class="reset-btn"><i class="fas fa-undo"></i> Reset</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Bulk Upload Tab -->
                    <div class="tab-content" id="bulk-upload-content">
                        <form class="form-grid" id="bulkMarksForm" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="bulkExamPeriod" class="required">Exam Period</label>
                                <select id="bulkExamPeriod" name="examPeriod" required>
                                    <option value="">Select Exam Period</option>
                                    <?php foreach ($examPeriods as $id => $name): ?>
                                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bulkCourseUnit" class="required">Course Unit</label>
                                <select id="bulkCourseUnit" name="courseUnit" required>
                                    <option value="">Select Course Unit</option>
                                    <?php foreach ($courseUnits as $id => $name): ?>
                                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="excelFile" class="required">Upload Excel File</label>
                                <div class="file-upload-container">
                                    <div class="file-upload">
                                        <input type="file" id="excelFile" name="excelFile" accept=".xlsx, .xls" required>
                                        <label for="excelFile" class="file-label">
                                            <i class="fas fa-cloud-upload-alt"></i> 
                                            <span class="file-label-text">Choose Excel File</span>
                                        </label>
                                        <span class="file-name">No file chosen</span>
                                    </div>
                                    <div class="file-requirements">
                                        <p><i class="fas fa-info-circle"></i> Maximum file size: 5MB</p>
                                        <p><i class="fas fa-info-circle"></i> Accepted formats: .xlsx, .xls</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group full-width">
                                <div class="upload-instructions">
                                    <h4><i class="fas fa-file-excel"></i> Excel File Requirements:</h4>
                                    <ul>
                                        <li>File must contain these columns in order: <strong>Student ID, Student Name, Mark</strong></li>
                                        <li>First row should be headers (will be skipped during import)</li>
                                        <li>Marks must be numeric values between 0 and 100</li>
                                        <li>Student ID and Name cannot be empty</li>
                                    </ul>
                                    <a href="templates/marks_upload_template.xlsx" class="download-template">
                                        <i class="fas fa-download"></i> Download Excel Template
                                    </a>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="submit-btn"><i class="fas fa-upload"></i> Upload Marks</button>
                                <button type="reset" class="reset-btn"><i class="fas fa-undo"></i> Reset</button>
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
                                <?php foreach ($examPeriods as $id => $name): ?>
                                    <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                <?php endforeach; ?>
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

            <!-- Marks Table - Course View -->
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
                                <th>Average Mark</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("
                                SELECT c.id, c.code, c.name, 
                                       CONCAT(u.first_name, ' ', u.last_name) AS teacher_name,
                                       COUNT(sm.student_id) AS student_count,
                                       AVG(sm.mark) AS average_mark
                                FROM course_units c
                                LEFT JOIN users u ON c.teacher_id = u.id
                                LEFT JOIN student_marks sm ON c.id = sm.course_unit_id
                                GROUP BY c.id
                                ORDER BY c.name
                            ");
                            
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $averageMark = $row['average_mark'] ? number_format($row['average_mark'], 1) : 'N/A';
                                    
                                    echo "<tr>
                                        <td>{$row['code']}</td>
                                        <td>{$row['name']}</td>
                                        <td>{$row['teacher_name']}</td>
                                        <td>{$row['student_count']}</td>
                                        <td>{$averageMark}</td>
                                        <td>
                                            <button class='action-icon view' data-course='{$row['name']}' data-id='{$row['id']}'><i class='fas fa-eye'></i></button>
                                            <button class='action-icon edit' data-id='{$row['id']}'><i class='fas fa-edit'></i></button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No course units found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    
                    <div class="pagination">
                        <button class="pagination-btn prev"><i class="fas fa-chevron-left"></i></button>
                        <span class="pagination-info">Page 1 of 2</span>
                        <button class="pagination-btn next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- Marks Table - Student View -->
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
                            <?php
                            $result = $conn->query("
                                SELECT s.id, s.student_id, CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                                       COUNT(sm.course_unit_id) AS course_count,
                                       AVG(CASE 
                                           WHEN sm.mark >= 90 THEN 4.0
                                           WHEN sm.mark >= 80 THEN 3.7
                                           WHEN sm.mark >= 70 THEN 3.0
                                           WHEN sm.mark >= 60 THEN 2.0
                                           WHEN sm.mark >= 50 THEN 1.0
                                           ELSE 0.0
                                       END) AS gpa
                                FROM students s
                                LEFT JOIN student_marks sm ON s.id = sm.student_id
                                GROUP BY s.id
                                ORDER BY student_name
                            ");
                            
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $gpa = $row['gpa'] ? number_format($row['gpa'], 2) : 'N/A';
                                    
                                    echo "<tr>
                                        <td>{$row['student_id']}</td>
                                        <td>{$row['student_name']}</td>
                                        <td>{$row['course_count']}</td>
                                        <td>{$gpa}</td>
                                        <td>
                                            <button class='action-icon view' data-student='{$row['student_id']}' data-name='{$row['student_name']}'><i class='fas fa-eye'></i></button>
                                            <button class='action-icon edit' data-id='{$row['id']}'><i class='fas fa-edit'></i></button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No students found</td></tr>";
                            }
                            ?>
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
                            <?php
                            $result = $conn->query("
                                SELECT * FROM audit_trail 
                                WHERE action LIKE '%Mark%' OR action LIKE '%Exam%'
                                ORDER BY created_at DESC
                                LIMIT 10
                            ");
                            
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . date('M j, Y - h:i A', strtotime($row['created_at'])) . "</td>
                                        <td>{$row['user']}</td>
                                        <td>{$row['action']}</td>
                                        <td>{$row['details']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No audit records found</td></tr>";
                            }
                            ?>
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
            const mark = parseFloat(this.value);
            let grade = '';
            
            if (!isNaN(mark)) {
                if (mark >= 90) grade = 'A+';
                else if (mark >= 80) grade = 'A';
                else if (mark >= 70) grade = 'B';
                else if (mark >= 60) grade = 'C';
                else if (mark >= 50) grade = 'D';
                else if (mark >= 0) grade = 'F';
            }
            
            document.getElementById('gradeValue').value = grade;
        });

        // Course Students Detail View functionality
        document.querySelectorAll('#courseViewCard .action-icon.view').forEach(button => {
            button.addEventListener('click', function() {
                const courseId = this.getAttribute('data-id');
                const courseName = this.getAttribute('data-course');
                document.getElementById('selectedCourseName').textContent = courseName;
                
                // Show the detail view as a modal
                document.getElementById('courseStudentsDetail').style.display = 'block';
                document.getElementById('modalOverlay').style.display = 'block';
                
                // Fetch student data for this course via AJAX
                fetch(`get_course_students.php?course_id=${courseId}`)
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('courseStudentsTableBody');
                        tableBody.innerHTML = '';
                        
                        // Update the course details
                        document.getElementById('detailCourseUnit').textContent = data.course_code || 'N/A';
                        document.getElementById('detailTeacher').textContent = data.teacher_name || 'N/A';
                        document.getElementById('detailStudentCount').textContent = data.students.length;
                        document.getElementById('detailAverageMark').textContent = data.average_mark ? data.average_mark.toFixed(1) : 'N/A';
                        
                        // Add students to table
                        data.students.forEach(student => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${student.student_id}</td>
                                <td>${student.student_name}</td>
                                <td>${student.mark}</td>
                                <td>${student.grade}</td>
                                <td>${student.exam_period}</td>
                                <td>
                                    <button class="action-icon edit" data-id="${student.id}"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete" data-id="${student.id}"><i class="fas fa-trash"></i></button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching course students:', error);
                        alert('Error loading student data');
                    });
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
                document.getElementById('modalOverlay').style.display = 'block';
                
                // Fetch course data for this student via AJAX
                fetch(`get_student_courses.php?student_id=${studentId}`)
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('studentCoursesTableBody');
                        tableBody.innerHTML = '';
                        
                        // Update the student details
                        document.getElementById('detailStudentId').textContent = studentId;
                        document.getElementById('detailCourseCount').textContent = data.courses.length;
                        document.getElementById('detailGPA').textContent = data.gpa ? data.gpa.toFixed(2) : 'N/A';
                        document.getElementById('detailOverallGrade').textContent = data.overall_grade || 'N/A';
                        
                        // Add courses to table
                        data.courses.forEach(course => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${course.course_code}</td>
                                <td>${course.course_name}</td>
                                <td>${course.teacher_name}</td>
                                <td>${course.mark}</td>
                                <td>${course.grade}</td>
                                <td>${course.exam_period}</td>
                            `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching student courses:', error);
                        alert('Error loading course data');
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
        document.getElementById('excelFile')?.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'No file chosen';
            this.closest('.file-upload').querySelector('.file-name').textContent = fileName;
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

        // Student search functionality
        document.getElementById('searchStudentBtn')?.addEventListener('click', function() {
            const studentId = document.getElementById('studentId').value.trim();
            if (!studentId) {
                alert('Please enter a student ID to search.');
                return;
            }
            
            // Fetch student details via AJAX
            fetch(`get_student.php?student_id=${studentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('studentName').value = data.student_name;
                    } else {
                        alert('Student not found. Please enter details manually.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching student:', error);
                    alert('Error searching for student');
                });
        });

        // Form submission handlers
        document.getElementById('examPeriodForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('save_exam_period.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Exam period saved successfully!');
                    this.reset();
                    document.getElementById('addExamPeriodForm').style.display = 'none';
                    location.reload(); // Refresh to show new exam period
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('An error occurred: ' + error.message);
            });
        });

        document.getElementById('manualMarksForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('save_mark.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Mark saved successfully!');
                    this.reset();
                    document.getElementById('addMarksForm').style.display = 'none';
                    location.reload(); // Refresh to show new mark
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('An error occurred: ' + error.message);
            });
        });

        document.getElementById('bulkMarksForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('.submit-btn');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;
            
            fetch('marks&exams.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch {
                            return { success: false, message: 'Unexpected response from server' };
                        }
                    });
                }
            })
            .then(data => {
                if (data && data.success) {
                    alert(data.message);
                    this.reset();
                    document.querySelector('.file-name').textContent = 'No file chosen';
                    document.getElementById('addMarksForm').style.display = 'none';
                    location.reload();
                } else if (data) {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('An error occurred: ' + error.message);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        document.getElementById('transcriptForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const studentId = document.getElementById('transcriptStudentId').value;
            const examPeriod = document.getElementById('transcriptExamPeriod').value;
            
            if (!studentId) {
                alert('Please enter a student ID');
                return;
            }
            
            // Generate transcript PDF
            window.open(`generate_transcript.php?student_id=${studentId}&exam_period=${examPeriod}`, '_blank');
        });

        // Initialize the page with exam periods tab active
        document.querySelector('.tabs-container .tab.active').click();
    </script>

</body>
</html>