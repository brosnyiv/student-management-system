<?php
require_once 'dbconnect.php';


//define variables
$role = $fullname = $email = $password = $status = "";'';


if($_SERVER['REQUEST_METHOD'] == 'POST') {

    //pick variables from the form
    $fullname = $_POST['username'];
    $email = $_POST['email'];
    $role=$_POST['role_name'];
    $accessslevel=$_POST['access_level'];
    $password = $_POST['password_hash'];
    $status = $_POST['is_active'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database using sql query
    $sql = "INSERT INTO users (username, email, password_hash, role_id,access_level, is_active) values ('$fullname', '$email', '$hashed_password','$role', '$accessslevel', '$status')";
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