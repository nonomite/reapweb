<?php
    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: auth/login.php");
        exit();
    }

    $account_id = $_SESSION['account_id'] ?? null;
    $username = $_SESSION['username'] ?? null;
    $role = $_SESSION['role'] ?? null;
    $applicant_id = $_SESSION['applicantID'] ?? null;

    $php_errormsg = '';

    $timeout = 1800; 
    if (isset($_SESSION['start']) && (time() - $_SESSION['start']) > $timeout) {
        session_unset();
        session_destroy();
        header("Location: auth/login.php");
        exit();
    }
    $_SESSION['start'] = time();
?>