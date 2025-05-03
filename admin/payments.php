<?php

session_start(); // Start the session
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'dbconnect.php'; // Include the database connection file

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
// Function to get total fees collected
function getTotalFeesCollected($conn) {
    $sql = "SELECT SUM(amount) as total FROM payment_receipts";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error: " . $conn->error);
        return 0;
    }
    
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Function to get total fees due
function getTotalFeesDue($conn) {
    $sql = "SELECT SUM(amount_due) as total FROM student_fees WHERE status IN ('Not Paid', 'Partially Paid')";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error: " . $conn->error);
        return 0;
    }
    
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Function to get total school expenses
function getTotalExpenses($conn) {
    $sql = "SELECT SUM(amount) as total FROM expenses";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error: " . $conn->error);
        return 0;
    }
    
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Function to get net balance
function getNetBalance($conn) {
    $totalCollected = getTotalFeesCollected($conn);
    $totalExpenses = getTotalExpenses($conn);
    return $totalCollected - $totalExpenses;
}

// Function to get count of students with dues - FIXED
function getStudentsWithDues($conn) {
    $sql = "SELECT COUNT(DISTINCT student_id) as total FROM student_fees WHERE status IN ('Not Paid', 'Partially Paid')";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getStudentsWithDues: " . $conn->error);
        return 0;
    }
    
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Function to get count of overdue students - FIXED
function getOverdueStudents($conn) {
    $sql = "SELECT COUNT(DISTINCT student_id) as total FROM student_fees WHERE status IN ('Not Paid', 'Partially Paid') AND due_date < CURDATE()";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getOverdueStudents: " . $conn->error);
        return 0;
    }
    
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Function to get monthly income data for chart
function getMonthlyIncome($conn) {
    $sql = "SELECT MONTH(payment_date) as month, SUM(amount) as total 
            FROM payment_receipts 
            WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY MONTH(payment_date)
            ORDER BY MONTH(payment_date)";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getMonthlyIncome: " . $conn->error);
        return [];
    }
    
    $monthlyData = [];
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    // Initialize with 0 values
    for ($i = 0; $i < 6; $i++) {
        $monthIdx = (date('n') - 6 + $i + 12) % 12;
        $monthlyData[$months[$monthIdx]] = 0;
    }
    
    // Fill with actual values
    while ($row = $result->fetch_assoc()) {
        $monthName = $months[$row['month'] - 1];
        $monthlyData[$monthName] = $row['total'];
    }
    
    return $monthlyData;
}

// Function to get monthly expenses data for chart
function getMonthlyExpenses($conn) {
    $sql = "SELECT MONTH(expense_date) as month, SUM(amount) as total 
            FROM expenses 
            WHERE expense_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY MONTH(expense_date)
            ORDER BY MONTH(expense_date)";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getMonthlyExpenses: " . $conn->error);
        return [];
    }
    
    $monthlyData = [];
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    // Initialize with 0 values
    for ($i = 0; $i < 6; $i++) {
        $monthIdx = (date('n') - 6 + $i + 12) % 12;
        $monthlyData[$months[$monthIdx]] = 0;
    }
    
    // Fill with actual values
    while ($row = $result->fetch_assoc()) {
        $monthName = $months[$row['month'] - 1];
        $monthlyData[$monthName] = $row['total'];
    }
    
    return $monthlyData;
}

// Function to get payment status breakdown for pie chart - FIXED
function getPaymentStatusBreakdown($conn) {
    $sql = "SELECT status as payment_status, COUNT(*) as count FROM student_fees GROUP BY status";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getPaymentStatusBreakdown: " . $conn->error);
        return [
            'Fully Paid' => 0,
            'Partially Paid' => 0,
            'Not Paid' => 0
        ];
    }
    
    $statusData = [
        'Fully Paid' => 0,
        'Partially Paid' => 0,
        'Not Paid' => 0
    ];
    
    while ($row = $result->fetch_assoc()) {
        if ($row['payment_status'] == 'Paid') {
            $statusData['Fully Paid'] = $row['count'];
        } else if ($row['payment_status'] == 'Partially Paid') {
            $statusData['Partially Paid'] = $row['count'];
        } else if ($row['payment_status'] == 'Not Paid') {
            $statusData['Not Paid'] = $row['count'];
        }
    }
    
    $total = array_sum($statusData);
    $percentages = [];
    
    foreach ($statusData as $status => $count) {
        $percentages[$status] = $total > 0 ? round(($count / $total) * 100) : 0;
    }
    
    return $percentages;
}

