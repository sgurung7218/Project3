<?php
session_start();
require 'db_connection.php';

$customer_id = $_SESSION['customer_id'];
$cart = $_SESSION['cart'] ?? [];

if (!$cart) {
    die("Cart is empty.");
}

// Start Transaction
$pdo->beginTransaction();

try {
    $pdo->prepare("INSERT INTO Orders (customer_id) VALUES (?)")->execute([$customer_id]);
    $order_id = $pdo->lastInsertId();

    $total = 0;
    foreach ($cart as $pid => $qty) {
        $stmt = $pdo->prepare("SELECT price, Stock FROM Product WHERE Product_id = ?");
        $stmt->execute([$pid]);
        $product = $stmt->fetch();

        if ($product['Stock'] < $qty) throw new Exception("Insufficient stock.");

        $pdo->prepare("INSERT INTO Contain (order_id, product_id, Quantity) VALUES (?, ?, ?)")
            ->execute([$order_id, $pid, $qty]);

        $pdo->prepare("UPDATE Product SET Stock = Stock - ? WHERE Product_id = ?")
            ->execute([$qty, $pid]);

        $total += $product['price'] * $qty;
    }

    // Bank transactions
    $store_account_id = 6; // Example: Store account
    $customer_account_id = $customer_id;

    $pdo->prepare("UPDATE Bank_Accounts SET balance = balance - ? WHERE account_id = ?")
        ->execute([$total, $customer_account_id]);

    $pdo->prepare("UPDATE Bank_Accounts SET balance = balance + ? WHERE account_id = ?")
        ->execute([$total, $store_account_id]);

    $pdo->prepare("INSERT INTO Bank_records (account_id, order_id, amount, payment_method, transaction_status) 
                   VALUES (?, ?, ?, 'bank_transfer', 'completed')")
        ->execute([$customer_account_id, $order_id, -$total]);

    $pdo->prepare("INSERT INTO Bank_records (account_id, order_id, amount, payment_method, transaction_status) 
                   VALUES (?, ?, ?, 'bank_transfer', 'completed')")
        ->execute([$store_account_id, $order_id, $total]);

    $pdo->commit();
    $_SESSION['cart'] = [];

    echo "<h2>Receipt</h2>
          <p>Customer ID: $customer_id</p>
          <p>Order ID: $order_id</p>
          <p>Total Paid: $" . number_format($total, 2) . "</p>
          <a href='project3.php'>Return to store</a>";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Checkout failed: " . $e->getMessage();
}
?>

