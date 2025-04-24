<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management - Monaco Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Form Styles */
        .add-course-form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .section-title {
            font-size: 22px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #3498db;
        }
        
        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            margin: 25px 0 15px;
            color: #2c3e50;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
            gap: 20px;
        }
        
        .form-group {
            flex: 1;
            min-width: 250px;
        }
        
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #555;
        }
        
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input:focus,
        select:focus,
        textarea:focus {
            border-color: #3498db;
            outline: none;
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        /* Course Units Styling */
        .course-units-container {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .semester-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 15px;
        }
        
        .semester-tab {
            padding: 8px 15px;
            background-color: #eee;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .semester-tab.active {
            background-color: #3498db;
            color: white;
        }
        
        .semester-content {
            display: none;
            padding: 10px 0;
        }
        
        .semester-content.active {
            display: block;
        }
        
        .unit-container {
            margin-bottom: 15px;
        }
        
        .unit-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
            padding: 10px;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .unit-input {
            flex: 2;
            min-width: 150px;
        }
        
        .instructor-select {
            flex: 2;
            min-width: 150px;
        }
        
        .unit-credits {
            flex: 1;
            min-width: 100px;
        }
        
        .unit-action {
            display: flex;
            align-items: flex-end;
            padding-bottom: 10px;
        }
        
        .btn-remove-unit {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-remove-unit:hover {
            background-color: #c0392b;
        }
        
        .btn-add-unit {
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .btn-add-unit:hover {
            background-color: #27ae60;
        }
        
        .btn-add-unit i {
            margin-right: 5px;
        }
        
        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-cancel {
            background-color: #95a5a6;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .btn-cancel:hover {
            background-color: #7f8c8d;
        }
        
        .btn-submit {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .btn-submit:hover {
            background-color: #2980b9;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 20px 0;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        /* Course Actions */
        .course-actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .add-button {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s;
        }
        
        .add-button:hover {
            background-color: #2980b9;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 10px;
            }
            
            .form-group {
                width: 100%;
            }
            
            .unit-row {
                flex-direction: column;
            }
            
            .semester-tabs {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="course-actions">
            <!-- This would be where your tabs and add button go -->
            <div class="tabs">
                <div class="course-tab active" data-view="list">Course List</div>
                <div class="course-tab" data-view="add">Add Course</div>
            </div>
            <button class="add-button"><i class="fas fa-plus"></i> Add Course</button>
        </div>
        
        <!-- Add Course Form -->
        <div class="add-course-form">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-plus-circle"></i> Add New Course</div>
            </div>
            <form>
                <div class="form-section-title">Course Information</div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="courseName">Course Name</label>
                        <input type="text" id="courseName" placeholder="Enter course name">
                    </div>
                    <div class="form-group">
                        <label for="courseCode">Course Code</label>
                        <input type="text" id="courseCode" placeholder="Enter course code">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="courseLevel">Level</label>
                        <select id="courseLevel">
                            <option value="">Select level</option>
                            <option value="certificate">Certificate</option>
                            <option value="diploma">Diploma</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="courseDepartment">Department</label>
                        <select id="courseDepartment">
                            <option value="">Select department</option>
                            <option value="it">Information Technology</option>
                            <option value="business">Business</option>
                            <option value="design">Design</option>
                            <option value="marketing">Marketing</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="courseDuration">Duration (Years)</label>
                        <select id="courseDuration">
                            <option value="1">1 Year</option>
                            <option value="2" selected>2 Years</option>
                            <option value="3">3 Years</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="courseCapacity">Maximum Capacity</label>
                        <input type="number" id="courseCapacity" placeholder="Enter capacity">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="courseLeadInstructor">Faculty leader</label>
                        <select id="courseLeadInstructor">
                            <option value="">Select Faculty leader</option>
                            <option value="1">Dr. John Smith</option>
                            <option value="2">Sarah Johnson</option>
                            <option value="3">Mark Cooper</option>
                            <option value="4">Lisa Taylor</option>
                            <option value="5">Robert Williams</option>
                            <option value="6">Dr. James Wilson</option>
                            <option value="7">Dr. Emily Chen</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="courseStatus">Status</label>
                        <select id="courseStatus">
                            <option value="active">Active</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="courseDescription">Description</label>
                        <textarea id="courseDescription" placeholder="Enter course description"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="courseFee">Course Fee (UGX)</label>
                        <input type="number" id="courseFee" placeholder="Enter course fee">
                    </div>
                    <div class="form-group">
                        <label for="courseStartDate">Start Date</label>
                        <input type="date" id="courseStartDate">
                    </div>
                </div>

                <!-- Course Units Section -->
                <div class="form-section-title">Course Units</div>
                <div class="course-units-container">
                    <div class="semester-tabs">
                        <div class="semester-tab active" data-semester="semester1year1">Year 1, Semester 1</div>
                        <div class="semester-tab" data-semester="semester2year1">Year 1, Semester 2</div>
                        <div class="semester-tab" data-semester="semester1year2">Year 2, Semester 1</div>
                        <div class="semester-tab" data-semester="semester2year2">Year 2, Semester 2</div>
                    </div>

                    <!-- Year 1, Semester 1 -->
                    <div id="semester1year1" class="semester-content active">
                        <div class="unit-container" id="units-semester1year1">
                            <div class="unit-row">
                                <div class="unit-input">
                                    <label>Unit Name</label>
                                    <input type="text" placeholder="Enter unit name">
                                </div>
                                <div class="unit-input">
                                    <label>Unit Code</label>
                                    <input type="text" placeholder="Enter unit code">
                                </div>
                                <div class="instructor-select">
                                    <label>Instructor</label>
                                    <select>
                                        <option value="">Select instructor</option>
                                        <option value="1">Dr. John Smith</option>
                                        <option value="2">Sarah Johnson</option>
                                        <option value="3">Mark Cooper</option>
                                        <option value="4">Lisa Taylor</option>
                                        <option value="5">Robert Williams</option>
                                        <option value="6">Dr. James Wilson</option>
                                        <option value="7">Dr. Emily Chen</option>
                                    </select>
                                </div>
                                <div class="unit-credits">
                                    <label>Credits</label>
                                    <input type="number" min="1" max="3" value="3">
                                </div>
                                <div class="unit-action">
                                    <button type="button" class="btn-remove-unit"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-add-unit" data-semester="semester1year1">
                            <i class="fas fa-plus"></i> Add Another Unit
                        </button>
                    </div>

                    <!-- Year 1, Semester 2 -->
                    <div id="semester2year1" class="semester-content">
                        <div class="unit-container" id="units-semester2year1">
                            <div class="unit-row">
                                <div class="unit-input">
                                    <label>Unit Name</label>
                                    <input type="text" placeholder="Enter unit name">
                                </div>
                                <div class="unit-input">
                                    <label>Unit Code</label>
                                    <input type="text" placeholder="Enter unit code">
                                </div>
                                <div class="instructor-select">
                                    <label>Instructor</label>
                                    <select>
                                        <option value="">Select instructor</option>
                                        <option value="1">Dr. John Smith</option>
                                        <option value="2">Sarah Johnson</option>
                                        <option value="3">Mark Cooper</option>
                                        <option value="4">Lisa Taylor</option>
                                        <option value="5">Robert Williams</option>
                                        <option value="6">Dr. James Wilson</option>
                                        <option value="7">Dr. Emily Chen</option>
                                    </select>
                                </div>
                                <div class="unit-credits">
                                    <label>Credits</label>
                                    <input type="number" min="1" max="3" value="3">
                                </div>
                                <div class="unit-action">
                                    <button type="button" class="btn-remove-unit"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-add-unit" data-semester="semester2year1">
                            <i class="fas fa-plus"></i> Add Another Unit
                        </button>
                    </div>

                    <!-- Year 2, Semester 1 -->
                    <div id="semester1year2" class="semester-content">
                        <div class="unit-container" id="units-semester1year2">
                            <div class="unit-row">
                                <div class="unit-input">
                                    <label>Unit Name</label>
                                    <input type="text" placeholder="Enter unit name">
                                </div>
                                <div class="unit-input">
                                    <label>Unit Code</label>
                                    <input type="text" placeholder="Enter unit code">
                                </div>
                                <div class="instructor-select">
                                    <label>Instructor</label>
                                    <select>
                                        <option value="">Select instructor</option>
                                        <option value="1">Dr. John Smith</option>
                                        <option value="2">Sarah Johnson</option>
                                        <option value="3">Mark Cooper</option>
                                        <option value="4">Lisa Taylor</option>
                                        <option value="5">Robert Williams</option>
                                        <option value="6">Dr. James Wilson</option>
                                        <option value="7">Dr. Emily Chen</option>
                                    </select>
                                </div>
                                <div class="unit-credits">
                                    <label>Credits</label>
                                    <input type="number" min="1" max="3" value="3">
                                </div>
                                <div class="unit-action">
                                    <button type="button" class="btn-remove-unit"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-add-unit" data-semester="semester1year2">
                            <i class="fas fa-plus"></i> Add Another Unit
                        </button>
                    </div>

                    <!-- Year 2, Semester 2 -->
                    <div id="semester2year2" class="semester-content">
                        <div class="unit-container" id="units-semester2year2">
                            <div class="unit-row">
                                <div class="unit-input">
                                    <label>Unit Name</label>
                                    <input type="text" placeholder="Enter unit name">
                                </div>
                                <div class="unit-input">
                                    <label>Unit Code</label>
                                    <input type="text" placeholder="Enter unit code">
                                </div>
                                <div class="instructor-select">
                                    <label>Instructor</label>
                                    <select>
                                        <option value="">Select instructor</option>
                                        <option value="1">Dr. John Smith</option>
                                        <option value="2">Sarah Johnson</option>
                                        <option value="3">Mark Cooper</option>
                                        <option value="4">Lisa Taylor</option>
                                        <option value="5">Robert Williams</option>
                                        <option value="6">Dr. James Wilson</option>
                                        <option value="7">Dr. Emily Chen</option>
                                    </select>
                                </div>
                                <div class="unit-credits">
                                    <label>Credits</label>
                                    <input type="number" min="1" max="3" value="3">
                                </div>
                                <div class="unit-action">
                                    <button type="button" class="btn-remove-unit"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-add-unit" data-semester="semester2year2">
                            <i class="fas fa-plus"></i> Add Another Unit
                        </button>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-submit">Add Course</button>
                </div>
            </form>
        </div>

        <div class="footer">
            <p>&copy; 2025 Monaco Institute. All rights reserved.</p>
        </div>
    </div>

    <script>
        // DOM Elements
        const DOM = {
            // Form elements
            form: document.querySelector('.add-course-form form'),
            courseNameInput: document.getElementById('courseName'),
            courseCodeInput: document.getElementById('courseCode'),
            courseLevelSelect: document.getElementById('courseLevel'),
            courseDepartmentSelect: document.getElementById('courseDepartment'),
            courseDurationSelect: document.getElementById('courseDuration'),
            courseCapacityInput: document.getElementById('courseCapacity'),
            courseLeadInstructorSelect: document.getElementById('courseLeadInstructor'),
            courseStatusSelect: document.getElementById('courseStatus'),
            courseDescriptionTextarea: document.getElementById('courseDescription'),
            courseFeeInput: document.getElementById('courseFee'),
            courseStartDateInput: document.getElementById('courseStartDate'),
            
            // Tab elements
            courseTabs: document.querySelectorAll('.course-tab'),
            semesterTabs: document.querySelectorAll('.semester-tab'),
            semesterContents: document.querySelectorAll('.semester-content'),
            
            // Buttons
            addUnitButtons: document.querySelectorAll('.btn-add-unit'),
            removeUnitButtons: document.querySelectorAll('.btn-remove-unit'),
            cancelButton: document.querySelector('.btn-cancel'),
            submitButton: document.querySelector('.btn-submit'),
            addCourseButton: document.querySelector('.add-button'),
            
            // Containers
            unitContainers: {
                semester1year1: document.getElementById('units-semester1year1'),
                semester2year1: document.getElementById('units-semester2year1'),
                semester1year2: document.getElementById('units-semester1year2'),
                semester2year2: document.getElementById('units-semester2year2')
            }
        };

        // Add event listeners for remove unit buttons
        function setupRemoveUnitButtons() {
            document.querySelectorAll('.btn-remove-unit').forEach(button => {
                button.addEventListener('click', function() {
                    const unitRow = this.closest('.unit-row');
                    const unitContainer = unitRow.closest('.unit-container');
                    
                    if (unitContainer.querySelectorAll('.unit-row').length > 1) {
                        unitRow.remove();
                    } else {
                        alert('At least one unit is required per semester.');
                    }
                });
            });
        }

        // Initialize event listeners
        function initEventListeners() {
            // Add new unit functionality
            DOM.addUnitButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const semesterId = this.getAttribute('data-semester');
                    const unitContainer = document.getElementById('units-' + semesterId);
                    const newUnitRow = unitContainer.querySelector('.unit-row').cloneNode(true);
                    
                    // Clear inputs in the new row
                    newUnitRow.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                    newUnitRow.querySelectorAll('input[type="number"]').forEach(input => input.value = '3');
                    newUnitRow.querySelector('select').selectedIndex = 0;
                    
                    // Add event listener to the new remove button
                    newUnitRow.querySelector('.btn-remove-unit').addEventListener('click', function() {
                        if (unitContainer.querySelectorAll('.unit-row').length > 1) {
                            this.closest('.unit-row').remove();
                        } else {
                            alert('At least one unit is required per semester.');
                        }
                    });
                    
                    unitContainer.appendChild(newUnitRow);
                });
            });
            
            // Semester tab switching
            DOM.semesterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const semesterId = this.getAttribute('data-semester');
                    
                    // Update active tab
                    DOM.semesterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Update visible content
                    DOM.semesterContents.forEach(content => {
                        content.classList.remove('active');
                    });
                    document.getElementById(semesterId).classList.add('active');
                });
            });
            
            // Course tabs switching
            if (DOM.courseTabs.length > 0) {
                DOM.courseTabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        const viewType = this.getAttribute('data-view');
                        
                        // Update active tab
                        DOM.courseTabs.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Here you would typically show/hide different views
                        // For demo purposes, we're just showing/hiding the form
                        if (viewType === 'add') {
                            document.querySelector('.add-course-form').style.display = 'block';
                        } else {
                            document.querySelector('.add-course-form').style.display = 'none';
                        }
                    });
                });
            }
            
            // Add button in course actions
            if (DOM.addCourseButton) {
                DOM.addCourseButton.addEventListener('click', function() {
                    // Show the add course form
                    document.querySelector('.add-course-form').style.display = 'block';
                    
                    // Update tabs if they exist
                    if (DOM.courseTabs.length > 0) {
                        DOM.courseTabs.forEach(tab => tab.classList.remove('active'));
                        const addTab = Array.from(DOM.courseTabs).find(tab => tab.getAttribute('data-view') === 'add');
                        if (addTab) addTab.classList.add('active');
                    }
                });
            }
            
            // Cancel button handler
            DOM.cancelButton.addEventListener('click', function() {
                // Reset the form
                DOM.form.reset();
                
                // Reset all unit inputs except the first one in each semester
                Object.values(DOM.unitContainers).forEach(container => {
                    const unitRows = container.querySelectorAll('.unit-row');
                    
                    // Keep the first row, remove the rest
                    for (let i = 1; i < unitRows.length; i++) {
                        unitRows[i].remove();
                    }
                    
                    // Reset the inputs in the first row
                    const firstRow = unitRows[0];
                    firstRow.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                    firstRow.querySelectorAll('input[type="number"]').forEach(input => input.value = '3');
                    firstRow.querySelector('select').selectedIndex = 0;
                });
                
                // Switch back to list view if tabs exist
                if (DOM.courseTabs.length > 0) {
                    const listTab = Array.from(DOM.courseTabs).find(tab => tab.getAttribute('data-view') === 'list');
                    if (listTab) listTab.click();
                } else {
                    // Just hide the form
                    document.querySelector('.add-course-form').style.display = 'none';
                }
            });
            
            // Form submission handler
            DOM.form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate required fields
                if (!validateForm()) {
                    return;
                }
                
                // Collect course data
                const courseData = collectCourseData();
                
                // Here you would typically send the data to the server
                console.log('Course data to be submitted:', courseData);
                alert('Course added successfully!');
                
                // Reset the form
                DOM.form.reset();
                
                // Reset unit rows
                resetUnitRows();
                
                // Switch back to list view if tabs exist
                if (DOM.courseTabs.length > 0) {
                    const listTab = Array.from(DOM.courseTabs).find(tab => tab.getAttribute('data-view') === 'list');
                    if (listTab) listTab.click();
                } else {
                    // Just hide the form
                    document.querySelector('.add-course-form').style.display = 'none';
                }
            });
            
            // Setup remove unit buttons
            setupRemoveUnitButtons();
            
            // Add duration change handler to show/hide appropriate semester tabs
            DOM.courseDurationSelect.addEventListener('change', function() {
                const duration = parseInt(this.value);
                adjustSemesterTabs(duration);
            });
            
            // Set default duration
            adjustSemesterTabs(parseInt(DOM.courseDurationSelect.value));
        }

        // Form validation
        function validateForm() {
            const requiredFields = [
                { element: DOM.courseNameInput, name: 'Course Name' },
                { element: DOM.courseCodeInput, name: 'Course Code' },
                { element: DOM.courseLevelSelect, name: 'Level' },
                { element: DOM.courseDepartmentSelect, name: 'Department' },
                { element: DOM.courseCapacityInput, name: 'Maximum Capacity' },
                { element: DOM.courseLeadInstructorSelect, name: 'Faculty Leader' },
                { element: DOM.courseFeeInput, name: 'Course Fee' },
                { element: DOM.courseStartDateInput, name: 'Start Date' }
            ];
            
            let isValid = true;
            let errorMessage = 'Please fill in the following required fields:';
            
            requiredFields.forEach(field => {
                if (!field.element.value.trim()) {
                    isValid = false;
                    errorMessage += '\n- ' + field.name;
                    field.element.style.borderColor = '#e74c3c';
                } 
                else if (field.element.tagName === 'SELECT' && field.element.value === '') {
                    isValid = false;
                    errorMessage += '\n- ' + field.name;
                    field.element.style.borderColor = '#e74c3c';}
                 else {
                    field.element.style.borderColor = '#ddd';
                }
            });
            
            // Validate units - at least one unit name and code required per visible semester
            const activeSemesters = getDurationBasedSemesters(parseInt(DOM.courseDurationSelect.value));
            
            activeSemesters.forEach(semesterId => {
                const container = DOM.unitContainers[semesterId];
                const unitRows = container.querySelectorAll('.unit-row');
                let hasValidUnit = false;
                
                unitRows.forEach(row => {
                    const unitName = row.querySelector('.unit-input:first-child input').value.trim();
                    const unitCode = row.querySelector('.unit-input:nth-child(2) input').value.trim();
                    
                    if (unitName && unitCode) {
                        hasValidUnit = true;
                    }
                });
                
                if (!hasValidUnit) {
                    isValid = false;
                    errorMessage += '\n- At least one unit with name and code in ' + getSemesterName(semesterId);
                }
            });
            
            if (!isValid) {
                alert(errorMessage);
            }
            
            return isValid;
        }
        
        // Collect form data
        function collectCourseData() {
            const units = {};
            const duration = parseInt(DOM.courseDurationSelect.value);
            const activeSemesters = getDurationBasedSemesters(duration);
            
            activeSemesters.forEach(semesterId => {
                units[semesterId] = [];
                const container = DOM.unitContainers[semesterId];
                const unitRows = container.querySelectorAll('.unit-row');
                
                unitRows.forEach(row => {
                    const unitName = row.querySelector('.unit-input:first-child input').value.trim();
                    const unitCode = row.querySelector('.unit-input:nth-child(2) input').value.trim();
                    const instructorId = row.querySelector('.instructor-select select').value;
                    const credits = row.querySelector('.unit-credits input').value;
                    
                    if (unitName && unitCode) {
                        units[semesterId].push({
                            name: unitName,
                            code: unitCode,
                            instructorId: instructorId,
                            credits: parseInt(credits)
                        });
                    }
                });
            });
            
            return {
                name: DOM.courseNameInput.value.trim(),
                code: DOM.courseCodeInput.value.trim(),
                level: DOM.courseLevelSelect.value,
                department: DOM.courseDepartmentSelect.value,
                duration: duration,
                capacity: parseInt(DOM.courseCapacityInput.value) || 0,
                leadInstructorId: DOM.courseLeadInstructorSelect.value,
                status: DOM.courseStatusSelect.value,
                description: DOM.courseDescriptionTextarea.value.trim(),
                fee: parseFloat(DOM.courseFeeInput.value) || 0,
                startDate: DOM.courseStartDateInput.value,
                units: units
            };
        }
        
        // Reset unit rows to initial state
        function resetUnitRows() {
            Object.values(DOM.unitContainers).forEach(container => {
                const unitRows = container.querySelectorAll('.unit-row');
                
                // Keep the first row, remove the rest
                for (let i = 1; i < unitRows.length; i++) {
                    unitRows[i].remove();
                }
                
                // Reset the inputs in the first row
                const firstRow = unitRows[0];
                firstRow.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                firstRow.querySelectorAll('input[type="number"]').forEach(input => input.value = '3');
                firstRow.querySelector('select').selectedIndex = 0;
            });
        }
        
        // Get active semesters based on course duration
        function getDurationBasedSemesters(duration) {
            const semesters = ['semester1year1', 'semester2year1'];
            
            if (duration >= 2) {
                semesters.push('semester1year2', 'semester2year2');
            }
            
            if (duration >= 3) {
                // For 3-year courses, you would add more semesters here
                // semesters.push('semester1year3', 'semester2year3');
            }
            
            return semesters;
        }
        
        // Get readable semester name
        function getSemesterName(semesterId) {
            const names = {
                'semester1year1': 'Year 1, Semester 1',
                'semester2year1': 'Year 1, Semester 2',
                'semester1year2': 'Year 2, Semester 1',
                'semester2year2': 'Year 2, Semester 2'
            };
            
            return names[semesterId] || semesterId;
        }
        
        // Adjust visible semester tabs based on duration
        function adjustSemesterTabs(duration) {
            const allSemesters = ['semester1year1', 'semester2year1', 'semester1year2', 'semester2year2'];
            const activeSemesters = getDurationBasedSemesters(duration);
            
            // Show/hide semester tabs
            DOM.semesterTabs.forEach(tab => {
                const semesterId = tab.getAttribute('data-semester');
                if (activeSemesters.includes(semesterId)) {
                    tab.style.display = 'block';
                } else {
                    tab.style.display = 'none';
                }
            });
            
            // Show/hide semester content
            DOM.semesterContents.forEach(content => {
                if (activeSemesters.includes(content.id)) {
                    content.style.display = 'none'; // Will be shown if active
                } else {
                    content.style.display = 'none';
                }
            });
            
            // Ensure an active semester is visible
            const visibleTabs = Array.from(DOM.semesterTabs).filter(tab => 
                activeSemesters.includes(tab.getAttribute('data-semester')));
            
            if (visibleTabs.length > 0) {
                // Find currently active tab
                const activeTab = Array.from(DOM.semesterTabs).find(tab => 
                    tab.classList.contains('active') && 
                    activeSemesters.includes(tab.getAttribute('data-semester')));
                
                // If no active tab is visible, activate the first visible tab
                if (!activeTab) {
                    DOM.semesterTabs.forEach(tab => tab.classList.remove('active'));
                    visibleTabs[0].classList.add('active');
                    
                    // Show corresponding content
                    const semesterId = visibleTabs[0].getAttribute('data-semester');
                    DOM.semesterContents.forEach(content => content.classList.remove('active'));
                    document.getElementById(semesterId).classList.add('active');
                }
            }
        }
        
        // Initialize the application
        function init() {
            initEventListeners();
            
            // Set today's date as the minimum date for start date
            const today = new Date();
            const yyyy = today.getFullYear();
            let mm = today.getMonth() + 1;
            let dd = today.getDate();
            
            if (mm < 10) mm = '0' + mm;
            if (dd < 10) dd = '0' + dd;
            
            const formattedDate = yyyy + '-' + mm + '-' + dd;
            DOM.courseStartDateInput.min = formattedDate;
        }
        
        // Run initialization when DOM is fully loaded
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>