<?php

    include('config/database.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $admins = [];
    $php_errormsg = '';

    try {
        // $result = $conn->query("SELECT * FROM applicationstb");
        
        $result = $conn->query("SELECT * FROM accountstb WHERE role = 'S-ADM' or role = 'ADM'");

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $admins[] = $row;
                }           
            }
            $result->free(); 
        } else {
            throw new Exception("Error executing query: " . $conn->error);
        }

    
    } catch (Exception $e){
        $php_errormsg = $e->getMessage();
    }

?>
