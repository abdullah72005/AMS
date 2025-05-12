<?php


$errorMsg = "";
$successMsg = "";
$userData = null;

$admin = $_SESSION['userObj'];
// Check if user is logged in and is an Admin
if (!isset($admin) || !($admin instanceof Admin)) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $admin = $_SESSION['userObj'];
        if (isset($_POST['addUser'])) {
            
            if (!($admin instanceof Admin))
            throw new Exception($admin . " is not an admin");

            $admin->createUser($_POST['username'], $_POST['password'], $_POST['role']);
            $successMsg = "User added successfully!";
        } 
        elseif (isset($_POST['searchUser'])) 
        {
            $userData = $admin->getUser($_POST['searchUsername']);
        } 
        elseif (isset($_POST['updateUser'])) 
        {
            $admin->updateUserData(
                $_POST['user_id'],
                !empty($_POST['newUsername']) ? $_POST['newUsername'] : null,
                !empty($_POST['newPassword']) ? $_POST['newPassword'] : null,
                !empty($_POST['newRole']) ? $_POST['newRole'] : null
            );
            $successMsg = "User updated successfully!";
        } 
        elseif (isset($_POST['deleteUser'])) 
        {
            $admin->deleteUser($_POST['user_id']);
            $successMsg = "User deleted successfully!";
        }
        elseif (isset($_POST['showAllUsers'])) 
        {
            $allUsers = $admin->getAllUsers();
        } 
    } 
    catch (Exception $e){
        $errorMsg = $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../../static/stylesheets/AdminPanel-view.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Add User Toggle Button -->
            <div class="d-grid gap-2 mb-4">
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addUserForm">
                    Add New User
                </button>
            </div>

            <!-- Collapsible Add User Form -->
            <div class="collapse mb-4" id="addUserForm">
                <div class="card">
                    <div class="card-body">
                        <form method="post">
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
                                <label for="password" class="form-label">Password</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="Admin">Admin</option>
                                    <option value="Student">Student</option>
                                    <option value="Alumni">Alumni</option>
                                    <option value="FacultyStaff">Faculty/Staff</option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="addUser" class="btn btn-success">Create User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Compact Search Section -->
            <div class="card">
                <div class="card-body">
                    <form method="post" class="mb-4">
                        <div class="input-group">
                            <input 
                                type="text" 
                                class="form-control" 
                                placeholder="Search username..." 
                                name="searchUsername" 
                                required
                                style="max-width: 250px;"
                            >
                            <button type="submit" name="searchUser" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                    <?php if (!empty($allUsers)): ?>
                        <div class="mt-4">
                            <h5>All Users (<?= count($allUsers) ?>)</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allUsers as $user): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($user['user_id']) ?></td>
                                                <td><?= htmlspecialchars($user['username']) ?></td>
                                                <td><?= htmlspecialchars($user['role']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($userData): ?>
                        <!-- Edit User Form -->
                        <div class="border p-3 rounded">
                            <h5 class="mb-3">Editing: <?= htmlspecialchars($userData['username']) ?></h5>
                            <form method="post">
                                <input type="hidden" name="user_id" value="<?= $userData['user_id'] ?>">
                                
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Username</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="newUsername"
                                            placeholder="Current: <?= htmlspecialchars($userData['username']) ?>"
                                        >
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Role</label>
                                        <select class="form-select" name="newRole">
                                            <option value="">Current: <?= htmlspecialchars($userData['role']) ?></option>
                                            <option value="Admin">Admin</option>
                                            <option value="Student">Student</option>
                                            <option value="Alumni">Alumni</option>
                                            <option value="FacultyStaff">Faculty Staff</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        name="newPassword"
                                        placeholder="Leave blank to keep current"
                                    >
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <button 
                                        type="submit" 
                                        name="deleteUser" 
                                        class="btn btn-link text-danger"
                                        onclick="return confirm('Delete this user permanently?')"
                                    >
                                        Delete Account
                                    </button>
                                    <button type="submit" name="updateUser" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                    <form method="post" class="mt-4">
                        <button type="submit" name="showAllUsers" class="btn btn-outline-info">
                            Show All Users
                        </button>
                    </form>
                </div>
            </div>

            <!-- Messages -->
            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger mt-4" role="alert">
                    <?= htmlspecialchars($errorMsg) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($successMsg)): ?>
                <div class="alert alert-success mt-4" role="alert">
                    <?= htmlspecialchars($successMsg) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Emulating Bootstrap's collapse functionality
    const toggleButton = document.querySelector('[data-bs-toggle="collapse"]');
    const targetId = toggleButton.getAttribute('data-bs-target');
    const targetElement = document.querySelector(targetId);
    
    toggleButton.addEventListener('click', function() {
        if (targetElement.classList.contains('collapse')) {
            targetElement.style.display = 'block';
            targetElement.classList.remove('collapse');
        } else {
            targetElement.style.display = 'none';
            targetElement.classList.add('collapse');
        }
    });
});
</script>
</body>
</html>