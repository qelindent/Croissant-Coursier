<?php
session_start();

$product_id = $_POST['product_id'] ?? null;
$quantite = isset($_POST['quantite']) ? max(1, (int)$_POST['quantite']) : 1;

if ($product_id) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantite;
    } else {
        $_SESSION['cart'][$product_id] += $quantite;
    }
    $_SESSION['last_added'] = $product_id;
}

header('Location: produits.php');
exit;