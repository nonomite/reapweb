<?php
    include('auth/session.php');

    $applicant_id = $_SESSION['applicant_id'] ?? null;

    $display_form = true; 
    $message = ''; // This will capture messages from PHP logic
    $server_error = ''; // To display server-side processing errors

    // Only check for an existing application if the user has an applicant_id in their session
    if ($applicant_id) {
        include('config/database.php');
        try {
            $query = $conn->prepare("SELECT application_status FROM applicantstb WHERE applicantID = ? LIMIT 1");
            if ($query === false) {
                throw new Exception("Error preparing select statement: " . $conn->error);
            }
            $query->bind_param('s', $applicant_id);
            $query->execute();
            $result = $query->get_result();

            if ($result && $result->num_rows > 0) {
                $applicant_data = $result->fetch_assoc();
                $current_status = $applicant_data['application_status'];

                // Define statuses that prevent filing a new application
                $restricted_statuses = ['PENDING', 'APPROVED', 'ON HOLD'];

                if (in_array($current_status, $restricted_statuses)) {
                    $message = "You have already submitted an application with status: <strong>" . htmlspecialchars($current_status) . "</strong>. You cannot file another application at this time.";
                    $display_form = false;
                }
                // If status is 'REJECTED' or other, allow re-application by default.
            }
            $query->close();
        } catch (Exception $e) {
            $server_error = "A database error occurred: " . $e->getMessage();
            $display_form = false; // Prevent form display on database error
            error_log("Database error on application form: " . $e->getMessage());
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

        <title>REAPWeb APPLICATION FORM</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/templatemo-leadership-event.css" rel="stylesheet">
        
        <style>
            /* Custom styles for validation messages */
            .error-message {
                color: red;
                font-size: 0.875em;
                margin-top: 5px;
            }
            .success-message {
                color: green;
                font-size: 0.875em;
                margin-top: 5px;
            }
            .form-control.is-invalid {
                border-color: #dc3545;
                padding-right: calc(1.5em + 0.75rem);
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            }
        </style>
    </head>

    
    <body>
        <nav class="navbar navbar-expand-lg">
            <div class="container">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a href="index.php" class="navbar-brand mx-auto mx-lg-0">
                    <i class="bi-bullseye brand-logo"></i>
                    <span class="brand-text">REAPWeb<br>APPLICATION</span>
                </a>
            </div>
        </nav>

        <main>
            <section class="contact section-padding" id="section_5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 col-14 mx-auto">
                            <?php if (!empty($message)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $message; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($server_error)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $server_error; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($display_form) : ?>
                            <form id="application-form" name="application-form" class="custom-form contact-form bg-white shadow-lg" action="application-submit.php" enctype="multipart/form-data" method="post" role="form">
                                <h2>REAP Application Form</h2>
                                <div class="row"> 
                                    <div class="col-5">
                                        <legend> <b>Please select a scholarship type</b></legend>                                             
                                    </div>
                                    <div class="col-5">
                                        <input class="form-control" list="SCtypeDatalist" name="SCtype" id="SCtype" placeholder="Scholarship Type" >
                                            <datalist id="SCtypeDatalist">
                                                <option value="Founder Scholarship">
                                                <option value="Founder Financial Grantee Scholarship">
                                                <option value="Financial Grantee Scholarship">
                                                <option value="TOPS Scholarship">
                                            </datalist>
                                        <div class="error-message" id="SCtypeError"></div>
                                    </div>
                                    <div class="col-12">
                                        <legend><b>Personal Information</b></legend>
                                    </div>

                                    <div id="formOverallMessage" class="col-12"></div> <p style="color: brown;"><i><b>All fields are required</b></i></p>

                                    <div class="col-4">    
                                        <input type="text" id="f_name" name="f_name" class="form-control" placeholder="First Name" value="" >
                                    </div>
                                    <div class="col-4">
                                        <input type="text" id="m_name" name="m_name" class="form-control" placeholder="Middle Name" value="">
                                    </div>
                                    <div class="col-4">
                                        <input type="text" id="l_name" name="l_name" class="form-control" placeholder="Last Name" value="" >
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-12">
                                        <input type="number" id="u_age" name="u_age" class="form-control" placeholder="Age" min="1" max="100" value="" >
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-12">
                                        <input type="date" id="b_day" name="b_day" class="form-control" placeholder="Birthday" >
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-12">
                                        <input type="text" id="b_place" name="b_place" class="form-control" placeholder="Place of Birth" value="" >
                                    </div>

                                    <div class="col-4">
                                        <div class="form-control">
                                            <label><b>Civil Status</b></label>
                                            <div class="col-12">
                                                <input type="radio" id="c_status_single" name="c_status" value="Single" > <label for="c_status_single">Single</label> <br>
                                                <input type="radio" id="c_status_married" name="c_status" value="Married"> <label for="c_status_married">Married</label> <br>
                                                <input type="radio" id="c_status_widowed" name="c_status" value="Widowed"> <label for="c_status_widowed">Widowed</label> <br>
                                            </div>
                                            <div class="error-message" id="cStatusError"></div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <input type="email" id="u_email" name="u_email" class="form-control" placeholder="Email" >    
                                        <input type="text" id="u_phone" name="u_phone" class="form-control" placeholder="Phone Number"  pattern="[0-9]{11}" title="Phone number must be 11 digits (e.g., 09123456789)">
                                        <div class="error-message" id="uPhoneError"></div>
                                    </div>
                                    <div class="col-2">
                                        <input type="text" id="u_height" name="u_height" class="col-4 form-control" placeholder="Height (cm)"  pattern="[0-9]+(\.[0-9]{1,2})?" title="Enter height in cm (e.g., 175 or 175.5)">
                                        <input type="text" id="u_weight" name="u_weight" class="col-4 form-control" placeholder="Weight (kg)"  pattern="[0-9]+(\.[0-9]{1,2})?" title="Enter weight in kg (e.g., 65 or 65.5)">
                                    </div>

                                    <div class="col-12">
                                        <legend><b>Educational Background</b></legend>
                                    </div>

                                    
                                    <div class="col-6">
                                        <input type="text" id="e_school" name="e_school" class="form-control" placeholder="Primary School" >
                                    </div>
                                    <div class="col-6">
                                        <input type="text" id="s_school" name="s_school" class="form-control" placeholder="Secondary School" >
                                    </div>
                                    <div class="col-6">
                                        <textarea rows="4" id="achievements" name="achievements" class="form-control" placeholder="Academic Achievements" ></textarea>
                                    </div>
                                    <div class="col-6">
                                        <textarea class="form-control" rows="4" id="u_honors" name="u_honors" placeholder="Honors, Leadership Awards, Sports Accomplishments" ></textarea>
                                    </div>
                                    <div class="col-6">
                                        <textarea class="form-control" rows="4" id="u_awards" name="u_awards" placeholder="Socio-Civic Activities, Other Awards" ></textarea>
                                    </div>
                                    <div class="col-6">
                                        <textarea class="form-control" rows="4" id="u_orgs" name="u_orgs" placeholder="Clubs, Societies, and Organizations" ></textarea>
                                    </div>

                                    <div class="col-12">
                                        <legend><b>Family Information</b></legend>
                                    </div>

                                    <div class="col-6">
                                        <p><b>Father's Information</b></p>
                                        <div class="col-12">
                                            <input type="text" class="form-control" id="fthr_name" name="fthr_name" placeholder="Father's Name" >
                                        </div>
                                        <div class="col-12">
                                            <input class="form-control"  type="text" id="fthr_job" name="fthr_job" placeholder="Occupation" >
                                        </div>

                                        <div class="col-12">
                                            <input class="form-control" list="fthr_income" name="fthr_income" id="fthr_income_input" placeholder="Income" >
                                            <datalist id="fthr_income">
                                                <option value="less than PHP 9,100">
                                                <option value="PHP 9,100 to PHP 18,200">
                                                <option value="PHP 18,200 to PHP 36,400">
                                                <option value="more than PHP 36,400">
                                            </datalist>
                                            <div class="error-message" id="fthrIncomeError"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-6">
                                        <p><b>Mother's Information</b></p>
                                        <div class="col-12">
                                            <input type="text" class="form-control" id="mthr_name" name="mthr_name" placeholder="Mother's Name" >
                                        </div>
                                        <div class="col-12">
                                            <input type="text" class="form-control" id="mthr_job" name="mthr_job" placeholder="Occupation" >
                                        </div>

                                        <div class="col-12">
                                            <input class="form-control" list="mthr_income" name="mthr_income" id="mthr_income_input" placeholder="Income" >
                                                <datalist id="mthr_income">
                                                <option value="less than PHP 9,100">
                                                <option value="PHP 9,100 to PHP 18,200">
                                                <option value="PHP 18,200 to PHP 36,400">
                                                <option value="more than PHP 36,400">
                                                </datalist>
                                            <div class="error-message" id="mthrIncomeError"></div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <p><b>Siblings Information</b></p>
                                        <div>
                                            <input class="form-control" list="u_siblings" name="u_siblings" id="u_siblings_input" placeholder="Number of Siblings" >
                                            <datalist id="u_siblings">
                                                <option value="Only child">
                                                <option value="2 to 4 siblings">
                                                <option value="5 to 10 siblings">
                                                <option value="More than 10 siblings">
                                            </datalist>
                                            <div class="error-message" id="uSiblingsError"></div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div>
                                            <textarea rows="3" id="nameofSiblings" name="nameofSiblings" class="form-control" placeholder="Name of Siblings" ></textarea> 
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <legend><b>File Uploads</b></legend>
                                    </div>
                                    <p><i>Only Image file formats (PNG, JPG, JPEG) and PDFs are acceptable. Max 5MB per file.</i></p> <br>
                                    <div class="col-6">
                                        <p><b>Birth Certificate</b></p>
                                        <input type="file" class="form-control" id="u_birthCerth" accept=".pdf" name="u_birthCerth" >
                                        <div class="error-message" id="uBirthCerthError"></div>
                                        <p><b>2x2 pic</b></p>
                                        <input type="file" class="form-control" id="u_pic" accept=".png, .jpg, .jpeg" name="u_pic" >
                                        <div class="error-message" id="uPicError"></div>
                                    </div>
                                    <div class="col-6">
                                        <p><b>Latest Report Card</b></p>
                                        <input type="file" class="form-control" id="u_reportCard" accept=".png, .jpg, .jpeg, .pdf" name="u_reportCard" >
                                        <div class="error-message" id="uReportCardError"></div>
                                        <p><b>Big Five Personality ID</b></p>
                                        <input type="text" class="form-control" id="u_bigFive" name="u_bigFive" placeholder="Big Five Personality ID" >
                                    </div>

                                    <div class="col-12">
                                        <h6>DATA PRIVACY NOTICE</h6>
                                    </div> 
                                    <div class="form-control">
                                        <input type="checkbox" id="certify_cb" name="certify_cb" > <label for="certify_cb">I certify that the above-mentioned data are true and correct</label><br>
                                        <input type="checkbox" id="allow_cb" name="allow_cb" > <label for="allow_cb">I allow REAP to verify my submitted documents and information</label>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" id="submit" name="submit" class="form-control">Submit</button>
                                    </div>
                                </div>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <?php include('includes/footer.php'); ?>

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/custom.js"></script>

        <!-- <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById("application-form");
                const formOverallMessage = document.getElementById('formOverallMessage');

                if (form) {
                    form.addEventListener('submit', async (event) => {
                        event.preventDefault(); // Prevent default form submission

                        // Clear previous error messages
                        const errorMessages = document.querySelectorAll('.error-message');
                        errorMessages.forEach(msg => msg.textContent = '');
                        const invalidFields = document.querySelectorAll('.form-control.is-invalid');
                        invalidFields.forEach(field => field.classList.remove('is-invalid'));
                        formOverallMessage.textContent = '';
                        formOverallMessage.className = ''; // Clear classes

                        let isValid = true;

                        // Function to display specific error message for a field
                        function displayFieldError(fieldId, message) {
                            const errorElement = document.getElementById(fieldId + 'Error');
                            if (errorElement) {
                                errorElement.textContent = message;
                                document.getElementById(fieldId).classList.add('is-invalid');
                            }
                            isValid = false;
                        }

                        // --- Client-side Validation Checks ---

                        // Scholarship Type (Datalist)
                        const scholarshipTypeInput = form.elements.SCtype;
                        const scholarshipOptions = Array.from(form.elements.SCtype.list.options).map(option => option.value);
                        if (!scholarshipTypeInput.value || !scholarshipOptions.includes(scholarshipTypeInput.value)) {
                            displayFieldError('SCtype', 'Please select a valid scholarship type from the list.');
                        }

                        // First Name, Last Name, Age, Birthday, Place of Birth
                        // HTML 'required' handles basic emptiness, but you can add more complex checks here if needed.
                        // Example: Age range (already has min/max in HTML, but you can double check)
                        const age = parseInt(form.elements.u_age.value);
                        if (isNaN(age) || age < 1 || age > 100) {
                            displayFieldError('u_age', 'Please enter a valid age between 1 and 100.');
                        }

                        // Civil Status Radio Buttons
                        const civilStatusRadios = form.querySelectorAll('input[name="c_status"]');
                        const isCivilStatusSelected = Array.from(civilStatusRadios).some(radio => radio.checked);
                        if (!isCivilStatusSelected) {
                            displayFieldError('cStatus', 'Please select your civil status.');
                        }


                        // Email Validation
                        const emailInput = form.elements.u_email;
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailPattern.test(emailInput.value.trim())) {
                            displayFieldError('u_email', 'Please enter a valid email address.');
                        }

                        // Phone Number Validation (11 digits, numeric)
                        const phoneInput = form.elements.u_phone;
                        const phonePattern = /^[0-9]{11}$/;
                        if (!phonePattern.test(phoneInput.value.trim())) {
                            displayFieldError('u_phone', 'Phone number must be 11 digits (e.g., 09123456789).');
                        }

                        // Height and Weight Validation (numeric, optional decimal)
                        const heightInput = form.elements.u_height;
                        const weightInput = form.elements.u_weight;
                        const numberPattern = /^[0-9]+(\.[0-9]{1,2})?$/;
                        if (!numberPattern.test(heightInput.value.trim())) {
                            displayFieldError('u_height', 'Enter height in cm (e.g., 175 or 175.5).');
                        }
                        if (!numberPattern.test(weightInput.value.trim())) {
                            displayFieldError('u_weight', 'Enter weight in kg (e.g., 65 or 65.5).');
                        }

                        // Datalist validations for Income and Siblings
                        const fthrIncomeInput = form.elements.fthr_income;
                        const fthrIncomeOptions = Array.from(form.elements.fthr_income.list.options).map(option => option.value);
                        if (!fthrIncomeInput.value || !fthrIncomeOptions.includes(fthrIncomeInput.value)) {
                            displayFieldError('fthr_income_input', 'Please select a valid income range from the list.');
                        }

                        const mthrIncomeInput = form.elements.mthr_income;
                        const mthrIncomeOptions = Array.from(form.elements.mthr_income.list.options).map(option => option.value);
                        if (!mthrIncomeInput.value || !mthrIncomeOptions.includes(mthrIncomeInput.value)) {
                            displayFieldError('mthr_income_input', 'Please select a valid income range from the list.');
                        }

                        const uSiblingsInput = form.elements.u_siblings;
                        const uSiblingsOptions = Array.from(form.elements.u_siblings.list.options).map(option => option.value);
                        if (!uSiblingsInput.value || !uSiblingsOptions.includes(uSiblingsInput.value)) {
                            displayFieldError('u_siblings_input', 'Please select a valid number of siblings from the list.');
                        }

                        // File Uploads
                        const maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

                        const birthCertFile = form.elements.u_birthCerth.files[0];
                        if (!birthCertFile) {
                            displayFieldError('u_birthCerth', 'Birth Certificate is required.');
                        } else if (birthCertFile.size > maxFileSize) {
                            displayFieldError('u_birthCerth', 'File size exceeds 5MB limit.');
                        } else if (birthCertFile.type !== 'application/pdf') {
                            displayFieldError('u_birthCerth', 'Only PDF files are allowed for Birth Certificate.');
                        }

                        const picFile = form.elements.u_pic.files[0];
                        if (!picFile) {
                            displayFieldError('u_pic', '2x2 pic is required.');
                        } else if (picFile.size > maxFileSize) {
                            displayFieldError('u_pic', 'File size exceeds 5MB limit.');
                        } else if (!['image/png', 'image/jpeg', 'image/jpg'].includes(picFile.type)) {
                            displayFieldError('u_pic', 'Only PNG, JPG, JPEG image files are allowed for 2x2 pic.');
                        }

                        const reportCardFile = form.elements.u_reportCard.files[0];
                        if (!reportCardFile) {
                            displayFieldError('u_reportCard', 'Latest Report Card is required.');
                        } else if (reportCardFile.size > maxFileSize) {
                            displayFieldError('u_reportCard', 'File size exceeds 5MB limit.');
                        } else if (!['image/png', 'image/jpeg', 'image/jpg', 'application/pdf'].includes(reportCardFile.type)) {
                            displayFieldError('u_reportCard', 'Only PNG, JPG, JPEG images or PDF files are allowed for Report Card.');
                        }


                        // Certification Checkboxes
                        const certifyCb = form.elements.certify_cb;
                        const allowCb = form.elements.allow_cb;
                        if (!certifyCb.checked || !allowCb.checked) {
                            formOverallMessage.textContent = 'Please agree to both certifications.';
                            formOverallMessage.className = 'error-message';
                            isValid = false;
                        }

                        // If any validation failed, stop here
                        if (!isValid) {
                            return;
                        }

                        // If all client-side validation passes, proceed with AJAX submission
                        const formData = new FormData(form);
                        try {
                            const response = await fetch(form.action, { 
                                method: 'POST',
                                body: formData
                            });

                            if (response.ok) {
                                const result = await response.text(); // Assuming PHP returns plain text message
                                formOverallMessage.textContent = result;
                                formOverallMessage.className = 'success-message';
                                form.reset(); // Clear the form after successful submission
                                // You might want to redirect or hide the form after success
                                // window.location.href = 'success_page.php'; // Example redirect
                            } else {
                                const errorText = await response.text();
                                formOverallMessage.textContent = `Error submitting application: ${errorText || response.statusText}`;
                                formOverallMessage.className = 'error-message';
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            formOverallMessage.textContent = 'An unexpected error occurred. Please try again.';
                            formOverallMessage.className = 'error-message';
                        }
                    });
                }
            });
        </script> -->
    </body>
</html>