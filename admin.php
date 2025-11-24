<?php 
    include('auth/session.php');
    include('api/applicants-fetch.php');
    include('api/applications-fetch.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>REAPWEB ADMIN</title>

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
                    <span class="brand-text">REAPWEB <br>ADMIN</span>
                </a>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#interviews">Interviews</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#applicants">List of Applicants</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link custom-btn btn d-none d-lg-block" href="auth/destroy.php" id="authBtn">Log Out</a>
                        </li>
                    </ul>
                <div>
            </div>
        </nav>

        <section class="custom-form section-padding" id="interviews">
            <div class="container">
                <div class=" col-14">
                    <h2 class="mb-4">For <u class="text-info">Interviews</u></h2>
                </div>
                <div class="row">
                    <?php if (!empty($aphp_errormsg)) : ?>
                        <p>Error: <?php echo htmlspecialchars($aphp_errormsg); ?></p>
                    <?php endif; ?>
                    <?php if (empty($from_applications_tb)) : ?>
                        <p>No data found in the interviews table.</p>
                    <?php else : ?>
                        <div>
                            <table style="width:100%;" class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <td>Applicant Name</td>
                                        <td>L1 Interviewer</td>
                                        <td>L1 Interview Status</td>
                                        <td>L2 Interviewer</td>
                                        <td>L2 Interview Status</td>
                                        <td>Approval Date</td>
                                        <td>Update</td>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($from_applications_tb as $int) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($int['fullName'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($int['flvl'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($int['fRating'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($int['sLvl'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($int['sRating'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($int['approval_date'] ?? ''); ?></td>
                                            <td><button><a href="applicant-interviews.php?applistatsID=<?php echo htmlspecialchars($int['applistatsID'] ?? ''); ?>">Update</a></button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="custom-form section-padding" id="applicants">
            <div class="container">
                <div class="col-lg-10 col-12">
                    <h2 class="mb-4">List of <u class="text-info">Applicants</u></h2>
                </div>
                <div class="row">
                    <?php if (!empty($php_errormsg)) : ?>
                        <p>Error: <?php echo htmlspecialchars($php_errormsg); ?></p>
                    <?php endif; ?>
                    <?php if (empty($applicants)) : ?>
                        <p>No data found in the applicants table.</p>
                    <?php else : ?>
                        <div>
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <td>Applicant Name</td>
                                        <td>Email</td>
                                        <td>Phone</td>
                                        <td>Application Status</td>
                                        <td>Update</td>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($applicants as $applicant) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($applicant['fullName'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($applicant['email'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($applicant['phone'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($applicant['application_status'] ?? ''); ?></td>
                                            <td><button><a href="application-update.php?applicantID=<?php echo htmlspecialchars($applicant['applicantID'] ?? ''); ?>">View/Update</a></button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php include('includes/footer.php');?>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/custom.js"></script>
    </body>
</html>