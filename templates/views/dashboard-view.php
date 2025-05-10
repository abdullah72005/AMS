<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Process any redirects first, before any HTML output
try {
    $user = $_SESSION['userObj'];
    
    // Handle redirects before any output is sent
    if ($user instanceof Admin) {
        header('Location: AdminPanel.php');
        exit();
    } elseif (!($user instanceof Alumni) && !($user instanceof FacultyStaff) && !($user instanceof Student)) {
        header('Location: login.php');
        exit();
    }
} catch (Exception $e) {
    $errorMsg = "What is happening " . $e->getMessage();
    // Don't exit here, as we'll display the error to the user
}

// Now it's safe to send output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Alumni Management System</title>
    <!-- Add your CSS links here -->
    <style>
        /* Modern Dashboard Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }
        
        .dashboard-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .glass-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 30px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .glass-container:hover {
            box-shadow: 0 12px 40px rgba(31, 38, 135, 0.15);
        }
        
        .glass-header {
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding-bottom: 20px;
        }
        
        .glass-header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .glass-header .lead {
            color: #6c757d;
            font-weight: 400;
            margin-bottom: 0;
        }
        
        /* Card Styles */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-title {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 12px;
            color: #333;
        }
        
        .card-text {
            color: #6c757d;
            flex-grow: 1;
            margin-bottom: 16px;
        }
        
        /* Button Styles */
        .btn-custom {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Button Colors */
        .text-primary {
            color: #4361ee !important;
        }
        
        .text-success {
            color: #2ec4b6 !important;
        }
        
        .text-warning {
            color: #ff9f1c !important;
        }
        
        .text-info {
            color: #3a86ff !important;
        }
        
        .text-danger {
            color: #e63946 !important;
        }
        
        /* Icon Styles */
        .card-icon {
            font-size: 24px;
            margin-bottom: 15px;
            display: inline-block;
            background: rgba(0, 0, 0, 0.03);
            width: 50px;
            height: 50px;
            line-height: 50px;
            text-align: center;
            border-radius: 12px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .glass-container {
                padding: 20px;
            }
            
            .glass-header h2 {
                font-size: 24px;
            }
        }
        
        /* Error message styling */
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <?php if (isset($errorMsg)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($errorMsg); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($user) && $user instanceof Alumni): ?>
        <!-- Alumni Dashboard -->
        <div class="glass-container">
            <div class="glass-header">
                <h2>Welcome, <?= htmlspecialchars($user->getUsername()) ?> 👋</h2>
                <p class="lead">Alumni Dashboard — Alumni Management System</p>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <!-- Profile -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-primary">📝</div>
                        <h5 class="card-title">My Profile</h5>
                        <p class="card-text">View and update your personal information.</p>
                        <a href="profilepage.php?profileId=<?= $user->getId() ?>" class="btn btn-light text-primary btn-custom mt-auto">View Profile</a>
                    </div>
                </div>

                <!-- Mentorship -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-success">👥</div>
                        <h5 class="card-title">Mentorship</h5>
                        <p class="card-text">Apply for or manage mentorship programs.</p>
                        <a href="mentorship.php" class="btn btn-light text-success btn-custom mt-auto">Mentorship</a>
                    </div>
                </div>

                <!-- Donations -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-warning">💰</div>
                        <h5 class="card-title">Donations</h5>
                        <p class="card-text">Make or review your alumni donations.</p>
                        <a href="donation.php" class="btn btn-light text-warning btn-custom mt-auto">Donate</a>
                    </div>
                </div>

                <!-- Events -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-info">📅</div>
                        <h5 class="card-title">Events</h5>
                        <p class="card-text">Check and join upcoming events.</p>
                        <a href="eventtable.php" class="btn btn-light text-info btn-custom mt-auto">View Events</a>
                    </div>
                </div>

                <!-- Newsletters -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-danger">📰</div>
                        <h5 class="card-title">Newsletters</h5>
                        <p class="card-text">Read latest or archived newsletters.</p>
                        <a href="newsletter.php" class="btn btn-light text-danger btn-custom mt-auto">Newsletters</a>
                    </div>
                </div>
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-primary">📝</div>
                        <h5 class="card-title">Notifications</h5>
                        <p class="card-text">check for any new notifications.</p>
                        <a href="notification.php" class="btn btn-light text-primary btn-custom mt-auto">check notification</a>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif (isset($user) && $user instanceof FacultyStaff): ?>
        <!-- Faculty Dashboard -->
        <div class="glass-container">
            <div class="glass-header">
                <h2>Welcome, <?= htmlspecialchars($user->getUsername()) ?> 👋</h2>
                <p class="lead">Faculty Dashboard — Alumni Management System</p>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <!-- Verify Users -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-primary">🧾</div>
                        <h5 class="card-title">Verify Users</h5>
                        <p class="card-text">Review and approve pending accounts.</p>
                        <a href="verifyAlumni.php" class="btn btn-light text-primary btn-custom mt-auto">View</a>
                    </div>
                </div>

                <!-- Schedule Event -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-success">📅</div>
                        <h5 class="card-title">Schedule Event</h5>
                        <p class="card-text">Create new university/alumni events.</p>
                        <a href="createEvent.php" class="btn btn-light text-success btn-custom mt-auto">Schedule</a>
                    </div>
                </div>

                <!-- Edit Event -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-danger">✏️</div>
                        <h5 class="card-title">Edit Event</h5>
                        <p class="card-text">Update details of existing events.</p>
                        <a href="eventTable.php" class="btn btn-light text-danger btn-custom mt-auto">Edit</a>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-warning">📰</div>
                        <h5 class="card-title">Create Newsletter</h5>
                        <p class="card-text">Write and publish alumni newsletters.</p>
                        <div class="d-flex gap-2 mt-auto">
                            <a href="createNewsletter.php" class="btn btn-light text-warning btn-custom flex-grow-1">Create</a>
                            <a href="newsletterdrafts.php" class="btn btn-light text-warning btn-custom flex-grow-1">Drafts</a>
                        </div>
                    </div>
                </div>

                <!-- Donations -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-info">💸</div>
                        <h5 class="card-title">View Donations</h5>
                        <p class="card-text">View records of alumni donations.</p>
                        <a href="allDonations.php" class="btn btn-light text-info btn-custom mt-auto">View</a>
                    </div>
                </div>

                <!-- Profile -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-primary">📝</div>
                        <h5 class="card-title">My Profile</h5>
                        <p class="card-text">View and update your personal information.</p>
                        <a href="profilepage.php?profileId=<?= $user->getId() ?>" class="btn btn-light text-primary btn-custom mt-auto">View Profile</a>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif (isset($user) && $user instanceof Student): ?>
        <!-- Student Dashboard -->
        <div class="glass-container">
            <div class="glass-header">
                <h2>Welcome, <?= htmlspecialchars($user->getUsername()) ?> 👋</h2>
                <p class="lead">Student Dashboard — Alumni Management System</p>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                <!-- Mentorship Card -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-primary">👥</div>
                        <h5 class="card-title">Available Mentorship Programs</h5>
                        <p class="card-text">Explore available mentorship programs for you.</p>
                        <a href="joinMentorship.php" class="btn btn-light text-primary btn-custom mt-auto">View Programs</a>
                    </div>
                </div>

                <!-- Newsletters Card -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-warning">📰</div>
                        <h5 class="card-title">Latest Newsletters</h5>
                        <p class="card-text">Stay updated with the latest newsletters from the Alumni system.</p>
                        <a href="newsletter.php" class="btn btn-light text-warning btn-custom mt-auto">Read Newsletters</a>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="col">
                    <div class="card p-4">
                        <div class="card-icon text-primary">📝</div>
                        <h5 class="card-title">My Profile</h5>
                        <p class="card-text">View and update your personal information.</p>
                        <a href="profilepage.php?profileId=<?= $user->getId() ?>" class="btn btn-light text-primary btn-custom mt-auto">View Profile</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>