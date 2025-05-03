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

// Uganda Public Holidays 2025 (Fixed + Computed Dates)
$current_year = date('Y');
$uganda_holidays = [
    '01-01' => 'New Year’s Day',
    '01-26' => 'Liberation Day',
    '03-08' => 'International Women’s Day',
    // Good Friday (April 18, 2025 - Computed)
    '04-18' => 'Good Friday',
    // Easter Monday (April 21, 2025 - Computed)
    '04-21' => 'Easter Monday',
    '05-01' => 'Labour Day',
    '06-03' => 'Martyrs’ Day',
    '06-09' => 'National Heroes’ Day',
    '10-09' => 'Independence Day',
    '12-25' => 'Christmas Day',
    '12-26' => 'Boxing Day'
];

// Get current date info
$current_date = new DateTime();
$current_day = $current_date->format('j');
$current_month = $current_date->format('n');
$current_year = $current_date->format('Y');

// For 2026
if ($current_year == 2026) {
    $uganda_holidays['04-03'] = 'Good Friday';
    $uganda_holidays['04-06'] = 'Easter Monday';
}

// Count total students
$sql = "SELECT COUNT(*) as total_students FROM students";
$result = mysqli_query($conn, $sql);
 if ($result) {
  $student_count = mysqli_fetch_assoc($result)['total_students'];
} else {
   $student_count = 0;
     // For debugging
  // echo "Error in student query: " . mysqli_error($conn);
}

$sql = "SELECT COUNT(*) as total_staff  FROM staff";
$result = mysqli_query($conn, $sql);
 if ($result) {
  $staff_count = mysqli_fetch_assoc($result)['total_staff'];
} else {
   $staff_count = 0;
     // For debugging
  // echo "Error in student query: " . mysqli_error($conn);
}

$sql = "SELECT COUNT(*) as total_notices FROM notices";
$result = mysqli_query($conn, $sql);
 if ($result) {
  $notice_count = mysqli_fetch_assoc($result)['total_notices'];
} else {
   $notice_count = 0;
     // For debugging
  // echo "Error in student query: " . mysqli_error($conn);
}

$sql = "SELECT COUNT(*) as total_courses FROM courses";
$result = mysqli_query($conn, $sql);
 if ($result) {
  $course_count = mysqli_fetch_assoc($result)['total_courses'];
} else {
   $course_count = 0;
     // For debugging
  // echo "Error in student query: " . mysqli_error($conn);
}

$sql = "SELECT COUNT(*) as total_rooms FROM rooms";
$result = mysqli_query($conn, $sql);
 if ($result) {
  $rooms_count = mysqli_fetch_assoc($result)['total_rooms'];
} else {
   $rooms_count = 0;
     // For debugging
  // echo "Error in student query: " . mysqli_error($conn);
}
//calculate student attendance and display it as a percentage on front end
$sql = "SELECT COUNT(*) as total_present FROM student_attendance WHERE status = 'present'";
$result = mysqli_query($conn, $sql);
if ($result) {
    $student_present = mysqli_fetch_assoc($result)['total_present'];
} else {
    $student_present = 0;
    // For debugging
    // echo "Error in student query: " . mysqli_error($conn);
}



//staff attendance in a similar manner as students attendance
$sql = "SELECT COUNT(*) as total_present FROM staff_attendance_records WHERE status = 'present'";
$result = mysqli_query($conn, $sql);
if ($result) {
    $staff_present = mysqli_fetch_assoc($result)['total_present'];
} else {
    $staff_present = 0;
    // For debugging
    // echo "Error in student query: " . mysqli_error($conn);
}


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

// Fetch upcoming events
$sql = "SELECT event_id as id, title, start_datetime, location, organizer_id 
        FROM events 
        WHERE start_datetime >= NOW() 
        ORDER BY start_datetime ASC 
        LIMIT 5"; // Limit to 5 upcoming events

$result = mysqli_query($conn, $sql);
if ($result) {
    $events_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $events_data = [];
    // For debugging
    // echo "Error in events query: " . mysqli_error($conn);
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
    <style>
        /* Holiday Styling */
.calendar-day.holiday {
    color:rgb(19, 3, 238); 
    font-weight: bold;
}

.calendar-day.holiday:hover::after {
    content: attr(title);
    position: absolute;
    background:#8B1818;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    z-index: 100;
    margin-top: 25px;
    margin-left: -10px;
}

/* Current Day Styling */
.calendar-day.current {
    background-color: #8B1818;
    color: white;
}
/* Attendance Chart Colors */
.pie-chart {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin: 0 auto;
}

/* Standardized Colors */
/* Present - Green */
.conic-gradient #4CAF50 { 
    stop-color: #4CAF50; /* Solid green */
}

/* Absent - Light Red */
.conic-gradient #FF5252 { 
    stop-color: #FF5252; /* Soft red */
}

/* On Leave - Gray */
.conic-gradient #9E9E9E { 
    stop-color: #9E9E9E; /* Medium gray */
}

