<?php
require 'db_connection.php';

$stmt = $pdo->query("SELECT * FROM Bank_Accounts");
$accounts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bank Accounts</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fc;
      margin: 0;
      padding: 40px;
    }

    h2 {
      color: #2c3e50;
      text-align: center;
      margin-bottom: 30px;
    }

    .table-container {
      max-width: 900px;
      margin: 0 auto;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 12px;
      overflow: hidden;
    }

    th, td {
      padding: 16px;
      text-align: left;
    }

    th {
      background-color: #2c3e50;
      color: #fff;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background-color: #f4f6f9;
    }

    tr:hover {
      background-color: #eaf1f8;
      transition: background-color 0.3s ease;
    }

    td {
      color: #333;
    }

    .status-active {
      color: #27ae60;
      font-weight: bold;
    }

    .status-inactive {
      color: #e74c3c;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h2>Bank Accounts</h2>
  <div class="table-container">
    <table>
      <tr>
        <th>ID</th>
        <th>Account #</th>
        <th>Type</th>
        <th>Balance</th>
        <th>Status</th>
      </tr>
      <?php foreach ($accounts as $acc): ?>
        <tr>
          <td><?= $acc['account_id'] ?></td>
          <td><?= $acc['account_number'] ?></td>
          <td><?= ucfirst($acc['account_type']) ?></td>
          <td>$<?= number_format($acc['balance'], 2) ?></td>
          <td class="<?= $acc['account_status'] === 'active' ? 'status-active' : 'status-inactive' ?>">
            <?= ucfirst($acc['account_status']) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</body>
</html>