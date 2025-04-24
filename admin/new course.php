<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - New Course</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
    display: flex;
    width: 80%;
    margin-left:10%;
    margin-right: 10%;
     padding: 20px;
    margin-top: 2%;
    margin-bottom: 2%;
    min-height: 100vh;
    background-color: #f8f9fa;
    overflow-x: hidden;
}
        /* Additional Styles for Course Unit Section */
        .course-units-container {
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #f9f9f9;
        }
        
        .semester-tab {
            display: inline-block;
            padding: 10px 15px;
            background-color: #e0e0e0;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .semester-tab.active {
            background-color: #8B1818;
            color: white;
        }
        
        .semester-content {
            display: none;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 0 5px 5px 5px;
            background-color: white;
        }
        
        .semester-content.active {
            display: block;
        }
        
        .unit-row {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        
        .unit-row:last-child {
            margin-bottom: 0;
        }
        
        .unit-input {
            flex: 2;
            margin-right: 10px;
        }
        
        .instructor-select {
            flex: 2;
            margin-right: 10px;
        }
        
        .unit-credits {
            flex: 1;
            margin-right: 10px;
        }
        
        .unit-action {
            flex: 0 0 40px;
            text-align: center;
        }
        
        .btn-add-unit {
            display: block;
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }
        
        .btn-add-unit:hover {
            background-color: #45a049;
        }
        
        .btn-remove-unit {
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }
        
        .btn-remove-unit:hover {
            background-color: #d32f2f;
        }
        
        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            margin: 20px 0 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
            color: #8B1818;
        }

        /* Main content container styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        /* Form styles */
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .form-row {
            display: flex;
            margin-bottom: 15px;
            gap: 20px;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            gap: 10px;
        }

        .btn-cancel, .btn-submit {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-cancel {
            background-color: #f2f2f2;
            color: #333;
        }

        .btn-submit {
            background-color: #8B1818;
            color: white;
        }

        .btn-submit:hover {
            background-color: #6d1414;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 24px;
            color: #333;
            font-weight: 600;
        }

        .back-button {
            padding: 8px 15px;
            background-color: #f2f2f2;
            border: none;
            border-radius: 4px;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-button:hover {
            background-color: #e0e0e0;
        }

        .back-button i {
            font-size: 16px;
        }
        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            margin: 20px 0 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
            color: #8B1818;
        }
        .form-row {
            display: flex;
            margin-bottom: 15px;
            gap: 20px;
        }
        .form-group {
            flex: 1;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        
        /* Add styles for error messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="page-header">
            <div class="page-title"><i class="fas fa-plus-circle"></i> Create New Course</div>
            <button class="back-button" onclick="window.location.href='courses.php'">
                <i class="fas fa-arrow-left"></i> Back to Courses
            </button>
        </div>  
        
        <?php
        // Display error messages if any
        if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
            echo '<div class="alert alert-danger">';
            foreach ($_SESSION['errors'] as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
            unset($_SESSION['errors']);
        }
        
        // Display success message if any
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        
        // Display error message if any
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        
        <form id="newCourseForm" action="submit_course.php" method="POST">
            <div class="form-section-title">Course Information</div>
            <div class="form-row">
                <div class="form-group">
                    <label for="courseName">Course Name</label>
                    <input type="text" id="courseName" name="courseName" placeholder="Enter course name" required>
                </div>
                <div class="form-group">
                    <label for="courseCode">Course Code</label>
                    <input type="text" id="courseCode" name="courseCode" placeholder="Enter course code" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="courseLevel">Level*</label>
                    <select id="courseLevel" name="courseLevel" required>
                        <option value="">Select level</option>
                        <option value="certificate">Certificate</option>
                        <option value="diploma">Diploma</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="courseDepartment">Department</label>
                    <select id="courseDepartment" name="courseDepartment" required>
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
                    <select id="courseDuration" name="courseDuration" required>
                        <option value="1">1 Year</option>
                        <option value="2" selected>2 Years</option>
                        <option value="3">3 Years</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="courseCapacity">Maximum Capacity</label>
                    <input type="number" id="courseCapacity" name="courseCapacity" placeholder="Enter capacity" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="courseLeadInstructor">Faculty Leader</label>
                    <select id="courseLeadInstructor" name="courseLeadInstructor" required>
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
                    <select id="courseStatus" name="courseStatus" required>
                        <option value="active">Active</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="courseDescription">Description</label>
                    <textarea id="courseDescription" name="courseDescription" placeholder="Enter course description"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="courseFee">Course Fee (UGX)</label>
                    <input type="number" id="courseFee" name="courseFee" placeholder="Enter course fee" required>
                </div>
                <div class="form-group">
                    <label for="courseStartDate">Start Date</label>
                    <input type="date" id="courseStartDate" name="courseStartDate" required>
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
                                <label>Course Unit Name</label>
                                <input type="text" placeholder="Enter unit name" required>
                            </div>
                            <div class="unit-input">
                                <label>Course Unit Code</label>
                                <input type="text" placeholder="Enter unit code" required>
                            </div>
                            <div class="instructor-select">
                                <label>Instructor</label>
                                <select required>
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
                                <input type="number"min="1" max="10" value="10" required>
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
                                <input type="text" placeholder="Enter unit name" required>
                            </div>
                            <div class="unit-input">
                                <label>Unit Code</label>
                                <input type="text" placeholder="Enter unit code" required>
                            </div>
                            <div class="instructor-select">
                                <label>Instructor</label>
                                <select required>
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
                                <input type="number" min="1" max="10" value="10" required>
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
                                <input type="text" placeholder="Enter unit name" required>
                            </div>
                            <div class="unit-input">
                                <label>Unit Code</label>
                                <input type="text" placeholder="Enter unit code" required>
                            </div>
                            <div class="instructor-select">
                                <label>Instructor</label>
                                <select required>
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
                                <input type="number" min="1" max="10" value="10" required>
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
                                <label>Unit Name*</label>
                                <input type="text" placeholder="Enter unit name" required>
                            </div>
                            <div class="unit-input">
                                <label>Unit Code*</label>
                                <input type="text" placeholder="Enter unit code" required>
                            </div>
                            <div class="instructor-select">
                                <label>Instructor*</label>
                                <select required>
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
                                <label>Credits*</label>
                                <input type="number" min="1" max="10" value="10" required>
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
                <button type="button" class="btn-cancel" onclick="window.location.href='courses.php'">Cancel</button>
                <button type="submit" class="btn-submit">Create Course</button>
            </div>
        </form>
    </div>

    <script>
        // Initialize remove unit buttons
        document.querySelectorAll('.btn-remove-unit').forEach(button => {
            button.addEventListener('click', function() {
                // Only remove if there's more than one unit in the semester
                const unitContainer = this.closest('.unit-container');
                if (unitContainer.querySelectorAll('.unit-row').length > 2) {
                    this.closest('.unit-row').remove();
                } else {
                    alert('At least one unit is required per semester.');
                }
            });
        });
        
        // Add new unit functionality
        document.querySelectorAll('.btn-add-unit').forEach(button => {
            button.addEventListener('click', function() {
                const semesterId = this.getAttribute('data-semester');
                const unitContainer = document.getElementById('units-' + semesterId);
                const newUnitRow = unitContainer.querySelector('.unit-row').cloneNode(true);
                
                // Clear inputs in the new row
                newUnitRow.querySelectorAll('input').forEach(input => {
                    if (input.type === 'number') {
                        input.value = '10';
                    } else {
                        input.value = '';
                    }
                });
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
        document.querySelectorAll('.semester-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const semesterId = this.getAttribute('data-semester');
                
                // Update active tab
                document.querySelectorAll('.semester-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Update visible content
                document.querySelectorAll('.semester-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(semesterId).classList.add('active');
            });
        });
        
        document.getElementById('newCourseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            const courseName = document.getElementById('courseName').value;
            const courseCode = document.getElementById('courseCode').value;
            const courseLevel = document.getElementById('courseLevel').value;
            
            if (!courseName || !courseCode || !courseLevel) {
                alert('Please fill in all required fields (Course Name, Code, and Level are required)');
                return;
            }
            
            // Collect all unit data from all semesters
            const unitsData = {};
            
            document.querySelectorAll('.semester-content').forEach(semesterContent => {
                const semesterId = semesterContent.id;
                const unitRows = semesterContent.querySelectorAll('.unit-row');
                
                unitsData[semesterId] = {
                    unitName: [],
                    unitCode: [],
                    instructor: [],
                    credits: []
                };
                
                unitRows.forEach(row => {
                    unitsData[semesterId].unitName.push(row.querySelector('.unit-input input[type="text"]').value);
                    unitsData[semesterId].unitCode.push(row.querySelector('.unit-input:nth-child(2) input').value);
                    unitsData[semesterId].instructor.push(row.querySelector('.instructor-select select').value);
                    unitsData[semesterId].credits.push(row.querySelector('.unit-credits input').value);
                });
            });
            
            // Create hidden input for units data
            const unitsInput = document.createElement('input');
            unitsInput.type = 'hidden';
            unitsInput.name = 'units';
            unitsInput.value = JSON.stringify(unitsData);
            this.appendChild(unitsInput);
            
            // Submit the form
            this.submit();
        });
    </script>
</body>
</html>