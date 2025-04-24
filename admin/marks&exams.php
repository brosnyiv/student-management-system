<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Marks & Exams</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="marks-exams.css">
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
            <li  onclick="window.location.href='dash.php'"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></li>
            <li onclick="window.location.href='student.php'"><i class="fas fa-user-graduate"></i> <span>Student Management</span></li>
            <li onclick="window.location.href='staff.php'"><i class="fas fa-user-tie"></i> <span>Staff Management</span></li>
            <li onclick="window.location.href='courses.php'"><i class="fas fa-book"></i> <span>Courses</span></li>
            <li onclick="window.location.href='payments.php'"><i class="fas fa-money-bill-wave"></i> <span>Payments Info</span></li>
            <li  class="active" onclick="window.location.href='marks&exams.php'"><i class="fas fa-file-alt"></i> <span>Marks & Exams</span></li>
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
                <h1>MARKS & EXAMS</h1>
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
            <input type="text" placeholder="Search for students, exams, or courses..." aria-label="Search">
        </div>

        <div class="tabs-container">
            <div class="tab active" data-tab="exam-periods">Exam Period Management</div>
            <div class="tab" data-tab="marks-management">Course Unit Marks Management</div>
        </div>

        <!-- Exam Period Management Tab Content -->
        <div class="tab-content active" id="exam-periods-content">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-calendar-alt"></i> Exam Period Management</div>
                <div class="action-buttons">
                    <button class="add-button" id="showAddExamPeriodForm"><i class="fas fa-plus"></i> Add New Exam Period</button>
                </div>
            </div>

            <!-- Add New Exam Period Form -->
            <div class="card" id="addExamPeriodForm">
                <div class="card-header">
                    <h3><i class="fas fa-plus-circle"></i> Add New Exam Period</h3>
                </div>
                <div class="card-body">
                    <form class="form-grid">
                        <div class="form-group">
                            <label for="examPeriodName">Exam Period Name</label>
                            <input type="text" id="examPeriodName" placeholder="e.g., Semester 1 - 2025">
                        </div>
                        <div class="form-group">
                            <label for="startDate">Start Date</label>
                            <input type="date" id="startDate">
                        </div>
                        <div class="form-group">
                            <label for="endDate">End Date</label>
                            <input type="date" id="endDate">
                        </div>
                        <div class="form-group full-width">
                            <label for="description">Description (optional)</label>
                            <textarea id="description" placeholder="Enter additional details about this exam period"></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="submit-btn">Save Exam Period</button>
                            <button type="reset" class="reset-btn">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Existing Exam Periods Table -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-list"></i> Existing Exam Periods</h3>
                </div>
                <div class="card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Period Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Semester 1 - 2025</td>
                                <td>Apr 1, 2025</td>
                                <td>Apr 20, 2025</td>
                                <td><span class="status ongoing">Ongoing</span></td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Mid-Term Exams - 2025</td>
                                <td>May 15, 2025</td>
                                <td>May 30, 2025</td>
                                <td><span class="status upcoming">Upcoming</span></td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>First Quarter Assessment - 2025</td>
                                <td>Mar 10, 2025</td>
                                <td>Mar 20, 2025</td>
                                <td><span class="status ended">Ended</span></td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Course Unit Marks Management Tab Content -->
        <div class="tab-content" id="marks-management-content">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-graduation-cap"></i> Course Unit Marks Management</div>
                <div class="action-buttons">
                    <button class="add-button" id="showAddMarksForm"><i class="fas fa-plus"></i> Add/Update Marks</button>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-container">
                <div class="filter-group">
                    <label for="examPeriodFilter">Exam Period:</label>
                    <select id="examPeriodFilter">
                        <option value="">All Periods</option>
                        <option value="1">Semester 1 - 2025</option>
                        <option value="2">Mid-Term Exams - 2025</option>
                        <option value="3">First Quarter Assessment - 2025</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="courseUnitFilter">Course Unit:</label>
                    <select id="courseUnitFilter">
                        <option value="">All Course Units</option>
                        <option value="1">Introduction to Programming</option>
                        <option value="2">Data Structures & Algorithms</option>
                        <option value="3">Database Management</option>
                        <option value="4">Web Development</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="studentFilter">Student:</label>
                    <input type="text" id="studentFilter" placeholder="Student ID or Name">
                </div>
                <div class="filter-actions">
                    <button class="filter-btn"><i class="fas fa-search"></i> Apply Filters</button>
                    <button class="reset-filter-btn"><i class="fas fa-undo"></i> Reset</button>
                </div>
            </div>

            <div class="view-toggle">
                <label>View by:</label>
                <div class="toggle-options">
                    <button class="toggle-btn active" data-view="course">Course Unit</button>
                    <button class="toggle-btn" data-view="student">Student</button>
                </div>
            </div>

            <!-- Add/Update Marks Form -->
            <div class="card" id="addMarksForm">
                <div class="card-header">
                    <h3><i class="fas fa-plus-circle"></i> Add/Update Marks</h3>
                </div>
                <div class="card-body">
                    <div class="tabs-container mark-entry-tabs">
                        <div class="tab active" data-tab="manual-entry">Manual Entry</div>
                        <div class="tab" data-tab="bulk-upload">Bulk Upload</div>
                    </div>
                    
                    <div class="tab-content active" id="manual-entry-content">
                        <form class="form-grid">
                            <div class="form-group">
                                <label for="selectExamPeriod">Exam Period</label>
                                <select id="selectExamPeriod" required>
                                    <option value="">Select Exam Period</option>
                                    <option value="1">Semester 1 - 2025</option>
                                    <option value="2">Mid-Term Exams - 2025</option>
                                    <option value="3">First Quarter Assessment - 2025</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="selectCourseUnit">Course Unit</label>
                                <select id="selectCourseUnit" required>
                                    <option value="">Select Course Unit</option>
                                    <option value="1">Introduction to Programming</option>
                                    <option value="2">Data Structures & Algorithms</option>
                                    <option value="3">Database Management</option>
                                    <option value="4">Web Development</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentId">Student ID</label>
                                <input type="text" id="studentId" placeholder="Enter Student ID" required>
                            </div>
                            <div class="form-group">
                                <label for="studentName">Student Name</label>
                                <input type="text" id="studentName" placeholder="Enter Student Name" required>
                            </div>
                            <div class="form-group">
                                <label for="markValue">Mark (0-100)</label>
                                <input type="number" id="markValue" min="0" max="100" placeholder="Enter mark value" required>
                            </div>
                            <div class="form-group">
                                <label for="gradeValue">Grade (Auto-calculated)</label>
                                <input type="text" id="gradeValue" readonly>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">Save Mark</button>
                                <button type="reset" class="reset-btn">Reset</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="tab-content" id="bulk-upload-content">
                        <form class="form-grid">
                            <div class="form-group">
                                <label for="bulkExamPeriod">Exam Period</label>
                                <select id="bulkExamPeriod" required>
                                    <option value="">Select Exam Period</option>
                                    <option value="1">Semester 1 - 2025</option>
                                    <option value="2">Mid-Term Exams - 2025</option>
                                    <option value="3">First Quarter Assessment - 2025</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bulkCourseUnit">Course Unit</label>
                                <select id="bulkCourseUnit" required>
                                    <option value="">Select Course Unit</option>
                                    <option value="1">Introduction to Programming</option>
                                    <option value="2">Data Structures & Algorithms</option>
                                    <option value="3">Database Management</option>
                                    <option value="4">Web Development</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="csvFile">Upload CSV File</label>
                                <div class="file-upload">
                                    <input type="file" id="csvFile" accept=".csv">
                                    <label for="csvFile" class="file-label"><i class="fas fa-cloud-upload-alt"></i> Choose File</label>
                                    <span class="file-name">No file chosen</span>
                                </div>
                            </div>
                            <div class="form-group full-width">
                                <div class="upload-instructions">
                                    <p><i class="fas fa-info-circle"></i> CSV file should have columns: Student ID, Student Name, Mark</p>
                                    <a href="#" class="download-template"><i class="fas fa-download"></i> Download Template</a>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">Upload Marks</button>
                                <button type="reset" class="reset-btn">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Marks Table -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-table"></i> Course Units Record</h3>
        <div class="card-actions">
            <button class="export-btn"><i class="fas fa-file-pdf"></i> Export PDF</button>
            <button class="export-btn"><i class="fas fa-file-excel"></i> Export Excel</button>
        </div>
    </div>
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course Unit</th>
                    <th>Course Name</th>
                    <th>Teacher</th>
                    <th>Number of Students</th>
                    <th>Assignment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CU2025001</td>
                    <td>Introduction to Programming</td>
                    <td>Dr. Robert Chen</td>
                    <td>32</td>
                    <td>Programming Basics</td>
                    <td>
                        <button class="action-icon view" data-course="Introduction to Programming"><i class="fas fa-eye"></i></button>
                        <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                        <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>CU2025002</td>
                    <td>Data Structures & Algorithms</td>
                    <td>Prof. Lisa Watkins</td>
                    <td>28</td>
                    <td>Algorithm Analysis</td>
                    <td>
                        <button class="action-icon view" data-course="Data Structures & Algorithms"><i class="fas fa-eye"></i></button>
                        <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                        <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>CU2025003</td>
                    <td>Database Management</td>
                    <td>Dr. Michael Patel</td>
                    <td>35</td>
                    <td>SQL Fundamentals</td>
                    <td>
                        <button class="action-icon view" data-course="Database Management"><i class="fas fa-eye"></i></button>
                        <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                        <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>CU2025004</td>
                    <td>Web Development</td>
                    <td>Prof. Sarah Johnson</td>
                    <td>40</td>
                    <td>Responsive Layouts</td>
                    <td>
                        <button class="action-icon view" data-course="Web Development"><i class="fas fa-eye"></i></button>
                        <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                        <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>CU2025005</td>
                    <td>Computer Networks</td>
                    <td>Dr. James Wilson</td>
                    <td>25</td>
                    <td>Network Protocols</td>
                    <td>
                        <button class="action-icon view" data-course="Computer Networks"><i class="fas fa-eye"></i></button>
                        <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                        <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div class="pagination">
            <button class="pagination-btn prev"><i class="fas fa-chevron-left"></i></button>
            <span class="pagination-info">Page 1 of 2</span>
            <button class="pagination-btn next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<!-- Student Course Marks Detail View (Initially Hidden) -->
