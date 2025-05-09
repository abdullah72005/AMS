<?php
session_start();
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin': header('Location: admin_dashboard.php'); break;
        case 'faculty': header('Location: faculty_index.php'); break;
        case 'alumni': header('Location: alumni_page.php'); break;
        case 'student': header('Location: student_page.php'); break;
        default: session_destroy(); header('Location: login.php');
    }
    exit;
}
?>

<head>
  <title>AMS | Welcome</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #1e3c72, #2a5d84, #4b79c1, #7a9fd7); /* Gradient background */
      background-size: 400% 400%;
      animation: gradientAnimation 10s ease infinite; /* Animation for the moving gradient */
    }

    @keyframes gradientAnimation {
      0% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
      100% {
        background-position: 0% 50%;
      }
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 20px;
      padding: 50px 30px;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      box-shadow: 0 8px 32px rgba(0,0,0,0.3);
      text-align: center;
      max-width: 500px;
      color: white;
      position: relative;
      z-index: 1;
    }

    .glass-card h1 {
      font-weight: 600;
    }

    .glass-card p {
      font-weight: 300;
    }

    .btn-custom {
      border-radius: 30px;
      padding: 10px 30px;
      font-weight: 500;
      transition: 0.3s ease;
    }

    .btn-custom:hover {
      opacity: 0.9;
    }

    .footer-text {
      font-size: 0.85rem;
      color: #ddd;
      margin-top: 30px;
    }
  </style>
</head>
<body>

<div class="glass-card">
  <h1>Welcome to AMS</h1>
  <p class="mb-4">Your gateway to connect, grow, and contribute through our Alumni Management System.</p>

  <div class="d-flex justify-content-center gap-3">
    <a href="login.php" class="btn btn-light text-primary btn-custom">Login</a>
    <a href="register.php" class="btn btn-outline-light btn-custom">Sign Up</a>
  </div>

  <div class="footer-text">
    &copy; <?= date("Y") ?> AMS | All rights reserved
  </div>
</div>

</body>
</html>
