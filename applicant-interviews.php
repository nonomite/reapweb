<?php 

include('auth/session.php'); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('config/database.php');

$applicants = [];
$php_errormsg = '';

$applistats_id= htmlspecialchars($_GET['applistatsID'] ?? '');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_update'])) {
    // Correctly retrieve the posted values for the update
    $posted_applistats_id = $_POST['applistats'] ?? null; // Changed to applistats as per your form
    $fLvlInt = $_POST['fLvl'] ?? null;
    $fRating = $_POST['fLvlR'] ?? null;
    $sLvlInt = $_POST['sLvl'] ?? null;
    $sRating = $_POST['sLvlR'] ?? null;


    if ($posted_applistats_id) {
        try {
            $update_stmt = $conn->prepare("UPDATE applicationstb SET fLvlInt = ?, fLvlIntRt = ?, sLvlInt = ?, sLvlIntRt = ? WHERE applistatsID = ?"); // Corrected column names
            if ($update_stmt === false) {
                throw new Exception("Error preparing update statement: " . $conn->error);
            }
            // Corrected bind_param types and variables
            $update_stmt->bind_param('sssss', $fLvlInt, $fRating, $sLvlInt, $sRating, $posted_applistats_id);
            if ($update_stmt->execute()) {
                $update_success_msg = "Application status updated successfully!";
                // Redirect to prevent form resubmission and show updated data 
                header("Location: applicant-interviews.php?applistatsID=" . urlencode($posted_applistats_id) . "&msg=" . urlencode($update_success_msg));
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


if ($applistats_id) {
    try {
        $query = $conn->prepare("SELECT DISTINCT applicationstb.applistatsID AS applistats , CONCAT(applicantstb.lastname,',',' ', applicantstb.firstName) AS fullName, 
            applicationstb.fLvlInt AS flvl , 
            applicationstb.fLvlIntRt AS fRating, 
            applicationstb.sLvlInt AS sLvl, 
            applicationstb.sLvlIntRt AS sRating,
            applicationstb.approval_date AS approval_date 
            FROM applicantstb INNER JOIN applicationstb 
            ON applicantstb.applicantID=applicationstb.applicantID 
            WHERE applicantstb.application_status = 'APPROVED' AND applicationstb.applistatsID = ?");
        if ($query === false) {
            throw new Exception("Error preparing select statement: " . $conn->error);
        }
        $query->bind_param('s', $applistats_id);
        $query->execute();
        $result = $query->get_result();

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
        $query->close();
    } catch (Exception $e) {
        $php_errormsg = $e->getMessage();
    }       
} else {
    $php_errormsg = "Applicant Status ID not provided.";
}

// Check for messages from redirect
$update_success_msg = $_GET['msg'] ?? ''; // Retrieve success message from URL


?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>REAPWEB ADMIN</title>

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
                        <div class="col-12 mx-auto">
                            <?php if (!empty($php_errormsg)) : ?> <p class="text-danger">Error: <?php echo htmlspecialchars($php_errormsg); ?></p> <?php endif; ?>
                            <?php if (!empty($update_error_msg)) : ?> <p class="text-danger">Update Error: <?php echo htmlspecialchars($update_error_msg); ?></p> <?php endif; ?>
                            <?php if (!empty($update_success_msg)) : ?> <p class="text-success">Success: <?php echo htmlspecialchars($update_success_msg); ?></p> <?php endif; ?>
                            <?php if (!empty($email_error_msg)) : ?> <p class="text-danger">Email Error: <?php echo htmlspecialchars($email_error_msg); ?></p> <?php endif; ?>
                            <?php if (!empty($email_success_msg)) : ?> <p class="text-success">Success: <?php echo htmlspecialchars($email_success_msg); ?></p> <?php endif; ?>
                        </div>
                        <?php if (empty($applicants)) : ?>
                            <h2>We don't have a user under that account ID</h2>
                        <?php else : ?>
                            <?php foreach ($applicants as $app) : ?>
                        <div class="col-5 mx-auto">
                            <form class="custom-form contact-form bg-white shadow-lg" action="applicant-interviews.php?applistatsID=<?php echo htmlspecialchars($app['applistats'] ?? ''); ?>" method="post" role="form">
                                        <input type="hidden" name="applistats" value="<?php echo htmlspecialchars($app['applistats'] ?? ''); ?>">
                                <h2>Interview Update</h2>
                                <div class="row">
                                    <div class="col-12">    
                                        <br>
                                        <label class="form-label">Applicant's Name:</label>                                             
                                        <input type="text" class="form-control" placeholder="Applicant's Name" value="<?php echo htmlspecialchars($app['fullName'] ?? ''); ?>" disabled>
                                    </div>
                                    <div class="col-12">    
                                        <label class="form-label">First Level Interviewer:</label>                                             
                                        <select class="form-control" name="fLvl" id="fLvl">
                                            <option value="<?php echo htmlspecialchars($app['flvl'] ?? ''); ?>"><?php echo htmlspecialchars($app['flvl'] ?? ''); ?></option>
                                            <option value="Ms. Maurine Jhoy Dollesin">Ms. Maurine Jhoy Dollesin</option>
                                            <option value="Ms. Mjoy Mansbridge">Ms. Mjoy Mansbridge</option>
                                            <option value="Ms. Ami Salanguit">Ms. Ami Salanguit</option>
                                        </select>
                                    </div>
                                    <div class="col-12">    
                                        <label class="form-label">First Level Interviewer Rating:</label>                                             
                                        <select class="form-control" name="fLvlR" id="fLvlR">
                                            <option value="<?php echo htmlspecialchars($app['fRating'] ?? ''); ?>"><?php echo htmlspecialchars($app['fRating'] ?? ''); ?></option>
                                            <option value="5.00">5.00</option>
                                            <option value="4.50">4.50</option>
                                            <option value="4.00">4.00</option>
                                            <option value="3.50">3.50</option>
                                            <option value="3.00">3.00</option>
                                            <option value="2.50">2.50</option>
                                        </select>
                                    </div>
                                    <div class="col-12">    
                                        <label class="form-label">Second Level Interviewer:</label>                                             
                                        <select class="form-control" name="sLvl" id="sLvl">
                                            <option value="<?php echo htmlspecialchars($app['sLvl'] ?? ''); ?>"><?php echo htmlspecialchars($app['sLvl'] ?? ''); ?></option>
                                            <option value="Ms. Violeta Revoltar">Ms. Violeta Revoltar</option>
                                        </select>
                                    </div>
                                    <div class="col-12">    
                                        <label class="form-label">Second Level Interviewer Rating:</label>                                             
                                        <select class="form-control" name="sLvlR" id="sLvlR">
                                            <option value="<?php echo htmlspecialchars($app['sRating'] ?? ''); ?>"><?php echo htmlspecialchars($app['sRating'] ?? ''); ?></option>
                                            <option value="5.00">5.00</option>
                                            <option value="4.50">4.50</option>
                                            <option value="4.00">4.00</option>
                                            <option value="3.50">3.50</option>
                                            <option value="3.00">3.00</option>
                                            <option value="2.50">2.50</option>
                                        </select>
                                    </div>
                                    <div class="form-control">
                                        <button class="form-control" type="submit" name="submit_update">Submit</button>
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
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/custom.js"></script>

    </body>
</html> 