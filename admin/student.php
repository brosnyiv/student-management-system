<?php
 
 ob_start();

 if (session_status() === PHP_SESSION_NONE) {
     session_start();
 }
 
 include 'dbconnect.php'; // Include the database connection file

// Count total students
$student_query = "SELECT COUNT(*) as total_students FROM students";
$student_result = mysqli_query($conn, $student_query);
if ($student_result) {
    $student_count = mysqli_fetch_assoc($student_result)['total_students'];
} else {
    $student_count = 0;
    // For debugging
    // echo "Error in student query: " . mysqli_error($conn);
}

// Count male students
$male_query = "SELECT COUNT(*) as male_students FROM students WHERE gender = 'Male'";
$male_result = mysqli_query($conn, $male_query);
if ($male_result) {
    $male_count = mysqli_fetch_assoc($male_result)['male_students'];
} else {
    $male_count = 0;
}

// Count female students
$female_query = "SELECT COUNT(*) as female_students FROM students WHERE gender = 'Female'";
$female_result = mysqli_query($conn, $female_query);
if ($female_result) {
    $female_count = mysqli_fetch_assoc($female_result)['female_students'];
} else {
    $female_count = 0;
}

// Count active students
$active_query = "SELECT COUNT(*) as active_students FROM students WHERE status = 'Active'";
$active_result = mysqli_query($conn, $active_query);
if ($active_result) {
    $active_count = mysqli_fetch_assoc($active_result)['active_students'];
} else {
    $active_count = 0;
}

// Count inactive students
$inactive_query = "SELECT COUNT(*) as inactive_students FROM students WHERE status = 'Inactive'";
$inactive_result = mysqli_query($conn, $inactive_query);
if ($inactive_result) {
    $inactive_count = mysqli_fetch_assoc($inactive_result)['inactive_students'];
} else {
    $inactive_count = 0;
}

