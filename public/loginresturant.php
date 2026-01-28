<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../config/db.php';
session_start();

$error = "";

/* ---------------- CSRF TOKEN GENERATION ---------------- */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* ---------------- FORM SUBMISSION ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF validation
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die("Invalid CSRF token");
    }

    $name = trim($_POST['name']);
    $password = $_POST['password'];

    // Fetch user with role 'Staff' (case-insensitive)
    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE name = ? AND LOWER(role) = 'staff'"
    );
    $stmt->execute([$name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        // Successful login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_name'] = $user['name'];

        // Regenerate session ID for security
        session_regenerate_id(true);

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
    <link rel="stylesheet" type="text/css" href="../assets/style.css">
</head>
<body>

<div class="topic">
    <h2>Staff Login</h2>
</div>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="Register">
    <h2>Log In</h2>

    <form method="POST" action="">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <input type="text" name="name"
               placeholder="Enter your account name"
               autocomplete="username" required>

        <input type="password" name="password"
               placeholder="Enter your account password"
               autocomplete="current-password" required>

        <button type="submit">Log In</button>
    </form>
</div>

</body>
</html>