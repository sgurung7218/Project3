<?php
require 'db_connection.php';

$product_id = $_GET['product_id'] ?? 0;
$stmt = $pdo->prepare("SELECT price FROM Product WHERE Product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();
echo json_encode(['price' => $product['price']]);
?>
