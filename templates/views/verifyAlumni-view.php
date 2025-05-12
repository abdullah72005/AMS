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

<link rel="stylesheet" href="./../../static/stylesheets/verifyAlumni-view.css">

<div class="container mt-5">
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Verify Alumni</h1>
                <span class="badge badge-light"><?php echo count($alumniData); ?> Pending</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Major</th>
                                <th>Graduation Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($alumniData)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">No alumni pending verification</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($alumniData as $alumni): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-medium"><?php echo htmlspecialchars($alumni['username']); ?></div>
                                        </td>
                                        <td><?php echo htmlspecialchars($alumni['major'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($alumni['graduationDate'] ?? 'N/A'); ?></td>
                                        <td class="text-center">
                                            <form method="POST" class="d-inline position-relative">
                                                <input type="hidden" name="verifyAlumniId" value="<?php echo htmlspecialchars($alumni['user_id']); ?>">
                                                <button type="submit" class="btn btn-verify">
                                                    <i class="fas fa-check me-1"></i> Verify
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
