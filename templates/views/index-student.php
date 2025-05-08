<?php
session_start();
$username = $_SESSION['username'] ?? "StudentUser"; // fallback if not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AMS | Student Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../static/stylesheets/index.css">


</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top mb-4">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="#">AMS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon bg-light"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Newsletters</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Mentorship</a></li>
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

  <!-- Main Content -->
  <div class="glass-container">
    <div class="glass-header">
      <h2>Welcome <?= htmlspecialchars($username) ?> 👋</h2>
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

  <!-- Footer -->
  <div class="footer">
    <p>&copy; 2025 AMS | Alumni Management System</p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
