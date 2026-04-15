<?php
session_start();
include 'includes/dbconnect.php';


if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id <= 0) {
    die("Invalid product ID.");
}

try {
    
    $stmt = $pdo->prepare("UPDATE products 
                           SET status = 'deleted' 
                           WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        ':id' => $product_id,
        ':user_id' => $user_id
    ]);

    header("Location: my_products.php?msg=deleted");
    exit;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