<div class="card" id="courseStudentsDetail" style="display: none;">
    <div class="card-header">
        <h3><i class="fas fa-users"></i> <span id="selectedCourseName">Course</span> - Students Marks</h3>
        <button class="close-btn" id="closeDetailView"><i class="fas fa-times"></i></button>
    </div>
    <div class="card-body">
        <div class="course-summary">
            <div class="summary-item">
                <span class="label">Course Unit:</span>
                <span class="value" id="detailCourseUnit">CU2025001</span>
            </div>
            <div class="summary-item">
                <span class="label">Teacher:</span>
                <span class="value" id="detailTeacher">Dr. Robert Chen</span>
            </div>
            <div class="summary-item">
                <span class="label">Total Students:</span>
                <span class="value" id="detailStudentCount">32</span>
            </div>
            <div class="summary-item">
                <span class="label">Average Mark:</span>
                <span class="value" id="detailAverageMark">78.5</span>
            </div>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Mark</th>
                    <th>Grade</th>
                    <th>Exam Period</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="courseStudentsTableBody">
                <!-- This will be populated dynamically when a course is clicked -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add this to the view-toggle section in your php -->
<div class="view-toggle">
    <label>View by:</label>
    <div class="toggle-options">
        <button class="toggle-btn active" data-view="course">Course Unit</button>
        <button class="toggle-btn" data-view="student">Student</button>
    </div>
