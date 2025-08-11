<?php include('partial-admin/navbar.php'); 
include('partial-admin/login-check.php');?>
<?php
$page_title = "Manage Routines";

// Handle form submissions
if (isset($_POST['add_routine'])) {
    $day = mysqli_real_escape_string($conn, $_POST['day']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $is_break = isset($_POST['is_break']) ? '1' : '0';

    $sql = "INSERT INTO academic_routines (day, start_time, end_time, subject_id, is_break, class_id, teacher_id) 
            VALUES ('$day', '$start_time', '$end_time', '$subject_id', '$is_break', '$class_id', '$teacher_id')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['add_routine'] = "<div class='alert alert-success'>Routine Added Successfully.</div>";
    } else {
        $_SESSION['add_routine'] = "<div class='alert alert-danger'>Failed to Add Routine.</div>";
    }
    header('location:' . SITEURL . 'admin/routines.php');
    exit();
}

if (isset($_POST['update_routine'])) {
    $ar_id = $_POST['ar_id'];
    $day = mysqli_real_escape_string($conn, $_POST['day']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $is_break = isset($_POST['is_break']) ? '1' : '0';

    $sql = "UPDATE academic_routines SET day='$day', start_time='$start_time', end_time='$end_time', subject_id='$subject_id', is_break='$is_break', class_id='$class_id', teacher_id='$teacher_id' WHERE ar_id=$ar_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['update_routine'] = "<div class='alert alert-success'>Routine Updated Successfully.</div>";
    } else {
        $_SESSION['update_routine'] = "<div class='alert alert-danger'>Failed to Update Routine.</div>";
    }
    header('location:' . SITEURL . 'admin/routines.php');
    exit();
}

if (isset($_GET['delete_id'])) {
    $ar_id = $_GET['delete_id'];
    $sql = "DELETE FROM academic_routines WHERE ar_id=$ar_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['delete_routine'] = "<div class='alert alert-success'>Routine Deleted Successfully.</div>";
    } else {
        $_SESSION['delete_routine'] = "<div class='alert alert-danger'>Failed to Delete Routine.</div>";
    }
    header('location:' . SITEURL . 'admin/routines.php');
    exit();
}

// Fetch data for dropdowns
$sql_teachers = "SELECT teach_id, full_name FROM teachers ORDER BY full_name";
$res_teachers = mysqli_query($conn, $sql_teachers);

$sql_subjects = "SELECT subj_id, name FROM subjects ORDER BY name";
$res_subjects = mysqli_query($conn, $sql_subjects);

