<?php
include('partial-front/navbar.php');
include('partial-front/login-check.php');
?>

<div class="main-content">
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
        <h2 class="text-center mb-4" id="teachersHeading">Our Dedicated Teachers</h2>

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
                document.getElementById('modalTeacherPhoto').src = '<?php echo SITEURL; ?>images/teachers/' + teacherPhoto;
                
                modal.show();
            }
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const teachersHeading = document.getElementById('teachersHeading');
        const teacherItems = document.querySelectorAll('.teacher-search-item');
        
        const filterTeachers = () => {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let foundTeachers = false;

            teacherItems.forEach(item => {
                const teacherName = item.getAttribute('data-name').toLowerCase();
                // Check if the teacher name starts with the search term
                if (teacherName.startsWith(searchTerm)) {
                    item.style.display = 'block';
                    foundTeachers = true;
                } else {
                    item.style.display = 'none';
                }
            });

            if (searchTerm === '') {
                teachersHeading.textContent = 'Our Dedicated Teachers';
            } else {
                teachersHeading.textContent = foundTeachers ? `Search Results for "${searchInput.value}"` : `No teachers found for "${searchInput.value}"`;
            }
        };
        
        // This is the new line that listens for every keystroke
        searchInput.addEventListener('input', filterTeachers);

        // Keep the click and keyup listeners for a more complete experience
        // The click handler is redundant now, but harmless.
        document.getElementById('searchButton').addEventListener('click', filterTeachers);
        searchInput.addEventListener('keyup', (event) => {
            if (event.key === 'Enter') {
                filterTeachers();
            }
        });
    });
</script>

<?php include('partial-front/footer.php'); ?>