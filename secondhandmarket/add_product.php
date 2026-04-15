<?php
session_start();
include 'includes/dbconnect.php';

$message = "";
$message_type = "";

// 判断是否登录
if (!isset($_SESSION['user_id'])) {
    die("Please login first before adding a product.");
}

$user_id = $_SESSION['user_id'];

// 获取分类
try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to load categories: " . $e->getMessage());
}

// 初始化表单数据
$title = "";
$price = "";
$category_id = "";
$description = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category_id = trim($_POST['category_id'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $user_id = $_SESSION['user_id'];

    // 基础校验
    if ($title === "" || $price === "" || $description === "" || $category_id === "") {
        $message = "Please fill in all required fields.";
        $message_type = "error";
    } elseif (!is_numeric($price) || $price < 0) {
        $message = "Price must be a valid non-negative number.";
        $message_type = "error";
    } else {
        // 默认图片
        $image_path = 'assets/images/default-product.png';

        // 处理图片上传
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['product_image'];

            if ($file['error'] === 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $file_type = mime_content_type($file['tmp_name']);
                $max_size = 5 * 1024 * 1024; // 5MB

                if (!in_array($file_type, $allowed_types)) {
                    $message = "Only JPG, PNG, GIF, and WEBP images are allowed.";
                    $message_type = "error";
                } elseif ($file['size'] > $max_size) {
                    $message = "Image size cannot exceed 5MB.";
                    $message_type = "error";
                } else {
                    $upload_dir = 'uploads/products/';

                    // 如果文件夹不存在就自动创建
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $new_filename = 'product_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                    $target_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($file['tmp_name'], $target_path)) {
                        $image_path = $target_path;
                    } else {
                        $message = "Failed to upload image.";
                        $message_type = "error";
                    }
                }
            } else {
                $message = "Image upload error.";
                $message_type = "error";
            }
        }

        // 如果前面没有报错，再插入数据库
        if ($message_type !== "error") {
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
                    ':image' => $image_path
                ]);

                // 发布成功后跳转到我的商品页
                header("Location: my_products.php?msg=added");
                exit;

            } catch (PDOException $e) {
                $message = "Database error: " . $e->getMessage();
                $message_type = "error";
            }
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

        .upload-box {
            border: 2px dashed #2e7d32;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            background: #f8fff8;
            cursor: pointer;
            transition: 0.2s;
        }

        .upload-box.dragover {
            background: #e8f5e9;
            border-color: #1b5e20;
        }

        .upload-box p {
            margin: 0;
            color: #555;
        }

        .preview-image {
            margin-top: 15px;
            max-width: 100%;
            max-height: 220px;
            border-radius: 10px;
            display: none;
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

    <form action="" method="POST" enctype="multipart/form-data">
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
            <label>Product Image</label>

            <div class="upload-box" id="uploadBox">
                <p>Drag image here or click to choose a file</p>
                <input type="file" id="product_image" name="product_image" accept="image/*" hidden>
                <img id="previewImage" class="preview-image" alt="Preview">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($description); ?></textarea>
        </div>

        <button type="submit" class="btn-submit">Add Product</button>
    </form>
</div>

<script>
    const uploadBox = document.getElementById('uploadBox');
    const fileInput = document.getElementById('product_image');
    const previewImage = document.getElementById('previewImage');

    uploadBox.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        showPreview(this.files[0]);
    });

    uploadBox.addEventListener('dragover', function (e) {
        e.preventDefault();
        uploadBox.classList.add('dragover');
    });

    uploadBox.addEventListener('dragleave', function () {
        uploadBox.classList.remove('dragover');
    });

    uploadBox.addEventListener('drop', function (e) {
        e.preventDefault();
        uploadBox.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            showPreview(files[0]);
        }
    });

    function showPreview(file) {
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
</script>

</body>
</html>
