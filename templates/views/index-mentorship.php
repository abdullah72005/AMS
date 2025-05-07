<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    die("Access denied. Please log in.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mentorship Portal</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form, .section { margin-bottom: 30px; }
        textarea { width: 100%; max-width: 600px; }
        .mentorship { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

<h1>Mentorship Portal</h1>

<!-- CREATE mentorship (for Alumni) -->
<?php if ($_SESSION['role'] === 'Alumni'): ?>
<div class="section">
    <h2>Create a Mentorship</h2>
    <form method="POST" action="back_mentorship_backend.php">
        <input type="hidden" name="action" value="create">
        <label>Description:</label><br>
        <textarea name="description" rows="4" required></textarea><br><br>
        <button type="submit">Create</button>
    </form>
</div>
<?php endif; ?>

<!-- VIEW mentorships (all users) -->
<div class="section">
    <h2>Available Mentorships</h2>
    <form method="POST" action="back_mentorship_backend.php">
        <input type="hidden" name="action" value="view">
        <button type="submit">Refresh List</button>
    </form>
</div>

<!-- JOIN mentorship (for Students) -->
<?php if ($_SESSION['role'] === 'Student'): ?>
<div class="section">
    <h2>Join a Mentorship</h2>
    <form method="POST" action="back_mentorship_backend.php">
        <input type="hidden" name="action" value="join">
        <label>Mentorship ID:</label>
        <input type="number" name="mentorship_id" required><br><br>
        <button type="submit">Join</button>
    </form>
</div>
<?php endif; ?>

<!-- NOTIFICATIONS (all users) -->
<div class="section">
    <h2>Your Notifications</h2>
    <form method="POST" action="back_mentorship_backend.php">
        <input type="hidden" name="action" value="notifications">
        <button type="submit">Show Notifications</button>
    </form>
</div>

</body>
</html>
