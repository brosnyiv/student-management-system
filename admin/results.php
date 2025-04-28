
<?php

session_start(); // Start the session
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'dbconnect.php'; // Include the database connection file

// Get filter parameters from request
$course_id = isset($_GET['course']) ? $_GET['course'] : '';
$semester_id = isset($_GET['semester']) ? $_GET['semester'] : '';
$academic_year_id = isset($_GET['academic-year']) ? $_GET['academic-year'] : '';
$search_term = isset($_GET['student-search']) ? $_GET['student-search'] : '';

// Build the base query
$query = "SELECT 
            sr.semester_result_id,
            s.student_id,
            CONCAT(s.first_name, ' ', s.surname) AS student_name,
            c.course_name,
            sem.semester_number AS semester,
            ay.year_number AS academic_year,
            sr.average_score,
            sr.final_grade,
            sr.status
          FROM semester_result sr
          JOIN students s ON sr.student_id = s.student_id
          JOIN courses c ON sr.course_id = c.course_id
          JOIN semesters sem ON sr.semester_id = sem.semester_id
          JOIN academic_years ay ON sr.academic_year_id = ay.academic_year_id
          WHERE 1=1";

// Add filters if provided
if (!empty($course_id)) {
    $course_id = mysqli_real_escape_string($conn, $course_id);
    $query .= " AND sr.course_id = '$course_id'";
}
if (!empty($semester_id)) {
    $semester_id = mysqli_real_escape_string($conn, $semester_id);
    $query .= " AND sr.semester_id = '$semester_id'";
}
if (!empty($academic_year_id)) {
    $academic_year_id = mysqli_real_escape_string($conn, $academic_year_id);
    $query .= " AND sr.academic_year_id = '$academic_year_id'";
}
if (!empty($search_term)) {
    $search_term = mysqli_real_escape_string($conn, $search_term);
    $query .= " AND (s.student_id LIKE '%$search_term%' OR s.first_name LIKE '%$search_term%' OR s.surname LIKE '%$search_term%')";
}

// Order by student name
$query .= " ORDER BY s.surname, s.first_name";

// Execute the query
$result = mysqli_query($conn, $query);
$results = [];

// Fetch all results
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }
}

// Get summary statistics
$summaryQuery = "SELECT 
                    COUNT(*) AS total_students,
                    SUM(CASE WHEN status = 'Passed' THEN 1 ELSE 0 END) AS passed_students,
                    SUM(CASE WHEN status = 'Failed' THEN 1 ELSE 0 END) AS failed_students,
                    AVG(average_score) AS average_score
                 FROM semester_result";
$summaryResult = mysqli_query($conn, $summaryQuery);
$summary = mysqli_fetch_assoc($summaryResult);

// Get courses for dropdown
$coursesResult = mysqli_query($conn, "SELECT course_id, course_name FROM courses");
$courses = [];
while ($row = mysqli_fetch_assoc($coursesResult)) {
    $courses[] = $row;
}

// Get semesters for dropdown
$semestersResult = mysqli_query($conn, "SELECT semester_id, semester_number FROM semesters");
$semesters = [];
while ($row = mysqli_fetch_assoc($semestersResult)) {
    $semesters[] = $row;
}

