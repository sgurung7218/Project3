<?php
$dsn = 'mysql:host=localhost;dbname=store;charset=utf8mb4';
$username = 'root';
$password = 'password';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

