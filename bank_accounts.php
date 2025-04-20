<?php
require 'db_connection.php';

$stmt = $pdo->query("SELECT * FROM Bank_Accounts");
$accounts = $stmt->fetchAll();
?>

<h2>Bank Accounts</h2>
<table border="1" cellpadding="10">
    <tr><th>ID</th><th>Account #</th><th>Type</th><th>Balance</th><th>Status</th></tr>
    <?php foreach ($accounts as $acc): ?>
        <tr>
            <td><?= $acc['account_id'] ?></td>
            <td><?= $acc['account_number'] ?></td>
            <td><?= $acc['account_type'] ?></td>
            <td>$<?= number_format($acc['balance'], 2) ?></td>
            <td><?= $acc['account_status'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

