<?php 
include('partial-admin/navbar.php');
include('partial-admin/login-check.php');
?>

<style>
    /* Custom style to make carousel images smaller and fit */
    .carousel-item img {
        height: 300px;
        /* Adjust height as needed */
        object-fit: cover;
    }

    /* Additional styles to make the carousel look better */
    .carousel-inner {
        border-radius: 0.5rem;
    }

    /* CSS to style the dropdown options */
    .status-present {
        color: green;
    }

    .status-absent {
        color: red;
    }

    /* .status-leave {
        color: blue;
    } */
</style>

<div class="container-fluid px-4 flex-grow-1">
    <?php
    if (isset($_SESSION['login'])) {
        echo $_SESSION['login'];
        unset($_SESSION['login']);
    }
    if (isset($_SESSION['update-attendance'])) {
        echo $_SESSION['update-attendance'];
        unset($_SESSION['update-attendance']);
    }
    if (isset($_SESSION['delete-attendance'])) {
        echo $_SESSION['delete-attendance'];
        unset($_SESSION['delete-attendance']);
    }

    // Check for an action and ID in the URL
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $action = $_GET['action'];
        $att_id = $_GET['id'];
        
        // Handle the View action
        if ($action == 'view') {
            $sql_view_attendance = "SELECT att_id, teachers.full_name, attendance.date, attendance.status, attendance.reason FROM attendance JOIN teachers ON attendance.teacher_id = teachers.teach_id WHERE attendance.att_id = $att_id and teachers.role='teacher'";
            $res_view_attendance = mysqli_query($conn, $sql_view_attendance);

            if (mysqli_num_rows($res_view_attendance) == 1) {
                $row = mysqli_fetch_assoc($res_view_attendance);
    ?>
                <div class="row my-4">
                    <div class="col-12">
                        <div class="card p-4 shadow-sm">
                            <h3 class="fs-4 mb-3">Attendance Details</h3>
                            <table class="table">
                                <tr>
                                    <th>Teacher Name</th>
                                    <td><?php echo $row['full_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td><?php echo $row['date']; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><?php echo $row['status']; ?></td>
                                </tr>
                                <tr>
                                    <th>Reason</th>
                                    <td><?php echo $row['reason']; ?></td>
                                </tr>
                            </table>
                            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            <?php
            } else {
                echo '<div class="alert alert-danger">Record not found.</div>';
            }
        }

        // Handle the Delete action
        if ($action == 'delete') {
            $sql_check_teacher = "SELECT 1 FROM attendance a JOIN teachers t ON a.teacher_id = t.teach_id WHERE a.att_id = $att_id AND t.role = 'teacher'";
            $res_check = mysqli_query($conn, $sql_check_teacher);
        
            if (mysqli_num_rows($res_check) > 0) {
                $sql_delete = "DELETE FROM attendance WHERE att_id = $att_id";
                if (mysqli_query($conn, $sql_delete)) {
                    $_SESSION['delete-attendance'] = '<div class="alert alert-success">Record deleted successfully.</div>';
                } else {
                    $_SESSION['delete-attendance'] = '<div class="alert alert-danger">Error deleting record: ' . mysqli_error($conn) . '</div>';
                }
            } else {
                $_SESSION['delete-attendance'] = '<div class="alert alert-danger">Error: Attendance record not found or does not belong to a teacher.</div>';
            }
            header("Location: index.php");
            exit();
        }

        // Handle the Update action
        if ($action == 'update') {
            $att_id = $_GET['id'];

            if (isset($_POST['update_attendance'])) {
                $new_date = mysqli_real_escape_string($conn, $_POST['date']);
                $new_status = mysqli_real_escape_string($conn, $_POST['status']);
                $new_reason = mysqli_real_escape_string($conn, $_POST['reason']);

                $sql_update = "UPDATE attendance SET 
                                date = '$new_date', 
                                status = '$new_status', 
                                reason = '$new_reason' 
                                WHERE att_id = $att_id";

                if (mysqli_query($conn, $sql_update)) {
                    $_SESSION['update-attendance'] = '<div class="alert alert-success">Attendance record updated successfully.</div>';
                    header("Location: index.php");
                    exit();
                } else {
                    $_SESSION['update-attendance'] = '<div class="alert alert-danger">Error updating record: ' . mysqli_error($conn) . '</div>';
                }
            }

            $sql_get_record = "SELECT * FROM attendance WHERE att_id = $att_id";
            $res_get_record = mysqli_query($conn, $sql_get_record);

            if (mysqli_num_rows($res_get_record) == 1) {
                $record_to_update = mysqli_fetch_assoc($res_get_record);
                ?>
                <div class="row my-4">
                    <div class="col-12">
                        <div class="card p-4 shadow-sm">
                            <h3 class="fs-4 mb-3">Update Attendance Record</h3>
                            <form action="index.php?action=update&id=<?php echo $att_id; ?>" method="POST">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="<?php echo $record_to_update['date']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="Present" class="status-present" <?php if ($record_to_update['status'] == 'Present') echo 'selected'; ?>>Present</option>
                                        <option value="Absent" class="status-absent" <?php if ($record_to_update['status'] == 'Absent') echo 'selected'; ?>>Absent</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Reason (Optional)</label>
                                    <textarea class="form-control" id="reason" name="reason" rows="3"><?php echo $record_to_update['reason']; ?></textarea>
                                </div>
                                <button type="submit" name="update_attendance" class="btn btn-primary">Update Record</button>
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo '<div class="alert alert-danger">Record for updating not found.</div>';
            }
        }
    }

    if (!isset($_GET['action'])) {
        ?>
        <div class="row my-4">
            <div class="col-12">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner rounded shadow-sm">
                        <div class="carousel-item active">
                            <img src="../images/school.jpg" class="d-block w-100" alt="Slide 1">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>First slide label</h5>
                                <p>Some representative placeholder content for the first slide.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="../images/school.jpg" class="d-block w-100" alt="Slide 2">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Second slide label</h5>
                                <p>Some representative placeholder content for the second slide.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="../images/school.jpg" class="d-block w-100" alt="Slide 3">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Third slide label</h5>
                                <p>Some representative placeholder content for the third slide.</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
        <hr>

        <div class="row g-3 my-2">
            <?php
            $sql_teachers = "SELECT COUNT(*) AS total FROM teachers where role='teacher'";
            $res_teachers = mysqli_query($conn, $sql_teachers);
            $count_teachers = mysqli_fetch_assoc($res_teachers)['total'];

            $sql_classes = "SELECT COUNT(*) AS total FROM classes";
            $res_classes = mysqli_query($conn, $sql_classes);
            $count_classes = mysqli_fetch_assoc($res_classes)['total'];

            $sql_subjects = "SELECT COUNT(*) AS total FROM subjects";
            $res_subjects = mysqli_query($conn, $sql_subjects);
            $count_subjects = mysqli_fetch_assoc($res_subjects)['total'];

            $sql_messages = "SELECT COUNT(*) AS total FROM contact_msgs";
            $res_messages = mysqli_query($conn, $sql_messages);
            $count_messages = mysqli_fetch_assoc($res_messages)['total'];

            $sql_attendance_count = "SELECT COUNT(*) AS total FROM attendance";
            $res_attendance_count = mysqli_query($conn, $sql_attendance_count);
            $count_attendance = mysqli_fetch_assoc($res_attendance_count)['total'];
            ?>

            <div class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                    <div>
                        <i class="bi bi-people p-3 fs-1 text-primary"></i>
                        <h3 class="fs-2 text-center"><?php echo $count_teachers; ?></h3>
                        <p class="fs-5 text-center">Teachers</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                    <div>
                        <i class="bi bi-building p-3 fs-1 text-success"></i>
                        <h3 class="fs-2 text-center"><?php echo $count_classes; ?></h3>
                        <p class="fs-5 text-center">Classes</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                    <div>
                        <i class="bi bi-book p-3 fs-1 text-warning"></i>
                        <h3 class="fs-2 text-center"><?php echo $count_subjects; ?></h3>
                        <p class="fs-5 text-center">Subjects</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                    <div>
                        <i class="bi bi-envelope p-3 fs-1 text-info"></i>
                        <h3 class="fs-2 text-center"><?php echo $count_messages; ?></h3>
                        <p class="fs-5 text-center">Messages</p>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="row my-1">
            <h3 class="fs-4 mb-3">Recent Attendance</h3>
            <div class="col">
                <table class="table bg-white rounded shadow-sm table-hover">
                    <thead>
                        <tr>
                            <th scope="col">S.N.</th>
                            <th scope="col">Teacher Name</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Reason</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_recent_attendance = "SELECT att_id, teachers.full_name, attendance.date, attendance.status, attendance.reason FROM attendance JOIN teachers ON attendance.teacher_id = teachers.teach_id where teachers.role='teacher' ORDER BY attendance.recorded_at DESC LIMIT 5";
                        $res_recent_attendance = mysqli_query($conn, $sql_recent_attendance);

                        if (mysqli_num_rows($res_recent_attendance) > 0) {
                            $sn = 1;
                            while ($row = mysqli_fetch_assoc($res_recent_attendance)) {
                                $status_class = '';
                                if ($row['status'] == 'Present') {
                                    $status_class = 'text-success';
                                } elseif ($row['status'] == 'Absent') {
                                    $status_class = 'text-danger';
                                } elseif ($row['status'] == 'Leave') {
                                    $status_class = 'text-info';
                                }
                        ?>
                                <tr>
                                    <th scope="row"><?php echo $sn++; ?></th>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td class="<?php echo $status_class; ?>"><?php echo $row['status']; ?></td>
                                    <td><?php echo $row['reason']; ?></td>
                                    <td>
                                        <a href="index.php?action=view&id=<?php echo $row['att_id']; ?>" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                                        <a href="index.php?action=update&id=<?php echo $row['att_id']; ?>" class="btn btn-success btn-sm"><i class="bi bi-pencil"></i></a>
                                        <a href="index.php?action=delete&id=<?php echo $row['att_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6">No recent attendance records found.</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div> <hr>
        
        <div class="row my-1">
            <h3 class="fs-4 mb-3">Absent Today</h3>
            <div class="col">
                <table class="table bg-white rounded shadow-sm table-hover">
                    <thead>
                        <tr>
                            <th scope="col">S.N.</th>
                            <th scope="col">Teacher Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $today = date('Y-m-d');
                        $sql_absent_leave_teachers = "SELECT teachers.full_name, attendance.status, attendance.reason 
                                                FROM attendance 
                                                JOIN teachers ON attendance.teacher_id = teachers.teach_id 
                                                WHERE teachers.role = 'teacher' 
                                                AND (LOWER(attendance.status) = 'absent' OR LOWER(attendance.status) = 'leave')
                                                AND attendance.date = '$today'";

                        $res_absent_leave_teachers = mysqli_query($conn, $sql_absent_leave_teachers);

                        if (mysqli_num_rows($res_absent_leave_teachers) > 0) {
                            $sn = 1;
                            while ($row = mysqli_fetch_assoc($res_absent_leave_teachers)) {
                                $status_class = '';
                                if ($row['status'] == 'Absent') {
                                    $status_class = 'text-danger';
                                } elseif ($row['status'] == 'Leave') {
                                    $status_class = 'text-info';
                                }
                        ?>
                                <tr>
                                    <th scope="row"><?php echo $sn++; ?></th>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td class="<?php echo $status_class; ?>"><?php echo $row['status']; ?></td>
                                    <td><?php echo $row['reason']; ?></td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4">No teachers are absent or on leave today.</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
</div>
<?php
    }
?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="../js/admin_scripts.js"></script>

<?php include('partial-admin/footer.php'); ?>
</body>

</html>