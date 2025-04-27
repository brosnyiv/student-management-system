<?php

session_start(); // Start the session
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'dbconnect.php'; // Include the database connection file

$current_user_id = $_SESSION['user_id'];
$active_conversation = isset($_GET['conversation']) ? intval($_GET['conversation']) : null;

// Fetch all conversations for the current user
$conversations_query = "
    SELECT DISTINCT 
        IF(m.sender_id = ?, m.receiver_id, m.sender_id) as contact_id,
        u.username as contact_name,
        '' as contact_department,  /* Replace with actual column name if you have it */
        (SELECT message_content FROM messages 
         WHERE (sender_id = ? AND receiver_id = contact_id) 
            OR (sender_id = contact_id AND receiver_id = ?)
         ORDER BY created_at DESC LIMIT 1) as last_message,
        (SELECT created_at FROM messages 
         WHERE (sender_id = ? AND receiver_id = contact_id) 
            OR (sender_id = contact_id AND receiver_id = ?)
         ORDER BY created_at DESC LIMIT 1) as last_message_time,
        (SELECT COUNT(*) FROM messages 
         WHERE sender_id = contact_id AND receiver_id = ? AND is_read = FALSE) as unread_count
    FROM messages m
    JOIN users u ON IF(m.sender_id = ?, m.receiver_id, m.sender_id) = u.user_id
    WHERE m.sender_id = ? OR m.receiver_id = ?
    ORDER BY last_message_time DESC";


$stmt = $conn->prepare($conversations_query);
$stmt->bind_param("iiiiiiiii", $current_user_id, $current_user_id, $current_user_id, 
                  $current_user_id, $current_user_id, $current_user_id, 
                  $current_user_id, $current_user_id, $current_user_id);
$stmt->execute();
$conversations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// If there's an active conversation, fetch messages
$messages = [];
$contact_info = null;
if ($active_conversation) {
    // Mark messages as read
    $mark_read = "UPDATE messages SET is_read = TRUE 
                  WHERE sender_id = ? AND receiver_id = ? AND is_read = FALSE";
    $stmt = $conn->prepare($mark_read);
    $stmt->bind_param("ii", $active_conversation, $current_user_id);
    $stmt->execute();
    
    // Get contact info
$contact_query = "SELECT user_id, username
                 FROM users WHERE user_id = ?";

    
    // Get messages
    $messages_query = "SELECT m.*, 
                            ma.attachment_id, ma.file_path, ma.file_name, ma.file_type
                      FROM messages m
                      LEFT JOIN message_attachments ma ON m.message_id = ma.message_id
                      WHERE (m.sender_id = ? AND m.receiver_id = ?) 
                         OR (m.sender_id = ? AND m.receiver_id = ?)
                      ORDER BY m.created_at ASC";
    $stmt = $conn->prepare($messages_query);
    $stmt->bind_param("iiii", $current_user_id, $active_conversation, 
                     $active_conversation, $current_user_id);
    $stmt->execute();
    $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fetch broadcast messages
$broadcasts_query = "SELECT bm.broadcast_id, bm.title, bm.recipient_group, 
                            bm.sent_at, bm.read_count, bm.total_recipients
                     FROM broadcast_messages bm
                     ORDER BY bm.sent_at DESC 
                     LIMIT 10";
                     
$broadcasts_result = $conn->query($broadcasts_query);
$broadcasts = [];

if ($broadcasts_result) {
    $broadcasts = $broadcasts_result->fetch_all(MYSQLI_ASSOC);
}

// Handle new message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $receiver_id = $_POST['receiver_id'];
    $message_content = $_POST['message_content'];
    
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message_content) 
                           VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $current_user_id, $receiver_id, $message_content);
    
    if ($stmt->execute()) {
        $message_id = $stmt->insert_id;
        
        // Handle file attachment if present
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
            $file_name = $_FILES['attachment']['name'];
            $file_type = $_FILES['attachment']['type'];
            $file_size = $_FILES['attachment']['size'];
            
            // Create directory if doesn't exist
            $upload_dir = "uploads/messages/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_path = $upload_dir . uniqid() . '_' . $file_name;
            
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $file_path)) {
                $stmt = $conn->prepare("INSERT INTO message_attachments 
                                      (message_id, file_path, file_name, file_type, file_size) 
                                      VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("isssi", $message_id, $file_path, $file_name, $file_type, $file_size);
                $stmt->execute();
            }
        }
        
        // Redirect to avoid form resubmission
        header("Location: messages.php?conversation=" . $receiver_id);
        exit();
    }

}

