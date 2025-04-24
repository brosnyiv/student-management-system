<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Attendance</title>
    <link rel="stylesheet" href="dash.css">
    <style>
        
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
            <ul class="nav-menu">
                <li class="nav-item" onclick="window.location.href='dash.html'">Dashboard</li>
                <li class="nav-item" onclick="window.location.href='course.html'">My Courses</li>
                <li class="nav-item" onclick="window.location.href='asignments.html'">Assignments</li>
                <li class="nav-item" onclick="window.location.href='results.html'">Results</li>
                <li class="nav-item" onclick="window.location.href='attendence.html'">Attendance</li>
                <li class="nav-item" onclick="window.location.href='payments.html'">Payments</li>
                <li class="nav-item" onclick="window.location.href='drop semester.html'">Drop Semester</li>
                <li class="nav-item" onclick="window.location.href='notices.html'">Notices</li>
                <li class="nav-item" onclick="window.location.href='messages.html'">Messages <span class="badge">3</span></li>
                <li class="logout-item" onclick="window.location.href='login.html'">Log Out</li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1>Monaco Institute </h1>
                    <h3> Welcome, John!</h3>
                    <span class="header-date" id="current-date">Friday, April 12, 2025</span>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                </div>
                <div class="user-actions">
                    <i class="settings-icon">⚙️</i>
                </div>
            </div>
            
            <!-- Attendance Section -->
            <div id="attendance" class="content-section">
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
                
                <!-- Overall Attendance Summary -->
                <div class="section">
                    <div class="section-header">
                        <h3>Overall Attendance Summary</h3>
                        <div class="action-buttons">
                            <button class="generate-report-btn">📊 Generate Report</button>
                        </div>
                    </div>
                    
                    <div class="attendance-summary">
                        <div class="card">
                            <div class="card-header">
                                <h3>Overall Attendance</h3>
                                <div class="card-icon green">A</div>
                            </div>
                            <div class="card-number">92%</div>
                            <div class="card-label">Spring Semester 2025</div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Classes Attended</h3>
                                <div class="card-icon blue">C</div>
                            </div>
                            <div class="card-number">68</div>
                            <div class="card-label">Out of 74 Total Classes</div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Present Days</h3>
                                <div class="card-icon teal">P</div>
                            </div>
                            <div class="card-number">42</div>
                            <div class="card-label">Out of 45 Academic Days</div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Absences</h3>
                                <div class="card-icon red">A</div>
                            </div>
                            <div class="card-number">3</div>
                            <div class="card-label">Including 1 Excused Absence</div>
                        </div>
                    </div>
                </div>
                
                <!-- Attendance Report By Course -->
                <div class="section">
                    <div class="section-header">
                        <h3>Attendance Report By Course</h3>
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
                
                <!-- Detailed Attendance Record -->
                <div class="section">
                    <div class="attendance-detail-header">
                        <h3>Detailed Attendance Record</h3>
                        <div class="action-buttons">
                            <button class="generate-report-btn">📥 Export Data</button>
                        </div>
                    </div>
                    
                    <div class="attendance-filters">
                        <button class="filter-btn active">All Courses</button>
                        <button class="filter-btn">CS301</button>
                        <button class="filter-btn">CS315</button>
                        <button class="filter-btn">MATH302</button>
                        <button class="filter-btn">ENG210</button>
                    </div>
                    
                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #2ecc71;"></div>
                            <span>Present (P)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #e74c3c;"></div>
                            <span>Absent (A)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #f39c12;"></div>
                            <span>Late (L)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #3498db;"></div>
                            <span>Excused (E)</span>
                        </div>
                    </div>
                    
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>CS301</th>
                                <th>CS315</th>
                                <th>MATH302</th>
                                <th>ENG210</th>
                                <th>Daily Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="week-heading">
                                <td colspan="7">Week 1 (January 13 - 17, 2025)</td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 13, 2025</td>
                                <td>Monday</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 14, 2025</td>
                                <td>Tuesday</td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 15, 2025</td>
                                <td>Wednesday</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 16, 2025</td>
                                <td>Thursday</td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 17, 2025</td>
                                <td>Friday</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            
                            <tr class="week-heading">
                                <td colspan="7">Week 2 (January 20 - 24, 2025)</td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 20, 2025</td>
                                <td>Monday</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 21, 2025</td>
                                <td>Tuesday</td>
                                <td>-</td>
                                <td><div class="attendance-status status-late">L</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><span class="status pending">Late</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 22, 2025</td>
                                <td>Wednesday</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 23, 2025</td>
                                <td>Thursday</td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 24, 2025</td>
                                <td>Friday</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td><div class="attendance-status status-excused">E</div></td>
                                <td><span class="status active">Excused</span></td>
                            </tr>
                            
                            <tr class="week-heading">
                                <td colspan="7">Week 3 (January 27 - 31, 2025)</td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 27, 2025</td>
                                <td>Monday</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><div class="attendance-status status-absent">A</div></td>
                                <td>-</td>
                                <td><span class="status inactive">Partial</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 28, 2025</td>
                                <td>Tuesday</td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 29, 2025</td>
                                <td>Wednesday</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 30, 2025</td>
                                <td>Thursday</td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-absent">A</div></td>
                                <td><span class="status inactive">Partial</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Jan 31, 2025</td>
                                <td>Friday</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            
                            <tr class="week-heading">
                                <td colspan="7">Week 4 (February 3 - 7, 2025)</td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Feb 3, 2025</td>
                                <td>Monday</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Feb 4, 2025</td>
                                <td>Tuesday</td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Feb 5, 2025</td>
                                <td>Wednesday</td>
                                <td><div class="attendance-status status-late">L</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><span class="status pending">Late</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Feb 6, 2025</td>
                                <td>Thursday</td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            <tr>
                                <td class="attendance-date">Feb 7, 2025</td>
                                <td>Friday</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td><div class="attendance-status status-present">P</div></td>
                                <td><span class="status active">Present</span></td>
                            </tr>
                            
                            <!-- Additional weeks would continue here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Attendance Insights -->
                <div class="section">
                    <div class="section-header">
                        <h3>Attendance Insights</h3>
                    </div>
                    
                    <div class="dashboard-cards">
                        <div class="card">
                            <div class="card-header">
                                <h3>Most Missed Class</h3>
                                <div class="card-icon red">M</div>
                            </div>
                            <div class="card-number">ENG210</div>
                            <div class="card-label">Technical Writing (75% attendance)</div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Perfect Attendance</h3>
                                <div class="card-icon green">P</div>
                            </div>
                            <div class="card-number">Feb</div>
                            <div class="card-label">Best Attendance Month</div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Absence Pattern</h3>
                                <div class="card-icon orange">A</div>
                            </div>
                            <div class="card-number">Thu</div>
                            <div class="card-label">Most Missed Day</div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Remaining Absences</h3>
                                <div class="card-icon purple">R</div>
                            </div>
                            <div class="card-number">2</div>
                            <div class="card-label">Before Grade Impact</div>
                        </div>
                    </div>
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
        
        // Filter buttons functionality
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                // In a real application, this would filter the attendance data
                alert('Filtering for: ' + this.textContent);
            });
        });
        
        // Generate report functionality
        const reportBtns = document.querySelectorAll('.generate-report-btn');
        reportBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Generating attendance report...');
                // In a real application, this would generate a downloadable report
            });
        });
        
        // Function to handle logout
        function logout() {
            alert('Logging out...');
            // In a real application, this would handle the logout process
        }
    </script>
</body>
</html>