// Function to get expense categories breakdown
function getExpenseCategoriesBreakdown($conn) {
    $sql = "SELECT category, SUM(amount) as total FROM expenses GROUP BY category";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getExpenseCategoriesBreakdown: " . $conn->error);
        return [];
    }
    
    $categoryData = [];
    
    while ($row = $result->fetch_assoc()) {
        $categoryData[$row['category']] = $row['total'];
    }
    
    $total = array_sum($categoryData);
    $percentages = [];
    
    foreach ($categoryData as $category => $amount) {
        $percentages[$category] = $total > 0 ? round(($amount / $total) * 100) : 0;
    }
    
    return $percentages;
}

// Function to get outstanding payments - FIXED
function getOutstandingPayments($conn, $classFilter = '', $termFilter = '', $statusFilter = '', $searchQuery = '') {
    $sql = "SELECT sf.student_fee_id as invoice_id, s.student_id, s.first_name, s.last_name, c.class_name, 
                  sf.amount_due, sf.due_date, sf.status as payment_status
           FROM student_fees sf
           JOIN students s ON sf.student_id = s.student_id
           JOIN classes c ON s.class_id = c.class_id
           WHERE sf.status IN ('Not Paid', 'Partially Paid')";
    
    // Apply filters if provided
    if (!empty($classFilter)) {
        $sql .= " AND c.class_id = '" . $conn->real_escape_string($classFilter) . "'";
    }
    
    if (!empty($termFilter)) {
        $sql .= " AND sf.term_id = '" . $conn->real_escape_string($termFilter) . "'";
    }
    
    if (!empty($statusFilter)) {
        $sql .= " AND sf.status = '" . $conn->real_escape_string($statusFilter) . "'";
    }
    
    if (!empty($searchQuery)) {
        $searchQuery = $conn->real_escape_string($searchQuery);
        $sql .= " AND (s.first_name LIKE '%$searchQuery%' OR s.last_name LIKE '%$searchQuery%' OR s.student_id LIKE '%$searchQuery%')";
    }
    
    $sql .= " ORDER BY sf.due_date ASC";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getOutstandingPayments: " . $conn->error);
        return [];
    }
    
    $payments = [];
    
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    
    return $payments;
}

// Function to get payment history - FIXED
function getPaymentHistory($conn, $limit = 10) {
    $sql = "SELECT pr.payment_date, s.first_name, s.last_name, pr.amount, pr.payment_method, pr.receipt_id as payment_id
            FROM payment_receipts pr
            JOIN students s ON pr.student_id = s.student_id
            ORDER BY pr.payment_date DESC
            LIMIT $limit";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getPaymentHistory: " . $conn->error);
        return [];
    }
    
    $history = [];
    
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    
    return $history;
}

// Function to get expense records
function getExpenseRecords($conn, $limit = 10) {
    $sql = "SELECT expense_id, expense_date, description, amount, category
            FROM expenses
            ORDER BY expense_date DESC
            LIMIT $limit";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getExpenseRecords: " . $conn->error);
        return [];
    }
    
    $expenses = [];
    
    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row;
    }
    
    return $expenses;
}

// Function to get all classes for filter dropdown
function getAllClasses($conn) {
    $sql = "SELECT class_id, class_name FROM classes ORDER BY class_name";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getAllClasses: " . $conn->error);
        return [];
    }
    
    $classes = [];
    
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
    
    return $classes;
}

// Function to get all terms for filter dropdown
function getAllTerms($conn) {
    $sql = "SELECT term_id, term_name FROM terms ORDER BY term_id";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database error in getAllTerms: " . $conn->error);
        return [];
    }
    
    $terms = [];
    
    while ($row = $result->fetch_assoc()) {
        $terms[] = $row;
    }
    
    return $terms;
}

