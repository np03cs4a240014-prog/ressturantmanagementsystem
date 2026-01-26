
<?php
include __DIR__ . '/../config/db.php';
session_start();


if (!isset($_SESSION['user_id'])) die("Login required");

if (!isset($_POST['cart_id'], $_POST['quantity'])) die("Invalid request");

$cart_id = (int)$_POST['cart_id'];
$quantity = max(1, (int)$_POST['quantity']);

$pdo->prepare("UPDATE cart SET quantity=? WHERE id=?")->execute([$quantity, $cart_id]);

$_SESSION['message'] = "Cart updated successfully!";
header("Location: cartview.php");
exit;?>
