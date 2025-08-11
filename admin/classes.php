<?php include('partial-admin/navbar.php'); 
include('partial-admin/login-check.php');?>
<?php
$page_title = "Manage Classes";

// Handle form submissions
if (isset($_POST['add_class'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $sql = "INSERT INTO classes (name, section) VALUES ('$name', '$section')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['add_class'] = "<div class='alert alert-success'>Class Added Successfully.</div>";
    } else {
        $_SESSION['add_class'] = "<div class='alert alert-danger'>Failed to Add Class.</div>";
    }
    header('location:' . SITEURL . 'admin/classes.php');
    exit();
}

if (isset($_POST['update_class'])) {
    $cls_id = $_POST['cls_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);

    $sql = "UPDATE classes SET name='$name', section='$section' WHERE cls_id=$cls_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['update_class'] = "<div class='alert alert-success'>Class Updated Successfully.</div>";
    } else {
        $_SESSION['update_class'] = "<div class='alert alert-danger'>Failed to Update Class.</div>";
    }
    header('location:' . SITEURL . 'admin/classes.php');
    exit();
}

if (isset($_GET['delete_id'])) {
    $cls_id = $_GET['delete_id'];
    $sql = "DELETE FROM classes WHERE cls_id=$cls_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['delete_class'] = "<div class='alert alert-success'>Class Deleted Successfully.</div>";
    } else {
        $_SESSION['delete_class'] = "<div class='alert alert-danger'>Failed to Delete Class.</div>";
    }
    header('location:' . SITEURL . 'admin/classes.php');
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
                        <h3 class="fs-4 mb-3"><?php echo $page_title; ?></h3>
                        <?php
                        if (isset($_SESSION['add_class'])) {
                            echo $_SESSION['add_class'];
                            unset($_SESSION['add_class']);
                        }
                        if (isset($_SESSION['update_class'])) {
                            echo $_SESSION['update_class'];
                            unset($_SESSION['update_class']);
                        }
                        if (isset($_SESSION['delete_class'])) {
                            echo $_SESSION['delete_class'];
                            unset($_SESSION['delete_class']);
                        }
                        ?>
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addClassModal">
                            <i class="bi bi-folder-plus"> </i> Add New Class
                        </button>
                        <div class="table-responsive">
                            <table class="table bg-white rounded shadow-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">S.N.</th>
                                        <th scope="col">Class Name</th>
                                        <th scope="col">Section</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM classes ORDER BY name, section";
                                    $res = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($res) > 0) {
                                        $sn = 1;
                                        while ($row = mysqli_fetch_assoc($res)) {
                                    ?>
                                            <tr>
                                                <th scope="row"><?php echo $sn++; ?></th>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['section']; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#updateClassModal-<?php echo $row['cls_id']; ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <a href="<?php echo SITEURL; ?>admin/classes.php?delete_id=<?php echo $row['cls_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this class?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="updateClassModal-<?php echo $row['cls_id']; ?>" tabindex="-1" aria-labelledby="updateClassModalLabel-<?php echo $row['cls_id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="updateClassModalLabel-<?php echo $row['cls_id']; ?>">Update Class</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="cls_id" value="<?php echo $row['cls_id']; ?>">
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Class Name</label>
                                                                    <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="section" class="form-label">Section</label>
                                                                    <select class="form-select" name="section" required>
                                                                        <option value="A" <?php if ($row['section'] == 'NA') echo 'selected'; ?>>NA</option>
                                                                        <option value="A" <?php if ($row['section'] == 'A') echo 'selected'; ?>>A</option>
                                                                        <option value="B" <?php if ($row['section'] == 'B') echo 'selected'; ?>>B</option>
                                                                        <option value="C" <?php if ($row['section'] == 'C') echo 'selected'; ?>>C</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" name="update_class" class="btn btn-primary">Update</button>
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
                                            <td colspan="4" class="text-center">No classes found.</td>
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

    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">Add New Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="section" class="form-label">Section</label>
                            <select class="form-select" name="section" required>
                                <option value="NA">NA</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_class" class="btn btn-primary">Add Class</button>
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