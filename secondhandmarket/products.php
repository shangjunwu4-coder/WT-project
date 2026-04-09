<?php
session_start();
include 'includes/dbconnect.php'; // 确保 $pdo 已经在这里初始化

// 查询数据库，关联分类和用户
$sql = "SELECT products.*, categories.name AS category_name, users.username
        FROM products
        JOIN categories ON products.category_id = categories.id
        JOIN users ON products.user_id = users.id
        WHERE products.status = 'active'
        ORDER BY products.created_at DESC";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusMart - Products</title>
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

<!-- Hero Section -->
<section class="hero small-hero">
    <div class="container">
        <h1>Browse Second-Hand Products</h1>
        <p>Explore books, electronics, clothes, sports items, and more shared by students on campus.</p>
    </div>
</section>

<!-- Search / Filter -->
<section class="container">
    <div class="filter-bar">
        <input type="text" placeholder="Search products...">
        <select>
            <option>All Categories</option>
            <option>Books</option>
            <option>Electronics</option>
            <option>Clothes</option>
            <option>Daily Items</option>
            <option>Sports</option>
            <option>Others</option>
        </select>
        <button class="btn">Search</button>
    </div>
</section>

<!-- Products List -->
<section class="container">
    <h2 class="section-title">All Products</h2>

    <?php if (count($products) > 0): ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                        <p class="product-price">$<?php echo htmlspecialchars($product['price']); ?></p>
                        <p class="product-meta">Category: <?php echo htmlspecialchars($product['category_name']); ?></p>
                        <p class="product-meta">Seller: <?php echo htmlspecialchars($product['username']); ?></p>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="product-actions">
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn">View</a>
                            <button class="btn btn-secondary">Add to Cart</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No products found.</p>
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
