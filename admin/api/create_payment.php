<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Payment.php';

$database = new Database();
$db = $database->getConnection();

$payment = new Payment($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->student_id) &&
    !empty($data->student_name) &&
    !empty($data->payment_date) &&
    !empty($data->course) &&
    !empty($data->payment_type) &&
    !empty($data->amount) &&
    !empty($data->payment_method)
) {
    // Generate receipt number
    $date = new DateTime();
    $year = $date->format('Y');
    $month = $date->format('m');
    $day = $date->format('d');
    $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $receipt_number = "MI-{$year}{$month}{$day}-{$random}";

    $payment->student_id = $data->student_id;
    $payment->student_name = $data->student_name;
    $payment->payment_date = $data->payment_date;
    $payment->course = $data->course;
    $payment->email = $data->email ?? null;
    $payment->phone = $data->phone ?? null;
    $payment->payment_type = $data->payment_type;
    $payment->amount = $data->amount;
    $payment->description = $data->description ?? null;
    $payment->payment_method = $data->payment_method;
    $payment->payment_details = $data->payment_details ?? null;
    $payment->receipt_number = $receipt_number;
    $payment->status = "pending";

    if($payment->create()) {
        http_response_code(201);
        echo json_encode(array(
            "message" => "Payment was created successfully.",
            "payment" => array(
                "receipt_number" => $receipt_number,
                "student_id" => $payment->student_id,
                "student_name" => $payment->student_name,
                "amount" => $payment->amount,
                "status" => $payment->status
            )
        ));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create payment."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create payment. Data is incomplete."));
}
?> 