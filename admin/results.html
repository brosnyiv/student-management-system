<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Monaco Institute Results</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 8px;
            color: var(--primary-color);
        }

        .filter-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-group {
            flex: 1 1 200px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #666;
        }

        .filter-group select, 
        .filter-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            background-color: white;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .filter-button, 
        .reset-button {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .filter-button {
            background-color: var(--primary-color);
            color: white;
        }

        .filter-button:hover {
            background-color: var(--primary-light);
        }

        .reset-button {
            background-color: #e9ecef;
            color: var(--text-color);
        }

        .reset-button:hover {
            background-color: #dee2e6;
        }

        .results-table {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #f8f9fa;
            color: var(--text-color);
            font-weight: 600;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid var(--border-color);
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }

        tr:hover {
            background-color: var(--hover-color);
        }

        .student-photo {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--primary-color);
        }

        .student-info {
            display: flex;
            align-items: center;
        }

        .grade {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 3px;
            text-align: center;
            width: 40px;
            display: inline-block;
        }

        .grade-a {
            background-color: rgba(46, 204, 113, 0.2);
            color: #27ae60;
        }

        .grade-b {
            background-color: rgba(52, 152, 219, 0.2);
            color: #2980b9;
        }

        .grade-c {
            background-color: rgba(241, 196, 15, 0.2);
            color: #f39c12;
        }

        .grade-d {
            background-color: rgba(230, 126, 34, 0.2);
            color: #d35400;
        }

        .grade-f {
            background-color: rgba(231, 76, 60, 0.2);
            color: #c0392b;
        }

        .action-icons {
            display: flex;
            gap: 10px;
        }

        .action-icon {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .view {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .edit {
            background-color: rgba(243, 156, 18, 0.1);
            color: #f39c12;
        }

        .print {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }

        .view:hover {
            background-color: rgba(52, 152, 219, 0.2);
        }

        .edit:hover {
            background-color: rgba(243, 156, 18, 0.2);
        }

        .print:hover {
            background-color: rgba(46, 204, 113, 0.2);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 5px;
        }

        .pagination button {
            width: 35px;
            height: 35px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .pagination button.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination button:hover:not(.active) {
            background-color: var(--hover-color);
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .summary-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .summary-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 14px;
            color: #666;
        }

        .icon-pass {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }

        .icon-fail {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .icon-average {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .icon-total {
            background-color: rgba(156, 39, 176, 0.1);
            color: #9c27b0;
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            color: #666;
            font-size: 14px;
            border-top: 1px solid var(--border-color);
            position: fixed;
            bottom: 0;
            left: 280px;
            right: 0;
            background-color: var(--bg-color);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar-menu li span,
            .institute-name,
            .institute-motto,
            .support-button span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .footer {
                left: 70px;
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
            <li onclick="window.location.href='dash.php'"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></li>
            <li onclick="window.location.href='student.php'"><i class="fas fa-user-graduate"></i> <span>Student Management</span></li>
            <li onclick="window.location.href='staff.php'"><i class="fas fa-user-tie"></i> <span>Staff Management</span></li>
            <li onclick="window.location.href='courses.php'"><i class="fas fa-book"></i> <span>Courses</span></li>
            <li onclick="window.location.href='payments.php'"><i class="fas fa-money-bill-wave"></i> <span>Payments Info</span></li>
            <li onclick="window.location.href='marks&exams.php'"><i class="fas fa-file-alt"></i> <span>Marks & Exams</span></li>
            <li  class="active"  onclick="window.location.href='results.php'"><i class="fas fa-search"></i> <span>Result</span></li>
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
                <p>Results Management</p>
                <div class="date-display">
                    <i class="fas fa-calendar-alt"></i> <span id="currentDate"></span>
                    <span class="time-display"><i class="fas fa-clock"></i> <span id="currentTime"></span></span>
                    <div class="weather-widget">
                        <i class="fas fa-sun weather-icon"></i>
                        <span class="temperature">26°C</span>
                    </div>
                </div>
            </div>
            <div class="user-section">
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
            <input type="text" placeholder="Search by Student Name or ID..." aria-label="Search">
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon icon-total">
                    <i class="fas fa-users"></i>
                </div>
                <div class="summary-value">362</div>
                <div class="summary-label">Total Students</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon icon-pass">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="summary-value">298</div>
                <div class="summary-label">Passed Students</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon icon-fail">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="summary-value">64</div>
                <div class="summary-label">Failed Students</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon icon-average">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="summary-value">72%</div>
                <div class="summary-label">Average Score</div>
            </div>
        </div>

        <div class="filter-section">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-filter"></i> Filter Results</div>
            </div>
            <div class="filter-controls">
                <div class="filter-group">
                    <label for="course">Course</label>
                    <select id="course">
                        <option value="">All Courses</option>
                        <option value="1">BSc Computer Science</option>
                        <option value="2">Diploma in Business Administration</option>
                        <option value="3">Certificate in Digital Marketing</option>
                        <option value="4">Diploma in Graphic Design</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="semester">Semester</label>
                    <select id="semester">
                        <option value="">All Semesters</option>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                        <option value="3">Summer Semester</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="academic-year">Academic Year</label>
                    <select id="academic-year">
                        <option value="">All Years</option>
                        <option value="2024-2025" selected>2024/2025</option>
                        <option value="2023-2024">2023/2024</option>
                        <option value="2022-2023">2022/2023</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="student-search">Search Student</label>
                    <input type="text" id="student-search" placeholder="Name or Student ID">
                </div>
            </div>
            <div class="filter-actions">
                <button class="filter-button"><i class="fas fa-filter"></i> Filter Results</button>
                <button class="reset-button"><i class="fas fa-undo"></i> Reset Filters</button>
            </div>
        </div>

        <div class="results-table">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-file-alt"></i> Examination Results</div>
                <div class="action-buttons">
                    <button class="filter-button"><i class="fas fa-download"></i> Export Results</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>ID</th>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Final Grade</th>
                        <th>Average Score</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="student-info">
                                <div class="student-photo">AM</div>
                                <div>Alice Miller</div>
                            </div>
                        </td>
                        <td>STU24501</td>
                        <td>BSc Computer Science</td>
                        <td>Semester 1</td>
                        <td><span class="grade grade-a">A</span></td>
                        <td>92%</td>
                        <td><span style="color: #2ecc71">Passed</span></td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon view"><i class="fas fa-eye"></i></button>
                                <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                <button class="action-icon print"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="student-info">
                                <div class="student-photo">BJ</div>
                                <div>Bob Johnson</div>
                            </div>
                        </td>
                        <td>STU24502</td>
                        <td>BSc Computer Science</td>
                        <td>Semester 1</td>
                        <td><span class="grade grade-b">B</span></td>
                        <td>85%</td>
                        <td><span style="color: #2ecc71">Passed</span></td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon view"><i class="fas fa-eye"></i></button>
                                <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                <button class="action-icon print"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="student-info">
                                <div class="student-photo">CW</div>
                                <div>Carol White</div>
                            </div>
                        </td>
                        <td>STU24503</td>
                        <td>Diploma in Business Administration</td>
                        <td>Semester 1</td>
                        <td><span class="grade grade-a">A</span></td>
                        <td>94%</td>
                        <td><span style="color: #2ecc71">Passed</span></td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon view"><i class="fas fa-eye"></i></button>
                                <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                <button class="action-icon print"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="student-info">
                                <div class="student-photo">DT</div>
                                <div>David Thompson</div>
                            </div>
                        </td>
                        <td>STU24504</td>
                        <td>Certificate in Digital Marketing</td>
                        <td>Semester 1</td>
                        <td><span class="grade grade-c">C</span></td>
                        <td>75%</td>
                        <td><span style="color: #2ecc71">Passed</span></td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon view"><i class="fas fa-eye"></i></button>
                                <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                <button class="action-icon print"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="student-info">
                                <div class="student-photo">ES</div>
                                <div>Emma Smith</div>
                            </div>
                        </td>
                        <td>STU24505</td>
                        <td>Diploma in Graphic Design</td>
                        <td>Semester 1</td>
                        <td><span class="grade grade-a">A</span></td>
                        <td>90%</td>
                        <td><span style="color: #2ecc71">Passed</span></td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon view"><i class="fas fa-eye"></i></button>
                                <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                <button class="action-icon print"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="student-info">
                                <div class="student-photo">FD</div>
                                <div>Frank Davis</div>
                            </div>
                        </td>
                        <td>STU24506</td>
                        <td>BSc Computer Science</td>
                        <td>Semester 1</td>
                        <td><span class="grade grade-f">F</span></td>
                        <td>48%</td>
                        <td><span style="color: #e74c3c">Failed</span></td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon view"><i class="fas fa-eye"></i></button>
                                <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                <button class="action-icon print"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="student-info">
                                <div class="student-photo">GA</div>
                                <div>Grace Anderson</div>
                            </div>
                        </td>
                        <td>STU24507</td>
                        <td>Diploma in Business Administration</td>
                        <td>Semester 1</td>
                        <td><span class="grade grade-b">B</span></td>
                        <td>87%</td>
                        <td><span style="color: #2ecc71">Passed</span></td>
                        <td>
                            <div class="action-icons">
                            <button class="action-icon view"><i class="fas fa-eye"></i></button>
                                <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                <button class="action-icon print"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="student-info">
                                <div class="student-photo">HW</div>
                                <div>Henry Wilson</div>
                            </div>
                        </td>
                        <td>STU24508</td>
                        <td>Certificate in Digital Marketing</td>
                        <td>Semester 1</td>
                        <td><span class="grade grade-d">D</span></td>
                        <td>62%</td>
                        <td><span style="color: #e67e22">Passed</span></td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon view"><i class="fas fa-eye"></i></button>
                                <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                <button class="action-icon print"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <button><i class="fas fa-angle-double-left"></i></button>
                <button><i class="fas fa-angle-left"></i></button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button>4</button>
                <button>5</button>
                <button><i class="fas fa-angle-right"></i></button>
                <button><i class="fas fa-angle-double-right"></i></button>
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
    </script>
</body>
</html>