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

<!-- Custom CSS for enhanced UI -->
<style>
    .login-container {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }
    
    .login-card {
        border-radius: 8px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        background-color: #fff;
        transition: all 0.3s ease;
    }
    
    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    
    .login-title {
        color: #212529;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.8rem;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        border-radius: 6px;
        padding: 0.8rem 1.2rem;
        border: 1px solid #e0e0e0;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
        border-color: #86b7fe;
    }
    
    .btn {
        border-radius: 6px;
        padding: 0.8rem 1.2rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #4a6bff, #2541b2);
        border: none;
    }
    
    .register-link {
        font-weight: 500;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.2s ease;
        padding: 0.6rem 1rem;
        border-radius: 6px;
        display: inline-block;
    }
    
    .register-link:hover {
        background-color: #f8f9fa;
        color: #343a40;
        transform: translateY(-2px);
    }
    
    .alert {
        border-radius: 6px;
        padding: 1rem 1.5rem;
        margin-top: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .login-form-group {
        margin-bottom: 1.5rem;
    }
    
    .login-divider {
        margin: 1.5rem 0;
        border-top: 1px solid #e9ecef;
    }
    
    .login-app-icon {
        font-size: 3rem;
        color: #4a6bff;
        margin-bottom: 1rem;
    }
</style>

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
