<?php

    include('config/database.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $applicants = [];
    $php_errormsg = '';

    $applicantid = ['applicantID'];
    try {
        // $result = $conn->query("SELECT * FROM applicationstb");

        $result = $conn->query("SELECT * , CONCAT(lastName,',',' ',firstName) AS fullName , application_status FROM applicantstb");
        
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $applicants[] = $row;
                }           
            }
            $result->free(); 
        } else {
            throw new Exception("Error executing query: " . $conn->error);
        }

    } catch (Exception $e){
        $php_errormsg = $e->getMessage();
    } finally {
        if (isset($conn)) {
            $conn->close();
        }
    }
?>
