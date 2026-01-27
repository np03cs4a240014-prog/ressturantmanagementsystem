<?php
include __DIR__ . '/../config/db.php';
session_start();

/* Ensure user is logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* Allow only staff */
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'staff') {
    exit("Access denied: Staff only");
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
    ORDER BY o.created_at DESC, o.id DESC
");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Orders</title>
    <style>
        body {
            background-image: url("../images/stafffoodimage.jpg");
            font-family: "Times New Roman";
            background-size: cover;
            background-attachment: fixed;
        }
        h1 {
            margin-top:-20px;
            margin-right:-20px;
            margin-left:-20px;
            background-color:#404E3B;
            padding:30px;
            color:#E5EDF0;
            text-align:center;
        }
        h3 {
            color:#404E3B;
            max-width: 48.8%;
            margin: auto;
            background:#E5EDF0;
            padding:10px;
            text-align:center;
        }
        table {
            width:50%;
            margin: auto;
            border-collapse: collapse;
            text-align:center;
        }
        th, td {
            border:1px solid #333;
            padding:8px;
        }
        th {
            background:#404E3B;
            color:#E5EDF0;
        }
        td {
            background:#E5EDF0;
            color:#404E3B;
        }
        button {
            font-family:Times New Roman;
            display:block;
            margin:10px auto 30px;
            padding:8px 16px;
            background-color:#404E3B;
            color:#E5EDF0;
            border:none;
            cursor:pointer;
            border-radius:5px;
        }
        .completed {
            max-width:80px;
            display:block;
            margin:10px auto 30px;
            padding:8px 16px;
            background-color:#E5EDF0;
            color:#404E3B;
            border:none;
            cursor:pointer;
            border-radius:5px;
        }
        a{
            padding:10px;
            border-radius:5px;
            text-decoration:none;
            max-width:50px;
            background-color:#E5EDF0;
            color:#404E3B;
        }
    </style>
</head>
<body>

<h1>Customer Orders</h1>
<a href="logoutstaff.php">Logout as a Staff</a>

<?php
$currentOrder = null;
$currentStatus = null;

foreach ($rows as $row) {

    if ($currentOrder !== $row['order_id']) {

        if ($currentOrder !== null) {
            echo "</table>";

            if ($currentStatus === 'Pending') {
                // Correctly use concatenation to send POST data
                echo '<form method="post" action="update_status.php">
                        <input type="hidden" name="order_id" value="'.$currentOrder.'">
                        <button type="submit">Mark as Completed</button>
                      </form>';
            } else {
                echo '<p class="completed"> Completed</p>';
            }
        }

        // Display new order header
        echo "<h3>
            Order #{$row['order_id']}<br>
            Customer: ".htmlspecialchars($row['customer_name'])."<br>
            Total: Rs. ".number_format($row['total'],2)."<br>
            Status: {$row['status']}<br>
            Date: {$row['created_at']}
        </h3>";

        echo "<table>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>";

        $currentOrder = $row['order_id'];
        $currentStatus = $row['status'];
    }

    echo "<tr>
        <td>".htmlspecialchars($row['item_name'])."</td>
        <td>{$row['quantity']}</td>
        <td>".number_format($row['price'],2)."</td>
    </tr>";
}

// Close last order table
if ($currentOrder !== null) {
    echo "</table>";
    if ($currentStatus === 'Pending') {
        echo '<form method="post" action="update_status.php">
                <input type="hidden" name="order_id" value="'.$currentOrder.'">
                <button type="submit">Mark as Completed</button>
              </form>';
    } else {
        echo '<p class="completed"> Completed</p>';
    }
}
?>


</body>
</html>