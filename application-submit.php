<?php

include('auth/session.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    die("Error: Invalid request method.");
}

//account_id from session (login) 
$application_status = "PENDING";
$scholarshipType = $_POST['sscType'] ?? '';




// Personal Information
$f_name = $_POST['f_name'] ?? '';
$m_name = $_POST['m_name'] ?? '';
$l_name = $_POST['l_name'] ?? '';
$age = !empty($_POST['u_age']) ? (int)$_POST['u_age'] : null;
$b_day = $_POST['b_day'] ?? '';
$b_place = $_POST['b_place'] ?? '';
$c_stat = $_POST['c_status'] ?? '';
$email = $_POST['u_email'] ?? '';
$phone = $_POST['u_phone'] ?? '';
$height = $_POST['u_height'] ?? '';
$weightuser = $_POST['u_weight'] ?? '';


// Educational Background
$e_school = $_POST['e_school'] ?? '';
$s_school = $_POST['s_school'] ?? '';
$achievements = $_POST['achievements'] ?? '';
$honors = $_POST['u_honors'] ?? '';
$awards = $_POST['u_awards'] ?? '';
$orgs = $_POST['u_orgs'] ?? '';


// Family Information
$fthr_Name = $_POST['fthr_name'] ?? '';
$fthr_Occupation = $_POST['fthr_job'] ?? '';
$fthr_Salary = $_POST['fthr_income'] ?? '';
$mthr_Name = $_POST['mthr_name'] ?? '';
$mthr_Occupation = $_POST['mthr_job'] ?? '';
$mthr_Salary = $_POST['mthr_income'] ?? '';
$siblings = $_POST['u_siblings'] ?? '';
$siblingsName = $_POST['nameofSiblings'] ?? '';


//BigFiveID
$bigFiveID = $_POST['bigFive'] ?? '';



// Basic validation for required fields
if (empty($_POST['f_name']) || empty($_POST['l_name'])) {
    http_response_code(400); // Bad Request
    die("Error: First Name and Last Name are required.");
}


$safeLastName = preg_replace('/[^a-zA-Z0-9-]/', '', $l_name);
$safeFirstName = preg_replace('/[^a-zA-Z0-9-]/', '', $f_name);
$folderName = $safeLastName . '_' . $safeFirstName;


$target_dir_base = "uploads/applicants/"; 
$target_dir = $target_dir_base . $folderName . '/';


// Create directory if it doesn't exist
if (!file_exists($target_dir)) {

    if (!mkdir($target_dir, 0755, true)) {
        http_response_code(500); // Internal Server Error
        die("Error: Failed to create directory for uploads.");
    }
}

$fileInputs = [
    'u_birthCerth' => 'birthCertPath',
    'u_reportCard' => 'reportCardPath',
    'u_pic' => 'picFilePath'
];

$filePaths = [];

foreach ($fileInputs as $inputName => $dbColumn) {

    // if (empty($fileInputs)) {
    //     echo () ;
    //     continue;
    // }


    $filePaths[$dbColumn] = ''; 
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES[$inputName];

        // More robust file type validation
        $allowedMimeTypes = [
            'image/png',
            'image/jpeg',
            'application/pdf'
        ];
        if (!in_array($file['type'], $allowedMimeTypes)) {
            http_response_code(400);
            die("Error: Only PNG files are allowed for " . htmlspecialchars($inputName) . ".");
            die("Error: Invalid file type for " . htmlspecialchars($inputName) . ". Only PNG, JPG, and PDF are allowed.");
        }

        // Generate a unique filename to prevent overwrites and security issues
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueFileName = uniqid($inputName . '_', true) . '.' . $fileExtension;
        $destination = $target_dir . $uniqueFileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $filePaths[$dbColumn] = $destination;
        } else {
            http_response_code(500);
            die("Error: Failed to move uploaded file for " . htmlspecialchars($inputName) . ".");
        }
    }
}

include('config/database.php');

if ($conn->connect_error) {
    http_response_code(500);
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO applicantstb 
                    (`firstName`, 
                    `middleName`,
                    `lastname`, 
                    `age`, 
                    `bday`, 
                    `bplace`, 
                    `civilStatus`, 
                    `email`, 
                    `phone`, 
                    `height`, 
                    `weight`, 
                    `elemSchl`, 
                    `secSchl`, 
                    `achvmnts`, 
                    `honors`, 
                    `awards`, 
                    `schlOrgs`, 
                    `fatherName`, 
                    `fatherOccupation`, 
                    `fatherIncome`, 
                    `motherName`, 
                    `motherOccupation`, 
                    `motherIncome`, 
                    `siblings`,
                    `siblingsName`, 
                    `birthCertPath`, 
                    `reportCardPath`, 
                    `picFilePath`, 
                    `bigFiveID`,
                    `scholarshipType`, 
                    `application_status`, 
                    `account_id`)
                    VALUES 
                    (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

if ($stmt === false) {
    http_response_code(500);
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters using the collected file paths
$stmt->bind_param("sssissssssssssssssssssssssssssss", 
    $f_name, 
    $m_name, 
    $l_name, 
    $age, 
    $b_day, 
    $b_place, 
    $c_stat,
    $email, 
    $phone, 
    $height, 
    $weightuser, 
    $e_school, 
    $s_school, 
    $achievements, 
    $honors,
    $awards, 
    $orgs, 
    $fthr_Name, 
    $fthr_Occupation, 
    $fthr_Salary,
    $mthr_Name, 
    $mthr_Occupation, 
    $mthr_Salary, 
    $siblings,
    $siblingsName, 
    $filePaths['birthCertPath'], 
    $filePaths['reportCardPath'], 
    $filePaths['picFilePath'], 
    $bigFiveID,
    $scholarshipType, 
    $application_status, 
    $account_id);

if ($stmt->execute()){
    header('Location: landing-page.php');
    exit();
} else {    
    http_response_code(500); // Internal Server Error
    die("Error executing statement: " . $stmt->error);
}

$stmt->close();
$conn->close();


?>






