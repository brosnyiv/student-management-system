<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Expense Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h2><i class="fas fa-calculator"></i> Expense Tracker</h2>
        </div>
        
        <div class="form-body">
            <!-- Dashboard Summary -->
            <div class="expense-dashboard">
                <div class="dashboard-summary">
                    <div class="summary-card total">
                        <div class="card-title">Total Expenses</div>
                        <div class="card-value">$7,850.00</div>
                    </div>
                    <div class="summary-card month">
                        <div class="card-title">This Month</div>
                        <div class="card-value">$2,350.00</div>
                    </div>
                    <div class="summary-card category">
                        <div class="card-title">Top Category</div>
                        <div class="card-value">Supplies</div>
                    </div>
                </div>
            </div>
            
            <!-- New Expense Form -->
            <form id="expenseForm">
                <h3>Add New Expense</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="expenseDate" class="required-field">Date</label>
                        <input type="date" id="expenseDate" required>
                    </div>
                    <div class="form-group">
                        <label for="expenseAmount" class="required-field">Amount ($)</label>
                        <input type="number" id="expenseAmount" min="0" step="0.01" placeholder="Enter amount" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="expenseCategory" class="required-field">Category</label>
                        <select id="expenseCategory" required>
                            <option value="">Select category</option>
                            <option value="utilities">Utilities</option>
                            <option value="supplies">Office Supplies</option>
                            <option value="travel">Travel & Transportation</option>
                            <option value="food">Food & Catering</option>
                            <option value="equipment">Equipment</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expenseStatus">Status</label>
                        <select id="expenseStatus">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="expenseVendor" class="required-field">Vendor/Payee</label>
                        <input type="text" id="expenseVendor" placeholder="Enter vendor name" required>
                    </div>
                    <div class="form-group">
                        <label for="expenseDepartment">Department</label>
                        <select id="expenseDepartment">
                            <option value="">Select department</option>
                            <option value="academic">Academic Affairs</option>
                            <option value="admin">Administration</option>
                            <option value="facilities">Facilities</option>
                            <option value="it">IT Services</option>
                            <option value="student">Student Services</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="expenseDescription">Description</label>
                    <textarea id="expenseDescription" rows="2" placeholder="Describe the expense"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">Clear Form</button>
                    <button type="submit" class="btn btn-primary">Add Expense</button>
                </div>
            </form>
            
            <!-- Expense List -->
            <div class="expense-list">
                <h3>Recent Expenses</h3>
                
                <div class="filters">
                    <div class="form-group">
                        <label for="filterCategory">Filter by Category</label>
                        <select id="filterCategory">
                            <option value="">All Categories</option>
                            <option value="utilities">Utilities</option>
                            <option value="supplies">Office Supplies</option>
                            <option value="travel">Travel & Transportation</option>
                            <option value="food">Food & Catering</option>
                            <option value="equipment">Equipment</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filterStatus">Filter by Status</label>
                        <select id="filterStatus">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date Range</label>
                        <div class="date-filter">
                            <input type="date" id="filterStartDate">
                            <div class="date-label">to</div>
                            <input type="date" id="filterEndDate">
                        </div>
                    </div>
                </div>
                
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
                        <tr>
                            <td>Apr 15, 2025</td>
                            <td>Monaco Office Supplies</td>
                            <td><span class="category-badge supplies">Supplies</span></td>
                            <td>Printer paper and toner cartridges</td>
                            <td><span class="status-indicator status-approved"></span> Approved</td>
                            <td class="amount">$245.75</td>
                            <td class="actions">
                                <div class="expense-actions">
                                    <button class="action-btn edit" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Apr 12, 2025</td>
                            <td>City Electric Co.</td>
                            <td><span class="category-badge utilities">Utilities</span></td>
                            <td>Monthly electricity bill</td>
                            <td><span class="status-indicator status-approved"></span> Approved</td>
                            <td class="amount">$1,350.00</td>
                            <td class="actions">
                                <div class="expense-actions">
                                    <button class="action-btn edit" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Apr 10, 2025</td>
                            <td>Global Tech Solutions</td>
                            <td><span class="category-badge equipment">Equipment</span></td>
                            <td>New projector for Room 301</td>
                            <td><span class="status-indicator status-pending"></span> Pending</td>
                            <td class="amount">$780.50</td>
                            <td class="actions">
                                <div class="expense-actions">
                                    <button class="action-btn edit" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Apr 07, 2025</td>
                            <td>Monaco Catering</td>
                            <td><span class="category-badge food">Food</span></td>
                            <td>Faculty meeting refreshments</td>
                            <td><span class="status-indicator status-approved"></span> Approved</td>
                            <td class="amount">$325.00</td>
                            <td class="actions">
                                <div class="expense-actions">
                                    <button class="action-btn edit" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Apr 03, 2025</td>
                            <td>City Taxi Services</td>
                            <td><span class="category-badge travel">Travel</span></td>
                            <td>Transportation for guest speaker</td>
                            <td><span class="status-indicator status-rejected"></span> Rejected</td>
                            <td class="amount">$85.25</td>
                            <td class="actions">
                                <div class="expense-actions">
                                    <button class="action-btn edit" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="pagination">
                    <button class="pagination-btn"><i class="fas fa-chevron-left"></i></button>
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">2</button>
                    <button class="pagination-btn">3</button>
                    <button class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Expense Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Expense</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editExpenseForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editExpenseDate" class="required-field">Date</label>
                            <input type="date" id="editExpenseDate" required>
                        </div>
                        <div class="form-group">
                            <label for="editExpenseAmount" class="required-field">Amount ($)</label>
                            <input type="number" id="editExpenseAmount" min="0" step="0.01" placeholder="Enter amount" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editExpenseCategory" class="required-field">Category</label>
                            <select id="editExpenseCategory" required>
                                <option value="">Select category</option>
                                <option value="utilities">Utilities</option>
                                <option value="supplies">Office Supplies</option>
                                <option value="travel">Travel & Transportation</option>
                                <option value="food">Food & Catering</option>
                                <option value="equipment">Equipment</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editExpenseStatus">Status</label>
                            <select id="editExpenseStatus">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="editExpenseVendor" class="required-field">Vendor/Payee</label>
                        <input type="text" id="editExpenseVendor" placeholder="Enter vendor name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editExpenseDescription">Description</label>
                        <textarea id="editExpenseDescription" rows="2" placeholder="Describe the expense"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary modal-close">Cancel</button>
                <button class="btn btn-primary" id="saveEditBtn">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Delete</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this expense record? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary modal-close">Cancel</button>
                <button class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

    <script>
        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('expenseDate').value = today;
        
        // Modal functionality
        const modals = document.querySelectorAll('.modal');
        const modalCloseBtns = document.querySelectorAll('.modal-close');
        const editBtns = document.querySelectorAll('.action-btn.edit');
        const deleteBtns = document.querySelectorAll('.action-btn.delete');
        
        // Open edit modal
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // In a real application, you would populate the form with the expense data
                document.getElementById('editModal').classList.add('show');
            });
        });
        
        // Open delete modal
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('deleteModal').classList.add('show');
            });
        });
        
        // Close modals
        modalCloseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                modals.forEach(modal => {
                    modal.classList.remove('show');
                });
            });
        });
        
        // Close modal when clicking outside the content
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('show');
                }
            });
        });
        
        // Form submission
        document.getElementById('expenseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would collect form data and add it to your expense list
            alert('Expense added successfully!');
            this.reset();
            document.getElementById('expenseDate').value = today;
        });
        
        // Save edited expense
        document.getElementById('saveEditBtn').addEventListener('click', function() {
            // Here you would update the expense data
            document.getElementById('editModal').classList.remove('show');
            alert('Expense updated successfully!');
        });
        
        // Confirm delete
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            // Here you would delete the expense record
            document.getElementById('deleteModal').classList.remove('show');
            alert('Expense deleted successfully!');
        });
    </script>
</body>
</html>