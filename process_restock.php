<?php
require 'db_connection.php';

$product_id = $_POST['product_id'];
$supplier_id = $_POST['supplier_id'];
$quantity = $_POST['quantity'];

$stmt = $pdo->prepare("SELECT price FROM Product WHERE Product_id = ?");
$stmt->execute([$product_id]);
$price = $stmt->fetchColumn();
$total_cost = $price * $quantity;

$store_account = 6; // store owner
$pdo->beginTransaction();

$pdo->prepare("INSERT INTO Restock (request_date) VALUES (NOW())")->execute();
$request_id = $pdo->lastInsertId();

$pdo->prepare("INSERT INTO RestockRequest (ProductID, SupplierID) VALUES (?, ?)")
    ->execute([$product_id, $supplier_id]);

$pdo->prepare("UPDATE Product SET Stock = Stock + ? WHERE Product_id = ?")
    ->execute([$quantity, $product_id]);

$pdo->prepare("UPDATE Bank_Accounts SET balance = balance - ? WHERE account_id = ?")
    ->execute([$total_cost, $store_account]);

$pdo->prepare("INSERT INTO Bank_records (account_id, request_id, amount, payment_method, transaction_status)
               VALUES (?, ?, ?, 'bank_transfer', 'completed')")
    ->execute([$store_account, $request_id, $total_cost]);

$pdo->commit();

header("Location: restock_success.php");
?>
