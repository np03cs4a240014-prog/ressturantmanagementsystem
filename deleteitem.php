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

/* Validate item */
if (!isset($_POST['item_id'])) {
    die("Invalid request");
}

$item_id = (int)$_POST['item_id'];

if (isset($_SESSION['cart'][$item_id])) {
    unset($_SESSION['cart'][$item_id]);
}

/* Redirect back to cart */
header("Location: cart.php");
exit;
