<?php
include 'db.php';
session_start(); // start session to track logged-in admin

$error = "";

// Check if login form submitted
if (isset($_POST['LogIn'])) {
    $name = trim($_POST['name']);
    $password = $_POST['password'];

    // Find the user with role 'admin'
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ? AND role = 'admin'");
    $stmt->execute([$name]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Login successful, set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_name'] = $user['name'];

        // Redirect to admin dashboard
        header("Location: resturantdashboard.php");
        exit;
    } else {
        $error = "Invalid login credentials or not an admin";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
    <style>.error { color:red; margin-bottom:10px; }</style>
</head>
<body>
<div class="topic">
    <h2>Restaurant Management System - Admin Login</h2>
</div>

<div class="Register">
    <h2><center>Log In</h2>

    <?php if($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="name" placeholder="Enter your account name" required><br>
        <input type="password" name="password" placeholder="Enter your account password" required><br><br>
        <button type="submit" name="LogIn">Log In as a staff</button>
    </form>
</div>

</body>
<footer>
    &copy; 2026 Restaurant Management System
</footer>
</html>
