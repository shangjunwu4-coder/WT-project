<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusMart - Home</title>
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
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Buy and Sell Second-Hand Items on Campus</h1>
            <p>
                CampusMart is a simple marketplace for students to trade books,
                electronics, clothes, and daily essentials easily and safely.
            </p>
            <div style="margin-top: 20px;">
                <a href="products.php" class="btn">Browse Products</a>
                <a href="add_product.php" class="btn btn-secondary">Sell Your Item</a>
            </div>
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

    <!-- Latest Products -->
    <section class="container">
        <h2 class="section-title">Latest Products</h2>

        <div class="product-grid">
            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Product 1" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Used Calculator</h3>
                    <p class="product-price">$15.00</p>
                    <p class="product-meta">Category: Electronics</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Product 2" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Programming Book</h3>
                    <p class="product-price">$10.00</p>
                    <p class="product-meta">Category: Books</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Product 3" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Sports Bottle</h3>
                    <p class="product-price">$8.00</p>
                    <p class="product-meta">Category: Sports</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Product 4" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Winter Jacket</h3>
                    <p class="product-price">$25.00</p>
                    <p class="product-meta">Category: Clothes</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <!-- Favorite Products -->
<section class="container">
    <h2 class="section-title">Favorite Products</h2>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="empty-box">
            <h3>Please login first</h3>
            <p>Login to view and manage your favorite products.</p>
            <div class="empty-box-actions">
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn btn-secondary">Register</a>
            </div>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Favorite Product 1" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Wireless Mouse</h3>
                    <p class="product-price">$9.00</p>
                    <p class="product-meta">Category: Electronics</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Remove</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Favorite Product 2" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Data Structures Book</h3>
                    <p class="product-price">$11.00</p>
                    <p class="product-meta">Category: Books</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Remove</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Favorite Product 3" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Portable Fan</h3>
                    <p class="product-price">$7.00</p>
                    <p class="product-meta">Category: Daily Items</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Remove</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Favorite Product 4" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Campus Backpack</h3>
                    <p class="product-price">$20.00</p>
                    <p class="product-meta">Category: Daily Items</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Remove</button>
                    </div>
                </div>
            </div>
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