// Fetch all students for the table
$students_list_query = "SELECT * FROM students ORDER BY id DESC LIMIT 10";
$students_list_result = mysqli_query($conn, $students_list_query);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Student Management</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Additional styles for student management page */
        .filter-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .filter-group select, .filter-group input {
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .student-avatar {
            width: 40px;
            height: 40px;
            background-color: #8B1818;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            margin: 0 auto;
        }
        
        .action-button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            margin: 0 3px;
        }
        
        .view-button {
            background-color: #3498db;
            color: white;
        }
        
        .edit-button {
            background-color: #f39c12;
            color: white;
        }
        
        .delete-button {
            background-color: #e74c3c;
            color: white;
        }
        
        .action-button:hover {
            transform: scale(1.1);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }
        
        .pagination-button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        
        .pagination-button.active {
            background-color: #8B1818;
            color: white;
            border-color: #8B1818;
        }
        
        .student-tabs {
            display: flex;
            gap: 2px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .student-tab {
            padding: 12px 20px;
            cursor: pointer;
            background-color: #f5f5f5;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            font-weight: 500;
        }
        
        .student-tab.active {
            background-color: #8B1818;
            color: white;
        }
        
        .student-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: none; /* Hidden by default */
        }
        
        .form-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #8B1818;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-group label {
            font-weight: 500;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .save-button {
            background-color: #8B1818;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .cancel-button {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .student-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-box {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            background-color: rgba(139, 24, 24, 0.1);
            color: #8B1818;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #555;
            font-size: 14px;
        }
        
        .import-export {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }
        
        .import-export button {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            border-radius: 4px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            cursor: pointer;
        }
        
        .student-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        /* New styles for table view */
        .student-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .student-table th {
            background-color: #f5f5f5;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        
        .student-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .student-table tr:hover {
            background-color: #f9f9f9;
        }
        
        .student-table tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            text-align: center;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-onleave {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .balance-positive {
            color: #155724;
        }
        
        .balance-negative {
            color: #721c24;
        }
        
        .add-button {
            background-color: #8B1818;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .add-button:hover {
            background-color: #6d1212;
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
            <li  onclick="window.location.href='dash.php'"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></li>
            <li  class="active"onclick="window.location.href='student.php'"><i class="fas fa-user-graduate"></i> <span>Student Management</span></li>
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
            <li ><i class="fas fa-sign-out-alt"></i> <span>Logout</span></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>MONACO INSTITUTE</h1>
                <p>Student Management</p>
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

        <div class="student-tabs">
            <div class="student-tab active">All Students</div>

            <div class="student-tab">Import/Export</div>
            
            <div class="student-tab">Reports</div>
        </div>

        <div class="student-stats">
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-value"><?php echo $student_count; ?></div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-male"></i></div>
                <div class="stat-value"><?php echo $male_count; ?></div>
                <div class="stat-label">Male Students</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-female"></i></div>
                <div class="stat-value"><?php echo $female_count; ?></div>
                <div class="stat-label">Female Students</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                <div class="stat-value"><?php echo $active_count; ?></div>
                <div class="stat-label">Active Students</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-user-times"></i></div>
                <div class="stat-value"><?php echo $inactive_count; ?></div>
                <div class="stat-label">Inactive Students</div>
            </div>
        </div>

        
           

        <div class="student-list-header">
            <h3>Students List</h3>
            <button class="add-button" id="addStudentButton" onclick="window.location.href='student registration.php'"  ><i class="fas fa-plus"></i> Add New Student</button>
        </div>

        <!-- New Table Layout -->
        <table class="student-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Student ID</th>
                    <th>Course</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php 
    if(mysqli_num_rows($students_list_result) > 0) {
        while($student = mysqli_fetch_assoc($students_list_result)) {
            // Get first letter of name for avatar
            $first_letter = substr($student['first_name'], 0, 1);
            
            // Determine balance class
            $balance_class = ($student['balance'] >= 0) ? 'balance-positive' : 'balance-negative';
            
            // Determine status class
            $status_class = 'status-active';
            if($student['status'] == 'Inactive') {
                $status_class = 'status-inactive';
            } elseif($student['status'] == 'On Leave') {
                $status_class = 'status-onleave';
            }
    ?>
    <tr>
        <td>
            <div class="student-avatar"><?php echo $first_letter; ?></div>
        </td>
        <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
        <td><?php echo $student['student_id']; ?></td>
        <td><?php echo $student['course']; ?></td>
        <td><?php echo $student['email']; ?></td>
        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $student['status']; ?></span></td>
        <td class="<?php echo $balance_class; ?>"><?php echo '$' . number_format($student['balance'], 2); ?></td>
        <td>
            <button class="action-button view-button" onclick="viewStudent(<?php echo $student['id']; ?>)"><i class="fas fa-eye"></i></button>
            <button class="action-button edit-button" onclick="editStudent(<?php echo $student['id']; ?>)"><i class="fas fa-edit"></i></button>
            <button class="action-button delete-button" onclick="deleteStudent(<?php echo $student['id']; ?>)"><i class="fas fa-trash"></i></button>
        </td>
    </tr>
    <?php
        }
    } else {
    ?>
    <tr>
        <td colspan="8" style="text-align: center;">No students found</td>
    </tr>
    <?php
    }
    ?>
</tbody>
        </table>

        <div class="pagination">
            <button class="pagination-button"><i class="fas fa-angle-double-left"></i></button>
            <button class="pagination-button"><i class="fas fa-angle-left"></i></button>
            <button class="pagination-button active">1</button>
            <button class="pagination-button">2</button>
            <button class="pagination-button">3</button>
            <button class="pagination-button">4</button>
            <button class="pagination-button">5</button>
            <button class="pagination-button"><i class="fas fa-angle-right"></i></button>
            <button class="pagination-button"><i class="fas fa-angle-double-right"></i></button>
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
            
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', timeOptions);
        }
        
        setInterval(updateDateTime, 1000);
        
       
    </script>
</body>
</html>