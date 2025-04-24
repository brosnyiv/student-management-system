<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - My Courses</title>
    <link rel="stylesheet" href="dash.css">
    <style>
        /* Instructor styles */
        .instructor-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .instructor-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 300px;
            overflow: hidden;
        }
        
        .instructor-header {
            background: #2c3e50;
            color: white;
            padding: 15px;
        }
        
        .instructor-body {
            padding: 15px;
        }
        
        .instructor-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 15px;
        }
        
        .contact-info {
            display: flex;
            align-items: center;
            margin: 8px 0;
        }
        
        .contact-info i {
            margin-right: 10px;
            width: 20px;
        }
        
        /* Resources styles */
        .resource-item {
            display: flex;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            background: #f5f7fa;
            border-radius: 5px;
        }
        
        .resource-icon {
            font-size: 24px;
            margin-right: 15px;
            color: #3498db;
        }
        
        /* Activities styles */
        .timeline {
            position: relative;
            margin: 20px 0;
            padding-left: 30px;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 2px;
            background: #3498db;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: -36px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #3498db;
        }
        
        .timeline-date {
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .deadline {
            color: #e74c3c;
        }

        /* Tab display handling */
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Course cards and details */
        .course-detail {
            display: none;
        }
        
        .course-tab {
            cursor: pointer;
            padding: 10px 15px;
            background: #f5f7fa;
            border-radius: 5px 5px 0 0;
            display: inline-block;
            margin-right: 5px;
        }
        
        .course-tab.active {
            background: #3498db;
            color: white;
        }
        
        .course-tabs {
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 1px;
        }
    </style>

    <script>
        // Function to show course details
        function showCourseDetail(courseId) {
            // Hide all course cards
            document.querySelector('.course-cards').style.display = 'none';
            
            // Show selected course details
            document.getElementById(courseId).style.display = 'block';
        }
        
        // Function to hide details and return to course list
        function hideDetails() {
            // Show course cards
            document.querySelector('.course-cards').style.display = 'flex';
            
            // Hide all course details
            const courseDetails = document.querySelectorAll('.course-detail');
            courseDetails.forEach(detail => {
                detail.style.display = 'none';
            });
        }
        
        // Function to switch tabs
        function switchTab(courseId, tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll(`#${courseId} .tab-content`);
            tabContents.forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(`${courseId}-${tabName}`).classList.add('active');
            
            // Update tab styling
            const tabs = document.querySelectorAll(`#${courseId} .course-tab`);
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
        }
        
        // Set current date
        document.addEventListener('DOMContentLoaded', function() {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const today = new Date();
            document.getElementById('current-date').textContent = today.toLocaleDateString('en-US', options);
        });
    </script>
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
                <li class="nav-item" onclick="window.location.href='dash.php'">Dashboard</li>
                <li class="nav-item" onclick="window.location.href='course.php'">My Courses</li>
                <li class="nav-item" onclick="window.location.href='asignments.php'">Assignments</li>
                <li class="nav-item" onclick="window.location.href='results.php'">Results</li>
                <li class="nav-item" onclick="window.location.href='attendence.php'">Attendance</li>
                <li class="nav-item" onclick="window.location.href='payments.php'">Payments</li>
                <li class="nav-item" onclick="window.location.href='drop semester.php'">Drop Semester</li>
                <li class="nav-item" onclick="window.location.href='notices.php'">Notices</li>
                <li class="nav-item" onclick="window.location.href='messages.php'">Messages <span class="badge">3</span></li>
                <li class="logout-item" onclick="window.location.href='login.php'">Log Out</li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1>Monaco Institute </h1>
                    <h3> Welcome, John!</h3>
                    <span class="header-date" id="current-date">Thursday, April 11, 2025</span>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search courses...">
                </div>
                <div class="user-actions">
                    <i class="settings-icon">⚙️</i>
                </div>
            </div>
            
            <!-- Courses Overview Section -->
            <div class="section">
                <div class="section-header">
                    <h2>Current Semester Courses (Spring 2025)</h2>
                </div>
                
                <div class="course-cards">
                    <!-- CS301 Course Card -->
                    <div class="course-card" onclick="showCourseDetail('CS301')">
                        <div class="course-header course-cs">
                            <h3>Data Structures and Algorithms</h3>
                            <div class="course-code">CS301 • 4 Credits</div>
                        </div>
                        <div class="course-body">
                            <div class="course-info">
                                <i>👨‍🏫</i> Dr. James Wilson
                            </div>
                            <div class="course-info">
                                <i>🕒</i> Mon/Wed 10:00-11:30
                            </div>
                            <div class="course-info">
                                <i>📍</i> Room CS-201, Computer Science Building
                            </div>
                            <div class="course-progress">
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: 65%;"></div>
                                </div>
                                <small>Course Progress: 65%</small>
                            </div>
                        </div>
                        <div class="course-footer">
                            <button class="view-button">View Details</button>
                        </div>
                    </div>
                    
                    <!-- CS315 Course Card -->
                    <div class="course-card" onclick="showCourseDetail('CS315')">
                        <div class="course-header course-cs">
                            <h3>Database Systems</h3>
                            <div class="course-code">CS315 • 3 Credits</div>
                        </div>
                        <div class="course-body">
                            <div class="course-info">
                                <i>👩‍🏫</i> Prof. Maria Rodriguez
                            </div>
                            <div class="course-info">
                                <i>🕒</i> Tue/Thu 13:00-14:30
                            </div>
                            <div class="course-info">
                                <i>📍</i> Room CS-105, Computer Science Building
                            </div>
                            <div class="course-progress">
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: 70%;"></div>
                                </div>
                                <small>Course Progress: 70%</small>
                            </div>
                        </div>
                        <div class="course-footer">
                            <button class="view-button">View Details</button>
                        </div>
                    </div>
                    
                    <!-- MATH302 Course Card -->
                    <div class="course-card" onclick="showCourseDetail('MATH302')">
                        <div class="course-header course-math">
                            <h3>Discrete Mathematics</h3>
                            <div class="course-code">MATH302 • 3 Credits</div>
                        </div>
                        <div class="course-body">
                            <div class="course-info">
                                <i>👨‍🏫</i> Dr. Robert Chen
                            </div>
                            <div class="course-info">
                                <i>🕒</i> Mon/Wed 13:00-14:30
                            </div>
                            <div class="course-info">
                                <i>📍</i> Room M-301, Mathematics Building
                            </div>
                            <div class="course-progress">
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: 75%;"></div>
                                </div>
                                <small>Course Progress: 75%</small>
                            </div>
                        </div>
                        <div class="course-footer">
                            <button class="view-button">View Details</button>
                        </div>
                    </div>
                    
                    <!-- ENG210 Course Card -->
                    <div class="course-card" onclick="showCourseDetail('ENG210')">
                        <div class="course-header course-eng">
                            <h3>Technical Writing</h3>
                            <div class="course-code">ENG210 • 2 Credits</div>
                        </div>
                        <div class="course-body">
                            <div class="course-info">
                                <i>👩‍🏫</i> Prof. Sarah Johnson
                            </div>
                            <div class="course-info">
                                <i>🕒</i> Fri 09:00-12:00
                            </div>
                            <div class="course-info">
                                <i>📍</i> Room H-105, Humanities Building
                            </div>
                            <div class="course-progress">
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: 60%;"></div>
                                </div>
                                <small>Course Progress: 60%</small>
                            </div>
                        </div>
                        <div class="course-footer">
                            <button class="view-button">View Details</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Course Details Sections (initially hidden) -->
            <div id="CS301" class="course-detail">
                <div class="section">
                    <div class="section-header">
                        <h2>CS301. Data Structures and Algorithms</h2>
                        <div class="action-buttons">
                            <button class="view-button" onclick="hideDetails()">Back to Courses</button>
                        </div>
                    </div>
                    
                    <div class="course-tabs">
                        <div class="course-tab active" onclick="switchTab('CS301', 'overview')">Overview</div>
                        <div class="course-tab" onclick="switchTab('CS301', 'schedule')">Schedule</div>
                        <div class="course-tab" onclick="switchTab('CS301', 'instructors')">Instructors</div>
                        <div class="course-tab" onclick="switchTab('CS301', 'activities')">Activities</div>
                        <div class="course-tab" onclick="switchTab('CS301', 'resources')">Resources</div>
                    </div>
                    
                    <!-- Overview Tab -->
                    <div id="CS301-overview" class="tab-content active">
                        <h3>Course Description</h3>
                        <p>This course covers fundamental data structures and algorithms used in software development. Topics include analysis of algorithms, searching, sorting, recursion, trees, graphs, and advanced algorithmic strategies.</p>
                        
                        <h3>Course Details</h3>
                        <div class="course-info">
                            <strong>Credits:</strong> 4 (3 Theory + 1 Lab)
                        </div>
                        <div class="course-info">
                            <strong>Prerequisites:</strong> CS201 - Programming Fundamentals, CS205 - Discrete Structures
                        </div>
                        <div class="course-info">
                            <strong>Required For:</strong> CS401 - Algorithm Design, CS405 - Artificial Intelligence
                        </div>
                        
                        <h3>Learning Outcomes</h3>
                        <ul>
                            <li>Analyze the time and space complexity of algorithms</li>
                            <li>Implement and apply advanced data structures</li>
                            <li>Design efficient algorithms for complex problems</li>
                            <li>Evaluate tradeoffs between different algorithmic approaches</li>
                            <li>Apply algorithm design techniques to real-world problems</li>
                        </ul>
                        
                        <h3>Grading Policy</h3>
                        <div class="course-info">
                            <strong>Assignments:</strong> 25%
                        </div>
                        <div class="course-info">
                            <strong>Labs:</strong> 15%
                        </div>
                        <div class="course-info">
                            <strong>Midterm Exam:</strong> 25%
                        </div>
                        <div class="course-info">
                            <strong>Final Exam:</strong> 30%
                        </div>
                        <div class="course-info">
                            <strong>Participation:</strong> 5%
                        </div>
                    </div>
                    
                    <!-- Schedule Tab -->
                    <div id="CS301-schedule" class="tab-content">
                        <h3>Weekly Schedule</h3>
                        <div class="schedule-grid">
                            <div class="schedule-header">Monday</div>
                            <div class="schedule-header">Tuesday</div>
                            <div class="schedule-header">Wednesday</div>
                            <div class="schedule-header">Thursday</div>
                            <div class="schedule-header">Friday</div>
                            
                            <div class="schedule-day">
                                <div class="schedule-event">
                                    CS301 Lecture
                                    <div class="schedule-time">10:00 - 11:30</div>
                                    <div class="schedule-location">Room CS-201</div>
                                </div>
                            </div>
                            
                            <div class="schedule-day">
                                <!-- Empty -->
                            </div>
                            
                            <div class="schedule-day">
                                <div class="schedule-event">
                                    CS301 Lecture
                                    <div class="schedule-time">10:00 - 11:30</div>
                                    <div class="schedule-location">Room CS-201</div>
                                </div>
                            </div>
                            
                            <div class="schedule-day">
                                <div class="schedule-event lab">
                                    CS301 Lab Session
                                    <div class="schedule-time">15:00 - 16:30</div>
                                    <div class="schedule-location">Lab CS-301</div>
                                </div>
                            </div>
                            
                            <div class="schedule-day">
                                <div class="schedule-event">
                                    CS301 Office Hours
                                    <div class="schedule-time">13:00 - 15:00</div>
                                    <div class="schedule-location">Room CS-420</div>
                                </div>
                            </div>
                        </div>
                        
                        <h3>Important Dates</h3>
                        <div class="activity-item exam">
                            <div class="activity-header">
                                <div class="activity-title">Midterm Exam</div>
                                <div class="activity-date">May 13, 2025</div>
                                <div class="activity-location">Main Auditorium, 10:00-12:00</div>
                            </div>
                        </div>
                        
                        <div class="activity-item exam">
                            <div class="activity-header">
                                <div class="activity-title">Final Exam</div>
                                <div class="activity-date">June 29, 2025</div>
                                <div class="activity-location">Main Auditorium, 09:00-12:00</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Instructors Tab Content -->
                    <div id="CS301-instructors" class="tab-content">
                        <h3>Course Instructors</h3>
                        <div class="instructor-container">
                            <!-- Main Professor -->
                            <div class="instructor-card">
                                <div class="instructor-header">
                                    <h4>Dr. James Wilson</h4>
                                    <div>Professor of Computer Science</div>
                                </div>
                                <div class="instructor-body">
                                    <div class="instructor-avatar">JW</div>
                                    <p><strong>Specialization:</strong> Algorithmic Complexity, Graph Theory</p>
                                    <p><strong>Contact Information:</strong></p>
                                    <div class="contact-info">
                                        <i>📧</i> jwilson@monacoinstitute.edu
                                    </div>
                                    <div class="contact-info">
                                        <i>📞</i> (555) 123-4567
                                    </div>
                                    <div class="contact-info">
                                        <i>🏢</i> Computer Science Building, Room 420
                                    </div>
                                    <p><strong>Office Hours:</strong> Fridays 13:00-15:00 or by appointment</p>
                                </div>
                            </div>
                            
                            <!-- Teaching Assistant 1 -->
                            <div class="instructor-card">
                                <div class="instructor-header">
                                    <h4>Alex Thompson</h4>
                                    <div>Teaching Assistant</div>
                                </div>
                                <div class="instructor-body">
                                    <div class="instructor-avatar">AT</div>
                                    <p><strong>Specialization:</strong> Algorithmic Optimization</p>
                                    <p><strong>Contact Information:</strong></p>
                                    <div class="contact-info">
                                        <i>📧</i> athompson@monacoinstitute.edu
                                    </div>
                                    <div class="contact-info">
                                        <i>🏢</i> CS Lab Building, Room 105
                                    </div>
                                    <p><strong>Lab Sessions:</strong> Thursdays 15:00-16:30</p>
                                    <p><strong>Office Hours:</strong> Mondays 14:00-16:00</p>
                                </div>
                            </div>
                            
                            <!-- Teaching Assistant 2 -->
                            <div class="instructor-card">
                                <div class="instructor-header">
                                    <h4>Maya Patel</h4>
                                    <div>Teaching Assistant</div>
                                </div>
                                <div class="instructor-body">
                                    <div class="instructor-avatar">MP</div>
                                    <p><strong>Specialization:</strong> Data Structures Implementation</p>
                                    <p><strong>Contact Information:</strong></p>
                                    <div class="contact-info">
                                        <i>📧</i> mpatel@monacoinstitute.edu
                                    </div>
                                    <div class="contact-info">
                                        <i>🏢</i> CS Lab Building, Room 105
                                    </div>
                                    <p><strong>Lab Sessions:</strong> Tuesdays 16:00-17:30 (Overflow)</p>
                                    <p><strong>Office Hours:</strong> Wednesdays 12:00-14:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activities Tab Content -->
                    <div id="CS301-activities" class="tab-content">
                        <h3>Course Timeline and Activities</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-date">Week 1-2 (Apr 1-12, 2025)</div>
                                <div class="timeline-content">
                                    <strong>Topic:</strong> Algorithm Analysis and Complexity
                                    <p>Introduction to asymptotic notation, best/worst/average case analysis, and algorithm efficiency.</p>
                                    <div class="deadline">Assignment #1 Due: April 12, 2025</div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-date">Week 3-4 (Apr 15-26, 2025)</div>
                                <div class="timeline-content">
                                    <strong>Topic:</strong> Sorting Algorithms
                                    <p>Bubble, insertion, selection, merge, quick, and heap sort implementations and analysis.</p>
                                    <div class="deadline">Assignment #2 Due: April 26, 2025</div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-date">Week 5-6 (Apr 29-May 10, 2025)</div>
                                <div class="timeline-content">
                                    <strong>Topic:</strong> Trees and Graph Representations
                                    <p>Binary trees, AVL trees, B-trees, and graph representations (adjacency matrices, adjacency lists).</p>
                                    <div class="deadline">Assignment #3 Due: May 10, 2025</div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-date">May 13, 2025</div>
                                <div class="timeline-content">
                                    <strong>Midterm Examination</strong>
                                    <p>Covers weeks 1-6 material. Closed book, 2 hours duration.</p>
                                    <div class="deadline">Location: Main Auditorium, 10:00-12:00</div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-date">Week 7-8 (May 14-25, 2025)</div>
                                <div class="timeline-content">
                                    <strong>Topic:</strong> Graph Algorithms
                                    <p>DFS, BFS, Dijkstra's algorithm, Minimum Spanning Trees, Topological Sort.</p>
                                    <div class="deadline">Assignment #4 Due: May 25, 2025</div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-date">Week 9-10 (May 28-Jun 8, 2025)</div>
                                <div class="timeline-content">
                                    <strong>Topic:</strong> Advanced Algorithm Design
                                    <p>Dynamic programming, greedy algorithms, backtracking.</p>
                                    <div class="deadline">Assignment #5 Due: June 8, 2025</div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-date">Week 11-12 (Jun 11-22, 2025)</div>
                                <div class="timeline-content">
                                    <strong>Topic:</strong> Algorithm Optimization and Case Studies
                                    <p>Practical applications, specialized data structures, algorithm optimization techniques.</p>
                                    <div class="deadline">Final Project Due: June 22, 2025</div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-date">Jun 29, 2025</div>
                                <div class="timeline-content">
                                    <strong>Final Examination</strong>
                                    <p>Comprehensive examination covering all course material. Closed book, 3 hours duration.</p>
                                    <div class="deadline">Location: Main Auditorium, 09:00-12:00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resources Tab Content -->
                    <div id="CS301-resources" class="tab-content">
                        <h3>Course Resources</h3>
                        
                        <h4>Required Textbooks</h4>
                        <div class="resource-item">
                            <div class="resource-icon">📚</div>
                            <div>
                                <strong>Introduction to Algorithms, 4th Edition</strong>
                                <p>by Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, and Clifford Stein</p>
                                <p>MIT Press, 2022 | ISBN: 978-0262046305</p>
                            </div>
                        </div>
                        
                        <div class="resource-item">
                            <div class="resource-icon">📚</div>
                            <div>
                                <strong>Data Structures and Algorithm Analysis in Java, 3rd Edition</strong>
                                <p>by Mark Allen Weiss</p>
                                <p>Pearson, 2011 | ISBN: 978-0132576277</p>
                            </div>
                        </div>
                        
                        <h4>Online Resources</h4>
                        <div class="resource-item">
                            <div class="resource-icon">🌐</div>
                            <div>
                                <strong>Course LMS Page</strong>
                                <p>All lecture slides, assignment descriptions, and submission portals are available on the course LMS page.</p>
                                <p><a href="#">https://lms.monacoinstitute.edu/courses/cs301-spring2025</a></p>
                            </div>
                        </div>
                        
                        <div class="resource-item">
                            <div class="resource-icon">🌐</div>
                            <div>
                                <strong>Visualization Tools</strong>
                                <p>Interactive algorithm visualizations to help understand core concepts.</p>
                                <p><a href="#">https://visualgo.net</a></p>
                            </div>
                        </div>
                        
                        <div class="resource-item">
                            <div class="resource-icon">🌐</div>
                            <div>
                                <strong>Practice Problems</strong>
                                <p>Additional practice problems with solutions.</p>
                                <p><a href="#">https://leetcode.com</a></p>
                            </div>
                        </div>
                        
                        <h4>Software Requirements</h4>
                        <div class="resource-item">
                            <div class="resource-icon">💻</div>
                            <div>
                                <strong>Java Development Kit (JDK) 17 or higher</strong>
                                <p>Required for all programming assignments and lab sessions.</p>
                            </div>
                        </div>
                        
                        <div class="resource-item">
                            <div class="resource-icon">💻</div>
                            <div>
                                <strong>IntelliJ IDEA or Eclipse IDE</strong>
                                <p>Recommended development environments for course projects.</p>
                            </div>
                        </div>
                        
                        <div class="resource-item">
                            <div class="resource-icon">💻</div>
                            <div>
                                <strong>Git Version Control</strong>
                                <p>Required for project submissions and collaboration.</p>
                            </div>
                        </div>
                        
                        <h4>Support Services</h4>
                        <div class="resource-item">
                            <div class="resource-icon">🤝</div>
                            <div>
                                <strong>CS Tutoring Center</strong>
                                <p>Free peer tutoring available for all course topics.</p>