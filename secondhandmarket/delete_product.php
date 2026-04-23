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
    $stmt = $pdo->prepare("
        SELECT image
        FROM products
        WHERE id = :id AND user_id = :user_id
        LIMIT 1
    ");
    $stmt->execute([
        ':id' => $product_id,
        ':user_id' => $user_id
    ]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found or you do not have permission to delete it.");
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        DELETE FROM products
        WHERE id = :id AND user_id = :user_id
    ");
    $stmt->execute([
        ':id' => $product_id,
        ':user_id' => $user_id
    ]);

    $pdo->commit();

    $imagePath = $product['image'] ?? '';
    $defaultImage = 'assets/images/default-product.png';

    if ($imagePath !== '' && $imagePath !== $defaultImage) {
        $fullImagePath = __DIR__ . '/' . ltrim($imagePath, '/');
        if (is_file($fullImagePath)) {
            unlink($fullImagePath);
        }
    }

    header("Location: my_products.php?msg=deleted");
    exit;
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Database error: " . $e->getMessage());
}
?>
