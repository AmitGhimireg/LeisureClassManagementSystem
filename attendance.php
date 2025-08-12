<?php
// Include necessary files
include('partial-front/navbar.php');
include('partial-front/login-check.php');

// Check if a teacher's ID is stored in the session
if (!isset($_SESSION['teach_id'])) {
    // If no teacher ID is in the session, redirect to the login page
    header('location:' . SITEURL . 'login.php');
    exit();
}

$teach_id = $_SESSION['teach_id'];

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the data from the form
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    // SQL query to insert attendance data into the database
    $sql2 = "INSERT INTO attendance SET 
        teacher_id = $teach_id,
        date = '$date',
        status = '$status',
        reason = '$reason'";

    // Execute the query
    $res2 = mysqli_query($conn, $sql2);

    // Check if the query was successful
    if ($res2) {
        // Attendance recorded successfully
        $_SESSION['attendance_success'] = "<div class='success'>Attendance recorded successfully.</div>";
        header('location:' . SITEURL . 'user_profile.php');
        exit();
    } else {
        // Failed to record attendance
        $_SESSION['attendance_failed'] = "<div class='error'>Failed to record attendance. Please try again.</div>";
        header('location:' . SITEURL . 'attendance.php');
        exit();
    }
}
?>

<div class="main-content">
    <div class="container mt-4">
        <h2 class="text-center mb-4">Mark Your Attendance</h2>

        <?php
        // Display session messages, if any
        if (isset($_SESSION['attendance_failed'])) {
            echo $_SESSION['attendance_failed'];
            unset($_SESSION['attendance_failed']);
        }
        ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" id="date" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-select" id="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Present">Present</option>
                                    <option value="Absent">Absent</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason (Optional)</label>
                                <textarea name="reason" class="form-control" id="reason" rows="3" placeholder="Enter reason for absence or leave"></textarea>
                            </div>
                            <button type="submit" name="submit" class="btn btn-success w-100 mb-2">Submit Attendance</button>
                            <a href="<?php echo SITEURL; ?>user_profile.php" class="btn btn-secondary w-100">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partial-front/footer.php'); ?>