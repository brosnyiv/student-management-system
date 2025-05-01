<?php
session_start(); // Start the session
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'dbconnect.php'; // Include the database connection file

// Initialize variables
$success_message = "";
$error_message = "";
$receipt_number = "MI-" . date('Ymd') . "-" . rand(1000, 9999);

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $student_id = mysqli_real_escape_string($conn, $_POST['studentId']);
    $student_name = mysqli_real_escape_string($conn, $_POST['studentName']);
    $payment_date = mysqli_real_escape_string($conn, $_POST['paymentDate']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $payment_type = mysqli_real_escape_string($conn, $_POST['paymentType']);
    $amount = floatval($_POST['amount']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['paymentMethod']);
    
    // Process payment based on method
    $payment_details = [];
    $save_details = false;
    
    switch ($payment_method) {
        case 'cash':
            $cash_received = isset($_POST['cashReceived']) ? floatval($_POST['cashReceived']) : 0;
            $cash_change = isset($_POST['cashChange']) ? floatval($_POST['cashChange']) : 0;
            $save_details = true;
            break;
            
        case 'card':
            $card_name = mysqli_real_escape_string($conn, $_POST['cardName']);
            $card_number = mysqli_real_escape_string($conn, $_POST['cardNumber']);
            // Get last 4
            $card_last_four = substr($card_number, -4);
            $payment_details['card_name'] = $card_name;
            $payment_details['card_last_four'] = $card_last_four;
            $save_details = true;
            break;
            
        case 'bank':
            $bank_name = mysqli_real_escape_string($conn, $_POST['bankName']);
            $transaction_id = mysqli_real_escape_string($conn, $_POST['transactionId']);
            $payment_details['bank_name'] = $bank_name;
            $payment_details['transaction_reference'] = $transaction_id;
            $save_details = true;
            break;
            
        case 'online':
            $online_method = mysqli_real_escape_string($conn, $_POST['onlineMethod']);
            $online_reference = mysqli_real_escape_string($conn, $_POST['onlineReference']);
            $payment_details['online_platform'] = $online_method;
            $payment_details['transaction_reference'] = $online_reference;
            $save_details = true;
            break;
    }
    
    // Staff ID (you might want to get this from the session)
    $processed_by = 1; // Replace with actual staff ID from session
    
    // Insert payment receipt
    $sql = "INSERT INTO payment_receipts (receipt_number, student_id, course_id, payment_date, amount, payment_method, payment_type, description, processed_by) 
            VALUES ('$receipt_number', '$student_id', '$course', '$payment_date', $amount, '$payment_method', '$payment_type', '$description', $processed_by)";
    
    if (mysqli_query($conn, $sql)) {
        $receipt_id = mysqli_insert_id($conn);
        
        // If we have payment details, save them
        if ($save_details) {
            if ($payment_method == 'card') {
                $card_name = $payment_details['card_name'];
                $card_last_four = $payment_details['card_last_four'];
                
                $detail_sql = "INSERT INTO payment_method_details (receipt_id, card_name, card_last_four) 
                               VALUES ($receipt_id, '$card_name', '$card_last_four')";
            } 
            elseif ($payment_method == 'bank') {
                $bank_name = $payment_details['bank_name'];
                $transaction_reference = $payment_details['transaction_reference'];
                
                $detail_sql = "INSERT INTO payment_method_details (receipt_id, bank_name, transaction_reference) 
                               VALUES ($receipt_id, '$bank_name', '$transaction_reference')";
            }
            elseif ($payment_method == 'online') {
                $online_platform = $payment_details['online_platform'];
                $transaction_reference = $payment_details['transaction_reference'];
                
                $detail_sql = "INSERT INTO payment_method_details (receipt_id, online_platform, transaction_reference) 
                               VALUES ($receipt_id, '$online_platform', '$transaction_reference')";
            }
            
            if (isset($detail_sql)) {
                mysqli_query($conn, $detail_sql);
            }
        }
        
        // Set success message
        $success_message = "Payment processed successfully!";
    } else {
        $error_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Function to fetch student data
function getStudentData($conn, $studentId) {
    $studentId = mysqli_real_escape_string($conn, $studentId);
    $query = "SELECT students.student_name, students.email, students.phone, courses.id as course_id, courses.course_name 
              FROM students 
              LEFT JOIN courses ON students.course_id = courses.id 
              WHERE students.student_id = '$studentId'";
    
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute - Payment Receipt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS styles omitted as requested -->
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
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form id="paymentForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="studentId" class="required-field">Student ID</label>
                        <input type="text" id="studentId" name="studentId" placeholder="Enter student ID" required>
                    </div>
                    <div class="form-group">
                        <label for="paymentDate" class="required-field">Payment Date</label>
                        <input type="date" id="paymentDate" name="paymentDate" value="<?php echo date('Y-m-d'); ?>" readonly required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="studentName" class="required-field">Student Name</label>
                        <input type="text" id="studentName" name="studentName" placeholder="Enter student name" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="course" class="required-field">Course/Program</label>
                        <select id="course" name="course" readonly required>
                            <option value="">Select course</option>
                            <?php
                            // Fetch courses from database
                            $courses_query = "SELECT id, course_name FROM courses";
                            $courses_result = mysqli_query($conn, $courses_query);
                            
                            if ($courses_result && mysqli_num_rows($courses_result) > 0) {
                                while ($course = mysqli_fetch_assoc($courses_result)) {
                                    echo '<option value="' . $course['id'] . '">' . $course['course_name'] . '</option>';
                                }
                            } else {
                                // Fallback options if database query fails
                                echo '<option value="1">Computer Science</option>';
                                echo '<option value="2">Business Administration</option>';
                                echo '<option value="3">Digital Marketing</option>';
                                echo '<option value="4">Graphic Design</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter email address" readonly>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter phone number" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="paymentType" class="required-field">Payment Type</label>
                        <select id="paymentType" name="paymentType" required>
                            <option value="">Select payment type</option>
                            <option value="tuition">Tuition Fee</option>
                            <option value="exam">Examination Fee</option>
                            <option value="registration">Registration Fee</option>
                            <option value="library">Library Fee</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="required-field">Amount (UGX)</label>
                        <input type="number" id="amount" name="amount" min="0" step="0.01" placeholder="Enter amount" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Payment Description</label>
                    <textarea id="description" name="description" rows="2" placeholder="Additional details about the payment"></textarea>
                </div>
                
                <div class="payment-options">
                    <h3 class="receipt-section-title">Payment Method</h3>
                    <input type="hidden" id="paymentMethod" name="paymentMethod" value="">
                    
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
                            <input type="number" id="cashReceived" name="cashReceived" min="0" step="0.01" placeholder="Enter cash received">
                        </div>
                        <div class="form-group">
                            <label for="cashChange">Change (UGX)</label>
                            <input type="number" id="cashChange" name="cashChange" readonly>
                        </div>
                    </div>
                    
                    <div id="cardDetails" class="payment-details" style="display: none;">
                        <div class="form-group">
                            <label for="cardName" class="required-field">Name on Card</label>
                            <input type="text" id="cardName" name="cardName" placeholder="Enter name as it appears on card">
                        </div>
                        <div class="form-group">
                            <label for="cardNumber" class="required-field">Card Information</label>
                            <div class="card-input">
                                <input type="text" id="cardNumber" name="cardNumber" class="card-number" placeholder="Card number" maxlength="19">
                                <input type="text" id="cardExpiry" name="cardExpiry" placeholder="MM/YY" maxlength="5">
                                <input type="password" id="cardCVV" name="cardCVV" class="card-cvv" placeholder="CVV" maxlength="3">
                            </div>
                        </div>
                    </div>
                    
                    <div id="bankDetails" class="payment-details" style="display: none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="bankName" class="required-field">Bank Name</label>
                                <input type="text" id="bankName" name="bankName" placeholder="Enter bank name">
                            </div>
                            <div class="form-group">
                                <label for="transactionId" class="required-field">Transaction ID/Reference</label>
                                <input type="text" id="transactionId" name="transactionId" placeholder="Enter transaction reference">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transferDate" class="required-field">Transfer Date</label>
                            <input type="date" id="transferDate" name="transferDate" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div id="onlineDetails" class="payment-details" style="display: none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="onlineMethod" class="required-field">Payment Platform</label>
                                <select id="onlineMethod" name="onlineMethod">
                                    <option value="">Select platform</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="stripe">Stripe</option>
                                    <option value="mtn">MTN Mobile Money</option>
                                    <option value="airtel">Airtel Money</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="onlineReference" class="required-field">Payment Reference</label>
                                <input type="text" id="onlineReference" name="onlineReference" placeholder="Enter reference number">
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
                            Receipt #: <span id="receiptNumber"><?php echo $receipt_number; ?></span>
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
                                <td id="receiptPaymentDate"><?php echo date('F j, Y'); ?></td>
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
                                    <td class="amount" id="receiptAmount">UGX 0</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="receipt-total">
                            <div class="label">Total Paid:</div>
                            <div class="value" id="receiptTotal">UGX 0</div>
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
        // Auto fetch student data when ID is entered
        document.getElementById('studentId').addEventListener('blur', function() {
            const studentId = this.value;
            if (studentId) {
                fetchStudentData(studentId);
            }
        });
        
        function fetchStudentData(studentId) {
            // Use AJAX to fetch student data from the server
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'get_student_data.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status === 200) {
                    try {
                        const response = JSON.parse(this.responseText);
                        if (response.success) {
                            // Populate the form with student data
                            document.getElementById('studentName').value = response.data.student_name;
                            document.getElementById('receiptStudentName').textContent = response.data.student_name;
                            
                            document.getElementById('email').value = response.data.email;
                            document.getElementById('phone').value = response.data.phone;
                            
                            // Set course
                            const courseSelect = document.getElementById('course');
                            for (let i = 0; i < courseSelect.options.length; i++) {
                                if (courseSelect.options[i].value == response.data.course_id) {
                                    courseSelect.options[i].selected = true;
                                    document.getElementById('receiptCourse').textContent = courseSelect.options[i].text;
                                    break;
                                }
                            }
                            
                            // Update receipt student ID
                            document.getElementById('receiptStudentId').textContent = studentId;
                        } else {
                            alert("Student not found!");
                        }
                    } catch (e) {
                        console.error("Error parsing JSON response:", e);
                    }
                }
            };
            xhr.send('studentId=' + encodeURIComponent(studentId));
        }
        
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
                
                // Set the payment method in the hidden input
                document.getElementById('paymentMethod').value = method;
                
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
            const formattedAmount = new Intl.NumberFormat('en-UG').format(amount);
            document.getElementById('receiptAmount').textContent = `UGX ${formattedAmount}`;
            document.getElementById('receiptTotal').textContent = `UGX ${formattedAmount}`;
            
            // Calculate change if cash payment
            const cashReceived = document.getElementById('cashReceived');
            const cashChange = document.getElementById('cashChange');
            
            if (cashReceived && cashReceived.value) {
                const received = parseFloat(cashReceived.value) || 0;
                const change = received - amount;
                if (change > 0) {
                    cashChange.value = change.toFixed(0);
                } else {
                    cashChange.value = '0';
                }
            }
        }
        
        // Form validation before submission
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            // Validate if payment method is selected
            const selectedMethod = document.querySelector('.payment-option.selected');
            if (!selectedMethod) {
                alert('Please select a payment method');
                e.preventDefault();
                return;
            }
            
            const methodName = selectedMethod.getAttribute('data-method');
            
            // Method-specific validation
            switch (methodName) {
                case 'cash':
                    const cashReceived = document.getElementById('cashReceived');
                    if (!cashReceived.value) {
                        alert('Please enter the cash amount received');
                        e.preventDefault();
                        return;
                    }
                    break;
                    
                case 'card':
                    const cardName = document.getElementById('cardName');
                    const cardNumber = document.getElementById('cardNumber');
                    const cardExpiry = document.getElementById('cardExpiry');
                    const cardCVV = document.getElementById('cardCVV');
                    
                    if (!cardName.value || !cardNumber.value || !cardExpiry.value || !cardCVV.value) {
                        alert('Please complete all card details');
                        e.preventDefault();
                        return;
                    }
                    break;
                    
                case 'bank':
                    const bankName = document.getElementById('bankName');
                    const transactionId = document.getElementById('transactionId');
                    const transferDate = document.getElementById('transferDate');
                    
                    if (!bankName.value || !transactionId.value || !transferDate.value) {
                        alert('Please complete all bank transfer details');
                        e.preventDefault();
                        return;
                    }
                    break;
                    
                case 'online':
                    const onlineMethod = document.getElementById('onlineMethod');
                    const onlineReference = document.getElementById('onlineReference');
                    
                    if (!onlineMethod.value || !onlineReference.value) {
                        alert('Please complete all online payment details');
                        e.preventDefault();
                        return;
                    }
                    break;
            }
            
            // Show the paid stamp
            document.querySelector('.payment-status').classList.add('show');
        });
        
        // Print receipt functionality
        document.getElementById('printReceipt').addEventListener('click', function() {
            window.print();
        });
    </script>
</body>
</html>

<?php
// Close the database connection
if(isset($conn)) {
    mysqli_close($conn);
}
?>