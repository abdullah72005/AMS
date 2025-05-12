<?php 


if (session_status() == PHP_SESSION_NONE) {
    ob_start(); // Start output buffering
    session_start();
}
require_once("../src/User.php");

// Get current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Pages that don't require authentication
$excluded_pages = ['login.php', 'register.php'];

// Only check session if not on excluded pages
if (!in_array($current_page, $excluded_pages)) {
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }
}
if (isset($_POST['search'])) {
    // Sanitize input
    $username = htmlspecialchars($_POST['username']);
    
    try{
      // Get alumni ID from search function
      $alumniId = User::searchAlumni($username);
      
      if ($alumniId) {
          // Redirect to profile page if found
          header("Location: profilepage.php?profileId=$alumniId");
          exit();
      } else {
          // Show error message if not found
          echo '<div class="alert alert-danger mt-2">Alumni not found</div>';
      }
    }
    catch (Exception $e) {
      // Handle exception if needed
      echo '<div class="alert alert-danger mt-2">No Alumni with this username.</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../static/stylesheets/styles.css">
    <link rel="stylesheet" href="./../static/stylesheets/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- <link rel="stylesheet" href="./../static/stylesheets/index.css"> -->
    <script rel="stylesheet" href="./../static/scripts/script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <?php  echo "<title>" . $titleView . "</title>"; ?>
    
</head>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">ALMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      </ul>

      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <form method="post" class="d-flex me-2" role="search">
            <input class="form-control me-2" type="search" name="username" placeholder="Search Alumni" aria-label="Search">
            <button class="btn btn-outline-success" type="submit" name="search">Search</button>
        </form>
        <form method="post" action="/logout.php" class="d-flex">
            <button class="btn btn-outline-danger" type="submit">Logout</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</nav>

<style>
/* Modern Navbar Styling */
.navbar {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 0.8rem 1rem;
  background-color: #ffffff !important;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.navbar-brand {
  font-weight: 600;
  font-size: 1.5rem;
  color: #3a3a3a;
  transition: color 0.3s ease, background-color 0.3s ease;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  letter-spacing: 0.5px;
}

.navbar-brand:hover {
  color: #0d6efd;
  background-color: rgba(13, 110, 253, 0.05);
}

.navbar-toggler {
  border: none;
  padding: 0.5rem;
  transition: background-color 0.3s ease;
}

.navbar-toggler:focus {
  box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
  outline: none;
}

.navbar-toggler:hover {
  background-color: rgba(0, 0, 0, 0.03);
}

.form-control {
  border-radius: 20px;
  padding: 0.5rem 1rem;
  border: 1px solid #dee2e6;
  transition: all 0.3s ease;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.form-control:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn {
  border-radius: 20px;
  padding: 0.5rem 1.25rem;
  transition: all 0.3s ease;
  font-weight: 500;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.btn-outline-success {
  border-color: #0d6efd;
  color: #0d6efd;
}

.btn-outline-success:hover {
  background-color: #0d6efd;
  border-color: #0d6efd;
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-outline-danger {
  margin-left: 0.75rem;
  border-color: #dc3545;
}

.btn-outline-danger:hover {
  background-color: #dc3545;
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Mobile-friendly styles */
@media (max-width: 991.98px) {
  .navbar-collapse {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
  }
  
  .navbar-collapse form {
    width: 100%;
    margin: 0.5rem 0;
  }
  
  .d-flex {
    width: 100%;
  }
  
  .form-control {
    flex: 1;
  }
  
  .btn-outline-danger {
    margin-left: 0;
    margin-top: 0.5rem;
    width: 100%;
  }
  
  form + form {
    margin-top: 0.75rem;
  }
}
</style>
<body class="main">
    <?php include($childView); ?>
</body>
<footer>
          <div class="footertxt">&copy;2025 | ALMS | All rights reserved &nbsp;&nbsp;&nbsp; </div>
</footer>
</html>