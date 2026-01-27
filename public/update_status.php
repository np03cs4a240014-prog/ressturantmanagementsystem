<?php
include __DIR__ . '/../config/db.php';
session_start();

/* Only staff */
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'staff') {
    exit("Unauthorized access");
}

/* Validate order_id from POST */
if (!isset($_POST['order_id']) || empty($_POST['order_id'])) {
    exit("Invalid request");
}

$order_id = (int)$_POST['order_id'];

/* Update order status */
$stmt = $pdo->prepare("UPDATE orders SET status = 'Completed' WHERE id = ?");
$stmt->execute([$order_id]);

/* Redirect back to vieworders.php */
header("Location: vieworder.php");
exit;
