<?php
include('partial-admin/navbar.php');
include('partial-admin/login-check.php');

// Database connection is assumed to be included in navbar.php
?>

<div class="container-fluid px-4 flex-grow-1">
    <div class="row my-4">
        <div class="col-12">
            <div class="card p-4 shadow-sm">
                <h3 class="fs-4 mb-5 text-center"><b> All Teacher's Attendance </b></h3>

                <form action="" method="GET" class="mb-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3 text-center">
                            <label for="start_date" class="form-label"><b>Start Date</b></label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01'); ?>">
                        </div>
                        <div class="col-md-3 text-center">
                            <label for="end_date" class="form-label"><b>End Date</b></label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3 text-center">
                            <label for="search_name" class="form-label"><b>Search Teacher</b></label>
                            <input type="text" class="form-control" id="search_name" name="search_name" placeholder="Search by name..." value="<?php echo isset($_GET['search_name']) ? htmlspecialchars($_GET['search_name']) : ''; ?>">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                        <div class="col-md-2">
                            <a href="download-attendance.php?start_date=<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>&end_date=<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>&search_name=<?php echo isset($_GET['search_name']) ? htmlspecialchars($_GET['search_name']) : ''; ?>" class="btn btn-success w-100">Download Attendance</a>
                        </div>
                    </div>
                </form>

                <?php
                // Get start and end dates from URL parameters, or set defaults
                $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
                $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
                $search_name = isset($_GET['search_name']) ? mysqli_real_escape_string($conn, $_GET['search_name']) : '';

                // SQL to get all teachers
                $sql_teachers = "SELECT teach_id, full_name FROM teachers WHERE role='teacher'";

                // Add a search condition if a search name is provided
                if (!empty($search_name)) {
                    $sql_teachers .= " AND full_name LIKE '%$search_name%'";
                }
                
                $sql_teachers .= " ORDER BY full_name ASC"; // Optional: order results alphabetically
                
                $res_teachers = mysqli_query($conn, $sql_teachers);

                if (mysqli_num_rows($res_teachers) > 0) {
                    while ($teacher = mysqli_fetch_assoc($res_teachers)) {
                        $teacher_id = $teacher['teach_id'];
                        $teacher_name = $teacher['full_name'];

                        // SQL to get attendance for the specified date range for this teacher
                        $sql_attendance_for_teacher = "SELECT date, status FROM attendance 
                                                       WHERE teacher_id = '$teacher_id' 
                                                       AND date BETWEEN '$start_date' AND '$end_date' 
                                                       ORDER BY date ASC";
                        $res_attendance = mysqli_query($conn, $sql_attendance_for_teacher);

                        $present_count = 0;
                        $absent_count = 0;
                        $attendance_records = [];

                        while ($record = mysqli_fetch_assoc($res_attendance)) {
                            $attendance_records[$record['date']] = $record['status'];
                            if ($record['status'] == 'Present') $present_count++;
                            if ($record['status'] == 'Absent') $absent_count++;
                        }
                ?>
                        <div class="card p-3 mb-3">
                            <h4><?php echo htmlspecialchars($teacher_name); ?></h4>
                            <p><strong>Attendance Summary (<?php echo $start_date; ?> to <?php echo $end_date; ?>):</strong>
                                <span class="badge bg-success">Present: <?php echo $present_count; ?></span>
                                <span class="badge bg-danger">Absent: <?php echo $absent_count; ?></span>
                            </p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Loop through the date range to display all days
                                        $current_date = new DateTime($start_date);
                                        $end_date_obj = new DateTime($end_date);
                                        while ($current_date <= $end_date_obj) {
                                            $date_str = $current_date->format('Y-m-d');
                                            $status = isset($attendance_records[$date_str]) ? $attendance_records[$date_str] : 'N/A';
                                            $status_class = '';
                                            if ($status == 'Present') $status_class = 'bg-success text-white text-center';
                                            if ($status == 'Absent') $status_class = 'bg-danger text-white text-center';
                                        ?>
                                            <tr>
                                                <td><?php echo $date_str; ?></td>
                                                <td class="<?php echo $status_class; ?>"><?php echo $status; ?></td>
                                            </tr>
                                        <?php
                                            $current_date->modify('+1 day');
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<div class="alert alert-warning">No teachers found.</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include('partial-admin/footer.php'); ?>
</body>
</html>