<?php
session_start();
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $item_id = $data['item_id'];
    $user_id = $_SESSION['user_id'];

    // Kiểm tra xem món ăn đã có trong giỏ hàng chưa
    $sql = "SELECT * FROM cart WHERE user_id = :user_id AND item_id = :item_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id, 'item_id' => $item_id]);
    $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        // Nếu đã có, tăng số lượng lên 1
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $existing_item['id']]);
    } else {
        // Nếu chưa có, thêm mới vào giỏ hàng
        $sql = "INSERT INTO cart (user_id, item_id, quantity) VALUES (:user_id, :item_id, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'item_id' => $item_id]);
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
