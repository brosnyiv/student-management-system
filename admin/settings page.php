<?php
session_start();
include 'dbconnect.php';

// Check if user is not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

//pull notifications count from notifications table where the user_id is the same as the current session user_id
$user_notified=$_SESSION['user_id'];
$sql="select count(*) from notifications where user_id is not null and user_id='$user_notified' and is_read='unread' order by created_at desc";
$result=mysqli_query($conn,$sql);
if ($result) {
   $notification_count= mysqli_fetch_assoc($result)['count(*)'];
} else {
    $notifications_count = 0;
    // For debugging
    // echo "Error in student query: " . mysqli_error($conn);
}
// Fetch data from role table
$sql = "SELECT * FROM roles";
$result = mysqli_query($conn, $sql);
if ($result) {
    $roles = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($conn);
}

// Fetch data from users table with role names
$userSql = "SELECT u.*, r.role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.role_id
            ORDER BY u.created_at DESC";
$userResult = mysqli_query($conn, $userSql);
if ($userResult) {
    $users = mysqli_fetch_all($userResult, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($conn);
}

// Process form submission for adding a new user
if (isset($_POST['add_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_name']);
    $access_level = mysqli_real_escape_string($conn, $_POST['access_level']);
    $password = password_hash($_POST['password_hash'], PASSWORD_DEFAULT);
    $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
    
    $insertSql = "INSERT INTO users (username, email, password_hash, role_id, access_level, is_active, created_at) 
                 VALUES ('$username', '$email', '$password', '$role_id', '$access_level', $is_active, NOW())";
    
    if (mysqli_query($conn, $insertSql)) {
        // Redirect to refresh the page
        header("Location: settings page.php?success=1");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Process role creation form
if (isset($_POST['add_role'])) {
    $role_name = mysqli_real_escape_string($conn, $_POST['role_name']);
    $role_description = mysqli_real_escape_string($conn, $_POST['role_description']);
    $is_teaching_role = isset($_POST['is_teaching_role']) ? 1 : 0;
    
    $insertRoleSql = "INSERT INTO roles (role_name, role_description, is_teaching_role) 
                      VALUES ('$role_name', '$role_description', $is_teaching_role)";
    
    if (mysqli_query($conn, $insertRoleSql)) {
        header("Location: settings page.php?role_success=1");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Count users by role
$roleCountSql = "SELECT r.role_id, r.role_name, COUNT(u.user_id) as user_count 
                FROM roles r 
                LEFT JOIN users u ON r.role_id = u.role_id 
                GROUP BY r.role_id";
$roleCountResult = mysqli_query($conn, $roleCountSql);
if ($roleCountResult) {
    $roleCounts = mysqli_fetch_all($roleCountResult, MYSQLI_ASSOC);
    // Convert to associative array for easier lookup
    $roleCountMap = [];
    foreach ($roleCounts as $rc) {
        $roleCountMap[$rc['role_id']] = $rc['user_count'];
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Settings</title>

    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="setting.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <li onclick="window.location.href='messages.php'"><i class="fas fa-envelope"></i> <span>Messages</span></li>
            <li  class="active" onclick="window.location.href='settings page.php'"><i class="fas fa-cog"></i> <span>Settings</span></li>
            <li onclick="window.location.href='logout.php'"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></li>
        </ul>
    </div>

    <div class="main-content">
    <div class="welcome-banner">

<!-- welcome message   -->

    <div class="welcome-text">
<h1>MONACO INSTITUTE</h1>
<div class="welcome-message">
    <?php
    // Time-based greeting
    $hour = date('H');
    $greeting = ($hour < 12) ? "Good Morning" : (($hour < 17) ? "Good Afternoon" : "Good Evening");
    
    // Get username
    $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "User";
    echo "<p class='welcome-user'>{$greeting}, <span class='username'>{$username}</span></p>";
    
    // Dynamic messages
    $month = date('n');
    $seasonalMessages = [
        1 => "Happy New Year! New year, new learning opportunities",
        5 => "Spring into your educational journey",
        9 => "Welcome to the new academic year!",
        12 => "Season's greetings! Wrapping up the year with excellence"
    ];
    
    $dailyMessages = [
        "Empowering your educational journey every day!",
        "Building futures, one lesson at a time",
        "Today is a great day to learn something new!",
        "Where skills meet innovation",
        "Excellence in education since 2007",
        "Together, we grow"
    ];
    
    $message = $seasonalMessages[$month] ?? $dailyMessages[date('z') % count($dailyMessages)];
    echo "<p class='welcome-message-text'>{$message}</p>";
    ?>
</div>
<div class="date-display">
    <i class="fas fa-calendar-alt"></i> <span id="currentDate"><?php echo date('l, F j, Y'); ?></span>
    <span class="time-display"><i class="fas fa-clock"></i> <span id="currentTime"><?php echo date('h:i:s A'); ?></span></span>
</div>
</div>


         <!-- Notifications  -->

        <div class="user-section" style="display:flex; align-items:center;">
        <div class="notification-bell" id="notificationBell">
<i class="fas fa-bell"></i>
<span class="notification-count"><?php echo $notification_count; ?></span>

<!-- Notifications dropdown -->
<div class="notifications-dropdown" id="notificationsDropdown">
    <div class="notifications-header">
        <h3></h3>
        <?php if ($notification_count > 0): ?>
            <a href="mark_all_read.php" class="mark-all-read">Mark all as read</a>
        <?php endif; ?>
    </div>
    
    <div class="notifications-list">
        <?php if (empty($notifications)): ?>
            <div class="no-notifications"></div>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item" data-id="<?php echo $notification['id']; ?>">
                    <div class="notification-icon">
                        <?php if ($notification['type'] == 'message'): ?>
                            <i class="fas fa-envelope"></i>
                        <?php elseif ($notification['type'] == 'event'): ?>
                            <i class="fas fa-calendar-alt"></i>
                        <?php elseif ($notification['type'] == 'alert'): ?>
                            <i class="fas fa-exclamation-circle"></i>
                        <?php else: ?>
                            <i class="fas fa-bell"></i>
                        <?php endif; ?>
                    </div>
                    <div class="notification-content">
                        <div class="notification-text"><?php echo htmlspecialchars($notification['message']); ?></div>
                        <div class="notification-time">
                            <?php 
                                $time_diff = time() - strtotime($notification['created_at']);
                                if ($time_diff < 60) {
                                    echo "Just now";
                                } elseif ($time_diff < 3600) {
                                    echo floor($time_diff / 60) . " min ago";
                                } elseif ($time_diff < 86400) {
                                    echo floor($time_diff / 3600) . " hrs ago";
                                } else {
                                    echo floor($time_diff / 86400) . " days ago";
                                }
                            ?>
                        </div>
                    </div>
                    <button class="mark-read" onclick="markAsRead(<?php echo $notification['id']; ?>)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endforeach; ?>
            
            <?php if ($notification_count > count($notifications)): ?>
                <a href="all_notifications.php" class="view-all-notifications">
                    View all notifications (<?php echo $notification_count; ?>)
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
</div>


            <div class="user-profile">
                <div class="user-avatar">
                    <?php echo isset($_SESSION['username']) ? substr($_SESSION['username'], 0, 1) : 'U'; ?>
                </div>
                <div class="user-info">
                    <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; ?><br>
                    <span class="role"><?php echo isset($_SESSION['role_name']) ? $_SESSION['role_name'] : 'User'; ?></span>
                </div>
            </div>
        </div>
    </div>



        <div class="search-bar">
            <input type="text" placeholder="Search settings..." aria-label="Search">
        </div>

        <!-- Settings Tab Navigation -->
        <div class="settings-tabs">
            <button class="tab-button active" data-tab="user-management">
                <i class="fas fa-users"></i> User Management
            </button>
            
          
            <button class="tab-button" data-tab="roles-management">
                <i class="fas fa-user-tag"></i> Roles Management
            </button>
            <button class="tab-button" data-tab="security-settings">
                <i class="fas fa-shield-alt"></i> Security Settings
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- 1. User Management Panel -->
            <div class="tab-pane active" id="user-management">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-users"></i> User Management Panel</div>
                    <div class="action-buttons">
                        <button class="add-button" id="addUserBtn"><i class="fas fa-plus"></i> Add New User</button>
                    </div>
                </div>

                <div class="user-table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($users) && !empty($users)): ?>
                                <?php foreach($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['user_id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <?php if($user['is_active'] == 1): ?>
                                                <span class="status-badge active">Active</span>
                                            <?php else: ?>
                                                <span class="status-badge suspended">Suspended</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $user['last_login'] ? date('M d, Y', strtotime($user['last_login'])) : 'Never'; ?></td>
                                        <td>
                                            <button class="action-icon edit" data-id="<?php echo $user['user_id']; ?>"><i class="fas fa-edit"></i></button>
                                            <?php if($user['is_active'] == 1): ?>
                                                <button class="action-icon suspend" data-id="<?php echo $user['user_id']; ?>"><i class="fas fa-user-slash"></i></button>
                                            <?php else: ?>
                                                <button class="action-icon reactivate" data-id="<?php echo $user['user_id']; ?>"><i class="fas fa-user-check"></i></button>
                                            <?php endif; ?>
                                            <button class="action-icon delete" data-id="<?php echo $user['user_id']; ?>"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add User Modal -->
                <div class="modal" id="addUserModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-user-plus"></i> Add New User</h3>
                            <span class="close-modal">&times;</span>
                        </div>
                        <div class="modal-body">
                            <form id="addUserForm" action="settings page.php" method="POST">
                                <div class="form-group">
                                    <label for="userName">Full Name</label>
                                    <input type="text" id="userName" placeholder="Enter full name" required name="username">
                                </div>
                                <div class="form-group">
                                    <label for="userEmail">Email</label>
                                    <input type="email" id="userEmail" placeholder="Enter email address" required name="email">
                                </div>
                                <div class="form-group">
                                    <label for="userRole">Role</label>
                                    <select id="userRole" required name="role_name">
                                        <option value="">Select a role</option>
                                        <?php foreach($roles as $r): ?>
                                            <option value="<?= $r['role_id']; ?>"><?= htmlspecialchars($r['role_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="userAccessLevel">Access Level</label>
                                    <select id="userAccessLevel" required name="access_level">                                        
                                        <option value="standard">Standard</option>
                                        <option value="basic">Basic</option>
                                        <option value="advanced">Advanced</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="userPassword">Password</label>
                                    <input type="password" id="userPassword" placeholder="Enter password" required name="password_hash">
                                </div>
                                <div class="form-group">
                                    <label for="userStatus">Status</label>
                                    <select id="userStatus" required name="is_active">                                        
                                        <option value="1">Active</option>
                                        <option value="0">Suspended</option>
                                    </select>
                                </div>
                                <div class="form-options">
                                    <div class="option-group">
                                        <input type="checkbox" id="sendInvite">
                                        <label for="sendInvite">Send invitation email</label>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="submit-btn" name="add_user">Add User</button>
                                    <button type="button" class="cancel-btn" name="cancel">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        
            
              
            <!-- 4. Roles Management -->
            <div class="tab-pane" id="roles-management">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-user-tag"></i> Roles Management</div>
                    <div class="action-buttons">
                        <button class="add-button" id="addRoleBtn"><i class="fas fa-plus"></i> Create New Role</button>
                    </div>
                </div>

                <div class="roles-container">
                    <?php if(isset($roles) && !empty($roles)): ?>
                        <?php foreach($roles as $role): ?>
                            <div class="role-card">
                                <div class="role-header">
                                    <h3><?php echo htmlspecialchars($role['role_name']); ?></h3>
                                    <span class="role-count">
                                        <?php 
                                            $count = isset($roleCountMap[$role['role_id']]) ? $roleCountMap[$role['role_id']] : 0;
                                            echo $count . ' ' . ($count == 1 ? 'user' : 'users');
                                        ?>
                                    </span>
                                </div>
                                <div class="role-description">
                                    <?php echo htmlspecialchars($role['role_description'] ?? 'No description available'); ?>
                                </div>
                                <div class="role-modules">
                                    
                                    
                                    <?php if($role['role_name'] == 'Super Admin' || $role['role_name'] == 'Admin'): ?>
                                        <span class="module-tag">All Modules</span>
                                    <?php elseif($role['role_name'] == 'Instructor'): ?>

                                        <span class="module-tag">Results</span>
                                        <span class="module-tag">Notices</span>
                                        <span class="module-tag">Attendance</span>
                                    <?php elseif($role['role_name'] == 'Finance Officer'): ?>
                                        <span class="module-tag">Payments</span>
                                        <span class="module-tag">Reports</span>
                                    <?php elseif($role['role_name'] == 'Academic Assistant'): ?>
                                        <span class="module-tag">Results (View)</span>
                                        <span class="module-tag">Timetable (View)</span>
                                    <?php endif; ?>
                                </div>
                                <div class="role-actions">
                                    <button class="edit-button" data-id="<?php echo $role['role_id']; ?>"><i class="fas fa-edit"></i> Edit</button>
                                    <?php if($role['role_name'] != 'Super Admin' && $role['role_name'] != 'Admin'): ?>
                                        <button class="delete-button" data-id="<?php echo $role['role_id']; ?>"><i class="fas fa-trash"></i> Delete</button>
                                    <?php else: ?>
                                        <button class="delete-button" disabled><i class="fas fa-trash"></i> Delete</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No roles found. Create a new role to get started.</p>
                    <?php endif; ?>
                </div>


                <!-- Add Role Modal -->
                <div class="modal" id="addRoleModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-user-tag"></i> Create New Role</h3>
                            <span class="close-modal">&times;</span>
                        </div>
                        <div class="modal-body">
                            <form id="addRoleForm">
                                <div class="form-group">
                                    <label for="roleName">Role Name</label>
                                    <input type="text" id="roleName" placeholder="Enter role name" required>
                                </div>
                                <div class="form-group">
                                    <label for="roleDescription">Description</label>
                                    <textarea id="roleDescription" placeholder="Enter role description" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Select Modules & Permissions</label>
                                    <div class="module-permissions">
                                        <div class="module-item">
                                            <div class="module-name">Dashboard</div>
                                            <div class="module-permissions-options">
                                                <label><input type="checkbox" name="dashboard-view"> View</label>
                                                <label><input type="checkbox" name="dashboard-edit"> Edit</label>
                                                <label><input type="checkbox" name="dashboard-delete"> Delete</label>
                                            </div>
                                        </div>
                                        <div class="module-item">
                                            <div class="module-name">Results</div>
                                            <div class="module-permissions-options">
                                                <label><input type="checkbox" name="results-view"> View</label>
                                                <label><input type="checkbox" name="results-edit"> Edit</label>
                                                <label><input type="checkbox" name="results-delete"> Delete</label>
                                            </div>
                                        </div>
                                        <div class="module-item">
                                            <div class="module-name">Notices</div>
                                            <div class="module-permissions-options">
                                                <label><input type="checkbox" name="notices-view"> View</label>
                                                <label><input type="checkbox" name="notices-edit"> Edit</label>
                                                <label><input type="checkbox" name="notices-delete"> Delete</label>
                                            </div>
                                        </div>
                                        <div class="module-item">
                                            <div class="module-name">Settings</div>
                                            <div class="module-permissions-options">
                                                <label><input type="checkbox" name="settings-view"> View</label>
                                                <label><input type="checkbox" name="settings-edit"> Edit</label>
                                                <label><input type="checkbox" name="settings-delete"> Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="submit-btn">Create Role</button>
                                    <button type="button" class="cancel-btn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Security Settings -->
            <div class="tab-pane" id="security-settings">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-shield-alt"></i> Security Settings</div>
                    <div class="action-buttons">
                        <button class="save-button"><i class="fas fa-save"></i> Save Changes</button>
                    </div>
                </div>

                <div class="security-sections">
                    <!-- Notification Settings -->
                    <div class="security-section">
                        <h3><i class="fas fa-bell"></i> Notification Settings</h3>
                        <div class="security-options">
                            <div class="option-item">
                                <div class="option-label">New user registrations</div>
                                <div class="option-toggle">
                                    <label class="switch">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="option-item">
                                <div class="option-label">Suspicious logins</div>
                                <div class="option-toggle">
                                    <label class="switch">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="option-item">
                                <div class="option-label">Permission changes</div>
                                <div class="option-toggle">
                                    <label class="switch">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="option-item">
                                <div class="option-label">System updates</div>
                                <div class="option-toggle">
                                    <label class="switch">
                                        <input type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Policy -->
                    <div class="security-section">
                        <h3><i class="fas fa-key"></i> Password Policy</h3>
                        <div class="security-options">
                            <div class="option-item">
                                <div class="option-label">Force password change every</div>
                                <div class="option-input">
                                    <select>
                                        <option value="30">30 days</option>
                                        <option value="60">60 days</option>
                                        <option value="90" selected>90 days</option>
                                        <option value="180">180 days</option>
                                        <option value="0">Never</option>
                                    </select>
                                </div>
                            </div>
                            <div class="option-item">
                                <div class="option-label">Minimum password length</div>
                                <div class="option-input">
                                    <select>
                                        <option value="6">6 characters</option>
                                        <option value="8" selected>8 characters</option>
                                        <option value="10">10 characters</option>
                                        <option value="12">12 characters</option>
                                    </select>
                                </div>
                            </div>
                            <div class="option-item">
                                <div class="option-label">Require complex passwords</div>
                                <div class="option-toggle">
                                    <label class="switch"><input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Login Security -->
                    <div class="security-section">
                        <h3><i class="fas fa-sign-in-alt"></i> Login Security</h3>
                        <div class="security-options">
                            <div class="option-item">
                                <div class="option-label">Maximum login attempts</div>
                                <div class="option-input">
                                    <select>
                                        <option value="3">3 attempts</option>
                                        <option value="5" selected>5 attempts</option>
                                        <option value="10">10 attempts</option>
                                    </select>
                                </div>
                            </div>
                            <div class="option-item">
                                <div class="option-label">Lock account after failed attempts</div>
                                <div class="option-toggle">
                                    <label class="switch">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="option-item">
                                <div class="option-label">Enable 2FA for admin logins</div>
                                <div class="option-toggle">
                                    <label class="switch">
                                        <input type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Session Settings -->
                    <div class="security-section">
                        <h3><i class="fas fa-clock"></i> Session Settings</h3>
                        <div class="security-options">
                            <div class="option-item">
                                <div class="option-label">Session timeout</div>
                                <div class="option-input">
                                    <select>
                                        <option value="15">15 minutes</option>
                                        <option value="30" selected>30 minutes</option>
                                        <option value="60">60 minutes</option>
                                        <option value="120">2 hours</option>
                                    </select>
                                </div>
                            </div>
                            <div class="option-item">
                                <div class="option-label">Remember login</div>
                                <div class="option-toggle">
                                    <label class="switch">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        // Tab navigation functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons and panes
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    
                    // Add active class to current button
                    this.classList.add('active');
                    
                    // Show the corresponding tab pane
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // Modal functionality
            const addUserBtn = document.getElementById('addUserBtn');
            const addUserModal = document.getElementById('addUserModal');
            const addRoleBtn = document.getElementById('addRoleBtn');
            const addRoleModal = document.getElementById('addRoleModal');
            const closeButtons = document.querySelectorAll('.close-modal, .cancel-btn');
            
            // Open modals
            if (addUserBtn) {
                addUserBtn.addEventListener('click', function() {
                    addUserModal.style.display = 'block';
                });
            }
            
            if (addRoleBtn) {
                addRoleBtn.addEventListener('click', function() {
                    addRoleModal.style.display = 'block';
                });
            }
            
            // Close modals
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    addUserModal.style.display = 'none';
                    addRoleModal.style.display = 'none';
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === addUserModal) {
                    addUserModal.style.display = 'none';
                }
                if (event.target === addRoleModal) {
                    addRoleModal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>