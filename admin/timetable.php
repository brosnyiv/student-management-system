<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Add Timetable Entry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #8B1818;
            --primary-hover: #701010;
            --text-color: #333;
            --light-bg: #f5f5f5;
            --border-color: #ddd;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }
        
        .container {
            max-width: 800px;
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
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
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
        
        .required-field::after {
            content: "*";
            color: #e74c3c;
            margin-left: 4px;
        }
        
        .form-section {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        
        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        .hint-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .repeating-schedule {
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        
        .weekday-selection {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .weekday-btn {
            background-color: #fff;
            border: 1px solid var(--border-color);
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .weekday-btn.selected {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .institute-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo-svg {
            width: 60px;
            height: 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h2><i class="fas fa-calendar-alt"></i> Add Timetable Entry</h2>
        </div>
        
        <div class="form-body">
            <div class="institute-logo">
                <svg viewBox="0 0 24 24" class="logo-svg">
                    <path fill="#8B1818" d="M12,2L1,8l11,6l9-4.91V17c0,0.55,0.45,1,1,1s1-0.45,1-1V7L12,2z M17,15l-5,3l-5-3V9l5-3l0,0l5,3V15z"/>
                </svg>
                <h3>MONACO INSTITUTE</h3>
            </div>
            
            <form id="timetableForm">
                <div class="form-section">
                    <div class="form-section-title">Class Information</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="className" class="required-field">Class Name</label>
                            <input type="text" id="className" placeholder="Enter class name" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="courseSelect" class="required-field">Course</label>
                            <select id="courseSelect" required>
                                <option value="">Select a course</option>
                                <option value="1">Computer Science</option>
                                <option value="2">Business Administration</option>
                                <option value="3">Digital Marketing</option>
                                <option value="4">Graphic Design</option>
                                <option value="5">Add new course...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="instructorSelect" class="required-field">Instructor</label>
                            <select id="instructorSelect" required>
                                <option value="">Select an instructor</option>
                                <option value="1">Dr. Smith</option>
                                <option value="2">Prof. Anderson</option>
                                <option value="3">Ms. Johnson</option>
                                <option value="4">Mr. Williams</option>
                                <option value="5">Add new instructor...</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="classDescription">Description (Optional)</label>
                        <textarea id="classDescription" rows="3" placeholder="Enter class description"></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <div class="form-section-title">Schedule Details</div>
                    
                    <div class="repeating-schedule">
                        <div class="form-group">
                            <label>Weekdays</label>
                            <div class="weekday-selection">
                                <button type="button" class="weekday-btn" data-day="monday">Monday</button>
                                <button type="button" class="weekday-btn" data-day="tuesday">Tuesday</button>
                                <button type="button" class="weekday-btn" data-day="wednesday">Wednesday</button>
                                <button type="button" class="weekday-btn" data-day="thursday">Thursday</button>
                                <button type="button" class="weekday-btn" data-day="friday">Friday</button>
                                <button type="button" class="weekday-btn" data-day="saturday">Saturday</button>
                                <button type="button" class="weekday-btn" data-day="sunday">Sunday</button>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="startTime" class="required-field">Start Time</label>
                                <input type="time" id="startTime" required>
                            </div>
                            <div class="form-group">
                                <label for="endTime" class="required-field">End Time</label>
                                <input type="time" id="endTime" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="startDate">Start Date (Optional)</label>
                            <input type="date" id="startDate">
                            <p class="hint-text">Leave blank for indefinite schedule</p>
                        </div>
                        <div class="form-group">
                            <label for="endDate">End Date (Optional)</label>
                            <input type="date" id="endDate">
                            <p class="hint-text">Leave blank for indefinite schedule</p>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <div class="form-section-title">Location & Resources</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="roomSelect" class="required-field">Room</label>
                            <select id="roomSelect" required>
                                <option value="">Select a room</option>
                                <option value="A5">Room A5</option>
                                <option value="B12">Room B12</option>
                                <option value="C3">Lab C3</option>
                                <option value="D7">Room D7</option>
                                <option value="5">Add new room...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="capacity">Expected Capacity</label>
                            <input type="number" id="capacity" placeholder="Number of students">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="resourcesNeeded">Resources Needed (Optional)</label>
                        <textarea id="resourcesNeeded" rows="2" placeholder="List any special equipment or resources needed"></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <div class="form-section-title">Additional Options</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="repeatPattern">Repeat Pattern</label>
                            <select id="repeatPattern">
                                <option value="weekly">Weekly</option>
                                <option value="biweekly">Bi-weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="once">One-time only</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="classStatus">Initial Status</label>
                            <select id="classStatus">
                                <option value="upcoming">Upcoming</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notificationSettings">Notifications</label>
                        <select id="notificationSettings">
                            <option value="default">Default (24 hours before)</option>
                            <option value="1hour">1 hour before</option>
                            <option value="3hours">3 hours before</option>
                            <option value="none">No notifications</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="button" class="btn btn-secondary" id="saveAsDraftBtn">Save as Draft</button>
                    <button type="submit" class="btn btn-primary">Add to Timetable</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle weekday selection
        const weekdayButtons = document.querySelectorAll('.weekday-btn');
        weekdayButtons.forEach(button => {
            button.addEventListener('click', () => {
                button.classList.toggle('selected');
            });
        });
        
        // Form submission handler
        document.getElementById('timetableForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get selected weekdays
            const selectedDays = [];
            document.querySelectorAll('.weekday-btn.selected').forEach(btn => {
                selectedDays.push(btn.getAttribute('data-day'));
            });
            
            // Form validation logic would go here
            if (selectedDays.length === 0) {
                alert('Please select at least one weekday');
                return;
            }
            
            // Show success message or redirect
            alert('Timetable entry added successfully!');
            // In a real application, you'd submit the form data to your backend
        });
        
        // Cancel button handler
        document.getElementById('cancelBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel? All changes will be lost.')) {
                window.location.href = 'classes.php'; // Redirect back to classes page
            }
        });
        
        // Save as draft button handler
        document.getElementById('saveAsDraftBtn').addEventListener('click', function() {
            alert('Timetable entry saved as draft!');
            // In a real application, you'd save the form data temporarily
        });
    </script>
</body>
</html>