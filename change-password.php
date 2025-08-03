<?php
include('partial-front/navbar.php');
include('partial-front/login-check.php');


// Check if a teacher ID is set in the URL and matches the logged-in user
if(isset($_GET['id']))
{
    $teach_id = $_GET['id'];
    
    if($teach_id != $_SESSION['teach_id']) {
        $_SESSION['unauthorized'] = "<div class='error'>Unauthorized Access.</div>";
        header('location:' . SITEURL . 'user_profile.php');
        exit();
    }
}
else
{
    header('location:' . SITEURL . 'user_profile.php');
    exit();
}

// Process the form submission
if(isset($_POST['submit']))
{
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // SQL query to get the current password from the database
    $sql = "SELECT password FROM teachers WHERE teach_id=$teach_id";
    $res = mysqli_query($conn, $sql);

    if(mysqli_num_rows($res) == 1)
    {
        $row = mysqli_fetch_assoc($res);
        $hashed_password_from_db = $row['password'];

        // Verify if the current password matches the one in the database
        if(password_verify($current_password, $hashed_password_from_db))
        {
            // Check if the new password and confirm password match
            if($new_password === $confirm_password)
            {
                // Hash the new password
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $sql2 = "UPDATE teachers SET password = '$new_hashed_password' WHERE teach_id=$teach_id";
                $res2 = mysqli_query($conn, $sql2);

                if($res2 == true)
                {
                    $_SESSION['password-change'] = "<div class='alert alert-success'>Password Changed Successfully.</div>";
                    header('location:'.SITEURL.'user_profile.php');
                    exit();
                }
                else
                {
                    $_SESSION['password-change'] = "<div class='alert alert-danger'>Failed to Change Password.</div>";
                    header('location:'.SITEURL.'change-password.php?id='.$teach_id);
                    exit();
                }
            }
            else
            {
                $_SESSION['password-change'] = "<div class='alert alert-danger'>New Password and Confirm Password Did Not Match.</div>";
                header('location:'.SITEURL.'change-password.php?id='.$teach_id);
                exit();
            }
        }
        else
        {
            $_SESSION['password-change'] = "<div class='alert alert-danger'>Current Password Did Not Match.</div>";
            header('location:'.SITEURL.'change-password.php?id='.$teach_id);
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
                    <h2 class="text-center mb-4 card-title">Change Password</h2>
                    <?php
                    if(isset($_SESSION['password-change'])) { echo $_SESSION['password-change']; unset($_SESSION['password-change']); }
                    ?>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-primary">Change Password</button>
                            <a href="<?php echo SITEURL; ?>user_profile.php" class="btn btn-secondary">Cancel</a>
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