// Fetch broadcast messages
$broadcasts_query = "SELECT bm.*, u.username as sender_name 
                    FROM broadcast_messages bm
                    JOIN users u ON bm.sender_id = u.user_id
                    ORDER BY bm.sent_at DESC 
                    LIMIT 10";
$broadcasts = $conn->query($broadcasts_query)->fetch_all(MYSQLI_ASSOC);

// Function to format date/time
function formatDateTime($datetime) {
    $now = new DateTime();
    $date = new DateTime($datetime);
    $diff = $now->diff($date);
    
    if ($diff->d == 0) {
        return $date->format('h:i A');
    } elseif ($diff->d == 1) {
        return 'Yesterday';
    } else {
        return $date->format('M d');
    }
}

// Function to get first letter of name for avatar
function getInitial($name) {
    return strtoupper(substr($name, 0, 1));
}

// Handle broadcast deletion
if (isset($_GET['delete_broadcast']) && is_numeric($_GET['delete_broadcast'])) {
    $broadcast_id = $_GET['delete_broadcast'];
    
    // First delete recipients
    $delete_recipients = "DELETE FROM broadcast_recipients WHERE broadcast_id = ?";
    $stmt = $conn->prepare($delete_recipients);
    $stmt->bind_param("i", $broadcast_id);
    $stmt->execute();
    
    // Then delete the broadcast
    $delete_broadcast = "DELETE FROM broadcast_messages WHERE broadcast_id = ?";
    $stmt = $conn->prepare($delete_broadcast);
    $stmt->bind_param("i", $broadcast_id);
    $stmt->execute();
    
    // Redirect to refresh the page
    header("Location: messages.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Messages</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Message Page Specific Styles */
        .message-page-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .contacts-panel {
            flex: 1;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 350px;
        }
        
        .contacts-header {
            padding: 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .contacts-header h3 {
            margin: 0;
            color: #333;
            font-size: 16px;
        }
        
        .contacts-search {
            padding: 10px 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .contacts-search input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .contacts-list {
            height: 500px;
            overflow-y: auto;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .contact-item:hover {
            background-color: #f8f9fa;
        }
        
        .contact-item.active {
            background-color: #f0f0f0;
            border-left: 3px solid #8B1818;
        }
        
        .contact-avatar {
            width: 40px;
            height: 40px;
            background-color: #8B1818;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .contact-info {
            flex-grow: 1;
            min-width: 0;
        }
        
        .contact-name {
            font-weight: bold;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .contact-preview {
            font-size: 12px;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .contact-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-left: 10px;
        }
        
        .contact-time {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .message-count {
            background-color: #8B1818;
            color: white;
            font-size: 11px;
            min-width: 18px;
            height: 18px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
        }
        
        .chat-panel {
            flex: 2;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .chat-header {
            padding: 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .chat-recipient {
            display: flex;
            align-items: center;
        }
        
        .recipient-avatar {
            width: 40px;
            height: 40px;
            background-color: #8B1818;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }
        
        .recipient-info h3 {
            margin: 0;
            font-size: 16px;
        }
        
        .recipient-info p {
            margin: 0;
            font-size: 12px;
            color: #6c757d;
        }
        
        .chat-actions {
            display: flex;
            gap: 10px;
        }
        
        .chat-actions button {
            background: none;
            border: none;
            font-size: 16px;
            color: #6c757d;
            cursor: pointer;
        }
        
        .chat-actions button:hover {
            color: #8B1818;
        }
        
        .chat-messages {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            height: 400px;
            background-color: #f9f9f9;
        }
        
        .message {
            margin-bottom: 15px;
            max-width: 70%;
        }
        
        .message-sent {
            margin-left: auto;
        }
        
        .message-received {
            margin-right: auto;
        }
        
        .message-bubble {
            padding: 10px 15px;
            border-radius: 18px;
            position: relative;
        }
        
        .message-sent .message-bubble {
            background-color: #8B1818;
            color: white;
            border-bottom-right-radius: 5px;
        }
        
        .message-received .message-bubble {
            background-color: #e9e9e9;
            color: #333;
            border-bottom-left-radius: 5px;
        }
        
        .message-time {
            font-size: 11px;
            margin-top: 5px;
            text-align: right;
            color: #6c757d;
        }
        
        .message-sent .message-time {
            color: #ddd;
        }
        
        .chat-input {
            padding: 15px;
            border-top: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        
        .chat-input textarea {
            flex-grow: 1;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            border-radius: 20px;
            resize: none;
            height: 40px;
            font-family: inherit;
            margin-right: 10px;
        }
        
        .chat-input button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #8B1818;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .chat-input .attachment-btn {
            background: none;
            border: none;
            color: #6c757d;
            margin-right: 10px;
            cursor: pointer;
        }
        
        .message-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .filter-option {
            padding: 6px 12px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .filter-option.active {
            background-color: #8B1818;
            color: white;
            border-color: #8B1818;
        }
        
        .message-date-divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        
        .message-date-divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background-color: #e9ecef;
            z-index: 1;
        }
        
        .message-date-divider span {
            background-color: #f9f9f9;
            padding: 0 10px;
            font-size: 12px;
            color: #6c757d;
            position: relative;
            z-index: 2;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .message-page-container {
                flex-direction: column;
            }
            
            .contacts-panel {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <svg viewBox="0 0 24 24" width="50" height="50">
                    <path fill="#8B1818" d="M12,2L1,8l11,6l9-4.91V17c0,0.55,0.45,1,1,1s1-0.45,1-1V7L12,2z M17,15l-5,3l-5-3V9l5-3l0,0l5,3V15z"/>
                </svg>
            </div>
            <div class="institute-name">MONACO INSTITUTE</div>
            <div class="institute-motto">Empowering Professional Skills</div>
            <button class="support-button"><i class="fas fa-headset"></i> Support</button>
        </div>
        <ul class="sidebar-menu">
            <li  onclick="window.location.href='dash.php'"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></li>
            <li onclick="window.location.href='student.php'"><i class="fas fa-user-graduate"></i> <span>Student Management</span></li>
            <li onclick="window.location.href='staff.php'"><i class="fas fa-user-tie"></i> <span>Staff Management</span></li>
            <li onclick="window.location.href='courses.php'"><i class="fas fa-book"></i> <span>Courses</span></li>
            <li onclick="window.location.href='payments.php'"><i class="fas fa-money-bill-wave"></i> <span>Payments Info</span></li>
            <li onclick="window.location.href='marks&exams.php'"><i class="fas fa-file-alt"></i> <span>Marks & Exams</span></li>
            <li onclick="window.location.href='results.php'"><i class="fas fa-search"></i> <span>Result</span></li>
            <li onclick="window.location.href='notices.php'"><i class="fas fa-bullhorn"></i> <span>Notice</span></li>
            <li onclick="window.location.href='attendence.php'"><i class="fas fa-clipboard-list"></i> <span>Attendance</span></li>
            <li onclick="window.location.href='classes.php'"><i class="fas fa-chalkboard-teacher"></i> <span>Classes</span></li>
            <li  class="active" onclick="window.location.href='messages.php'"><i class="fas fa-envelope"></i> <span>Messages</span></li>
            <li onclick="window.location.href='settings page.php'"><i class="fas fa-cog"></i> <span>Settings</span></li>
            <li ><i class="fas fa-sign-out-alt"></i> <span>Logout</span></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>MONACO INSTITUTE</h1>
                <p>Messages & Communication</p>
                <div class="date-display">
                    <i class="fas fa-calendar-alt"></i> <span id="currentDate"></span>
                    <span class="time-display"><i class="fas fa-clock"></i> <span id="currentTime"></span></span>
                    <div class="weather-widget">
                        <i class="fas fa-sun weather-icon"></i>
                        <span class="temperature">26°C</span>
                    </div>
                </div>
            </div>
            <div class="user-section" style="display:flex; align-items:center;">
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                    <span class="notification-count">3</span>
                </div>
                <div class="user-profile">
                    <div class="user-avatar">J</div>
                    <div class="user-info">
                        John Doe<br>
                        <span class="role">Admin</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Search..." aria-label="Search">
        </div>

        <div class="message-filters">
            <div class="filter-option active">All Messages</div>
            <div class="filter-option">Staff</div>
            <div class="filter-option">Students</div>
            <div class="filter-option">Unread</div>
            <div class="filter-option">Starred</div>
        </div>


        <div class="message-page-container">
            <div class="contacts-panel">
                <div class="contacts-header">
                    <h3><i class="fas fa-comments"></i> Conversations</h3>
                    <button style="background:none; border:none; cursor:pointer;"><i class="fas fa-edit"></i></button>
                </div>
                <div class="contacts-search">
                    <input type="text" placeholder="Search contacts...">
                </div>
                
                            
                                
                                
                               
                            <div class="contacts-list">
    <?php if (!empty($conversations)): ?>
        <?php foreach ($conversations as $convo): ?>
            <div class="contact-item <?php echo ($active_conversation == $convo['contact_id']) ? 'active' : ''; ?>" 
                 onclick="window.location.href='messages.php?conversation=<?php echo $convo['contact_id']; ?>'">
                <div class="contact-avatar"><?php echo substr($convo['contact_name'], 0, 1); ?></div>
                <div class="contact-info">
                    <div class="contact-name"><?php echo htmlspecialchars($convo['contact_name']); ?></div>
                    <div class="contact-preview">
                        <?php 
                            echo htmlspecialchars(substr($convo['last_message'] ?? '', 0, 40));
                            echo (strlen($convo['last_message'] ?? '') > 40) ? '...' : '';
                        ?>
                    </div>
                </div>
                <div class="contact-meta">
                    <div class="contact-time">
                        <?php 
                            if (isset($convo['last_message_time'])) {
                                $date = new DateTime($convo['last_message_time']);
                                $now = new DateTime();
                                $diff = $date->diff($now);
                                
                                if ($diff->days == 0) {
                                    echo $date->format('h:i A');
                                } elseif ($diff->days == 1) {
                                    echo 'Yesterday';
                                } else {
                                    echo $date->format('M d');
                                }
                            }
                        ?>
                    </div>
                    <?php if ($convo['unread_count'] > 0): ?>
                        <div class="message-count"><?php echo $convo['unread_count']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-conversations">No conversations yet</div>
    <?php endif; ?>
</div>

                                
                  
            </div>
            
            
            <div class="chat-panel">
                            <?php if ($contact_info): ?>
                <div class="chat-header">
                    <div class="chat-recipient">
                        <div class="recipient-avatar"><?php echo getInitial($contact_info['username']); ?></div>
                        <div class="recipient-info">
                            <h3><?php echo htmlspecialchars($contact_info['username']); ?></h3>
                            <p><?php echo htmlspecialchars($contact_info['username']); ?></p> <!-- Replace with appropriate field -->
                        </div>
                    </div>
                    <div class="chat-actions">
                        <button><i class="fas fa-phone"></i></button>
                        <button><i class="fas fa-video"></i></button>
                        <button><i class="fas fa-info-circle"></i></button>
                        <button><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                </div>
                <?php endif; ?>
                
                
                <div class="chat-messages">
    <?php
    $current_date = '';
    foreach ($messages as $message):
        $message_date = date('Y-m-d', strtotime($message['created_at']));
        if ($current_date != $message_date):
            $current_date = $message_date;
    ?>
    <div class="message-date-divider">
        <span><?php echo date('l, F j, Y', strtotime($message['created_at'])); ?></span>
    </div>
    <?php endif; ?>
    
    <div class="message <?php echo ($message['sender_id'] == $current_user_id) ? 'message-sent' : 'message-received'; ?>">
        <div class="message-bubble">
            <?php echo nl2br(htmlspecialchars($message['message_content'])); ?>
            <?php if (isset($message['attachment_id'])): ?>
            <div class="message-attachment">
                <a href="<?php echo htmlspecialchars($message['file_path']); ?>" target="_blank">
                    <i class="fas fa-paperclip"></i> <?php echo htmlspecialchars($message['file_name']); ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
        <div class="message-time"><?php echo date('h:i A', strtotime($message['created_at'])); ?></div>
    </div>
    <?php endforeach; ?>
</div>

                
                <div class="chat-input">
                    <button class="attachment-btn"><i class="fas fa-paperclip"></i></button>
                    <textarea placeholder="Type a message..."></textarea>
                    <button><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>

        <div class="section-header" style="margin-top: 20px;">
        <?php if ($contact_info): ?>
<form method="POST" enctype="multipart/form-data" class="chat-input">
    <input type="hidden" name="receiver_id" value="<?php echo $contact_info['user_id']; ?>">
    <button type="button" class="attachment-btn" onclick="document.getElementById('file-upload').click();">
        <i class="fas fa-paperclip"></i>
    </button>
    <input type="file" id="file-upload" name="attachment" style="display: none;">
    <textarea name="message_content" placeholder="Type a message..." required></textarea>
    <button type="submit" name="send_message"><i class="fas fa-paper-plane"></i></button>
</form>
<?php endif; ?>
        </div>
        <button class="section-header" style="margin-top: 20px; background-color: #8B1818; color: white; padding: 10px 20px; border-radius: 5px; border: none; cursor: pointer;" >Create Broadcast</button>
        <div class="section-header" style="margin-top: 20px;">Broadcast Messages</div>
        <table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Recipient Group</th>
            <th>Sent Date</th>
            <th>Status</th>
            <th>Delivered</th>
            <th>Read</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($broadcasts)): ?>
            <?php foreach ($broadcasts as $broadcast): ?>
                <?php 
                    // Format the recipient group text for display
                    $group_display = str_replace('_', ' ', $broadcast['recipient_group']);
                    $group_display = ucwords($group_display);
                    
                    // Determine status
                    $status = ($broadcast['read_count'] >= $broadcast['total_recipients']) ? 'Completed' : 'In Progress';
                    $status_color = ($status == 'Completed') ? '#28a745' : '#ffc107';
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($broadcast['title']); ?></td>
                    <td><?php echo htmlspecialchars($group_display); ?></td>
                    <td><?php echo date('M d, Y', strtotime($broadcast['sent_at'])); ?></td>
                    <td>
                        <span class="badge" style="background:<?php echo $status_color; ?>;color:white;padding:3px 8px;border-radius:3px;font-size:12px;">
                            <?php echo $status; ?>
                        </span>
                    </td>
                    <td><?php echo $broadcast['total_recipients'] . '/' . $broadcast['total_recipients']; ?></td>
                    <td><?php echo $broadcast['read_count'] . '/' . $broadcast['total_recipients']; ?></td>
                    <td>
                        <button class="action-icon edit" onclick="viewBroadcast(<?php echo $broadcast['broadcast_id']; ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-icon delete" onclick="deleteBroadcast(<?php echo $broadcast['broadcast_id']; ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align: center;">No broadcast messages found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

        <div class="footer">
            <p>&copy; 2025 Monaco Institute. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Display current date and time
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', dateOptions);
            
            const timeOptions = { hour: '2-digit', minute: '2-digit' };
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', timeOptions);
        }
        
        updateDateTime();
        setInterval(updateDateTime, 60000); // Update every minute

        function viewBroadcast(broadcastId) {
    // Redirect to a broadcast detail page or show in a modal
    window.location.href = 'broadcast_details.php?id=' + broadcastId;
}

function deleteBroadcast(broadcastId) {
    if (confirm('Are you sure you want to delete this broadcast message?')) {
        // You can use AJAX here or a form submission
        window.location.href = 'messages.php?delete_broadcast=' + broadcastId;
    }
}
    </script>
</body>
</html>