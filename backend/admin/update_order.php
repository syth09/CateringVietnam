<?php
session_start();

// Include các file cần thiết
require_once __DIR__ . '/../includes/db_connection.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: /project-buffet/backend/login.php");
    exit();
}

// Lấy order_id từ URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin đơn hàng từ CSDL
$order_query = "SELECT * FROM orders WHERE id = :id";
$order_stmt = $conn->prepare($order_query);
$order_stmt->execute(['id' => $order_id]);
$order = $order_stmt->fetch(PDO::FETCH_ASSOC);

// Xử lý cập nhật trạng thái đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];

    // Cập nhật trạng thái đơn hàng
    $update_query = "UPDATE orders SET status = :status WHERE id = :id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->execute(['status' => $new_status, 'id' => $order_id]);

    // Chuyển hướng về trang orders.php sau khi cập nhật
    header('orders.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../frontend/css/admin_dashboard.css" />
    <link href="https://fonts.cdnfonts.com/css/nunito" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Update Order - CateringVN</title>
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
        <h1>Update Order Status</h1>

        <!-- Form cập nhật trạng thái đơn hàng -->
        <section class="update-order">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="status">Order Status</label>
                    <select name="status" id="status" required>
                        <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Processing" <?php echo $order['status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                        <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn">Update Status</button>
            </form>
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