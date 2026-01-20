<?php
require 'db.php';

// Initialize menu items
$menuItems = [];

try {
    $stmt = $pdo->query("SELECT * FROM menu");
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>Database error: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu List</title>
    <style>
        body{
            background-color:#E5EDF0 ;
        }
        h2{
            padding:30px;
            background-color: #404E3B;
            margin-top: -20px;
            margin-right:-20px;
            margin-left:-20px;
            color:#E5EDF0;
            text-align: center;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 2px solid #333;
            padding: 10px;
            text-align: center;
            background-color:#6F866C;
            color:white;
        }
        th {
            background-color:#404E3B;
            color:#E5EDF0;
        }
        a {
            text-decoration: none;
            color: blue;
        }
        a:hover {
            text-decoration: underline;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Menu List</h2>

<?php if (!empty($menuItems)): ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($menuItems as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= htmlspecialchars($item['description']) ?></td>
            <td><?= htmlspecialchars($item['price']) ?></td>
            <td>
                <a href="editstaff.php?id=<?= $item['id'] ?>">Edit</a> |
                <a href="deletestaff.php?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure you want to delete this food item from the menu?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p class="message">No menu items found. Please add some food first.</p>
<?php endif; ?>
<a href="logoutstaff.php">Logout as a staff</a>

</body>
</html>
