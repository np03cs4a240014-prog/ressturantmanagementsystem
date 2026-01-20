<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

$stmt = $pdo->prepare("SELECT name, phone, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>
<style>
body{background:#404E3B;color:#E5EDF0;font-family:Times New Roman;}
.box{background:#2f3b2a;padding:20px;margin:30px;border-radius:8px;}
a{color:#E5EDF0;text-decoration:none;display:block;margin:10px 0;}
</style>
</head>
<body>

<div class="box">
    <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
    <p><strong>Joined:</strong> <?= htmlspecialchars($user['created_at']) ?></p>

    <a href="menu.php"> View Menu</a>
    <a href="cartview.php">View Cart</a>
    <a href="logout.php"> Logout</a>
</div>

</body>
</html>
