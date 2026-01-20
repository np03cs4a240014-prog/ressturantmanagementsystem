<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF validation failed");
}

$item_id = (int)$_GET['item_id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][$item_id] = ($_SESSION['cart'][$item_id] ?? 0) + 1;

header("Location: menu.php");
exit;
