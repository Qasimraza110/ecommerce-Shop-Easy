<?php
require_once 'includes/header.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Process profile update
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // Validate username
    if(empty($username)) {
        $errors[] = "Please enter a username.";
    }
    
    // Validate email
    if(empty($email)) {
        $errors[] = "Please enter an email.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    
    // Check if username or email is already taken
    if($username != $user['username'] || $email != $user['email']) {
        $sql = "SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if(mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = "This username or email is already taken.";
        }
    }
    
    // If changing password
    if(!empty($current_password)) {
        if(!password_verify($current_password, $user['password'])) {
            $errors[] = "Current password is incorrect.";
        }
        
        if(empty($new_password)) {
            $errors[] = "Please enter a new password.";
        } elseif(strlen($new_password) < 8) {
            $errors[] = "New password must have at least 8 characters.";
        }
        
        if($new_password != $confirm_password) {
            $errors[] = "New passwords do not match.";
        }
    }
    
    // If no errors, update profile
    if(empty($errors)) {
        $sql = "UPDATE users SET username = ?, email = ?";
        $params = [$username, $email];
        $types = "ss";
        
        if(!empty($new_password)) {
            $sql .= ", password = ?";
            $params[] = password_hash($new_password, PASSWORD_DEFAULT);
            $types .= "s";
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $_SESSION['user_id'];
        $types .= "i";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        if(mysqli_stmt_execute($stmt)) {
            $success_message = "Profile updated successfully.";
            $_SESSION['username'] = $username;
            
            // Refresh user data
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
            mysqli_stmt_execute($stmt);
            $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        } else {
            $errors[] = "Something went wrong. Please try again later.";
        }
    }
}

// Get order history
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$orders = mysqli_stmt_get_result($stmt);
?>

<div class="container">
    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">Profile Information</h3>
                    
                    <?php if(isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="profileForm" onsubmit="return validateForm('profileForm')">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                        </div>
                        
                        <hr>
                        <h4>Change Password</h4>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" onkeyup="checkPasswordStrength(this.value)">
                            <div class="progress mt-2" style="height: 5px;">
                                <div id="password-strength" class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Order History -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-4">Order History</h3>
                    
                    <?php if(mysqli_num_rows($orders) > 0): ?>
                        <?php while($order = mysqli_fetch_assoc($orders)): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0">Order #<?php echo $order['id']; ?></h5>
                                        <span class="badge bg-<?php echo $order['status'] == 'completed' ? 'success' : 'primary'; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </div>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Placed on <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                                        </small>
                                    </p>
                                    <p class="card-text">
                                        Total: $<?php echo number_format($order['total_amount'], 2); ?>
                                    </p>
                                    <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            You haven't placed any orders yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 