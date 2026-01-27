<?php
include __DIR__ . '/../config/db.php';
session_start();

$error = "";

if (isset($_POST['login'])) {

    $name = trim($_POST['name']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ? AND LOWER(role) = 'customer'");
    $stmt->execute([$name]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role']      = $user['role']; // 

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
    <title>Customer Login</title>
    <link rel="stylesheet" type="text/css" href="../assets/style.css">
</head>
<body>

<div class="topic">
    <h2>Restaurant Management System</h2>
</div>

<div class="Register">
    <h2>Log In</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Enter your name" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit" name="login">Log In</button>
    </form>
</div>

<footer>
    &copy; 2026 Restaurant Management System
</footer>

</body>
</html>