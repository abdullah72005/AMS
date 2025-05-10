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

<!-- Easter Egg: Custom CSS for modern aesthetic -->
<style>
/* Modern styling with shadows and hover effects */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    overflow: hidden;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #f0f0f0;
    padding: 1.25rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

h1 {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1.75rem;
    margin-bottom: 1.5rem;
}

.table {
    margin-bottom: 0;
    border: none;
}

.table th {
    background-color: #4e73df;
    color: white;
    font-weight: 500;
    border: none;
    padding: 0.85rem 1rem;
    vertical-align: middle;
}

.table td {
    padding: 1rem;
    vertical-align: middle;
    border-color: #f0f0f0;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f9fafc;
}

.table tr {
    transition: all 0.2s;
}

.table tbody tr:hover {
    background-color: #eef2ff !important;
}

.btn-verify {
    background-color: #4e73df;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    color: white;
    font-weight: 500;
    transition: all 0.2s;
    box-shadow: 0 2px 5px rgba(78, 115, 223, 0.15);
}

.btn-verify:hover {
    background-color: #3a5fc8;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(78, 115, 223, 0.25);
}

.btn-verify:active {
    transform: translateY(0);
}

.alert {
    border: none;
    border-radius: 8px;
    padding: 1rem 1.25rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.alert-danger {
    background-color: #fff5f5;
    color: #e53e3e;
    border-left: 4px solid #e53e3e;
}

.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 30px;
    font-weight: 500;
    font-size: 0.75rem;
}

.badge-light {
    background-color: #f7fafc;
    color: #4a5568;
    border: 1px solid #edf2f7;
}

/* Easter egg: Add a subtle checkmark animation for verification */
@keyframes checkmark {
    0% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1.2); opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
}

.btn-verify:focus::before {
    content: "✓";
    position: absolute;
    top: -10px;
    right: -10px;
    background: #48bb78;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    animation: checkmark 0.5s ease-out forwards;
}
</style>

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
