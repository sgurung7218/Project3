<?php
require 'db_connection.php';
$products = $pdo->query("SELECT Product_id, Name FROM Product")->fetchAll();
$suppliers = $pdo->query("SELECT supplier_id, name FROM Suppliers")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Restock Request</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 40px;
      display: flex;
      justify-content: center;
    }

    .container {
      background: #fff;
      border-radius: 10px;
      padding: 30px 40px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      max-width: 500px;
      width: 100%;
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
    }

    select, input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    .cost-display {
      margin-top: 20px;
      font-size: 16px;
      font-weight: bold;
      color: #333;
    }

    .btn {
      display: inline-block;
      margin-top: 25px;
      background-color: #4CAF50;
      color: white;
      padding: 12px 18px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #45a049;
    }
  </style>

  <script>
    async function updateCost() {
      const productId = document.getElementById('product_id').value;
      const quantity = document.getElementById('quantity').value;
      if (!productId || !quantity) return;

      const res = await fetch(`get_product_price.php?product_id=${productId}`);
      const data = await res.json();
      const cost = (data.price * quantity).toFixed(2);

      document.getElementById('cost_display').innerText = '$' + cost;
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>📦 Restock Request</h2>
    <form method="POST" action="process_restock.php">
      
      <label for="product_id">Product</label>
      <select name="product_id" id="product_id" onchange="updateCost()">
        <option value="">-- Select Product --</option>
        <?php foreach ($products as $product): ?>
          <option value="<?= $product['Product_id'] ?>"><?= $product['Name'] ?></option>
        <?php endforeach; ?>
      </select>

      <label for="supplier_id">Supplier</label>
      <select name="supplier_id" id="supplier_id">
        <option value="">-- Select Supplier --</option>
        <?php foreach ($suppliers as $supplier): ?>
          <option value="<?= $supplier['supplier_id'] ?>"><?= $supplier['name'] ?></option>
        <?php endforeach; ?>
      </select>

      <label for="quantity">Quantity</label>
      <input type="number" name="quantity" id="quantity" min="1" onchange="updateCost()">

      <p class="cost-display">Total Cost: <span id="cost_display">$0.00</span></p>

      <button type="submit" class="btn">Checkout Restock</button>
    </form>
  </div>
</body>
</html>
