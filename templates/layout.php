<?php 


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="./../static/stylesheets/footer.css">
    <script src="script.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <?php  echo "<title>" . $titleView . "</title>"; ?>
</head>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li><li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li><li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
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
<body class="main">
    <?php include($childView); ?>
</body>
<footer>
          <div class="footertxt">&copy;2025 | AMS | All rights reserved &nbsp;&nbsp;&nbsp; </div>
</footer>
</html>