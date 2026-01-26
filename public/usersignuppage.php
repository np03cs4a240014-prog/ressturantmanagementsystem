<?php
include __DIR__ . '/../config/db.php';
session_start();

if (isset($_POST['signup'])) {

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check password match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

     $stmt = $pdo->prepare(
    "INSERT INTO users (name, password, phone, role) VALUES (?, ?, ?, 'Customer')"
);
$stmt->execute([$name, $hashed_password, $phone]);
        header("Location: logincustomer.php");
        exit;
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
<body>
<link rel="stylesheet" type="text/css" href="../assets/style.css">
<body>
	<div class="topic">
	<h2><center>Resutrant Management System</center></h2>
</div>
	<div class="Register">
		<h2>Register</h2>
		<form method="POST" action="">
			<input type="text" name="name" placeholder="Enter your name" required><br>
			<input type="password" name="password" placeholder="Enter your password" required><br>
			<input type="password" name="confirm_password" placeholder="Enter your confirmed password" required><br>
			<input type="tel" name="phone" placeholder="Phone" pattern="[0-9]{10}" title="Enter your number"><br><br>
			<button type="submit" name="signup">Sign up</button>
		</form>
</div>



<footer>
    &copy; 2026 Restaurant Management System
</footer>
</body>
</html>

