<?php 
require_once("../src/FacultyStaff.php");
$successMsg = "";
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $date = new DateTime($_POST['date']);

        $manager = new FacultyStaff();
        $eventId = $manager->scheduleEvent($title, $description, $date);

        $successMsg = "Event created successfully. Event ID: " . $eventId;
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}
?>
<form class="container mt-5">
    <h2>Create a New Event</h2>

    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>

    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="title" class="form-label">Event Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Event Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Event Date & Time</label>
            <input type="datetime-local" class="form-control" id="date" name="date" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Event</button>
    </form> 
</form>


