<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'dbconnect.php'; // Include the database connection file

// Initialize staff count variables
$totalStaff = 0;
$teachingStaff = 0;
$nonTeachingStaff = 0;
$activeStaff = 0;
$inactiveStaff = 0;
//pull notifications count from notifications table where the user_id is the same as the current session user_id
$user_notified=$_SESSION['user_id'];
$sql="select count(*) from notifications where user_id is not null and user_id='$user_notified' and is_read='unread' order by created_at desc";
$result=mysqli_query($conn,$sql);
if ($result) {
   $notification_count= mysqli_fetch_assoc($result)['count(*)'];
} else {
    $notifications_count = 0;
    // For debugging
    // echo "Error in student query: " . mysqli_error($conn);
}

// Query to count total staff
$totalStaffQuery = "SELECT COUNT(*) AS total FROM staff";
$result = $conn->query($totalStaffQuery);
if ($result && $row = $result->fetch_assoc()) {
    $totalStaff = $row['total'];
}

// Query to count teaching staff
$teachingStaffQuery = "SELECT COUNT(*) AS teaching FROM staff WHERE staff_type = 'teaching'";
$result = $conn->query($teachingStaffQuery);
if ($result && $row = $result->fetch_assoc()) {
    $teachingStaff = $row['teaching'];
}

// Query to count non-teaching staff
$nonTeachingStaffQuery = "SELECT COUNT(*) AS nonteaching FROM staff WHERE staff_type = 'non-teaching'";
$result = $conn->query($nonTeachingStaffQuery);
if ($result && $row = $result->fetch_assoc()) {
    $nonTeachingStaff = $row['nonteaching'];
}

// Query to count active staff - assuming employment_type 'full-time', 'part-time', or 'visiting' means active
$activeStaffQuery = "SELECT COUNT(*) AS active FROM staff WHERE employment_type IN ('full-time', 'part-time', 'visiting')";
$result = $conn->query($activeStaffQuery);
if ($result && $row = $result->fetch_assoc()) {
    $activeStaff = $row['active'];
}

// Query to count inactive staff - assuming employment_type 'temporary' or 'contract' that has expired means inactive
$inactiveStaffQuery = "SELECT COUNT(*) AS inactive FROM staff WHERE employment_type IN ('temporary', 'contract')";
$result = $conn->query($inactiveStaffQuery);
if ($result && $row = $result->fetch_assoc()) {
    $inactiveStaff = $row['inactive'];
}

// Set up pagination
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$resultsPerPage = 10;
$offset = ($currentPage - 1) * $resultsPerPage;

// Get staff data for table display with department name
$staffQuery = "SELECT s.*, d.department_name 
               FROM staff s
               LEFT JOIN departments d ON s.department_id = d.department_id
               ORDER BY s.full_name 
               LIMIT $offset, $resultsPerPage";
$staffResult = $conn->query($staffQuery);

