<?php
include('partial-admin/navbar.php');
include('partial-admin/login-check.php');

$page_title = "Manage Routines";

// Handle form submissions for adding, updating, and deleting academic routines
if (isset($_POST['add_routine'])) {
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);

    // Correctly handle optional fields by setting them to NULL if empty
    $subject_id1 = !empty($_POST['subject_id1']) ? "'" . mysqli_real_escape_string($conn, $_POST['subject_id1']) . "'" : "NULL";
    $subject_id2 = !empty($_POST['subject_id2']) ? "'" . mysqli_real_escape_string($conn, $_POST['subject_id2']) . "'" : "NULL";
    $teacher_id1 = !empty($_POST['teacher_id1']) ? "'" . mysqli_real_escape_string($conn, $_POST['teacher_id1']) . "'" : "NULL";
    $teacher_id2 = !empty($_POST['teacher_id2']) ? "'" . mysqli_real_escape_string($conn, $_POST['teacher_id2']) . "'" : "NULL";
    $day_range1 = !empty($_POST['day_range1']) ? "'" . mysqli_real_escape_string($conn, $_POST['day_range1']) . "'" : "NULL";
    $day_range2 = !empty($_POST['day_range2']) ? "'" . mysqli_real_escape_string($conn, $_POST['day_range2']) . "'" : "NULL";
    $is_break = isset($_POST['is_break']) ? '1' : '0';

    $sql = "INSERT INTO academic_routine (class_id, start_time, end_time, subject_id1, subject_id2, teacher_id1, teacher_id2, day_range1, day_range2, is_break) 
            VALUES ('$class_id', '$start_time', '$end_time', $subject_id1, $subject_id2, $teacher_id1, $teacher_id2, $day_range1, $day_range2, '$is_break')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['add_routine'] = "<div class='alert alert-success'>Routine Added Successfully.</div>";
    } else {
        $_SESSION['add_routine'] = "<div class='alert alert-danger'>Failed to Add Routine: " . mysqli_error($conn) . "</div>";
    }
    header('location:' . SITEURL . 'admin/routines.php');
    exit();
}

if (isset($_POST['update_routine'])) {
    $ar_id = $_POST['ar_id'];
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);

    // Correctly handle optional fields by setting them to NULL if empty
    $subject_id1 = !empty($_POST['subject_id1']) ? "'" . mysqli_real_escape_string($conn, $_POST['subject_id1']) . "'" : "NULL";
    $subject_id2 = !empty($_POST['subject_id2']) ? "'" . mysqli_real_escape_string($conn, $_POST['subject_id2']) . "'" : "NULL";
    $teacher_id1 = !empty($_POST['teacher_id1']) ? "'" . mysqli_real_escape_string($conn, $_POST['teacher_id1']) . "'" : "NULL";
    $teacher_id2 = !empty($_POST['teacher_id2']) ? "'" . mysqli_real_escape_string($conn, $_POST['teacher_id2']) . "'" : "NULL";
    $day_range1 = !empty($_POST['day_range1']) ? "'" . mysqli_real_escape_string($conn, $_POST['day_range1']) . "'" : "NULL";
    $day_range2 = !empty($_POST['day_range2']) ? "'" . mysqli_real_escape_string($conn, $_POST['day_range2']) . "'" : "NULL";
    $is_break = isset($_POST['is_break']) ? '1' : '0';

    $sql = "UPDATE academic_routine SET class_id='$class_id', start_time='$start_time', end_time='$end_time', subject_id1=$subject_id1, subject_id2=$subject_id2, teacher_id1=$teacher_id1, teacher_id2=$teacher_id2, day_range1=$day_range1, day_range2=$day_range2, is_break='$is_break' WHERE ar_id=$ar_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['update_routine'] = "<div class='alert alert-success'>Routine Updated Successfully.</div>";
    } else {
        $_SESSION['update_routine'] = "<div class='alert alert-danger'>Failed to Update Routine: " . mysqli_error($conn) . "</div>";
    }
    header('location:' . SITEURL . 'admin/routines.php');
    exit();
}

