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

<!-- Link to external CSS file for modern aesthetic -->
<link rel="stylesheet" href="./../../static/stylesheets/register-view.css">

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
