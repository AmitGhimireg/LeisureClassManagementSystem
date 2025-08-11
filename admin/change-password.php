<?php
include('partial-admin/navbar.php');
include('partial-admin/login-check.php');

// Check if a teacher ID is set in the URL and matches the logged-in admin
if(isset($_GET['id']))
{
    $teach_id = $_GET['id'];
    
    // Ensure the ID in the URL belongs to the logged-in user to prevent unauthorized access
    if($teach_id != $_SESSION['teach_id']) {
        $_SESSION['unauthorized'] = "<div class='alert alert-danger'>Unauthorized Access.</div>";
        header('location:' . SITEURL . 'admin_profile.php');
        exit();
    }
}
else
{
    // Redirect if no ID is provided in the URL
    header('location:' . SITEURL . 'admin_profile.php');
    exit();
}

// Process the form submission
if(isset($_POST['submit']))
{
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // SQL query to get the current password from the database
    // Use a prepared statement to prevent SQL injection.
    $sql = "SELECT password FROM teachers WHERE teach_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $teach_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($res) == 1)
    {
        $row = mysqli_fetch_assoc($res);
        $hashed_password_from_db = $row['password'];

        // Use password_verify to check the current password. This is the correct, secure way.
        if(password_verify($current_password, $hashed_password_from_db))
        {
            // Check if the new password and confirm password match
            if($new_password === $confirm_password)
            {
                // Hash the new password before updating
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database using a prepared statement
                $sql2 = "UPDATE teachers SET password = ? WHERE teach_id=?";
                $stmt2 = mysqli_prepare($conn, $sql2);
                mysqli_stmt_bind_param($stmt2, "si", $new_hashed_password, $teach_id);
                $res2 = mysqli_stmt_execute($stmt2);

                if($res2)
                {
                    $_SESSION['password-change'] = "<div class='alert alert-success'>Password Changed Successfully.</div>";
                    header('location:'.SITEURL.'admin/admin_profile.php');
                    exit();
                }
                else
                {
                    $_SESSION['password-change'] = "<div class='alert alert-danger'>Failed to Change Password.</div>";
                    header('location:'.SITEURL.'admin/change-password.php?id='.$teach_id);
                    exit();
                }
            }
            else
            {
                $_SESSION['password-change'] = "<div class='alert alert-danger'>New Password and Confirm Password Did Not Match.</div>";
                header('location:'.SITEURL.'admin/change-password.php?id='.$teach_id);
                exit();
            }
        }
        else
        {
            $_SESSION['password-change'] = "<div class='alert alert-danger'>Current Password Did Not Match.</div>";
            header('location:'.SITEURL.'admin/change-password.php?id='.$teach_id);
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
                            <a href="<?php echo SITEURL; ?>admin/admin_profile.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle multiple password fields
    document.addEventListener('DOMContentLoaded', function () {
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');
        
        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');

                // Toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the eye icon
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        });
    });
</script>

<?php include('partial-admin/footer.php'); ?>