<?php

$errorMsg = "";
$successMsg = "";
$mentors = [];
$searchTerm = "";
$majors = [];

// Check if user is logged in and is a Student
if (!isset($_SESSION['userObj']) || !($_SESSION['userObj'] instanceof Student)) {
    header("Location: index.php");
    exit();
}

$student = $_SESSION['userObj'];

// Get student's current mentorship info
$mentorshipInfo = $student->getCurrentMentorship();
$hasMentor = !empty($mentorshipInfo);

// Get unique majors from all mentors
try {
    $allMentors = $student->seeAllMentors();
    $majors = array_column($allMentors, 'major');
} catch (Exception $e) {
    $errorMsg = "Error loading mentor data: " . $e->getMessage();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (isset($_POST['search'])) {
            $searchTerm = $_POST['major'];
            $mentors = $student->search($searchTerm);
        } elseif (isset($_POST['selectMentor']) && !$hasMentor) {
            $mentorUsername = $_POST['mentor_id'];
            $student->selectMentor($mentorUsername);
            $successMsg = "Mentor selected successfully!";
            // Refresh mentorship info
            $mentorshipInfo = $student->getCurrentMentorship();
            $hasMentor = !empty($mentorshipInfo);
        }
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}

// Load all mentors if no search
if (empty($mentors) && !isset($_POST['search'])) {
    try {
        $mentors = $student->seeAllMentors();
    } catch (Exception $e) {
        $errorMsg = "Error loading mentors: " . $e->getMessage();
    }
}
?>

<link rel="stylesheet" href="./../../static/stylesheets/joinMentorship-view.css">

<div class="container mt-5">
    <h2 class="page-title">Mentorship Program</h2>
    
    <div class="row justify-content-center">
        <div class="col-md-10">

            <!-- Current Mentorship Section -->
            <?php if ($hasMentor): ?>
                <div class="card mentor-card mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">Your Current Mentor</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="current-mentor-info">
                                    <div>
                                        <h5 class="current-mentor-name"><?= htmlspecialchars($mentorshipInfo[0]['username']) ?></h5>
                                        <p class="text-muted mb-0">Active Mentorship</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mentor-description-card">
                                    <h6 class="mb-2">Mentorship Description</h6>
                                    <p class="mb-0">
                                        <?= !empty($mentorshipInfo[0]['description']) ? 
                                            htmlspecialchars($mentorshipInfo[0]['description']) : 
                                            'No description provided' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Search Section -->
            <div class="card mentor-card mb-4">
                <div class="card-header bg-white">
                    <h3 class="section-title mb-0">Find Mentors</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <input type="text" class="form-control search-box" name="major" 
                                    placeholder="Enter major (e.g., Computer Science)" 
                                    value="<?= htmlspecialchars($searchTerm) ?>"
                                    list="majorsList">
                                <datalist id="majorsList">
                                    <?php foreach ($majors as $major): ?>
                                        <option value="<?= htmlspecialchars($major) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="col-md-4">
                                <div class="d-grid gap-2 d-md-flex">
                                    <button type="submit" name="search" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i> Search
                                    </button>
                                    <button type="button" onclick="location.href='?clear'" 
                                        class="btn btn-secondary">
                                        <i class="fas fa-redo me-1"></i> Show All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Mentors List -->
            <?php if (!empty($mentors)): ?>
                <div class="card mentor-card">
                    <div class="card-header bg-white">
                        <h4 class="section-title mb-0">Available Mentors</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mentor-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Major</th>
                                        <th>Graduation Year</th>
                                        <th>Mentorship Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mentors as $mentor): ?>
                                        <tr>
                                            <td class="fw-medium"><?= htmlspecialchars($mentor['username']) ?></td>
                                            <td><?= htmlspecialchars($mentor['major']) ?></td>
                                            <td>
                                                <?php if (!empty($mentor['graduationDate'])): 
                                                    $date = DateTime::createFromFormat('Y-m-d', $mentor['graduationDate']);
                                                    echo $date ? $date->format('Y') : 'N/A';
                                                endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($mentor['description'] ?? 'No description') ?></td>
                                            <td>
                                                <?php if (!$hasMentor): ?>
                                                    <form method="post">
                                                        <input type="hidden" name="mentor_id" value="<?= htmlspecialchars($mentor['userId']) ?>">
                                                        <button type="submit" name="selectMentor" 
                                                            class="btn btn-success btn-sm"
                                                            onclick="return confirm('Select this mentor?')">
                                                            <i class="fas fa-user-plus me-1"></i> Select
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-lock me-1"></i> Already in mentorship
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-mentors">
                    <i class="fas fa-search mb-3" style="font-size: 2rem;"></i>
                    <p class="mb-0">No mentors found matching your criteria</p>
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

<!-- Make sure Font Awesome is included in your layout for the icons -->
<!-- If not already included in your project, add this line to your header -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
