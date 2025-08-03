<?php
include('partial-front/navbar.php'); // Include the navbar, which should also start the session
include('partial-front/login-check.php');



// Fetch school details from the database
$sql_school_details = "SELECT * FROM school_details WHERE sd_id = 1";
$res_school_details = mysqli_query($conn, $sql_school_details);
$school_details = mysqli_fetch_assoc($res_school_details);

// Handle contact form submission
if (isset($_POST['submit'])) {
    // Get the form data
    $contactName = mysqli_real_escape_string($conn, $_POST['contactName']);
    $contactEmail = mysqli_real_escape_string($conn, $_POST['contactEmail']);
    $contactSubject = mysqli_real_escape_string($conn, $_POST['contactSubject']);
    $contactMessage = mysqli_real_escape_string($conn, $_POST['contactMessage']);
    $created_at = date('Y-m-d H:i:s');

    // SQL query to insert data. Note: The teacher_id column is removed from the INSERT statement.
    $sql_contact = "INSERT INTO `contact_msgs` (`full_name`, `email`, `subject`, `message`, `created_at`) VALUES ('$contactName', '$contactEmail', '$contactSubject', '$contactMessage', '$created_at')";

    // Execute the query
    $res_contact = mysqli_query($conn, $sql_contact);

    // Check if the query was successful
    if ($res_contact) {
        $_SESSION['contact'] = "<div class='alert alert-success'>Message sent successfully! We have forwarded your message to the appropriate department.</div>";
    } else {
        $_SESSION['contact'] = "<div class='alert alert-danger'>Failed to send message. Please try again.</div>";
    }

    // Redirect back to the contact page
    header("location: " . SITEURL . 'contact.php');
    exit();
}

// Pre-populate form fields if a user is logged in (prioritizing teacher)
$prefilled_name = '';
$prefilled_email = '';

if (isset($_SESSION['full_name'])) {
    $prefilled_name = htmlspecialchars($_SESSION['full_name']);
} else if (isset($_SESSION['full_name'])) {
    $prefilled_name = htmlspecialchars($_SESSION['full_name']);
}

if (isset($_SESSION['teacher_email'])) {
    $prefilled_email = htmlspecialchars($_SESSION['teacher_email']);
} else if (isset($_SESSION['email'])) {
    $prefilled_email = htmlspecialchars($_SESSION['email']);
}

?>

<div class="main-content">
    <div class="container mt-4">
        <h2 class="text-center mb-4">Contact Us</h2>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Send us a Message</h4>
                        <?php
                        if (isset($_SESSION['contact'])) {
                            echo $_SESSION['contact'];
                            unset($_SESSION['contact']);
                        }
                        ?>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="contactName" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="contactName" name="contactName" required value="<?php echo $prefilled_name; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="contactEmail" class="form-label">Your Email</label>
                                <input readonly type="email" class="form-control" id="contactEmail" name="contactEmail" required value="<?php echo $prefilled_email; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="contactSubject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="contactSubject" name="contactSubject" placeholder="Subject of your message" required>
                            </div>
                            <div class="mb-3">
                                <label for="contactMessage" class="form-label">Message</label>
                                <textarea class="form-control" id="contactMessage" name="contactMessage" rows="5" required></textarea>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="d-flex flex-column h-100">
                    <div class="card mb-4 flex-grow-1">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Our School Details</h4>
                            <?php if ($school_details) { ?>
                                <p><i class="bi bi-geo-alt-fill me-2"></i><strong>Address:</strong> <?php echo $school_details['address']; ?></p>
                                <p><i class="bi bi-telephone-fill me-2"></i><strong>Phone:</strong> <a href="tel:<?php echo $school_details['phone']; ?>" class="text-decoration-none text-dark"><?php echo $school_details['phone']; ?> (Office)</a></p>
                                <p><i class="bi bi-envelope-fill me-2"></i><strong>Email:</strong> <a href="mailto:<?php echo $school_details['email']; ?>" class="text-decoration-none text-dark"><?php echo $school_details['email']; ?></a></p>
                                <p><i class="bi bi-globe me-2"></i><strong>Website:</strong> <a href="<?php echo $school_details['website']; ?>" target="_blank" class="text-decoration-none text-dark"><?php echo $school_details['website']; ?></a></p>
                                <p><i class="bi bi-clock-fill me-2"></i><strong>Operating Hours:</strong> <?php echo $school_details['opening_hour']; ?></p>
                            <?php } else { ?>
                                <p class='alert alert-warning'>School details are not available. Please contact the administrator.</p>
                            <?php } ?>
                            <p class="mt-3 text-center">We look forward to hearing from you!</p>
                        </div>
                    </div>

                    <div class="card flex-grow-1">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Find Us on Map</h4>
                            <div class="map-container">
                                <?php if ($school_details) { ?>
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3544.758479810789!2d87.19590367450495!3d27.32074634243842!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39e8c86d915f0ab1%3A0xce474c2c538a10a9!2z4KSu4KSo4KSV4KS-4KSu4KSo4KS-IOCkruCkvuCkp-CljeCkr-CkruCkv-CklSDgpLXgpL_gpKbgpY3gpK_gpL7gpLLgpK8!5e0!3m2!1sne!2snp!4v1754058276874!5m2!1sne!2snp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                <?php } else { ?>
                                    <p class='alert alert-warning text-center'>Map is not available.</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partial-front/footer.php'); ?>