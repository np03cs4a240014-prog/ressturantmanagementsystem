<?php
include __DIR__ . '/../config/db.php';

session_start();

if (!isset($_SESSION['user_id'])) exit;

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q) {
    $stmt = $pdo->prepare(
        "SELECT * FROM menu WHERE name LIKE :q OR description LIKE :q"
    );
    $stmt->execute(['q' => "%$q%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM menu");
}

header('Content-Type: application/json');
echo json_encode($stmt->fetchAll());
?>
