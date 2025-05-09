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

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow rounded-4">
                <div class="card-body p-4">
                    <h2 class="mb-4 text-center">Edit Event</h2>

                    <?php if (!empty($errorMsg)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
                    <?php elseif (!empty($successMsg)): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="title" class="form-label">Event Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="<?= htmlspecialchars($currentTitle) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Event Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($currentDescription) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Event Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                   value="<?= htmlspecialchars($currentDate) ?>">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>