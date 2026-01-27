<?php
include __DIR__ . '/../config/db.php';
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
body{
    background-image: url("../images/backgroundimage.jpg");
    color:#E5EDF0;
    font-family:Times New Roman;
    background-size: cover;      /* 👈 key property */
    background-position: center;
    background-attachment: fixed;
}
.box{
    align-items:center;
    background:#404E3B;
    padding:20px;
    max-width:500px;
    margin-top:200px;
    margin-left:500px;
    border-radius:8px;
}
a{
    color:#E5EDF0;
    text-decoration:none;
    display:block;
    margin:10px 0;
}
.goto{
    margin-right:-10px;
    margin-left:-10px;
    margin-top:-10px;
    background-color:#E5EDF0;
    display:flex;
}

.goto a{
    margin:5px;
    padding:15px;
    background-color:#404E3B;
    color:white;
    border-radius:6px;
}
.goto a:hover{
    background-color:#7B9669;
    color:#404E3B;
    border-radius:6px;
}
h1{
    text-align: center;
    margin-top:-20px;
    margin-right:-20px;
    margin-left:-20px;
    padding:30px;
    background-color:#404E3B;
    color:#E5EDF0;
    margin-bottom:0px;
}
</style>
</head>
<body>
<h1>Let us make u happy with food today </h1>
<div class="goto">
    <a href="menu.php"> View Menu</a>
    <a href="cartview.php">View Cart</a>
    <a href="logoutcustomer.php"> Logout</a>
</div>  
<div class="box">

    <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
    <p><strong>Joined:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
</div>
  

</body>
</html>
