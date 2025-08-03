<?php
include('partial-front/navbar.php');
include('partial-front/login-check.php');

// Check if a teacher ID is set in the URL
if(isset($_GET['id']))
{
    $teach_id = $_GET['id'];
    
    // Check if the ID from the URL matches the logged-in user's ID
    if($teach_id != $_SESSION['teach_id']) {
        $_SESSION['unauthorized'] = "<div class='error'>Unauthorized Access.</div>";
        header('location:' . SITEURL . 'user_profile.php');
        exit();
    }

    // SQL query to get the details of the selected teacher
    $sql = "SELECT * FROM teachers WHERE teach_id=$teach_id";
    $res = mysqli_query($conn, $sql);

    if(mysqli_num_rows($res) == 1)
    {
        $row = mysqli_fetch_assoc($res);
        $full_name = $row['full_name'];
        $username = $row['username'];
        $contact = $row['contact'];
        $email = $row['email'];
        $photo = $row['photo'];
    }
    else
    {
        $_SESSION['not_found'] = "<div class='error'>Teacher not found.</div>";
        header('location:' . SITEURL . 'user_profile.php');
        exit();
    }
}
else
{
    header('location:' . SITEURL . 'user_profile.php');
    exit();
}

// Process the form submission
if(isset($_POST['submit']))
{
    // Get the data from the form
    $new_full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $current_photo = $_POST['current_photo'];

    // Check if a new photo is selected
    if(isset($_FILES['new_photo']['name']))
    {
        $new_photo_name = $_FILES['new_photo']['name'];

        if($new_photo_name != "")
        {
            // Get the extension of the new photo
            $ext = end(explode('.', $new_photo_name));
            $new_photo_name = "Teacher_".rand(000, 999).".".$ext;
            
            $source_path = $_FILES['new_photo']['tmp_name'];
            $destination_path = "images/teachers/".$new_photo_name;

            $upload = move_uploaded_file($source_path, $destination_path);

            if($upload == false)
            {
                $_SESSION['upload'] = "<div class='error'>Failed to upload new photo.</div>";
                header('location:'.SITEURL.'update-profile.php?id='.$teach_id);
                exit();
            }

            // Remove the old photo if it exists
            if($current_photo != "")
            {
                $remove_path = "images/teachers/".$current_photo;
                if(file_exists($remove_path)) {
                    $remove = unlink($remove_path);
                    if($remove == false)
                    {
                        $_SESSION['remove-failed'] = "<div class='error'>Failed to remove old photo.</div>";
                        header('location:'.SITEURL.'update-profile.php?id='.$teach_id);
                        exit();
                    }
                }
            }
        }
        else
        {
            $new_photo_name = $current_photo;
        }
    }
    else
    {
        $new_photo_name = $current_photo;
    }

    // SQL query to update the teacher's details
    $sql2 = "UPDATE teachers SET
        full_name = '$new_full_name',
        username = '$new_username',
        contact = '$new_contact',
        email = '$new_email',
        photo = '$new_photo_name'
        WHERE teach_id=$teach_id
    ";

    $res2 = mysqli_query($conn, $sql2);

    if($res2 == true)
    {
        $_SESSION['update'] = "<div class='alert alert-success'>Profile Updated Successfully.</div>";
        // Update the session username as well
        $_SESSION['username'] = $new_username; 
        header('location:'.SITEURL.'user_profile.php');
        exit();
    }
    else
    {
        $_SESSION['update'] = "<div class='alert alert-danger'>Failed to Update Profile.</div>";
        header('location:'.SITEURL.'update-profile.php?id='.$teach_id);
        exit();
    }
}
?>

<div class="main-content">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card p-4 shadow-sm">
                    <h2 class="text-center mb-4 card-title">Update Profile</h2>
                    <?php
                    if(isset($_SESSION['upload'])) { echo $_SESSION['upload']; unset($_SESSION['upload']); }
                    if(isset($_SESSION['remove-failed'])) { echo $_SESSION['remove-failed']; unset($_SESSION['remove-failed']); }
                    ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 text-center">
                            <label class="form-label">Current Photo</label>
                            <?php 
                                if($photo != "")
                                {
                                    ?><img src="<?php echo SITEURL; ?>images/teachers/<?php echo $photo; ?>" alt="Current Photo" class="img-fluid rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;"><?php
                                }
                                else
                                {
                                    echo "<div class='text-danger'>Photo not added.</div>";
                                }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label for="new_photo" class="form-label">New Photo</label>
                            <input type="file" class="form-control" id="new_photo" name="new_photo">
                            <input type="hidden" name="current_photo" value="<?php echo $photo; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $full_name; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $contact; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-primary">Update Profile</button>
                            <a href="<?php echo SITEURL; ?>user_profile.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partial-front/footer.php'); ?>