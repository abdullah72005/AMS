<?php
session_start();
$username = $_SESSION['username'] ?? "AlumniUser";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>AMS | Alumni Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
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

    <!-- Footer -->
    <div class="footer mt-5">
      <p>&copy; 2025 AMS | Alumni Management System</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
