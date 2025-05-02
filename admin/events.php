<?php
// Turn off output buffering
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();

session_start();
include 'dbconnect.php';

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch staff members for the instructor dropdown
$staff_query = "SELECT staff_id, full_name, designation 
                FROM staff ";
$staff_result = mysqli_query($conn, $staff_query);
$staff_members = [];

if ($staff_result) {
    while ($row = mysqli_fetch_assoc($staff_result)) {
        $staff_members[] = $row;
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = [
        'status' => 'error',
        'message' => 'An error occurred while processing your request.'
    ];
    
    // Validate required fields
    if (empty($_POST['eventTitle']) || empty($_POST['eventDate']) || 
        empty($_POST['startTime']) || empty($_POST['endTime']) || 
        empty($_POST['venue']) || empty($_POST['instructor'])) {
        
        $error_message = 'Please fill in all required fields.';
        
        // Continue with the page load but show error
    } else {
        try {
            // Sanitize input data
            $title = mysqli_real_escape_string($conn, $_POST['eventTitle']);
            $description = mysqli_real_escape_string($conn, isset($_POST['description']) ? $_POST['description'] : '');
            $event_date = mysqli_real_escape_string($conn, $_POST['eventDate']);
            $start_time = mysqli_real_escape_string($conn, $_POST['startTime']);
            $end_time = mysqli_real_escape_string($conn, $_POST['endTime']);
            $location = mysqli_real_escape_string($conn, $_POST['venue']);
            $organizer_id = mysqli_real_escape_string($conn, $_POST['instructor']);
            $event_type = !empty($_POST['eventCategory']) ? mysqli_real_escape_string($conn, $_POST['eventCategory']) : 'other';
            $resources = mysqli_real_escape_string($conn, isset($_POST['resources']) ? $_POST['resources'] : '');
            $max_participants = !empty($_POST['capacity']) ? intval($_POST['capacity']) : NULL;
            
            // Handle recurrence fields
            $is_recurring = isset($_POST['recurrence']) ? 1 : 0;
            $recurrence_pattern = NULL;
            $recurrence_end_date = NULL;
            
            if ($is_recurring && !empty($_POST['recurrenceType'])) {
                $recurrence_pattern = mysqli_real_escape_string($conn, $_POST['recurrenceType']);
                
                if (!empty($_POST['recurrenceEnd'])) {
                    $recurrence_end_date = mysqli_real_escape_string($conn, $_POST['recurrenceEnd']);
                }
            }
            
            // Format datetime fields
            $start_datetime = $event_date . ' ' . $start_time . ':00';
            $end_datetime = $event_date . ' ' . $end_time . ':00';
            
            // Create a new event record
            $insert_event_query = "INSERT INTO events (
                title, 
                description, 
                start_datetime, 
                end_datetime, 
                location, 
                organizer_id, 
                event_type, 
                is_recurring, 
                recurrence_pattern, 
                recurrence_end_date, 
                max_participants, 
                resources_needed,
                created_at
            ) VALUES (
                '$title', 
                '$description', 
                '$start_datetime', 
                '$end_datetime', 
                '$location', 
                '$organizer_id', 
                '$event_type', 
                $is_recurring, 
                " . ($recurrence_pattern ? "'$recurrence_pattern'" : "NULL") . ", 
                " . ($recurrence_end_date ? "'$recurrence_end_date'" : "NULL") . ", 
                " . ($max_participants ? "$max_participants" : "NULL") . ", 
                '$resources',
                NOW()
            )";
            
            if (mysqli_query($conn, $insert_event_query)) {
                $event_id = mysqli_insert_id($conn);
                
                // Process participants
                if (!empty($_POST['participants']) && is_array($_POST['participants'])) {
                    foreach ($_POST['participants'] as $participant) {
                        // Determine the type of participant and process accordingly
                        $participant = mysqli_real_escape_string($conn, $participant);
                        $participant_type = 'external';
                        
                        // Check if it's a staff member
                        if (stripos($participant, 'prof.') !== false || stripos($participant, 'mr.') !== false || 
                            stripos($participant, 'mrs.') !== false || stripos($participant, 'ms.') !== false || 
                            stripos($participant, 'dr.') !== false) {
                            
                            $participant_type = 'staff';
                        }
                        // Check if it's a student group
                        elseif (stripos($participant, 'all') !== false && (
                                stripos($participant, 'student') !== false || 
                                stripos($participant, 'class') !== false)) {
                            
                            $participant_type = 'group';
                        }
                        
                        // Insert participant record
                        $participant_query = "INSERT INTO event_participants (
                            event_id, 
                            participant_type, 
                            external_name
                        ) VALUES (
                            $event_id, 
                            '$participant_type', 
                            '$participant'
                        )";
                        
                        mysqli_query($conn, $participant_query);
                    }
                }
                
                // Set success message
                $success_message = 'Event created successfully!';
                header("Location: dash.php?event_added=1");
                exit();
                
            } else {
                // Error in SQL query
                $error_message = 'Database error: ' . mysqli_error($conn);
            }
        } catch (Exception $e) {
            $error_message = 'Exception occurred: ' . $e->getMessage();
        }
    }
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
    <!-- Your existing CSS would go here -->
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
        
        <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
        <?php endif; ?>

        <form id="addEventForm" method="POST" action="events.php">
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
                        <select id="instructor" name="instructor" required>
                            <option value="">Select an instructor</option>
                            <?php foreach ($staff_members as $staff): ?>
                                <option value="<?php echo $staff['staff_id']; ?>"><?php echo $staff['full_name']; ?> (<?php echo $staff['designation']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
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
                    <!-- Participants will be added here dynamically -->
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
                <button type="submit" class="btn btn-primary">Create Event</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Recurrence checkbox toggle
            const recurrenceCheckbox = document.getElementById('recurrence');
            const recurrenceOptions = document.querySelector('.recurrence-options');
            recurrenceOptions.style.display = 'none';
            
            recurrenceCheckbox.addEventListener('change', function() {
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
                    const participantList = document.getElementById('participantList');
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
            
            // Form validation
            const form = document.getElementById('addEventForm');
            form.addEventListener('submit', function(event) {
                const eventTitle = document.getElementById('eventTitle').value.trim();
                const eventDate = document.getElementById('eventDate').value;
                const startTime = document.getElementById('startTime').value;
                const endTime = document.getElementById('endTime').value;
                const venue = document.getElementById('venue').value.trim();
                const instructor = document.getElementById('instructor').value;
                
                if (!eventTitle || !eventDate || !startTime || !endTime || !venue || !instructor) {
                    event.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
        });
    </script>
</body>
</html>