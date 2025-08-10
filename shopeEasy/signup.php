<?php
require_once 'includes/header.php';

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Process signup form
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    $errors = [];
    
    if(empty($username)) {
        $errors[] = "Please enter a username.";
    }
    
    if(empty($email)) {
        $errors[] = "Please enter an email.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    
    if(empty($password)) {
        $errors[] = "Please enter a password.";
    } elseif(strlen($password) < 8) {
        $errors[] = "Password must have at least 8 characters.";
    }
    
    if($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    
    // Check if username or email already exists
    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    if($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if(mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = "This username or email is already taken.";
        }
        
        mysqli_stmt_close($stmt);
    }
    
    // If no errors, proceed with registration
    if(empty($errors)) {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
            
            if(mysqli_stmt_execute($stmt)) {
                // Registration successful, redirect to login page
                header("location: login.php");
                exit();
            } else {
                $errors[] = "Something went wrong. Please try again later.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="text-center mb-4">Sign Up</h2>
                
                <?php if(!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="signupForm" onsubmit="return validateForm('signupForm')">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required onkeyup="checkPasswordStrength(this.value)">
                        <div class="progress mt-2" style="height: 5px;">
                            <div id="password-strength" class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small class="form-text text-muted">Password must be at least 8 characters long</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">I agree to the <a href="terms.php">Terms and Conditions</a></label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 