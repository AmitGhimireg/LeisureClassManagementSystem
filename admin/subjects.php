<?php include('partial-admin/navbar.php');
include('partial-admin/login-check.php');?>
<?php
$page_title = "Manage Subjects";

// Handle form submissions
if (isset($_POST['add_subject'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $sql = "INSERT INTO subjects (name) VALUES ('$name')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['add_subject'] = "<div class='alert alert-success'>Subject Added Successfully.</div>";
    } else {
        $_SESSION['add_subject'] = "<div class='alert alert-danger'>Failed to Add Subject.</div>";
    }
    header('location:' . SITEURL . 'admin/subjects.php');
    exit();
}

if (isset($_POST['update_subject'])) {
    $subj_id = $_POST['subj_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    $sql = "UPDATE subjects SET name='$name' WHERE subj_id=$subj_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['update_subject'] = "<div class='alert alert-success'>Subject Updated Successfully.</div>";
    } else {
        $_SESSION['update_subject'] = "<div class='alert alert-danger'>Failed to Update Subject.</div>";
    }
    header('location:' . SITEURL . 'admin/subjects.php');
    exit();
}

if (isset($_GET['delete_id'])) {
    $subj_id = $_GET['delete_id'];
    $sql = "DELETE FROM subjects WHERE subj_id=$subj_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['delete_subject'] = "<div class='alert alert-success'>Subject Deleted Successfully.</div>";
    } else {
        $_SESSION['delete_subject'] = "<div class='alert alert-danger'>Failed to Delete Subject.</div>";
    }
    header('location:' . SITEURL . 'admin/subjects.php');
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
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div id="page-content-wrapper">
            <div class="container-fluid px-4">
                <div class="row my-4">
                    <div class="col-12">
                        <div class="card p-4 shadow-sm">
                        <h3 class="fs-4 mb-5 text-center"><b><?php echo $page_title; ?></b></h3>
                        <?php
                        if (isset($_SESSION['add_subject'])) {
                            echo $_SESSION['add_subject'];
                            unset($_SESSION['add_subject']);
                        }
                        if (isset($_SESSION['update_subject'])) {
                            echo $_SESSION['update_subject'];
                            unset($_SESSION['update_subject']);
                        }
                        if (isset($_SESSION['delete_subject'])) {
                            echo $_SESSION['delete_subject'];
                            unset($_SESSION['delete_subject']);
                        }
                        ?>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <form action="" method="GET">
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <input type="text" class="form-control" name="search" placeholder="Search Subject Name" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-secondary">Search</button>
                                    </div>
                                    <div class="col-auto">
                                        <a href="<?php echo SITEURL; ?>admin/subjects.php" class="btn btn-outline-secondary">Clear Search</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                           <i class="bi bi-journal-plus"></i> Add New Subject
                        </button>
                        <div class="table-responsive">
                            <table class="table bg-white rounded shadow-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">S.N.</th>
                                        <th scope="col">Subject Name</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $search_term = $_GET['search'] ?? '';
                                    $sql = "SELECT * FROM subjects";
                                    if (!empty($search_term)) {
                                        $sanitized_search_term = mysqli_real_escape_string($conn, $search_term);
                                        $sql .= " WHERE name LIKE '%$sanitized_search_term%'";
                                    }
                                    $sql .= " ORDER BY name";

                                    $res = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($res) > 0) {
                                        $sn = 1;
                                        while ($row = mysqli_fetch_assoc($res)) {
                                    ?>
                                            <tr>
                                                <th scope="row"><?php echo $sn++; ?></th>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#updateSubjectModal-<?php echo $row['subj_id']; ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <a href="<?php echo SITEURL; ?>admin/subjects.php?delete_id=<?php echo $row['subj_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this subject?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="updateSubjectModal-<?php echo $row['subj_id']; ?>" tabindex="-1" aria-labelledby="updateSubjectModalLabel-<?php echo $row['subj_id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="updateSubjectModalLabel-<?php echo $row['subj_id']; ?>">Update Subject</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="subj_id" value="<?php echo $row['subj_id']; ?>">
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Subject Name</label>
                                                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" name="update_subject" class="btn btn-primary">Update</button>
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
                                            <td colspan="3" class="text-center">No subjects found.</td>
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

    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Add New Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_subject" class="btn btn-primary">Add Subject</button>
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