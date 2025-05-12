<?php
  $errorMsg = "";  // Initialize error message
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $username = $_POST['username'];
        $pass = $_POST['pass'];
        $role = User::getRole($username);  // Get the role of the user
        
        if ($role == "Alumni") {
            $user = new Alumni($username);
        } elseif ($role == "Student") {
            $user = new Student($username);
        } elseif ($role == "FacultyStaff") {
            $user = new FacultyStaff($username);
        } elseif ($role == "Admin") {
            $user = new Admin($username);
        } else {
            throw new Exception("User not found");
        }
        $user->login_user($pass);
        header("Location: index.php");
        exit;
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}
?>

<!-- Link to external CSS for enhanced UI -->
<link rel="stylesheet" href="./../../static/stylesheets/login-view.css">

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="login-card">
                <div class="text-center mb-4">
                    <i class="fas fa-graduation-cap login-app-icon"></i>
                    <h2 class="login-title">Welcome Back</h2>
                </div>
                
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="login-form-group">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control"
                                id="username"
                                name="username"
                                placeholder="Enter your username"
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="login-form-group">
                        <label for="pass" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input
                                type="password"
                                class="form-control"
                                id="pass"
                                name="pass"
                                placeholder="Enter your password"
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>
                </form>
                
                <?php if (!empty($errorMsg)): ?>
                    <div class="alert alert-danger mt-4" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($errorMsg); ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="login-divider"></div>
                
                <!-- Button to redirect to the register page -->
                <div class="text-center">
                    <a href="register.php" class="register-link">
                        <i class="fas fa-user-plus me-1"></i>
                        Don't have an account? Register here
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
