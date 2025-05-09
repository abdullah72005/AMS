<?php


$manager = $_SESSION['userObj'] ?? null;
$userType = $manager ? User::getRole($manager->getUsername()) : null;

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

<div class="container py-5">
    <?php if ($successMsg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($successMsg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($errorMsg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow rounded-4">
        <div class="card-body p-4">
            <h2 class="text-center mb-4"><?php echo htmlspecialchars($eventName); ?></h2>

            <table class="table table-bordered">
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

            <div class="text-center mt-4">
                <?php if ($userType === 'Alumni'): ?>
                    <form method="POST" class="d-inline">
                        <button type="submit" class="btn btn-success">Join Event</button>
                    </form>
                <?php elseif ($userType === 'FacultyStaff'): ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#participantsModal">
                        Show Participants
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($userType === 'FacultyStaff'): ?>
    
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
<?php endif; ?>