$sql_classes = "SELECT cls_id, name, section FROM classes ORDER BY name, section";
$res_classes = mysqli_query($conn, $sql_classes);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div id="page-content-wrapper">
            <div class="container-fluid px-4">
                <div class="row my-4">
                    <div class="col-12">
                        <h3 class="fs-4 mb-3"><?php echo $page_title; ?></h3>
                        <?php
                        if (isset($_SESSION['add_routine'])) {
                            echo $_SESSION['add_routine'];
                            unset($_SESSION['add_routine']);
                        }
                        if (isset($_SESSION['update_routine'])) {
                            echo $_SESSION['update_routine'];
                            unset($_SESSION['update_routine']);
                        }
                        if (isset($_SESSION['delete_routine'])) {
                            echo $_SESSION['delete_routine'];
                            unset($_SESSION['delete_routine']);
                        }
                        ?>
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addRoutineModal">
                            <i class="bi bi-calendar-plus"></i> Add New Routine
                        </button>
                        <div class="table-responsive">
                            <table class="table bg-white rounded shadow-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">S.N.</th>
                                        <th scope="col">Day</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Class</th>
                                        <th scope="col">Teacher</th>
                                        <th scope="col">Is Break?</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT ar.*, s.name as subject_name, c.name as class_name, c.section as class_section, t.full_name as teacher_name
                                            FROM academic_routines ar
                                            JOIN subjects s ON ar.subject_id = s.subj_id
                                            JOIN classes c ON ar.class_id = c.cls_id
                                            JOIN teachers t ON ar.teacher_id = t.teach_id
                                            ORDER BY ar.day, ar.start_time";
                                    $res = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($res) > 0) {
                                        $sn = 1;
                                        while ($row = mysqli_fetch_assoc($res)) {
                                    ?>
                                            <tr>
                                                <th scope="row"><?php echo $sn++; ?></th>
                                                <td><?php echo $row['day']; ?></td>
                                                <td><?php echo date('h:i A', strtotime($row['start_time'])) . ' - ' . date('h:i A', strtotime($row['end_time'])); ?></td>
                                                <td><?php echo $row['subject_name']; ?></td>
                                                <td><?php echo $row['class_name'] . ($row['class_section'] ? '-' . $row['class_section'] : ''); ?></td>
                                                <td><?php echo $row['teacher_name']; ?></td>
                                                <td><?php echo $row['is_break'] == 1 ? 'Yes' : 'No'; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#updateRoutineModal-<?php echo $row['ar_id']; ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <a href="<?php echo SITEURL; ?>admin/routines.php?delete_id=<?php echo $row['ar_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this routine entry?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="updateRoutineModal-<?php echo $row['ar_id']; ?>" tabindex="-1" aria-labelledby="updateRoutineModalLabel-<?php echo $row['ar_id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="updateRoutineModalLabel-<?php echo $row['ar_id']; ?>">Update Routine</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="ar_id" value="<?php echo $row['ar_id']; ?>">
                                                                <div class="mb-3">
                                                                    <label for="day" class="form-label">Day</label>
                                                                    <select class="form-select" name="day" required>
                                                                        <option value="Sunday" <?php if ($row['day'] == 'Sunday') echo 'selected'; ?>>Sunday</option>
                                                                        <option value="Monday" <?php if ($row['day'] == 'Monday') echo 'selected'; ?>>Monday</option>
                                                                        <option value="Tuesday" <?php if ($row['day'] == 'Tuesday') echo 'selected'; ?>>Tuesday</option>
                                                                        <option value="Wednesday" <?php if ($row['day'] == 'Wednesday') echo 'selected'; ?>>Wednesday</option>
                                                                        <option value="Thursday" <?php if ($row['day'] == 'Thursday') echo 'selected'; ?>>Thursday</option>
                                                                        <option value="Friday" <?php if ($row['day'] == 'Friday') echo 'selected'; ?>>Friday</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="start_time" class="form-label">Start Time</label>
                                                                    <input type="time" class="form-control" name="start_time" value="<?php echo $row['start_time']; ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="end_time" class="form-label">End Time</label>
                                                                    <input type="time" class="form-control" name="end_time" value="<?php echo $row['end_time']; ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="subject_id" class="form-label">Subject</label>
                                                                    <select class="form-select" name="subject_id" required>
                                                                        <?php
                                                                        $res_subjects_update = mysqli_query($conn, $sql_subjects);
                                                                        while ($subj = mysqli_fetch_assoc($res_subjects_update)) {
                                                                            $selected = ($subj['subj_id'] == $row['subject_id']) ? 'selected' : '';
                                                                            echo "<option value='{$subj['subj_id']}' {$selected}>{$subj['name']}</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="class_id" class="form-label">Class</label>
                                                                    <select class="form-select" name="class_id" required>
                                                                        <?php
                                                                        $res_classes_update = mysqli_query($conn, $sql_classes);
                                                                        while ($cls = mysqli_fetch_assoc($res_classes_update)) {
                                                                            $selected = ($cls['cls_id'] == $row['class_id']) ? 'selected' : '';
                                                                            echo "<option value='{$cls['cls_id']}' {$selected}>{$cls['name']}" . ($cls['section'] ? '-' . $cls['section'] : '') . "</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="teacher_id" class="form-label">Teacher</label>
                                                                    <select class="form-select" name="teacher_id" required>
                                                                        <?php
                                                                        $res_teachers_update = mysqli_query($conn, $sql_teachers);
                                                                        while ($teach = mysqli_fetch_assoc($res_teachers_update)) {
                                                                            $selected = ($teach['teach_id'] == $row['teacher_id']) ? 'selected' : '';
                                                                            echo "<option value='{$teach['teach_id']}' {$selected}>{$teach['full_name']}</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="is_break" value="1" id="is_break_<?php echo $row['ar_id']; ?>" <?php if ($row['is_break'] == 1) echo 'checked'; ?>>
                                                                    <label class="form-check-label" for="is_break_<?php echo $row['ar_id']; ?>">Is this a break?</label>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" name="update_routine" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No routines found.</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRoutineModal" tabindex="-1" aria-labelledby="addRoutineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoutineModalLabel">Add New Routine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="day" class="form-label">Day</label>
                            <select class="form-select" name="day" required>
                                <option value="Sunday">Sunday</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" name="start_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" name="end_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject_id" class="form-label">Subject</label>
                            <select class="form-select" name="subject_id" required>
                                <?php
                                $res_subjects_add = mysqli_query($conn, $sql_subjects);
                                while ($subj = mysqli_fetch_assoc($res_subjects_add)) {
                                    echo "<option value='{$subj['subj_id']}'>{$subj['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="class_id" class="form-label">Class</label>
                            <select class="form-select" name="class_id" required>
                                <?php
                                $res_classes_add = mysqli_query($conn, $sql_classes);
                                while ($cls = mysqli_fetch_assoc($res_classes_add)) {
                                    echo "<option value='{$cls['cls_id']}'>{$cls['name']}" . ($cls['section'] ? '-' . $cls['section'] : '') . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">Teacher</label>
                            <select class="form-select" name="teacher_id" required>
                                <?php
                                $res_teachers_add = mysqli_query($conn, $sql_teachers);
                                while ($teach = mysqli_fetch_assoc($res_teachers_add)) {
                                    echo "<option value='{$teach['teach_id']}'>{$teach['full_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_break" value="1" id="is_break">
                            <label class="form-check-label" for="is_break">Is this a break?</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_routine" class="btn btn-primary">Add Routine</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="../js/admin_scripts.js"></script>

    <?php
    include('partial-admin/footer.php');
    ?>
</body>

</html>