</div>

<!-- Student View Table (Initially Hidden) -->
<div class="card" id="studentViewCard" style="display: none;">
    <div class="card-header">
        <h3><i class="fas fa-user-graduate"></i> Students Record</h3>
        <div class="card-actions">
            <button class="export-btn"><i class="fas fa-file-pdf"></i> Export PDF</button>
            <button class="export-btn"><i class="fas fa-file-excel"></i> Export Excel</button>
        </div>
    </div>
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Number of Courses</th>
                    <th>Average Grade</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ST2025001</td>
                    <td>Jane Smith</td>
                    <td>3</td>
                    <td>A</td>
                    <td>
                        <button class="action-icon view" data-student="ST2025001" data-name="Jane Smith"><i class="fas fa-eye"></i></button>
                        <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                        <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>ST2025002</td>
                    <td>Mark Johnson</td>
                    <td>2</td>
                    <td>B</td>
                    <td>
                        <button class="action-icon view" data-student="ST2025002" data-name="Mark Johnson"><i class="fas fa-eye"></i></button>
                        <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                        <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>ST2025003</td>
                    <td>Sarah Williams</td>
                    <td>4</td>
                    <td>A</td>
                    <td>
                        <button class="action-icon view" data-student="ST2025003" data-name="Sarah Williams"><i class="fas fa-eye"></i></button>
                        <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                        <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>ST2025004</td>
                    <td>Michael Brown</td>
                    <td>3</td>
                    <td>C</td>
                    <td>
                        <button class="action-icon view" data-student="ST2025004" data-name="Michael Brown"><i class="fas fa-eye"></i></button>
                        <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                        <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>ST2025005</td>
                    <td>Emily Davis</td>
                    <td>2</td>
                    <td>A+</td>
                    <td>
                        <button class="action-icon view" data-student="ST2025005" data-name="Emily Davis"><i class="fas fa-eye"></i></button>
                        <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                        <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div class="pagination">
            <button class="pagination-btn prev"><i class="fas fa-chevron-left"></i></button>
            <span class="pagination-info">Page 1 of 2</span>
            <button class="pagination-btn next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<!-- Student Detail View (Initially Hidden) -->
