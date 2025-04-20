// Restock Request Page
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Restock Request</title>
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
  <h2>Request Restock</h2>
  <form method="POST" action="process_restock.php">
    <label>Product:</label>
    <select name="product_id" id="product_id" onchange="updateCost()">
      <?php
      require 'db_connection.php';
      $products = $pdo->query("SELECT Product_id, Name FROM Product")->fetchAll();
      foreach ($products as $product) {
        echo "<option value='{$product['Product_id']}'>{$product['Name']}</option>";
      }
      ?>
    </select><br>

    <label>Supplier:</label>
    <select name="supplier_id">
      <?php
      $suppliers = $pdo->query("SELECT supplier_id, name FROM Suppliers")->fetchAll();
      foreach ($suppliers as $supplier) {
        echo "<option value='{$supplier['supplier_id']}'>{$supplier['name']}</option>";
      }
      ?>
    </select><br>

    <label>Quantity:</label>
    <input type="number" name="quantity" id="quantity" min="1" onchange="updateCost()"><br>

    <p>Cost: <span id="cost_display">$0.00</span></p>
    <input type="submit" value="Checkout Restock">
  </form>
</body>
</html>
