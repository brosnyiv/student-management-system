<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Payment Receipt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #8B1818;
            --primary-hover: #701010;
            --text-color: #333;
            --light-bg: #f5f5f5;
            --border-color: #ddd;
            --success-color: #2ecc71;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }
        
        .container {
            max-width: 800px;
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
        
        .receipt-container {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            background-color: #fff;
            position: relative;
        }
        
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .institute-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .institute-logo svg {
            width: 50px;
            height: 50px;
        }
        
        .institute-details {
            line-height: 1.4;
        }
        
        .institute-name {
            font-weight: 700;
            font-size: 18px;
            color: var(--primary-color);
        }
        
        .receipt-title {
            text-align: center;
            margin: 20px 0;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
            font-size: 20px;
            font-weight: 600;
        }
        
        .receipt-id {
            text-align: right;
            font-weight: 500;
        }
        
        .receipt-id span {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .receipt-section {
            margin-bottom: 20px;
        }
        
        .receipt-section-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .receipt-table th {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
        }
        
        .receipt-table td {
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .receipt-table .amount {
            text-align: right;
        }
        
        .receipt-total {
            display: flex;
            justify-content: flex-end;
            padding: 10px 0;
            font-weight: 600;
            font-size: 18px;
            border-top: 2px solid var(--primary-color);
            margin-top: 10px;
        }
        
        .receipt-total .label {
            margin-right: 40px;
        }
        
        .payment-options {
            margin-top: 30px;
        }
        
        .payment-type-selector {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .payment-option {
            flex: 1;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .payment-option:hover {
            background-color: #f9f9f9;
        }
        
        .payment-option.selected {
            border-color: var(--primary-color);
            background-color: rgba(139, 24, 24, 0.05);
        }
        
        .payment-option i {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        
        .payment-details {
            margin-top: 20px;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        #printReceipt {
            background-color: var(--success-color);
            color: white;
        }
        
        #printReceipt:hover {
            background-color: #27ae60;
        }
        
        .payment-status {
            position: absolute;
            top: 50px;
            right: 30px;
            transform: rotate(45deg);
            font-size: 20px;
            font-weight: 700;
            color: var(--success-color);
            border: 2px solid var(--success-color);
            padding: 5px 20px;
            border-radius: 4px;
            opacity: 0;
            transition: opacity 0.5s;
        }
        
        .payment-status.show {
            opacity: 1;
        }
        
        .terms-conditions {
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed var(--border-color);
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .signature-line {
            width: 200px;
            border-top: 1px solid var(--border-color);
            margin-top: 5px;
            padding-top: 5px;
            text-align: center;
            font-size: 12px;
        }
        
        .card-input {
            display: flex;
            gap: 10px;
        }
        
        .card-number {
            flex: 3;
        }
        
        .card-cvv {
            flex: 1;
        }
        
        @media print {
            body {
                background-color: white;
            }
            
            .container {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
            
            .form-header, 
            .form-actions, 
            .payment-options,
            .btn {
                display: none;
            }
            
            .receipt-container {
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h2><i class="fas fa-receipt"></i> Payment Receipt</h2>
        </div>
        
        <div class="form-body">
            <form id="paymentForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="studentId" class="required-field">Student ID</label>
                        <input type="text" id="studentId" placeholder="Enter student ID" required>
                    </div>
                    <div class="form-group">
                        <label for="paymentDate" class="required-field">Payment Date</label>
                        <input type="date" id="paymentDate" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="studentName" class="required-field">Student Name</label>
                        <input type="text" id="studentName" placeholder="Enter student name" required>
                    </div>
                    <div class="form-group">
                        <label for="course" class="required-field">Course/Program</label>
                        <select id="course" required>
                            <option value="">Select course</option>
                            <option value="cs">Computer Science</option>
                            <option value="ba">Business Administration</option>
                            <option value="dm">Digital Marketing</option>
                            <option value="gd">Graphic Design</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" placeholder="Enter email address">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" placeholder="Enter phone number">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="paymentType" class="required-field">Payment Type</label>
                        <select id="paymentType" required>
                            <option value="">Select payment type</option>
                            <option value="tuition">Tuition Fee</option>
                            <option value="exam">Examination Fee</option>
                            <option value="registration">Registration Fee</option>
                            <option value="library">Library Fee</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="required-field">Amount ($)</label>
                        <input type="number" id="amount" min="0" step="0.01" placeholder="Enter amount" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Payment Description</label>
                    <textarea id="description" rows="2" placeholder="Additional details about the payment"></textarea>
                </div>
                
                <div class="payment-options">
                    <h3 class="receipt-section-title">Payment Method</h3>
                    
                    <div class="payment-type-selector">
                        <div class="payment-option" data-method="cash">
                            <i class="fas fa-money-bill-wave"></i>
                            <div>Cash</div>
                        </div>
                        <div class="payment-option" data-method="card">
                            <i class="fas fa-credit-card"></i>
                            <div>Credit/Debit Card</div>
                        </div>
                        <div class="payment-option" data-method="bank">
                            <i class="fas fa-university"></i>
                            <div>Bank Transfer</div>
                        </div>
                        <div class="payment-option" data-method="online">
                            <i class="fas fa-globe"></i>
                            <div>Online Payment</div>
                        </div>
                    </div>
                    
                    <div id="cashDetails" class="payment-details" style="display: none;">
                        <div class="form-group">
                            <label for="cashReceived" class="required-field">Cash Received (UGX)</label>
                            <input type="number" id="cashReceived" min="0" step="0.01" placeholder="Enter cash received">
                        </div>
                        <div class="form-group">
                            <label for="cashChange">Change (UGX)</label>
                            <input type="number" id="cashChange" readonly>
                        </div>
                    </div>
                    
                    <div id="cardDetails" class="payment-details" style="display: none;">
                        <div class="form-group">
                            <label for="cardName" class="required-field">Name on Card</label>
                            <input type="text" id="cardName" placeholder="Enter name as it appears on card">
                        </div>
                        <div class="form-group">
                            <label for="cardNumber" class="required-field">Card Information</label>
                            <div class="card-input">
                                <input type="text" id="cardNumber" class="card-number" placeholder="Card number" maxlength="19">
                                <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5">
                                <input type="password" id="cardCVV" class="card-cvv" placeholder="CVV" maxlength="3">
                            </div>
                        </div>
                    </div>
                    
                    <div id="bankDetails" class="payment-details" style="display: none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="bankName" class="required-field">Bank Name</label>
                                <input type="text" id="bankName" placeholder="Enter bank name">
                            </div>
                            <div class="form-group">
                                <label for="transactionId" class="required-field">Transaction ID/Reference</label>
                                <input type="text" id="transactionId" placeholder="Enter transaction reference">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transferDate" class="required-field">Transfer Date</label>
                            <input type="date" id="transferDate">
                        </div>
                    </div>
                    
                    <div id="onlineDetails" class="payment-details" style="display: none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="onlineMethod" class="required-field">Payment Platform</label>
                                <select id="onlineMethod">
                                    <option value="">Select platform</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="stripe">Stripe</option>
                                    <option value="square">Square</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="onlineReference" class="required-field">Payment Reference</label>
                                <input type="text" id="onlineReference" placeholder="Enter reference number">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="receipt-container">
                    <div class="payment-status">PAID</div>
                    
                    <div class="receipt-header">
                        <div class="institute-info">
                            <div class="institute-logo">
                                <svg viewBox="0 0 24 24">
                                    <path fill="#8B1818" d="M12,2L1,8l11,6l9-4.91V17c0,0.55,0.45,1,1,1s1-0.45,1-1V7L12,2z M17,15l-5,3l-5-3V9l5-3l0,0l5,3V15z"/>
                                </svg>
                            </div>
                            <div class="institute-details">
                                <div class="institute-name">MONACO INSTITUTE</div>
                                <div>123 Education Street, Monaco City</div>
                                <div>Phone: (123) 456-7890</div>
                            </div>
                        </div>
                        <div class="receipt-id">
                            Receipt #: <span id="receiptNumber">MI-2025-</span>
                        </div>
                    </div>
                    
                    <div class="receipt-title">OFFICIAL PAYMENT RECEIPT</div>
                    
                    <div class="receipt-section">
                        <div class="receipt-section-title">Student Information</div>
                        <table class="receipt-table">
                            <tr>
                                <td>Student ID:</td>
                                <td id="receiptStudentId">-</td>
                                <td>Payment Date:</td>
                                <td id="receiptPaymentDate">-</td>
                            </tr>
                            <tr>
                                <td>Student Name:</td>
                                <td id="receiptStudentName">-</td>
                                <td>Course/Program:</td>
                                <td id="receiptCourse">-</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="receipt-section">
                        <div class="receipt-section-title">Payment Details</div>
                        <table class="receipt-table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Payment Type</th>
                                    <th>Payment Method</th>
                                    <th class="amount">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="receiptDescription">-</td>
                                    <td id="receiptPaymentType">-</td>
                                    <td id="receiptPaymentMethod">-</td>
                                    <td class="amount" id="receiptAmount">UGX 0.00</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="receipt-total">
                            <div class="label">Total Paid:</div>
                            <div class="value" id="receiptTotal">UGX 0.00</div>
                        </div>
                    </div>
                    
                    <div class="signature-section">
                        <div class="signature-line">Student Signature</div>
                        <div class="signature-line">Authorized Signature</div>
                    </div>
                    
                    <div class="terms-conditions">
                        <strong>Terms & Conditions:</strong>
                        <ul>
                            <li>This receipt is valid only when stamped and signed by an authorized person.</li>
                            <li>Payment for tuition fees is non-refundable after the course has commenced.</li>
                            <li>Please retain this receipt for future reference.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="form-actions">
                    <div>
                        <button type="reset" class="btn btn-secondary">Clear Form</button>
                    </div>
                    <div>
                        <button type="button" id="printReceipt" class="btn">Print Receipt</button>
                        <button type="submit" class="btn btn-primary">Process Payment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // API base URL
        const API_BASE_URL = 'http://localhost/student_management/api';

        // Generate a unique receipt number
        function generateReceiptNumber() {
            const date = new Date();
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            return `MI-${year}${month}${day}-${random}`;
        }
        
        document.getElementById('receiptNumber').textContent = generateReceiptNumber();
        
        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('paymentDate').value = today;
        
        // Toggle payment method details
        const paymentOptions = document.querySelectorAll('.payment-option');
        paymentOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                paymentOptions.forEach(opt => opt.classList.remove('selected'));
                
                // Add selected class to clicked option
                this.classList.add('selected');
                
                // Hide all payment details
                document.querySelectorAll('.payment-details').forEach(detail => {
                    detail.style.display = 'none';
                });
                
                // Show details for selected payment method
                const method = this.getAttribute('data-method');
                document.getElementById(`${method}Details`).style.display = 'block';
                
                // Update receipt payment method
                const methodTexts = {
                    'cash': 'Cash',
                    'card': 'Credit/Debit Card',
                    'bank': 'Bank Transfer',
                    'online': 'Online Payment'
                };
                document.getElementById('receiptPaymentMethod').textContent = methodTexts[method];
            });
        });
        
        // Update receipt as form is filled
        const formFields = document.querySelectorAll('#paymentForm input, #paymentForm select, #paymentForm textarea');
        formFields.forEach(field => {
            field.addEventListener('input', updateReceipt);
        });
        
        function updateReceipt() {
            // Update student info
            const studentId = document.getElementById('studentId').value || '-';
            document.getElementById('receiptStudentId').textContent = studentId;
            
            const studentName = document.getElementById('studentName').value || '-';
            document.getElementById('receiptStudentName').textContent = studentName;
            
            const paymentDate = document.getElementById('paymentDate').value;
            document.getElementById('receiptPaymentDate').textContent = paymentDate ? formatDate(paymentDate) : '-';
            
            const courseSelect = document.getElementById('course');
            const courseText = courseSelect.options[courseSelect.selectedIndex]?.text || '-';
            document.getElementById('receiptCourse').textContent = courseText;
            
            // Update payment details
            const typeSelect = document.getElementById('paymentType');
            const paymentTypeText = typeSelect.options[typeSelect.selectedIndex]?.text || '-';
            document.getElementById('receiptPaymentType').textContent = paymentTypeText;
            
            const description = document.getElementById('description').value || paymentTypeText;
            document.getElementById('receiptDescription').textContent = description;
            
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const formattedAmount = amount.toFixed(2);
            document.getElementById('receiptAmount').textContent = `$${formattedAmount}`;
            document.getElementById('receiptTotal').textContent = `$${formattedAmount}`;
            
            // Calculate change if cash payment
            const cashReceived = document.getElementById('cashReceived');
            const cashChange = document.getElementById('cashChange');
            
            if (cashReceived && cashReceived.value) {
                const received = parseFloat(cashReceived.value) || 0;
                const change = received - amount;
                cashChange.value = change > 0 ? change.toFixed(2) : '0.00';
            }
        }
        
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }
        
        // Form submission
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate if payment method is selected
            const selectedMethod = document.querySelector('.payment-option.selected');
            if (!selectedMethod) {
                alert('Please select a payment method');
                return;
            }
            
            try {
                // Prepare payment data
                const paymentData = {
                    studentId: document.getElementById('studentId').value,
                    studentName: document.getElementById('studentName').value,
                    paymentDate: document.getElementById('paymentDate').value,
                    course: document.getElementById('course').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    paymentType: document.getElementById('paymentType').value,
                    amount: parseFloat(document.getElementById('amount').value),
                    description: document.getElementById('description').value,
                    paymentMethod: selectedMethod.getAttribute('data-method'),
                    paymentDetails: {}
                };
                
                // Add payment method specific details
                switch (paymentData.paymentMethod) {
                    case 'cash':
                        paymentData.paymentDetails = {
                            received: parseFloat(document.getElementById('cashReceived').value),
                            change: parseFloat(document.getElementById('cashChange').value)
                        };
                        break;
                    case 'card':
                        paymentData.paymentDetails = {
                            name: document.getElementById('cardName').value,
                            number: document.getElementById('cardNumber').value,
                            expiry: document.getElementById('cardExpiry').value,
                            cvv: document.getElementById('cardCVV').value
                        };
                        break;
                    case 'bank':
                        paymentData.paymentDetails = {
                            bankName: document.getElementById('bankName').value,
                            transactionId: document.getElementById('transactionId').value,
                            transferDate: document.getElementById('transferDate').value
                        };
                        break;
                    case 'online':
                        paymentData.paymentDetails = {
                            platform: document.getElementById('onlineMethod').value,
                            reference: document.getElementById('onlineReference').value
                        };
                        break;
                }
                
                // Send payment data to backend
                const response = await fetch(`${API_BASE_URL}/create_payment.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(paymentData)
                });
                
                if (!response.ok) {
                    throw new Error('Payment processing failed');
                }
                
                const result = await response.json();
                
                // Show paid stamp
                document.querySelector('.payment-status').classList.add('show');
                
                // Update receipt number with the one from backend
                document.getElementById('receiptNumber').textContent = result.payment.receipt_number;
                
                alert('Payment processed successfully!');
                
                // Reset form
                this.reset();
                document.getElementById('paymentDate').value = today;
                
            } catch (error) {
                console.error('Error:', error);
                alert('Payment processing failed. Please try again.');
            }
        });
        
        // Print receipt functionality
        document.getElementById('printReceipt').addEventListener('click', function() {
            window.print();
        });
    </script>
</body>
</html>