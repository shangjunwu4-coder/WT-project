<?php
session_start();
header('Content-Type: application/json');

function send_json($statusCode, $payload) {
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json(405, [
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}

if (!isset($_SESSION['user_id'])) {
    send_json(401, [
        'success' => false,
        'login_required' => true,
        'message' => 'Please login to save favorites.'
    ]);
}

$user_id = (int)$_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id <= 0) {
    send_json(400, [
        'success' => false,
        'message' => 'Invalid product ID.'
    ]);
}

include 'includes/dbconnect.php';

try {
    $stmt = $pdo->prepare("
        SELECT id
        FROM products
        WHERE id = :product_id AND status = 'active'
        LIMIT 1
    ");
    $stmt->execute(['product_id' => $product_id]);

    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        send_json(404, [
            'success' => false,
            'message' => 'Product not found.'
        ]);
    }

    $stmt = $pdo->prepare("
        SELECT id
        FROM favorites
        WHERE user_id = :user_id AND product_id = :product_id
        LIMIT 1
    ");
    $stmt->execute([
        'user_id' => $user_id,
        'product_id' => $product_id
    ]);
    $favorite = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($favorite) {
        $stmt = $pdo->prepare("
            DELETE FROM favorites
            WHERE user_id = :user_id AND product_id = :product_id
        ");
        $stmt->execute([
            'user_id' => $user_id,
            'product_id' => $product_id
        ]);

        $favorited = false;
        $message = 'Removed from favorites.';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO favorites (user_id, product_id)
            VALUES (:user_id, :product_id)
        ");
        $stmt->execute([
            'user_id' => $user_id,
            'product_id' => $product_id
        ]);

        $favorited = true;
        $message = 'Added to favorites.';
    }

    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS favorite_count
        FROM favorites
        WHERE user_id = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $favoriteCount = (int)$stmt->fetchColumn();

    send_json(200, [
        'success' => true,
        'favorited' => $favorited,
        'favorite_count' => $favoriteCount,
        'message' => $message
    ]);
} catch (PDOException $e) {
    send_json(500, [
        'success' => false,
        'message' => 'Database error. Please try again later.'
    ]);
}
