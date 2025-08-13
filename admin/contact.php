<?php include('partial-admin/navbar.php'); 
include('partial-admin/login-check.php'); ?>
<?php
$page_title = "Manage Contact Messages";

// Handle delete
if (isset($_GET['delete_id'])) {
    $cm_id = $_GET['delete_id'];
    $sql = "DELETE FROM contact_msgs WHERE cm_id=$cm_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['delete_message'] = "<div class='alert alert-success'>Message Deleted Successfully.</div>";
    } else {
        $_SESSION['delete_message'] = "<div class='alert alert-danger'>Failed to Delete Message.</div>";
    }
    header('location:' . SITEURL . 'admin/contact.php');
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
                        if (isset($_SESSION['delete_message'])) {
                            echo $_SESSION['delete_message'];
                            unset($_SESSION['delete_message']);
                        }
                        ?>
                        <div class="table-responsive">
                            <table class="table bg-white rounded shadow-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">S.N.</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Message</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM contact_msgs ORDER BY created_at DESC";
                                    $res = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($res) > 0) {
                                        $sn = 1;
                                        while ($row = mysqli_fetch_assoc($res)) {
                                    ?>
                                            <tr>
                                                <th scope="row"><?php echo $sn++; ?></th>
                                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                                <td><?php echo substr(htmlspecialchars($row['message']), 0, 50) . '...'; ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info view-message" data-bs-toggle="modal" data-bs-target="#messageDetailsModal" data-full_name="<?php echo htmlspecialchars($row['full_name']); ?>" data-email="<?php echo htmlspecialchars($row['email']); ?>" data-subject="<?php echo htmlspecialchars($row['subject']); ?>" data-message="<?php echo htmlspecialchars($row['message']); ?>" data-date="<?php echo date('F d, Y \a\t h:i A', strtotime($row['created_at'])); ?>">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <a href="<?php echo SITEURL; ?>admin/contact.php?delete_id=<?php echo $row['cm_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this message?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No messages found.</td>
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

    <div class="modal fade" id="messageDetailsModal" tabindex="-1" aria-labelledby="messageDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageDetailsModalLabel">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 id="modalMessageSubject"></h4>
                    <p class="text-muted">From: <span id="modalMessageFullName"></span> &lt;<span id="modalMessageEmail"></span>&gt;</p>
                    <p><strong>Date:</strong> <span id="modalMessageDate"></span></p>
                    <hr>
                    <p><strong>Message:</strong></p>
                    <p id="modalMessageContent" style="white-space: pre-wrap;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="../js/admin_scripts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var messageDetailsModal = document.getElementById('messageDetailsModal');
            if (messageDetailsModal) {
                messageDetailsModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var fullName = button.getAttribute('data-full_name');
                    var email = button.getAttribute('data-email');
                    var subject = button.getAttribute('data-subject');
                    var message = button.getAttribute('data-message');
                    var date = button.getAttribute('data-date');

                    var modalMessageSubject = document.getElementById('modalMessageSubject');
                    var modalMessageFullName = document.getElementById('modalMessageFullName');
                    var modalMessageEmail = document.getElementById('modalMessageEmail');
                    var modalMessageDate = document.getElementById('modalMessageDate');
                    var modalMessageContent = document.getElementById('modalMessageContent');

                    modalMessageSubject.textContent = subject;
                    modalMessageFullName.textContent = fullName;
                    modalMessageEmail.textContent = email;
                    modalMessageDate.textContent = date;
                    modalMessageContent.textContent = message;
                });
            }
        });
    </script>
    <?php
    include('partial-admin/footer.php');
    ?>
</body>
</html>