try {
    // Get data for the page
    $totalFeesCollected = getTotalFeesCollected($conn);
    $totalFeesDue = getTotalFeesDue($conn);
    $totalExpenses = getTotalExpenses($conn);
    $netBalance = getNetBalance($conn);
    $studentsWithDues = getStudentsWithDues($conn);
    $overdueStudents = getOverdueStudents($conn);

    $monthlyIncome = getMonthlyIncome($conn);
    $monthlyExpenses = getMonthlyExpenses($conn);
    $paymentStatusBreakdown = getPaymentStatusBreakdown($conn);
    $expenseCategoriesBreakdown = getExpenseCategoriesBreakdown($conn);

    // Get filter values from GET parameters if they exist
    $classFilter = $_GET['classFilter'] ?? '';
    $termFilter = $_GET['termFilter'] ?? '';
    $statusFilter = $_GET['statusFilter'] ?? '';
    $searchQuery = $_GET['search'] ?? '';

    // Get data for tables
    $outstandingPayments = getOutstandingPayments($conn, $classFilter, $termFilter, $statusFilter, $searchQuery);
    $paymentHistory = getPaymentHistory($conn);
    $expenseRecords = getExpenseRecords($conn);

    // Get data for dropdowns
    $classes = getAllClasses($conn);
    $terms = getAllTerms($conn);

    // Calculate change percentages (dummy data for demonstration - replace with actual calculations)
    $collectedChangePercent = 12; // Example value
    $dueChangePercent = 5; // Example value
    $netBalanceChangePercent = 15; // Example value
} catch (Exception $e) {
    error_log("Error loading payments page: " . $e->getMessage());
    // Set default values in case of errors
    $totalFeesCollected = 0;
    $totalFeesDue = 0;
    $totalExpenses = 0;
    $netBalance = 0;
    $studentsWithDues = 0;
    $overdueStudents = 0;
    $monthlyIncome = [];
    $monthlyExpenses = [];
    $paymentStatusBreakdown = ['Fully Paid' => 0, 'Partially Paid' => 0, 'Not Paid' => 0];
    $expenseCategoriesBreakdown = [];
    $outstandingPayments = [];
    $paymentHistory = [];
    $expenseRecords = [];
    $classes = [];
    $terms = [];
    $collectedChangePercent = 0;
    $dueChangePercent = 0;
    $netBalanceChangePercent = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Payments</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       
           /* Add any additional styles here */
           
        /* Enhanced Filter Styles for Payments Page */

/* Filter container styling */
.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    background-color: #f8f9fc;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

/* Filter form styling */
#paymentFiltersForm {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    width: 100%;
    gap: 15px;
}

/* Individual filter group */
.filter-group {
    display: flex;
    flex-direction: column;
    min-width: 180px;
    flex: 1;
}

/* Filter labels */
.filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: #4e4e4e;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Filter dropdowns */
.filter-input {
    height: 40px;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
    padding: 8px 12px;
    font-size: 14px;
    color: #333;
    background-color: #fff;
    transition: all 0.3s ease;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
}

/* Filter dropdown focus state */
.filter-input:focus {
    border-color: #8B1818;
    box-shadow: 0 0 0 2px rgba(139, 24, 24, 0.1);
    outline: none;
}

/* Filter dropdown hover state */
.filter-input:hover {
    border-color: #ccc;
}

/* Search box styling */
.search-box {
    height: 40px;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
    padding: 8px 12px 8px 35px;
    font-size: 14px;
    color: #333;
    background-color: #fff;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>');
    background-repeat: no-repeat;
    background-position: 10px center;
    background-size: 16px;
    transition: all 0.3s ease;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    min-width: 200px;
}

.search-box:focus {
    border-color: #8B1818;
    box-shadow: 0 0 0 2px rgba(139, 24, 24, 0.1);
    outline: none;
}

/* Filter button styling */
.action-button {
    height: 40px;
    background-color: #8B1818;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0 20px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(139, 24, 24, 0.2);
    align-self: flex-end;
}

.action-button:hover {
    background-color: #6e1414;
    box-shadow: 0 4px 8px rgba(139, 24, 24, 0.3);
}

.action-button i {
    font-size: 14px;
}

/* Custom select styling - dropdown arrow */
select.filter-input {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="%23888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>');
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 35px;
    cursor: pointer;
}

/* Custom styling for Status dropdown */
#statusFilter {
    color: #555;
    font-weight: 500;
}

#statusFilter option[value="Overdue"] {
    color: #dc3545;
    font-weight: 500;
}

#statusFilter option[value="Partially Paid"] {
    color: #ffc107;
    font-weight: 500;
}

#statusFilter option[value="Not Paid"] {
    color: #fd7e14;
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .filter-group {
        min-width: 150px;
    }
}

