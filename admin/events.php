<?php
session_start(); // Start the session
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'dbconnect.php'; // Include the database connection file

// Check if user is not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Add New Event</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            width: 80%;
            margin-left: 10%;
            margin-right: 10%;
            margin-top: 2%;
            margin-bottom: 2%;
            padding: 20px;
        }
        .nav-link {
            text-decoration: none;
            color: #8B1818;
            font-size: 16px;
            margin-right: 10px;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
        .nav-separator {
            margin: 0 10px;
            color: #8B1818;
        }
        .current-page {
            font-size: 16px;
            color: #8B1818;
        }
        .section-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        .section-title i {
            color: #8B1818;
            margin-right: 10px;
        }
        .section-subtitle {
            font-size: 18px;
            font-weight: 500;
            color: #555;
            margin-bottom: 10px;
        }
        .section-subtitle i {
            color: #8B1818;
            margin-right: 10px;
        }
        .section-description {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .section-description i {
            color: #8B1818;
            margin-right: 10px;
        }
        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .section-header i {
            color: #8B1818;
            margin-right: 10px;
        }
        .section-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .section-header p {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }
        .section-navigation {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        /* Additional styles for the add event page */
        .form-container {
            width: 100%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .form-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .form-title i {
            color: #8B1818;
            margin-right: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 5px;
        }

        .form-column {
            flex: 1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        textarea,
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus,
        textarea:focus,
        select:focus {
            border-color: #8B1818;
            outline: none;
            box-shadow: 0 0 0 2px rgba(139, 24, 24, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #8B1818;
            color: white;
        }

        .btn-primary:hover {
            background-color: #6d1313;
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #d0d0d0;
        }

        .required-field::after {
            content: '*';
            color: #8B1818;
            margin-left: 4px;
        }

        .recurrence-options {
            margin-top: 15px;
            padding-left: 25px;
            display: none;
        }

        #recurrence:checked ~ .recurrence-options {
            display: block;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .checkbox-label input {
            margin-right: 10px;
        }

        .participant-list {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            max-height: 150px;
            overflow-y: auto;
            margin-top: 10px;
        }

        .participant-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .participant-item:last-child {
            border-bottom: none;
        }

        .remove-participant {
            background: none;
            border: none;
            color: #E74C3C;
            cursor: pointer;
        }

        .add-participant-row {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .add-participant-row input {
            flex: 1;
        }

        .add-participant-row button {
            padding: 12px 15px;
            background-color: #8B1818;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .preview-section {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .preview-header {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .preview-header i {
            color: #8B1818;
            margin-right: 10px;
        }

        .event-preview {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.05);
        }

        .event-preview-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #8B1818;
        }

        .event-preview-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .event-preview-detail {
            display: flex;
            align-items: center;
        }

        .event-preview-detail i {
            margin-right: 6px;
            color: #8B1818;
        }

        .event-description {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
            border-top: 1px solid #eee;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="section-navigation">
            <a href="dash.php" class="nav-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            <span class="nav-separator">/</span>
            <span class="current-page">Add New Event</span>
        </div>
        <div class="form-title">
            <i class="fas fa-calendar-plus"></i> Create New Event
        </div>
        
        <div id="alert-container" class="hidden"></div>

        <form id="addEventForm" method="POST" action="process_event.php">
            <div class="form-group">
                <label for="eventTitle" class="required-field">Event Title</label>
                <input type="text" id="eventTitle" name="eventTitle" placeholder="Enter event title" required>
            </div>

            <div class="form-row">
                <div class="form-column">
                    <div class="form-group">
                        <label for="eventDate" class="required-field">Date</label>
                        <input type="date" id="eventDate" name="eventDate" required>
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-group">
                        <label for="eventCategory">Category</label>
                        <select id="eventCategory" name="eventCategory">
                            <option value="">Select a category</option>
                            <option value="workshop">Workshop</option>
                            <option value="seminar">Seminar</option>
                            <option value="masterclass">Master Class</option>
                            <option value="lecture">Lecture</option>
                            <option value="meeting">Meeting</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-column">
                    <div class="form-group">
                        <label for="startTime" class="required-field">Start Time</label>
                        <input type="time" id="startTime" name="startTime" required>
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-group">
                        <label for="endTime" class="required-field">End Time</label>
                        <input type="time" id="endTime" name="endTime" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-column">
                    <div class="form-group">
                        <label for="venue" class="required-field">Venue</label>
                        <input type="text" id="venue" name="venue" placeholder="Enter venue" required>
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-group">
                        <label for="instructor" class="required-field">Instructor/Speaker</label>
                        <input type="text" id="instructor" name="instructor" placeholder="Enter instructor name" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea id="description" name="description" placeholder="Enter event details, objectives, and any special instructions"></textarea>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="recurrence" name="recurrence">
                    <span>This is a recurring event</span>
                </label>
                
                <div class="recurrence-options">
                    <div class="form-row">
                        <div class="form-column">
                            <div class="form-group">
                                <label for="recurrenceType">Repeat</label>
                                <select id="recurrenceType" name="recurrenceType">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label for="recurrenceEnd">End Date</label>
                                <input type="date" id="recurrenceEnd" name="recurrenceEnd">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Participants</label>
                <div class="participant-list" id="participantList">
                    <div class="participant-item">
                        <span>All Data Science Students</span>
                        <button type="button" class="remove-participant"><i class="fas fa-times"></i></button>
                        <input type="hidden" name="participants[]" value="All Data Science Students">
                    </div>
                    <div class="participant-item">
                        <span>Prof. Anderson</span>
                        <button type="button" class="remove-participant"><i class="fas fa-times"></i></button>
                        <input type="hidden" name="participants[]" value="Prof. Anderson">
                    </div>
                </div>
                <div class="add-participant-row">
                    <input type="text" id="newParticipant" placeholder="Type participant name or group">
                    <button type="button" id="addParticipant"><i class="fas fa-plus"></i> Add</button>
                </div>
            </div>

            <div class="form-row">
                <div class="form-column">
                    <div class="form-group">
                        <label for="resources">Required Resources</label>
                        <input type="text" id="resources" name="resources" placeholder="Enter required resources">
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-group">
                        <label for="capacity">Maximum Capacity</label>
                        <input type="text" id="capacity" name="capacity" placeholder="Enter maximum number of participants">
                    </div>
                </div>
            </div>

            <div class="form-buttons">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='dash.php'">Cancel</button>
                <button type="button" class="btn btn-secondary" id="previewButton">Preview</button>
                <button type="submit" class="btn btn-primary">Create Event</button>
            </div>
        </form>
    </div>

    <div class="preview-section" id="previewSection" style="display: none;">
        <div class="preview-header">
            <i class="fas fa-eye"></i> Event Preview
        </div>
        <div class="event-preview">
            <div class="event-preview-title" id="previewTitle">Digital Technology Workshop</div>
            <div class="event-preview-details">
                <div class="event-preview-detail"><i class="fas fa-calendar"></i> <span id="previewDate">April 18, 2025</span></div>
                <div class="event-preview-detail"><i class="fas fa-clock"></i> <span id="previewTime">8:30am - 10:30am</span></div>
                <div class="event-preview-detail"><i class="fas fa-map-marker-alt"></i> <span id="previewVenue">Room B12</span></div>
                <div class="event-preview-detail"><i class="fas fa-user"></i> <span id="previewInstructor">Mr. Murage Charles</span></div>
            </div>
            <div class="event-description" id="previewDescription">
                This workshop will cover the latest trends in digital technology with hands-on practical sessions. Students will learn about emerging technologies and their applications in various industries.
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Recurrence checkbox toggle
            const recurrenceCheckbox = document.getElementById('recurrence');
            recurrenceCheckbox.addEventListener('change', function() {
                const recurrenceOptions = document.querySelector('.recurrence-options');
                if (this.checked) {
                    recurrenceOptions.style.display = 'block';
                } else {
                    recurrenceOptions.style.display = 'none';
                }
            });
            
            // Add participant functionality
            const addButton = document.getElementById('addParticipant');
            addButton.addEventListener('click', function() {
                const participantInput = document.getElementById('newParticipant');
                const participantName = participantInput.value.trim();
                
                if (participantName) {
                    const participantList = document.querySelector('.participant-list');
                    const newParticipant = document.createElement('div');
                    newParticipant.className = 'participant-item';
                    newParticipant.innerHTML = `
                        <span>${participantName}</span>
                        <button type="button" class="remove-participant"><i class="fas fa-times"></i></button>
                        <input type="hidden" name="participants[]" value="${participantName}">
                    `;
                    participantList.appendChild(newParticipant);
                    
                    // Add event listener to the new remove button
                    const removeButton = newParticipant.querySelector('.remove-participant');
                    removeButton.addEventListener('click', function() {
                        participantList.removeChild(newParticipant);
                    });
                    
                    participantInput.value = '';
                }
            });
            
            // Add event listeners to existing remove buttons
            const removeButtons = document.querySelectorAll('.remove-participant');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const participantItem = this.parentNode;
                    participantItem.parentNode.removeChild(participantItem);
                });
            });
            
            // Preview functionality
            const previewButton = document.getElementById('previewButton');
            previewButton.addEventListener('click', function() {
                const previewSection = document.getElementById('previewSection');
                
                // Update preview with form values
                document.getElementById('previewTitle').textContent = document.getElementById('eventTitle').value || 'Event Title';
                
                // Format date
                const dateValue = document.getElementById('eventDate').value;
                if (dateValue) {
                    const date = new Date(dateValue);
                    document.getElementById('previewDate').textContent = date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                }
                
                // Format time
                const startTime = document.getElementById('startTime').value;
                const endTime = document.getElementById('endTime').value;
                if (startTime && endTime) {
                    const formattedStartTime = formatTime(startTime);
                    const formattedEndTime = formatTime(endTime);
                    document.getElementById('previewTime').textContent = `${formattedStartTime} - ${formattedEndTime}`;
                }
                
                document.getElementById('previewVenue').textContent = document.getElementById('venue').value || 'Venue';
                document.getElementById('previewInstructor').textContent = document.getElementById('instructor').value || 'Instructor';
                document.getElementById('previewDescription').textContent = document.getElementById('description').value || 'No description provided.';
                
                previewSection.style.display = 'block';
                
                // Scroll to preview
                previewSection.scrollIntoView({ behavior: 'smooth' });
            });
            
            // Form submission with AJAX
            const form = document.getElementById('addEventForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                // Create form data object
                const formData = new FormData(form);
                
                // Send AJAX request
                fetch('process_event.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const alertContainer = document.getElementById('alert-container');
                    alertContainer.classList.remove('hidden');
                    
                    if (data.status === 'success') {
                        alertContainer.className = 'alert alert-success';
                        alertContainer.textContent = data.message;
                        
                        // Redirect after successful submission (after 2 seconds)
                        setTimeout(function() {
                            window.location.href = 'dash.php';
                        }, 2000);
                    } else {
                        alertContainer.className = 'alert alert-danger';
                        alertContainer.textContent = data.message;
                    }
                    
                    // Scroll to top to see the alert
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                })
                .catch(error => {
                    console.error('Error:', error);
                    const alertContainer = document.getElementById('alert-container');
                    alertContainer.classList.remove('hidden');
                    alertContainer.className = 'alert alert-danger';
                    alertContainer.textContent = 'An error occurred. Please try again.';
                    
                    // Scroll to top to see the alert
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        });
        
        // Format time from 24h to 12h format
        function formatTime(time24h) {
            const [hours, minutes] = time24h.split(':');
            let period = 'am';
            let hour = parseInt(hours);
            
            if (hour >= 12) {
                period = 'pm';
                if (hour > 12) {
                    hour -= 12;
                }
            }
            if (hour === 0) {
                hour = 12;
            }
            
            return `${hour}:${minutes}${period}`;
        }
    </script>
</body>
</html>