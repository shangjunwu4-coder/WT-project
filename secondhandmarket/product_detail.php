<?php
session_start();
include 'includes/dbconnect.php';

// 检查是否有商品 id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$product_id = (int)$_GET['id'];

// Increase product views each time the detail page is opened.
$stmt = $pdo->prepare("
    UPDATE products
    SET views = views + 1
    WHERE id = :id AND status = 'active'
");
$stmt->execute(['id' => $product_id]);

// 查询单个商品详情
$stmt = $pdo->prepare("
    SELECT products.*, categories.name AS category_name, users.username
    FROM products
    JOIN categories ON products.category_id = categories.id
    JOIN users ON products.user_id = users.id
    WHERE products.id = :id AND products.status = 'active'
");
$stmt->execute(['id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// 如果商品不存在
if (!$product) {
    header("Location: products.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT products.*, categories.name AS category_name, users.username
    FROM products
    JOIN categories ON products.category_id = categories.id
    JOIN users ON products.user_id = users.id
    WHERE products.category_id = :category_id
      AND products.id != :id
      AND products.status = 'active'
    ORDER BY products.created_at DESC
    LIMIT 4
");
$stmt->execute([
    'category_id' => $product['category_id'],
    'id' => $product_id
]);
$relatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 图片兜底
$product_image = !empty($product['image']) ? $product['image'] : 'assets/images/default-product.png';
$sellerInitial = strtoupper(substr($product['username'], 0, 1));
$postedDate = date('M j, Y', strtotime($product['created_at']));
$viewCount = (int)($product['views'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusMart - Product Detail</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="index.php" class="logo">CampusMart</a>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="add_product.php">Sell Item</a>
                <a href="cart.php">Cart</a>

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

    <!-- Hero -->
    <section class="hero small-hero">
        <div class="container">
            <h1>Product Details</h1>
            <p>View full information about this second-hand item.</p>
        </div>
    </section>

    <!-- Product Detail -->
    <section class="container">
        <div class="product-detail">
            <div class="product-detail-image">
                <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
            </div>

            <div class="product-detail-info">
                <h2><?php echo htmlspecialchars($product['title']); ?></h2>
                <p class="product-detail-price">$<?php echo htmlspecialchars($product['price']); ?></p>

                <div class="seller-card">
                    <div class="seller-avatar"><?php echo htmlspecialchars($sellerInitial); ?></div>
                    <div class="seller-details">
                        <strong><?php echo htmlspecialchars($product['username']); ?></strong>
                        <span>Campus seller</span>
                    </div>
                </div>

                <div class="product-facts">
                    <div class="product-fact">
                        <span>Category</span>
                        <strong><?php echo htmlspecialchars($product['category_name']); ?></strong>
                    </div>
                    <div class="product-fact">
                        <span>Posted</span>
                        <strong><?php echo htmlspecialchars($postedDate); ?></strong>
                    </div>
                    <div class="product-fact">
                        <span>Views</span>
                        <strong><?php echo $viewCount; ?></strong>
                    </div>
                </div>

                <p class="product-detail-desc">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </p>

                <div class="product-detail-actions">
                    <a href="products.php" class="btn btn-secondary">Back to Products</a>
                    <button class="btn">Add to Cart</button>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button class="btn btn-secondary">Add to Favorites</button>
                    <?php else: ?>
                        <a href="login.php" class="btn">Login to Favorite</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Related Products</h2>
                <p class="section-subtitle">More items from the same category you may want to check.</p>
            </div>
            <a href="products.php?category_id=<?php echo $product['category_id']; ?>" class="section-link">View more</a>
        </div>

        <?php if (count($relatedProducts) > 0): ?>
            <div class="product-grid">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                    <div class="product-card">
                        <img
                            src="<?php echo htmlspecialchars(!empty($relatedProduct['image']) ? $relatedProduct['image'] : 'assets/images/default-product.png'); ?>"
                            alt="<?php echo htmlspecialchars($relatedProduct['title']); ?>"
                            class="product-image"
                        >
                        <div class="product-info">
                            <h3 class="product-title"><?php echo htmlspecialchars($relatedProduct['title']); ?></h3>
                            <p class="product-price">$<?php echo htmlspecialchars($relatedProduct['price']); ?></p>
                            <p class="product-meta">Seller: <?php echo htmlspecialchars($relatedProduct['username']); ?></p>
                            <div class="product-actions">
                                <a href="product_detail.php?id=<?php echo $relatedProduct['id']; ?>" class="btn">View</a>
                                <a href="products.php?category_id=<?php echo $relatedProduct['category_id']; ?>" class="btn btn-secondary">More Like This</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                <h3>No related products yet</h3>
                <p>There are no other active items in this category right now.</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>© 2026 CampusMart. Built for Web Technologies Assignment.</p>
        </div>
    </footer>

</body>
</html>

    <!-- Product Detail -->
    <section class="container">
        <div class="product-detail">
            <div class="product-detail-image">
                <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
            </div>

            <div class="product-detail-info">
                <h2><?php echo htmlspecialchars($product['title']); ?></h2>
                <p class="product-detail-price">$<?php echo htmlspecialchars($product['price']); ?></p>
                <p class="product-meta">Category: <?php echo htmlspecialchars($product['category_name']); ?></p>
                <p class="product-meta">Seller: <?php echo htmlspecialchars($product['username']); ?></p>
                <p class="product-meta">Posted on: <?php echo htmlspecialchars($product['created_at']); ?></p>

                <p class="product-detail-desc">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </p>

                <div class="product-detail-actions">
                    <a href="products.php" class="btn btn-secondary">Back to Products</a>
                    <button class="btn">Add to Cart</button>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button class="btn btn-secondary">Add to Favorites</button>
                    <?php else: ?>
                        <a href="login.php" class="btn">Login to Favorite</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>© 2026 CampusMart. Built for Web Technologies Assignment.</p>
        </div>
    </footer>

</body>
</html>
