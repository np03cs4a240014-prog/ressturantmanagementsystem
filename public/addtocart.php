<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

// CSRF check
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF validation failed");
}

$user_id = $_SESSION['user_id'];
$menu_id = (int)$_POST['item_id'];

// Optional validation: check menu exists
$stmt = $pdo->prepare("SELECT id FROM menu WHERE id=?");
$stmt->execute([$menu_id]);
if (!$stmt->fetch()) die("Invalid menu item");

// Check if item already exists in database cart
$stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id=? AND menu_id=?");
$stmt->execute([$user_id, $menu_id]);
$item = $stmt->fetch();

if ($item) {
    // Update quantity
    $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id=?")
        ->execute([$item['id']]);
} else {
    // Insert new item
    $pdo->prepare("INSERT INTO cart (user_id, menu_id, quantity) VALUES (?, ?, 1)")
        ->execute([$user_id, $menu_id]);
}

header("Location: menu.php");
exit;