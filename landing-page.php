<?php 
include('auth/session.php'); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>REAPWeb</title>

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
                    <span class="brand-text">REAPWEB <br>APPLICATION</span>
                </a>
    </nav>
    <div class="custom-form contact-form bg-white shadow-lg" action="application-submit.php" enctype="multipart/form-data" method="post ">
        <div class="row">
            <div class="col-8 mx-auto">
                <div class="row">
                    <h3>Congrats! Application submitted sucessfully!</h3>

                </div>
            </div>
        </div>
    </div>

    <section class="call-to-action section-padding">
        <div class="container">
            <div class="row ">

                <div class="col-lg-8 col-12 mx-auto">
                    <h2 class="text-white mb-4">Want to learn more about our website?</h2>
                </div>

                <div class="col-lg-3 col-12 ms-lg-auto mt-4 mt-lg-0">
                    <a href="applicant-dashboard.php" class="custom-btn btn">Click here</a>
                </div>

            </div>
        </div>
    </section>
    
    <?php include ('includes/footer.php'); ?>

</body>
</html>