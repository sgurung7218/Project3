<?php
session_start();
require 'db_connection.php';

// Default to customer 1 if not set
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['customer_id'] = 1;
}

$stmt = $pdo->query("SELECT * FROM Product");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// Fetch customers
$stmt = $pdo->query("SELECT CustomerID, Name FROM Customer");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle customer selection
if (isset($_POST['selected_customer'])) {
    $_SESSION['customer_id'] = $_POST['selected_customer'];
}
$currentCustomer = $_SESSION['customer_id'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Store</title>
    <style>
        .product-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px;
            width: 220px;
            text-align: center;
            float: left;
        }
        #cart-summary {
            clear: both;
            margin-top: 20px;
        }
    </style>
    <script>
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId
            }).then(() => updateCart());
        }

        function removeFromCart(productId) {
            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId
            }).then(() => updateCart());
        }

        function updateCart() {
            fetch('cart_summary.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('cart-summary').innerHTML = html;
                });
        }

        window.onload = updateCart;
    </script>
</head>
<body>
    <form method="POST" style="margin-bottom: 20px;">
    <label for="selected_customer">Select Customer:</label>
    <select name="selected_customer" id="selected_customer" onchange="this.form.submit()">
        <option value="">-- Select --</option>
        <?php foreach ($customers as $customer): ?>
            <option value="<?= $customer['CustomerID'] ?>"
                <?= ($currentCustomer == $customer['CustomerID']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($customer['Name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<?php if ($currentCustomer): ?>
    <p><strong>Shopping as:</strong>
        <?= htmlspecialchars($customers[array_search($currentCustomer, array_column($customers, 'CustomerID'))]['Name']) ?>
    </p>
<?php endif; ?>
    <h2>Products</h2>
    <div>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h4><?= htmlspecialchars($product['Name']) ?></h4>
                <p><?= htmlspecialchars($product['Description']) ?></p>
                <p><strong>$<?= number_format($product['price'], 2) ?></strong></p>
                <p>Stock: <?= $product['Stock'] ?></p>
                <button onclick="addToCart(<?= $product['Product_id'] ?>)">Add to Cart</button>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="cart-summary"></div>
</body>
</html>

