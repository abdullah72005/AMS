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
                $alumni->updateMentorStatus(false);
                $successMsg = "You are no longer serving as a mentor!";
            } else {
                // Become a mentor
                $alumni->serveAsMentor();
                $successMsg = "You are now serving as a mentor!";
            }
        } elseif (isset($_POST['updateDescription'])) {
            $mentorship = new Mentorship($alumni->getID());
            $mentorship->setDescription($_POST['description']);
            $successMsg = "Description updated successfully!";
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

<!-- Custom CSS for enhanced UI -->
<style>
    .mentor-card {
        transition: all 0.3s ease;
        border-radius: 8px; 
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        border: none;
    }
    
    .mentor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    
    .card-header {
        border-bottom: none;
        padding: 1.2rem 1.5rem;
        font-weight: 600;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .btn {
        border-radius: 6px;
        padding: 0.8rem 1.2rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #4a6bff, #2541b2);
        border: none;
    }
    
    .btn-success {
        background: linear-gradient(45deg, #28a745, #1e7e34);
        border: none;
    }
    
    .btn-danger {
        background: linear-gradient(45deg, #dc3545, #c82333);
        border: none;
    }
    
    .btn-sm {
        padding: 0.5rem 0.8rem;
        font-size: 0.875rem;
    }
    
    .form-control {
        border-radius: 6px;
        padding: 0.8rem 1.2rem;
        border: 1px solid #e0e0e0;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(13,110,253,0.2);
        border-color: #86b7fe;
    }
    
    .alert {
        border-radius: 6px;
        padding: 1rem 1.5rem;
        margin-top: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .status-badge {
        padding: 0.35rem 0.8rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .status-active {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .status-inactive {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }
    
    .page-title {
        color: #212529;
        margin-bottom: 1.5rem;
        font-weight: 700;
    }
    
    .section-title {
        color: #343a40;
        margin-bottom: 0;
        font-weight: 600;
        font-size: 1.25rem;
    }
    
    .student-card {
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .student-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .description-textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    .status-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .warning-text {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #dc3545;
        font-size: 0.875rem;
    }
    
    .students-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .no-students {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background-color: rgba(255, 193, 7, 0.1);
        color: #856404;
        border-radius: 6px;
        padding: 1.5rem;
    }
    
    .mentorship-header {
        background: linear-gradient(45deg, #4a6bff, #2541b2);
    }
</style>

<div class="container mt-5">
    <h2 class="page-title">Mentor Dashboard</h2>
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Mentor Status Section -->
            <div class="card mentor-card">
                <div class="card-header mentorship-header text-white">
                    <h3 class="section-title">Mentor Management</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <p class="status-label mb-1">Current Status</p>
                            <div class="d-flex align-items-center">
                                <span class="status-badge <?= $alumni->isMentor() ? 'status-active' : 'status-inactive' ?>">
                                    <?= $alumni->isMentor() ? 'Active Mentor' : 'Not Mentoring' ?>
                                </span>
                                
                                <?php if (!$alumni->isVerfied()): ?>
                                    <div class="warning-text ms-3">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>Account verification required</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($alumni->isVerfied()): ?>
                            <form method="post">
                                <button type="submit" name="toggleMentor" 
                                    class="btn <?= $alumni->isMentor() ? 'btn-danger' : 'btn-success' ?>">
                                    <i class="fas <?= $alumni->isMentor() ? 'fa-user-minus' : 'fa-user-plus' ?> me-2"></i>
                                    <?= $alumni->isMentor() ? 'Stop Mentoring' : 'Become Mentor' ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <?php if ($alumni->isMentor() && $mentorship): ?>
                        <div class="card bg-light border-0 mt-4">
                            <div class="card-body">
                                <h5 class="mb-3">Your Mentorship Description</h5>
                                <form method="post">
                                    <div class="mb-3">
                                        <textarea class="form-control description-textarea" name="description" 
                                            placeholder="Describe what students can expect from your mentorship..."><?= 
                                            htmlspecialchars($mentorship->getDescription()) ?></textarea>
                                        <div class="form-text mt-2">
                                            A good description helps students understand how you can help them.
                                        </div>
                                    </div>
                                    <button type="submit" name="updateDescription" 
                                        class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Update Description
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Current Students Section -->
            <?php if ($alumni->isMentor() && $mentorship): ?>
                <div class="card mentor-card">
                    <div class="card-header mentorship-header text-white">
                        <h3 class="section-title">Your Students</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($mentorship->getStudentsIds())): ?>
                            <div class="no-students">
                                <i class="fas fa-info-circle fa-lg"></i>
                                <span>No students are currently assigned to your mentorship</span>
                            </div>
                        <?php else: ?>
                            <div class="students-grid">
                                <?php foreach ($mentorship->getStudentsIds() as $studentId): 
                                    $username = htmlspecialchars(User::getUsernameFromId($studentId));
                                ?>
                                    <div class="student-card card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-user-graduate text-primary me-2"></i>
                                                    <span class="fw-medium"><?= $username ?></span>
                                                </div>
                                                <form method="post">
                                                    <input type="hidden" name="student_id" value="<?= $studentId ?>">
                                                    <button type="submit" name="removeStudent" 
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Remove <?= $username ?> from your mentorship?')">
                                                        <i class="fas fa-times me-1"></i> Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Messages -->
            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger mt-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($errorMsg) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($successMsg)): ?>
                <div class="alert alert-success mt-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($successMsg) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
