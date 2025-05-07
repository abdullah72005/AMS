<?php

$env = parse_ini_file(__DIR__.'/../.env');
$host = $env['host'];
$dbname = $env['dbname'];
$Username = $env['username'];
$Password = $env['password'];   
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $Username, $Password);
    return $pdo;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>