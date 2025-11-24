<?php
    include('api/accounts-fetch.php');
    include('auth/session.php');
    // include ('api/add-admin.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

    $error_message = '';
    $success_message = '';
    $email_success_msg = '';
    $email_error_msg = '';
    $php_errormsg = '';


try {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        if (isset($_POST['submit'])) {
            $username = $_POST['username'] ?? ''; //POST - it's sent over 
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $cpassword = $_POST['cpassword'] ?? '';

            if ($password !== $cpassword) {
                $error_message = "Passwords do not match.";
            } elseif (empty($username) || empty($email) || empty($password)) {
                $error_message = "All fields are required.";
            } else {
                // Check if username or email already exists
                $stmt = $conn->prepare("SELECT account_id FROM accountstb WHERE username = ? OR email = ?");        
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $error_message = "Username or email already taken.";
                } 
                
                else {
                    // Inserting new user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $role = 'ADM'; 
                    $accountypeid = 'ADM-';
                    $account_id = $accountypeid . uniqid(); 
                    $insert_stmt = $conn->prepare("INSERT INTO accountstb (account_id, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
                    $insert_stmt->bind_param("sssss", $account_id, $username, $email, $hashed_password, $role);
                    
                    if ($insert_stmt->execute()) {
                        $success_message = "Account creation successful!";
                    } else {
                        $error_message = "Error: " . $insert_stmt->error;
                    }
                    $insert_stmt->close();
                }
            }
        }
    }
}
catch (Exception $ex) {
    $php_errormsg = $ex->getMessage();
    echo $php_errormsg;

}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_email'])) {
    $recipient_email = $_POST['recipient'] ?? null;
    $email_subject = $_POST['subject'] ?? '';
    $email_message = $_POST['message'] ?? '';

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
            $mail->addAddress($recipient_email);

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

?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>REAPWEB BACKEND</title>

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
                <a href="index.php" class="navbar-brand mx-auto mx-lg-0">
                    <i class="bi-bullseye brand-logo"></i>
                    <span class="brand-text">REAPWEB <br>BACK-END</span>
                </a>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link custom-btn btn d-none d-lg-block" href="auth/destroy.php" id="authBtn">Log Out</a>
                        </li>
                    </ul>
                <div>
            </div>
        </nav>

        <main>
            <section class="about  section-padding">
                <div class="container">
                    <div class="col-lg-10 col-12">
                        <h2 class="mb-4">List of Admins</h2>
                        <div class="row">
                            <div class="col-12 mx-auto">
                                <table style="width: 100%;;" class="table">
                                    <thead style="background-color: #273053; color: white;" class="table">
                                        <tr>
                                            <th>Username</th>
                                            <th>Email</th>  
                                            <th>Role</th>
                                            <th colspan="2" style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($admins as $admin) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($admin['username'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($admin['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($admin['role'] ?? ''); ?></td>
                                                <td>
                                                    <button>
                                                        <a href="api/edit-admin.php?account_id=<?php echo urlencode($admin['account_id']); ?>" >Edit</a>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button>
                                                        <a href="api/delete-admin.php?account_id=<?php echo urlencode($admin['account_id']); ?>" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="about section-padding">
                <div class="container">
                    <div class="row">
                        <div class="col-5">
                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($success_message)): ?>
                                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                            <?php endif; ?>
                            <form id="addAdmin" class="custom-form contact-form bg-white shadow-lg" action="super-admin.php" method="post" role="form" target="_self">
                                <h2>Admin Manager</h2>
                                <div class="row">
                                    <div>                                    
                                        <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                                    </div>
                                    <div>                                    
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                    </div>
                                    <div>                                    
                                        <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password">
                                    </div>
                                    <div>         
                                        <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email" required>
                                    </div>
                                    <div>
                                        <button type="submit" id="submit" name="submit" class="form-control">Add</button>
                                    </div>
                                </div>
                            </form> 
                        </div>
                        <div class="col-7 ms-auto">
                            <div class="row">
                                <div class="col-12">
                                    <?php if (!empty($email_error_msg)): ?>
                                        <div class="alert alert-danger"><?php echo htmlspecialchars($email_error_msg); ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($email_success_msg)): ?>
                                        <div class="alert alert-success"><?php echo htmlspecialchars($email_success_msg); ?></div>
                                    <?php endif; ?>
                                    <form id="sendEmail" class="custom-form contact-form bg-white shadow-lg" method="post" role="form">
                                        <h2>Email</h2>
                                        <div class="row">
                                            <div>
                                                <label for="">Recipient:</label>
                                                <input class="form-control" id="recipient" name="recipient" type="text">
                                            </div>
                                            <div>
                                                <label for="">Subject:</label>
                                                <input class="form-control" id="subject" name="subject" type="text">
                                            </div>
                                            <div>
                                                <label for="">Message:</label>
                                                <textarea class="form-control" id="message" name="message" rows="8"></textarea>
                                            </div>
                                            <div>
                                                <button type="submit" id="send_email" name="send_email" class="form-control">Send</button>
                                            </div>
                                        </div>
                                    </form>
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