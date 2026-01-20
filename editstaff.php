<?php
include 'db.php';
session_start();

// Only staff/admin can edit menu
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: loginresturant.php");
    exit;
}

// Get menu item ID from query string
if (!isset($_GET['id'])) {
    header("Location: resturantdashboard.php");
    exit;
}

$id = $_GET['id'];
$error = "";

// Fetch the menu item
$stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
$stmt->execute([$id]);
$menu = $stmt->fetch();

if (!$menu) {
    die("Menu item not found.");
}

// Handle form submission
if (isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];

    if (empty($name) || empty($price)) {
        $error = "Name and Price are required!";
    } else {
        $stmt = $pdo->prepare("UPDATE menu SET name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $id]);

        header("Location: displaymenu.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Menu Item - Staff</title>
    <link rel="stylesheet" href="style.css">
    <style>.error { color:red; }</style>
</head>
<body>
<div class="topic">
    <h2>Edit Menu Item</h2>
</div>

<div class="Register">
    <?php if($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="name" placeholder="Item Name" value="<?= htmlspecialchars($menu['name']) ?>" required><br>
        <input type="text" name="description" placeholder="Description" value= "<?=htmlspecialchars($menu['description']) ?>"required><br>
        <input type="number" step="0.01" name="price" placeholder="Price" value="<?= $menu['price'] ?>" required><br><br>
        <button type="submit" name="update">Update Menu Item</button>
    </form>

    <br>
    <a href="displaymenu.php"> Back to Menu</a>
</div>
</body>
</html>
