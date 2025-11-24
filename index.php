<?php
    session_start();

    if (isset($_SESSION['loggedin']) === true) {
        switch ($_SESSION['role']) {
            case 'APP':
                header('Location: applicant-dashboard.php');
                break;
            case 'ADM':
                header('Location: admin.php');
                break;
            case 'S-ADM':
                header('Location: super-admin.php');
                break;
            default:
                header('Location: auth/login.php');
                break;
        }
        exit();
    }
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
                    <span class="brand-text">REAPWeb <br> APPLICATION</span>
                </a>

                <!-- <a class="nav-link custom-btn btn d-lg-none" href="#">Buy Tickets</a> -->

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_1">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_2">About Us</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_3">Scholarship Programs</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_4">Contact Us</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-btn btn d-none d-lg-block" href="auth/register.php">Apply Now</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-btn btn d-none d-lg-block" href="auth/login.php" id="authBtn">Login</a>
                        </li>

                    </ul>
                <div>
                        
            </div>
        </nav>

        <main>

            <section class="hero" id="section_1">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-5 col-12 m-auto">
                            <div class="hero-text">
                                <h1 class="text-white mb-4"><u class="text-info">REAPWeb</u> APPLICATION</h1>

                                    <div class="wrapper-center">
                                        <div class="date-location-box">
                                            <span class="date-text" id="date"></span>
                                            <span class="location-text" id="location"></span>
                                        </div>
                                    </div>

                                    <script>
                                        const dateSpan = document.getElementById("date");
                                        const locationSpan = document.getElementById("location");

                                        // Set date
                                        const now = new Date();
                                        const options = { year: 'numeric', month: 'long', day: '2-digit' };
                                        dateSpan.textContent = now.toLocaleDateString(undefined, options);
    
                                        // Get location if allowed
                                        if (navigator.geolocation) {
                                            navigator.geolocation.getCurrentPosition(
                                                position => {
                                                    const lat = position.coords.latitude;
                                                    const lon = position.coords.longitude;

                                                    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            const city = data.address.city || data.address.town || data.address.village || '';
                                                            const country = data.address.country_code ? data.address.country_code.toUpperCase() : '';
                                                            if (city || country) {
                                                                locationSpan.textContent = `• ${city}, ${country}`;
                                                                locationSpan.style.display = "inline";
                                                            }
                                                        });
                                                }
                                            );
                                        }
                                    </script>

                                <a href="#section_2" class="custom-link bi-arrow-down arrow-icon"></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="video-wrap">
                    <video autoplay="" loop="" muted="" class="custom-video" poster="">
                        <source src="videos/Welcome to Asian SEED Academy of Technology.mp4" type="video/mp4">

                    </video>
                </div>
            </section>

            <section class="highlight">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="highlight-thumb">
                                <img src="images/highlight/3.jpg" class="highlight-image img-fluid" alt="">

                                <div class="highlight-info">
                                    <h3 class="highlight-title">2023 City Tour</h3>

                                    <a href="https://www.facebook.com/share/p/1Fx6PaXgnv/" class="bi-facebook highlight-icon"></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="highlight-thumb">
                                <img src="images/highlight/2.jpg" class="highlight-image img-fluid" alt="">

                                <div class="highlight-info">
                                    <h3 class="highlight-title">2024 Founder's Birthday</h3>

                                    <a href="https://www.facebook.com/share/p/1ARCDVapy5/" class="bi-facebook highlight-icon"></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="highlight-thumb">
                                <img src="images/highlight/1.jpg" class="highlight-image img-fluid" alt="">

                                <div class="highlight-info">
                                    <h3 class="highlight-title">2024 Christmas Party</h3>

                                    <a href="https://www.facebook.com/share/p/16YKc4Pfd9/" class="bi-facebook highlight-icon"></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="about section-padding" id="section_2">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-10 col-12">
                            <h2 class="mb-4">About <u class="text-info">Us</u></h2>
                        </div>

                        <div class="col-lg-6 col-12">
                            <h3 class="mb-3">REAP Scholarship Programs</h3>

                            <p>The Responsive Educational Assistance Program (R.E.A.P) of Southville International School and Colleges (SISC) offers a range of scholarship programs designed to support students who demonstrate academic potential, leadership, athletic excellence, and financial need. Each scholarship is tailored to help students overcome personal and economic challenges while pursuing their educational goals.</p>
                            <h4>Take the first step toward your success. Apply now and become a REAP scholar!</h4>

                            <a class="custom-btn custom-border-btn btn custom-link mt-3 me-3" href="#section_3">Programs Offers</a>

                            <a class="custom-btn btn custom-link mt-3" href="auth/register.php">Apply Now</a>
                        </div>

                        <div class="col-lg-6 col-12 mt-5 mt-lg-0">
                            <div class="speakers-thumb">
                                <img src="images/11.jpg" class="img-fluid speakers-image" alt="">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="speakers section-padding" id="section_3">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-12 d-flex flex-column justify-content-center align-items-center">
                            <div class="speakers-text-info">
                                <h2 class="mb-4">Scholarship <u class="text-info">Programs</u></h2>
                                <p>A full scholarship awarded to underprivileged but deserving students, primarily graduates of the Sisters of Mary School, who are enrolled in a two-year technical course at the Asian SEED Academy of Technology (ASAT). Upon completion, scholars may transition into the regular college program.</p>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <div class="speakers-thumb">
                                <img src="images/avatar/1.jpg" class="img-fluid speakers-image" alt="">

                                <small class="speakers-featured-text">Most course choose.</small>

                                <div class="speakers-info">

                                    <h5 class="speakers-title mb-0">BS Information Technology</h5>

                                    <p class="speakers-text mb-0">Artificial Intelligence/Cyber Security/Data Analytics</p>

                                    <ul class="social-icon">
                                        <li><a href="https://www.facebook.com/SISC.CollegeofITE" class="social-icon-link bi-facebook"></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="speakers-thumb speakers-thumb-small">
                                        <img src="images/avatar/2.jpg" class="img-fluid speakers-image" alt="">

                                        <div class="speakers-info">
                                            <h5 class="speakers-title mb-0">BS Psychology</h5>

                                            <p class="speakers-text mb-0">Psychology</p>

                                            <ul class="social-icon">
                                                <li><a href="https://www.facebook.com/sisc.psychologysociety" class="social-icon-link bi-facebook"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="speakers-thumb speakers-thumb-small">
                                        <img src="images/avatar/3.jpg" class="img-fluid speakers-image" alt="">

                                        <div class="speakers-info">
                                            <h5 class="speakers-title mb-0">BS Accountancy</h5>

                                            <p class="speakers-text mb-0">Accountancy</p>

                                            <ul class="social-icon">
                                                <li><a href="https://www.facebook.com/groups/1725529568395486/members" class="social-icon-link bi-facebook"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="speakers-thumb speakers-thumb-small">
                                        <img src="images/avatar/4.jpg" class="img-fluid speakers-image" alt="">

                                        <div class="speakers-info">
                                            <h5 class="speakers-title mb-0">BS Nursing</h5>

                                            <p class="speakers-text mb-0">Nursing</p>

                                            <ul class="social-icon">
                                                <li><a href="https://www.facebook.com/sion.org" class="social-icon-link bi-facebook"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="speakers-thumb speakers-thumb-small">
                                        <img src="images/avatar/5.jpg" class="img-fluid speakers-image" alt="">

                                        <div class="speakers-info">
                                            <h5 class="speakers-title mb-0">BS Multimedia Arts</h5>

                                            <p class="speakers-text mb-0">Multimedia Arts</p>

                                            <ul class="social-icon">
                                                <li><a href="https://www.facebook.com/sisc.monarchs" class="social-icon-link bi-facebook"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="call-to-action section-padding">
                <div class="container">
                    <div class="row ">

                        <div class="col-lg-8 col-12 mx-auto">
                            <h2 class="text-white mb-4">Want to be a <u class="text-info">REAP Scholar</u> today?</h2>
                        </div>

                        <div class="col-lg-3 col-12 ms-lg-auto mt-4 mt-lg-0">
                            <a href="auth/register.php" class="custom-btn btn">Apply Now</a>
                        </div>

                    </div>
                </div>
            </section>

            <section class="venue section-padding" id="section_4">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-12 col-12">
                            <h2 class="mb-5">Reach us at</h2>
                        </div>

                        <div class="col-lg-6 col-12">
                            <iframe class="google-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.2933658380117!2d120.9941065!3d14.4547994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ce0f181f7b7d%3A0xc51e71ddf9aa8fb5!2sSouthville%20International%20School%20and%20Colleges%20(SISC)!5e0!3m2!1sen!2sph!4v1717890000000!5m2!1sen!2sph"  width="100%" height="450" allowfullscreen="" loading="lazy"></iframe>
                        </div>

                        <div class="col-lg-6 col-12 mt-5 mt-lg-0">
                            <div class="venue-thumb bg-white shadow-lg">
                                
                                <div class="venue-info-title">
                                    <h2 class="text-white mb-0">Southville International School and Colleges</h2>
                                </div>

                                <div class="venue-info-body">
                                    <h4 class="d-flex">
                                        <i class="bi-geo-alt me-2"></i> 
                                        <span>1281 Tropical Ave. Cor. Luxembourg St., BF Homes International, Las Pinas City, 1740, Philippines</span>
                                    </h4>

                                    <h5 class="mt-4 mb-3">
                                        <a href="mailto:reap.application@gmail.com">
                                            <i class="bi-envelope me-2"></i>
                                            reap.application@gmail.com
                                        </a>
                                    </h5>

                                    <h5 class="mb-0">
                                        <a href="tel: 305-240-9671">
                                            <i class="bi-telephone me-2"></i>
                                            0991-692-6909
                                        </a>
                                    </h5>
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
        <script src="js/click-scroll.js"></script>e
        <script src="js/custom.js"></script>

    </body>
</html>