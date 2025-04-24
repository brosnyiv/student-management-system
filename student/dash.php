<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Student Portal</title>
    <link rel="stylesheet" href="dash.css">
    <style>
        /* Adding modal styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            max-width: 90%;
            position: relative;
        }
        
        .modal-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
        }
        
        .modal-header {
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
        }
        
        .modal-header h3 {
            margin: 0;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .secondary-btn {
            padding: 8px 16px;
            background-color: #f1f1f1;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .primary-btn {
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-logo">
                <img src="logo.png" alt="Monaco Institute Logo" class="logo-img">
            </div>
            <div class="sidebar-header">
                <h2>Monaco Institute</h2>
                <p>Empowering Professional Skills</p>
            </div>
           <!-- Updated Navigation Menu with onclick for logout modal -->
<ul class="nav-menu">
    <li class="nav-item" onclick="window.location.href='dash.php'">Dashboard</li>
    <li class="nav-item" onclick="window.location.href='course.php'">My Courses</li>
    <li class="nav-item" onclick="window.location.href='asignments.php'">Assignments</li>
    <li class="nav-item" onclick="window.location.href='results.php'">Results</li>
    <li class="nav-item" onclick="window.location.href='attendence.php'">Attendance</li>
    <li class="nav-item" onclick="window.location.href='payments.php'">Payments</li>
    <li class="nav-item" onclick="window.location.href='drop semester.php'">Drop Semester</li>
    <li class="nav-item" onclick="window.location.href='notices.php'">Notices</li>
    <li class="nav-item" onclick="window.location.href='messages.php'">Messages <span class="badge">3</span></li>
    <li class="logout-item" onclick="logout()">Log Out</li>
</ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1>Monaco Institute </h1>
                    <h3> Welcome, John!</h2>
                    <span class="header-date" id="current-date">Thursday, April 11, 2025</span>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                </div>
                <div class="user-actions">
                    <i class="settings-icon">⚙️</i>
                </div>
            </div>
            
            <!-- Dashboard Section -->
            <div id="dashboard" class="content-section">
                <div class="student-profile">
                    <div class="student-avatar">JS</div>
                    <div class="student-info">
                        <h2>John Smith</h2>
                        <p>Computer Science, Year 3</p>
                        <p>Student ID: STU2025001</p>
                        <div class="gpa-container">
                            <div class="gpa-circle">3.7</div>
                            <div>
                                <strong>Current GPA</strong><br>
                                <small>90 Credits Completed</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <h3>Current Courses</h3>
                            <div class="card-icon blue">C</div>
                        </div>
                        <div class="card-number">4</div>
                        <div class="card-label">Spring Semester 2025</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Overall Attendance</h3>
                            <div class="card-icon green">A</div>
                        </div>
                        <div class="card-number">92%</div>
                        <div class="card-label">This Semester</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Upcoming Exams</h3>
                            <div class="card-icon red">E</div>
                        </div>
                        <div class="card-number">2</div>
                        <div class="card-label">Next 14 Days</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Tuition Balance</h3>
                            <div class="card-icon orange">$</div>
                        </div>
                        <div class="card-number">$1,250</div>
                        <div class="card-label">Due April 30, 2025</div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-header">
                        <h3>Attendance Report</h3>
                        <div class="action-buttons">
                            <button class="primary-btn" onclick="showSection('attendance')">View Detailed Report</button>
                        </div>
                    </div>
                    
                    <div>
                        <h4>CS301 - Data Structures and Algorithms</h4>
                        <div class="attendance-bar">
                            <div class="attendance-progress progress-good" style="width: 95%;">95%</div>
                        </div>
                        
                        <h4>CS315 - Database Systems</h4>
                        <div class="attendance-bar">
                            <div class="attendance-progress progress-good" style="width: 90%;">90%</div>
                        </div>
                        
                        <h4>MATH302 - Discrete Mathematics</h4>
                        <div class="attendance-bar">
                            <div class="attendance-progress progress-warning" style="width: 85%;">85%</div>
                        </div>
                        
                        <h4>ENG210 - Technical Writing</h4>
                        <div class="attendance-bar">
                            <div class="attendance-progress progress-danger" style="width: 75%;">75%</div>
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-header">
                        <h3>Current Courses</h3>
                        <div class="action-buttons">
                            <button class="primary-btn" onclick="showSection('courses')">View All Courses</button>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Instructor</th>
                                <th>Schedule</th>
                                <th>Current Grade</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CS301</td>
                                <td>Data Structures and Algorithms</td>
                                <td>Dr. James Wilson</td>
                                <td>Mon/Wed 10:00-11:30</td>
                                <td><span class="grade-a">A-</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewCourse('CS301')">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>CS315</td>
                                <td>Database Systems</td>
                                <td>Prof. Maria Rodriguez</td>
                                <td>Tue/Thu 13:00-14:30</td>
                                <td><span class="grade-b">B+</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewCourse('CS315')">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>MATH302</td>
                                <td>Discrete Mathematics</td>
                                <td>Dr. Robert Chen</td>
                                <td>Mon/Wed 13:00-14:30</td>
                                <td><span class="grade-a">A</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewCourse('MATH302')">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>ENG210</td>
                                <td>Technical Writing</td>
                                <td>Prof. Sarah Johnson</td>
                                <td>Fri 09:00-12:00</td>
                                <td><span class="grade-b">B</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewCourse('ENG210')">View</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="section">
                    <div class="section-header">
                        <h3>Assignments Due</h3>
                        <div class="action-buttons">
                            <button class="primary-btn" onclick="showSection('courses')">All Assignments</button>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Assignment</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CS301</td>
                                <td>Algorithm Analysis Project</td>
                                <td>Apr 15, 2025</td>
                                <td><span class="status pending">In Progress</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn">View</button>
                                </td>
                            </tr>

                            <tr>
                                <td>CS305</td>
                                <td>System desgin</td>
                                <td>Apr 15, 2025</td>
                                <td><span class="status pending">In Progress</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn">View</button>
                                </td>
                            </tr>

                            <tr>
                                <td>CS305</td>
                                <td>System desgin</td>
                                <td>Apr 15, 2025</td>
                                <td><span class="status pending">In Progress</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn">View</button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal-overlay" id="logout-modal">
        <div class="modal">
            <span class="modal-close" onclick="closeLogoutModal()">&times;</span>
            <div class="modal-header">
                <h3>Confirm Logout</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to log out of your student portal?</p>
                <div style="display: flex; justify-content: flex-end; margin-top: 20px; gap: 10px;">
                    <button class="secondary-btn" onclick="closeLogoutModal()">Cancel</button>
                    <button class="primary-btn" style="background-color: #e74c3c;" onclick="confirmLogout()">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Display current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
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
        
        // Function to view course details
        function viewCourse(courseId) {
            alert('Viewing details for course: ' + courseId);
            // In a real application, this would load course details
        }
        
        // Function to show logout confirmation modal
        function logout() {
            document.getElementById('logout-modal').style.display = 'flex';
        }
        
        // Function to close logout modal
        function closeLogoutModal() {
            document.getElementById('logout-modal').style.display = 'none';
        }
        
        // Function to handle confirmed logout
        function confirmLogout() {
            // In a real application, this would handle session termination
            window.location.href = 'login.php'; // Redirect to login page
        }
    </script>
</body>
</html>