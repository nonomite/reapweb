<?php 

include('auth/session.php');  

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$applicant_id = $_SESSION['applicantID'] ?? null;
include('config/database.php');

$applicant = [];
$php_errormsg = '';




if ($applicant_id) {
    try {
        $query = $conn->prepare("SELECT *, CONCAT(lastName,',',' ',firstName) AS fullName FROM applicantstb WHERE applicantID = ?");
        if ($query === false) {
            throw new Exception("Error preparing select statement: " . $conn->error);
        }
        $query->bind_param('s', $applicant_id);
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
                        <?php if (empty($applicant)) : ?>
                            <h2>You haven't submitted an application yet</h2>
                        <?php else : ?>
                            <?php foreach ($applicant as $app) : ?>
                        <div class="col-5 mx-auto">
                            <form class="custom-form contact-form bg-white shadow-lg" >
                                <!-- Hidden input to pass applicantID via POST -->
                                <h2>Application Info</h2>
                                <div class="row">
                                    <div class="col-6">
                                        <img id="pic" class="rounded-circle" height="150px" width="140px" src="<?php echo htmlspecialchars($app['picFilePath'] ?? ''); ?>" alt="Applicant's Picture">
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
                                        <input type="text" class="form-control" disabled value="<?php echo htmlspecialchars($app['application_status'] ?? ''); ?>">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="container">    
                    <div class="row">
                        <div class="col-11 mx-auto">
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
                                <?php endforeach;?>
                        <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
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