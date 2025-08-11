<?php include('partial-admin/navbar.php'); 
include('partial-admin/login-check.php');?>

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
</style>

<div class="container-fluid px-4 flex-grow-1">
    <?php
    if (isset($_SESSION['login'])) {
        echo $_SESSION['login'];
        unset($_SESSION['login']);
    }
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
        $sql_teachers = "SELECT COUNT(*) AS total FROM teachers";
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

    <div class="row my-5">
        <h3 class="fs-4 mb-3">Recent Teachers</h3>
        <div class="col">
            <table class="table bg-white rounded shadow-sm table-hover">
                <thead>
                    <tr>
                        <th scope="col">S.N.</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_recent_teachers = "SELECT full_name, email, level FROM teachers ORDER BY created_at DESC LIMIT 5";
                    $res_recent_teachers = mysqli_query($conn, $sql_recent_teachers);
                    if (mysqli_num_rows($res_recent_teachers) > 0) {
                        $sn = 1;
                        while ($row = mysqli_fetch_assoc($res_recent_teachers)) {
                    ?>
                            <tr>
                                <th scope="row"><?php echo $sn++; ?></th>
                                <td><?php echo $row['full_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['level']; ?></td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4">No recent teachers found.</td>
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="../js/admin_scripts.js"></script>

<?php include('partial-admin/footer.php'); ?>
</body>

</html>