<?php
include __DIR__ . '/../config/db.php';

session_start();

// Only staff/admin can add menu items
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: loginresturant.php");
    exit;
}

$error = "";
$success = ""; // new variable for success message

// Handle form submission
if (isset($_POST['add_menu'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];

    if (empty($name) || empty($price)) {
        $error = "Name and Price are required!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO menu (name, description, price) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $price]);

        $success = " Food item '$name' has been added successfully!";
    }
}
?>
<link rel="stylesheet" type="text/css" href="../assets/style.css">
<div class="topic">
<header><h1> Add food to today's menu</h1></header></div>
</div>
<div class="Register">
    <?php if($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>
<form method="POST" action="">
    <input type="text" name="name" placeholder="Food Name" required><br>
    <input type="text" name="description" placeholder="Description"required><br>
    <input type="number" step="0.01" name="price" placeholder="Price" required><br>

    <button type="submit" name="add_menu">Add Food</button>
    <a href="displaymenu.php" class="displaymenu">Display Food Items</a>
</form>



</div>
?>




