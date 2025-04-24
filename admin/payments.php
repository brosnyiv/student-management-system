<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Payments</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      
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
            <div class="welcome-text">
                <h1>MONACO INSTITUTE</h1>
                <p>Financial Management</p>
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
            <input type="text" placeholder="Search payments, students, or invoices..." aria-label="Search">
        </div>

        <!-- Financial Overview Cards -->
        <div class="financial-overview">
            <div class="finance-card collected">
                <div class="finance-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="finance-label">Total Fees Collected</div>
                <div class="finance-value">$2,150,000</div>
                <div class="finance-additional positive">
                    <i class="fas fa-arrow-up"></i> 12% from last month
                </div>
            </div>
            
            <div class="finance-card due">
                <div class="finance-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="finance-label">Total Fees Due (Unpaid)</div>
                <div class="finance-value">$450,000</div>
                <div class="finance-additional negative">
                    <i class="fas fa-arrow-up"></i> 5% from last month
                </div>
            </div>
            
            <div class="finance-card expenses">
                <div class="finance-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="finance-label">Total School Expenses</div>
                <div class="finance-value">$1,800,000</div>
                <div class="finance-additional neutral">
                    <i class="fas fa-equals"></i> Same as last month
                </div>
            </div>
            
            <div class="finance-card balance">
                <div class="finance-icon">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="finance-label">Net Balance</div>
                <div class="finance-value">$350,000</div>
                <div class="finance-additional positive">
                    <i class="fas fa-arrow-up"></i> 15% from last month
                </div>
            </div>

            <div class="finance-card stats">
                <div class="finance-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="finance-label">Students with Dues</div>
                <div class="finance-value">235</div>
                <div class="finance-additional">
                    <span class="negative">25 overdue</span> • <span class="neutral">210 current</span>
                </div>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="actions-bar">
            <button class="action-button">
                <i class="fas fa-bell"></i> Send Payment Reminders
            </button>
            <button class="action-button" onclick="window.location.href='recipt.html'">
                <i class="fas fa-file-invoice"></i> Record New Payment
            </button>
            <button class="action-button"  onclick="window.location.href='expenses.html'">
                <i class="fas fa-plus-circle" ></i> Add New Expense
            </button>
            <button class="action-button">
                <i class="fas fa-file-excel" ></i> Export to Excel
            </button>
            <button class="action-button">
                <i class="fas fa-file-pdf"></i> Generate PDF Report
            </button>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <div class="chart-container">
                <div class="section-header">
                    <div class="section-title" ><i class="fas fa-chart-bar"></i> Monthly Income vs Expenses</div>
                </div>
                <div class="chart">
                    <div class="bar-chart">
                        <div class="bar-container">
                           <!--  <div class="bar" style="height: 10px;"></div> -->
                            <div class="expense-bar" style="height: 20px;"></div>
                            <div class="bar-label">Jan</div>
                        </div>
                        <div class="bar-container">
                            <!-- <div class="bar" style="height: 150px;"></div> -->
                            <div class="expense-bar" style="height: 30px;"></div>
                            <div class="bar-label">Feb</div>
                        </div>
                        <div class="bar-container">
                            <!-- <div class="bar" style="height: 100px;"></div> -->
                            <div class="expense-bar" style="height: 40px;"></div>
                            <div class="bar-label">Mar</div>
                        </div>
                        <div class="bar-container">
                            <!-- <div class="bar" style="height: 180px;"></div> -->
                            <div class="expense-bar" style="height: 50px;"></div>
                            <div class="bar-label">Apr</div>
                        </div>
                        <div class="bar-container">
                            <!-- <div class="bar" style="height: 140px;"></div> -->
                            <div class="expense-bar" style="height: 20px;"></div>
                            <div class="bar-label">May</div>
                        </div>
                        <div class="bar-container">
                            <!-- <div class="bar" style="height: 160px;"></div> -->
                            <div class="expense-bar" style="height: 40px;"></div>
                            <div class="bar-label">Jun</div>
                        </div>
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
                    <div class="pie-chart"></div>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #ff0303;"></div>
                        <span>Fully Paid (60%)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #ffc107;"></div>
                        <span>Partially Paid (25%)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #dc3545;"></div>
                        <span>Not Paid (15%)</span>
                    </div>
                </div>
            </div>

            <div class="chart-container"  style="margin-top: 20px;">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-chart-line" ></i> Fees Collection Over Time</div>
                </div>
                <div class="chart">
                    <!-- Simplified line chart representation -->
                    <svg width="100%" height="100%" viewBox="0 0 500 200">
                        <defs>
                            <linearGradient id="gradientBg" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#8B1818;stop-opacity:0.2" />
                                <stop offset="100%" style="stop-color:#8B1818;stop-opacity:0" />
                            </linearGradient>
                        </defs>
                        <!-- Grid lines -->
                        <line x1="50" y1="20" x2="50" y2="180" stroke="#e0e0e0" stroke-width="1" />
                        <line x1="133" y1="20" x2="133" y2="180" stroke="#e0e0e0" stroke-width="1" />
                        <line x1="216" y1="20" x2="216" y2="180" stroke="#e0e0e0" stroke-width="1" />
                        <line x1="299" y1="20" x2="299" y2="180" stroke="#e0e0e0" stroke-width="1" />
                        <line x1="382" y1="20" x2="382" y2="180" stroke="#e0e0e0" stroke-width="1" />
                        <line x1="465" y1="20" x2="465" y2="180" stroke="#e0e0e0" stroke-width="1" />
                        
                        <line x1="50" y1="20" x2="465" y2="20" stroke="#e0e0e0" stroke-width="1" />
                        <line x1="50" y1="60" x2="465" y2="60" stroke="#e0e0e0" stroke-width="1" />
                        <line x1="50" y1="100" x2="465" y2="100" stroke="#e0e0e0" stroke-width="1" />
                        <line x1="50" y1="140" x2="465" y2="140" stroke="#e0e0e0" stroke-width="1" />
                        <line x1="50" y1="180" x2="465" y2="180" stroke="#e0e0e0" stroke-width="1" />
                        
                        <!-- Area fill -->
                        <path d="M50,120 L133,140 L216,90 L299,70 L382,40 L465,20 L465,180 L50,180 Z" fill="url(#gradientBg)" />
                        
                        <!-- Line -->
                        <path d="M50,120 L133,140 L216,90 L299,70 L382,40 L465,20" fill="none" stroke="#8B1818" stroke-width="3" />
                        
                        <!-- Data points -->
                        <circle cx="50" cy="120" r="4" fill="#8B1818" />
                        <circle cx="133" cy="140" r="4" fill="#8B1818" />
                        <circle cx="216" cy="90" r="4" fill="#8B1818" />
                        <circle cx="299" cy="70" r="4" fill="#8B1818" />
                        <circle cx="382" cy="40" r="4" fill="#8B1818" />
                        <circle cx="465" cy="20" r="4" fill="#8B1818" />
                        
                        <!-- X-axis labels -->
                        <text x="50" y="195" text-anchor="middle" font-size="12" fill="#6c757d">Nov</text>
                        <text x="133" y="195" text-anchor="middle" font-size="12" fill="#6c757d">Dec</text>
                        <text x="216" y="195" text-anchor="middle" font-size="12" fill="#6c757d">Jan</text>
                        <text x="299" y="195" text-anchor="middle" font-size="12" fill="#6c757d">Feb</text>
                        <text x="382" y="195" text-anchor="middle" font-size="12" fill="#6c757d">Mar</text>
                        <text x="465" y="195" text-anchor="middle" font-size="12" fill="#6c757d">Apr</text>
                    </svg>
                </div>
            </div>
            
            <div class="chart-container"  style="margin-top: 20px;">
                <div class="section-header">
                    <div class="section-title"><i class="fas fa-chart-pie"></i> Top Spending Categories</div>
                </div>
                <div class="chart">
                    <div class="donut-chart"></div>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #8B1818;"></div>
                        <span>Staff Salaries (30%)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #E74C3C;"></div>
                        <span>Maintenance (15%)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #F39C12;"></div>
                        <span>Equipment (20%)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #3498DB;"></div>
                        <span>Utilities (35%)</span>
                    </div>
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
                <div class="tab-content">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="classFilter">Class:</label>
                            <select id="classFilter" class="filter-input">
                                <option value="">All Classes</option>
                                <option value="1">Computer Science</option>
                                <option value="2">Business Administration</option>
                                <option value="3">Digital Marketing</option>
                                <option value="4">Graphic Design</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="termFilter">Term:</label>
                            <select id="termFilter" class="filter-input">
                                <option value="">All Terms</option>
                                <option value="1">Term 1</option>
                                <option value="2">Term 2</option>
                                <option value="3">Term 3</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="statusFilter">Status:</label>
                            <select id="statusFilter" class="filter-input">
                                <option value="">All Statuses</option>
                                <option value="1">Overdue</option>
                                <option value="2">Partially Paid</option>
                                <option value="3">Not Paid</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <input type="text" placeholder="Search student..." class="search-box" aria-label="Search students">
                        </div>
                        <button class="action-button">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
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
                            <tr>
                                <td>ST001</td>
                                <td>Michael Johnson</td>
                                <td>Computer Science</td>
                                <td>$2,500</td>
                                <td>Apr 20, 2025</td>
                                <td><span class="status-pill status-unpaid">Not Paid</span></td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-envelope"></i></button>
                                    <button class="action-icon"><i class="fas fa-money-bill"></i></button>
                                    <button class="action-icon"><i class="fas fa-info-circle"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>ST002</td>
                                <td>Sarah Smith</td>
                                <td>Business Administration</td>
                                <td>$1,800</td>
                                <td>Apr 15, 2025</td>
                                <td><span class="status-pill status-partial">Partially Paid</span></td>
                                <td>
                                    <button class="action-icon edit"><i class="fas fa-envelope"></i></button>
                                    <button class="action-icon"><i class="fas fa-money-bill"></i></button>
                                    <button class="action-icon"><i class="fas fa-info-circle"></i></button>
                                </tr>
                                <tr>
                                    <td>ST003</td>
                                    <td>Emily Davis</td>
                                    <td>Digital Marketing</td>
                                    <td>$3,200</td>
                                    <td>Apr 25, 2025</td>
                                    <td><span class="status-pill status-overdue">Overdue</span></td>
                                    <td>
                                        <button class="action-icon edit"><i class="fas fa-envelope"></i></button>
                                        <button class="action-icon"><i class="fas fa-money-bill"></i></button>
                                        <button class="action-icon"><i class="fas fa-info-circle"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
            



            // JavaScript for tab functionality
            const tabs = document.querySelectorAll('.tab'); 

            const tabContents = document.querySelectorAll('.tab-content');
            tabs.forEach((tab, index) => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(tc => tc.style.display = 'none');
                    tab.classList.add('active');
                    tabContents[index].style.display = 'block';
                });
            });
            // Show the first tab content by default
            tabContents[0].style.display = 'block';
            tabContents[1].style.display = 'none';
            tabContents[2].style.display = 'none';
            // JavaScript for filter functionality (dummy data)
            const filterButton = document.querySelector('.action-button');
            filterButton.addEventListener('click', () => {
                const classFilter = document.getElementById('classFilter').value;
                const termFilter = document.getElementById('termFilter').value;
                const statusFilter = document.getElementById('statusFilter').value;
                const searchInput = document.querySelector('.search-box').value.toLowerCase();

                // Dummy filter logic (replace with actual filtering logic)
                console.log(`Filters applied: Class - ${classFilter}, Term - ${termFilter}, Status - ${statusFilter}, Search - ${searchInput}`);
            });

            // JavaScript for action icons (dummy functionality)
            actionIcons.forEach(icon => {
                icon.addEventListener('click', () => {
                    const action = icon.classList.contains('edit') ? 'Edit' : 'View Details';
                    console.log(`${action} clicked`);
                });
            });

            // JavaScript for pagination (dummy functionality)
            const paginationButtons = document.querySelectorAll('.pagination-button');
            paginationButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const page = button.textContent;
                    console.log(`Page ${page} clicked`);
                });
            });

            // JavaScript for search box (dummy functionality)
            const searchBox = document.querySelector('.search-box');
            searchBox.addEventListener('input', () => {
                const query = searchBox.value.toLowerCase();
                console.log(`Searching for: ${query}`);
            });

            // JavaScript for donut chart (dummy functionality)
            const donutChart = document.querySelector('.donut-chart');
            donutChart.style.width = '200px';
            donutChart.style.height = '200px';
            donutChart.style.borderRadius = '50%';
            donutChart.style.background = 'conic-gradient(#28a745 0%, #28a745 60%, #ffc107 60%, #ffc107 85%, #dc3545 85%, #dc3545 100%)';
            donutChart.style.position = 'relative';
            donutChart.style.display = 'flex';
            donutChart.style.alignItems = 'center';
            donutChart.style.justifyContent = 'center';
            donutChart.innerHTML = '<div style="width: 80px; height: 80px; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold;">15%</div>';
            donutChart.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
            donutChart.style.margin = '0 auto';
            donutChart.style.padding = '20px';
            donutChart.style.border = '2px solid #8B1818';
            donutChart.style.borderRadius = '50%';
            donutChart.style.transition = 'transform 0.3s ease-in-out';
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
            barChart.style.border = '2px solid #8B1818';
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
           
            
    document.addEventListener("DOMContentLoaded", () => {
        const tabs = document.querySelectorAll(".tab");
        const tabContents = document.querySelectorAll(".tab-content");
    
        // Add click event listeners to all tabs
        tabs.forEach((tab, index) => {
            tab.addEventListener("click", () => {
                // Remove the active class from all tabs and tab contents
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
    });

  
    document.addEventListener("DOMContentLoaded", () => {
        const tabs = document.querySelectorAll(".tab");
        const tabContents = document.querySelectorAll(".tab-content");

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

        // Function to load payment history data
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
    });
</script>

    
        </script>
    
</body>
</html>
<!-- End of HTML Document -->