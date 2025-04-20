<?php
session_start();
$product_id = $_POST['product_id'] ?? null;
if (!$product_id || !isset($_SESSION['cart'][$product_id])) exit;

if ($_SESSION['cart'][$product_id] > 1) {
    $_SESSION['cart'][$product_id]--;
} else {
    unset($_SESSION['cart'][$product_id]);
}
?>

