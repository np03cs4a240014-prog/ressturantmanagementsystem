<?php
// Include database connection
require 'db.php';

// Check if 'id' is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepare DELETE statement for the menu item
        $stmt = $pdo->prepare("DELETE FROM menu WHERE id = :id");
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() > 0) {
            // Success: redirect back to the menu list with a success message
            header("Location: displaymenu.php?msg=deleted");
            exit;
        } else {
            // Item not found
            header("Location: displaymenu.php?msg=notfound");
            exit;
        }

    } catch (PDOException $e) {
        // Database error
        header("Location: displaymenu.php?msg=error");
        exit;
    }
} else {
    // No ID specified
    header("Location: displaymenu.php?msg=invalid");
    exit;
}
?>
