<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Payment.php';

$database = new Database();
$db = $database->getConnection();

$payment = new Payment($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->receipt_number) &&
    !empty($data->status)
) {
    $payment->receipt_number = $data->receipt_number;
    $payment->status = $data->status;

    if($payment->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Payment was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update payment."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update payment. Data is incomplete."));
}
?> 