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
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            </div>
        </div>
    </nav>

    <!-- Page Title -->
    <section class="hero">
        <div class="container">
            <h1>Browse Second-Hand Products</h1>
            <p>
                Explore books, electronics, clothes, sports items, and more
                shared by students on campus.
            </p>
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
            <select>
                <option>Sort by Price</option>
                <option>Low to High</option>
                <option>High to Low</option>
            </select>
            <button class="btn">Search</button>
        </div>
    </section>

    <!-- Product List -->
    <section class="container">
        <h2 class="section-title">All Products</h2>

        <div class="product-grid">
            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Used Calculator" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Used Calculator</h3>
                    <p class="product-price">$15.00</p>
                    <p class="product-meta">Category: Electronics</p>
                    <p class="product-meta">Seller: Alex</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Programming Book" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Programming Book</h3>
                    <p class="product-price">$10.00</p>
                    <p class="product-meta">Category: Books</p>
                    <p class="product-meta">Seller: Emma</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Winter Jacket" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Winter Jacket</h3>
                    <p class="product-price">$25.00</p>
                    <p class="product-meta">Category: Clothes</p>
                    <p class="product-meta">Seller: John</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Sports Bottle" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Sports Bottle</h3>
                    <p class="product-price">$8.00</p>
                    <p class="product-meta">Category: Sports</p>
                    <p class="product-meta">Seller: Lily</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Desk Lamp" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Desk Lamp</h3>
                    <p class="product-price">$12.00</p>
                    <p class="product-meta">Category: Daily Items</p>
                    <p class="product-meta">Seller: Tom</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Headphones" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Headphones</h3>
                    <p class="product-price">$18.00</p>
                    <p class="product-meta">Category: Electronics</p>
                    <p class="product-meta">Seller: Kevin</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Notebook Set" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Notebook Set</h3>
                    <p class="product-price">$6.00</p>
                    <p class="product-meta">Category: Books</p>
                    <p class="product-meta">Seller: Mia</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/300x220" alt="Backpack" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Backpack</h3>
                    <p class="product-price">$20.00</p>
                    <p class="product-meta">Category: Daily Items</p>
                    <p class="product-meta">Seller: Sarah</p>
                    <div class="product-actions">
                        <a href="product_detail.php" class="btn">View</a>
                        <button class="btn btn-secondary">Add to Cart</button>
                    </div>
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
