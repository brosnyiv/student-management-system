<?php
session_start();
ob_start();

include 'dbconnect.php';


// Fetch notices from database
$notices = [];
$sql = "SELECT * FROM notices ORDER BY post_date DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    $notices = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = "Error loading notices: " . mysqli_error($conn);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
    $content = mysqli_real_escape_string($conn, $_POST['content'] ?? '');
    $source_office = mysqli_real_escape_string($conn, $_POST['source_office'] ?? '');
    $category = mysqli_real_escape_string($conn, $_POST['category'] ?? '');
    $post_date = mysqli_real_escape_string($conn, $_POST['post_date'] ?? date('Y-m-d'));
    $expiry_date = !empty($_POST['expiry_date']) ? mysqli_real_escape_string($conn, $_POST['expiry_date']) : null;
    $is_urgent = isset($_POST['is_urgent']) ? 1 : 0;
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? 'draft');
    $created_by = $_SESSION['user_id'] ?? 1;

    $notice_id = $_POST['notice_id'] ?? null;

    try {
        if ($notice_id) {
            // Update existing notice
            $sql = "UPDATE notices SET 
                    title = '$title', 
                    content = '$content', 
                    source_office = '$source_office', 
                    category = '$category', 
                    post_date = '$post_date', 
                    expiry_date = " . ($expiry_date ? "'$expiry_date'" : "NULL") . ", 
                    is_urgent = $is_urgent, 
                    status = '$status', 
                    updated_at = NOW() 
                    WHERE notice_id = $notice_id";
        } else {
            // Insert new notice
            $sql = "INSERT INTO notices 
                    (title, content, source_office, category, post_date, expiry_date, is_urgent, status, created_by) 
                    VALUES (
                        '$title', 
                        '$content', 
                        '$source_office', 
                        '$category', 
                        '$post_date', 
                        " . ($expiry_date ? "'$expiry_date'" : "NULL") . ", 
                        $is_urgent, 
                        '$status', 
                        $created_by
                    )";
        }

        if (mysqli_query($conn, $sql)) {
            $notice_id = $notice_id ?? mysqli_insert_id($conn);

            // Handle file upload
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = "uploads/notices/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_name = time() . '_' . basename($_FILES['attachment']['name']);
                $target_file = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
                    $attach_sql = "INSERT INTO notice_attachments 
                                  (notice_id, file_name, file_path) 
                                  VALUES ($notice_id, '$file_name', '$target_file')";
                    mysqli_query($conn, $attach_sql);
                }
            }

            header("Location: notices.php?success=1");
            exit();
        } else {
            $error = "Error saving notice: " . mysqli_error($conn);
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

$success_message = '';
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_message = "Notice operation completed successfully!";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Notice Management</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Additional CSS for Notice Management Page */
        .notice-management-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .create-notice-panel {
            flex: 1;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        .notice-preview {
            flex: 1;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        .notice-preview-content {
            border: 1px dashed #ccc;
            padding: 15px;
            border-radius: 5px;
            min-height: 200px;
            background-color: #f9f9f9;
        }
        
        .form-row {
            margin-bottom: 15px;
        }
        
        .form-row label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        
        .form-row input[type="text"], 
        .form-row select, 
        .form-row textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-row textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-row.inline {
            display: flex;
            gap: 20px;
        }
        
        .form-row.inline > div {
            flex: 1;
        }
        
        .publish-options {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 15px;
        }
        
        .publish-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .submit-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .submit-buttons button {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .publish-btn {
            background-color: #8B1818;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .draft-btn {
            background-color: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
        }
        
        .preview-btn {
            background-color: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 10px;
        }
        
        .reset-btn {
            background-color: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .submit-buttons button:hover, .publish-btn:hover, .preview-btn:hover, .reset-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .file-input-container {
            position: relative;
            width: 100%;
            height: 40px;
            margin-top: 5px;
        }
        
        .file-input-container input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        
        .file-input-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 10px;
            background-color: #f5f5f5;
            border: 1px dashed #ccc;
            border-radius: 5px;
            color: #777;
        }
        
        .attachment-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .attachment-item {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            font-size: 12px;
        }
        
        .attachment-item i {
            color: #8B1818;
        }
        
        .notices-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .notices-table th {
            background-color: #f5f5f5;
            text-align: left;
            padding: 12px;
            font-weight: 500;
            color: #333;
            border-bottom: 1px solid #ddd;
        }
        
        .notices-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .notices-table tr:hover {
            background-color: #f9f9f9;
        }
        
        .notice-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
        }
        
        .badge-academic {
            background-color: #E3F2FD;
            color: #1976D2;
        }
        
        .badge-administrative {
            background-color: #E8F5E9;
            color: #388E3C;
        }
        
        .badge-event {
            background-color: #FFF8E1;
            color: #FFA000;
        }
        
        .badge-financial {
            background-color: #FFEBEE;
            color: #D32F2F;
        }
        
        .badge-general {
            background-color: #F5F5F5;
            color: #616161;
        }
        
        .badge-urgent {
            background-color: #D32F2F;
            color: white;
        }
        
        .badge-draft {
            background-color: #ECEFF1;
            color: #607D8B;
        }
        
        .badge-published {
            background-color: #E8F5E9;
            color: #388E3C;
        }
        
        .action-icons {
            display: flex;
            gap: 10px;
        }
        
        .action-icon {
            background: none;
            border: none;
            cursor: pointer;
            color: #777;
            font-size: 16px;
            transition: all 0.2s ease;
        }
        
        .action-icon:hover {
            color: #8B1818;
        }
        
        .filter-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .filter-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-item label {
            font-weight: 500;
            color: #555;
        }
        
        .filter-item select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: #8B1818;
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        
        .editor-toolbar {
            display: flex;
            gap: 5px;
            padding: 8px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-bottom: none;
            border-radius: 5px 5px 0 0;
        }
        
        .editor-toolbar button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px 8px;
            border-radius: 3px;
            color: #555;
        }
        
        .editor-toolbar button:hover {
            background-color: #e0e0e0;
        }
        
        .search-notices {
            position: relative;
            margin-bottom: 20px;
            flex: 1;
        }
        
        .search-notices input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .search-notices i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }
        
        .pagination button {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .pagination button.active {
            background-color: #8B1818;
            color: white;
            border-color: #8B1818;
        }
        
        .pagination button:hover:not(.active) {
            background-color: #f5f5f5;
        }
        
        .no-notices {
            text-align: center;
            padding: 40px;
            color: #777;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        .alert-success {
            background-color: #E8F5E9;
            color: #388E3C;
            border: 1px solid #C8E6C9;
        }
        
        .alert-error {
            background-color: #FFEBEE;
            color: #D32F2F;
            border: 1px solid #FFCDD2;
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
            <li onclick="window.location.href='dash.php'"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></li>
            <li onclick="window.location.href='student.php'"><i class="fas fa-user-graduate"></i> <span>Student Management</span></li>
            <li onclick="window.location.href='staff.php'"><i class="fas fa-user-tie"></i> <span>Staff Management</span></li>
            <li onclick="window.location.href='courses.php'"><i class="fas fa-book"></i> <span>Courses</span></li>
            <li onclick="window.location.href='payments.php'"><i class="fas fa-money-bill-wave"></i> <span>Payments Info</span></li>
            <li onclick="window.location.href='marks&exams.php'"><i class="fas fa-file-alt"></i> <span>Marks & Exams</span></li>
            <li onclick="window.location.href='results.php'"><i class="fas fa-search"></i> <span>Result</span></li>
            <li class="active" onclick="window.location.href='notices.php'"><i class="fas fa-bullhorn"></i> <span>Notice</span></li>
            <li onclick="window.location.href='attendence.php'"><i class="fas fa-clipboard-list"></i> <span>Attendance</span></li>
            <li onclick="window.location.href='classes.php'"><i class="fas fa-chalkboard-teacher"></i> <span>Classes</span></li>
            <li onclick="window.location.href='messages.php'"><i class="fas fa-envelope"></i> <span>Messages</span></li>
            <li onclick="window.location.href='settings page.php'"><i class="fas fa-cog"></i> <span>Settings</span></li>
            <li onclick="window.location.href='logout.php'"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>MONACO INSTITUTE</h1>
                <p>Welcome back, <?php echo $_SESSION['user_name'] ?? 'Admin'; ?>!</p>
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
                    <div class="user-avatar"><?php echo substr($_SESSION['user_name'] ?? 'A', 0, 1); ?></div>
                    <div class="user-info">
                        <?php echo $_SESSION['user_name'] ?? 'Admin'; ?><br>
                        <span class="role"><?php echo $_SESSION['user_role'] ?? 'Administrator'; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <div class="section-header" style="margin-bottom: 20px;">
            <div class="section-title"><i class="fas fa-bullhorn"></i> Notice Management</div>
            <div class="action-buttons">
                <button class="add-button" id="newNoticeBtn"><i class="fas fa-plus"></i> New Notice</button>
            </div>
        </div>

        <div class="notice-management-container">
            <div class="create-notice-panel">
                <h3><i class="fas fa-edit"></i> Create New Notice</h3>
                <form id="noticeForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="notice_id" id="noticeId" value="">
                    
                    <div class="form-row">
                        <label for="noticeTitle">Notice Title *</label>
                        <input type="text" id="noticeTitle" name="title" placeholder="Enter notice title" required 
                            value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="noticeContent">Notice Content *</label>
                        <div class="editor-toolbar">
                            <button type="button"><i class="fas fa-bold"></i></button>
                            <button type="button"><i class="fas fa-italic"></i></button>
                            <button type="button"><i class="fas fa-underline"></i></button>
                            <button type="button"><i class="fas fa-list-ul"></i></button>
                            <button type="button"><i class="fas fa-list-ol"></i></button>
                            <button type="button"><i class="fas fa-link"></i></button>
                            <button type="button"><i class="fas fa-image"></i></button>
                        </div>
                        <textarea id="noticeContent" name="content" placeholder="Enter notice content" required><?= 
                            isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '' 
                        ?></textarea>
                    </div>
                    
                    <div class="form-row inline">
                        <div>
                            <label for="sourceOffice">Source Office *</label>
                            <select id="sourceOffice" name="source_office" required>
                                <option value="">Select Office</option>
                                <option value="registrar">Registrar Office</option>
                                <option value="academic">Academic Affairs</option>
                                <option value="finance">Finance Department</option>
                                <option value="admissions">Admissions Office</option>
                                <option value="it">IT Department</option>
                                <option value="library">Library</option>
                                <option value="principal">Principal's Office</option>
                            </select>
                        </div>
                        <div>
                            <label for="noticeCategory">Category *</label>
                            <select id="noticeCategory" name="category" required>
                                <option value="">Select Category</option>
                                <option value="academic">Academic</option>
                                <option value="administrative">Administrative</option>
                                <option value="event">Event</option>
                                <option value="financial">Financial</option>
                                <option value="general">General</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row inline">
                        <div>
                            <label for="noticeDate">Post Date *</label>
                            <input type="date" id="noticeDate" name="post_date" required 
                                value="<?= isset($_POST['post_date']) ? $_POST['post_date'] : date('Y-m-d') ?>">
                        </div>
                        <div>
                            <label for="expiryDate">Expiry Date (optional)</label>
                            <input type="date" id="expiryDate" name="expiry_date"
                                value="<?= isset($_POST['expiry_date']) ? $_POST['expiry_date'] : '' ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <label for="noticeAttachment">Attachments (optional)</label>
                        <div class="file-input-container">
                            <input type="file" id="noticeAttachment" name="attachment">
                            <div class="file-input-placeholder">
                                <i class="fas fa-paperclip"></i> Click to attach files
                            </div>
                        </div>
                        <div class="attachment-preview"></div>
                    </div>
                    
                    <div class="form-row">
                        <div class="publish-option">
                            <input type="checkbox" id="urgentNotice" name="is_urgent" value="1"
                                <?= isset($_POST['is_urgent']) && $_POST['is_urgent'] ? 'checked' : '' ?>>
                            <label for="urgentNotice">Mark as Urgent</label>
                        </div>
                    </div>
                    
                    <div class="publish-options">
                        <div class="publish-option">
                            <input type="radio" id="publishNow" name="status" value="published" checked>
                            <label for="publishNow">Publish Now</label>
                        </div>
                        <div class="publish-option">
                            <input type="radio" id="saveDraft" name="status" value="draft"
                                <?= isset($_POST['status']) && $_POST['status'] == 'draft' ? 'checked' : '' ?>>
                            <label for="saveDraft">Save as Draft</label>
                        </div>
                    </div>
                    
                    <div class="submit-buttons" style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="button" class="preview-btn" id="previewBtn">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                        <button type="button" class="reset-btn" id="resetBtn">
                            <i class="fas fa-undo"></i> Reset Form
                        </button>
                        <button type="submit" class="publish-btn" name="submit" id="submitBtn">
                            <i class="fas fa-paper-plane"></i> Publish Notice
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="notice-preview">
                <h3><i class="fas fa-eye"></i> Notice Preview</h3>
                <p><small>This is how your notice will appear to students and staff</small></p>
                
                <div class="notice-preview-content">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <div>
                            <h3 style="margin: 0; color: #8B1818;">End of Semester Examination Schedule</h3>
                            <p style="margin: 5px 0; color: #777; font-size: 13px;">
                                <i class="fas fa-building"></i> Academic Affairs &nbsp;|&nbsp; 
                                <i class="fas fa-calendar"></i> April 15, 2025
                            </p>
                        </div>
                        <div>
                            <span class="notice-badge badge-academic">Academic</span>
                            <span class="notice-badge badge-urgent">Urgent</span>
                        </div>
                    </div>
                    
                    <div style="padding: 10px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
                        <p>Dear Students,</p>
                        <p>This is to inform all students that the End of Semester Examinations will commence on May 5, 2025. The detailed schedule has been attached to this notice.</p>
                        <p>Please ensure that you have cleared all your fee balances before the examination period as those with outstanding balances will not be allowed to sit for the exams.</p>
                        <p>Best regards,<br>Examination Office</p>
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <div class="attachment-item">
                            <i class="fas fa-file-pdf"></i> Academic_Calendar_2025.pdf
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="section-header" style="margin-bottom: 15px;">
            <div class="section-title"><i class="fas fa-list"></i> Manage Existing Notices</div>
        </div>
        
        <div class="filter-row">
            <div class="search-notices">
                <i class="fas fa-search"></i>
                <input type="text" id="searchNotices" placeholder="Search notices...">
            </div>
        </div>
        
        <div class="filter-row">
            <div class="filter-item">
                <label>Filter by Category:</label>
                <select >                   
                <option value="">All Categories</option>
                    <option value="academic">Academic</option>
                    <option value="administrative">Administrative</option>
                    <option value="event">Event</option>
                    <option value="financial">Financial</option>
                    <option value="general">General</option>
                </select>
            </div>
            
            <div class="filter-item">
                <label>Filter by Office:</label>
                <select>
                    <option value="">All Offices</option>
                    <option value="registrar">Registrar Office</option>
                    <option value="academic">Academic Affairs</option>
                    <option value="finance">Finance Department</option>
                    <option value="admissions">Admissions Office</option>
                    <option value="it">IT Department</option>
                    <option value="library">Library</option>
                    <option value="principal">Principal's Office</option>
                </select>
            </div>
            
            <div class="filter-item">
                <label>Status:</label>
                <select>
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
            
            <div class="filter-item">
                <label>Date Range:</label>
                <select>
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="quarter">Last 3 Months</option>
                </select>
            </div>
        </div>
        
        <table class="notices-table">
            <thead>
                <tr>
                    <th>Notice Title</th>
                    <th>Category</th>
                    <th>Source Office</th>
                    <th>Posted Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php if (empty($notices)): ?>
        <tr>
            <td colspan="6" class="no-notices">No notices found</td>
        </tr>
    <?php else: ?>
        <?php foreach ($notices as $notice): ?>
            <tr>
                <td><?= htmlspecialchars($notice['title']) ?></td>
                <td>
                    <span class="notice-badge badge-<?= $notice['category'] ?>">
                        <?= ucfirst($notice['category']) ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($notice['source_office']) ?></td>
                <td><?= date('M j, Y', strtotime($notice['post_date'])) ?></td>
                <td>
                    <span class="notice-badge badge-<?= $notice['status'] ?>">
                        <?= ucfirst($notice['status']) ?>
                    </span>
                    <?php if ($notice['is_urgent']): ?>
                        <span class="notice-badge badge-urgent">Urgent</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="action-icons">
                        <button class="action-icon edit-notice" data-id="<?= $notice['notice_id'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-icon view-notice" data-id="<?= $notice['notice_id'] ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-icon delete-notice" data-id="<?= $notice['notice_id'] ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
        </table>
        
        <div class="pagination">
            <button><i class="fas fa-angle-double-left"></i></button>
            <button><i class="fas fa-angle-left"></i></button>
            <button class="active">1</button>
            <button>2</button>
            <button>3</button>
            <button><i class="fas fa-angle-right"></i></button>
            <button><i class="fas fa-angle-double-right"></i></button>
        </div>

        <div class="footer">
            <p>&copy; 2025 Monaco Institute. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Display
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

// Notice form functionality
document.addEventListener('DOMContentLoaded', function() {
    // Auto-set today's date in the date field
    const today = new Date();
    const formattedDate = today.toISOString().substr(0, 10);
    document.getElementById('noticeDate').value = formattedDate;
    
    // Preview functionality
    const previewBtn = document.querySelector('.preview-btn');
    if (previewBtn) {
        previewBtn.addEventListener('click', function() {
            const title = document.getElementById('noticeTitle').value || 'Notice Title';
            const content = document.getElementById('noticeContent').value || 'Notice content will appear here.';
            const category = document.getElementById('noticeCategory').value;
            const sourceOffice = document.getElementById('sourceOffice').value;
            const isUrgent = document.getElementById('urgentNotice').checked;
            
            // Get category display name
            let categoryName = 'General';
            let categoryClass = 'badge-general';
            if (category === 'academic') {
                categoryName = 'Academic';
                categoryClass = 'badge-academic';
            } else if (category === 'administrative') {
                categoryName = 'Administrative';
                categoryClass = 'badge-administrative';
            } else if (category === 'event') {
                categoryName = 'Event';
                categoryClass = 'badge-event';
            } else if (category === 'financial') {
                categoryName = 'Financial';
                categoryClass = 'badge-financial';
            }
            
            // Get office display name
            let officeName = 'Administration';
            if (sourceOffice === 'registrar') officeName = 'Registrar Office';
            else if (sourceOffice === 'academic') officeName = 'Academic Affairs';
            else if (sourceOffice === 'finance') officeName = 'Finance Department';
            else if (sourceOffice === 'admissions') officeName = 'Admissions Office';
            else if (sourceOffice === 'it') officeName = 'IT Department';
            else if (sourceOffice === 'library') officeName = 'Library';
            else if (sourceOffice === 'principal') officeName = 'Principal\'s Office';
            
            // Format the preview HTML
            const previewContent = `
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div>
                        <h3 style="margin: 0; color: #8B1818;">${title}</h3>
                        <p style="margin: 5px 0; color: #777; font-size: 13px;">
                            <i class="fas fa-building"></i> ${officeName} &nbsp;|&nbsp; 
                            <i class="fas fa-calendar"></i> ${new Date().toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'})}
                        </p>
                    </div>
                    <div>
                        <span class="notice-badge ${categoryClass}">${categoryName}</span>
                        ${isUrgent ? '<span class="notice-badge badge-urgent">Urgent</span>' : ''}
                    </div>
                </div>
                
                <div style="padding: 10px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
                    ${content.replace(/\n/g, '<br>')}
                </div>
                
                <div style="margin-top: 15px;">
                    <div class="attachment-item">
                        <i class="fas fa-file-pdf"></i> Academic_Calendar_2025.pdf
                    </div>
                </div>
            `;
            
            document.querySelector('.notice-preview-content').innerHTML = previewContent;
        });
    }
    
    // Reset button functionality
    const resetBtn = document.querySelector('.reset-btn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            document.getElementById('noticeForm').reset();
            document.getElementById('noticeDate').value = formattedDate;
        });
    }
    
    // Form submission (prevented for demo)
    const noticeForm = document.getElementById('noticeForm');
    if (noticeForm) {
        noticeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Notice published successfully!');
        });
    }
    
    // Rich text editor basic functionality
    const editorButtons = document.querySelectorAll('.editor-toolbar button');
    const textarea = document.getElementById('noticeContent');
    
    editorButtons.forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i').className;
            let textToInsert = '';
            
            if (icon.includes('bold')) {
                textToInsert = '**Bold Text**';
            } else if (icon.includes('italic')) {
                textToInsert = '*Italic Text*';
            } else if (icon.includes('underline')) {
                textToInsert = '_Underlined Text_';
            } else if (icon.includes('list-ul')) {
                textToInsert = '\n- List item 1\n- List item 2\n- List item 3';
            } else if (icon.includes('list-ol')) {
                textToInsert = '\n1. List item 1\n2. List item 2\n3. List item 3';
            } else if (icon.includes('link')) {
                textToInsert = '[Link Text](https://example.com)';
            } else if (icon.includes('image')) {
                textToInsert = '![Image Description](image_url)';
            }
            
            // Insert at cursor position
            const startPos = textarea.selectionStart;
            const endPos = textarea.selectionEnd;
            textarea.value = textarea.value.substring(0, startPos) + textToInsert + textarea.value.substring(endPos);
            
            // Set cursor position after inserted text
            textarea.focus();
            textarea.selectionStart = startPos + textToInsert.length;
            textarea.selectionEnd = startPos + textToInsert.length;
        });
    });
    
    // File attachment preview
    const fileInput = document.getElementById('noticeAttachment');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const attachmentPreview = document.querySelector('.attachment-preview');
            attachmentPreview.innerHTML = '';
            
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                let iconClass = 'fas fa-file';
                
                if (file.type.includes('pdf')) {
                    iconClass = 'fas fa-file-pdf';
                } else if (file.type.includes('word') || file.type.includes('doc')) {
                    iconClass = 'fas fa-file-word';
                } else if (file.type.includes('image')) {
                    iconClass = 'fas fa-file-image';
                } else if (file.type.includes('excel') || file.type.includes('sheet')) {
                    iconClass = 'fas fa-file-excel';
                }
                
                const attachmentItem = document.createElement('div');
                attachmentItem.className = 'attachment-item';
                attachmentItem.innerHTML = `<i class="${iconClass}"></i> ${file.name}`;
                attachmentPreview.appendChild(attachmentItem);
            }
        });
    }
    
    // Table row actions
    const actionIcons = document.querySelectorAll('.action-icon');
    actionIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            const action = this.querySelector('i').className;
            const row = this.closest('tr');
            const noticeTitle = row.querySelector('td:first-child').textContent;
            
            if (action.includes('edit')) {
                // Load notice data into form (demo)
                document.getElementById('noticeTitle').value = noticeTitle;
                document.querySelector('.section-title').innerHTML = '<i class="fas fa-edit"></i> Edit Notice';
                document.querySelector('.publish-btn').innerHTML = '<i class="fas fa-save"></i> Update Notice';
                document.getElementById('noticeTitle').focus();
                window.scrollTo(0, 0);
            } else if (action.includes('eye')) {
                // View notice
                alert(`Viewing notice: ${noticeTitle}`);
            } else if (action.includes('trash')) {
                // Delete notice with confirmation
                if (confirm(`Are you sure you want to delete notice: "${noticeTitle}"?`)) {
                    row.style.opacity = '0.5';
                    setTimeout(() => {
                        row.remove();
                    }, 500);
                }
            }
        });
    });
    
    // New notice button
    const newNoticeBtn = document.getElementById('newNoticeBtn');
    if (newNoticeBtn) {
        newNoticeBtn.addEventListener('click', function() {
            document.getElementById('noticeForm').reset();
            document.getElementById('noticeDate').value = formattedDate;
            document.querySelector('.section-title').innerHTML = '<i class="fas fa-bullhorn"></i> Create New Notice';
            document.querySelector('.publish-btn').innerHTML = '<i class="fas fa-paper-plane"></i> Publish Notice';
            document.getElementById('noticeTitle').focus();
            window.scrollTo(0, 0);
        });
    }
});