<div class="card" id="studentDetailView" style="display: none;">
    <div class="card-header">
        <h3><i class="fas fa-user"></i> <span id="selectedStudentName">Student</span> - Course Marks</h3>
        <button class="close-btn" id="closeStudentView"><i class="fas fa-times"></i></button>
    </div>
    <div class="card-body">
        <div class="student-summary">
            <div class="summary-item">
                <span class="label">Student ID:</span>
                <span class="value" id="detailStudentId">ST2025001</span>
            </div>
            <div class="summary-item">
                <span class="label">Total Courses Units:</span>
                <span class="value" id="detailCourseCount">3</span>
            </div>
            <div class="summary-item">
                <span class="label">GPA:</span>
                <span class="value" id="detailGPA">3.7</span>
            </div>
            <div class="summary-item">
                <span class="label">Overall Grade:</span>
                <span class="value" id="detailOverallGrade">A</span>
            </div>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course Unit </th>
                    <th>Course Name</th>
                    <th>Teacher</th>
                    <th>Mark</th>
                    <th>Grade</th>
                    <th>Exam Period</th>
                </tr>
            </thead>
            <tbody id="studentCoursesTableBody">
                <!-- This will be populated dynamically when a student is clicked -->
            </tbody>
        </table>
    </div>
</div>

            <!-- Student Report Card Generator -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-id-card"></i> Generate Student Transcript</h3>
                </div>
                <div class="card-body">
                    <form class="form-grid">
                        <div class="form-group">
                            <label for="transcriptStudentId">Student ID</label>
                            <input type="text" id="transcriptStudentId" placeholder="Enter Student ID">
                        </div>
                        <div class="form-group">
                            <label for="transcriptExamPeriod">Exam Period (Optional)</label>
                            <select id="transcriptExamPeriod">
                                <option value="">All Periods</option>
                                <option value="1">Semester 1 - 2025</option>
                                <option value="2">Mid-Term Exams - 2025</option>
                                <option value="3">First Quarter Assessment - 2025</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>PDF Options</label>
                            <div class="checkbox-group">
                                <label class="checkbox-container">
                                    <input type="checkbox" checked>
                                    <span class="checkbox-label">Include Institute Logo</span>
                                </label>
                                <label class="checkbox-container">
                                    <input type="checkbox" checked>
                                    <span class="checkbox-label">Include GPA</span>
                                </label>
                                <label class="checkbox-container">
                                    <input type="checkbox" checked>
                                    <span class="checkbox-label">Include Digital Signature</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="submit-btn"><i class="fas fa-file-pdf"></i> Generate Transcript</button>
                            <button type="button" class="email-btn"><i class="fas fa-envelope"></i> Email to Student</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Audit Trail Section -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-history"></i> Audit Trail</h3>
                </div>
                <div class="card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Apr 15, 2025 - 09:45 AM</td>
                                <td>John Doe (Admin)</td>
                                <td>Added Mark</td>
                                <td>Added mark 89 for ST2025001 in Introduction to Programming</td>
                            </tr>
                            <tr>
                                <td>Apr 14, 2025 - 03:20 PM</td>
                                <td>Sarah Johnson (Teacher)</td>
                                <td>Updated Mark</td>
                                <td>Updated mark for ST2025003 from 88 to 92</td>
                            </tr>
                            <tr>
                                <td>Apr 14, 2025 - 11:10 AM</td>
                                <td>John Doe (Admin)</td>
                                <td>Bulk Upload</td>
                                <td>Uploaded 25 marks for Data Structures & Algorithms</td>
                            </tr>
                        </tbody>
                    </table>
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

