<?php

$newsletterSubject = new Newsletter();
$eventSubject = new Event();
$displayNames = [
    'newsletter' => 'Newsletter Updates',
    'mentorship' => 'New Student Notifications',
    'events' => 'Event Notifications'
];

$notifications = [];

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

        $isNewsletterSubscribed = $newsletterSubject->isSubscribed($user);
        $isMentorshipSubscribed = $mentorshipSubject->isSubscribed($user);
        $isEventSubscribed = $eventSubject->isSubscribed($user);

    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}
        
if (!isset($isNewsletterSubscribed)) {
    try {
        $isNewsletterSubscribed = $newsletterSubject->isSubscribed($user);
        $isEventSubscribed = $eventSubject->isSubscribed($user);
    } catch (Exception $e) {
        $errorMsg = "Error loading subscription data: " . $e->getMessage();
    }
}
$notifications = $user->getNotifications();
?>

<link rel="stylesheet" href="./../../static/stylesheets/notification-view.css">
<body class="bg-light">
    <div class="container py-5">
        <h1 class="page-title">Notification Preferences</h1>

        <?php if (isset($successMsg)): ?>
            <div class="alert alert-success d-flex align-items-center fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div><?= $successMsg ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($errorMsg)): ?>
            <div class="alert alert-danger d-flex align-items-center fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><?= $errorMsg ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="bi bi-envelope-fill me-2"></i> Newsletter Updates
                    </div>
                    <div class="card-body text-center">
                        <div class="subscription-icon text-primary">
                            <i class="bi bi-envelope-paper"></i>
                        </div>
                        <p class="card-text mb-4">
                            Receive our monthly newsletter with updates, tips, and community highlights.
                        </p>
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <span class="status-indicator <?= $isNewsletterSubscribed ? 'status-active' : 'status-inactive' ?>"></span>
                            <span><?= $isNewsletterSubscribed ? 'Currently Active' : 'Not Active' ?></span>
                        </div>
                        <form method="post">
                            <input type="hidden" name="subjectType" value="newsletter">
                            <div class="d-grid gap-2">
                                <button type="submit" name="action" value="subscribe" 
                                    class="btn btn-lg <?= $isNewsletterSubscribed ? 'btn-success disabled' : 'btn-primary' ?>"
                                    <?= $isNewsletterSubscribed ? 'disabled aria-disabled="true"' : '' ?>>
                                    <?= $isNewsletterSubscribed ? '<i class="bi bi-check2-circle me-1"></i> Subscribed' : '<i class="bi bi-plus-circle me-1"></i> Subscribe' ?>
                                </button>
                                <button type="submit" name="action" value="unsubscribe" 
                                    class="btn btn-outline-secondary"
                                    <?= !$isNewsletterSubscribed ? 'disabled aria-disabled="true"' : '' ?>>
                                    <i class="bi bi-x-circle me-1"></i> Unsubscribe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-warning text-dark d-flex align-items-center">
                        <i class="bi bi-calendar-event-fill me-2"></i> Event Notifications
                    </div>
                    <div class="card-body text-center">
                        <div class="subscription-icon text-warning">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <p class="card-text mb-4">
                            Stay updated on upcoming workshops, webinars, and community gatherings.
                        </p>
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <span class="status-indicator <?= $isEventSubscribed ? 'status-active' : 'status-inactive' ?>"></span>
                            <span><?= $isEventSubscribed ? 'Currently Active' : 'Not Active' ?></span>
                        </div>
                        <form method="post">
                            <input type="hidden" name="subjectType" value="events">
                            <div class="d-grid gap-2">
                                <button type="submit" name="action" value="subscribe" 
                                    class="btn btn-lg <?= $isEventSubscribed ? 'btn-success disabled' : 'btn-warning' ?>"
                                    <?= $isEventSubscribed ? 'disabled aria-disabled="true"' : '' ?>>
                                    <?= $isEventSubscribed ? '<i class="bi bi-check2-circle me-1"></i> Notifications Active' : '<i class="bi bi-bell me-1"></i> Enable Notifications' ?>
                                </button>
                                <button type="submit" name="action" value="unsubscribe" 
                                    class="btn btn-outline-secondary"
                                    <?= !$isEventSubscribed ? 'disabled aria-disabled="true"' : '' ?>>
                                    <i class="bi bi-bell-slash me-1"></i> Disable Notifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="bi bi-bell-fill me-2"></i>
                <span>Notification History</span>
                <span class="badge bg-light text-dark ms-2 rounded-pill">
                    <?= count($notifications) ?>
                </span>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($notifications)): ?>
                    <div class="list-group">
                        <?php foreach ($notifications as $notification): ?>
                            <?php 
                            $message = $notification['notification']; 

                            $notifType = 'info';
                            $notifIcon = 'info-circle';

                            if (stripos($message, 'newsletter') !== false) {
                                $notifType = 'primary';
                                $notifIcon = 'envelope';
                            } elseif (stripos($message, 'student') !== false || stripos($message, 'mentorship') !== false) {
                                $notifType = 'success';
                                $notifIcon = 'person';
                            } elseif (stripos($message, 'event') !== false) {
                                $notifType = 'warning';
                                $notifIcon = 'calendar-event';
                            }
                            ?>
                            <div class="notification-item d-flex align-items-center border-<?= $notifType ?>">
                                <div class="me-3 text-<?= $notifType ?>">
                                    <i class="bi bi-<?= $notifIcon ?>-fill fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="fw-bold"><?= $message ?></div>
                                        <?php if (!empty($notification['timestamp'])): ?>
                                            <small class="notification-timestamp ms-2">
                                                <?= date('M d, Y h:i A', strtotime($notification['timestamp'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state text-center">
                        <i class="bi bi-inbox-fill fs-1 mb-3"></i>
                        <h5 class="text-muted">No notifications yet</h5>
                        <p class="text-muted mb-0">When you receive notifications, they will appear here</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div