<?php
session_start();
include 'dbconnect.php';

if(isset($_POST['exportFormat'])) {
    $format = $_POST['exportFormat'];
    $filter = $_POST['exportFilter'];
    
    // Build SQL query based on filter
    $sql = "SELECT * FROM students";
    
    if($filter == "active") {
        $sql .= " WHERE status = 'Active'";
    } elseif($filter == "inactive") {
        $sql .= " WHERE status = 'Inactive'";
    } elseif($filter == "male") {
        $sql .= " WHERE gender = 'Male'";
    } elseif($filter == "female") {
        $sql .= " WHERE gender = 'Female'";
    }
    
    $result = mysqli_query($conn, $sql);
    
    if($result) {
        $students = array();
        while($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
        
        // Set headers based on format
        if($format == "csv") {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="students_export_'.date('Y-m-d').'.csv"');
            
            $output = fopen('php://output', 'w');
            
            // Output header row
            fputcsv($output, array('First Name', 'Last Name', 'Student ID', 'Email', 'Gender', 'Course', 'Status', 'Balance'));
            
            // Output data rows
            foreach($students as $student) {
                fputcsv($output, array(
                    $student['first_name'],
                    $student['last_name'],
                    $student['student_id'],
                    $student['email'],
                    $student['gender'],
                    $student['course'],
                    $student['status'],
                    $student['balance']
                ));
            }
            
            fclose($output);
            exit;
            
        } elseif($format == "excel") {
            // For Excel, we'll use a simple CSV with an Excel MIME type
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="students_export_'.date('Y-m-d').'.xls"');
            
            echo '<table border="1">';
            echo '<tr><th>First Name</th><th>Last Name</th><th>Student ID</th><th>Email</th><th>Gender</th><th>Course</th><th>Status</th><th>Balance</th></tr>';
            
            foreach($students as $student) {
                echo '<tr>';
                echo '<td>'.$student['first_name'].'</td>';
                echo '<td>'.$student['last_name'].'</td>';
                echo '<td>'.$student['student_id'].'</td>';
                echo '<td>'.$student['email'].'</td>';
                echo '<td>'.$student['gender'].'</td>';
                echo '<td>'.$student['course'].'</td>';
                echo '<td>'.$student['status'].'</td>';
                echo '<td>'.$student['balance'].'</td>';
                echo '</tr>';
            }
            
            echo '</table>';
            exit;
            
        } elseif($format == "pdf") {
            // For PDF, we'll need to include a PDF library
            // This is a simplified example using FPDF
            require('fpdf/fpdf.php');
            
            class PDF extends FPDF {
                function Header() {
                    $this->SetFont('Arial', 'B', 14);
                    $this->Cell(0, 10, 'Monaco Institute - Student Export', 0, 1, 'C');
                    $this->Ln(5);
                    
                    // Table header
                    $this->SetFont('Arial', 'B', 10);
                    $this->Cell(35, 7, 'Name', 1, 0, 'C');
                    $this->Cell(25, 7, 'Student ID', 1, 0, 'C');
                    $this->Cell(50, 7, 'Email', 1, 0, 'C');
                    $this->Cell(20, 7, 'Gender', 1, 0, 'C');
                    $this->Cell(35, 7, 'Course', 1, 0, 'C');
                    $this->Cell(25, 7, 'Status', 1, 1, 'C');
                }
                
                function Footer() {
                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
                }
            }
            
            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage('L', 'A4');
            $pdf->SetFont('Arial', '', 9);
            
            foreach($students as $student) {
                $pdf->Cell(35, 6, $student['first_name'].' '.$student['last_name'], 1, 0);
                $pdf->Cell(25, 6, $student['student_id'], 1, 0);
                $pdf->Cell(50, 6, $student['email'], 1, 0);
                $pdf->Cell(20, 6, $student['gender'], 1, 0);
                $pdf->Cell(35, 6, $student['course'], 1, 0);
                $pdf->Cell(25, 6, $student['status'], 1, 1);
            }
            
            $pdf->Output('D', 'students_export_'.date('Y-m-d').'.pdf');
            exit;
        }
    } else {
        $_SESSION['export_error'] = "Error: Could not retrieve student data.";
        header("Location: student.php");
        exit;
    }
} else {
    $_SESSION['export_error'] = "Error: Invalid export request.";
    header("Location: student.php");
    exit;
}
?>