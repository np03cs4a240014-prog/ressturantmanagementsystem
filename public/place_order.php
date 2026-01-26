<?php
include __DIR__ . '/../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) die("Login required");

if (!isset($_POST['cart_id'], $_POST['quantity'])) die("Invalid request");

$cart_id = (int)$_POST['cart_id'];
$quantity = max(1, (int)$_POST['quantity']);
$user_id = $_SESSION['user_id'];

// Verify that the cart item belongs to the logged-in user
$stmt = $pdo->prepare("SELECT id FROM cart WHERE id=? AND user_id=?");
$stmt->execute([$cart_id, $user_id]);
if (!$stmt->fetch()) {
    die("Unauthorized operation");
}

// Update quantity
$update = $pdo->prepare("UPDATE cart SET quantity=? WHERE id=?");
if ($update->execute([$quantity, $cart_id])) {
    $_SESSION['message'] = "Cart updated successfully!";
} else {
    $_SESSION['message'] = "Failed to update cart.";
}

header("Location: cartview.php");
exit;