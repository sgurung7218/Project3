<?php
session_start();
require 'db_connection.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

echo "<h3>Your Cart</h3><ul>";

foreach ($cart as $pid => $qty) {
    $stmt = $pdo->prepare("SELECT Name, price FROM Product WHERE Product_id = ?");
    $stmt->execute([$pid]);
    $product = $stmt->fetch();

    if ($product) {
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
        echo "<li>{$product['Name']} x $qty = $" . number_format($subtotal, 2) . 
             " <button onclick='removeFromCart($pid)'>Remove</button></li>";
    }
}

echo "</ul><p><strong>Total: $" . number_format($total, 2) . "</strong></p>";

if (!empty($cart)) {
    echo '<form action="checkout.php" method="POST">
            <input type="submit" value="Proceed to Checkout" style="padding:10px;background:green;color:white;">
          </form>';
}
?>

