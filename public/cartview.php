<?php
include __DIR__ . '/../config/db.php';
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// CSRF token for forms
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// Fetch cart items from database
$stmt = $pdo->prepare("
    SELECT c.id AS cart_id, c.quantity, m.id AS menu_id, m.name, m.price
    FROM cart c
    JOIN menu m ON c.menu_id = m.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
            body {
            background-image: url("../images/backgroundimage.jpg");

            font-family: "Times New Roman", Times, serif;
            background-size: cover;  
            background-position: center;
            background-attachment: fixed;
        }   
        
        h1{
            padding:30px;
            margin-top:-10px;
            margin-right:-10px;
            margin-left:-10px;
            background-color:#404E3B;
            color:#E5EDF0;
            text-align:center;
        }
      table {
        width: 80%;
        margin: 30px auto;
        border-collapse: collapse;
        background-color:#404E3B;
        color:#ffffff;
        border-radius: 8px;
        overflow: hidden;
}
        th, td { 
            padding:10px;
            border:1px solid #fff;
            }

        th { 
            background:#404E3B;
            color:#E5EDF0;
            }
        a, button { 
            padding:6px 12px;
             border:none;
            border-radius:5px;
             cursor:pointer;
         }
        a{
         background:#404E3B; 
         color:#E5EDF0;
          text-decoration:none;
           margin:5px; 
       }
        a:hover {
         background:white; 
         color:#2f3b2a;
          }
        .btn {
         background:#2ecc71;
          color:white;
           }
        .btn:hover { 
            opacity:0.8;
             }
             .alert {
    width: 80%;
    margin: 15px auto;
    padding: 12px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
}

.alert.success {
    background-color: #2ecc71;
    color: white;
}
    </style>
</head>
<body>

<h1>Your Cart</h1>
<?php if (!empty($_SESSION['message'])): ?>
    <div class="alert success">
        <?= htmlspecialchars($_SESSION['message']) ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
    <a href="menu.php">Back to Menu</a>
<?php else: ?>
    <table>
        <tr>
            <th>Food</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($cartItems as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>
                <form method="POST" action="updatefood.php" style="display:inline;">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width:60px;">
                    <button type="submit" class="btn">Update</button>
                </form>
            </td>
            <td><?= number_format($item['price'],2) ?></td>
            <td><?= number_format($item['price'] * $item['quantity'],2) ?></td>
            <td>
                <form method="POST" action="deletefood.php" style="display:inline;"
      onsubmit="return confirm('Are you sure you want to remove this item from your cart?');">
    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <button type="submit" class="btn" style="background:red;">Delete</button>
</form>
            </td>
        </tr>
        <?php endforeach; ?>

        <tr>
            <th colspan="3">Grand Total</th>
            <th colspan="2"><?= number_format($total,2) ?></th>
        </tr>
    </table>

    <a href="menu.php">Back to Menu</a>
    <a href="place_order.php">Place Order</a>

<?php endif; ?>

</body>
</html>
