<?php 
require_once("../src/FacultyStaff.php");
$successMsg = "";
$errorMsg = "";

//check if user is FacultyStaff role
if (User::getRole($_SESSION['username']) !== 'FacultyStaff') {
    throw new Exception("You do not have permission to create an event.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $date = new DateTime($_POST['date']);

        
        $manager = new FacultyStaff($_SESSION['username']);
        $eventId = $manager->scheduleEvent($title, $description, $date);
        header("Location: eventPage.php/?eventId=$eventId");
        exit;
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow rounded-4">
                    <div class="card-body p-4">
                        <h2 class="mb-4 text-center">Create a New Event</h2>

                        <?php if (!empty($errorMsg)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="" novalidate>
                            <div class="mb-3">
                                <label for="title" class="form-label">Event Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Event Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Event Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Create Event</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS for optional components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
