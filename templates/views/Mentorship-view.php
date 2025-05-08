<?php

$errorMsg = "";
$successMsg = "";
$mentorship = null;

// Check if user is logged in and is an Alumni
if (!isset($_SESSION['userObj']) || !($_SESSION['userObj'] instanceof Alumni)) {
    header("Location: index.php");
    exit();
}

$alumni = $_SESSION['userObj'];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (isset($_POST['toggleMentor'])) {
            if ($alumni->isMentor()) {
                // Stop being a mentor
                $alumni->updateMentorStatus(0);
                $successMsg = "You are no longer serving as a mentor!";
            } else {
                // Become a mentor
                $alumni->serveAsMentor();
                $successMsg = "You are now serving as a mentor!";
            }
        } elseif (isset($_POST['removeStudent'])) {
            $mentorship = new Mentorship($alumni->getID());
            $mentorship->removeStudent($_POST['student_id']);
            $successMsg = "Student removed successfully!";
        }
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}

// Load mentorship data if exists
try {
    if ($alumni->isMentor()) {
        $mentorship = new Mentorship($alumni->getID());
    }
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Mentor Status Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">Mentor Management</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">
                                Mentor Status: 
                                <span class="<?= $alumni->isMentor() ? 'text-success' : 'text-secondary' ?>">
                                    <?= $alumni->isMentor() ? 'Active' : 'Inactive' ?>
                                </span>
                            </h5>
                            <?php if (!$alumni->isVerfied()): ?>
                                <small class="text-danger">Account verification required to become a mentor</small>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($alumni->isVerfied()): ?>
                            <form method="post">
                                <button type="submit" name="toggleMentor" 
                                    class="btn <?= $alumni->isMentor() ? 'btn-danger' : 'btn-success' ?>">
                                    <?= $alumni->isMentor() ? 'Stop Mentoring' : 'Become Mentor' ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Current Students Section -->
            <?php if ($alumni->isMentor() && $mentorship): ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Your Students</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($mentorship->getStudentsIds())): ?>
                            <div class="alert alert-warning">
                                No students currently assigned to your mentorship
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Username</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($mentorship->getStudentsIds() as $studentId): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($studentId) ?></td>
                                                <td><?= htmlspecialchars(User::getUsernameFromId($studentId)) ?></td>
                                                <td>
                                                    <form method="post" class="d-inline">
                                                        <input type="hidden" name="student_id" value="<?= $studentId ?>">
                                                        <button type="submit" name="removeStudent" 
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Remove this student from your mentorship?')">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

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