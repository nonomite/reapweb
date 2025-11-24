<?php
session_start();
include('../config/database.php');

$error_message = '';
$success_message = '';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'register') {
            //Registration Handler
            $username = $_POST['username'] ?? ''; //POST - it's sent over 
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $cpassword = $_POST['cpassword'] ?? '';

            if ($password !== $cpassword) {
                $error_message = "Passwords do not match.";
            } elseif (empty($username) || empty($email) || empty($password)) {
                $error_message = "All fields are required for registration.";
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
                    $role = 'APP'; 
                    $accountypeid = 'USR-';
                    $account_id = $accountypeid . uniqid(); 
                    $insert_stmt = $conn->prepare("INSERT INTO accountstb (account_id, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
                    $insert_stmt->bind_param("sssss", $account_id, $username, $email, $hashed_password, $role);
                    
                    if ($insert_stmt->execute()) {
                        $success_message = "Registration successful! You can now log in.";
                    } else {
                        $error_message = "Error: " . $insert_stmt->error;
                    }
                    $insert_stmt->close();
                }
                $stmt->close();
            }
        } elseif ($_POST['action'] == 'login') {
            // Handle Login
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error_message = "Username and password are required.";
            } else {
                $stmt = $conn->prepare("SELECT account_id, password, role FROM accountstb WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($account_id, $hashed_password, $role);
                    $stmt->fetch();

                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['account_id'] = $account_id;
                        $_SESSION['username'] = $username;
                        $_SESSION['role'] = $role;

                        if ($_SESSION['role'] === 'APP') {
                            $applicant_stmt = $conn->prepare("SELECT applicantID FROM applicantstb WHERE account_id = ? LIMIT 1");
                            $applicant_stmt->bind_param("s", $_SESSION['accountID']);
                            $applicant_stmt->execute();
                            $applicant_result = $applicant_stmt->get_result();

                            if ($applicant_result->num_rows == 1) {
                                $applicant_data = $applicant_result->fetch_assoc();
                                $_SESSION['applicant_id'] = $applicant_data['applicantID']; 
                            } else {
                                $_SESSION['applicant_id'] = null;
                                $error_message = "Applicant profile not found. Please contact support.";
                            }
                            $applicant_stmt->close();
                        } else {
                            $_SESSION['applicant_id'] = null;
                        }

                        switch ($role) {
                            case 'S-ADM':
                                header("Location: ../super-admin.php");
                                break;
                            case 'ADM' :
                                header("Location: ../admin.php");
                                break;
                            case 'APP':
                                header("Location: ../applicant-dashboard.php");
                                break;
                            
                            default:
                            $success_message = " ";
                            break;
                        }
                        exit();
                    } else {
                        $error_message = "Invalid username or password.";
                    }
                } else {
                    $error_message = "Invalid username or password.";
                }
                $stmt->close();
            }
        }
    }
}
$conn->close();

}
catch (Exception $e) {
    $error_message = $e->getMessage();

}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>REAPWEB LOGIN / REGISTER</title>

    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/bootstrap-icons.css" rel="stylesheet" />
    <link href="../css/templatemo-leadership-event.css" rel="stylesheet" />
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
        }

        .form-signin {
            max-width: 420px;
        }
    </style>
</head>

<body>
    <main class="form-signin w-100 m-auto">
        <form id="authForm" class="custom-form bg-white shadow-lg rounded p-4 p-md-5" action="login.php" method="POST">
            <div class="text-center mb-4">
                <a href="../index.php" class="navbar-brand text-decoration-none d-inline-block">
                    <i class="bi-bullseye brand-logo" style="font-size: 3rem;"></i>
                    <span class="brand-text h2 align-middle ms-2">REAPWEB <br> APPLICATION</span>
                </a>
                <h1 id="form-title" class="h3 mt-3 mb-3 fw-normal">Login</h1>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <input type="hidden" name="action" id="formAction" value="login">
            
            <div class="form-floating mb-3">
                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required />
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3 registration-field" style="display: none;">
                <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" />
                <label for="email">Email Address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required />
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3 registration-field" style="display: none;">
                <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password" />
                <label for="cpassword">Confirm Password</label>
            </div>
        
            <button id="submit-button" class="w-100 btn btn-lg btn-primary" type="submit">Login</button>

            <p id="login-link" class="text-center mt-4" style="display: none;">
                Already have an account? <a href="#" id="showLogin">Login</a>
            </p>
            <p id="register-link" class="text-center mt-4">
                Don't have an account? <a href="#" id="showRegister">Create one</a>
            </p>
            <p class="mt-4 mb-1 text-muted text-center">&copy; 2025 REAPWeb Application</p>
        </form>
    </main>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formTitle = document.getElementById('form-title');
            const submitButton = document.getElementById('submit-button');
            const formAction = document.getElementById('formAction');
            const registrationFields = document.querySelectorAll('.registration-field');
            const loginLink = document.getElementById('login-link');
            const registerLink = document.getElementById('register-link');
            const showLogin = document.getElementById('showLogin');
            const showRegister = document.getElementById('showRegister');

            function toggleForm(mode) {
                if (mode === 'register') {
                    formTitle.textContent = 'Create Account';
                    submitButton.textContent = 'Register';
                    formAction.value = 'register';
                    registrationFields.forEach(field => field.style.display = 'block');
                    loginLink.style.display = 'block';
                    registerLink.style.display = 'none';
                } else { // login
                    formTitle.textContent = 'Login';
                    submitButton.textContent = 'Login';
                    formAction.value = 'login';
                    registrationFields.forEach(field => field.style.display = 'none');
                    loginLink.style.display = 'none';
                    registerLink.style.display = 'block';
                }
            }

            showRegister.addEventListener('click', function (e) {
                e.preventDefault();
                toggleForm('register');
            });

            showLogin.addEventListener('click', function (e) {
                e.preventDefault();
                toggleForm('login');
            });



            // If there was a registration error, show the registration form on page load
            <?php if (!empty($error_message) && isset($_POST['action']) && $_POST['action'] == 'register') {
                echo "toggleForm('register');";
            } ?>
        });
    </script>
</body>
</html>