<?php
require_once("../src/Event.php");
require_once("../src/FacultyStaff.php");


$manager = new FacultyStaff($_SESSION['username']);

$event = Event::getEventById($_SESSION['eventId']);
if (!$event) {
    throw new Exception("Event not found.");
}
$eventName =$event['name'];
$eventDescription = $event['description'];
$eventDate = new DateTime($event['date']);

$eventObject = new Event($eventName, $eventDescription, $eventDate);


// Simulate user type: 'alumni' or 'facultyStaff'
$userType = $_SESSION['role']; // change to 'alumni' to test the other view

// Dummy participant list
$participants = $manager->getEventParticipants($_SESSION['eventId']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="card shadow rounded-4">
        <div class="card-body p-4">
            <h2 class="text-center mb-4"><?php echo htmlspecialchars($event['name']); ?></h2>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Description</th>
                        <td><?php echo htmlspecialchars($event['description']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Date</th>
                        <td><?php echo htmlspecialchars($event['date']); ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <?php if ($userType === 'alumni'): ?>
                    <button class="btn btn-success">Join Event</button>
                <?php elseif ($userType === 'facultyStaff'): ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#participantsModal">
                        Show Participants
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Faculty/Staff -->
<div class="modal fade" id="participantsModal" tabindex="-1" aria-labelledby="participantsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title" id="participantsModalLabel">Participants</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($participants)): ?>
                    <ul class="list-group">
                        <?php foreach ($participants as $name): ?>
                            <li class="list-group-item"><?php echo htmlspecialchars($name); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No participants yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
