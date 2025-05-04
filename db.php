<?php
$host = 'localhost';
$dbname = 'AMS';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT username, password_hash, role FROM User WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ali']);

    // Fetch the results
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "<h3>User Found:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Username</th><th>Password Hash</th><th>Role</th></tr>";
        echo "<tr>";
        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
        echo "<td>" . htmlspecialchars($user['password_hash']) . "</td>";
        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "No user found with username 'ali'";
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>