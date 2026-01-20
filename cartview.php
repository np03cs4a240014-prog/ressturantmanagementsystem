<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

/* CSRF token */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

/* Initialize cart if not set */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* Handle update quantity */
if (isset($_POST['update_qty'], $_POST['item_id'], $_POST['csrf_token'])) {
    if ($_POST['csrf_token'] !== $csrf) die("CSRF validation failed");

    $item_id = (int)$_POST['item_id'];
    $quantity = max(1, (int)$_POST['quantity']); // minimum 1

    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] = $quantity;
    }

    header("Location: cart.php");
    exit;
}

/* Handle delete item */
if (isset($_POST['delete_item'], $_POST['item_id'], $_POST['csrf_token'])) {
    if ($_POST['csrf_token'] !== $csrf) die("CSRF validation failed");

    $item_id = (int)$_POST['item_id'];
    unset($_SESSION['cart'][$item_id]);

    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'];
$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
<title>Your Cart</title>
<style>
body{background:#404E3B;color:#E5EDF0;font-family:Times New Roman;}
.box{background:#2f3b2a;padding:20px;margin:30px;border-radius:8px;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #E5EDF0;padding:10px;}
th{background:#E5EDF0;color:#404E3B;}
input[type=number]{width:50px;}
button{padding:5px 8px;margin:2px;}
a{color:#E5EDF0;text-decoration:none;}
</style>
</head>
<body>

<div class="box">
<h2>Your Cart</h2>

<?php if (!$cart): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
<table>
<tr>
<th>Food</th>
<th>Quantity</th>
<th>Price</th>
<th>Subtotal</th>
<th>Actions</th>
</tr>

<?php foreach ($cart as $id => $qty):
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id=?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    $subtotal = $item['price'] * $qty;
    $total += $subtotal;
?>
<tr>
<td><?= htmlspecialchars($item['name']) ?></td>
<td>
    <!-- Edit Quantity -->
    <form method="post" action="edit.php" style="display:inline-block">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <input type="hidden" name="item_id" value="<?= $id ?>">
        <input type="number" name="quantity" value="<?= $qty ?>" min="1">
        <button type="submit">Update</button>
    </form>
</td>
<td>
    <!-- Delete Item -->
    <form method="post" action="delete.php" style="display:inline-block">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <input type="hidden" name="item_id" value="<?= $id ?>">
        <button type="submit">Delete</button>
    </form>
</td>

</tr>
<?php endforeach; ?>

<tr>
<td colspan="3"><strong>Total</strong></td>
<td colspan="2"><strong><?= number_format($total,2) ?></strong></td>
</tr>
</table>

<form method="post" action="place_order.php">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <button type="submit">Place Order</button>
</form>
<?php endif; ?>

<a href="menu.php">⬅ Back to Menu</a>
</div>

</body>
</html>