// Edit notice
document.querySelectorAll('.edit-notice').forEach(button => {
    button.addEventListener('click', function() {
        const noticeId = this.getAttribute('data-id');
        fetch(`get_notice.php?id=${noticeId}`)
            .then(response => response.json())
            .then(data => {
                // Populate form with notice data
                document.getElementById('noticeId').value = data.notice_id;
                document.getElementById('noticeTitle').value = data.title;
                document.getElementById('noticeContent').value = data.content;
                document.getElementById('sourceOffice').value = data.source_office;
                document.getElementById('noticeCategory').value = data.category;
                document.getElementById('noticeDate').value = data.post_date;
                document.getElementById('expiryDate').value = data.expiry_date || '';
                document.getElementById('urgentNotice').checked = data.is_urgent == 1;
                
                // Set status radio
                if (data.status === 'published') {
                    document.getElementById('publishNow').checked = true;
                } else {
                    document.getElementById('saveDraft').checked = true;
                }
                
                // Change form header and submit button
                document.querySelector('.section-title').innerHTML = '<i class="fas fa-edit"></i> Edit Notice';
                document.querySelector('.publish-btn').innerHTML = '<i class="fas fa-save"></i> Update Notice';
                
                // Scroll to form
                window.scrollTo(0, 0);
            })
            .catch(error => console.error('Error:', error));
    });
});

// Delete notice
document.querySelectorAll('.delete-notice').forEach(button => {
    button.addEventListener('click', function() {
        const noticeId = this.getAttribute('data-id');
        const row = this.closest('tr');
        const noticeTitle = row.querySelector('td:first-child').textContent;
        
        if (confirm(`Are you sure you want to delete notice: "${noticeTitle}"?`)) {
            fetch(`delete_notice.php?id=${noticeId}`, { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.style.opacity = '0.5';
                        setTimeout(() => {
                            row.remove();
                        }, 500);
                    } else {
                        alert('Error deleting notice: ' + data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
});
</script>
</body>
</html>