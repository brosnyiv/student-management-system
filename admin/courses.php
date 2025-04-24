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
            <li onclick="window.location.href='dash.php'"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></li>
            <li onclick="window.location.href='student.php'"><i class="fas fa-user-graduate"></i> <span>Student Management</span></li>
            <li onclick="window.location.href='staff.php'"><i class="fas fa-user-tie"></i> <span>Staff Management</span></li>
            <li  class="active" onclick="window.location.href='courses.php'"><i class="fas fa-book"></i> <span>Courses</span></li>
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
                    <div class="user-avatar">J</div>
                    <div class="user-info">
                        John Doe<br>
                        <span class="role">Admin</span>
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

        <!-- List View (Initially visible) -->
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
                    <tr>
                        <td>
                            <div class="course-badge">
                                <div class="course-icon">
                                    <i class="fas fa-laptop-code"></i>
                                </div>
                                <div>
                                    Computer Science
                                    <div style="font-size: 12px; color: #777;">IT Department</div>
                                </div>
                            </div>
                        </td>
                        <td>Diploma</td>
                        <td>16 Weeks</td>
                        <td>125</td>
                        <td>Dr. John Smith</td>
                        <td><span class="status-tag status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                <button class="btn-icon btn-edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="course-badge">
                                <div class="course-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div>
                                    Business Administration
                                    <div style="font-size: 12px; color: #777;">Business Department</div>
                                </div>
                            </div>
                        </td>
                        <td>Diploma</td>
                        <td>14 Weeks</td>
                        <td>98</td>
                        <td>Sarah Johnson</td>
                        <td><span class="status-tag status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                <button class="btn-icon btn-edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="course-badge">
                                <div class="course-icon">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div>
                                    Digital Marketing
                                    <div style="font-size: 12px; color: #777;">Marketing Department</div>
                                </div>
                            </div>
                        </td>
                        <td>Certificate</td>
                        <td>8 Weeks</td>
                        <td>76</td>
                        <td>Mark Cooper</td>
                        <td><span class="status-tag status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                <button class="btn-icon btn-edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="course-badge">
                                <div class="course-icon">
                                    <i class="fas fa-paint-brush"></i>
                                </div>
                                <div>
                                    Graphic Design
                                    <div style="font-size: 12px; color: #777;">Design Department</div>
                                </div>
                            </div>
                        </td>
                        <td>Certificate</td>
                        <td>10 Weeks</td>
                        <td>82</td>
                        <td>Lisa Taylor</td>
                        <td><span class="status-tag status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                <button class="btn-icon btn-edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="course-badge">
                                <div class="course-icon">
                                    <i class="fas fa-code"></i>
                                </div>
                                <div>
                                    Web Development
                                    <div style="font-size: 12px; color: #777;">IT Department</div>
                                </div>
                            </div>
                        </td>
                        <td>Diploma</td>
                        <td>16 Weeks</td>
                        <td>110</td>
                        <td>Robert Williams</td>
                        <td><span class="status-tag status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                <button class="btn-icon btn-edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="course-badge">
                                <div class="course-icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div>
                                    Data Science
                                    <div style="font-size: 12px; color: #777;">IT Department</div>
                                </div>
                            </div>
                        </td>
                        <td>Degree</td>
                        <td>24 Weeks</td>
                        <td>65</td>
                        <td>Dr. James Wilson</td>
                        <td><span class="status-tag status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                <button class="btn-icon btn-edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="course-badge">
                                <div class="course-icon">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div>
                                    Artificial Intelligence
                                    <div style="font-size: 12px; color: #777;">IT Department</div>
                                </div>
                            </div>
                        </td>
                        <td>Degree</td>
                        <td>20 Weeks</td>
                        <td>42</td>
                        <td>Dr. Emily Chen</td>
                        <td><span class="status-tag status-upcoming">Upcoming</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view"><i class="fas fa-eye"></i></button>
                                <button class="btn-icon btn-edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
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
        
        
</script>
</body>
</html>