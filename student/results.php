<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Student Portal - Results</title>
    <link rel="stylesheet" href="dash.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
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
                    <span class="header-date" id="current-date">Friday, April 12, 2025</span>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                </div>
                <div class="user-actions">
                    <i class="settings-icon">⚙️</i>
                </div>
            </div>
            
            <!-- Results Section -->
            <div id="results" class="content-section">
                <div class="student-profile">
                    <div class="student-avatar">JS</div>
                    <div class="student-info">
                        <h2>John Smith</h2>
                        <p>Computer Science, Year 3</p>
                        <p>Student ID: STU2025001</p>
                        <div class="gpa-container">
                            <div class="gpa-circle">3.7</div>
                            <div>
                                <strong>Cumulative GPA</strong><br>
                                <small>90 Credits Completed</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Semester Selection -->
                <div class="section">
                    <div class="section-header">
                        <h3>Academic Results</h3>
                        <div class="action-buttons">
                            <button class="primary-btn" id="download-all-results">Download All Results</button>
                        </div>
                    </div>
                    
                    <div class="semester-filters">
                        <select id="semester-select" class="semester-dropdown" onchange="changeSemester()">
                            <option value="spring-2025">Spring 2025 (Current)</option>
                            <option value="fall-2024">Fall 2024</option>
                            <option value="spring-2024">Spring 2024</option>
                            <option value="fall-2023">Fall 2023</option>
                        </select>
                    </div>
                </div>
                
                <!-- Current Semester Results -->
                <div id="spring-2025" class="semester-results active-semester">
                    <div class="section">
                        <div class="section-header">
                            <h3>Spring 2025 Results</h3>
                            <div class="semester-summary">
                                <div class="semester-gpa">
                                    <strong>Semester GPA:</strong> 3.8
                                </div>
                                <div class="semester-credits">
                                    <strong>Credits:</strong> 15
                                </div>
                                <div class="action-buttons">
                                    <button class="secondary-btn" onclick="downloadResults('spring-2025')">
                                        <i class="fas fa-download"></i> Download Semester Result
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Credit Hours</th>
                                    <th>Grade</th>
                                    <th>Grade Points</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CS301</td>
                                    <td>Data Structures and Algorithms</td>
                                    <td>4</td>
                                    <td><span class="grade-a">A-</span></td>
                                    <td>3.7</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('CS301')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('CS301', 'Data Structures and Algorithms', 'A-')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>CS315</td>
                                    <td>Database Systems</td>
                                    <td>4</td>
                                    <td><span class="grade-b">B+</span></td>
                                    <td>3.3</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('CS315')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('CS315', 'Database Systems', 'B+')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>MATH302</td>
                                    <td>Discrete Mathematics</td>
                                    <td>4</td>
                                    <td><span class="grade-a">A</span></td>
                                    <td>4.0</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('MATH302')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('MATH302', 'Discrete Mathematics', 'A')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ENG210</td>
                                    <td>Technical Writing</td>
                                    <td>3</td>
                                    <td><span class="grade-b">B</span></td>
                                    <td>3.0</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('ENG210')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('ENG210', 'Technical Writing', 'B')">Report Issue</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td><strong>15</strong></td>
                                    <td></td>
                                    <td><strong>3.8 GPA</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Fall 2024 Semester Results -->
                <div id="fall-2024" class="semester-results">
                    <div class="section">
                        <div class="section-header">
                            <h3>Fall 2024 Results</h3>
                            <div class="semester-summary">
                                <div class="semester-gpa">
                                    <strong>Semester GPA:</strong> 3.6
                                </div>
                                <div class="semester-credits">
                                    <strong>Credits:</strong> 16
                                </div>
                                <div class="action-buttons">
                                    <button class="secondary-btn" onclick="downloadResults('fall-2024')">
                                        <i class="fas fa-download"></i> Download Semester Result
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Credit Hours</th>
                                    <th>Grade</th>
                                    <th>Grade Points</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CS290</td>
                                    <td>Web Development</td>
                                    <td>4</td>
                                    <td><span class="grade-a">A</span></td>
                                    <td>4.0</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('CS290')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('CS290', 'Web Development', 'A')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>CS240</td>
                                    <td>Operating Systems</td>
                                    <td>4</td>
                                    <td><span class="grade-b">B+</span></td>
                                    <td>3.3</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('CS240')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('CS240', 'Operating Systems', 'B+')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>CS260</td>
                                    <td>Computer Networks</td>
                                    <td>4</td>
                                    <td><span class="grade-a">A-</span></td>
                                    <td>3.7</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('CS260')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('CS260', 'Computer Networks', 'A-')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>MATH240</td>
                                    <td>Linear Algebra</td>
                                    <td>4</td>
                                    <td><span class="grade-b">B</span></td>
                                    <td>3.0</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('MATH240')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('MATH240', 'Linear Algebra', 'B')">Report Issue</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td><strong>16</strong></td>
                                    <td></td>
                                    <td><strong>3.6 GPA</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Spring 2024 Semester Results -->
                <div id="spring-2024" class="semester-results">
                    <div class="section">
                        <div class="section-header">
                            <h3>Spring 2024 Results</h3>
                            <div class="semester-summary">
                                <div class="semester-gpa">
                                    <strong>Semester GPA:</strong> 3.9
                                </div>
                                <div class="semester-credits">
                                    <strong>Credits:</strong> 15
                                </div>
                                <div class="action-buttons">
                                    <button class="secondary-btn" onclick="downloadResults('spring-2024')">
                                        <i class="fas fa-download"></i> Download Semester Result
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Credit Hours</th>
                                    <th>Grade</th>
                                    <th>Grade Points</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CS210</td>
                                    <td>Data Science Fundamentals</td>
                                    <td>4</td>
                                    <td><span class="grade-a">A</span></td>
                                    <td>4.0</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('CS210')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('CS210', 'Data Science Fundamentals', 'A')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>CS220</td>
                                    <td>Software Engineering</td>
                                    <td>4</td>
                                    <td><span class="grade-a">A</span></td>
                                    <td>4.0</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('CS220')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('CS220', 'Software Engineering', 'A')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>MATH230</td>
                                    <td>Probability & Statistics</td>
                                    <td>4</td>
                                    <td><span class="grade-a">A-</span></td>
                                    <td>3.7</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('MATH230')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('MATH230', 'Probability & Statistics', 'A-')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>COMM200</td>
                                    <td>Business Communication</td>
                                    <td>3</td>
                                    <td><span class="grade-b">B+</span></td>
                                    <td>3.3</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('COMM200')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('COMM200', 'Business Communication', 'B+')">Report Issue</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td><strong>15</strong></td>
                                    <td></td>
                                    <td><strong>3.9 GPA</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Fall 2023 Semester Results -->
                <div id="fall-2023" class="semester-results">
                    <div class="section">
                        <div class="section-header">
                            <h3>Fall 2023 Results</h3>
                            <div class="semester-summary">
                                <div class="semester-gpa">
                                    <strong>Semester GPA:</strong> 3.5
                                </div>
                                <div class="semester-credits">
                                    <strong>Credits:</strong> 14
                                </div>
                                <div class="action-buttons">
                                    <button class="secondary-btn" onclick="downloadResults('fall-2023')">
                                        <i class="fas fa-download"></i> Download Semester Result
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Credit Hours</th>
                                    <th>Grade</th>
                                    <th>Grade Points</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CS101</td>
                                    <td>Introduction to Programming</td>
                                    <td>4</td>
                                    <td><span class="grade-a">A</span></td>
                                    <td>4.0</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('CS101')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('CS101', 'Introduction to Programming', 'A')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>CS120</td>
                                    <td>Computer Architecture</td>
                                    <td>3</td>
                                    <td><span class="grade-b">B</span></td>
                                    <td>3.0</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('CS120')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('CS120', 'Computer Architecture', 'B')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>MATH101</td>
                                    <td>Calculus I</td>
                                    <td>4</td>
                                    <td><span class="grade-a">A-</span></td>
                                    <td>3.7</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('MATH101')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('MATH101', 'Calculus I', 'A-')">Report Issue</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ENG110</td>
                                    <td>Academic Writing</td>
                                    <td>3</td>
                                    <td><span class="grade-b">B+</span></td>
                                    <td>3.3</td>
                                    <td class="action-cell">
                                        <button class="action-btn view-btn" onclick="viewCourseDetails('ENG110')">View Details</button>
                                        <button class="action-btn" onclick="openComplaintModal('ENG110', 'Academic Writing', 'B+')">Report Issue</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td><strong>14</strong></td>
                                    <td></td>
                                    <td><strong>3.5 GPA</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Academic Standing Summary -->
                <div class="section">
                    <div class="section-header">
                        <h3>Academic Standing Summary</h3>
                    </div>
                    
                    <div class="academic-summary">
                        <div class="progress-container">
                            <h4>Cumulative GPA: 3.7</h4>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: 92.5%;"></div>
                            </div>
                            <p><small>Academic Standing: <strong class="grade-a">Excellent</strong></small></p>
                        </div>
                        
                        <div class="summary-stats">
                            <div class="summary-card">
                                <div class="card-header">
                                    <h3>Total Credits</h3>
                                    <div class="card-icon blue">C</div>
                                </div>
                                <div class="card-number">90</div>
                                <div class="card-label">60 credits remaining</div>
                            </div>
                            
                            <div class="summary-card">
                                <div class="card-header">
                                    <h3>Honors Status</h3>
                                    <div class="card-icon purple">H</div>
                                </div>
                                <div class="card-number">Dean's List</div>
                                <div class="card-label">3 Consecutive Semesters</div>
                            </div>
                            
                            <div class="summary-card">
                                <div class="card-header">
                                    <h3>Graduation Tracking</h3>
                                    <div class="card-icon green">G</div>
                                </div>
                                <div class="card-number">60%</div>
                                <div class="card-label">Expected: May 2026</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grade Complaint Modal -->
    <div id="complaint-modal" class="modal-overlay">
        <div class="modal">
            <span class="modal-close" onclick="closeComplaintModal()">&times;</span>
            <div class="modal-header">
                <h3>Report Grade Issue</h3>
            </div>
            <div class="modal-body">
                <form id="complaint-form">
                    <div class="form-group">
                        <label for="course-code">Course Code:</label>
                        <input type="text" id="course-code" readonly>
                    </div>
                    <div class="form-group">
                        <label for="course-name">Course Name:</label>
                        <input type="text" id="course-name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="current-grade">Current Grade:</label>
                        <input type="text" id="current-grade" readonly>
                    </div>
                    <div class="form-group">
                        <label for="issue-type">Issue Type:</label>
                        <select id="issue-type">
                            <option value="incorrect-marking">Incorrect Marking</option>
                            <option value="missing-grades">Missing Assessment Grades</option>
                            <option value="calculation-error">Grade Calculation Error</option>
                            <option value="other">Other Issue</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="issue-description">Detailed Description:</label>
                        <textarea id="issue-description" rows="5" placeholder="Please provide specific details about the issue..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="supporting-document">Supporting Document (optional):</label>
                        <div class="file-input-wrapper">
                            <button class="file-input-button">
                                <i class="fas fa-paperclip"></i> Attach File
                            </button>
                            <input type="file" id="supporting-document">
                        </div>
                        <small>Upload any evidence that supports your claim (e.g., marked assignments, exam papers)</small>
                    </div>
                    <div class="complaint-notice">
                        <p><small>Your complaint will be sent to the course instructor, department head, and academic registrar for review.</small></p>
                    </div>
                    <div class="form-buttons">
                        <button type="button" class="primary-btn" onclick="submitComplaint()">Submit Complaint</button>
                        <button type="button" class="secondary-btn" onclick="closeComplaintModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Course Details Modal -->
    <div id="course-details-modal" class="modal-overlay">
        <div class="modal">
            <span class="modal-close" onclick="closeCourseDetailsModal()">&times;</span>
            <div class="modal-header">
                <h3 id="course-details-title">Course Details</h3>
            </div>
            <div class="modal-body">
                <div id="course-details-content">
                    <div class="assessment-breakdown">
                        <h4>Assessment Breakdown</h4>
                        <table class="assessment-table">
                            <thead>
                                <tr>
                                    <th>Assessment</th>
                                    <th>Weight</th>
                                    <th>Your Score</th>
                                    <th>Class Average</th>
                                </tr>
                            </thead>
                            <tbody id="assessment-breakdown-body">
                                <!-- Populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="grade-distribution">
                        <h4>Class Grade Distribution</h4>
                        <div class="grade-bars">
                            <div class="grade-bar-container">
                                <div class="grade-label">A</div>
                                <div class="grade-bar-wrapper">
                                    <div class="grade-bar-fill" id="grade-a-fill"></div>
                                </div>
                                <div class="grade-count" id="grade-a-count"></div>
                            </div>
                            <div class="grade-bar-container">
                                <div class="grade-label">B</div>
                                <div class="grade-bar-wrapper">
                                    <div class="grade-bar-fill" id="grade-b-fill"></div>
                                </div>
                                <div class="grade-count" id="grade-b-count"></div>
                            </div>
                            <div class="grade-bar-container">
                                <div class="grade-label">C</div>
                                <div class="grade-bar-wrapper">
                                    <div class="grade-bar-fill" id="grade-c-fill"></div>
                                </div>
                                <div class="grade-count" id="grade-c-count"></div>
                            </div>
                            <div class="grade-bar-container">
                                <div class="grade-label">D</div>
                                <div class="grade-bar-wrapper">
                                    <div class="grade-bar-fill" id="grade-d-fill"></div>
                                </div>
                                <div class="grade-count" id="grade-d-count"></div>
                            </div>
                            <div class="grade-bar-container">
                                <div class="grade-label">F</div>
                                <div class="grade-bar-wrapper">
                                    <div class="grade-bar-fill" id="grade-f-fill"></div>
                                </div>
                                <div class="grade-count" id="grade-f-count"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="instructor-feedback">
                        <h4>Instructor Feedback</h4>
                        <p id="instructor-feedback-content"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Additional styles for Results page */
        .semester-dropdown {
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 250px;
            margin-bottom: 20px;
        }
        
        .semester-results {
            display: none;
        }
        
        .active-semester {
            display: block;
        }
        
        .semester-summary {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .semester-gpa, .semester-credits {
            font-size: 16px;
        }
        
        .results-table {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .grade-circle {
            font-size: 24px;
            width: 50px;
            height: 50px;
        }
        
        .academic-summary {
            margin-top: 20px;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .summary-card {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px;
        }