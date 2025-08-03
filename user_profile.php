<?php
// Include necessary files for the page to function
include('partial-front/navbar.php');
// include('partial-front/login-check.php');
include('partial-front/login-check.php');
// Check if a teacher's ID is stored in the session
if (isset($_SESSION['teach_id'])) {
    $teach_id = $_SESSION['teach_id'];

    // SQL query to fetch the teacher's details from the database using their ID
    $sql = "SELECT * FROM teachers WHERE teach_id=$teach_id";
    $res = mysqli_query($conn, $sql);

    // Check if the query returned exactly one teacher
    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $full_name = $row['full_name'];
        $username = $row['username'];
        $contact = $row['contact'];
        $email = $row['email'];
        $pan = $row['pan'];
        $level = $row['level'];
        $photo = $row['photo'];
    } else {
        // If the teacher's details are not found, set an error message and redirect
        $_SESSION['not_found'] = "<div class='error'>Teacher not found.</div>";
        header('location:' . SITEURL . 'index.php');
        exit();
    }
} else {
    // If no teacher ID is in the session, it means the user is not logged in, so redirect to the login page
    header('location:' . SITEURL . 'login.php');
    exit();
}
?>

<div class="main-content">
    <div class="container mt-4">
        <h2 class="text-center mb-4">Teacher Profile</h2>

        <?php
        // Display any session messages, like a success message after an update
        if (isset($_SESSION['update'])) {
            echo $_SESSION['update'];
            unset($_SESSION['update']);
        }
        ?>

        <div class="row">
            <div class="col-md-4 text-center">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <img src="<?php echo SITEURL; ?>images/teachers/<?php echo $photo; ?>" class="img-fluid rounded-circle mb-3" alt="<?php echo $full_name; ?>" style="width: 200px; height: 200px; object-fit: cover;">
                        <h4 class="card-title"><?php echo $full_name; ?></h4>
                        <p class="card-text text-muted">@<?php echo $username; ?></p>
                        <hr>
                        <p class="card-text"><strong>Level:</strong> <?php echo $level; ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Personal Information</h3>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Full Name:</strong></div>
                            <div class="col-sm-9"><?php echo $full_name; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Email:</strong></div>
                            <div class="col-sm-9"><?php echo $email; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Contact:</strong></div>
                            <div class="col-sm-9"><?php echo $contact; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>PAN Number:</strong></div>
                            <div class="col-sm-9"><?php echo $pan; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Level:</strong></div>
                            <div class="col-sm-9"><?php echo $level; ?></div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end">
                            <a href="<?php echo SITEURL; ?>update-profile.php?id=<?php echo $teach_id; ?>" class="btn btn-primary me-2">Update Profile</a>
                            <a href="<?php echo SITEURL; ?>change-password.php?id=<?php echo $teach_id; ?>" class="btn btn-secondary me-2">Change Password</a>
                            <a href="<?php echo SITEURL; ?>logout.php?id=<?php echo $teach_id; ?>" class="btn btn-warning me-2">Logout</a>
                            <a href="<?php echo SITEURL; ?>delete-profile.php?id=<?php echo $teach_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partial-front/footer.php'); ?>