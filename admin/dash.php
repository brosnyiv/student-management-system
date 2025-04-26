<?php


ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'dbconnect.php'; // Include the database connection file

// Check if user is not logged in
// if (empty($_SESSION['userid'])) {
//     header("Location: index.php");
//     exit();
// }

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

// Count total faculty
$faculty_query = "SELECT COUNT(*) as total_faculty FROM staff WHERE role = 'faculty'";
$faculty_result = mysqli_query($conn, $faculty_query);
if ($faculty_result) {
    $faculty_count = mysqli_fetch_assoc($faculty_result)['total_faculty'];
} else {
    $faculty_count = 0;
    // For debugging
    // echo "Error in faculty query: " . mysqli_error($conn);
}

// Count total notices
$notice_query = "SELECT COUNT(*) as total_notices FROM notices";
$notice_result = mysqli_query($conn, $notice_query);
if ($notice_result) {
    $notice_count = mysqli_fetch_assoc($notice_result)['total_notices'];
} else {
    $notice_count = 0;
    // For debugging
    // echo "Error in notice query: " . mysqli_error($conn);
}

// Count total courses
$course_query = "SELECT COUNT(*) as total_courses FROM courses";
$course_result = mysqli_query($conn, $course_query);
if ($course_result) {
    $course_count = mysqli_fetch_assoc($course_result)['total_courses'];
} else {
    $course_count = 0;
    // For debugging
    // echo "Error in course query: " . mysqli_error($conn);
}

// Count total rooms (as study items)
$rooms_query = "SELECT COUNT(*) as total_rooms FROM rooms";
$rooms_result = mysqli_query($conn, $rooms_query);
if ($rooms_result) {
    $rooms_count = mysqli_fetch_assoc($rooms_result)['total_rooms'];
} else {
    $rooms_count = 0;
    // For debugging
    // echo "Error in rooms query: " . mysqli_error($conn);
}


// Fetch upcoming events from the database
$events_query = "SELECT * FROM events ORDER BY date ASC LIMIT 4";
$events_result = mysqli_query($conn, $events_query);

// Check if query was successful
$events_data = [];
if ($events_result) {
    while ($event = mysqli_fetch_assoc($events_result)) {
        $events_data[] = $event;
    }
}

// Calculate student attendance percentage
$student_attendance_query = "SELECT 
    (COUNT(CASE WHEN status = 'present' THEN 1 END) * 100.0 / COUNT(*)) as present_percentage,
    (COUNT(CASE WHEN status = 'absent' THEN 1 END) * 100.0 / COUNT(*)) as absent_percentage,
    (COUNT(CASE WHEN status = 'leave' THEN 1 END) * 100.0 / COUNT(*)) as leave_percentage
FROM student_attendance 
WHERE date = CURRENT_DATE()";

$student_att_result = mysqli_query($conn, $student_attendance_query);
if ($student_att_result) {
    $student_att_data = mysqli_fetch_assoc($student_att_result);
    $student_present = round($student_att_data['present_percentage'] ?? 0);
    $student_absent = round($student_att_data['absent_percentage'] ?? 0);
    $student_leave = round($student_att_data['leave_percentage'] ?? 0);
} else {
    $student_present = 65; // Default value
    $student_absent = 25;
    $student_leave = 10;
}

// Calculate staff attendance percentage
$staff_attendance_query = "SELECT 
    (COUNT(CASE WHEN status = 'present' THEN 1 END) * 100.0 / COUNT(*)) as present_percentage,
    (COUNT(CASE WHEN status = 'absent' THEN 1 END) * 100.0 / COUNT(*)) as absent_percentage,
    (COUNT(CASE WHEN status = 'leave' THEN 1 END) * 100.0 / COUNT(*)) as leave_percentage
FROM staff_attendance 
WHERE date = CURRENT_DATE()";

