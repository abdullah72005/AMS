<?php
$host = 'localhost';
$dbname = 'AMS';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Use prepare() for parameterized queries
    $sql = "INSERT INTO User (username, password_hash, role) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    // Use execute() with an array of values
    $stmt->execute(['ali', '1233', 'Admin']);
    
    echo "All tables created successfully.";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>