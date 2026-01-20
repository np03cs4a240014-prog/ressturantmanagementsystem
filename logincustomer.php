<?php
require 'db.php';
session_start();

if (isset($_POST['login'])) {

    $name = $_POST['name'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$name]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: userdashboard.php");
        exit;
    } else {
        $error = "Invalid login credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<link rel="stylesheet" type="text/css" href="CSS/style.css">
<body>
<div class="topic">
	<h2>Resutrant Management System</h2>
</div>
	<div class="Register">
		<h2>Log In</h2>
		<form method="POST" action="">
			<input type="text" name="name" placeholder="Enter your name" required><br>
			
			<input type="password" name="password" placeholder="Enter your password" required><br><br>
			<button type="submit" name="login">Log In</button>
		</form>
</div>


</body>
<footer>
    &copy; 2026 Restaurant Management System
</footer>
</html>