.percentage {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: bold;
    font-size: 1.2rem;
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


        <div class="search-bar">
    <form method="GET" action=""> 
        <input type="text" name="query" placeholder="Search students, staff, courses..." aria-label="Search">
        <button type="submit"><i class="fas fa-search"></i></button>
    </form>
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
                <div class="stat-value"><?php echo $staff_count; ?></div>
                <div class="stat-label">Total Staff</div>
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
                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                    <td>
                        <?php 
                        $start_time = new DateTime($event['start_datetime']);
                        echo $start_time->format('h:i A');
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                    <td>
                        <?php 
                        // You might want to fetch organizer name from staff table
                        echo htmlspecialchars($event['organizer_id']); 
                        ?>
                    </td>
                    <td>
                        <?php 
                        $today = new DateTime('today');
                        $tomorrow = new DateTime('tomorrow');
                        
                        if ($start_time->format('Y-m-d') == $today->format('Y-m-d')) {
                            echo 'Today';
                        } elseif ($start_time->format('Y-m-d') == $tomorrow->format('Y-m-d')) {
                            echo 'Tomorrow';
                        } else {
                            echo $start_time->format('M d, Y');
                        }
                        ?>
                    </td>
                    <td>
                        <button class="action-icon edit" onclick="location.href='edit_event.php?id=<?php echo $event['id']; ?>'">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-icon delete" onclick="if(confirm('Are you sure you want to delete this event?')) location.href='delete_event.php?id=<?php echo $event['id']; ?>'">
                            <i class="fas fa-trash"></i>
                        </button>
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
                            #4CAF50 0% <?php echo $student_present; ?>%, 
                            #FF5252 <?php echo $student_present; ?>% <?php echo ($student_present + $student_absent); ?>%, 
                            #9E9E9E <?php echo ($student_present + $student_absent); ?>% 100);"></div>
                        <div class="percentage"><?php echo $student_present; ?>%</div>
                    </div>

                    <div class="chart-legend">
    <div class="legend-item">
        <div class="legend-color" style="background-color: #4CAF50;"></div>
        <span>Present</span>
    </div>
    <div class="legend-item">
        <div class="legend-color" style="background-color: #FF5252;"></div>
        <span>Absent</span>
    </div>
    <div class="legend-item">
        <div class="legend-color" style="background-color: #9E9E9E;"></div>
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
        <div class="legend-color" style="background-color: #4CAF50;"></div>
        <span>Present</span>
    </div>
    <div class="legend-item">
        <div class="legend-color" style="background-color: #FF5252;"></div>
        <span>Absent</span>
    </div>
    <div class="legend-item">
        <div class="legend-color" style="background-color: #9E9E9E;"></div>
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
        <div class="calendar-title">
            <?php echo date('F Y', strtotime("$current_year-$current_month-01")); ?>
        </div>
        <div class="calendar-navigation">
            <button id="nextMonth"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
    <div class="calendar-grid">
        <!-- Day Headers -->
        <div class="day-header">Sun</div>
        <div class="day-header">Mon</div>
        <div class="day-header">Tue</div>
        <div class="day-header">Wed</div>
        <div class="day-header">Thu</div>
        <div class="day-header">Fri</div>
        <div class="day-header">Sat</div>
        
        <?php
        // Calculate calendar layout
        $first_day_of_month = date('w', strtotime("$current_year-$current_month-01"));
        $days_in_month = date('t', strtotime("$current_year-$current_month-01"));
        $days_in_prev_month = date('t', strtotime("last day of previous month", strtotime("$current_year-$current_month-01")));

        // Generate calendar cells
        for ($i = 0; $i < 42; $i++) {
            if ($i < $first_day_of_month) {
                // Previous month's days
                $day = $days_in_prev_month - ($first_day_of_month - $i - 1);
                echo "<div class='calendar-day other-month'>$day</div>";
            } elseif ($i < ($first_day_of_month + $days_in_month)) {
                // Current month's days
                $day = $i - $first_day_of_month + 1;
                $date_key = sprintf("%02d-%02d", $current_month, $day);
                $is_today = ($day == $current_day && $current_month == date('n')) ? 'current' : '';
                $is_holiday = isset($uganda_holidays[$date_key]) ? 'holiday' : '';
                $holiday_name = $is_holiday ? $uganda_holidays[$date_key] : '';
                
                echo "<div class='calendar-day $is_today $is_holiday' title='$holiday_name'>$day</div>";
            } else {
                // Next month's days
                $day = $i - ($first_day_of_month + $days_in_month) + 1;
                echo "<div class='calendar-day other-month'>$day</div>";
            }
        }
        ?>
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
    
    // Real-time clock with seconds
function updateClock() {
    const now = new Date();
    
    // Format time with seconds (e.g., "02:30:45 PM")
    const timeString = now.toLocaleTimeString('en-US', { 
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true 
    });
    
    // Format date (e.g., "Monday, April 15, 2024")
    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const dateString = now.toLocaleDateString('en-US', dateOptions);
    
    // Update elements
    document.getElementById('currentTime').textContent = timeString;
    document.getElementById('currentDate').textContent = dateString;
}

// Update immediately and every second
updateClock();
setInterval(updateClock, 1000);

// Add this to your existing JavaScript section
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-bar input');
    const searchForm = document.querySelector('.search-bar form');
    
    // AJAX search suggestions
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length > 2) {
                fetch(`search_suggestions.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        showSearchSuggestions(data);
                    });
            } else {
                hideSearchSuggestions();
            }
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchForm.contains(e.target)) {
                hideSearchSuggestions();
            }
        });
    }
    
    function showSearchSuggestions(suggestions) {
        // Remove existing suggestions if any
        hideSearchSuggestions();
        
        if (suggestions.length === 0) return;
        
        const suggestionsDiv = document.createElement('div');
        suggestionsDiv.className = 'search-suggestions';
        
        suggestions.forEach(item => {
            const suggestion = document.createElement('div');
            suggestion.className = 'suggestion-item';
            suggestion.textContent = item.name;
            suggestion.addEventListener('click', function() {
                searchInput.value = item.name;
                searchForm.submit();
            });
            suggestionsDiv.appendChild(suggestion);
        });
        
        searchForm.appendChild(suggestionsDiv);
    }
    
    function hideSearchSuggestions() {
        const existing = document.querySelector('.search-suggestions');
        if (existing) {
            existing.remove();
        }
    }
});


    </script>
</body>
</html>