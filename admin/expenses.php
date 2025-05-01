<?php
session_start(); // Start the session
ob_start();

include 'dbconnect.php'; // Include the database connection file

// Check if user is not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Function to safely escape and format inputs
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

// Handle form submission for adding expense
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_expense'])) {
    $date = clean_input($_POST['date']);
    $amount = clean_input($_POST['expense_amount']);
    $category_id = clean_input($_POST['expense_category']);
    $status = clean_input($_POST['expense_status']);
    $vendor = clean_input($_POST['expense_vendor']);
    $department_id = clean_input($_POST['expense_department']);
    $description = clean_input($_POST['expense_description']);
    $staff_id = $_SESSION['user_id']; // Current logged-in user
    
    // Insert into expenses table
    $insert_query = "INSERT INTO expenses (staff_id, category_id, department_id, da, amount, vendor, description, status, created_at) 
                    VALUES ('$staff_id', '$category_id', '$department_id', '$da', '$amount', '$vendor', '$description', '$status', NOW())";
    
    if(mysqli_query($conn, $insert_query)) {
        $success_message = "Expense added successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Handle expense deletion
if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $delete_id = clean_input($_GET['delete_id']);
    
    // Delete the expense record
    $delete_query = "DELETE FROM expenses WHERE expense_id = '$delete_id' AND staff_id = '" . $_SESSION['user_id'] . "'";
    
    if(mysqli_query($conn, $delete_query)) {
        $success_message = "Expense deleted successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Handle expense edit form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_expense'])) {
    $expense_id = clean_input($_POST['expense_id']);
    $date = clean_input($_POST['edit_date']);
    $amount = clean_input($_POST['edit_expense_amount']);
    $category_id = clean_input($_POST['edit_expense_category']);
    $status = clean_input($_POST['edit_expense_status']);
    $vendor = clean_input($_POST['edit_expense_vendor']);
    $description = clean_input($_POST['edit_expense_description']);
    $department_id = isset($_POST['edit_expense_department']) ? clean_input($_POST['edit_expense_department']) : null;
    
    // Update expense record
    $update_query = "UPDATE expenses SET 
                    date = '$date', 
                    amount = '$amount', 
                    category_id = '$category_id', 
                    department_id = '$department_id',
                    status = '$status', 
                    vendor = '$vendor', 
                    description = '$description',
                    updated_at = NOW() 
                    WHERE expense_id = '$expense_id' AND staff_id = '" . $_SESSION['user_id'] . "'";
    
    if(mysqli_query($conn, $update_query)) {
        $success_message = "Expense updated successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Fetch categories for dropdown
// First check what column exists for category name
$check_query = "SHOW COLUMNS FROM expense_categories";
$check_result = mysqli_query($conn, $check_query);
$name_column = 'name'; // Default fallback

while ($column = mysqli_fetch_assoc($check_result)) {
    if (strpos(strtolower($column['Field']), 'name') !== false) {
        $name_column = $column['Field'];
        break;
    }
}

$categories_query = "SELECT * FROM expense_categories ORDER BY $name_column";
$categories_result = mysqli_query($conn, $categories_query);
$categories = [];
while ($category = mysqli_fetch_assoc($categories_result)) {
    $categories[] = $category;
}

// Fetch departments for dropdown
// First check what column exists for department name
$check_dept_query = "SHOW COLUMNS FROM departments";
$check_dept_result = mysqli_query($conn, $check_dept_query);
$department_name_column = 'name'; // Default fallback

while ($column = mysqli_fetch_assoc($check_dept_result)) {
    if (strpos(strtolower($column['Field']), 'name') !== false) {
        $department_name_column = $column['Field'];
        break;
    }
}

$departments_query = "SELECT * FROM departments ORDER BY $department_name_column";
$departments_result = mysqli_query($conn, $departments_query);
$departments = [];
while ($department = mysqli_fetch_assoc($departments_result)) {
    $departments[] = $department;
}

// Setup pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Build query with filters
$where_conditions = ["staff_id = '" . $_SESSION['user_id'] . "'"];

if (isset($_GET['filter_category']) && !empty($_GET['filter_category'])) {
    $filter_category = clean_input($_GET['filter_category']);
    $where_conditions[] = "category_id = '$filter_category'";
}

if (isset($_GET['filter_status']) && !empty($_GET['filter_status'])) {
    $filter_status = clean_input($_GET['filter_status']);
    $where_conditions[] = "status = '$filter_status'";
}

if (isset($_GET['filter_start_date']) && !empty($_GET['filter_start_date'])) {
    $filter_start_date = clean_input($_GET['filter_start_date']);
    $where_conditions[] = "date >= '$filter_start_date'";
}

if (isset($_GET['filter_end_date']) && !empty($_GET['filter_end_date'])) {
    $filter_end_date = clean_input($_GET['filter_end_date']);
    $where_conditions[] = "date <= '$filter_end_date'";
}

$where_clause = implode(" AND ", $where_conditions);

// Count total records for pagination
$count_query = "SELECT COUNT(*) as total FROM expenses WHERE $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $records_per_page);

// Find the correct column names for category and department tables
$name_field = "name"; // Default
$department_name_field = "name"; // Default

// Check category table structure
$cat_cols_query = "SHOW COLUMNS FROM expense_categories";
$cat_cols_result = mysqli_query($conn, $cat_cols_query);
while ($col = mysqli_fetch_assoc($cat_cols_result)) {
    if (strpos(strtolower($col['Field']), 'name') !== false) {
        $name_field = $col['Field'];
        break;
    }
}

// Check department table structure
$dept_cols_query = "SHOW COLUMNS FROM departments";
$dept_cols_result = mysqli_query($conn, $dept_cols_query);
while ($col = mysqli_fetch_assoc($dept_cols_result)) {
    if (strpos(strtolower($col['Field']), 'name') !== false) {
        $department_name_field = $col['Field'];
        break;
    }
}

// Fetch expenses with joined category and department names
$expenses_query = "SELECT e.*, c.$name_field as name, d.$department_name_field as department_name 
                  FROM expenses e
                  LEFT JOIN expense_categories c ON e.category_id = c.category_id
                  LEFT JOIN departments d ON e.department_id = d.department_id
                  WHERE $where_clause
                  ORDER BY e.date DESC
                  LIMIT $offset, $records_per_page";
$expenses_result = mysqli_query($conn, $expenses_query);

// 3. Query expense categories
$query = "SELECT * FROM expense_categories";
$result = mysqli_query($conn, $query);

// 4. Set $top_category (e.g., first category)
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $top_category = $row['name']; // Use 'name' or relevant column
} else {
    $top_category = "Uncategorized"; // Fallback
}

// Now safely use $top_category
echo "Selected Category: " . htmlspecialchars($top_category);

// Calculate summary data
$summary_query = "SELECT 
                    SUM(amount) as total_expenses,
                    (SELECT SUM(amount) FROM expenses WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) AND staff_id = '" . $_SESSION['user_id'] . "') as month_expenses,
                    (SELECT c.name FROM expenses e
                     JOIN expense_categories c ON e.category_id = c.category_id
                     WHERE e.staff_id = '" . $_SESSION['user_id'] . "'
                     GROUP BY e.category_id
                     ORDER BY SUM(e.amount) DESC
                     LIMIT 1) as top_category
                  FROM expenses 
                  WHERE staff_id = '" . $_SESSION['user_id'] . "'";
$summary_result = mysqli_query($conn, $summary_query);
$summary = mysqli_fetch_assoc($summary_result);

// Set default values if null
$summary = mysqli_fetch_assoc($summary_result);
$total_expenses = $summary['total_expenses'] ? $summary['total_expenses'] : 0;
$month_expenses = $summary['month_expenses'] ? $summary['month_expenses'] : 0;
// top_category is already set above

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Expense Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Keep your existing CSS here -->

    <style>
        :root {
            --primary-color: #8B1818;
            --primary-hover: #701010;
            --text-color: #333;
            --light-bg: #f5f5f5;
            --border-color: #ddd;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }
        
        .container {
            max-width: 900px;
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
        
        .required-field::after {
            content: "*";
            color: #e74c3c;
            margin-left: 4px;
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
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .expense-dashboard {
            padding: 15px;
            background-color: var(--light-bg);
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .dashboard-summary {
            display: flex;
            gap: 15px;
        }
        
        .summary-card {
            flex: 1;
            background-color: white;
            border-radius: 6px;
            padding: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .summary-card .card-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-card .card-value {
            font-size: 24px;
            font-weight: 600;
        }
        
        .summary-card.total {
            border-left: 4px solid var(--primary-color);
        }
        
        .summary-card.month {
            border-left: 4px solid #3498db;
        }
        
        .summary-card.category {
            border-left: 4px solid #2ecc71;
        }
        
        .expense-list {
            margin-top: 30px;
        }
        
        .expense-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .expense-table th {
            background-color: #f5f5f5;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .expense-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .expense-table .amount {
            text-align: right;
            font-weight: 500;
        }
        
        .expense-table .actions {
            text-align: center;
            width: 100px;
        }
        
        .expense-table tr:hover {
            background-color: #f9f9f9;
        }
        
        .expense-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        
        .action-btn {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: #666;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            background-color: #f0f0f0;
        }
        
        .action-btn.edit:hover {
            color: #3498db;
        }
        
        .action-btn.delete:hover {
            color: var(--danger-color);
        }
        
        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .filters .form-group {
            margin-bottom: 0;
        }
        
        .date-filter {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .date-label {
            font-weight: 500;
            color: var(--text-color);
            white-space: nowrap;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .category-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            color: white;
        }
        
        .category-badge.utilities {
            background-color: #3498db;
        }
        
        .category-badge.supplies {
            background-color: #2ecc71;
        }
        
        .category-badge.travel {
            background-color: #9b59b6;
        }
        
        .category-badge.food {
            background-color: #e67e22;
        }
        
        .category-badge.equipment {
            background-color: #34495e;
        }
        
        .category-badge.other {
            background-color: #95a5a6;
        }
        
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .status-pending {
            background-color: #f39c12;
        }
        
        .status-approved {
            background-color: #2ecc71;
        }
        
        .status-rejected {
            background-color: #e74c3c;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .pagination-btn {
            padding: 8px 12px;
            margin: 0 5px;
            border: 1px solid var(--border-color);
            background-color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .pagination-btn:hover {
            background-color: #f5f5f5;
        }
        
        .pagination-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal.show {
            display: flex;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 500px;
            max-width: 90%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            position: relative;
        }
        
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid var(--border-color);
            text-align: right;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .dashboard-summary {
                flex-direction: column;
            }
            
            .expense-table {
                display: block;
                overflow-x: auto;
            }
            
            .filters {
                flex-direction: column;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h2><i class="fas fa-calculator"></i> Expense Tracker</h2>
        </div>
        
        <div class="form-body">
            <?php if(isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <!-- Dashboard Summary -->
            <div class="expense-dashboard">
                <div class="dashboard-summary">
                    <div class="summary-card total">
                        <div class="card-title">Total Expenses</div>
                        <div class="card-value">UGX <?php echo number_format($total_expenses, 0); ?></div>
                    </div>
                    <div class="summary-card month">
                        <div class="card-title">This Month</div>
                        <div class="card-value">UGX <?php echo number_format($month_expenses, 0); ?></div>
                    </div>
                    <div class="summary-card category">
                        <div class="card-title">Top Category</div>
                        <div class="card-value"><?php echo $top_category; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- New Expense Form -->
            <form id="expenseForm" method="POST" action="">
                <h3>Add New Expense</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="da" class="required-field">Date</label>
                        <input type="date" id="da" name="da" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="expense_amount" class="required-field">Amount (UGX)</label>
                        <input type="number" id="expense_amount" name="expense_amount" min="0" step="1" placeholder="Enter amount" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="expense_category" class="required-field">Category</label>
                        <select id="expense_category" name="expense_category" required>
                            <option value="">Select category</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expense_status">Status</label>
                        <select id="expense_status" name="expense_status">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="expense_vendor" class="required-field">Vendor/Payee</label>
                        <input type="text" id="expense_vendor" name="expense_vendor" placeholder="Enter vendor name" required>
                    </div>
                    <div class="form-group">
                        <label for="expense_department">Department</label>
                        <select id="expense_department" name="expense_department">
                            <option value="">Select department</option>
                            <?php foreach($departments as $department): ?>
                                <option value="<?php echo $department['department_id']; ?>"><?php echo $department['department_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="expense_description">Description</label>
                    <textarea id="expense_description" name="expense_description" rows="2" placeholder="Describe the expense"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">Clear Form</button>
                    <button type="submit" name="add_expense" class="btn btn-primary">Add Expense</button>
                </div>
            </form>
            
            <!-- Expense List -->
            <div class="expense-list">
                <h3>Recent Expenses</h3>
                
                <form method="GET" action="">
                    <div class="filters">
                        <div class="form-group">
                            <label for="filter_category">Filter by Category</label>
                            <select id="filter_category" name="filter_category" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <?php foreach($categories as $category): ?>
                                    <option value="<?php echo $category['category_id']; ?>" <?php echo (isset($_GET['filter_category']) && $_GET['filter_category'] == $category['category_id']) ? 'selected' : ''; ?>>
                                        <?php echo $category['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="filter_status">Filter by Status</label>
                            <select id="filter_status" name="filter_status" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo (isset($_GET['filter_status']) && $_GET['filter_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="approved" <?php echo (isset($_GET['filter_status']) && $_GET['filter_status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                                <option value="rejected" <?php echo (isset($_GET['filter_status']) && $_GET['filter_status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date Range</label>
                            <div class="date-filter">
                                <input type="date" id="filter_start_date" name="filter_start_date" value="<?php echo isset($_GET['filter_start_date']) ? $_GET['filter_start_date'] : ''; ?>">
                                <div class="date-label">to</div>
                                <input type="date" id="filter_end_date" name="filter_end_date" value="<?php echo isset($_GET['filter_end_date']) ? $_GET['filter_end_date'] : ''; ?>">
                                <button type="submit" class="btn btn-sm btn-primary" style="margin-left: 10px;">Apply</button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <table class="expense-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Vendor</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="amount">Amount</th>
                            <th class="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($expenses_result) > 0): ?>
                            <?php while($expense = mysqli_fetch_assoc($expenses_result)): ?>
                                <tr data-id="<?php echo $expense['expense_id']; ?>">
                                    <td><?php echo date('M d, Y', strtotime($expense['date'])); ?></td>
                                    <td><?php echo htmlspecialchars($expense['vendor']); ?></td>
                                    <td>
                                        <span class="category-badge">
                                            <?php 
                                            // Determine which field to use for category name from joined results
                                            $category_display = "";
                                            foreach ($expense as $key => $value) {
                                                if (strpos(strtolower($key), 'category') !== false && strpos(strtolower($key), 'name') !== false) {
                                                    $category_display = $value;
                                                    break;
                                                }
                                            }
                                            if (empty($category_display) && isset($expense['category_id'])) {
                                                // Get category name from ID
                                                $cat_query = "SELECT * FROM expense_categories WHERE category_id = '".$expense['category_id']."'";
                                                $cat_result = mysqli_query($conn, $cat_query);
                                                if ($cat_data = mysqli_fetch_assoc($cat_result)) {
                                                    foreach ($cat_data as $key => $value) {
                                                        if (strpos(strtolower($key), 'name') !== false) {
                                                            $category_display = $value;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                            echo htmlspecialchars($category_display ?: "Unknown Category");
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($expense['description']); ?></td>
                                    <td>
                                        <span class="status-indicator status-<?php echo $expense['status']; ?>"></span>
                                        <?php echo ucfirst($expense['status']); ?>
                                    </td>
                                    <td class="amount">UGX <?php echo number_format($expense['amount'], 0); ?></td>
                                    <td class="actions">
                                        <div class="expense-actions">
                                            <button type="button" class="action-btn edit" title="Edit" onclick="openEditModal(<?php 
                                            echo json_encode([
                                                'id' => $expense['expense_id'],
                                                'date' => $expense['date'],
                                                'amount' => $expense['amount'],
                                                'category_id' => $expense['category_id'],
                                                'department_id' => $expense['department_id'],
                                                'status' => $expense['status'],
                                                'vendor' => htmlspecialchars_decode($expense['vendor']),
                                                'description' => htmlspecialchars_decode($expense['description'])
                                            ]);
                                            ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="?delete_id=<?php echo $expense['expense_id']; ?>" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure you want to delete this expense?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">No expenses found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <?php if($total_pages > 1): ?>
                <div class="pagination">
                    <?php if($page > 1): ?>
                        <a href="?page=<?php echo ($page-1); ?><?php echo isset($_GET['filter_category']) ? '&filter_category='.$_GET['filter_category'] : ''; ?><?php echo isset($_GET['filter_status']) ? '&filter_status='.$_GET['filter_status'] : ''; ?><?php echo isset($_GET['filter_start_date']) ? '&filter_start_date='.$_GET['filter_start_date'] : ''; ?><?php echo isset($_GET['filter_end_date']) ? '&filter_end_date='.$_GET['filter_end_date'] : ''; ?>" class="page-link">&laquo; Previous</a>
                    <?php endif; ?>
                    
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo isset($_GET['filter_category']) ? '&filter_category='.$_GET['filter_category'] : ''; ?><?php echo isset($_GET['filter_status']) ? '&filter_status='.$_GET['filter_status'] : ''; ?><?php echo isset($_GET['filter_start_date']) ? '&filter_start_date='.$_GET['filter_start_date'] : ''; ?><?php echo isset($_GET['filter_end_date']) ? '&filter_end_date='.$_GET['filter_end_date'] : ''; ?>" class="page-link <?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if($page < $total_pages): ?>
                        <a href="?page=<?php echo ($page+1); ?><?php echo isset($_GET['filter_category']) ? '&filter_category='.$_GET['filter_category'] : ''; ?><?php echo isset($_GET['filter_status']) ? '&filter_status='.$_GET['filter_status'] : ''; ?><?php echo isset($_GET['filter_start_date']) ? '&filter_start_date='.$_GET['filter_start_date'] : ''; ?><?php echo isset($_GET['filter_end_date']) ? '&filter_end_date='.$_GET['filter_end_date'] : ''; ?>" class="page-link">Next &raquo;</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Expense Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Expense</h3>
                <button type="button" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editExpenseForm" method="POST" action="">
                    <input type="hidden" id="edit_expense_id" name="expense_id">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_da" class="required-field">Date</label>
                            <input type="date" id="edit_da" name="edit_da" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_expense_amount" class="required-field">Amount (UGX)</label>
                            <input type="number" id="edit_expense_amount" name="edit_expense_amount" min="0" step="1" placeholder="Enter amount" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_expense_category" class="required-field">Category</label>
                            <select id="edit_expense_category" name="edit_expense_category" required>
                                <option value="">Select category</option>
                                <?php foreach($categories as $category): ?>
                                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_expense_status">Status</label>
                            <select id="edit_expense_status" name="edit_expense_status">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_expense_vendor" class="required-field">Vendor/Payee</label>
                            <input type="text" id="edit_expense_vendor" name="edit_expense_vendor" placeholder="Enter vendor name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_expense_department">Department</label>
                            <select id="edit_expense_department" name="edit_expense_department">
                                <option value="">Select department</option>
                                <?php foreach($departments as $department): ?>
                                    <option value="<?php echo $department['department_id']; ?>"><?php echo $department['department_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_expense_description">Description</label>
                        <textarea id="edit_expense_description" name="edit_expense_description" rows="2" placeholder="Describe the expense"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary modal-close">Cancel</button>
                        <button type="submit" name="edit_expense" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById('editModal');
        const modalClose = document.querySelectorAll('.modal-close');
        
        // Close modal when clicking on close button or outside the modal
        modalClose.forEach(element => {
            element.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        });
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
        
        // Function to open edit modal with expense data
        function openEditModal(expenseData) {
            // Parse the JSON if it's a string
            if (typeof expenseData === 'string') {
                expenseData = JSON.parse(expenseData);
            }
            
            // Populate form fields with expense data
            document.getElementById('edit_expense_id').value = expenseData.id;
            document.getElementById('edit_da').value = expenseData.date;
            document.getElementById('edit_expense_amount').value = expenseData.amount;
            document.getElementById('edit_expense_category').value = expenseData.category_id;
            document.getElementById('edit_expense_status').value = expenseData.status;
            document.getElementById('edit_expense_vendor').value = expenseData.vendor;
            document.getElementById('edit_expense_description').value = expenseData.description;
            
            // Set department if available
            if (expenseData.department_id) {
                document.getElementById('edit_expense_department').value = expenseData.department_id;
            }
            
            // Display the modal
            modal.style.display = 'block';
        }
    </script>
</body>
</html>