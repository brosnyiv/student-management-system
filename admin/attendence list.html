<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Enhanced Attendance Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #8B1818;
            --primary-hover: #701010;
            --text-color: #333;
            --light-bg: #f5f5f5;
            --border-color: #ddd;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --excused-color: #3498db;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }
        
        .container {
            max-width: 1100px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .form-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .form-header h2 {
            margin: 0;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .back-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .back-link:hover {
            color: #f0f0f0;
        }
        
        .form-body {
            padding: 25px;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            flex: 1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .required-field::after {
            content: "*";
            color: #e74c3c;
            margin-left: 4px;
        }
        
        .btn {
            padding: 12px 20px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-size: 15px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
        }
        
        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background-color: #d0d0d0;
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #27ae60;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .attendance-table th {
            background-color: #f5f5f5;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .attendance-table th.center-align,
        .attendance-table td.center-align {
            text-align: center;
        }
        
        .attendance-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .attendance-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .attendance-table tr:hover {
            background-color: #f1f1f1;
        }
        
        .attendance-status {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        
        .status-radio {
            display: none;
        }
        
        .status-label {
            display: inline-block;
            cursor: pointer;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            transition: transform 0.2s;
        }
        
        .status-label.present {
            background-color: var(--success-color);
        }
        
        .status-label.absent {
            background-color: var(--danger-color);
        }
        
        .status-label.late {
            background-color: var(--warning-color);
        }
        
        .status-label.excused {
            background-color: var(--excused-color);
        }
        
        .status-radio:checked + .status-label {
            transform: scale(1.1);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .status-legend {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        
        .legend-indicator {
            width: 16px;
            height: 16px;
            border-radius: 50%;
        }
        
        .legend-indicator.present {
            background-color: var(--success-color);
        }
        
        .legend-indicator.absent {
            background-color: var(--danger-color);
        }
        
        .legend-indicator.late {
            background-color: var(--warning-color);
        }
        
        .legend-indicator.excused {
            background-color: var(--excused-color);
        }
        
        .actions-column {
            width: 100px;
            text-align: center;
        }
        
        .status-column {
            width: 220px;
        }
        
        .time-column {
            width: 120px;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .add-row-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
            padding: 10px;
            transition: all 0.2s;
            margin-bottom: 15px;
        }
        
        .add-row-btn:hover {
            background-color: rgba(139, 24, 24, 0.05);
            border-radius: 4px;
        }
        
        .remove-row-btn {
            background: none;
            border: none;
            color: var(--danger-color);
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s;
        }
        
        .remove-row-btn:hover {
            transform: scale(1.1);
        }
        
        .note-field {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
            resize: none;
        }
        
        .note-field:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .summary-section {
            margin-top: 30px;
            padding: 15px;
            background-color: var(--light-bg);
            border-radius: 6px;
        }
        
        .summary-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        .summary-stats {
            display: flex;
            gap: 20px;
        }
        
        .stat-item {
            flex: 1;
            background-color: white;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 600;
        }
        
        .stat-item.present {
            border-left: 4px solid var(--success-color);
        }
        
        .stat-item.absent {
            border-left: 4px solid var(--danger-color);
        }
        
        .stat-item.late {
            border-left: 4px solid var(--warning-color);
        }
        
        .stat-item.excused {
            border-left: 4px solid var(--excused-color);
        }
        
        .stat-item.total {
            border-left: 4px solid var(--primary-color);
        }
        
        .course-info {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid var(--primary-color);
            margin-bottom: 20px;
        }
        
        .course-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .course-details {
            font-size: 14px;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .summary-stats {
                flex-wrap: wrap;
            }
            
            .stat-item {
                min-width: 45%;
            }
            
            .attendance-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h2><i class="fas fa-clipboard-check"></i> Attendance Tracker</h2>
            <a href="dash.html" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <div class="form-body">
            <form id="attendanceForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="attendanceDate" class="required-field">Date</label>
                        <input type="date" id="attendanceDate" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="instructor" class="required-field">Instructor</label>
                        <input type="text" id="instructor" placeholder="Enter instructor name" required>
                    </div>
                    <div class="form-group">
                        <label for="courseClass" class="required-field">Course/Class</label>
                        <select id="courseClass" required>
                            <option value="">Select course or class</option>
                        </select>
                    </div>
                </div>
                
                <div id="courseInfoContainer" class="course-info" style="display: none;">
                    <div class="course-title" id="courseTitle"></div>
                    <div class="course-details" id="courseDetails"></div>
                </div>
                
                <div class="status-legend">
                    <div class="legend-item">
                        <div class="legend-indicator present"></div>
                        <span>Present</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-indicator absent"></div>
                        <span>Absent</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-indicator late"></div>
                        <span>Late</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-indicator excused"></div>
                        <span>Excused</span>
                    </div>
                </div>
                
                <table class="attendance-table" id="studentTable">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th class="time-column">Time In</th>
                            <th class="status-column center-align">Status</th>
                            <th>Notes</th>
                            <th class="actions-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Student rows will be loaded here -->
                    </tbody>
                </table>
                
                <button type="button" class="add-row-btn" id="addStudentRow">
                    <i class="fas fa-plus-circle"></i> Add Student
                </button>
                
                <div class="summary-section">
                    <div class="summary-title">Attendance Summary</div>
                    <div class="summary-stats">
                        <div class="stat-item present">
                            <div class="stat-label">Present</div>
                            <div class="stat-value" id="presentCount">0</div>
                        </div>
                        <div class="stat-item absent">
                            <div class="stat-label">Absent</div>
                            <div class="stat-value" id="absentCount">0</div>
                        </div>
                        <div class="stat-item late">
                            <div class="stat-label">Late</div>
                            <div class="stat-value" id="lateCount">0</div>
                        </div>
                        <div class="stat-item excused">
                            <div class="stat-label">Excused</div>
                            <div class="stat-value" id="excusedCount">0</div>
                        </div>
                        <div class="stat-item total">
                            <div class="stat-label">Total</div>
                            <div class="stat-value" id="totalCount">0</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="additionalNotes">Additional Notes</label>
                    <textarea id="additionalNotes" rows="3" placeholder="Any general notes about attendance"></textarea>
                </div>
                
                <div class="form-actions">
                    <div>
                        <button type="reset" class="btn btn-secondary">Clear Form</button>
                    </div>
                    <div>
                        <button type="button" id="exportBtn" class="btn btn-success">Export Record</button>
                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Database of instructors, courses, and students (mock data)
        const instructorCourses = {
            "John Smith": [
                { id: "cs101", name: "CS101 - Intro to Computer Science", units: 3, 
                  students: [
                    { id: "ST001", name: "Alex Johnson" },
                    { id: "ST002", name: "Maria Garcia" },
                    { id: "ST003", name: "James Wilson" },
                    { id: "ST004", name: "Sophia Lee" }
                  ]
                },
                { id: "cs202", name: "CS202 - Data Structures", units: 4,
                  students: [
                    { id: "ST005", name: "Daniel Brown" },
                    { id: "ST006", name: "Emma Davis" },
                    { id: "ST007", name: "Michael Taylor" }
                  ]
                }
            ],
            "Sarah Johnson": [
                { id: "ba200", name: "BA200 - Business Administration", units: 3,
                  students: [
                    { id: "ST008", name: "Olivia Miller" },
                    { id: "ST009", name: "William Wilson" },
                    { id: "ST010", name: "Sophia Rodriguez" }
                  ]
                },
                { id: "ba305", name: "BA305 - Marketing Strategies", units: 4,
                  students: [
                    { id: "ST011", name: "Ethan Martinez" },
                    { id: "ST012", name: "Ava Anderson" },
                    { id: "ST013", name: "Noah Thomas" }
                  ]
                }
            ],
            "David Chen": [
                { id: "gd150", name: "GD150 - Fundamentals of Design", units: 3,
                  students: [
                    { id: "ST014", name: "Isabella White" },
                    { id: "ST015", name: "Lucas Jackson" },
                    { id: "ST016", name: "Mia Harris" }
                  ]
                }
            ],
            "Rebecca Taylor": [
                { id: "dm220", name: "DM220 - Digital Marketing Strategy", units: 3,
                  students: [
                    { id: "ST017", name: "Benjamin Martin" },
                    { id: "ST018", name: "Charlotte Thompson" },
                    { id: "ST019", name: "Alexander Garcia" }
                  ]
                }
            ]
        };
        
        // Set default date to today
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        document.getElementById('attendanceDate').value = formattedDate;
        
        // Instructor field event listener
        const instructorField = document.getElementById('instructor');
        const courseSelect = document.getElementById('courseClass');
        
        instructorField.addEventListener('input', function() {
            const instructorName = this.value.trim();
            courseSelect.innerHTML = '<option value="">Select course or class</option>';
            
            if (instructorName && instructorCourses[instructorName]) {
                const courses = instructorCourses[instructorName];
                courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.name;
                    courseSelect.appendChild(option);
                });
            }
        });
        
        // Course select event listener
        courseSelect.addEventListener('change', function() {
            const courseId = this.value;
            const instructorName = instructorField.value.trim();
            
            // Clear student table
            const studentTable = document.querySelector('#studentTable tbody');
            studentTable.innerHTML = '';
            
            if (courseId && instructorName && instructorCourses[instructorName]) {
                const courses = instructorCourses[instructorName];
                const selectedCourse = courses.find(course => course.id === courseId);
                
                if (selectedCourse) {
                    // Show course info
                    document.getElementById('courseInfoContainer').style.display = 'block';
                    document.getElementById('courseTitle').textContent = selectedCourse.name;
                    document.getElementById('courseDetails').textContent = `${selectedCourse.units} Units`;
                    
                    // Populate student table
                    selectedCourse.students.forEach((student, index) => {
                        addStudentToTable(student.id, student.name, index + 1);
                    });
                    
                    // Update counts
                    updateStudentCounts();
                }
            } else {
                document.getElementById('courseInfoContainer').style.display = 'none';
            }
        });
        
        // Function to add a student to the table
        function addStudentToTable(id, name, rowIndex) {
            const studentTable = document.querySelector('#studentTable tbody');
            const newRow = document.createElement('tr');
            
            const currentTime = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            newRow.innerHTML = `
                <td>
                    <input type="text" value="${id}" class="form-control" readonly>
                </td>
                <td>
                    <input type="text" value="${name}" class="form-control" readonly>
                </td>
                <td>
                    <input type="time" class="form-control" value="${getCurrentTime()}">
                </td>
                <td class="center-align">
                    <div class="attendance-status">
                        <input type="radio" name="status_s_${rowIndex}" id="present_s_${rowIndex}" class="status-radio" value="present" checked>
                        <label for="present_s_${rowIndex}" class="status-label present" title="Present">P</label>
                        
                        <input type="radio" name="status_s_${rowIndex}" id="absent_s_${rowIndex}" class="status-radio" value="absent">
                        <label for="absent_s_${rowIndex}" class="status-label absent" title="Absent">A</label>
                        
                        <input type="radio" name="status_s_${rowIndex}" id="late_s_${rowIndex}" class="status-radio" value="late">
                        <label for="late_s_${rowIndex}" class="status-label late" title="Late">L</label>
                        
                        <input type="radio" name="status_s_${rowIndex}" id="excused_s_${rowIndex}" class="status-radio" value="excused">
                        <label for="excused_s_${rowIndex}" class="status-label excused" title="Excused">E</label>
                    </div>
                </td>
                <td>
                    <input type="text" placeholder="Optional notes" class="note-field">
                </td>
                <td class="center-align">
                    <button type="button" class="remove-row-btn" title="Remove row">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
            
            studentTable.appendChild(newRow);
            
            // Add event listener to the remove button
            const removeBtn = newRow.querySelector('.remove-row-btn');
            removeBtn.addEventListener('click', function() {
                this.closest('tr').remove();
                updateStudentCounts();
            });
            
            // Add event listeners for status changes
            const statusRadios = newRow.querySelectorAll('.status-radio');
            statusRadios.forEach(radio => {
                radio.addEventListener('change', updateStudentCounts);
            });
        }
        
        // Function to get current time in HH:MM format
        function getCurrentTime() {
            const now = new Date();
            return `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
        }
        
        // Add new student row
        let studentRowCount = 0;
        
        document.getElementById('addStudentRow').addEventListener('click', function() {
            studentRowCount++;
            const studentTable = document.querySelector('#studentTable tbody');
            const newRow = document.createElement('tr');
            
            newRow.innerHTML = `
                <td>
                    <input type="text" placeholder="Student ID" class="form-control">
                </td>
                <td>
                    <input type="text" placeholder="Student Name" class="form-control">
                </td>
                <td>
                    <input type="time" class="form-control" value="${getCurrentTime()}">
                </td>
                <td class="center-align">
                    <div class="attendance-status">
                        <input type="radio" name="status_new_${studentRowCount}" id="present_new_${studentRowCount}" class="status-radio" value="present" checked>
                        <label for="present_new_${studentRowCount}" class="status-label present" title="Present">P</label>
                        
                        <input type="radio" name="status_new_${studentRowCount}" id="absent_new_${studentRowCount}" class="status-radio" value="absent">
                        <label for="absent_new_${studentRowCount}" class="status-label absent" title="Absent">A</label>
                        
                        <input type="radio" name="status_new_${studentRowCount}" id="late_new_${studentRowCount}" class="status-radio" value="late">
                        <label for="late_new_${studentRowCount}" class="status-label late" title="Late">L</label>
                        
                        <input type="radio" name="status_new_${studentRowCount}" id="excused_new_${studentRowCount}" class="status-radio" value="excused">
                        <label for="excused_new_${studentRowCount}" class="status-label excused" title="Excused">E</label>
                    </div>
                </td>
                <td>
                    <input type="text" placeholder="Optional notes" class="note-field">
                </td>
                <td class="center-align">
                    <button type="button" class="remove-row-btn" title="Remove row">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
            
            studentTable.appendChild(newRow);
            updateStudentCounts();
            
            // Add event listener to the remove button
            const removeBtn = newRow.querySelector('.remove-row-btn');
            removeBtn.addEventListener('click', function() {
                this.closest('tr').remove();
                updateStudentCounts();
            });
            
            // Add event listeners for status changes
            const statusRadios = newRow.querySelectorAll('.status-radio');
            statusRadios.forEach(radio => {
                radio.addEventListener('change', updateStudentCounts);
            });
        });
        
        // Update counts
        function updateStudentCounts() {
            const rows = document.querySelectorAll('#studentTable tbody tr');
            let presentCount = 0;
            let absentCount = 0;
            let lateCount = 0;
            let excusedCount = 0;
            
            rows.forEach(row => {
                const presentRadio = row.querySelector('input[value="present"]');
                const absentRadio = row.querySelector('input[value="absent"]');
                const lateRadio = row.querySelector('input[value="late"]');
                const excusedRadio = row.querySelector('input[value="excused"]');
                
                if (presentRadio && presentRadio.checked) presentCount++;
                if (absentRadio && absentRadio.checked) absentCount++;
                if (lateRadio && lateRadio.checked) lateCount++;
                if (excusedRadio && excusedRadio.checked) excusedCount++;
            });
            
            document.getElementById('presentCount').textContent = presentCount;
            document.getElementById('absentCount').textContent = absentCount;
            document.getElementById('lateCount').textContent = lateCount;
            document.getElementById('excusedCount').textContent = excusedCount;
            document.getElementById('totalCount').textContent = rows.length;
        }
        
        // Form submission
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would typically save the data to a server
            alert('Attendance record saved successfully!');
        });
        
        // Export functionality
        document.getElementById('exportBtn').addEventListener('click', function() {
            alert('Attendance record exported!');
            // In a real implementation, this would generate a CSV/PDF export
        });
    </script>
</body>
</html>