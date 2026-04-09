<?php
session_start();
include 'includes/dbconnect.php';

$_SESSION['user_id'] = 1;

$message = "";


$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $price = trim($_POST['price']);
    $category_id = $_POST['category_id'];
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    if ($title === "" || $price === "" || $description === "" || $category_id === "") {
        $message = "Please fill in all required fields.";
    } elseif (!is_numeric($price)) {
        $message = "Price must be a number.";
    } else {
        $sql = "INSERT INTO products (user_id, category_id, title, description, price)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $category_id, $title, $description, $price]);

        $message = "Product added successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <h2>Add Product</h2>

    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <div>
            <label>Title:</label><br>
            <input type="text" name="title">
        </div>
        <br>

        <div>
            <label>Price:</label><br>
            <input type="text" name="price">
        </div>
        <br>

        <div>
            <label>Category:</label><br>
            <select name="category_id">
                <option value="">Select category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>

        <div>
            <label>Description:</label><br>
            <textarea name="description" rows="5" cols="30"></textarea>
        </div>
        <br>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
