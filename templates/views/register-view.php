<?php 
require_once("../src/User.php");
require_once("../src/Admin.php");
require_once("../src/Alumni.php");
require_once("../src/FacultyStaff.php");
require_once("../src/Student.php");

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
        } elseif ($role == "FacultyStaff") {
            $user = new FacultyStaff($username);
        } elseif ($role == "Admin") {
            $user = new Admin($username);
        } else {
            throw new Exception("Invalid role selected.");
        }

        $user->register_user($pass, $role);
        header("Location: index.php");
        exit;
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Register</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="username" 
                        name="username" 
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="pass" class="form-label">Password</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="pass" 
                        name="pass" 
                        required
                    >
                </div>

                <div class="mb-3">
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
                        <option value="FacultyStaff">Faculty Staff</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>

            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger mt-3">
                    <?php echo htmlspecialchars($errorMsg); ?>
                </div>
            <?php endif; ?>

            <!-- Button to redirect to the login page -->
            <div class="mt-3 text-center">
                <a href="login.php" class="btn" style="color: #777">Already have an account? Login here</a>
            </div>
        </div>
    </div>
</div>