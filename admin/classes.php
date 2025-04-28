<?php

session_start(); // Start the session
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

// Function to get class status
function getClassStatus($day_of_week, $start_time, $end_time, $status) {
    $current_day = strtolower(date('l'));
    $current_time = date('H:i:s');
    
    if ($status === 'canceled') {
        return 'canceled';
    } elseif ($status === 'completed') {
        return 'completed';
    } elseif ($day_of_week === $current_day && $start_time <= $current_time && $end_time >= $current_time) {
        return 'ongoing';
    } else {
        return 'upcoming';
    }
}

// Count classes by status
$completed_count = 0;
$ongoing_count = 0;
$upcoming_count = 0;
$canceled_count = 0;

$sql_count = "SELECT 
    SUM(CASE WHEN cs.status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN cs.status = 'scheduled' AND DAYOFWEEK(NOW()) = 
        CASE 
            WHEN cs.day_of_week = 'monday' THEN 2
            WHEN cs.day_of_week = 'tuesday' THEN 3
            WHEN cs.day_of_week = 'wednesday' THEN 4
            WHEN cs.day_of_week = 'thursday' THEN 5
            WHEN cs.day_of_week = 'friday' THEN 6
            WHEN cs.day_of_week = 'saturday' THEN 7
            WHEN cs.day_of_week = 'sunday' THEN 1
        END
        AND TIME(NOW()) BETWEEN cs.start_time AND cs.end_time THEN 1 ELSE 0 END) as ongoing,
    SUM(CASE WHEN cs.status = 'scheduled' AND 
        (DAYOFWEEK(NOW()) < 
            CASE 
                WHEN cs.day_of_week = 'monday' THEN 2
                WHEN cs.day_of_week = 'tuesday' THEN 3
                WHEN cs.day_of_week = 'wednesday' THEN 4
                WHEN cs.day_of_week = 'thursday' THEN 5
                WHEN cs.day_of_week = 'friday' THEN 6
                WHEN cs.day_of_week = 'saturday' THEN 7
                WHEN cs.day_of_week = 'sunday' THEN 1
            END
        OR 
        (DAYOFWEEK(NOW()) = 
            CASE 
                WHEN cs.day_of_week = 'monday' THEN 2
                WHEN cs.day_of_week = 'tuesday' THEN 3
                WHEN cs.day_of_week = 'wednesday' THEN 4
                WHEN cs.day_of_week = 'thursday' THEN 5
                WHEN cs.day_of_week = 'friday' THEN 6
                WHEN cs.day_of_week = 'saturday' THEN 7
                WHEN cs.day_of_week = 'sunday' THEN 1
            END
        AND TIME(NOW()) < cs.start_time)) THEN 1 ELSE 0 END) as upcoming,
    SUM(CASE WHEN cs.status = 'canceled' THEN 1 ELSE 0 END) as canceled
FROM class_sessions cs";

$result_count = $conn->query($sql_count);
if ($result_count->num_rows > 0) {
    $counts = $result_count->fetch_assoc();
    $completed_count = $counts['completed'];
    $ongoing_count = $counts['ongoing'];
    $upcoming_count = $counts['upcoming'];
    $canceled_count = $counts['canceled'];
}

// Get class sessions for display
$sql = "SELECT cs.session_id, cu.unit_name, r.room_code, 
        cs.day_of_week, cs.start_time, cs.end_time, cs.status, 
        CONCAT(ts.title, ' ', s.first_name, ' ', s.last_name) as instructor_name,
        cs.status as db_status
    FROM class_sessions cs
    JOIN course_units cu ON cs.course_unit_id = cu.unit_id
    JOIN rooms r ON cs.room_id = r.room_id
    JOIN teaching_staff ts ON cu.instructor_id = ts.teaching_id
    JOIN staff s ON ts.staff_id = s.staff_id";

// Add filter condition if provided
if (isset($_GET['filterCourse']) && !empty($_GET['filterCourse'])) {
    $sql .= " AND cu.unit_id = " . intval($_GET['filterCourse']);
}

if (isset($_GET['filterInstructor']) && !empty($_GET['filterInstructor'])) {
    $sql .= " AND ts.teaching_id = " . intval($_GET['filterInstructor']);
}

if (isset($_GET['filterRoom']) && !empty($_GET['filterRoom'])) {
    $sql .= " AND r.room_code = '" . $conn->real_escape_string($_GET['filterRoom']) . "'";
}

