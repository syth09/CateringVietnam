<?php
session_start();

// Include các file cần thiết
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db_connection.php';

// Kiểm tra đăng nhập và role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


// Lấy order_id từ URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Xóa đơn hàng khỏi CSDL
$delete_query = "DELETE FROM orders WHERE id = :id";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->execute(['id' => $order_id]);

// Chuyển hướng về trang orders.php sau khi xóa
header('orders.php');
