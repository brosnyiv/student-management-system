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
                <div class="status-count">24</div>
                <div class="status-label">Completed</div>
            </div>
            <div class="status-card ongoing">
                <div class="status-icon">
                    <i class="fas fa-play"></i>
                </div>
                <div class="status-count">3</div>
                <div class="status-label">Ongoing</div>
            </div>
            <div class="status-card upcoming">
                <div class="status-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="status-count">18</div>
                <div class="status-label">Upcoming</div>
            </div>
            <div class="status-card canceled">
                <div class="status-icon">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="status-count">2</div>
                <div class="status-label">Canceled</div>


                
            </div>
        </div>

        <!-- Timetable Management Panel -->
        <div class="section-header">
            <div class="section-title"><i class="fas fa-calendar-week"></i> Timetable Management</div>
            <div class="action-buttons">
                <button class="add-button" id="openAddClassModal"  onclick="window.location.href='timetable.php'"
                ><i class="fas fa-plus"></i> Add New Class</button>
            </div>
        </div>

        <!-- Filters -->
        <div class="class-filters">
            <div class="filter-group">
                <label for="filterCourse">Course:</label>
                <select id="filterCourse">
                    <option value="">All Courses</option>
                    <option value="1">Computer Science</option>
                    <option value="2">Business Administration</option>
                    <option value="3">Digital Marketing</option>
                    <option value="4">Graphic Design</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filterInstructor">Instructor:</label>
                <select id="filterInstructor">
                    <option value="">All Instructors</option>
                    <option value="1">Dr. Smith</option>
                    <option value="2">Prof. Anderson</option>
                    <option value="3">Ms. Johnson</option>
                    <option value="4">Mr. Williams</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filterRoom">Room:</label>
                <select id="filterRoom">
                    <option value="">All Rooms</option>
                    <option value="A5">Room A5</option>
                    <option value="B12">Room B12</option>
                    <option value="C3">Lab C3</option>
                    <option value="D7">Room D7</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filterStatus">Status:</label>
                <select id="filterStatus">
                    <option value="">All Statuses</option>
                    <option value="completed">Completed</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>
            <div class="view-toggle">
                <button class="active"><i class="fas fa-calendar-week"></i> Week</button>
                <button><i class="fas fa-list"></i> List</button>
            </div>
        </div>

        <!-- Week View -->
        <div class="timetable">
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
                <!-- 8:00 - 10:00 -->
                <div class="time-slot">8:00 - 10:00</div>
                <div class="class-slot">
                    <div class="class-card completed">
                        <div class="class-name">Introduction to Programming</div>
                        <div class="class-details">Room B12</div>
                        <div class="class-instructor">
                            Prof. Anderson
                            <span class="status-badge completed">Completed</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot">
                    <div class="class-card completed">
                        <div class="class-name">Business Ethics</div>
                        <div class="class-details">Room A5</div>
                        <div class="class-instructor">
                            Dr. Williams
                            <span class="status-badge completed">Completed</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot"></div>
                <div class="class-slot">
                    <div class="class-card upcoming">
                        <div class="class-name">Database Management</div>
                        <div class="class-details">Lab C3</div>
                        <div class="class-instructor">
                            Ms. Johnson
                            <span class="status-badge upcoming">Upcoming</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot"></div>
                <div class="class-slot"></div>
                <div class="class-slot"></div>

                <!-- 10:30 - 12:30 -->
                <div class="time-slot">10:30 - 12:30</div>
                <div class="class-slot"></div>
                <div class="class-slot">
                    <div class="class-card ongoing">
                        <div class="class-name">Data Structures & Algorithms</div>
                        <div class="class-details">Room A5</div>
                        <div class="class-instructor">
                            Dr. Smith
                            <span class="status-badge ongoing">Ongoing</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot">
                    <div class="class-card canceled">
                        <div class="class-name">Marketing Principles</div>
                        <div class="class-details">Room D7</div>
                        <div class="class-instructor">
                            Mrs. Peterson
                            <span class="status-badge canceled">Canceled</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot"></div>
                <div class="class-slot">
                    <div class="class-card upcoming">
                        <div class="class-name">UI/UX Design</div>
                        <div class="class-details">Lab C3</div>
                        <div class="class-instructor">
                            Mr. Thomas
                            <span class="status-badge upcoming">Upcoming</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot"></div>
                <div class="class-slot"></div>

                <!-- 13:30 - 15:30 -->
                <div class="time-slot">13:30 - 15:30</div>
                <div class="class-slot">
                    <div class="class-card upcoming">
                        <div class="class-name">Web Development</div>
                        <div class="class-details">Room D7</div>
                        <div class="class-instructor">
                            Mr. Williams
                            <span class="status-badge upcoming">Upcoming</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot"></div>
                <div class="class-slot">
                    <div class="class-card upcoming">
                        <div class="class-name">Financial Accounting</div>
                        <div class="class-details">Room A5</div>
                        <div class="class-instructor">
                            Prof. Roberts
                            <span class="status-badge upcoming">Upcoming</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot"></div>
                <div class="class-slot">
                    <div class="class-card upcoming">
                        <div class="class-name">Database Management</div>
                        <div class="class-details">Lab C3</div>
                        <div class="class-instructor">
                            Ms. Johnson
                            <span class="status-badge upcoming">Upcoming</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot"></div>
                <div class="class-slot"></div>

                <!-- 16:00 - 18:00 -->
                <div class="time-slot">16:00 - 18:00</div>
                <div class="class-slot"></div>
                <div class="class-slot">
                    <div class="class-card ongoing">
                        <div class="class-name">Mobile App Development</div>
                        <div class="class-details">Lab C3</div>
                        <div class="class-instructor">
                            Prof. Brown
                            <span class="status-badge ongoing">Ongoing</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot"></div>
                <div class="class-slot">
                    <div class="class-card upcoming">
                        <div class="class-name">Network Security</div>
                        <div class="class-details">Room B12</div>
                        <div class="class-instructor">
                            Dr. Garcia
                            <span class="status-badge upcoming">Upcoming</span>
                        </div>
                    </div>
                </div>
                <div class="class-slot"></div>
                <div class="class-slot"></div>
                <div class="class-slot"></div>
            </div>
        </div>

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