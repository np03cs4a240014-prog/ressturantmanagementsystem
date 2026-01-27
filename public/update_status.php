<?php
include __DIR__ . '/../config/db.php';
session_start();

/* Only staff */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    exit("Unauthorized access");
}

if (!isset($_POST['order_id'])) {
    exit("Invalid request");
}

$order_id = (int)$_POST['order_id'];

$stmt = $pdo->prepare("
    UPDATE orders
    SET status = 'Completed'
    WHERE id = ?
");
$stmt->execute([$order_id]);

header("Location: orders.php");
exit;