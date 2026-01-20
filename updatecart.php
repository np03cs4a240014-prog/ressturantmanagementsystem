<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

/* CSRF check */
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF validation failed");
}

/* Validate item and quantity */
if (!isset($_POST['item_id'], $_POST['quantity'])) {
    die("Invalid request");
}

$item_id = (int)$_POST['item_id'];
$quantity = max(1, (int)$_POST['quantity']); // minimum 1

if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id] = $quantity;
}

/* Redirect back to cart */
header("Location: cart.php");
exit;
