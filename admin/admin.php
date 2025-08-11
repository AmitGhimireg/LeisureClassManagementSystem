<?php
include('partial-admin/navbar.php');
include('partial-admin/login-check.php');

$page_title = "Manage Admins";
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
    <div class="main-content">
        <div class="container mt-4">
            <h2 class="text-center mb-4"><?php echo $page_title; ?></h2>

            <?php
            // SQL query to fetch all teachers with the role 'admin', including the photo column
            $sql = "SELECT `teach_id`, `username`, `full_name`, `contact`, `email`, `pan`, `photo`, `level`, `role`, `created_at` FROM `teachers` WHERE `role` = 'admin'";
            $res = mysqli_query($conn, $sql);

            // Check if the query was successful
            if ($res == true) {
                // Count the number of rows
                $count = mysqli_num_rows($res);

                // Check if there are any admin teachers
                if ($count > 0) {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">S.N.</th>
                                    <th scope="col" class="text-center">Image</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">PAN Number</th>
                                    <th scope="col">Level</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sn = 1; // Serial number counter
                                while ($rows = mysqli_fetch_assoc($res)) {
                                    $teach_id = $rows['teach_id'];
                                    $full_name = $rows['full_name'];
                                    $username = $rows['username'];
                                    $email = $rows['email'];
                                    $contact = $rows['contact'];
                                    $pan = $rows['pan'];
                                    $photo = $rows['photo'];
                                    $level = $rows['level'];
                                    $created_at = $rows['created_at'];
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
                                        <td><?php echo htmlspecialchars($username); ?></td>
                                        <td><?php echo htmlspecialchars($email); ?></td>
                                        <td><?php echo htmlspecialchars($contact); ?></td>
                                        <td><?php echo htmlspecialchars($pan); ?></td>
                                        <td><?php echo htmlspecialchars($level); ?></td>
                                        <td><?php echo htmlspecialchars($created_at); ?></td>
                                        <td>
                                            <a href="<?php echo SITEURL; ?>admin/update-profile.php?id=<?php echo $teach_id; ?>" class="btn btn-sm btn-success">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?php echo SITEURL; ?>admin/delete-profile.php?id=<?php echo $teach_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this admin account?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else {
                    echo "<div class='alert alert-info text-center'>No Admin Teachers Found.</div>";
                }
            } else {
                echo "<div class='alert alert-danger text-center'>Failed to retrieve data.</div>";
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('partial-admin/footer.php'); ?>