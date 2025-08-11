<?php

// Include necessary files.
include('partial-front/navbar.php');
include('partial-front/login-check.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .carousel-item img {
            max-height: 400px;
            object-fit: cover;
        }

        .main-content {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .teacher-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e0e0e0;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .teacher-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="main-content">
        <?php
        if (isset($_SESSION['login'])) {
            echo $_SESSION['login'];
            unset($_SESSION['login']);
        }
        ?>
        <div class="container mt-4">
            <h2 class="text-center mb-4">Search Teachers From Here</h2>
            <div class="row justify-content-center mb-5">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search teachers by name..." aria-label="Search" id="searchInput">
                        <button class="btn btn-primary" type="button" id="searchButton">Search</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container my-5">
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner rounded shadow-sm">
                    <div class="carousel-item active">
                        <img src="images/school.jpg" class="d-block w-100" alt="Dedicated Faculty">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Our Dedicated Faculty</h5>
                            <p>Meet the teachers who make a difference in our students' lives.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/school.jpg" class="d-block w-100" alt="Leisure and Extracurriculars">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Leisure and Extracurriculars</h5>
                            <p>Discover our wide range of activities beyond the classroom.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/school.jpg" class="d-block w-100" alt="Community Events">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>School Community Events</h5>
                            <p>Stay updated with our latest school events and news.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <div class="container mt-4">
            <h2 class="text-center mb-4" id="teachersHeading">Our Teachers</h2>

            <div class="row" id="teachersList">
                <?php
                // SQL query to fetch only the first 4 teachers with the role 'teacher'
                $sql = "SELECT * FROM teachers WHERE role = 'teacher' LIMIT 4";
                $res = mysqli_query($conn, $sql);

                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $teacher_id = $row['teach_id'];
                        $teacher_name = $row['full_name'];
                        $teacher_photo = $row['photo'];
                        $teacher_level = $row['level'];
                        $teacher_contact = $row['contact'];
                        $teacher_email = $row['email'];
                        $teacher_pan = $row['pan'];
                ?>
                        <div class="col-md-6 col-lg-4 mb-4 teacher-search-item" data-id="<?php echo $teacher_id; ?>" data-name="<?php echo $teacher_name; ?>" data-level="<?php echo $teacher_level; ?>" data-contact="<?php echo $teacher_contact; ?>" data-email="<?php echo $teacher_email; ?>" data-pan="<?php echo $teacher_pan; ?>" data-photo="<?php echo $teacher_photo; ?>">
                            <div class="card h-100 text-center teacher-card">
                                <div class="card-body">
                                    <?php
                                    // Check if photo is set and exists, otherwise use a placeholder
                                    if ($teacher_photo && file_exists('images/teachers/' . $teacher_photo)) {
                                        $image_path = SITEURL . 'images/teachers/' . $teacher_photo;
                                    } else {
                                        $image_path = 'https://via.placeholder.com/150';
                                    }
                                    ?>
                                    <img src="<?php echo $image_path; ?>" class="img-fluid rounded-circle mb-3" alt="<?php echo $teacher_name; ?>" style="width: 150px; height: 150px; object-fit: cover;">
                                    <h5 class="card-title teacher-name-click" style="cursor: pointer;"><?php echo $teacher_name; ?></h5>
                                    <p class="card-text"><strong>Level:</strong> <?php echo $teacher_level; ?></p>
                                    <p class="card-text"><strong>Contact:</strong> <?php echo $teacher_contact; ?></p>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<div class='text-center w-100'><div class='alert alert-info'>No teachers found.</div></div>";
                }
                ?>
            </div>
            <div class="text-center mt-3">
                <a href="teachers.php" class="btn btn-primary">Browse All Teachers</a>
            </div>
        </div>

        <div class="container mt-5">
            <h2 class="text-center mb-4" id="leisureRoutineHeading">Leisure Teachers</h2>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped leisure-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center"><b>S.N.</b></th>
                                    <th scope="col" class="text-center"><b>Image</b></th>
                                    <th scope="col" class="text-center"><b>Teacher Name</b></th>
                                    <th scope="col" class="text-center"><b>Contact Number</b></th>
                                    <th scope="col" class="text-center"><b>Email</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql_leisure = "SELECT t.full_name AS teacher_name, t.contact AS teacher_contact, t.email AS teacher_email, t.photo AS teacher_photo
                                                FROM leisure_routines lr
                                                LEFT JOIN teachers t ON lr.teacher_id = t.teach_id";
                                $result_leisure = mysqli_query($conn, $sql_leisure);
                                $sn = 1; // Initialize a counter for the serial number

                                if ($result_leisure && mysqli_num_rows($result_leisure) > 0) {
                                    while ($row = mysqli_fetch_assoc($result_leisure)) {
                                        $teacher_name = $row['teacher_name'] ?? 'N/A';
                                        $teacher_contact = $row['teacher_contact'] ?? 'N/A';
                                        $teacher_email = $row['teacher_email'] ?? 'N/A';
                                        $teacher_photo = $row['teacher_photo'] ?? 'N/A';
                                        echo '<tr>';
                                        echo '<td class="text-center"><b>' . $sn++ . '</b></td>'; // Display and increment S.N.
                                        echo '<td class="text-center">';
                                        // Check if photo is set and exists, otherwise use a placeholder
                                        if ($teacher_photo && file_exists('images/teachers/' . $teacher_photo)) {
                                            $image_path_leisure = SITEURL . 'images/teachers/' . $teacher_photo;
                                        } else {
                                            $image_path_leisure = 'https://via.placeholder.com/50';
                                        }
                                        echo '<img src="' . $image_path_leisure . '" alt="' . htmlspecialchars($teacher_name) . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">';
                                        echo '</td>';
                                        echo '<td class="text-center"><b>' . htmlspecialchars($teacher_name) . '</b></td>';
                                        echo '<td class="text-center"><b>' . htmlspecialchars($teacher_contact) . '</b></td>';
                                        echo '<td class="text-center"><b>' . htmlspecialchars($teacher_email) . '</b></td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">No leisure teachers found.</td></tr>'; // Adjusted colspan
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="routine.php" class="btn btn-primary">Browse All Leisure Classes</a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Teacher Details Modal on name click
            const teachersList = document.getElementById('teachersList');
            const modal = new bootstrap.Modal(document.getElementById('teacherDetailsModal'));

            teachersList.addEventListener('click', function(e) {
                if (e.target.classList.contains('teacher-name-click')) {
                    const teacherCard = e.target.closest('.teacher-search-item');
                    const teacherName = teacherCard.getAttribute('data-name');
                    const teacherLevel = teacherCard.getAttribute('data-level');
                    const teacherContact = teacherCard.getAttribute('data-contact');
                    const teacherEmail = teacherCard.getAttribute('data-email');
                    const teacherPan = teacherCard.getAttribute('data-pan');
                    const teacherPhoto = teacherCard.getAttribute('data-photo');

                    document.getElementById('modalTeacherName').textContent = teacherName;
                    document.getElementById('modalTeacherLevel').textContent = teacherLevel;
                    document.getElementById('modalTeacherContact').textContent = teacherContact;
                    document.getElementById('modalTeacherEmail').textContent = teacherEmail;
                    document.getElementById('modalTeacherPan').textContent = teacherPan;

                    const modalTeacherPhoto = document.getElementById('modalTeacherPhoto');
                    if (teacherPhoto) {
                        modalTeacherPhoto.src = '<?php echo SITEURL; ?>images/teachers/' + teacherPhoto;
                    } else {
                        modalTeacherPhoto.src = 'https://via.placeholder.com/150';
                    }

                    modal.show();
                }
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const teachersHeading = document.getElementById('teachersHeading');
            const teachersListContainer = document.getElementById('teachersList');
            const teacherItems = document.querySelectorAll('.teacher-search-item');

            const filterTeachers = () => {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let foundTeachers = 0;

                teacherItems.forEach(item => {
                    const teacherName = item.getAttribute('data-name').toLowerCase();
                    if (teacherName.startsWith(searchTerm)) {
                        item.style.display = 'block';
                        foundTeachers++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (searchTerm === '') {
                    teachersHeading.textContent = 'Our Teachers';
                } else {
                    teachersHeading.textContent = foundTeachers > 0 ? `Search Results for "${searchInput.value}"` : `No teachers found for "${searchInput.value}"`;
                }
            };

            searchInput.addEventListener('input', filterTeachers);
            document.getElementById('searchButton').addEventListener('click', filterTeachers);
            searchInput.addEventListener('keyup', (event) => {
                if (event.key === 'Enter') {
                    filterTeachers();
                }
            });
        });
    </script>

    <?php include('partial-front/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>

</html>