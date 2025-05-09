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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <!-- Current Mentorship Section -->
            <?php if ($hasMentor): ?>
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">Your Current Mentor</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><?= htmlspecialchars($mentorshipInfo[0]['username']) ?></h5>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        Mentorship Description
                                    </div>
                                    <div class="card-body">
                                        <?= !empty($mentorshipInfo[0]['description']) ? 
                                            htmlspecialchars($mentorshipInfo[0]['description']) : 
                                            'No description provided' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Search Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">Find Mentors</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="major" 
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
                                        Search
                                    </button>
                                    <button type="button" onclick="location.href='?clear'" 
                                        class="btn btn-secondary">
                                        Show All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Mentors List -->
            <?php if (!empty($mentors)): ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Available Mentors</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                            <td><?= htmlspecialchars($mentor['username']) ?></td>
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
                                                            Select
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        Already in mentorship
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
                <div class="alert alert-info">
                    No mentors found matching your criteria
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