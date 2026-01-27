<?php
include __DIR__ . '/../config/db.php';
session_start();

/* Allow only staff */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../resturantdashboard.php");
    exit;
}

/* Fetch orders with items */
$stmt = $pdo->prepare("
    SELECT
        o.id AS order_id,
        o.total,
        o.status,
        o.created_at,
        u.name AS customer_name,
        m.name AS item_name,
        oi.quantity,
        oi.price
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN order_items oi ON o.id = oi.order_id
    JOIN menu m ON oi.menu_id = m.id
    ORDER BY o.created_at DESC
");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Orders</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background: #f2f2f2; }
        h3 { background: #ddd; padding: 10px; margin-bottom: 0; }
        button {
            padding: 8px 12px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }
        .completed {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>📋 Customer Orders</h1>

<?php
$currentOrder = null;
$currentStatus = null;

foreach ($rows as $row) {

    /* New order starts */
    if ($currentOrder !== $row['order_id']) {

        /* Close previous order */
        if ($currentOrder !== null) {
            echo "</table>";

            if ($currentStatus === 'Pending') {
                echo "
                <form method='post' action='update_status.php'>
                    <input type='hidden' name='order_id' value='{$currentOrder}'>
                    <button type='submit'>Mark as Completed</button>
                </form><br>";
            } else {
                echo "<p class='completed'>✔ Completed</p>";
            }
        }

        /* Order header */
        echo "<h3>
            Order #{$row['order_id']} |
            Customer: " . htmlspecialchars($row['customer_name']) . " |
            Total: Rs. " . number_format($row['total'], 2) . " |
            Status: {$row['status']} |
            Date: {$row['created_at']}
        </h3>";

        echo "<table>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>";

        $currentOrder  = $row['order_id'];
        $currentStatus = $row['status'];
    }

    /* Order items */
    echo "<tr>
        <td>" . htmlspecialchars($row['item_name']) . "</td>
        <td>{$row['quantity']}</td>
        <td>" . number_format($row['price'], 2) . "</td>
    </tr>";
}

/* Close LAST order */
if ($currentOrder !== null) {
    echo "</table>";

    if ($currentStatus === 'Pending') {
        echo "
        <form method='post' action='update_status.php'>
            <input type='hidden' name='order_id' value='{$currentOrder}'>
            <button type='submit'>Mark as Completed</button>
        </form>";
    } else {
        echo "<p class='completed'>✔ Completed</p>";
    }
}
?>

</body>
</html>