<?php

    $error = null;

    try {
        // Restrict access to faculty staff only
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'FacultyStaff') {
            throw new Exception('Access Denied: You do not have permission to access this page.');
        }

        // Fetch unverified alumni
        $unverifiedAlumni = Alumni::getUnverifiedAlumni();

        // Fetch user data for each unverified alumni
        $alumniData = [];
        foreach ($unverifiedAlumni as $userId) { // $userId is now a flat value
            $alumniData[] = Alumni::getAllUserData($userId);
        }

        // Handle POST request to verify alumni
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verifyAlumniId'])) {
            $alumniIdToVerify = $_POST['verifyAlumniId'];
            $user = $_SESSION['userObj'];
            $user->verifyAlumni($alumniIdToVerify);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
?>

<div class="container mt-5">
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php else: ?>
        <h1 class="mb-4">Verify Alumni</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Username</th>
                        <th>Major</th>
                        <th>Graduation Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumniData as $alumni): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alumni['username']); ?></td>
                            <td><?php echo htmlspecialchars($alumni['major'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($alumni['graduationDate'] ?? 'N/A'); ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="verifyAlumniId" value="<?php echo htmlspecialchars($alumni['user_id']); ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Verify</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>