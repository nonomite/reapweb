<?php 

    include('auth/session.php');  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('config/database.php'); // Establish connection once

$applicantid = $_GET['applicantID'] ?? null; // Get ID from URL for initial display (prod purposes)
$applicant = [];
$php_errormsg = '';
$update_success_msg = '';
$update_error_msg = '';
$email_success_msg = '';
$email_error_msg = '';

// Handle email submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_email'])) {
    $recipient_email = $_POST['recipient_email'] ?? null;
    $recipient_name = $_POST['recipient_name'] ?? '';
    $email_subject = $_POST['email_subject'] ?? '';
    $email_message = $_POST['email_message'] ?? '';
    $applicantid = $_POST['applicantID'] ?? $applicantid; // Keep applicantid for page reload

    if ($recipient_email && $email_subject && $email_message) {
        $mail = new PHPMailer(true);
        try {
            //Server settings from mailer.php
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'reap.applications@gmail.com'; 
            $mail->Password   = 'zjcp djqf yvhb pezt';                   //App Password Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('reap.applications@gmail.com', 'REAP Applications');
            $mail->addAddress($recipient_email, $recipient_name);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $email_subject;
            $mail->Body    = nl2br(htmlspecialchars($email_message)); // Convert newlines to <br> and escape HTML
            $mail->AltBody = htmlspecialchars($email_message);

            $mail->send();
            $email_success_msg = 'Email sent successfully!';
        } catch (Exception $e) {
            $email_error_msg = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $email_error_msg = 'Missing recipient, subject, or message for email.';
    }
}

// Handle form submission for update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_update'])) {
    $posted_applicant_id = $_POST['applicantID'] ?? null;
    $new_status = $_POST['status'] ?? '';

    if ($posted_applicant_id && $new_status) {
        try {
            $update_stmt = $conn->prepare("UPDATE applicantstb SET application_status = ? WHERE applicantid = ?");
            if ($update_stmt === false) {
                throw new Exception("Error preparing update statement: " . $conn->error);
            }
            $update_stmt->bind_param('ss', $new_status, $posted_applicant_id);
            if ($update_stmt->execute()) {
                $update_success_msg = "Application status updated successfully!";
                // Redirect to prevent form resubmission and show updated data
                header("Location: application-update.php?applicantID=" . urlencode($posted_applicant_id) . "&msg=" . urlencode($update_success_msg));
                exit();
            } else {
                throw new Exception("Error executing update statement: " . $update_stmt->error);
            }
            $update_stmt->close();
        } catch (Exception $e) {
            $update_error_msg = "Update failed: " . $e->getMessage();
        }
    } else {
        $update_error_msg = "Invalid data for update.";
    }
}


if ($applicantid) {
    try {
        $query = $conn->prepare("SELECT *, CONCAT(lastName,',',' ',firstName) AS fullName FROM applicantstb WHERE applicantid = ?");
        if ($query === false) {
            throw new Exception("Error preparing select statement: " . $conn->error);
        }
        $query->bind_param('s', $applicantid);
        $query->execute();
        $result = $query->get_result();

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $applicant[] = $row;
                }
            }
            $result->free();
        } else {
            throw new Exception("Error executing query: " . $conn->error);
        }
        $query->close();
    } catch (Exception $e) {
        $php_errormsg = $e->getMessage();
    }       
} else {
    $php_errormsg = "Applicant ID not provided.";
}

// Check for messages from redirect
if (isset($_GET['msg'])) {
    $update_success_msg = htmlspecialchars($_GET['msg']);
}


