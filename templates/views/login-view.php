<?php 
require_once("../src/User.php");
require_once("../src/Admin.php");
require_once("../src/Alumni.php");
require_once("../src/FacultyStaff.php");
require_once("../src/Student.php");

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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Login</h2>
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

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>

            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo htmlspecialchars($errorMsg); ?>
                </div>
            <?php endif; ?>

            <!-- Button to redirect to the register page -->
            <div class="mt-3 text-center">
                <a href="register.php" class="btn" style="color:#777">Don't have an account? Register here</a>
            </div>
        </div>
    </div>
</div>
