<?php
session_start();
$username = $_SESSION['username'] ?? "Faculty"; // fallback if not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AMS | Faculty Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../static/stylesheets/index.css">

</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="#">AMS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon bg-light"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#">About</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
        </ul>

        <form class="d-flex me-2" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
          <form method="post" action="/logout.php" class="d-flex">
            <button class="btn btn-outline-danger" type="submit">Logout</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </nav>




  <!-- Main Glass Content -->
  <div class="glass-container">
    <div class="glass-header">
      <h2>Welcome, <?= htmlspecialchars($username) ?> 👋</h2>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
