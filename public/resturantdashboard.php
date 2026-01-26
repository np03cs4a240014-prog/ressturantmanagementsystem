<?php
include __DIR__ . '/../config/db.php';
session_start();

// Staff access only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Staff') {
    header("Location: loginstaff.php");
    exit;
}

// Fetch all menu items
$menuItems = $pdo->query("SELECT * FROM menu ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Dashboard</title>
    <style>
        body {
            background-image: url("../images/stafffoodimage.jpg");
            font-family: Arial, sans-serif;
        }
        h1{
            padding:25px;
            background-color:#404E3B;
            color:#E5EDF0;
            text-align: center;
        }
        a {
            padding:10px;
            border-radius:3px;
            background-color:white;
            color:#404E3B;
            text-decoration:none;
            font-weight:bold;
            margin-right: 10px;
        }
        a:hover {
            background-color:#404E3B;
            color:white;
        }
        h2{
            margin-top: 50px;
            padding:30px;
            background-color: white;
            border-radius: 20px;
            text-align: center;
            color:#404E3B;
        }
        footer{
            background-color:#404E3B;
            color:#E6E6E6;
            padding:20px;
            text-align: center;
            position:fixed;
            width:100%;
            bottom:0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 30px;
        }
        th, td {
            border:1px solid #ccc;
            padding:10px;
        }
        th {
            background:#404E3B;
            color:white;
        }
    </style>
</head>
<body>

<h1>Restaurant Dashboard - Staff</h1>
<a href="addfoodstaff.php">Add New Menu Item</a>
<a href="logoutstaff.php">Logout</a>

<h2>Let's make the customers happy <br> with Good Food</h2>

<!-- Example Table of Menu Items -->
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
    </tr>
    <?php foreach($menuItems as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['id']) ?></td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= htmlspecialchars($item['price']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<footer>
    &copy; 2026 Restaurant Management System
</footer>
</body>
</html>