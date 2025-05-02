<?php
session_start();
include 'dbconnect.php'; // Include the database connection file

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Function to sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data and sanitize inputs
        $title = sanitize($_POST['eventTitle']);
        $description = isset($_POST['description']) ? sanitize($_POST['description']) : null;
        $event_date = sanitize($_POST['eventDate']);
        $start_time = sanitize($_POST['startTime']);
        $end_time = sanitize($_POST['endTime']);
        $location = sanitize($_POST['venue']);
        $event_type = isset($_POST['eventCategory']) ? sanitize($_POST['eventCategory']) : 'other';
        $instructor = sanitize($_POST['instructor']);
        
        // Convert start and end times to datetime format
        $start_datetime = $event_date . ' ' . $start_time . ':00';
        $end_datetime = $event_date . ' ' . $end_time . ':00';
        
        // Handle recurring event options
        $is_recurring = isset($_POST['recurrence']) ? 1 : 0;
        $recurrence_pattern = null;
        $recurrence_end_date = null;
        
        if ($is_recurring) {
            $recurrence_pattern = isset($_POST['recurrenceType']) ? sanitize($_POST['recurrenceType']) : null;
            $recurrence_end_date = isset($_POST['recurrenceEnd']) ? sanitize($_POST['recurrenceEnd']) : null;
        }
        
        // Handle capacity and resources
        $max_participants = !empty($_POST['capacity']) ? intval(sanitize($_POST['capacity'])) : null;
        $resources_needed = isset($_POST['resources']) ? sanitize($_POST['resources']) : null;
        
        // Get the organizer ID from the session
        $organizer_id = $_SESSION['user_id'];
        
        // Prepare SQL statement for inserting event
        $sql = "INSERT INTO events (title, description, start_datetime, end_datetime, location, organizer_id, 
                event_type, is_recurring, recurrence_pattern, recurrence_end_date, max_participants, resources_needed) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssisssiss", $title, $description, $start_datetime, $end_datetime, $location, 
                          $organizer_id, $event_type, $is_recurring, $recurrence_pattern, $recurrence_end_date, 
                          $max_participants, $resources_needed);
        
        // Execute the statement
        if ($stmt->execute()) {
            $event_id = $conn->insert_id;
            
            // Process participants if they exist
            if (isset($_POST['participants']) && is_array($_POST['participants'])) {
                foreach ($_POST['participants'] as $participant) {
                    // Determine participant type and details
                    // This is simplified - you would need to enhance based on your actual participant data structure
                    $participant_type = 'student'; // Default to student type
                    $name = $participant;
                    
                    // For staff members (detecting by title)
                    if (strpos($name, 'Prof.') !== false || strpos($name, 'Dr.') !== false) {
                        $participant_type = 'staff';
                    }
                    
                    // For groups (simplified approach)
                    if (strpos($name, 'All') !== false && strpos($name, 'Students') !== false) {
                        // This is a placeholder - you would need to implement group handling logic
                        continue;
                    }
                    
                    // Insert external participants
                    $sql_participant = "INSERT INTO event_participants (event_id, participant_type, external_name) 
                                        VALUES (?, ?, ?)";
                    $stmt_participant = $conn->prepare($sql_participant);
                    $stmt_participant->bind_param("iss", $event_id, $participant_type, $name);
                    $stmt_participant->execute();
                    $stmt_participant->close();
                }
            }
            
            // Return success response
            $response = array('status' => 'success', 'message' => 'Event created successfully!', 'event_id' => $event_id);
            echo json_encode($response);
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        // Return error response
        $response = array('status' => 'error', 'message' => 'Error creating event: ' . $e->getMessage());
        echo json_encode($response);
        exit();
    }
} else {
    // Not a POST request, redirect to the event form
    header("Location: events.php");
    exit();
}

$conn->close();
?>