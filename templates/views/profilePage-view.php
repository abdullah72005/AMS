<?php 

// Initialize error message
$errorMsg = "";
$userData = null;

try {
    if (!isset($_GET['profileId']) || empty($_GET['profileId'])) {
        throw new Exception("Invalid access to profile page.");
    }
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && empty($errorMsg)) {
    try {
        // Role-based delegation
        switch (User::getRoleById($_GET['profileId'])) {
            case 'Alumni':
                $userData = Alumni::getAllUserData($_GET['profileId']);
                break;
            case 'Student':
                $userData = Student::getAllUserData($_GET['profileId']);
                break;
            case 'FacultyStaff':
                $userData = FacultyStaff::getAllUserData($_GET['profileId']);
                break;
            case 'Admin':
                $userData = Admin::getAllUserData($_GET['profileId']);
                break;
        }
        
        // Optional: normalize booleans
        if (isset($userData['mentor'])) {
            $userData['mentor'] = (bool)$userData['mentor'];
        }
        if (isset($userData['verified'])) {
            $userData['verified'] = (bool)$userData['verified'];
        }
      

        // Check if the viewer is the owner of the profile
        $isOwner = isset($_SESSION['username']) && $userData['username'] === $_SESSION['username'];
        if ($isOwner) {
            $userObj = $_SESSION['userObj'];
            $validMajors = User::$validMajors;
        }
    
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['updateProfile'])) {
    try {
        $userObj = $_SESSION['userObj']; // Get the logged-in user object
        if (!empty($_POST['username'])) {
            $userObj->setUsername(trim($_POST['username']));
            $_SESSION['userObj'] = $userObj; // Update the session variable
        }

        if (!empty($_POST['newPassword'])) {
            if (empty($_POST['oldPassword'])) {
                throw new Exception("You must enter your current password to set a new one.");
            }
        
            $userObj->setPassword(trim($_POST['newPassword']), trim($_POST['oldPassword']));
            $_SESSION['userObj'] = $userObj; // Update session
        }

        if (!empty($_POST['major']) && in_array($_POST['major'], User::$validMajors)) {
            $userObj->setMajor($_POST['major']);
            $_SESSION['userObj'] = $userObj; // Update the session variable
        }

        if (!empty($_POST['graduationDate'])) {
            $inputDate = new DateTime($_POST['graduationDate']);
            $today = new DateTime();
            if ($inputDate < $today) {
                $userObj->setGraduationDate($_POST['graduationDate']);
                $_SESSION['userObj'] = $userObj; // Update the session variable

            } else {
                throw new Exception("Graduation date must be in the past.");
            }
        }

        header("Location: profilepage.php?profileId=" . $_GET['profileId']);
        exit();

    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}
?>

<?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php elseif (!empty($userData)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?php echo htmlspecialchars($userData['username']); ?>'s Profile</h4>
            </div>
            <div class="card-body">

                <!-- Static Profile Info -->
                <div class="mb-3"><strong>Role:</strong> <?php echo htmlspecialchars($userData['role']); ?></div>

                <?php if ($userData['role'] === 'Alumni'): ?>
                    <div class="mb-2"><strong>Mentor:</strong> <?php echo $userData['mentor'] ? 'Yes' : 'No'; ?></div>
                    <div class="mb-2"><strong>Verified:</strong> <?php echo $userData['verified'] ? 'Yes' : 'No'; ?></div>
                    <div class="mb-2"><strong>Graduation Date:</strong> 
                        <?php echo !empty($userData['graduationDate']) ? htmlspecialchars($userData['graduationDate']) : 'Not yet specified'; ?>
                    </div>
                    <div class="mb-2"><strong>Major:</strong> 
                        <?php echo !empty($userData['major']) ? htmlspecialchars($userData['major']) : 'Not yet specified'; ?>
                    </div>
                <?php elseif ($userData['role'] === 'Student'): ?>
                    <div class="mb-2"><strong>Major:</strong> 
                        <?php echo !empty($userData['major']) ? htmlspecialchars($userData['major']) : 'Not yet specified'; ?>
                    </div>
                <?php endif; ?>

                <!-- Editable Form for Current User -->
                <?php if (isset($isOwner) && $isOwner): ?>
                    <hr>
                    <h5>Edit Your Profile</h5>
                    <form method="post" class="row g-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">New Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Leave blank to keep current">
                        </div>
                        <div class="col-12">
                            <fieldset class="border rounded-3 p-3">
                                <legend class="float-none w-auto px-2">Change Password</legend>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="oldPassword" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="Required to change password">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="newPassword" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Leave blank to keep current">
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <?php if ($userData['role'] === 'Alumni' || $userData['role'] === 'Student'): ?>
                            <div class="col-md-6">
                                <label for="major" class="form-label">Major</label>
                                <select class="form-select" id="major" name="major">
                                    <option value="">Select a major</option>
                                    <?php foreach ($validMajors as $major): ?>
                                        <option value="<?php echo htmlspecialchars($major); ?>"><?php echo htmlspecialchars($major); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <?php if ($userData['role'] === 'Alumni'): ?>
                            <div class="col-md-6">
                                <label for="graduationDate" class="form-label">Graduation Date</label>
                                <input type="date" class="form-control" id="graduationDate" name="graduationDate" max="<?php echo date('Y-m-d'); ?>">
                            </div>
                        <?php endif; ?>

                        <div class="col-12">
                            <button type="submit" name="updateProfile" class="btn btn-success">Update Profile</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>