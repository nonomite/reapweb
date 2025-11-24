<?php 

include('auth/session.php');  

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('config/database.php');

$accounts = [];
$php_errormsg = '';

$account_id = $_SESSION['account_id'];


if ($account_id) {
    try {
        $query = $conn->prepare("SELECT * FROM accountstb WHERE account_id = ?");
        if ($query === false) {
            throw new Exception("Error preparing select statement: " . $conn->error);
        }
        $query->bind_param('s', $account_id);
        $query->execute();
        $result = $query->get_result();

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $accounts[] = $row;
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

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>REAPWEB APPLICATION</title>

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

                <a href="applicant-dashboard.php" class="navbar-brand mx-auto mx-lg-0">
                    <i class="bi-bullseye brand-logo"></i>
                    <span class="brand-text">REAPWEB <br>APPLICATION</span>
                </a>
            </div>
        </nav>

        <main>
            <section class="about section-padding" id="section_4">
                <div class="container">
                    <div class="row">
                        <div class="col-12 mx-auto">
                            <?php if (!empty($php_errormsg)) : ?> <p class="text-danger">Error: <?php echo htmlspecialchars($php_errormsg); ?></p> <?php endif; ?>
                            <?php if (!empty($update_error_msg)) : ?> <p class="text-danger">Update Error: <?php echo htmlspecialchars($update_error_msg); ?></p> <?php endif; ?>
                            <?php if (!empty($update_success_msg)) : ?> <p class="text-success">Success: <?php echo htmlspecialchars($update_success_msg); ?></p> <?php endif; ?>
                            <?php if (!empty($email_error_msg)) : ?> <p class="text-danger">Email Error: <?php echo htmlspecialchars($email_error_msg); ?></p> <?php endif; ?>
                            <?php if (!empty($email_success_msg)) : ?> <p class="text-success">Success: <?php echo htmlspecialchars($email_success_msg); ?></p> <?php endif; ?>
                        </div>
                        <?php if (empty($accounts)) : ?>
                            <h2>We don't have a user under that account ID</h2>
                        <?php else : ?>
                            <?php foreach ($accounts as $app) : ?>
                        <div class="col-5 mx-auto">
                            <form class="custom-form contact-form bg-white shadow-lg" >
                                <!-- Hidden input to pass applicantID via POST -->
                                <h2>Account Info</h2>
                                <div class="row">
                                    <div class="col-12">  
                                        <br>
                                        <label class="form-label">Account Username:</label>                                  
                                        <input type="text" class="form-control" placeholder="Applicant's Name" value="<?php echo htmlspecialchars($app['username'] ?? ''); ?>" disabled>
                                    </div>
                                    <div class="col-12">     
                                        <label class="form-label">Email:</label>                                  
                                        <input type="text" class="form-control" placeholder="Scholarship Type" value="<?php echo htmlspecialchars($app['email'] ?? ''); ?>" disabled>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
                <?php endforeach;?>
                <?php endif; ?>
        </main>
        
    <?php include('includes/footer.php');?>
        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/custom.js"></script>
    </body>
</html>