<?php
session_start();
require_once '../includes/db_connection.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Lấy user_id từ session
$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của người dùng
$order_query = "
    SELECT * 
    FROM orders 
    WHERE user_id = :user_id 
    ORDER BY order_date DESC
";
$order_stmt = $pdo->prepare($order_query);
$order_stmt->execute(['user_id' => $user_id]);
$orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Catering Vietnam</title>
    <link rel="stylesheet" href="../../frontend/css/user_dashboard.css">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="flex items-center">
            <a class="mr-auto logo" href="/frontend/index.html">CateringVietnam<span>.</span></a>
            <ul class="flex nav">
                <li><a class="mr-60px" href="../frontend/index.html">Home</a></li>
                <li><a class="mr-60px" href="../frontend/about.html">About</a></li>
                <li><a class="mr-60px" href="../frontend/contact.html">Contact</a></li>
            </ul>
            <a href="cart.php" class="cart-icon">
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
                    <li><a href="buffet_packages.php">Buffet Packages</a></li>
                </ul>
            </div>
            <div class="user-content">
                <h1>My Orders</h1>
                <div class="orders">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                        <td><?php echo number_format($order['total_amount'], 2); ?>đ</td>
                                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                                        <td>
                                            <a href="order_details.php?id=<?php echo $order['id']; ?>">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">No orders found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2025 Catering Vietnam. All rights reserved.</p>
    </footer>
</body>

</html>