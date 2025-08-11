<?php
include('partial-admin/navbar.php');
include('partial-admin/login-check.php');

$page_title = "Manage School Details";

// Handle form submissions for Add, Update, and Delete
if (isset($_POST['add_school_details'])) {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $opening_hour = mysqli_real_escape_string($conn, $_POST['opening_hour']);
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO school_details (`address`, `phone`, `email`, `website`, `opening_hour`, `created_at`) VALUES ('$address', '$phone', '$email', '$website', '$opening_hour', '$created_at')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['add_school_details'] = "<div class='alert alert-success'>School Details Added Successfully.</div>";
    } else {
        $_SESSION['add_school_details'] = "<div class='alert alert-danger'>Failed to Add School Details.</div>";
    }
    header('location:' . SITEURL . 'admin/school_details.php');
    exit();
}

if (isset($_POST['update_school_details'])) {
    $sd_id = $_POST['sd_id'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $opening_hour = mysqli_real_escape_string($conn, $_POST['opening_hour']);

    $sql = "UPDATE school_details SET `address`='$address', `phone`='$phone', `email`='$email', `website`='$website', `opening_hour`='$opening_hour' WHERE `sd_id`=$sd_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['update_school_details'] = "<div class='alert alert-success'>School Details Updated Successfully.</div>";
    } else {
        $_SESSION['update_school_details'] = "<div class='alert alert-danger'>Failed to Update School Details.</div>";
    }
    header('location:' . SITEURL . 'admin/school_details.php');
    exit();
}

if (isset($_GET['delete_id'])) {
    $sd_id = $_GET['delete_id'];
    $sql = "DELETE FROM school_details WHERE sd_id=$sd_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['delete_school_details'] = "<div class='alert alert-success'>School Details Deleted Successfully.</div>";
    } else {
        $_SESSION['delete_school_details'] = "<div class='alert alert-danger'>Failed to Delete School Details.</div>";
    }
    header('location:' . SITEURL . 'admin/school_details.php');
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
                        // Display session messages
                        if (isset($_SESSION['add_school_details'])) {
                            echo $_SESSION['add_school_details'];
                            unset($_SESSION['add_school_details']);
                        }
                        if (isset($_SESSION['update_school_details'])) {
                            echo $_SESSION['update_school_details'];
                            unset($_SESSION['update_school_details']);
                        }
                        if (isset($_SESSION['delete_school_details'])) {
                            echo $_SESSION['delete_school_details'];
                            unset($_SESSION['delete_school_details']);
                        }
                        ?>
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSchoolDetailsModal">
                            <i class="bi bi-plus-square"></i> Add New School Detail
                        </button>
                        <div class="table-responsive">
                            <table class="table bg-white rounded shadow-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">S.N.</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Website</th>
                                        <th scope="col">Opening Hours</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // SQL query to fetch all school details
                                    $sql = "SELECT `sd_id`, `address`, `phone`, `email`, `website`, `opening_hour`, `created_at` FROM `school_details` ORDER BY created_at DESC";
                                    $res = mysqli_query($conn, $sql);

                                    // Check if the query was successful and there are rows
                                    if ($res && mysqli_num_rows($res) > 0) {
                                        $sn = 1; // Serial number counter
                                        while ($rows = mysqli_fetch_assoc($res)) {
                                            $sd_id = $rows['sd_id'];
                                            $address = $rows['address'];
                                            $phone = $rows['phone'];
                                            $email = $rows['email'];
                                            $website = $rows['website'];
                                            $opening_hour = $rows['opening_hour'];
                                            $created_at = $rows['created_at'];
                                    ?>
                                            <tr>
                                                <th scope="row" class="text-center"><?php echo $sn++; ?></th>
                                                <td><?php echo htmlspecialchars($address); ?></td>
                                                <td><?php echo htmlspecialchars($phone); ?></td>
                                                <td><?php echo htmlspecialchars($email); ?></td>
                                                <td><?php echo htmlspecialchars($website); ?></td>
                                                <td><?php echo htmlspecialchars($opening_hour); ?></td>
                                                <td><?php echo htmlspecialchars($created_at); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#updateSchoolDetailsModal-<?php echo $sd_id; ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <a href="<?php echo SITEURL; ?>admin/school_details.php?delete_id=<?php echo $sd_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="updateSchoolDetailsModal-<?php echo $sd_id; ?>" tabindex="-1" aria-labelledby="updateSchoolDetailsModalLabel-<?php echo $sd_id; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="updateSchoolDetailsModalLabel-<?php echo $sd_id; ?>">Update School Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="sd_id" value="<?php echo $sd_id; ?>">
                                                                <div class="mb-3">
                                                                    <label for="address" class="form-label">Address</label>
                                                                    <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="phone" class="form-label">Phone</label>
                                                                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="email" class="form-label">Email</label>
                                                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="website" class="form-label">Website</label>
                                                                    <input type="text" class="form-control" name="website" value="<?php echo htmlspecialchars($website); ?>">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="opening_hour" class="form-label">Opening Hours</label>
                                                                    <input type="text" class="form-control" name="opening_hour" value="<?php echo htmlspecialchars($opening_hour); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" name="update_school_details" class="btn btn-primary">Update</button>
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
                                            <td colspan="8" class="text-center">No school details found.</td>
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

    <div class="modal fade" id="addSchoolDetailsModal" tabindex="-1" aria-labelledby="addSchoolDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSchoolDetailsModalLabel">Add New School Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="text" class="form-control" name="website">
                        </div>
                        <div class="mb-3">
                            <label for="opening_hour" class="form-label">Opening Hours</label>
                            <input type="text" class="form-control" name="opening_hour">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_school_details" class="btn btn-primary">Add Details</button>
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