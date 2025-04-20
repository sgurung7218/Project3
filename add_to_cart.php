<?php
session_start();
require 'db_connection.php';

$product_id = $_POST['product_id'] ?? null;
if (!$product_id) exit;

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$stmt = $pdo->prepare("SELECT Stock FROM Product WHERE Product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if ($product && $product['Stock'] > ($_SESSION['cart'][$product_id] ?? 0)) {
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;
}
?>

