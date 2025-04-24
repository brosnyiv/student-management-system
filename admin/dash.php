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
                <div class="stat-value">10,000</div>
                <div class="stat-label">Total Students</div>
                <div class="progress-bar"><div class="progress" style="width:80%"></div></div>
                <button class="view-details">View Details</button>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                <div class="stat-value">50</div>
                <div class="stat-label">Total Faculty</div>
                <div class="progress-bar"><div class="progress" style="width:65%"></div></div>
                <button class="view-details">View Details</button>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-bullhorn"></i></div>
                <div class="stat-value">10</div>
                <div class="stat-label">Notice Board (New)</div>
                <div class="progress-bar"><div class="progress" style="width:40%"></div></div>
                <button class="view-details">View Details</button>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-book"></i></div>
                <div class="stat-value">10</div>
                <div class="stat-label">Total Courses</div>
                <div class="progress-bar"><div class="progress" style="width:90%"></div></div>
                <button class="view-details">View Details</button>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="stat-value">10</div>
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
                    <tr>
                        <td>Digital Technology Workshop</td>
                        <td>8:30am</td>
                        <td>B12</td>
                        <td>Mr. Murage Charles</td>
                        <td>Today</td>
                        <td>
                            <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                            <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Career Development Seminar</td>
                        <td>10:30am</td>
                        <td>Main Hall</td>
                        <td>Ms. Sarah Johnson</td>
                        <td>Tomorrow</td>
                        <td>
                            <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                            <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Data Science Master Class</td>
                        <td>2:00pm</td>
                        <td>Lab C3</td>
                        <td>Dr. James Wilson</td>
                        <td>Apr 15, 2025</td>
                        <td>
                            <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                            <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="charts-row">
            <div class="chart-container">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-user-graduate"></i> Student Attendance</div>
                </div>
                <div class="chart">
                    <div class="pie-chart"></div>
                    <div class="percentage">65%</div>
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
                <div class="chart">
                    <div class="pie-chart" style="background: conic-gradient(#8B1818 0% 85%, #E74C3C 85% 90%, #ddd 90% 100%);"></div>
                    <div class="percentage">85%</div>
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
                    <li class="class-item">
                        <div class="class-time">08:00 - 10:00</div>
                        <div class="class-details">
                            <div class="class-name">Introduction to Programming</div>
                            <div class="class-info">Room B12 • Prof. Anderson</div>
                        </div>
                        <div class="class-status completed">Completed</div>
                    </li>
                    <li class="class-item active">
                        <div class="class-time">10:30 - 12:30</div>
                        <div class="class-details">
                            <div class="class-name">Data Structures & Algorithms</div>
                            <div class="class-info">Room A5 • Dr. Smith</div>
                        </div>
                        <div class="class-status ongoing">Ongoing</div>
                    </li>
                    <li class="class-item">
                        <div class="class-time">13:30 - 15:30</div>
                        <div class="class-details">
                            <div class="class-name">Database Management</div>
                            <div class="class-info">Lab C3 • Ms. Johnson</div>
                        </div>
                        <div class="class-status upcoming">Upcoming</div>
                    </li>
                    <li class="class-item">
                        <div class="class-time">16:00 - 18:00</div>
                        <div class="class-details">
                            <div class="class-name">Web Development</div>
                            <div class="class-info">Room D7 • Mr. Williams</div>
                        </div>
                        <div class="class-status upcoming">Upcoming</div>
                    </li>
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
    </script>
</body>
</html>