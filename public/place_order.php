<?php
include __DIR__ . '/../config/db.php';
session_start();

/* Check login */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* Get cart items */
$stmt = $pdo->prepare("
    SELECT c.menu_id, c.quantity, m.name, m.price
    FROM cart c
    JOIN menu m ON c.menu_id = m.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* If cart empty */
if (!$cartItems) {
    echo "Your cart is empty.";
    exit;
}

/* Calculate total */
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

try {
    $pdo->beginTransaction();

    /* Create order */
    $stmt = $pdo->prepare(
        "INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'Pending')"
    );
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();

    /* Insert order items */
    $stmt = $pdo->prepare(
        "INSERT INTO order_items (order_id, menu_id, quantity, price)
         VALUES (?, ?, ?, ?)"
    );

    foreach ($cartItems as $item) {
        $stmt->execute([
            $order_id,
            $item['menu_id'],
            $item['quantity'],
            $item['price']
        ]);
    }

    /* Clear cart */
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $pdo->commit();

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Order failed.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Placed</title>

    <!-- INTERNAL CSS (Correct Location) -->
    <style>
        body {
            background-image: url("../images/backgroundimage.jpg");
            font-family: "Times New Roman", Times, serif;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 20px;
        }

        h1 {
            padding:50px;
            margin-top:-10px;
            margin-right:-10px;
            margin-left:-10px;
            background-color:#404E3B;
            text-align:center;
            color:#E5EDF0;
        }

        .Register {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            width: 85%;
            margin: auto;
            border-radius: 10px;
            text-align:center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #333;
            text-align: center;
        }

        th {
            background-color:#404E3B;
            color:white;
        }
        p{
            text-align:center;
            color:#404E3B;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #0066cc;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h1>Place Your Order</h1>

<div class="Register">
    <h2>Order Placed Successfully</h2>

    <p><strong>Order ID:</strong> <?= htmlspecialchars($order_id) ?></p>

    <table>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>

        <?php foreach ($cartItems as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= (int)$item['quantity'] ?></td>
            <td><?= number_format($item['price'], 2) ?></td>
            <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="3"><strong>Total Amount to Pay</strong></td>
            <td><strong><?= number_format($total, 2) ?></strong></td>
        </tr>
    </table>

    <a href="userdashboard.php">Back to Dashboard</a>
</div>

</body>
</html>