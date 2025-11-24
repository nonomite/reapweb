<?php

    include('config/database.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $from_applications_tb = [];
    $aphp_errormsg = '';

    $applicantid = ['applicantID'];
    $applistats_id = ['applistatsID'];

    try {
        $res = $conn->query(
            "SELECT DISTINCT applicationstb.applistatsID , CONCAT(applicantstb.lastname,',',' ', applicantstb.firstName) AS fullName, 
            applicationstb.fLvlInt AS flvl , 
            applicationstb.fLvlIntRt AS fRating, 
            applicationstb.sLvlInt AS sLvl, 
            applicationstb.sLvlIntRt AS sRating,
            applicationstb.approval_date AS approval_date 
            FROM applicantstb INNER JOIN applicationstb 
            ON applicantstb.applicantID=applicationstb.applicantID 
            WHERE applicantstb.application_status = 'APPROVED' ");
        
        if ($res) {
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $from_applications_tb[] = $row;
                }
            }
            $res->free(); 
        } else {
            throw new Exception("Error executing query: " . $conn->error);
        }

    } catch (Exception $e){
        $a_php_errormsg = $e->getMessage();
    } finally {
        if (isset($conn)) {
            $conn->close();
        }
    }    
?>
