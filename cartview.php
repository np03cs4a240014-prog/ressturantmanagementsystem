<?php
require 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// CSRF token for any form actions
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// Get cart items
$cart = $_SESSION['cart'];
$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart Items</title>
    <style>
        body {

            font-family:Times New Roman;
            background-image:url("images/stafffoodimage");
            color:white;
        }
        .cart-box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #2f3b2a;
            color: #fff;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #E5EDF0;
            text-align: center;
        }
        th {
            background: #E5EDF0;
            color: #404E3B;
        }
        button {
            padding: 5px 10px;
            cursor: pointer;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            color: #E5EDF0;
            text-decoration: none;
            background: #404E3B;
            padding: 8px 12px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="cart-box">
<h2>Your Cart</h2>

<?php if (empty($cart)): ?>
    <p>Your cart is empty.</p>
    <a href="menu.php">⬅ Back to Menu</a>
<?php else: ?>
<table>
<tr>
    <th>Food</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Subtotal</th>
</tr>

<?php
foreach ($cart as $item_id => $qty):
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id=?");
    $stmt->execute([$item_id]);
    $item = $stmt->fetch();
    if (!$item) continue;

    $subtotal = $item['price'] * $qty;
    $total += $subtotal;
?>
<tr>
    <td><?= htmlspecialchars($item['name']) ?></td>
    <td><?= $qty ?></td>
    <td><?= number_format($item['price'], 2) ?></td>
    <td><?= number_format($subtotal, 2) ?></td>
</tr>
<?php endforeach; ?>

<tr>
    <td colspan="3"><strong>Total</strong></td>
    <td><strong><?= number_format($total, 2) ?></strong></td>
</tr>
</table>

<a href="menu.php">⬅ Back to Menu</a>
<?php endif; ?>

</div>
</body>
</html>
