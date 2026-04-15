<?php
session_start();
include 'includes/dbconnect.php';

// 检查是否有商品 id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$product_id = (int)$_GET['id'];

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

// 图片兜底
$product_image = !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/600x420';
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