$staff_att_result = mysqli_query($conn, $staff_attendance_query);
if ($staff_att_result) {
    $staff_att_data = mysqli_fetch_assoc($staff_att_result);
    $staff_present = round($staff_att_data['present_percentage'] ?? 0);
    $staff_absent = round($staff_att_data['absent_percentage'] ?? 0);
    $staff_leave = round($staff_att_data['leave_percentage'] ?? 0);
} else {
    $staff_present = 85; // Default value
    $staff_absent = 5;
    $staff_leave = 10;
}

// Fetch today's classes
$today = date('Y-m-d');
$classes_query = "SELECT 
    c.class_name, 
    c.start_time, 
    c.end_time, 
    c.room, 
    CONCAT(s.title, ' ', s.firstname, ' ', s.lastname) as instructor,
    CASE 
        WHEN c.start_time <= CURRENT_TIME() AND c.end_time >= CURRENT_TIME() THEN 'ongoing'
        WHEN c.end_time < CURRENT_TIME() THEN 'completed'
        ELSE 'upcoming'
    END as status
FROM classes c
JOIN staff s ON c.instructor_id = s.id
WHERE c.class_date = '$today'
ORDER BY c.start_time ASC";

$classes_result = mysqli_query($conn, $classes_query);
$classes_data = [];
if ($classes_result) {
    while ($class = mysqli_fetch_assoc($classes_result)) {
        $classes_data[] = $class;
    }
}

// Function to get events for the calendar
function getCalendarEvents($conn, $month, $year) {
    $start_date = "$year-$month-01";
    $end_date = date('Y-m-t', strtotime($start_date)); // Last day of month
    
    $events_query = "SELECT date, event_name FROM events 
                     WHERE date BETWEEN '$start_date' AND '$end_date'";
    $events_result = mysqli_query($conn, $events_query);
    
    $events = [];
    if ($events_result) {
        while ($row = mysqli_fetch_assoc($events_result)) {
            $event_day = date('j', strtotime($row['date']));
            $events[$event_day] = true;
        }
    }
    
    return $events;
}

// Get current month and year
$current_month = date('n');
$current_year = date('Y');
$current_day = date('j');

// Get events for current month
$calendar_events = getCalendarEvents($conn, $current_month, $current_year);

// Fetch notifications from the database
$notifications_query = "SELECT * FROM notifications 
                       WHERE user_id = {$_SESSION['userid']} 
                       AND is_read = 0 
                       ORDER BY created_at DESC 
                       LIMIT 5";
$notifications_result = mysqli_query($conn, $notifications_query);

$notifications = [];
$notification_count = 0;

if ($notifications_result) {
    while ($row = mysqli_fetch_assoc($notifications_result)) {
        $notifications[] = $row;
    }
    $notification_count = count($notifications);
    
    // Get total count of unread notifications
    $count_query = "SELECT COUNT(*) as total FROM notifications 
                   WHERE user_id = {$_SESSION['userid']} AND is_read = 0";
    $count_result = mysqli_query($conn, $count_query);
    if ($count_result) {
        $notification_count = mysqli_fetch_assoc($count_result)['total'];
    }
}

// Get user profile image or first letter for avatar
$user_id = $_SESSION['userid'];
$profile_query = "SELECT profile_image, firstname FROM users WHERE id = $user_id";
$profile_result = mysqli_query($conn, $profile_query);

$profile_image = null;
$first_letter = "U"; // Default