if (isset($conn)) {
    $conn->close();
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>REAP Admin</title>

        <!-- CSS FILES -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/templatemo-leadership-event.css" rel="stylesheet">
        

    </head>

    <body>

        <nav class="navbar navbar-expand-lg">
            <div class="container">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a href="admin.php" class="navbar-brand mx-auto mx-lg-0">
                    <i class="bi-bullseye brand-logo"></i>
                    <span class="brand-text">REAPWEB <br>ADMIN</span>
                </a>
            </div>
        </nav>

            <section class="about section-padding" id="section_4">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <?php if (!empty($php_errormsg)) : ?> <p class="text-dang   er">Error: <?php echo htmlspecialchars($php_errormsg); ?></p> <?php endif; ?>
                            <?php if (!empty($update_error_msg)) : ?> <p class="text-danger">Update Error: <?php echo htmlspecialchars($update_error_msg); ?></p> <?php endif; ?>
                            <?php if (!empty($update_success_msg)) : ?> <p class="text-success">Success: <?php echo htmlspecialchars($update_success_msg); ?></p> <?php endif; ?>
                            <?php if (!empty($email_error_msg)) : ?> <p class="text-danger">Email Error: <?php echo htmlspecialchars($email_error_msg); ?></p> <?php endif; ?>
                            <?php if (!empty($email_success_msg)) : ?> <p class="text-success">Success: <?php echo htmlspecialchars($email_success_msg); ?></p> <?php endif; ?>
                        </div>
                        <?php if (empty($applicant)) : ?>
                            <p>No data found for this applicant.</p>
                        <?php else : ?>
                            <?php foreach ($applicant as $app) : ?>
                                <div class="col-5">
                                    <form class="custom-form contact-form bg-white shadow-lg" action="application-update.php?applicantID=<?php echo htmlspecialchars($app['applicantID'] ?? ''); ?>" method="post" role="form">
                                        <!-- Hidden input to pass applicantID via POST -->
                                        <input type="hidden" name="applicantID" value="<?php echo htmlspecialchars($app['applicantID'] ?? ''); ?>">
                                        <h2>Application Update</h2>
                                        <div class="row">
                                            <div class="col-6">
                                                <img id="pic" class="rounded-circle" padding="" height="200px" width="200px" src="<?php echo htmlspecialchars($app['picFilePath'] ?? ''); ?>" alt="Applicant's Picture">
                                            </div>
                                            <div class=" col-6 mx-auto">
                                                <h5>Uploaded Files</h5>
                                                <div>
                                                    <ul>
                                                        <li><a href="<?php echo htmlspecialchars($app['birthCertPath'] ?? '#'); ?>" target="_blank"><b>Birth Certificate</b></a></li>  
                                                        <li><a href="<?php echo htmlspecialchars($app['reportCardPath'] ?? '#'); ?>" target="_blank"><b>Report Card</b></a></li>  
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-12">  
                                                <br>
                                                <label class="form-label">Applicant's Name:</label>                                  
                                                <input type="text" class="form-control" placeholder="Applicant's Name" value="<?php echo htmlspecialchars($app['fullName'] ?? ''); ?>" disabled>
                                            </div>
                                            <div class="col-6">     
                                                <label class="form-label">Email:</label>                                  
                                                <input type="text" class="form-control" placeholder="Scholarship Type" value="<?php echo htmlspecialchars($app['email'] ?? ''); ?>" disabled>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Contact Number:</label>                                  
                                                <input type="text" class="form-control" placeholder="Contact Number" value="<?php echo htmlspecialchars($app['phone'] ?? ''); ?>" disabled>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Big Five ID:</label>                                  
                                                <input type="text" class="form-control" placeholder="Big Five ID" value="<?php echo htmlspecialchars($app['bigFiveID'] ?? ''); ?>" disabled>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Scholarship Type:</label>                                  
                                                <input type="text" class="form-control" placeholder="Scholarship Type" value="<?php echo htmlspecialchars($app['sscType'] ?? ''); ?>" disabled>
                                            </div>
                                            <div class="col-12">
                                                <label for="role">Application Status: </label>
                                                <select class="form-control" name="status" id="status">
                                                    <option value="<?php echo htmlspecialchars($app['application_status'] ?? ''); ?>"><?php echo htmlspecialchars($app['application_status'] ?? 'PENDING'); ?></option>
                                                    <option value="APPROVED">APPROVED</option>
                                                    <option value="ON HOLD">ON HOLD</option>
                                                    <option value="TEST">TEST</option>
                                                </select>
                                                <button type="submit" id="submit" name="submit_update" class="form-control">Update Status</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-7 ms-auto">
                                    <div class="row ">
                                        <div class="col-10">
                                            <form class="custom-form contact-form bg-white shadow-lg" action="application-update.php?applicantID=<?php echo htmlspecialchars($app['applicantID'] ?? ''); ?>" method="post" role="form">
                                                <input type="hidden" name="applicantID" value="<?php echo htmlspecialchars($app['applicantID'] ?? ''); ?>">
                                                <input type="hidden" name="recipient_email" value="<?php echo htmlspecialchars($app['email'] ?? ''); ?>">
                                                <input type="hidden" name="recipient_name" value="<?php echo htmlspecialchars($app['fullName'] ?? ''); ?>">
                                                
                                                <h2>Send Email Notification</h2>
                                                
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="form-label" for="email_subject">Subject:</label>
                                                        <input type="text" name="email_subject" class="form-control" value="Update on your REAP Application" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="email_message">Message:</label>
                                                        <textarea class="form-control" name="email_message" rows="19" required><?php
                                                            $status = strtolower($app['application_status'] ?? '');
                                                            $name = htmlspecialchars($app['fullName'] ?? 'Applicant');
                                                            if ($status === 'approved') {
                                                                echo "Dear $name,\n\nCongratulations! We are pleased to inform you that your application has been approved.\n\nFurther details will follow.\n\nBest regards,\nThe REAPWEB Team";
                                                            } elseif ($status === 'rejected') {
                                                                echo "Dear $name,\n\nThank you for your interest. After careful consideration, we regret to inform you that we cannot proceed with your application at this time.\n\nWe wish you the best in your future endeavors.\n\nBest regards,\nThe REAP Team";
                                                            } elseif ($status === 'test') {
                                                                echo "Dear $name,\n\nThis is for development purposes only.\n\nIf you are receiving this email, the developer is testing out the email functionality of the website.\n\nBest regards,\nThe REAPWEB Dev";
                                                            }    else {
                                                                
                                                                echo "Dear $name,\n\nThis is an update regarding your application. \n\n[Your message here]\n\nBest regards,\nThe REAP Team";
                                                            }
                                                        ?></textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="submit" name="submit_email" class="form-control">Send Email</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section>
                <div class="container">    
                <!-- Email Notification Section -->
                    <div class="row">
                        <div class="col-11">
                            <div class="custom-form contact-form bg-white shadow-lg">
                                <div class="col-12">
                                        <h2>Other Information</h2>
                                    <div class="row">
                                        <div class="col-12">
                                            <legend>Other Personal Information</legend>
                                        </div>
                                        <div class="col-2">  
                                                <label class="form-label" >Birthday:</label>                                  
                                                <input type="text" class="form-control" placeholder="Birthday" value="<?php echo htmlspecialchars($app['bday'] ?? ''); ?>" disabled>
                                            </div>
                                            <div class="col-2">     
                                                <label class="form-label">Birthplace:</label>                                  
                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['bplace'] ?? ''); ?>" disabled>
                                            </div> 
                                        <div class="col-2">
                                            <label class="form-label">Height:</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['height'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-2">
                                            <label class="form-label">Weight:</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['weight'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-4">     
                                            <label class="form-label">Civil Status:</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['civilStatus'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-12">
                                            <legend>Academic Background</legend>
                                        </div>
                                        <div class="col-6">     
                                            <label class="form-label" >Elementary School</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['elemSchl'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-6">     
                                            <label class="form-label">Secondary School</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['secSchl'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Academic Achievements</label>
                                            <textarea rows="3" class="form-control" placeholder="Academic Achievements" disabled><?php echo htmlspecialchars($app['achvmnts'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Honors, Leadership Awards, Sports Accomplishments</label>
                                            <textarea class="form-control" rows="3" placeholder="Honors, Leadership Awards, Sports Accomplishments" disabled><?php echo htmlspecialchars($app['honors'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Socio-Civic Activities, Other Awards</label>
                                            <textarea class="form-control" rows="3" placeholder="Socio-Civic Activities, Other Awards" disabled><?php echo htmlspecialchars($app['awards'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Clubs, Societies, and Organizations</label>
                                            <textarea class="form-control" rows="3" placeholder="Clubs, Societies, and Organizations" disabled><?php echo htmlspecialchars($app['schlOrgs'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="col-12">
                                            <legend>Family Information</legend>
                                        </div>
                                        <div class="col-3">     
                                            <label class="form-label">Father's Name:</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['fatherName'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-3">     
                                            <label class="form-label">Father's Occupation:</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['fatherOccupation'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-3">     
                                            <label class="form-label">Father's Estimated Income:</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['fatherIncome'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-3">     
                                            <label class="form-label">Siblings:</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['siblings'] ?? ''); ?>" disabled>
                                        </div>    
                                        <div class="col-3">     
                                            <label class="form-label">Mother's Name:</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['motherName'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-3">     
                                            <label class="form-label">Mother's Occupation:</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['motherOccupation'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-3">     
                                            <label class="form-label">Mother's Estimated Income:</label>                                  
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['motherIncome'] ?? ''); ?>" disabled>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        
        <?php include ('includes/footer.php');?>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/custom.js"></script>
    </body>
</html>