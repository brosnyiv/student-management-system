<?php
require_once 'dbconnect.php';


//define variables
$role = $fullname = $email = $password = $status = "";'';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    //pick variables from the form
    $role = $_POST['user_role'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $status = $_POST['status'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database using sql query
    $sql = "INSERT INTO users (user_role, fullname, email, password, status) values ('$role', '$fullname', '$email', '$hashed_password', '$status')";
    $result=mysqli_query($conn, $sql);
    if($result) {
        echo "User added successfully!";

        // Redirect to the settings page
        header("Location: settings page.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);

        // // Optionally, you can redirect to the settings page even if there was an error
        // header("Location: settings page.php");
        // exit();
    }
}

?>