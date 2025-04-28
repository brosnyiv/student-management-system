<?php


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

// Function to get course icon based on department
function getCourseIcon($department) {
    switch(strtolower($department)) {
        case 'it department':
        case 'information technology':
            return 'fas fa-laptop-code';
        case 'business':
        case 'business department':
            return 'fas fa-chart-line';
        case 'marketing':
        case 'marketing department':
            return 'fas fa-bullhorn';
        case 'design':
        case 'design department':
            return 'fas fa-paint-brush';
        case 'computer science':
            return 'fas fa-code';
        case 'data science':
            return 'fas fa-database';
        case 'artificial intelligence':
            return 'fas fa-robot';
        default:
            return 'fas fa-book';
    }
}

// Prepare and execute query to fetch courses
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);

// Check for errors
if ($result === false) {
    die("Error executing query: " . $conn->error);
}else{
    // Fetch all courses
    $tables = $result->fetch_all(MYSQLI_ASSOC);
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Courses</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
         /* Additional Styles for Course Unit Section */
         .course-units-container {
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #f9f9f9;
        }
        
        .semester-tab {
            display: inline-block;
            padding: 10px 15px;
            background-color: #e0e0e0;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .semester-tab.active {
            background-color: #8B1818;
            color: white;
        }
        
        .semester-content {
            display: none;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 0 5px 5px 5px;
            background-color: white;
        }
        
        .semester-content.active {
            display: block;
        }
        
        .unit-row {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        
        .unit-row:last-child {
            margin-bottom: 0;
        }
        
        .unit-input {
            flex: 2;
            margin-right: 10px;
        }
        
        .instructor-select {
            flex: 2;
            margin-right: 10px;
        }
        
        .unit-credits {
            flex: 1;
            margin-right: 10px;
        }
        
        .unit-action {
            flex: 0 0 40px;
            text-align: center;
        }
        
        .btn-add-unit {
            display: block;
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }
        
        .btn-add-unit:hover {
            background-color: #45a049;
        }
        
        .btn-remove-unit {
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }
        
        .btn-remove-unit:hover {
            background-color: #d32f2f;
        }
        
        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            margin: 20px 0 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
            color: #8B1818;
        }

        /* Additional styles to ensure proper display */
        .course-tab {
            cursor: pointer;
        }
        
        .course-tab.active {
            background-color: #8B1818;
            color: white;
        }
        
        .add-course-form {
            display: none;
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
            <li class="active" onclick="window.location.href='dash.php'"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></li>
            <li onclick="window.location.href='student.php'"><i class="fas fa-user-graduate"></i> <span>Student Management</span></li>
            <li onclick="window.location.href='staff.php'"><i class="fas fa-user-tie"></i> <span>Staff Management</span></li>
            <li onclick="window.location.href='courses.php'"><i class="fas fa-book"></i> <span>Courses</span></li>
            <li onclick="window.location.href='payments.php'"><i class="fas fa-money-bill-wave"></i> <span>Payments Info</span></li>
            <li onclick="window.location.href='marks&exams.php'"><i class="fas fa-file-alt"></i> <span>Marks & Exams</span></li>
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
                <h1>MONACO INSTITUTE</h1>
                <p>Course Management</p>
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
                    <div class="user-avatar"><?php echo isset($_SESSION['username']) ? substr($_SESSION['username'], 0, 1) : 'U'; ?></div>
                    <div class="user-info">
                        <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; ?><br>
                        <span class="role"><?php echo isset($_SESSION['role_name']) ? $_SESSION['role_name'] : 'User'; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Search courses..." aria-label="Search">
        </div>

        <div class="course-actions">
            <div class="course-filters">
                <div class="filter-dropdown">
                    <select name="course-level">
                        <option value="">All Levels</option>
                        <option value="certificate">Certificate</option>
                        <option value="diploma">Diploma</option>
                        
                    </select>
                </div>
                <div class="filter-dropdown">
                    <select name="course-status">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="filter-dropdown">
                    <select name="course-department">
                        <option value="">All Departments</option>
                        <option value="it">Information Technology</option>
                        <option value="business">Business</option>
                        <option value="design">Design</option>
                        <option value="marketing">Marketing</option>
                    </select>
                </div>
            </div>
            <button class="add-button"  onclick="window.location.href='new course.php'" ><i class="fas fa-plus"></i> Add New Course</button>
        </div>

        <div class="course-tabs">
            <div class="course-tab active">View courses</div>
            
        </div>

       <!-- This part goes in your HTML where you display the course table -->
      <div style="margin: 20px; padding: 15px; border: 1px solid #ddd; background-color: #f9f9f9;">
        <h3>Database Debug Information:</h3>
        <p>Tables in database:</p>
        <ul>
            <?php foreach($tables as $table): ?>
                <li><?php echo htmlspecialchars($table); ?></li>
            <?php endforeach; ?>
        </ul>
        
        <?php if($courses_exists): ?>
            <p>Structure of courses table:</p>
            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Null</th>
                    <th>Key</th>
                </tr>
                <?php foreach($structure as $field): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($field['Field']); ?></td>
                        <td><?php echo htmlspecialchars($field['Type']); ?></td>
                        <td><?php echo htmlspecialchars($field['Null']); ?></td>
                        <td><?php echo htmlspecialchars($field['Key']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <p style="color: red;">Courses table does not exist!</p>
        <?php endif; ?>
    </div>
    
    <div class="detailed-courses">
        <table>
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Level</th>
                    <th>Duration</th>
                    <th>Students</th>
                    <th>Faculty leader</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="course-badge">
                                    <div class="course-icon">
                                        <i class="<?php echo getCourseIcon($row['department_name'] ?? ''); ?>"></i>
                                    </div>
                                    <div>
                                        <?php echo htmlspecialchars($row['course_name'] ?? 'Unknown Course'); ?>
                                        <div style="font-size: 12px; color: #777;">
                                            <?php echo htmlspecialchars($row['department_name'] ?? 'Unknown Department'); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['level'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['duration'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['student_count'] ?? '0'); ?></td>
                            <td><?php echo htmlspecialchars($row['faculty_leader'] ?? 'Unassigned'); ?></td>
                            <td>
                                <span class="status-tag status-<?php echo strtolower($row['status'] ?? 'unknown'); ?>">
                                    <?php echo htmlspecialchars($row['status'] ?? 'Unknown'); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon btn-view" onclick="viewCourse(<?php echo $row['course_id'] ?? 0; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-icon btn-edit" onclick="editCourse(<?php echo $row['course_id'] ?? 0; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-delete" onclick="deleteCourse(<?php echo $row['course_id'] ?? 0; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">
                            No courses found in the database
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>



       
        <div class="add-course-form">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-plus-circle"></i> Add New Course</div>
            </div>
            <form>
              

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
        
          // Functions for course actions
    function viewCourse(courseId) {
        window.location.href = 'view_course.php?id=' + courseId;
    }
    
    function editCourse(courseId) {
        window.location.href = 'edit_course.php?id=' + courseId;
    }
    
    function deleteCourse(courseId) {
        if(confirm('Are you sure you want to delete this course?')) {
            window.location.href = 'delete_course.php?id=' + courseId;
        }
    }
</script>
</body>
</html>