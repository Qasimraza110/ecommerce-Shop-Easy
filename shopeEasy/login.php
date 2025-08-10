<?php
require_once 'includes/header.php';

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Process login form
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if(mysqli_stmt_fetch($stmt)) {
                    if(password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        session_start();
                        
                        // Store data in session variables
                        $_SESSION["user_id"] = $id;
                        $_SESSION["username"] = $username;
                        
                        // Redirect user to home page
                        header("location: index.php");
                        exit();
                    } else {
                        $login_err = "Invalid email or password.";
                    }
                }
            } else {
                $login_err = "Invalid email or password.";
            }
        } else {
            $login_err = "Oops! Something went wrong. Please try again later.";
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="text-center mb-4">Login</h2>
                
                <?php if(isset($login_err)): ?>
                    <div class="alert alert-danger"><?php echo $login_err; ?></div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="loginForm" onsubmit="return validateForm('loginForm')">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="signup.php">Sign up now</a></p>
                    <p><a href="forgot-password.php">Forgot your password?</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 