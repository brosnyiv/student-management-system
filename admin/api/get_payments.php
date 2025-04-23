<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Payment.php';

$database = new Database();
$db = $database->getConnection();

$payment = new Payment($db);

$stmt = $payment->read();
$num = $stmt->rowCount();

if($num > 0) {
    $payments_arr = array();
    $payments_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $payment_item = array(
            "id" => $id,
            "student_id" => $student_id,
            "student_name" => $student_name,
            "payment_date" => $payment_date,
            "course" => $course,
            "email" => $email,
            "phone" => $phone,
            "payment_type" => $payment_type,
            "amount" => $amount,
            "description" => $description,
            "payment_method" => $payment_method,
            "payment_details" => json_decode($payment_details),
            "receipt_number" => $receipt_number,
            "status" => $status,
            "created_at" => $created_at
        );

        array_push($payments_arr["records"], $payment_item);
    }

    http_response_code(200);
    echo json_encode($payments_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No payments found."));
}
?> 