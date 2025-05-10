<?php 
$successMsg = "";
$errorMsg = "";
$manager = $_SESSION['userObj'];

// Ensure user is logged in and is FacultyStaff
if (!isset($manager) || !($manager instanceof FacultyStaff)) {
    header("Location: index.php");
    exit();
}

// Get event ID from URL
if (!isset($_GET['eventId'])) {
    header("Location: events_list.php");
    exit();
}

$eventId = $_GET['eventId'];
$eventData = Event::getEventById($eventId); // Fetch existing event details

if (!$eventData) {
    $errorMsg = "Event not found.";
} else {
    $currentTitle = $eventData['name'];
    $currentDescription = $eventData['description'];
    $currentDateObj = new DateTime($eventData['date']);
    $currentDate = $currentDateObj->format('Y-m-d');
}

// Handle POST (form submission)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $hasChanges = false;

        // Title
        if (!empty($_POST['title']) && $_POST['title'] !== $currentTitle) {
            $manager->editEventName($eventId, $_POST['title']);
            $currentTitle = $_POST['title'];
            $hasChanges = true;
        }

        // Description
        if (!empty($_POST['description']) && $_POST['description'] !== $currentDescription) {
            $manager->editEventDescription($eventId, $_POST['description']);
            $currentDescription = $_POST['description'];
            $hasChanges = true;
        }

        // Date
        if (!empty($_POST['date'])) {
            $newDateObj = new DateTime($_POST['date']);
            if ($newDateObj->format('Y-m-d') !== $currentDate) {
                $manager->editEventDate($eventId, $newDateObj->format('Y-m-d'));
                $currentDate = $newDateObj->format('Y-m-d');
                $hasChanges = true;
            }
        }

        if ($hasChanges) {
            $successMsg = "Event updated successfully.";
            // Optionally redirect:
            header("Location: eventPage.php?eventId=" . urlencode($eventId));
            exit();
        } else {
            $errorMsg = "No changes detected.";
        }
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}
?>

<!-- Custom CSS for enhanced design -->
<style>
    .edit-form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .edit-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .edit-card:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        transform: translateY(-5px);
    }
    
    .card-header-custom {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .form-title {
        color: #333;
        font-weight: 600;
        margin: 0;
        position: relative;
        display: inline-block;
    }
    
    .form-title:after {
        content: '';
        position: absolute;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #007bff, #6610f2);
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .form-control-custom {
        border-radius: 8px;
        padding: 0.8rem 1rem;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
    }
    
    .form-control-custom:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
        transform: translateY(-1px);
    }
    
    .form-control-custom:hover:not(:focus) {
        border-color: #ced4da;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.04);
    }
    
    .form-label-custom {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .form-label-custom i {
        margin-right: 0.5rem;
        color: #6c757d;
    }
    
    .btn-save {
        border-radius: 8px;
        padding: 0.8rem 1.5rem;
        font-weight: 500;
        border: none;
        background: linear-gradient(135deg, #007bff, #0056b3);
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.25);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 123, 255, 0.35);
        background: linear-gradient(135deg, #0069d9, #004494);
    }
    
    .btn-save:active {
        transform: translateY(0);
        box-shadow: 0 2px 5px rgba(0, 123, 255, 0.2);
    }
    
    .btn-save i {
        margin-right: 0.5rem;
    }
    
    .btn-save:before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.2) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        transition: all 0.6s ease;
    }
    
    .btn-save:hover:before {
        left: 100%;
    }
    
    .alert-custom {
        border: none;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    
    .alert-success-custom {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }
    
    .alert-danger-custom {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
    }
    
    .alert-icon {
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .card-footer-custom {
        background: #f8f9fa;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }
    
    .floating-back {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 10;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        color: #6c757d;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        color: #007bff;
        transform: translateX(-3px);
    }
    
    .btn-back i {
        margin-right: 0.5rem;
    }
</style>

<div class="container py-5 edit-form-container">
    <div class="row">
        <div class="col-12">
            <div class="card edit-card">
                <div class="card-header-custom text-center position-relative">
                    <a href="eventTable.php" class="btn-back floating-back">
                        <i class="bi bi-arrow-left"></i> Back to Events
                    </a>
                    <h2 class="form-title">Edit Event</h2>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <?php if (!empty($errorMsg)): ?>
                        <div class="alert alert-custom alert-danger-custom">
                            <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                            <?php echo htmlspecialchars($errorMsg); ?>
                        </div>
                    <?php elseif (!empty($successMsg)): ?>
                        <div class="alert alert-custom alert-success-custom">
                            <i class="bi bi-check-circle-fill alert-icon"></i>
                            <?php echo htmlspecialchars($successMsg); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="title" class="form-label-custom">
                                <i class="bi bi-type"></i> Event Title
                            </label>
                            <input type="text" class="form-control form-control-custom" id="title" name="title"
                                   value="<?= htmlspecialchars($currentTitle) ?>" placeholder="Enter event title">
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label-custom">
                                <i class="bi bi-card-text"></i> Event Description
                            </label>
                            <textarea class="form-control form-control-custom" id="description" name="description" 
                                      rows="4" placeholder="Enter event description"><?= htmlspecialchars($currentDescription) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="date" class="form-label-custom">
                                <i class="bi bi-calendar3"></i> Event Date
                            </label>
                            <input type="date" class="form-control form-control-custom" id="date" name="date"
                                   value="<?= htmlspecialchars($currentDate) ?>">
                        </div>

                        <div class="card-footer-custom mt-4 text-center p-0 bg-transparent border-0">
                            <button type="submit" class="btn btn-save">
                                <i class="bi bi-check2-circle"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>