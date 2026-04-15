<?php
session_start();
include 'includes/dbconnect.php';

$message = "";
$message_type = "";


if (!isset($_SESSION['user_id'])) {
    die("Please login first before adding a product.");
}


try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to load categories: " . $e->getMessage());
}

$title = "";
$price = "";
$category_id = "";
$description = "";
$image = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category_id = trim($_POST['category_id'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $user_id = $_SESSION['user_id'];

    
    if ($title === "" || $price === "" || $description === "" || $category_id === "") {
        $message = "Please fill in all required fields.";
        $message_type = "error";
    } elseif (!is_numeric($price) || $price < 0) {
        $message = "Price must be a valid non-negative number.";
        $message_type = "error";
    } else {
        try {
           
            $sql = "INSERT INTO products 
                    (user_id, category_id, title, description, price, image, status, created_at)
                    VALUES 
                    (:user_id, :category_id, :title, :description, :price, :image, 'active', NOW())";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $user_id,
                ':category_id' => $category_id,
                ':title' => $title,
                ':description' => $description,
                ':price' => $price,
                ':image' => $image !== "" ? $image : 'assets/images/default-product.png'
            ]);

            $message = "Product added successfully!";
            $message_type = "success";

            // 清空表单
            $title = "";
            $price = "";
            $category_id = "";
            $description = "";
            $image = "";

        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Item - CampusMart</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .form-container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .form-container h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
        }

        .btn-submit {
            background: #2e7d32;
            color: #fff;
            border: none;
            padding: 12px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-submit:hover {
            background: #256428;
        }

        .message {
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .message.success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .message.error {
            background: #ffebee;
            color: #c62828;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container navbar-content">
        <a href="index.php" class="logo">CampusMart</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="add_product.php">Sell Item</a>
            <a href="cart.php">Cart</a>
            <a href="my_products.php">My Products</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="form-container">
    <h2>Add Product</h2>

    <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="title">Product Title *</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        </div>

        <div class="form-group">
            <label for="price">Price *</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
        </div>

        <div class="form-group">
            <label for="category_id">Category *</label>
            <select id="category_id" name="category_id" required>
                <option value="">Select category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"
                        <?php echo ($category_id == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Image URL</label>
            <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($image); ?>" placeholder="e.g. assets/images/book1.jpg">
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($description); ?></textarea>
        </div>

        <button type="submit" class="btn-submit">Add Product</button>
    </form>
</div>

</body>
</html>
