<?php
// Include the constants file to use SITEURL
require_once('../config/constants.php');

// Check if the user is logged in by verifying the 'teach_id' and 'username' session variables
$is_logged_in = isset($_SESSION['teach_id']) && isset($_SESSION['username']);
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M.R.A.S.S. - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand me-auto" href="index.php">
                <i class="bi bi-mortarboard-fill me-2"></i>M.R.A.S.S.
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" aria-current="page" href="index.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'teachers.php') ? 'active' : ''; ?>" href="teachers.php">TEACHERS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'classes.php') ? 'active' : ''; ?>" href="classes.php">CLASSES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'subjects.php') ? 'active' : ''; ?>" href="subjects.php">SUBJECTS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'routines.php') ? 'active' : ''; ?>" href="routines.php">ROUTINE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'school_details.php') ? 'active' : ''; ?>" href="School_details.php">SCHOOL DETAILS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" href="contact.php">CONTACT</a>
                    </li>

                    <?php if ($is_logged_in): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'admin_profile.php') ? 'active' : ''; ?>" href="admin_profile.php">
                                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                        </li>
                        <!-- <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>" href="login.php">LOGIN</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'register.php') ? 'active' : ''; ?>" href="register.php">REGISTER</a>
                        </li>
                    <?php endif; ?> -->
                </ul>
            </div>
        </div>
    </nav>
    <marquee behavior="scroll" direction="left" scrollamount="10">
        <h3 class="my-0">
            <i class="bi bi-info-circle-fill"></i>
            <b class="text-success" style="font-size: 20px;">
                Welcome to Admin panel of leisure class management system!
                It is an all-in-one web-site that helps schools easily manage daily leisure class activities, and class routines.
            </b>
        </h3>
    </marquee>