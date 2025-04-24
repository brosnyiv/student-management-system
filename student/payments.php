<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Student Portal - Payments</title>
    <link rel="stylesheet" href="dash.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-logo">
                <img src="logo.png" alt="Monaco Institute Logo" class="logo-img">
            </div>
            <div class="sidebar-header">
                <h2>Monaco Institute</h2>
                <p>Empowering Professional Skills</p>
            </div>
            <ul class="nav-menu">
                <li class="nav-item" onclick="window.location.href='dash.html'">Dashboard</li>
                <li class="nav-item" onclick="window.location.href='course.html'">My Courses</li>
                <li class="nav-item" onclick="window.location.href='asignments.html'">Assignments</li>
                <li class="nav-item" onclick="window.location.href='results.html'">Results</li>
                <li class="nav-item" onclick="window.location.href='attendence.html'">Attendance</li>
                <li class="nav-item" onclick="window.location.href='payments.html'">Payments</li>
                <li class="nav-item" onclick="window.location.href='drop semester.html'">Drop Semester</li>
                <li class="nav-item" onclick="window.location.href='notices.html'">Notices</li>
                <li class="nav-item" onclick="window.location.href='messages.html'">Messages <span class="badge">3</span></li>
                <li class="logout-item" onclick="window.location.href='login.html'">Log Out</li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1>Payments Management</h1>
                    <span class="header-date" id="current-date">Saturday, April 12, 2025</span>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search payments...">
                </div>
                <div class="user-actions">
                    <i class="settings-icon">⚙️</i>
                </div>
            </div>
            
            <!-- Payments Section -->
            <div id="payments" class="content-section">
                <div class="student-profile">
                    <div class="student-avatar">JS</div>
                    <div class="student-info">
                        <h2>John Smith</h2>
                        <p>Computer Science, Year 3</p>
                        <p>Student ID: STU2025001</p>
                        <p>Payment Code: PAY2025-JS-001</p>
                        <p>Semester: Spring 2025</p>
                        <p>Course: Bachelor of Science in Computer Science</p>
                        <p>Level: 300 (Third Year)</p>
                    </div>
                </div>
                
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <h3>Total Fee</h3>
                            <div class="card-icon blue">$</div>
                        </div>
                        <div class="card-number">$5,000</div>
                        <div class="card-label">Spring Semester 2025</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Amount Paid</h3>
                            <div class="card-icon green">$</div>
                        </div>
                        <div class="card-number">$3,750</div>
                        <div class="card-label">75% Completed</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Balance Due</h3>
                            <div class="card-icon red">$</div>
                        </div>
                        <div class="card-number">$1,250</div>
                        <div class="card-label">Due April 30, 2025</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Payment Status</h3>
                            <div class="card-icon orange">PS</div>
                        </div>
                        <div class="card-number">Partial</div>
                        <div class="card-label">2 of 3 Installments</div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-header">
                        <h3>Payment Summary</h3>
                        <div class="action-buttons">
                            <button class="primary-btn" onclick="generateReceipt()">Generate Receipt</button>
                        </div>
                    </div>
                    
                    <div class="progress-container" style="height: 20px; margin: 20px 0;">
                        <div class="progress-bar" style="width: 75%; height: 100%;">
                            <span style="padding: 0 10px; line-height: 20px; color: white;">75% Paid</span>
                        </div>
                    </div>
                    
                    <div class="payment-details" style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
                        <div style="flex: 1; min-width: 200px;">
                            <h4>Payment Plan</h4>
                            <p>3 Installments</p>
                            <p><strong>1st Installment:</strong> $2,000 (Jan 15, 2025) - <span class="status paid">Paid</span></p>
                            <p><strong>2nd Installment:</strong> $1,750 (Mar 01, 2025) - <span class="status paid">Paid</span></p>
                            <p><strong>3rd Installment:</strong> $1,250 (Apr 30, 2025) - <span class="status unpaid">Pending</span></p>
                        </div>
                        
                        <div style="flex: 1; min-width: 200px;">
                            <h4>Payment Methods</h4>
                            <p><strong>Online Banking:</strong> Yes</p>
                            <p><strong>Credit Card:</strong> Yes</p>
                            <p><strong>Bank Transfer:</strong> Yes</p>
                            <p><strong>Cash Payment:</strong> No</p>
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-header">
                        <h3>Make a Payment</h3>
                    </div>
                    
                    <div class="payment-form" style="background-color: #f9f9f9; padding: 20px; border-radius: 5px;">
                        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 15px;">
                            <div style="flex: 1; min-width: 200px;">
                                <label for="amount" style="display: block; margin-bottom: 5px;">Amount ($)</label>
                                <input type="number" id="amount" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" value="1250">
                            </div>
                            
                            <div style="flex: 1; min-width: 200px;">
                                <label for="payment-method" style="display: block; margin-bottom: 5px;">Payment Method</label>
                                <select id="payment-method" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    <option value="credit-card">Credit Card</option>
                                    <option value="bank-transfer">Bank Transfer</option>
                                    <option value="online-banking">Online Banking</option>
                                </select>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 15px;">
                            <div style="flex: 1; min-width: 200px;">
                                <label for="description" style="display: block; margin-bottom: 5px;">Description</label>
                                <input type="text" id="description" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" value="Spring 2025 - Final Installment">
                            </div>
                            
                            <div style="flex: 1; min-width: 200px;">
                                <label for="payment-date" style="display: block; margin-bottom: 5px;">Payment Date</label>
                                <input type="date" id="payment-date" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" value="2025-04-12">
                            </div>
                        </div>
                        
                        <button class="primary-btn" style="width: 100%; margin-top: 10px; padding: 10px;">Proceed to Payment</button>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-header">
                        <h3>Payment History</h3>
                        <div class="action-buttons">
                            <button class="secondary-btn" onclick="exportPaymentHistory()">Export History</button>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TRX-20250301-0589</td>
                                <td>Mar 01, 2025</td>
                                <td>Spring 2025 - Second Installment</td>
                                <td>Credit Card</td>
                                <td>$1,750</td>
                                <td><span class="status paid">Completed</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewReceipt('TRX-20250301-0589')">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>TRX-20250115-0231</td>
                                <td>Jan 15, 2025</td>
                                <td>Spring 2025 - First Installment</td>
                                <td>Bank Transfer</td>
                                <td>$2,000</td>
                                <td><span class="status paid">Completed</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewReceipt('TRX-20250115-0231')">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>TRX-20241205-1102</td>
                                <td>Dec 05, 2024</td>
                                <td>Fall 2024 - Late Payment Fee</td>
                                <td>Online Banking</td>
                                <td>$75</td>
                                <td><span class="status paid">Completed</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewReceipt('TRX-20241205-1102')">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>TRX-20241010-0789</td>
                                <td>Oct 10, 2024</td>
                                <td>Fall 2024 - Final Installment</td>
                                <td>Credit Card</td>
                                <td>$1,500</td>
                                <td><span class="status paid">Completed</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewReceipt('TRX-20241010-0789')">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>TRX-20240901-0456</td>
                                <td>Sep 01, 2024</td>
                                <td>Fall 2024 - Second Installment</td>
                                <td>Bank Transfer</td>
                                <td>$1,750</td>
                                <td><span class="status paid">Completed</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewReceipt('TRX-20240901-0456')">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>TRX-20240715-0123</td>
                                <td>Jul 15, 2024</td>
                                <td>Fall 2024 - First Installment</td>
                                <td>Online Banking</td>
                                <td>$2,000</td>
                                <td><span class="status paid">Completed</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewReceipt('TRX-20240715-0123')">View</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="section">
                    <div class="section-header">
                        <h3>Other Fees</h3>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Fee Type</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Technology Fee</td>
                                <td>$150</td>
                                <td>Apr 20, 2025</td>
                                <td><span class="status unpaid">Unpaid</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="payFee('tech-fee')">Pay Now</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Library Access</td>
                                <td>$75</td>
                                <td>Apr 20, 2025</td>
                                <td><span class="status paid">Paid</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewReceipt('lib-fee-25')">Receipt</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Laboratory Fee (CS315)</td>
                                <td>$200</td>
                                <td>Apr 20, 2025</td>
                                <td><span class="status unpaid">Unpaid</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="payFee('lab-fee')">Pay Now</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Student Activity Fee</td>
                                <td>$100</td>
                                <td>Apr 20, 2025</td>
                                <td><span class="status paid">Paid</span></td>
                                <td class="action-cell">
                                    <button class="action-btn view-btn" onclick="viewReceipt('act-fee-25')">Receipt</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Display current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        // Function to show different sections
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => {
                section.style.display = 'none';
            });
            
            // Show the selected section
            document.getElementById(sectionId).style.display = 'block';
            
            // Update active nav item
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.classList.remove('active');
                if(item.getAttribute('onclick').includes(sectionId)) {
                    item.classList.add('active');
                }
            });
        }
        
        // Function to view payment receipt
        function viewReceipt(receiptId) {
            alert('Viewing receipt: ' + receiptId);
            // In a real application, this would open a receipt view
        }
        
        // Function to generate receipt
        function generateReceipt() {
            alert('Generating receipt for all payments...');
            // In a real application, this would generate a comprehensive receipt
        }
        
        // Function to export payment history
        function exportPaymentHistory() {
            alert('Exporting payment history...');
            // In a real application, this would export payment history to CSV/PDF
        }
        
        // Function to pay additional fees
        function payFee(feeId) {
            alert('Proceeding to pay fee: ' + feeId);
            // In a real application, this would redirect to payment gateway
        }
        
        // Function to handle logout
        function logout() {
            alert('Logging out...');
            // In a real application, this would handle the logout process
        }
    </script>
</body>
</html>