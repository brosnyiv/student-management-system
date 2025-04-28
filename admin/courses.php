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

// Handle course insertion if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    // Sanitize and validate input
    $course_name = $conn->real_escape_string($_POST['course_name']);
    $course_code = $conn->real_escape_string($_POST['course_code']);
    $level_id = $conn->real_escape_string($_POST['level_id']);
    $department_id = (int)$_POST['department_id'];
    $duration = (int)$_POST['duration'];
    $max_capacity = (int)$_POST['max_capacity'];
    $faculty_leader_id = (int)$_POST['faculty_leader_id'];
    $status = $conn->real_escape_string($_POST['status']);
    $description = $conn->real_escape_string($_POST['description']);
    $course_fee = (float)$_POST['course_fee'];
    
    // Create SQL query to insert course
    $sql = "INSERT INTO courses (course_name, course_code, level_id, department_id, duration, max_capacity, faculty_leader_id, status, description, course_fee) 
            VALUES ('$course_name', '$course_code', '$level_id', $department_id, $duration, $max_capacity, $faculty_leader_id, '$status', '$description', $course_fee)";
    
    if ($conn->query($sql) === TRUE) {
        // Success message
        $success_message = "Course added successfully!";
        // Redirect to prevent form resubmission
        header("Location: courses.php?success=added");
        exit();
    } else {
        // Error message
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Prepare and execute query to fetch courses with department and faculty leader info
$sql = "SELECT 
            c.course_id, 
            c.course_name, 
            c.course_code, 
            cl.level_name as level,
            d.department_name,
            c.duration,
            c.status,
            c.course_fee,
            s.full_name as faculty_leader,
            COUNT(e.student_id) as student_count
        FROM courses c
        LEFT JOIN course_levels cl ON c.level_id = cl.level_id
        LEFT JOIN departments d ON c.department_id = d.department_id
        LEFT JOIN faculty_leaders fl ON c.faculty_leader_id = fl.faculty_leader_id
        LEFT JOIN staff s ON fl.staff_id = s.staff_id
        LEFT JOIN enrollments e ON c.course_id = e.course_id AND e.status = 'active'
        GROUP BY c.course_id
        ORDER BY c.course_name";

$result = $conn->query($sql);   // Execute the query
if ($result === false) {
    die("Error executing query: " . $conn->error);
}

// Fetch all departments for the dropdown
$dept_sql = "SELECT department_id, department_name FROM departments ORDER BY department_name";
$dept_result = $conn->query($dept_sql);

// Fetch all course levels for the dropdown
$level_sql = "SELECT level_id, level_name FROM course_levels ORDER BY level_name";
$level_result = $conn->query($level_sql);

// Fetch all faculty leaders for the dropdown
$faculty_sql = "SELECT fl.faculty_leader_id, s.full_name 
                FROM faculty_leaders fl 
                JOIN staff s ON fl.staff_id = s.staff_id 
                ORDER BY s.full_name";
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
        
        /* Status tag styles */
        .status-tag {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-upcoming {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-archived {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        /* Course badge styles */
        .course-badge {
            display: flex;
            align-items: center;
        }
        
        .course-icon {
            margin-right: 10px;
            font-size: 20px;
            color: #8B1818;
        }
        
        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-icon {
            border: none;
            background: none;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            width: 30px;
            height: 30px;
        }
        
        .btn-view {
            color: #17a2b8;
        }
        
        .btn-edit {
            color: #ffc107;
        }
        
        .btn-delete {
            color: #dc3545;
        }
        
        .btn-icon:hover {
            background-color: #f0f0f0;
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
            <li class="active" onclick="window.location.href='courses.php'"><i class="fas fa-book"></i> <span>Courses</span></li>
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

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="search-bar">
            <input type="text" id="courseSearch" placeholder="Search courses..." aria-label="Search">
        </div>

        <div class="course-actions">
            <div class="course-filters">
                <div class="filter-dropdown">
                    <select name="course-level" id="filterLevel">
                        <option value="">All Levels</option>
                        <?php if($level_result && $level_result->num_rows > 0): ?>
                            <?php while($level = $level_result->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($level['level_name']); ?>">
                                    <?php echo htmlspecialchars($level['level_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="filter-dropdown">
                    <select name="course-status" id="filterStatus">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="filter-dropdown">
                    <select name="course-department" id="filterDepartment">
                        <option value="">All Departments</option>
                        <?php if($dept_result && $dept_result->num_rows > 0): ?>
                            <?php while($dept = $dept_result->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($dept['department_name']); ?>">
                                    <?php echo htmlspecialchars($dept['department_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <button onclick="window.location.href='new course.php'" class="add-button" id="showAddCourseForm" ><i class="fas fa-plus" ></i> Add New Course</button>
        </div>

        <div class="course-tabs">
            <div class="course-tab active" id="viewCoursesTab">View courses</div>
           
        </div>

        <!-- View Courses Section -->
        <div class="detailed-courses" id="viewCoursesSection">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Level</th>
                        <th>Duration</th>
                        <th>Students</th>
                        <th>Course Fee</th>
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
                                            <?php echo htmlspecialchars($row['course_name']); ?>
                                            <div style="font-size: 12px; color: #777;">
                                                <?php echo htmlspecialchars($row['department_name']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($row['level']); ?></td>
                                <td><?php echo htmlspecialchars($row['duration']); ?> years</td>
                                <td><?php echo htmlspecialchars($row['student_count']); ?></td>
                                <td>$<?php echo number_format($row['course_fee'], 2); ?></td>
                                <td><?php echo htmlspecialchars($row['faculty_leader'] ?? 'Unassigned'); ?></td>
                                <td>
                                    <span class="status-tag status-<?php echo strtolower($row['status']); ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon btn-view" onclick="viewCourse(<?php echo $row['course_id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-icon btn-edit" onclick="editCourse(<?php echo $row['course_id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-icon btn-delete" onclick="deleteCourse(<?php echo $row['course_id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">
                                No courses found in the database
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
        
        // Tab switching
        document.addEventListener('DOMContentLoaded', function() {
            // Get tab elements
            const viewCoursesTab = document.getElementById('viewCoursesTab');
            const addCourseTab = document.getElementById('addCourseTab');
            
            // Get content sections
            const viewCoursesSection = document.getElementById('viewCoursesSection');
            const addCourseSection = document.getElementById('addCourseSection');
            
            // Get buttons
            const showAddCourseFormBtn = document.getElementById('showAddCourseForm');
            const cancelAddCourseBtn = document.getElementById('cancelAddCourse');
            
            // Function to show View Courses section
            function showViewCourses() {
                viewCoursesTab.classList.add('active');
                addCourseTab.classList.remove('active');
                viewCoursesSection.style.display = 'block';
                addCourseSection.style.display = 'none';
            }
            
            // Function to show Add Course section
            function showAddCourse() {
                viewCoursesTab.classList.remove('active');
                addCourseTab.classList.add('active');
                viewCoursesSection.style.display = 'none';
                addCourseSection.style.display = 'block';
            }
            
            // Set initial state
            showViewCourses();
            
            // Add event listeners
            viewCoursesTab.addEventListener('click', showViewCourses);
            addCourseTab.addEventListener('click', showAddCourse);
            showAddCourseFormBtn.addEventListener('click', showAddCourse);
            cancelAddCourseBtn.addEventListener('click', showViewCourses);
            
            // Course search functionality
            const courseSearch = document.getElementById('courseSearch');
            courseSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.detailed-courses table tbody tr');
                
                rows.forEach(row => {
                    const courseName = row.querySelector('td:first-child').textContent.toLowerCase();
                    if (courseName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            // Course filtering functionality
            const filterLevel = document.getElementById('filterLevel');
            const filterStatus = document.getElementById('filterStatus');
            const filterDepartment = document.getElementById('filterDepartment');
            
            function applyFilters() {
                const levelFilter = filterLevel.value.toLowerCase();
                const statusFilter = filterStatus.value.toLowerCase();
                const departmentFilter = filterDepartment.value.toLowerCase();
                
                const rows = document.querySelectorAll('.detailed-courses table tbody tr');
                
                rows.forEach(row => {
                    const level = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const department = row.querySelector('td:first-child div div:last-child div').textContent.toLowerCase();
                    const status = row.querySelector('td:nth-child(7) span').textContent.toLowerCase();
                    
                    const levelMatch = levelFilter === '' || level.includes(levelFilter);
                    const statusMatch = statusFilter === '' || status.includes(statusFilter);
                    const departmentMatch = departmentFilter === '' || department.includes(departmentFilter);
                    
                    if (levelMatch && statusMatch && departmentMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            filterLevel.addEventListener('change', applyFilters);
            filterStatus.addEventListener('change', applyFilters);
            filterDepartment.addEventListener('change', applyFilters);
        });
    </script>
</body>
</html>