<?php
require_once 'includes/header.php';

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } elseif (strlen($name) > 100) {
        $error_message = "Name must be less than 100 characters.";
    } elseif (strlen($subject) > 200) {
        $error_message = "Subject must be less than 200 characters.";
    } else {
        try {
            // Prepare the SQL statement
            $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success_message = "Thank you for your message. We will get back to you soon!";
                    // Clear form data
                    $name = $email = $subject = $message = '';
                } else {
                    $error_message = "Sorry, there was an error sending your message. Please try again later.";
                }
                
                mysqli_stmt_close($stmt);
            } else {
                $error_message = "Database error. Please try again later.";
            }
        } catch (Exception $e) {
            $error_message = "An error occurred. Please try again later.";
        }
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h2 class="card-title mb-4">Contact Us</h2>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="contactForm" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required maxlength="100">
                            <div class="invalid-feedback">Please enter your name.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>" required maxlength="200">
                            <div class="invalid-feedback">Please enter a subject.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                            <div class="invalid-feedback">Please enter your message.</div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">Get in Touch</h3>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-map-marker-alt text-primary me-2"></i> Address</h5>
                        <p>ShaAlam Market <br>Walled City<br>PAKISTAN</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-phone text-primary me-2"></i> Phone</h5>
                        <p>+92 03265307342</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-envelope text-primary me-2"></i> Email</h5>
                        <p>qr255591@gmail.com</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-clock text-primary me-2"></i> Business Hours</h5>
                        <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                        Saturday: 10:00 AM - 4:00 PM<br>
                        Sunday: Closed</p>
                    </div>
                    
                    <div class="social-links">
                        <h5 class="mb-3">Follow Us</h5>
                        <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-primary"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Map -->
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title mb-3">Location</h5>
                    <div class="ratio ratio-4x3">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d435518.6817857847!2d73.847554!3d31.472023!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39190483e58107d9%3A0xc23abe6ccc7e2462!2sLahore%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1645564750986!5m2!1sen!2s" 
                                style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

<?php require_once 'includes/footer.php'; ?> 