// Tab functionality
document.querySelectorAll('.tabs-container .tab').forEach(tab => {
    tab.addEventListener('click', function() {
        const tabId = this.getAttribute('data-tab');
        
        // Remove active class from all tabs and content
        document.querySelectorAll('.tabs-container .tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        // Add active class to clicked tab and corresponding content
        this.classList.add('active');
        document.getElementById(tabId + '-content')?.classList.add('active');
    });
});

// Toggle marks form visibility
document.getElementById('showAddMarksForm')?.addEventListener('click', function() {
    const form = document.getElementById('addMarksForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
});

// Toggle exam period form visibility
document.getElementById('showAddExamPeriodForm')?.addEventListener('click', function() {
    const form = document.getElementById('addExamPeriodForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
});

// Auto-calculate grade based on mark
document.getElementById('markValue')?.addEventListener('input', function() {
    const mark = parseInt(this.value);
    let grade = '';
    
    if (mark >= 90) grade = 'A+';
    else if (mark >= 80) grade = 'A';
    else if (mark >= 70) grade = 'B';
    else if (mark >= 60) grade = 'C';
    else if (mark >= 50) grade = 'D';
    else grade = 'F';
    
    document.getElementById('gradeValue').value = grade;
});

// Course Students Detail View functionality
document.querySelectorAll('.action-icon.view').forEach(button => {
    button.addEventListener('click', function() {
        const courseName = this.getAttribute('data-course');
        document.getElementById('selectedCourseName').textContent = courseName;
        
        // Show the detail view
        document.getElementById('courseStudentsDetail').style.display = 'block';
        
        // Populate the table with student data for this course
        const tableBody = document.getElementById('courseStudentsTableBody');
        tableBody.innerHTML = ''; // Clear existing data
        
        // This is where you would normally fetch data from the server
        // For this example, we'll use sample data based on the selected course
        
        // Sample data mapping
        const studentData = {
            'Introduction to Programming': [
                { id: 'ST2025001', name: 'Jane Smith', mark: 89, grade: 'A', period: 'Semester 1 - 2025' },
                { id: 'ST2025002', name: 'Mark Johnson', mark: 75, grade: 'B', period: 'Semester 1 - 2025' },
                { id: 'ST2025006', name: 'Alex Turner', mark: 82, grade: 'A', period: 'Semester 1 - 2025' },
                { id: 'ST2025010', name: 'Priya Sharma', mark: 95, grade: 'A+', period: 'Semester 1 - 2025' }
            ],
            'Data Structures & Algorithms': [
                { id: 'ST2025003', name: 'Sarah Williams', mark: 92, grade: 'A', period: 'Semester 1 - 2025' },
                { id: 'ST2025008', name: 'John Parker', mark: 78, grade: 'B', period: 'Semester 1 - 2025' },
                { id: 'ST2025012', name: 'Emma Richards', mark: 85, grade: 'A', period: 'Semester 1 - 2025' }
            ],
            'Database Management': [
                { id: 'ST2025004', name: 'Michael Brown', mark: 68, grade: 'C', period: 'Mid-Term Exams - 2025' },
                { id: 'ST2025007', name: 'Lisa Chen', mark: 77, grade: 'B', period: 'Mid-Term Exams - 2025' },
                { id: 'ST2025011', name: 'David Wilson', mark: 73, grade: 'B', period: 'Mid-Term Exams - 2025' }
            ],
            'Web Development': [
                { id: 'ST2025005', name: 'Emily Davis', mark: 95, grade: 'A+', period: 'First Quarter Assessment - 2025' },
                { id: 'ST2025009', name: 'Thomas Moore', mark: 88, grade: 'A', period: 'First Quarter Assessment - 2025' },
                { id: 'ST2025013', name: 'Sophie Grant', mark: 91, grade: 'A', period: 'First Quarter Assessment - 2025' }
            ],
            'Computer Networks': [
                { id: 'ST2025014', name: 'Ryan Martinez', mark: 79, grade: 'B', period: 'Semester 1 - 2025' },
                { id: 'ST2025015', name: 'Olivia Johnson', mark: 84, grade: 'A', period: 'Semester 1 - 2025' },
                { id: 'ST2025016', name: 'James Lee', mark: 76, grade: 'B', period: 'Semester 1 - 2025' }
            ]
        };
        
        // Get the students for the selected course
        const students = studentData[courseName] || [];
        
        // Update the course details
        document.getElementById('detailCourseUnit').textContent = courseName === 'Introduction to Programming' ? 'CU2025001' : 
                                               courseName === 'Data Structures & Algorithms' ? 'CU2025002' :
                                               courseName === 'Database Management' ? 'CU2025003' :
                                               courseName === 'Web Development' ? 'CU2025004' : 'CU2025005';
                                               
        document.getElementById('detailTeacher').textContent = courseName === 'Introduction to Programming' ? 'Dr. Robert Chen' : 
                                          courseName === 'Data Structures & Algorithms' ? 'Prof. Lisa Watkins' :
                                          courseName === 'Database Management' ? 'Dr. Michael Patel' :
                                          courseName === 'Web Development' ? 'Prof. Sarah Johnson' : 'Dr. James Wilson';
                                          
        document.getElementById('detailStudentCount').textContent = students.length;
        
        // Calculate average mark
        const totalMarks = students.reduce((sum, student) => sum + student.mark, 0);
        const averageMark = students.length > 0 ? (totalMarks / students.length).toFixed(1) : '0.0';
        document.getElementById('detailAverageMark').textContent = averageMark;
        
        // Add students to table
        students.forEach(student => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${student.id}</td>
                <td>${student.name}</td>
                <td>${student.mark}</td>
                <td>${student.grade}</td>
                <td>${student.period}</td>
                <td>
                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    });
});

// View toggle functionality
document.querySelectorAll('.view-toggle .toggle-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all buttons
        document.querySelectorAll('.view-toggle .toggle-btn').forEach(btn => 
            btn.classList.remove('active'));
        
        // Add active class to clicked button
        this.classList.add('active');
        
        // Get the view type
        const viewType = this.getAttribute('data-view');
        
        // Hide all view cards
        document.getElementById('courseStudentsDetail').style.display = 'none';
        document.getElementById('studentDetailView').style.display = 'none';
        
        // Show the appropriate table based on the selected view
        if (viewType === 'course') {
            document.querySelector('.card .data-table').closest('.card').style.display = 'block';
            document.getElementById('studentViewCard').style.display = 'none';
        } else {
            document.querySelector('.card .data-table').closest('.card').style.display = 'none';
            document.getElementById('studentViewCard').style.display = 'block';
        }
    });
});

// Student detail view functionality
document.querySelectorAll('#studentViewCard .action-icon.view').forEach(button => {
    button.addEventListener('click', function() {
        const studentId = this.getAttribute('data-student');
        const studentName = this.getAttribute('data-name');
        document.getElementById('selectedStudentName').textContent = studentName;
        
        // Show the detail view
        document.getElementById('studentDetailView').style.display = 'block';
        
        // Populate the table with course data for this student
        const tableBody = document.getElementById('studentCoursesTableBody');
        tableBody.innerHTML = ''; // Clear existing data
        
        // Sample data mapping for students and their courses
        const courseData = {
            'ST2025001': [
                { unit: 'CU2025001', name: 'Introduction to Programming', teacher: 'Dr. Robert Chen', mark: 89, grade: 'A', period: 'Semester 1 - 2025' },
                { unit: 'CU2025002', name: 'Data Structures & Algorithms', teacher: 'Prof. Lisa Watkins', mark: 85, grade: 'A', period: 'Semester 1 - 2025' },
                { unit: 'CU2025004', name: 'Web Development', teacher: 'Prof. Sarah Johnson', mark: 92, grade: 'A', period: 'First Quarter Assessment - 2025' }
            ],
            'ST2025002': [
                { unit: 'CU2025001', name: 'Introduction to Programming', teacher: 'Dr. Robert Chen', mark: 75, grade: 'B', period: 'Semester 1 - 2025' },
                { unit: 'CU2025003', name: 'Database Management', teacher: 'Dr. Michael Patel', mark: 70, grade: 'B', period: 'Mid-Term Exams - 2025' }
            ],
            'ST2025003': [
                { unit: 'CU2025001', name: 'Introduction to Programming', teacher: 'Dr. Robert Chen', mark: 88, grade: 'A', period: 'Semester 1 - 2025' },
                { unit: 'CU2025002', name: 'Data Structures & Algorithms', teacher: 'Prof. Lisa Watkins', mark: 92, grade: 'A', period: 'Semester 1 - 2025' },
                { unit: 'CU2025003', name: 'Database Management', teacher: 'Dr. Michael Patel', mark: 85, grade: 'A', period: 'Mid-Term Exams - 2025' },
                { unit: 'CU2025005', name: 'Computer Networks', teacher: 'Dr. James Wilson', mark: 90, grade: 'A', period: 'Semester 1 - 2025' }
            ],
            'ST2025004': [
                { unit: 'CU2025001', name: 'Introduction to Programming', teacher: 'Dr. Robert Chen', mark: 65, grade: 'C', period: 'Semester 1 - 2025' },
                { unit: 'CU2025003', name: 'Database Management', teacher: 'Dr. Michael Patel', mark: 68, grade: 'C', period: 'Mid-Term Exams - 2025' },
                { unit: 'CU2025004', name: 'Web Development', teacher: 'Prof. Sarah Johnson', mark: 70, grade: 'B', period: 'First Quarter Assessment - 2025' }
            ],
            'ST2025005': [
                { unit: 'CU2025004', name: 'Web Development', teacher: 'Prof. Sarah Johnson', mark: 95, grade: 'A+', period: 'First Quarter Assessment - 2025' },
                { unit: 'CU2025005', name: 'Computer Networks', teacher: 'Dr. James Wilson', mark: 92, grade: 'A', period: 'Semester 1 - 2025' }
            ]
        };
        
        // Get the courses for the selected student
        const courses = courseData[studentId] || [];
        
        // Update the student details
        document.getElementById('detailStudentId').textContent = studentId;
        document.getElementById('detailCourseCount').textContent = courses.length;
        
        // Calculate GPA
        let totalGradePoints = 0;
        courses.forEach(course => {
            const mark = course.mark;
            let gradePoint = 0;
            
            if (mark >= 90) gradePoint = 4.0;
            else if (mark >= 80) gradePoint = 3.7;
            else if (mark >= 70) gradePoint = 3.0;
            else if (mark >= 60) gradePoint = 2.0;
            else if (mark >= 50) gradePoint = 1.0;
            else gradePoint = 0.0;
            
            totalGradePoints += gradePoint;
        });
        
        const gpa = courses.length > 0 ? (totalGradePoints / courses.length).toFixed(1) : '0.0';
        document.getElementById('detailGPA').textContent = gpa;
        
        // Determine overall grade based on GPA
        let overallGrade = '';
        const gpaNum = parseFloat(gpa);
        if (gpaNum >= 3.7) overallGrade = 'A';
        else if (gpaNum >= 3.0) overallGrade = 'B';
        else if (gpaNum >= 2.0) overallGrade = 'C';
        else if (gpaNum >= 1.0) overallGrade = 'D';
        else overallGrade = 'F';
        
        document.getElementById('detailOverallGrade').textContent = overallGrade;
        
        // Add courses to table
        courses.forEach(course => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${course.unit}</td>
                <td>${course.name}</td>
                <td>${course.teacher}</td>
                <td>${course.mark}</td>
                <td>${course.grade}</td>
                <td>${course.period}</td>
            `;
            tableBody.appendChild(row);
        });
    });
});

// Close student detail view when X button is clicked
document.getElementById('closeStudentView')?.addEventListener('click', function() {
    document.getElementById('studentDetailView').style.display = 'none';
});

// Close detail view when X button is clicked
document.getElementById('closeDetailView')?.addEventListener('click', function() {
    document.getElementById('courseStudentsDetail').style.display = 'none';
});

// Close add marks form when reset button is clicked
document.querySelectorAll('.reset-btn').forEach(button => {
    button.addEventListener('click', function() {
        const form = this.closest('.card');
        if (form) form.style.display = 'none';
    });
});


    </script>
   
</body>
</html>