if (isset($_GET['delete_id'])) {
    $ar_id = $_GET['delete_id'];
    $sql = "DELETE FROM academic_routine WHERE ar_id=$ar_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['delete_routine'] = "<div class='alert alert-success'>Routine Deleted Successfully.</div>";
    } else {
        $_SESSION['delete_routine'] = "<div class='alert alert-danger'>Failed to Delete Routine: " . mysqli_error($conn) . "</div>";
    }
    header('location:' . SITEURL . 'admin/routines.php');
    exit();
}

// Handle form submissions for adding, updating, and deleting leisure routines
if (isset($_POST['add_leisure_routine'])) {
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $start_time = mysqli_real_escape_string($conn, $_POST['st_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['ed_time']);

    $sql_leisure_add = "INSERT INTO leisure_teacher (teacher_id, st_time, ed_time) 
                        VALUES ('$teacher_id', '$start_time', '$end_time')";

    if (mysqli_query($conn, $sql_leisure_add)) {
        $_SESSION['add_leisure_routine'] = "<div class='alert alert-success'>Leisure Routine Added Successfully.</div>";
    } else {
        $_SESSION['add_leisure_routine'] = "<div class='alert alert-danger'>Failed to Add Leisure Routine: " . mysqli_error($conn) . "</div>";
    }
    header('location:' . SITEURL . 'admin/routines.php');
    exit();
}

if (isset($_POST['update_leisure_routine'])) {
    $lt_id = $_POST['lt_id'];
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $start_time = mysqli_real_escape_string($conn, $_POST['st_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['ed_time']);

    $sql_leisure_update = "UPDATE leisure_teacher SET teacher_id='$teacher_id', st_time='$start_time', ed_time='$end_time' WHERE lt_id=$lt_id";

    if (mysqli_query($conn, $sql_leisure_update)) {
        $_SESSION['update_leisure_routine'] = "<div class='alert alert-success'>Leisure Routine Updated Successfully.</div>";
    } else {
        $_SESSION['update_leisure_routine'] = "<div class='alert alert-danger'>Failed to Update Leisure Routine: " . mysqli_error($conn) . "</div>";
    }
    header('location:' . SITEURL . 'admin/routines.php');
    exit();
}

if (isset($_GET['delete_leisure_id'])) {
    $lt_id = $_GET['delete_leisure_id'];
    $sql_leisure_delete = "DELETE FROM leisure_teacher WHERE lt_id=$lt_id";
    if (mysqli_query($conn, $sql_leisure_delete)) {
        $_SESSION['delete_leisure_routine'] = "<div class='alert alert-success'>Leisure Routine Deleted Successfully.</div>";
    } else {
        $_SESSION['delete_leisure_routine'] = "<div class='alert alert-danger'>Failed to Delete Leisure Routine: " . mysqli_error($conn) . "</div>";
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

// --- ACADEMIC ROUTINE SEARCH & FETCH ---
$search_teacher = $_GET['search_teacher'] ?? '';
$sql_academic = "SELECT ar.*, c.name AS class_name, c.section AS class_section, 
            s1.name AS subject1_name, s2.name AS subject2_name,
            t1.full_name AS teacher1_name, t2.full_name AS teacher2_name
        FROM academic_routine ar
        LEFT JOIN classes c ON ar.class_id = c.cls_id
        LEFT JOIN subjects s1 ON ar.subject_id1 = s1.subj_id
        LEFT JOIN subjects s2 ON ar.subject_id2 = s2.subj_id
        LEFT JOIN teachers t1 ON ar.teacher_id1 = t1.teach_id
        LEFT JOIN teachers t2 ON ar.teacher_id2 = t2.teach_id";

if (!empty($search_teacher)) {
    $sanitized_search_term = mysqli_real_escape_string($conn, $search_teacher);
    $sql_academic .= " WHERE t1.full_name LIKE '%$sanitized_search_term%' OR t2.full_name LIKE '%$sanitized_search_term%'";
}

$sql_academic .= " ORDER BY ar.start_time";

$res_academic = mysqli_query($conn, $sql_academic);

// --- LEISURE ROUTINE SEARCH & FETCH ---
$search_leisure_teacher = $_GET['search_leisure_teacher'] ?? '';
$sql_leisure = "SELECT lt.lt_id, t.full_name AS teacher_name, lt.st_time, lt.ed_time
                FROM leisure_teacher lt
                JOIN teachers t ON lt.teacher_id = t.teach_id";

if (!empty($search_leisure_teacher)) {
    $sanitized_search_term_leisure = mysqli_real_escape_string($conn, $search_leisure_teacher);
    $sql_leisure .= " WHERE t.full_name LIKE '%$sanitized_search_term_leisure%'";
}

$sql_leisure .= " ORDER BY lt.st_time";

$res_leisure = mysqli_query($conn, $sql_leisure);
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
                        <div class="card p-4 shadow-sm mb-5">
                            <h3 class="fs-4 mb-5 text-center"><b>Manage Academic Routines</b></h3>
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

                            <div class="d-flex justify-content-center mb-3">
                                <form action="" method="GET">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-auto">
                                            <input type="text" class="form-control" name="search_teacher" placeholder="Search by Teacher Name" value="<?php echo htmlspecialchars($_GET['search_teacher'] ?? ''); ?>">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-secondary">Search</button>
                                        </div>
                                        <div class="col-auto">
                                            <a href="<?php echo SITEURL; ?>admin/routines.php" class="btn btn-outline-secondary">Clear Search</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addRoutineModal">
                                <i class="bi bi-calendar-plus"></i> Add New Academic Routine
                            </button>
                            <div class="table-responsive">
                                <table class="table bg-white rounded shadow-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.N.</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Subject 1</th>
                                            <th scope="col">Subject 2</th>
                                            <th scope="col">Class</th>
                                            <th scope="col">Teacher 1</th>
                                            <th scope="col">Teacher 2</th>
                                            <th scope="col">Day Range 1</th>
                                            <th scope="col">Day Range 2</th>
                                            <th scope="col">Is Break?</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($res_academic) > 0) {
                                            $sn = 1;
                                            $search_teacher_lower = strtolower($search_teacher);
                                            while ($row = mysqli_fetch_assoc($res_academic)) {
                                                $teacher1_name_lower = strtolower($row['teacher1_name'] ?? '');
                                                $teacher2_name_lower = strtolower($row['teacher2_name'] ?? '');

                                                $subject1_display = '';
                                                $teacher1_display = '';
                                                $day_range1_display = '';

                                                $subject2_display = '';
                                                $teacher2_display = '';
                                                $day_range2_display = '';

                                                if (!empty($search_teacher)) {
                                                    if (strpos($teacher1_name_lower, $search_teacher_lower) !== false) {
                                                        $subject1_display = htmlspecialchars($row['subject1_name'] ?? '');
                                                        $teacher1_display = htmlspecialchars($row['teacher1_name'] ?? '');
                                                        $day_range1_display = htmlspecialchars($row['day_range1'] ?? '');
                                                    }
                                                    if (strpos($teacher2_name_lower, $search_teacher_lower) !== false) {
                                                        $subject2_display = htmlspecialchars($row['subject2_name'] ?? '');
                                                        $teacher2_display = htmlspecialchars($row['teacher2_name'] ?? '');
                                                        $day_range2_display = htmlspecialchars($row['day_range2'] ?? '');
                                                    }
                                                } else {
                                                    $subject1_display = htmlspecialchars($row['subject1_name'] ?? '');
                                                    $teacher1_display = htmlspecialchars($row['teacher1_name'] ?? '');
                                                    $day_range1_display = htmlspecialchars($row['day_range1'] ?? '');
                                                    $subject2_display = htmlspecialchars($row['subject2_name'] ?? '-');
                                                    $teacher2_display = htmlspecialchars($row['teacher2_name'] ?? '-');
                                                    $day_range2_display = htmlspecialchars($row['day_range2'] ?? '-');
                                                }

                                                if (!empty($search_teacher) && empty($teacher1_display) && empty($teacher2_display)) {
                                                    continue;
                                                }
                                        ?>
                                                <tr>
                                                    <th scope="row"><?php echo $sn++; ?></th>
                                                    <td><?php echo date('h:i A', strtotime($row['start_time'])) . ' - ' . date('h:i A', strtotime($row['end_time'])); ?></td>
                                                    <td><?php echo $subject1_display; ?></td>
                                                    <td><?php echo $subject2_display; ?></td>
                                                    <td><?php echo htmlspecialchars($row['class_name'] . ($row['class_section'] ? ' (' . $row['class_section'] . ')' : '')); ?></td>
                                                    <td><?php echo $teacher1_display; ?></td>
                                                    <td><?php echo $teacher2_display; ?></td>
                                                    <td><?php echo $day_range1_display; ?></td>
                                                    <td><?php echo $day_range2_display; ?></td>
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
                                                                <h5 class="modal-title" id="updateRoutineModalLabel-<?php echo $row['ar_id']; ?>">Update Academic Routine</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="" method="POST">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="ar_id" value="<?php echo $row['ar_id']; ?>">
                                                                    <div class="mb-3">
                                                                        <label for="class_id" class="form-label">Class</label>
                                                                        <select class="form-select" name="class_id" required>
                                                                            <?php
                                                                            $res_classes_update = mysqli_query($conn, $sql_classes);
                                                                            while ($cls = mysqli_fetch_assoc($res_classes_update)) {
                                                                                $selected = ($cls['cls_id'] == $row['class_id']) ? 'selected' : '';
                                                                                echo "<option value='{$cls['cls_id']}' {$selected}>{$cls['name']}" . ($cls['section'] ? ' (' . $cls['section'] . ')' : '') . "</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="start_time" class="form-label">Start Time</label>
                                                                        <input type="time" class="form-control" name="start_time" value="<?php echo $row['start_time']; ?>">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="end_time" class="form-label">End Time</label>
                                                                        <input type="time" class="form-control" name="end_time" value="<?php echo $row['end_time']; ?>">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="subject_id1" class="form-label">Subject 1</label>
                                                                        <select class="form-select" name="subject_id1">
                                                                            <option value="">-- Select Subject 1 --</option>
                                                                            <?php
                                                                            $res_subjects_update = mysqli_query($conn, $sql_subjects);
                                                                            while ($subj = mysqli_fetch_assoc($res_subjects_update)) {
                                                                                $selected = ($subj['subj_id'] == $row['subject_id1']) ? 'selected' : '';
                                                                                echo "<option value='{$subj['subj_id']}' {$selected}>{$subj['name']}</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="subject_id2" class="form-label">Subject 2 (Optional)</label>
                                                                        <select class="form-select" name="subject_id2">
                                                                            <option value="">-- Select Subject 2 --</option>
                                                                            <?php
                                                                            $res_subjects_update_2 = mysqli_query($conn, $sql_subjects);
                                                                            while ($subj = mysqli_fetch_assoc($res_subjects_update_2)) {
                                                                                $selected = ($subj['subj_id'] == $row['subject_id2']) ? 'selected' : '';
                                                                                echo "<option value='{$subj['subj_id']}' {$selected}>{$subj['name']}</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="teacher_id1" class="form-label">Teacher 1</label>
                                                                        <select class="form-select" name="teacher_id1">
                                                                            <option value="">-- Select Teacher 1 --</option>
                                                                            <?php
                                                                            $res_teachers_update = mysqli_query($conn, $sql_teachers);
                                                                            while ($teach = mysqli_fetch_assoc($res_teachers_update)) {
                                                                                $selected = ($teach['teach_id'] == $row['teacher_id1']) ? 'selected' : '';
                                                                                echo "<option value='{$teach['teach_id']}' {$selected}>{$teach['full_name']}</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="teacher_id2" class="form-label">Teacher 2 (Optional)</label>
                                                                        <select class="form-select" name="teacher_id2">
                                                                            <option value="">-- Select Teacher 2 --</option>
                                                                            <?php
                                                                            $res_teachers_update_2 = mysqli_query($conn, $sql_teachers);
                                                                            while ($teach = mysqli_fetch_assoc($res_teachers_update_2)) {
                                                                                $selected = ($teach['teach_id'] == $row['teacher_id2']) ? 'selected' : '';
                                                                                echo "<option value='{$teach['teach_id']}' {$selected}>{$teach['full_name']}</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="day_range1" class="form-label">Day Range 1</label>
                                                                        <input type="text" class="form-control" name="day_range1" value="<?php echo htmlspecialchars($row['day_range1'] ?? ''); ?>" placeholder="e.g., 1-3, 1-4, 1-6">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="day_range2" class="form-label">Day Range 2 (Optional)</label>
                                                                        <input type="text" class="form-control" name="day_range2" value="<?php echo htmlspecialchars($row['day_range2'] ?? ''); ?>" placeholder="e.g., 1-3, 1-4, 1-6">
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
                                                <td colspan="11" class="text-center">No academic routines found.</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card p-4 shadow-sm">
                            <h3 class="fs-4 mb-5 text-center"><b>Manage Leisure Routines</b></h3>
                            <?php
                            if (isset($_SESSION['add_leisure_routine'])) {
                                echo $_SESSION['add_leisure_routine'];
                                unset($_SESSION['add_leisure_routine']);
                            }
                            if (isset($_SESSION['update_leisure_routine'])) {
                                echo $_SESSION['update_leisure_routine'];
                                unset($_SESSION['update_leisure_routine']);
                            }
                            if (isset($_SESSION['delete_leisure_routine'])) {
                                echo $_SESSION['delete_leisure_routine'];
                                unset($_SESSION['delete_leisure_routine']);
                            }
                            ?>
                             <div class="d-flex justify-content-center mb-3">
                                <form action="" method="GET">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-auto">
                                            <input type="text" class="form-control" name="search_leisure_teacher" placeholder="Search by Teacher Name" value="<?php echo htmlspecialchars($_GET['search_leisure_teacher'] ?? ''); ?>">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-secondary">Search</button>
                                        </div>
                                        <div class="col-auto">
                                            <a href="<?php echo SITEURL; ?>admin/routines.php" class="btn btn-outline-secondary">Clear Search</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addLeisureRoutineModal">
                                <i class="bi bi-plus-lg"></i> Add New Leisure Routine
                            </button>
                            <div class="table-responsive">
                                <table class="table bg-white rounded shadow-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.N.</th>
                                            <th scope="col">Teacher Name</th>
                                            <th scope="col">Start Time</th>
                                            <th scope="col">End Time</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($res_leisure) > 0) {
                                            $sn = 1;
                                            while ($row = mysqli_fetch_assoc($res_leisure)) {
                                        ?>
                                                <tr>
                                                    <th scope="row"><?php echo $sn++; ?></th>
                                                    <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                                                    <td><?php echo date('h:i A', strtotime($row['st_time'])); ?></td>
                                                    <td><?php echo date('h:i A', strtotime($row['ed_time'])); ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#updateLeisureRoutineModal-<?php echo $row['lt_id']; ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <a href="<?php echo SITEURL; ?>admin/routines.php?delete_leisure_id=<?php echo $row['lt_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this leisure routine?');">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="updateLeisureRoutineModal-<?php echo $row['lt_id']; ?>" tabindex="-1" aria-labelledby="updateLeisureRoutineModalLabel-<?php echo $row['lt_id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="updateLeisureRoutineModalLabel-<?php echo $row['lt_id']; ?>">Update Leisure Routine</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="" method="POST">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="lt_id" value="<?php echo $row['lt_id']; ?>">
                                                                    <div class="mb-3">
                                                                        <label for="teacher_id" class="form-label">Teacher</label>
                                                                        <select class="form-select" name="teacher_id" required>
                                                                            <option value="">-- Select Teacher --</option>
                                                                            <?php
                                                                            $res_teachers_update = mysqli_query($conn, $sql_teachers);
                                                                            while ($teach = mysqli_fetch_assoc($res_teachers_update)) {
                                                                                $selected = ($teach['full_name'] == $row['teacher_name']) ? 'selected' : '';
                                                                                echo "<option value='{$teach['teach_id']}' {$selected}>{$teach['full_name']}</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="st_time" class="form-label">Start Time</label>
                                                                        <input type="time" class="form-control" name="st_time" value="<?php echo $row['st_time']; ?>">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="ed_time" class="form-label">End Time</label>
                                                                        <input type="time" class="form-control" name="ed_time" value="<?php echo $row['ed_time']; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" name="update_leisure_routine" class="btn btn-primary">Update</button>
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
                                                <td colspan="5" class="text-center">No leisure routines found.</td>
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
    </div>

    <div class="modal fade" id="addRoutineModal" tabindex="-1" aria-labelledby="addRoutineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoutineModalLabel">Add New Academic Routine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="class_id" class="form-label">Class</label>
                            <select class="form-select" name="class_id" required>
                                <option value="">-- Select Class --</option>
                                <?php
                                $res_classes_add = mysqli_query($conn, $sql_classes);
                                while ($cls = mysqli_fetch_assoc($res_classes_add)) {
                                    echo "<option value='{$cls['cls_id']}'>{$cls['name']}" . ($cls['section'] ? ' (' . $cls['section'] . ')' : '') . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" name="start_time">
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" name="end_time">
                        </div>
                        <div class="mb-3">
                            <label for="subject_id1" class="form-label">Subject 1</label>
                            <select class="form-select" name="subject_id1">
                                <option value="">-- Select Subject 1 --</option>
                                <?php
                                $res_subjects_add = mysqli_query($conn, $sql_subjects);
                                while ($subj = mysqli_fetch_assoc($res_subjects_add)) {
                                    echo "<option value='{$subj['subj_id']}'>{$subj['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subject_id2" class="form-label">Subject 2 (Optional)</label>
                            <select class="form-select" name="subject_id2">
                                <option value="">-- Select Subject 2 --</option>
                                <?php
                                $res_subjects_add_2 = mysqli_query($conn, $sql_subjects);
                                while ($subj = mysqli_fetch_assoc($res_subjects_add_2)) {
                                    echo "<option value='{$subj['subj_id']}'>{$subj['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="teacher_id1" class="form-label">Teacher 1</label>
                            <select class="form-select" name="teacher_id1">
                                <option value="">-- Select Teacher 1 --</option>
                                <?php
                                $res_teachers_add = mysqli_query($conn, $sql_teachers);
                                while ($teach = mysqli_fetch_assoc($res_teachers_add)) {
                                    echo "<option value='{$teach['teach_id']}'>{$teach['full_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="teacher_id2" class="form-label">Teacher 2 (Optional)</label>
                            <select class="form-select" name="teacher_id2">
                                <option value="">-- Select Teacher 2 --</option>
                                <?php
                                $res_teachers_add_2 = mysqli_query($conn, $sql_teachers);
                                while ($teach = mysqli_fetch_assoc($res_teachers_add_2)) {
                                    echo "<option value='{$teach['teach_id']}'>{$teach['full_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="day_range1" class="form-label">Day Range 1</label>
                            <input type="text" class="form-control" name="day_range1" placeholder="e.g., 1-3, 1-4, 1-6">
                        </div>
                        <div class="mb-3">
                            <label for="day_range2" class="form-label">Day Range 2 (Optional)</label>
                            <input type="text" class="form-control" name="day_range2" placeholder="e.g., 1-3, 1-4, 1-6">
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

    <div class="modal fade" id="addLeisureRoutineModal" tabindex="-1" aria-labelledby="addLeisureRoutineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLeisureRoutineModalLabel">Add New Leisure Routine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">Teacher</label>
                            <select class="form-select" name="teacher_id" required>
                                <option value="">-- Select Teacher --</option>
                                <?php
                                $res_teachers_add = mysqli_query($conn, $sql_teachers);
                                while ($teach = mysqli_fetch_assoc($res_teachers_add)) {
                                    echo "<option value='{$teach['teach_id']}'>{$teach['full_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="st_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" name="st_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="ed_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" name="ed_time" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_leisure_routine" class="btn btn-primary">Add Leisure Routine</button>
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