<?php
require_once('config/constants.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M.R.A.S.S. - Manakamana Ratna Ambika Secondary School</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>

<?php

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        require_once('./phpmailer/PHPMailer.php');
        require_once('./phpmailer/SMTP.php');
        require_once('./phpmailer/Exception.php');

        function sendMail($to, $otp)
        {
            $from = "amitghimire100@gmail.com";
            $app_password = "oxta jdni qomh gvjd";

            $subject = "Register OTP From School Project";
            $mail = new PHPMailer(true);
            $message = "Your OTP Code To Activate Your account is: " . strval($otp);

            try {
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $from;
                $mail->Password = $app_password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($from, 'School Project');
                $mail->addAddress($to);
                $mail->isHTML(false);
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        if (isset($_POST['submit'])) {
            $t_username = mysqli_real_escape_string($conn, stripslashes($_POST['username']));
            $t_fullname = mysqli_real_escape_string($conn, stripslashes($_POST['full_name']));
            $t_email = mysqli_real_escape_string($conn, stripslashes($_POST['email']));
            $t_phone = mysqli_real_escape_string($conn, stripslashes($_POST['contact']));
            $t_pan_number = mysqli_real_escape_string($conn, stripslashes($_POST['pan']));
            $t_password = mysqli_real_escape_string($conn, stripslashes($_POST['password']));
            $t_level = mysqli_real_escape_string($conn, stripslashes($_POST['level']));

            $photo_url = "";
            if (isset($_FILES['photo']['name'])) {
                $image_name = $_FILES['photo']['name'];
                if ($image_name != "") {
                    $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                    $image_name = "teacher_" . rand(0000, 9999) . '.' . $ext;
                    $source_path = $_FILES['photo']['tmp_name'];
                    $destination_path = "./images/teachers/" . $image_name;
                    $upload = move_uploaded_file($source_path, $destination_path);
                    if ($upload == false) {
                        $_SESSION['upload'] = "<div class='alert alert-danger'>Failed to Upload Image.</div>";
                        header("location:" . SITEURL . 'register.php');
                        die();
                    }
                    $photo_url = $image_name;
                }
            }

            $hashed_password = password_hash($t_password, PASSWORD_BCRYPT);
            $email_check_query = "SELECT * FROM `teachers` WHERE email='$t_email' LIMIT 1";
            $email_check_result = mysqli_query($conn, $email_check_query);

            if (mysqli_num_rows($email_check_result) > 0) {
                $_SESSION['email_exists'] = "<div class='alert alert-danger'>Email already exists</div>";
                header("location:" . SITEURL . 'register.php');
                exit();
            } else {
                $otp = str_shuffle('012345678998745632108264539701');
                $verification_token = substr($otp, 0, 6);
                $_SESSION["user_email"] = $t_email;
                $is_verified = 0;

                $query = "INSERT INTO `teachers` (`username`, `full_name`, `email`, `contact`, `pan`, `photo`, `password`, `otp`, `verified`, `level`) 
                  VALUES ('$t_username', '$t_fullname', '$t_email', '$t_phone', '$t_pan_number', '$photo_url', '$hashed_password', '$verification_token', '$is_verified', '$t_level')";

                $result = mysqli_query($conn, $query);

                if ($result) {
                    if (sendMail($t_email, $verification_token)) {
                        $_SESSION['add'] = "<div class='alert alert-success'>Registration successful. Please check your email for the OTP.</div>";
                        header("location:" . SITEURL . 'otp-validate.php');
                        exit();
                    } else {
                        $_SESSION['add'] = "<div class='alert alert-warning'>Registration successful, but failed to send OTP email.</div>";
                        header("location:" . SITEURL . 'otp-validate.php');
                        exit();
                    }
                } else {
                    $_SESSION['add'] = "<div class='alert alert-danger'>Failed to Add User. Please try again.</div>";
                    header("location:" . SITEURL . 'register.php');
                    exit();
                }
            }
        }
        ?>

<div class="main-content">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card p-4 shadow-sm">
                    <h2 class="text-center mb-4 card-title">Register as a Teacher</h2>
                    <p class="text-center text-muted mb-4">Create your account to get started.</p>

                    <?php
                    if (isset($_SESSION['email_exists'])) {
                        echo $_SESSION['email_exists'];
                        unset($_SESSION['email_exists']);
                    }
                    if (isset($_SESSION['add'])) {
                        echo $_SESSION['add'];
                        unset($_SESSION['add']);
                    }
                    if (isset($_SESSION['upload'])) {
                        echo $_SESSION['upload'];
                        unset($_SESSION['upload']);
                    }
                    ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="e.g. john_doe" required>
                        </div>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" name="full_name" placeholder="John Doe" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="contactNumber" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contactNumber" name="contact" placeholder="98XXXXXXXX" required>
                        </div>
                        <div class="mb-3">
                            <label for="panNumber" class="form-label">PAN Number (Optional)</label>
                            <input type="text" class="form-control" id="panNumber" name="pan" placeholder="e.g. 123456789">
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                        </div>
                        <div class="mb-3">
                            <label for="level" class="form-label">Level</label>
                            <select class="form-select" id="level" name="level" required>
                                <option selected disabled>Select a level</option>
                                <option value="Basic">Basic</option>
                                <option value="Secondary">Secondary</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div> <br>
                            <div class="d-grid gap-2">
                                <button type="submit" name="submit" class="btn btn-primary">Register</button>
                            </div>
                    </form>
                    <div class="mt-3 text-center">
                        Already have an account? <a href="login.php" class="text-primary text-decoration-none">Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function(e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>

<?php include('partial-front/footer.php'); ?>