<?php
session_start();
include 'includes/dbconnect.php';

$favorites = [];

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("
        SELECT
            p.*,
            c.name AS category_name,
            u.username,
            f.created_at AS favorited_at
        FROM favorites f
        JOIN products p ON f.product_id = p.id
        JOIN categories c ON p.category_id = c.id
        JOIN users u ON p.user_id = u.id
        WHERE f.user_id = :user_id
          AND p.status = 'active'
        ORDER BY f.created_at DESC
    ");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusMart - Favorites</title>
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
            <a href="cart.php" class="nav-current">Favorites</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="my_products.php">My Products</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<section class="hero small-hero">
    <div class="container">
        <h1>My Favorites</h1>
        <p>Keep track of campus items you want to revisit later.</p>
    </div>
</section>

<section class="container">
    <div class="section-header">
        <div>
            <h2 class="section-title">Saved Products</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <p class="section-subtitle"><?php echo count($favorites); ?> saved item<?php echo count($favorites) === 1 ? '' : 's'; ?> in your favorites.</p>
            <?php endif; ?>
        </div>
        <a href="products.php" class="section-link">Browse products</a>
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="empty-box">
            <h3>Login to view your favorites</h3>
            <p>Your saved products are connected to your account, so please login first.</p>
            <div class="empty-box-actions">
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn btn-secondary">Create account</a>
            </div>
        </div>
    <?php elseif (count($favorites) > 0): ?>
        <div class="product-grid">
            <?php foreach ($favorites as $product): ?>
                <div class="product-card favorite-card">
                    <img
                        src="<?php echo htmlspecialchars(!empty($product['image']) ? $product['image'] : 'assets/images/default-product.png'); ?>"
                        alt="<?php echo htmlspecialchars($product['title']); ?>"
                        class="product-image"
                    >
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                        <p class="product-price">$<?php echo htmlspecialchars($product['price']); ?></p>
                        <p class="product-meta">Category: <?php echo htmlspecialchars($product['category_name']); ?></p>
                        <p class="product-meta">Seller: <?php echo htmlspecialchars($product['username']); ?></p>
                        <p class="product-meta">Saved on: <?php echo htmlspecialchars($product['favorited_at']); ?></p>
                        <div class="product-actions">
                            <a href="product_detail.php?id=<?php echo (int)$product['id']; ?>" class="btn">View</a>
                            <button
                                type="button"
                                class="btn btn-secondary favorite-toggle is-favorited"
                                data-product-id="<?php echo (int)$product['id']; ?>"
                                data-favorited="1"
                                data-remove-card="1"
                            >
                                Remove
                            </button>
                            <span class="favorite-feedback" aria-live="polite"></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="empty-box" id="favorites-empty-state" hidden>
            <h3>You have no favorite products yet</h3>
            <p>Browse the marketplace and save items you want to compare later.</p>
            <div class="empty-box-actions">
                <a href="products.php" class="btn">Explore products</a>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-box">
            <h3>You have no favorite products yet</h3>
            <p>Browse the marketplace and save items you want to compare later.</p>
            <div class="empty-box-actions">
                <a href="products.php" class="btn">Explore products</a>
            </div>
        </div>
    <?php endif; ?>
</section>

<footer class="footer">
    <div class="container">
        <p>&copy; 2026 CampusMart. Built for Web Technologies Assignment.</p>
    </div>
</footer>

<script src="assets/javascript/main.js"></script>
</body>
</html>