if ($profile_result && mysqli_num_rows($profile_result) > 0) {
    $user_data = mysqli_fetch_assoc($profile_result);
    $profile_image = $user_data['profile_image'];
    
    // Get first letter of first name if available
    if (!empty($user_data['firstname'])) {
        $first_letter = strtoupper(substr($user_data['firstname'], 0, 1));
    }
}
else {
    // For debugging
    // echo "Error in profile query: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Dashboard</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <p>Welcome back, <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : "User"; ?></p>
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
        <?php if (!empty($profile_image)): ?>
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">
        <?php else: ?>
            <?php echo $first_letter; ?>
        <?php endif; ?>
    </div>
                    
                    <div class="user-info">
                    <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : "User"; ?><br>
                        <span class="role"><?php echo isset($_SESSION['user_role']) ? htmlspecialchars($_SESSION['user_role']) : "undefined"; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Search..." aria-label="Search">
        </div>

        <div class="quick-access" >
            <div class="quick-access-item">
                <div class="quick-access-icon" onclick="window.location.href='student registration.php'" ><i class="fas fa-user-plus"></i></div>
                <div  onclick="window.location.href='student registration.php'" >Add Student</div>
            </div>  

            <div class="quick-access-item">
                <div class="quick-access-icon"  onclick="window.location.href='staff registration.php'" ><i class="fas fa-user-shield"></i></div>
                <div class="quick-access-text"   onclick="window.location.href='staff registration.php'" > Add Staff</div>
            </div>
            <div class="quick-access-item">
                <div class="quick-access-icon"  onclick="window.location.href='new course.php'"> <i class="fas fa-book-medical"></i></div>
                <div class="quick-access-text" onclick="window.location.href='new course.php'">Add Course</div>
            </div>
            <div class="quick-access-item">
                <div class="quick-access-icon" onclick="window.location.href='events.php'"><i class="fas fa-calendar-plus"></i></div>
                <div class="quick-access-text" onclick="window.location.href='events.php'">Add Event</div>
            </div>
            <div class="quick-access-item">
                <div class="quick-access-icon"><i class="fas fa-file-invoice"></i></div>
                <div class="quick-access-text">Create Report</div>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-value"> <?php echo $student_count; ?></div>
                <div class="stat-label">Total Students</div>
                <div class="progress-bar"><div class="progress" style="width:80%"></div></div>
                <button class="view-details">View Details</button>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                <div class="stat-value"><?php echo $faculty_count; ?></div>
                <div class="stat-label">Total Faculty</div>
                <div class="progress-bar"><div class="progress" style="width:65%"></div></div>
                <button class="view-details">View Details</button>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-bullhorn"></i></div>
                <div class="stat-value"><?php echo $notice_count; ?></div>
                <div class="stat-label">Notice Board (New)</div>
                <div class="progress-bar"><div class="progress" style="width:40%"></div></div>
                <button class="view-details">View Details</button>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-book"></i></div>
                <div class="stat-value"><?php echo $course_count; ?></div>
                <div class="stat-label">Total Courses</div>
                <div class="progress-bar"><div class="progress" style="width:90%"></div></div>
                <button class="view-details">View Details</button>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="stat-value"><?php echo $rooms_count; ?></div>
                <div class="stat-label">Total Study Items</div>
                <div class="progress-bar"><div class="progress" style="width:55%"></div></div>
                <button class="view-details">View Details</button>
            </div>
        </div>

        <div class="upcoming-events">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-calendar-week"></i> Upcoming Events</div>
                <div class="action-buttons">
                    <button class="add-button"  onclick="window.location.href='events.php'"><i class="fas fa-plus"></i> Add New</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th>Instructor</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    <?php if (empty($events_data)): ?>
        <tr>
            <td colspan="6">No upcoming events found</td>
        </tr>
    <?php else: ?>
        <?php foreach ($events_data as $event): ?>
            <tr>
                <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                <td><?php echo htmlspecialchars($event['time']); ?></td>
                <td><?php echo htmlspecialchars($event['venue']); ?></td>
                <td><?php echo htmlspecialchars($event['instructor']); ?></td>
                <td>
                    <?php 
                        $event_date = new DateTime($event['date']);
                        $today = new DateTime('today');
                        $tomorrow = new DateTime('tomorrow');
                        
                        if ($event_date->format('Y-m-d') == $today->format('Y-m-d')) {
                            echo 'Today';
                        } elseif ($event_date->format('Y-m-d') == $tomorrow->format('Y-m-d')) {
                            echo 'Tomorrow';
                        } else {
                            echo $event_date->format('M d, Y');
                        }
                    ?>
                </td>
                <td>
                    <button class="action-icon edit" onclick="location.href='edit_event.php?id=<?php echo $event['id']; ?>'"><i class="fas fa-edit"></i></button>
                    <button class="action-icon delete" onclick="if(confirm('Are you sure you want to delete this event?')) location.href='delete_event.php?id=<?php echo $event['id']; ?>'"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
            </table>
        </div>

        <div class="charts-row">
            <div class="chart-container">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-user-graduate"></i> Student Attendance</div>
                </div>
                <!-- Student Attendance Chart -->
                <div class="chart">
                <div class="pie-chart" style="background: conic-gradient(
                    #8B1818 0% <?php echo $student_present; ?>%, 
                    #E74C3C <?php echo $student_present; ?>% <?php echo ($student_present + $student_absent); ?>%, 
                    #ddd <?php echo ($student_present + $student_absent); ?>% 100%);"></div>
                <div class="percentage"><?php echo $student_present; ?>%</div>
            </div>

                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #8B1818;"></div>
                        <span>Present</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #E74C3C;"></div>
                        <span>Absent</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #ddd;"></div>
                        <span>On Leave</span>
                    </div>
                </div>
            </div>
            <div class="chart-container">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-user-tie"></i> Staff Attendance</div>
                </div>
                <!-- Staff Attendance Chart -->
                <div class="chart">
                    <div class="pie-chart" style="background: conic-gradient(
                        #8B1818 0% <?php echo $staff_present; ?>%, 
                        #E74C3C <?php echo $staff_present; ?>% <?php echo ($staff_present + $staff_absent); ?>%, 
                        #ddd <?php echo ($staff_present + $staff_absent); ?>% 100%);"></div>
                    <div class="percentage"><?php echo $staff_present; ?>%</div>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #8B1818;"></div>
                        <span>Present</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #E74C3C;"></div>
                        <span>Absent</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #ddd;"></div>
                        <span>On Leave</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-section">
            <!-- Schedule with Calendar on the left -->
            <div class="schedule-section" style="margin-top: 20px;">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-calendar-alt"></i> Schedule</div>
                </div>
                <div class="calendar">
                    <div class="calendar-header">
                        <div class="calendar-navigation">
                            <button id="prevMonth"><i class="fas fa-chevron-left"></i></button>
                        </div>
                        <div class="calendar-title" id="calendarTitle">April 2025</div>
                        <div class="calendar-navigation">
                            <button id="nextMonth"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="calendar-grid">
                        <div class="day-header">Sun</div>
                        <div class="day-header">Mon</div>
                        <div class="day-header">Tue</div>
                        <div class="day-header">Wed</div>
                        <div class="day-header">Thu</div>
                        <div class="day-header">Fri</div>
                        <div class="day-header">Sat</div>
                        
                        <div class="calendar-day other-month">30</div>
                        <div class="calendar-day other-month">31</div>
                        <div class="calendar-day">1</div>
                        <div class="calendar-day">2</div>
                        <div class="calendar-day">3</div>
                        <div class="calendar-day">4</div>
                        <div class="calendar-day">5</div>
                        <div class="calendar-day">6</div>
                        <div class="calendar-day">7</div>
                        <div class="calendar-day">8</div>
                        <div class="calendar-day">9</div>
                        <div class="calendar-day">10</div>
                        <div class="calendar-day">11</div>
                        <div class="calendar-day">12</div>
                        <div class="calendar-day">13</div>
                        <div class="calendar-day current has-event">14</div>
                        <div class="calendar-day has-event">15</div>
                        <div class="calendar-day">16</div>
                        <div class="calendar-day">17</div>
                        <div class="calendar-day">18</div>
                        <div class="calendar-day">19</div>
                        <div class="calendar-day">20</div>
                        <div class="calendar-day">21</div>
                        <div class="calendar-day">22</div>
                        <div class="calendar-day">23</div>
                        <div class="calendar-day">24</div>
                        <div class="calendar-day">25</div>
                        <div class="calendar-day">26</div>
                        <div class="calendar-day">27</div>
                        <div class="calendar-day">28</div>
                        <div class="calendar-day">29</div>
                        <div class="calendar-day">30</div>
                        <div class="calendar-day other-month">1</div>
                        <div class="calendar-day other-month">2</div>
                        <div class="calendar-day other-month">3</div>
                    </div>
                </div>
            </div>
            
            <!-- Classes section on the right -->
            <div class="classes-section" style="margin-top: 20px;">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-chalkboard-teacher"></i> Today's Classes</div>
                    <div class="action-buttons">
                        <button class="add-button"  onclick="window.location.href='timetable.php'"><i class="fas fa-plus"></i> Add Class</button>
                    </div>
                </div>
                <ul class="classes-list">
    <?php if (empty($classes_data)): ?>
        <li class="class-item">No classes scheduled for today</li>
    <?php else: ?>
        <?php foreach ($classes_data as $class): ?>
            <li class="class-item <?php echo ($class['status'] == 'ongoing') ? 'active' : ''; ?>">
                <div class="class-time"><?php echo date('H:i', strtotime($class['start_time'])); ?> - <?php echo date('H:i', strtotime($class['end_time'])); ?></div>
                <div class="class-details">
                    <div class="class-name"><?php echo htmlspecialchars($class['class_name']); ?></div>
                    <div class="class-info">Room <?php echo htmlspecialchars($class['room']); ?> • <?php echo htmlspecialchars($class['instructor']); ?></div>
                </div>
                <div class="class-status <?php echo $class['status']; ?>"><?php echo ucfirst($class['status']); ?></div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
            </div>
        </div>

        <!-- Forms Section -->
        
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

        // Function to show different sections
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => {
                section.style.display = 'none';
            });
            
            // Show the selected section
            document.getElementById(sectionId).style.display = 'block';
            
            // Update active nav item
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.classList.remove('active');
                if(item.getAttribute('onclick').includes(sectionId)) {
                    item.classList.add('active');
                }
            });
        }

         // Notification dropdown toggle
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBell = document.getElementById('notificationBell');
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        
        notificationBell.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationsDropdown.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationBell.contains(e.target)) {
                notificationsDropdown.classList.remove('show');
            }
        });
        
        // Prevent clicks inside dropdown from closing it
        notificationsDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Mark notification as read
    function markAsRead(notificationId) {
        fetch('mark_notification_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'notification_id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove notification from list
                const notificationItem = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
                if (notificationItem) {
                    notificationItem.remove();
                    
                    // Update notification count
                    const countElement = document.querySelector('.notification-count');
                    let currentCount = parseInt(countElement.textContent);
                    countElement.textContent = currentCount - 1;
                    
                    // Show "no notifications" message if needed
                    const notificationsList = document.querySelector('.notifications-list');
                    if (notificationsList.children.length === 0) {
                        notificationsList.innerHTML = '<div class="no-notifications">No new notifications</div>';
                    }
                }
            }
        });
    }
        
          // Calendar functionality
    document.addEventListener('DOMContentLoaded', function() {
        let currentMonth = <?php echo $current_month; ?>;
        let currentYear = <?php echo $current_year; ?>;
        let currentDay = <?php echo $current_day; ?>;
        
        // Initial calendar events from PHP
        let calendarEvents = <?php echo json_encode($calendar_events); ?>;
        
        // Update calendar title
        function updateCalendarTitle() {
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                          'July', 'August', 'September', 'October', 'November', 'December'];
            document.getElementById('calendarTitle').textContent = months[currentMonth-1] + ' ' + currentYear;
        }
        
        // Generate calendar days
        function generateCalendarDays() {
            const firstDay = new Date(currentYear, currentMonth-1, 1).getDay(); // Day of week for 1st day (0=Sun, 6=Sat)
            const daysInMonth = new Date(currentYear, currentMonth, 0).getDate(); // Days in current month
            const daysInPrevMonth = new Date(currentYear, currentMonth-1, 0).getDate(); // Days in previous month
            
            let html = '';
            
            // Headers for days of week
            const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayHeaders.forEach(day => {
                html += `<div class="day-header">${day}</div>`;
            });
            
            // Previous month days
            for (let i = firstDay - 1; i >= 0; i--) {
                html += `<div class="calendar-day other-month">${daysInPrevMonth - i}</div>`;
            }
            
            // Current month days
            let today = new Date();
            let isCurrentMonth = (today.getMonth() + 1 === currentMonth && today.getFullYear() === currentYear);
            
            for (let i = 1; i <= daysInMonth; i++) {
                let classes = 'calendar-day';
                if (isCurrentMonth && i === today.getDate()) {
                    classes += ' current';
                }
                if (calendarEvents[i]) {
                    classes += ' has-event';
                }
                html += `<div class="${classes}" data-day="${i}">${i}</div>`;
            }
            
            // Next month days
            const totalDaysShown = firstDay + daysInMonth;
            const remainingDays = 42 - totalDaysShown; // 42 = 6 rows of 7 days
            
            for (let i = 1; i <= remainingDays; i++) {
                if (i <= (42 - totalDaysShown)) {
                    html += `<div class="calendar-day other-month">${i}</div>`;
                }
            }
            
            document.querySelector('.calendar-grid').innerHTML = html;
            
            // Add event listeners to calendar days
            document.querySelectorAll('.calendar-day:not(.other-month)').forEach(day => {
                day.addEventListener('click', function() {
                    const selectedDay = this.getAttribute('data-day');
                    showDayEvents(selectedDay);
                });
            });
        }
        
        // Show events for a specific day
        function showDayEvents(day) {
            if (calendarEvents[day]) {
                // Fetch events for this day via AJAX
                fetch(`get_day_events.php?date=${currentYear}-${currentMonth}-${day}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.events.length > 0) {
                            let eventsHTML = '<div class="day-events-popup">';
                            eventsHTML += `<h4>Events on ${currentMonth}/${day}/${currentYear}</h4>`;
                            eventsHTML += '<ul>';
                            data.events.forEach(event => {
                                eventsHTML += `<li>${event.time} - ${event.event_name}</li>`;
                            });
                            eventsHTML += '</ul>';
                            eventsHTML += '<button class="close-popup">Close</button>';
                            eventsHTML += '</div>';
                            
                            // Show popup
                            const popup = document.createElement('div');
                            popup.className = 'events-popup-overlay';
                            popup.innerHTML = eventsHTML;
                            document.body.appendChild(popup);
                            
                            // Close button event
                            popup.querySelector('.close-popup').addEventListener('click', function() {
                                document.body.removeChild(popup);
                            });
                        }
                    });
            } else {
                // Ask if user wants to add an event on this day
                if (confirm(`Add an event on ${currentMonth}/${day}/${currentYear}?`)) {
                    window.location.href = `events.php?date=${currentYear}-${currentMonth}-${day}`;
                }
            }
        }
        
        // Navigate to previous month
        document.getElementById('prevMonth').addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            }
            updateCalendar();
        });
        
        // Navigate to next month
        document.getElementById('nextMonth').addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }
            updateCalendar();
        });
        
        // Update calendar (title and days)
        function updateCalendar() {
            updateCalendarTitle();
            
            // Fetch events for new month via AJAX
            fetch(`get_calendar_events.php?month=${currentMonth}&year=${currentYear}`)
                .then(response => response.json())
                .then(data => {
                    calendarEvents = data.events;
                    generateCalendarDays();
                });
        }
        
        // Initialize calendar
        updateCalendarTitle();
        generateCalendarDays();
    });
    
    
    </script>
</body>
</html>