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
    <title>Modern Online Store</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0 20px;
            color: #333;
        }

        header {
            background-color: #0077cc;
            color: white;
            padding: 20px;
            text-align: center;
        }

        h2 {
            margin-top: 40px;
            text-align: center;
            color: #0077cc;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            text-align: center;
        }

        select {
            padding: 8px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .customer-info {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            padding: 20px;
            text-align: center;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card h4 {
            margin-bottom: 10px;
            color: #333;
        }

        .product-card p {
            margin: 5px 0;
        }

        .product-card button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .product-image 
        {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .product-card button:hover {
            background-color: #218838;
        }

        #cart-summary {
            margin-top: 40px;
            padding: 20px;
            background-color: #e9f7ef;
            border-left: 5px solid #28a745;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .navbar {
            background-color: #d62828; /* deep red */
            padding: 10px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: white;
            margin: 0;
            font-size: 24px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        .nav-links li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.2s ease-in-out;
        }

        .nav-links li a:hover {
            color: #ffc300; /* yellow highlight on hover */
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
    <header>
    <nav class="navbar">
    <div class="navbar-container">
        <h1 class="logo">My Store</h1>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">Products</a></li>
            <li><a href="#">Cart</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </div>
</nav>
    </header>

    <form method="POST">
        <label for="selected_customer">Select Customer:</label>
        <select name="selected_customer" id="selected_customer" onchange="this.form.submit()">
            <option value="">-- Select --</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['CustomerID'] ?>" <?= ($currentCustomer == $customer['CustomerID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($customer['Name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($currentCustomer): ?>
        <div class="customer-info">
            Shopping as: <?= htmlspecialchars($customers[array_search($currentCustomer, array_column($customers, 'CustomerID'))]['Name']) ?>
        </div>
    <?php endif; ?>

    <h2>Available Products</h2>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
            <?php
                $imageName = strtolower(str_replace(' ', '-', $product['Name'])) . '.jpeg';
                $imagePath = "images/$imageName";
            ?>
                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($product['Name']) ?>" class="product-image">
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

