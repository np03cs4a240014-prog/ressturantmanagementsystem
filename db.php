<?php
$host = 'localhost';
$db   = 'resturant_db'; // <-- make sure this matches your actual database name
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
?>
<?php
try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
