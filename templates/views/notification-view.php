<?php

$newsletterSubject = new Newsletter();
$mentorshipSubject = new Mentorship();
$eventSubject = new Event();

// Display names mapping
$displayNames = [
    'newsletter' => 'Newsletter Updates',
    'mentorship' => 'New Student Notifications',
    'events' => 'Event Notifications'
];

// Initialize notifications array
$notifications = [];

// Get user object first
$user = $_SESSION['userObj'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $subjectType = $_POST['subjectType'];
        
        if (!array_key_exists($subjectType, $displayNames)) {
            throw new Exception("Invalid subscription type");
        }
        
        $displayName = $displayNames[$subjectType];
        $subscriptionType = 'subscribed_' . $subjectType;

        $subject = match($subjectType) {
            'newsletter' => $newsletterSubject,
            'mentorship' => $mentorshipSubject,
            'events' => $eventSubject,
        };

        if ($_POST['action'] === 'subscribe') {
            if ($subject->isSubscribed($user)) {
                throw new Exception("You are already receiving $displayName");
            }
            $subject->attach($user, $subscriptionType);
        } else {
            if (!$subject->isSubscribed($user)) {
                throw new Exception("$displayName are not currently enabled");
            }
            $subject->detach($user, $subscriptionType);
        }

        // Refresh subscription statuses
        $isNewsletterSubscribed = $newsletterSubject->isSubscribed($user);
        $isMentorshipSubscribed = $mentorshipSubject->isSubscribed($user);
        $isEventSubscribed = $eventSubject->isSubscribed($user);

    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}
        

// Get current statuses if not in POST
if (!isset($isNewsletterSubscribed)) {
    try {
        $isNewsletterSubscribed = $newsletterSubject->isSubscribed($user);
        $isMentorshipSubscribed = $mentorshipSubject->isSubscribed($user);
        $isEventSubscribed = $eventSubject->isSubscribed($user);
        $notifications = $user->getNotifications();
    } catch (Exception $e) {
        $errorMsg = "Error loading subscription data: " . $e->getMessage();
    }
}

?>
<div class="container mt-5">
    <?php if (isset($successMsg)): ?>
        <div class="alert alert-success d-flex align-items-center">
            <i class="bi bi-check2-circle me-2"></i>
            <?= $successMsg ?>
        </div>
    <?php endif; ?>
    <?php if (isset($errorMsg)): ?>
        <div class="alert alert-danger d-flex align-items-center">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?= $errorMsg ?>
        </div>
    <?php endif; ?>

    <!-- Subscription Cards -->
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
        <!-- Newsletter Card -->
        <div class="col">
            <div class="card h-100 shadow">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-envelope"></i> Newsletter Updates
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="subjectType" value="newsletter">
                        <button type="submit" name="action" value="subscribe" 
                            class="btn btn-lg w-100 <?= $isNewsletterSubscribed ? 'btn-success disabled' : 'btn-primary' ?>"
                            <?= $isNewsletterSubscribed ? 'disabled aria-disabled="true"' : '' ?>>
                            <?= $isNewsletterSubscribed ? 'Subscribed' : 'Subscribe to Newsletter' ?>
                        </button>
                        <button type="submit" name="action" value="unsubscribe" 
                            class="btn btn-lg btn-outline-primary w-100 mt-2"
                            <?= !$isNewsletterSubscribed ? 'disabled aria-disabled="true"' : '' ?>>
                            Unsubscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mentorship Card -->
        <div class="col">
            <div class="card h-100 shadow">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-person-plus"></i> New Student Alerts
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="subjectType" value="mentorship">
                        <button type="submit" name="action" value="subscribe" 
                            class="btn btn-lg w-100 <?= $isMentorshipSubscribed ? 'btn-success disabled' : 'btn-outline-success' ?>"
                            <?= $isMentorshipSubscribed ? 'disabled aria-disabled="true"' : '' ?>>
                            <?= $isMentorshipSubscribed ? 'Alerts Active' : 'Enable Student Alerts' ?>
                        </button>
                        <button type="submit" name="action" value="unsubscribe" 
                            class="btn btn-lg btn-outline-success w-100 mt-2"
                            <?= !$isMentorshipSubscribed ? 'disabled aria-disabled="true"' : '' ?>>
                            Disable Alerts
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Event Card -->
        <div class="col">
            <div class="card h-100 shadow">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-calendar-event"></i> Event Notifications
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="subjectType" value="events">
                        <button type="submit" name="action" value="subscribe" 
                            class="btn btn-lg w-100 <?= $isEventSubscribed ? 'btn-success disabled' : 'btn-warning' ?>"
                            <?= $isEventSubscribed ? 'disabled aria-disabled="true"' : '' ?>>
                            <?= $isEventSubscribed ? 'Notifications Active' : 'Enable Event Alerts' ?>
                        </button>
                        <button type="submit" name="action" value="unsubscribe" 
                            class="btn btn-lg btn-outline-warning w-100 mt-2"
                            <?= !$isEventSubscribed ? 'disabled aria-disabled="true"' : '' ?>>
                            Disable Notifications
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="card shadow">
        <div class="card-header bg-info text-white d-flex align-items-center">
            <i class="bi bi-bell me-2"></i>
            Notification History
        </div>
        <div class="card-body">
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notification): ?>
                    <?php 
                    $message = $notification['notification'] ?>
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="badge bg-dark rounded-pill ms-2">
                                <?= $message ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2 mb-0">No notifications available at this time</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>