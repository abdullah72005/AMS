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

<link rel="stylesheet" href="./../../static/stylesheets/editEvent-view.css">

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