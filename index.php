<?php

// Include necessary files.
include('partial-front/navbar.php');
include('partial-front/login-check.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
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

        .small-time-header {
            font-size: 0.8rem;
            white-space: nowrap;
            padding-left: 0.3rem;
            padding-right: 0.3rem;
        }

        .leisure-table td,
        .leisure-table th {
            padding: 0.4rem;
            vertical-align: middle;
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

        <div class="container mt-4">
            <h2 class="text-center mb-4" id="teachersHeading">Our Teachers</h2>

            <div class="row" id="teachersList">
                <?php
                // SQL query to fetch all teachers from the database for the initial page load
                $sql = "SELECT * FROM teachers WHERE role = 'teacher'";
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
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <img src="<?php echo SITEURL; ?>images/teachers/<?php echo $teacher_photo; ?>" class="img-fluid rounded-circle mb-3" alt="<?php echo $teacher_name; ?>" style="width: 150px; height: 150px; object-fit: cover;">
                                    <b>
                                        <h5 class="card-title teacher-name-click" style="cursor: pointer;"><?php echo $teacher_name; ?></h5>
                                        <p class="card-text"><strong>Level:</strong> <?php echo $teacher_level; ?></p>
                                        <p class="card-text"><strong>Contact:</strong> <?php echo $teacher_contact; ?></p>
                                    </b>
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
        </div>

        <div class="container mt-4">
            <h2 class="text-center mb-4" id="substituteHeading">Absent Teachers</h2>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Absent Teacher Name</th>
                                    <th scope="col" class="text-center">Mobile Number</th>
                                    <th scope="col" class="text-center">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                // Get the current date in the correct format for your database (e.g., 'YYYY-MM-DD')
                                $current_date = date('Y-m-d');

                                // SQL query to fetch the full names of teachers who are marked as 'Absent' for the current date
                                // We use a JOIN to link the teachers and attendance tables on the teacher_id
                                // and filter by attendance status and date.
                                $sql_absent_teachers = "
                                SELECT t.full_name, t.contact, t.email
                                FROM teachers AS t
                                JOIN attendance AS a ON teacher_id = a.teacher_id
                                WHERE a.status = 'Absent' AND t.role='teacher' AND a.date = '$current_date'
                                GROUP BY t.full_name
                                AND t.contact
                                AND t.email
                                ORDER BY t.full_name ASC
                            ";

                                $result_absent_teachers = mysqli_query($conn, $sql_absent_teachers);

                                if ($result_absent_teachers) {
                                    if (mysqli_num_rows($result_absent_teachers) > 0) {
                                        while ($row = mysqli_fetch_assoc($result_absent_teachers)) {
                                            $teacher_name = htmlspecialchars($row['full_name']);
                                            echo "<tr>";
                                            echo "<td class='text-center'><b>$teacher_name</b></td>";
                                            echo "<td class='text-center'><b>" . htmlspecialchars($row['contact']) . "<b></td>";
                                            echo "<td class='text-center'><b>" . htmlspecialchars($row['email']) . "<b></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr>";
                                        echo "<td class='text-center'>No teachers are absent today.</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    // Handle query error gracefully
                                    echo "<tr>";
                                    echo "<td class='text-center'>Error fetching data: " . mysqli_error($conn) . "</td>";
                                    echo "</tr>";
                                }

                                // Close the database connection
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted mt-3 text-center">
                        This table lists the teachers who are absent today.
                    </p>
                </div>
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
                    <b>
                        <p><strong>Level:</strong> <span id="modalTeacherLevel"></span></p>
                        <p><strong>Contact:</strong> <span id="modalTeacherContact"></span></p>
                        <p><strong>Email:</strong> <span id="modalTeacherEmail"></span></p>
                        <p><strong>PAN:</strong> <span id="modalTeacherPan"></span></p>
                    </b>
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