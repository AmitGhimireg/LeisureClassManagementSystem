<?php include('partial-front/navbar.php');?>

<div class="main-content">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card p-4 shadow-sm">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-key me-2"></i> Forgot Password
                    </h2>
                    <p class="text-center text-muted mb-4">
                        Enter your email address below to receive a password reset link.
                    </p>
                    <form action="php/functions.php" method="POST">
                        <input type="hidden" name="action" value="forgot_password">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Send Reset Link</button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        Remembered your password? <a href="login.php" class="text-primary text-decoration-none">Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // You might add client-side validation here if needed
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            const email = document.getElementById('email').value;
            if (!email) {
                alert('Please enter your email address.');
                event.preventDefault();
            }
            // Basic email format validation (more robust validation should be done server-side)
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                event.preventDefault();
            }
        });
    });
</script>

<?php include('partial-front/footer.php'); ?>