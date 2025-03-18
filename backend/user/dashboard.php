<?php
session_start();
require_once '../includes/db_connection.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: /project-buffet/backend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Lấy thông tin user
$sql = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../../frontend/css/user_dashboard.css">
</head>

<body>
    <header>
        <div class="flex items-center">
            <a class="mr-auto logo" href="../../frontend/index.html">CateringVietnam<span>.</span></a>
            <ul class="flex nav">
                <li><a class="mr-60px" href="../../frontend/index.html">Home</a></li>
                <li><a class="mr-60px" href="../../frontend/about.html">About</a></li>
                <li><a class="mr-60px" href="../../frontend/contact.html">Contact</a></li>
            </ul>
            <a href="#" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">3</span>
            </a>
        </div>
    </header>

    <main>
        <div class="user-dashboard">
            <div class="user-sidebar">
                <h2>Dashboard</h2>
                <ul>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="payment.php">Payments</a></li>
                    <li><a href="#">Settings</a></li>
                </ul>
            </div>
            <div class="user-content">
                <h1>Welcome, User!</h1>
                <div class="user-stats">
                    <div class="user-stat-card">
                        <h2>Total Orders</h2>
                        <p>15</p>
                    </div>
                    <div class="user-stat-card">
                        <h2>Total Spent</h2>
                        <p>$450.00</p>
                    </div>
                    <div class="user-stat-card">
                        <h2>Pending Orders</h2>
                        <p>2</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>© 2025 Catering Vietnam. All rights reserved.</p>
    </footer>

    <!-- sticky header scrolling js code -->
    <script src="js/sticky-header.js"></script>
</body>

</html>