if (isset($_GET['filterStatus']) && !empty($_GET['filterStatus'])) {
    // Handle dynamic status from the database
    $sql .= " AND cs.status = '" . $conn->real_escape_string($_GET['filterStatus']) . "'";
}

$sql .= " ORDER BY FIELD(cs.day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'), cs.start_time";

$result = $conn->query($sql);

// Get courses for filter dropdown
$sql_courses = "SELECT unit_id, unit_name FROM course_units ORDER BY unit_name";
$result_courses = $conn->query($sql_courses);

// Get instructors for filter dropdown
$sql_instructors = "SELECT ts.teaching_id, CONCAT(ts.title, ' ', s.first_name, ' ', s.last_name) as instructor_name 
                    FROM teaching_staff ts 
                    JOIN staff s ON ts.staff_id = s.staff_id 
                    ORDER BY s.last_name";
$result_instructors = $conn->query($sql_instructors);

// Get rooms for filter dropdown
$sql_rooms = "SELECT room_code FROM rooms ORDER BY room_code";
$result_rooms = $conn->query($sql_rooms);

// Prepare timetable data structure
$timetable = [];
$class_list = [];

// Time slots for timetable
$timeSlots = [
    '08:00:00-10:00:00' => '8:00 - 10:00',
    '10:30:00-12:30:00' => '10:30 - 12:30',
    '13:30:00-15:30:00' => '13:30 - 15:30',
    '16:00:00-18:00:00' => '16:00 - 18:00'
];

// Days of week
$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

// Initialize timetable with empty slots
foreach ($timeSlots as $timeRange => $displayTime) {
    list($startTime, $endTime) = explode('-', $timeRange);
    $timetable[$timeRange] = [
        'display' => $displayTime,
        'monday' => [],
        'tuesday' => [],
        'wednesday' => [],
        'thursday' => [],
        'friday' => [],
        'saturday' => [],
        'sunday' => []
    ];
}

