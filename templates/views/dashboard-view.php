<?php
try{
    $user = $_SESSION['userObj'];
}
catch (Exception $e){
    throw new Exception("What is happening " . $e->getMessage());
    exit();
}
?>
<?php if ($user instanceof Alumni): ?>
        <!-- Alumni cards... -->
        <!-- Main Glass Content -->
        <div class="glass-container">
            <div class="glass-header">
            <h2>Welcome,👋</h2>
            <p class="lead">Alumni Dashboard — Alumni Management System</p>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 allign-items-center justify-content-center">
            <!-- Profile -->
            <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title">📝 My Profile</h5>
                <p class="card-text">View and update your personal information.</p>
                <a href="profile.php" class="btn btn-light text-primary btn-custom mt-auto">View Profile</a>
                </div>
            </div>

            <!-- Mentorship -->
            <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title">👥 Mentorship</h5>
                <p class="card-text">Apply for or manage mentorship programs.</p>
                <a href="mentorship.php" class="btn btn-light text-success btn-custom mt-auto">Mentorship</a>
                </div>
            </div>

            <!-- Donations -->
            <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title">💰 Donations</h5>
                <p class="card-text">Make or review your alumni donations.</p>
                <a href="donation.php" class="btn btn-light text-warning btn-custom mt-auto">Donate</a>
                </div>
            </div>

            <!-- Events -->
            <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title">📅 Events</h5>
                <p class="card-text">Check and join upcoming events.</p>
                <a href="events.php" class="btn btn-light text-info btn-custom mt-auto">View Events</a>
                </div>
            </div>

            <!-- Newsletters -->
            <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title">📰 Newsletters</h5>
                <p class="card-text">Read latest or archived newsletters.</p>
                <a href="newsletters.php" class="btn btn-light text-danger btn-custom mt-auto">Newsletters</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php elseif ($user instanceof FacultyStaff): ?>
        <!-- Faculty cards... -->
        <div class="glass-container">
            <div class="glass-header">
            <h2>Welcome,👋</h2>
            <p class="lead">Faculty Dashboard — Alumni Management System</p>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 allign-items-center justify-content-center">
            <!-- Verify Users -->
            <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title">🧾 Verify Users</h5>
                <p class="card-text">Review and approve pending accounts.</p>
                <a href="verify_users.php" class="btn btn-light text-primary btn-custom mt-auto">View</a>
                </div>
            </div>

            <!-- Schedule Event -->
            <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title">📅 Schedule Event</h5>
                <p class="card-text">Create new university/alumni events.</p>
                <a href="schedule_event.php" class="btn btn-light text-success btn-custom mt-auto">Schedule</a>
                </div>
            </div>
            <!-- Reschedule Event -->
            <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title"> Edit Event</h5>
                <p class="card-text">Update details of existing events.</p>
                <a href="reschedule_event.php" class="btn btn-light text-danger btn-custom mt-auto">Edit</a>
                </div>
            </div>

            <!-- Draft Newsletter -->
            <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title">📰 Draft Newsletter</h5>
                <p class="card-text">Write and publish alumni newsletters.</p>
                <a href="draft_newsletter.php" class="btn btn-light text-warning btn-custom mt-auto">Draft</a>
                </div>
            </div>

            <!-- Donation History -->
            <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title">💸 View Donations </h5>
                <p class="card-text">View records of alumni donations.</p>
                <a href="donation_history.php" class="btn btn-light text-info btn-custom mt-auto">View</a>
                </div>
            </div>
        <div class="col">
                <div class="card p-3 h-100">
                <h5 class="card-title">📝 My Profile</h5>
                <p class="card-text">View and update your personal information.</p>
                <a href="profile.php" class="btn btn-light text-primary btn-custom mt-auto">View Profile</a>
                </div>
            </div>
            
            </div>
        </div>

    </div>
</div>

<?php elseif ($user instanceof Student): ?>
        <!-- Student cards... -->
        <!-- Main Content -->
        <div class="glass-container">
            <div class="glass-header">
            <h2>Welcome 👋</h2>
            <p class="lead">Student Dashboard — Alumni Management System</p>
            </div>

            <div class="row">
            <!-- Mentorship Card -->
            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                <h5 class="card-title">Available Mentorship Programs</h5>
                <p class="card-text">Explore available mentorship programs for you.</p>
                <a href="apply_mentorship.php" class="btn btn-light text-primary btn-custom mt-auto">View Programs</a>
                </div>
            </div>

            <!-- Newsletters Card -->
            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                <h5 class="card-title">Latest Newsletters</h5>
                <p class="card-text">Stay updated with the latest newsletters from the Alumni system.</p>
                <a href="view_newsletters.php" class="btn btn-light text-warning btn-custom mt-auto">Read Newsletters</a>
                </div>
            </div>

            <!-- Profile Card -->
            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                <h5 class="card-title">📝 My Profile</h5>
                <p class="card-text">View and update your personal information.</p>
                <a href="profile.php" class="btn btn-light text-primary btn-custom mt-auto">View Profile</a>
                </div>
            </div>
            </div>
        </div>

    </div>
</div>
<?php elseif ($user instanceof Admin): header('Location: AdminPanel.php');?>

<?php else: header('Location: login.php');?>
<?php endif; ?>

</body>
</html>