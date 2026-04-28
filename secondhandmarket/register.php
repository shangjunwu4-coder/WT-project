<?php
session_start();
include 'includes/dbconnect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = $_POST['confirm_password'];
    $gender = $_POST['gender'];

    $errors = [];

    if(empty($username) || empty($email) || empty($password)|| empty($confirm_password) || empty($gender)) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username'=>$username,'email'=>$email]);
        if($stmt->fetch()) {
            $error = "Username or email already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, gender) VALUES (:username, :email, :password, :gender)");
            $stmt->execute(['username'=>$username, 'email'=>$email,'password'=>$hashed,'gender'=>$gender]);

            // 自动登录
            $user_id = $pdo->lastInsertId(); // 获取刚插入的用户ID
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['gender'] = $gender;
            

            header("Location: index.php"); // 跳转到首页
            exit;
        }
    }
}
?>
<!-- 保留你原来的注册表单布局 -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusMart - Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="container navbar-content">
        <a href="index.php" class="logo">CampusMart</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="add_product.php">Sell Item</a>
            <a href="cart.php">Favorites</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
    </div>
</nav>

<section class="hero small-hero">
    <div class="container">
        <h1>Create Your Account</h1>
        <p>Join CampusMart to buy, sell, and save your favorite campus items.</p>
    </div>
</section>

<section class="container">
    <div class="form-container">
        <h2>Register</h2>

        <?php if (!empty($error)): ?>
            <p class="form-error-box"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p class="form-success-box"><?php echo $success; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username"require>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email"require>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password"require>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password"require>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>


            <button type="submit" class="btn full-width-btn">Register</button>
        </form>

        <p class="form-switch-text">
            Already have an account?
            <a href="login.php" class="text-link">Login here</a>
        </p>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <p>&copy; 2026 CampusMart. Built for Web Technologies Assignment.</p>
    </div>
</footer>

</body>
</html>