// Get academic years for dropdown
$academicYearsResult = mysqli_query($conn, "SELECT academic_year_id, year_number FROM academic_years ORDER BY year_number DESC");
$academicYears = [];
while ($row = mysqli_fetch_assoc($academicYearsResult)) {
    $academicYears[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Monaco Institute Results</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 8px;
            color: var(--primary-color);
        }

        .filter-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-group {
            flex: 1 1 200px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #666;
        }

        .filter-group select, 
        .filter-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            background-color: white;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .filter-button, 
        .reset-button {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .filter-button {
            background-color: var(--primary-color);
            color: white;
        }

        .filter-button:hover {
            background-color: var(--primary-light);
        }

        .reset-button {
            background-color: #e9ecef;
            color: var(--text-color);
        }

        .reset-button:hover {
            background-color: #dee2e6;
        }

        .results-table {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #f8f9fa;
            color: var(--text-color);
            font-weight: 600;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid var(--border-color);
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }

        tr:hover {
            background-color: var(--hover-color);
        }

        .student-photo {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--primary-color);
        }

        .student-info {
            display: flex;
            align-items: center;
        }

        .grade {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 3px;
            text-align: center;
            width: 40px;
            display: inline-block;
        }

        .grade-a {
            background-color: rgba(46, 204, 113, 0.2);
            color: #27ae60;
        }

        .grade-b {
            background-color: rgba(52, 152, 219, 0.2);
            color: #2980b9;
        }

        .grade-c {
            background-color: rgba(241, 196, 15, 0.2);
            color: #f39c12;
        }

        .grade-d {
            background-color: rgba(230, 126, 34, 0.2);
            color: #d35400;
        }

        .grade-f {
            background-color: rgba(231, 76, 60, 0.2);
            color: #c0392b;
        }

        .action-icons {
            display: flex;
            gap: 10px;
        }

        .action-icon {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .view {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .edit {
            background-color: rgba(243, 156, 18, 0.1);
            color: #f39c12;
        }

        .print {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }

        .view:hover {
            background-color: rgba(52, 152, 219, 0.2);
        }

        .edit:hover {
            background-color: rgba(243, 156, 18, 0.2);
        }

        .print:hover {
            background-color: rgba(46, 204, 113, 0.2);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 5px;
        }

        .pagination button {
            width: 35px;
            height: 35px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .pagination button.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination button:hover:not(.active) {
            background-color: var(--hover-color);
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .summary-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .summary-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 14px;
            color: #666;
        }

        .icon-pass {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }

        .icon-fail {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .icon-average {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .icon-total {
            background-color: rgba(156, 39, 176, 0.1);
            color: #9c27b0;
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            color: #666;
            font-size: 14px;
            border-top: 1px solid var(--border-color);
            position: fixed;
            bottom: 0;
            left: 280px;
            right: 0;
            background-color: var(--bg-color);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar-menu li span,
            .institute-name,
            .institute-motto,
            .support-button span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .footer {
                left: 70px;
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
            <button class="support-button"><i class="fas fa-headset"></i> Support</button>
        </div>
        <ul class="sidebar-menu">
            <li onclick="window.location.href='dash.php'"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></li>
            <li onclick="window.location.href='student.php'"><i class="fas fa-user-graduate"></i> <span>Student Management</span></li>
            <li onclick="window.location.href='staff.php'"><i class="fas fa-user-tie"></i> <span>Staff Management</span></li>
            <li onclick="window.location.href='courses.php'"><i class="fas fa-book"></i> <span>Courses</span></li>
            <li onclick="window.location.href='payments.php'"><i class="fas fa-money-bill-wave"></i> <span>Payments Info</span></li>
            <li onclick="window.location.href='marks&exams.php'"><i class="fas fa-file-alt"></i> <span>Marks & Exams</span></li>
            <li  class="active"  onclick="window.location.href='results.php'"><i class="fas fa-search"></i> <span>Result</span></li>
            <li onclick="window.location.href='notices.php'"><i class="fas fa-bullhorn"></i> <span>Notice</span></li>
            <li onclick="window.location.href='attendence.php'"><i class="fas fa-clipboard-list"></i> <span>Attendance</span></li>
            <li onclick="window.location.href='classes.php'"><i class="fas fa-chalkboard-teacher"></i> <span>Classes</span></li>
            <li onclick="window.location.href='messages.php'"><i class="fas fa-envelope"></i> <span>Messages</span></li>
            <li onclick="window.location.href='settings page.php'"><i class="fas fa-cog"></i> <span>Settings</span></li>
            <li ><i class="fas fa-sign-out-alt"></i> <span>Logout</span></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>MONACO INSTITUTE</h1>
                <p>Results Management</p>
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
                    <div class="user-avatar">J</div>
                    <div class="user-info">
                        John Doe<br>
                        <span class="role">Admin</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Search by Student Name or ID..." aria-label="Search">
        </div>



        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon icon-total">
                    <i class="fas fa-users"></i>
                </div>
                <div class="summary-value"><?php echo $summary['total_students']; ?></div>
                <div class="summary-label">Total Students</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon icon-pass">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="summary-value"><?php echo $summary['passed_students']; ?></div>
                <div class="summary-label">Passed Students</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon icon-fail">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="summary-value"><?php echo $summary['failed_students']; ?></div>
                <div class="summary-label">Failed Students</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon icon-average">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="summary-value"><?php echo round($summary['average_score'], 0) . '%'; ?></div>
                <div class="summary-label">Average Score</div>
            </div>
        </div>

        <div class="filter-section">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-filter"></i> Filter Results</div>
            </div>
            <form method="GET" action="results.php">
                <div class="filter-controls">
                    <div class="filter-group">
                        <label for="course">Course</label>
                        <select id="course" name="course">
                            <option value="">All Courses</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['course_id']; ?>" <?php echo $course_id == $course['course_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($course['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="semester">Semester</label>
                        <select id="semester" name="semester">
                            <option value="">All Semesters</option>
                            <?php foreach ($semesters as $semester): ?>
                                <option value="<?php echo $semester['semester_id']; ?>" <?php echo $semester_id == $semester['semester_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($semester['semester_number']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="academic-year">Academic Year</label>
                        <select id="academic-year" name="academic-year">
                            <option value="">All Years</option>
                            <?php foreach ($academicYears as $year): ?>
                                <option value="<?php echo $year['academic_year_id']; ?>" <?php echo $academic_year_id == $year['academic_year_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($year['year_number']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="student-search">Search Student</label>
                        <input type="text" id="student-search" name="student-search" placeholder="Name or Student ID" value="<?php echo htmlspecialchars($search_term); ?>">
                    </div>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="filter-button"><i class="fas fa-filter"></i> Filter Results</button>
                    <button type="button" class="reset-button" onclick="window.location.href='results.php'"><i class="fas fa-undo"></i> Reset Filters</button>
                </div>
            </form>
        </div>

        <div class="results-table">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-file-alt"></i> Examination Results</div>
                <div class="action-buttons">
                    <button class="filter-button"><i class="fas fa-download"></i> Export Results</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>ID</th>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Final Grade</th>
                        <th>Average Score</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($results) > 0): ?>
                        <?php foreach ($results as $result): 
                            // Get initials for profile photo
                            $names = explode(' ', $result['student_name']);
                            $initials = substr($names[0], 0, 1) . substr(end($names), 0, 1);
                            
                            // Determine grade class
                            $grade_class = 'grade-' . strtolower($result['final_grade']);
                            
                            // Determine status color
                            $status_color = $result['status'] == 'Passed' ? '#2ecc71' : '#e74c3c';
                        ?>
                            <tr>
                                <td>
                                    <div class="student-info">
                                        <div class="student-photo"><?php echo $initials; ?></div>
                                        <div><?php echo htmlspecialchars($result['student_name']); ?></div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($result['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($result['course_name']); ?></td>
                                <td><?php echo htmlspecialchars($result['semester']); ?></td>
                                <td><span class="grade <?php echo $grade_class; ?>"><?php echo $result['final_grade']; ?></span></td>
                                <td><?php echo round($result['average_score'], 0) . '%'; ?></td>
                                <td><span style="color: <?php echo $status_color; ?>"><?php echo $result['status']; ?></span></td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-icon view" onclick="viewResult(<?php echo $result['semester_result_id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" onclick="editResult(<?php echo $result['semester_result_id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon print" onclick="printResult(<?php echo $result['semester_result_id']; ?>)">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">No results found matching your criteria</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="pagination">
                <button><i class="fas fa-angle-double-left"></i></button>
                <button><i class="fas fa-angle-left"></i></button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button>4</button>
                <button>5</button>
                <button><i class="fas fa-angle-right"></i></button>
                <button><i class="fas fa-angle-double-right"></i></button>
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

        function viewResult(resultId) {
            window.location.href = 'view_result.php?id=' + resultId;
        }
        
        function editResult(resultId) {
            window.location.href = 'edit_result.php?id=' + resultId;
        }
        
        function printResult(resultId) {
            window.open('print_result.php?id=' + resultId, '_blank');
        }

    </script>
</body>
</html>