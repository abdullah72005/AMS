<?php
// Ensure the user role is defined
if (!isset($userRole)) {
    $userRole = $_SESSION['role'] ?? 'Guest';
}

$events = FacultyStaff::getEvents(); // Returns array of event IDs

// Handle view event action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view_event_id'])) {
    $eventId = $_POST['view_event_id'];
    header("Location: eventPage.php?eventId=" . urlencode($eventId));
    exit();
}

// Handle delete event action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];
    $eventDelete = FacultyStaff::deleteEvent($eventId);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<h2 class="mb-4">All Events</h2>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Creator</th>
            <?php if ($userRole === 'FacultyStaff'): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($events as $event): ?>
            <?php
            $eventData = Event::getEventById($event);
            $eventName = $eventData['name'];
            $eventDate = new DateTime($eventData['date']);
            $creatorId = $eventData['creatorId'];
            $eventCreator = Event::getMadeBy($creatorId);
            ?>
            <tr>
                <td>
                    <?= htmlspecialchars($eventName) ?>
                    <!-- View Button -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="view_event_id" value="<?= htmlspecialchars($event) ?>">
                        <button type="submit" class="btn btn-sm btn-info ml-2">View</button>
                    </form>
                </td>
                <td><?= $eventDate->format('d-m-Y') ?></td>
                <td><?= htmlspecialchars($eventCreator) ?></td>
                <?php if ($userRole === 'FacultyStaff'): ?>
                    <td>
                        <!-- Edit Button -->
                        <a class="btn btn-sm btn-primary" href="editEvent.php?eventId=<?= urlencode($eventData['eventId']) ?>">Edit</a>

                        <!-- Delete Button (in form) -->
                        <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                            <input type="hidden" name="event_id" value="<?= htmlspecialchars($eventData['eventId']) ?>">
                            <button type="submit" class="btn btn-sm btn-danger ml-2">Delete</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>