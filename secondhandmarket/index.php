<?php
session_start();
include 'includes/dbconnect.php';

// 查询最新商品
$stmt = $pdo->query("SELECT products.*, categories.name AS category_name, users.username
    FROM products
    JOIN categories ON products.category_id = categories.id
    JOIN users ON products.user_id = users.id
    WHERE products.status='active'
    ORDER BY products.created_at DESC LIMIT 8");
$latest_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 查询用户收藏商品
$favorites = [];
if (isset($_SESSION['user_id'])) {
    $stmtFav = $pdo->prepare("SELECT p.*, c.name AS category_name, u.username
        FROM favorites f
        JOIN products p ON f.product_id = p.id
        JOIN categories c ON p.category_id = c.id
        JOIN users u ON p.user_id = u.id
        WHERE f.user_id = :uid");
    $stmtFav->execute(['uid' => $_SESSION['user_id']]);
    $favorites = $stmtFav->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CampusMart</title>
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
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="my_products.php">My Products</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Buy and Sell Second-Hand Items on Campus</h1>
        <p>CampusMart is a simple marketplace for students to trade books, electronics, clothes, and daily essentials easily and safely.</p>
    </div>
</section>

<!-- Latest Products -->
<section class="container">
    <h2>Latest Products</h2>
    <div class="product-grid">
        <?php foreach($latest_products as $p): ?>
        <div class="product-card">
            <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>">
            <div class="product-info">
                <h3><?php echo htmlspecialchars($p['title']); ?></h3>
                <p class="price">$<?php echo $p['price']; ?></p>
                <p>Category: <?php echo htmlspecialchars($p['category_name']); ?></p>
                <p>Seller: <?php echo htmlspecialchars($p['username']); ?></p>
                <div class="actions">
                    <a href="product_detail.php?id=<?php echo $p['id']; ?>" class="btn">View</a>
                    <button class="btn btn-secondary">Add to Cart</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Favorite Products -->
<section class="container">
    <h2>Favorite Products</h2>
    <div class="product-grid">
        <?php if(!isset($_SESSION['user_id'])): ?>
            <p>Please login first to see your favorite products.</p>
        <?php elseif(count($favorites) > 0): ?>
            <?php foreach($favorites as $f): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($f['image']); ?>" alt="<?php echo htmlspecialchars($f['title']); ?>">
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($f['title']); ?></h3>
                    <p class="price">$<?php echo $f['price']; ?></p>
                    <p>Category: <?php echo htmlspecialchars($f['category_name']); ?></p>
                    <div class="actions">
                        <a href="product_detail.php?id=<?php echo $f['id']; ?>" class="btn">View</a>
                        <button class="btn btn-secondary">Remove</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have no favorite products.</p>
        <?php endif; ?>
    </div>
</section>

<footer>
    <div class="container">
        <p>© 2026 CampusMart. Built for Web Technologies Assignment.</p>
    </div>
</footer>

</body>
</html>
