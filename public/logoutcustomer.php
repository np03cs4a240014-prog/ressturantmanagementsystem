<?php
include __DIR__ . '/../config/db.php';
session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Optional: Redirect to login page
header("Location: index.php");
exit();
?>
