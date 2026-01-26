<?php
include __DIR__ . '/../config/db.php';

/* Fetch menu items from database */
$menuStmt = $pdo->query("SELECT * FROM menu ORDER BY name");
$menuItems = $menuStmt->fetchAll();

<div class="box">
    <h2>Menu</h2>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr>
            <th>Item Name</th>
            <th>Description</th>
            <th>Price (Rs.)</th>
            <th>Actions</th>
        </tr>

        <?php if ($menuItems): ?>
            <?php foreach ($menuItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                    <td><?= htmlspecialchars($item['price']) ?></td>
                    <td>
                        <a href="editmenu.php?id=<?= $item['id'] ?>">Edit</a> |
                        <a href="deletemenu.php?id=<?= $item['id'] ?>"
                           onclick="return confirm('Delete this item?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No menu items available</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
