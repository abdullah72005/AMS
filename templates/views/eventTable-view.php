<?php
if (!isset($userRole)) {
    $userRole = $_SESSION['role'] ?? 'Guest';
}

$user = $_SESSION['userObj'] ?? null;

if ($userRole === 'Alumni' && $user && !$user->isVerfied()) {
    echo '<div style="padding: 20px; background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 5px; margin: 20px;">
             Your account is not verified. Please contact the administrator for verification.
          </div>';
    exit;
}

$events = FacultyStaff::getEvents();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view_event_id'])) {
    $eventId = $_POST['view_event_id'];
    header("Location: eventPage.php?eventId=" . urlencode($eventId));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];
    $eventDelete = FacultyStaff::deleteEvent($eventId);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<link rel="stylesheet" href="./../../static/stylesheets/eventTable-view.css">

<div class="events-section">
    <h2 class="section-header">All Events</h2>

    <?php if (empty($events)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-calendar-x"></i>
            </div>
            <p class="empty-state-text">No events found. Check back later for upcoming events.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table events-table">
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
                                <div class="event-row-name">
                                    <span class="event-name">
                                        <i class="bi bi-calendar-event"></i>
                                        <?= htmlspecialchars($eventName) ?>
                                    </span>
                                    <form method="post" class="mt-2 mt-md-0">
                                        <input type="hidden" name="view_event_id" value="<?= htmlspecialchars($event) ?>">
                                        <button type="submit" class="btn-action btn-view">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <span class="date-badge">
                                    <i class="bi bi-calendar3"></i>
                                    <?= $eventDate->format('d-m-Y') ?>
                                </span>
                            </td>
                            <td>
                                <span class="creator-badge">
                                    <i class="bi bi-person"></i>
                                    <?= htmlspecialchars($eventCreator) ?>
                                </span>
                            </td>
                            <?php if ($userRole === 'FacultyStaff'): ?>
                                <td>
                                    <div class="action-buttons">
                                        <a class="btn-action btn-edit" href="editEvent.php?eventId=<?= urlencode($eventData['eventId']) ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>

                                        <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                            <input type="hidden" name="event_id" value="<?= htmlspecialchars($eventData['eventId']) ?>">
                                            <button type="submit" class="btn-action btn-delete">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>