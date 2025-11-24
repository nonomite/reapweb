
// $f_name = isset($_POST['f_name']) ? trim($_POST['f_name']) : '';
// $m_name = isset($_POST['m_name']) ? trim($_POST['m_name']) : '';
// $l_name = isset($_POST['l_name']) ? trim($_POST['l_name']) : '';
// $age = isset($_POST['age']) ? (int)$_POST['age'] : 0; 
// $b_day = isset($_POST['b_day']) ? trim($_POST['b_day']) : '';
// $b_place = isset($_POST['b_place']) ? trim($_POST['b_place']) : '';
// $c_stat = isset($_POST['c_stat']) ? trim($_POST['c_stat']) : '';
// $email = isset($_POST['email']) ? trim($_POST['email']) : '';
// $mobileNumber = isset($_POST['mobileNumber']) ? trim($_POST['mobileNumber']) : '';
// $height = isset($_POST['height']) ? trim($_POST['height']) : '';
// $weightuser = isset($_POST['weightuser']) ? trim($_POST['weightuser']) : '';
// $e_school = isset($_POST['e_school']) ? trim($_POST['e_school']) : '';
// $s_school = isset($_POST['s_school']) ? trim($_POST['s_school']) : '';
// $achievements = isset($_POST['achievements']) ? trim($_POST['achievements']) : '';
// $accomplishments = isset($_POST['accomplishments']) ? trim($_POST['accomplishments']) : '';
// $awards = isset($_POST['awards']) ? trim($_POST['awards']) : '';
// $orgs = isset($_POST['orgs']) ? trim($_POST['orgs']) : '';
// $fthr_Name = isset($_POST['fthr_Name']) ? trim($_POST['fthr_Name']) : '';
// $fthr_Occupation = isset($_POST['fthr_Occupation']) ? trim($_POST['fthr_Occupation']) : '';
// $fthr_Salary = isset($_POST['fthr_Salary']) ? trim($_POST['fthr_Salary']) : '';
// $mthr_Name = isset($_POST['mthr_Name']) ? trim($_POST['mthr_Name']) : '';
// $mthr_Occupation = isset($_POST['mthr_Occupation']) ? trim($_POST['mthr_Occupation']) : '';
// $mthrv_Salary = isset($_POST['mthrv_Salary']) ? trim($_POST['mthrv_Salary']) : '';
// $siblings = isset($_POST['siblings']) ? (int)$_POST['siblings'] : '';

// // $f_name =  trim($_POST["f_name"]);
// // $m_name = trim($_POST['m_name']);
// // $l_name = trim($_POST['l_name']);
// // $age = trim($_POST['u_age']);
// // $b_day = trim($_POST['b_day']);
// // $b_place = trim($_POST['b_place']);
// // $c_stat = trim($_POST['c_status']);
// // $email = trim($_POST['u_email']);
// // $mobileNumber = trim( $_POST['u_phone']);
// // $height = trim($_POST['u_height']);
// // $weightuser = trim($_POST['u_weight']);
// // $e_school = trim($_POST['e_school']); 
// // $s_school = trim($_POST['s_school']);
// // $achievements = trim($_POST['achievements']);
// // $accomplishments = trim($_POST['u_honors']);
// // $awards = trim($_POST['u_awards']);
// // $orgs =trim($_POST['u_orgs']);
// // $fthr_Name = trim($_POST['fthr_name']);
// // $fthr_Occupation = trim($_POST['fthr_job']);
// // $fthr_Salary = trim($_POST['fthr_income']);
// // $mthr_Name = trim($_POST['mthr_name']);
// // $mthr_Occupation = trim($_POST['mthr_job']);
// // $mthrv_Salary = trim($_POST['mthr_income']);
// // $siblings = trim($_POST['u_siblings']);

// $base_dir = '/uploads/applicants/';

// $user_name = preg_replace('/[^a-zA-Z0-9_-]/', '', $f_name . '_' . $l_name);
// $target_dir = $base_dir . $user_name . '/';

// try {

//     if (isset($_POST["submit"])) {

//         if ($f_name === "" || $l_name === ""){
//             die ("Name must not be blank");
//             header("Location: /application.php");
//             exit();
//         } 
//         else {

//             $upload_errors = [];
//             $file_fields = [
//                 'u_birthCerth' => 'birthCertPath',
//                 'u_reportCard' => 'reportCardPath',
//                 'u_pic' => 'picFilePath',
//                 'u_bigFive' => 'bigFivePath'
//             ];

//             $uploaded_file_paths = [];

//             foreach ($file_fields as $input_name => $db_column) {
//                 if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] === UPLOAD_ERR_OK) {
//                     // Ensure the filename is safe before using it
//                     $safe_filename = basename($_FILES[$input_name]['name']);
//                     $uploaded_file_paths[$db_column] = $target_dir . $safe_filename;
//                 } else if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] !== UPLOAD_ERR_NO_FILE) {
//                     // Handle specific upload errors
//                     $upload_errors[] = "Error uploading " . $input_name . ": " . $_FILES[$input_name]['error'];
//                 } else {
//                     // If file is optional and not uploaded, its path will be empty string or null in DB
//                     $uploaded_file_paths[$db_column] = ''; // Or null, depending on DB schema
//                 }
//             }

