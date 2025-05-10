<?php
$errorMsg = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $username = $_POST['username'];
        $pass = $_POST['pass'];
        $role = $_POST['role'];
        if ($role == "Alumni") {
            $user = new Alumni($username);
        } elseif ($role == "Student") {
            $user = new Student($username);
        } else {
            throw new Exception("Invalid role selected.");
            exit();
        }
        
        $user->register_user($pass, $role);
        header("Location: index.php");
        exit;
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}
?>

<!-- Easter Egg: Custom CSS for modern aesthetic -->
<style>
/* Modern styling with shadows and hover effects */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    padding: 2rem;
    background-color: #fff;
}

.card:hover {
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
    transform: translateY(-5px);
}

h2 {
    color: #333;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.form-control, .form-select {
    border-radius: 6px;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
}

.form-label {
    font-weight: 500;
    color: #444;
    margin-bottom: 0.5rem;
}

.btn-primary {
    background-color: #4e73df;
    border: none;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s;
    box-shadow: 0 4px 6px rgba(78, 115, 223, 0.15);
}

.btn-primary:hover {
    background-color: #2e59d9;
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(78, 115, 223, 0.25);
}

.alert {
    border-radius: 6px;
    padding: 1rem;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.alert-danger {
    background-color: #fff5f5;
    color: #e53e3e;
    border-left: 4px solid #e53e3e;
}

.login-link {
    color: #718096;
    transition: all 0.2s;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
}

.login-link:hover {
    color: #4e73df;
    background-color: rgba(78, 115, 223, 0.05);
}

/* Easter egg: Subtle animation on form submission */
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(78, 115, 223, 0); }
    100% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0); }
}

.btn-primary:active {
    animation: pulse 0.8s;
}
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <h2 class="text-center">Register</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input
                            type="text"
                            class="form-control"
                            id="username"
                            name="username"
                            required
                        >
                    </div>
                    
                    <div class="mb-4">
                        <label for="pass" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="pass"
                            name="pass"
                            required
                        >
                    </div>
                    
                    <div class="mb-4">
                        <label for="role" class="form-label">Role</label>
                        <select
                            class="form-select"
                            id="role"
                            name="role"
                            required
                        >
                            <option value="">Select a role</option>
                            <option value="Alumni">Alumni</option>
                            <option value="Student">Student</option>
                        </select>
                    </div>
                    
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
                
                <?php if (!empty($errorMsg)): ?>
                    <div class="alert alert-danger mt-3">
                        <?php echo htmlspecialchars($errorMsg); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Button to redirect to the login page -->
                <div class="text-center">
                    <a href="login.php" class="login-link">Already have an account? Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>
