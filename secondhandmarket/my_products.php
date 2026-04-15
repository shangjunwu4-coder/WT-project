<?php
session_start();
include 'includes/dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.user_id = :user_id
    ORDER BY p.created_at DESC
");
$stmt->execute(['user_id' => $user_id]);
$myProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusMart - My Products</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="container navbar-content">
        <a href="index.php" class="logo">CampusMart</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="add_product.php">Sell Item</a>
            <a href="cart.php">Cart</a>
            <a href="my_products.php">My Products</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<section class="hero small-hero">
    <div class="container">
        <h1>My Products</h1>
        <p>Manage the items you have posted on CampusMart.</p>
    </div>
</section>

<section class="container">
    <h2 class="section-title">Your Listed Products</h2>

    <?php if (count($myProducts) > 0): ?>
        <div class="product-grid">
            <?php foreach ($myProducts as $product): ?>
                <div class="product-card">
                    <img 
                        src="<?php echo htmlspecialchars(!empty($product['image']) ? $product['image'] : 'assets/images/default-product.png'); ?>" 
                        alt="<?php echo htmlspecialchars($product['title']); ?>" 
                        class="product-image"
                    >
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                        <p class="product-price">$<?php echo htmlspecialchars($product['price']); ?></p>
                        <p class="product-meta">Category: <?php echo htmlspecialchars($product['category_name']); ?></p>

                        <div class="product-actions">
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>You have not posted any products yet.</p>
    <?php endif; ?>
</section>

<footer class="footer">
    <div class="container">
        <p>© 2026 CampusMart. Built for Web Technologies Assignment.</p>
    </div>
</footer>

</body>
</html>