// Store staff data in an array for display
$staffData = [];
if ($staffResult) {
    while ($row = $staffResult->fetch_assoc()) {
        $staffData[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Staff Management</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Staff Management Specific Styles */
        /* (Keep all the CSS styles from the original file) */
        /* Staff Tabs */
        .staff-tabs {
            display: flex;
            background-color: #f7f7f7;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .staff-tab {
            padding: 12px 24px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .staff-tab.active {
            background-color: #8B1818;
            color: white;
        }

        .staff-tab:hover:not(.active) {
            background-color: #e9e9e9;
        }

        /* Staff Statistics */
        .staff-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-box {
            background: linear-gradient(145deg, #ffffff, #f5f5f5);
            border-radius: 12px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background-color: rgba(139, 24, 24, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .stat-icon i {
            color: #8B1818;
            font-size: 22px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
        }

        /* Staff Type Buttons */
        .staff-type-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .staff-type-button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            background-color: #f5f5f5;
            color: #444;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .staff-type-button i {
            font-size: 16px;
        }

        .staff-type-button.active {
            background-color: #8B1818;
            color: white;
        }

        .staff-type-button:hover:not(.active) {
            background-color: #e0e0e0;
        }

        /* Staff Forms */
        .staff-form {
            background-color: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: none;
        }

        .form-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eaeaea;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #8B1818;
            outline: none;
            box-shadow: 0 0 0 2px rgba(139, 24, 24, 0.1);
        }

        .form-group select[multiple] {
            height: 120px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .save-button, .cancel-button {
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .save-button {
            background-color: #8B1818;
            color: white;
        }

        .save-button:hover {
            background-color: #7a1515;
        }

        .cancel-button {
            background-color: #f5f5f5;
            color: #444;
        }

        .cancel-button:hover {
            background-color: #e0e0e0;
        }

        /* Filter and Search Section */
        .staff-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .filter-controls {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-group label {
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        .filter-group input,
        .filter-group select {
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            border-color: #8B1818;
            outline: none;
        }

        .import-export {
            display: flex;
            gap: 12px;
        }

        .import-export button {
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        #exportButton {
            background-color: #f5f5f5;
            color: #444;
        }

        #exportButton:hover {
            background-color: #e0e0e0;
        }

        .add-button {
            background-color: #8B1818;
            color: white;
        }

        .add-button:hover {
            background-color: #7a1515;
        }

        /* Staff Table */
        .staff-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .staff-table thead th {
            background-color: #f7f7f7;
            padding: 8px;
            text-align: left;
            font-weight: 400;
            color: #333;
            border-bottom: 1px solid #eaeaea;
        }

        .staff-table tbody tr {
            transition: background-color 0.3s ease;
        }

        .staff-table tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .staff-table tbody tr:hover {
            background-color: rgba(139, 24, 24, 0.05);
        }

        .staff-table td {
            padding: 14px;
            border-bottom: 1px solid #eaeaea;
        }

        .staff-avatar {
            width: 36px;
            height: 36px;
            background-color: #8B1818;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 400;
        }

        .department-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 12px;
            background-color: #f0f0f0;
            color: #555;
        }

        .teaching-icon {
            color: #8B1818;
        }

        .non-teaching-icon {
            color: #2a5394;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 4px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 300;
        }

        .status-active {
            background-color: rgba(0, 150, 0, 0.1);
            color: #008000;
        }

        .status-inactive {
            background-color: rgba(150, 0, 0, 0.1);
            color: #b00000;
        }

        .status-onleave {
            background-color: rgba(255, 165, 0, 0.1);
            color: #e67e00;
        }

        .action-button {
            width: 20px;
            height: 20px;
            border-radius: 2px;
            border: none;
            background-color: transparent;
            color: #555;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-right: 4px;
            display: inline-flex;
        }

        .view-button:hover {
            background-color: rgba(0, 150, 0, 0.1);
            color: #008000;
        }

        .edit-button:hover {
            background-color: rgba(0, 100, 200, 0.1);
            color: #0064c8;
        }

        .delete-button:hover {
            background-color: rgba(220, 0, 0, 0.1);
            color: #dc0000;
        }

        /* Responsive Adjustments */
        @media (max-width: 1200px) {
            .form-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .stat-box {
                padding: 14px;
            }
            
            .stat-value {
                font-size: 24px;
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .staff-list-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-controls {
                flex-direction: column;
            }
            
            .import-export {
                justify-content: space-between;
            }
            
            .staff-table {
                display: block;
                overflow-x: auto;
            }
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            position: relative;
        }

        .page-button {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            border: 1px solid #e0e0e0;
            color: #555;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .page-button:hover {
            background-color: #f5f5f5;
            border-color: #ccc;
        }

        .page-button.active {
            background-color: #8B1818;
            color: white;
            border-color: #8B1818;
        }

        .page-info {
            font-size: 14px;
            color: #666;
            margin-left: 12px;
        }

        /* Responsive adjustments for pagination */
        @media (max-width: 576px) {
            .pagination {
                flex-direction: column;
                gap: 12px;
            }
            
            .page-info {
                margin-left: 0;
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
            <li  onclick="window.location.href='dash.php'"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></li>
            <li onclick="window.location.href='student.php'"><i class="fas fa-user-graduate"></i> <span>Student Management</span></li>
            <li class="active" onclick="window.location.href='staff.php'"><i class="fas fa-user-tie"></i> <span>Staff Management</span></li>
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

<!-- welcome message   -->

    <div class="welcome-text">
<h1>MONACO INSTITUTE</h1>
<div class="welcome-message">
    <?php
    // Time-based greeting
    $hour = date('H');
    $greeting = ($hour < 12) ? "Good Morning" : (($hour < 17) ? "Good Afternoon" : "Good Evening");
    
    // Get username
    $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "User";
    echo "<p class='welcome-user'>{$greeting}, <span class='username'>{$username}</span></p>";
    
    // Dynamic messages
    $month = date('n');
    $seasonalMessages = [
        1 => "Happy New Year! New year, new learning opportunities",
        5 => "Spring into your educational journey",
        9 => "Welcome to the new academic year!",
        12 => "Season's greetings! Wrapping up the year with excellence"
    ];
    
    $dailyMessages = [
        "Empowering your educational journey every day!",
        "Building futures, one lesson at a time",
        "Today is a great day to learn something new!",
        "Where skills meet innovation",
        "Excellence in education since 2007",
        "Together, we grow"
    ];
    
    $message = $seasonalMessages[$month] ?? $dailyMessages[date('z') % count($dailyMessages)];
    echo "<p class='welcome-message-text'>{$message}</p>";
    ?>
</div>
<div class="date-display">
    <i class="fas fa-calendar-alt"></i> <span id="currentDate"><?php echo date('l, F j, Y'); ?></span>
    <span class="time-display"><i class="fas fa-clock"></i> <span id="currentTime"><?php echo date('h:i:s A'); ?></span></span>
</div>
</div>


         <!-- Notifications  -->

        <div class="user-section" style="display:flex; align-items:center;">
        <div class="notification-bell" id="notificationBell">
<i class="fas fa-bell"></i>
<span class="notification-count"><?php echo $notification_count; ?></span>

<!-- Notifications dropdown -->
<div class="notifications-dropdown" id="notificationsDropdown">
    <div class="notifications-header">
        <h3></h3>
        <?php if ($notification_count > 0): ?>
            <a href="mark_all_read.php" class="mark-all-read">Mark all as read</a>
        <?php endif; ?>
    </div>
    
    <div class="notifications-list">
        <?php if (empty($notifications)): ?>
            <div class="no-notifications"></div>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item" data-id="<?php echo $notification['id']; ?>">
                    <div class="notification-icon">
                        <?php if ($notification['type'] == 'message'): ?>
                            <i class="fas fa-envelope"></i>
                        <?php elseif ($notification['type'] == 'event'): ?>
                            <i class="fas fa-calendar-alt"></i>
                        <?php elseif ($notification['type'] == 'alert'): ?>
                            <i class="fas fa-exclamation-circle"></i>
                        <?php else: ?>
                            <i class="fas fa-bell"></i>
                        <?php endif; ?>
                    </div>
                    <div class="notification-content">
                        <div class="notification-text"><?php echo htmlspecialchars($notification['message']); ?></div>
                        <div class="notification-time">
                            <?php 
                                $time_diff = time() - strtotime($notification['created_at']);
                                if ($time_diff < 60) {
                                    echo "Just now";
                                } elseif ($time_diff < 3600) {
                                    echo floor($time_diff / 60) . " min ago";
                                } elseif ($time_diff < 86400) {
                                    echo floor($time_diff / 3600) . " hrs ago";
                                } else {
                                    echo floor($time_diff / 86400) . " days ago";
                                }
                            ?>
                        </div>
                    </div>
                    <button class="mark-read" onclick="markAsRead(<?php echo $notification['id']; ?>)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endforeach; ?>
            
            <?php if ($notification_count > count($notifications)): ?>
                <a href="all_notifications.php" class="view-all-notifications">
                    View all notifications (<?php echo $notification_count; ?>)
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
</div>


            <div class="user-profile">
                <div class="user-avatar">
                    <?php echo isset($_SESSION['username']) ? substr($_SESSION['username'], 0, 1) : 'U'; ?>
                </div>
                <div class="user-info">
                    <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; ?><br>
                    <span class="role"><?php echo isset($_SESSION['role_name']) ? $_SESSION['role_name'] : 'User'; ?></span>
                </div>
            </div>
        </div>
    </div>

        <div class="staff-tabs">
            <div class="staff-tab active">All Staff</div>
            <div class="staff-tab">Import/Export</div>
            <div class="staff-tab">Reports</div>
        </div>

        <div class="staff-stats">
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                <div class="stat-value"><?php echo $totalStaff; ?></div>
                <div class="stat-label">Total Staff</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-value"><?php echo $teachingStaff; ?></div>
                <div class="stat-label">Teaching Staff</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-user-cog"></i></div>
                <div class="stat-value"><?php echo $nonTeachingStaff; ?></div>
                <div class="stat-label">Non-Teaching Staff</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                <div class="stat-value"><?php echo $activeStaff; ?></div>
                <div class="stat-label">Active Staff</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-user-times"></i></div>
                <div class="stat-value"><?php echo $inactiveStaff; ?></div>
                <div class="stat-label">Inactive Staff</div>
            </div>
        </div>

        <div class="staff-type-buttons">
            <button class="staff-type-button active" id="allStaffButton">
                <i class="fas fa-users"></i> All Staff
            </button>
            <button class="staff-type-button" id="teachingStaffButton">
                <i class="fas fa-chalkboard-teacher"></i> Teaching Staff
            </button>
            <button class="staff-type-button" id="nonTeachingStaffButton">
                <i class="fas fa-user-cog"></i> Non-Teaching Staff
            </button>
        </div>

       
            
         
            <!-- Staff listing with actions -->
<div class="staff-list-header">
    <div class="filter-controls">
        <div class="filter-group">
            <label for="searchStaff">Search:</label>
            <input type="text" id="searchStaff" placeholder="Search by name, ID...">
        </div>
        <div class="filter-group">
            <label for="departmentFilter">Department:</label>
            <select id="departmentFilter">
                <option value="">All Departments</option>
                <?php
                // Connect to database
                include 'dbconnect.php';
                
                // Query to get all departments from the database
                $deptQuery = "SELECT department_id, department_name FROM departments ORDER BY department_name";
                $deptResult = $conn->query($deptQuery);
                
                // Loop through departments and create options
                if ($deptResult && $deptResult->num_rows > 0) {
                    while ($deptRow = $deptResult->fetch_assoc()) {
                        echo '<option value="' . $deptRow['department_id'] . '">' . 
                             htmlspecialchars($deptRow['department_name']) . '</option>';
                    }
                }
                
                // Close this database connection as we already have one active in the main code
                $deptResult->close();
                ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="statusFilter">Status:</label>
            <select id="statusFilter">
                <option value="">All Status</option>
                <option value="full-time">Full-Time</option>
                <option value="part-time">Part-Time</option>
                <option value="visiting">Visiting</option>
                <option value="contract">Contract</option>
                <option value="temporary">Temporary</option>
            </select>
        </div>
    </div>
    
    <div class="import-export">
        <button id="exportButton"><i class="fas fa-file-export"></i> Export</button>
        <button id="addStaffButton" class="add-button"><i class="fas fa-plus"></i> Add Staff</button>
    </div>
</div>

<table class="staff-table">
    <thead>
        <tr>
            
            <th>Full Name</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // Check if we have results
    if (!empty($staffData)) {
        // Fetch staff data
        foreach ($staffData as $row) {
            // Get first letter of name for avatar
            $firstLetter = substr($row['full_name'], 0, 1);
            
            // Determine department icon based on staff type
            $deptIcon = 'fas fa-laptop';
            $iconClass = 'teaching-icon';
            
            if (isset($row['staff_type']) && $row['staff_type'] == 'non-teaching') {
                $iconClass = 'non-teaching-icon';
                
                // Set appropriate icon for non-teaching departments
                switch(strtolower($row['department_name'] ?? '')) {
                    case 'administration':
                        $deptIcon = 'fas fa-user-tie';
                        break;
                    case 'it support':
                        $deptIcon = 'fas fa-server';
                        break;
                    case 'library':
                        $deptIcon = 'fas fa-book';
                        break;
                    case 'security':
                        $deptIcon = 'fas fa-shield-alt';
                        break;
                    case 'finance & accounting':
                        $deptIcon = 'fas fa-dollar-sign';
                        break;
                    case 'human resources':
                        $deptIcon = 'fas fa-users-cog';
                        break;
                    case 'maintenance':
                        $deptIcon = 'fas fa-tools';
                        break;
                    default:
                        $deptIcon = 'fas fa-building';
                }
            } else {
                // Set appropriate icon for teaching departments
                switch(strtolower($row['department_name'] ?? '')) {
                    case 'computer science':
                        $deptIcon = 'fas fa-laptop';
                        break;
                    case 'business administration':
                        $deptIcon = 'fas fa-chart-bar';
                        break;
                    case 'digital marketing':
                        $deptIcon = 'fas fa-bullhorn';
                        break;
                    case 'graphic design':
                        $deptIcon = 'fas fa-paint-brush';
                        break;
                    case 'languages':
                        $deptIcon = 'fas fa-language';
                        break;
                    case 'mathematics':
                        $deptIcon = 'fas fa-calculator';
                        break;
                    default:
                        $deptIcon = 'fas fa-chalkboard-teacher';
                }
            }
            
            // Determine status class based on employment type
            $statusClass = 'status-active';
            $statusText = 'Active';
            
            if(isset($row['employment_type'])) {
                switch($row['employment_type']) {
                    case 'full-time':
                    case 'part-time':
                    case 'visiting':
                        $statusClass = 'status-active';
                        $statusText = ucfirst($row['employment_type']);
                        break;
                    case 'contract':
                        $statusClass = 'status-onleave';
                        $statusText = 'Contract';
                        break;
                    case 'temporary':
                        $statusClass = 'status-inactive';
                        $statusText = 'Temporary';
                        break;
                }
            }
    ?>

 <tr>
    <td>
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="staff-avatar"><?php echo htmlspecialchars($firstLetter); ?></div>
            <div><?php echo htmlspecialchars($row['full_name']); ?></div>
        </div>
    </td>
    <td>
        <span class="department-tag">
            <i class="<?php echo $deptIcon.' '.$iconClass; ?>"></i> 
            <?php echo htmlspecialchars($row['department_name'] ?? 'Not Assigned'); ?>
        </span>
    </td>
    <td><?php echo htmlspecialchars($row['designation']); ?></td>
    <td><?php echo htmlspecialchars($row['personal_email'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['phone_number'] ?? ''); ?></td>
    <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
    <td>
        <button class="action-button view-button" onclick="viewStaff('<?php echo $row['staff_id']; ?>')"><i class="fas fa-eye"></i></button>
        <button class="action-button edit-button" onclick="editStaff('<?php echo $row['staff_id']; ?>')"><i class="fas fa-pen"></i></button>
        <button class="action-button delete-button" onclick="deleteStaff('<?php echo $row['staff_id']; ?>')"><i class="fas fa-trash"></i></button>
    </td>
</tr>
    <?php
        }
    } else {
        // No results found
        echo '<tr><td colspan="8" style="text-align:center;">No staff members found</td></tr>';
    }
    ?>
    </tbody>
</table>

<div class="pagination">
    <button class="page-button"><i class="fas fa-angle-double-left"></i></button>
    <button class="page-button"><i class="fas fa-angle-left"></i></button>
    <?php
    // Calculate total pages
    $totalPages = ceil($totalStaff / $resultsPerPage);
    
    // Show page numbers
    for($i = 1; $i <= min(5, $totalPages); $i++) {
        $activeClass = ($i == $currentPage) ? 'active' : '';
        echo '<button class="page-button '.$activeClass.'">'.$i.'</button>';
    }
    ?>
    <button class="page-button"><i class="fas fa-angle-right"></i></button>
    <button class="page-button"><i class="fas fa-angle-double-right"></i></button>
    <div class="page-info">Showing <?php echo min(($currentPage-1)*$resultsPerPage + 1, $totalStaff); ?>-<?php echo min($currentPage*$resultsPerPage, $totalStaff); ?> of <?php echo $totalStaff; ?> staff members</div>
</div>

        
    
        <script>
            // Date and Time Display
            function updateDateTime() {
                const now = new Date();
                const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const timeOptions = { hour: '2-digit', minute: '2-digit' };
                
                document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', dateOptions);
                document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', timeOptions);
            }
            
            updateDateTime();
            setInterval(updateDateTime, 60000);
            
         
            
            
            // Search and filtering functionality
            const searchInput = document.getElementById('searchStaff');
            const departmentFilter = document.getElementById('departmentFilter');
            const statusFilter = document.getElementById('statusFilter');
            
            // Sample implementation of search (in real application, this would query a database)
            searchInput.addEventListener('input', function() {
                // Implement search functionality
                console.log('Searching for:', this.value);
            });
            
            departmentFilter.addEventListener('change', function() {
                // Implement department filtering
                console.log('Filtering by department:', this.value);
            });
            
            statusFilter.addEventListener('change', function() {
                // Implement status filtering
                console.log('Filtering by status:', this.value);
            });

            // Staff tab switching
document.querySelectorAll('.staff-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.staff-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        // Implement tab switching logic here
    });
});

// Staff type filter buttons
document.querySelectorAll('.staff-type-button').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.staff-type-button').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        // Implement staff type filtering logic here
    });
});

// Add staff button functionality
document.getElementById('addStaffButton').addEventListener('click', function() {
    window.location.href = 'staff registration.php';
});

// Staff action functions
function viewStaff(staffId) {
    console.log('View staff with ID:', staffId);
    // Implement view staff functionality
}

function editStaff(staffId) {
    console.log('Edit staff with ID:', staffId);
    // Implement edit staff functionality
    window.location.href = 'staff registration.php?edit=' + staffId;
}

function deleteStaff(staffId) {
    if(confirm('Are you sure you want to delete this staff member?')) {
        console.log('Delete staff with ID:', staffId);
        // Implement delete staff functionality
    }
}
        </script>
    </body>
    </html>
