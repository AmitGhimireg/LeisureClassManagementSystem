<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('./phpmailer/PHPMailer.php');
require_once('./phpmailer/SMTP.php');
require_once('./phpmailer/Exception.php');

// include('./config/constants.php');
include('partial-front/navbar.php');

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

if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];

    if (isset($_POST['Submit'])) {
        $user_otp = $_POST["otp"];
        $sql = "SELECT * FROM `teachers` WHERE `email`='$email' AND `otp`='$user_otp'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $upd_sql = "UPDATE `teachers` SET `verified`=1, `otp`=NULL WHERE `email`='$email'";
            $upd_res = mysqli_query($conn, $upd_sql);

            if ($upd_res) {
                unset($_SESSION['user_email']);
                $_SESSION['otp_success'] = "<div class='alert alert-success'>Your account has been successfully activated!</div>";
                header("location: login.php");
                exit();
            } else {
                $_SESSION['otp_err'] = "<div class='alert alert-danger'>Failed to activate account. Please try again.</div>";
                header("location: otp-validate.php");
                exit();
            }
        } else {
            $_SESSION['otp_err'] = "<div class='alert alert-danger'>Wrong OTP. Please try again.</div>";
            header("location: otp-validate.php");
            exit();
        }
    }

    if (isset($_POST['resend_otp'])) {
        $new_otp = str_shuffle('012345678998745632108264539701');
        $new_verification_token = substr($new_otp, 0, 6);

        $upd_sql = "UPDATE `teachers` SET `otp`='$new_verification_token' WHERE `email`='$email'";
        $upd_res = mysqli_query($conn, $upd_sql);

        if($upd_res && sendMail($email, $new_verification_token)){
            $_SESSION['otp_err'] = "<div class='alert alert-info'>A new OTP has been sent to your email.</div>";
        } else {
            $_SESSION['otp_err'] = "<div class='alert alert-danger'>Failed to resend OTP. Please try again.</div>";
        }
        header("location: otp-validate.php");
        exit();
    }
} else {
    header("location: register.php");
    exit();
}
?>

<div class="main-content">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card p-4 shadow-sm">
                    <h2 class="text-center mb-4 card-title">Email Verification</h2>
                    <p class="text-center text-muted mb-4">A one-time password (OTP) has been sent to your email address. Please enter it below to verify your account.</p>

                    <?php
                    if (isset($_SESSION['otp_err'])) {
                        echo $_SESSION['otp_err'];
                        unset($_SESSION['otp_err']);
                    }
                    if (isset($_SESSION['add'])) {
                        echo $_SESSION['add'];
                        unset($_SESSION['add']);
                    }
                    ?>
                    
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="otp" class="form-label">Enter OTP</label>
                            <input type="text" class="form-control" id="otp" name="otp" placeholder="e.g. 123456" required>
                        </div>
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" name="Submit" class="btn btn-primary">Verify Account</button>
                        </div>
                        <div class="text-center">
                            <p class="mb-0">Didn't receive the OTP?</p>
                            <button type="submit" name="resend_otp" class="btn btn-link text-primary text-decoration-none">Resend OTP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partial-front/footer.php'); ?>