<?php
session_start();
require 'db_connection.php';

$customer_id = $_SESSION['customer_id'];
$cart = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout Receipt</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2e8b1b3c1.js" crossorigin="anonymous"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 0;
    }

    .receipt-container {
      max-width: 800px;
      margin: 50px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      padding: 40px;
      animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .receipt-header {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      color: #2ecc71;
    }

    .receipt-header h2 {
      margin: 0;
      font-size: 28px;
    }

    .info {
      margin: 30px 0 20px;
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid #e0e0e0;
      padding-bottom: 10px;
    }

    .info div {
      font-size: 16px;
    }

    .items-list {
      margin: 20px 0;
    }

    .item {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px dashed #e0e0e0;
    }

    .item:last-child {
      border-bottom: none;
    }

    .item-name {
      font-weight: 600;
    }

    .total {
      text-align: right;
      margin-top: 20px;
      font-size: 22px;
      font-weight: bold;
      color: #333;
    }

    .back-btn {
      display: inline-block;
      margin-top: 30px;
      padding: 12px 24px;
      background-color: #3498db;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .back-btn:hover {
      background-color: #2980b9;
    }

    .error {
      color: #e74c3c;
      text-align: center;
      font-size: 20px;
    }
  </style>
</head>
<body>
  <div class="receipt-container">
    <?php
    if (!$cart) {
        echo "<p class='error'>🛒 Your cart is empty. <a class='back-btn' href='project3.php'>Return to store</a></p>";
        exit;
    }

    $pdo->beginTransaction();

    try {
        $pdo->prepare("INSERT INTO Orders (customer_id) VALUES (?)")->execute([$customer_id]);
        $order_id = $pdo->lastInsertId();

        $total = 0;
        $purchased_items = [];

        foreach ($cart as $pid => $qty) {
            $stmt = $pdo->prepare("SELECT name, price, Stock FROM Product WHERE Product_id = ?");
            $stmt->execute([$pid]);
            $product = $stmt->fetch();

            if ($product['Stock'] < $qty) throw new Exception("Insufficient stock for product: {$product['name']}");

            $pdo->prepare("INSERT INTO Contain (order_id, product_id, Quantity) VALUES (?, ?, ?)")
                ->execute([$order_id, $pid, $qty]);

            $pdo->prepare("UPDATE Product SET Stock = Stock - ? WHERE Product_id = ?")
                ->execute([$qty, $pid]);

            $subtotal = $product['price'] * $qty;
            $total += $subtotal;

            $purchased_items[] = [
                'name' => $product['name'],
                'qty' => $qty,
                'subtotal' => $subtotal
            ];
        }

        $store_account_id = 6;
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

        echo "<div class='receipt-header'><i class='fas fa-check-circle fa-2x'></i><h2>Order Confirmed</h2></div>";
        echo "<div class='info'>
                <div><strong>Customer ID:</strong> $customer_id</div>
                <div><strong>Order ID:</strong> $order_id</div>
              </div>";
        echo "<div class='items-list'>";
        foreach ($purchased_items as $item) {
            echo "<div class='item'>
                    <div class='item-name'>{$item['name']} × {$item['qty']}</div>
                    <div>$" . number_format($item['subtotal'], 2) . "</div>
                  </div>";
        }
        echo "</div>";
        echo "<div class='total'>Total Paid: $" . number_format($total, 2) . "</div>";
        echo "<a class='back-btn' href='project3.php'><i class='fas fa-arrow-left'></i> Return to Store</a>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p class='error'><i class='fas fa-exclamation-triangle'></i> Checkout failed: " . $e->getMessage() . "</p>
              <a class='back-btn' href='project3.php'>Try Again</a>";
    }
    ?>
  </div>
</body>
</html>