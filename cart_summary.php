<?php
session_start();
require 'db_connection.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f8;
      padding: 40px;
      color: #333;
    }

    .cart-container {
      background: #fff;
      max-width: 600px;
      margin: auto;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    h3 {
      margin-bottom: 20px;
      text-align: center;
    }

    ul {
      list-style: none;
      padding: 0;
    }

    li {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }

    .remove-btn {
      background: #ff4d4f;
      border: none;
      color: white;
      padding: 5px 12px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 0.9em;
      transition: background 0.3s;
    }

    .remove-btn:hover {
      background: #e60000;
    }

    .total {
      font-size: 1.2em;
      margin-top: 20px;
      text-align: right;
    }

    .checkout-btn {
      background: #28a745;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      cursor: pointer;
      margin-top: 20px;
      width: 100%;
      transition: background 0.3s ease;
    }

    .checkout-btn:hover {
      background: #218838;
    }
  </style>
  <script>
    function removeFromCart(productId) {
      fetch(`remove_from_cart.php?product_id=${productId}`)
        .then(() => location.reload());
    }
  </script>
</head>
<body>

<div class="cart-container">
  <h3>Your Cart</h3>
  <ul>
    <?php
    foreach ($cart as $pid => $qty) {
        $stmt = $pdo->prepare("SELECT Name, price FROM Product WHERE Product_id = ?");
        $stmt->execute([$pid]);
        $product = $stmt->fetch();

        if ($product) {
            $subtotal = $product['price'] * $qty;
            $total += $subtotal;
            echo "<li>
                    <span>{$product['Name']} x $qty = $" . number_format($subtotal, 2) . "</span>
                    <button class='remove-btn' onclick='removeFromCart($pid)'>Remove</button>
                  </li>";
        }
    }
    ?>
  </ul>

  <p class="total"><strong>Total: $<?= number_format($total, 2) ?></strong></p>

  <?php if (!empty($cart)): ?>
    <form action="checkout.php" method="POST">
      <input type="submit" value="Proceed to Checkout" class="checkout-btn">
    </form>
  <?php endif; ?>
</div>

</body>
</html>
