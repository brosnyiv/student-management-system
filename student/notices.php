<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Student Portal - Notices</title>
    <link rel="stylesheet" href="dash.css">
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
                    <h1>Monaco Institute</h1>
                    <h3>Welcome, John!</h3>
                    <span class="header-date" id="current-date">Saturday, April 12, 2025</span>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search notices...">
                </div>
                <div class="user-actions">
                    <i class="settings-icon">⚙️</i>
                </div>
            </div>
            
            <!-- Notices Section -->
            <div id="notices" class="content-section">
                <div class="section">
                    <div class="section-header">
                        <h3>Notices & Announcements</h3>
                        <div class="action-buttons">
                            <button class="secondary-btn">Mark All Read</button>
                            <button class="primary-btn">Filter</button>
                        </div>
                    </div>
                    
                    <div class="notice-filters">
                        <button class="filter-btn active">All</button>
                        <button class="filter-btn">Academic</button>
                        <button class="filter-btn">Events</button>
                        <button class="filter-btn">Administrative</button>
                        <button class="filter-btn">Urgent</button>
                    </div>
                    
                    <!-- Urgent Notice -->
                    <div class="notice-item urgent">
                        <div class="notice-header">
                            <div class="notice-title">URGENT: Campus Closure - April 14th</div>
                            <div class="notice-date">Posted: April 11, 2025</div>
                        </div>
                        <div class="notice-content">
                            <p>Due to scheduled maintenance of electrical systems, the campus will be closed on Monday, April 14th. All classes will be conducted online via the virtual classroom platform. Please ensure you have access to the required technology before the scheduled class times.</p>
                            <div class="notice-meta">
                                <span class="notice-source">From: Campus Administration</span>
                                <span class="notice-tag">Administrative</span>
                                <span class="notice-badge urgent">Urgent</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Academic Notice -->
                    <div class="notice-item">
                        <div class="notice-header">
                            <div class="notice-title">Final Exam Schedule Released</div>
                            <div class="notice-date">Posted: April 10, 2025</div>
                        </div>
                        <div class="notice-content">
                            <p>The final examination schedule for Spring Semester 2025 has been published. Please check your student portal for your personal exam timetable. Note that some exams may be scheduled outside of regular class hours.</p>
                            <p>For any scheduling conflicts, please contact the examination office within 48 hours of this announcement.</p>
                            <div class="notice-meta">
                                <span class="notice-source">From: Office of Academic Affairs</span>
                                <span class="notice-tag">Academic</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Event Notice -->
                    <div class="notice-item event">
                        <div class="notice-header">
                            <div class="notice-title">Annual Tech Symposium - Call for Student Projects</div>
                            <div class="notice-date">Posted: April 9, 2025</div>
                        </div>
                        <div class="notice-content">
                            <p>The Monaco Institute Annual Tech Symposium will be held on May 15-16, 2025. We are now accepting student project submissions for the exhibition. This is an excellent opportunity to showcase your work to industry professionals and potential employers.</p>
                            <p>Submission deadline: April 30, 2025</p>
                            <div class="notice-actions">
                                <button class="primary-btn">Submit Project</button>
                                <button class="secondary-btn">Learn More</button>
                            </div>
                            <div class="notice-meta">
                                <span class="notice-source">From: Student Activities Committee</span>
                                <span class="notice-tag">Event</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Administrative Notice -->
                    <div class="notice-item">
                        <div class="notice-header">
                            <div class="notice-title">Library Hours Extended During Finals Week</div>
                            <div class="notice-date">Posted: April 8, 2025</div>
                        </div>
                        <div class="notice-content">
                            <p>To support students during the final examination period, the campus library will extend its operating hours from May 1-15, 2025:</p>
                            <ul>
                                <li>Monday-Friday: 7:00 AM - 2:00 AM</li>
                                <li>Saturday-Sunday: 8:00 AM - 12:00 AM</li>
                            </ul>
                            <p>Additional study rooms will be available on a first-come, first-served basis.</p>
                            <div class="notice-meta">
                                <span class="notice-source">From: Library Services</span>
                                <span class="notice-tag">Administrative</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Academic Notice -->
                    <div class="notice-item">
                        <div class="notice-header">
                            <div class="notice-title">Summer Semester Registration Opens April 20</div>
                            <div class="notice-date">Posted: April 5, 2025</div>
                        </div>
                        <div class="notice-content">
                            <p>Registration for Summer Semester 2025 courses will open on April 20, 2025, at 9:00 AM. Please consult with your academic advisor before registration to ensure course selections align with your program requirements.</p>
                            <p>Priority registration will be given to graduating seniors for the first 24 hours.</p>
                            <div class="notice-meta">
                                <span class="notice-source">From: Registrar's Office</span>
                                <span class="notice-tag">Academic</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Scholarship Notice -->
                    <div class="notice-item">
                        <div class="notice-header">
                            <div class="notice-title">Monaco Merit Scholarship Application Now Open</div>
                            <div class="notice-date">Posted: April 3, 2025</div>
                        </div>
                        <div class="notice-content">
                            <p>Applications for the Monaco Merit Scholarship for the 2025-2026 academic year are now being accepted. This scholarship covers up to 75% of tuition costs for qualifying students with outstanding academic performance.</p>
                            <p>Eligibility requirements:</p>
                            <ul>
                                <li>Minimum GPA of 3.5</li>
                                <li>Full-time enrollment status</li>
                                <li>Demonstrated leadership and community involvement</li>
                            </ul>
                            <div class="notice-actions">
                                <button class="primary-btn">Apply Now</button>
                                <button class="secondary-btn">View Details</button>
                            </div>
                            <div class="notice-meta">
                                <span class="notice-source">From: Financial Aid Office</span>
                                <span class="notice-tag">Financial</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Maintenance Notice -->
                    <div class="notice-item">
                        <div class="notice-header">
                            <div class="notice-title">Student Portal Scheduled Maintenance</div>
                            <div class="notice-date">Posted: April 1, 2025</div>
                        </div>
                        <div class="notice-content">
                            <p>The student portal will be undergoing scheduled maintenance on Sunday, April 13, 2025, from 2:00 AM to 6:00 AM EDT. During this time, the portal and all associated services will be unavailable.</p>
                            <p>We apologize for any inconvenience this may cause.</p>
                            <div class="notice-meta">
                                <span class="notice-source">From: IT Services</span>
                                <span class="notice-tag">Administrative</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Archived Notices Section -->
                <div class="section">
                    <div class="section-header">
                        <h3>Archived Notices</h3>
                        <div class="action-buttons">
                            <button class="secondary-btn">View All Archives</button>
                        </div>
                    </div>
                    
                    <div class="notice-item archived">
                        <div class="notice-header">
                            <div class="notice-title">Mid-term Examination Schedule</div>
                            <div class="notice-date">Posted: March 15, 2025</div>
                        </div>
                        <div class="notice-content">
                            <p>Mid-term examinations will be conducted from March 25-30, 2025. The detailed schedule is now available for download.</p>
                            <div class="notice-meta">
                                <span class="notice-source">From: Examination Committee</span>
                                <span class="notice-tag">Academic</span>
                                <span class="notice-archived">Archived</span>
                            </div>
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
        
        // Function to handle logout
        function logout() {
            alert('Logging out...');
            // In a real application, this would handle the logout process
        }
        
        // Additional styles for notices
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                .notice-filters {
                    display: flex;
                    gap: 10px;
                    margin-bottom: 20px;
                    flex-wrap: wrap;
                }
                
                .notice-item {
                    background-color: #fff;
                    border-radius: 8px;
                    padding: 20px;
                    margin-bottom: 15px;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                    border-left: 4px solid #3498db;
                }
                
                .notice-item.urgent {
                    border-left-color: #e74c3c;
                }
                
                .notice-item.event {
                    border-left-color: #2ecc71;
                }
                
                .notice-item.archived {
                    border-left-color: #95a5a6;
                    opacity: 0.8;
                }
                
                .notice-header {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 10px;
                    align-items: center;
                }
                
                .notice-title {
                    font-weight: bold;
                    font-size: 18px;
                    color: #2c3e50;
                }
                
                .notice-date {
                    color: #7f8c8d;
                    font-size: 14px;
                }
                
                .notice-content {
                    color: #333;
                    line-height: 1.5;
                }
                
                .notice-content p {
                    margin-bottom: 10px;
                }
                
                .notice-content ul {
                    margin-left: 20px;
                    margin-bottom: 10px;
                }
                
                .notice-actions {
                    margin-top: 15px;
                    display: flex;
                    gap: 10px;
                }
                
                .notice-meta {
                    display: flex;
                    gap: 15px;
                    margin-top: 15px;
                    font-size: 12px;
                    color: #7f8c8d;
                    border-top: 1px solid #f1f1f1;
                    padding-top: 10px;
                }
                
                .notice-badge {
                    background-color: #3498db;
                    color: white;
                    padding: 2px 8px;
                    border-radius: 10px;
                    font-size: 11px;
                }
                
                .notice-badge.urgent {
                    background-color: #e74c3c;
                }
                
                .notice-archived {
                    background-color: #95a5a6;
                    color: white;
                    padding: 2px 8px;
                    border-radius: 10px;
                    font-size: 11px;
                }
            </style>
        `);
    </script>
</body>
</html>