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

<!-- Custom CSS for enhanced UI -->
<style>
    .mentor-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
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
    
    .search-box {
        border-radius: 6px;
        padding: 0.8rem 1.2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0;
        transition: all 0.2s ease;
    }
    
    .search-box:focus {
        box-shadow: 0 0 0 3px rgba(13,110,253,0.2);
        border-color: #86b7fe;
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
    
    .btn-secondary {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
    }
    
    .btn-success {
        background: linear-gradient(45deg, #28a745, #1e7e34);
        border: none;
    }
    
    .mentor-table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .mentor-table th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid #dee2e6;
    }
    
    .mentor-table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }
    
    .mentor-table tr:hover {
        background-color: #f8f9fa;
    }
    
    .current-mentor-info {
        display: flex;
        align-items: center;
    }
    
    .current-mentor-name {
        margin-bottom: 0.5rem;
        color: #212529;
        font-size: 1.25rem;
    }
    
    .mentor-description-card {
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 1rem;
        height: 100%;
    }
    
    .alert {
        border-radius: 6px;
        padding: 1rem 1.5rem;
        margin-top: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .page-title {
        color: #212529;
        margin-bottom: 2rem;
        font-weight: 700;
    }
    
    .section-title {
        color: #343a40;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .no-mentors {
        text-align: center;
        padding: 2rem;
        background-color: #f8f9fa;
        border-radius: 6px;
        color: #6c757d;
    }
</style>

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
