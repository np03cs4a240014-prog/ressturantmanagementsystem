<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) die("Login required");
if (!isset($_POST['cart_id'])) die("Invalid request");

$cart_id = (int)$_POST['cart_id'];

$pdo->prepare("DELETE FROM cart WHERE id=?")->execute([$cart_id]);

$_SESSION['message'] = "Item removed from cart!";
header("Location: cartview.php");
exit;