// Populate timetable and class list from database results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Determine status based on current time and day
        $status = getClassStatus($row['day_of_week'], $row['start_time'], $row['end_time'], $row['db_status']);
        
        $class_data = [
            'session_id' => $row['session_id'],
            'class_name' => $row['unit_name'],
            'room' => $row['room_code'],
            'instructor' => $row['instructor_name'],
            'status' => $status
        ];
        
        // Add to class list view
        $class_list[] = [
            'session_id' => $row['session_id'],
            'class_name' => $row['unit_name'],
            'day' => ucfirst($row['day_of_week']),
            'time' => date('g:i A', strtotime($row['start_time'])) . ' - ' . date('g:i A', strtotime($row['end_time'])),
            'room' => $row['room_code'],
            'instructor' => $row['instructor_name'],
            'status' => $status
        ];
        
        // Find appropriate time slot for timetable view
        foreach ($timeSlots as $timeRange => $displayTime) {
            list($slotStart, $slotEnd) = explode('-', $timeRange);
            if ($row['start_time'] >= $slotStart && $row['end_time'] <= $slotEnd) {
                $timetable[$timeRange][$row['day_of_week']][] = $class_data;
                break;
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Classes</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Additional CSS for Classes Page */
        .class-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filter-group {
            display: flex;
            align-items: center;
        }

        .filter-group label {
            margin-right: 8px;
            font-weight: 500;
            color: #333;
        }

        .filter-group select, .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .view-toggle {
            display: flex;
            margin-left: auto;
        }

        .view-toggle button {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .view-toggle button:first-child {
            border-radius: 4px 0 0 4px;
        }

        .view-toggle button:last-child {
            border-radius: 0 4px 4px 0;
        }

        .view-toggle button.active {
            background-color: #8B1818;
            color: #fff;
            border-color: #8B1818;
        }

        .timetable {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .timetable-header {
            display: grid;
            grid-template-columns: 100px repeat(7, 1fr);
            background-color: #f5f5f5;
            font-weight: 600;
        }

        .timetable-header div {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .timetable-body {
            display: grid;
            grid-template-columns: 100px repeat(7, 1fr);
        }

        .time-slot {
            padding: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            color: #555;
        }

        .class-slot {
            padding: 10px;
            border-bottom: 1px solid #eee;
            border-left: 1px solid #eee;
            min-height: 80px;
        }

        .class-card {
            background-color: #f9f9f9;
            border-left: 4px solid #8B1818;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 13px;
        }

        .class-card:hover {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .class-card .class-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .class-card .class-details {
            color: #666;
            font-size: 12px;
        }

        .class-card .class-instructor {
            display: flex;
            align-items: center;
            margin-top: 5px;
            font-size: 12px;
        }

        .class-card.completed {
            border-left-color: #2ecc71;
        }

        .class-card.ongoing {
            border-left-color: #3498db;
        }

        .class-card.upcoming {
            border-left-color: #8B1818;
        }

        .class-card.canceled {
            border-left-color: #e74c3c;
            background-color: #ffeeee;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 500;
            margin-left: auto;
        }

        .status-badge.completed {
            background-color: #d5f5e3;
            color: #2ecc71;
        }

        .status-badge.ongoing {
            background-color: #d4e6f1;
            color: #3498db;
        }

        .status-badge.upcoming {
            background-color: #f8d7da;
            color: #8B1818;
        }

        .status-badge.canceled {
            background-color: #fadbd8;
            color: #e74c3c;
        }

        .status-summary {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .status-card {
            flex: 1;
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .status-card .status-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 20px;
            color: white;
        }

        .status-card.completed .status-icon {
            background-color: #2ecc71;
        }

        .status-card.ongoing .status-icon {
            background-color: #3498db;
        }

        .status-card.upcoming .status-icon {
            background-color: #8B1818;
        }

        .status-card.canceled .status-icon {
            background-color: #e74c3c;
        }

        .status-card .status-count {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .status-card .status-label {
            color: #666;
            font-size: 14px;
        }

        .add-class-modal, .edit-class-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 8px;
            width: 500px;
            max-width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            margin: 0;
            color: #333;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .modal-body {
            padding: 20px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 15px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #8B1818;
            color: white;
        }

        .btn-primary:hover {
            background-color: #701010;
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #d0d0d0;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .class-list-view {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .class-list-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr;
            background-color: #f5f5f5;
            font-weight: 600;
            padding: 12px;
        }

        .class-list-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr;
            padding: 12px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }

        .class-list-row:hover {
            background-color: #f9f9f9;
        }

        .class-actions {
            display: flex;
            gap: 8px;
        }

        .class-actions button {
            border: none;
            background: none;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .class-actions button:hover {
            background-color: #eee;
        }

        .class-actions .edit {
            color: #3498db;
        }

        .class-actions .cancel {
            color: #e74c3c;
        }

        .class-actions .view {
            color: #2ecc71;
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
            <li onclick="window.location.href='staff.php'"><i class="fas fa-user-tie"></i> <span>Staff Management</span></li>
            <li onclick="window.location.href='courses.php'"><i class="fas fa-book"></i> <span>Courses</span></li>
            <li onclick="window.location.href='payments.php'"><i class="fas fa-money-bill-wave"></i> <span>Payments Info</span></li>
            <li onclick="window.location.href='marks&exams.php'"><i class="fas fa-file-alt"></i> <span>Marks & Exams</span></li>
            <li onclick="window.location.href='results.php'"><i class="fas fa-search"></i> <span>Result</span></li>
            <li onclick="window.location.href='notices.php'"><i class="fas fa-bullhorn"></i> <span>Notice</span></li>
            <li onclick="window.location.href='attendence.php'"><i class="fas fa-clipboard-list"></i> <span>Attendance</span></li>
            <li  class="active" onclick="window.location.href='classes.php'"><i class="fas fa-chalkboard-teacher"></i> <span>Classes</span></li>
            <li onclick="window.location.href='messages.php'"><i class="fas fa-envelope"></i> <span>Messages</span></li>
            <li onclick="window.location.href='settings page.php'"><i class="fas fa-cog"></i> <span>Settings</span></li>
            <li ><i class="fas fa-sign-out-alt"></i> <span>Logout</span></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>MONACO INSTITUTE</h1>
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

        <h2><i class="fas fa-chalkboard-teacher"></i> Classes Management</h2>

          <!-- Status Summary -->
          <div class="status-summary">
            <div class="status-card completed">
                <div class="status-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="status-count"><?php echo $completed_count; ?></div>
                <div class="status-label">Completed</div>
            </div>
            <div class="status-card ongoing">
                <div class="status-icon">
                    <i class="fas fa-play"></i>
                </div>
                <div class="status-count"><?php echo $ongoing_count; ?></div>
                <div class="status-label">Ongoing</div>
            </div>
            <div class="status-card upcoming">
                <div class="status-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="status-count"><?php echo $upcoming_count; ?></div>
                <div class="status-label">Upcoming</div>
            </div>
            <div class="status-card canceled">
                <div class="status-icon">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="status-count"><?php echo $canceled_count; ?></div>
                <div class="status-label">Canceled</div>
            </div>
        </div>

        <!-- Timetable Management Panel -->
        <div class="section-header">
            <div class="section-title"><i class="fas fa-calendar-week"></i> Timetable Management</div>
            <div class="action-buttons">
                <button class="add-button" onclick="window.location.href='timetable.php'"><i class="fas fa-plus"></i> Add New Class</button>
            </div>
        </div>

        <!-- Filters -->
        <div class="class-filters">
            <form method="GET" action="">
                <div class="filter-group">
                    <label for="filterCourse">Course:</label>
                    <select id="filterCourse" name="filterCourse">
                        <option value="">All Courses</option>
                        <?php
                        if ($result_courses->num_rows > 0) {
                            while ($course = $result_courses->fetch_assoc()) {
                                $selected = (isset($_GET['filterCourse']) && $_GET['filterCourse'] == $course['unit_id']) ? 'selected' : '';
                                echo "<option value='{$course['unit_id']}' {$selected}>{$course['unit_name']} ({$course['unit_code']})</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterInstructor">Instructor:</label>
                    <select id="filterInstructor" name="filterInstructor">
                        <option value="">All Instructors</option>
                        <?php
                        if ($result_staff->num_rows > 0) {
                            while ($staff = $result_staff->fetch_assoc()) {
                                $selected = (isset($_GET['filterInstructor']) && $_GET['filterInstructor'] == $staff['teaching_id']) ? 'selected' : '';
                                echo "<option value='{$staff['teaching_id']}' {$selected}>{$staff['instructor_name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterRoom">Room:</label>
                    <select id="filterRoom" name="filterRoom">
                        <option value="">All Rooms</option>
                        <?php
                        if ($result_rooms->num_rows > 0) {
                            while ($room = $result_rooms->fetch_assoc()) {
                                $selected = (isset($_GET['filterRoom']) && $_GET['filterRoom'] == $room['room_id']) ? 'selected' : '';
                                echo "<option value='{$room['room_id']}' {$selected}>{$room['room_code']} - {$room['room_name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterStatus">Status:</label>
                    <select id="filterStatus" name="filterStatus">
                        <option value="">All Statuses</option>
                        <option value="completed" <?php echo (isset($_GET['filterStatus']) && $_GET['filterStatus'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="ongoing" <?php echo (isset($_GET['filterStatus']) && $_GET['filterStatus'] == 'ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                        <option value="upcoming" <?php echo (isset($_GET['filterStatus']) && $_GET['filterStatus'] == 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                        <option value="canceled" <?php echo (isset($_GET['filterStatus']) && $_GET['filterStatus'] == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 8px 15px; margin-left: 10px;">Filter</button>
            </form>
            <div class="view-toggle">
                <button class="active" id="week-view-btn"><i class="fas fa-calendar-week"></i> Week</button>
                <button id="list-view-btn"><i class="fas fa-list"></i> List</button>
            </div>
        </div>

        <!-- Week View -->
        <div class="timetable" id="week-view">
            <div class="timetable-header">
                <div>Time</div>
                <div>Monday</div>
                <div>Tuesday</div>
                <div>Wednesday</div>
                <div>Thursday</div>
                <div>Friday</div>
                <div>Saturday</div>
                <div>Sunday</div>
            </div>
            <div class="timetable-body">
            <?php foreach ($timeSlots as $slot_key => $slot_display): ?>
                    <!-- Time Slot Row -->
                    <div class="time-slot"><?php echo $slot_display; ?></div>
                    
                    <?php foreach ($days as $day): ?>
                        <div class="class-slot">
                            <?php if (!empty($timetable[$slot_key][$day])): ?>
                                <?php foreach ($timetable[$slot_key][$day] as $class): ?>
                                    <div class="class-card <?php echo $class['status']; ?>" 
                                         data-id="<?php echo $class['session_id']; ?>"
                                         onclick="editClass(<?php echo $class['session_id']; ?>)">
                                        <div class="class-name"><?php echo htmlspecialchars($class['class_name']); ?></div>
                                        <div class="class-details"><?php echo htmlspecialchars($class['room']); ?></div>
                                        <div class="class-instructor">
                                            <?php echo htmlspecialchars($class['instructor']); ?>
                                            <span class="status-badge <?php echo $class['status']; ?>">
                                                <?php echo ucfirst($class['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- List View (hidden by default) -->
        <!-- List View (hidden by default) -->
        <div class="class-list-view" style="display: none;">
            <div class="class-list-header">
                <div>Class Name</div>
                <div>Schedule</div>
                <div>Room</div>
                <div>Instructor</div>
                <div>Status</div>
                <div>Actions</div>
            </div>
            <div class="class-list-row">
                <div>Introduction to Programming</div>
                <div>Mon, 8:00 - 10:00</div>
                <div>Room B12</div>
                <div>Prof. Anderson</div>
                <div><span class="status-badge completed">Completed</span></div>
                <div class="class-actions">
                    <button class="view"><i class="fas fa-eye"></i></button>
                    <button class="edit"><i class="fas fa-edit"></i></button>
                    <button class="cancel"><i class="fas fa-ban"></i></button>
                </div>
            </div>
            <div class="class-list-row">
                <div>Data Structures & Algorithms</div>
                <div>Tue, 10:30 - 12:30</div>
                <div>Room A5</div>
                <div>Dr. Smith</div>
                <div><span class="status-badge ongoing">Ongoing</span></div>
                <div class="class-actions">
                    <button class="view"><i class="fas fa-eye"></i></button>
                    <button class="edit"><i class="fas fa-edit"></i></button>
                    <button class="cancel"><i class="fas fa-ban"></i></button>
                </div>
            </div>
            <div class="class-list-row">
                <div>Marketing Principles</div>
                <div>Wed, 10:30 - 12:30</div>
                <div>Room D7</div>
                <div>Mrs. Peterson</div>
                <div><span class="status-badge canceled">Canceled</span></div>
                <div class="class-actions">
                    <button class="view"><i class="fas fa-eye"></i></button>
                    <button class="edit"><i class="fas fa-edit"></i></button>
                    <button class="cancel"><i class="fas fa-ban"></i></button>
                </div>
            </div>
            <div class="class-list-row">
                <div>Web Development</div>
                <div>Mon, 13:30 - 15:30</div>
                <div>Room D7</div>
                <div>Mr. Williams</div>
                <div><span class="status-badge upcoming">Upcoming</span></div>
                <div class="class-actions">
                    <button class="view"><i class="fas fa-eye"></i></button>
                    <button class="edit"><i class="fas fa-edit"></i></button>
                    <button class="cancel"><i class="fas fa-ban"></i></button>
                </div>
            </div>
            <div class="class-list-row">
                <div>UI/UX Design</div>
                <div>Fri, 10:30 - 12:30</div>
                <div>Lab C3</div>
                <div>Mr. Thomas</div>
                <div><span class="status-badge upcoming">Upcoming</span></div>
                <div class="class-actions">
                    <button class="view"><i class="fas fa-eye"></i></button>
                    <button class="edit"><i class="fas fa-edit"></i></button>
                    <button class="cancel"><i class="fas fa-ban"></i></button>
                </div>
            </div>
            <div class="class-list-row">
                <div>Mobile App Development</div>
                <div>Tue, 16:00 - 18:00</div>
                <div>Lab C3</div>
                <div>Prof. Brown</div>
                <div><span class="status-badge ongoing">Ongoing</span></div>
                <div class="class-actions">
                    <button class="view"><i class="fas fa-eye"></i></button>
                    <button class="edit"><i class="fas fa-edit"></i></button>
                    <button class="cancel"><i class="fas fa-ban"></i></button>
                </div>
            </div>
        </div>

        <!-- Add Class Modal (hidden by default) -->
        <div class="add-class-modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-plus-circle"></i> Add New Class</h3>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="className">Class Name</label>
                                <input type="text" id="className" placeholder="Enter class name">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="courseSelect">Course</label>
                                <select id="courseSelect">
                                    <option value="">Select a course</option>
                                    <option value="1">Computer Science</option>
                                    <option value="2">Business Administration</option>
                                    <option value="3">Digital Marketing</option>
                                    <option value="4">Graphic Design</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="instructorSelect">Instructor</label>
                                <select id="instructorSelect">
                                    <option value="">Select an instructor</option>
                                    <option value<option value="">Select an instructor</option>
                                    <option value="1">Dr. Smith</option>
                                    <option value="2">Prof. Anderson</option>
                                    <option value="3">Ms. Johnson</option>
                                    <option value="4">Mr. Williams</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="classDay">Day of Week</label>
                                <select id="classDay">
                                    <option value="">Select day</option>
                                    <option value="monday">Monday</option>
                                    <option value="tuesday">Tuesday</option>
                                    <option value="wednesday">Wednesday</option>
                                    <option value="thursday">Thursday</option>
                                    <option value="friday">Friday</option>
                                    <option value="saturday">Saturday</option>
                                    <option value="sunday">Sunday</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="roomSelect">Room</label>
                                <select id="roomSelect">
                                    <option value="">Select a room</option>
                                    <option value="A5">Room A5</option>
                                    <option value="B12">Room B12</option>
                                    <option value="C3">Lab C3</option>
                                    <option value="D7">Room D7</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="startTime">Start Time</label>
                                <input type="time" id="startTime">
                            </div>
                            <div class="form-group">
                                <label for="endTime">End Time</label>
                                <input type="time" id="endTime">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="classDescription">Description (Optional)</label>
                            <textarea id="classDescription" rows="3" placeholder="Enter class description"></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary close-add-modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Class</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Class Modal (hidden by default) -->
        <div class="edit-class-modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-edit"></i> Edit Class</h3>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="editClassName">Class Name</label>
                                <input type="text" id="editClassName" value="Data Structures & Algorithms">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="editCourseSelect">Course</label>
                                <select id="editCourseSelect">
                                    <option value="">Select a course</option>
                                    <option value="1" selected>Computer Science</option>
                                    <option value="2">Business Administration</option>
                                    <option value="3">Digital Marketing</option>
                                    <option value="4">Graphic Design</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editInstructorSelect">Instructor</label>
                                <select id="editInstructorSelect">
                                    <option value="">Select an instructor</option>
                                    <option value="1" selected>Dr. Smith</option>
                                    <option value="2">Prof. Anderson</option>
                                    <option value="3">Ms. Johnson</option>
                                    <option value="4">Mr. Williams</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="editClassDay">Day of Week</label>
                                <select id="editClassDay">
                                    <option value="">Select day</option>
                                    <option value="monday">Monday</option>
                                    <option value="tuesday" selected>Tuesday</option>
                                    <option value="wednesday">Wednesday</option>
                                    <option value="thursday">Thursday</option>
                                    <option value="friday">Friday</option>
                                    <option value="saturday">Saturday</option>
                                    <option value="sunday">Sunday</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editRoomSelect">Room</label>
                                <select id="editRoomSelect">
                                    <option value="">Select a room</option>
                                    <option value="A5" selected>Room A5</option>
                                    <option value="B12">Room B12</option>
                                    <option value="C3">Lab C3</option>
                                    <option value="D7">Room D7</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="editStartTime">Start Time</label>
                                <input type="time" id="editStartTime" value="10:30">
                            </div>
                            <div class="form-group">
                                <label for="editEndTime">End Time</label>
                                <input type="time" id="editEndTime" value="12:30">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="editClassStatus">Status</label>
                                <select id="editClassStatus">
                                    <option value="upcoming">Upcoming</option>
                                    <option value="ongoing" selected>Ongoing</option>
                                    <option value="completed">Completed</option>
                                    <option value="canceled">Canceled</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editCancelReason">Cancellation Reason (if applicable)</label>
                            <textarea id="editCancelReason" rows="2" placeholder="Reason for cancellation"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editClassDescription">Description (Optional)</label>
                            <textarea id="editClassDescription" rows="3" placeholder="Enter class description">Advanced programming techniques and algorithmic analysis.</textarea>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-danger">Cancel Class</button>
                            <button type="button" class="btn btn-secondary close-edit-modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
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

        // Toggle between week and list views
        const viewToggleButtons = document.querySelectorAll('.view-toggle button');
        const timetableView = document.querySelector('.timetable');
        const listView = document.querySelector('.class-list-view');
        
        viewToggleButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                viewToggleButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                if (index === 0) {
                    timetableView.style.display = 'block';
                    listView.style.display = 'none';
                } else {
                    timetableView.style.display = 'none';
                    listView.style.display = 'block';
                }
            });
        });


        // Open edit modal when clicking on edit buttons
        const editButtons = document.querySelectorAll('.edit');
        editButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                editClassModal.style.display = 'flex';
            });
        });
        
        // Make class cards clickable to open edit modal
        const classCards = document.querySelectorAll('.class-card');
        classCards.forEach(card => {
            card.addEventListener('click', () => {
                editClassModal.style.display = 'flex';
            });
        });
    </script>
</body>
</html>