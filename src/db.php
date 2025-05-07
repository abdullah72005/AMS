<?php

$env = parse_ini_file(__DIR__.'/../.env');
$host = $env['host'];
$dbname = $env['dbname'];
$username = $env['username'];
$password = $env['password'];   
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    return $pdo;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>