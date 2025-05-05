<?php
$host = 'localhost';
$dbname = 'AMS';
$user = 'root';
$pass = 'root';

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>