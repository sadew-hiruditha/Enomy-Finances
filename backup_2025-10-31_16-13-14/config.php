<?php
// Database connection configuration
$host = 'localhost';
$db   = 'enomy_finances';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // In production, you would log this error instead of displaying it
    die('Database connection failed: ' . $e->getMessage());
}

// Start a session for authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}