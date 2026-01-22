<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF validation failed");
}

$item_id = (int)$_POST['item_id'];

$_SESSION['cart'][$item_id] = ($_SESSION['cart'][$item_id] ?? 0) + 1;

header("Location: menu.php");
exit;
