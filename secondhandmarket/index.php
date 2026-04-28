<?php
session_start();
include 'includes/dbconnect.php';

try {
    $stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to load categories: " . $e->getMessage());
}

$priceRanges = [
    'under_50' => 'Under $50',
    '50_100' => '$50 - $100',
    '100_300' => '$100 - $300',
    '300_500' => '$300 - $500',
    '500_plus' => '$500+',
];

try {
    $stmt = $pdo->query("
        SELECT
            COUNT(*) AS product_count,
            COUNT(DISTINCT user_id) AS seller_count
        FROM products
        WHERE status = 'active'
    ");
    $siteStats = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $siteStats = ['product_count' => 0, 'seller_count' => 0];
}

try {
    $stmt = $pdo->query("
        SELECT c.id, c.name, COUNT(p.id) AS product_count
        FROM categories c
        LEFT JOIN products p
            ON p.category_id = c.id
           AND p.status = 'active'
        GROUP BY c.id, c.name
        ORDER BY product_count DESC, c.name ASC
        LIMIT 6
    ");
    $featuredCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featuredCategories = [];
}

if (count($featuredCategories) === 0) {
    foreach (array_slice($categories, 0, 6) as $category) {
        $featuredCategories[] = [
            'id' => $category['id'],
            'name' => $category['name'],
            'product_count' => 0
        ];
    }
}

$latestStmt = $pdo->query("SELECT products.*, categories.name AS category_name, users.username
    FROM products
    JOIN categories ON products.category_id = categories.id
    JOIN users ON products.user_id = users.id
    WHERE products.status='active'
    ORDER BY products.created_at DESC LIMIT 8");
$latest_products = $latestStmt->fetchAll(PDO::FETCH_ASSOC);

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

<nav class="navbar">
    <div class="container navbar-content">
        <a href="index.php" class="logo">CampusMart</a>
        <div class="nav-links">
            <a href="index.php" class="nav-current">Home</a>
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

<section class="hero">
    <div class="container hero-layout">
        <div class="hero-copy">
            <span class="hero-badge">Campus marketplace for students</span>
            <h1>Find useful second-hand deals on campus in just a few clicks.</h1>
            <p>Search textbooks, electronics, dorm supplies, bikes, and daily essentials from other students. CampusMart makes campus trading faster, simpler, and more affordable.</p>

            <div class="hero-cta-row">
                <a href="products.php" class="btn">Browse Products</a>
                <a href="add_product.php" class="btn btn-secondary">Post an Item</a>
            </div>

            <div class="hero-stats">
                <div class="hero-stat-card">
                    <strong><?php echo (int)($siteStats['product_count'] ?? 0); ?></strong>
                    <span>active listings</span>
                </div>
                <div class="hero-stat-card">
                    <strong><?php echo (int)($siteStats['seller_count'] ?? 0); ?></strong>
                    <span>student sellers</span>
                </div>
                <div class="hero-stat-card">
                    <strong><?php echo count($categories); ?></strong>
                    <span>categories</span>
                </div>
            </div>
        </div>

        <div class="hero-panel">
            <div class="hero-panel-header">
                <h2>Start with a smart search</h2>
                <p>Use keyword, category, and price range together to get to the right listings faster.</p>
            </div>

            <form class="filter-bar hero-search" method="GET" action="products.php">
                <input type="text" name="q" placeholder="Search what you want to buy...">
                <select name="category_id">
                    <option value="0">All Categories</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="price_range">
                    <option value="">All Prices</option>
                    <?php foreach($priceRanges as $rangeKey => $rangeLabel): ?>
                        <option value="<?php echo $rangeKey; ?>">
                            <?php echo htmlspecialchars($rangeLabel); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn">Search Products</button>
            </form>

            <div class="trust-points">
                <div class="trust-point">
                    <strong>Meet locally on campus</strong>
                    <span>Easy pickup and easier item verification.</span>
                </div>
                <div class="trust-point">
                    <strong>Student-friendly prices</strong>
                    <span>Good for textbooks, dorm moves, and semester swaps.</span>
                </div>
                <div class="trust-point">
                    <strong>Useful filters</strong>
                    <span>Browse by type, keyword, and budget without wasting time.</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container homepage-section">
    <div class="section-header">
        <div>
            <h2 class="section-title">Popular Categories</h2>
            <p class="section-subtitle">Quick shortcuts to the kinds of products students search most often.</p>
        </div>
        <a href="products.php" class="section-link">View all products</a>
    </div>

    <div class="category-pills">
        <?php foreach($featuredCategories as $category): ?>
            <a href="products.php?category_id=<?php echo $category['id']; ?>" class="category-pill">
                <span><?php echo htmlspecialchars($category['name']); ?></span>
                <strong><?php echo (int)$category['product_count']; ?> items</strong>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="container homepage-section">
    <div class="feature-strip">
        <div class="feature-card">
            <h3>Search faster</h3>
            <p>Jump directly to the category and price range you care about instead of scrolling through everything.</p>
        </div>
        <div class="feature-card">
            <h3>Built for student life</h3>
            <p>From textbooks to dorm supplies, the homepage now points people toward the most useful campus items.</p>
        </div>
        <div class="feature-card">
            <h3>More practical homepage</h3>
            <p>Listings, categories, and guidance are all visible right away so the page feels useful from the first second.</p>
        </div>
    </div>
</section>

<section class="container homepage-section">
    <div class="section-header">
        <div>
            <h2 class="section-title">Latest Products</h2>
            <p class="section-subtitle">Fresh listings from students around campus.</p>
        </div>
        <a href="products.php" class="section-link">Browse all listings</a>
    </div>

    <?php if (count($latest_products) > 0): ?>
        <div class="product-grid">
            <?php foreach($latest_products as $p): ?>
            <div class="product-card">
                <img
                    src="<?php echo htmlspecialchars(!empty($p['image']) ? $p['image'] : 'assets/images/default-product.png'); ?>"
                    alt="<?php echo htmlspecialchars($p['title']); ?>"
                    class="product-image"
                >
                <div class="product-info">
                    <h3 class="product-title"><?php echo htmlspecialchars($p['title']); ?></h3>
                    <p class="product-price">$<?php echo htmlspecialchars($p['price']); ?></p>
                    <p class="product-meta">Category: <?php echo htmlspecialchars($p['category_name']); ?></p>
                    <p class="product-meta">Seller: <?php echo htmlspecialchars($p['username']); ?></p>
                    <div class="product-actions">
                        <a href="product_detail.php?id=<?php echo $p['id']; ?>" class="btn">View</a>
                        <a href="products.php?category_id=<?php echo $p['category_id']; ?>" class="btn btn-secondary">More Like This</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-box">
            <h3>No active listings yet</h3>
            <p>Be the first student to post something useful for others on campus.</p>
            <div class="empty-box-actions">
                <a href="add_product.php" class="btn">Post the first item</a>
            </div>
        </div>
    <?php endif; ?>
</section>

<section class="container homepage-section">
    <div class="section-header">
        <div>
            <h2 class="section-title">Favorite Products</h2>
            <p class="section-subtitle">A simple place to revisit items you are interested in.</p>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="my_products.php" class="section-link">Manage my account</a>
        <?php endif; ?>
    </div>

    <?php if(!isset($_SESSION['user_id'])): ?>
        <div class="empty-box">
            <h3>Login to save and revisit products</h3>
            <p>Sign in to manage your listings and keep track of items you may want later.</p>
            <div class="empty-box-actions">
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn btn-secondary">Create account</a>
            </div>
        </div>
    <?php elseif(count($favorites) > 0): ?>
        <div class="product-grid">
            <?php foreach($favorites as $f): ?>
            <div class="product-card">
                <img
                    src="<?php echo htmlspecialchars(!empty($f['image']) ? $f['image'] : 'assets/images/default-product.png'); ?>"
                    alt="<?php echo htmlspecialchars($f['title']); ?>"
                    class="product-image"
                >
                <div class="product-info">
                    <h3 class="product-title"><?php echo htmlspecialchars($f['title']); ?></h3>
                    <p class="product-price">$<?php echo htmlspecialchars($f['price']); ?></p>
                    <p class="product-meta">Category: <?php echo htmlspecialchars($f['category_name']); ?></p>
                    <p class="product-meta">Seller: <?php echo htmlspecialchars($f['username']); ?></p>
                    <div class="product-actions">
                        <a href="product_detail.php?id=<?php echo $f['id']; ?>" class="btn">View</a>
                        <a href="products.php?category_id=<?php echo $f['category_id']; ?>" class="btn btn-secondary">Similar Items</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-box">
            <h3>You have no favorite products yet</h3>
            <p>Browse the marketplace and save the items you want to compare later.</p>
            <div class="empty-box-actions">
                <a href="products.php" class="btn">Explore products</a>
            </div>
        </div>
    <?php endif; ?>
</section>

<section class="container homepage-section">
    <div class="info-banner">
        <div>
            <h2>Trade smarter on campus</h2>
            <p>Meet in public places, check item condition in person, and confirm details before payment for safer campus trading.</p>
        </div>
        <a href="add_product.php" class="btn">Sell Something Today</a>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <p>© 2026 CampusMart. Built for Web Technologies Assignment.</p>
    </div>
</footer>

</body>
</html>
