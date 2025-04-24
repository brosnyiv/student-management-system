<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Staff Attendance Tracker</title>
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
            --wfh-color: #9b59b6;
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
            width: 28px;
            height: 28px;
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
        
        .status-label.leave {
            background-color: var(--excused-color);
        }
        
        .status-label.wfh {
            background-color: var(--wfh-color);
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
        
        .legend-indicator.leave {
            background-color: var(--excused-color);
        }
        
        .legend-indicator.wfh {
            background-color: var(--wfh-color);
        }
        
        .actions-column {
            width: 100px;
            text-align: center;
        }
        
        .status-column {
            width: 250px;
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
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .stat-item {
            flex: 1;
            min-width: 120px;
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
        
        .stat-item.leave {
            border-left: 4px solid var(--excused-color);
        }
        
        .stat-item.wfh {
            border-left: 4px solid var(--wfh-color);
        }
        
        .stat-item.total {
            border-left: 4px solid var(--primary-color);
        }
        
        .department-info {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid var(--primary-color);
            margin-bottom: 20px;
        }
        
        .department-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .department-details {
            font-size: 14px;
            color: #666;
        }
        
        .filter-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-label {
            font-weight: 500;
            margin-right: 5px;
        }
        
        .search-box {
            display: flex;
            flex: 1;
        }
        
        .search-input {
            flex: 1;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px 0 0 4px;
            font-size: 14px;
        }
        
        .search-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0 15px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: var(--primary-hover);
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
            
            .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h2><i class="fas fa-clipboard-check"></i> Staff Attendance Tracker</h2>
            <a href="dash.html" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <div class="form-body">
            <form id="attendanceForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="attendanceDate" class="required-field">Date</label>
                        <input type="date" id="attendanceDate" required>
                    </div>
                    <div class="form-group">
                        <label for="supervisor" class="required-field">Supervisor</label>
                        <input type="text" id="supervisor" placeholder="Enter supervisor name" required>
                    </div>
                    <div class="form-group">
                        <label for="department" class="required-field">Department</label>
                        <select id="department" required>
                            <option value="">Select department</option>
                        </select>
                    </div>
                </div>
                
                <div id="departmentInfoContainer" class="department-info" style="display: none;">
                    <div class="department-title" id="departmentTitle"></div>
                    <div class="department-details" id="departmentDetails"></div>
                </div>
                
                <div class="filter-controls">
                    <div class="search-box">
                        <input type="text" id="searchStaff" class="search-input" placeholder="Search by name or ID...">
                        <button type="button" class="search-button"><i class="fas fa-search"></i></button>
                    </div>
                    
                    <div>
                        <span class="filter-label">Status:</span>
                        <select id="statusFilter">
                            <option value="all">All</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="leave">On Leave</option>
                            <option value="wfh">WFH</option>
                        </select>
                    </div>
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
                        <div class="legend-indicator leave"></div>
                        <span>On Leave</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-indicator wfh"></div>
                        <span>WFH</span>
                    </div>
                </div>
                
                <table class="attendance-table" id="staffTable">
                    <thead>
                        <tr>
                            <th>Staff ID</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th class="time-column">Time In</th>
                            <th class="time-column">Time Out</th>
                            <th class="status-column center-align">Status</th>
                            <th>Notes</th>
                            <th class="actions-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Staff rows will be loaded here -->
                    </tbody>
                </table>
                
                <button type="button" class="add-row-btn" id="addStaffRow">
                    <i class="fas fa-plus-circle"></i> Add Staff Member
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
                        <div class="stat-item leave">
                            <div class="stat-label">On Leave</div>
                            <div class="stat-value" id="leaveCount">0</div>
                        </div>
                        <div class="stat-item wfh">
                            <div class="stat-label">WFH</div>
                            <div class="stat-value" id="wfhCount">0</div>
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
        // Database of departments, supervisors, and staff (mock data)
        const departmentStaff = {
            "Michael Johnson": [
                { id: "admin", name: "Administration", location: "Main Building - 3rd Floor", 
                  staff: [
                    { id: "EMP001", name: "Emma Williams", position: "Administrative Assistant" },
                    { id: "EMP002", name: "David Thompson", position: "Office Manager" },
                    { id: "EMP003", name: "Sarah Miller", position: "HR Coordinator" },
                    { id: "EMP004", name: "Robert Clark", position: "Finance Officer" }
                  ]
                },
                { id: "it", name: "Information Technology", location: "Tech Wing - 2nd Floor",
                  staff: [
                    { id: "EMP005", name: "Jason Lee", position: "IT Manager" },
                    { id: "EMP006", name: "Michelle Garcia", position: "Network Administrator" },
                    { id: "EMP007", name: "Kevin Wilson", position: "Software Developer" }
                  ]
                }
            ],
            "Lisa Rodriguez": [
                { id: "marketing", name: "Marketing & Communications", location: "East Wing - 1st Floor",
                  staff: [
                    { id: "EMP008", name: "Thomas Brown", position: "Marketing Manager" },
                    { id: "EMP009", name: "Jennifer Davis", position: "Social Media Specialist" },
                    { id: "EMP010", name: "Christopher White", position: "Graphic Designer" }
                  ]
                },
                { id: "sales", name: "Sales Department", location: "West Wing - 1st Floor",
                  staff: [
                    { id: "EMP011", name: "Ashley Martinez", position: "Sales Manager" },
                    { id: "EMP012", name: "Daniel Harris", position: "Sales Representative" },
                    { id: "EMP013", name: "Jessica Lewis", position: "Account Manager" }
                  ]
                }
            ],
            "James Anderson": [
                { id: "academics", name: "Academic Affairs", location: "Main Building - 2nd Floor",
                  staff: [
                    { id: "EMP014", name: "Olivia Martin", position: "Academic Coordinator" },
                    { id: "EMP015", name: "William Taylor", position: "Course Administrator" },
                    { id: "EMP016", name: "Elizabeth Jackson", position: "Academic Advisor" }
                  ]
                }
            ],
            "Patricia Wright": [
                { id: "facilities", name: "Facilities Management", location: "Service Building - Ground Floor",
                  staff: [
                    { id: "EMP017", name: "Joseph Allen", position: "Facilities Manager" },
                    { id: "EMP018", name: "Margaret Young", position: "Maintenance Supervisor" },
                    { id: "EMP019", name: "Richard Adams", position: "Security Officer" }
                  ]
                }
            ]
        };
        
        // Set default date to today
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        document.getElementById('attendanceDate').value = formattedDate;
        
        // Supervisor field event listener
        const supervisorField = document.getElementById('supervisor');
        const departmentSelect = document.getElementById('department');
        
        supervisorField.addEventListener('input', function() {
            const supervisorName = this.value.trim();
            departmentSelect.innerHTML = '<option value="">Select department</option>';
            
            if (supervisorName && departmentStaff[supervisorName]) {
                const departments = departmentStaff[supervisorName];
                departments.forEach(dept => {
                    const option = document.createElement('option');
                    option.value = dept.id;
                    option.textContent = dept.name;
                    departmentSelect.appendChild(option);
                });
            }
        });
        
        // Department select event listener
        departmentSelect.addEventListener('change', function() {
            const deptId = this.value;
            const supervisorName = supervisorField.value.trim();
            
            // Clear staff table
            const staffTable = document.querySelector('#staffTable tbody');
            staffTable.innerHTML = '';
            
            if (deptId && supervisorName && departmentStaff[supervisorName]) {
                const departments = departmentStaff[supervisorName];
                const selectedDept = departments.find(dept => dept.id === deptId);
                
                if (selectedDept) {
                    // Show department info
                    document.getElementById('departmentInfoContainer').style.display = 'block';
                    document.getElementById('departmentTitle').textContent = selectedDept.name;
                    document.getElementById('departmentDetails').textContent = `Location: ${selectedDept.location}`;
                    
                    // Populate staff table
                    selectedDept.staff.forEach((staff, index) => {
                        addStaffToTable(staff.id, staff.name, staff.position, index + 1);
                    });
                    
                    // Update counts
                    updateStaffCounts();
                }
            } else {
                document.getElementById('departmentInfoContainer').style.display = 'none';
            }
        });
        
        // Function to add a staff member to the table
        function addStaffToTable(id, name, position, rowIndex) {
            const staffTable = document.querySelector('#staffTable tbody');
            const newRow = document.createElement('tr');
            
            const currentTime = getCurrentTime();
            
            newRow.innerHTML = `
                <td>
                    <input type="text" value="${id}" class="form-control" readonly>
                </td>
                <td>
                    <input type="text" value="${name}" class="form-control" readonly>
                </td>
                <td>
                    <input type="text" value="${position}" class="form-control" readonly>
                </td>
                <td>
                    <input type="time" class="form-control" value="${currentTime}">
                </td>
                <td>
                    <input type="time" class="form-control">
                </td>
                <td class="center-align">
                    <div class="attendance-status">
                        <input type="radio" name="status_s_${rowIndex}" id="present_s_${rowIndex}" class="status-radio" value="present" checked>
                        <label for="present_s_${rowIndex}" class="status-label present" title="Present">P</label>
                        
                        <input type="radio" name="status_s_${rowIndex}" id="absent_s_${rowIndex}" class="status-radio" value="absent">
                        <label for="absent_s_${rowIndex}" class="status-label absent" title="Absent">A</label>
                        
                        <input type="radio" name="status_s_${rowIndex}" id="late_s_${rowIndex}" class="status-radio" value="late">
                        <label for="late_s_${rowIndex}" class="status-label late" title="Late">L</label>
                        
                        <input type="radio" name="status_s_${rowIndex}" id="leave_s_${rowIndex}" class="status-radio" value="leave">
                        <label for="leave_s_${rowIndex}" class="status-label leave" title="On Leave">L</label>
                        
                        <input type="radio" name="status_s_${rowIndex}" id="wfh_s_${rowIndex}" class="status-radio" value="wfh">
                        <label for="wfh_s_${rowIndex}" class="status-label wfh" title="Working From Home">W</label>
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
            
            staffTable.appendChild(newRow);
            
            // Add event listener to the remove button
            const removeBtn = newRow.querySelector('.remove-row-btn');
            removeBtn.addEventListener('click', function() {
                this.closest('tr').remove();
                updateStaffCounts();
            });
            
            // Add event listeners for status changes
            const statusRadios = newRow.querySelectorAll('.status-radio');
            statusRadios.forEach(radio => {
                radio.addEventListener('change', updateStaffCounts);
            });
        }
        
        // Function to get current time in HH:MM format
        function getCurrentTime() {
            const now = new Date();
            return `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
        }
        
        // Add new staff row
        let staffRowCount = 0;
        
        document.getElementById('addStaffRow').addEventListener('click', function() {
            staffRowCount++;
            const staffTable = document.querySelector('#staffTable tbody');
            const newRow = document.createElement('tr');
            
            newRow.innerHTML = `
                <td>
                    <input type="text" placeholder="Staff ID" class="form-control">
                </td>
                <td>
                    <input type="text" placeholder="Staff Name" class="form-control">
                </td>
                <td>
                    <input type="text" placeholder="Position" class="form-control">
                </td>
                <td>
                    <input type="time" class="form-control" value="${getCurrentTime()}">
                </td>
                <td>
                    <input type="time" class="form-control">
                </td>
                <td class="center-align">
                    <div class="attendance-status">
                        <input type="radio" name="status_new_${staffRowCount}" id="present_new_${staffRowCount}" class="status-radio" value="present" checked>
                        <label for="present_new_${staffRowCount}" class="status-label present" title="Present">P</label>
                        
                        <input type="radio" name="status_new_${staffRowCount}" id="absent_new_${staffRowCount}" class="status-radio" value="absent">
                        <label for="absent_new_${staffRowCount}" class="status-label absent" title="Absent">A</label>
                        
                        <input type="radio" name="status_new_${staffRowCount}" id="late_new_${staffRowCount}" class="status-radio" value="late">
                        <label for="late_new_${staffRowCount}" class="status-label late" title="Late">L</label>
                        
                        <input type="radio" name="status_new_${staffRowCount}" id="leave_new_${staffRowCount}" class="status-radio" value="leave">
                        <label for="leave_new_${staffRowCount}" class="status-label leave" title="On Leave">L</label>
                        
                        <input type="radio" name="status_new_${staffRowCount}" id="wfh_new_${staffRowCount}" class="status-radio" value="wfh">
                        <label for="wfh_new_${staffRowCount}" class="status-label wfh" title="Working From Home">W</label>
                    </div>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-icon btn-edit" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn-icon btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            `;
            
            staffTable.appendChild(newRow);
            
            // Add event listeners for status changes
            const statusRadios = newRow.querySelectorAll('.status-radio');
            statusRadios.forEach(radio => {
                radio.addEventListener('change', updateStaffCounts);
            });
        });

        // Function to update attendance counts
        function updateStaffCounts() {
            const staffTable = document.querySelector('#staffTable tbody');
            const rows = staffTable.querySelectorAll('tr');
            
            let presentCount = 0;
            let absentCount = 0;
            let lateCount = 0;
            let leaveCount = 0;
            let wfhCount = 0;
            let totalCount = 0;
                
            rows.forEach(row => {   
                const statusRadios = row.querySelectorAll('.status-radio');
                statusRadios.forEach(radio => {
                    if (radio.checked) {
                        const status = radio.value;
                        if (status === 'present') presentCount++;
                        else if (status === 'absent') absentCount++;
                        else if (status === 'late') lateCount++;
                        else if (status === 'leave') leaveCount++;
                        else if (status === 'wfh') wfhCount++;
                    }
                });
            });     
 
            totalCount = presentCount + absentCount + lateCount + leaveCount + wfhCount;
            
            document.getElementById('presentCount').textContent = presentCount;
            document.getElementById('absentCount').textContent = absentCount;
            document.getElementById('lateCount').textContent = lateCount;
            document.getElementById('leaveCount').textContent = leaveCount;
            document.getElementById('wfhCount').textContent = wfhCount;
            document.getElementById('totalCount').textContent = totalCount;
        }
        
        // Add event listener to the export button
        document.getElementById('exportBtn').addEventListener('click', function() {
            const staffTable = document.querySelector('#staffTable tbody');
            const rows = staffTable.querySelectorAll('tr');
            
            const data = [];
            
            rows.forEach(row => {
                const statusRadios = row.querySelectorAll('.status-radio');
                const status = Array.from(statusRadios)
                    .find(radio => radio.checked)
                    .value;
                
                const notes = row.querySelector('.note-field').value;
 
                data.push({
                    id: row.querySelector('input[type="text"]').value,
                    name: row.querySelector('input[type="text"]').value,
                    position: row.querySelector('input[type="text"]').value,
                    status: status,
                    notes: notes
                });
            });
 
            const csvContent = data.map(row => 
                `${Object.values(row).join(',')}`
            ).join('\n');   
 
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `attendance_${formattedDate}.csv`;
            a.click();
            URL.revokeObjectURL(url);
        });
    </script>
</body>
</html>


