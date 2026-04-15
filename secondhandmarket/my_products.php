<?php
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
include 'includes/dbconnect.php';

$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.user_id = :uid");
$stmt->execute(['uid'=>$_SESSION['user_id']]);
$my_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Products</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php" class="logo">CampusMart</a>
    <a href="logout.php">Logout</a>
</nav>

<h1>My Products</h1>
<div class="product-grid">
<?php foreach($my_products as $p): ?>
<div class="product-card">
    <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>">
    <div class="product-info">
        <h3><?php echo htmlspecialchars($p['title']); ?></h3>
        <p class="price">$<?php echo $p['price']; ?></p>
        <p>Category: <?php echo htmlspecialchars($p['category_name']); ?></p>
        <div class="actions">
            <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn">Edit</a>
            <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn btn-secondary">Delete</a>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

</body>
</html>

<section class="hero small-hero">
    <div class="container">
        <h1>My Products</h1>
        <p>Manage the items you have posted on CampusMart.</p>
    </div>
</section>

<section class="container">
    <h2 class="section-title">Your Listed Products</h2>

    <div class="product-grid">
        <?php foreach ($myProducts as $product): ?>
            <div class="product-card">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="product-image">
                <div class="product-info">
                    <h3 class="product-title"><?php echo $product['title']; ?></h3>
                    <p class="product-price">$<?php echo $product['price']; ?></p>
                    <p class="product-meta">Category: <?php echo $product['category']; ?></p>
                    <div class="product-actions">
                        <a href="edit_product.php" class="btn">Edit</a>
                        <button class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <p>© 2026 CampusMart. Built for Web Technologies Assignment.</p>
    </div>
</footer>

</body>
</html>