//             if (!empty($upload_errors)) {
//                 // Log errors, display a user-friendly message, or redirect
//                 foreach ($upload_errors as $error) {
//                     error_log("File Upload Error: " . $error);
//                 }
//                 die("There were issues with file uploads. Please try again. (Details logged)");
//             }

//             if (!file_exists($target_dir)) {
//                 if (!mkdir($target_dir, 0777, true)) { // 0777 for full permissions, but consider stricter
//                     error_log("Failed to create directory: " . $target_dir);
//                     die("Error creating upload folder. Please contact support.");
//                 }
//             } else {
//                 // For development, echo this. In production, remove or log.
//                 die("\nFolder already exists.");
//                 // You might redirect here if the folder existing means an existing application,
//                 // or just continue if it's fine for the folder to exist.
//                 // If you redirect, make sure to exit.
//                 header("Location: application.php");
//                 exit();
//             }

//              // Move uploaded files
//             foreach ($file_fields as $input_name => $db_column) {
//                 if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] === UPLOAD_ERR_OK) {
//                     $temp_file = $_FILES[$input_name]['tmp_name'];
//                     $newfilepath = $uploaded_file_paths[$db_column]; // Use the path derived earlier
//                     if (!move_uploaded_file($temp_file, $newfilepath)) {
//                         error_log("Failed to move uploaded file: " . $temp_file . " to " . $newfilepath);
//                         die("Error moving uploaded file. Please try again.");
//                     }
//                 }
//             }

//             include("config/database.php"); // This must provide the $pdo object

            
//                 if(file_exists($target_dir)){

//                     ini_set('display_errors', 1);
//                     ini_set('display_startup_errors', 1);
//                     error_reporting(E_ALL); 

//                     echo("\nFolder already exists.");
//                     header("Location: /index.php");

//                 }
//                 else {
//                     mkdir($target_dir, 0777, true);
                    
//                     $temp_file = $_FILES['u_birthCerth']['tmp_name'];
//                     $temp_file2 = $_FILES['u_reportCard']['tmp_name'];
//                     $temp_file3 = $_FILES['u_pic']['tmp_name'];
//                     $temp_file4 = $_FILES['u_bigFive']['tmp_name'];

//                     $newfilepath = $target_dir. $_FILES['u_birthCerth']['name'];
//                     $newfilepath2 = $target_dir. $_FILES['u_reportCard']['name'];
//                     $newfilepath3 = $target_dir. $_FILES['u_pic']['name'];
//                     $newfilepath4 = $target_dir. $_FILES['u_bigFive']['name'];

//                     move_uploaded_file($temp_file, $newfilepath);
//                     move_uploaded_file($temp_file2, $newfilepath2);
//                     move_uploaded_file($temp_file3, $newfilepath3);
//                     move_uploaded_file($temp_file4, $newfilepath4);

                    
//                   //  sql code
//                     require("../config/database.php");

//                     $stmt = $conn->prepare("INSERT INTO applicants 
//                     (`firstName`, `middleName`, `lastName`, `age`, `birthday`, 
//                     `birthplace`, `civilStatus`, `email`, `phone`, `height`, 
//                     `weight`, `elementarySchool`, `secondarySchool`, `achievements`, 
//                     `honors`, `awards`, `schoolOrgs`, `fatherName`, `fatherOccupation`, 
//                     `fatherIncome`, `motherName`, `motherOccupation`, `motherIncome`, 
//                     `siblings`, `birthCertPath`, `reportCardPath`, `picFilePath`, `bigFivePath`)
//                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

//                     $stmt->bind_param("sssissssssssssssssssssssssss", 
//                     $f_name, $m_name, $l_name, $age, $b_day, $b_place, $c_stat,
//                     $email, $mobileNumber, $height, $weightuser, $e_school, $s_school, $achievements,
//                     $accomplishments, $awards, $orgs, $fthr_Name, $fthr_Occupation, $fthr_Salary,
//                     $mthr_Name, $mthr_Occupation, $mthrv_Salary, $siblings, 
//                     $newfilepath, $newfilepath2, $newfilepath3, $newfilepath4);

//                     if ($stmt->execute()){
//                         echo ('insert done');
//                     }
//                     else {
//                         die ("Error preparing statement: " . $conn->error);
//                         die(mysqli_error($conn));

//                     $stmt->close();
//                     $conn->close();
//                     }
//                 }
//         }
//     }
//     else {
//         header("Location: /application_error.html?error=db_insert");
//     }

// }
// catch (Exception $e) {
//     $php_errormsg = $e->getMessage();
        
//     ini_set('display_errors', 1);
//     ini_set('display_startup_errors', 1);
//     error_reporting(E_ALL);
    
// }    