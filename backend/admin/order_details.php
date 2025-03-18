<?php
session_start();

// Include các file cần thiết
require_once __DIR__ . '/../includes/db_connection.php';

// Kiểm tra đăng nhập và role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


// Lấy order_id từ URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin đơn hàng từ CSDL
$order_query = "
    SELECT orders.*, users.name AS customer_name, users.email AS customer_email 
    FROM orders 
    JOIN users ON orders.user_id = users.id 
    WHERE orders.id = :id
";
$order_stmt = $conn->prepare($order_query);
$order_stmt->execute(['id' => $order_id]);
$order = $order_stmt->fetch(PDO::FETCH_ASSOC);

// Lấy chi tiết các món ăn trong đơn hàng
$order_items_query = "
    SELECT order_items.*, menu.dish_name 
    FROM order_items 
    JOIN menu ON order_items.menu_id = menu.id 
    WHERE order_items.order_id = :order_id
";
$order_items_stmt = $conn->prepare($order_items_query);
$order_items_stmt->execute(['order_id' => $order_id]);
$order_items = $order_items_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../frontend/css/admin_dashboard.css" />
    <link href="https://fonts.cdnfonts.com/css/nunito" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Order Details - CateringVN</title>
</head>

<body class="bgc">
    <!-- Header -->
    <header>
        <div class="flex items-center">
            <a class="mr-auto logo" href="../../frontend/index.html">CateringVietnam<span>.</span></a>
            <ul class="flex nav">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <h1>Order Details</h1>

        <!-- Thông tin đơn hàng -->
        <section class="order-details">
            <div class="order-info">
                <h2>Order ID: <?php echo htmlspecialchars($order['id']); ?></h2>
                <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>Customer Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            </div>

            <!-- Danh sách món ăn trong đơn hàng -->
            <h2>Order Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Dish Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($order_items)): ?>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['dish_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No items found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>© 2025 Catering Vietnam. All rights reserved.</p>
        </div>
    </footer>

    <script src="../../frontend/js/sticky-header.js"></script>
</body>

</html>