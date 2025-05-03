<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Monaco Institute</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <!-- Copy your existing sidebar from dash.php -->
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



    <div class="main-content">
        <div class="search-results-container">
            <h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
            
            <?php if (empty($query)): ?>
                <div class="search-message">Please enter a search term</div>
            <?php else: ?>
                <!-- Students Results -->
                <?php if (!empty($results['students'])): ?>
                    <div class="results-section">
                        <h3><i class="fas fa-user-graduate"></i> Students</h3>
                        <div class="results-grid">
                            <?php foreach ($results['students'] as $student): ?>
                                <div class="result-card">
                                    <div class="result-name">
                                        <?php echo htmlspecialchars($student['first_name']) . ' ' . htmlspecialchars($student['last_name']); ?>
                                    </div>
                                    <div class="result-id">ID: <?php echo htmlspecialchars($student['student_id']); ?></div>
                                    <a href="student_profile.php?id=<?php echo $student['id']; ?>" class="view-button">View</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Staff Results -->
                <?php if (!empty($results['staff'])): ?>
                    <div class="results-section">
                        <h3><i class="fas fa-user-tie"></i> Staff</h3>
                        <div class="results-grid">
                            <?php foreach ($results['staff'] as $staff): ?>
                                <div class="result-card">
                                    <div class="result-name">
                                        <?php echo htmlspecialchars($staff['first_name'] ). ' ' . htmlspecialchars($staff['last_name']); ?>
                                    </div>
                                    <div class="result-id">ID: <?php echo htmlspecialchars($staff['staff_id']); ?></div>
                                    <a href="staff_profile.php?id=<?php echo $staff['id']; ?>" class="view-button">View</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Courses Results -->
                <?php if (!empty($results['courses'])): ?>
                    <div class="results-section">
                        <h3><i class="fas fa-book"></i> Courses</h3>
                        <div class="results-grid">
                            <?php foreach ($results['courses'] as $course): ?>
                                <div class="result-card">
                                    <div class="result-name">
                                        <?php echo htmlspecialchars($course['course_name']); ?>
                                    </div>
                                    <div class="result-id">Code: <?php echo htmlspecialchars($course['course_code']); ?></div>
                                    <a href="course_details.php?id=<?php echo $course['id']; ?>" class="view-button">View</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Notices Results -->
                <?php if (!empty($results['notices'])): ?>
                    <div class="results-section">
                        <h3><i class="fas fa-bullhorn"></i> Notices</h3>
                        <div class="results-list">
                            <?php foreach ($results['notices'] as $notice): ?>
                                <div class="notice-result">
                                    <h4><?php echo htmlspecialchars($notice['title']); ?></h4>
                                    <p><?php echo substr(htmlspecialchars($notice['content']), 0, 100); ?>...</p>
                                    <div class="notice-meta">
                                        <span><?php echo date('M d, Y', strtotime($notice['created_at'])); ?></span>
                                        <a href="notice_details.php?id=<?php echo $notice['id']; ?>">Read More</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($results['students']) && empty($results['staff']) && empty($results['courses']) && empty($results['notices'])): ?>
                    <div class="no-results">No results found for "<?php echo htmlspecialchars($query); ?>"</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>