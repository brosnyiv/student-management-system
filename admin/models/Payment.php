<?php
class Payment {
    private $conn;
    private $table_name = "payments";

    public $id;
    public $student_id;
    public $student_name;
    public $payment_date;
    public $course;
    public $email;
    public $phone;
    public $payment_type;
    public $amount;
    public $description;
    public $payment_method;
    public $payment_details;
    public $receipt_number;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (student_id, student_name, payment_date, course, email, phone,
                payment_type, amount, description, payment_method, payment_details,
                receipt_number, status)
                VALUES
                (:student_id, :student_name, :payment_date, :course, :email, :phone,
                :payment_type, :amount, :description, :payment_method, :payment_details,
                :receipt_number, :status)";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->student_id = htmlspecialchars(strip_tags($this->student_id));
        $this->student_name = htmlspecialchars(strip_tags($this->student_name));
        $this->payment_date = htmlspecialchars(strip_tags($this->payment_date));
        $this->course = htmlspecialchars(strip_tags($this->course));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->payment_type = htmlspecialchars(strip_tags($this->payment_type));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));
        $this->payment_details = json_encode($this->payment_details);
        $this->receipt_number = htmlspecialchars(strip_tags($this->receipt_number));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind values
        $stmt->bindParam(":student_id", $this->student_id);
        $stmt->bindParam(":student_name", $this->student_name);
        $stmt->bindParam(":payment_date", $this->payment_date);
        $stmt->bindParam(":course", $this->course);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":payment_type", $this->payment_type);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":payment_method", $this->payment_method);
        $stmt->bindParam(":payment_details", $this->payment_details);
        $stmt->bindParam(":receipt_number", $this->receipt_number);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByReceiptNumber($receipt_number) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE receipt_number = :receipt_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":receipt_number", $receipt_number);
        $stmt->execute();
        return $stmt;
    }

    public function readByStudentId($student_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE student_id = :student_id ORDER BY payment_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":student_id", $student_id);
        $stmt->execute();
        return $stmt;
    }

    public function updateStatus($receipt_number, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE receipt_number = :receipt_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":receipt_number", $receipt_number);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?> 