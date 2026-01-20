<?php
require 'db.php';
session_start();

/* Protect dashboard */
if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

/* Get logged-in user ID */
$id = $_SESSION['user_id'];

/* Fetch user details */
$stmt = $pdo->prepare("SELECT name, phone, created_at FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>

    <style>
        body{
            background-color:#404E3B;
            color:#E5EDF0;
            font-family:Times New Roman;
        }
        h1{
            margin-top:-20px;
            margin-left:-20px;
            margin-right:-20px;
            padding:25px;
            background-color:#E5EDF0;
            color:#404E3B;
            text-align:center;
        }
        .box{
            background:#2f3b2a;
            padding:20px;
            margin:30px;
            border-radius:8px;
        }
    </style>
</head>

<body>

<div class="topic">
    <h1>Welcome to Restaurant Management System</h1>
</div>

<div class="box">
    <h2>Hello, <?= htmlspecialchars($user['name']) ?></h2>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
    <p><strong>Joined on:</strong> <?= $user['created_at'] ?></p>
</div>

<div class="box">
    <h2>On today’s menu</h2>
    <p>Menu items will be displayed here.</p>
</div>

<div class="box">
    <a href="logout.php" style="color:#E5EDF0;">Logout</a>
</div>

</body>
</html>
