<?php
// Include necessary files
include('partial-admin/navbar.php');
include('partial-admin/login-check.php');

$page_title = "Manage Teachers";

// Handle form submissions
if (isset($_POST['add_teacher'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $pan = mysqli_real_escape_string($conn, $_POST['pan']);
    $level = mysqli_real_escape_string($conn, $_POST['level']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'teacher'; // Set the role to 'teacher' by default

    // Image upload handling
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {
        $image_name = $_FILES['photo']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $photo = "Teacher-" . rand(0000, 9999) . "." . $ext;
        $source_path = $_FILES['photo']['tmp_name'];
        $destination_path = "../images/teachers/" . $photo;

        $upload = move_uploaded_file($source_path, $destination_path);

        if (!$upload) {
            $_SESSION['upload'] = "<div class='alert alert-danger'>Failed to upload image.</div>";
            $photo = '';
        }
    }

    $sql = "INSERT INTO teachers (username, full_name, email, contact, pan, password, level, photo, role) VALUES ('$username', '$full_name', '$email', '$contact', '$pan', '$password', '$level', '$photo', '$role')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['add_teacher'] = "<div class='alert alert-success'>Teacher Added Successfully.</div>";
    } else {
        $_SESSION['add_teacher'] = "<div class='alert alert-danger'>Failed to Add Teacher.</div>";
    }
    header('location:' . SITEURL . 'admin/teachers.php');
    exit();
}

if (isset($_POST['update_teacher'])) {
    $teach_id = $_POST['teach_id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $pan = mysqli_real_escape_string($conn, $_POST['pan']);
    $level = mysqli_real_escape_string($conn, $_POST['level']);
    $current_photo = $_POST['current_photo'];

    // Handle new photo upload
    $photo = $current_photo;
    if (isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {
        $image_name = $_FILES['photo']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $photo = "Teacher-" . rand(0000, 9999) . "." . $ext;
        $source_path = $_FILES['photo']['tmp_name'];
        $destination_path = "../images/teachers/" . $photo;

        $upload = move_uploaded_file($source_path, $destination_path);

        if (!$upload) {
            $_SESSION['upload'] = "<div class='alert alert-danger'>Failed to upload image.</div>";
            $photo = $current_photo;
        } else {
            // Remove old photo if it exists
            if (!empty($current_photo) && file_exists("../images/teachers/" . $current_photo)) {
                unlink("../images/teachers/" . $current_photo);
            }
        }
    }

    $sql = "UPDATE teachers SET username='$username', full_name='$full_name', email='$email', contact='$contact', pan='$pan', level='$level', photo='$photo' WHERE teach_id=$teach_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['update_teacher'] = "<div class='alert alert-success'>Teacher Updated Successfully.</div>";
    } else {
        $_SESSION['update_teacher'] = "<div class='alert alert-danger'>Failed to Update Teacher.</div>";
    }
    header('location:' . SITEURL . 'admin/teachers.php');
    exit();
}


if (isset($_GET['delete_id'])) {
    // Sanitize the input to prevent SQL injection
    $teach_id = mysqli_real_escape_string($conn, $_GET['delete_id']);

    // Start a transaction for safety
    mysqli_begin_transaction($conn);

    try {
        // 1. Delete all related records from the `attendance` table
        $sql_delete_attendance = "DELETE FROM `attendance` WHERE `teacher_id` = '$teach_id'";
        if (!mysqli_query($conn, $sql_delete_attendance)) {
            throw new Exception("Failed to delete related attendance records.");
        }
        
        // 2. Delete all related records from the `academic_routine` table
        // This addresses the foreign key constraint: `CONSTRAINT fk_academic_teacher2`
        $sql_delete_routine = "DELETE FROM `academic_routine` WHERE `teacher_id2` = '$teach_id'";
        if (!mysqli_query($conn, $sql_delete_routine)) {
            throw new Exception("Failed to delete related academic routine records.");
        }
        
        // 3. Fetch photo name to delete the file
        $sql_photo = "SELECT photo FROM `teachers` WHERE `teach_id` = '$teach_id'";
        $res_photo = mysqli_query($conn, $sql_photo);
        $row_photo = mysqli_fetch_assoc($res_photo);
        $photo_name = $row_photo['photo'];

        if (!empty($photo_name) && file_exists("../images/teachers/" . $photo_name)) {
            unlink("../images/teachers/" . $photo_name);
        }

        // 4. Delete the teacher record
        $sql_delete_teacher = "DELETE FROM `teachers` WHERE `teach_id` = '$teach_id'";
        if (!mysqli_query($conn, $sql_delete_teacher)) {
            throw new Exception("Failed to delete teacher record.");
        }

        // If all queries were successful, commit the transaction
        mysqli_commit($conn);
        $_SESSION['delete_teacher'] = "<div class='alert alert-success'>Teacher and all related records deleted successfully.</div>";
    } catch (Exception $e) {
        // If any query failed, roll back the transaction
        mysqli_rollback($conn);
        $_SESSION['delete_teacher'] = "<div class='alert alert-danger'>Failed to Delete Teacher: " . $e->getMessage() . "</div>";
    }
    
    header('location:' . SITEURL . 'admin/teachers.php');
    exit();
}
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
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .table img {
            max-width: 50px;
            max-height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div id="page-content-wrapper">
            <div class="container-fluid px-4">
                <div class="row my-4">
                    <div class="col-12">
                        <h3 class="fs-4 mb-3"><?php echo $page_title; ?></h3>
                        <?php
                        if (isset($_SESSION['add_teacher'])) {
                            echo $_SESSION['add_teacher'];
                            unset($_SESSION['add_teacher']);
                        }
                        if (isset($_SESSION['update_teacher'])) {
                            echo $_SESSION['update_teacher'];
                            unset($_SESSION['update_teacher']);
                        }
                        if (isset($_SESSION['delete_teacher'])) {
                            echo $_SESSION['delete_teacher'];
                            unset($_SESSION['delete_teacher']);
                        }
                        if (isset($_SESSION['upload'])) {
                            echo $_SESSION['upload'];
                            unset($_SESSION['upload']);
                        }
                        ?>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <form action="" method="GET">
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <input type="text" class="form-control" name="search" placeholder="Search by Full Name" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-secondary">Search</button>
                                    </div>
                                    <div class="col-auto">
                                        <a href="<?php echo SITEURL; ?>admin/teachers.php" class="btn btn-outline-secondary">Clear Search</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                            <i class="bi bi-person-plus"></i> Add New Teacher
                        </button>
                        <div class="table-responsive">
                            <table class="table bg-white rounded shadow-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">S.N.</th>
                                        <th scope="col" class="text-center">Image</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Level</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $search_term = $_GET['search'] ?? '';
                                    // Modified SQL query to only select teachers with the role 'teacher'
                                    $sql = "SELECT * FROM teachers WHERE role = 'teacher'";
                                    if (!empty($search_term)) {
                                        $sanitized_search_term = mysqli_real_escape_string($conn, $search_term);
                                        $sql .= " AND full_name LIKE '%$sanitized_search_term%'";
                                    }
                                    $sql .= " ORDER BY full_name";
                                    
                                    $res = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($res) > 0) {
                                        $sn = 1;
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            $teach_id = $row['teach_id'];
                                            $full_name = $row['full_name'];
                                            $username = $row['username'];
                                            $email = $row['email'];
                                            $contact = $row['contact'];
                                            $level = $row['level'];
                                            $pan = $row['pan'];
                                            $photo = $row['photo'];
                                    ?>
                                    <tr>
                                        <th scope="row" class="text-center"><?php echo $sn++; ?></th>
                                        <td class="text-center">
                                            <?php
                                            if (!empty($photo) && file_exists("../images/teachers/" . $photo)) {
                                            ?>
                                                <img src="<?php echo SITEURL; ?>images/teachers/<?php echo htmlspecialchars($photo); ?>" alt="<?php echo htmlspecialchars($full_name); ?>">
                                            <?php
                                            } else {
                                            ?>
                                                <img src="https://via.placeholder.com/50" alt="No Image">
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($full_name); ?></td>
                                        <td><?php echo htmlspecialchars($email); ?></td>
                                        <td><?php echo htmlspecialchars($contact); ?></td>
                                        <td><?php echo htmlspecialchars($level); ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-info view-teacher" data-bs-toggle="modal" data-bs-target="#teacherDetailsModal" data-full_name="<?php echo htmlspecialchars($full_name); ?>" data-username="<?php echo htmlspecialchars($username); ?>" data-contact="<?php echo htmlspecialchars($contact); ?>" data-email="<?php echo htmlspecialchars($email); ?>" data-level="<?php echo htmlspecialchars($level); ?>" data-pan="<?php echo htmlspecialchars($pan); ?>" data-photo="<?php echo htmlspecialchars($photo); ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#updateTeacherModal-<?php echo $teach_id; ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="<?php echo SITEURL; ?>admin/teachers.php?delete_id=<?php echo $teach_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this teacher? This will also delete all their related attendance and academic routine records.');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="updateTeacherModal-<?php echo $teach_id; ?>" tabindex="-1" aria-labelledby="updateTeacherModalLabel-<?php echo $teach_id; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateTeacherModalLabel-<?php echo $teach_id; ?>">Update Teacher</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="teach_id" value="<?php echo $teach_id; ?>">
                                                        <input type="hidden" name="current_photo" value="<?php echo htmlspecialchars($photo); ?>">
                                                        <div class="mb-3">
                                                            <label for="username" class="form-label">Username</label>
                                                            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="full_name" class="form-label">Full Name</label>
                                                            <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="contact" class="form-label">Contact</label>
                                                            <input type="text" class="form-control" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="pan" class="form-label">PAN</label>
                                                            <input type="text" class="form-control" name="pan" value="<?php echo htmlspecialchars($pan); ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="level" class="form-label">Level</label>
                                                            <select class="form-select" name="level" required>
                                                                <option value="basic" <?php if ($level == 'basic') echo 'selected'; ?>>Basic</option>
                                                                <option value="secondary" <?php if ($level == 'secondary') echo 'selected'; ?>>Secondary</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="photo" class="form-label">Teacher Photo</label>
                                                            <?php
                                                            if (!empty($photo) && file_exists("../images/teachers/" . $photo)) {
                                                                echo '<div class="mb-2"><img src="' . SITEURL . 'images/teachers/' . htmlspecialchars($photo) . '" alt="Current Photo" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;"></div>';
                                                            }
                                                            ?>
                                                            <input type="file" class="form-control" name="photo">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" name="update_teacher" class="btn btn-primary">Update</button>
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
                                        <td colspan="7" class="text-center">No teachers found.</td>
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

    <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTeacherModalLabel">Add New Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" name="contact" required>
                        </div>
                        <div class="mb-3">
                            <label for="pan" class="form-label">PAN</label>
                            <input type="text" class="form-control" name="pan">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="level" class="form-label">Level</label>
                            <select class="form-select" name="level" required>
                                <option value="basic">Basic</option>
                                <option value="secondary">Secondary</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Teacher Photo</label>
                            <input type="file" class="form-control" name="photo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_teacher" class="btn btn-primary">Add Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="teacherDetailsModal" tabindex="-1" aria-labelledby="teacherDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacherDetailsModalLabel">Teacher Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalTeacherPhoto" src="" class="img-fluid rounded-circle mb-3" alt="" style="width: 150px; height: 150px; object-fit: cover;">
                    <h4 id="modalTeacherName"></h4>
                    <p><strong>Username:</strong> <span id="modalTeacherUsername"></span></p>
                    <p><strong>Level:</strong> <span id="modalTeacherLevel"></span></p>
                    <p><strong>Contact:</strong> <span id="modalTeacherContact"></span></p>
                    <p><strong>Email:</strong> <span id="modalTeacherEmail"></span></p>
                    <p><strong>PAN:</strong> <span id="modalTeacherPan"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var teacherDetailsModal = document.getElementById('teacherDetailsModal');
            if (teacherDetailsModal) {
                teacherDetailsModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var fullName = button.getAttribute('data-full_name');
                    var username = button.getAttribute('data-username');
                    var contact = button.getAttribute('data-contact');
                    var email = button.getAttribute('data-email');
                    var level = button.getAttribute('data-level');
                    var pan = button.getAttribute('data-pan');
                    var photo = button.getAttribute('data-photo');

                    var modalTeacherPhoto = document.getElementById('modalTeacherPhoto');
                    var modalTeacherName = document.getElementById('modalTeacherName');
                    var modalTeacherUsername = document.getElementById('modalTeacherUsername');
                    var modalTeacherLevel = document.getElementById('modalTeacherLevel');
                    var modalTeacherContact = document.getElementById('modalTeacherContact');
                    var modalTeacherEmail = document.getElementById('modalTeacherEmail');
                    var modalTeacherPan = document.getElementById('modalTeacherPan');
                    var siteUrl = '<?php echo SITEURL; ?>';

                    modalTeacherName.textContent = fullName;
                    modalTeacherUsername.textContent = username;
                    modalTeacherLevel.textContent = level;
                    modalTeacherContact.textContent = contact;
                    modalTeacherEmail.textContent = email;
                    modalTeacherPan.textContent = pan;

                    if (photo && photo !== 'null') {
                        modalTeacherPhoto.src = siteUrl + 'images/teachers/' + photo;
                    } else {
                        modalTeacherPhoto.src = 'https://via.placeholder.com/150';
                    }
                });
            }
        });
    </script>
    <?php
    include('partial-admin/footer.php');
    ?>
</body>

</html>