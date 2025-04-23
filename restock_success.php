<?php
// restock_success.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Restock Successful</title>
  <style>
    body {
      background-color: #f0f4f8;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .card {
      background: #ffffff;
      padding: 40px 50px;
      border-radius: 16px;
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
      text-align: center;
      max-width: 400px;
      animation: fadeIn 0.8s ease-in-out;
    }

    .checkmark-circle {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #4CAF50;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
    }

    .checkmark {
      color: white;
      font-size: 40px;
    }

    h2 {
      margin-bottom: 10px;
      color: #333;
    }

    p {
      font-size: 16px;
      color: #666;
    }

    a.button {
      display: inline-block;
      margin-top: 25px;
      background-color: #4CAF50;
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      border-radius: 8px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    a.button:hover {
      background-color: #45a049;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="checkmark-circle">
      <span class="checkmark">✔</span>
    </div>
    <h2>Restock Successful</h2>
    <p>The restock request has been processed and the inventory has been updated successfully.</p>
    <a href="restock.php" class="button">← Back to Restock Page</a>
  </div>
</body>
</html>
