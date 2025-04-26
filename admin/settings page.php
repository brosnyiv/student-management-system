<?php
session_start();
include 'dbconnect.php';


// Check if user is not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

//fetch data from role table
$sql = "SELECT * FROM roles";
$result = mysqli_query($conn, $sql);
if ($result) {
    $roles = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
            <li ><i class="fas fa-sign-out-alt"></i> <span>Logout</span></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>MONACO INSTITUTE</h1>
                <p>Settings & Admin Controls</p>
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
            <input type="text" placeholder="Search settings..." aria-label="Search">
        </div>

        <!-- Settings Tab Navigation -->
        <div class="settings-tabs">
            <button class="tab-button active" data-tab="user-management">
                <i class="fas fa-users"></i> User Management
            </button>
            <button class="tab-button" data-tab="activity-logs">
                <i class="fas fa-clipboard-list"></i> User Activity Logs
            </button>
            <button class="tab-button" data-tab="access-control">
                <i class="fas fa-lock"></i> Access Control
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
                            <tr>
                                <td>001</td>
                                <td>Sarah Kim</td>
                                <td>Instructor</td>
                                <td>sarah@mi.edu</td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>Apr 15, 2025</td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon suspend"><i class="fas fa-user-slash"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>002</td>
                                <td>John Smith</td>
                                <td>Admin</td>
                                <td>john@mi.edu</td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>Apr 15, 2025</td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon suspend"><i class="fas fa-user-slash"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>003</td>
                                <td>Lisa Johnson</td>
                                <td>Assistant</td>
                                <td>lisa@mi.edu</td>
                                <td><span class="status-badge suspended">Suspended</span></td>
                                <td>Apr 10, 2025</td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon reactivate"><i class="fas fa-user-check"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>004</td>
                                <td>Michael Wong</td>
                                <td>Accountant</td>
                                <td>michael@mi.edu</td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>Apr 14, 2025</td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-icon suspend"><i class="fas fa-user-slash"></i></button>
                                    <button class="action-icon delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
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
                            <form id="addUserForm" action="add_user.php" method="POST">
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
                                                <option value="<?= $r['role_id']; ?>"><?= $r['role_name']; ?></option>
                                            <?php endforeach; ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="userStatus">Access Level</label>
                                    <select id="userStatus" required name="access_level">                                        
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

            <!-- 2. User Activity Logs -->
            <div class="tab-pane" id="activity-logs">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-clipboard-list"></i> User Activity Logs</div>
                    <div class="action-buttons">
                        <button class="export-button"><i class="fas fa-file-export"></i> Export Logs</button>
                    </div>
                </div>

                <div class="filters-container">
                    <div class="filter-group">
                        <label for="dateRangeStart">Date Range:</label>
                        <input type="date" id="dateRangeStart" value="2025-04-01">
                        <span>to</span>
                        <input type="date" id="dateRangeEnd" value="2025-04-15">
                    </div>
                    <div class="filter-group">
                        <label for="userFilter">User:</label>
                        <select id="userFilter">
                            <option value="">All Users</option>
                            <option value="Sarah Kim">Sarah Kim</option>
                            <option value="John Smith">John Smith</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="activityFilter">Activity Type:</label>
                        <select id="activityFilter">
                            <option value="">All Activities</option>
                            <option value="Login">Login</option>
                            <option value="Edit">Edit</option>
                            <option value="View">View</option>
                            <option value="Delete">Delete</option>
                        </select>
                    </div>
                    <button class="filter-button"><i class="fas fa-filter"></i> Apply Filters</button>
                </div>

                <div class="logs-table-container">
                    <table class="logs-table">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Page/Module</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2025-04-15 09:21</td>
                                <td>Sarah Kim</td>
                                <td><span class="activity-badge view">Viewed</span></td>
                                <td>Grades Results</td>
                                <td>Accessed final grades for CS101</td>
                            </tr>
                            <tr>
                                <td>2025-04-15 09:35</td>
                                <td>Admin</td>
                                <td><span class="activity-badge add">Added</span></td>
                                <td>Notices</td>
                                <td>Created new notice "Exam Schedule"</td>
                            </tr>
                            <tr>
                                <td>2025-04-15 10:05</td>
                                <td>John Smith</td>
                                <td><span class="activity-badge login">Login</span></td>
                                <td>System</td>
                                <td>Successful login from 192.168.1.45</td>
                            </tr>
                            <tr>
                                <td>2025-04-15 10:15</td>
                                <td>Lisa Johnson</td>
                                <td><span class="activity-badge edit">Edited</span></td>
                                <td>Student Records</td>
                                <td>Updated contact info for Student #1024</td>
                            </tr>
                            <tr>
                                <td>2025-04-15 10:30</td>
                                <td>Michael Wong</td>
                                <td><span class="activity-badge delete">Deleted</span></td>
                                <td>Payments</td>
                                <td>Removed duplicate payment record #5523</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Access Control & Permissions -->
            <div class="tab-pane" id="access-control">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-lock"></i> Access Control & Permissions</div>
                    <div class="action-buttons">
                        <button class="export-button"><i class="fas fa-file-export"></i> Export Matrix</button>
                        <button class="save-button"><i class="fas fa-save"></i> Save Changes</button>
                    </div>
                </div>

                <div class="user-selector">
                    <label for="permissionUser">Select User or Role:</label>
                    <select id="permissionUser">
                        <option value="role-instructor">Role: Instructor</option>
                        <option value="role-admin">Role: Admin</option>
                        <option value="role-assistant">Role: Assistant</option>
                        <option value="user-sarah">User: Sarah Kim</option>
                        <option value="user-john">User: John Smith</option>
                    </select>
                </div>

                <div class="permissions-matrix">
                    <table class="permissions-table">
                        <thead>
                            <tr>
                                <th>Page / Module</th>
                                <th>View</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Access Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dashboard</td>
                                <td><input type="checkbox" checked></td>
                                <td><input type="checkbox"></td>
                                <td><input type="checkbox"></td>
                                <td>
                                    <select class="access-level">
                                        <option value="full">Full Access</option>
                                        <option value="view-only" selected>View-Only</option>
                                        <option value="no-access">No Access</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Results</td>
                                <td><input type="checkbox" checked></td>
                                <td><input type="checkbox" checked></td>
                                <td><input type="checkbox"></td>
                                <td>
                                    <select class="access-level">
                                        <option value="full" selected>Full Access</option>
                                        <option value="view-only">View-Only</option>
                                        <option value="no-access">No Access</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Notices</td>
                                <td><input type="checkbox" checked></td>
                                <td><input type="checkbox" checked></td>
                                <td><input type="checkbox" checked></td>
                                <td>
                                    <select class="access-level">
                                        <option value="full" selected>Full Access</option>
                                        <option value="view-only">View-Only</option>
                                        <option value="no-access">No Access</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Timetable</td>
                                <td><input type="checkbox" checked></td>
                                <td><input type="checkbox" checked></td>
                                <td><input type="checkbox"></td>
                                <td>
                                    <select class="access-level">
                                        <option value="full" selected>Full Access</option>
                                        <option value="view-only">View-Only</option>
                                        <option value="no-access">No Access</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Settings</td>
                                <td><input type="checkbox"></td>
                                <td><input type="checkbox"></td>
                                <td><input type="checkbox"></td>
                                <td>
                                    <select class="access-level">
                                        <option value="full">Full Access</option>
                                        <option value="view-only">View-Only</option>
                                        <option value="no-access" selected>No Access</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="permissions-legend">
                    <h4>Permission Types</h4>
                    <div class="legend-items">
                        <div class="legend-item">
                            <span class="legend-color full-access"></span>
                            <span>Full Access: View, create, update, delete</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color view-only"></span>
                            <span>View-Only: Can only view the module, no interaction</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color no-access"></span>
                            <span>No Access: Hidden entirely</span>
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
                    <div class="role-card">
                        <div class="role-header">
                            <h3>Super Admin</h3>
                            <span class="role-count">2 users</span>
                        </div>
                        <div class="role-description">Full access to all modules and features</div>
                        <div class="role-modules">
                            <span class="module-tag">All Modules</span>
                        </div>
                        <div class="role-actions">
                            <button class="edit-button"><i class="fas fa-edit"></i> Edit</button>
                            <button class="delete-button" disabled><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>

                    <div class="role-card">
                        <div class="role-header">
                            <h3>Instructor</h3>
                            <span class="role-count">15 users</span>
                        </div>
                        <div class="role-description">Can manage courses, grades, and attendance</div>
                        <div class="role-modules">
                            <span class="module-tag">Results</span>
                            <span class="module-tag">Notices</span>
                            <span class="module-tag">Attendance</span>
                        </div>
                        <div class="role-actions">
                            <button class="edit-button"><i class="fas fa-edit"></i> Edit</button>
                            <button class="delete-button"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>

                    <div class="role-card">
                        <div class="role-header">
                            <h3>Finance Officer</h3>
                            <span class="role-count">3 users</span>
                        </div>
                        <div class="role-description">Access to payment and financial modules only</div>
                        <div class="role-modules">
                            <span class="module-tag">Payments</span>
                            <span class="module-tag">Reports</span>
                        </div>
                        <div class="role-actions">
                            <button class="edit-button"><i class="fas fa-edit"></i> Edit</button>
                            <button class="delete-button"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>

                    <div class="role-card">
                        <div class="role-header">
                            <h3>Academic Assistant</h3>
                            <span class="role-count">8 users</span>
                        </div>
                        <div class="role-description">View-only access to academic records</div>
                        <div class="role-modules">
                            <span class="module-tag">Results (View)</span>
                            <span class="module-tag">Timetable (View)</span>
                        </div>
                        <div class="role-actions">
                            <button class="edit-button"><i class="fas fa-edit"></i> Edit</button>
                            <button class="delete-button"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>
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