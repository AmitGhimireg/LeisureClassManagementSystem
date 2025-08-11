<?php
require_once('config/constants.php');

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Updated SQL query to select all fields from the teachers table.
    // This assumes you have already added the 'role' column using the ALTER TABLE command.
    $sql = "SELECT * FROM teachers WHERE email = '$email'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['login'] = "<div class='alert alert-success'>Login Successful!</div>";
            $_SESSION['email'] = $email;
            $_SESSION['teach_id'] = $row['teach_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['logged'] = true;
            
            // Store the user's role in the session
            $_SESSION['role'] = $row['role'];

            // Redirect based on the user's role
            if ($row['role'] == 'admin') {
                header('location:' . SITEURL . 'admin/index.php');
            } else {
                // Default redirection for 'teacher' or any other role
                header('location:' . SITEURL . 'index.php');
            }
            exit();
        } else {
            $_SESSION['login'] = "<div class='alert alert-danger'>Email or Password Did Not Match</div>";
            header('location:' . SITEURL . 'login.php');
            exit();
        }
    } else {
        $_SESSION['login'] = "<div class='alert alert-danger'>Email or Password Did Not Match</div>";
        header('location:' . SITEURL . 'login.php');
        exit();
    }
}
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
<body>
    <div class="main-content">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-6">
                    <div class="card p-4 shadow-sm">
                        <h2 class="text-center mb-4 card-title">Login</h2>
                        <p class="text-center text-muted mb-4">Sign in to your account.</p>
                        <?php
                        if (isset($_SESSION['login'])) {
                            echo $_SESSION['login'];
                            unset($_SESSION['login']);
                        }
                        if (isset($_SESSION['otp_success'])) {
                            echo $_SESSION['otp_success'];
                            unset($_SESSION['otp_success']);
                        }
                        if (isset($_SESSION['no-login-message'])) {
                            echo $_SESSION['no-login-message'];
                            unset($_SESSION['no-login-message']);
                        }
                        if (isset($_SESSION['add'])) {
                            echo $_SESSION['add'];
                            unset($_SESSION['add']);
                        }
                        ?>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
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
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" name="submit" class="btn btn-primary">Login</button>
                            </div>
                            <div class="text-center">
                                <p class="mb-1"><a href="forgotpassword.php" class="text-primary text-decoration-none">Forgot Password?</a></p>
                                <p class="mb-0">Don't have an account? <a href="register.php" class="text-primary text-decoration-none">Register here</a></p>
                            </div>
                        </form>
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
</body>
</html>