<?php

include('../config/database.php');


try {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? ''; 
    $accountType = 'ADM';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && $password == $_POST['cpassword']) {

        $create=$conn->prepare('INSERT INTO accountstb (username, password, email, role) VALUES (?,?,?,?)');

        $create->bind_param("ssss", $username, $hashed_password, $email, $role);

        if ($create->execute()) {
            $success_message = "Registration successful! You can now log in.";
        } else {
            $error_message = "Error: " . $create->error;    
        }
        $create->close();

    }

}
catch (Exception $ex) {
    $php_errormsg = $ex->getMessage();
    echo $php_errormsg;

}











?>