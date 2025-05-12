<?php
$manager = $_SESSION['userObj'] ?? null;
$userType = $manager ? User::getRole($manager->getUsername()) : null;

// Ensure $user is initialized
$user = $_SESSION['userObj'] ?? null;

if ($userRole === 'Alumni' && $user && !$user->isVerfied()) {
    echo '<div style="padding: 20px; background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 5px; margin: 20px;">
             Your account is not verified. Please contact the administrator for verification.
          </div>';
    exit;
}

if (!isset($_GET['eventId']) || empty($_GET['eventId'])) {
    echo "<div class='container mt-4 alert alert-warning'>No event selected.</div>";
    exit;
}

$successMsg = '';
$errorMsg = '';

try {
    $eventId = (int)$_GET['eventId'];
    $event = Event::getEventById($eventId);

    if (!$event) {
        throw new Exception("Event not found.");
    }

    $eventName = $event['name'];
    $eventDescription = $event['description'];
    $eventDate = new DateTime($event['date']);
    $eventObject = new Event($eventName, $eventDescription, $eventDate);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userType === 'Alumni') {
        try {
            $manager->signupForEvent($eventId);
            $successMsg = "You have successfully joined the event.";
        } catch (Exception $e) {
            $errorMsg = "Could not join event: " . $e->getMessage();
        }
    }

    if ($userType === 'FacultyStaff') {
        $participants = $manager->getEventParticipants($eventId);
    }

} catch (Exception $e) {
    echo "<div class='container mt-4 alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>

<!-- Link to external CSS -->
<link rel="stylesheet" href="./../../static/stylesheets/eventPage-view.css">

<div class="container py-5">
    <?php if ($successMsg): ?>
        <div class="alert alert-success alert-dismissible fade show alert-custom mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php echo htmlspecialchars($successMsg); ?>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger alert-dismissible fade show alert-custom mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo htmlspecialchars($errorMsg); ?>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card event-card">
        <div class="card-body p-4 p-md-5">
            <h2 class="event-title text-center"><?php echo htmlspecialchars($eventName); ?></h2>

            <table class="table table-bordered event-table mb-4">
                <tbody>
                    <tr>
                        <th scope="row">Description</th>
                        <td><?php echo htmlspecialchars($eventDescription); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Date</th>
                        <td><?php echo htmlspecialchars($event['date']); ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center mt-5">
                <?php if ($userType === 'Alumni'): ?>
                    <form method="POST" class="d-inline">
                        <button type="submit" class="btn btn-success btn-event btn-join">
                            <i class="bi bi-person-plus me-2"></i>Join Event
                        </button>
                    </form>
                <?php elseif ($userType === 'FacultyStaff'): ?>
                    <button class="btn btn-primary btn-event btn-view" data-bs-toggle="modal" data-bs-target="#participantsModal">
                        <i class="bi bi-people me-2"></i>Show Participants
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($userType === 'FacultyStaff'): ?>
<!-- Modal for Faculty/Staff -->
<div class="modal fade custom-modal" id="participantsModal" tabindex="-1" aria-labelledby="participantsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title" id="participantsModalLabel">
                    <i class="bi bi-people-fill me-2"></i>Event Participants
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php if (!empty($participants)): ?>
                    <div class="participant-list">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($participants as $name): ?>
                                <li class="list-group-item participant-item">
                                    <i class="bi bi-person me-2"></i><?php echo htmlspecialchars($name); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x" style="font-size: 2rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">No participants have joined this event yet.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>