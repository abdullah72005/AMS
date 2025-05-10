<?php
// Ensure the user role is defined
if (!isset($userRole)) {
    $userRole = $_SESSION['role'] ?? 'Guest';
}

// Ensure $user is initialized
$user = $_SESSION['userObj'] ?? null;

if ($userRole === 'Alumni' && $user && !$user->isVerfied()) {
    echo '<div style="padding: 20px; background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 5px; margin: 20px;">
             Your account is not verified. Please contact the administrator for verification.
          </div>';
    exit;
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

<!-- Custom CSS for enhanced design -->
<style>
    .events-section {
        background-color: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }
    
    .section-header {
        color: #333;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
        position: relative;
    }
    
    .section-header:after {
        content: '';
        position: absolute;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #007bff, #6610f2);
        bottom: -2px;
        left: 0;
    }
    
    .events-table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.03);
    }
    
    .events-table thead {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }
    
    .events-table th {
        font-weight: 600;
        color: #495057;
        border-bottom: none;
        padding: 1rem;
    }
    
    .events-table td {
        vertical-align: middle;
        padding: 1rem;
        border-color: #f0f0f0;
    }
    
    .events-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .events-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.04);
        transform: translateY(-1px);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .event-name {
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .date-badge {
        background-color: #e9f5ff;
        color: #0d6efd;
        border-radius: 6px;
        padding: 0.3rem 0.75rem;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .creator-badge {
        color: #495057;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }
    
    .btn-view {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }
    
    .btn-view:hover {
        background: linear-gradient(135deg, #138496, #117a8b);
        color: white;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #007bff, #0069d9);
        color: white;
    }
    
    .btn-edit:hover {
        background: linear-gradient(135deg, #0069d9, #0062cc);
        color: white;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    
    .btn-delete:hover {
        background: linear-gradient(135deg, #c82333, #bd2130);
        color: white;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .event-row-name {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            gap: 0.3rem;
        }
        
        .event-row-name {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        color: #6c757d;
        font-size: 1.1rem;
    }
</style>

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
                                        <!-- Edit Button -->
                                        <a class="btn-action btn-edit" href="editEvent.php?eventId=<?= urlencode($eventData['eventId']) ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>

                                        <!-- Delete Button (in form) -->
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