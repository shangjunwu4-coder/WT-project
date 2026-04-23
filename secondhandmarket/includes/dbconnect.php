<?php
$host = "localhost";
$dbname = "secondhandmarket";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $viewsColumn = $pdo->query("SHOW COLUMNS FROM products LIKE 'views'")->fetch(PDO::FETCH_ASSOC);
    if (!$viewsColumn) {
        $pdo->exec("ALTER TABLE products ADD COLUMN views INT NOT NULL DEFAULT 0 AFTER status");
    }

    // Keep the built-in category list in sync for older local databases.
    $pdo->exec("
        INSERT IGNORE INTO categories (name) VALUES
        ('Books'),
        ('Textbooks'),
        ('Electronics'),
        ('Phones & Tablets'),
        ('Laptops'),
        ('Clothes'),
        ('Shoes'),
        ('Furniture'),
        ('Daily Use'),
        ('Dorm Supplies'),
        ('Kitchenware'),
        ('Bicycles'),
        ('Sports Equipment'),
        ('Beauty & Personal Care'),
        ('Study Supplies'),
        ('Tickets & Coupons'),
        ('Other')
    ");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
