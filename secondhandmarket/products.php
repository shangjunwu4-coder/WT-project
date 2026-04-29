<?php
session_start();
include 'includes/dbconnect.php'; // 确保 $pdo 已经在这里初始化

try {
    $stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to load categories: " . $e->getMessage());
}

$search = trim($_GET['q'] ?? '');
$selectedCategoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$selectedPriceRange = trim($_GET['price_range'] ?? '');
$selectedCategoryName = '';
$priceRanges = [
    'under_50' => ['label' => 'Under $50', 'min' => null, 'max' => 50],
    '50_100' => ['label' => '$50 - $100', 'min' => 50, 'max' => 100],
    '100_300' => ['label' => '$100 - $300', 'min' => 100, 'max' => 300],
    '300_500' => ['label' => '$300 - $500', 'min' => 300, 'max' => 500],
    '500_plus' => ['label' => '$500+', 'min' => 500, 'max' => null],
];
$activePriceRange = $priceRanges[$selectedPriceRange] ?? null;

foreach ($categories as $category) {
    if ((int)$category['id'] === $selectedCategoryId) {
        $selectedCategoryName = $category['name'];
        break;
    }
}

// 查询数据库，关联分类和用户
$sql = "SELECT products.*, categories.name AS category_name, users.username
        FROM products
        JOIN categories ON products.category_id = categories.id
        JOIN users ON products.user_id = users.id
        WHERE products.status = 'active'";
$params = [];

if ($search !== '') {
    $sql .= " AND (
        products.title LIKE :keyword
        OR products.description LIKE :keyword
        OR users.username LIKE :keyword
    )";
    $params[':keyword'] = '%' . $search . '%';
}

if ($selectedCategoryId > 0) {
    $sql .= " AND products.category_id = :category_id";
    $params[':category_id'] = $selectedCategoryId;
}

if ($activePriceRange !== null) {
    if ($activePriceRange['min'] !== null) {
        $sql .= " AND products.price >= :min_price";
        $params[':min_price'] = $activePriceRange['min'];
    }

    if ($activePriceRange['max'] !== null) {
        $sql .= " AND products.price < :max_price";
        $params[':max_price'] = $activePriceRange['max'];
    }
}

$sql .= " ORDER BY products.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
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
            <a href="products.php" class="nav-current">Products</a>
            <a href="add_product.php">Sell Item</a>
            <a href="cart.php">Favorites</a>
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
    <form class="filter-bar" method="GET" action="products.php">
        <input
            type="text"
            name="q"
            placeholder="Search by product name, description, or seller..."
            value="<?php echo htmlspecialchars($search); ?>"
        >
        <select name="category_id">
            <option value="0">All Categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>" <?php echo $selectedCategoryId === (int)$category['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="price_range">
            <option value="">All Prices</option>
            <?php foreach ($priceRanges as $rangeKey => $range): ?>
                <option value="<?php echo $rangeKey; ?>" <?php echo $selectedPriceRange === $rangeKey ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($range['label']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Search</button>
        <?php if ($search !== '' || $selectedCategoryId > 0 || $activePriceRange !== null): ?>
            <a href="products.php" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </form>
</section>

<!-- Products List -->
<section class="container products-section">
    <div class="products-heading">
        <div>
            <h2 class="section-title">All Products</h2>
            <p class="filter-summary">
                <?php echo count($products); ?> product(s) found
                <?php if ($selectedCategoryName !== ''): ?>
                    in <?php echo htmlspecialchars($selectedCategoryName); ?>
                <?php endif; ?>
                <?php if ($search !== ''): ?>
                    for "<?php echo htmlspecialchars($search); ?>"
                <?php endif; ?>
                <?php if ($activePriceRange !== null): ?>
                    in <?php echo htmlspecialchars($activePriceRange['label']); ?>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <?php if (count($products) > 0): ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card product-list-card">
                    <div class="product-image-wrap">
                        <img
                            src="<?php echo htmlspecialchars(!empty($product['image']) ? $product['image'] : 'assets/images/default-product.png'); ?>"
                            alt="<?php echo htmlspecialchars($product['title']); ?>"
                            class="product-image"
                        >
                        <span class="product-badge"><?php echo htmlspecialchars($product['category_name']); ?></span>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                        <p class="product-price">$<?php echo number_format((float)$product['price'], 2); ?></p>
                        <p class="product-meta">Seller: <?php echo htmlspecialchars($product['username']); ?></p>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="product-actions">
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">Save Item</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-box">
            <h3>No products found</h3>
            <p>Try a different keyword, category, or price range.</p>
            <div class="empty-box-actions">
                <a href="products.php" class="btn">Clear filters</a>
            </div>
        </div>
    <?php endif; ?>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p>&copy; 2026 CampusMart. Built for Web Technologies Assignment.</p>
    </div>
</footer>

</body>
</html>
