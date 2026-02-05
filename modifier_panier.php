<?php
session_start();
$id = $_POST['product_id'] ?? null;
$action = $_POST['action'] ?? null;

if ($id && isset($_SESSION['cart'][$id])) {
    if ($action === 'increment') {
        $_SESSION['cart'][$id]++;
    } elseif ($action === 'decrement') {
        $_SESSION['cart'][$id]--;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    } elseif ($action === 'remove') {
        unset($_SESSION['cart'][$id]);
    }
}
header('Location: panier.php');
exit;