@media (max-width: 768px) {
    .filter-row {
        flex-direction: column;
        gap: 10px;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .action-button {
        width: 100%;
        justify-content: center;
        margin-top: 5px;
    }
}

/* Badge styling for filter selections */
.selected-filter-badge {
    display: inline-flex;
    align-items: center;
    background-color: #f2f2f2;
    border: 1px solid #ddd;
    border-radius: 16px;
    padding: 3px 10px;
    margin-right: 5px;
    margin-top: 10px;
    font-size: 12px;
    color: #333;
}

.selected-filter-badge i {
    color: #888;
    margin-left: 5px;
    cursor: pointer;
}

.selected-filter-badge i:hover {
    color: #8B1818;
}

/* Applied filters section */
.applied-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-top: 10px;
}

/* Clear all filters button */
.clear-filters {
    background: none;
    border: none;
    color: #8B1818;
    text-decoration: underline;
    cursor: pointer;
    font-size: 12px;
    margin-left: auto;
}

.clear-filters:hover {
    color: #6e1414;
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
            <li class="active" onclick="window.location.href='payments.php'"><i class="fas fa-money-bill-wave"></i> <span>Payments Info</span></li>
            <li onclick="window.location.href='marks&exams.php'"><i class="fas fa-file-alt"></i> <span>Marks & Exams</span></li>
            <li onclick="window.location.href='results.php'"><i class="fas fa-search"></i> <span>Result</span></li>
            <li onclick="window.location.href='notices.php'"><i class="fas fa-bullhorn"></i> <span>Notice</span></li>
            <li onclick="window.location.href='attendence.php'"><i class="fas fa-clipboard-list"></i> <span>Attendance</span></li>
            <li onclick="window.location.href='classes.php'"><i class="fas fa-chalkboard-teacher"></i> <span>Classes</span></li>
            <li onclick="window.location.href='messages.php'"><i class="fas fa-envelope"></i> <span>Messages</span></li>
            <li onclick="window.location.href='settings page.php'"><i class="fas fa-cog"></i> <span>Settings</span></li>
            <li ><i class="fas fa-sign-out-alt"></i> <span>Logout</span></li>
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
            <input type="text" placeholder="Search payments, students, or invoices..." aria-label="Search">
        </div>

       <!-- Financial Overview Cards -->
       <div class="financial-overview">
            <div class="finance-card collected">
                <div class="finance-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="finance-label">Total Fees Collected</div>
                <div class="finance-value">$<?php echo number_format($totalFeesCollected, 0); ?></div>
                <div class="finance-additional positive">
                    <i class="fas fa-arrow-up"></i> <?php echo $collectedChangePercent; ?>% from last month
                </div>
            </div>
            
            <div class="finance-card due">
                <div class="finance-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="finance-label">Total Fees Due (Unpaid)</div>
                <div class="finance-value">$<?php echo number_format($totalFeesDue, 0); ?></div>
                <div class="finance-additional negative">
                    <i class="fas fa-arrow-up"></i> <?php echo $dueChangePercent; ?>% from last month
                </div>
            </div>
            
            <div class="finance-card expenses">
                <div class="finance-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="finance-label">Total School Expenses</div>
                <div class="finance-value">$<?php echo number_format($totalExpenses, 0); ?></div>
                <div class="finance-additional neutral">
                    <i class="fas fa-equals"></i> Same as last month
                </div>
            </div>
            
            <div class="finance-card balance">
                <div class="finance-icon">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="finance-label">Net Balance</div>
                <div class="finance-value">$<?php echo number_format($netBalance, 0); ?></div>
                <div class="finance-additional positive">
                    <i class="fas fa-arrow-up"></i> <?php echo $netBalanceChangePercent; ?>% from last month
                </div>
            </div>

            <div class="finance-card stats">
                <div class="finance-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="finance-label">Students with Dues</div>
                <div class="finance-value"><?php echo $studentsWithDues; ?></div>
                <div class="finance-additional">
                    <span class="negative"><?php echo $overdueStudents; ?> overdue</span> • 
                    <span class="neutral"><?php echo ($studentsWithDues - $overdueStudents); ?> current</span>
                </div>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="actions-bar">
            <button class="action-button" id="sendReminders">
                <i class="fas fa-bell"></i> Send Payment Reminders
            </button>
            <button class="action-button" onclick="window.location.href='recipt.php'">
                <i class="fas fa-file-invoice"></i> Record New Payment
            </button>
            <button class="action-button" onclick="window.location.href='expenses.php'">
                <i class="fas fa-plus-circle"></i> Add New Expense
            </button>
            <button class="action-button" id="exportExcel">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
            <button class="action-button" id="generatePDF">
                <i class="fas fa-file-pdf"></i> Generate PDF Report
            </button>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <div class="chart-container">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-chart-bar"></i> Monthly Income vs Expenses</div>
                </div>
                <div class="chart">
                    <div class="bar-chart">
                        <?php foreach ($monthlyIncome as $month => $amount): ?>
                        <div class="bar-container">
                            <div class="bar" style="height: <?php echo min(round($amount / 1000), 200); ?>px;"></div>
                            <div class="expense-bar" style="height: <?php echo min(round(($monthlyExpenses[$month] ?? 0) / 1000), 200); ?>px;"></div>
                            <div class="bar-label"><?php echo $month; ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #8B1818;"></div>
                        <span>Income</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #E74C3C;"></div>
                        <span>Expenses</span>
                    </div>
                </div>
            </div>
            
            <div class="chart-container">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-chart-pie"></i> Payment Status Breakdown</div>
                </div>
                <div class="chart">
                    <div class="pie-chart" id="paymentStatusChart"></div>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color:rgb(5, 255, 68);"></div>
                        <span>Fully Paid (<?php echo $paymentStatusBreakdown['Fully Paid'] ?? 0; ?>%)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #ffc107;"></div>
                        <span>Partially Paid (<?php echo $paymentStatusBreakdown['Partially Paid'] ?? 0; ?>%)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #dc3545;"></div>
                        <span>Not Paid (<?php echo $paymentStatusBreakdown['Not Paid'] ?? 0; ?>%)</span>
                    </div>
                </div>
            </div>

            <div class="chart-container" style="margin-top: 20px;">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-chart-line"></i> Fees Collection Over Time</div>
                </div>
                <div class="chart">
                    <!-- Line chart will be rendered by JavaScript -->
                    <canvas id="feesCollectionChart" width="500" height="200"></canvas>
                </div>
            </div>
            
            <div class="chart-container" style="margin-top: 20px;">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-chart-pie"></i> Top Spending Categories</div>
                </div>
                <div class="chart">
                    <div class="donut-chart" id="expenseCategoriesChart"></div>
                </div>
                <div class="chart-legend">
                    <?php foreach ($expenseCategoriesBreakdown as $category => $percentage): ?>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: <?php echo getColorForCategory($category); ?>;"></div>
                        <span><?php echo $category; ?> (<?php echo $percentage; ?>%)</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
          <!-- Payment Tables Section -->
<div class="payment-tables">
    <div class="tab-container">
        <div class="tabs">
            <div class="tab active">Outstanding Payments</div>
            <div class="tab">Payment History</div>
            <div class="tab">Expense Records</div>
        </div>
        <div class="tab-content active" id="outstandingPayments">

          <!-- Replace the existing filter-row section with this enhanced version -->
<div class="filter-row">
    <form method="GET" action="payments.php" id="paymentFiltersForm">
        <div class="filter-group">
            <label for="classFilter">Class</label>
            <select id="classFilter" name="classFilter" class="filter-input">
                <option value="">All Classes</option>
                <?php foreach ($classes as $class): ?>
                <option value="<?php echo $class['class_id']; ?>" <?php echo ($classFilter == $class['class_id']) ? 'selected' : ''; ?>>
                    <?php echo $class['class_name']; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="termFilter">Term</label>
            <select id="termFilter" name="termFilter" class="filter-input">
                <option value="">All Terms</option>
                <?php foreach ($terms as $term): ?>
                <option value="<?php echo $term['term_id']; ?>" <?php echo ($termFilter == $term['term_id']) ? 'selected' : ''; ?>>
                    <?php echo $term['term_name']; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="statusFilter">Status</label>
            <select id="statusFilter" name="statusFilter" class="filter-input">
                <option value="">All Statuses</option>
                <option value="Overdue" <?php echo ($statusFilter == 'Overdue') ? 'selected' : ''; ?>>Overdue</option>
                <option value="Partially Paid" <?php echo ($statusFilter == 'Partially Paid') ? 'selected' : ''; ?>>Partially Paid</option>
                <option value="Not Paid" <?php echo ($statusFilter == 'Not Paid') ? 'selected' : ''; ?>>Not Paid</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="searchFilter">Search</label>
            <input type="text" id="searchFilter" name="search" placeholder="Search student..." class="search-box" value="" aria-label="Search students">
        </div>
        <button type="submit" class="action-button">
            <i class="fas fa-filter"></i> Apply Filters
        </button>
    </form>

    <div class="applied-filters">
        <!-- Filter badges will be added dynamically -->
        <button class="clear-filters">Clear all filters</button>
    </div>
    
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Amount Due</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($outstandingPayments)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No outstanding payments found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($outstandingPayments as $payment): ?>
                        <tr>
                            <td><?php echo $payment['student_id']; ?></td>
                            <td><?php echo $payment['first_name'] . ' ' . $payment['last_name']; ?></td>
                            <td><?php echo $payment['class_name']; ?></td>
                            <td>$<?php echo number_format($payment['amount_due'], 2); ?></td>
                            <td><?php echo date('M d, Y', strtotime($payment['due_date'])); ?></td>
                            <td>
                                <span class="status-pill status-<?php echo strtolower(str_replace(' ', '-', $payment['payment_status'])); ?>">
                                    <?php echo $payment['payment_status']; ?>
                                </span>
                            </td>
                            <td>
                                <button class="action-icon send-reminder" data-student-id="<?php echo $payment['student_id']; ?>">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button class="action-icon record-payment" data-invoice-id="<?php echo $payment['invoice_id']; ?>">
                                    <i class="fas fa-money-bill"></i>
                                </button>
                                <button class="action-icon view-details" data-invoice-id="<?php echo $payment['invoice_id']; ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="tab-content" id="paymentHistory">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student Name</th>
                        <th>Amount Paid</th>
                        <th>Payment Method</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($paymentHistory)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No payment history found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($paymentHistory as $history): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($history['payment_date'])); ?></td>
                            <td><?php echo $history['first_name'] . ' ' . $history['last_name']; ?></td>
                            <td>$<?php echo number_format($history['amount'], 2); ?></td>
                            <td><?php echo $history['payment_method']; ?></td>
                            <td>
                                <button class="action-icon view-receipt" data-payment-id="<?php echo $history['payment_id'] ?? ''; ?>">
                                    <i class="fas fa-receipt"></i>
                                </button>
                                <button class="action-icon email-receipt" data-payment-id="<?php echo $history['payment_id'] ?? ''; ?>">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="tab-content" id="expenseRecords">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($expenseRecords)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No expense records found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($expenseRecords as $expense): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($expense['expense_date'])); ?></td>
                            <td><?php echo $expense['description']; ?></td>
                            <td>$<?php echo number_format($expense['amount'], 2); ?></td>
                            <td><?php echo $expense['category']; ?></td>
                            <td>
                                <button class="action-icon edit-expense" data-expense-id="<?php echo $expense['expense_id'] ?? ''; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-icon delete-expense" data-expense-id="<?php echo $expense['expense_id'] ?? ''; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    // JavaScript for dynamic date and time display
    function updateDateTime() {
        const now = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = now.toLocaleDateString(undefined, options);
        document.getElementById('currentTime').textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // JavaScript for weather widget (dummy data)
    const weatherWidget = document.querySelector('.weather-widget');
    const temperature = weatherWidget.querySelector('.temperature');
    const weatherIcon = weatherWidget.querySelector('.weather-icon');
    const weatherData = {
        temperature: '26°C',
        icon: 'fas fa-sun'
    };
    temperature.textContent = weatherData.temperature;
    weatherIcon.className = 'fas ' + weatherData.icon + ' weather-icon';

    // JavaScript for donut chart (dummy functionality)
    const donutChart = document.querySelector('.donut-chart');
    donutChart.style.width = '200px';
    donutChart.style.height = '200px';
    donutChart.style.borderRadius = '50%';
    donutChart.style.background = 'conic-gradient(#28a745 0%,rgb(167, 49, 40) 60%, #ffc107 60%, #ffc107 85%, #dc3545 85%, #dc3545 100%)';
    donutChart.style.position = 'relative';
    donutChart.style.display = 'flex';
    donutChart.style.alignItems = 'center';
    donutChart.style.justifyContent = 'center';
    donutChart.innerHTML = '<div style="width: 80px; height: 80px; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold;">15%</div>';
    donutChart.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
    donutChart.style.margin = '0 auto';
    donutChart.style.padding = '20px';
    donutChart.style.borderRadius = '50%';
    donutChart.style.transition = 'transform 0.60s ease-in-out';
    donutChart.addEventListener('mouseover', () => {
        donutChart.style.transform = 'scale(1.05)';
    });
    donutChart.addEventListener('mouseout', () => {
        donutChart.style.transform = 'scale(1)';
    });

    // JavaScript for bar chart (dummy functionality)
    const barChart = document.querySelector('.bar-chart');
    barChart.style.display = 'flex';
    barChart.style.justifyContent = 'space-between';
    barChart.style.alignItems = 'flex-end';
    barChart.style.height = '200px';
    barChart.style.width = '100%';
    barChart.style.position = 'relative';
    barChart.style.backgroundColor = '#f8f9fa';
    barChart.style.borderRadius = '10px';
    barChart.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
    barChart.style.padding = '10px';
    barChart.style.margin = '0 auto';
    barChart.style.padding = '20px';
    barChart.style.borderRadius = '10px';           
    barChart.style.transition = 'transform 0.3s ease-in-out';
    barChart.addEventListener('mouseover', () => {
        barChart.style.transform = 'scale(1.05)';
    }); 
    barChart.addEventListener('mouseout', () => {
        barChart.style.transform = 'scale(1)';
    });

    // JavaScript for action icons (dummy functionality)    
    const actionIcons = document.querySelectorAll('.action-icon');
    actionIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const action = icon.classList.contains('edit') ? 'Edit' : 'View Details';
            console.log(`${action} clicked`);
        });
    });

    // JavaScript for filter functionality (dummy data)
    const filterButton = document.querySelector('.action-button');
    filterButton.addEventListener('click', () => {
        const classFilter = document.getElementById('classFilter').value;
        const termFilter = document.getElementById('termFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const searchInput = document.querySelector('.search-box').value.toLowerCase();
        console.log(`Filters applied: Class - ${classFilter}, Term - ${termFilter}, Status - ${statusFilter}, Search - ${searchInput}`);
    });

    // JavaScript for search box (dummy functionality)
    const searchBox = document.querySelector('.search-box');
    searchBox.addEventListener('input', () => {
        const query = searchBox.value.toLowerCase();
        console.log(`Searching for: ${query}`);
    });

    // JavaScript for pagination (dummy functionality)
    const paginationButtons = document.querySelectorAll('.pagination-button');
    paginationButtons.forEach(button => {
        button.addEventListener('click', () => {
            const page = button.textContent;
            console.log(`Page ${page} clicked`);
        });
    });

    // Consolidated tab functionality
    document.addEventListener("DOMContentLoaded"), () => {
        const tabs = document.querySelectorAll(".tab");
        const tabContents = document.querySelectorAll(".tab-content")};

        // Add click event listeners to all tabs
        tabs.forEach((tab, index) => {
            tab.addEventListener("click", () => {
                // Remove the active class from all tabs and hide all tab contents
                tabs.forEach(t => t.classList.remove("active"));
                tabContents.forEach(content => content.style.display = "none");

                // Add the active class to the clicked tab and show the corresponding content
                tab.classList.add("active");
                tabContents[index].style.display = "block";

                // If "Payment History" tab is clicked, load the history data
                if (tab.textContent.trim() === "Payment History") {
                    loadPaymentHistory();
                }
            });
        });

       /*  // Function to load payment history data
        function loadPaymentHistory() {
            const paymentHistoryContent = document.querySelector(".tab-content:nth-child(2)");
            if (paymentHistoryContent) {
                paymentHistoryContent.innerHTML = `
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student Name</th>
                                <th>Amount Paid</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>April 10, 2025</td>
                                <td>Michael Johnson</td>
                                <td>$1,200</td>
                                <td>Credit Card</td>
                            </tr>
                            <tr>
                                <td>April 12, 2025</td>
                                <td>Sarah Smith</td>
                                <td>$800</td>
                                <td>Bank Transfer</td>
                            </tr>
                            <tr>
                                <td>April 13, 2025</td>
                                <td>Emily Davis</td>
                                <td>$1,500</td>
                                <td>Cash</td>
                            </tr>
                        </tbody>
                    </table>
                `;
            }
        }

        // Initialize by showing the first tab content
        tabs[0].click();
    }); */
</script>
</body>
</html>
<!-- End of HTML Document -->