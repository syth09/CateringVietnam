<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT o.id, o.total_amount, o.order_date, o.status 
        FROM orders o
        WHERE o.user_id = :user_id
        ORDER BY o.order_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Catering Vietnam</title>
    <link rel="stylesheet" href="../../frontend/css/user_dashboard.css"> <!-- Sử dụng CSS của user dashboard -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> <!-- Font Awesome -->
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
                <h1>Your Orders</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($orders): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                    <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</td>
                                    <td>
                                        <span class="status <?php echo strtolower($order['status']); ?>">
                                            <?php echo htmlspecialchars($order['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2025 Catering Vietnam. All rights reserved.</p>
    </footer>
</body>

</html>