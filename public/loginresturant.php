<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../config/db.php';
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $password = $_POST['password'];

    // Fetch user with role 'Staff' (case-insensitive)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ? AND LOWER(role) = 'staff'");
    $stmt->execute([$name]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // 'Staff'
        $_SESSION['user_name'] = $user['name'];

        header("Location: resturantdashboard.php");
        exit;
    } else {
        $error = "Invalid login credentials or not a staff";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - Restaurant Management</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .error { color: red; margin-bottom: 10px; }
        input { padding: 10px; margin-bottom: 10px; width: 100%; }
        input[type=submit] { cursor: pointer; background-color:#404E3B; color:white; border:none; }
    </style>
</head>
<body>
    <h1>Staff Login</h1>

    <?php if($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="name" placeholder="Enter your account name" autocomplete="username" required>
        <input type="password" name="password" placeholder="Enter your account password" autocomplete="current-password" required>
        <input type="submit" name="LogIn" value="Log In as Staff">
    </form>
</body>
</html>